<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/oCoreOpenssl.php');

	$sCypher 		= filter_input_str('cypher',			INPUT_REQUEST);
	$sJData =  oCoreOpenssl::AESdecrypt(SYS['KEY'],$sCypher);
	
	$aReturnData = array();
	$aUrl = array(
		'sBack'		=> sys_web_encode($aMenuToNo['pages/login/php/_login_0.php']),
		'sIndex'		=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
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

	
	if($aData === false || !isset($aData['sAccount']) || !isset($aData['sSid']))
	{
		$aReturn['nError'] = 101;
		echo json_encode($aReturn);#json陣列錯誤
		exit;
	}

	$aValue = array(
		't'		=> NOWTIME,
		'sPerLink' 	=> empty($aData['sPerLink'])?'':$aData['sPerLink'],
		'sValueLink'=> empty($aData['sValueLink'])?'':$aData['sValueLink'],
		'sLinkList' => empty($aData['sLinkList'])?'':$aData['sLinkList'],
		'sBackurl'	=> empty($aData['sBackurl'])?'':$aData['sBackurl'],
	);
	$sCookieJWT = sys_jwt_encode($aValue);
	setcookie('sUserData', $sCookieJWT, COOKIE['REMEMBER']);
	// trigger_error(print_r($aValue,true));

	$sAccount = $sSite.'-'.trim(strtolower($sAccount));
	$sSQL = '	SELECT 	nCreateTime
			FROM 		'.CLIENT_USER_COOKIE.'
			WHERE 	sAccount = :sAccount 
			AND		sSid = :sSid';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sAccount', $aData['sAccount'],PDO::PARAM_STR);
	$Result->bindValue(':sSid', $aData['sSid'],PDO::PARAM_STR);
	sql_query($Result);
	$aCookie = $Result->fetch(PDO::FETCH_ASSOC);

	if($aCookie === false || $aCookie['nCreateTime'] < COOKIE['CLOSE'])
	{
		if($aCookie === false)
		{
			$aJumpMsg['0']['sMsg'] = aERROR[1];
		}
		else if($aCookie['nCreateTime'] < COOKIE['CLOSE'])
		{
			$aJumpMsg['0']['sMsg'] = aERROR[2];
		}
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aValue['sBackurl'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;		
	}
	else
	{
		setcookie('sSid', $aData['sSid'], COOKIE['REMEMBER'], '/');
		
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = aLOGIN['LOGINV'];
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sIndex'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
?>