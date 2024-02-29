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

	$aReturn['aData']['nMoney'] = $aMoney['nMoney'];
	echo json_encode($aReturn);
	exit;

?>