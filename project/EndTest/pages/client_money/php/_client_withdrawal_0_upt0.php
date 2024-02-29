<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_withdrawal_upt.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nId			= filter_input_int('nId', 			INPUT_REQUEST,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0_act1.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']),
		'sHtml'	=> 'pages/client_money/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_withdrawal_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aMoney = array();
	$aBank = array();
	$aPayment = array();
	$aTunnel = array();
	$sPaymentId = '0';
	$nErr = 0;
	$sMsg = '';

	$aValue = array(
		'a'			=> 'PAYMENT' . $nId,
		't'			=> NOWTIME,
		'sStartTime'	=> $aJWT['sStartTime'],
		'sEndTime'		=> $aJWT['sEndTime'],
		'nKid'		=> $aJWT['nKid'],
		'nStatus'		=> $aJWT['nStatus'],
		'sAdmin'		=> $aJWT['sAdmin'],
		'sMemberAccount'	=> $aJWT['sMemberAccount'],
		'sMemo'		=> $aJWT['sMemo'],
	);
	$sJWT = sys_jwt_encode($aValue);
	#宣告結束

	#程式邏輯區

	$sSQL = '	SELECT	nId,
					nUid,
					nMoney,
					nKid
			FROM		'. CLIENT_MONEY .'
			WHERE		nId = :nId
			AND		nType0 = 3
			AND		nType3 = 2
			AND		nAdmin1 >= 0
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aMoney = $Result->fetch(PDO::FETCH_ASSOC);

	if($aMoney === false)
	{
		$aJumpMsg['0']['sMsg'] = aERRORMSG['NOORDER'];
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$sSQL = '	SELECT	sAccount
				FROM		'. CLIENT_USER_DATA .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aMoney['nUid'], PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$aData['sAccount'] = $aRows['sAccount'];

		$sSQL = '	SELECT	nMoney
				FROM		'. CLIENT_USER_MONEY .'
				WHERE		nUid = :nUid
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aMoney['nUid'], PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$aData['nMoney'] = $aRows['nMoney'];

		$sSQL = '	SELECT	sName0,
						sName1,
						sName2,
						nBid
				FROM		'. CLIENT_USER_BANK .'
				WHERE		nOnline != 99
				AND 		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aMoney['nKid'], PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$aData['sCardNumber'] = $aRows['sName0'];
		$aData['sCardName'] = $aRows['sName1'];
		$aData['sBranchName'] = $aRows['sName2'];

		$sSQL = '	SELECT	nId,
						sName0,
						sBankNumber
				FROM		'. SYS_BANK .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aRows['nBid'], PDO::PARAM_INT);
		sql_query($Result);
		$aBank = $Result->fetch(PDO::FETCH_ASSOC);
		$aData['sBankName'] = $aBank['sName0'];

		# 比對銀行代碼 #
		$sSQL = '	SELECT	nId,
						sName0
				FROM		'. CLIENT_PAYMENT .'
				WHERE		nOnline = 1
				AND		nWithdrawal = 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sPaymentId .= ',' . $aRows['nId'];
			$aPayment[$aRows['nId']] = $aRows;
		}

		$sSQL = '	SELECT	nPid,
						sKey
				FROM		'. CLIENT_PAYMENT_WITHDRAWAL_TUNNEL .'
				WHERE		nPid IN (' . $sPaymentId . ')
				AND		nOnline = 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aTunnel[$aRows['nPid']][$aRows['sKey']] = 1;
		}

		foreach ($aTunnel as $LPnPid => $LPaTunnel)
		{
			if(!array_key_exists($aBank['sBankNumber'],$LPaTunnel))
			{
				unset($aPayment[$LPnPid]);
			}
		}

		if(empty($aPayment))
		{
			$nErr = 1;
			$aValue = array(
				'a'		=> 'NOPAYMENT' . $nId,
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
		}
	}

	#程式邏輯結束

	#輸出json
	if($nErr == 1)
	{
		$aJumpMsg['0']['sMsg'] = aERRORMSG['NOPAYMENT'];
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sAct'] . '&nId=' . $nId . '&sJWT=' . $sJWT;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['sClicktoClose'] = 1;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

		$sData = json_encode($aData);
		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>