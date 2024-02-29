<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_rule.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, 1001);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_rule_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_rule_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_rule_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_rule_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aGameSetting = array();
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

	$sSQL = '	SELECT	nId,
					nGame,
					sName0,
					nOnline
			FROM		'. END_GAMES_SETTING .'
			WHERE		nOnline = 1
			AND	sLang = :sLang
			ORDER BY sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aGameSetting[$aRows['nGame']] = $aRows;
		$aGameSetting[$aRows['nGame']]['sSelect'] = '';
	}

	if($nGame > 0)
	{
		$sCondition .= ' AND nGame = :nGame ';
		$aGameSetting[$nGame]['sSelect'] = 'selected';
		$aPage['aVar']['nGame'] = $nGame;
		$aBind['nGame'] = $nGame;
	}

	$sSQL = '	SELECT	1
			FROM		'. END_GAMES_RULE .'
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
					nGame,
					sContent0,
					nLid,
					nOnline,
					sUpdateTime,
					sCreateTime
			FROM		'. END_GAMES_RULE .'
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
		$aData[$aRows['nLid']]['sGame'] = $aGameSetting[$aRows['nGame']]['sName0'];

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