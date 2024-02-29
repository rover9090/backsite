<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_chat_filter.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nOnline	= filter_input_int('nOnline', INPUT_REQUEST,-1);
	$sSearch	= filter_input_str('sSearch', INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chat_filter_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_chat_filter_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;
	#宣告結束

	#程式邏輯區

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

	foreach($aOnline as $LPnStatus => $LPaDetail)
	{
		if($nOnline == $LPnStatus)
		{
			$aOnline[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	nId,
					sName0,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_CHAT_FILTER .'
			WHERE		nOnline != 99
					' . $sCondition . '
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}

	$aPage['nDataAmount'] = $nCount;

	$sSQL = '	SELECT	nId,
					sName0,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_CHAT_FILTER .'
			WHERE		nOnline != 99
					' . $sCondition . '
			ORDER	BY	nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;

		$aData[$aRows['nId']]['sIns'] = $aUrl['sIns'].'&nId='.$aRows['nId'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nId'],
			't'		=> NOWTIME,
		);
		$sJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nId']]['sDel'] = $aUrl['sDel'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT;
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>