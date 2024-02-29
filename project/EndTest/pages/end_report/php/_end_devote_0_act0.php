<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_devote.php');
	#require結束

	#參數接收區

	#參數結束

	#參數宣告區
	$aData = array();
	$aBetData = array();
	$aEditLog = array();
	$sSearchId = '0';
	$nLastSettleTime = 0;
	$nTotalMoney = 0;		#總貢獻
	$nTotalBetMoney = 0;	#總投注額

	$nErr = 0;
	$sMsg = '';
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		do {
			$sSQL = '	SELECT 	1
					FROM 	'.CLIENT_GAMES_NUMS.'
					WHERE nEndTime < 4
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nErr = 1;
				$sMsg = aDEVOTE['SETTLEERROR'];
				break;
			}
			$sSQL = '	SELECT 	nId,
							sEndTime
					FROM 	'.END_DEVOTE.'
					ORDER BY nId DESC
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nLastSettleTime = strtotime($aRows['sEndTime']);
			}

			// 取得貢獻加總
			$sSQL = '	SELECT 	sResult
					FROM 	'.CLIENT_GAMES_NUMS.'
					WHERE nEndTime > :nLastSettleTime';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLastSettleTime', $nLastSettleTime, PDO::PARAM_INT);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aResult = json_decode($aRows['sResult'],true);
				if ($aResult !== false && isset($aResult['Money1']))
				{
					$aResult = $aResult['Money1'];
				}
				else
				{
					$aResult = array();
				}

				foreach ($aResult as $LPsBankType => $LPaPlayer)
				{
					foreach ($LPaPlayer as $LPnMoney)
					{
						$nTotalMoney += abs($LPnMoney);
					}
				}
			}
			$nTotalMoney = $nTotalMoney * ($aSystem['aParam']['nCompanyLeast'] * 2) / 100;

			//注單
			$sSQL = '	SELECT 	nId,
							nLid,
							nMoney0
					FROM 	'.CLIENT_GAMES_DATA.'
					WHERE nDone = 1
					AND 	nStatus IN (0,1)
					AND 	nCreateTime > :nLastSettleTime';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLastSettleTime', $nLastSettleTime, PDO::PARAM_INT);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$nTotalBetMoney += $aRows['nMoney0'];
				$aBetData[$aRows['nId']] = $aRows;
				$sSearchId .= ','.$aRows['nLid'];
			}

			if ($nTotalMoney == 0 || $nTotalBetMoney == 0)
			{
				$nErr = 1;
				$sMsg = aDEVOTE['NOBET'];
				break;
			}

			// aLink nId => 一級代理uid
			$sSQL = '	SELECT 	nId,
							sLinkList
					FROM 	'.CLIENT_USER_LINK.'
					WHERE nId IN ('.$sSearchId.')';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aTmpLink = explode(',', $aRows['sLinkList']);
				$aLink[$aRows['nId']] = (int) array_shift($aTmpLink);
			}

			// 統計代理線投注額
			foreach ($aBetData as $LPaDetail)
			{
				$LPnAgent = $aLink[$LPaDetail['nLid']];
				if (!isset($aData[$LPnAgent]))
				{
					$aData[$LPnAgent] = 0;
				}
				$aData[$LPnAgent] += $LPaDetail['nMoney0'];
			}

			$aSQL_Array = array(
				'nMoney'		=> (float)	$nTotalMoney,
				'nBetMoney'		=> (float)	$nTotalBetMoney,
				'nDevote'		=> (float)	$aSystem['aParam']['nCompanyLeast'] * 2,
				'sStartTime'	=> (string)	date('Y-m-d H:i:s',$nLastSettleTime),
				'sEndTime'		=> (string)	NOWDATE,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			$sSQL = 'INSERT INTO '. END_DEVOTE .' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();
			$aEditLog[END_DEVOTE]['aNew'][$nLastId] = $aSQL_Array;

			foreach ($aData as $LPnAgent => $LPnTotalBet)
			{
				$LPnPercent = $LPnTotalBet / $nTotalBetMoney;
				$aSQL_Array = array(
					'nKid'		=> (int)	$nLastId,
					'nUid'		=> (int)	$LPnAgent,
					'nBetMoney'		=> (float)	$LPnTotalBet,
					'nMoney'		=> (float)	$nTotalMoney * $LPnPercent,
					'nPercent'		=> (float)	$LPnPercent,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				$sSQL = 'INSERT INTO '. END_DEVOTE_DETAIL .' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$LPnLastId = $oPdo->lastInsertId();

				$aEditLog[END_DEVOTE_DETAIL]['aNew'][$LPnLastId] = $aSQL_Array;
			}

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8108001,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$sMsg = INSV;

			// $sSQL = '	SELECT 	nId as nUid,
			// 				sAccount,
			// 				sSiteId
			// 		FROM 	'.CLIENT_USER_DATA.'
			// 		WHERE nOnline != 99';
			// $Result = $oPdo->prepare($sSQL);
			// sql_query($Result);
			// while ($LPaUser = $Result->fetch(PDO::FETCH_ASSOC))
			// {
			// 	$LPaAccLogArray = array(
			// 		'nUid' 		=> (int)	$LPaUser['nUid'],
			// 		'nKid' 		=> (int)	$LPaUser['nUid'],
			// 		'nType0' 		=> (int)	1,
			// 		'nType1' 		=> (int)	381,
			// 		'nType2' 		=> (int)	0,
			// 		'nBefore' 		=> (float)	0,
			// 		'nDelta' 		=> (float)	0,
			// 		'nAfter' 		=> (float)	0,
			// 		'sParams' 		=> (string)	'',
			// 		'nCreateTime' 	=> (int)	NOWTIME,
			// 		'sCreateTime' 	=> (string)	NOWDATE,
			// 		'nCreateDay' 	=> (int)	strtotime('today'),
			// 		'sSiteId'		=> (string)	$LPaUser['sSiteId'],
			// 		'sNoTag'		=> (string) '',
			// 	);
			// 	$LPaAccLogWaterArray = $LPaAccLogArray;
			// 	$LPaAccLogWaterArray['nType2'] = 1;
			// 	$oPdo->beginTransaction();
			// 	$sSQL = '	SELECT 	*
			// 			FROM  	'.CLIENT_USER_MONEY.'
			// 			WHERE 	nUid = :nUid
			// 			LIMIT 1
			// 			FOR UPDATE';
			// 	$Result1 = $oPdo->prepare($sSQL);
			// 	$Result1->bindValue(':nUid',$LPaUser['nUid'],PDO::PARAM_INT);
			// 	sql_query($Result1);
			// 	$aUserMoney = $Result1->fetch(PDO::FETCH_ASSOC);
			// 	$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPaUser['nUid']] = $aUserMoney;
			// 	$LPaAccLogArray['nBefore'] = $aUserMoney['nMoney'];
			// 	$LPaAccLogArray['nDelta'] = -1*$aUserMoney['nMoney'];
			// 	$LPaAccLogWaterArray['nBefore'] = $aUserMoney['nWater'];
			// 	$LPaAccLogWaterArray['nDelta'] = -1*$aUserMoney['nWater'];

			// 	if($aUserMoney !== false)
			// 	{
			// 		$aNewMoney = array(
			// 			'Money' => 0,
			// 			'Water' => 0
			// 		);
			// 		$aAccLogArray['nAfter'] = 0;
			// 		$aAccLogWaterArray['nAfter'] = 0;
			// 		$aSQL_Array = oTransfer::PointUpdate($LPaUser['nUid'],$aNewMoney);
			// 		if($aSQL_Array !== false)
			// 		{
			// 			$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
			// 					WHERE	nUid = :nUid LIMIT 1';
			// 			$Result1 = $oPdo->prepare($sSQL);
			// 			$Result1->bindValue(':nUid', $LPaUser['nUid'], PDO::PARAM_INT);
			// 			sql_build_value($Result1, $aSQL_Array);
			// 			sql_query($Result);
			// 			$oPdo->commit();

			// 			$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPaUser['nUid']] = $aSQL_Array;
			// 			// print_r($LPaAccLogArray);
			// 			// print_r($LPaAccLogWaterArray);
			// 			DoLogAcc($LPaAccLogArray);
			// 			DoLogAcc($LPaAccLogWaterArray);
			// 		}
			// 		else
			// 		{
			// 			$oPdo->commit();
			// 			$aActionLog = array(
			// 				'nWho'		=> (int)	$aAdm['nId'],
			// 				'nWhom'		=> (int)	$LPaUser['nUid'],
			// 				'sWhomAccount'	=> (string)	$LPaUser['sAccount'],
			// 				'nKid'		=> (int)	$LPaUser['nUid'],
			// 				'sIp'			=> (string)	USERIP,
			// 				'nLogCode'		=> (int)	8108011,
			// 				'sParam'		=> (string)	json_encode($aEditLog),
			// 				'nType0'		=> (int)	0,
			// 				'nCreateTime'	=> (int)	NOWTIME,
			// 				'sCreateTime'	=> (string)	NOWDATE,
			// 			);
			// 			DoActionLog($aActionLog);
			// 			// print_r($aActionLog);
			// 		}
			// 	}
			// }

			// $sSQL = '	SELECT 	nId,nMoney0
			// 		FROM  	'. CLIENT_JACKPOT_SET .'
			// 		WHERE 	nGame = 1001';
			// $Result = $oPdo->prepare($sSQL);
			// sql_query($Result);
			// $aJackpot = $Result->fetch(PDO::FETCH_ASSOC);

			// $aSQL_Array = array(
			// 	'nGame'		=> 1001,
			// 	'sNo'			=> '',
			// 	'nLottryId'		=> 0,
			// 	'nUid'		=> 0,
			// 	'nBefore'		=> $aJackpot['nMoney0'],
			// 	'nDelta'		=> -1 * $aJackpot['nMoney0'],
			// 	'nAfter'		=> 0,
			// 	'nType0'		=> 5,
			// 	'nCreateTime'	=> NOWTIME,
			// 	'sCreateTime'	=> NOWDATE,
			// );

			// $sSQL = 'INSERT INTO '. CLIENT_JACKPOT_LOG .' ' . sql_build_array('INSERT', $aSQL_Array );
			// $Result = $oPdo->prepare($sSQL);
			// sql_build_value($Result, $aSQL_Array);
			// sql_query($Result);

			// $aSQL_Array = array(
			// 	'nMoney0' 		=> 0,
			// 	'nUpdateTime'	=> NOWTIME,
			// 	'sUpdateTime'	=> NOWDATE,
			// );
			// $sSQL = '	UPDATE '. CLIENT_JACKPOT_SET . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
			// 		WHERE	nId = :nId LIMIT 1';
			// $Result = $oPdo->prepare($sSQL);
			// $Result->bindValue(':nId', $aJackpot['nId'], PDO::PARAM_INT);
			// sql_build_value($Result, $aSQL_Array);
			// sql_query($Result);
		} while (false);

		$aJumpMsg['0']['sTitle'] = ($nErr == 0) ? RIGHTMSG : ERRORMSG;
		$aJumpMsg['0']['sIcon'] = ($nErr == 0) ? 'success' : 'error';
		$aJumpMsg['0']['sMsg'] = $sMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_report/php/_end_devote_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	#程式邏輯結束
?>