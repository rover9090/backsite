<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_black.php');
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
	// $nIdentity	 = filter_input_int('nIdentity',	INPUT_REQUEST, -1);
	$nOnline	 = filter_input_int('nOnline',	INPUT_REQUEST, -1);

	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sUpt2'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt2.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_black_0.php']),
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_black_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBindArray = array();
	$aCountBindArray = array();
	$sTmpPa = '0';
	$sInclude = '';
	$aOnlineMember = array();

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

	$aOnline = array(
		0 => array(
			'sTitle' => '離線',
			'sSelect' => '',
		),
		1 => array(
                  'sTitle' => '在線',
                  'sSelect' => '',
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
			'sClass'=> 'FontGreen',
		),
		// '10' => array(
		// 	'sTitle' => aUSER['STATUS10'],
		// 	'sSelect'=> '',
		// 	'sClass'=> 'FontRed',
		// ),
	);

	// $aIdentity = array(
	// 	'-1' => array(
	// 		'sTitle' => aUSER['SELIDENTITY'],
	// 		'sSelect'=> '',
	// 	),
	// 	'0' => array(
	// 		'sTitle' => aUSER['IDENTITY0'],
	// 		'sSelect'=> '',
	// 	),
	// 	'1' => array(
	// 		'sTitle' => aUSER['IDENTITY1'],
	// 		'sSelect'=> '',
	// 	),
	// );

	$aPage['aVar'] = array(
		'sSearchType'	=> $sSearchType,
		'sSearch'		=> $sSearch,
		'nStatus'		=> $nStatus,
		// 'nIdentity'		=> $nIdentity,
		'nInclude'		=> $nInclude,
	);

	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sCondition = '';
	$sCountCondition = '';
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
			$sCountCondition .= ' AND '.$sSearchType.' LIKE :'.$sSearchType.'';
		}
		$aSearchType[$sSearchType]['sSelect'] = 'selected';
		$aBindArray[$sSearchType] = '%'.$sSearch.'%';
		$aCountBindArray[$sSearchType] = '%'.$sSearch.'%';
	}
	if ($nStatus != -1)
	{
		$aStatus[$nStatus]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nStatus = :nStatus';
		$aBindArray['nStatus'] = $nStatus;
		$sCountCondition .= ' AND nStatus = :nStatus';
		$aCountBindArray['nStatus'] = $nStatus;
	}

	if ($nOnline != -1)
	{
		$aOnline[$nOnline]['sSelect'] = 'selected';
		$sCondition .= ' AND User_.nStatus = :nStatus';
		$aBindArray['nStatus'] = $nStatus;
	}

	// if ($nIdentity != -1)
	// {
	// 	$aIdentity[$nIdentity]['sSelect'] = 'selected';
	// 	$sCondition .= ' AND User_.nIdentity = :nIdentity';
	// 	$aBindArray['nIdentity'] = $nIdentity;
	// }

	// 代理帳號只能查自己下線
	if($aAdm['nUid'] != 0)
	{
		$sCondition .= ' AND Link_.sLinkList LIKE :sLinkList';
		$aBindArray['sLinkList'] = sprintf('%%%09d%%',$aAdm['nUid']);
	}

	$sSQL = '	SELECT 	1
			FROM  	'. CLIENT_USER_DATA .'
			WHERE  	nOnline != 99
			AND		nBlack = 1
			'.$sCountCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aCountBindArray);
	sql_query($Result);
	$aPage['nDataAmount'] = $Result->rowCount();

	$sSQL = 'SELECT 	User_.nId,
				User_.sName0,
				User_.sAccount,
				User_.nIdentity,
				User_.nStatus,
				User_.sCreateTime,
				User_.sUpdateTime,
				User_.nBlack,
				User_.nRoom,
				Money_.nMoney
		FROM  	'. CLIENT_USER_DATA .' User_
		JOIN		'. CLIENT_USER_MONEY .' Money_
		ON		User_.nId = Money_.nUid
		WHERE 	User_.nOnline != 99
		AND		User_.nBlack = 1
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
		// $aData[$aRows['nId']]['sIdentity'] = $aIdentity[$aRows['nIdentity']]['sTitle'];
		// $aData[$aRows['nId']]['sOnline'] = $aOnline[$aRows['nOnline']]['sTitle'];
		// $aData[$aRows['nId']]['sIns'] = $aUrl['sIns'].'&nId='.$aRows['nId'];

	}

	$sSQL = 'SELECT 	nId,
				nUid,
				sAccount
		FROM  	'. CLIENT_USER_COOKIE .'
		WHERE 	nCreateTime > :nCreateTime';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nCreateTime',NOWTIME - COOKIE['CLOSE'], PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aOnlineMember[$aRows['nUid']] = $aRows;
	}

	foreach($aData as $LPnUid => $LPaData)
	{
		if(isset($aOnlineMember[$LPnUid]))
		{
			$aData[$LPnUid]['sOnline'] = $aOnline[1]['sTitle'];			
		}
		else
		{
			$aData[$LPnUid]['sOnline'] = $aOnline[0]['sTitle'];
			// unset($aData[$LPnUid]);
		}
	}


	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>