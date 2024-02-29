<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ranking.php');
	#require結束
	
	$nGame	= filter_input_int('nGame', 	INPUT_POST, '0');
	$sStartTime = filter_input_str('sStartTime',	INPUT_POST,'');
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_POST,'');
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$sAccount 	= filter_input_str('sAccount',		INPUT_POST, '');	# 查詢的會員帳號
	$nSearchType = filter_input_int('nSearchType', 	INPUT_REQUEST, '4');# 0用投注量 1用投注筆數 2用贏錢 3用輸錢 4用輸贏
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區
	$aData = array();
	$aRawData = array();
	$aAllMember = array();
	$aBindArray = array();
	$nCount = 0;
	$sCondition = '';

	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);

	if($sStartTime !== '')
	{
		$sCondition .= ' AND nCreateTime > :nStartTime ';
		$aBindArray['nStartTime']	= $nStartTime;
	}

	if($sEndTime !== '')
	{
		$sCondition .= ' AND nCreateTime < :nEndTime ';
		$aBindArray['nEndTime']		= $nEndTime;
	}

	if($nGame > 0)
	{
		$sCondition .= ' AND nGame = :nGame ';
		$aBindArray['nGame'] = $nGame;
	}
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'RANKING')
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						sAccount,
						sSiteId,
						nBlack
				FROM		'. CLIENT_USER_DATA .'
				WHERE 	nStatus = 0
				AND		nOnline = 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aAllMember[$aRows['nId']] = $aRows;
			$aAllMember[$aRows['nId']]['sColor'] = '';

			$nAccountType = substr($aRows['sAccount'],-1);
			if($nAccountType == '0')
			{
				$aAllMember[$aRows['nId']]['sColor'] = 'FontGreen';
			}
			if($aRows['nBlack'] == 1)
			{
				$aAllMember[$aRows['nId']]['sColor'] = 'FontRed';
			}
		}

		# 注單 #
		$sSQL = '	SELECT 	nUid,
						sSiteId,
						nGame,
						nMoney0,
						nMoney1
				FROM		'. CLIENT_GAMES_DATA .'
				WHERE 	nDone = 1
				AND		nStatus IN (0,1,2)'.$sCondition;
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result, $aBindArray);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			if(!isset($aAllMember[$aRows['nUid']]))
			{
				continue;
			}
			if($nSearchType == 0)
			{
				$nSearchAmount = $aRows['nMoney0'];
			}
			else if($nSearchType == 1)
			{
				$nSearchAmount = 1;		
			}
			else if($nSearchType == 2 && $aRows['nMoney1'] > 0)
			{
				$nSearchAmount = $aRows['nMoney1'];
			}
			else if($nSearchType == 3 && $aRows['nMoney1'] < 0)
			{
				$nSearchAmount = $aRows['nMoney1'];
			}
			else if($nSearchType == 4)
			{
				$nSearchAmount = $aRows['nMoney1'];
			}

			if(!isset($aRawData[$aRows['nUid']]))
			{
				$aRawData[$aRows['nUid']] = 0;
				$aData[$aRows['nUid']] = array(
					'nMoney' => 0,
					'nCount' => 0,
					'nWin' => 0,
					'nLose' => 0,
					'nProfit' => 0,
				);
			}

			$aRawData[$aRows['nUid']] += $nSearchAmount;
			$aData[$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
			$aData[$aRows['nUid']]['nCount'] ++;
			if( $aRows['nMoney1'] > 0)
			{
				$aData[$aRows['nUid']]['nWin'] += $aRows['nMoney1'];
			}
			if( $aRows['nMoney1'] < 0)
			{
				$aData[$aRows['nUid']]['nLose'] += $aRows['nMoney1'];
			}
			$aData[$aRows['nUid']]['nProfit'] += $aRows['nMoney1'];
		}

		arsort($aRawData);

		$sSQL = '	SELECT 	nId,
						sName0,
						sAccount,
						sSiteId,
						nBlack
				FROM		'. CLIENT_USER_DATA .'
				WHERE 	nOnline < 99';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['nId']]['nUid'] = $aRows['nId'];
			$aData[$aRows['nId']]['sName0'] = $aRows['sName0'];
			$aData[$aRows['nId']]['sAccount'] = $aRows['sAccount'];
			$aData[$aRows['nId']]['sColor'] = '';
			if(isset($aData[$aRows['nId']]['nMoney']))
			{
				$aData[$aRows['nId']]['nMoney'] = round($aData[$aRows['nId']]['nMoney'],3);
				$aData[$aRows['nId']]['nWin'] = round($aData[$aRows['nId']]['nWin'],3);
				$aData[$aRows['nId']]['nLose'] = round($aData[$aRows['nId']]['nLose'],3);
				$aData[$aRows['nId']]['nProfit'] = round($aData[$aRows['nId']]['nProfit'],3);
			}
			#fnCeilBit,fnFloorBit

			$nAccountType = substr($aRows['sAccount'],-1);
			if($nAccountType == '0')
			{
				$aData[$aRows['nId']]['sColor'] = 'FontGreen';
			}
			if($aRows['nBlack'] == 1)
			{
				$aData[$aRows['nId']]['sColor'] = 'FontRed';
			}
		}

		foreach ($aRawData as $LPnUid => $LPaData)
		{
			$aReturn['aData'][] = $aData[$LPnUid];
		}
	}

	#程式邏輯結束
	// $aReturn['aData'] = $aRawData;
	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	exit;
	#輸出結束
?>