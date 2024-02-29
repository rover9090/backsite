<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_chatroom.php');

	$sA		= filter_input_str('a',		INPUT_REQUEST);
	$nId		= filter_input_int('nId',	INPUT_REQUEST);
	$sText 	= filter_input_str('sText',	INPUT_POST, '');
	$sType 	= filter_input_str('sType',	INPUT_POST, 'close');
	$nI = 0;
	$nOnline = 0;
	$nErr	= 0;
	$sMsg = '';
	$aValue = array(
		'a'		=> 'CHAT',
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aReturn = array(
		'nStatus' 	=> 0,
		'aData'	=> array(),
		'sAjax'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_0_act0.php']).'&run_page=1&sJWT='.$sJWT,
	);
	$aData = array();
	$aSearchUid = array(
		0 => 0,
	);
	$aActionLog = array();
	$aEditLog[CLIENT_CHATROOM] = array(
		'aOld' => array(),
		'aNew' => array(),
	);

	if ($aJWT['a'] != 'CHAT')
	{
		echo json_encode($aReturn);
		exit;
	}

	if ($sA == 'DEL'.$nId)
	{
		if ( $sType == 'delete' )
		{
			$nOnline = 99;
		}
		$sSQL = '	SELECT 	nId,
						nOnline
				FROM 		'. CLIENT_CHATROOM .'
				WHERE 	nOnline != 99
				AND 		nId = :nId
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows === false)
		{
			echo json_encode($aReturn);
			exit;
		}
		else
		{
			$aEditLog[CLIENT_CHATROOM]['aOld'] = $aRows;
			$aSQL_Array = array(
				'nOnline' => $nOnline,
			);

			$sSQL = '	UPDATE	'. CLIENT_CHATROOM .'
					SET		' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE 	nId = :nId
					AND 		nOnline != 99
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_CHATROOM]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8108201,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$sSQL = '	SELECT 	nId,
							nUid,
							nAdminStatus,
							sUpdateTime,
							sChat
					FROM 		'. CLIENT_CHATROOM .'
					WHERE 	nOnline = 1
					AND 		(
								nCreateTime >= '.strtotime('today').'
								OR nUpdateTime >= '.strtotime('today').'
							)
					ORDER BY 	nUpdateTime DESC';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aData[$nI] = $aRows;
				$aData[$nI]['aChat'] = json_decode($aRows['sChat'],true);
				$aData[$nI]['sAccount'] = 'Guest'.$aRows['nId'];
				$aSearchUid[$aRows['nUid']] = $aRows['nUid'];
				$nI++;
			}

			$sSQL = '	SELECT 	nId,
							sAccount
					FROM 		'. CLIENT_USER_DATA .'
					WHERE 	nId IN ( '.implode(',', $aSearchUid).' )';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aMember[$aRows['nId']] = $aRows['sAccount'];
			}

			foreach ($aData as $LPnId => $LPaData)
			{
				if ($LPaData['nUid'] == 0)
				{
					continue;
				}
				$aData[$LPnId]['sAccount'] = $aMember[$LPaData['nUid']];
			}
			$aReturn['nStatus'] = 1;
			$aReturn['aData'] = $aData;
			echo json_encode($aReturn);
			exit;
		}
	}

	if ($sA == 'GetData'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						nOnline
				FROM 		'. CLIENT_CHATROOM .'
				WHERE 	nOnline != 99
				AND 		nId = :nId
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows === false)
		{
			echo json_encode($aReturn);
			exit;
		}
		else
		{
			$aEditLog[CLIENT_CHATROOM]['aOld'] = $aRows;
			$aSQL_Array = array(
				'nAdminStatus' => 0,
			);
			$sSQL = '	UPDATE	'. CLIENT_CHATROOM .'
					SET		' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE 	nId = :nId
					AND 		nOnline != 99
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$sSQL = '	SELECT 	sChat
					FROM 		'. CLIENT_CHATROOM .'
					WHERE 	nOnline = 1
					AND 		nId = :nId
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aData = json_decode($aRows['sChat'],true);
			}

			$aReturn['nStatus'] = 1;
			$aReturn['aData'] = $aData;
			echo json_encode($aReturn);
			exit;
		}
	}

	if ($sA == 'Reply'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sChat,
						nClientStatus
				FROM 		'. CLIENT_CHATROOM .'
				WHERE 	nOnline != 99
				AND 		nId = :nId
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows === false)
		{
			echo json_encode($aReturn);
			exit;
		}
		else
		{
			$aEditLog[CLIENT_CHATROOM]['aOld'] = $aRows;
			$aChat = json_decode($aRows['sChat'],true);
			$aChat[] = array(
				'sText' => $sText,
				'nTime' => NOWTIME,
				'sTime' => NOWDATE,
				'nWho'  => 1, # 0=>user 1=>admin
			);

			$aSQL_Array = array(
				'nAdmin' 		=> (int)	$aAdm['nId'],
				'nAdminStatus' 	=> (int)	0,
				'nClientStatus'	=> (int)	$aRows['nClientStatus']+1,
				'sChat'		=> (string)	json_encode($aChat,JSON_UNESCAPED_UNICODE),
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
			$sSQL = '	UPDATE	'. CLIENT_CHATROOM .'
					SET		' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE 	nId = :nId
					AND 		nOnline != 99
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_CHATROOM]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8108202,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aReturn['nStatus'] = 1;
			$aReturn['aData'] = $aChat;
			echo json_encode($aReturn);
			exit;
		}
	}

	if ($sA == 'CheckData')
	{
		$sSQL = '	SELECT 	nId,
						nUid,
						nAdminStatus,
						sUpdateTime,
						sChat
				FROM 		'. CLIENT_CHATROOM .'
				WHERE 	nOnline = 1
				AND 		(
							nCreateTime >= '.strtotime('today').'
							OR nUpdateTime >= '.strtotime('today').'
						)
				ORDER BY 	nUpdateTime DESC';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$nI] = $aRows;
			$aData[$nI]['aChat'] = json_decode($aRows['sChat'],true);
			$aData[$nI]['sAccount'] = 'Guest'.$aRows['nId'];
			if ($nId == $aRows['nId'])
			{
				$aReturn['aData']['aChatMessage'] = json_decode($aRows['sChat'],true);
			}
			$aSearchUid[$aRows['nUid']] = $aRows['nUid'];
			$nI++;
		}

		$sSQL = '	SELECT 	nId,
						sAccount
				FROM 		'. CLIENT_USER_DATA .'
				WHERE 	nId IN ( '.implode(',', $aSearchUid).' )';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aMember[$aRows['nId']] = $aRows['sAccount'];
		}

		foreach ($aData as $LPnId => $LPaData)
		{
			if ($LPaData['nUid'] == 0)
			{
				continue;
			}
			$aData[$LPnId]['sAccount'] = $aMember[$LPaData['nUid']];
		}
		$aReturn['nStatus'] = 1;
		$aReturn['aData']['aChatUser'] = $aData;
		echo json_encode($aReturn);
		exit;
	}
?>

