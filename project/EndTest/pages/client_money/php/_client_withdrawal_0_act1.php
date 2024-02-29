<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_withdrawal.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/System/Connect/cDataEncrypt.php');
	#require end

	#參數接收區
	$nId			= filter_input_int('nId',			INPUT_REQUEST,0);
	$nPid			= filter_input_int('nPid',			INPUT_REQUEST,0);
	$sMemo		= filter_input_str('sMemo',			INPUT_REQUEST,0);
	#參數結束

	#參數宣告區
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);

	$aPageVar = array(
		'sStartTime'	=> $aJWT['sStartTime'],
		'sEndTime'		=> $aJWT['sEndTime'],
		'nKid'		=> $aJWT['nKid'],
		'nStatus'		=> $aJWT['nStatus'],
		'sAdmin'		=> $aJWT['sAdmin'],
		'sMemberAccount'	=> $aJWT['sMemberAccount'],
		'sMemo'		=> $aJWT['sMemo'],
	);

	$nStartTime = strtotime($aJWT['sStartTime']);
	$nEndTime = strtotime($aJWT['sEndTime']);
	$aData = array();
	$aPayment = array();
	$aTunnel = array();
	$aBank = array();
	$sOrder = $aSystem['aWebsite']['sTitle0']; # 訂單前綴
	$sNotifyUrl = $aSystem['aWebsite']['sUrl0'] . 'payout_notify.php'; # 回調網址
	$sTime = date('Ymd',NOWTIME);
	$sTime = substr($sTime,1);
	$sOrder .= $sTime;

	#宣告結束

	#程式邏輯區
	# 返回參數
	$sBackUrl = sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']);
	foreach ($aPageVar as $LPsParam => $LPsValue)
	{
		$sBackUrl .= '&'.$LPsParam.'='.$LPsValue;
	}

	if ($aJWT['a'] == 'NOPAYMENT'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	nId,
						nUid,
						nKid,
						nStatus,
						nAdmin1,
						nType4,
						sMemo,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nId = :nId
				AND		nType0 = 3
				AND		nType3 = 2
				AND		nStatus = 0
				AND		nAdmin1 > 0
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if($aData === false)
		{
			$nReturn = 0;
			$oPdo->rollback();
		}
		else
		{
			$aSQL_Array = array(
				'nType4'		=> (int)	0,
				'sMemo'		=> (int)	$aData . aWITHDRAWAL['NOTUNNEL'],
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
			$aEditLog[CLIENT_MONEY]['aNew'][$aData['nId']] = $aSQL_Array;

			// $aActionLog = array(
			// 	'nWho'		=> (int) 	$aAdm['nId'],
			// 	'nWho'		=> (int)	$aAdm['nId'],
			// 	'nWhom'		=> (int)	$aData['nUid'],
			// 	'sWhomAccount'	=> (string)	'',
			// 	'nKid'		=> (int)	$aData['nId'],
			// 	'sIp'			=> (string)	USERIP,
			// 	'nLogCode'		=> (int)	8107203,
			// 	'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
			// 	'nType0'		=> (int)	0,
			// 	'nCreateTime'	=> (int)	NOWTIME,
			// 	'sCreateTime'	=> (string)	NOWDATE,
			// );
			// DoActionLog($aActionLog);
			$aJumpMsg['0']['sMsg'] = aWITHDRAWAL['NOPAYMENT'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $sBackUrl;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->commit();
		}
	}

	if ($aJWT['a'] == 'PAYMENT'.$nId)
	{
		$oPdo->beginTransaction();

		$sSQL = '	SELECT	sName1,
						sAccount0,
						sSign
				FROM		'. CLIENT_PAYMENT .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nPid,PDO::PARAM_INT);
		sql_query($Result);
		$aPayment = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT	nId,
						nUid,
						nMoney,
						nKid,
						nStatus,
						nAdmin1,
						nAdmin2,
						nUpdateTime,
						sUpdateTime
				FROM		'. CLIENT_MONEY .'
				WHERE		nId = :nId
				AND		nType0 = 3
				AND		nType3 = 2
				AND		nType4 = 1
				AND		nStatus = 0
				AND		nAdmin1 > 0
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$aLastId = array(
			'INSERT'	=> 1,
			'sTable'	=> CLIENT_MONEY,
		);
		$nOrder = GetTABLastId($aLastId);
		$sOrder .= str_pad($nOrder,4,0,STR_PAD_LEFT);

		$sSQL = '	SELECT	nBid,
						sName0,
						sName1,
						sName2
				FROM		'. CLIENT_USER_BANK .'
				WHERE		nId = :nId
				AND 		nOnline != 99
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aData['nKid'], PDO::PARAM_INT);
		sql_query($Result);
		$aBank = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT	nId,
						sName0,
						sBankNumber
				FROM		'. SYS_BANK .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aBank['nBid'], PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$aBank['sBankNumber'] = $aRows['sBankNumber'];
		$aBank['sBankName'] = $aRows['sName0'];

		$sSQL = '	SELECT	sKey,
						sValue
				FROM		'. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL .'
				WHERE		nPid = :nPid
				AND		nOnline = 1
				AND		sKey = :sKey';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nPid',$nPid,PDO::PARAM_INT);
		$Result->bindValue(':sKey',$aBank['sBankNumber'],PDO::PARAM_STR);
		sql_query($Result);
		$aTunnel = $Result->fetch(PDO::FETCH_ASSOC);

		if ($aData === false)
		{
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $sBackUrl;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			$oPdo->rollback();
		}
		else
		{
			$aSQL_Array = array(
				'nStatus'		=> (int)	2,
				'sOrder'		=> (string)	$sOrder,
				'sPaymentName1'	=> (string)	$aPayment['sName1'],
				'nAdmin2'		=> (int)	$aAdm['nId'],
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
			$aEditLog[CLIENT_MONEY]['aNew'][$aData['nId']] = $aSQL_Array;

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$aData['nUid'],
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	5100021,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$oPdo->commit();

			# 送往金流機 #
			$aPostData = array(
				'sTitle0'		=> $aSystem['aWebsite']['sTitle0'],
				'sAccount0'		=> $aPayment['sAccount0'],
				'sName1'		=> $aPayment['sName1'],
				'nUid'		=> $aData['nUid'],
				'nMoney'		=> $aData['nMoney'],
				'sNotifyUrl'	=> $sNotifyUrl,
				'sOrder'		=> $sOrder,
				'sPayType'		=> $aTunnel['sKey'],
				'sCardNumber'	=> $aBank['sName0'],		# 卡號
				'sCardName'		=> $aBank['sName1'],		# 持卡人姓名
				'sBankName0'	=> $aBank['sBankName'],		# 銀行名稱
				'sBankName1'	=> $aBank['sName2'],		# 分行名稱
			);
			$sSign = sortASCII($aPostData);
			$sSign .= '&sKey=' . $aPayment['sSign'];
			$aPostData['sSign'] = md5($sSign);
			// error_log(print_r($aPostData,true));
			// echo 1;exit;
			$sUrl =  PAY['PAYOUT_URL'];
			$ch = curl_init($sUrl);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');# GET || POST
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aPostData));
			# curl 執行時間
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
			curl_setopt($ch, CURLOPT_TIMEOUT,3);
			$result = curl_exec($ch);
			$aReturn = json_decode($result,true);
			// echo $result;

			if(!empty($aReturn) && $aReturn['sStatus'] == 1)
			{
				$aJumpMsg['0']['sMsg'] = aWITHDRAWAL['ORDERCHECK'];
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = $sBackUrl;
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}
	else
	{
		$aJumpMsg['0']['sMsg'] = PARAMSERR;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $sBackUrl;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	#程式邏輯結束
?>