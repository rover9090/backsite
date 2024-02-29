<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_exchange_rate.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
	);

	#參數接收區
	// $aPostData = $_POST['aPostData'];
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_exchange_rate_0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_exchange_rate_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_exchange_rate_0.php',
	);
	#url結束

	#參數宣告區
	$aDay = aDAY;
	$aValue = array(
		'a'		=> 'RATE',
		'nExp'	=> NOWTIME + JWTWAIT,
	);
	$sActJWT = sys_jwt_encode($aValue);
	
	$aData = array();
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	sName0,
					sName1,
					nRate
			FROM 		'. END_EXCHANGE_RATE .'
			WHERE 	1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['sName0']][$aRows['sName1']] = $aRows['nRate'];
	}

	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>