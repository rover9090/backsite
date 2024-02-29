<?php
	// require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/mall.php');

	$aCss = array();
	$aJs = array(
		'20' => 'plugins/js/tool/cookie.js',
		'0' => 'plugins/js/index/lobby.js',
		'1' => 'plugins/js/tool/roadClass.js',
		'2' => 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/game.js',
		'3' => 'plugins/js/tool/cell.js',
		// '5' => 'https://player.live-video.net/1.3.1/amazon-ivs-player.min.js',
		// '6' => 'plugins/js/tool/jitsi_external.js',
		'7' => 'plugins/js/center/center.js',
		// '8' => 'plugins/js/tool/Socket.js',
		'4' => 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet.js',
		'9' => 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/sayMsg.js',
		'10' => 'plugins/js/jquery.mousewheel.min.js',
		'11' => 'plugins/js/mouse_wheel.js',
		'31' => 'https://imgcache.qq.com/open/qcloud/live/webrtc/js/TXLivePusher-1.0.0.min.js',
		'32' => 'plugins/js/tool/testCast.js',
	);
	$aUrl = array(
		'sRankAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.rank_0.php']),
		'sAttentionAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.attention_0.php']),
		'sRecordAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.record_0.php']),
		'sLobbyAjax'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_0.php']),
		'sLobbyAjax1'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_1.php']),
		'sDonateAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.donate_0.php']),
		'sBetAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_0.php']),
		'sRateAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.winrate_0.php']),
		'sBetHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.bet_history_0.php']),
		'sDonateHistoryAjax'=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.donate_history_0.php']),
		'sBetRankingAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_ranking_0.php']),
		'sWatchDealerAjax'=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.watch_dealer_0.php']),
		'sBet'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_0.php']),
		'sIndex'		=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
		'sHtml'	=> 'pages/bet/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/bet_9.php',
	);
	#url結束

	#參數宣告區
	// if(isset($_GET['demo']))
	// {
	// 	$aJs[] = 'plugins/js/tool/Socket.js';
	// }
	$nGame = filter_input_int('nGame', INPUT_REQUEST, 1001);
	$nDay = filter_input_int('day', INPUT_REQUEST, 1);
	if(isset($_COOKIE['gino']))
	{
		// unset($aJs['8']);
		$aJs['8'] = 'plugins/js/tool/photon/Photon-Javascript_SDK.js';
		$aJs['13'] = 'plugins/js/tool/photon/Photon_Interface.js';
		$aJs['14'] = 'plugins/js/tool/BaseCmdLogic.js';
		$aJs['15'] = 'plugins/js/tool/SocketNew.js';
	}
	$aData = array();
	$aPicture = array();
	$aNumsArray = array();# 歷屆期數0~71共72期
	$aNumsCounting = array();
	$sDealerIdList = '0';
	$aRecordData = array('aBetData'=>array());
	$aDonateData = array('aDonateData'=>array());
	$aDealer = array();
	$aBetGroup = array();
	$nGamesCount = 0;
	$sWatchClass = '';
	$aFilter = array();
	$aPlayerData = array();
	$aCoinArrayPc = array(2,3,4,5,6);
	$aCoinArrayMb = array(2,3,4,5,6,7);
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
		'PP' => '閑對',#1:11
		'BP' => '庄對',#1:11
		'PW' => '閑',#1:1
		'BW' => '庄',#1:0.95
		'DRAW' => '和',#1:8
		'SIX' => '幸運6',#1:12/1:20
	);
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
	$aSocketPack = array(
		'a'		=> 'LOGIN',
		'nGid'	=> $nGame,
		'nUid'	=> $aUser['nUid'],
		'sAccount'	=> $aUser['sAccount'],
		'sPassword'	=> $aUser['sPassword'],
	);
	$sSocketJWT = sys_jwt_encode_paipai($aSocketPack);
	
	if($nDay == 1)
	{
		$nQueryCreateTime = strtotime('today');
	}
	else if($nDay == 2)
	{
		$nQueryCreateTime = strtotime('yesterday');
	}

	$sSQL = '	SELECT 	nId,nRoom,nGame,nGroupId,nDealer,sOpenTime,sCloseTime,(SELECT sName0 FROM '.END_MANAGER_DATA.' as _data WHERE _setting.nDealer = _data.nId)
			FROM  	'.END_GAMES_SETTING.' _setting
			WHERE 	sLang = :sLang
			AND		nOnline = 1
			ORDER BY nGame ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sDealerIdList .= ','.$aRows['nDealer'];
		$aData[$aRows['nGame']] = $aRows;
		$aData[$aRows['nGame']]['nDealerId'] = $aRows['nDealer'];
		$aData[$aRows['nGame']]['sName'] = '';
		$aNumsCounting[$aRows['nGame']] = array(
			'PW' => 0,
			'BW' => 0,
			'DRAW' => 0,
			'BP' => 0,
			'PP' => 0,
			'SIX' => 0,
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
	}
	$nGamesCount = count($aData);
	if(!isset($aData[$nGame]))
	{
		header('Location: '.$aUrl['sIndex']); 
		exit;
	}
	
	$sSQL = '	SELECT 	nId,
					nLid,
					sName0,
					sMethod,
					nMoney0,
					nMoney1
			FROM  	'.END_GAMES_GROUP.'
			WHERE 	nOnline = 1
			AND		sLang = :sLang
			AND		nKid = :nKid
			ORDER BY nSort DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	$Result->bindValue(':nKid', $aData[$nGame]['nGroupId'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBetGroup[$aRows['nLid']][$aRows['sMethod']] = $aRows;
		$aBetGroup[$aRows['nLid']]['sActive'] = '';
		$aBetGroup[$aRows['nLid']]['sName0'] = $aRows['sName0'];
	}
	$aBetGroup[key($aBetGroup)]['sActive'] = 'active';

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

	$sSQL = '	SELECT 	nId,
					nGame,
					sResult,
					nRound
			FROM  	'.CLIENT_GAMES_NUMS.'
			WHERE 	nUse = 1
			ORDER BY nRound ASC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		// if($aRows['nGame'] == 1001) continue;
		if(!empty($aRows['sResult']) && isset($aNumsCounting[$aRows['nGame']]))
		{
			$LPaResult = json_decode($aRows['sResult'],true);
		
			$aNumsArray[$aRows['nGame']][$aRows['nRound']] = $aRows;
			$aNumsArray[$aRows['nGame']][$aRows['nRound']]['aResult'] = $LPaResult;
			foreach($LPaResult as $LP2sResult)
			{
				$aNumsCounting[$aRows['nGame']][$LP2sResult]++;
			}
		}		
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
			AND		nCreateTime > :nCreateTime
			AND		nCreateTime < :nCreateTime1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aPage['nDataAmount']++;
		$aRecordData['nBetTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}
	$aPage['nDataAmount'] = count($Result->fetchAll());

	$sSQL = '	SELECT 	sCode,
					nOdds,
					nOdds1,
					nMinBet,
					nMaxBet,
					nRoundMaxBet
			FROM 		'.END_METHOD_SETTING.'
			WHERE 	1';
	$Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nOdds'] = (float)$aRows['nOdds'];
		$aRows['nOdds1'] = (float)$aRows['nOdds1'];
		$aRows['nMinBet'] = (float)$aRows['nMinBet'];
		$aRows['nMaxBet'] = (float)$aRows['nMaxBet'];
		$aRows['nRoundMaxBet'] = (float)$aRows['nRoundMaxBet'];
		$aOdds[$aRows['sCode']] = $aRows;
	}

	$sSQL = '	SELECT 	nId,
					nGame,
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
			'.sql_limit($nPageStart, $aPage['nPageSize']);;
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRecordData['aBetData'][$aRows['nId']] = $aRows;
		$aRecordData['nBetSubTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinSubTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	}

	$aRecordPageList = pageSet($aPage, 'javascript:void(0);');

	$aSQL_Array = array(
		'nRoom' => $nGame,
	);
	$sSQL = '	UPDATE '. CLIENT_USER_DATA .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
			WHERE	nId = :nUid LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
	sql_build_value($Result, $aSQL_Array);
	sql_query($Result);

	setcookie('nCookieRoom', $nGame, COOKIE['REMEMBER']);

	$sSQL = '	SELECT 	nId,
					sName0
			FROM 		'.END_CHAT_FILTER.'
			WHERE 	nOnline = 1';
	$Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aFilter[$aRows['sName0']] = $aRows['sName0'];
	}

	$sSQL = '	SELECT 	User_.nId,
					User_.sName0,
					User_.sAccount,
					User_.nMoney
			FROM 		'.CLIENT_USER_ROBOT.' User_
			WHERE 	nOnline != 99
			ORDER BY RAND()
			LIMIT 10';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nSit'] = 0;
		$aPlayerData[] = $aRows;
	}

	// 打賞紀錄
	$aDonateData['nDonateTotal'] = 0;
	$aDonateData['nDonateSubTotal'] = 0;

	$sSQL = '	SELECT 	nMoney0
			FROM  	'.CLIENT_GAMES_DONATE.'
			WHERE 	nUid = :nUid
			AND		nCreateTime > :nCreateTime
			AND		nCreateTime < :nCreateTime1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aDonateData['nDonateTotal'] += $aRows['nMoney0'];
	}

	$sSQL = '	SELECT 	nId,
					nDealer,
					nGame,
					nMoney0,
					nCreateTime,
					sCreateTime
			FROM  	'.CLIENT_GAMES_DONATE.'
			WHERE 	nUid = :nUid
			AND		nCreateTime > :nCreateTime
			AND		nCreateTime < :nCreateTime1
			ORDER BY nCreateTime DESC
			LIMIT 10';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aDonateData['aDonateData'][$aRows['nId']] = $aRows;
		$aDonateData['nDonateSubTotal'] += $aRows['nMoney0'];
	}

	$sSQL = '	SELECT 	nId,
					sName0
			FROM  	'. END_MANAGER_DATA .'
			WHERE 	nAdmType = 4';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aDealer[$aRows['nId']] = $aRows;
	}

	$sSQL = '	SELECT 	nId
			FROM 		'.CLIENT_WATCH_DEALER.'
			WHERE 	nUid = :nUid
			AND		nDealer = :nDealer
			LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'],PDO::PARAM_INT);
	$Result->bindValue(':nDealer', $aData[$nGame]['nDealerId'],PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sWatchClass = 'active';
	}

	$aRoomData = array(
		'sName' => md5($nGame),
		'sPassword' => '1231',
		'sHost'	=> 'HOST',
	);

	if(isset($_COOKIE['sjCoinShowPc']) && $ajCoinShowPc = json_decode($_COOKIE['sjCoinShowPc'],true))
	{
		$aCoinArrayPc = $ajCoinShowPc['aFile'];
	}
	if(isset($_COOKIE['sjCoinShowMb']) && $ajCoinShowMb = json_decode($_COOKIE['sjCoinShowMb'],true))
	{
		$aCoinArrayMb = $ajCoinShowMb['aFile'];
	}
	
	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>