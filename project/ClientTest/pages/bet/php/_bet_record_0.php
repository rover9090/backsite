<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/bet.php');

	$aCss = array();	
	$aJs = array(
		// 'plugins/js/index/lobby.js',
		// 'plugins/js/tool/roadClass.js',
		// 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/game.js',
		// 'plugins/js/tool/cell.js',
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/record.js',
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet.js',
		// 'plugins/js/center/center.js',
		// 'plugins/js/tool/timeCreate.js',
		// 'plugins/js/tool/chat.js',		
		// 'plugins/js/tool/Socket.js',		
		// 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/sticker.js',		
		// 'plugins/js/tool/TcPlayer-2.4.1.js',
		// 'plugins/js/tool/stream.js',
	);

	$aUrl = array(
		// 'sLobbyAjax'	=> '_ajax.lobby_0',
		// 'sChatAjax'		=> '_ajax.chat_0.php',
	);
	$aUrl = array(
		// 'sChatAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.chat_0.php']),
		// 'sRankAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.rank_0.php']),
		// 'sAttentionAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.attention_0.php']),
		// 'sRecordAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.record_0.php']),
		// 'sRoadAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.road_0.php']),
		// 'sLobbyAjax'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_0.php']),
		// 'sDonateAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.donate_0.php']),
		// 'sBetAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_0.php']),
		// 'sBankerAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.banker_0.php']),
		// 'sRateAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.winrate_0.php']),
		'sBetHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_history_0.php']),
		'sBankerHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.banker_history_0.php']),
		// 'sBetDetailAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.betdetail_0.php']),
		// 'sDonateHistoryAjax'=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.donate_history_0.php']),
		// 'sBetRankingAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_ranking_0.php']),
		// 'sWatchDealerAjax'=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.watch_dealer_0.php']),
		// 'sStickerAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.sticker_0.php']),
		// 'sTotalBetAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.totalbet_0.php']),
		'sPage'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_record_0.php']),
		// 'sIndex'		=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
		'sHtml'	=> 'pages/bet/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet_record_0.php',
	);
	#url結束

	#參數宣告區
	$nGame = filter_input_int('nGame', INPUT_REQUEST, 1001);
	$nDay = filter_input_int('day', INPUT_REQUEST, 1);
	$sType = filter_input_str('sType', INPUT_REQUEST, 'bethistory');
	$sNowLiff = '1656844931-nqJqqy8g';
	$aData = array();
	$nUid = 0;
	$aRecordData = array('aBetData'=>array(),'aBetList'=>array());
	$aBankerRecordData = array('aBetData'=>array(),'aBetList'=>array());
	$sUid = '0';
	$aMemberName = array();
	#宣告結束

	#程式邏輯區	
	$aHistoryJWT = array(
		'a'		=> 'HISTORY',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sHistoryJWT = sys_jwt_encode($aHistoryJWT);
	$aTotalBetJWT = array(
		'a'		=> 'TOTALBET',
		't'		=> NOWTIME,
	);
	$sTotalBetJWT = sys_jwt_encode($aTotalBetJWT);
	
	if($nDay == 1)
	{
		$nQueryCreateTime = strtotime('today');
	}
	else if($nDay == 2)
	{
		$nQueryCreateTime = strtotime('yesterday');
	}

	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aPage['nDataAmount'] = 0;

	$aRecordData['nBetTotal'] = 0;
	$aRecordData['nWinTotal'] = 0;
	$aRecordData['nBetSubTotal'] = 0;
	$aRecordData['nWinSubTotal'] = 0;
	$sSQL = '	SELECT 	nMoney0,nMoney1
			FROM  	'.CLIENT_GAMES_DATA.'
			WHERE 	nUid = :nUid
			AND		nCreateTime > :nCreateTime';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', strtotime(aDAY['LASTWEEK']['sStartDay']), PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aPage['nDataAmount']++;
		$aRecordData['nBetTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}
	$aPage['nDataAmount'] = count($Result->fetchAll());

	$sSQL = '	SELECT 	nId,
					nGame,
					sNo,
					nMoney0,
					nMoney1,
					sContent0,
					nStatus,
					nDone,
					sUpdateTime,
					sCreateTime
			FROM  	'.CLIENT_GAMES_DATA.'
			WHERE 	nUid = :nUid
			AND		nCreateTime > :nCreateTime
			AND		nCreateTime < :nCreateTime1
			ORDER BY nCreateTime DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nMoney0'] = (float)$aRows['nMoney0'];
		$aRows['nMoney1'] = (float)$aRows['nMoney1'];
		$aRecordData['aBetData'][$aRows['nId']] = $aRows;
		$aRecordData['nBetSubTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinSubTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}

	$aRecordPageList = pageSet($aPage, 'javascript:void(0);');

	// 當莊紀錄
	$aBankerRecordData['nBetTotal'] = 0;
	$aBankerRecordData['nWinTotal'] = 0;
	$aBankerRecordData['nBetSubTotal'] = 0;
	$aBankerRecordData['nWinSubTotal'] = 0;
	$sSQL = '	SELECT 	nMoney0,nMoney1
			FROM  	'.CLIENT_GAMES_BANKER.'
			WHERE 	nUid = :nUid
			AND		nCreateTime > :nCreateTime';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', strtotime(aDAY['LASTWEEK']['sStartDay']), PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBankerRecordData['nBetTotal'] += $aRows['nMoney0'];
		$aBankerRecordData['nWinTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}

	$sSQL = '	SELECT 	nId,
					nGame,
					sNo,
					nMoney0,
					nMoney1,
					nStatus,
					nDone,
					sCreateTime
			FROM  	'.CLIENT_GAMES_BANKER.'
			WHERE 	nUid = :nUid
			AND		nStatus != 99
			AND		nCreateTime > :nCreateTime
			AND		nCreateTime < :nCreateTime1
			ORDER BY nCreateTime DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nMoney0'] = (float)$aRows['nMoney0'];
		$aRows['nMoney1'] = (float)$aRows['nMoney1'];
		$aBankerRecordData['aBetData'][$aRows['nId']] = $aRows;
		$aBankerRecordData['nBetSubTotal'] += $aRows['nMoney0'];
		$aBankerRecordData['nWinSubTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}

	$sSQL = '	SELECT 	nId,
					sNo,
					nUid,
					nBefore,
					nDelta,
					nAfter,
					sCreateTime
			FROM  	'.CLIENT_JACKPOT_LOG.'
			WHERE 	nType0 = 1
			ORDER BY nCreateTime DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aJackPotRecordData[$aRows['nId']] = $aRows;
		$sUid .= ','.$aRows['nUid'];
	}

	$sSQL = '	SELECT 	nId,
					sName0
			FROM  	'.CLIENT_USER_DATA.'
			WHERE 	nId IN ('. $sUid .')';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMemberName[$aRows['nId']] = $aRows;
	}

	$aJumpMsg['buysticker'] = $aJumpMsg['0'];
	$aJumpMsg['buysticker']['sClicktoClose'] = 1;
	$aJumpMsg['buysticker']['sShow'] = 0;
	$aJumpMsg['buysticker']['sTitle'] = RIGHTMSG;
	$aJumpMsg['buysticker']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['buysticker']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['buysticker']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['buysticker']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['buysticker']['aButton']['1']['sText'] = CANCEL;

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>