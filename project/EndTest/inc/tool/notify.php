<?php
	#require
	ini_set('error_log', dirname(dirname(dirname(__FILE__))).'/error_log.txt');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/System.php');
	require_once(dirname(dirname(__FILE__)).'/#Define.php');
	require_once(dirname(dirname(__FILE__)).'/#DefineTable.php');
	require_once(dirname(dirname(__FILE__)).'/#Function.php');
	$aSystem['nConnect'] = 2;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/ConnectBase.php');
	require_once(dirname(dirname(__FILE__)).'/lang/TW/define.php');
	#require end

	#參數接收區
	$nUid			= filter_input_int('nUid',		INPUT_POST,0);
	$nMoney		= filter_input_int('nMoney',		INPUT_POST,0);
	$sOrder		= filter_input_str('sOrder',		INPUT_POST,'');
	$sPaymentName1	= filter_input_str('sPaymentName1',	INPUT_POST,'');
	$sSign		= filter_input_str('sSign',		INPUT_POST,'');
	#參數結束

	if(true)	# true / false
	{
		$file = 'moneyerror.txt';
		$aErr = array(
			'time'	=> date('Y-m-d H:i:s',time()),
			'where'	=> '1st',
			'REQUEST'	=> $_REQUEST,
		);
		$sErr = json_encode($aErr);
		$sMoneyLog = $sErr.PHP_EOL;
		file_put_contents($file, $sMoneyLog, FILE_APPEND | LOCK_EX);
	}
	
	#參數宣告區
	$aMd5 = array();
	$aData = array();
	$aMemberData = array();
	$aPayment = array();
	$aReturn = array(
		'nStatus'	=> 0,
		'sMsg'	=> '',
	);
	$nId = 0;
	$nPaymentId = 0;
	$sSignCheck = '';
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
	$nType1 = 0;
	$nType2 = 0;
	#宣告結束

	#程式邏輯區

	$sSQL = '	SELECT	nId,sSign
			FROM		'. CLIENT_PAYMENT .'
			WHERE		sName1 LIKE :sName1
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sName1',$sPaymentName1,PDO::PARAM_STR);
	sql_query($Result);
	$aRows = $Result->fetch(PDO::FETCH_ASSOC);
	$nPaymentId = $aRows['nId'];

	# 驗證簽名 #
	$aMd5 = array(
		'nUid'		=> $nUid,
		'nMoney'		=> $nMoney,
		'sOrder'		=> $sOrder,
		'sPaymentName1'	=> $sPaymentName1,
	);
	$sSignCheck = sortASCII($aMd5);
	$sSignCheck .= '&sKey=' . $aRows['sSign'];
	if($sSignCheck !== $sSign)
	{
		$aReturn = array(
			'nStatus'	=> 11,
			'sMsg'	=> 'order error',
		);
		echo json_encode($aReturn);
		exit;
	}

	# 取訂單資料 #
	$sSQL = '	SELECT 	nId
			FROM		' . CLIENT_MONEY . '
			WHERE		nType0 = 2
			AND		nKid = :nKid
			AND		sOrder LIKE :sOrder
			AND		nStatus = 0
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nKid',	$nPaymentId,	PDO::PARAM_INT);
	$Result->bindValue(':sOrder',	$sOrder,		PDO::PARAM_STR);
	sql_query($Result);
	$aRows = $Result->fetch(PDO::FETCH_ASSOC);
	$nId = $aRows['nId'];

	$oPdo->beginTransaction();

	$sSQL = '	SELECT 	nId,
					nUid,
					nKid,
					nMoney,
					nFee,
					sOrder
			FROM		' . CLIENT_MONEY . '
			WHERE		nId = :nId
			AND		nStatus = 0
			AND		nKid = :nKid
			AND		sOrder LIKE :sOrder
			LIMIT		1
			FOR		UPDATE';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId',	$nId,		PDO::PARAM_INT);
	$Result->bindValue(':nKid',	$nPaymentId,PDO::PARAM_INT);
	$Result->bindValue(':sOrder',	$sOrder,	PDO::PARAM_STR);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);
	$nMoney -= $aData['nFee'];
	if($aData === false)
	{
		$oPdo->rollBack();
		$aReturn = array(
			'nStatus'	=> 11,
			'sMsg'	=> 'order error',
		);
		echo json_encode($aReturn);
		exit;
	}

	# 更新會員金額 #
	$sSQL = '	SELECT	nId,
					nUid,
					nMoney,
					nMoneyTime,
					sMoneyKey
			FROM		'. CLIENT_USER_MONEY .'
			WHERE		nUid = :nUid
			LIMIT		1
			FOR		UPDATE';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid',$aData['nUid'],PDO::PARAM_INT);
	sql_query($Result);
	$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);
	if($aMemberData === false)
	{
		$oPdo->rollBack();
		$aReturn = array(
			'nStatus'	=> 12,
			'sMsg'	=> 'member error',
		);
		echo json_encode($aReturn);
		exit;
	}
	$nType1 = $aData['nKid'];
	$nType2 = 201;

	$nBefore	= $aMemberData['nMoney'];
	$nDelta 	= $nMoney;
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
			'nType1' 		=> (int)	$nType1,
			'nType2' 		=> (int)	$nType2,
			'nType3' 		=> (int)	0,
			'nBefore' 		=> (float)	$nBefore,
			'nDelta' 		=> (float)	$nDelta,
			'nAfter' 		=> (float)	$nAfter,
			'sParam' 		=> (string)	'',
			'nCreateTime' 	=> (int)	NOWTIME,
			'sCreateTime' 	=> (string)	NOWDATE,
			'nCreateDay' 	=> (int)	strtotime('today'),
		);
		DoLogAcc($aAccLog);
	}
	else
	{
		$oPdo->rollBack();
		$aReturn = array(
			'nStatus'	=> 13,
			'sMsg'	=> 'money_update error',
		);
		echo json_encode($aReturn);
		exit;
	}
	# 更新會員金額結束 #

	$aEditLog[CLIENT_USER_MONEY]['aOld'][$aMemberData['nId']] = $aMemberData;
	$aEditLog[CLIENT_USER_MONEY]['aNew'][$aMemberData['nId']] = $aSQL_Array;

	# 更新金流平台累計 #
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
	
	# 更新金流平台結束 #
	$aEditLog[CLIENT_PAYMENT]['aOld'][$aPayment['nId']] = $aPayment;
	$aEditLog[CLIENT_PAYMENT]['aNew'][$aPayment['nId']] = $aSQL_Array;

	# 更新訂單狀態 #
	$aSQL_Array = array(
		'nMoney'		=> $nMoney,
		'nStatus'		=> 1,
		'sMemo'		=> '訂單完成',
		'nUpdateTime'	=> NOWTIME,
		'sUpdateTime'	=> NOWDATE,
	);
	
	$sSQL = '	UPDATE ' . CLIENT_MONEY . ' SET ' . sql_build_array('UPDATE', $aSQL_Array).'
			WHERE nId = :nId AND nStatus = 0 LIMIT 1 ';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId',	$nId,	PDO::PARAM_INT);
	sql_build_value($Result, $aSQL_Array);
	sql_query($Result);
	# 訂單處理結束 #
	$aEditLog[CLIENT_MONEY]['aOld'][$aData['nId']] = $aData;
	$aEditLog[CLIENT_MONEY]['aNew'][$aData['nId']] = $aSQL_Array;

	$sSQL = '	SELECT	sAccount
			FROM		'. CLIENT_USER_DATA .'
			WHERE		nId = :nId
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId',$aMemberData['nId'],PDO::PARAM_INT);
	sql_query($Result);
	$aRows = $Result->fetch(PDO::FETCH_ASSOC);
	$aMemberData['sAccount'] = $aRows['sAccount'];

	$aActionLog = array(
		'nWho'		=> (int)	0,
		'nWhom'		=> (int)	$aData['nUid'],
		'sWhomAccount'	=> (string)	$aMemberData['sAccount'],
		'nKid'		=> (int)	$aData['nId'],
		'sIp'			=> (string)	USERIP,
		'nLogCode'		=> (int)	5100013,
		'sParam'		=> (string)	json_encode($aEditLog),
		'nType0'		=> (int)	0,
		'nCreateTime'	=> (int)	NOWTIME,
		'sCreateTime'	=> (string)	NOWDATE,
	);
	DoActionLog($aActionLog);

	$oPdo->commit();
	$aReturn = array(
		'nStatus'	=> 1,
		'sMsg'	=> 'success',
	);
	echo json_encode($aReturn);
	exit;
	#程式邏輯結束
?>