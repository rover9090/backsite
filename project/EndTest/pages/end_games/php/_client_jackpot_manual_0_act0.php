<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_jackpot_manual.php');
	#require end

	#參數接收區
	$nId		= filter_input_int('nId',			INPUT_REQUEST,0);
	$nMoney	= filter_input_int('nMoney',			INPUT_REQUEST,0);
	$nGame	= filter_input_str('nGame',			INPUT_REQUEST,'1001');
	$sMemo	= filter_input_str('sMemo',			INPUT_REQUEST,'');
	$nType1	= filter_input_int('nType1',			INPUT_REQUEST,-1);
	$nType3	= filter_input_int('nType3',			INPUT_REQUEST,-1);
	#參數結束

	#參數宣告區
	$aData = array();
	$aMemberData = array();
	$aEditLog = array(
		CLIENT_JACKPOT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sErrMsg = '';
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$nJid = 0;
	$nLogType = 0;
	#宣告結束

	#程式邏輯區


	if ($aJWT['a'] == 'INS')
	{
		$oPdo->beginTransaction();

		$nId = $aJWT['nId'];# 這個是nJid

		if($nMoney <= 0)
		{
			$nErr = 1;
			$sErrMsg .= aMANUAL['MONEYBIGGERZERO'].'<div class="MarginBottom10"></div>';
		}
		else
		{
			$sSQL = '	SELECT	nId,
							nGame,
							nMoney0 as nMoney
					FROM		'. CLIENT_JACKPOT_SET .'
					WHERE		nId = :nId
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_query($Result);
			$aJackpotData = $Result->fetch(PDO::FETCH_ASSOC);

			if($aJackpotData !== false)
			{
				if($nType3 == 2 && $aJackpotData['nMoney'] - $nMoney < 0)
				{
					$nErr = 1;
					$sErrMsg .= aMANUAL['MONEYTOOMUCH'].'<div class="MarginBottom10"></div>';
				}
			}
			else
			{
				$nErr = 1;
				$sErrMsg .= NODATA.'<div class="MarginBottom10"></div>';
			}
		}

		if($nType1 == -1)
		{
			$nErr = 1;
			$sErrMsg .= aMANUAL['TITLEUNFILED'].'<div class="MarginBottom10"></div>';
		}

		if($nErr == 0)
		{
			$aSQL_Array = array(
				'nJid'			=> (int)	$nId,
				'nMoney'			=> (float)	$nMoney,
				'nStatus'			=> (int)	0,
				'nType0'			=> (int)	4,
				'nType1'			=> (int)	$nType1,
				'nType3'			=> (int)	$nType3,
				'sMemo'			=> (string)	$sMemo,
				'nCreateTime'		=> (int)	NOWTIME,
				'sCreateTime'		=> (string)	NOWDATE,
				'nUpdateTime'		=> (int)	NOWTIME,
				'sUpdateTime'		=> (string)	NOWDATE,
				'nCreateDay'		=> (int)	strtotime('today')
			);

			$sSQL = 'INSERT INTO '. CLIENT_JACKPOT_MONEY . ' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			//log ??
			$aEditLog[CLIENT_JACKPOT_MONEY]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_JACKPOT_MONEY]['aNew']['nId'] = $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$nId,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104801,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);

			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = INSV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

			$oPdo->commit();
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sErrMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'PASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nJid,
						nGame,
						nMoney,
						nStatus,
						nType1,
						nType3,
						nAdmin0,
						sMemo,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_JACKPOT_MONEY .'
				WHERE		nType0 = 4
				AND		nStatus = 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	Money_.nGame,
							Money_.nId,
							Money_.nMoney0 as nMoney
					FROM		'. CLIENT_JACKPOT_SET .' Money_
					WHERE		Money_.nOnline != 99
					AND		Money_.nId = :nId
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nJid'], PDO::PARAM_INT);
			sql_query($Result);
			$aJackpotData = $Result->fetch(PDO::FETCH_ASSOC);
			// echo '<pre>';print_r($aJackpotData);echo '</pre>';exit;

			if($aJackpotData === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
			else
			{
				$nBefore	= $aJackpotData['nMoney'];
				$nDelta 	= $aData['nMoney'];
				if($aData['nType3'] == 2)
				{
					$nLogType = 3;
					$nDelta *= -1;
				}
				else
				{
					$nLogType = 2;
				}

				$nAfter 	= $nBefore + $nDelta;

				if(true)
				{
					$aSQL_Array = array(
						'nMoney0' => $nAfter
					);
					$sSQL = '	UPDATE '. CLIENT_JACKPOT_SET .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nJid'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aAccLog = array(
						'nLottryId' 	=> (int)	$aData['nId'],
						'nGame' 		=> (int)	$aJackpotData['nGame'],
						'nJid' 		=> (int)	$aJackpotData['nId'],
						'nType0' 		=> (int)	$nLogType,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta,
						'nAfter' 		=> (float)	$nAfter,
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
					);
		
					$sSQL = 'INSERT INTO '. CLIENT_JACKPOT_LOG . ' ' . sql_build_array('INSERT', $aAccLog );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aAccLog);
					sql_query($Result);

					$aSQL_Array = array(
						'nStatus'			=> (int)	1,
						'nAdmin0'			=> (int)	$aAdm['nId'],
						'nUpdateTime'		=> (int)	NOWTIME,
						'sUpdateTime'		=> (string)	NOWDATE,
					);

					$sSQL = '	UPDATE '. CLIENT_JACKPOT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aEditLog[CLIENT_JACKPOT_MONEY]['aOld'] = $aData;
					$aEditLog[CLIENT_JACKPOT_MONEY]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_JACKPOT_MONEY]['aNew']['nId'] = $aData['nId'];

					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	$aData['nJid'],
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$aData['nId'],
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8104802,
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
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

					$oPdo->commit();
				}
				else
				{
					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = NODATA;
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
					$oPdo->rollback();
				}
			}
		}
	}

	if ($aJWT['a'] == 'DENY'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nJid,
						nMoney,
						nStatus,
						nType1,
						nAdmin0,
						sMemo,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_JACKPOT_MONEY .'
				WHERE		nType0 = 4
				AND		nStatus = 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_JACKPOT_MONEY]['aOld'] = $aData;

			$aSQL_Array = array(
				'nStatus'		=> (int) 99,
				'nAdmin0'		=> (int) $aAdm['nId'],
				'nUpdateTime'	=> (int) NOWTIME,
				'sUpdateTime'	=> (string) NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_JACKPOT_MONEY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_JACKPOT_MONEY]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int) $aAdm['nId'],
				'nWhom'		=> (int) $aData['nJid'],
				'sWhomAccount'	=> (string) '',
				'nKid'		=> (int) $nId,
				'sIp'			=> (string) USERIP,
				'nLogCode'		=> (int) 8104803,
				'sParam'		=> (string) json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int) 0,
				'nCreateTime'	=> (int) NOWTIME,
				'sCreateTime'	=> (string) NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

			$oPdo->commit();
		}
	}

	#程式邏輯結束
?>