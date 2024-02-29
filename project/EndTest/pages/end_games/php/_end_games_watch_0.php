<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_watch.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'plugins/js/end_games/end_games_watch.js',
	);
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '1001');
	$nReloadTime	= filter_input_int('nReloadTime', 	INPUT_REQUEST, '5');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		#'sUpt1'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_watch_0_upt1.php']),# 注單投注排行
		'sUser'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sUserAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_act0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_watch_0.php']),
		'sAjax0'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_watch_0_ajax0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_watch_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_watch_0.php',
	);
	#url結束

	#參數宣告區
	$aGameSetting = array();
	$aReloadSetting = array(
		'1' => array(
			'sName0' => '1'.aLOTTERY['SEC'],
			'sSelect' => '',
		),
		'3' => array(
			'sName0' => '3'.aLOTTERY['SEC'],
			'sSelect' => '',
		),
		'5' => array(
			'sName0' => '5'.aLOTTERY['SEC'],
			'sSelect' => '',
		),
		'10' => array(
			'sName0' => '10'.aLOTTERY['SEC'],
			'sSelect' => '',
		),
		'15' => array(
			'sName0' => '15'.aLOTTERY['SEC'],
			'sSelect' => '',
		),
	);
	$aData = array(
		'aNums' => array(),
		'aBetData' => array(),
		'aBetRank' => array(),
		'aBankerData' => array(),
		'aBetCount' => array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
			'BANKER' => 0,
			'OTHERS' => 0,
		),
		'aBetMoney' => array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
			'BANKER' => 0,
			'OTHERS' => 0,
		),
		'aPerMoney' => array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
			'BANKER' => 0,
			'OTHERS' => 0,
		),
		'nBetMoneySum' => 0,
		'nPerMoneySum' => 0,
		'nBetCountSum' => 0,
	);
	$aValue = array(
		'a'	=> 'LOTTERYAJAX',
		't'	=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nCount = 0;
	$aStatus = aLOTTERY['aSTATUS'];
	$aResult = aLOTTERY['aRESULT'];
	$aBetResult = aLOTTERY['aBETRESULT'];
	$sUserList = '0';
	#宣告結束

	#程式邏輯區
	$aReloadSetting[$nReloadTime]['sSelect'] = 'SELECTED';
	$aGameSetting = GameSet_Class::fnCallSetting();
	foreach($aGameSetting as $LPsKey => $true)
	{
		if($nGame == $LPsKey)
		{
			$aGameSetting[$LPsKey]['sSelect'] = 'selected';
		}
		else
		{
			$aGameSetting[$LPsKey]['sSelect'] = '';
		}
	}

	$aBindArray['nGame'] = $nGame;

	if($nGame > 2000)
	{
		$sSQL = '	SELECT 	nId,
				sNo,
				nGame,
				sResult,
				sNums,
				nStatus,
				nRound,
				nStartTime,
				nEndTime
		FROM 		'.CLIENT_GAMES_NUMS2.' 
		WHERE 	nGame = :nGame
		AND		nPreStartTime <= :NOWTIME
		AND		nPauseTime1 > :NOWTIME';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
		sql_query($Result);
		$aData['aNums'] = $Result->fetch(PDO::FETCH_ASSOC);
	}
	else
	{
		$sSQL = '	SELECT 	nId,
				sNo,
				nGame,
				sResult,
				sNums,
				nStatus,
				nRound,
				nStartTime,
				nEndTime
		FROM  	'.CLIENT_GAMES_NUMS.'
		WHERE 	nGame = :nGame
		ORDER BY nGameSerId DESC
		LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		$aData['aNums'] = $Result->fetch(PDO::FETCH_ASSOC);	
	}
	
	

	if($aData['aNums'] === false)
	{
		$aData['aNums']['sNo'] = aMsg['NODATA'];
	}
	else
	{
		$aBindArray['nGame'] = $nGame;
		$aBindArray['sNo'] = $aData['aNums']['sNo'];

		$aData['aNums']['aResult'] = json_decode($aData['aNums']['sResult'],true);
		$aData['aNums']['aNums'] = json_decode($aData['aNums']['sNums'],true);

		$aData['aNums']['sResultText'] = '';
		// if(!empty($aData['aNums']['aNums']))
		// {
		// 	foreach($aData['aNums']['aResult']['Money0'] as $LPsResult)
		// 	{
		// 		if(isset(aRESULTTYPE[$LPsResult])){
		// 			$aData['aNums']['sResultText'] .= aRESULTTYPE[$LPsResult].',';
		// 		}else{
		// 			trigger_error($LPsResult.print_r(aRESULTTYPE,true));
		// 		}
		// 	}
		// 	foreach($aData['aNums']['aResult']['Money1']['Banker'] as $LPsResult)
		// 	{
		// 		if(isset(aRESULTTYPE[$LPsResult])){
		// 			$aData['aNums']['sResultText'] .= aRESULTTYPE[$LPsResult].',';
		// 		}else{
		// 			trigger_error($LPsResult.print_r(aRESULTTYPE,true));
		// 		}
		// 	}
		// 	foreach($aData['aNums']['aResult']['Money1']['Others'] as $LPsResult)
		// 	{
		// 		if(isset(aRESULTTYPE[$LPsResult])){
		// 			$aData['aNums']['sResultText'] .= aRESULTTYPE[$LPsResult].',';
		// 		}else{
		// 			trigger_error($LPsResult.print_r(aRESULTTYPE,true));
		// 		}
		// 	}
		// }		
	}

	$sSQL = '	SELECT	nId,
					nUid,
					nMoney0,
					nMoney1,
					sContent0,
					sCreateTime,
					sPerLink,
					sSiteId
			FROM		'. CLIENT_GAMES_DATA .'
			WHERE		sNo LIKE :sNo
			AND		nGame = :nGame';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	$Result->bindValue(':sNo', $aData['aNums']['sNo'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sUserList .= ','.$aRows['nUid'];
		$aData['aBetData'][$aRows['nId']] = $aRows;

		$aTempPerLink = explode(',',$aRows['sPerLink']);		
		$aData['aBetData'][$aRows['nId']]['nPer'] = 0;
		$aData['aBetData'][$aRows['nId']]['nPerMoney'] = 0;
		if($aRows['sSiteId'] === 'line06')
		{
			$aData['aBetData'][$aRows['nId']]['nPer'] = $aTempPerLink[0]-$aTempPerLink[1];
			$aData['aBetData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
			$aData['aPerMoney'][$aRows['sContent0']] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
			$aData['nPerMoneySum'] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
		}
		else if(count($aTempPerLink) >= 3)
		{
			$aData['aBetData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			$aData['aBetData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
			$aData['aPerMoney'][$aRows['sContent0']] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
			$aData['nPerMoneySum'] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
		}

		$aData['aBetMoney'][$aRows['sContent0']] += $aRows['nMoney0'];
		$aData['aBetCount'][$aRows['sContent0']] ++;
		$aData['nBetMoneySum'] += $aRows['nMoney0'];
		$aData['nBetCountSum'] ++;
		if(!isset($aData['aBetRank'][$aRows['nUid']]))
		{
			$aData['aBetRank'][$aRows['nUid']] = $aRows;
			$aData['aBetRank'][$aRows['nUid']]['nCount'] = 0;
			$aData['aBetRank'][$aRows['nUid']]['nMoney'] = 0;
		}
		$aData['aBetRank'][$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
		$aData['aBetRank'][$aRows['nUid']]['nCount'] ++;
	}

	$sSQL = '	SELECT	nId,
					nUid,
					nMoney0,
					nMoney1,
					nMoney2,
					nType0,
					sCreateTime,
					sPerLink,
					sSiteId
			FROM		'. CLIENT_GAMES_BANKER .'
			WHERE		nGame = :nGame
			AND		sNo = :sNo';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	$Result->bindValue(':sNo', $aData['aNums']['sNo'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$LPsContent0 = $aRows['nType0'] === '0'?'BANKER':'OTHERS';
		$sUserList .= ','.$aRows['nUid'];
		$aRows['sContent0'] = $LPsContent0;
		$aData['aBankerData'][$aRows['nId']] = $aRows;

		$aTempPerLink = explode(',',$aRows['sPerLink']);		
		$aData['aBankerData'][$aRows['nId']]['nPer'] = 0;
		$aData['aBankerData'][$aRows['nId']]['nPerMoney'] = 0;
		if($aRows['sSiteId'] === 'line06')
		{
			$aData['aBankerData'][$aRows['nId']]['nPer'] = $aTempPerLink[0]-$aTempPerLink[1];
			$aData['aBankerData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
			$aData['aPerMoney'][$LPsContent0] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
			$aData['nPerMoneySum'] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
		}
		else if(count($aTempPerLink) >= 3)
		{
			$aData['aBankerData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			$aData['aBankerData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
			$aData['aPerMoney'][$LPsContent0] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
			$aData['nPerMoneySum'] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
		}

		$aData['aBetMoney'][$LPsContent0] += $aRows['nMoney0'];
		$aData['aBetCount'][$LPsContent0] ++;
		$aData['nBetMoneySum'] += $aRows['nMoney0'];
		$aData['nBetCountSum'] ++;
		if(!isset($aData['aBetRank'][$aRows['nUid']]))
		{
			$aData['aBetRank'][$aRows['nUid']] = $aRows;
			$aData['aBetRank'][$aRows['nUid']]['nCount'] = 0;
			$aData['aBetRank'][$aRows['nUid']]['nMoney'] = 0;
		}
		$aData['aBetRank'][$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
		$aData['aBetRank'][$aRows['nUid']]['nCount'] ++;
	}

	$sSQL = '	SELECT	nId,
					sAccount,
					sName0,
					nOnline,
					nStatus,
					nIdentity,
					nBlack,
					nMute
			FROM		'. CLIENT_USER_DATA .'
			WHERE		nId IN ('.$sUserList.')';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	$Result->bindValue(':sNo', $aData['aNums']['sNo'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['aUser'][$aRows['nId']] = $aRows;
		$nAccountType = substr($aRows['sAccount'],-1);
		if($nAccountType == '0')
		{
			$sDomColor = 'FontGreen';
		}
		$sDomColor = $aRows['nBlack'] == 1?'FontRed':'';
		$aData['aUser'][$aRows['nId']]['sAccountDom'] = '<a style="text-decoration:underline;" class="'.$sDomColor.'" href="'.$aUrl['sUser'].'&sSearchType=sAccount&sSearch='.$aRows['sAccount'].'">'.$aRows['sAccount'].'</a>';
		$aData['aUser'][$aRows['nId']]['sNameDom'] = '<a style="text-decoration:underline;" class="'.$sDomColor.'" href="'.$aUrl['sUser'].'&sSearchType=sAccount&sSearch='.$aRows['sAccount'].'">'.$aRows['sName0'].'</a>';
		$aData['aUser'][$aRows['nId']]['sMuteUrl'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&nMute=1';
		$aData['aUser'][$aRows['nId']]['sBlackUrl'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&nBlack=1';
	}

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>