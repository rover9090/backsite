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
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, aDAY['TODAY']['sStartDay']);
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, aDAY['TODAY']['sEndDay']);
	$sAccount	= filter_input_str('sAccount',	INPUT_REQUEST, '');
	$nType0	= filter_input_int('nType0',		INPUT_REQUEST, -1);
	$nType2	= filter_input_int('nType2',		INPUT_REQUEST, -1);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_POST, 'TODAY');
	$sSiteId 	= filter_input_str('sSite',		INPUT_POST, '');
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_donate_0.php']),
		'sUpt'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_donate_0_upt0.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_report_donate_0.php',
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
	$sExcelVar .= '&nSelDay='.$nSelDay.'&sStartTime='.$sStartTime.'&sEndTime='.$sEndTime.'&sAccount='.$sAccount.'&nType0='.$nType0.'&nType2='.$nType2;
	// $aUrl['sExcel'] = $aUrl['sAct'].$sExcelVar;
	$nTotalCount = 0;
	$nTotalMoney = 0;

	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$aPage['aVar'] = array(
		'nSelDay'		=> $nSelDay,
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sAccount'		=> $sAccount,
		'nType0'		=> $nType0,
		'nType2'		=> $nType2,
	);
	$aSearch = array();
	$aHideMember = array();
	$aData = array(
		// 'aData'	=> array(),
		// 'aSubTotal' => array(
		// 	'nBefore' 	=> 0,
		// 	'nDelta'	=> 0,
		// 	'nAfter'	=> 0,
		// ),
		// 'aTotal' 	=> array(
		// 	'nBefore' 	=> 0,
		// 	'nDelta'	=> 0,
		// 	'nAfter'	=> 0,
		// ),
	);
	$aType0 = array(
		'-1'		=> array(
			'sName' 	=> aLOG['ALL'],
			'sSelect' 	=> '',
		),
		'1'	=> array(
			'sName' 	=> aLOG['GAME'],
			'sSelect' 	=> '',
		),
		'2'	=> array(
			'sName' 	=> aLOG['CASHFLOW'],
			'sSelect' 	=> '',
		),
		'3'	=> array(
			'sName' 	=> aLOG['DONATE'],
			'sSelect' 	=> '',
		),
	);

	$aType2 = array(
		'-1'		=> array(
			'sName' 	=> aLOG['ALL'],
			'sSelect' 	=> '',
		),
		'0'	=> array(
			'sName' 	=> aLOG['TYPEMONEY'],
			'sSelect' 	=> '',
		),

	);
	// $aSite = array();
	// foreach(aSITE as $LPsSiteId => $LPsSiteText)
	// {
	// 	$aSite[$LPsSiteId] = array(
	// 		'sText' => $LPsSiteText,
	// 		'sSelect' => '',
	// 	);
	// }

	$aMember[0] = array(
		'sAccount' => '',
		'nPa' => '',
	);
	$sCondition = ' WHERE nCreateTime >= :nStartTime AND nCreateTime <= :nEndTime';
	$aBindValue = array(
		'nStartTime'=> $nStartTime,
		'nEndTime' 	=> $nEndTime,
	);
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];

	$sGamesNumsId = '0';
	$aGamesNums = array('0'=>'');
	#宣告結束

	#程式邏輯區
	// $sSQL = '	SELECT 	nUid
	// 		FROM 		'. CLIENT_USER_HIDE .'
	// 		WHERE 	nOnline = 1';
	// $Result = $oPdo->prepare($sSQL);
	// sql_query($Result);
	// while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aHideMember[$aRows['nUid']] = $aRows['nUid'];
	// }

	// if ($sAccount != '')
	// {
	// 	$sSQL = '	SELECT 	nId
	// 			FROM 		'. CLIENT_USER_DATA .'
	// 			WHERE 	sAccount LIKE :sAccount
	// 			LIMIT 	1';
	// 	$Result = $oPdo->prepare($sSQL);
	// 	$Result->bindValue(':sAccount',$sAccount,PDO::PARAM_STR);
	// 	sql_query($Result);
	// 	$aRows = $Result->fetch(PDO::FETCH_ASSOC);

	// 	$sCondition .= ' AND nUid = :nUid';
	// 	$aBindValue['nUid'] = $aRows['nId'];
	// }
	// if ($nType0 != -1)
	// {
	// 	$sCondition .= ' AND nType0 = :nType0';
	// 	$aBindValue['nType0'] = $nType0;
	// }
	// if ($nType2 != -1)
	// {
	// 	$sCondition .= ' AND nType2 = :nType2';
	// 	$aBindValue['nType2'] = $nType2;
	// }
	// if ( !empty($aHideMember) && $aAdm['nAdmType'] != 1)
	// {
	// 	$sCondition .= ' AND  nUid NOT IN ( '.implode(',', $aHideMember).' ) ';
	// }
	// if($sSiteId != '')
	// {
	// 	$sCondition .= 'AND sSiteId = :sSiteId ';
	// 	$aBindArray['sSiteId'] = $sSiteId;
	// 	$aSite[$sSiteId]['sSelect'] = 'selected';
	// }

	// $sSQL = '	SELECT	nBefore,
	// 				nDelta,
	// 				nAfter
	// 		FROM 		'. END_LOG_ACCOUNT .'
	// 				'.$sCondition.'
	// 		ORDER BY 	nCreateTime DESC';
	// $Result = $oPdo->prepare($sSQL);
	// sql_build_value($Result, $aBindValue);
	// sql_query($Result);
	// $aPage['nDataAmount'] = $nTotalCount = $Result->rowCount();
	// while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aData['aTotal']['nBefore'] += $aRows['nBefore'];
	// 	$aData['aTotal']['nDelta'] += $aRows['nDelta'];
	// 	$aData['aTotal']['nAfter'] += $aRows['nAfter'];
	// }


	// $sSQL = '	SELECT 	nId,
	// 				nUid,
	// 				nKid,
	// 				nFromUid,
	// 				nType0,
	// 				nType1,
	// 				nType2,
	// 				nBefore,
	// 				nDelta,
	// 				nAfter,
	// 				sCreateTime,
	// 				sSiteId
	// 		FROM 		'. END_LOG_ACCOUNT .'
	// 				'.$sCondition.'
	// 		AND 		nType2 != 1
	// 		AND 		nDelta != 0
	// 		ORDER BY 	nCreateTime DESC, nId DESC
	// 		'.sql_limit($nPageStart, $aPage['nPageSize']);
	// $Result = $oPdo->prepare($sSQL);
	// sql_build_value($Result, $aBindValue);
	// sql_query($Result);
	// while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aData['aData'][$aRows['nId']] = $aRows;
	// 	$aGamesId[] = $aRows['nKid'];
	// 	$aSearch['aUser'][$aRows['nUid']] = $aRows['nUid'];
	// }

	// if (!empty($aSearch['aUser']))
	// {

	// 	$sSQL = '	SELECT	nId,
	// 					sAccount
	// 			FROM 		'. CLIENT_USER_DATA .'
	// 			WHERE		nOnline != 99
	// 			AND 		nId IN ( '.implode(',',$aSearch['aUser']).' )';
	// 	$Result = $oPdo->prepare($sSQL);
	// 	sql_query($Result);
	// 	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// 	{
	// 		$aUserData[$aRows['nId']] = $aRows['sAccount'];
	// 	}
	// }

	// array_unique($aGamesId);
	// foreach ($aGamesId as $LPnIndex => $LPnKid)
	// {
	// 	$sGamesNumsId .= ',' . $LPnKid;
	// }

	// $sSQL = '	SELECT	nId,
	// 				sNo
	// 		FROM 		'. CLIENT_GAMES_DATA .'
	// 		WHERE		nId IN ( '. $sGamesNumsId .' )';
	// $Result = $oPdo->prepare($sSQL);
	// sql_query($Result);
	// while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aGamesNums[$aRows['nId']] = $aRows['sNo'];
	// }

	// foreach ($aData['aData'] as $LPnId => $LPaData)
	// {
	// 	$aData['aData'][$LPnId]['sAccount'] = $aUserData[$LPaData['nUid']];
	// 	$aData['aData'][$LPnId]['sType0'] =	$aType0[$LPaData['nType0']]['sName'];
	// 	$aData['aData'][$LPnId]['sType1'] = aTYPE1[$LPaData['nType1']];
	// 	$aData['aData'][$LPnId]['sType2'] =	$aType2[$LPaData['nType2']]['sName'];
	// 	$aData['aData'][$LPnId]['sFromAccount'] = '';
	// 	if($LPaData['nFromUid'] != 0 && isset($aUserData[$LPaData['nFromUid']]))
	// 	{
	// 		$aData['aData'][$LPnId]['sFromAccount'] = $aUserData[$LPaData['nFromUid']];
	// 	}
	// 	$aData['aSubTotal']['nBefore'] += $LPaData['nBefore'];
	// 	$aData['aSubTotal']['nDelta'] += $LPaData['nDelta'];
	// 	$aData['aSubTotal']['nAfter'] += $LPaData['nAfter'];
	// }
	// $aType0[$nType0]['sSelect'] = 'selected';
	// $aType2[$nType2]['sSelect'] = 'selected';

	$sSQL = '	SELECT 	nId,
					sAccount,
					sName0,
					sPer
			FROM  	'. END_MANAGER_DATA .'
			WHERE 	nOnline != 99
			AND		nAdmType = 4 ';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['sPer'] = (float)$aRows['sPer'];
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['nDoate'] = 0;
		$aData[$aRows['nId']]['nTotalPer'] = 0;
		$aData[$aRows['nId']]['sUpt'] = $aUrl['sUpt'].'&sStartTime='.$sStartTime.'&sEndTime='.$sEndTime.'&sName0='.$aRows['sName0'].'&sSelDay='.$sSelDay;
	}

	$sSQL = '	SELECT 	nId,
					sDealer,
					nMoney0,
					sPer
			FROM  	'. CLIENT_GAMES_DONATE .'
					'.$sCondition.'';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindValue);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!empty($aData[$aRows['sDealer']]))
		{
			$aData[$aRows['sDealer']]['nDoate'] += ($aRows['nMoney0']);
			$aData[$aRows['sDealer']]['nTotalPer'] += ($aRows['nMoney0'])*($aRows['sPer']/100);
			$nTotalMoney += ($aRows['nMoney0']);
		}
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