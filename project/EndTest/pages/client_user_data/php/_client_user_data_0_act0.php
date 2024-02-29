<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/ClientUserClass.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');

	$nId				= filter_input_int('nId',		INPUT_REQUEST, 0);
	$sAccount			= filter_input_str('sAccount',	INPUT_POST, '', 20);
	$sPassword			= filter_input_str('sPassword',	INPUT_POST, '');
	$sName0			= filter_input_str('sName0',		INPUT_POST, '', 20);
	$sName1			= filter_input_str('sName1',		INPUT_POST, '', 20);
	$nStatus			= filter_input_int('nStatus',		INPUT_POST, 0);
	$nBlack			= filter_input_int('nBlack',		INPUT_POST, 0);
	$nMute			= filter_input_int('nMute',		INPUT_POST, 0);
	$nTest			= filter_input_int('nTest',		INPUT_POST, 0);
	$nHide			= filter_input_int('nHide',		INPUT_POST, -1);
	$sPa				= filter_input_str('sPa',		INPUT_POST, '', 20);
	$nType			= filter_input_int('nType',		INPUT_POST, 0);
	$sWater			= filter_input_str('sWater',		INPUT_POST, '', 20);
	$sPercent			= filter_input_str('sPercent',	INPUT_POST, '100', 20);

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
		elseif(!preg_match('/^(([a-z]+[0-9]+)|([0-9]+[a-z]+))[a-z0-9]*$/i', $sPassword) || $nPasswordLeng < 6 || $nPasswordLeng > 16)
		{
			$nErr	= 1;
			$sMsg	.= aUSER['PWERROR'].'<br>';
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
				$sMsg	.= $sAccount.aUSER['ACCOUNTEXISTED'].'<br>';
			}
		}

		if($aAdm['nUid'] != 0)
		{
			// 代理在後台建帳號，直接是自己下級
			$nPaId = $aAdm['nUid'];
		}
		else if($sPa == '' && $aAdm['nAdmType'] == 1 || $aAdm['nAdmType'] == 2)
		{
			// 管理帳號如未填寫上級代理，為mmg001下級
			$nPaId = 0;
		}
		else
		{
			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_USER_DATA .'
					WHERE 	nOnline != 99
					AND 		sAccount LIKE :sPa';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sPa', $sPa, PDO::PARAM_STR);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aRows === false)
			{
				$nErr	= 1;
				$sMsg	.= aUSER['PANODATA'].'<br>';
			}
			else
			{
				$nPaId = $aRows['nId'];
			}
		}
		$aGameSetting = GameSet_Class::fnCallSetting(1001);
		$aGameSetting = $aGameSetting[1001];

		$aPaLinkData = $oUser->getLinkData($nPaId);
		$aPaFsData = $oUser->getFsData($nPaId);
		$aPaFsData['sPromoWater'] = 0;
		$aPaFsData['sPromoPercent'] = 0;
		if($nPaId != 0)
		{
			if ($aPaLinkData === false || $aPaFsData === false)
			{
				$nErr	= 1;
				$sMsg	.= aUSER['PANODATA'].'<br>';
			}
		}
		else
		{
			$aPaFsData['sPromoWater'] = $aGameSetting['nWaterMax'] - $aGameSetting['nWaterLeast'] - $aGameSetting['nCompanyLeast'];
		}


		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->beginTransaction();
			$aRegister = array(
				// 'sAccount'		=> aSITE['NAME'].'-'.$sAccount.'-'.aSITE['CREDIT'],
				'sAccount'		=> $sAccount,
				'sName0'		=> $sName0,
				'sName1'		=> $sName1,
				'sPassword'		=> oCypher::ReHash($sPassword),
				'aPaLinkData'	=> $aPaLinkData,
				'aPaFsData'		=> $aPaFsData,
				'nStatus'		=> $nStatus,
				'nBlack'		=> $nBlack,
				'nMute'		=> $nMute,
				'nTest'		=> $nTest,
				'nIdentity'		=> 1, // 後台新增的皆為代理
				'nFrom'		=> 0,	// 0:後台 1:前台
				'nAdmin'		=> $aAdm['nId'],
				'sLineId'		=> '',
				'sSiteId'		=> aSITE['NAME'],
			);
			if($nHide != -1)
			{
				$aRegister['nHide'] = $nHide;
			}
			$oUser->register($aRegister);

			# 紀錄動作 - 新增

			if($bRollBack)
			{
				$oPdo->rollBack();
				$aJumpMsg['0']['sTitle'] = FS_ERROR;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = $sMsg;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$oPdo->commit();
				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sMsg'] = INSV;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sAccount,
						sName0,
						sName1,
						nStatus,
						nBlack,
						nMute,
						nIdentity
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

		$aOldAdm = array();
		if($aOld['nIdentity'] === '1')
		{
			$sSQL = '	SELECT 	nId,
							sAccount,
							sName0
					FROM 		'. END_MANAGER_DATA .'
					WHERE 	nUid = :nUid
					AND		nOnline != 99
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nId, PDO::PARAM_INT);
			sql_query($Result);
			$aOldAdm = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aOldAdm === false)
			{
				$nErr	= 1;
				$sMsg	= NODATA.'1<br>';
			}
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt0.php']).'&nId='.$nId;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'sName1'		=> (string)	$sName1,
				// 'sPassword'		=> (string)	oCypher::ReHash($sPassword),
				'nStatus'		=> (int)	$nStatus,
				'nBlack'		=> (int)	$nBlack,
				'nMute'		=> (int)	$nMute,
				'nTest'		=> (int)	$nTest,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
			if($nHide != -1)
			{
				$aSQL_Array['nHide'] = $nHide;
			}
			if($sPassword != '')
			{
				$aSQL_Array['sPassword'] = (string)	oCypher::ReHash($sPassword);
			}
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

			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				// 'sPassword'		=> (string)	oCypher::ReHash($sPassword),
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
				'nStatus'		=> (int)	$nStatus,
			);

			if($sPassword != '')
			{
				$aSQL_Array['sPassword'] = (string)	oCypher::ReHash($sPassword);
			}
			$aEditLog[END_MANAGER_DATA]['aOld'] = $aOldAdm;
			$aEditLog[END_MANAGER_DATA]['aNew'] = $aSQL_Array;

			$sSQL = '	UPDATE	'. END_MANAGER_DATA .'
					SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
					WHERE		nUid = :nUid
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			# 變更隱藏會員
			if ($aAdm['nAdmType'] == 1 || $aAdm['nAdmType'] == 2)
			{
				$sSQL = '	SELECT 	nUid
						FROM 		'. CLIENT_USER_HIDE .'
						WHERE 	nUid = :nUid
						LIMIT 	1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $nId, PDO::PARAM_INT);
				sql_query($Result);
				$aRows = $Result->fetch(PDO::FETCH_ASSOC);
				if ($aRows !== false)
				{
					$aSQL_Array = array(
						'nOnline'		=> (int) $nType,
					);
					$sSQL = '	UPDATE	'. CLIENT_USER_HIDE .'
							SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
							WHERE		nUid = :nUid
							LIMIT 	1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $nId, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}
				else
				{
					$aSQL_Array = array(
						'nUid'		=> (int)	$nId,
						'nOnline'		=> (int)	$nType,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					$sSQL = 'INSERT INTO '. CLIENT_USER_HIDE . ' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}
			}

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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'UPTFS'.$nId)
	{
		$nMaxWater = $aSystem['aParam']['nWaterMax'] - $aSystem['aParam']['nWaterLeast'] - $aSystem['aParam']['nCompanyLeast'];
		if($aAdm['nUid'] != 0)
		{
			$sSQL = '	SELECT 	1
					FROM 		'.CLIENT_USER_LINK.'
					WHERE 	nUid = :nId
					AND		sLinkList LIKE :sLinkList
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			$Result->bindValue(':sLinkList', sprintf('%%%09d%%',$aAdm['nUid']), PDO::PARAM_STR);
			sql_query($Result);
			$aFindLink = $Result->fetch(PDO::FETCH_ASSOC);
			if ($aFindLink === false)
			{
				$nErr	= 1;
				$sMsg	= NODATA.'2<br>';
			}
		}

		$sSQL = '	SELECT 	sWater,
						sPercent,
						sWaterLink,
						sPercentLink
				FROM 		'.CLIENT_USER_FS.'
				WHERE 	nUid = :nId
				AND		nEndTime = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aOld = $Result->fetch(PDO::FETCH_ASSOC);
		if($aOld === false)
		{
			$nErr	= 1;
			$sMsg	= NODATA.'5<br>';
		}
		else
		{
			// 最多小數2位
			$sWater = round($sWater,2);
			$sPercent = round($sPercent,2);

			if($sWater < 0)
			{
				$nErr	= 1;
				$sMsg	= aUSER['FSERROR'].'<br>';
			}
			if($sPercent < 0)
			{
				$nErr	= 1;
				$sMsg	= aUSER['PERCENTERROR'].'<br>';
			}

			$aWaterLink = explode(',', $aOld['sWaterLink']);
			$aPercentLink = explode(',', $aOld['sPercentLink']);
			$nPaLength = count($aWaterLink)-2;

			// 超過上限
			if($nPaLength < 0 || $sWater > $aWaterLink[$nPaLength] || $sPercent > $aPercentLink[$nPaLength])
			{
				$nErr	= 11;
			}
			if(count($aWaterLink) === 1 && 
			$sWater != $nMaxWater)
			{
				$nErr = 1;
				$sMsg = str_replace('[:WATER:]',$nMaxWater,aUSER['FSROOT']).'<br>';
			}
			// if($sWater < $aSystem['aParam']['nWaterLeast'])
			// {
			// 	$nErr = 1;
			// 	$sMsg = str_replace('[:WATER:]',$aSystem['aParam']['nWaterLeast'],aUSER['FSLIMIT']).'<br>';
			// }
		}

		if ($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt1.php']).'&nId='.$nId;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							nUid,
							sLinkList,
							nLevel
					FROM 		'.CLIENT_USER_LINK.'
					WHERE 	nUid = :nId
					AND		nEndTime = 0';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_query($Result);
			$aThisLink = $Result->fetch(PDO::FETCH_ASSOC);

			$oPdo->beginTransaction();
			# 紀錄動作 - 更新
			$aSQL_Array = array(
				'nWho'		=> (int) $aAdm['nId'],
				'nWhom'		=> (int) $nId,
				'sWhomAccount'	=> (string) '',
				'nKid'		=> (int) $nId,
				'sIp'			=> (string) USERIP,
				'nLogCode'		=> (int) 8103106,
				'nType0'		=> (int) 0,
				'sParam'		=> '',# 不寫LOG  返水紀錄另外處理
				'nCreateTime'	=> (int) NOWTIME,
				'sCreateTime'	=> (string) NOWDATE,
			);
			$nLogId = DoActionLog($aSQL_Array);

			$aNewWater = array(
				'sWater'	=> $sWater,
				'sPercent'	=> $sPercent,
			);

			$oUser->UpdateFs($aNewWater,$aThisLink,$nLogId);

			if($bRollBack)
			{
				$oPdo->rollBack();
				$aJumpMsg['0']['sTitle'] = FS_ERROR;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = $sMsg;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$oPdo->commit();
				if($nErr == 11)
				{
					$aJumpMsg['0']['sMsg'] = UPTV.'<br/>'.aUSER['UPPERLIMIT'];
				}
				else
				{
					$aJumpMsg['0']['sMsg'] = UPTV;
				}
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt1.php']).'&nId='.$nId;
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}
?>