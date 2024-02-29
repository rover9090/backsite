<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nMid = filter_input_int('nMid', INPUT_POST, 0);
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');
	$aPostStickerData = isset($_POST['aStickerData'])?$_POST['aStickerData']:array();
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare

	/**
	 * 回傳陣列 JSON
	 * @var Int nError
	 * 	回傳狀態值
	 * 	0 => 正常 其餘待補
	 * @var String sMsg
	 * 	回傳訊息
	 * @var Array aData
	 * 	回傳陣列
	 * @var Int nAlertType
	 * 	回傳訊息提示類型
	 * 	0 => 不需提示框
	 * @var String sUrl
	 * 	回傳後導頁檔案
	 */
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
		'sTime'		=> date('H:i:s'),
		'nType0'		=> 0,
		'aUser'		=> $aUser,
		'aSocketMsg'	=> array(),
	);
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();
	#宣告結束

	#程式邏輯區
	if($aUser === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}
	
	if($aJWT['a'] == 'RETURN')
	{
		$aReturn['nError'] = 1;
	}
	
	# 購買貼圖
	if($aJWT['a'] == 'BUYSTICKER')
	{
		$aEditLog = array(
			CLIENT_MALL_LOG => array(
				'aNew' => array(
				),
			),
			CLIENT_MALL_COLLECT => array(
				'aNew' => array(
				),
			),
			CLIENT_USER_MONEY => array(
				'aNew' => array(
				),
				'aOld' => array(
				),
			),
		);
		$sMallList = '0';
		$nSumMoney = 0;
		$aStickerData = array();
		foreach($aPostStickerData as $LPnId => $true)
		{
			$sMallList .= ','.$LPnId;
		}
		
		if($sMallList === '0')
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aSTICKERALERT['CHOOSESTICKER'];
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							nPrice0,
							nKid,
							sName0
					FROM 		'.CLIENT_MALL.'
					WHERE 	nOnline = 1
					AND		nId IN ('.$sMallList.')';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aStickerData[$aRows['nId']] = $aRows;
			}

			foreach($aPostStickerData as $LPnId => $LPnCount)
			{
				$nSumMoney += $LPnCount * $aStickerData[$LPnId]['nPrice0'];
			}

			$oPdo->beginTransaction();
			$sSQL = '	SELECT 	nId,nMoney
					FROM  	'.CLIENT_USER_MONEY.'
					WHERE 	nUid = :nUid
					LIMIT 1
					FOR UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
			sql_query($Result);
			$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);
			$aEditLog[CLIENT_USER_MONEY]['aOld'][$aUserMoney['nId']] = $aUserMoney;
			
			if($aUserMoney === false || $aUserMoney['nMoney'] < $nSumMoney)
			{
				$oPdo->rollBack();
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aSTICKERALERT['NOTENOUGH'];
			}
			else
			{
				$aNewMoney = array(
					'Money' => (float) $aUserMoney['nMoney'] - $nSumMoney
				);
				$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aUserMoney['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$aEditLog[CLIENT_USER_MONEY]['aNew'][$aUserMoney['nId']] = $aSQL_Array;
				}
				
				$oPdo->commit();

				$aSQL_Array = array(
					'nUid'		=> (int)	$aUser['nId'],
					'nKid'		=> (int)	0,
					'nType0'		=> (int)	1,
					'nType1'		=> (int)	501,
					'nType2'		=> (int)	0,
					'nBefore'		=> (float)	$aUserMoney['nMoney'],
					'nDelta'		=> (float)	$nSumMoney * -1,
					'nAfter'		=> (float)	$aNewMoney['Money'],
					'sParams'		=> (string)	'',
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nCreateDay'	=> (int)	strtotime('today'),
					'sSiteId'		=> (string)	$aUser['sSiteId'],
				);
				DoLogAcc($aSQL_Array);

				foreach($aPostStickerData as $LPnId => $LPnCount)
				{
					$aSQL_Array = array(
						'nUid'		=> $aUser['nId'],
						'nMid'		=> $LPnId,
						'nMKid'		=> $aStickerData[$LPnId]['nKid'],
						'nCount'		=> $LPnCount,
						'nPrice'		=> $aStickerData[$LPnId]['nPrice0'],
						'nSumCost'		=> $LPnCount * $aStickerData[$LPnId]['nPrice0'],
						'nStatus'		=> 1,
						'nMoneyId'		=> $aUserMoney['nId'],
						'nCreateTime'	=> NOWTIME,
						'sCreateTime'	=> NOWDATE,
						'nCreateDay'	=> strtotime('today'),
						'nUpdateTime'	=> NOWTIME,
						'sUpdateTime'	=> NOWDATE,
					);	
					$sSQL = 'INSERT INTO '. CLIENT_MALL_LOG .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nLogId = $oPdo->lastInsertId();
					$aEditLog[CLIENT_MALL_LOG]['aNew'][$nLogId] = $aSQL_Array;
					for($i=0;$i<$LPnCount;$i++)
					{
						$aSQL_Array = array(
							'nUid'		=> $aUser['nId'],
							'nMid'		=> $LPnId,
							'nPrice0'		=> $aStickerData[$LPnId]['nPrice0'],
							'sName0'		=> $aStickerData[$LPnId]['sName0'],
							'nStock'		=> 1,
							'nUsed'		=> 0,
							'nOnline'		=> 1,
							'nMKid'		=> $aStickerData[$LPnId]['nKid'],
							'nCreateTime'	=> NOWTIME,
							'sCreateTime'	=> NOWDATE,
						);	
						$sSQL = 'INSERT INTO '. CLIENT_MALL_COLLECT .' ' . sql_build_array('INSERT', $aSQL_Array );
						$Result = $oPdo->prepare($sSQL);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);
						$nCollectId = $oPdo->lastInsertId();
						$aEditLog[CLIENT_MALL_COLLECT]['aNew'][$nCollectId] = $aSQL_Array;
					}
				}
				
				$aActionLog = array(
					'nWho'		=> (int)	$aUser['nId'],
					'nWhom'		=> (int)	$aUser['nId'],
					'sWhomAccount'	=> (string)	$aUser['sAccount'],
					'nKid'		=> (int)	0,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	7101011,
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$aReturn['nError'] = 0;
				$aReturn['sMsg'] = aSTICKERALERT['BUYSUCCESS'];
			}
		}

		

	}

	# 使用貼圖
	if($aJWT['a'] == 'USESTICKER')
	{
		$aEditLog = array(
			CLIENT_MALL_COLLECT => array(
				'aNew' => array(
				),
				'aOld' => array(
				),
			),
		);
		$aBanker = false;

		$sSQL = '	SELECT 	nId,
						sNo,
						sResult,
						nEndTime,
						nEndTime1,
						nStartTime,
						sStartTime,
						nBetEndTime,
						sBetEndTime,
						nBankerEndTime,
						nStatus
				FROM  	'.CLIENT_GAMES_NUMS.'
				WHERE 	nGame = :nGame
				AND		nStartTime <= :NOWTIME
				ORDER BY nId DESC
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
		$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aGame = $Result->fetch(PDO::FETCH_ASSOC);

		if($aGame !== false)
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							nUid,
							nStatus,
							nMoney0,
							nMoney1,
							nType0
					FROM  	'.CLIENT_GAMES_BANKER.'
					WHERE 	nType0 = 0
					AND		sNo = :sNo
					AND		nStatus = 1
					ORDER BY nId ASC
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
			sql_query($Result);
			$aBanker = $Result->fetch(PDO::FETCH_ASSOC);
		}		

		$sSQL = '	SELECT	nId
				FROM		'. CLIENT_MALL_COLLECT .'
				WHERE		nUid = :nUid
				AND		nMid = :nMid
				AND		nOnline = 1
				ORDER BY nId ASC
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
		$Result->bindValue(':nMid', $nMid, PDO::PARAM_INT);
		sql_query($Result);
		$aStickerData = $Result->fetch(PDO::FETCH_ASSOC);

		if($aStickerData === false)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aSTICKERALERT['NOSTICKER'];
		}
		// else if($aBanker === false || $aBanker['nUid'] != $aUser['nId'])
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = aSTICKERALERT['NOTBANKERNOW'];
		// }
		else if($aGame !== false && ($aGame['nStatus'] === '1' || $aGame['nStatus'] === '2'))
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aSTICKERALERT['NOTNOW'];
		}
		else
		{
			$aEditLog[CLIENT_MALL_COLLECT]['aOld'][$aStickerData['nId']] = $aStickerData;

			$aSQL_Array = array(
				'nOnline' => 0,
				'nUsed' => 1,
				'nUpdateTime' => NOWTIME,
				'sUpdateTime' => NOWDATE,
			);
			$sSQL = '	UPDATE '. CLIENT_MALL_COLLECT .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aStickerData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$aEditLog[CLIENT_MALL_COLLECT]['aNew'][$aStickerData['nId']] = $aSQL_Array;
	
			$aActionLog = array(
				'nWho'		=> (int)	$aUser['nId'],
				'nWhom'		=> (int)	$aUser['nId'],
				'sWhomAccount'	=> (string)	$aUser['sAccount'],
				'nKid'		=> (int)	$aStickerData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	7101012,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			
			$aJsonMsg = array();
			if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
			{
				$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
				$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
			}

			# 彈出貼圖
			$nMicroTime = microtime(true)*10000;
			$aJsonMsg[$nMicroTime] = array(
				'nUid' => (int)$aUser['nId'],
				'nUse' => (int)$nMid,
				'sType' => 'sticker',
			);
			$aReturn['aSocketMsg'][] = $aJsonMsg[$nMicroTime];
			$sJsonMsg = json_encode($aJsonMsg);
			$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonMsg,LOCK_EX);

			$aSocketMsg[] = $aJsonMsg[$nMicroTime];
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.$sSocketMsg);
			}
			socket_close($oSocket);

			$aReturn['nError'] = 0;
			$aReturn['sMsg'] = '';
			$aReturn['aData']['nMid'] = $nMid;
			$aReturn['aData']['nMicroTime'] = (int)$nMicroTime;
		}
	}
	
	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>