<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_method_setting.php');
	#require結束

	#參數接收區
	$nId		= filter_input_int('nId',		INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline',		INPUT_POST,0);
	$nOdds	= filter_input_str('nOdds',		INPUT_POST,0);
	$nOdds1	= filter_input_str('nOdds1',		INPUT_POST,0);
	$nMinBet	= filter_input_str('nMinBet',		INPUT_POST,0);
	$nMaxBet	= filter_input_str('nMaxBet',		INPUT_POST,0);
	#參數結束

	#參數宣告區
	$aData = array();
	$aEditLog = array(
		END_METHOD_SETTING	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'UPT'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						nGame,
						sCode,
						nOdds,
						nOdds1,
						nMinBet,
						nMaxBet,
						nOnline
				FROM 		'. END_METHOD_SETTING .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if($aData !== false)
		{
			$oPdo->beginTransaction();
	
			$aSQL_Array = array(
				'nOnline'		=> (int)	$nOnline,
				'nOdds'		=> (float)	$nOdds,
				'nOdds1'		=> $nOdds1,
				'nMinBet'		=> $nMinBet,
				'nMaxBet'		=> $nMaxBet,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
	
			$sSQL = '	UPDATE '. END_METHOD_SETTING . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
	
			#紀錄動作 - 新增
			$aEditLog[END_METHOD_SETTING]['aOld'] = $aData;
			$aEditLog[END_METHOD_SETTING]['aNew'] = $aSQL_Array;
			$aEditLog[END_METHOD_SETTING]['aNew']['nId'] = $nId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104202,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
	
			$oPdo->commit();
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}

		// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
		// $aJumpMsg['0']['sIcon'] = 'success';
		// $aJumpMsg['0']['sMsg'] = UPTV;
		// $aJumpMsg['0']['sShow'] = 1;
		// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php']);
		// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php'])); 
	}

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						nGame,
						sCode,
						nOdds,
						nOnline
				FROM 		'. END_METHOD_SETTING .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if ($aData === false)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[END_METHOD_SETTING]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_METHOD_SETTING . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[END_METHOD_SETTING]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104203,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>