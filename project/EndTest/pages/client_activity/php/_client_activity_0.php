<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_activity.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nKid		= filter_input_int('nKid', INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline', INPUT_REQUEST,-1);
	$sSearch	= filter_input_str('sSearch', INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']),
		'sHtml'	=> 'pages/client_activity/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_activity_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aKind = array();
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;
	#宣告結束

	#程式邏輯區

	if($nKid > 0)
	{
		$sCondition .= ' AND nKid = :nKid ';
		$aPage['aVar']['nKid'] = $nKid;
		$aBind['nKid'] = $nKid;
	}

	if($nOnline > -1)
	{
		$sCondition .= ' AND nOnline = :nOnline ';
		$aPage['aVar']['nOnline'] = $nOnline;
		$aBind['nOnline'] = $nOnline;
	}

	if($sSearch != '')
	{
		$sCondition .= ' AND sName0 LIKE :sSearch ';
		$aPage['aVar']['sSearch'] = $sSearch;
		$aBind['sSearch'] = '%'.$sSearch.'%';
	}

	$sSQL = '	SELECT	nLid,
					sName0
			FROM	'.	CLIENT_ACTIVITY_KIND .'
			WHERE		sLang LIKE :sLang
			AND		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aKind[$aRows['nLid']] = $aRows;
		$aKind[$aRows['nLid']]['sSelect'] = '';
		if($nKid == $aRows['nLid'])
		{
			$aKind[$aRows['nLid']]['sSelect'] = 'selected';
		}
	}

	foreach($aOnline as $LPnStatus => $LPaDetail)
	{
		if($nOnline == $LPnStatus)
		{
			$aOnline[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	nId,
					sName0,
					nLid,
					nKid,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM	'.	CLIENT_ACTIVITY .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			' . $sCondition . '
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}

	$aPage['nDataAmount'] = $nCount;

	$sSQL = '	SELECT	nId,
					sName0,
					nKid,
					nLid,
					nOnline,
					nGive,
					nSave,
					sStartTime,
					sEndTime,
					sCreateTime,
					sUpdateTime
			FROM	'.	CLIENT_ACTIVITY .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			'. $sCondition .'
			ORDER	BY	nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nLid']] = $aRows;
		$aData[$aRows['nLid']]['sKind'] = $aKind[$aRows['nKid']]['sName0'];

		$aData[$aRows['nLid']]['sIns'] = $aUrl['sIns'].'&nLid='.$aRows['nLid'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nLid'],
			't'		=> NOWTIME,
		);
		$sLPJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nLid']]['sDel'] = $aUrl['sDel'].'&nLid='.$aRows['nLid'].'&sJWT='.$sLPJWT;
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>