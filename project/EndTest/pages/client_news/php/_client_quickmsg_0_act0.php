<?php
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_quickmsg.php');

	$nId		= filter_input_int('sName0',	INPUT_POST,0);
	$sName0	= filter_input_str('sName0',	INPUT_POST,'',30);
	$sMessage	= filter_input_str('sMessage',INPUT_POST,'');
	$aParam	= (isset($_POST['aParam'])) ? $_POST['aParam'] : array();

	$aSQL_Array = array();
	$aEditLog = array(
		CLIENT_QUICKMSG => array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sMsg = NODATACHANGED;
	if ($aJWT['a'] == 'UPT')
	{
		$sSQL = '	SELECT 	nId,
						sMessage,
						sUpdateTime
				FROM		'. CLIENT_QUICKMSG;
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOld[$aRows['nId']] = $aRows;
		}

		foreach ($aOld as $LPnId => $LPaOld)
		{
			# 有改動才更新
			if ( isset($aParam[$LPaOld['nId']]) && ($aParam[$LPaOld['nId']]['sMessage'] != $LPaOld['sMessage']) )
			{
				$aSQL_Array[$LPnId] = array(
					'sMessage' 		=> $aParam[$LPaOld['nId']]['sMessage'],
					'nUpdateTime' 	=> NOWTIME,
					'sUpdateTime' 	=> NOWDATE,
				);
			}
		}

		foreach ($aSQL_Array as $LPnId => $LPaUpdateData)
		{
			$oPdo->beginTransaction();
			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_QUICKMSG .'
					WHERE 	nId = :nId
					LIMIT 	1 FOR UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);

			$sSQL = '	UPDATE	'. CLIENT_QUICKMSG .'
					SET		'. sql_build_array('UPDATE', $LPaUpdateData) . '
					WHERE		nId = :nId
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
			sql_build_value($Result, $LPaUpdateData);
			sql_query($Result);

			$aEditLog[CLIENT_QUICKMSG]['aOld'] = $aOld[$LPnId];
			$aEditLog[CLIENT_QUICKMSG]['aNew'] = $LPaUpdateData;

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$LPnId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102502,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$oPdo->commit();

			$sMsg = UPTV.'<div class="MarginBottom5"></div>';
		}

		if (in_array($sName0, $aOld))
		{
			$sMsg .= ADDPARAMERROR;
		}
		if ($sMessage != '' && !in_array($sName0, $aOld))
		{
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'sMessage'		=> (string)	$sMessage,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
				'nType0'		=> (int)	0,
			);
			$sSQL = 'INSERT INTO '. CLIENT_QUICKMSG .' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();
			$aEditLog[CLIENT_QUICKMSG]['aOld'] = array();
			$aEditLog[CLIENT_QUICKMSG]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_QUICKMSG]['aNew']['nId'] = $nLastId;

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102501,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$sMsg = INSV.'<div class="MarginBottom5"></div>';
		}

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = $sMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_quickmsg_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
?>