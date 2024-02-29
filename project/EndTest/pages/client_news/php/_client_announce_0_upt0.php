<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_announce.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'plugins/js/ckeditor/ckeditor.js',
		'plugins/js/js_date/laydate.js',
		'plugins/js/client_new/client_announce.js',
	);
	#js結束

	#參數接收區
	$nLid		= filter_input_int('nLid', 		INPUT_GET,	0);
	$sPushTime	= filter_input_str('sPushTime', 	INPUT_POST,	NOWDATE);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']),
		'sHtml'	=> 'pages/client_news/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_announce_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$nPushTime = strtotime($sPushTime);
	$nPushTime = '';
	$aData = array(
		'TW' => array(
			'sName0' => '',
			'sContent0' => '',
			'sEndTime' => '',
		),
		'CN' => array(
			'sName0' => '',
			'sContent0' => '',
			'sEndTime' => '',
		),
		'EN' => array(
			'sName0' => '',
			'sContent0' => '',
			'sEndTime' => '',
		),
	);
	$aAnnounceKind = array();
	$aRoom = array(
		'0' => array(
			'sSelect' => '',
			'sName0'  => '不限館',
		),
	);
	$aValue = array(
		'a'		=> ($nLid == 0)?'INS':'UPT'.$nLid,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nErr = 0;
	$sErrMsg = '';
	$nCount = 0;
	$aOnline = aONLINE;

	$sImgId = '0';
	$aImage = array();
	$aIndexToLang = array();
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nLid,
					sName0
			FROM		'. CLIENT_ANNOUNCE_KIND .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAnnounceKind[$aRows['nLid']] = $aRows;
		$aAnnounceKind[$aRows['nLid']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nGame,
					sName0
			FROM		'. END_GAMES_SETTING .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
			ORDER	BY	sName0 DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRoom[$aRows['nGame']] = $aRows;
		$aRoom[$aRows['nGame']]['sSelect'] = '';
	}

	$sSQL = '	SELECT	nId,
					sName0,
					sContent0,
					nOnline,
					nKid,
					nLid,
					sLang,
					sPushTime,
					sCreateTime,
					sUpdateTime,
					sEndTime,
					nGame
			FROM		'. CLIENT_ANNOUNCE .'
			WHERE		nLid = :nLid
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['nOnline'] = $aRows['nOnline'];
		$aData['nKid'] = $aRows['nKid'];
		$aData['nGame'] = $aRows['nGame'];
		$aData[$aRows['sLang']] = $aRows;
		$nCount++;
		$sPushTime = $aRows['sPushTime'];

		$sImgId .= ','.$aRows['nId'];
		$aIndexToLang[$aRows['nId']] = $aRows['sLang'];
	}

	if($nCount == 0 && $nLid != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if($nLid != 0)
	{
		$aOnline[$aData['nOnline']]['sSelect'] = 'selected';
		$aData['sKind'] = $aAnnounceKind[$aData['nKid']];
		$aAnnounceKind[$aData['nKid']]['sSelect'] = 'selected';
		$aRoom[$aData['nGame']]['sSelect'] = 'selected';


		// img
		$sSQL = '	SELECT	nKid,
						sTable,
						sFile,
						nCreateTime,
						nType0
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		sTable LIKE \''. CLIENT_ANNOUNCE .'\'
				AND		nKid IN ('.$sImgId.')';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aValue = array(
				'a'		=> 'DELIMG'.$aRows['nKid'],
				't'		=> NOWTIME,
			);
			$sLPJWT = sys_jwt_encode($aValue);

			$aImage[$aIndexToLang[$aRows['nKid']]] = array(
				'sUrl'	=> IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d', $aRows['nCreateTime']).'/'.$aRows['sTable'].'/'.$aRows['sFile'],
				'sDel'	=> $aUrl['sAct'].'&nId='.$aRows['nKid'].'&nLid='.$nLid.'&sJWT='.$sLPJWT,
			);
		}
	}

	foreach(aLANG as $LPsLang => $LPsText)
	{
		if(!isset($aData[$LPsLang]))
		{
			$aData[$LPsLang]['sName0'] = '';
			$aData[$LPsLang]['sContent0'] = '';
		}
	}

	$aData['aAnnounceKind'] = $aAnnounceKind;

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