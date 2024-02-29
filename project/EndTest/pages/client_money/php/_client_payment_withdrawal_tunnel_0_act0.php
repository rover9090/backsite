<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_payment_online_tunnel.php');
	#require end

	#參數接收區
	$nId		= filter_input_int('nId',	INPUT_REQUEST,0);
	$nPid		= filter_input_int('nPid',	INPUT_POST, 0);
	$sKey		= filter_input_str('sKey',	INPUT_POST, '');
	$sValue	= filter_input_str('sValue',	INPUT_POST, '');
	$nMin		= filter_input_int('nMin',	INPUT_POST, 0);
	$nMax		= filter_input_int('nMax',	INPUT_POST, 0);
	$nOnline	= filter_input_int('nOnline',	INPUT_POST, 1);
	
	#參數結束
	#參數宣告區
	$aData = array();
	$aEditLog = array(
		CLIENT_PAYMENT_WITHDRAWAL_TUNNEL	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	#宣告結束

	#程式邏輯區

	# 最小金額 #
	if($nMin <= 0)
	{
		$nMin = 0;
	}

	if($nMax >= 1000000)
	{
		$nMax = 1000000;
	}

	if ($aJWT['a'] == 'INS')
	{
		$oPdo->beginTransaction();
		$aSQL_Array = array(
			'nPid'			=> (int)	$nPid,
			'sKey'			=> (string)	$sKey,
			'sValue'			=> (string)	$sValue,
			'nMin'			=> (int)	$nMin,
			'nMax'			=> (int)	$nMax,
			'nOnline'			=> (int)	$nOnline,
			'nCreateTime'		=> (int)	NOWTIME,
			'sCreateTime'		=> (string)	NOWDATE,
			'nUpdateTime'		=> (int)	NOWTIME,
			'sUpdateTime'		=> (string)	NOWDATE,
		);

		$sSQL = 'INSERT INTO '. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL . ' ' . sql_build_array('INSERT', $aSQL_Array );
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);
		$nLastId = $oPdo->lastInsertId();

		$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aNew'] = $aSQL_Array;
		$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aNew']['nId'] = $nLastId;

		//log
		$aActionLog = array(
			'nWho'		=> (int)	$aAdm['nId'],
			'nWhom'		=> (int)	0,
			'sWhomAccount'	=> (string)	'',
			'nKid'		=> (int)	$nLastId,
			'sIp'			=> (string)	USERIP,
			'nLogCode'		=> (int)	8107309,
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
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'UPT'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nPid,
						sKey,
						sValue,
						nMin,
						nMax,
						nOnline,
						sCreateTime,
						sUpdateTime
				FROM		'. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL .'
				WHERE		nOnline != 99
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$oPdo->rollback();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			if($sKey !== false)
			{
				$aSQL_Array = array(
					'nPid'			=> (int)	$nPid,
					'sKey'			=> (string)	$sKey,
					'sValue'			=> (string)	$sValue,
					'nMin'			=> (int)	$nMin,
					'nMax'			=> (int)	$nMax,
					'nOnline'			=> (int)	$nOnline,
					'nUpdateTime'		=> (int)	NOWTIME,
					'sUpdateTime'		=> (string)	NOWDATE,
				);

				$sSQL = '	UPDATE '. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				#紀錄動作 - 更新
				$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aNew']['nId'] = $aData['nId'];

				$aActionLog = array(
					'nWho'		=> (int) $aAdm['nId'],
					'nWhom'		=> (int) 0,
					'sWhomAccount'	=> (string) '',
					'nKid'		=> (int) $aData['nId'],
					'sIp'			=> (string) USERIP,
					'nLogCode'		=> (int) 8107310,
					'sParam'		=> (string) json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int) 0,
					'nCreateTime'	=> (int) NOWTIME,
					'sCreateTime'	=> (string) NOWDATE,
				);
				DoActionLog($aActionLog);

				$oPdo->commit();
				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = UPTV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$oPdo->rollback();
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = aPAYMENTONLINESETTING['ACCOUNTERR'];
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}

	if ($aJWT['a'] == 'DEL'.$nId)
	{
		$oPdo->beginTransaction();
		$sSQL = '	SELECT	nId,
						nPid,
						sKey,
						sValue,
						nMin,
						nMax,
						nOnline,
						sCreateTime,
						sUpdateTime
				FROM		'. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL .'
				WHERE		nOnline != 99
				AND		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$oPdo->rollback();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int) 	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string) NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_PAYMENT_WITHDRAWAL_TUNNEL]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int) $aAdm['nId'],
				'nWhom'		=> (int) 0,
				'sWhomAccount'	=> (string) '',
				'nKid'		=> (int) $nId,
				'sIp'			=> (string) USERIP,
				'nLogCode'		=> (int) 8107311,
				'sParam'		=> (string) json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int) 0,
				'nCreateTime'	=> (int) NOWTIME,
				'sCreateTime'	=> (string) NOWDATE,
			);
			DoActionLog($aActionLog);

			$oPdo->commit();
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_withdrawal_tunnel_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>