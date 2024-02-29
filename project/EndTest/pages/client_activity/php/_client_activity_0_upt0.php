<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_activity.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0' => 'plugins/js/client_activity/client_activity.js',
		'1' => 'plugins/js/js_date/laydate.js',
		// '2' => 'plugins/js/ckeditor/ckeditor.js',
	);
	#js結束

	#參數接收區
	$nLid		= filter_input_int('nLid', INPUT_GET,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_activity/php/_client_activity_0.php']),
		'sHtml'	=> 'pages/client_activity/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_activity_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sStartTime'	=> date('Y-m-d 00:00:00',NOWTIME),
		'sEndTime'		=> date('Y-m-d 23:59:59',NOWTIME),
		'nGive'		=> 0,
		'nSave'		=> 0,
		'sDisabled'		=> '',
	);
	$aKind = array();
	$aValue = array(
		'a'		=> ($nLid == 0)?'INS':'UPT'.$nLid,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nErr = 0;
	$sErrMsg = '';
	$nCount = 0;
	$aOnline = aONLINE;
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nLid,
					sName0
			FROM	'.	CLIENT_ACTIVITY_KIND .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aKind[$aRows['nLid']] = $aRows;
		$aKind[$aRows['nLid']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nId,
					sName0,
					sContent0,
					nKid,
					sLang,
					nLid,
					nGive,
					nSave,
					nOnline,
					sStartTime,
					sEndTime,
					sCreateTime,
					sUpdateTime
			FROM	'.	CLIENT_ACTIVITY .'
			WHERE		nLid = :nLid
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['nOnline'] = $aRows['nOnline'];
		$aData['nKid'] = $aRows['nKid'];
		$aData['sStartTime'] = $aRows['sStartTime'];
		$aData['sEndTime'] = $aRows['sEndTime'];
		$aData['nGive'] = $aRows['nGive'];
		$aData['nSave'] = $aRows['nSave'];
		$aData['sDisabled'] = 'disabled="disabled"';

		$aData[$aRows['sLang']] = $aRows;
		$nCount++;
	}

	if($nCount == 0 && $nLid != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if($nLid != 0)
	{
		$aOnline[$aData['nOnline']]['sSelect'] = 'selected';
		$aData['sKind'] = $aKind[$aData['nKid']];
		$aKind[$aData['nKid']]['sSelect'] = 'selected';
	}

	foreach(aLANG as $LPsLang => $LPsText)
	{
		if(!isset($aData[$LPsLang]))
		{
			$aData[$LPsLang]['sName0'] = '';
			$aData[$LPsLang]['sContent0'] = '';
		}
	}

	$aData['aKind'] = $aKind;

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if($nErr == 1)
	{
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['sClicktoClose'] = 1;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>