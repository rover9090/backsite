<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_group.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nLid		= filter_input_int('nLid', INPUT_GET,0);
	$nKindId	= filter_input_int('nKindId', INPUT_GET,0);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1.php']).'&nKindId='.$nKindId,
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_group_1_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'TW' => array(
			'BW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'PW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,				
			),
			'DRAW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'BP' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'PP' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'SIX' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
		),
		'CN' => array(
			'BW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'PW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'DRAW' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'BP' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'PP' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
			'SIX' => array(
				'nMoney0' => 0,
				'nMoney1' => 0,
			),
		),
	);
	$aValue = array(
		'a'		=> ($nLid == 0)?'INS':'UPT'.$nLid,
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nErr = 0;
	$sErrMsg = '';
	$nCount = 0;
	$nSort = 0;
	$aOnline = aONLINE;
	$aKind = array();
	$aName = array(
		'TW' => '',
		'CN' => '',
		'EN' => '',
	);

	$aBetContent = array(
		'BW' => aBETCONTENT['BW'],
		'PW' => aBETCONTENT['PW'],
		'DRAW' => aBETCONTENT['DRAW'],
		'BP' => aBETCONTENT['BP'],
		'PP' => aBETCONTENT['PP'],
		'SIX' => aBETCONTENT['SIX'],
	);
	#宣告結束

	#程式邏輯區

	$sSQL = '	SELECT	nId,
					nLid,
					nKid,
					sName0,
					sMethod,
					nMoney0,
					nMoney1,
					nSort,
					nOnline,
					sCreateTime,
					sUpdateTime,
					sLang
			FROM		'. END_GAMES_GROUP .'
			WHERE		nLid = :nLid
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData['nOnline'] = $aRows['nOnline'];
		$aData['nKid'] = $aRows['nKid'];
		$aData[$aRows['sLang']][$aRows['sMethod']] = $aRows;
		$aName[$aRows['sLang']] = $aRows['sName0'];
		$nSort = $aRows['nSort'];
		$nCount ++;
	}

	$sSQL = '	SELECT	nId,
					nLid,
					sName0
			FROM		'. END_GAMES_GROUP_KIND .'
			WHERE		nOnline = 1
			AND		sLang LIKE :sLang
			ORDER	BY	nId DESC ';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aKind[$aRows['nLid']] = $aRows;
		$aKind[$aRows['nLid']]['sSelect'] = '';
	}

	if($nCount == 0 && $nLid != 0)
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	if($nLid != 0)
	{
		$aOnline[$aData['nOnline']]['sSelect'] = 'selected';
		$aKind[$aData['nKid']]['sSelect'] = 'selected';
	}
	else
	{
		$aKind[$nKindId]['sSelect'] = 'selected';
	}

	foreach(aLANG as $LPsLang => $LPsText)
	{
		if(!isset($aData[$LPsLang]))
		{
			$aData[$LPsLang]['sName0'] = '';
		}
	}

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
		$aJumpMsg['0']['sClicktoClose'] = 1;
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