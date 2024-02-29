<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$sSearchType = filter_input_str('sSearchType',	INPUT_REQUEST, 'sAccount');
	$nInclude    = filter_input_int('nInclude',	INPUT_REQUEST, 0);
	$sSearch 	 = filter_input_str('sSearch',	INPUT_REQUEST, '');
	$nStatus	 = filter_input_int('nStatus',	INPUT_REQUEST, -1);
	$nBlack	 = filter_input_int('nBlack',		INPUT_REQUEST, -1);
	$nMute	 = filter_input_int('nMute',		INPUT_REQUEST, -1);
	$nIdentity	 = filter_input_int('nIdentity',	INPUT_REQUEST, -1);
	$nHide	 = filter_input_int('nHide',		INPUT_REQUEST, -1);

	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sUpt2'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt2.php']),
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_data_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBindArray = array();
	$sTmpPa = '0';
	$sInclude = '';

	$aPaData = array(
		'0'	=> array(
			'sName0' => '',
			'sUrl' => '',
		),
	);

	$aSearchType = array(
		'sAccount' => array(
			'sTitle'	=> ACCOUNT,
			'sSelect'	=> '',
		),
		'sName0' => array(
			'sTitle'	=> aUSER['NAME'],
			'sSelect'	=> '',
		),
	);

	$aStatus = array(
		'-1' => array(
			'sTitle' => aUSER['SELSTATUS'],
			'sSelect'=> '',
			'sClass'=> '',
		),
		'0' => array(
			'sTitle' => aUSER['STATUS0'],
			'sSelect'=> '',
			'sClass'=> 'FontGreen',
		),
		'1' => array(
			'sTitle' => aUSER['STATUS1'],
			'sSelect'=> '',
			'sClass'=> 'FontRed',
		),
		'99' => array(
			'sTitle' => aUSER['STATUS99'],
			'sSelect'=> '',
			'sClass'=> 'FontRed',
		),
	);
	$aBlack = array(
		'-1' => array(
			'sTitle' => aUSER['SELSTATUS'],
			'sSelect'=> '',
			'sClass'=> '',
		),
		'0' => array(
			'sTitle' => aUSER['BLACK0'],
			'sSelect'=> '',
			'sClass'=> 'FontGreen',
		),
		'1' => array(
			'sTitle' => aUSER['BLACK1'],
			'sSelect'=> '',
			'sClass'=> 'FontRed',
		),
	);
	$aMute = array(
		'-1' => array(
			'sTitle' => aUSER['SELSTATUS'],
			'sSelect'=> '',
			'sClass'=> '',
		),
		'0' => array(
			'sTitle' => aUSER['MUTE0'],
			'sSelect'=> '',
			'sClass'=> 'FontGreen',
		),
		'1' => array(
			'sTitle' => aUSER['MUTE1'],
			'sSelect'=> '',
			'sClass'=> 'FontRed',
		),
	);

	$aIdentity = array(
		'-1' => array(
			'sTitle' => aUSER['SELIDENTITY'],
			'sSelect'=> '',
		),
		'0' => array(
			'sTitle' => aUSER['IDENTITY0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['IDENTITY1'],
			'sSelect'=> '',
		),
	);

	$aHide = array(
		'-1' => array(
			'sTitle' => aUSER['HIDE99'],
			'sSelect'=> '',
			'sClass'=> '',
		),
		'0' => array(
			'sTitle' => aUSER['HIDE0'],
			'sSelect'=> '',
			'sClass'=> 'FontGreen',
		),
		'1' => array(
			'sTitle' => aUSER['HIDE1'],
			'sSelect'=> '',
			'sClass'=> 'FontRed',
		),
	);

	$aPage['aVar'] = array(
		'sSearchType'	=> $sSearchType,
		'sSearch'		=> $sSearch,
		'nStatus'		=> $nStatus,
		'nBlack'		=> $nBlack,
		'nMute'		=> $nMute,
		'nIdentity'		=> $nIdentity,
		'nHide'		=> $nHide,
		'nInclude'		=> $nInclude,
	);

	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sCondition = '';
	#宣告結束

	#程式邏輯區
	if ($sSearch != '' && isset($aSearchType[$sSearchType]))
	{
		if($nInclude === 1)
		{
			$sInclude = 'checked';
			$sCondition .= ' AND ( User_.'.$sSearchType.' LIKE :'.$sSearchType.' OR Link_.nPa =
						(SELECT nId FROM '. CLIENT_USER_DATA .' WHERE '.$sSearchType.' LIKE :'.$sSearchType.' LIMIT 1) )';
		}
		else
		{
			$sCondition .= ' AND User_.'.$sSearchType.' LIKE :'.$sSearchType.'';
		}
		$aSearchType[$sSearchType]['sSelect'] = 'selected';
		$aBindArray[$sSearchType] = '%'.$sSearch.'%';
	}
	if ($nStatus != -1)
	{
		$aStatus[$nStatus]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nStatus = :nStatus';
		$aBindArray['nStatus'] = $nStatus;
	}
	if ($nBlack != -1)
	{
		$aBlack[$nBlack]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nBlack = :nBlack';
		$aBindArray['nBlack'] = $nBlack;
	}
	if ($nMute != -1)
	{
		$aMute[$nMute]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nMute = :nMute';
		$aBindArray['nMute'] = $nMute;
	}

	if ($nIdentity != -1)
	{
		$aIdentity[$nIdentity]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nIdentity = :nIdentity';
		$aBindArray['nIdentity'] = $nIdentity;
	}

	if ($nHide != -1)
	{
		$aHide[$nHide]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nHide = :nHide';
		$aBindArray['nHide'] = $nHide;
	}

	// 代理帳號只能查自己下線
	if($aAdm['nUid'] != 0)
	{
		$sCondition .= ' AND Link_.sLinkList LIKE :sLinkList';
		$aBindArray['sLinkList'] = sprintf('%%%09d%%',$aAdm['nUid']);
	}

	$sSQL = '	SELECT 	1
			FROM  	'. CLIENT_USER_LINK .' Link_
			JOIN		'. CLIENT_USER_DATA .' User_
			ON		User_.nId = Link_.nUid
			WHERE  	User_.nOnline != 99
			AND		Link_.nEndTime = 0
			'.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();

	$sSQL = 'SELECT 	User_.nId,
				User_.sName0,
				User_.sName1,
				User_.sAccount,
				User_.nIdentity,
				User_.nStatus,
				User_.nBlack,
				User_.nMute,
				User_.nHide,
				User_.sCreateTime,
				User_.sUpdateTime,
				Money_.nMoney,
				Money_.nWater,
				Link_.nLevel,
				Link_.nPa
		FROM  	'. CLIENT_USER_DATA .' User_
		JOIN		'. CLIENT_USER_MONEY .' Money_
		ON		User_.nId = Money_.nUid
		JOIN		'. CLIENT_USER_LINK .' Link_
		ON		User_.nId = Link_.nUid
		WHERE 	User_.nOnline != 99
		AND 		Link_.nEndTime = 0
				'.$sCondition.'
		ORDER BY nId DESC
		'.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sStatus'] = $aStatus[$aRows['nStatus']]['sTitle'];
		$aData[$aRows['nId']]['sBlack'] = $aBlack[$aRows['nBlack']]['sTitle'];
		$aData[$aRows['nId']]['sMute'] = $aMute[$aRows['nMute']]['sTitle'];
		$aData[$aRows['nId']]['sIdentity'] = $aIdentity[$aRows['nIdentity']]['sTitle'];
		$aData[$aRows['nId']]['sHide'] = $aHide[$aRows['nHide']]['sTitle'];
		$aData[$aRows['nId']]['sIns'] = $aUrl['sIns'].'&nId='.$aRows['nId'];
		$aData[$aRows['nId']]['sColor'] = '';

		$sTmpPa .= ','.$aRows['nPa'];

		$nAccountType = substr($aRows['sAccount'],-1);
		if($nAccountType == '0')
		{
			$aData[$aRows['nId']]['sColor'] = 'FontGreen';
		}

		if($aRows['nBlack'] == 1)
		{
			$aData[$aRows['nId']]['sColor'] = 'FontRed';
		}
	}

	$sSQL = '	SELECT 	nId,
					sName0,
					sName1,
					sAccount
			FROM  	'. CLIENT_USER_DATA .'
			WHERE 	nId IN ('.$sTmpPa.') ';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aPaData[$aRows['nId']] = $aRows;
		$aPaData[$aRows['nId']]['sName'] = $aRows['sAccount'].'('.$aRows['sName1'].')';
		$aPaData[$aRows['nId']]['sUrl'] = $aUrl['sPage'] .'&nInclude=1&sSearch='. $aRows['sAccount'];
	}
	$aPaData[0]['sName'] = '';

	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>