<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_activity.php');
	#require結束

	#參數接收區
	$nLid			= filter_input_int('nLid',		INPUT_REQUEST,0);
	$nKid			= filter_input_int('nKid',		INPUT_POST,0);
	$nOnline		= filter_input_int('nOnline',		INPUT_POST,0);
	$sStartTime		= filter_input_str('sStartTime',	INPUT_POST,'');
	$nStartTime		= strtotime($sStartTime);
	$sEndTime		= filter_input_str('sEndTime',	INPUT_POST,'');
	$nEndTime		= strtotime($sEndTime);
	$nGive		= filter_input_int('nGive',		INPUT_POST,0);
	$nSave		= filter_input_int('nSave',		INPUT_POST,0);


	$aName 	= array();
	$aContent 	= array();
	$aOld = array();
	if(isset($_POST['sName0']))
	{
		$aName = $_POST['sName0'];
	}
	if(isset($_POST['sContent0']))
	{
		$aContent = $_POST['sContent0'];
	}
	#參數結束

	#參數宣告區
	$aId = array();
	$aData = array();
	$aEditLog = array(
		CLIENT_ACTIVITY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sMsg = '';
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		if($nGive <= 0)
		{
			$nErr = 1;
			$sMsg .= aERROR['GIVE'].'<br>'; // 儲值達標金額需大於0
		}
		if($nSave <= 0)
		{
			$nErr = 1;
			$sMsg .= aERROR['SAVE'].'<br>'; // 贈送金額需大於0
		}
		if($nEndTime <= $nStartTime)
		{
			$nErr = 1;
			$sMsg .= aERROR['TIME'].'<br>'; // 結束時間需大於開始時間
		}
		else
		{
			$sSQL = '	SELECT 	nStartTime,
							nEndTime
					FROM 		'. CLIENT_ACTIVITY .'
					WHERE 	nKid = :nKid
					GROUP BY	nLid';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nKid',$nKid,PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if( ($nStartTime >= $aRows['nStartTime'] && $nStartTime <= $aRows['nEndTime']) || ($nEndTime >= $aRows['nStartTime'] && $nEndTime <= $aRows['nEndTime']))
				{
					$nErr = 1;
					$sMsg .= aERROR['REPEAT'].'<br>'; // 相同類別活動時間不可重疊
				}
			}
		}
		
		if ($nErr == 1)
		{
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0_upt0.php']).'&nLid='.$nLid;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$nLid = 0;#開始預設 = 0;
			foreach(aLANG as $LPsLang => $LPsText)
			{
				$oPdo->beginTransaction();
				$aSQL_Array = array(
					'sName0'		=> (string) $aName[$LPsLang],
					'sContent0'		=> (string) $aContent[$LPsLang],
					'nGive'		=> (int) $nGive,
					'nSave'		=> (int) $nSave,
					'nKid'		=> (int) $nKid,
					'nLid'		=> (int) $nLid,
					'sLang'		=> (string) $LPsLang,
					'nOnline'		=> (int) $nOnline,
					'nCreateTime'	=> (int) NOWTIME,
					'sCreateTime'	=> (string) NOWDATE,
					'nUpdateTime'	=> (int) NOWTIME,
					'sUpdateTime'	=> (string) NOWDATE,
					'nStartTime'	=> (int) $nStartTime,
					'sStartTime'	=> (string) $sStartTime,
					'nEndTime'		=> (int) $nEndTime,
					'sEndTime'		=> (string) $sEndTime,
				);

				$sSQL = 'INSERT INTO '. CLIENT_ACTIVITY . ' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nLastId = $oPdo->lastInsertId();
				if($nLid == 0)
				{
					$nLid = $nLastId;

					# 更新 TW nLid
					$aSQL_Array = array(
						'nLid' => (int) $nLid,
					);
					$sSQL = '	UPDATE '. CLIENT_ACTIVITY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $nLid, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}

				#紀錄動作 - 新增
				$aEditLog[CLIENT_ACTIVITY]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_ACTIVITY]['aNew']['nId'] = $nLastId;
				$aActionLog = array(
					'nWho'		=> (int) $aAdm['nId'],
					'nWhom'		=> (int) 0,
					'sWhomAccount'	=> (string) '',
					'nKid'		=> (int) $nLastId,
					'sIp'			=> (string) USERIP,
					'nLogCode'		=> (int) 8104101,
					'sParam'		=> (string) json_encode($aEditLog),
					'nType0'		=> (int) 0,
					'nCreateTime'	=> (int) NOWTIME,
					'sCreateTime'	=> (string) NOWDATE,
				);
				DoActionLog($aActionLog);

				$oPdo->commit();
			}

			$aJumpMsg['0']['sMsg'] = INSV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

		}
	}

	if ($aJWT['a'] == 'UPT'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						sLang,
						nLid,
						sName0,
						sContent0,
						nKid,
						nOnline,
						nUpdateTime,
						sUpdateTime,
						nStartTime,
						sStartTime,
						nEndTime,
						sEndTime
				FROM 		'. CLIENT_ACTIVITY .'
				WHERE 	nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aId[$aRows['sLang']] = $aRows['nId'];
			$aData[$aRows['nId']] = $aRows;
			$aOld = $aRows;
		}

		if(empty($aData))
		{
			$nErr = 1;
			$sMsg = NODATA;
		}
		else
		{
			// 活動已開始
			if($aOld['nStartTime'] <= NOWTIME)
			{
				$nStartTime = $aOld['nStartTime'];
			}
			else if($nGive <= 0)
			{
				$nErr = 1;
				$sMsg .= aERROR['GIVE'].'<br>'; // 儲值達標金額需大於0
			}
			else if($nSave <= 0)
			{
				$nErr = 1;
				$sMsg .= aERROR['SAVE'].'<br>'; // 贈送金額需大於0
			}
			if($nEndTime <= $nStartTime)
			{
				$nErr = 1;
				$sMsg .= aERROR['TIME'].'<br>'; // 結束時間需大於開始時間
			}
			else
			{
				$sSQL = '	SELECT 	nStartTime,
								nEndTime
						FROM 		'. CLIENT_ACTIVITY .'
						WHERE 	nKid = :nKid
						AND		nLid != :nLid
						GROUP BY	nLid';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nKid',$nKid,PDO::PARAM_INT);
				$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);

				sql_query($Result);
				while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
				{
					if( ($nStartTime >= $aRows['nStartTime'] && $nStartTime <= $aRows['nEndTime']) || ($nEndTime >= $aRows['nStartTime'] && $nEndTime <= $aRows['nEndTime']))
					{
						$nErr = 1;
						$sMsg .= aERROR['REPEAT'].'<br>'; // 相同類別活動時間不可重疊
					}
				}
			}
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0_upt0.php']).'&nLid='.$nLid;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			foreach(aLANG as $LPsLang => $LPsText)
			{
				$oPdo->beginTransaction();

				$aSQL_Array = array(
					'sName0'		=> (string) $aName[$LPsLang],
					'nKid'		=> (int) $nKid,
					'nOnline'		=> (int) $nOnline,
					'sContent0'		=> (string) $aContent[$LPsLang],
					'nUpdateTime'	=> (int) NOWTIME,
					'sUpdateTime'	=> (string) NOWDATE,
					'nEndTime'		=> (int) $nEndTime,
					'sEndTime'		=> (string) $sEndTime,
				);

				// 活動尚未開始
				if($aData[$aId[$LPsLang]]['nStartTime'] > NOWTIME)
				{
					$aSQL_Array['nGive'] 		= (int) $nGive;
					$aSQL_Array['nSave'] 		= (int) $nSave;
					$aSQL_Array['nStartTime'] 	= (int) $nStartTime;
					$aSQL_Array['sStartTime'] 	= (string) $sStartTime;
				}

				if(isset($aId[$LPsLang]))
				{
					$sSQL = '	UPDATE '. CLIENT_ACTIVITY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aId[$LPsLang], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}
				//沒的話新增
				else
				{
					$aSQL_Array['nCreateTime']	= (int) NOWTIME;
					$aSQL_Array['sCreateTime']	= (string) NOWDATE;
					$aSQL_Array['sLang']		= (string) $LPsLang;
					$aSQL_Array['nLid']		= (int) $nLid;

					$sSQL = 'INSERT INTO '. CLIENT_ACTIVITY .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$aId[$LPsLang] = $oPdo->lastInsertId();
					$aData[$aId[$LPsLang]] = $aSQL_Array;
				}

				#紀錄動作 - 新增
				$aEditLog[CLIENT_ACTIVITY]['aOld'] = $aData[$aId[$LPsLang]];
				$aEditLog[CLIENT_ACTIVITY]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_ACTIVITY]['aNew']['nId'] = $aId[$LPsLang];
				$aActionLog = array(
					'nWho'		=> (int) $aAdm['nId'],
					'nWhom'		=> (int) 0,
					'sWhomAccount'	=> (string) '',
					'nKid'		=> (int) $aId[$LPsLang],
					'sIp'			=> (string) USERIP,
					'nLogCode'		=> (int) 8104102,
					'sParam'		=> (string) json_encode($aEditLog),
					'nType0'		=> (int) 0,
					'nCreateTime'	=> (int) NOWTIME,
					'sCreateTime'	=> (string) NOWDATE,
				);
				DoActionLog($aActionLog);

				$oPdo->commit();
			}

			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

		}
	}

	if ($aJWT['a'] == 'DEL'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						nLid,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'.CLIENT_ACTIVITY.'
				WHERE 	nOnline != 99
				AND 		nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['nId']] = $aRows;
		}

		if (empty($aData))
		{
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_ACTIVITY]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int) 99,
				'nUpdateTime'	=> (int) NOWTIME,
				'sUpdateTime'	=> (string) NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_ACTIVITY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nLid = :nLid ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_ACTIVITY]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int) $aAdm['nId'],
				'nWhom'		=> (int) 0,
				'sWhomAccount'	=> (string) '',
				'nKid'		=> (int) $nLid,
				'sIp'			=> (string) USERIP,
				'nLogCode'		=> (int) 8104103,
				'sParam'		=> (string) json_encode($aEditLog),
				'nType0'		=> (int) 0,
				'nCreateTime'	=> (int) NOWTIME,
				'sCreateTime'	=> (string) NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>