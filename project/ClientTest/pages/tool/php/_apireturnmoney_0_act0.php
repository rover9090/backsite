<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/oCoreOpenssl.php');

	// $sAccount	= filter_input_str('sAccount',	INPUT_POST, '', 16);
	$sCypher 		= filter_input_str('cypher',			INPUT_REQUEST);
	$sJData =  oCoreOpenssl::AESdecrypt(SYS['KEY'],$sCypher);
	
	$aReturnData = array();
	$aUrl = array(
		'sApiLogin1'		=> sys_web_encode($aMenuToNo['pages/tool/php/_apilogin_0_act1.php']),
	);
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'aUser'		=> $aUser,
	);
	$aData = json_decode($sJData,true);

	$sAccount		= '';
	$nMoney		= '';
	$sSite		= '';

	#登入
	if($sJData === false)
	{
		$aReturn['nError'] = 100;
		echo json_encode($aReturn);#加密錯誤
		exit;
	}
	
	if($aData === false || !isset($aData['sAccount']) || !isset($aData['sSite']))
	{
		$aReturn['nError'] = 101;
		echo json_encode($aReturn);#json陣列錯誤
		exit;
	}

	$sAccount		= $aData['sSite'].'-'.trim(strtolower($aData['sAccount']));
	$sSite		= $aData['sSite'];
	$nReturnMoney 	= 0;
	$nDelta = 0;


	$sSQL = '	SELECT 	nId
			FROM 		'.CLIENT_USER_DATA.'
			WHERE 	sAccount = :sAccount
			AND		sSiteId = :sSiteId
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
	$Result->bindValue(':sSiteId', $sSite, PDO::PARAM_STR);
	sql_query($Result);
	$aUser = $Result->fetch(PDO::FETCH_ASSOC);

	if($aUser === false)
	{
		$aReturn['nError'] = 401;
		$aReturn['aData']['nMoney'] = 0;
		echo json_encode($aReturn);
		exit;
	}

	$oPdo->beginTransaction();

	$sSQL = '	SELECT 	nMoney
			FROM 		'.CLIENT_USER_MONEY.'
			WHERE 	nUid = :nUid
			LIMIT 1
			FOR UPDATE';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
	sql_query($Result);
	$aMoney = $Result->fetch(PDO::FETCH_ASSOC);

	if($aMoney === false)
	{
		$aReturn['nError'] = 401;
		$aReturn['aData']['nMoney'] = 0;
		echo json_encode($aReturn);
		exit;
	}
	
	if(!isset($aData['nMoney']))
	{
		$aNewMoney = array(
			'Money' => 0
		);
		$nDelta = $aMoney['nMoney'];
	}
	else
	{
		$aNewMoney = array(
			'Money' => (float) $aMoney['nMoney'] -  (float)$aData['nMoney']
		);		
		$nDelta = $aData['nMoney'];
	}
	
	$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney);

	$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
			WHERE	nUid = :nUid LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
	sql_build_value($Result, $aSQL_Array);
	sql_query($Result);

	$sSQL = '	DELETE
			FROM 		'.CLIENT_USER_COOKIE.'
			WHERE 	nUid = :nUid ';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nId'],PDO::PARAM_INT);
	sql_query($Result);

	$aSQL_Array = array(
		'nUid'		=> (int)	$aUser['nId'],
		'nKid'		=> (int)	$aUser['nId'],
		'nType0'		=> (int)	2,
		'nType1'		=> (int)	212,
		'nType2'		=> (int)	0,
		'nBefore'		=> (float)	$aMoney['nMoney'],
		'nDelta'		=> (float)	$nDelta,
		'nAfter'		=> (float)	$aMoney['nMoney'] - $nDelta,
		'sParams'		=> (string)	'',
		'nCreateTime'	=> (int)	NOWTIME,
		'sCreateTime'	=> (string)	NOWDATE,
		'nCreateDay'	=> (int)	strtotime('today'),
		'sSiteId'		=> (string)	$sSite,
	);
	DoLogAcc($aSQL_Array);

	$oPdo->commit();

	if(!isset($aData['nMoney']))
	{
		$aReturn['aData']['nMoney'] = $aMoney['nMoney'];
	}
	echo json_encode($aReturn);
	exit;

?>