<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	#require結束
	
	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0' => 'plugins/js/md5.js',
		'1' => 'plugins/js/login/login.js',
	);
	#js結束

	#參數接收區
	$nKid		= filter_input_int('nKid', 	INPUT_REQUEST,-1);
	#參數結束

	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/login/'.$aSystem['sClientHtml'].$aSystem['nVer'].'/login_0.php',				
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$sCondition = '';
	$sKind = '0';
	#宣告結束

	#程式邏輯區
	$aValue = array(
		'a'		=> 'LOGIN',
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	// $sSQL = '	SELECT	nId,
	// 				sName0
	// 		FROM		'. CLIENT_ACTIVITY_KIND .'
	// 		WHERE		sLang LIKE :sLang
	// 		AND		nOnline = 1
	// 		ORDER	BY	nSort DESC, nId DESC';
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aActivityKind[$aRows['nId']] = $aRows;
	// 	$aActivityKind[$aRows['nId']]['sSelect'] = '';
	// 	if($nKid == $aRows['nId'])
	// 	{
	// 		$aActivityKind[$aRows['nId']]['sSelect'] = 'selected';
	// 	}

	// 	$sKind .= ','.$aRows['nId'];
	// }

	// if(!empty($aActivityKind[$nKid]))
	// {
	// 	$sCondition .= ' AND nKid = :nKid ';
	// 	$aPage['aVar']['nKid'] = $nKid;
	// 	$aBind['nKid'] = $nKid;
	// }
	// else
	// {
	// 	$sCondition .= ' AND nKid IN ('.$sKind.')';
	// }


	// $sSQL = '	SELECT	1
	// 		FROM		'. CLIENT_ACTIVITY .'
	// 		WHERE		nOnline = 1
	// 		AND		sLang LIKE :sLang
	// 				' . $sCondition;
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	// sql_build_value($Result,$aBind);
	// sql_query($Result);
	// $aPage['nDataAmount'] = count($Result->fetchAll());

	// $sSQL = '	SELECT	nId,
	// 				sName0,
	// 				nKid,
	// 				nOnline,
	// 				sCreateTime,
	// 				sUpdateTime,
	// 				sStartTime,
	// 				nStartTime,
	// 				sEndTime,
	// 				nEndTime,
	// 				sContent0,
	// 				nCoverPic
	// 		FROM		'. CLIENT_ACTIVITY .'
	// 		WHERE		nOnline = 1
	// 		AND		sLang LIKE :sLang
	// 				' . $sCondition . '
	// 		ORDER	BY	nSort DESC ,nCreateTime DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	// $Result = $oPdo->prepare($sSQL);
	// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	// sql_build_value($Result,$aBind);
	// sql_query($Result);
	// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$aData[$aRows['nId']] = $aRows;
	// 	$aData[$aRows['nId']]['sKind'] = $aActivityKind[$aRows['nKid']]['sName0'];
	// }

	// foreach ($aData as $LPnId => $LPaDetail)
	// {
	// 	$sSQL = '	SELECT	nKid,
	// 					sFile,
	// 					sTable,
	// 					nCreateTime,
	// 					nType0
	// 			FROM		'. CLIENT_IMAGE_CTRL .'
	// 			WHERE		sTable LIKE \''. CLIENT_PICTURE .'\'
	// 			AND		nKid = '.$LPaDetail['nCoverPic'].'';
	// 	$Result = $oPdo->prepare($sSQL);
	// 	sql_query($Result);
	// 	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// 	{
	// 		$aData[$LPnId]['aImage'] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).CLIENT_PICTURE.'/'.$aRows['sFile'];
	// 	}
	// 	if(!isset($aData[$LPnId]['aImage']))
	// 	{
	// 		$aData[$LPnId]['aImage'] = '';
	// 	}
	// }

	// $aPageList = pageSet($aPage, $aUrl['sPage']);

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>