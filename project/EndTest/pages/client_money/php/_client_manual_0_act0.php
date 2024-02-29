<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_manual.php');
	#require end

	#參數接收區
	$nId		= filter_input_int('nId',			INPUT_REQUEST,0);
	$nMoney	= filter_input_int('nMoney',			INPUT_REQUEST,0);
	$sAccount	= filter_input_str('sAccount',		INPUT_REQUEST,'');
	$sMemo	= filter_input_str('sMemo',			INPUT_REQUEST,'');
	$nType1	= filter_input_int('nType1',			INPUT_REQUEST,-1);
	$nType3	= filter_input_int('nType3',			INPUT_REQUEST,-1);
	#參數結束

	#參數宣告區
	$aData = array();
	$aMemberData = array();
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sErrMsg = '';
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$nUid = 0;
	$nLogType = 0;
	#宣告結束

	#程式邏輯區


	if ($aJWT['a'] == 'INS')
	{
		$oPdo->beginTransaction();

		$nUid = $aJWT['nUid'];


		if($nMoney <= 0)
		{
			$nErr = 1;
			$sErrMsg .= aMANUAL['MONEYBIGGERZERO'].'<div class="MarginBottom10"></div>';
		}
		else if($nType3 != 1 && $nType3 !== 2)
		{
			$nErr = 1;
			$sErrMsg .= aMANUAL['TYPE3UNFILED'];
		}
		else
		{
			$sSQL = '	SELECT	nUid,
							nMoney,
							nMoneyTime,
							sMoneyKey
					FROM		'. CLIENT_USER_MONEY .'
					WHERE		nUid = :nUid
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			if($aMemberData !== false)
			{
				if($nType3 == 2 && $aMemberData['nMoney'] - $nMoney < 0)
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
				'nUid'			=> (int)	$nUid,
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

			$sSQL = 'INSERT INTO '. CLIENT_MONEY . ' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			//log ??
			$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$nUid,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8107501,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

			$oPdo->commit();
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sErrMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'PASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nStatus,
						nType1,
						nType3,
						nAdmin0,
						sMemo,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 4
				AND		nStatus = 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		// echo '<pre>';print_r($aData);echo '</pre>';exit;

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	User_.nId,
							User_.sAccount,
							Money_.nMoney
					FROM		'. CLIENT_USER_DATA .' User_,
							'. CLIENT_USER_MONEY .' Money_
					WHERE		User_.nOnline != 99
					AND		User_.nId = :nId
					AND		User_.nId = Money_.nUid
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nUid'], PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			if($aMemberData === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
			else
			{
				$nBefore	= $aMemberData['nMoney'];
				$nDelta 	= $aData['nMoney'];
				if($aData['nType3'] == 2)
				{
					$nLogType = 206;
					$nDelta *= -1;
				}
				else
				{
					$nLogType = 205;
				}

				$nAfter 	= $nBefore + $nDelta;

				$aNewMoney = array(
					'Money' => (float) $nAfter,
				);

				$aSQL_Array = oTransfer::PointUpdate($aData['nUid'],$aNewMoney,1,true);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nUid = :nUid LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $aData['nUid'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aAccLog = array(
						'nUid' 		=> (int)	$aData['nUid'],
						'nKid' 		=> (int)	$aData['nId'],
						'nType0' 		=> (int)	2,
						'nType1' 		=> (int)	$nLogType,
						'nType2' 		=> (int)	0,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta,
						'nAfter' 		=> (float)	$nAfter,
						'sParams' 		=> (string)	'',
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
						'nCreateDay' 	=> (int)	strtotime('today'),
					);
					DoLogAcc($aAccLog);

					$aSQL_Array = array(
						'nStatus'			=> (int)	1,
						'nAdmin0'			=> (int)	$aAdm['nId'],
						'nUpdateTime'		=> (int)	NOWTIME,
						'sUpdateTime'		=> (string)	NOWDATE,
					);

					$sSQL = '	UPDATE '. CLIENT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aEditLog[CLIENT_MONEY]['aOld'] = $aData;
					$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $aData['nId'];

					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	$aData['nUid'],
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$aData['nId'],
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8107502,
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
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

					$oPdo->commit();
				}
				else
				{
					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = NODATA;
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
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
						nUid,
						nMoney,
						nStatus,
						nType1,
						nAdmin0,
						sMemo,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_MONEY]['aOld'] = $aData;

			$aSQL_Array = array(
				'nStatus'		=> (int) 99,
				'nAdmin0'		=> (int) $aAdm['nId'],
				'nUpdateTime'	=> (int) NOWTIME,
				'sUpdateTime'	=> (string) NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_MONEY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int) $aAdm['nId'],
				'nWhom'		=> (int) $aData['nUid'],
				'sWhomAccount'	=> (string) '',
				'nKid'		=> (int) $nId,
				'sIp'			=> (string) USERIP,
				'nLogCode'		=> (int) 8107503,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

			$oPdo->commit();
		}
	}

	#程式邏輯結束
?>