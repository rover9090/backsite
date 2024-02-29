<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_report_lottery.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_report/end_report.js',
	);

	#參數接收區
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, aDAY['TODAY']['sStartDay']);
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, aDAY['TODAY']['sEndDay']);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_REQUEST, 'TODAY');
	$sAccount 	= filter_input_str('sAccount',	INPUT_REQUEST, '');
	$nStatus 	= filter_input_int('nStatus',		INPUT_REQUEST, -1);
	$nDone 	= filter_input_int('nDone',		INPUT_REQUEST, -1);
	$nGoalType 	= filter_input_int('nGoalType',	INPUT_REQUEST, -1);
	$nGame 	= filter_input_int('nGame',		INPUT_REQUEST, -1);
	$sMethod 	= filter_input_str('sMethod',		INPUT_REQUEST, '',16);
	$sSiteId 	= filter_input_str('sSiteId',		INPUT_REQUEST, '',50);
	$sNo 		= filter_input_str('sNo',		INPUT_REQUEST, '');
	$sBankerAccount 	= filter_input_str('sBankerAccount',	INPUT_REQUEST, '');


	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_lottery_0.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_report_lottery_0.php',
	);
	#url結束


	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$aHideMember = array();
	$sCondition1 = '';
	$sCondition2 = '';
	$aBindArray1 = array();
	$aBindArray2 = array();
	$sCondition1 .= ' AND nCreateTime > :nStartTime ';
	$aBindArray1['nStartTime']	= $nStartTime;
	$sCondition1 .= ' AND nCreateTime < :nEndTime ';
	$aBindArray1['nEndTime']		= $nEndTime;
	$sCondition2 .= ' AND nCreateTime > :nStartTime ';
	$aBindArray2['nStartTime']	= $nStartTime;
	$sCondition2 .= ' AND nCreateTime < :nEndTime ';
	$aBindArray2['nEndTime']		= $nEndTime;

	$aHideMember = array();
	$aDay = aDAY;
	$aData = array(
		'aData'	=> array(),
		'aSubTotal' => array(
			'nMoney0' 		=> 0,
			'nMoney1'		=> 0,
			'nWin'		=> 0,
			'nWater'		=> 0,
			'nRealWin'		=> 0,# 實拿
			'nBetWin1'		=> 0,# 總輸
		),
		'aTotal' 	=> array(
			'nMoney0' 		=> 0,
			'nMoney1'		=> 0,
			'nWin'		=> 0,
			'nWater'		=> 0,
			'nRealWin'		=> 0,# 實拿
			'nBetWin1'		=> 0,# 總輸
		),
	);
	$sHideUser = '';
	$aUid2Pa = array();
	$aUserName = array();
	$aBankerName = array();
	$aBanker = array();
	$aUser = array();
	$sIncludeMember = '0';
	$sUidLink = '0';
	$sBankerList = '0';
	$sNoList = '0';
	$aNoList = array();
	$sBankerNoList = '0';
	// $aPage['nPageSize'] = 2;
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];

	$aMethod = array();
	foreach (aMETHOD as $LPsMethod => $LPsText)
	{
		$aMethod[$LPsMethod]['sSelect'] = '';
		$aMethod[$LPsMethod]['sText'] = $LPsText;
	}


	$aStatus = array(
		'0' => array(
			'sText'	=> aLOTTERY['STATUS0'],
			'sSelect'	=> '',
			'sClass'	=> 'FontGreen',
		),
		'1' => array(
			'sText'	=> aLOTTERY['STATUS1'],
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
		'2' => array(
			'sText'	=> aLOTTERY['STATUS2'],
			'sSelect'	=> '',
			'sClass'	=> 'FontBlue',
		),
		'3' => array(
			'sText'	=> aLOTTERY['STATUS3'],
			'sSelect'	=> '',
			'sClass'	=> 'FontBlue',
		),
	);

	$aDone = array(
		'0' => array(
			'sText'	=> aLOTTERY['DONE0'],
			'sSelect'	=> '',
		),
		'1' => array(
			'sText'	=> aLOTTERY['DONE1'],
			'sSelect'	=> '',
		),
	);

	$aSite = array();
	foreach(aSITE as $LPsSiteId => $LPsSiteText)
	{
		$aSite[$LPsSiteId] = array(
			'sText' => $LPsSiteText,
			'sSelect' => '',
		);
	}

	$aPage['aVar'] = array(
		'sSelDay'		=> $sSelDay,
		'sAccount'		=> $sAccount,
		'sNo'			=> $sNo,
		'nDone'		=> $nDone,
		'nStatus'		=> $nStatus,
		'nGame'		=> $nGame,
		'sMethod'		=> $sMethod,
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sSiteId'		=> $sSiteId,
	);


	#宣告結束

	#程式邏輯區

	foreach ($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if ($sSelDay == $LPsText)
		{
			$aDay[$LPsText]['sSelect'] = 'active';
		}
	}

	// 篩選隱藏會員
	if($aAdm['nAdmType'] != 1)
	{
		$sSQL = '	SELECT 	nUid
				FROM 		'. CLIENT_USER_HIDE .'
				WHERE 	nOnline = 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sHideUser .= $aRows['nUid'].',';
		}

		$sHideUser = substr($sHideUser,0,-1);
		if($sHideUser != '')
		{
			$sCondition1 .= ' AND  nUid NOT IN ( '.$sHideUser.' ) ';
			$sCondition2 .= ' AND  nUid NOT IN ( '.$sHideUser.' ) ';
		}

	}


	// 非經營者身分只能搜尋下線訂單
	if($aAdm['nUid'] != 0)
	{
		$sSearchUser = $aAdm['nUid'];

		$sSQL = '	SELECT 	nUid
				FROM 		'.CLIENT_USER_LINK.'
				WHERE 	nEndTime = 0
				AND		sLinkList LIKE :sLinkListBelow';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLinkListBelow',sprintf('%%%09d%%', $sSearchUser),PDO::PARAM_STR);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$sSearchUser .= ','.$aRows['nUid'];
		}
		$sCondition1 .= ' AND  nUid IN ( '.$sSearchUser.' ) ';
		$sCondition2 .= ' AND  nUid IN ( '.$sSearchUser.' ) ';
	}

	if ($sAccount != '')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'.CLIENT_USER_DATA.'
				WHERE 	sAccount LIKE :sAccount
				AND 		nOnline != 99 ';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount',$sAccount,PDO::PARAM_STR);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows !== false)
		{
			$sCondition1 .= ' AND nUid = '.$aRows['nId'];
			$sCondition2 .= ' AND nUid = '.$aRows['nId'];
		}
		else
		{
			$sCondition1 .= ' AND nUid = 0';
			$sCondition2 .= ' AND nUid = 0';
		}
	}
	if($sNo != '')
	{
		$sCondition1 .= ' AND sNo = :sNo ';
		$sCondition2 .= ' AND sNo = :sNo ';
		$aBindArray1['sNo'] = $sNo;
		$aBindArray2['sNo'] = $sNo;
	}
	if($nStatus != -1)
	{
		$sCondition1 .= ' AND nStatus = :nStatus ';
		$sCondition2 .= ' AND nStatus = :nStatus ';
		$aBindArray1['nStatus'] = $nStatus;
		$aBindArray2['nStatus'] = $nStatus;

		$aStatus[$nStatus]['sSelect'] = 'selected';
	}

	if($nDone != -1)
	{
		$sCondition1 .= ' AND nDone = :nDone ';
		$sCondition2 .= ' AND nDone = :nDone ';
		$aBindArray1['nDone'] = $nDone;
		$aBindArray2['nDone'] = $nDone;

		$aDone[$nDone]['sSelect'] = 'selected';
	}

	if($sMethod != '')
	{
		$aMethod[$sMethod]['sSelect'] = 'SELECTED';
		if($sMethod !== 'PLAYER' && $sMethod !== 'BANKER')
		{
			$sCondition1 .= ' AND sContent0 = :sMethod ';
			$aBindArray1['sMethod'] = $sMethod;
			$aMethod[$sMethod]['sSelect'] = 'selected';
			$sCondition2 .= ' AND 0 ';
		}
		else if($sMethod === 'PLAYER')
		{
			$sCondition2 .= ' AND 0 ';
		}
		else if($sMethod === 'BANKER')
		{
			$sCondition1 .= ' AND 0 ';
		}
	}

	if($sSiteId != '')
	{
		$sCondition1 .= ' AND sSiteId = :sSiteId ';
		$sCondition2 .= ' AND sSiteId = :sSiteId ';
		$aBindArray1['sSiteId'] = $sSiteId;
		$aBindArray2['sSiteId'] = $sSiteId;
		$aSite[$sSiteId]['sSelect'] = 'selected';
	}

	if($sBankerAccount != '')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'.CLIENT_USER_DATA.'
				WHERE 	sAccount LIKE :sAccount
				AND 		nOnline != 99 ';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount',$sBankerAccount,PDO::PARAM_STR);
		sql_query($Result);
		$aRow = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRow !== false)
		{
			$sSQL = '	SELECT	nId,
							sNo
					FROM 		'.CLIENT_GAMES_BANKER.'
					WHERE 	nUid = :nUid
					AND		nStatus = 1
					AND		nType0 = 0
					AND nCreateTime > :nStartTime
					AND nCreateTime < :nEndTime ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',$aRow['nId'],PDO::PARAM_INT);
			$Result->bindValue(':nStartTime',$nStartTime,PDO::PARAM_INT);
			$Result->bindValue(':nEndTime',$nEndTime,PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$sBankerNoList .= ','.$aRows['sNo'];
			}

			$sCondition .= ' AND sNo IN ('.$sBankerNoList.') ';
		}
		else
		{
			$sCondition .= ' AND sNo = 0';
		}
	}

	$aGame = array();
	$sSQL = '   SELECT	nId,
					nGame,
					sName0
			FROM   	'. END_GAMES_SETTING .'
			WHERE  	nOnline = 1
			AND		sLang LIKE :sLang
			ORDER BY nGame ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aGame[$aRows['nGame']] = array(
			'sText'	=> $aRows['sName0'],
			'sSelect'	=> '',
		);
	}

	if($nGame != -1)
	{
		$sCondition1 .= ' AND nGame = :nGame ';
		$sCondition2 .= ' AND nGame = :nGame ';
		$aBindArray1['nGame'] = $nGame;
		$aBindArray2['nGame'] = $nGame;
		$aGame[$nGame]['sSelect'] = 'selected';
	}

	$sSQL = '	SELECT 	nId,
					sWater
			FROM		'. CLIENT_USER_FS .'
			WHERE 	(nEndTime = 0 OR (nEndTime BETWEEN :nStartTime AND :nEndTime ))';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nStartTime',strtotime($sStartTime),PDO::PARAM_INT);
	$Result->bindValue(':nEndTime',strtotime($sEndTime),PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aFsData[$aRows['nId']] = $aRows['sWater'];
	}

	$sSQL = '	SELECT	nFsId,
					nDone,
					nStatus,
					nMoney0,
					nMoney1,
					\'\' as sMoney1
			FROM   	'. CLIENT_GAMES_DATA .'
			WHERE  	1'.
			$sCondition1.'
			UNION ALL
			SELECT	nFsId,
					nDone,
					nStatus,
					nMoney0,
					nMoney1,
					sMoney1
			FROM   	'. CLIENT_GAMES_BANKER .'
			WHERE  	1'.
			$sCondition2;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray1);
	sql_build_value($Result, $aBindArray2);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['aTotal']['nMoney0'] += $aRows['nMoney0'];
		$aData['aTotal']['nMoney1'] += $aRows['nMoney1'];
		$aData['aTotal']['nWin'] += ($aRows['nMoney0'] + $aRows['nMoney1']);

		if ( $aRows['nDone'] == 1 && $aRows['nStatus'] != 2 && isset($aFsData[$aRows['nFsId']]))
		{
			$nWater = ($aFsData[$aRows['nFsId']] * $aRows['nMoney0']) / 100;
			$aData['aTotal']['nWater'] += $nWater;
		}
		if($aRows['sMoney1'] !== '' && $aMoney1 = json_decode($aRows['sMoney1'],true))
		{
			foreach($aMoney1 as $LPnMoney)
			{
				if($LPnMoney > 0)
				{
					$aData['aTotal']['nRealWin'] += $LPnMoney;
				}
				else
				{
					$aData['aTotal']['nBetWin1'] += abs($LPnMoney);
				}
			}
		}
		else
		{
			if($aRows['nMoney1'] > 0)
			{
				$aData['aTotal']['nRealWin'] += $aRows['nMoney1'];
			}
			else
			{
				$aData['aTotal']['nBetWin1'] += abs($aRows['nMoney1']);
			}
		}
	}

	$nIndex = 0;
	$nI = 1;
	$sSQL = '   SELECT	nId,
					nUid,
					nGame,
					nFsId,
					nDone,
					nStatus,
					sContent0,
					sNo,
					nType0,
					nMoney0,
					nMoney1,
					sNums,
					nCreateTime,
					sCreateTime,
					sSiteId,
					sPerLink,
					\'\' as sMoney1
			FROM   	'. CLIENT_GAMES_DATA .'
			WHERE  	1'.
			$sCondition1.'
			UNION ALL
			SELECT	nId,
					nUid,
					nGame,
					nFsId,
					nDone,
					nStatus,
					\'BANKER\' as sContent0,
					sNo,
					nType0,
					nMoney0,
					nMoney1,
					\'\' as sNums,
					nCreateTime,
					sCreateTime,
					sSiteId,
					sPerLink,
					sMoney1
			FROM   	'. CLIENT_GAMES_BANKER .'
			WHERE  	1'.
			$sCondition2.'
			ORDER BY nCreateTime DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray1);
	sql_build_value($Result, $aBindArray2);
	sql_query($Result);
	$aLotteryId = array();
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aUser[] = $aRows['nUid'];
		$aData['aData'][$nI] = $aRows;
		$aData['aData'][$nI]['sGame'] = $aGame[$aRows['nGame']]['sText'];
		$aData['aData'][$nI]['sMethod'] = $aMethod[$aRows['sContent0']]['sText'];
		$aData['aData'][$nI]['nWin'] = $aRows['nMoney0'] + $aRows['nMoney1'];
		
		$aData['aData'][$nI]['sDone'] = $aDone[$aRows['nDone']]['sText'];
		$aData['aData'][$nI]['sStatus'] = $aStatus[$aRows['nStatus']]['sText'];
		$aData['aData'][$nI]['sClass'] = $aStatus[$aRows['nStatus']]['sClass'];
		
		$aData['aData'][$nI]['nWater'] = 0;
		
		$aData['aSubTotal']['nMoney0'] += $aRows['nMoney0'];
		$aData['aSubTotal']['nMoney1'] += $aRows['nMoney1'];
		$aData['aSubTotal']['nWin'] += ($aRows['nMoney0'] + $aRows['nMoney1']);

		// $sNoList .= ','.$aRows['sNo'];
		$aBankerName[$aRows['sNo']] = 0;
		
		if ( $aRows['nDone'] == 1 && $aRows['nStatus'] != 2 && isset($aFsData[$aRows['nFsId']]))
		{
			$nWater = ($aFsData[$aRows['nFsId']] * $aRows['nMoney0']) / 100;
			$aData['aData'][$nI]['nWater'] = $nWater;
			$aData['aSubTotal']['nWater'] += $nWater;
		}
		
		$aData['aData'][$nI]['aNums'] = json_decode($aData['aData'][$nI]['sNums'],true);
		if(isset($aData['aData'][$nI]['aNums']['A'])){			
			$aData['aData'][$nI]['sNumsText'] = aNUMSNAME['Z'][$aData['aData'][$nI]['aNums']['Z']].','.aNUMSNAME['A'][$aData['aData'][$nI]['aNums']['A']].','.aNUMSNAME['B'][$aData['aData'][$nI]['aNums']['B']].','.aNUMSNAME['C'][$aData['aData'][$nI]['aNums']['C']];
			if(isset($LPaResult['Money0']) && $LPaResult['Money1'])
			{
				$aData['aData'][$nI]['sResultText'] = '下注額度：';
				foreach($LPaResult['Money0'] as $LPsKey => $LPnResult)
				{
					$aData['aData'][$nI]['sResultText'] .= $aRESULTNAME[$LPsKey].$LPnResult.',';
				}
				$aData['aData'][$nI]['sResultText'] .= '<br>純贏：';
				foreach($LPaResult['Money1'] as $LPsKey => $LPaResult)
				{
					foreach($LPaResult as $LPsKey2 => $LPnResult2)
					{
						$aData['aData'][$nI]['sResultText'] .= $aRESULTNAME[$LPsKey].$LPsKey2.$LPnResult2.',';
					}
				}
			}
			if($aRows['nMoney1'] > 0)
			{
				$aData['aSubTotal']['nRealWin'] += $aRows['nMoney1'];
			}
			else
			{
				$aData['aSubTotal']['nBetWin1'] += abs($aRows['nMoney1']);
			}
		}
		else if($aMoney1 = json_decode($aData['aData'][$nI]['sMoney1'],true)){	
			$aData['aData'][$nI]['sNumsText'] = '';
			if(isset($aMoney1['A']))
			{
				$aData['aData'][$nI]['sNumsText'] .= aMETHOD['A'].$aMoney1['A'].',';
			}
			if(isset($aMoney1['B']))
			{
				$aData['aData'][$nI]['sNumsText'] .= aMETHOD['B'].$aMoney1['B'].',';
			}
			if(isset($aMoney1['C']))
			{
				$aData['aData'][$nI]['sNumsText'] .= aMETHOD['C'].$aMoney1['C'].',';
			}
			$aData['aData'][$nI]['sResult'] = '';
			$aData['aData'][$nI]['sResultText'] = '';
			foreach($aMoney1 as $LPnMoney)
			{
				if($LPnMoney > 0)
				{
					$aData['aSubTotal']['nRealWin'] += $LPnMoney;
				}
				else
				{
					$aData['aSubTotal']['nBetWin1'] += abs($LPnMoney);
				}
			}
		}
		else
		{
			$aData['aData'][$nI]['sResult'] = '';
			$aData['aData'][$nI]['sNumsText'] = '';
			$aData['aData'][$nI]['sResultText'] = '';
		}

		$aTempPerLink = explode(',',$aRows['sPerLink']);
		if(count($aTempPerLink) == 3)
		{
			$aData['aData'][$nI]['nPer'] = $aTempPerLink[1]-$aTempPerLink[2];
			$aData['aData'][$nI]['nPerMoney'] = ($aTempPerLink[1]-$aTempPerLink[2])/100 * $aRows['nMoney0'];
		}
		else
		{
			$aData['aData'][$nI]['nPer'] = 0;
			$aData['aData'][$nI]['nPerMoney'] = 0;
		}	
		$nI++;
	}
	
	$aUser = array_unique($aUser);
	foreach ($aUser as $LPnIndex => $LPnUid)
	{
		$sUidLink .= ',' . $LPnUid;
	}
	
	$sSQL = '	SELECT	_Pa.sAccount,
					_Child.nUid
			FROM 		'. CLIENT_USER_DATA .' as _Pa, (
						SELECT nUid,nPa
						FROM '.CLIENT_USER_LINK.'
						WHERE nUid In ('.$sUidLink.')
					) as _Child
			WHERE		_Child.nPa = _Pa.nId';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aUid2Pa[$aRows['nUid']] = $aRows['sAccount'];
	}

	$sSQL = '	SELECT	nId,
					sAccount
			FROM 		'.CLIENT_USER_DATA.'
			WHERE 	nId In ('.$sUidLink.')';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aUserName[$aRows['nId']] = $aRows['sAccount'];
	}

	$sNoList = 0;
	foreach($aBankerName as $LPsNo => $false)
	{
		$sNoList .= ','.$LPsNo;
	}
	# 莊家資訊
	$sSQL = '	SELECT	nId,
					sNo,
					nUid
			FROM 		'.CLIENT_GAMES_BANKER.'
			WHERE 	sNo In ('.$sNoList.')
			AND		nStatus = 1
			AND		nType0 = 0';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sBankerList .= ','.$aRows['nUid'];
		if(isset($aBankerName[$aRows['sNo']]))
		{
			$aBankerName[$aRows['sNo']] = $aRows['nUid'];
		}
	}

	$sSQL = '	SELECT	nId,
					sAccount
			FROM 		'.CLIENT_USER_DATA.'
			WHERE 	nId In ('.$sBankerList.')';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBanker[$aRows['nId']] = $aRows['sAccount'];
	}
	$aBanker[0] = '';

	foreach($aBankerName as $LPsNo => $LPnUid)
	{
		$aBankerName[$LPsNo] = $aBanker[$LPnUid];
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束

?>