<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_withdrawal_list.php');
	#require??

	#參數接收區
	$nId		= filter_input_int('nId',		INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline',		INPUT_POST, 1);
	$nMoney	= filter_input_str('nMoney',		INPUT_POST, '');
	$sName0	= filter_input_str('sName0',		INPUT_POST, '');
	#參數結束

	#參數宣告區
	$aId = array();
	$aUserData = array();
	$aData = array();
	$nCount = 0;
	$aEditLog = array(
		CLIENT_WITHDRAWAL_LIST	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nErr = 0;
	$sErrMsg = '';
	$nStrLen = mb_strlen($sName0);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		if($nStrLen < 10 || $nStrLen > 20)
		{
			$nErr = 1;
			$sErrMsg .= aWITHDRAWALLIST['ACCOUNTERR'];
		}

		if($nMoney < $aSystem['aParam']['nMinWithdrawal'])
		{
			$nErr = 1;
			$sErrMsg .= aWITHDRAWALLIST['MONEYERR'];
		}

		if($nErr == 0)
		{
			$oPdo->beginTransaction();
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'nMoney'		=> (float)	$nMoney,
				'nOnline'		=> (int)	$nOnline,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = 'INSERT INTO '. CLIENT_WITHDRAWAL_LIST . ' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			//log ??
			$aEditLog[CLIENT_WITHDRAWAL_LIST]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_WITHDRAWAL_LIST]['aNew']['nId'] = $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102401,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sErrMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{

		if($nStrLen < 10 || $nStrLen > 20)
		{
			$nErr = 1;
			$sErrMsg .= aWITHDRAWALLIST['ACCOUNTERR'];
		}

		if($nMoney < $aSystem['aParam']['nMinWithdrawal'])
		{
			$nErr = 1;
			$sErrMsg .= aWITHDRAWALLIST['MONEYERR'];
		}

		if($nErr == 0)
		{
			$sSQL = '	SELECT 	nId,
							sName0,
							nMoney,
							nOnline,
							nUpdateTime,
							sUpdateTime
					FROM 		'. CLIENT_WITHDRAWAL_LIST .'
					WHERE 	nId = :nId
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
			sql_query($Result);
			$aData = $Result->fetch(PDO::FETCH_ASSOC);

			if(empty($aData))
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$oPdo->beginTransaction();

				$aSQL_Array = array(
					'sName0'		=> (string)	$sName0,
					'nMoney'		=> (float)	$nMoney,
					'nOnline'		=> (int)	$nOnline,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);

				$sSQL = '	UPDATE '. CLIENT_WITHDRAWAL_LIST . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				#紀錄動作 - 更新
				$aEditLog[CLIENT_WITHDRAWAL_LIST]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_WITHDRAWAL_LIST]['aNew']['nId'] = $nId;
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$nId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102402,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
				$oPdo->commit();
			}

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sErrMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						nMoney,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'. CLIENT_WITHDRAWAL_LIST .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_WITHDRAWAL_LIST]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_WITHDRAWAL_LIST . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			$aEditLog[CLIENT_WITHDRAWAL_LIST]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102403,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_withdrawal_list_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>