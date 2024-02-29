<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_chatroom.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/client_chatroom/client_chatroom.js',
	);
	#js結束

	#參數接收區
	$sAccount	= filter_input_str('sAccount',	INPUT_REQUEST, '');
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sAjax'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/client_chatroom/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_chatroom_0.php'
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aKind = array();
	$aBindArray = array();
	$aSearchUid = array(0=>0);
	$aSearchKind = array(0=>0);
	$aPage['aVar'] = array(
		'sAccount' => $sAccount,
	);
	$nPageStart = $aPage['nNowNo']*$aPage['nPageSize'] - $aPage['nPageSize'] ;
	$sCondition = '';
	$aValue = array(
		'a'		=> 'CHAT',
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aUrl['sAjax'] .= '&sJWT='.$sJWT;

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = '';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	/*
	對話內容
	array(
		0 => array(
			'sText' => 'xxxx',
			'nTime' => '',
			'nWho'  => '', 0=>user 1=>admin
		),
		1 => array(
			'sText' => 'xxxx',
			'nTime' => '',
			'nWho'  => '', 0=>user 1=>admin
		),
	)
	*/
	$sSQL = '	SELECT	nLid,
					sName0
			FROM		'. END_CANNED_KIND .'
			WHERE		sLang LIKE :sLang
			AND 		nOnline = 1
			ORDER	BY 	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aKind[$aRows['nLid']] = $aRows;
		$aKind[$aRows['nLid']]['aList'] = array();
		$aSearchKind[$aRows['nLid']] = $aRows['nLid'];
	}

	$sSQL = '	SELECT	nLid,
					nKid,
					sContent0
			FROM		'. END_CANNED .'
			WHERE		sLang LIKE :sLang
			AND 		nOnline = 1
			AND 		nKid IN ( '.implode(',', $aSearchKind).' )
			ORDER	BY 	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aKind[$aRows['nKid']]['aList'][$aRows['nLid']] = $aRows;
	}

	$sSQL = '	SELECT 	nId,
					nUid,
					nAdminStatus,
					sUpdateTime,
					sChat
			FROM 		'. CLIENT_CHATROOM .'
			WHERE 	nOnline = 1
			AND 		(
						nCreateTime >= '.strtotime('today').'
					OR 	nUpdateTime >= '.strtotime('today').'
					)
			ORDER BY 	nUpdateTime DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sAccount'] = 'Guest'.$aRows['nId'];
		$aSearchUid[$aRows['nUid']] = $aRows['nUid'];
	}
	$sSQL = '	SELECT 	nId,
					sAccount
			FROM 		'. CLIENT_USER_DATA .'
			WHERE 	nId IN ( '.implode(',', $aSearchUid).' )';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMember[$aRows['nId']] = $aRows['sAccount'];
	}

	foreach ($aData as $LPnId => $LPaData)
	{

		if ($LPaData['nUid'] == 0)
		{
			continue;
		}
		$aData[$LPnId]['sAccount'] = $aMember[$LPaData['nUid']];
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>