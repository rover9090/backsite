<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_devote.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nKid		= filter_input_int('nId', 	INPUT_GET,	0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sBack'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_devote_0.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_devote_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$nErr = 0;
	$sErrMsg = '';
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	EDD_.nId,
					EDD_.nKid,
					EDD_.nUid,
					EDD_.nBetMoney,
					EDD_.nMoney,
					EDD_.nPercent,
					EDD_.sCreateTime,
					CUD_.sName0,
					CUD_.sAccount
			FROM		'. END_DEVOTE_DETAIL .'	EDD_,
					'. CLIENT_USER_DATA.'	CUD_
			WHERE		EDD_.nUid = CUD_.nId
			AND		EDD_.nKid = :nKid';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nKid', $nKid, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
	}

	if(empty($aData))
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if($nErr == 1)
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