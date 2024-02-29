<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_payment_online.php');
	#require end

	#參數接收區
	$nId			= filter_input_int('nId',			INPUT_REQUEST,0);

	$sStartTime		= filter_input_str('sStartTime',		INPUT_REQUEST,'');
	$sEndTime		= filter_input_str('sEndTime',		INPUT_REQUEST,'');
	$sAdmin		= filter_input_str('sAdmin',			INPUT_REQUEST,'');
	$sMemberAccount	= filter_input_str('sMemberAccount',	INPUT_REQUEST,'');
	$sOrder		= filter_input_str('sOrder',			INPUT_REQUEST,'');
	$nKid			= filter_input_int('nKid',			INPUT_REQUEST,0);
	$nStatus		= filter_input_int('nStatus',			INPUT_REQUEST,0);
	#參數結束

	#參數宣告區
	$aSearch = array();
	$aData = array();
	$aPayment = array();
	$aMemberData = array();
	# 帳變參數 #
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_PAYMENT	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_USER_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$nType2 = 0;	# 0 => 現金 ， 1 => 碼量

	$aMsg = aPAYMENTONLINE['aMsg'];

	# 搜尋 #
	$aSearch = array(
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sAdmin'		=> $sAdmin,
		'sMemberAccount'	=> $sMemberAccount,
		'sOrder'		=> $sOrder,
		'nKid'		=> $nKid,
		'nStatus'		=> $nStatus,
	);
	$sSearch = '';
	$sTitle = RIGHTMSG;
	$sIcon = 'success';
	$sMsg = '';
	#宣告結束

	#程式邏輯區

	foreach($aSearch as $LPsKey => $LPsValue)
	{
		$sSearch .= '&' . $LPsKey . '=' . $LPsValue;
	}

	if ($aJWT['a'] == 'PASS'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nKid,
						nUkid,
						nMoney,
						nFee,
						sOrder
				FROM		'. CLIENT_MONEY .'
				WHERE		nId = :nId
				AND		nType0 = 2
				AND		nType3 = 1
				AND		nStatus = 0
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aData === false)
		{
			$oPdo->rollback();
			$sTitle = ERRORMSG;
			$sIcon = 'error';
			$sMsg = $aMsg['NOORDER'];
		}
		else
		{
			$sSQL = '	SELECT	nId,
							nUid,
							nMoney,
							nMoneyTime,
							sMoneyKey
					FROM		'. CLIENT_USER_MONEY .'
					WHERE		nUid = :nUid
					LIMIT 	1
					FOR		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aData['nUid'],PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			if($aMemberData === false)
			{
				$oPdo->rollback();
				$sTitle = ERRORMSG;
				$sIcon = 'error';
				$sMsg = $aMsg['NOMEMBER'];
			}
			else
			{
				$nBefore	= $aMemberData['nMoney'];
				$nDelta 	= $aData['nMoney'];
				$nAfter 	= $nBefore + $nDelta;
	
				$aNewMoney = array(
					'Money' => (float) $nAfter,
				);
	
				$aSQL_Array = oTransfer::PointUpdate($aData['nId'],$aNewMoney,1,true);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '.CLIENT_USER_MONEY.' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nUid = :nUid LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
	
					$aAccLog = array(
						'nUid' 		=> (int)	$LPnUid,
						'nFromUid' 		=> (int)	$aData['nUid'],
						'nKid' 		=> (int)	$aData['nId'],
						'nType0' 		=> (int)	2,
						'nType1' 		=> (int)	100,
						'nType2' 		=> (int)	0,
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
	
				# 更新金流平台累計
				$sSQL = '	SELECT	nId,
								nDayMoney,
								nDayTimes,
								nTotalMoney,
								nTotalTimes,
								nUpdateTime,
								sUpdateTime
						FROM		'. CLIENT_PAYMENT .'
						WHERE		nPid = :nPid
						LIMIT		1
						FOR		UPDATE';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nPid',$aData['nKid'],PDO::PARAM_INT);
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
						WHERE	nPid = :nPid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nPid', $aData['nKid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
	
				$aEditLog[CLIENT_PAYMENT]['aOld'][$aPayment['nId']] = $aPayment;
				$aEditLog[CLIENT_PAYMENT]['aNew'][$aPayment['nId']] = $aSQL_Array;
	
				# 更新訂單狀態
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
	
				$aEditLog[CLIENT_MONEY]['aOld'][$aData['nId']] = $aData;
				$aEditLog[CLIENT_MONEY]['aNew'][$aData['nId']] = $aSQL_Array;
	
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aData['nId'],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	5100011,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
				$oPdo->commit();
				$sMsg = PASSV;
			}
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
				WHERE		nId = :nId
				AND		nType0 = 2
				AND		nType3 = 1
				AND		nStatus = 0
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if ($aData === false)
		{
			$sTitle = ERRORMSG;
			$sIcon = 'error';
			$sMsg = NODATA;
		}
		else
		{
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

			$aEditLog[CLIENT_MONEY]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_MONEY]['aNew'][$aData['nId']] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	5100012,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$oPdo->commit();

			$sMsg = DENYV;
		}
	}

	$aJumpMsg['0']['sTitle'] = $sTitle;
	$aJumpMsg['0']['sIcon'] = $sIcon;
	$aJumpMsg['0']['sMsg'] = $sMsg;
	$aJumpMsg['0']['sShow'] = 1;
	$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_online_0.php']) . $sSearch;
	$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	#程式邏輯結束
?>