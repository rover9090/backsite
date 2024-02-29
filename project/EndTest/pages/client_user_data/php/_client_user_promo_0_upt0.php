<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_promo.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 	'plugins/js/client_user_data/client_user_promo.js',
	);
	#js結束

	#參數接收區
	$nId		= filter_input_int('nId',	INPUT_GET, 0);
	#參數結束


	$aUrl   = array(
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_promo_0_upt0.php',
	);

	$aData = array(
		'nId' 		=>  $nId,
		'nOnline' 		=> 0,
		'sPromoCode'	=> '',
		'aFsData'		=> array(
			'sWater' => '',
			'sPercent' => '',
		),
		'aSelfFs'		=> array(
			'sWater' => '',
			'sPercent' => '',
		),
	);
	$aOnline = aONLINE;
	$aOnline[0]['sText'] = aPROMO['TURNOFF'];
	$aOnline[1]['sText'] = aPROMO['TURNON'];
	$nErr = 0;
	$sErrMsg = '';
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	nId,
					sPromoCode,
					sCreateTime,
					nOnline
			FROM 		'. CLIENT_USER_PROMO .'
			WHERE 	nOnline != 99
			AND 		nUid = :nUid
			AND 		nId = :nId';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aRows = $Result->fetch(PDO::FETCH_ASSOC);
	if ($aRows !== false)
	{
		$aData = $aRows;
	}

	# 自己的水
	$sSQL = '	SELECT 	nUid,
					sWater,
					sPercent
			FROM  	'. CLIENT_USER_FS .'
			WHERE 	nUid = :nUid
			AND 		nEndTime = 0';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid',$aAdm['nUid'], PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['aSelfFs'] = $aRows;
	}

	# 設定的水
	$sSQL = '	SELECT 	nId,
					sWater,
					sPercent
			FROM  	'.CLIENT_USER_PROMO_FS.'
			WHERE nPid = :nPid';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nPid',$aData['nId'], PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['aFsData'] = $aRows;
	}

	$aOnline[$aData['nOnline']]['sSelect'] = 'selected';

	$aValue = array(
		'a'		=> ($nId == 0)?'INS':'UPT'.$aData['nId'],
		't'		=> NOWTIME,
		'nId'		=> $aData['nId'],
	);
	$sJWTAct = sys_jwt_encode($aValue);
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
		$aJumpMsg['0']['nClicktoClose'] = 1;
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