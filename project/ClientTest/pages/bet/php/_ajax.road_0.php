<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');

	$aNumsData = array(
		'A' => array(
			0 => array(
				'sText' => '',
				'sClass' => '',
			),
		),
		'B' => array(
			0 => array(
				'sText' => '',
				'sClass' => '',
			),
		),
		'C' => array(
			0 => array(
				'sText' => '',
				'sClass' => '',
			),
		),
	);
	$aData = array();
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);

	$aNumsText = array(
		'0' => aBET['LOSE'],
		'1' => aBET['WIN'],
		'2' => aBET['TIE'],
	);

	$aNumsClass = array(
		'0' => 'FontRedImp',
		'1' => 'FontBlueImp',
		'2' => 'FontGreenImp',
	);

	$nToday = strtotime('today');
	// $sCondition = ' AND nEndTime > '.$nToday;
	$sCondition = '';

	
	if($aJWT['a'] == 'ROAD')
	{
		$nI = 0;
		$sSQL = '	SELECT 	sNo,
						sNums
				FROM 		'.CLIENT_GAMES_NUMS.'
				WHERE 	nGame = :nGame
				AND		nStatus = 4
				'.$sCondition.'
				ORDER BY nId DESC
				LIMIT 9';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$LPaNums = json_decode($aRows['sNums'],true);
			$aNumsData['A'][$nI]['sText'] = $aNumsText[$LPaNums['A']];
			$aNumsData['A'][$nI]['sClass'] = $aNumsClass[$LPaNums['A']];
			$aNumsData['B'][$nI]['sText'] = $aNumsText[$LPaNums['B']];
			$aNumsData['B'][$nI]['sClass'] = $aNumsClass[$LPaNums['B']];
			$aNumsData['C'][$nI]['sText'] = $aNumsText[$LPaNums['C']];
			$aNumsData['C'][$nI]['sClass'] = $aNumsClass[$LPaNums['C']];
			$nI++;
		}

		$aReturn['aData'] = $aNumsData;
	}

	if($aJWT['a'] == 'HISTORY')
	{
		$sType = filter_input_str('sType', INPUT_POST, '');
		$sCondition = '';
		$sNoIdList = '0';
		switch($sType)
		{
			case 'myhistory2':
				$sCondition .= ' AND nEndTime > '.strtotime('today');
				break;
			case 'myhistory3':
				$sCondition .= ' AND nEndTime > '.strtotime('-1 day',$nToday).' AND nEndTime <= '.strtotime('today');
				break;
			case 'myhistory4':
				$sCondition .= ' AND nEndTime > '.strtotime('-2 day',$nToday).' AND nEndTime <= '.strtotime('-1 day',$nToday);
				break;
			case 'myhistory5':
				break;
		}

		$sSQL = '	SELECT 	nId,
						sNo,
						sNums,
						sEndTime,
						sResult
				FROM 		'.CLIENT_GAMES_NUMS.'
				WHERE 	nGame = :nGame
				AND		nStatus = 4
				'.$sCondition.'
				ORDER BY nId DESC
				LIMIT 20';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sNoIdList .= ','.$aRows['nId'];
			$LPaNums = json_decode($aRows['sNums'],true);
			$LPaResult = json_decode($aRows['sResult'],true);
			
			$aData[$aRows['nId']] = 
			'<tr>'.
			'<td>'.$aRows['sNo'].'</td>'.
			'<td colspan="2">'.$aRows['sEndTime'].'</td>'.
			'<td class="'.$aNumsClass[$LPaNums['A']].'">'.$aNumsText[$LPaNums['A']].'</td>'.
			'<td class="'.$aNumsClass[$LPaNums['B']].'">'.$aNumsText[$LPaNums['B']].'</td>'.
			'<td class="'.$aNumsClass[$LPaNums['C']].'">'.$aNumsText[$LPaNums['C']].'</td>'.
			'<td colspan="2">'.$LPaResult['Money0']['A'].'</td>'.
			'<td colspan="2">'.$LPaResult['Money0']['B'].'</td>'.
			'<td colspan="2">'.$LPaResult['Money0']['C'].'</td>'.
			'<td colspan="2"><a href="javascript:void(0)" data-showimg="[:url:]">'.'結算畫面'.'</a></td>'.
			'</tr>';
		}

		$sSQL = '	SELECT	nId,
						nKid,
						sTable,
						sFile,
						nCreateTime,
						nType0
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		sTable LIKE \''. CLIENT_GAMES_NUMS .'\'
				AND		nKid IN ('.$sNoIdList.')
				AND		nType0 = 0';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			if(isset($aData[$aRows['nKid']]))
			{
				$aData[$aRows['nKid']] = str_replace('[:url:]',IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'],$aData[$aRows['nKid']]);
			}
		}
		$aReturn['aData'] = $aData;
	}
	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>