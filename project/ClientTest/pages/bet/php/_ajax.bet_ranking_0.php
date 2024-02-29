<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '0');
	$nSearchType = filter_input_int('nSearchType', 	INPUT_REQUEST, '4');# 0用投注量 1用投注筆數 2用贏錢 3用輸錢 4用輸贏
	$sStartTime = filter_input_str('sStartTime',	INPUT_POST, date('Y-m-d 00:00:00'));
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_POST, date('Y-m-d 23:59:59'));
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);

	$aBetData = array();
	if(isset($_POST['aBetData']))
	{
		$aBetData = $_POST['aBetData'];
	}
	$aOdds = array();
	$nAllBetMoney = 0;
	$nNowGameBet = 0;
	$nTopCount = 10;
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare
	$aOdds = array();
	$nAllBetMoney = 0;
	$nNowGameBet = 0;
	$sContent = '';
	$sCondition = '';
	$aBindArray = array();
	// $sCondition .= ' AND nCreateTime > :nStartTime ';
	// $aBindArray['nStartTime']	= $nStartTime;
	// $sCondition .= ' AND nCreateTime < :nEndTime ';
	// $aBindArray['nEndTime']		= $nEndTime;
	if($nGame > 0)
	{
		$sCondition .= ' AND nGame = :nGame ';
		$aBindArray['nGame'] = $nGame;
	}
	$aData = array();
	$aDataReal = array();

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
		'aUser'		=> $aUser,
	);
	#宣告結束

	#程式邏輯區
	if($aChkLogin_Member === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}

	if($aJWT['a'] == 'RANKING')
	{

		$aChkLogin_Member['nId'] = $aChkLogin_Member['nUid'];
		$nUid = $aChkLogin_Member['nUid'];
		$aMember = $aChkLogin_Member;

		// print_r($aChkLogin_Member);
		if(empty($aChkLogin_Member))
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOPLAYER'];
		}
		else
		{
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


			$sSQL = '	SELECT 	nId,
							sName0,
							sAccount,
							sSiteId
					FROM		'. CLIENT_USER_DATA .'
					WHERE 	1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aAllMember[$aRows['nId']] = $aRows;
			}

			foreach($aData as $LPsKey => $LPaData)
			{
				$aData[$LPsKey]['sAccount'] = $aAllMember[$LPsKey]['sName0'];
			}

			$sSQL = '	SELECT 	nId,
							sName0,
							sAccount,
							nMoney1
					FROM		'. CLIENT_USER_ROBOT .'
					WHERE 	1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aData['Bot'.$aRows['nId']] = $aRows;
				$aData['Bot'.$aRows['nId']]['nProfit'] = $aRows['nMoney1'];
				$aRawData['Bot'.$aRows['nId']] = $aRows['nMoney1'];
			}

			arsort($aRawData);

			$i=0;
			foreach ($aRawData as $LPnUid => $LPaData)
			{
				if($i > $nTopCount)
				{
					break;
				}
				$aDataReal[$i] = $aData[$LPnUid];
				$i++;
			}

			if($aReturn['nError'] == 0)
			{				
				$aReturn['nError'] = 0;
				$aReturn['sMsg'] = '';
			}

			$aReturn['aData'] = $aDataReal;

		}
		
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>