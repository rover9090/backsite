<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_chatroom_history.php');

	$nId			= filter_input_int('nId',		INPUT_REQUEST);

	$nErr	= 0;
	$sMsg = '';
	$aActionLog = array();
	$aEditLog = array(
		CLIENT_CHATROOM => array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						nOnline
				FROM 		'. CLIENT_CHATROOM .'
				WHERE 	nId = :nId
				AND 		nOnline != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows === false)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aERROR['NODATA'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0.php']).'&nPid='.$nPid;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_CHATROOM]['aOld'] = $aRows;
			$aSQL_Array = array(
				'nOnline'	=> (int) 99,
			);
			$sSQL = '	UPDATE	'. CLIENT_CHATROOM .'
					SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
					WHERE		nId = :nId
					AND 		nOnline != 99
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			# 紀錄動作 - 刪除
			$aEditLog[CLIENT_CHATROOM]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8108203,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = aMENU['DELETESUCCESS'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
?>