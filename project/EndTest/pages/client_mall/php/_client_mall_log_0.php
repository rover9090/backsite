<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	// require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_mall_log.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/client_mall/client_mall_log.js',
	);
	#js結束

	#參數接收區
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, aDAY['TODAY']['sStartDay']);
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, aDAY['TODAY']['sEndDay']);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_REQUEST, 'TODAY');
	$sAccount 	= filter_input_str('sAccount',	INPUT_REQUEST, '');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_log_0.php']),
		'sHtml'	=> 'pages/client_mall/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_mall_log_0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);

	$aPage['nPageSize'] = 20;
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];

	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$sUid = '0';
	$aDay = aDAY;
	$aOnline = aONLINE;

	$sCondition .= ' AND nCreateTime > :nStartTime ';
	$aBind['nStartTime']	= $nStartTime;
	$sCondition .= ' AND nCreateTime < :nEndTime ';
	$aBind['nEndTime']	= $nEndTime;

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區

	// if($nSort > -1)
	// {
	// 	$sCondition .= ' AND nSort = :nSort ';
	// 	$aPage['aVar']['nSort'] = $nSort;
	// 	$aBind['nSort'] = $nSort;
	// }

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
			$sCondition .= ' AND nUid = '.$aRows['nId'];
		}
		else
		{
			$sCondition .= ' AND nUid = 0';
		}
	}

	$sSQL = '	SELECT	nId,
					sName0
			FROM		'. CLIENT_MALL .'
			WHERE		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMall[$aRows['nId']] = $aRows;			
	}

	$nTotal = 0;
	$sSQL = '	SELECT	nId,
					nSumCost
			FROM		'. CLIENT_MALL_LOG .'
			WHERE		1
					'.$sCondition.'
			ORDER	BY	nId DESC ';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nTotal += $aRows['nSumCost'];
	}
	
	$nSubTotal = 0;
	$sSQL = '	SELECT	nId,
					nUid,
					nMid,
					nMkid,
					nCount,
					nPrice,
					nSumCost,
					nStatus,
					nCreateDay
			FROM		'. CLIENT_MALL_LOG .'
			WHERE		1
					'.$sCondition.'
			ORDER	BY	nId DESC '.
			sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sCreateDay'] = date('Y-m-d',$aRows['nCreateDay']);
		$nSubTotal += $aRows['nSumCost'];
		$sUid .= ','.$aRows['nUid'];
	}

	$sSQL = '	SELECT	nId,
					sName0,
					sAccount
			FROM		'. CLIENT_USER_DATA .'
			WHERE		nId IN ( '.$sUid.' )';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aUserDetail[$aRows['nId']] = $aRows;			
	}

	foreach ($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if ($sSelDay == $LPsText)
		{
			$aDay[$LPsText]['sSelect'] = 'active';
		}
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>