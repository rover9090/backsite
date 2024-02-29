<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_stream.php');

	$aCss = array();
	$aJs = array(
		'3' => 'plugins/js/tool/TcPlayer-2.4.1.js',
		// '0' => 'plugins/js/tool/external_api.js',
		'1' => 'plugins/js/end_games/end_games_stream.js',
		// '2' => 'plugins/js/tool/jitsi_external.js',
	);
	$aUrl = array(
		'sLobbyAjax'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_watch_1_ajax1.php']),
		'sLobbyAjax1'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_stream_0_ajax0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_stream_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_stream_0.php',
	);
	#url結束

	#參數宣告區
	$nGame = filter_input_int('nGame', INPUT_REQUEST, 1001);
	$nDay = filter_input_int('day', INPUT_REQUEST, 1);
	$aData = array();
	$aPicture = array();
	$aNumsArray = array();# 歷屆期數0~71共72期
	$aNumsCounting = array();
	$sDealerIdList = '0';
	$nGamesCount = 0;
	$aGame_Setting = array();
	$aLobbyPack = array(
		'a'		=> 'LOBBY',
		't'		=> NOWTIME,
		// 'nExp'	=> NOWTIME+300,
	);
	$sLobbyJWT = sys_jwt_encode($aLobbyPack);
	if($nGame >= 1003){
		unset($aJs['0']);
		unset($aJs['2']);
	}
	#宣告結束

	#程式邏輯區	
	// $aUrl['sPage_Mod'] = 'https://'.WEBSITE['NEWADM'].substr($aUrl['sPage'],2);
	$aUrl['sPage_Mod'] = $aUrl['sPage'];
	$sSQL = '	SELECT 	nId,
					nGame,
					sName0,
					nOnline,
					sOpenTime,
					sCloseTime,
					nDealer,
					nShuffling
			FROM  	'.END_GAMES_SETTING.'
			WHERE 	sLang = :sLang
			AND		nOnline = 1
			ORDER BY sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nGame']] = $aRows;
		$aData[$aRows['nGame']]['nDealerId'] = 0;
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
		if ($nGame < 0)# 預設
		{
			$nGame = $aRows['nId'];
		}
		$aGame_Setting[$aRows['nGame']] = $aRows;
		$aGame_Setting[$aRows['nGame']]['sSelect'] = '';
		if($nGame == $aRows['nGame'])
		{
			$aGame_Setting[$aRows['nGame']]['sSelect'] = 'selected';
		}		
	}
	$nGamesCount = count($aData);

	// $sSQL = '	SELECT 	nums.nId,
	// 				nums.sNo,
	// 				nums.nGame,
	// 				nums.sResult,
	// 				nums.nEndTime,
	// 				nums.nStatus,
	// 				dealer.sName0,
	// 				dealer.nId as nDealerId
	// 		FROM  	'.CLIENT_GAMES_NUMS.' as nums, '.END_MANAGER_DATA.' as dealer
	// 		WHERE 	nums.nDealer = dealer.nId
	// 		AND		nStartTime < :NOWTIME
	// 		AND		nums.nStatus < 4
	// 		LIMIT '.$nGamesCount;
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	if(isset($aData[$aRows['nGame']]))
	// 	{
	// 		$aData[$aRows['nGame']]['nDealerId'] = $aRows['nDealerId'];
	// 		$aData[$aRows['nGame']]['nStatus'] = $aRows['nStatus'];
	// 		$aData[$aRows['nGame']]['sName0'] = $aRows['sName0'];
	// 		$sDealerIdList .= ','.$aRows['nDealerId'];
	// 	}
	// }

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
		if(!empty($aRows['sResult']) && isset($aData[$aRows['nGame']]))
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

	$sSQL = '	SELECT 	sCode,
					nOdds,
					nOdds1,
					nMinBet,
					nMaxBet
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
		$aOdds[$aRows['sCode']] = $aRows;
	}

	$aRoomData = array(
		'sName' => md5($nGame),
		'sPassword' => '1231',
		'sHost'	=> 'HOST',
	);
	if(isset($_GET['GINO']))
	{
		$aRoomData['sHost'] = 'GINO';
	}
	
	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>