<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_chat_filter.php');
	#require??

	#參數接收區
	$nId		= filter_input_int('nId',	INPUT_REQUEST,0);
	$sName0	= filter_input_str('sName0',	INPUT_POST, '');
	$nOnline	= filter_input_int('nOnline',	INPUT_POST, 1);
	#參數結束

	#參數宣告區
	$aData = array();
	$aEditLog = array(
		END_CHAT_FILTER	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);

	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{

		$oPdo->beginTransaction();
		$aSQL_Array = array(
			'sName0'		=> (string)	$sName0,
			'nOnline'		=> (int)	$nOnline,
			'nCreateTime'	=> (int)	NOWTIME,
			'sCreateTime'	=> (string)	NOWDATE,
			'nUpdateTime'	=> (int)	NOWTIME,
			'sUpdateTime'	=> (string)	NOWDATE,
		);

		$sSQL = 'INSERT INTO '. END_CHAT_FILTER . ' ' . sql_build_array('INSERT', $aSQL_Array );
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);
		$nLastId = $oPdo->lastInsertId();


		//log ??
		$aEditLog[END_CHAT_FILTER]['aNew'] = $aSQL_Array;
		$aEditLog[END_CHAT_FILTER]['aNew']['nId'] = $nLastId;
		$aActionLog = array(
			'nWho'		=> (int)	$aAdm['nId'],
			'nWhom'		=> (int)	0,
			'sWhomAccount'	=> (string)	'',
			'nKid'		=> (int)	$nLastId,
			'sIp'			=> (string)	USERIP,
			'nLogCode'		=> (int)	8102201,
			'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
			'nType0'		=> (int)	0,
			'nCreateTime'	=> (int)	NOWTIME,
			'sCreateTime'	=> (string)	NOWDATE,
		);
		DoActionLog($aActionLog);

		$oPdo->commit();

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = INSV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nId,
						sName0,
						nOnline
				FROM 		'. END_CHAT_FILTER .'
				WHERE 	nId = :nId';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'nOnline'		=> (int)	$nOnline,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_CHAT_FILTER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			#紀錄動作 - 更新
			$aEditLog[END_CHAT_FILTER]['aNew'] = $aSQL_Array;
			$aEditLog[END_CHAT_FILTER]['aOld'] = $aData;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102202,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$oPdo->commit();

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'. END_CHAT_FILTER .'
				WHERE 	nOnline != 99
				AND 		nId = :nId';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[END_CHAT_FILTER]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_CHAT_FILTER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			$aEditLog[END_CHAT_FILTER]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102003,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>