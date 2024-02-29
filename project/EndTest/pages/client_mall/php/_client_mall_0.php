<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_mall.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nKid			= filter_input_int('nKid', 	INPUT_REQUEST,1);
	$nOnline		= filter_input_int('nOnline', INPUT_REQUEST,-1);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sUpt'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_upt0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0.php']),
		'sHtml'	=> 'pages/client_mall/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_mall_0.php',
	);
	#url結束

	#參數宣告區
	$aPage['nPageSize'] = 50;
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sPictureIds = '0';
	$aPictureKind = array(
		'0' => array(
			'sSelect' => '',
			'sName0'  => '全部',
		)
	);
	$aImage = array();
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;
	$nTdCount = 5;
	$nTdCount_m1 = 4;
	#宣告結束

	#程式邏輯區

	// if($nSort > -1)
	// {
	// 	$sCondition .= ' AND nSort = :nSort ';
	// 	$aPage['aVar']['nSort'] = $nSort;
	// 	$aBind['nSort'] = $nSort;
	// }
	
	// $sSQL = '	SELECT	1
	// 		FROM		'. client_mall .'
	// 		WHERE		nOnline != 99
	// 				' . $sCondition;
	// $Result = $oPdo->prepare($sSQL);
	// sql_build_value($Result,$aBind);
	// sql_query($Result);
	// $aPage['nDataAmount'] = count($Result->fetchAll());

	$sSQL = '	SELECT	nId,
					sName0,
					nPrice0,
					nStock,
					nOnline,
					nKid,
					nSort,
					sContent0,
					sCreateTime,
					sUpdateTime
			FROM		'. CLIENT_MALL .'
			WHERE		nOnline != 99
			ORDER	BY	nSort ASC, nId DESC '.
			sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nPrice0'] = (float)$aRows['nPrice0'];
		$sPictureIds .= ','.$aRows['nId'];
		$aData[$aRows['nId']] = $aRows;			
	}

	$sSQL = '	SELECT	nKid,
					sFile,
					nCreateTime,
					nType0
			FROM		'. CLIENT_IMAGE_CTRL .'
			WHERE		sTable LIKE \''. CLIENT_MALL .'\'
			AND		nKid IN ('.$sPictureIds.')';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nKid']]['sFileType'] = substr($aRows['sFile'],strpos($aRows['sFile'],'.'));
		$aImage[$aRows['nKid']] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).CLIENT_MALL.'/'.$aRows['sFile'];
	}
	
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>