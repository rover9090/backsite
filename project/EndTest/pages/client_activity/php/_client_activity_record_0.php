<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_activity_record.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
	);
	#js結束

	#參數接收區
	$sStartTime		= filter_input_str('sStartTime', 	INPUT_REQUEST,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 	INPUT_REQUEST,date('Y-m-d 23:59:59'));
	$nAid			= filter_input_int('nAid', 		INPUT_REQUEST,0);
	$sAccount		= filter_input_str('sAccount', 	INPUT_REQUEST,'');
	$nStartTime 	= strtotime($sStartTime);
	$nEndTime 		= strtotime($sEndTime);
	

	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_record_0.php']),
		'sHtml'	=> 'pages/client_activity/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_activity_record_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aActivity = array();
	#宣告結束

	#程式邏輯區

	$sCondition .= ' AND Record_.nCreateTime >= :nStartTime AND Record_.nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$aBind['nEndTime'] = $nEndTime;

	if($nAid > 0)
	{
		$sCondition .= ' AND Record_.nAid = :nAid ';
		$aPage['aVar']['nAid'] = $nAid;
		$aBind['nAid'] = $nAid;
	}

	if($sAccount != '')
	{
		$aPage['aVar']['sAccount'] = '%'.$sAccount.'%';
		$sCondition .= '	AND User_.sAccount LIKE :sAccount ';
		$aBind['sAccount'] = '%'.$sAccount.'%';
	}

	$sSQL = '	SELECT	nLid,
					sName0
			FROM	'.	CLIENT_ACTIVITY .'
			WHERE		sLang LIKE :sLang
			AND		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aActivity[$aRows['nLid']] = $aRows;
		$aActivity[$aRows['nLid']]['sSelect'] = '';
		if($nAid == $aRows['nLid'])
		{
			$aActivity[$aRows['nLid']]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	1
			FROM		'.CLIENT_ACTIVITY_RECORD .' Record_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Record_.nUid = User_.nId
			' . $sCondition . '
			ORDER	BY	Record_.nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}

	$aPage['nDataAmount'] = $nCount;

	$sSQL = '	SELECT	User_.sAccount,
					Record_.nId,
					Record_.nUid,
					Record_.nAid,
					Record_.nGive,
					Record_.nUse,
					Record_.sCreateTime,
					Record_.sUpdateTime
			FROM		'.CLIENT_ACTIVITY_RECORD .' Record_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Record_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Record_.nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sActivity'] = $aActivity[$aRows['nAid']]['sName0'];
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>