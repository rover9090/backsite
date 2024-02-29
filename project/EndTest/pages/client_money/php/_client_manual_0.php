<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_manual.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
		1	=> 'plugins/js/client_money/client_manual.js'
	);
	#js結束

	#參數接收區
	$sStartTime		= filter_input_str('sStartTime', 		INPUT_REQUEST,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 		INPUT_REQUEST,date('Y-m-d 23:59:59'));
	$nKid			= filter_input_int('nKid', 			INPUT_REQUEST,0);
	$nType1		= filter_input_int('nType1', 			INPUT_REQUEST,-1);
	$nType3		= filter_input_int('nType3', 			INPUT_REQUEST,-1);
	$nStatus		= filter_input_int('nStatus', 		INPUT_REQUEST,-1);
	$sAdmin		= filter_input_str('sAdmin', 			INPUT_REQUEST,'');
	$sMemberAccount	= filter_input_str('sMemberAccount', 	INPUT_REQUEST,'');
	$sMemo		= filter_input_str('sMemo', 			INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0.php']),
		'sIns'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_manual_0_upt0.php']),
		'sHtml'	=> 'pages/client_money/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_manual_0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aCompany = array();
	$aMemberData = array();
	$aAllAdminData = array(
		'-1' => array(
			'sAccount' => '',
		),
	);
	$sCondition = '';
	$sAdmCondition = '';
	$aAdmBind = array();
	$aBind = array();
	$sAdmIds = '0';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aStatus = aMANUAL['STATUS'];
	$aType1 = aMANUAL['TYPE1'];
	$aType3 = aMANUAL['TYPE3'];
	$nTotalCount = 0;
	$nTotalInMoney = 0;
	$nTotalInCount = 0;
	$nTotalOutMoney = 0;
	$nTotalOutCount = 0;
	$nPageTotalInMoney = 0;
	$nPageTotalInCount = 0;
	$nPageTotalOutMoney = 0;
	$nPageTotalOutCount = 0;
	#宣告結束

	#程式邏輯區

	$sCondition .= ' AND Money_.nCreateTime >= :nStartTime AND Money_.nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$aBind['nEndTime'] = $nEndTime;

	if($nKid > 0)
	{
		$sCondition .= ' AND Money_.nKid = :nKid ';
		$aPage['aVar']['nKid'] = $nKid;
		$aBind['nKid'] = $nKid;
	}

	if($nType1 > -1)
	{
		$sCondition .= ' AND Money_.nType1 = :nType1 ';
		$aPage['aVar']['nType1'] = $nType1;
		$aBind['nType1'] = $nType1;
	}

	if($nType3 > -1)
	{
		$sCondition .= ' AND Money_.nType3 = :nType3 ';
		$aPage['aVar']['nType3'] = $nType3;
		$aBind['nType3'] = $nType3;
	}

	if($nStatus > -1)
	{
		$sCondition .= ' AND Money_.nStatus = :nStatus ';
		$aPage['aVar']['nStatus'] = $nStatus;
		$aBind['nStatus'] = $nStatus;
	}

	if($sAdmin != '')
	{
		$aPage['aVar']['sAdmin'] = '%'.$sAdmin.'%';
		$sAdmCondition .= '	AND sAccount LIKE :sAdmin ';
		$aAdmBind['sAdmin'] = '%'.$sAdmin.'%';
	}

	if($sMemberAccount != '')
	{
		$aPage['aVar']['sMemberAccount'] = '%'.$sMemberAccount.'%';
		$sCondition .= '	AND User_.sAccount LIKE :sMemberAccount ';
		$aBind['sMemberAccount'] = '%'.$sMemberAccount.'%';
	}

	if($sMemo != '')
	{
		$aPage['aVar']['sMemo'] = '%'.$sMemo.'%';
		$sCondition .= '	AND Money_.sMemo LIKE :sMemo ';
		$aBind['sMemo'] = '%'.$sMemo.'%';
	}

	unset($aStatus['sTitle']);
	foreach($aStatus as $LPnStatus => $LPaDetail)
	{
		if($nStatus == $LPnStatus)
		{
			$aStatus[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	unset($aType1['sTitle']);
	foreach($aType1 as $LPnStatus => $LPaDetail)
	{
		if($nType1 == $LPnStatus)
		{
			$aType1[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	unset($aType3['sTitle']);
	foreach($aType3 as $LPnStatus => $LPaDetail)
	{
		if($nType3 == $LPnStatus)
		{
			$aType3[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	nId,
					sAccount
			FROM		'. END_MANAGER_DATA .'
			WHERE		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAllAdminData[$aRows['nId']] = $aRows;
	}

	$sSQL = '	SELECT	nId,
					sAccount
			FROM		'. END_MANAGER_DATA .'
			WHERE		nOnline != 99
					'. $sAdmCondition .'
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aAdmBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sAdmIds .= ','.$aRows['nId'];
	}

	if($sAdmin != '')
	{
		$sCondition .= ' AND Money_.nAdmin0 IN ( ' . $sAdmIds . ' ) ';
	}


	$sSQL = '	SELECT	Money_.nId,
					Money_.nMoney,
					Money_.nType3
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 4
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		// 1 => 入款 2 => 出款
		if($aRows['nType3'] == 1)
		{
			$nTotalInMoney += $aRows['nMoney'];
			$nTotalInCount ++;
		}
		else
		{
			$nTotalOutMoney += $aRows['nMoney'];
			$nTotalOutCount ++;
		}
		$nTotalCount ++;
	}

	$aPage['nDataAmount'] = $nTotalCount;

	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nMoney,
					Money_.nStatus,
					Money_.nType1,
					Money_.nType3,
					Money_.nAdmin0,
					Money_.sMemo,
					Money_.sCreateTime,
					Money_.sUpdateTime,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 4
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sAdmin0'] = '';

		// 1 => 入款 2 => 出款
		if($aRows['nType3'] == 1)
		{
			$nPageTotalInMoney += $aRows['nMoney'];
			$nPageTotalInCount ++;
		}
		else
		{
			$nPageTotalOutMoney += $aRows['nMoney'];
			$nPageTotalOutCount ++;
		}

		if($aRows['nAdmin0'] != 0)
		{
			$aData[$aRows['nId']]['sAdmin0'] = $aAllAdminData[$aRows['nAdmin0']]['sAccount'];
		}

		if($aRows['nStatus'] == 0)
		{
			$aValue = array(
				'a'		=> 'PASS'.$aRows['nId'],
				'nUid'	=> $aRows['nUid'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sPass'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT;

			$aValue = array(
				'a'		=> 'DENY'.$aRows['nId'],
				'nUid'	=> $aRows['nUid'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sDeny'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT;
		}
	}
	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>