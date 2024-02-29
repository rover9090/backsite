<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ctrl.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'plugins/js/end_games/end_games_check.js',
	);
	#js結束

	#參數接收區
	$nLobby	= filter_input_int('nLobby', 		INPUT_REQUEST, '1');
	$nRoom	= filter_input_int('nRoom', 		INPUT_REQUEST, '1');
	$nGame	= filter_input_int('nGame', 		INPUT_REQUEST, '1001');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sResultAjax0'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_stream_0_ajax0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_check_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_check_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sNo'			=> '',
		'nStatus'		=> -1,
	);
	$aValue = array(
		'a'	=> 'LOBBY',
		't'	=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$nCount = 0;
	$aStatus = aGAMECTRL['aSTATUS'];
	// $aResult0 = aGAMECTRL['aRESULT0'];
	// $aResult1 = aGAMECTRL['aRESULT1'];
	// $aRoom = aGAMECTRL['aROOM'];
	// $aSpecial = aGAMECTRL['aSPECIAL'];
	$aRoomData = array(
		'sId' => '',
	);
	$nTotalRoom = 2;
	$aDonateData = array();
	$aNums = array();
	$aPlayerStr = array();
	$aBankerStr = array();
	$aPlayer = array();
	$aBanker = array();
	$sBanker = '';
	$sPlayer = '';
	$sRoom = '';
	$aTextareaHint = array(
		'0' => '輸入莊家結果',
		'1' => '輸入閒家結果',
		// '2' => '輸入Ｂ門結果',
		// '3' => '輸入Ｃ門結果',
	);
	$sTextareaHint = '';
	$sBankerStr = '';
	$sPlayerStr = '';
	$nBuild = 0;
	$nSettle = 0;
	$nShuffling = 0;

	$aGame_Setting = array();
	$sConfirmMsg = '結算後新局';
	$sShufflingClass = '';
	$sLastCountClass = '';
	$sFinalRoundClass = '';
	$nLastCount = 0;
	$aLastDoneNo = array();
	$nNextRound = 0;
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nId,
					nGame,
					sName0,
					nOnline,
					sOpenTime,
					sCloseTime,
					nDealer,
					nShuffling
			FROM	'. END_GAMES_SETTING .'
			WHERE	nOnline = 1
			AND	sLang = :sLang
			ORDER	BY sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if ($nGame < 0)# 預設
		{
			$nGame = $aRows['nId'];
		}
		$aRows['sLang'] = $aRows['sName0'];
		$aGame_Setting[$aRows['nGame']] = $aRows;
		$aGame_Setting[$aRows['nGame']]['sSelect'] = '';
		if($nGame == $aRows['nGame'])
		{
			$aGame_Setting[$aRows['nGame']]['sSelect'] = 'selected';
		}
	}

	if($aGame_Setting[$nGame]['nShuffling'] == 1)
	{
		$nShuffling = 0;
		$sShufflingClass = 'red';
	}
	else
	{
		$nShuffling = 1;
		$sShufflingClass = '';
	}

	$sSQL = '	SELECT	nId,
					sAccount,
					sName0
			FROM		'. END_MANAGER_DATA .'
			WHERE		nId = :nId
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $aGame_Setting[$nGame]['nDealer'], PDO::PARAM_INT);
	sql_query($Result);
	$aDealer = $Result->fetch(PDO::FETCH_ASSOC);
	if($aDealer == false)
	{
		$aDealer = array(
			'nId' => 0,
			'sName0' => '',
		);
	}
	$aDonateData[$aDealer['nId']]['nSum'] = 0;

	$sSQL = '	SELECT	nId,
					nGame,
					sNo,
					nStatus,
					sNums,
					nRound,
					nUse,
					nBetEndTime,
					nFinalRound,
					sResult,
					nDayLast,
					nCancel
			FROM		'. CLIENT_GAMES_NUMS .'
			WHERE		nGame = :nGame
			ORDER BY	nId DESC
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);

	if($aData === false)
	{
		$aData = array(
			'sNo'			=> aMsg['NODATA'],
			'nStatus'		=> 99,
		);
		$nBuild = 1;
	}
	else
	{
		$aNums = json_decode($aData['sNums'],true);
	}
	// $nTotalRoom = count($aRoom);
	// $aNums['sPlayer'] = isset($aNums['sPlayer'])?$aNums['sPlayer']:'';

	$sSQL = '	SELECT	sNo,sNums,nRound
			FROM		'. CLIENT_GAMES_NUMS .'
			WHERE		nGame = :nGame
			AND		sResult != \'\'
			ORDER BY	nId DESC
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	$aLastDoneNo = $Result->fetch(PDO::FETCH_ASSOC);
	if($aLastDoneNo == false)
	{
		$aLastDoneNo = array('sNo'=>'');
	}
	$aNums = json_decode($aLastDoneNo['sNums'],true);
	if(isset($aNums['sBanker']))
	{
		$aBanker = explode(',',$aNums['sBanker']);
	}
	$aBankerStr = fnChangeText($aBanker);
	if(isset($aNums['sPlayer']))
	{
		$aPlayer = explode(',',$aNums['sPlayer']);
	}
	$aPlayerStr = fnChangeText($aPlayer);

	// echo '<pre>';print_r($aData);echo '</pre>';

	$aRoomData['sId'] = md5($nGame);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束

	function fnChangeText($aText)
	{
		$aData = array();
		$aReturn = array(
			'nError' => 0,
			'aData' => array(),
			'sData' => '',
			'sDataText' => '',
		);

		$aReturn['aData'] = $aText;
		$aReturn['sData'] = implode('.',$aText);
		
		foreach($aText as $LPsCard)
		{
			$aReturn['sDataText'] .= aCARDFLO[$LPsCard].',';
		}

		return $aReturn;
	}
?>