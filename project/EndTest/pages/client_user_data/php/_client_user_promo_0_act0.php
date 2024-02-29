<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_promo.php');


	$nId				= filter_input_int('nId',		INPUT_REQUEST, 0);
	$sPromoCode 		= filter_input_str('sPromoCode',	INPUT_POST, '');
	$nType 			= filter_input_int('nType',		INPUT_POST, 1); # 1隨機產生 0手動輸入
	$nOnline 			= filter_input_int('nOnline',		INPUT_POST, 0);
	$sWater			= filter_input_str('sWater',		INPUT_POST, '', 20);
	$sPercent			= filter_input_str('sPercent',	INPUT_POST, '', 20);

	$nErr = 0;
	$sMsg = '';
	$aOld = array();

	$sWater = round($sWater,2); // 最多小數兩位

	if ($aJWT['a'] == 'INS')
	{
		# 隨機產生 先幫他產生 8碼 sPromoCode
		if ($nType == 1)
		{
			do
			{
				$sPromoCode = '';
				for ($i = 0; $i < 8; $i++)
				{
					// $sTempWords = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					$sTempWords = '1234567890';
					$sPromoCode .= $sTempWords[mt_rand(0, 9)];
				}

				$sSQL = ' SELECT 1 FROM '.CLIENT_USER_PROMO.' WHERE sPromoCode = :sPromoCode AND nOnline != 99 LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':sPromoCode', $sPromoCode, PDO::PARAM_STR);
				sql_query($Result);
				$nCount = $Result->rowCount();
			}
			while ($nCount != 0);
		}
		else
		{
			# 檢查 sPromoCode 是否重複
			$sSQL = '	SELECT 	1
					FROM 		'.CLIENT_USER_PROMO.'
					WHERE 	nOnline != 99
					AND 		sPromoCode LIKE :sPromoCode
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sPromoCode', $sPromoCode, PDO::PARAM_STR);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nErr = 1;
				$sMsg = aPROMO['PROMOCODEEXISTED'].'<br>';
			}
		}

		if(!preg_match('/^.[A-Za-z0-9]+$/i', $sPromoCode) || strlen($sPromoCode) < 3)
		{
			$nErr = 1;
			$sMsg = aPROMO['PROMOCODEERROR'].'<br>';
		}


		$sSQL = '	SELECT 	nUid,
						sWater,
						sPercent
				FROM  	'.CLIENT_USER_FS.'
				WHERE 	nUid = :nUid
				AND 		nEndTime = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aAdm['nUid'], PDO::PARAM_INT);
		sql_query($Result);
		$nCount = $Result->rowCount();
		if($nCount == 0)
		{
			$nErr = 1;
			$sMsg = aPROMO['FSERROR'].'<br>';
		}
		else
		{
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if($sWater > $aRows['sWater'])
				{
					$sWater = $aRows['sWater'];
					$nErr = 11;
				}
			}
		}


		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0_upt0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'nUid'		=> $aAdm['nUid'],
				'sPromoCode'	=> $sPromoCode,
				'nOnline'		=> $nOnline,
				'nUsedTimes'	=> 0,
				'nCreateTime'	=> NOWTIME,
				'sCreateTime'	=> NOWDATE,
				'nUpdateTime'	=> NOWTIME,
				'sUpdateTime'	=> NOWDATE,
			);
			$sSQL = 'INSERT INTO '.CLIENT_USER_PROMO.' ' . sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nPid = $oPdo->lastInsertId();

			$aEditLog[CLIENT_USER_PROMO]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_USER_PROMO]['aNew']['nId'] = $nPid;

			if($sPercent === '') $sPercent = 100;
			if($sWater === '') $sWater = 100;
			$aSQL_Array = array(
				'nPid'		=> (int) $nPid,
				'sWater'		=> (string) $sWater,
				'sPercent'		=> (string) $sPercent,
				'nCreateTime'	=> NOWTIME,
				'sCreateTime'	=> NOWDATE,
			);

			$sSQL = 'INSERT INTO '.CLIENT_USER_PROMO_FS.' ' . sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_USER_PROMO_FS]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_USER_PROMO_FS]['aNew']['nId'] = $nPid;

			# 紀錄動作 - 新增
			$aSQL_Array = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$nId,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8103201,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aSQL_Array);

			if($nErr == 11)
			{
				$aJumpMsg['0']['sMsg'] = INSV.'<br/>'.aPROMO['UPPERLIMIT'];
			}
			else
			{
				$aJumpMsg['0']['sMsg'] = INSV;
			}

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = INSV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{
		# 只修改上下線
		$sSQL = '	SELECT 	nId,
						nOnline
				FROM 		'.CLIENT_USER_PROMO.'
				WHERE 	nOnline != 99
				AND 		nId = :nId
				AND 		nUid = :nUid
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
		sql_query($Result);
		$aOldData = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aOldData === false)
		{
			$nErr = 1;
			$sMsg = NODATA.'<br>';
		}
		$aEditLog[CLIENT_USER_PROMO]['aOld'] = $aOldData;

		$sSQL = '	SELECT 	nId,
						sWater
				FROM  	'.CLIENT_USER_PROMO_FS.'
				WHERE nPid = :nPid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nPid',$nId, PDO::PARAM_INT);
		sql_query($Result);
		$aOldFs = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aOldFs === false)
		{
			$nErr = 1;
			$sMsg = NODATA.'<br>';
		}
		$aEditLog[CLIENT_USER_PROMO]['aOld'] = $aOldFs;

		$sSQL = '	SELECT 	nUid,
						sWater,
						sPercent
				FROM  	'.CLIENT_USER_FS.'
				WHERE 	nUid = :nUid
				AND 		nEndTime = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aAdm['nUid'], PDO::PARAM_INT);
		sql_query($Result);
		$nCount = $Result->rowCount();
		if($nCount == 0)
		{
			$nErr = 1;
			$sMsg = aPROMO['FSERROR'].'<br>';
		}
		else
		{
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if($sWater > $aRows['sWater'])
				{
					$sWater = $aRows['sWater'];
					$nErr = 11;
				}				
			}
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0_upt0.php']).'&nId='.$nId;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'nOnline'		=> (int) $nOnline,
				'nUpdateTime'	=> (int) NOWTIME,
				'sUpdateTime'	=> (string) NOWDATE,
			);
			$sSQL = '	UPDATE	'.CLIENT_USER_PROMO.'
					SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
					WHERE		nId = :nId
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$aEditLog[CLIENT_USER_PROMO]['aNew'] = $aOldFs;

			if($sWater != $aOldFs['sWater'])
			{
				$aSQL_Array = array(
					'sWater'		=> (string) $sWater,
				);
				$sSQL = '	UPDATE	'.CLIENT_USER_PROMO_FS.'
						SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
						WHERE		nId = :nId
						LIMIT 	1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aOldFs['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
			}

			# 紀錄動作 - 新增
			$aSQL_Array = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$aAdm['nUid'],
				'sWhomAccount'	=> (string)	$aAdm['sAccount'],
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8103202,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aSQL_Array);

			if($nErr == 11)
			{
				$aJumpMsg['0']['sMsg'] = UPTV.'<br/>'.aPROMO['UPPERLIMIT'];
			}
			else
			{
				$aJumpMsg['0']['sMsg'] = UPTV;
			}

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						nUid,
						nOnline
				FROM 		'. CLIENT_USER_PROMO .'
				WHERE 	nOnline != 99
				AND 		nId = :nId';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOldData[$aRows['nId']] = $aRows;
		}

		if (empty($aOldData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_CLIENT_USER_PROMO_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_USER_PROMO]['aOld'] = $aOldData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_USER_PROMO . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_USER_PROMO]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$aOld['nUid'],
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8103203,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
?>