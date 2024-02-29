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
		'plugins/js/end_games/end_games_ctrl.js',
	);
	#js結束

	#參數接收區
	$nLobby	= filter_input_int('nLobby', 		INPUT_REQUEST, '1');
	$nRoom	= filter_input_int('nRoom', 		INPUT_REQUEST, '1');
	$nGame	= filter_input_int('nGame', 		INPUT_REQUEST, '1001');

	if(!isset($_POST['nGame']) && isset($_COOKIE['nGame']))
	{
		$nGame = $_COOKIE['nGame'];
	}
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sNumsAjax0'=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1_ajax0.php']),
		'sReSettle' => sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_ctrl_1.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sNo'			=> '',
		'nStatus'		=> -1,
	);
	$aValue = array(
		'a'	=> 'NUMSAJAX',
		't'	=> NOWTIME,
	);
	$sJWTNums = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'GAMECTRL',
		't'	=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEWDECK',
		't'	=> NOWTIME,
	);
	$sNewDeckJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEWDEALER',
		't'	=> NOWTIME,
	);
	$sNewDealerJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'REDCARD',
		't'	=> NOWTIME,
	);
	$sRedCardJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'LASTCOUNT',
		't'	=> NOWTIME,
	);
	$sLastCountJWT = sys_jwt_encode($aValue);
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
	$aLockPage = array(
		'sCheck' => '',
		'sText' => aGAMECTRL['REMEMBER'],
	);
	$aNums = array();
	$aPlayerStr = array();
	$aBankerStr = array();
	$aPlayer = array();
	$aBanker = array();
	$sBanker = '';
	$sPlayer = '';
	$sRoom = '';
	$aTextareaHint = array(
		'0' => aGAMECTRL['PUT0'],
		'1' => aGAMECTRL['PUT1'],
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
	$sConfirmMsg = aGAMECTRL['SETTLENEW'];
	$sShufflingClass = '';
	$sLastCountClass = '';
	$sFinalRoundClass = '';
	$nLastCount = 0;
	$aLastDoneNo = array();
	$nNextRound = 0;
	$nNowRound = 0;
	#宣告結束

	#程式邏輯區
	if(isset($_COOKIE['nGame']) && $nGame == $_COOKIE['nGame'])
	{
		$aLockPage['sCheck'] = 'checked';
	}

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
			AND	nGame < 2000
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
		$nSettle = 0;
		$sConfirmMsg = aMsg['NEWNO'];
	}
	else
	{
		$aNums = json_decode($aData['sNums'],true);

		if($aData['nDayLast'] > 0)
		{
			if($aData['nDayLast'] == 1 && $aData['sNums'] != '')
			{
				$nLastCount = 0;
			}
			else
			{
				$nLastCount = $aData['nDayLast'];
			}
			$sLastCountClass = 'red';
		}

		if($aData['nFinalRound'] == 1 && $aData['sResult'] == '')
		{
			$sFinalRoundClass = 'red';
		}

		if($aData['nFinalRound'] == 1 || $aData['nDayLast'] == 1 || $aData['nCancel'] == 1)
		// if($aData['nUse'] == 0)
		{
			$nNextRound = 1;
			if($aData['nUse'] == 1)
			{
				$nNextRound = ($aData['nRound'] + 2).'('.aMsg['NOSHUFFLE'].')';
			}
			$nNowRound = $aData['nRound'] + 1;
		}
		else if($aData['nCancel'] == 2)
		{
			$nNextRound = $aData['nRound'] + 1;
			$nNowRound = $aData['nRound'];
		}
		else
		{
			$nNextRound = $aData['nRound'] + 2;
			$nNowRound = $aData['nRound'] + 1;
		}

		if($aData['nFinalRound'] == 1 || $aData['nDayLast'] == 1 || $aData['nCancel'] >= 1)
		{
			if($aData['sResult'] == '')
			{
				$nBuild = 0;
				$nSettle = 1;
				$sConfirmMsg = aMsg['SETTLE'];
			}
			else
			{
				$nBuild = 1;
				$nSettle = 0;
				$sConfirmMsg = aMsg['NEWNO'];
			}

			if($aData['nCancel'] > 0)
			{
				$nBuild = 1;
				$nSettle = 0;
				$sConfirmMsg = aMsg['NEWNO'];
				if($aData['nCancel'] == 2 && $aData['nUse'] == 1)
				{
					$sConfirmMsg = aMsg['RESTART'];
				}
			}
		}
		else
		{
			$nBuild = 1;
			$nSettle = 1;
		}
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
		$aLastDoneNo = array('sNo'=>'','sNums'=>'','nRound'=>0);
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

	$sSQL = '	SELECT	nId,
					nDealer,
					nDelta
			FROM		'. END_LOG_ACCOUNT .'
			WHERE		nType0 = 3
			AND		nDealer = :nDealer
			AND		nCreateTime > :nCreateTime';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nDealer', $aGame_Setting[$nGame]['nDealer'], PDO::PARAM_INT);
	// $Result->bindValue(':nCreateTime', 0, PDO::PARAM_INT);
	$Result->bindValue(':nCreateTime', strtotime('today'), PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aDonateData[$aRows['nDealer']]))
		{
			$aDonateData[$aRows['nDealer']]['nSum'] = 0;
		}
		$aDonateData[$aRows['nDealer']]['nSum'] += ($aRows['nDelta'] * -1);
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit JqEnterClick';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel JqEscapeClick';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aJumpMsg['1'] = $aJumpMsg['0'];
	$aJumpMsg['1']['aButton']['0']['sClass'] = 'submit JqEnterClick JqCancelNo';
	$aJumpMsg['2'] = $aJumpMsg['0'];
	$aJumpMsg['3'] = $aJumpMsg['0'];
	$aJumpMsg['4'] = $aJumpMsg['0'];
	$aJumpMsg['5'] = $aJumpMsg['0'];
	$aJumpMsg['5']['sMsg'] = '<div>'.aGAMECTRL['PUTADMID'].'</div><div class="Ipt"><input inputmode="Decimal" class="JqPutDealerId"></div>';
	$aJumpMsg['7'] = $aJumpMsg['0'];
	$aJumpMsg['8'] = $aJumpMsg['0'];

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
		$aReturn['sDataText'] = $aReturn['sData'];
		// foreach($aText as $LPsCard)
		// {
		// 	$aReturn['sDataText'] .= aCARDFLO[$LPsCard].',';
		// }

		return $aReturn;
	}
?>