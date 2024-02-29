<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_exchange_rate.php');

	#require end

	#參數接收區

	$aRate = isset($_POST['aRate'])?$_POST['aRate']:array();
	#參數結束


	#參數宣告區
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_exchange_rate_0.php']),
	);
	$nErr = 0;
	$sMsg = '';
	$aOldData = array();
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'RATE')
	{
		$sSQL = '	SELECT 	sName0,
						sName1,
						nRate
				FROM 		'. END_EXCHANGE_RATE .'
				WHERE 	1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aOldData[$aRows['sName0']][$aRows['sName1']] = $aRows['nRate'];
		}

		foreach($aRate as $LPsName0 => $LPaRate)
		{
			foreach($LPaRate as $LPsName1 => $true)
			{
				if(isset($aOldData[$LPsName0][$LPsName1]) && 
				is_numeric($aRate[$LPsName0][$LPsName1]) &&
				$aOldData[$LPsName0][$LPsName1] != $aRate[$LPsName0][$LPsName1])
				{
					$aSQL_Array = array(
						'nRate' => $aRate[$LPsName0][$LPsName1],
						'nUpdateTime' => NOWTIME,
						'sUpdateTime' => NOWDATE,
					);
					$sSQL = '	UPDATE	'. END_EXCHANGE_RATE .'
							SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
							WHERE		sName0 = :sName0
							AND		sName1 = :sName1
							LIMIT 	1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':sName0', $LPsName0, PDO::PARAM_STR);
					$Result->bindValue(':sName1', $LPsName1, PDO::PARAM_STR);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}
			}
		}

		if($nErr > 1)
		{
			$aJumpMsg['0']['sTitle'] = $sMsg;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sMsg'] = INSV;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>