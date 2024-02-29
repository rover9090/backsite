<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/index.php');

	$aCss = array();
	$aJs = array(
		'0' => 'plugins/js/index/lobby.js',
		'1' => 'plugins/js/tool/roadClass.js',
		'2' => 'plugins/js/tool/cell.js',
		// '4' => 'plugins/js/tool/cookie.js',
		// '3' => 'plugins/js/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/game.js',
		'7' => 'plugins/js/center/center.js',
		'8' => 'plugins/js/index/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/index.js',
		'9' => 'plugins/js/index/record.js',
	);
	$aUrl = array(
		'sRankAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.rank_0.php']),
		'sAttentionAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.attention_0.php']),
		'sRecordAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.record_0.php']),
		'sBetDetailAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.betdetail_0.php']),
		'sLobbyAjax'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_0.php']),
		'sLobbyAjax1'	=> sys_web_encode($aMenuToNo['pages/index/php/_ajax.lobby_1.php']),
		'sRateAjax'		=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.winrate_0.php']),
		'sBetHistoryAjax'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.bet_history_0.php']),
		'sDonateHistoryAjax'=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.donate_history_0.php']),
		'sBetRankingAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_ranking_0.php']),
		'sBet0'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_0.php']),
		'sBet1'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_1.php']),
		'sBet'		=> '',
		'sHtml'		=> 'pages/index/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/index_0.php',
	);
	#url結束

	#參數宣告區
	$nDay = filter_input_int('day', INPUT_REQUEST, 1);
	$aData = array();
	$aPicture = array();
	$aNumsArray = array();# 歷屆期數0~71共72期
	$aNumsCounting = array();
	$sDealerIdList = '0';
	$aRecordData = array('aBetData'=>array());
	$aDonateData = array('aDonateData'=>array());
	$nGamesCount = 0;
	$aDefaultGames = array();
	$aPageGames = array();
	#宣告結束

	#程式邏輯區
	if($nDay == 1)
	{
		$nQueryCreateTime = strtotime('today');
	}
	else if($nDay == 2)
	{
		$nQueryCreateTime = strtotime('yesterday');
	}

	$aUrl['sBet'] = $aUrl['sBet0'];
	if($nIsLoginLobby == 2)
	{
		$aUrl['sBet'] = $aUrl['sBet1'];
	}


	$aLobbyPack = array(
		'a'		=> 'LOBBY',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sLobbyJWT = sys_jwt_encode($aLobbyPack);
	$aBetRankingPack = array(
		'a'		=> 'RANKING',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sBetRankingJWT = sys_jwt_encode($aBetRankingPack);

	$sSQL = '	SELECT 	nId,nRoom,nGame,nDealer,sOpenTime,sCloseTime,nMaintenance
			FROM  	'.END_GAMES_SETTING.'
			WHERE 	sLang = :sLang
			AND		nOnline = 1
			ORDER	BY	nSort ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nGame']] = $aRows;
		$aData[$aRows['nGame']]['nDealerId'] = $aRows['nDealer'];
		$aData[$aRows['nGame']]['sName'] = '';
		$aData[$aRows['nGame']]['sBetUrl'] = $aUrl['sBet'].'&nGame='.$aRows['nGame'];
		$aData[$aRows['nGame']]['sClose'] = '';
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

		if(!isset($_GET['jp']) && ($aRows['nMaintenance'] == 1 || ($aRows['nGame'] < 2000 && $LPnOpenTime > NOWTIME)))
		{
			$aData[$aRows['nGame']]['sBetUrl'] = 'javascript: void(0)';
			$aData[$aRows['nGame']]['sClose'] = 'active';
		}
		if($nIsLoginLobby === 0 || $nIsLoginLobby == $aRows['nGame'][0])
		{
			$aDefaultGames[$aRows['nGame']] = 0;
		}
	}
	$nGamesCount = count($aData);

	$sSQL = '	SELECT 	nums.nId,
					nums.sNo,
					nums.nGame,
					nums.sResult,
					nums.nEndTime,
					nums.nStatus,
					dealer.sName0,
					dealer.nId as nDealerId
			FROM  	'.CLIENT_GAMES_NUMS.' as nums, '.END_MANAGER_DATA.' as dealer
			WHERE 	nums.nDealer = dealer.nId
			AND		dealer.nAdmType = 4
			AND		nums.nStatus < 4
			LIMIT '.$nGamesCount;
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nGame']]['nDealerId'] = $aRows['nDealerId'];
		$aData[$aRows['nGame']]['nStatus'] = $aRows['nStatus'];
		$aData[$aRows['nGame']]['sName'] = $aRows['sName'];
		$sDealerIdList .= ','.$aRows['nDealerId'];
	}

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
			AND		nStartTime < :NOWTIME	
			AND		nStatus < 10		
			ORDER BY nRound ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
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


	// $nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	// $aPage['nDataAmount'] = 0;
	// $aRecordData['nBetTotal'] = 0;
	// $aRecordData['nWinTotal'] = 0;
	// $aRecordData['nBetSubTotal'] = 0;
	// $aRecordData['nWinSubTotal'] = 0;
	// $sSQL = '	SELECT 	nMoney0,nMoney1
	// 		FROM  	'.CLIENT_GAMES_DATA.'
	// 		WHERE 	nUid = :nUid
	// 		AND		nCreateTime > :nCreateTime';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', strtotime('today'), PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aPage['nDataAmount']++;
	// 	$aRecordData['nBetTotal'] += $aRows['nMoney0'];
	// 	$aRecordData['nWinTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	// }
	// $aPage['nDataAmount'] = count($Result->fetchAll());

	// $sSQL = '	SELECT 	nId,
	// 				nGame,
	// 				nMoney0,
	// 				nMoney1,
	// 				sContent0,
	// 				nStatus,
	// 				nDone,
	// 				sUpdateTime,
	// 				sCreateTime
	// 		FROM  	'.CLIENT_GAMES_DATA.'
	// 		WHERE 	nUid = :nUid
	// 		AND		nCreateTime > :nCreateTime
	// 		ORDER BY 	nId DESC';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', strtotime('today'), PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aRecordData['aBetData'][$aRows['nId']] = $aRows;
	// 	$aRecordData['nBetSubTotal'] += $aRows['nMoney0'];
	// 	$aRecordData['nWinSubTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
	// }


	// 打賞紀錄
	// $aDonateData['nDonateTotal'] = 0;
	// $aDonateData['nDonateSubTotal'] = 0;

	// $sSQL = '	SELECT 	nMoney0
	// 		FROM  	'.CLIENT_GAMES_DONATE.'
	// 		WHERE 	nUid = :nUid
	// 		AND		nCreateTime > :nCreateTime
	// 		AND		nCreateTime < :nCreateTime1';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aDonateData['nDonateTotal'] += $aRows['nMoney0'];
	// }

	// $sSQL = '	SELECT 	nId,
	// 				nDealer,
	// 				nGame,
	// 				nMoney0,
	// 				nCreateTime,
	// 				sCreateTime
	// 		FROM  	'.CLIENT_GAMES_DONATE.'
	// 		WHERE 	nUid = :nUid
	// 		AND		nCreateTime > :nCreateTime
	// 		AND		nCreateTime < :nCreateTime1
	// 		ORDER BY 	nId DESC';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', $nQueryCreateTime, PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime1', $nQueryCreateTime+86399, PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aDonateData['aDonateData'][$aRows['nId']] = $aRows;
	// 	$aDonateData['nDonateSubTotal'] += $aRows['nMoney0'];
	// }

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

	// $aRecordPageList = pageSet($aPage, 'javascript:void(0);');
	
	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>