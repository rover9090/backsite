<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_method_setting.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST,-1);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_method_setting_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_method_setting_0.php',
	);
	#url結束

	#參數宣告區
	$aSetting = array();
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sGame = '0';
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

	if($nGame > -1)
	{
		$sCondition .= ' AND nGame = :nGame ';
		$aPage['aVar']['nGame'] = $nGame;
		$aBind['nGame'] = $nGame;
	}

	$sSQL = '	SELECT	nGame,
					sName0
			FROM		'. END_GAMES_SETTING .'
			WHERE		nOnline = 1
			AND		sLang = :sLang
			ORDER	BY	sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aSetting[$aRows['nGame']] = $aRows;
		$aSetting[$aRows['nGame']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nId,
					sName0,
					nGame,
					nOnline,
					nOdds,
					nOdds1,
					nMinBet,
					nMaxBet,
					sCreateTime,
					sUpdateTime
			FROM		'. END_METHOD_SETTING .'
			WHERE		nOnline != 99
					' . $sCondition . '
			ORDER	BY	nId ASC ';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aSetting[$aRows['nGame']]))
		{
			continue;
		}
		$aData[$aRows['nGame']][$aRows['nId']] = $aRows;
		$aData[$aRows['nGame']][$aRows['nId']]['sIns'] = $aUrl['sIns'].'&nId='.$aRows['nId'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nId'],
			't'		=> NOWTIME,
		);
		$sLPJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nGame']][$aRows['nId']]['sDel'] = $aUrl['sDel'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;

		$nCount ++;
	}
	$aPage['nDataAmount'] = $nCount;

	// $aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>