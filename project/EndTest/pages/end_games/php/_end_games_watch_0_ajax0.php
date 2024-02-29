<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_watch.php');
	#require end

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '1001');
	// $nGame = trim($sNo);
	#參數結束

	#參數宣告區
	$aUrl = array(
		'sUser'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sUserAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_act0.php']),
	);
	$sNo = '';
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> ''
	);
	$aReturnData = array(
		'aNums' => array(),
		'aBetData' => array(),
		'aBetRank' => array(),
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
	$aBetLang = aLOTTERY['aBETRESULT'];
	$sUserList = '0';
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'LOTTERYAJAX')
	{
		// $sJsonRooms = file_get_contents(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/File/RoomJson.txt');
		// $aRooms = json_decode($sJsonRooms,true);
		$aGameSetting = GameSet_Class::fnCallSetting();
		$aGameSetting = $aGameSetting[$nGame];

		$sSQL = '	SELECT 	nId,
						sNo,
						nGame,
						sResult,
						nStatus,
						sDealer,
						nRound,
						nStartTime,
						nEndTime,
						sNums,
						nBetEndTime,
						nFinalRound,
						nDayLast,
						nCancel,
						nPauseTime1,
						nPauseTime0
				FROM		' . CLIENT_GAMES_NUMS . '
				WHERE		nGame = :nGame
				ORDER BY nGameSerId DESC 
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		// $aRows = $aRooms[$nGame]['aLastest'];

		$aReturnData['aNums'] = $aRows;
		$aReturnData['aNums']['nSeconds'] = 0;
		$aReturnData['aNums']['nSeconds1'] = 0;
		$aReturnData['aNums']['nSeconds'] = ($aRows['nStartTime']+$aGameSetting['nSec']) - NOWTIME;
		if($aRows['nStartTime'] <= NOWTIME)
		{
			$aReturnData['aNums']['nSeconds'] = ($aRows['nStartTime']+$aGameSetting['nSec']) - NOWTIME;
		}
		$aReturnData['aNums']['nShuffling'] = $aGameSetting['nShuffling'];
		$sNo = $aRows['sNo'];

		## 用JS搭配class去處理


		$sSQL = '	SELECT	nId,
						nUid,
						nMoney0,
						sContent0,
						sCreateTime,
						sPerLink,
						sSiteId
				FROM		'. CLIENT_GAMES_DATA .'
				WHERE		nGame = :nGame
				AND		sNo = :sNo';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sUserList .= ','.$aRows['nUid'];
			// $aRows['nMoney0'] = $aRows['nMoney0'] * $aIsLoginCoinRate[aSITECOIN[$aRows['sSiteId']]];
			$aReturnData['aBetCount'][$aRows['sContent0']]++;
			$aReturnData['aBetMoney'][$aRows['sContent0']] += $aRows['nMoney0'];
			$aReturnData['aBetData'][$aRows['nId']] = $aRows;
			$aReturnData['aBetData'][$aRows['nId']]['sContent0'] = $aBetLang[$aRows['sContent0']];

			$aTempPerLink = explode(',',$aRows['sPerLink']);
			if($aRows['sSiteId'] === 'line06')
			{
				$aReturnData['aBetData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
				$aReturnData['aPerMoney'][$aRows['sContent0']] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
				$aReturnData['aBetData'][$aRows['nId']]['nPer'] = $aTempPerLink[0]-$aTempPerLink[1];
			}
			else if(!isset($aTempPerLink[1]) || !isset($aTempPerLink[2]))
			{
				trigger_error(print_r($aRows,true));
				$aReturnData['aBetData'][$aRows['nId']]['nPerMoney'] = 0;
				$aReturnData['aPerMoney'][$aRows['sContent0']] += 0;
			}
			else
			{
				$aReturnData['aBetData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
				$aReturnData['aPerMoney'][$aRows['sContent0']] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
				$aReturnData['aBetData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			}
			// if(count($aTempPerLink) == 3)
			// {
			// 	$aReturnData['aBetData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			// }
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
		$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$LPsContent0 = $aRows['nType0'] === '0'?'BANKER':'OTHERS';
			$sUserList .= ','.$aRows['nUid'];
			// $aRows['nMoney0'] = $aRows['nMoney0'] * $aIsLoginCoinRate[aSITECOIN[$aRows['sSiteId']]];
			$aReturnData['aBetCount'][$LPsContent0]++;
			$aReturnData['aBetMoney'][$LPsContent0] += $aRows['nMoney0'];
			$aReturnData['aBankerData'][$aRows['nId']] = $aRows;
			$aReturnData['aBankerData'][$aRows['nId']]['sContent0'] = $aBetLang[$LPsContent0];

			$aTempPerLink = explode(',',$aRows['sPerLink']);
			if($aRows['sSiteId'] === 'line06')
			{
				$aReturnData['aBankerData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
				$aReturnData['aPerMoney'][$LPsContent0] += ($aTempPerLink[0]-$aTempPerLink[1])/100 * $aRows['nMoney0'];
				$aReturnData['aBankerData'][$aRows['nId']]['nPer'] = $aTempPerLink[0]-$aTempPerLink[1];
			}
			else if(!isset($aTempPerLink[1]) || !isset($aTempPerLink[2]))
			{
				// trigger_error(print_r($aRows,true));
				$aReturnData['aBankerData'][$aRows['nId']]['nPerMoney'] = 0;
				$aReturnData['aPerMoney'][$LPsContent0] += 0;
			}
			else
			{
				$aReturnData['aBankerData'][$aRows['nId']]['nPerMoney'] = ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
				$aReturnData['aPerMoney'][$LPsContent0] += ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
				$aReturnData['aBankerData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			}
			// if(count($aTempPerLink) == 3)
			// {
			// 	$aReturnData['aBankerData'][$aRows['nId']]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			// }
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
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aReturnData['aUser'][$aRows['nId']] = $aRows;
			$nAccountType = substr($aRows['sAccount'],-1);
			if($nAccountType == '0')
			{
				$sDomColor = 'FontGreen';
			}
			$sDomColor = $aRows['nBlack'] == 1?'FontRed':'';
			$aReturnData['aUser'][$aRows['nId']]['sNameDom'] = '<a style="text-decoration:underline;" class="'.$sDomColor.'" href="'.$aUrl['sUser'].'&sSearchType=sAccount&sSearch='.$aRows['sAccount'].'">'.$aRows['sName0'].'</a>';
			$aReturnData['aUser'][$aRows['nId']]['sAccountDom'] = '<a style="text-decoration:underline;" class="'.$sDomColor.'" href="'.$aUrl['sUser'].'&sSearchType=sAccount&sSearch='.$aRows['sAccount'].'">'.$aRows['sAccount'].'</a>';
		}

		$aReturn['aData']['aJackpotSet'] = GameSet_Class::fnCallJackpotSet($nGame);

		$aReturn['aData'] = $aReturnData;

		echo json_encode($aReturn);
	}
	exit;
?>