<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_payment_company.php');
	#require end

	#參數接收區
	$nId			= filter_input_int('nId',			INPUT_REQUEST,0);
	#參數結束

	#參數宣告區
	$aData = array();
	$aPayment = array();
	$aMemberData = array();
	$aLinkId = array();
	$nPa = 0;
	$nGrandPa = 0;
	$sLinkIds = '0';
	$sSearchIds = '0';
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_PAYMENT	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_USER_DATA	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$nType2 = 0;	# 0 => 現金 ， 1 => 碼量

	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_company_0.php']),
	);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'PASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nStatus,
						nFee,
						nKid,
						nAdmin0,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 1
				AND		nStatus = 0
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$sSQL = '	SELECT	nId,
							nUpdateTime,
							sUpdateTime
					FROM		'. CLIENT_USER_DATA .'
					WHERE		nId = :nId
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aData['nUid'],PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			$aSQL_Array = array(
				'nUpdateTime'		=> (int)	NOWTIME,
				'sUpdateTime'		=> (string)	NOWDATE,
			);
			$sSQL = '	UPDATE '. CLIENT_USER_DATA .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nUid LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aData['nUid'],PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_USER_DATA]['aOld'] = $aMemberData[$aData['nUid']];
			$aEditLog[CLIENT_USER_DATA]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_USER_DATA]['aNew']['nId'] = $aData['nUid'];

			unset($aMemberData[$aData['nUid']]);

			$sSQL = '	SELECT	nId,
							nUid,
							nMoney,
							nMoneyTime,
							sMoneyKey
					FROM		'. CLIENT_USER_MONEY .'
					WHERE		nUid = :nUid
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aData['nUid'],PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			$aMemberData['nMoney'] = $aRows['nMoney'];

			$nBefore	= $aMemberData['nMoney'];
			$nDelta 	= $aData['nMoney'];
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
					'nFromUid' 		=> (int)	0,
					'nKid' 		=> (int)	$aData['nId'],
					'nType0' 		=> (int)	2,
					'nType1' 		=> (int)	200,
					'nType2' 		=> (int)	$nType2,
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
			}
			else
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				$oPdo->rollback();
				break;
			}

			$sSQL = '	SELECT	nId,
							nDayMoney,
							nDayTimes,
							nTotalMoney,
							nTotalTimes,
							nUpdateTime,
							sUpdateTime
					FROM		'. CLIENT_PAYMENT .'
					WHERE		nId = :nId
					LIMIT		1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aData['nKid'],PDO::PARAM_INT);
			sql_query($Result);
			$aPayment = $Result->fetch(PDO::FETCH_ASSOC);

			$aSQL_Array = array(
				'nDayMoney'			=> (float)	$aPayment['nDayMoney'] + $aData['nMoney'],
				'nDayTimes'			=> (int)	$aPayment['nDayTimes'] + 1,
				'nTotalMoney'		=> (float)	$aPayment['nTotalMoney'] + $aData['nMoney'] + $aData['nFee'],
				'nTotalTimes'		=> (int)	$aPayment['nTotalTimes'] + 1,
				'nUpdateTime'		=> (int)	NOWTIME,
				'sUpdateTime'		=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_PAYMENT .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nKid'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_PAYMENT]['aOld'] = $aData;
			$aEditLog[CLIENT_PAYMENT]['aNew'] = $aSQL_Array;

			$aSQL_Array = array(
				'nStatus'			=> (int)	1,
				'nAdmin0'			=> (int)	$aAdm['nId'],
				'nUpdateTime'		=> (int)	NOWTIME,
				'sUpdateTime'		=> (string)	NOWDATE,
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
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8107101,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;

			$oPdo->commit();
		}
	}

	if ($aJWT['a'] == 'CANCEL'.$nId)
	{
		$oPdo->beginTransaction();
		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nStatus,
						nFee,
						nAdmin0,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nType0 = 1
				AND		nStatus = 0
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_MONEY]['aOld'] = $aData;

			$aSQL_Array = array(
				'nStatus'		=> (int)	99,
				'nAdmin0'		=> (int)	$aAdm['nId'],
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_MONEY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_MONEY]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8107102,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->commit();
		}
	}
	#程式邏輯結束
?>