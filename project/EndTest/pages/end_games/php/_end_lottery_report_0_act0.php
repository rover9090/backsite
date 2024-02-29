<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_lottery_report.php');
	#require結束

	// echo '<pre>';print_r($aJWT);echo '</pre>';
	// echo '<pre>';print_r($_REQUEST);echo '</pre>';
	// exit;
	#參數接收區
	$nGame	= filter_input_int('nGame',		INPUT_POST,'1001');
	$sNo		= filter_input_str('sNo',		INPUT_POST,'');
	$nResult1	= filter_input_int('nResult1',	INPUT_POST,0);
	$nResult2	= filter_input_int('nResult2',	INPUT_POST,0);
	$nResult3	= filter_input_int('nResult3',	INPUT_POST,0);
	$nResult0	= filter_input_int('nResult0',	INPUT_POST,0);# 是否中彩金
	// $sNums	= $_POST['sNums'];
	#參數結束

	#參數宣告區
	$aOldNums = array();
	$aBindArray = array();
	$aLottery = array();
	$aOldMoney = array();
	$nErr = 0;
	$sMsg = '';

	$aEditLog = array(
		CLIENT_GAMES_NUMS	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_GAMES_DATA	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_USER_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$aResult = array();
	$aNums = array();
	#宣告結束

	// echo '<pre>';

	// print_r($_POST);
	#程式邏輯區

	# 帳務還原
	if ($aJWT['a'] == 'LOTTERYRESTORE' && $aJWT['sNo'] == $sNo)
	{
		do{

			$sSQL = '	SELECT 	nId,
							nGame,
							sNo,
							nStatus,
							nStartTime,
							sStartTime,
							nEndTime,
							sEndTime,
							nEndTime1,
							sResult
					FROM 		'. CLIENT_GAMES_NUMS .'
					WHERE 	nGame = :nGame
					AND		nStatus IN (4,7)
					AND		sNo LIKE :sNo
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',	$nGame,	PDO::PARAM_INT);
			$Result->bindValue(':sNo',	$sNo,		PDO::PARAM_STR);
			sql_query($Result);
			$aOldNums = $Result->fetch(PDO::FETCH_ASSOC);

			if($aOldNums === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = aMsg['NODATA'].'1';
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				break;
			}

			// if(empty($sResult) || $sResult == '')
			// {
			// 	$aJumpMsg['0']['sTitle'] = ERRORMSG;
			// 	$aJumpMsg['0']['sIcon'] = 'error';
			// 	$aJumpMsg['0']['sMsg'] = aMsg['RESULTERR'];
			// 	$aJumpMsg['0']['sShow'] = 1;
			// 	$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
			// 	$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			// 	break;
			// }

			$aEditLog[CLIENT_GAMES_NUMS]['aOld'] = $aOldNums;

			$aJackpot = array();
			$aLottery = array();
			$aUids = array();
			$sSQL = '	SELECT	nId,
							nUid,
							nDelta,
							nType1,
							nType2
					FROM		'.END_LOG_ACCOUNT.'
					WHERE		nCreateTime = :nCreateTime
					AND		sNoTag = :sNoTag
					AND		nType3 = 2';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nCreateTime', $aOldNums['nEndTime1'],PDO::PARAM_STR);
			$Result->bindValue(':sNoTag', $aOldNums['nGame'].'_'.$aOldNums['sNo'],PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if (!isset($aLottery[$aRows['nUid']]))
				{
					$aLottery[$aRows['nUid']] = array(
						'nId'		=> $aRows['nId'],
						'sId'		=> '0',
						'nMoney0'	=> 0,
						'nWater0'	=> 0,
						'aAll'	=> array(),
					);
				}
				$aLottery[$aRows['nUid']]['aAll'][$aRows['nId']] = $aRows;
				if($aRows['nType2'] === '1' && ($aRows['nType1'] === '305' || $aRows['nType1'] === '306'))
				{					
					$aLottery[$aRows['nUid']]['nWater0'] += $aRows['nDelta'];
				}
				else
				{
					$aLottery[$aRows['nUid']]['nMoney0'] += $aRows['nDelta'];
				}
				
				if($aRows['nType1'] === '309')
				{
					$aJackpot['nMoney9'] = $aRows['nDelta'];
				}
			}

			// $sUids = implode(',', $aUids);
			// if(empty($aUids))
			// {
			// 	$sUids = 0;
			// }

			// // 檢查變動會員的餘額
			// $sSQL = '   SELECT      nUid,
			// 				nMoney
			// 		FROM        '.CLIENT_USER_MONEY.'
			// 		WHERE       nUid IN ('.$sUids.')';
			// $Result = $oPdo->prepare($sSQL);
			// sql_query($Result);
			// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			// {
			// 	if($aLottery[$aRows['nUid']]['nMoney0'] > $aRows['nMoney'])
			// 	{
			// 		$aLottery[$aRows['nUid']]['nMoney0'] = $aRows['nMoney'];
			// 	}
			// }

			# 會員反款 #
			foreach ($aLottery as $LPnUid => $LPaMoney)
			{
				// if($LPaMoney['nMoney0'] <= 0)
				// {
				// 	continue;
				// }
				$nBefore	= 0;
				$nDelta	= 0;
				$nAfter	= 0;
				if($LPaMoney['nType2'] === '1' && ($LPaMoney['nType1'] === '305' || $LPaMoney['nType1'] === '306'))
				{
					$nType2 = 1;
				}
				else
				{
					$nType2 = 0;
				}

				$oPdo->beginTransaction();

				// 更改金額時才需要拉client_user_data表
				// if( $LPaMoney['nMoney0'] > 0 )
				// {
					$sSQL = '	SELECT 	nId
							FROM		'. CLIENT_USER_DATA .'
							WHERE		nId = :nId
							LIMIT		1
							FOR		UPDATE' ;
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId',$LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						error_log('index:670 查無會員 uid='.$LPnUid);
						continue;
					}

					$sSQL = '   SELECT      nUid,
									nMoney,
									nWater
							FROM        '.CLIENT_USER_MONEY.'
							WHERE       nUid = :nUid
							LIMIT		1
							FOR         UPDATE';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aOldMoney = $Result->fetch(PDO::FETCH_ASSOC);

					if($aOldMoney === false)
					{
						$oPdo->rollback();
						error_log('repost:210 查無會員 uid='.$LPnUid);
						continue;
					}
					$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPnUid] = $aOldMoney;

					if($nType2 === 1)
					{
						$nBefore	= (float) $aOldMoney['nWater'];
						$nDelta	= (float) $LPaMoney['nWater0'];
					}
					else
					{
						$nBefore	= (float) $aOldMoney['nMoney'];
						$nDelta	= (float) $LPaMoney['nMoney0'];
					}
					if($nDelta > 0)
					{
						$nAfter	= $nBefore - $nDelta;
					}
					else
					{
						$nAfter	= $nBefore + $nDelta;
					}

					# 補錢 #
					if($nType2 === 1)
					{
						$aNewMoney = array(
							'Water' => (float) $nAfter,
						);
					}
					else
					{
						$aNewMoney = array(
							'Money' => (float) $nAfter,
						);
					}
					$aSQL_Array = oTransfer::PointUpdate($LPnUid,$aNewMoney,1,true);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nUid = :nUid LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);

						// $aAccLog = array(
						// 	'nUid' 		=> (int)	$LPnUid,
						// 	'nKid' 		=> (int)	$LPaMoney['nId'],
						// 	'nType0' 		=> (int)	1,
						// 	'nType1' 		=> (int)	391,
						// 	'nType2' 		=> (int)	$nType2,
						// 	'nType3' 		=> (int)	3,
						// 	'nBefore' 		=> (float)	$nBefore,
						// 	'nDelta' 		=> (float)	$nDelta * -1,
						// 	'nAfter' 		=> (float)	$nAfter,
						// 	'sParams' 		=> (string)	json_encode($LPaMoney),
						// 	'nCreateTime' 	=> (int)	NOWTIME,
						// 	'sCreateTime' 	=> (string)	NOWDATE,
						// 	'nCreateDay' 	=> (int)	strtotime('today'),
						// 	'sNoTag'		=> (string)$aOldNums['nGame'].'_'.$aOldNums['sNo'],
						// );
						// DoLogAcc($aAccLog);
					}					
					else
					{
						echo 'false';exit;
					}

					$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPnUid] = $aSQL_Array;

					// 注單改為未過帳
					$aSQL_Array = array(
						'nDone' 	  => 0,
						'nStatus' 	  => 0,
						'nMoney1' 	  => 0,
					);
					$sSQL = '	UPDATE 	'.CLIENT_GAMES_DATA.'
							SET 		' . sql_build_array('UPDATE', $aSQL_Array).'
							WHERE		nGame = :nGame
							AND		sNo LIKE :sNo
							AND		nDone = 1';

					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aSQL_Array = array(
						'nDone' 	  => 0,
						'nMoney1' 	  => 0,
					);
					$sSQL = '	UPDATE 	'.CLIENT_GAMES_BANKER.'
							SET 		' . sql_build_array('UPDATE', $aSQL_Array).'
							WHERE		nGame = :nGame
							AND		sNo LIKE :sNo';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$oPdo->commit();
				// }
				$nBeforeMoney = $aOldMoney['nMoney'];
				$nBeforeWater = $aOldMoney['nWater'];
				foreach($LPaMoney['aAll'] as $LPnKid => $LPaDetail)
				{
					$nDelta = $LPaDetail['nDelta'];
					if($LPaDetail['nType2'] === '1' && ($LPaDetail['nType1'] === '305' || $LPaDetail['nType1'] === '306'))
					{
						$nBefore = $nBeforeWater;
					}
					else
					{
						$nBefore = $nBeforeMoney;
					}
					if($nDelta > 0)
					{
						$nAfter	= $nBefore - $nDelta;
					}
					else
					{
						$nAfter	= $nBefore + $nDelta;
					}
					$nAfter = $nBefore + $nDelta;
					$aAccLog = array(##############################
						'nUid' 		=> (int)	$LPnUid,
						'nKid' 		=> (int)	$LPnKid,
						'nType0' 		=> (int)	1,
						'nType1' 		=> (int)	391,
						'nType2' 		=> (int)	$LPaDetail['nType2'],
						'nType3' 		=> (int)	3,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta * -1,
						'nAfter' 		=> (float)	$nAfter,
						'sParams' 		=> (string)	json_encode($LPaDetail),
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
						'nCreateDay' 	=> (int)	strtotime('today'),
						'sNoTag'		=> (string)$aOldNums['nGame'].'_'.$aOldNums['sNo'],
					);
					DoLogAcc($aAccLog);
				}
			}

			# 重過帳 #
			$aGameNums_SQL_Array = array(
				'nEndTime'		=> NOWTIME,
				'sEndTime'		=> NOWDATE,
				'nStatus'		=> 6,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',  $aOldNums['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);

			#紀錄動作 - 新增
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'] = $aGameNums_SQL_Array;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew']['nId'] = $aOldNums['nId'];
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aOldNums['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104408,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
		
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}while(false);
	}

	if ($aJWT['a'] == 'LOTTERYRESETTLE' && $aJWT['sNo'] == $sNo)
	{
		$aJs = array(
			'plugins/js/end_games/run_page_settle.js',
		);
		$aValue = array(
			'a'	=> 'SETTLE',
			't'	=> NOWTIME,
		);
		$aPhpData = array(
			'sPhpJWT' => sys_jwt_encode($aValue),
			'sPhpUrl' => './Bot/settle.php',
			'nPhpGame' => $nGame,
			'nPhpNoId' => 0,
			'sPhpLocation' => sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']),
		);
		
		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nEndTime,
						sEndTime,
						nEndTime1,
						sResult
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				AND		nStatus = 6
				AND		sNo LIKE :sNo
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',	$nGame,	PDO::PARAM_INT);
		$Result->bindValue(':sNo',	$sNo,		PDO::PARAM_STR);
		sql_query($Result);
		$aOldNums = $Result->fetch(PDO::FETCH_ASSOC);

		if($aOldNums === false)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aMsg['NODATA'].'1';
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aPhpData['nPhpNoId'] = $aOldNums['nId'];
			echo '<script>';
			foreach($aPhpData as $LPsPhpKey => $LPsPhpData)
			{
				echo 'var '.$LPsPhpKey.' = \''.$LPsPhpData.'\';';
			}
			echo '</script>';
		}	
	}

	# 還原並重過帳
	if ($aJWT['a'] == 'LOTTERYREPORT' && $aJWT['sNo'] == $sNo)
	{
		do{

			$sSQL = '	SELECT 	nId,
							nGame,
							sNo,
							nStatus,
							nStartTime,
							sStartTime,
							nEndTime,
							sEndTime,
							nEndTime1,
							sResult
					FROM 		'. CLIENT_GAMES_NUMS .'
					WHERE 	nGame = :nGame
					AND		nStatus IN (4,7)
					AND		sNo LIKE :sNo
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',	$nGame,	PDO::PARAM_INT);
			$Result->bindValue(':sNo',	$sNo,		PDO::PARAM_STR);
			sql_query($Result);
			$aOldNums = $Result->fetch(PDO::FETCH_ASSOC);

			if($aOldNums === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = aMsg['NODATA'].'1';
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				break;
			}

			// if(empty($sResult) || $sResult == '')
			// {
			// 	$aJumpMsg['0']['sTitle'] = ERRORMSG;
			// 	$aJumpMsg['0']['sIcon'] = 'error';
			// 	$aJumpMsg['0']['sMsg'] = aMsg['RESULTERR'];
			// 	$aJumpMsg['0']['sShow'] = 1;
			// 	$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
			// 	$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			// 	break;
			// }

			$aEditLog[CLIENT_GAMES_NUMS]['aOld'] = $aOldNums;

			$aJackpot = array();
			$aLottery = array();
			$aUids = array();
			$sSQL = '	SELECT	nId,
							nUid,
							nDelta,
							nType1,
							nType2
					FROM		'.END_LOG_ACCOUNT.'
					WHERE		nCreateTime = :nCreateTime
					AND		sNoTag = :sNoTag
					AND		nType3 = 2';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nCreateTime', $aOldNums['nEndTime1'],PDO::PARAM_STR);
			$Result->bindValue(':sNoTag', $aOldNums['nGame'].'_'.$aOldNums['sNo'],PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if (!isset($aLottery[$aRows['nUid']]))
				{
					$aLottery[$aRows['nUid']] = array(
						'nId'		=> $aRows['nId'],
						'sId'		=> '0',
						'nMoney0'	=> 0,
						'nWater0'	=> 0,
						'aAll'	=> array(),
					);
				}
				$aLottery[$aRows['nUid']]['aAll'][$aRows['nId']] = $aRows;
				if($aRows['nType2'] === '1' && ($aRows['nType1'] === '305' || $aRows['nType1'] === '306'))
				{					
					$aLottery[$aRows['nUid']]['nWater0'] += $aRows['nDelta'];
				}
				else
				{
					$aLottery[$aRows['nUid']]['nMoney0'] += $aRows['nDelta'];
				}
				
				if($aRows['nType1'] === '309')
				{
					$aJackpot['nMoney9'] = $aRows['nDelta'];
				}
			}

			// $sUids = implode(',', $aUids);
			// if(empty($aUids))
			// {
			// 	$sUids = 0;
			// }

			// // 檢查變動會員的餘額
			// $sSQL = '   SELECT      nUid,
			// 				nMoney
			// 		FROM        '.CLIENT_USER_MONEY.'
			// 		WHERE       nUid IN ('.$sUids.')';
			// $Result = $oPdo->prepare($sSQL);
			// sql_query($Result);
			// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			// {
			// 	if($aLottery[$aRows['nUid']]['nMoney0'] > $aRows['nMoney'])
			// 	{
			// 		$aLottery[$aRows['nUid']]['nMoney0'] = $aRows['nMoney'];
			// 	}
			// }

			# 會員反款 #
			foreach ($aLottery as $LPnUid => $LPaMoney)
			{
				// if($LPaMoney['nMoney0'] <= 0)
				// {
				// 	continue;
				// }
				$nBefore	= 0;
				$nDelta	= 0;
				$nAfter	= 0;
				if($LPaMoney['nType2'] === '1' && ($LPaMoney['nType1'] === '305' || $LPaMoney['nType1'] === '306'))
				{					
					$nType2 = 1;
				}
				else
				{
					$nType2 = 0;
				}

				$oPdo->beginTransaction();

				// 更改金額時才需要拉client_user_data表
				// if( $LPaMoney['nMoney0'] > 0 )
				// {
					$sSQL = '	SELECT 	nId
							FROM		'. CLIENT_USER_DATA .'
							WHERE		nId = :nId
							LIMIT		1
							FOR		UPDATE' ;
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId',$LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						error_log('index:670 查無會員 uid='.$LPnUid);
						continue;
					}

					$sSQL = '   SELECT      nUid,
									nMoney,
									nWater
							FROM        '.CLIENT_USER_MONEY.'
							WHERE       nUid = :nUid
							LIMIT		1
							FOR         UPDATE';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aOldMoney = $Result->fetch(PDO::FETCH_ASSOC);

					if($aOldMoney === false)
					{
						$oPdo->rollback();
						error_log('repost:210 查無會員 uid='.$LPnUid);
						continue;
					}
					$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPnUid] = $aOldMoney;

					if($nType2 === 1)
					{
						$nBefore	= (float) $aOldMoney['nWater'];
						$nDelta	= (float) $LPaMoney['nWater0'];
					}
					else
					{
						$nBefore	= (float) $aOldMoney['nMoney'];
						$nDelta	= (float) $LPaMoney['nMoney0'];
					}
					if($nDelta > 0)
					{
						$nAfter	= $nBefore - $nDelta;
					}
					else
					{
						$nAfter	= $nBefore + $nDelta;
					}

					if($nType2 === 1)
					{
						$aNewMoney = array(
							'Water' => (float) $nAfter,
						);
					}
					else
					{
						$aNewMoney = array(
							'Money' => (float) $nAfter,
						);
					}
					# 補錢 #
					
					$aSQL_Array = oTransfer::PointUpdate($LPnUid,$aNewMoney,1,true);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nUid = :nUid LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);

						// $aAccLog = array(
						// 	'nUid' 		=> (int)	$LPnUid,
						// 	'nKid' 		=> (int)	$LPaMoney['nId'],
						// 	'nType0' 		=> (int)	1,
						// 	'nType1' 		=> (int)	391,
						// 	'nType2' 		=> (int)	$nType2,
						// 	'nType3' 		=> (int)	3,
						// 	'nBefore' 		=> (float)	$nBefore,
						// 	'nDelta' 		=> (float)	$nDelta * -1,
						// 	'nAfter' 		=> (float)	$nAfter,
						// 	'sParams' 		=> (string)	json_encode($LPaMoney),
						// 	'nCreateTime' 	=> (int)	NOWTIME,
						// 	'sCreateTime' 	=> (string)	NOWDATE,
						// 	'nCreateDay' 	=> (int)	strtotime('today'),
						// 	'sNoTag'		=> (string)$aOldNums['nGame'].'_'.$aOldNums['sNo'],
						// );
						// DoLogAcc($aAccLog);
					}
					else
					{
						echo 'false';exit;
					}

					$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPnUid] = $aSQL_Array;

					// 注單改為未過帳
					$aSQL_Array = array(
						'nDone' 	  => 0,
						'nStatus' 	  => 0,
						'nMoney1' 	  => 0,
					);
					$sSQL = '	UPDATE 	'.CLIENT_GAMES_DATA.'
							SET 		' . sql_build_array('UPDATE', $aSQL_Array).'
							WHERE		nGame = :nGame
							AND		sNo LIKE :sNo
							AND		nDone = 1';

					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aSQL_Array = array(
						'nDone' 	  => 0,
						'nMoney1' 	  => 0,
					);
					$sSQL = '	UPDATE 	'.CLIENT_GAMES_BANKER.'
							SET 		' . sql_build_array('UPDATE', $aSQL_Array).'
							WHERE		nGame = :nGame
							AND		sNo LIKE :sNo';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					$Result->bindValue(':sNo', $sNo, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$oPdo->commit();
				// }
				$nBeforeMoney = $aOldMoney['nMoney'];
				$nBeforeWater = $aOldMoney['nWater'];
				foreach($LPaMoney['aAll'] as $LPnKid => $LPaDetail)
				{
					$nDelta = $LPaDetail['nDelta'];
					if($LPaDetail['nType2'] === '1' && ($LPaDetail['nType1'] === '305' || $LPaDetail['nType1'] === '306'))
					{
						$nBefore = $nBeforeWater;
					}
					else
					{
						$nBefore = $nBeforeMoney;
					}
					if($nDelta > 0)
					{
						$nAfter	= $nBefore - $nDelta;
					}
					else
					{
						$nAfter	= $nBefore + $nDelta;
					}
					$nAfter = $nBefore + $nDelta;
					$aAccLog = array(#################################
						'nUid' 		=> (int)	$LPnUid,
						'nKid' 		=> (int)	$LPnKid,
						'nType0' 		=> (int)	1,
						'nType1' 		=> (int)	391,
						'nType2' 		=> (int)	$LPaDetail['nType2'],
						'nType3' 		=> (int)	3,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta * -1,
						'nAfter' 		=> (float)	$nAfter,
						'sParams' 		=> (string)	json_encode($LPaDetail),
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
						'nCreateDay' 	=> (int)	strtotime('today'),
						'sNoTag'		=> (string)$aOldNums['nGame'].'_'.$aOldNums['sNo'],
					);
					DoLogAcc($aAccLog);
				}
			}

			# 重過帳 #
			$aResult = array(
				'Z' => $nResult0,
				'A' => $nResult1,
				'B' => $nResult2,
				'C' => $nResult3,
			);
			$sJsonResult = json_encode($aResult);
			$aGameNums_SQL_Array = array(
				'nEndTime'		=> NOWTIME,
				'sEndTime'		=> NOWDATE,
				'sNums'		=> $sJsonResult,
				'sResult'		=> $sJsonResult,
				'nStatus'		=> 6,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',  $aOldNums['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);

			#紀錄動作 - 新增
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'] = $aGameNums_SQL_Array;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew']['nId'] = $aOldNums['nId'];
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aOldNums['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104408,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
		
			$aJs = array(
				'plugins/js/end_games/run_page_settle.js',
			);
			$aValue = array(
				'a'	=> 'SETTLE',
				't'	=> NOWTIME,
			);
			$aPhpData = array(
				'sPhpJWT' => sys_jwt_encode($aValue),
				'sPhpUrl' => './Bot/settle.php',
				'nPhpGame' => $nGame,
				'nPhpNoId' => 0,
				'sPhpLocation' => sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']),
			);
			$aPhpData['nPhpNoId'] = $aOldNums['nId'];
			echo '<script>';
			foreach($aPhpData as $LPsPhpKey => $LPsPhpData)
			{
				echo 'var '.$LPsPhpKey.' = \''.$LPsPhpData.'\';';
			}
			echo '</script>';
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']);
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}while(false);
	}
	#程式邏輯結束
?>