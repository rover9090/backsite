<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/ClientUserClass.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_robot.php');

	$nId				= filter_input_int('nId',		INPUT_REQUEST, 0);
	$sAccount			= filter_input_str('sAccount',	INPUT_POST, '', 20);
	$sPassword			= filter_input_str('sPassword',	INPUT_POST, '');
	$sName0			= filter_input_str('sName0',		INPUT_POST, '', 20);
	$nStatus			= filter_input_int('nStatus',		INPUT_POST, 0);
	$nBlack			= filter_input_int('nBlack',		INPUT_POST, 0);
	$nMute			= filter_input_int('nMute',		INPUT_POST, 0);
	$nTest			= filter_input_int('nTest',		INPUT_POST, 0);
	$sPa				= filter_input_str('sPa',		INPUT_POST, '', 20);
	$nType			= filter_input_int('nType',		INPUT_POST, 0);
	$sWater			= filter_input_str('sWater',		INPUT_POST, '', 20);
	$sPercent			= filter_input_str('sPercent',	INPUT_POST, '', 20);
	$nMoney			= filter_input_str('nMoney',		INPUT_POST, '', 20);

	$nPasswordLeng = strlen($sPassword);
	$nPaId = 0;
	$nErr	= 0;
	$sMsg = '';
	$oUser = new oClientUser();

	if ($aJWT['a'] == 'INS')
	{
		if (!preg_match('/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/', $sAccount))
		{
			$nErr	= 1;
			$sMsg	.= aUSER['NAMEERROR'].'<br>';
		}
		else
		{
			$sSQL = '	SELECT 	1
					FROM 		'. CLIENT_USER_DATA .'
					WHERE 	nOnline != 99
					AND 		sAccount LIKE :sAccount';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nErr	= 1;
			}

			$sSQL = '	SELECT 	1
					FROM 		'. CLIENT_USER_ROBOT .'
					WHERE 	nOnline != 99
					AND 		sAccount LIKE :sAccount';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nErr	= 1;
			}

			$sSQL = '	SELECT 	1
					FROM 		'.END_MANAGER_DATA.'
					WHERE 	nOnline != 99
					AND 		sAccount LIKE :sAccount';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows !== false)
			{
				$nErr	= 1;
			}
			if($nErr == 1)
			{
				$sMsg	.= $sAccount.aUSER['NAMEEXISTED'].'<br>';
			}
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_robot_0_upt0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->beginTransaction();
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'sPassword'		=> (string)	'',
				'sAccount'		=> (string)	$sAccount,
				'nOnline'		=> (int)	1,
				'nStatus'		=> (int)	$nStatus,
				'nBlack'		=> $nBlack,
				'nMute'		=> $nMute,
				'nTest'		=> $nTest,
				'nIdentity'		=> 1,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
				'nMoney'		=> $nMoney,
				'sSiteId'		=> 'local',
			);
			$aEditLog[CLIENT_USER_ROBOT]['aNew'] = $aSQL_Array;

			$sSQL = 'INSERT INTO '.CLIENT_USER_ROBOT.' '. sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			# 紀錄動作 - 新增
			$nWho = $aData['nAdmin'];
			$nLogCode = 8103101;
			$aSQL_Array = array(
				'nWho'		=> (int)	$nWho,
				'nWhom'		=> (int)	$nLastId,
				'sWhomAccount'	=> (string)	$sAccount,
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aSQL_Array);

			if($bRollBack)
			{
				$oPdo->rollBack();
				$aJumpMsg['0']['sTitle'] = FS_ERROR;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = $sMsg;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_robot_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$oPdo->commit();
				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sMsg'] = INSV;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_robot_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sAccount,
						sName0,
						nStatus,
						nBlack,
						nMute
				FROM 		'. CLIENT_USER_ROBOT .'
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

		if($sPassword != '' && (!preg_match('/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i', $sPassword) || $nPasswordLeng < 6 || $nPasswordLeng > 16))
		{
			$nErr	= 1;
			$sMsg	.= aUSER['PWERROR'].'<br>';
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_robot_0_upt0.php']).'&nId='.$nId;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				// 'sPassword'		=> (string)	oCypher::ReHash($sPassword),
				'nStatus'		=> (int)	$nStatus,
				'nBlack'		=> (int)	$nBlack,
				'nMute'		=> (int)	$nMute,
				'nTest'		=> (int)	$nTest,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$aEditLog[CLIENT_USER_DATA]['aOld'] = $aOld;
			$aEditLog[CLIENT_USER_DATA]['aNew'] = $aSQL_Array;

			$sSQL = '	UPDATE	'. CLIENT_USER_ROBOT .'
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_robot_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
?>