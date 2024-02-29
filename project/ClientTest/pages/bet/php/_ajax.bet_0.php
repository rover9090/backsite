<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	// $nType1 = filter_input_int('nCommission', INPUT_POST, 0);# 0正常 1免傭 吃nType1
	$nBetGroup = filter_input_int('nBetGroup', INPUT_POST, 0);# 0正常 1免傭 吃nType1
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');
	// $sPlay = filter_input_str('sPlay', INPUT_REQUEST, '');
	// $sPlay = $_POST['sPlay'];
	// $aPlay = json_decode($sPlay,true);
	$aBetData = array();
	$aBetMsg = array(
		'A' => '',
		'B' => '',
		'C' => '',
	);
	if(isset($_POST['aBetData']))
	{
		$aBetData = $_POST['aBetData'];
	}
	#參數結束

	#給此頁使用的url
	#url結束


	#參數宣告區 - declare
	$aReturnBetData = array();
	$aGameSetting = array();
	$aOdds = array();
	$nAllBetMoney = 0;
	$nNowGameBet = 0;
	$sContent = '';
	$aBetCount = array();
	$aBetMoney = array();
	$aSelfBetMoney = array();
	$aBetGroup = array();
	$aPerLink = array();
	$aSampleBet = array(
		'A' => 0,
		'B' => 0,
		'C' => 0,
	);
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();

	/**
	 * 回傳陣列 JSON
	 * @var Int nError
	 * 	回傳狀態值
	 * 	0 => 正常 其餘待補
	 * @var String sMsg
	 * 	回傳訊息
	 * @var Array aData
	 * 	回傳陣列
	 * @var Int nAlertType
	 * 	回傳訊息提示類型
	 * 	0 => 不需提示框
	 * @var String sUrl
	 * 	回傳後導頁檔案
	 */
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> '',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
		'sTime'		=> date('H:i:s'),
		'nType0'		=> 0,
		'aUser'		=> $aUser,
		'aSocketMsg'	=> array(),
	);
	#宣告結束


	#程式邏輯區
	if($aUser === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}
	
	if(empty($aBetData))
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['EMPTYBET'];
	}

	if($aJWT['a'] == 'RETURN')
	{
		$aReturn['nError'] = 1;
	}

	if($aJWT['a'] == 'BET')
	{
		$nUid = $aUser['nUid'];
		$aMember = $aUser;

		$aOdds['A']['nRoundMaxBet'] = 0;
		$aOdds['B']['nRoundMaxBet'] = 0;
		$aOdds['C']['nRoundMaxBet'] = 0;

		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];

		$aGameSetting['nCloseRoom'] = 0;
		if($aGameSetting['sOpenTime'] !== $aGameSetting['sCloseTime'])
		{
			$nOpenTime = strtotime($aGameSetting['sOpenTime']);
			$nCloseTime = strtotime($aGameSetting['sCloseTime']);
			if($nCloseTime < NOWTIME)
			{
				$nOpenTime += 86400;
				$nCloseTime += 86400;
			}
			if($nOpenTime > $nCloseTime)
			{
				$nOpenTime -= 86400;
			}
			$aGameSetting['nOpenTime'] = $nOpenTime;
			$aGameSetting['nCloseTime'] = $nCloseTime;
			$aGameSetting['nCloseRoom'] = ($nOpenTime > NOWTIME)?1:0;
		}
		if($aGameSetting['nMaintenance'] == '1') $aGameSetting['nCloseRoom'] = 1;

		$sSQL = '	SELECT 	sCode,
						nMaxBet,
						nMinBet,
						nRoundMaxBet
				FROM 		'.END_METHOD_SETTING.'
				WHERE 	nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOdds[$aRows['sCode']] = $aRows;
		}

		$sSQL = '	SELECT 	nId,
						sMethod,
						sName0,
						nMoney0,
						nMoney1
				FROM  	'.END_GAMES_GROUP.'
				WHERE 	nLid = :nId
				AND		nOnline = 1
				AND		sLang = :sLang';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nBetGroup, PDO::PARAM_INT);
		$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aBetGroup[$aRows['sMethod']] = $aRows;
		}

		if($nGame > 2000)
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS2.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nUse = 1
					AND		nPauseTime1 > :NOWTIME';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							sLimit,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nStatus < 3
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}

		$sSQL = '	SELECT 	nId,
						sNo,
						nUid,
						nStatus,
						nMoney0,
						nMoney1,
						nType0,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid) as sName0
				FROM  	'.CLIENT_GAMES_BANKER.'
				WHERE 	nType0 = 0
				AND		sNo = :sNo
				AND		nStatus = 1
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		$aBanker = $Result->fetch(PDO::FETCH_ASSOC);

		if($aGame === false)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOROUND'];
		}
		// else if($aGame['nBetEndTime'] >= NOWTIME && ($aGame['nBetEndTime']-$aGameSetting['nSec2']) <= NOWTIME)
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = str_replace('[:SEC:]',$aGameSetting['nSec2'],aACTMSG['CLOSING']);
		// }
		else if($aBanker['nUid'] === $aUser['nId'])
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['BANKERDONOT'];
		}
		else if($aGame['nStartTime'] >= NOWTIME || ($aGame['nBetEndTime'] !== '0' && $aGame['nBetEndTime'] < NOWTIME) || $aGame['nStatus'] !== '1')
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOTNOW'];
		}
		else if($aGameSetting['nCloseRoom'] == 1)# && $aUser['nTest'] == 0
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MAINTENANCE'];
		}
		else if($aUser['nStatus'] > 0)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MEMBEROFFLINE'];
		}
		else
		{
			$aEditLog = array(
				// CLIENT_JACKPOT_SET => array(
				// 	'aNew' => array(),
				// 	'aOld' => array(),
				// ),
				CLIENT_GAMES_DATA => array(
					'aNew' => array(),
				),
				CLIENT_USER_MONEY => array(
					'aNew' => array(),
					'aOld' => array(),
				),
			);
			$nLid = 0;
			$nFsId = 0;
			$sPerLink = 0;
			$sSQL = '	SELECT	nId
					FROM		'. CLIENT_USER_LINK .'
					WHERE		nUid = :nUid
					AND		nEndTime = 0
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aMember['nId'],PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);

			if($aRows !== false)
			{
				$nLid = $aRows['nId'];
			}
			else
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aBET['LINKERR'];
			}

			$sSQL = '	SELECT	nId,sPercentLink
					FROM		'.CLIENT_USER_FS.'
					WHERE		nUid = :nUid
					AND		nEndTime = 0
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aMember['nId'],PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			$sPerLink = $aRows['sPercentLink'];

			if($aRows !== false)
			{
				$nFsId = $aRows['nId'];
			}
			else
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aBET['FSERR'];
			}

			// if(isset($_COOKIE['sUserData']))
			// {
			// 	$aUserJWT = $oJWT->validToken($_COOKIE['sUserData']);
			// 	$aTempPerLink = explode(',',$aUserJWT['sPerLink']);
			// 	$aPerLink[0] = (float)isset($aTempPerLink[0])?$aTempPerLink[0]:0;
			// 	$aPerLink[1] = (float)isset($aTempPerLink[1])?$aTempPerLink[1]:0;
			// 	$aPerLink[2] = (float)isset($aTempPerLink[2])?$aTempPerLink[2]:0;
			// 	$sPerLink = implode(',',$aPerLink);
			// }

			if(!oTransfer::PointCheck($aMember['nId']))
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aACTMSG['MONEYERR'];
			}
			# 檢查限額
			$nNowGameBet = 0;
			$nSelfNowGameBet = 0;
			$aBetCount = $aSampleBet;
			$aBetMoney = $aSampleBet;
			$aSelfBetMoney = $aSampleBet;

			
			// 讀檔
			$aJsonMsg = array();
			if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
			{
				$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
				$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
			}
			
			$oPdo->beginTransaction();
			$sSQL = '	SELECT 	nId,
							sNo,
							nMoney0,
							nDone,
							sContent0,
							nUid
					FROM 		'.CLIENT_GAMES_DATA.'
					WHERE 	nGame = :nGame
					AND		sNo = :sNo
					AND		nStatus != 3
					FOR UPDATE';# AND		nUid = :nUid
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			// $Result->bindValue(':nUid', $aUser['nUid'],PDO::PARAM_INT);
			$Result->bindValue(':sNo', $aGame['sNo'], PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$nAllBetMoney += $aRows['nMoney0'];			// 當天已投注金額
				if($aRows['sNo'] == $aGame['sNo'])
				{
					$nNowGameBet += $aRows['nMoney0'];		// 單局已投注金額
					$aBetCount[$aRows['sContent0']] ++;
					$aBetMoney[$aRows['sContent0']] += $aRows['nMoney0'];
					if($aRows['nUid'] == $aUser['nUid'])
					{
						$nSelfNowGameBet += $aRows['nMoney0'];		// 單人單局已投注金額
						$aSelfBetMoney[$aRows['sContent0']] += $aRows['nMoney0'];
					}
				}
			}

			$nSumMoney = 0;
			foreach($aBetData as $LPsCode => $LPnMoney)
			{
				$bBetPass = true;
				$LPaOdds = $aOdds;
				
				if($LPnMoney <= 0 || !is_numeric($LPnMoney))
				{
					$aReturn['nError'] = 1;
					$aReturn['sMsg'] = aBET['MONEYERROR'];
					break;
				}

				if($aMember['nMoney'] < $LPnMoney )
				{
					$aReturn['nError'] = 1;
					$aReturn['sMsg'] = aBET['NOMONEY'];
					break;
				}

				// $LPaOdds['A']['nRoundMaxBet'] += $aBetMoney['A'];
				// $LPaOdds['B']['nRoundMaxBet'] += $aBetMoney['B'];
				// $LPaOdds['C']['nRoundMaxBet'] += $aBetMoney['C'];

				if ($aSystem['aParam']['nMinBet'] > $LPnMoney) # 最低單注
				{
					$aReturn['nError'] = 1;
					$aReturn['sMsg'] = aBET['MINBET'].$aSystem['aParam']['nMinBet'];
					break;
				}

				if($aGame['sLimit'] == $LPsCode && ($aBetMoney[$LPsCode] + $LPnMoney) > $aGameSetting['nLimitBet'])
				{
					$aReturn['nError'] = 9;
					$aBetData[$LPsCode] = $aGameSetting['nLimitBet'] - $aBetMoney[$LPsCode];
					$LPnMoney = $aBetData[$LPsCode];
					if($LPnMoney > 0)
					{
						$aReturn['sMsg'] .= str_replace('[:DOOR:]',aMETHODNAME[$LPsCode],aACTMSG['OVERBET']);
					}
					else
					{
						$aReturn['sMsg'] .= str_replace('[:CODE:]',aMETHODNAME[$LPsCode],aACTMSG['BETFAIL']);
					}
					// break;
				}

				// if ( ($aBetMoney[$LPsCode] + $LPnMoney) > $aBetGroup[$LPsCode]['nMoney1'] )# 最高單局投注
				// {
				// 	$bBetPass = false;
				// 	$sMsg = str_replace('[:MAXGAME:]',$aBetGroup[$LPsCode]['nMoney1'],aBET['MAXGAMEBET']);
				// 	$sMsg = str_replace('[:METHOD:]',aMETHODNAME[$LPsCode],$sMsg);
				// 	$nMaxPerGame = $aBetGroup[$LPsCode]['nMoney1'] - $aBetMoney[$LPsCode];

				// 	if($nMaxPerGame < 0)
				// 	{
				// 		$nMaxPerGame = 0;
				// 	}

				// 	$sMsg = str_replace('[:GAMELEFT:]',$nMaxPerGame,$sMsg);
				// 	$aReturn['nError'] = 1;
				// 	$aReturn['sMsg'] = $sMsg;
				// 	break;
				// }
				
				if ($aSystem['aParam']['nMaxPerBet'] < ($aSelfBetMoney[$LPsCode] + $LPnMoney)) # 單人每門最高單注金額
				{
					$aReturn['aBetGroup'] = $aBetGroup;
					$aReturn['nError'] = 9;
					$sMsg = str_replace('[:MAXGAME:]',$aSystem['aParam']['nMaxPerBet'],aBET['MAXBET']);
					$aBetData[$LPsCode] = $aSystem['aParam']['nMaxPerBet'] - $aSelfBetMoney[$LPsCode];
					$LPnMoney = $aBetData[$LPsCode];
					$nMaxPerBet = $aSystem['aParam']['nMaxPerBet'] - $aSelfBetMoney[$LPsCode];

					if($nMaxPerBet < 0)
					{
						$nMaxPerBet = 0;
					}

					$sMsg = str_replace('[:GAMELEFT:]',$nMaxPerBet,$sMsg);
					
					$aReturn['sMsg'] = $sMsg;
					// break;
				}

				if($LPnMoney <= 0)
				{
					unset($aBetData[$LPsCode]);
					continue;
				}
				
				$aMember['nMoney'] -= $LPnMoney;
				$nSumMoney += $LPnMoney;
			}

			if ( ($nSelfNowGameBet + $nSumMoney) > $aSystem['aParam']['nMaxPerGame'] )# 單人單局最高投注金額
			{
				$bBetPass = false;
				$sMsg = str_replace('[:MAXGAME:]',$aSystem['aParam']['nMaxPerGame'],aBET['MAXGAMEBET']);
				$nMaxPerGame = $aSystem['aParam']['nMaxPerGame'] - $nSelfNowGameBet;

				if($nMaxPerGame < 0)
				{
					$nMaxPerGame = 0;
				}

				$sMsg = str_replace('[:GAMELEFT:]',$nMaxPerGame,$sMsg);
				$aReturn['nError'] = 1;
				// $aBetData[$LPsCode] = $nMaxPerGame;
				// $LPnMoney = $aBetData[$LPsCode];
				$aReturn['sMsg'] = $sMsg;
			}

			if ( ($nSelfNowGameBet + $nSumMoney) > $aSystem['aParam']['nMaxPerDay'] ) # 單人單日最高投注金額
			{
				$bBetPass = false;
				$sMsg = str_replace('[:MAXDAY:]',$aSystem['aParam']['nMaxPerDay'],aBET['MAXDAYBET']);
				$nMaxPerDay = $aSystem['aParam']['nMaxPerDay'] - $nSelfNowGameBet;

				if($nMaxPerDay < 0)
				{
					$nMaxPerDay = 0;
				}
				$sMsg = str_replace('[:DAYLEFT:]',$nMaxPerDay,$sMsg);
				$aReturn['nError'] = 1;
				// $aBetData[$LPsCode] = $nMaxPerDay;
				// $LPnMoney = $aBetData[$LPsCode];
				$aReturn['sMsg'] = $sMsg;
			}			

			do
			{
				// $sSQL = '	SELECT 	nId,nMoney0
				// 		FROM  	'. CLIENT_JACKPOT_SET .'
				// 		WHERE 	nGame = :nGame
				// 		LIMIT 1
				// 		FOR UPDATE';
				// $Result = $oPdo->prepare($sSQL);
				// $Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
				// sql_query($Result);
				// $aJackpot = $Result->fetch(PDO::FETCH_ASSOC);
				// $aEditLog[CLIENT_JACKPOT_SET]['aOld'][$aJackpot['nId']] = $aJackpot;
				
				$sSQL = '	SELECT 	nId,nMoney
						FROM  	'.CLIENT_USER_MONEY.'
						WHERE 	nUid = :nUid
						LIMIT 	1
						FOR 		UPDATE';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $nUid,PDO::PARAM_INT);
				sql_query($Result);
				$aMoneyData = $Result->fetch(PDO::FETCH_ASSOC);
				$aEditLog[CLIENT_USER_MONEY]['aOld'][$aMoneyData['nId']] = $aMoneyData;
				if($aUser['nMoney'] > $aMoneyData['nMoney'])
				{
					$aReturn['nError'] = 1;
					$aReturn['sMsg'] = aBET['MONEYERROR'];
					break;
				}
				$aMember['nMoney'] = $aMoneyData['nMoney'];
				$aMember['nTempMoney'] = $aMoneyData['nMoney'];

				if($aReturn['nError'] != 1 && $aMember['nMoney'] > 0)
				{
					$aNewMoney = array(
						'Money' => (float) $aMember['nMoney'] - $nSumMoney
					);
					$aSQL_Array = oTransfer::PointUpdate($aMember['nId'],$aNewMoney);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nId = :nId LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nId', $aMoneyData['nId'], PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);
						$aEditLog[CLIENT_USER_MONEY]['aNew'][$aMoneyData['nId']] = $aSQL_Array;
					}

					// $aSQL_Array = array(
					// 	'nMoney0' 		=> $aJackpot['nMoney0'] + $nSumMoney * $aGameSetting['nWaterLeast'] / 100,
					// 	'nUpdateTime'	=> NOWTIME,
					// 	'sUpdateTime'	=> NOWDATE,
					// );
					// $sSQL = '	UPDATE '. CLIENT_JACKPOT_SET . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					// 		WHERE	nId = :nId LIMIT 1';
					// $Result = $oPdo->prepare($sSQL);
					// $Result->bindValue(':nId', $aJackpot['nId'], PDO::PARAM_INT);
					// sql_build_value($Result, $aSQL_Array);
					// sql_query($Result);
					// $aEditLog[CLIENT_JACKPOT_SET]['aNew'][$aJackpot['nId']] = $aSQL_Array;

					$sBetMsg = '';
					foreach($aBetData as $LPsCode => $LPnMoney)
					{
						$sBetMsg .= '<div class=" JqBetMsg">'.aBET[$LPsCode].'[<i class="FontGreen fas fa-check"></i>'.aBET['SUCCESS'].']'.$LPnMoney.'</div>';
						// $sBetMsg .= aBET[$LPsCode].'['.aBET['SUCCESS'].']'.$LPnMoney.'<br>';

						$aSQL_Array = array(
							'nNumsId'		=> (int)	$aGame['nId'],
							'nUid'		=> (int)	$aMember['nId'],
							'nGame'		=> (int)	$nGame,
							'nLid'		=> (int)	$nLid,
							'nFsId'		=> (int)	$nFsId,
							'sNo'			=> (string)	$aGame['sNo'],
							'nType0'		=> (int)	1,
							'nGroup0'		=> (int)	$nBetGroup,
							'nMoney0'		=> (float)	$LPnMoney,
							'sContent0'		=> (string)	$LPsCode,
							'nCreateTime'	=> (int)	NOWTIME,
							'sCreateTime'	=> (string)	NOWDATE,
							'nUpdateTime'	=> (int)	NOWTIME,
							'sUpdateTime'	=> (string)	NOWDATE,
							'nCreateDay'	=> (int)	strtotime('today'),
							'sPerLink'		=> (string) $sPerLink,
							'sSiteId'		=> aSITE['NAME'],
						);

						$sSQL = 'INSERT INTO '. CLIENT_GAMES_DATA .' ' . sql_build_array('INSERT', $aSQL_Array );
						$Result = $oPdo->prepare($sSQL);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);
						$nLastId = $oPdo->lastInsertId();
						$LPnMoney = (float)$LPnMoney;
						$aReturnBetData[$nLastId] = array('sContent0'=>aBET[$LPsCode],'nMoney0'=>(float)$LPnMoney,'sStatus'=>aBET['SUCCESS']);
						$aEditLog[CLIENT_GAMES_DATA]['aNew'][$nLastId] = $aSQL_Array;
						// $sBetMsg .= '<div class="FontBlue JqBetMsg">'.aBET[$LPsCode].'[<i class="FontGreen fas fa-check"></i>'.aBET['SUCCESS'].']'.$LPnMoney.'</div>';

						$aJsonMsg[$aGame['nBetEndTime']*10000]['aBet'][$LPsCode][$nLastId]['sName0'] = $aUser['sName0'];
						$aJsonMsg[$aGame['nBetEndTime']*10000]['aBet'][$LPsCode][$nLastId]['nMoney0'] = $LPnMoney;
						$aSQL_Array = array(
							'nUid'		=> (int)	$aMember['nId'],
							'nKid'		=> (int)	$nLastId,
							'nType0'		=> (int)	1,
							'nType1'		=> (int)	301,
							'nType2'		=> (int)	0,
							'nType3'		=> (int)	1,
							'nBefore'		=> (float)	$aMember['nMoney'],
							'nDelta'		=> (float)	$LPnMoney * -1,
							'nAfter'		=> (float)	$aMember['nMoney'] - $LPnMoney,
							'sParams'		=> (string)	'',
							'nCreateTime'	=> (int)	NOWTIME,
							'sCreateTime'	=> (string)	NOWDATE,
							'nCreateDay'	=> (int)	strtotime('today'),
							'sSiteId'		=> (string)	$aUser['sSiteId'],
							'sNoTag'		=> (string) $nGame.'_'.$aGame['sNo'],
						);
						DoLogAcc($aSQL_Array);

						$aActionLog = array(
							'nWho'		=> (int)	$aUser['nId'],
							'nWhom'		=> (int)	$aUser['nId'],
							'sWhomAccount'	=> (string)	$aUser['sAccount'],
							'nKid'		=> (int)	0,
							'sIp'			=> (string)	USERIP,
							'nLogCode'		=> (int)	7101001,
							'sParam'		=> (string)	json_encode($aEditLog),
							'nType0'		=> (int)	0,
							'nCreateTime'	=> (int)	NOWTIME,
							'sCreateTime'	=> (string)	NOWDATE,
						);
						DoActionLog($aActionLog);

						$aMember['nMoney'] -= $LPnMoney;
						$aReturn['aUser']['nMoney'] = $aNewMoney['Money'];

						// $aSQL_Array = array(
						// 	'nJid'		=> $aJackpot['nId'],
						// 	'nGame'		=> $nGame,
						// 	'nUid'		=> $aMember['nUid'],
						// 	'sNo'			=> $aGame['sNo'],
						// 	'nLottryId'		=> $nLastId,
						// 	'nBefore'		=> $aJackpot['nMoney0'],
						// 	'nDelta'		=> $LPnMoney * $aGameSetting['nWaterLeast'] / 100,
						// 	'nAfter'		=> $aJackpot['nMoney0'] + ($LPnMoney * $aGameSetting['nWaterLeast'] / 100),
						// 	'nType0'		=> 0,
						// 	'nCreateTime'	=> NOWTIME,
						// 	'sCreateTime'	=> NOWDATE,
						// );
						// $sSQL = 'INSERT INTO '. CLIENT_JACKPOT_LOG .' ' . sql_build_array('INSERT', $aSQL_Array );
						// $Result = $oPdo->prepare($sSQL);
						// sql_build_value($Result, $aSQL_Array);
						// sql_query($Result);
						// $aJackpot['nMoney0'] += $aSQL_Array['nDelta'];
					}
					
					$oPdo->commit();
				}
				else
				{
					$oPdo->rollBack();
				}
				

				// $aReturn['aData']['aBetCount'] = $aBetCount;
				// $aReturn['aData']['aBetMoney'] = $aBetMoney;
				$aReturn['aData']['aBetData'] = $aReturnBetData;

				if($aReturn['nError'] != 1)
				{
					// $aReturn['sMsg'] = aBET['BETSUCCESS'];					
					$aReturn['sMsg'] .= $sBetMsg;
					$nMicroTime = (float)microtime(true);

					if(true)
					{
						$aJsonMsg[$nMicroTime*10000] = array(
							'nRefresh' => '1',
							'nUid' => 0,
							'sMsg' => '<div class="betmsg font-xxs msgMember">
										<div class="coverbox">
											<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
											<div class="td float-left ifmeright msgMemberInf">
												<div class="dtbox">
													<div class="dt-nickname">系統機器人</div>
												</div>
												<div class="betmsgBlock arrow">
													<div class="betinfobox">
														<div class="betinfo">
															<div>'.$aMember['sName0'].$sBetMsg.'</div>
														</div>
													</div>
												</div>
												<div class="dt-time">
													<div class="dt-timeInner">'.NOWCLOCK.'</div>
												</div>
											</div>
										</div>
									</div>',
						);
	
						$nMsgCount = count($aJsonMsg);
						while($nMsgCount > 100)
						{
							$nMsgCount--;
							unset($aJsonMsg[key($aJsonMsg)]);
							
							if($nMsgCount< 0)
							{
								trigger_error('break');
								break;
							}
						}	
						$sJsonMsg = json_encode($aJsonMsg);
						$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
						file_put_contents($sFile, $sJsonMsg,LOCK_EX);

						// $aReturn['aSocketMsg'][] = $aJsonMsg[$nMicroTime*10000];
						$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000];
						if($aBanker['nUid'] == $aUser['nId'])
						{
							$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000+1];
						}
						$sSocketMsg = json_encode($aSocketMsg);
						socket_connect($oSocket,$sServerIp,$nPort);
						if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
							trigger_error('訊息發送失敗:'.$sSocketMsg);
						}
						socket_close($oSocket);
					}
				}
			}while(0);
		}
		
	}	
	
	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>