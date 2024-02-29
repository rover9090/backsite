<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
	);
	#js結束

	#參數接收區
	$nId		= filter_input_int('nId',	INPUT_GET, 0);
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sUpt0'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt0.php']),
		'sUpt1'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt1.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_data_0_upt1.php',
	);
	#url結束

	#參數宣告區
	$aUserData = array();
	$aUserFs = array();
	$aPaFs = array();
	$sJWTAct = '';
	$sSQLLang = 'sLang'.$aSystem['sLang'];

	$nErr = 0;
	$sErrMsg = '';

	#宣告結束

	#程式邏輯區

	// 代理帳號只能查自己下線
	if($aAdm['nUid'] != 0)
	{
		$sSQL = '	SELECT 	1
				FROM 	'.CLIENT_USER_LINK.'
				WHERE nUid = :nId
				AND	sLinkList LIKE :sLinkList
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		$Result->bindValue(':sLinkList', sprintf('%%%09d%%',$aAdm['nUid']), PDO::PARAM_STR);
		sql_query($Result);
		$aFindLink = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aFindLink === false)
		{
			$nErr	= 1;
			$sErrMsg	= NODATA.'<br>';
		}
	}

	$sSQL = '	SELECT 	User_.nId,
					User_.nStatus,
					User_.sName0,
					User_.nIdentity,
					Link_.nPa
			FROM 		'.CLIENT_USER_DATA.' User_,
					'.CLIENT_USER_LINK.' Link_
			WHERE 	User_.nId = Link_.nUid
			AND		User_.nId = :nId
			AND 		User_.nOnline != 99
			LIMIT 	1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aUser = $Result->fetch(PDO::FETCH_ASSOC);
	if ($aUser === false)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	$sSQL = '	SELECT 	nId,
					sWater,
					sPercent
			FROM 		'.CLIENT_USER_FS.'
			WHERE 	nUid = :nId
			AND 		nEndTime = 0';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);
	if($aData === false)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}
	else
	{
		$aData['sName0'] = $aUser['sName0'];
	}

	if($aUser['nPa'] != 0)
	{
		$sSQL = '	SELECT 	nId,
						sWater,
						sPercent
				FROM 		'.CLIENT_USER_FS.'
				WHERE 	nUid = :nPa
				AND 		nEndTime = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nPa', $aUser['nPa'], PDO::PARAM_INT);
		sql_query($Result);
		$aPaFs = $Result->fetch(PDO::FETCH_ASSOC);
		if($aPaFs === false)
		{
			$nErr = 1;
			$sErrMsg = NODATA;
		}
	}


	$aValue = array(
		'a'		=> 'UPTFS'.$nId,
		't'		=> NOWTIME,
	);
	$sJWTAct = sys_jwt_encode($aValue);

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if ($nErr == 1)
	{
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['nClicktoClose'] = 1;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>