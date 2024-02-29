<?php
	# 各站需要先取得維護 全局設定 目錄資料 logcode
	# $aSystem['aNav']
	# $aSystem['aLogNums']

	# 目錄 kind / list
	$sSQL = '	SELECT 	nId,
					sMenuName0,
					sMenuTable0
			FROM		'. END_MENU_KIND .'
			WHERE		nOnline = 1
			ORDER BY 	nSort DESC ,nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aSystem['aNav'][$aRows['nId']] = $aRows;
		$aSystem['aNav'][$aRows['nId']]['aList'] = array();
	}

	$sSQL = '	SELECT 	nId,
					sListName0,
					nMid,
					sListTable0,
					nType0
			FROM		'. END_MENU_LIST .'
			WHERE		nOnline = 1
			ORDER BY 	nSort DESC ,nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aSystem['aNav'][$aRows['nMid']]['aList'][$aRows['nId']] = $aRows;
	}
	# 動作代號
	$aSystem['aLogNums'][0] = '';
	$sSQL = '	SELECT 	nType0,
					nCode,
					sName0
			FROM		'. END_LOGCODE;
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aSystem['aLogNums'][$aRows['nType0'].$aRows['nCode']] = $aRows['sName0'];
	}
	# 環境設定
	$sSQL = '	SELECT 	nId,
					sName0,
					sParam
			FROM		'. SYS_PARAM;
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aSystem['aParam'][$aRows['sName0']] = $aRows['sParam'];
	}

	#切換語系
	$aTempLang = aLANG;
	if (isset($_COOKIE['sLang']) && isset($aTempLang[$_COOKIE['sLang']]))
	{
		$aSystem['sLang'] = $_COOKIE['sLang'];
	}

	if (isset($_GET['sLang']) && isset($aTempLang[$_GET['sLang']]))
	{
		setcookie('sLang',$_GET['sLang']);
		$aSystem['sLang'] = $_GET['sLang'];
	}
?>