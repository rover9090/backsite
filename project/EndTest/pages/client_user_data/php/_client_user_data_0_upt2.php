<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nId		= filter_input_int('nId', INPUT_REQUEST,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_act2.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt2.php']),
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_data_0_upt2.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBank = array();

	$nErr = 0;
	$sErrMsg = '';
	// $aType1 = aMANUAL['TYPE1'];
	// $aType3 = aMANUAL['TYPE3'];
	#宣告結束

	#程式邏輯區

	// unset($aType1['sTitle']);
	// unset($aType3['sTitle']);

	$sSQL = '	SELECT	1
			FROM		'. CLIENT_USER_LINK .'
			WHERE		nUid = :nId
			AND		nPa = :nPa
			AND		nEndTime = 0
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	$Result->bindValue(':nPa', $aAdm['nUid'], PDO::PARAM_INT);
	sql_query($Result);
	$aLinkBool = $Result->fetch(PDO::FETCH_ASSOC);
	if($aLinkBool === false)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	$sSQL = '	SELECT	User_.nId,
					User_.sName0,
					Money_.nMoney
			FROM		'. CLIENT_USER_DATA .' User_,
					'. CLIENT_USER_MONEY .' Money_
			WHERE		User_.nOnline != 99
			AND		User_.nId = :nId
			AND		User_.nId = Money_.nUid
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);

	if($aData === false && $nId != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	$sSQL = '	SELECT	User_.nId,
					User_.sName0,
					Money_.nMoney
			FROM		'. CLIENT_USER_DATA .' User_,
					'. CLIENT_USER_MONEY .' Money_
			WHERE		User_.nOnline != 99
			AND		User_.nId = :nId
			AND		User_.nId = Money_.nUid
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $aAdm['nUid'], PDO::PARAM_INT);
	sql_query($Result);
	$aAdmData = $Result->fetch(PDO::FETCH_ASSOC);

	$aValue = array(
		'a'		=> 'INS',
		'nUid'	=> $aData['nId'],
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if ($nErr == 1)
	{
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['sClicktoClose'] = 1;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>