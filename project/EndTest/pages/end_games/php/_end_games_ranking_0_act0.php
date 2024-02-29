<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');

	$nId				= filter_input_int('nId',		INPUT_REQUEST, 0);
	$nBlack			= filter_input_int('nBlack',		INPUT_REQUEST, 0);
	$nMute			= filter_input_int('nMute',		INPUT_REQUEST, 0);

	$nPaId = 0;
	$nErr	= 0;
	$sMsg = '';

	if (true)
	{
		$sSQL = '	SELECT 	nId,
						sAccount,
						sName0,
						nStatus,
						nBlack,
						nMute
				FROM 		'. CLIENT_USER_DATA .'
				WHERE 	nId = :nId
				AND		nOnline != 99
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aOld = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aOld === false)
		{
			$nErr	= 1;
			$sMsg	= NODATA.'<br>';
		}

		if ($nBlack == 0)
		{
			$nBlack = $aOld['nBlack'];
		}

		if ($nMute == 0)
		{
			$nMute = $aOld['nMute'];
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt0.php']).'&nId='.$nId;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'nBlack'		=> (int)	$nBlack,
				'nMute'		=> (int)	$nMute,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
			$aEditLog[CLIENT_USER_DATA]['aOld'] = $aOld;
			$aEditLog[CLIENT_USER_DATA]['aNew'] = $aSQL_Array;

			$sSQL = '	UPDATE	'. CLIENT_USER_DATA .'
					SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
					WHERE		nId = :nId
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			# 紀錄動作 - 更新
			$aSQL_Array = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$nId,
				'sWhomAccount'	=> (string)	$aOld['sAccount'],
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8103102,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aSQL_Array);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ranking_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
?>