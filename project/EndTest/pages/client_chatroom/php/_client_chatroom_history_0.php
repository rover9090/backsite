<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_chatroom_history.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/client_chatroom/client_chatroom_history.js',
	);
	#js結束

	#參數接收區
	$sAccount 	= filter_input_str('sAccount',	INPUT_REQUEST, '');
	$sText	= filter_input_str('sText',		INPUT_REQUEST, '');
	$sStartTime	= filter_input_str('sStartTime',	INPUT_REQUEST, date('Y-m-d 00:00:00'));
	$sEndTime	= filter_input_str('sEndTime',	INPUT_REQUEST, date('Y-m-d 23:59:59'));
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0_upt0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/client_chatroom/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_chatroom_history_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBindArray = array();
	$aSearchUid = array(0=>0);
	$aMember = array();
	$aPage['aVar'] = array(
		'sAccount'	=> $sAccount,
		'sText'	=> $sText,
		'sStartTime'=> $sStartTime,
		'sEndTime'	=> $sEndTime,
	);
	$nPageStart = $aPage['nNowNo']*$aPage['nPageSize'] - $aPage['nPageSize'] ;
	$sCondition = '';

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT;
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	$sCondition = ' AND nCreateTime >= :nStartTime AND nCreateTime <= :nEndTime ';
	$aBindArray['nStartTime'] = strtotime($sStartTime);
	$aBindArray['nEndTime'] = strtotime($sEndTime);
	if ($sAccount != '')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'. CLIENT_USER_DATA .'
				WHERE 	sAccount LIKE :sAccount';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount', '%'.$sAccount.'%', PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aSearchUid[$aRows['nId']] = $aRows['nId'];
		}
		$sCondition .= ' AND nUid IN ( '.implode(',', $aSearchUid).' ) ';
		$aSearchUid = array(0=>0);
	}
	if ($sText != '')
	{
		$sCondition .= ' AND sChat LIKE :sChat';
		$aBindArray['sChat'] = '%'.$sText.'%';
	}

	$sSQL = '	SELECT 	1
			FROM 		'. CLIENT_CHATROOM .'
			WHERE 	1
			'.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();

	$sSQL = '	SELECT 	nId,
					nUid,
					sChat,
					sUpdateTime
			FROM 		'. CLIENT_CHATROOM .'
			WHERE 	1
					'.$sCondition.'
			ORDER BY 	nUpdateTime DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$LPaTemp = json_decode($aRows['sChat'],true);
		$LPaTemp = array_pop($LPaTemp);
		$aData[$aRows['nId']]['sAccount'] = 'Guest'.$aRows['nId'];
		$aData[$aRows['nId']]['sLastChat'] = $LPaTemp['sText'];
		$aData[$aRows['nId']]['sUptUrl'] = $aUrl['sIns'].'&nId='.$aRows['nId'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nId'],
			't'		=> NOWTIME,
			'nId'		=> $aRows['nId'],
		);
		$sJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nId']]['sDelUrl'] =$aUrl['sDel'].'&sJWT='. $sJWT.'&nId='. $aRows['nId'].'&nt='. NOWTIME;
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
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>