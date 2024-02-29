<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_chat_filter.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nId		= filter_input_int('nId', INPUT_GET,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_chat_filter_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sName0' => '',
	);
	$aValue = array(
		'a'		=> ($nId == 0)?'INS':'UPT'.$nId,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nErr = 0;
	$sErrMsg = '';
	$nCount = 0;
	$aOnline = aONLINE;
	#宣告結束

	#程式邏輯區

	$sSQL = '	SELECT	nId,
					sName0,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_CHAT_FILTER .'
			WHERE		nId = :nId
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData = $aRows;
		$nCount ++;
	}

	if($nCount == 0 && $nId != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if($nId != 0)
	{
		$aOnline[$aData['nOnline']]['sSelect'] = 'selected';
	}

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