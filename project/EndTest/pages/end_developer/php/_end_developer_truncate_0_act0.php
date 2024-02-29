<?php
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/#Unload.php');

	$sPassword	= filter_input_str('sPassword',INPUT_POST, '');
	$sUserAccount= filter_input_str('sUserAccount',INPUT_POST, '');
	$sManagerAccount= filter_input_str('sManagerAccount',INPUT_POST, '');
	$aPostTable	= (isset($_POST['aPost'])) ? $_POST['aPost'] : array();
	$sPostTable = implode(',', array_keys($aPostTable));
	$aAllowTable = array(
		END_MANAGER_DATA		=> true,
		END_MANAGER_COOKIE	=> true,
		END_MENU_CTRL		=> true,
		SYS_GOOGLE_VERIFY		=> true,
		
		CLIENT_USER_DATA		=> true,
		CLIENT_USER_FS		=> true,
		CLIENT_USER_HIDE		=> true,
		CLIENT_USER_LINK		=> true,
		CLIENT_USER_MONEY		=> true,
		CLIENT_USER_BANK		=> true,
		CLIENT_USER_COOKIE	=> true,
		CLIENT_USER_VERIFY	=> true,
		CLIENT_USER_PROMO		=> true,
		CLIENT_USER_PROMO_FS	=> true,
		
		CLIENT_MONEY		=> true,
		CLIENT_PAYMENT		=> true,

		CLIENT_GAMES_DATA		=> true,
		CLIENT_GAMES_NUMS		=> true,
		CLIENT_GAMES_NUMS2	=> true,
		CLIENT_GAMES_DONATE	=> true,		
		CLIENT_GAMES_BANKER	=> true,		
		CLIENT_MALL			=> true,
		CLIENT_MALL_KIND		=> true,
		CLIENT_MALL_LOG		=> true,
		CLIENT_MALL_COLLECT	=> true,
		CLIENT_JACKPOT_LOG	=> true,
		CLIENT_JACKPOT_SET	=> true,
		
		END_LOG			=> true,
		END_LOG_ACCOUNT		=> true,
		END_MANAGER_LOGIN		=> true,
		CLIENT_USER_LOGIN		=> true,

		CLIENT_ANNOUNCE		=> true,
		CLIENT_ANNOUNCE_KIND	=> true,
		CLIENT_WITHDRAWAL_LIST	=> true,
		END_CHATROOM		=> true,
		END_CANNED			=> true,
		END_CANNED_KIND		=> true,
		CLIENT_DATA_CTRL		=> true,
		CLIENT_IMAGE_CTRL		=> true,

		SYS_MOVE_RECORD		=> true,
		END_LOG_MOVE		=> true,
		END_LOG_ACCOUNT_MOVE	=> true,
		END_MANAGER_LOGIN_MOVE	=> true,
		CLIENT_USER_LOGIN_MOVE	=> true,
		CLIENT_USER_LOGIN_MOVE	=> true,

		END_CHAT_MSG		=> true,
		END_CHAT_FILTER		=> true,

	);
	$aKeepUser = array(
		'aAid'	=> array(1=>1,),
		'aUid'	=> array(1=>1,),
	);
	$sUserAccount = 'null,'.$sUserAccount;
	$sUserAccount = trim($sUserAccount,',');
	$sManagerAccount = 'admroot,'.$sManagerAccount;
	$sManagerAccount = trim($sManagerAccount,',');

	$nErr = 0;
	$sMsg = '';
	$aEditLog = $aPostTable;
	if ($aJWT['a'] == 'TRUNCATE')
	{
		if (oCypher::ReHash($sPassword) != $aAdm['sPassword'])
		{
			$nErr = 1;
			$sMsg = '執行失敗，管理員密碼錯誤';
		}
		if (empty($aPostTable))
		{
			$nErr = 1;
			$sMsg = '請勾選處理項目';
		}
		if ($sUserAccount != '')
		{
			$sUserAccount = '\''.str_replace(',', '\',\'', $sUserAccount).'\'';
			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_USER_DATA .'
					WHERE 	sName0 IN ( '.$sUserAccount.' )
					AND 		nStatus != 99';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aKeepUser['aUid'][$aRows['nId']] = $aRows['nId'];
			}
			// if (sizeof($aKeepUser['aUid']) != sizeof(explode(',', $sUserAccount)))
			// {
			// 	$nErr = 1;
			// 	$sMsg = '會員輸入有誤';
			// }
			print_r($aKeepUser);
			print_r(explode(',', $sUserAccount));

			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_USER_LINK .'
					WHERE 	nEndTime = 0
					AND 		nUid IN ( '.implode(',', $aKeepUser['aUid']).' )';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aKeepUser['aLid'][$aRows['nId']] = $aRows['nId'];
			}

			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_USER_FS .'
					WHERE 	nUid IN ( '.implode(',', $aKeepUser['aUid']).' )';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aKeepUser['aFid'][$aRows['nId']] = $aRows['nId'];
			}
		}
		if ($sManagerAccount != '')
		{
			$sManagerAccount = '\''.str_replace(',', '\',\'', $sManagerAccount).'\'';
			$sSQL = '	SELECT 	nId
					FROM 		'. END_MANAGER_DATA .'
					WHERE 	sAccount IN ( '.$sManagerAccount.' )
					AND 		nStatus != 99';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aKeepUser['aAid'][$aRows['nId']] = $aRows['nId'];
			}
			if (sizeof($aKeepUser['aAid']) != sizeof(explode(',', $sManagerAccount)))
			{
				$nErr = 1;
				echo sizeof($aKeepUser['aUid']).'<br>';
				echo sizeof(explode(',', $sUserAccount));
				$sMsg = '管理員帳號輸入有誤';
			}
		}
		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_developer/php/_end_developer_truncate_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->beginTransaction();

			if (isset($aPostTable['end_manager_data'])) # 保留 admroot 帳號
			{
				$sSQL = 'DELETE FROM '. END_MANAGER_DATA .' WHERE nId NOT IN ('.implode(',', $aKeepUser['aAid']).') ';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. END_MANAGER_DATA .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. SYS_GOOGLE_VERIFY .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aAid']).') AND sTable LIKE :sTable';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':sTable',END_MANAGER_DATA, PDO::PARAM_STR);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. SYS_GOOGLE_VERIFY .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. END_MENU_CTRL .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aAid']).') ';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. END_MENU_CTRL .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				unset($aPostTable[END_MENU_CTRL]);
				unset($aPostTable[END_MANAGER_DATA]);
				unset($aPostTable[SYS_GOOGLE_VERIFY]);

			}

			if (isset($aPostTable['client_user_data']))
			{
				$sSQL = 'DELETE FROM '. CLIENT_USER_DATA .' WHERE nId NOT IN ('.implode(',', $aKeepUser['aUid']).')';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. CLIENT_USER_DATA .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. CLIENT_USER_HIDE .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aUid']).') ';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. CLIENT_USER_HIDE .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. CLIENT_USER_LINK .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aUid']).')';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. CLIENT_USER_LINK .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. CLIENT_USER_FS .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aUid']).')';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. CLIENT_USER_FS .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$sSQL = 'DELETE FROM '. CLIENT_USER_MONEY .' WHERE nUid NOT IN ('.implode(',', $aKeepUser['aUid']).') ';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
				$sSQL = 'ALTER TABLE '. CLIENT_USER_MONEY .' AUTO_INCREMENT = 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				foreach ($aKeepUser['aUid'] as $LPnUid)
				{
					# 會員金額歸 0
					$aNewMoney = array(
						'Money' => 0,
					);
					$aSQL_Array = oTransfer::PointUpdate($LPnUid,$aNewMoney,1,true);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nUid = :nUid LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);
					}
				}

				unset($aPostTable[CLIENT_USER_DATA]);
				unset($aPostTable[CLIENT_USER_HIDE]);
				unset($aPostTable[CLIENT_USER_LINK]);
				unset($aPostTable[CLIENT_USER_FS]);
				unset($aPostTable[CLIENT_USER_MONEY]);
			}

			foreach ($aPostTable as $LPsTable => $LPn)
			{
				if (!isset($aAllowTable[$LPsTable]))
				{
					unset($aPostTable[$LPsTable]);
					continue;
				}

				if ($LPsTable == 'client_image_ctrl')
				{
					$sSQL = 'DELETE FROM '. CLIENT_IMAGE_CTRL .' ';
					$Result = $oPdo->prepare($sSQL);
					sql_query($Result);
					$sSQL = 'ALTER TABLE '. CLIENT_IMAGE_CTRL .' AUTO_INCREMENT = 1';
					$Result = $oPdo->prepare($sSQL);
					sql_query($Result);

					continue;
				}

				if (	$LPsTable == 'end_canned'		||
					$LPsTable == 'end_canned_kind' )
				{
					$sSQL = 'DELETE FROM '.$LPsTable.' WHERE nOnline = 99';
					$Result = $oPdo->prepare($sSQL);
					sql_query($Result);
					$sSQL = 'ALTER TABLE '.$LPsTable.' AUTO_INCREMENT = 1';
					$Result = $oPdo->prepare($sSQL);
					sql_query($Result);

					continue;
				}

				$sSQL = 'TRUNCATE TABLE '.$LPsTable;
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);
			}

			$oPdo->commit();

			#紀錄動作 - 新增
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	0,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8100001,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = '處理完成';
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_developer/php/_end_developer_truncate_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
?>