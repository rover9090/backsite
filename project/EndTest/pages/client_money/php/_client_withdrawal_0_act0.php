<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_withdrawal.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/System/Connect/cDataEncrypt.php');
	#require end

	#參數接收區
	$nId			= filter_input_int('nId',			INPUT_REQUEST,0);

	// Excel 用
	$sStartTime		= filter_input_str('sStartTime', 		INPUT_GET,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 		INPUT_GET,date('Y-m-d 23:59:59'));
	$nKid			= filter_input_int('nKid', 			INPUT_GET,0);
	$nStatus		= filter_input_int('nStatus', 		INPUT_GET,-1);
	$sAdmin		= filter_input_str('sAdmin', 			INPUT_GET,'');
	$sMemberAccount	= filter_input_str('sMemberAccount', 	INPUT_GET,'');
	$sMemo		= filter_input_str('sMemo', 			INPUT_GET,'');
	#參數結束

	#參數宣告區
	$aData = array();
	$aMemberData = array();
	$aValid = array();
	$aPayment = array();
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;


	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aAllAdminData = array();
	$aSearchBankCard = array(0=>0);
	$aBankCard = array();
	$sCondition = '';
	$sAdmCondition = '';
	$aAdmBind = array();
	$aBind = array();
	$sAdmIds = '0';
	$aStatus = aWITHDRAWAL['STATUS'];
	$nTotalMoney = 0;
	$nTotalCount = 0;
	#宣告結束

	#程式邏輯區

	if ($aJWT['a'] == 'RISKPASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nKid,
						nStatus,
						nAdmin1,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 3
				AND		nStatus = 0
				AND		nAdmin1 <= 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	nId,
							sName0
					FROM		'. CLIENT_USER_BANK .'
					WHERE		nOnline = 1
					AND		nId = :nId
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aData['nKid'],PDO::PARAM_INT);
			sql_query($Result);
			$aPayment = $Result->fetch(PDO::FETCH_ASSOC);

			if($aPayment === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = aWITHDRAWAL['ACCOUNTNOTFOUND'];
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
			else
			{
				$aValid = array(
					'nKid'	=> $aData['nKid'],
					'sTable'	=> CLIENT_USER_BANK,
					'sNameOld'	=> $aPayment['sName0'],
					'NOWTIME'	=> NOWTIME
				);
				if(cDataEncrypt::check($aValid))
				{
					$aSQL_Array = array(
						'nAdmin1'		=> (int)	$aAdm['nId'],
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
					);
					$sSQL = '	UPDATE '. CLIENT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aEditLog[CLIENT_MONEY]['aOld'] = $aData;
					$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $aData['nId'];

					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	$aData['nUid'],
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$aData['nId'],
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8107201,
						'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					DoActionLog($aActionLog);

					$aJumpMsg['0']['sTitle'] = RIGHTMSG;
					$aJumpMsg['0']['sIcon'] = 'success';
					$aJumpMsg['0']['sMsg'] = UPTV;
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

					$oPdo->commit();
				}
				else
				{
					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = aWITHDRAWAL['ACCOUNTERR'];
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
					$oPdo->rollback();
				}
			}
		}
	}

	if ($aJWT['a'] == 'RISKDENY'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nStatus,
						nFee,
						nKid,
						nAdmin1,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 3
				AND		nStatus = 0
				AND		nAdmin1 <= 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	nUid,
							nMoney,
							nMoneyTime,
							sMoneyKey
					FROM		'. CLIENT_USER_MONEY .'
					WHERE		nUid = :nUid
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aData['nUid'], PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			$nBefore	= $aMemberData['nMoney'];
			$nDelta 	= $aData['nMoney'] + $aData['nFee'];
			$nAfter 	= $nBefore + $nDelta;

			$aNewMoney = array(
				'Money' => (float) $nAfter,
			);

			$aSQL_Array = oTransfer::PointUpdate($aData['nUid'],$aNewMoney,1,true);
			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aData['nUid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				$aAccLog = array(
					'nUid' 		=> (int)	$aData['nUid'],
					'nKid' 		=> (int)	$aData['nId'],
					'nType0' 		=> (int)	2,
					'nType1' 		=> (int)	$aData['nKid'],
					'nType2' 		=> (int)	204,
					'nType3' 		=> (int)	0,
					'nBefore' 		=> (float)	$nBefore,
					'nDelta' 		=> (float)	$nDelta,
					'nAfter' 		=> (float)	$nAfter,
					'sParams' 		=> (string)	'',
					'nCreateTime' 	=> (int)	NOWTIME,
					'sCreateTime' 	=> (string)	NOWDATE,
					'nCreateDay' 	=> (int)	strtotime('today'),
				);
				DoLogAcc($aAccLog);

				$aSQL_Array = array(
					'nAdmin1'		=> (int)	$aAdm['nId'],
					'nStatus'		=> (int)	99,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);
				$sSQL = '	UPDATE '. CLIENT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				$aEditLog[CLIENT_MONEY]['aOld'] = $aData;
				$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $aData['nId'];

				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	$aData['nUid'],
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aData['nId'],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8107202,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = UPTV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

				$oPdo->commit();
			}
			else
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_company_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
		}
	}

	if ($aJWT['a'] == 'MONEYPASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nKid,
						nStatus,
						nAdmin1,
						nAdmin2,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 3
				AND		nStatus = 0
				AND		nAdmin1 > 0
				AND		nAdmin2 <= 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	nId,
							sName0
					FROM		'. CLIENT_USER_BANK .'
					WHERE		nOnline = 1
					AND		nId = :nId
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aData['nKid'],PDO::PARAM_INT);
			sql_query($Result);
			$aPayment = $Result->fetch(PDO::FETCH_ASSOC);

			if($aPayment === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
			else
			{
				$aValid = array(
					'nKid'	=> $aData['nKid'],
					'sTable'	=> CLIENT_USER_BANK,
					'sNameOld'	=> $aPayment['sName0'],
				);
				if(cDataEncrypt::check($aValid))
				{
					$aSQL_Array = array(
						'nAdmin2'		=> (int)	$aAdm['nId'],
						'nStatus'		=> (int)	1,
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
					);

					$sSQL = '	UPDATE '. CLIENT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aEditLog[CLIENT_MONEY]['aOld'] = $aData;
					$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $aData['nId'];

					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	$aData['nUid'],
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$aData['nId'],
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8107203,
						'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					DoActionLog($aActionLog);

					$aJumpMsg['0']['sTitle'] = RIGHTMSG;
					$aJumpMsg['0']['sIcon'] = 'success';
					$aJumpMsg['0']['sMsg'] = UPTV;
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

					$oPdo->commit();
				}
				else
				{
					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = aWITHDRAWAL['ACCOUNTERR'];
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
					$oPdo->rollback();
				}
			}
		}
	}

	if ($aJWT['a'] == 'MONEYDENY'.$nId)
	{
		$oPdo->beginTransaction();
		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nStatus,
						nKid,
						nFee,
						nAdmin1,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 3
				AND		nStatus = 0
				AND		nAdmin1 > 0
				AND		nAdmin2 <= 0
				AND		nId = :nId
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	nUid,
							nMoney,
							nMoneyTime,
							sMoneyKey
					FROM		'. CLIENT_USER_MONEY .'
					WHERE		nUid = :nUid
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aData['nUid'], PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			$nBefore	= $aMemberData['nMoney'];
			$nDelta 	= $aData['nMoney'] + $aData['nFee'];
			$nAfter 	= $nBefore + $nDelta;

			$aNewMoney = array(
				'Money' => (float) $nAfter,
			);

			$aSQL_Array = oTransfer::PointUpdate($aData['nUid'],$aNewMoney,1,true);
			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aData['nUid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				$aAccLog = array(
					'nUid' 		=> (int)	$aData['nUid'],
					'nKid' 		=> (int)	$aData['nId'],
					'nType0' 		=> (int)	2,
					'nType1' 		=> (int)	$aData['nKid'],
					'nType2' 		=> (int)	204,
					'nType3' 		=> (int)	0,
					'nBefore' 		=> (float)	$nBefore,
					'nDelta' 		=> (float)	$nDelta,
					'nAfter' 		=> (float)	$nAfter,
					'sParams' 		=> (string)	'',
					'nCreateTime' 	=> (int)	NOWTIME,
					'sCreateTime' 	=> (string)	NOWDATE,
					'nCreateDay' 	=> (int)	strtotime('today'),
				);
				DoLogAcc($aAccLog);

				$aSQL_Array = array(
					'nAdmin2'		=> (int)	$aAdm['nId'],
					'nStatus'		=> (int)	99,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);

				$sSQL = '	UPDATE '. CLIENT_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

				$aEditLog[CLIENT_MONEY]['aOld'] = $aData;
				$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
				$aEditLog[CLIENT_MONEY]['aNew']['nId'] = $aData['nId'];

				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	$aData['nUid'],
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aData['nId'],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8107204,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = UPTV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->commit();
			}
			else
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_company_0.php']);
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
			}
		}
	}

	// 匯出excel
	if ($aJWT['a'] == 'EXCEL')
	{
		header("Content-type:application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition:filename=". NOWTIME .".xls");

		$sCondition = ' AND Money_.nCreateTime >= :nStartTime AND Money_.nCreateTime <= :nEndTime';
		$aBind['nStartTime'] = $nStartTime;
		$aBind['nEndTime'] = $nEndTime;

		if($nKid > 0)
		{
			$sSQL = '	SELECT	nId
					FROM		'. CLIENT_USER_BANK .'
					WHERE		nOnline != 99
					AND 		nBid = :nBid';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nBid', $nKid, PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aSearchBankCard[$aRows['nId']] = $aRows['nId'];
			}
			$sCondition .= ' AND Money_.nKid IN ( '.implode(',', $aSearchBankCard).' ) ';
			$aSearchBankCard = array(0=>0);

		}

		if($nStatus > -1)
		{
			$sCondition .= ' AND Money_.nStatus = :nStatus ';
			$aBind['nStatus'] = $nStatus;
		}

		if($sAdmin != '')
		{
			$sAdmCondition .= '	AND sAccount LIKE :sAdmin ';
			$aAdmBind['sAdmin'] = '%'.$sAdmin.'%';
		}
		else
		{
			$sAdmIds = '-1';
		}

		if($sMemberAccount != '')
		{
			$sCondition .= '	AND User_.sAccount LIKE :sMemberAccount ';
			$aBind['sMemberAccount'] = '%'.$sMemberAccount.'%';
		}

		if($sMemo != '')
		{
			$sCondition .= '	AND Money_.sMemo LIKE :sMemo ';
			$aBind['sMemo'] = '%'.$sMemo.'%';
		}

		unset($aStatus['sTitle']);

		$sSQL = '	SELECT	nId,
						sAccount
				FROM		'. END_MANAGER_DATA .'
				WHERE		nOnline != 99
				ORDER	BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aAdmBind);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aAllAdminData[$aRows['nId']] = $aRows;
		}

		$sSQL = '	SELECT	nId,
						sAccount
				FROM		'. END_MANAGER_DATA .'
				WHERE		nOnline != 99
						'. $sAdmCondition .'
				ORDER	BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aAdmBind);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sAdmIds .= ','.$aRows['nId'];
		}

		if($sAdmin != '')
		{
			$sCondition .= ' AND ( Money_.nAdmin1 IN ( ' . $sAdmIds . ' ) OR Money_.nAdmin2 IN ( ' . $sAdmIds . ' ) ) ';
		}

		$sSQL = '	SELECT	Money_.nId,
						Money_.nUid,
						Money_.nMoney,
						Money_.nStatus,
						Money_.nKid,
						Money_.nRid,
						Money_.sPayType,
						Money_.nAdmin1,
						Money_.nAdmin2,
						Money_.nFee,
						Money_.sMemo,
						Money_.nType1,
						Money_.sCreateTime,
						Money_.sUpdateTime,
						User_.sAccount as sUserAccount
				FROM		'. CLIENT_MONEY .' Money_,
						'. CLIENT_USER_DATA .' User_
				WHERE		Money_.nType0 = 3
				AND 		Money_.nUid = User_.nId
						'. $sCondition .'
				ORDER	BY	Money_.nId DESC ';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBind);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nTotalMoney += $aRows['nMoney'];
			$nTotalCount ++;
			$aData[$aRows['nId']] = $aRows;
			$aData[$aRows['nId']]['sBank'] = '';
			$aData[$aRows['nId']]['sAdmin1'] = '';
			$aData[$aRows['nId']]['sAdmin2'] = '';

			if($aRows['nAdmin1'] > 0)
			{
				$aData[$aRows['nId']]['sAdmin1'] = $aAllAdminData[$aRows['nAdmin1']]['sAccount'];
			}

			if($aRows['nAdmin2'] > 0 && $aRows['nAdmin1'] > 0)
			{
				$aData[$aRows['nId']]['sAdmin2'] = $aAllAdminData[$aRows['nAdmin2']]['sAccount'];
			}

			$aSearchBankCard[$aRows['nKid']] = $aRows['nKid'];
		}

		# 會員銀行卡
		$sSQL = '	SELECT 	Bank_.sCode,
						Bank_.sName0 as sBankName,
						UserBank_.nId,
						UserBank_.sName0,
						UserBank_.sName1,
						UserBank_.sName2,
						UserBank_.nBid
				FROM 		'. CLIENT_USER_BANK .' UserBank_
				JOIN		'. SYS_BANK .' Bank_
				ON		Bank_.nId = UserBank_.nBid
				WHERE 	UserBank_.nId IN ( '.implode(',', $aSearchBankCard).' )';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBind);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aBankCard[$aRows['nId']] = $aRows;
			$aBankCard[$aRows['nId']]['sBankName'] = $aRows['sBankName'].'('.$aRows['sCode'].')';
		}


		echo $sStartTime.' ~ '.$sEndTime.'<br><br>';
		echo '<table border=2>';

		echo '<tr>';
		echo '<td>'. aWITHDRAWAL['ACCOUNT'] .'</td>';
		echo '<td>'. aWITHDRAWAL['BANKNAME'] .'</td>';
		echo '<td>'. aWITHDRAWAL['SUBNAME'] .'</td>';
		echo '<td>'. aWITHDRAWAL['USERNAME'] .'</td>';
		echo '<td>'. aWITHDRAWAL['CARDNUMBER'] .'</td>';
		echo '<td>'. aWITHDRAWAL['MONEY'] .'</td>';
		echo '<td>'. aWITHDRAWAL['FEE'] .'</td>';
		echo '<td>'. aWITHDRAWAL['STATUS']['sTitle'] .'</td>';
		echo '<td>'. aWITHDRAWAL['ADMIN1'] .'</td>';
		echo '<td>'. aWITHDRAWAL['ADMIN2'] .'</td>';
		echo '<td>'. aWITHDRAWAL['MEMO'] .'</td>';
		echo '<td>'. CREATETIME .'</td>';
		echo '<td>'. UPDATETIME .'</td>';
		echo '</tr>';

		foreach ($aData as $LPnId => $LPaData)
		{
			echo '<tr>';
			echo '<td>'.$LPaData['sUserAccount'].'</td>';

			echo '<td>'.$aBankCard[$LPaData['nKid']]['sBankName'].'</td>';
			echo '<td>'.$aBankCard[$LPaData['nKid']]['sName2'].'</td>';
			echo '<td>'.$aBankCard[$LPaData['nKid']]['sName1'].'</td>';
			echo '<td>'.$aBankCard[$LPaData['nKid']]['sName0'].'</td>';

			echo '<td>'.$LPaData['nMoney'].'</td>';
			echo '<td>'.$LPaData['nFee'].'</td>';
			echo '<td>'.$aStatus[$LPaData['nStatus']]['sText'].'</td>';
			echo '<td>'.$LPaData['sAdmin1'].'</td>';
			echo '<td>'.$LPaData['sAdmin2'].'</td>';
			echo '<td>'.$LPaData['sMemo'].'</td>';
			echo '<td>'.$LPaData['sCreateTime'].'</td>';
			echo '<td>'.$LPaData['sUpdateTime'].'</td>';
			echo '</tr>';
		}
		echo '</table><br>';
		echo aWITHDRAWAL['TOTALCOUNT'].' : '.$nTotalCount.' '.aWITHDRAWAL['TOTALMONEY'].' : '.$nTotalMoney.'<br>';
		exit;

	}
	#程式邏輯結束
?>