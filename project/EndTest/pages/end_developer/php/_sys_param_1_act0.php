<?php
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/sys_param.php');

	$aParam	= (isset($_POST['aParam'])) ? $_POST['aParam'] : array();

	$aSQL_Array = array();
	$aEditLog = array(
		SYS_PARAM => array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sMsg = NODATACHANGED;

	// $aParam['nWaterLeast'] /= 2;
	// $aParam['nCompanyLeast'] /= 2;

	if ($aJWT['a'] == 'UPT')
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						sParam,
						sUpdateTime
				FROM 		'. SYS_PARAM .'
				WHERE 	nType0 = 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOld[$aRows['nId']] = $aRows;
		}

		foreach ($aOld as $LPnId => $LPaParam)
		{
			# 有改動才更新
			if (isset($aParam[$LPaParam['sName0']]) && $aParam[$LPaParam['sName0']] != $LPaParam['sParam'])
			{
				$aSQL_Array[$LPnId] = array(
					'sParam' 		=> $aParam[$LPaParam['sName0']],
					'nUpdateTime' 	=> NOWTIME,
					'sUpdateTime' 	=> NOWDATE,
				);
			}
		}

		foreach ($aSQL_Array as $LPnId => $LPaUpdateData)
		{
			$oPdo->beginTransaction();
			$sSQL = '	SELECT 	nId
					FROM 		'. SYS_PARAM .'
					WHERE 	nId = :nId
					AND 		nType0 = 1
					LIMIT 	1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);

			$sSQL = '	UPDATE	'. SYS_PARAM .'
					SET		'. sql_build_array('UPDATE', $LPaUpdateData) . '
					WHERE		nId = :nId
					AND 		nType0 = 1
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
			sql_build_value($Result, $LPaUpdateData);
			sql_query($Result);

			$aEditLog[SYS_PARAM]['aOld'] = $aOld[$LPnId];
			$aEditLog[SYS_PARAM]['aNew'] = $LPaUpdateData;

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$LPnId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8109002,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$oPdo->commit();

			$sMsg = UPTV.'<div class="MarginBottom5"></div>';
		}

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = $sMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_developer/php/_sys_param_1.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
?>