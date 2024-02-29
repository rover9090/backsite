<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_setting.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nLid		= filter_input_int('nLid', 		INPUT_GET,	0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_setting_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aGetNumsSet = array();
	$aGroupKind = array();
	$aValue = array(
		'a'		=> ($nLid == 0) ? 'INS' : 'UPT'.$nLid,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);

	$aCountBall = array(
		'0'	=> array(
			'sText'	=> '0',
			'sSelect'	=> '',
			'sClass'	=> '',
		),
		'6'	=> array(
			'sText'	=> '6',
			'sSelect'	=> '',
			'sClass'	=> '',
		),
	);

	$aMance = array(
		'0'	=> array(
			'sText'	=> aGAMESETTING['MAINTENANCEOFF'],
			'sSelect'	=> '',
			'sClass'	=> '',
		),
		'1'	=> array(
			'sText'	=> aGAMESETTING['MAINTENANCEON'],
			'sSelect'	=> '',
			'sClass'	=> '',
		),
	);

	$nErr = 0;
	$sErrMsg = '';

	$aOnline = aONLINE;
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nId,
					nLid,
					sName0
			FROM		'. END_GAMES_GROUP_KIND .'
			WHERE		nOnline = 1
			AND		sLang LIKE :sLang
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aGroupKind[$aRows['nLid']] = $aRows;
		$aGroupKind[$aRows['nLid']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nId,
					nLid,
					sLang,
					nGame,
					sName0,
					nCount,
					nSec,
					nSec1,
					nSec2,
					sOpenTime,
					sCloseTime,
					sNightTime,
					nOnline,
					nGroupId,
					nMaintenance,
					nWinPercent
			FROM		'. END_GAMES_SETTING .'
			WHERE		nLid = :nLid
			AND		nOnline != 99
			ORDER	BY 	sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['sLang']] = $aRows;
	}

	if(empty($aData) && $nLid != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if($nLid != 0)
	{
		$nCountBall = ($aData[$aSystem['sLang']]['nCount'] < 10) ? $aData[$aSystem['sLang']]['nCount'] : $aData[$aSystem['sLang']]['nCount'];
		$aOnline[$aData[$aSystem['sLang']]['nOnline']]['sSelect'] = 'selected';
		$aCountBall[$nCountBall]['sSelect'] = 'selected';
		$aGroupKind[$aData[$aSystem['sLang']]['nGroupId']]['sSelect'] = 'selected';
		$aMance[$aData[$aSystem['sLang']]['nMaintenance']]['sSelect'] = 'selected';
	}

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if($nErr == 1)
	{
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['sClicktoClose'] = 1;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>