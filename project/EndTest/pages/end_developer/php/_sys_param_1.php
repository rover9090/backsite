<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/sys_param.php');
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
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_developer/php/_sys_param_1_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_developer/php/_sys_param_1.php']),
		'sHtml'	=> 'pages/end_developer/'.$aSystem['sHtml'].$aSystem['nVer'].'/sys_param_1.php',
	);
	#url結束

	#參數宣告區
	$aParam = aPARAM;
	$aTempData = array();
	$aData = array();
	$aValue = array(
		'a'		=> 'UPT',
		't'		=> NOWTIME,
	);
	$sJWTAct = sys_jwt_encode($aValue);
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
			FROM 		sys_param
			WHERE 	nType0 = 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aTempData[strtoupper($aRows['sName0'])] = $aRows;
	}

	unset($aParam['aSMS']);
	unset($aParam['aTIMELIMIT']);
	foreach ($aParam as $LPsDefineKey => $LPsLangName)
	{
		if (is_array($LPsLangName))
		{
			foreach ($LPsLangName as $LPsDefineKey1 => $LPsLangName1)
			{				
				if (isset($aTempData[$LPsDefineKey1]))
				{
					$aData[$LPsDefineKey1]['sName1'] = $LPsLangName1;
					$aData[$LPsDefineKey1]['sParam'] = '';
					$aData[$LPsDefineKey1]['sUpdateTime'] = '';
					
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

	// echo print_r($aData,true);exit;
	// $aData['NWATERLEAST']['sParam'] *= 2;
	// $aData['NCOMPANYLEAST']['sParam'] *= 2;
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>