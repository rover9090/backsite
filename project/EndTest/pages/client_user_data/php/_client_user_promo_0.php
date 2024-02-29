<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_promo.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$sPromoCode = filter_input_str('sPromoCode',	INPUT_REQUEST, '');
	$nOnline 	= filter_input_int('nOnline',		INPUT_REQUEST, -1);
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0_upt0.php']),
		'sDel'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_promo_0.php']),
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_promo_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBindArray = array();
	$aOnline = aONLINE;
	$aOnline[0]['sText'] = aPROMO['TURNOFF'];
	$aOnline[1]['sText'] = aPROMO['TURNON'];
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sCondition = '';

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	if ($nOnline != -1)
	{
		$sCondition .= ' AND nOnline = :nOnline';
		$aBindArray['nOnline'] = $nOnline;
	}
	if ($sPromoCode != '')
	{
		$sCondition .= ' AND sPromoCode LIKE :sPromoCode';
		$aBindArray['sPromoCode'] = '%'.$sPromoCode.'%';
	}

	$sSQL = '	SELECT 	1
			FROM 		'.CLIENT_USER_PROMO.'
			WHERE 	nUid = :nUid
			AND 		nOnline != 99
			'.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();

	$sSQL = '	SELECT 	Promo_.nId,
					Promo_.sPromoCode,
					Promo_.sCreateTime,
					Promo_.nOnline,
					Promo_.nUsedTimes,
					Fs_.sWater
			FROM 		'.CLIENT_USER_PROMO.' Promo_,
					'.CLIENT_USER_PROMO_FS.' Fs_
			WHERE 	Promo_.nUid = :nUid
			AND 		Promo_.nOnline != 99
					'.$sCondition.'
			AND 		Promo_.nId = Fs_.nPid
			ORDER BY 	nId DESC
			'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sOnline'] = $aOnline[$aRows['nOnline']]['sText'];
		$aData[$aRows['nId']]['sIns'] = $aUrl['sIns'].'&nId='.$aRows['nId'];

		$aValue = array(
			'a'		=> 'DEL'.$aRows['nId'],
			't'		=> NOWTIME,
		);
		$sLPJWT = sys_jwt_encode($aValue);
		$aData[$aRows['nId']]['sDel'] = $aUrl['sDel'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;
	}


	if (isset($aOnline[$nOnline]))
	{
		$aOnline[$nOnline]['sSelect'] = 'selected';
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>