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
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_data_0_upt0.php',

	);
	#url結束

	#參數宣告區
	$aData = array(
		'nId'			=> $nId,
		'sCreateTime'	=> '',
		'sUpdateTime'	=> '',
		'sName0'		=> '',
		'sName1'		=> '',
		'sAccount'		=> '',
		'nStatus'		=> 0,
		'nIdentity'		=> 1,
		'aType'		=> array(
			'1'	=> '',
			'99'	=> '',
		),
	);
	$aStatus = array(
		'0' => array(
			'sTitle' => aUSER['STATUS0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['STATUS1'],
			'sSelect'=> '',
		),
	);

	$aBlack = array(
		'0' => array(
			'sTitle' => aUSER['BLACK0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['BLACK1'],
			'sSelect'=> '',
		),
	);

	$aMute = array(
		'0' => array(
			'sTitle' => aUSER['MUTE0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['MUTE1'],
			'sSelect'=> '',
		),
	);

	$aTest = array(
		'0' => array(
			'sTitle' => aUSER['TEST0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['TEST1'],
			'sSelect'=> '',
		),
	);

	$aHide = array(
		'0' => array(
			'sTitle' => aUSER['HIDE0'],
			'sSelect'=> '',
		),
		'1' => array(
			'sTitle' => aUSER['HIDE1'],
			'sSelect'=> '',
		),
	);

	$aIdentity = array(
		'0' => aUSER['IDENTITY0'],
		'1' => aUSER['IDENTITY1'],
	);

	$sJWTAct = '';
	$nErr = 0;
	$sErrMsg = '';
	$sCondition = '';

	// 代理帳號只能查自己下線
	if($aAdm['nUid'] != 0)
	{
		$sCondition .= ' AND Link_.sLinkList LIKE :sLinkList';
		$aBindArray['sLinkList'] = sprintf('%%%09d%%',$aAdm['nUid']);
	}

	#宣告結束

	#程式邏輯區

	// 代理帳號只能查自己下線
	if($aAdm['nUid'] != 0 && $nId > 0)
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

	$sSQL = '	SELECT 	nId,
					nStatus,
					nBlack,
					nMute,
					sName0,
					sName1,
					nIdentity,
					sCreateTime,
					sUpdateTime,
					sAccount,
					nTest,
					nHide
			FROM 		'. CLIENT_USER_DATA .'
			WHERE 	nId = :nId
			AND 		nOnline != 99
			LIMIT 	1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$nCount = $Result->rowCount();
	if ($nCount == 0 && $nId > 0 /*&& ($aAdm['nAdmType'] != 1 && $aAdm['nAdmType'] != 2)*/)
	{
		$nErr = 1;
		$sErrMsg = NODATA.'2';
	}
	else
	{
		while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData = $aRows;
			$aStatus[$aData['nStatus']]['sSelect'] = 'selected';
			$aBlack[$aData['nBlack']]['sSelect'] = 'selected';
			$aMute[$aData['nMute']]['sSelect'] = 'selected';
			$aTest[$aData['nTest']]['sSelect'] = 'selected';
			$aHide[$aData['nHide']]['sSelect'] = 'selected';
		}
		$aData['aType'] = array(
			'1'	=> '',
			'99'	=> '',
		);

		$sSQL = '	SELECT 	nUid,
						nOnline
				FROM 		'. CLIENT_USER_HIDE .'
				WHERE nUid = :nUid
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aData['nId'], PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		if ($aRows !== false)
		{
			$aData['aType'][$aRows['nOnline']] = 'checked';
		}
		else
		{
			$aData['aType'][99] = 'checked';
		}
	}

	$aValue = array(
		'a'		=> ($nId == 0)?'INS':'UPT'.$aData['nId'],
		't'		=> NOWTIME,
		'nId'		=> $aData['nId'],
	);
	$sJWTAct = sys_jwt_encode($aValue);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if ($nErr == 1)
	{
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['nClicktoClose'] = 1;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>