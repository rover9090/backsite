<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_group.php');
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
		'sInsKind'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_0_upt0.php']),
		'sDelKind'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_0_act0.php']).'&run_page=1',
		'sPageKind'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_0.php']),
		'sIns'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_group_0.php',
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
					nLid,
					sName0,
					nSort,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_GAMES_GROUP_KIND .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
					' . $sCondition . '
			ORDER	BY	nSort DESC';
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
					nLid,
					sName0,
					nSort,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_GAMES_GROUP_KIND .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
					' . $sCondition . '
			ORDER	BY	nSort DESC '.sql_limit($nPageStart, $aPage['nPageSize']);;
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nLid']] = $aRows;
		$aData[$aRows['nLid']]['sPage'] = $aUrl['sPage'].'&nKindId='.$aRows['nLid'];

		$aData[$aRows['nLid']]['sIns'] = $aUrl['sInsKind'].'&nKindId='.$aRows['nLid'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nLid'],
			't'		=> NOWTIME,
		);
		$sJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nLid']]['sDel'] = $aUrl['sDelKind'].'&nKindId='.$aRows['nLid'].'&sJWT='.$sJWT;
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>