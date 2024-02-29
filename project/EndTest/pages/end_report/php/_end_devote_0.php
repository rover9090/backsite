<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_devote.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_report/end_devote.js',
	);
	#js結束

	#參數接收區
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, aDAY['TODAY']['sStartDay']);
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, aDAY['TODAY']['sEndDay']);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_REQUEST, 'TODAY');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sDetail'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_devote_0_upt0.php']),
		'sDo'		=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_devote_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_devote_0.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_devote_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$aBindArray = array();
	$sCondition .= ' AND nCreateTime > :nStartTime ';
	$aBindArray['nStartTime']	= $nStartTime;
	$sCondition .= ' AND nCreateTime < :nEndTime ';
	$aBindArray['nEndTime']		= $nEndTime;
	$aPage['aVar'] = array(
		'sSelDay'		=> $sSelDay,
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
	);

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;

	$aValue = array(
		'a'		=> 'INS',
		't'		=> NOWTIME,
	);
	$sDo_Jwt = sys_jwt_encode($aValue);

	$aDay = aDAY;
	#宣告結束

	#程式邏輯區
	foreach ($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if ($sSelDay == $LPsText)
		{
			$aDay[$LPsText]['sSelect'] = 'active';
		}
	}

	$sSQL = '	SELECT	nId
			FROM		'. END_DEVOTE .'
			WHERE		1
					' . $sCondition . '
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBindArray);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}
	$aPage['nDataAmount'] = $nCount;

	$sSQL = '	SELECT	nId,
					nBetMoney,
					nMoney,
					nDevote,
					sStartTime,
					sEndTime
			FROM		'. END_DEVOTE .'
			WHERE		1
					'. $sCondition .'
			ORDER	BY	nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBindArray);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sDetail'] = $aUrl['sDetail'].'&nId='.$aRows['nId'];
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>