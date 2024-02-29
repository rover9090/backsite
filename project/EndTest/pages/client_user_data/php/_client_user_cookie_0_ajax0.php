<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_cookie.php');
	#require結束

	#參數接收區
	// $sSearchType = filter_input_str('sSearchType',	INPUT_REQUEST, 'sAccount');
	// $nInclude    = filter_input_int('nInclude',	INPUT_REQUEST, 0);
	// $sSearch 	 = filter_input_str('sSearch',	INPUT_REQUEST, '');
	// $nStatus	 = filter_input_int('nStatus',	INPUT_REQUEST, -1);
	// $nIdentity	 = filter_input_int('nIdentity',	INPUT_REQUEST, -1);
	// $nOnline	 = filter_input_int('nOnline',	INPUT_REQUEST, -1);
	#參數結束

	#參數宣告區
	$nCount = 0;
	$aData = array();
	$aRawData = array();
	$aOnlineMember = array();
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> ''
	);

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
		'99' => array(
			'sTitle' => aUSER['STATUS99'],
			'sSelect'=> '',
			'sClass'=> '',
		),
		// '10' => array(
		// 	'sTitle' => aUSER['STATUS10'],
		// 	'sSelect'=> '',
		// 	'sClass'=> 'FontRed',
		// ),
	);

	#宣告結束

	#程式邏輯區

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
		ORDER BY nBlack DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sStatus'] = $aStatus[$aRows['nStatus']]['sTitle'];
		$aData[$aRows['nId']]['sColor'] = '';
		$aRawData[] = $aRows['nId'];

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

	$sSQL = 'SELECT 	nId,
				nUid,
				sAccount,
				sUpdateTime
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
			$aData[$LPnUid]['sUpdateTime'] = $aOnlineMember[$LPnUid]['sUpdateTime'];
	
			$nCount++;
		}
		else
		{
			unset($aData[$LPnUid]);
		}
	}

	foreach($aRawData as $LPnOrder =>$LPnUid)
	{
		if(isset($aData[$LPnUid]))
		{
			$aRawData[$LPnOrder] = $aData[$LPnUid];
		}
		else
		{
			unset($aRawData[$LPnOrder]);
		}
	}

	$aReturn['nCount'] = $nCount;
	$aReturn['aData'] = $aRawData;
	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn);
	exit;
	#輸出結束
?>