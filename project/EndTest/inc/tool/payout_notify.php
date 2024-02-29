<?php
	#require
	ini_set('error_log', dirname(dirname(dirname(__FILE__))).'/error_log.txt');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/System.php');
	require_once(dirname(dirname(__FILE__)).'/#Define.php');
	require_once(dirname(dirname(__FILE__)).'/#DefineTable.php');
	require_once(dirname(dirname(__FILE__)).'/#Function.php');
	$aSystem['nConnect'] = 2;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/ConnectBase.php');
	require_once(dirname(dirname(__FILE__)).'/lang/'.$aSystem['sLang'].'/define.php');
	#require end

	#參數接收區
	$nStatus		= filter_input_int('nStatus',		INPUT_POST,0);
	$nUid			= filter_input_int('nUid',		INPUT_POST,0);
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

	$aReturn = array(
		'nStatus'	=> 0,
		'sMsg'	=> '',
	);
	$nId = 0;
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$sSignCheck = '';
	$sUrl = '';
	# 帳變參數 #
	$aEditLog = array(
		CLIENT_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
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

	# 驗證簽名 #
	$aMd5 = array(
		'nStatus'		=> $nStatus,
		'nUid'		=> $nUid,
		'sOrder'		=> $sOrder,
		'sPaymentName1'	=> $sPaymentName1,
	);
	$sSignCheck = sortASCII($aMd5);
	$sSignCheck .= '&sKey=' . $aRows['sSign'];
	if(md5($sSignCheck) !== $sSign)
	{
		$aReturn = array(
			'nStatus'	=> 14,
			'sMsg'	=> 'sign error',
		);
		echo json_encode($aReturn);
		exit;
	}
	else
	{
		# 取訂單資料 #
		$sSQL = '	SELECT 	nId
				FROM		' . CLIENT_MONEY . '
				WHERE		nType0 = 3
				AND		nType3 = 2
				AND		nType4 = 1
				AND		nStatus = 2
				AND		sOrder LIKE :sOrder
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sOrder',	$sOrder,		PDO::PARAM_STR);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$nId = $aRows['nId'];
	
		$oPdo->beginTransaction();
	
		$sSQL = '	SELECT 	nId,
						nUid,
						nMoney,
						nKid,
						nRid,
						nFee,
						sOrder
				FROM		' . CLIENT_MONEY . '
				WHERE		nId = :nId
				AND		nType0 = 3
				AND		nStatus = 2
				AND		sOrder LIKE :sOrder
				LIMIT		1
				FOR		UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',	$nId,		PDO::PARAM_INT);
		$Result->bindValue(':sOrder',	$sOrder,	PDO::PARAM_STR);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
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

		if(true)	# true / false
		{
			$file = 'moneyerror.txt';
			$aErr = array(
				'time'	=> date('Y-m-d H:i:s',time()),
				'where'	=> '2nde',
				'aData'	=> $aData,
			);
			$sErr = json_encode($aErr);
			$sMoneyLog = $sErr.PHP_EOL;
			file_put_contents($file, $sMoneyLog, FILE_APPEND | LOCK_EX);
		}
	
		# 出款成功更改狀態 #
		if($nStatus == 1)
		{
			$aSQL_Array = array(
				'nStatus'			=> (int)	1,
				'nAdmin2'			=> (int)	0,
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
				'nWho'		=> (int)	0,
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	5100022,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
		
			$oPdo->commit();
		}
		else # 失敗補錢
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
					'nAdmin2'		=> (int)	0,
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
					'nWho'		=> (int)	0,
					'nWhom'		=> (int)	$aData['nUid'],
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aData['nId'],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8107204,
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$oPdo->commit();
			}
			else
			{
				$oPdo->rollback();
			}
		}
	
		$aReturn = array(
			'nStatus'	=> 1,
			'sMsg'	=> 'success',
		);
	
		echo json_encode($aReturn);
		exit;
	}
	#程式邏輯結束
?>