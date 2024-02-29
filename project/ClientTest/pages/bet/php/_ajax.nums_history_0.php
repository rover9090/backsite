<?php
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/ClientUserClass.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/oCoreOpenssl.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/mall.php');

	$nKid		= filter_input_int('nKid', 	INPUT_REQUEST,0);
	$sName0	= filter_input_str('sName0', 	INPUT_REQUEST,'');
	$sSort	= filter_input_str('sSort', 	INPUT_REQUEST,'sAll');
	$aCookieCart = isset($_COOKIE['aCart'])?$_COOKIE['aCart']:array();
	$sLineData = isset($_COOKIE['sLineData'])?$_COOKIE['sLineData']:'';
	$aLineData = $oJWT->validToken($sLineData);

	$aCss = array();
	$aJs = array(
		// '0' => 'plugins/js/tool/cookie.js',
		'1' => 'plugins/js/mall/cart.js',
	);
	$aUrl = array(
		'sUpt'	=> sys_web_encode($aMenuToNo['pages/mall/php/_mall_0_upt0.php']),
		'sUpt1'	=> sys_web_encode($aMenuToNo['pages/mall/php/_mall_0_upt1.php']),
		'sUpt3'	=> sys_web_encode($aMenuToNo['pages/mall/php/_mall_0_upt3.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/mall/php/_mall_0.php']),
		'sList'	=> sys_web_encode($aMenuToNo['pages/mall/php/_mall_0.php']),
		'sOrder'	=> sys_web_encode($aMenuToNo['pages/order/php/_order_0.php']),
		// 'sAjax'	=> sys_web_encode($aMenuToNo['pages/mall/php/_ajax.addcart_0.php']),
		'sBack'	=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
		'sHtml'	=> 'pages/mall/'.$aSystem['sHtml'].$aSystem['nVer'].'/mall_0.php',
	);
	#url結束

	#參數宣告區
	const aSORT_ORDER = array(
		'sAll'	=> 'nSort DESC, nId DESC ',
		'sNew'	=> 'nCreateTime DESC, nId DESC ',
		'sPriceH2L'	=> 'nPrice0 DESC, nId DESC ',
		'sPriceL2H'	=> 'nPrice0 ASC, nId DESC ',
	);
	$aJsDefine = array(
		'COOKIE_CLOSE' => COOKIE['CLOSE'],
		'COOKIE_REMEMBER' => COOKIE['REMEMBER'],
		'ADD_CART_SUCCESS' => aJSDEFINE_CART['ADD_CART_SUCCESS'],
		'OUT_OF_STOCK' => aJSDEFINE_CART['OUT_OF_STOCK'],
		'ADD_CART_ALREADY' => aJSDEFINE_CART['ADD_CART_ALREADY'],
	);
	$aSortAttr = array(
		'sAll' => array(
			'sSelect' => '',
			'sLang' => aMALL_SORT['ALL'],
		),
		'sNew' => array(
			'sSelect' => '',
			'sLang' => aMALL_SORT['NEW'],
		),
		'sPriceH2L' => array(
			'sSelect' => '',
			'sLang' => aMALL_SORT['PRICE_H2L'],
		),
		'sPriceL2H' => array(
			'sSelect' => '',
			'sLang' => aMALL_SORT['PRICE_L2H'],
		),
	);
	$oUser = new oClientUser();
	$aSortAttr[$sSort]['sSelect'] = 'SELECTED';
	$nCookieCount = count($aCookieCart);
	$aData = array();
	$aUserData = array();
	$aBind = array();
	$sMallIdList = '0';
	$sCondition = '';
	$sSortOrder = aSORT_ORDER['sAll'];
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aMallKind = array(
		'0' => array(
			'sName0' => ALL,
			'sSelect' => $nKid === 0?'SELECTED':'',
		)
	);
	$nKindCount = 0;
	$nTdAmount = 2;
	// $aJumpMsg['Mall']['sTitle'] = ERRORMSG;
	// $aJumpMsg['Mall']['sIcon'] = 'error';
	// $aJumpMsg['Mall']['sMsg'] = $sErrMsg;
	$aJumpMsg['Mall']['sShow'] = 0;
	$aJumpMsg['Mall']['nClicktoClose'] = 1;
	// $aJumpMsg['Mall']['aButton']['0']['sUrl'] = $aUrl['sBack'];
	// $aJumpMsg['Mall']['aButton']['0']['sText'] = CONFIRM;
	#宣告結束

	#程式邏輯區
	if(isset(aSORT_ORDER[$sSort]))
	{
		$sSortOrder = aSORT_ORDER[$sSort];
		$aPage['aVar']['sSort'] = $sSort;
	}

	if($nKid > 0)
	{
		$sCondition .= ' AND nKid = :nKid ';
		$aPage['aVar']['nKid'] = $nKid;
		$aBind['nKid'] = $nKid;
	}

	if($sName0 != '')
	{
		$sCondition .= ' AND sName0 LIKE :sName0 ';
		$aPage['aVar']['sName0'] = $sName0;
		$aBind['sName0'] = '%'.$sName0.'%';
	}

	$sSQL = '	SELECT	nId,
					sName0
			FROM		'. CLIENT_MALL_KIND .'
			WHERE		sLang LIKE :sLang
			AND		nOnline = 1
			ORDER	BY	nSort DESC, nCreateTime DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMallKind[$aRows['nId']] = $aRows;
		$aMallKind[$aRows['nId']]['sSelect'] = '';
		if($nKid == $aRows['nId'])
		{
			$aMallKind[$aRows['nId']]['sSelect'] = 'selected';
		}
	}
	$nKindCount = count($aMallKind) + 1;

	$sSQL = '	SELECT	1
			FROM		'. CLIENT_MALL .'
			WHERE		sLang LIKE :sLang
			AND		nOnline = 1'
			.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	$aPage['nDataAmount'] = count($Result->fetchAll());

	$sSQL = '	SELECT	nId,
					sName0,
					nPrice0,
					nStock,
					nKid,
					nSort,
					sContent0,
					sCreateTime,
					sUpdateTime,
					nType0
			FROM		'. CLIENT_MALL .'
			WHERE		sLang LIKE :sLang
			AND		nOnline = 1'
			.$sCondition.'
			ORDER BY 	'.$sSortOrder
			.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['nPrice0'] = (float)$aRows['nPrice0'];
		$aData[$aRows['nId']]['sKind'] = $aMallKind[$aRows['nKid']]['sName0'];
		if(isset($aCookieCart[$aRows['nId']]))
		{
			$aData[$aRows['nId']]['sActive'] = 'active';
		}
		else
		{
			$aData[$aRows['nId']]['sActive'] = '';
		}
		$sMallIdList .= ','.$aRows['nId'];
	}
	
	$sSQL = '	SELECT	nKid,
					sFile,
					nCreateTime,
					nType0
			FROM		'. CLIENT_IMAGE_CTRL .'
			WHERE		sTable LIKE \''. CLIENT_MALL .'\'
			AND		nKid IN ('.$sMallIdList.')
			AND		nType0 = 0';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nKid']]['sPictureUrl'] =  IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).CLIENT_MALL.'/'.$aRows['sFile'];
	}

	$sSQL = '	SELECT	nId,
					sRealName,
					sPhone,
					sLineName,
					sAddress,
					sBirth,
					sLineId,
					sNatural as sNaturalCypher
			FROM		'. CLIENT_USER_DATA .'
			WHERE		sLineId = :sLineId
			LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLineId', $aLineData['sLineId'], PDO::PARAM_STR);
	sql_query($Result);
	$aUserData = $Result->fetch(PDO::FETCH_ASSOC);
	if($aUserData === false)
	{
		$aUserData = array(
			'sLineName' => '',
			'sPhone' => '',
			'sRealName' => '',
			'sAddress' => '',
			'sBirth' => '',
		);
	}
	else
	{
		$aUserData['sNatural'] = $oUser->decryptNatural($aUserData['sNaturalCypher']);
	}

	$aJumpMsg['aCartAlert']['sMsg'] = '<span class="JqCartMsg"></span>';
	$aJumpMsg['aCartAlert']['nClicktoClose'] = 1;
	$aJumpMsg['aCartAlert']['sShow'] = 0;

	$aPageList = pageSet($aPage, $aUrl['sPage']);

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];

	#輸出結束
?>