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
	
	# 不確定sSite要自訂還是直接抓$_SERVER['SERVER_NAME']
	if($aData === false || !isset($aData['sAccount']) || !isset($aData['nMoney']) || !isset($aData['sSite']))
	{
		$aReturn['nError'] = 101;
		echo json_encode($aReturn);#json陣列錯誤
		exit;
	}

	$sAccount		= $aData['sAccount'];
	$nMoney		= $aData['nMoney'];
	$sSite		= $aData['sSite'];
	$sBackurl		= $aData['sBackurl'];

	$sAccount = $sSite.'-'.trim(strtolower($sAccount));


	$aLoginData = array(
		'sBackurl' 	=> $sBackurl,
		'sSite' 	=> $sSite,
		'sAccount' 	=> $sAccount,
		'sPassword' => oCypher::ReHash(md5(''),NULL,1),
		'nRemember' => 0,
	);
	
	$nStatus = $oUser->login($aLoginData);
	if($nStatus === 1)
	{
		$aValue = array(
			't'		=> NOWTIME,
			'sPerLink' 	=> empty($aData['sPerLink'])?'':$aData['sPerLink'],
			'sValueLink'=> empty($aData['sValueLink'])?'':$aData['sValueLink'],
			'sLinkList' => empty($aData['sLinkList'])?'':$aData['sLinkList'],
			'sBackurl'	=> empty($aData['sBackurl'])?'':$aData['sBackurl'],
		);
		$sCookieJWT = sys_jwt_encode($aValue);
		setcookie('sUserData', $sCookieJWT, COOKIE['REMEMBER']);
		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nId
				FROM 		'.CLIENT_USER_DATA.'
				WHERE 	sAccount = :sAccount
				AND		nOnline != 99
				AND		sSiteId = :sSiteId';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
		$Result->bindValue(':sSiteId', $sSite, PDO::PARAM_STR);
		sql_query($Result);
		$aUser = $Result->fetch(PDO::FETCH_ASSOC);

		if($aUser == false)
		{
			$aReturn['nError'] = 103;
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

		if($aMoney == false)
		{
			$aReturn['nError'] = 103;
			echo json_encode($aReturn);
			exit;
		}

		$aUser['nMoney'] = $aMoney['nMoney'];

		$aNewMoney = array(
			'Money' => (float) $aUser['nMoney'] + $nMoney
		);
		$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney);

		$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nUid = :nUid LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$aSQL_Array = array(
			'nUid'		=> (int)	$aUser['nId'],
			'nKid'		=> (int)	$aUser['nId'],
			'nType0'		=> (int)	2,
			'nType1'		=> (int)	211,
			'nType2'		=> (int)	0,
			'nBefore'		=> (float)	$aUser['nMoney'],
			'nDelta'		=> (float)	$nMoney,
			'nAfter'		=> (float)	$aUser['nMoney'] + $nMoney,
			'sParams'		=> (string)	'',
			'nCreateTime'	=> (int)	NOWTIME,
			'sCreateTime'	=> (string)	NOWDATE,
			'nCreateDay'	=> (int)	strtotime('today'),
			'sSiteId'		=> (string)	$sSite,
		);
		DoLogAcc($aSQL_Array);

		$oPdo->commit();

		$aReturnData['sAccount'] = $sAccount;
		$aReturnData['sSid'] = $_COOKIE['sSid'];
		$aReturnData['sSiteId'] = $sSite;
		$aReturnData['sPerLink'] = empty($aData['sPerLink'])?'':$aData['sPerLink'];
		$aReturnData['sValueLink'] = empty($aData['sValueLink'])?'':$aData['sValueLink'];
		$aReturnData['sLinkList'] = empty($aData['sLinkList'])?'':$aData['sLinkList'];
		$aReturnData['sBackurl'] = empty($aData['sBackurl'])?'':$aData['sBackurl'];

		$sCypher =  oCoreOpenssl::AESencrypt(SYS['KEY'],json_encode($aReturnData));

		$sUrl = substr($aUrl['sApiLogin1'],3);

		$aReturn['aData']['sUrl'] = $sUrl.'&cypher='.$sCypher;
		echo json_encode($aReturn);
		exit;
	}
	else
	{
		$aReturn['nError'] = 102;
		$aReturn['nStatus'] = $nStatus;
		echo json_encode($aReturn);
		exit;
	}



?>