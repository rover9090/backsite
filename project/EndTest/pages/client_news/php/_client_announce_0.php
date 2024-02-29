<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_announce.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nKid		= filter_input_int('nKid', 	INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline', INPUT_REQUEST,-1);
	$sSearch	= filter_input_str('sSearch', INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']),
		'sHtml'	=> 'pages/client_news/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_announce_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aAnnounceKind = array();
	$aRoom = array(
		'0' => array(
			'sName0'  => '不限館',
		),
	);
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;

	$sImgId = '0';
	#宣告結束

	#程式邏輯區

	if($nKid > 0)
	{
		$sCondition .= ' AND nKid = :nKid ';
		$aPage['aVar']['nKid'] = $nKid;
		$aBind['nKid'] = $nKid;
	}

	if($nOnline > -1)
	{
		$sCondition .= ' AND nOnline = :nOnline ';
		$aPage['aVar']['nOnline'] = $nOnline;
		$aBind['nOnline'] = $nOnline;
	}

	if($sSearch != '')
	{
		$sCondition .= ' AND sName0 LIKE :sSearch ';
		$aPage['aVar']['sSearch'] = $sSearch;
		$aBind['sSearch'] = '%'.$sSearch.'%';
	}

	$sSQL = '	SELECT	nLid,
					sName0
			FROM		'. CLIENT_ANNOUNCE_KIND .'
			WHERE		sLang LIKE :sLang
			AND		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAnnounceKind[$aRows['nLid']] = $aRows;
		$aAnnounceKind[$aRows['nLid']]['sSelect'] = '';
		if($nKid == $aRows['nLid'])
		{
			$aAnnounceKind[$aRows['nLid']]['sSelect'] = 'selected';
		}
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
	}

	foreach($aOnline as $LPnStatus => $LPaDetail)
	{
		if($nOnline == $LPnStatus)
		{
			$aOnline[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	nId
			FROM		'. CLIENT_ANNOUNCE .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
					' . $sCondition . '
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}

	$aPage['nDataAmount'] = $nCount;

	$sSQL = '	SELECT	nId,
					sName0,
					nKid,
					nLid,
					nOnline,
					sPushTime,
					sCreateTime,
					sUpdateTime,
					sEndTime,
					nGame
			FROM		'. CLIENT_ANNOUNCE .'
			WHERE		nOnline != 99
			AND		sLang LIKE :sLang
					'. $sCondition .'
			ORDER	BY	nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nLid']] = $aRows;
		$aData[$aRows['nLid']]['sKind'] = $aAnnounceKind[$aRows['nKid']]['sName0'];

		$aData[$aRows['nLid']]['sIns'] = $aUrl['sIns'].'&nLid='.$aRows['nLid'];
		$aValue = array(
			'a'		=> 'DEL'.$aRows['nLid'],
			't'		=> NOWTIME,
		);
		$sLPJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nLid']]['sDel'] = $aUrl['sDel'].'&nLid='.$aRows['nLid'].'&sJWT='.$sLPJWT;

		$sImgId .= ','.$aRows['nId'];
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);

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
		$aImage[$aRows['nKid']] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d', $aRows['nCreateTime']).'/'.$aRows['sTable'].'/'.$aRows['sFile'];
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>