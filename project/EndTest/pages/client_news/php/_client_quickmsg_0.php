<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_quickmsg.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_quickmsg_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_quickmsg_0.php']),
		'sHtml'	=> 'pages/client_news/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_quickmsg_0.php',
	);
	#url結束

	#參數宣告區
	$aTempData = array();
	$aData = array();
	$aValue = array(
		'a'		=> 'UPT',
		't'		=> NOWTIME,
	);
	$sJWTAct = sys_jwt_encode($aValue);
	$aOnline = aONLINE;
	$aOnline['99'] = array(
		'sText'	=> DEL,
		'sSelect'	=> '',
		'sClass'	=> '',
	);

	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	nId,
					sName0,
					sMessage,
					nOnline,
					sUpdateTime
			FROM 		' . CLIENT_QUICKMSG.'
			WHERE		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;

		$aData[$aRows['nId']]['aOnlineSelect'] = $aOnline;
		$aData[$aRows['nId']]['aOnlineSelect'][$aRows['nOnline']]['sSelect'] = 'SELECTED';
	}

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>