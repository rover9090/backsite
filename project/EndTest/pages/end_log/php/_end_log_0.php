<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_log.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_log/end_log.js',
	);
	#js結束

	#參數接收區
	$sAccount 	 = filter_input_str('sAccount',	INPUT_REQUEST, '');
	$sIp		 = filter_input_str('sIp',		INPUT_REQUEST, '');
	$sStartTime	 = filter_input_str('sStartTime',	INPUT_REQUEST, date('Y-m-d 00:00:00'));
	$sEndTime	 = filter_input_str('sEndTime',	INPUT_REQUEST, date('Y-m-d 23:59:59'));
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_log/php/_end_log_0.php']),
		'sHtml'	=> 'pages/end_log/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_log_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aSearchId = array();
	$aManagerData = array(
		'0'	=> '',
	);
	$aUserData = array(
		'0'	=> '',
	);
	$aHideMember = array();
	$aBindArray = array();
	$aPage['aVar'] = array(
		'sAccount'	=> $sAccount,
		'sIp'		=> $sIp,
		'sStartTime'=> $sStartTime,
		'sEndTime'	=> $sEndTime,
	);
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sCondition = '';
	#宣告結束

	#程式邏輯區
	# 篩選隱藏會員
	$sSQL = '	SELECT 	nId
			FROM 		'. END_MANAGER_DATA .'
			WHERE 	nType1 = 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aHideMember[$aRows['nId']] = $aRows['nId'];
	}

	$sCondition = 'WHERE nCreateTime >= :nStartTime AND nCreateTime <= :nEndTime ';
	$aBindArray['nStartTime'] = strtotime($sStartTime);
	$aBindArray['nEndTime'] = strtotime($sEndTime);
	if ($sAccount != '')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'. END_MANAGER_DATA .'
				WHERE 	sAccount LIKE :sAccount
				AND 		nOnline != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount', '%'.$sAccount.'%', PDO::PARAM_STR);
		sql_query($Result);
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aSearchId[$aRows['nId']] = $aRows['nId'];
		}
		if (!empty($aSearchId))
		{
			$sCondition .= ' AND (nWho IN ('.implode(',', $aSearchId).') )';
		}
	}
	if ($sIp != '')
	{
		$sCondition .= ' AND sIp LIKE :sIp';
		$aBindArray['sIp'] = '%'.$sIp.'%';
	}
	if ( !empty($aHideMember) && $aAdm['nAdmType'] != 1)
	{
		$sCondition .= ' AND  nWho NOT IN ( '.implode(',', $aHideMember).' ) ';
	}

	$sSQL = '	SELECT 	nId,sAccount
			FROM 		'. END_MANAGER_DATA .'
			WHERE 	1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aManagerData[$aRows['nId']] = $aRows['sAccount'];
	}

	$sSQL = '	SELECT 	nId,sAccount
			FROM 		'. CLIENT_USER_DATA .'
			WHERE 	1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aUserData[$aRows['nId']] = $aRows['sAccount'];
	}

	$sSQL = '	SELECT 	1
			FROM 		'. END_LOG .'
					'.$sCondition.'
			AND 		nLogCode LIKE \'8%\'';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();

	$sSQL = 'SELECT 	nId,
				nWho,
				nWhom,
				nKid,
				sIp,
				nLogCode,
				sCreateTime
		FROM 		'. END_LOG .'
				'.$sCondition.'
		AND 		nLogCode LIKE \'8%\'
		ORDER BY 	nId DESC
		'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sWho'] = $aManagerData[$aRows['nWho']];
		$aData[$aRows['nId']]['sCode'] = $aSystem['aLogNums'][$aRows['nLogCode']];
		if (strpos($aRows['nLogCode'], '8103')!== false || strpos($aRows['nLogCode'], '8105')!== false || strpos($aRows['nLogCode'], '8107')!== false)
		{
			$aData[$aRows['nId']]['sWhom'] = $aUserData[$aRows['nWhom']];
		}
		else
		{
			$aData[$aRows['nId']]['sWhom'] = $aManagerData[$aRows['nWhom']];
		}
		if ($aRows['nKid'] == 0)
		{
			$aData[$aRows['nId']]['nKid'] = '';
		}
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>