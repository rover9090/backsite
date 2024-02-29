<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_report_donate.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_report/end_log_account.js',
	);

	#參數接收區
	$nSelDay	= filter_input_int('nSelDay',		INPUT_REQUEST, 1);
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, date('Y-m-d 00:00:00' , NOWTIME));
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, date('Y-m-d 23:59:59' , NOWTIME));
	$sName0	= filter_input_str('sName0',		INPUT_REQUEST, '');
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_POST, 'TODAY');
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sList'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_donate_0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_donate_0_upt0.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_report_donate_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aDay = aDAY;
	$aValue = array(
		'a'		=> 'EXCEL',
		'nExp'	=> NOWTIME + JWTWAIT,
	);
	$sExcelJWT = sys_jwt_encode($aValue);
	$sExcelVar = '&sJWT='.$sExcelJWT;
	$sExcelVar .= '&nSelDay='.$nSelDay.'&sStartTime='.$sStartTime.'&sEndTime='.$sEndTime.'&sName0='.$sName0;
	// $aUrl['sExcel'] = $aUrl['sAct'].$sExcelVar;
	$nTotalCount = 0;

	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$aPage['aVar'] = array(
		'nSelDay'		=> $nSelDay,
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sName0'		=> $sName0,
	);
	$aSearch = array();
	$aHideMember = array();
	$aData = array(
		'aData'	=> array(),
		'aSubTotal' => array(
			'nDelta'	=> 0,
			'sPerMoney' => 0,
		),
		'aTotal' 	=> array(
			'nDelta'	=> 0,
			'sPerMoney' => 0,
		),
	);

	$aUserData = array();
	$aMember[0] = array(
		'sAccount' => '',
		'sName0' => '',
		'nPa' => '',
	);
	$sCondition = ' WHERE nCreateTime >= :nStartTime AND nCreateTime <= :nEndTime ';
	$aBindValue = array(
		'nStartTime'=> $nStartTime,
		'nEndTime' 	=> $nEndTime,
	);
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];

	$sGamesNumsId = '0';
	$aGamesNums = array('0'=>'');
	$aGamesId = array();
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	nUid
			FROM 		'. CLIENT_USER_HIDE .'
			WHERE 	nOnline = 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aHideMember[$aRows['nUid']] = $aRows['nUid'];
	}

	if ($sName0 != '')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'. END_MANAGER_DATA .'
				WHERE 	sName0 LIKE :sName0
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sName0',$sName0,PDO::PARAM_STR);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);

		$sCondition .= ' AND sDealer = :sDealer';
		$aBindValue['sDealer'] = $aRows['nId'];
	}

	if ( !empty($aHideMember) && $aAdm['nAdmType'] != 1)
	{
		$sCondition .= ' AND  nUid NOT IN ( '.implode(',', $aHideMember).' ) ';
	}

	$sSQL = '	SELECT	nId,
					nGame,
					sName0
			FROM		'. END_GAMES_SETTING .'
			WHERE		sLang LIKE :sLang
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aGamesId[$aRows['nGame']] = $aRows;
	}

	$sSQL = '	SELECT	nMoney0,
					sPer
			FROM 		'. CLIENT_GAMES_DONATE .'
					'.$sCondition.'
			ORDER BY 	nCreateTime DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindValue);
	sql_query($Result);
	$aPage['nDataAmount'] = $nTotalCount = $Result->rowCount();
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['aTotal']['nDelta'] += $aRows['nMoney0'];
		$aData['aTotal']['sPerMoney'] += $aRows['nMoney0']*$aRows['sPer']/100;
	}
	// $aData['aTotal']['nDelta'] *= -1;


	$sSQL = '	SELECT 	nId,
					nUid,
					nMoney0,
					sCreateTime,
					sDealer,
					sPer,
					nGame
			FROM 		'. CLIENT_GAMES_DONATE .'
					'.$sCondition.'
			AND 		nMoney0 != 0
			ORDER BY 	nCreateTime DESC, nId DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindValue);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		// $aRows['nMoney0'] *= -1;
		$aRows['sPer'] = (float)$aRows['sPer'];
		$aRows['nMoney0'] = (float)$aRows['nMoney0'];
		$aData['aData'][$aRows['nId']] = $aRows;
		$aData['aData'][$aRows['nId']]['sPerMoney'] = $aRows['sPer']*$aRows['nMoney0']/100;
		// $aGamesId[] = $aRows['nGame'];
		$aSearch['aDealer'][$aRows['sDealer']] = $aRows['sDealer'];
		$aSearch['aUser'][$aRows['nUid']] = $aRows['nUid'];
	}

	if (!empty($aSearch['aUser']))
	{

		$sSQL = '	SELECT	nId,
						sAccount,
						nBlack
				FROM 		'. CLIENT_USER_DATA .'
				WHERE		nOnline != 99
				AND 		nId IN ( '.implode(',',$aSearch['aUser']).' )';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aUserData[$aRows['nId']]['sAccount'] = $aRows['sAccount'];
			$aUserData[$aRows['nId']]['sColor'] = '';
			$nAccountType = substr($aRows['sAccount'],-1);
			if($nAccountType == '0')
			{
				$aUserData[$aRows['nId']]['sColor'] = 'FontGreen';
			}
			if($aRows['nBlack'] == 1)
			{
				$aUserData[$aRows['nId']]['sColor'] = 'FontRed';
			}
		}
	}

	if (!empty($aSearch['aDealer']))
	{

		$sSQL = '	SELECT	nId,
						sAccount,
						sName0
				FROM 		'. END_MANAGER_DATA .'
				WHERE		nOnline != 99
				AND 		nId IN ( '.implode(',',$aSearch['aDealer']).' )';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aDealer[$aRows['nId']] = $aRows['sName0'];
		}
	}


	foreach ($aData['aData'] as $LPnId => $LPaData)
	{
		$aData['aData'][$LPnId]['sUser'] = $aUserData[$LPaData['nUid']]['sAccount'];
		$aData['aData'][$LPnId]['sUserColor'] = $aUserData[$LPaData['nUid']]['sColor'];
		$aData['aData'][$LPnId]['sName0'] = $aDealer[$LPaData['sDealer']];
		$aData['aSubTotal']['nDelta'] += $LPaData['nMoney0'];
		$aData['aSubTotal']['sPerMoney'] += $LPaData['sPerMoney'];
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);

	foreach($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if($sSelDay == $LPsText)
		{
			if(($LPaDate['sStartDay']==$sStartTime)&&($LPaDate['sEndDay']==$sEndTime))
			{
				$aDay[$LPsText]['sSelect'] = 'active';
			}
		}
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>