<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/bet.php');

	$aCss = array();	
	$aJs = array(
		'plugins/js/index/lobby.js',
		// 'plugins/js/tool/roadClass.js',
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/game.js',
		// 'plugins/js/tool/cell.js',
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/record.js',
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet.js',
		// 'plugins/js/center/center.js',
		'plugins/js/tool/timeCreate.js',
		'plugins/js/tool/chat.js',		
		'plugins/js/tool/Socket.js',		
		'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/sticker.js',		
		'plugins/js/tool/TcPlayer-2.4.1.js',
		'plugins/js/tool/stream.js',
	);

	$aUrl = array(
		// 'sLobbyAjax'	=> '_ajax.lobby_0',
		'sChatAjax'		=> '_ajax.chat_0.php',
	);
	$aUrl = array(
		'sChatAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.chat_0.php']),
		// 'sRankAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.rank_0.php']),
		// 'sAttentionAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.attention_0.php']),
		// 'sRecordAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.record_0.php']),
		'sRoadAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.road_0.php']),
		'sLobbyAjax'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_0.php']),
		'sDonateAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.donate_0.php']),
		'sBetAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_0.php']),
		'sBankerAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.banker_0.php']),
		// 'sRateAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.winrate_0.php']),
		'sBetHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_history_0.php']),
		'sBankerHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.banker_history_0.php']),
		// 'sBetDetailAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.betdetail_0.php']),
		// 'sDonateHistoryAjax'=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.donate_history_0.php']),
		'sBetRankingAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_ranking_0.php']),
		// 'sWatchDealerAjax'=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.watch_dealer_0.php']),
		'sStickerAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.sticker_0.php']),
		'sTotalBetAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.totalbet_0.php']),
		'sCancelAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.cancel_0.php']),
		'sBet'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_0.php']),
		'sIndex'		=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
		'sHtml'	=> 'pages/bet/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet_0.php',
	);
	#url結束

	#參數宣告區
	// if(isset($_GET['demo']))
	// {
	// 	$aJs[] = 'plugins/js/tool/Socket.js';
	// }
	$nGame = filter_input_int('nGame', INPUT_REQUEST, 1001);
	$nDay = filter_input_int('day', INPUT_REQUEST, 1);
	$sNowLiff = LINELIFFBET;

	// if(isset($_COOKIE['gino']))
	// {
		// unset($aJs['8']);
		// $aJs['8'] = 'plugins/js/tool/photon/Photon-Javascript_SDK.js';
		// $aJs['13'] = 'plugins/js/tool/photon/Photon_Interface.js';
		// $aJs['14'] = 'plugins/js/tool/BaseCmdLogic.js';
		// $aJs['15'] = 'plugins/js/tool/SocketNew.js';
	// }
	$aData = array();
	$aMethod = array();
	$aKind = array();
	$nUid = 0;
	$aPicture = array();
	$aNumsArray = array();# 歷屆期數0~71共72期
	$aNumsCounting = array();
	$sDealerIdList = '0';
	$aRecordData = array('aBetData'=>array(),'aBetList'=>array());
	$aBankerRecordData = array('aBetData'=>array(),'aBetList'=>array());
	$aDonateData = array('aDonateData'=>array());
	$aBetGroup = array();
	$nGamesCount = 0;
	$sWatchClass = '';
	$aFilter = array();
	$aPlayerData = array();
	$aJsonMsg = array();
	$aJsonMsgChk = array();
	$sUid = '0';
	$aUidData = array();
	$aMebmer = array();
	$aDefaultGames = array($nGame=>0);
	$aPageGames = array();
	$aAnnounce = array();
	// $aCoinArrayPc = array(2,3,4,5,6);
	// $aCoinArrayMb = array(2,3,4,5,6,7);	
	$aClip = array(
		'1' => 1,
		'2' => 5,
		'3' => 10,
		'4' => 20,
		'5' => 50,
		'6' => 100,
		'7' => 500,
		'8' => 1000,
		'9' => 5000,
		'10' => 10000,
		'11' => 20000,
		'12' => 50000,
		'13' => 100000,
		'14' => 200000,
		'15' => 500000,
		'16' => 1000000,
	);
	$aBetLangs = array(
		'A' => aBET['A'],
		'B' => aBET['B'],
		'C' => aBET['C'],
	);
	$sStreamUrl = 'vt.abp77.com/live/tianjiu01';
	#宣告結束

	#程式邏輯區	
	$aLobbyPack = array(
		'a'		=> 'LOBBY',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sLobbyJWT = sys_jwt_encode($aLobbyPack);
	$aBetPack = array(
		'a'		=> 'BET',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBetJWT = sys_jwt_encode($aBetPack);
	$aDonatePack = array(
		'a'		=> 'DONATE',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sDonateJWT = sys_jwt_encode($aDonatePack);
	$aBetRankingPack = array(
		'a'		=> 'RANKING',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBetRankingJWT = sys_jwt_encode($aBetRankingPack);
	$aWatchDealerPack = array(
		'a'		=> 'WATCH',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sWatchDealerJWT = sys_jwt_encode($aWatchDealerPack);
	$aChatPack = array(
		'a'		=> 'CHAT',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sChatJWT = sys_jwt_encode($aChatPack);
	$aBanker0JWT = array(
		'a'		=> 'BANKER0',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBanker0JWT = sys_jwt_encode($aBanker0JWT);
	$aBanker1JWT = array(
		'a'		=> 'BANKER1',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBanker1JWT = sys_jwt_encode($aBanker1JWT);
	$aBankerSumJWT = array(
		'a'		=> 'BANKERSUM',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBankerSumJWT = sys_jwt_encode($aBankerSumJWT);
	$aBuyStickerJWT = array(
		'a'		=> 'BUYSTICKER',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBuyStickerJWT = sys_jwt_encode($aBuyStickerJWT);
	$aUseStickerJWT = array(
		'a'		=> 'USESTICKER',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sUseStickerJWT = sys_jwt_encode($aUseStickerJWT);
	$aRoadJWT = array(
		'a'		=> 'ROAD',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sRoadJWT = sys_jwt_encode($aRoadJWT);
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
	$aCancelBetJWT = array(
		'a'		=> 'CANCELBET',
		't'		=> NOWTIME,
	);
	$sCancelBetJWT = sys_jwt_encode($aCancelBetJWT);
	$aCancelCollectJWT = array(
		'a'		=> 'CANCELCOLLECT',
		't'		=> NOWTIME,
	);
	$sCancelCollectJWT = sys_jwt_encode($aCancelCollectJWT);
	// $aSocketPack = array(
	// 	'a'		=> 'LOGIN',
	// 	'nGid'	=> $nGame,
	// 	'nUid'	=> $aUser['nUid'],
	// 	'sAccount'	=> $aUser['sAccount'],
	// 	'sPassword'	=> $aUser['sPassword'],
	// );
	// $sSocketJWT = sys_jwt_encode_paipai($aSocketPack);
	
	if($nDay == 1)
	{
		$nQueryCreateTime = strtotime('today');
	}
	else if($nDay == 2)
	{
		$nQueryCreateTime = strtotime('yesterday');
	}

	$sSQL = '	SELECT 	nId,nRoom,nGame,nGroupId,sDealer,sOpenTime,sCloseTime,nMaintenance,(SELECT sName0 FROM '.END_MANAGER_DATA.' as _data WHERE _setting.sDealer = _data.nId)
			FROM  	'.END_GAMES_SETTING.' _setting
			WHERE 	sLang = :sLang
			AND		nOnline = 1
			ORDER BY nSort ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sDealerIdList .= ','.$aRows['sDealer'];
		$aData[$aRows['nGame']] = $aRows;
		$aData[$aRows['nGame']]['sDealerId'] = $aRows['sDealer'];
		$aData[$aRows['nGame']]['sName'] = '';
		$aNumsCounting[$aRows['nGame']] = array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
		);
		$aNumsArray[$aRows['nGame']] = array();

		$LPnOpenTime = strtotime($aRows['sOpenTime']);
		$LPnCloseTime = strtotime($aRows['sCloseTime']);
		if($LPnCloseTime < NOWTIME)
		{
			$LPnOpenTime += 86400;
			$LPnCloseTime += 86400;
		}
		if($LPnOpenTime > $LPnCloseTime)
		{
			$LPnOpenTime -= 86400;
		}

		if($aRows['nGame'] < 2000 && $LPnOpenTime > NOWTIME)
		{
			$aData[$aRows['nGame']]['sBetUrl'] = 'javascript: void(0)';
			$aData[$aRows['nGame']]['sClose'] = 'active';
		}

		$aData[$aRows['nGame']]['aSetting']['nOpenTime'] = $LPnOpenTime;
		$aData[$aRows['nGame']]['aSetting']['nCloseTime'] = $LPnCloseTime;
		$aData[$aRows['nGame']]['aSetting']['nCloseRoom'] = 0;
		if(($aRows['sOpenTime'] !== $aRows['sCloseTime'] && $LPnOpenTime > NOWTIME) || $aRows['nMaintenance'] == 1)
		{
			$aData[$aRows['nGame']]['aSetting']['nCloseRoom'] = 1;
		}
		$aPageGames[$aRows['nGame']] = 0;
	}
	$nGamesCount = count($aData);
	// if(!isset($aData[$nGame]) || ($aData[$nGame]['aSetting']['nCloseRoom'] == 1 && $aUser['nTest'] == 0))
	// {
	// 	header('Location: '.$aUrl['sIndex']); 
	// 	exit;
	// }
	
		// $sSQL = '	SELECT 	nId,
		// 				nLid,
		// 				sName0,
		// 				sMethod,
		// 				nMoney0,
		// 				nMoney1
		// 		FROM  	'.END_GAMES_GROUP.'
		// 		WHERE 	nOnline = 1
		// 		AND		sLang = :sLang
		// 		AND		nKid = :nKid
		// 		ORDER BY nSort DESC';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		// $Result->bindValue(':nKid', $aData[$nGame]['nGroupId'], PDO::PARAM_INT);
		// sql_query($Result);
		// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		// {
		// 	$aBetGroup[$aRows['nLid']][$aRows['sMethod']] = $aRows;
		// 	$aBetGroup[$aRows['nLid']]['sActive'] = '';
		// 	$aBetGroup[$aRows['nLid']]['sName0'] = $aRows['sName0'];
		// }
		// $nBetGroupId = key($aBetGroup);
		// if(isset($_COOKIE[$nGame.'nBetGroupId']) && isset($aBetGroup[$_COOKIE[$nGame.'nBetGroupId']]))
		// {
		// 	$nBetGroupId = $_COOKIE[$nGame.'nBetGroupId'];
		// }
		// $aBetGroup[$nBetGroupId]['sActive'] = 'active';

	$sSQL = '	SELECT	nKid,
					sFile,
					nCreateTime,
					nType0
			FROM		'. CLIENT_IMAGE_CTRL .'
			WHERE		sTable LIKE \''. END_MANAGER_DATA .'\'
			AND		nKid IN ('.$sDealerIdList.')
			AND		nType0 = 0';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aPicture['aDealer'][$aRows['nKid']] =  IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).END_MANAGER_DATA.'/'.$aRows['sFile'];
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

	// $sSQL = '	SELECT 	sCode,
	// 				nOdds,
	// 				nOdds1,
	// 				nMinBet,
	// 				nMaxBet,
	// 				nRoundMaxBet
	// 		FROM 		'.END_METHOD_SETTING.'
	// 		WHERE 	1';
	// $Result = $oPdo->prepare($sSQL);
	// // $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aRows['nOdds'] = (float)$aRows['nOdds'];
	// 	$aRows['nOdds1'] = (float)$aRows['nOdds1'];
	// 	$aRows['nMinBet'] = (float)$aRows['nMinBet'];
	// 	$aRows['nMaxBet'] = (float)$aRows['nMaxBet'];
	// 	$aRows['nRoundMaxBet'] = (float)$aRows['nRoundMaxBet'];
	// 	$aOdds[$aRows['sCode']] = $aRows;
	// }

	$aMall = array();
	$sMallList = '0';
	$sSQL = '	SELECT 	nId,
					sName0,
					nPrice0					
			FROM 		'.CLIENT_MALL.'
			WHERE 	nOnline = 1
			ORDER BY nSort,nId ASC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sMallList .= ','.$aRows['nId'];
		$aRows['nPrice0'] = (float)$aRows['nPrice0'];
		$aMall[$aRows['nId']] = $aRows;
		$aMall[$aRows['nId']]['aImage'] = array();
	}

	$sSQL = '	SELECT 	nId,
					nKid,
					sTable,
					sFile,
					nCreateTime,
					nType0
			FROM 		'.CLIENT_IMAGE_CTRL.'
			WHERE 	nKid IN ('.$sMallList.')
			AND		sTable = \''.CLIENT_MALL.'\'';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMall[$aRows['nKid']]['aImage'][$aRows['nType0']]['sUrl'] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'];
	}

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

	// $sSQL = '	SELECT 	nId,
	// 				nGame,
	// 				nMoney0,
	// 				nMoney1,
	// 				sContent0,
	// 				nStatus,
	// 				nDone,
	// 				nUid,
	// 				sUpdateTime,
	// 				sCreateTime
	// 		FROM  	'.CLIENT_GAMES_DATA.'
	// 		WHERE 	nCreateTime > :nCreateTime
	// 		AND		nCreateTime < :nCreateTime1	
	// 		AND		sSiteId = \'line05\'	
	// 		ORDER BY nCreateTime DESC
	// 		LIMIT 30';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aRecordData['aBetList'][$aRows['nId']] = $aRows;
	// 	$aUidData[$aRows['nUid']] = array();
	// }
	// if(isset($_COOKIE['gino']))
	// {
	// 	print_r($aRecordData['aBetList']);
	// }
	// setcookie('nCookieRoom', $nGame, COOKIE['REMEMBER']);

	// $sSQL = '	SELECT 	`DATA`.sName0,
	// 				`DATA`.sAccount,
	// 				(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = DATA.nId) as nMoney,
	// 				`DATA`.nRoom as nGame
	// 		FROM  	'.CLIENT_USER_DATA.' as `DATA`, '.CLIENT_USER_COOKIE.' as COOKIE
	// 		WHERE 	COOKIE.nUid = DATA.nId
	// 		AND		DATA.nRoom = :nRoom
	// 		AND		DATA.nStatus = 0';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nRoom', $nGame,PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aRows['nMoney'] = (float)$aRows['nMoney'];
	// 	$aRows['sName0'] = $aRows['sName0']==''?$aRows['sAccount']:$aRows['sName0'];
	// 	$aPlayerData[$aRows['sName0']]['sName0'] = $aRows['sName0'];
	// 	$aPlayerData[$aRows['sName0']]['nMoney'] = $aRows['nMoney'];
	// 	$aPlayerData[$aRows['sName0']]['nGame'] = $aRows['nGame'];
	// 	$aPlayerData[$aRows['sName0']]['nMan'] = 1;
	// }

	// $sSQL = '	SELECT 	User_.nId,
	// 				User_.sName0,
	// 				User_.nMoney
	// 		FROM 		'.CLIENT_USER_ROBOT.' User_
	// 		WHERE 	nOnline != 99
	// 		AND		nGame = :nGame
	// 		ORDER BY RAND()
	// 		LIMIT 10';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aRows['nMoney'] = (float)$aRows['nMoney'];
	// 	$aPlayerData[$aRows['sName0']] = $aRows;
	// }

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

	// 打賞紀錄
	$aDonateData['nDonateTotal'] = 0;
	$aDonateData['nDonateSubTotal'] = 0;

	$sSQL = '	SELECT 	nMoney0
			FROM  	'.CLIENT_GAMES_DONATE.'
			WHERE 	nUid = :nUid';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aDonateData['nDonateTotal'] += $aRows['nMoney0'];
	}

	## 撈訊息紀錄 ##
	# 讀官方回合機器人
	// $ch = curl_init('https://adm.abp77.com/Bot/MsgJson'.$nGame.'.txt');
	// $ch = curl_init('https://demo801.dtap000s3.com/Project/t_linegame06/File/MsgJson'.$nGame.'.txt');
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

	// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
	// curl_setopt($ch, CURLOPT_TIMEOUT,10);

	// $result = curl_exec($ch);
	// curl_close($ch);
	$result = file_get_contents(dirname(dirname(dirname(dirname(dirname(dirname(__file__)))))) .'/t_linegame06/File/MsgJson'.$nGame.'.txt');
	// echo dirname(dirname(dirname(__file__))) .'/t_linegame06/File/MsgJson'.$nGame.'.txt';
	$sJsonSystem = $result;
	// echo $sJsonSystem;exit;
	$aJsonSystem = json_decode($sJsonSystem, true);
	if(empty($aJsonSystem))
	{
		// echo 1;exit;
		$aJsonSystem = array();
	}
	// $aJsonSystem = array();


	# 讀本地機器人和投注資料
	// $ch = curl_init('https://line01.abp77.com/ClientTest/MsgJson'.$nGame.'.txt');
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

	// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
	// curl_setopt($ch, CURLOPT_TIMEOUT,10);

	// $result = curl_exec($ch);
	// curl_close($ch);
	$aJsonBet = array();
	if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
	{
		$result = file_get_contents(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt');
		$sJsonBet = $result;
		$aJsonBet = json_decode($sJsonBet, true);
	}
	if(empty($aJsonBet) && $aJsonBet !== array())
	{
		$aJsonBet = array();
		// echo dirname(__file__) .'/MsgJson'.$nGame.'.txt';exit;
	}
	// $aJsonBet = array();
	if(count($aJsonBet) < 7)
	{
		# 執行假機器人寫入
	}

	$aJsonMsg = $aJsonBet+$aJsonSystem;
	ksort($aJsonMsg);

	$nSumMoney = 0;
	$nSumMoney0 = 0;
	foreach($aJsonMsg as $LPsUnixTime => $LPaJsonMsg)
	{
		$aJsonMsgChk[$LPsUnixTime] = 0;
		if(isset($aJsonMsg[$LPsUnixTime]['sMsg']) && $LPaJsonMsg['nUid'] == $aUser['nUid'])
		{
			$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('msgMember"','ifme msgSelf"',$LPaJsonMsg['sMsg']);
			$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('dtbox"','dtbox DisplayBlockNone"',$aJsonMsg[$LPsUnixTime]['sMsg']);
			$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('td imgbox float-left"','td imgbox float-left DisplayBlockNone "',$aJsonMsg[$LPsUnixTime]['sMsg']);
		}
		if(isset($aJsonMsg[$LPsUnixTime]['nSumMoney0']))
		{
			$nSumMoney = $aJsonMsg[$LPsUnixTime]['nSumMoney0'];
			$nSumMoney0 = $aJsonMsg[$LPsUnixTime]['nSumMoney0'];
		}
		if(isset($aJsonMsg[$LPsUnixTime]['nSumMoney1']))
		{
			$nSumMoney = $aJsonMsg[$LPsUnixTime]['nSumMoney1'];
		}
		if($LPsUnixTime/10000 > NOWTIME)
		{
			unset($aJsonMsg[$LPsUnixTime]);
			continue;
		}
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

	// if(isset($_COOKIE['sjCoinShowPc']) && $ajCoinShowPc = json_decode($_COOKIE['sjCoinShowPc'],true))
	// {
	// 	$aCoinArrayPc = $ajCoinShowPc['aFile'];
	// }
	// if(isset($_COOKIE['sjCoinShowMb']) && $ajCoinShowMb = json_decode($_COOKIE['sjCoinShowMb'],true))
	// {
	// 	$aCoinArrayMb = $ajCoinShowMb['aFile'];
	// }
	
	$sSQL = '	SELECT 	nSort,sContent0
			FROM  	'.CLIENT_ANNOUNCE.'
			WHERE 	nGame = 0
			AND		nKid = 1
			AND		sLang = \'TW\'';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAnnounce[$aRows['nSort']] = $aRows;
	}
	$sSQL = '	SELECT 	nGame,nMoney0,nMoney1,nMoney2
			FROM  	'.CLIENT_JACKPOT_SET.'
			WHERE 	nGame = 1001
			LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aJackpot[$aRows['nGame']] = $aRows;
	}
	$aAnnounce[0] = str_replace('[:上週彩金:]',(string)(float)$aJackpot[$nGame]['nMoney1'],$aAnnounce[0]);
	$aAnnounce[0] = str_replace('[:總彩金:]',(string)(float)$aJackpot[$nGame]['nMoney2'],$aAnnounce[0]);
	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>