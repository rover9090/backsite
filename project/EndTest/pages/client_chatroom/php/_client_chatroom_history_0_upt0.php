<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_chatroom_history.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nId	= filter_input_int('nId',		INPUT_GET, 0);
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_client_chatroom_history_0.php']),
		'sHtml'	=> 'pages/client_chatroom/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_chatroom_history_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$nErr = 0;
	$sErrMsg = '';
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	nId,
					nUid,
					sCreateTime,
					sChat
			FROM 		'. CLIENT_CHATROOM .'
			WHERE 	nId = :nId
			LIMIT 	1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData = $aRows;
		$aData['sAccount'] = 'Guest'.$aRows['nId'];
		$aData['aChat'] = json_decode($aRows['sChat'],true);
	}
	if ($nId != 0 && empty($aData))
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if ($aData['nUid'] != 0)
	{
		$sSQL = '	SELECT 	sAccount
				FROM 		'. CLIENT_USER_DATA .'
				WHERE 	nId = :nId
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows !== false)
		{
			$aData['sAccount'] = $aRows['sAccount'];
		}
	}

	foreach ($aData['aChat'] as $LPnId => $LPaChat)
	{
		$aData['aChat'][$LPnId]['sWho'] = 'UserMsg';
		if ($LPaChat['nWho'] == 1)
		{
			$aData['aChat'][$LPnId]['sWho'] = 'AdmMsg';
		}
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if ($nErr == 1)
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
		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>