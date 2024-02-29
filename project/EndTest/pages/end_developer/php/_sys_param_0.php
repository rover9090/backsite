<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_developer/php/_sys_param_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_developer/php/_sys_param_0.php']),
		'sHtml'	=> 'pages/end_developer/'.$aSystem['sHtml'].$aSystem['nVer'].'/sys_param_0.php',
	);
	#url結束

	#參數宣告區
	$aTempData = array();
	$aData = array();
	$aValue = array(
		'a'		=> 'UPT',
		't'		=> NOWTIME,
	);
	$sJWTAct = sys_jwt_encode($aValue);

	$aParam = array(
		'BMAINTENANCE'	=> '網站維護 0:維護 1:營運',
		'NAGENTID'		=> '總代理 nUid',
		'NUSERLEVEL'	=> '會員層級 0:關閉 1:開啟',
		// 'aWITHDRAWAL'	=> array(
		// 	'WITHDRAWALTITLE'	=> '出款設定',
		// 	'NCARDLIMIT'	=> '銀行卡綁定張數',
		// 	'NMINWITHDRAWAL'	=> '最低提領金額($)',
		// 	'NDAYWITHDRAWAL'	=> '每日提領次數',
		// 	'NWITHDRAWALFEE'	=> '提領手續費($)',
		// ),
		'aRECHARGE'	=> array(
			'aRECHARGETITLE'	=> '入款設定',
			'NRECHARGEFEE'	=> '手續費($)',
		),
		'aTIMELIMIT'	=> array(
			'aTIMELIMITTITLE'	=> '時間限制',
			'NRECHARGETIME'	=> '入款提單時間限制(秒)',
		),
		'aGAME'	=> array(
			'aGAMETITLE'	=> '遊戲設定',
			'NMINBET'		=> '單人最小投注金額',
			'NMAXPERBET'	=> '單人每門最高單注金額',
			'NMAXPERGAME'	=> '單人單局最高投注金額',
			'NMAXPERDAY'	=> '單人單日最高投注金額',
			'SNAME1RATE'	=> '輔助顯示幣種',
			'NWATERLEAST'	=> '轉移彩金水量(%)',
			'NBANKERMIN'	=> '最低打莊金額',
			'NBANKERMAX'	=> '最高打莊金額',
			'NLIMITBET'		=> '限制投注上限',
			'NOTHERCOUNT'	=> '配莊人數上限',
			'NOTHERMIN'		=> '配莊最低額度',
			'NOTHERMAX'		=> '配莊最高額度',
			'NBANKERLINECOUNT'=> '排莊人數上限',
			'NRETURNJACKPOT'	=> '吃彩返回彩池比例(%)',
			'NBANKERFEE'	=> '上莊費比例(%)',
			'NCOMPANYLEAST'	=> '公司佔成(%)',
			'NWATERMAX'		=> '返水總量(%)',
		),
	);

	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT 	nId,
					sName0,
					sParam,
					sUpdateTime
			FROM 		' . SYS_PARAM;
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		#$aData[$aRows['nId']] = $aRows;
		#$aData[$aRows['nId']]['sName1'] = aPARAM[strtoupper($aRows['sName0'])];

		$aTempData[strtoupper($aRows['sName0'])] = $aRows;
	}
	foreach ($aParam as $LPsDefineKey => $LPsLangName)
	{
		if (is_array($LPsLangName))
		{
			foreach ($LPsLangName as $LPsDefineKey1 => $LPsLangName1)
			{
				$aData[$LPsDefineKey1]['sName1'] = $LPsLangName1;
				$aData[$LPsDefineKey1]['sParam'] = '';
				$aData[$LPsDefineKey1]['sUpdateTime'] = '';
				if (isset($aTempData[$LPsDefineKey1]))
				{
					$aData[$LPsDefineKey1] = $aTempData[$LPsDefineKey1];
					$aData[$LPsDefineKey1]['sName1'] = $LPsLangName1;
				}
			}
		}
		else
		{
			if (!isset($aTempData[$LPsDefineKey]))
			{
				continue;
			}
			$aData[$LPsDefineKey] = $aTempData[$LPsDefineKey];
			$aData[$LPsDefineKey]['sName1'] = $LPsLangName;

		}
	}

	// $aData['NWATERLEAST']['sParam'] *= 2;
	// $aData['NCOMPANYLEAST']['sParam'] *= 2;

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>