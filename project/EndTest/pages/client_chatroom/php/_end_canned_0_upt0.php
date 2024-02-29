<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_canned.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nLid		= filter_input_int('nLid', INPUT_GET,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_chatroom/php/_end_canned_0.php']),
		'sHtml'	=> 'pages/client_chatroom/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_canned_0_upt0.php'
	);
	#url結束

	#參數宣告區
	$aData = array(
		'nOnline'	=> 1,
		'nKid'	=> 0,
		'sKind'	=> '',
	);
	$aKind = array();
	$aOnline = aONLINE;
	$aValue = array(
		'a'		=> ($nLid == 0)?'INS':'UPT'.$nLid,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nErr = 0;
	$sErrMsg = '';
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nLid,
					sName0
			FROM		'. END_CANNED_KIND .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			ORDER	BY 	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if ($aData['nKid'] == 0)
		{
			$aData['nKid'] = $aRows['nLid'];
		}
		$aKind[$aRows['nLid']] = $aRows;
		$aKind[$aRows['nLid']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nId,
					sContent0,
					nKid,
					sLang,
					nLid,
					nOnline,
					sCreateTime,
					sUpdateTime
			FROM		'. END_CANNED .'
			WHERE		nLid = :nLid
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
	sql_query($Result);
	$nCount = $Result->rowCount();
	if ($nCount == 0 && $nLid != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['nOnline'] = $aRows['nOnline'];
		$aData['nKid'] = $aRows['nKid'];
		$aData[$aRows['sLang']] = $aRows;
	}

	$aOnline[$aData['nOnline']]['sSelect'] = 'selected';
	$aKind[$aData['nKid']]['sSelect'] = 'selected';
	$aData['sKind'] = $aKind[$aData['nKid']];

	foreach(aLANG as $LPsLang => $LPsText)
	{
		$aLanguage[$LPsLang]['sText'] = $LPsText;
		$aLanguage[$LPsLang]['sActive'] = '';
		if ($LPsLang == $aSystem['sLang'])
		{
			$aLanguage[$LPsLang]['sActive'] = 'active';
		}
		if(!isset($aData[$LPsLang]))
		{
			$aData[$LPsLang]['sContent0'] = '';
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