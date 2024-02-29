<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	$sType = filter_input_str('sType', INPUT_POST, '');
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');

	$aRecordData = array('aBetData'=>array());
	$aRawData = array();
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);
	$sCondition = '';

	$aContentBet = array(
		'A' => aBET['A'],
		'B' => aBET['B'],
		'C' => aBET['C'],
	);

	$aStatus = array(
		'0' => aSTATUS['0'],
		'1' => aSTATUS['1'],
		'2' => aSTATUS['2'],
		'3' => aSTATUS['3'],
	);

	$aRecordData['nBetTotal'] = 0;
	$aRecordData['nWinTotal'] = 0;
	$aRecordData['nBetSubTotal'] = 0;
	$aRecordData['nWinSubTotal'] = 0;

	$nToday = strtotime('today');

	switch($sType)
	{
		case 'myhistory1':
			$sCondition .= ' AND nCreateTime > '.strtotime('today');
			break;
		case 'myhistory2':
			$sCondition .= ' AND nCreateTime > '.strtotime('-1 day',$nToday).' AND nCreateTime <= '.strtotime('today');
			break;
		case 'myhistory3':
			$sCondition .= ' AND nCreateTime > '.strtotime('-2 day',$nToday).' AND nCreateTime <= '.strtotime('-1 day',$nToday);
			break;
		case 'myhistory4':
			$sCondition .= ' AND nCreateTime > '.strtotime('-2 day',$nToday).' AND nCreateTime <= '.strtotime('-2 day',$nToday);
			break;
		case 'myhistory5':
			$sCondition .= ' AND nCreateTime > '.strtotime('-2 day',$nToday).' AND nCreateTime <= '.strtotime('-3 day',$nToday);
			break;
		case 'myhistory6':
			$sCondition .= ' AND nCreateTime > '.strtotime('-2 day',$nToday).' AND nCreateTime <= '.strtotime('-4 day',$nToday);
			break;
		case 'myhistory7':
			$sCondition .= ' AND nCreateTime > '.strtotime('-2 day',$nToday).' AND nCreateTime <= '.strtotime('-5 day',$nToday);
			break;
		case 'myhistory8':
			break;
	}

	$sSQL = '	SELECT 	nId
			FROM 		'.CLIENT_USER_DATA.'
			WHERE 	sLineId = :sLineId
			AND		nOnline != 99
			AND		nStatus = 0';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLineId', $sLineId, PDO::PARAM_STR);
	sql_query($Result);
	$aUser = $Result->fetch(PDO::FETCH_ASSOC);
	$aUser['nUid'] = $aUser['nId'];


	$sSQL = '	SELECT 	nId,
					nGame,
					sNo,
					nMoney0,
					nMoney1,
					sContent0,
					nStatus,
					nDone,
					sUpdateTime,
					sCreateTime
			FROM  	'.CLIENT_GAMES_DATA.'
			WHERE 	nUid = :nUid
					'.$sCondition.'
					ORDER BY nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aUser['nUid'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRecordData['nBetTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
		$aRows['nMoney1'] = (float)$aRows['nMoney1'];
		$aRows['nMoney0'] = (float)$aRows['nMoney0'];
		
		$aRecordData['aBetData'][$aRows['nId']] = $aRows;
		$aRecordData['aBetData'][$aRows['nId']]['sContent0'] = $aContentBet[$aRows['sContent0']];
		$aRecordData['aBetData'][$aRows['nId']]['nMoney2'] = $aRows['nMoney0'] + $aRows['nMoney1'];
		$aRecordData['aBetData'][$aRows['nId']]['sStatus'] = $aStatus[$aRows['nStatus']];
		$aRecordData['nBetSubTotal'] += $aRows['nMoney0'];
		$aRecordData['nWinSubTotal'] += $aRows['nMoney0'] + $aRows['nMoney1'];
		if($aRows['nMoney1'] > 0) $aRecordData['aBetData'][$aRows['nId']]['nMoney1'] = '+'.$aRows['nMoney1'];
		$aRawData[] = $aRows['nId'];
	}

	foreach($aRawData as $LPnOrder => $LPnId)
	{
		$aRawData[$LPnOrder] = $aRecordData['aBetData'][$LPnId];
	}
	$aRecordData['aBetData'] = $aRawData;

	$aReturn['aData'] = $aRecordData;

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>