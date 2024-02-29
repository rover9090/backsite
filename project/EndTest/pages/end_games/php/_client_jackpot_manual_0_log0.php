<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_jackpot_manual.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
		1	=> 'plugins/js/end_games/client_jackpot_manual.js'
	);
	#js結束

	#參數接收區
	$sStartTime		= filter_input_str('sStartTime', 		INPUT_REQUEST,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 		INPUT_REQUEST,date('Y-m-d 23:59:59'));
	$nType0		= filter_input_int('nType0', 			INPUT_REQUEST,-1);
	$sNo		= filter_input_str('sNo', 			INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0_log0.php']),
		'sManual'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_jackpot_manual_0_log0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aCompany = array();
	$aMemberData = array();
	$aAllAdminData = array(
		'-1' => array(
			'sAccount' => '',
		),
	);
	$sCondition = '';
	$sAdmCondition = '';
	$aAdmBind = array();
	$aBind = array();
	$sAdmIds = '0';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aStatus = aMANUAL['STATUS'];
	$aType0 = aLOG['TYPE0'];
	$nTotalCount = 0;
	$nTotalBefore = 0;
	$nTotalAfter = 0;
	$nTotalDelta = 0;
	$nPageTotalCount = 0;
	$nPageTotalBefore = 0;
	$nPageTotalAfter = 0;
	$nPageTotalDelta = 0;
	#宣告結束

	#程式邏輯區

	$sCondition .= ' AND nCreateTime >= :nStartTime AND nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$aBind['nEndTime'] = $nEndTime;

	if($nType0 > -1)
	{
		$sCondition .= ' AND nType0 = :nType0 ';
		$aPage['aVar']['nType0'] = $nType0;
		$aBind['nType0'] = $nType0;
	}

	if($sNo != '')
	{
		$aPage['aVar']['sNo'] = $sNo;
		$sCondition .= '	AND sNo = :sNo ';
		$aBind['sNo'] = $sNo;
	}

	if($nType0 >= 0)
	{
		$aType0[$nType0]['sSelect'] = 'selected';
	}

	$sSQL = '	SELECT	Pot_.nId,
					Pot_.nMoney0 as nMoney
			FROM		'. CLIENT_JACKPOT_SET .' Pot_
			WHERE		Pot_.nOnline != 99
			AND		Pot_.nId = 1
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	$aJackpot = $Result->fetch(PDO::FETCH_ASSOC);

	$sSQL = '	SELECT	nId,
					nBefore,
					nDelta,
					nAfter
			FROM		'. CLIENT_JACKPOT_LOG .'
			WHERE		1 '. $sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nTotalCount++;
		$nTotalBefore += $aRows['nBefore'];
		$nTotalAfter += $aRows['nAfter'];
		$nTotalDelta += $aRows['nDelta'];
	}

	$aPage['nDataAmount'] = $nTotalCount;

	$sSQL = '	SELECT	nId,
					nGame,
					sNo,
					nLottryId,
					nUid,
					nBefore,
					nDelta,
					nAfter,
					sCreateTime,
					nType0,
					(SELECT sAccount FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_JACKPOT_LOG.'.nUid LIMIT 1) as sAccount
			FROM		'. CLIENT_JACKPOT_LOG .'
			WHERE		1 '. $sCondition .'
			ORDER	BY	nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$nPageTotalCount++;
		$nPageTotalBefore += $aRows['nBefore'];
		$nPageTotalAfter += $aRows['nAfter'];
		$nPageTotalDelta += $aRows['nDelta'];
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>