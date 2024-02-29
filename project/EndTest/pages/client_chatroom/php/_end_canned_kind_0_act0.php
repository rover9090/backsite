<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_canned_kind.php');
	#require

	#參數接收區
	$nLid		= filter_input_int('nLid',	INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline',	INPUT_POST, 1);
	$aName 	= array();
	if(isset($_POST['sName0']))
	{
		$aName = $_POST['sName0'];
	}
	#參數結束

	#參數宣告區
	$aOldData = array();
	$aData = array();
	$aEditLog = array(
		END_CANNED_KIND	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{

		$nLid = 0;
		foreach(aLANG as $LPsLang => $LPsText)
		{
			$oPdo->beginTransaction();

			$aSQL_Array = array(
				'sName0'		=> (string)	$aName[$LPsLang],
				'nLid'		=> (int)	$nLid,
				'sLang'		=> (string)	$LPsLang,
				'nOnline'		=> (int)	$nOnline,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
			$sSQL = 'INSERT INTO '. END_CANNED_KIND .' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();
			$aEditLog[END_CANNED_KIND]['aNew'] = $aSQL_Array;
			$aEditLog[END_CANNED_KIND]['aNew']['nId'] = $nLastId;
			if ($nLid == 0)
			{
				$nLid = $nLastId;
				# update
				$aSQL_Array = array(
					'nLid' => $nLid,
				);
				$sSQL = '	UPDATE '. END_CANNED_KIND .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $nLid, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
			}

			//log
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

			$oPdo->commit();
		}

		$aJumpMsg['0']['sTitle'] = RIGNTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = INSV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_kind_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'UPT'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						sLang,
						sName0,
						nOnline
				FROM 		'. END_CANNED_KIND .'
				WHERE 	nLid = :nLid
				AND 		nOnline != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOldData[$aRows['sLang']] = $aRows;
		}
		if (empty($aOldData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_kind_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			foreach(aLANG as $LPsLang => $LPsText)
			{
				$aEditLog[END_CANNED_KIND] = array(
					'aOld' => array(),
					'aNew' => array(),
				);

				$oPdo->beginTransaction();
				$aSQL_Array = array(
					'sName0'		=> (string)	$aName[$LPsLang],
					'nOnline'		=> (int)	$nOnline,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);
				if(isset($aOldData[$LPsLang]))
				{
					$sSQL = '	UPDATE	'. END_CANNED_KIND .'
							SET		' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE		nId = :nId
							AND 		nOnline != 99
							LIMIT 	1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aOldData[$LPsLang]['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$aEditLog[END_CANNED_KIND]['aOld'] = $aOldData[$LPsLang];
					$aOldData[$aRows['sLang']]['nId'] = $aOldData[$LPsLang]['nId'];
				}
				else
				{
					$aSQL_Array['nCreateTime']	= (int)	NOWTIME;
					$aSQL_Array['sCreateTime']	= (string)	NOWDATE;
					$aSQL_Array['sLang']		= (string)	$LPsLang;
					$aSQL_Array['nLid']		= (int)	$nLid;

					$sSQL = 'INSERT INTO '. END_CANNED_KIND .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nLastId = $oPdo->lastInsertId();
					$aSQL_Array['nId'] = $nLastId;
					$aOldData[$aRows['sLang']]['nId'] = $nLastId;
				}

				#紀錄動作 - 更新
				$aEditLog[END_CANNED_KIND]['aNew'] = $aSQL_Array;
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aOldData[$aRows['sLang']]['nId'],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8108002,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
				$oPdo->commit();
			}

			$aJumpMsg['0']['sTitle'] = RIGNTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_kind_0.php']);
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
				FROM 		'. END_CANNED_KIND .'
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
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_kind_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[END_CANNED_KIND]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_CANNED_KIND .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nLid = :nLid AND nOnline != 99';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[END_CANNED_KIND]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8108003,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGNTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_kind_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>