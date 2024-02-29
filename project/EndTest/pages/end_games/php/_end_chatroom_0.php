<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_chatroom.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'1' => 'plugins/js/tool/photon/Photon-Javascript_SDK.js',
		'2' => 'plugins/js/tool/photon/Photon_Interface.js',
		'3' => 'plugins/js/tool/BaseCmdLogic.js',
		'4' => 'plugins/js/tool/SocketNew.js',
		'5' => 'plugins/js/end_games/end_chatroom.js',
	);
	#js結束

	#參數接收區
	$nLobby	= filter_input_int('nLobby', 		INPUT_REQUEST, '1');
	$nGame	= filter_input_int('nGame', 		INPUT_REQUEST, '1001');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_chatroom_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_chatroom_0.php',
		'sPage_Mod' => '',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sNo'			=> '',
		'nStatus'		=> -1,
		'nButtonCtrl'	=> 0,
	);
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
	$aMsgData = array();

	$sUseUser = '0';
	
	#宣告結束

	#程式邏輯區
	// $aUrl['sPage_Mod'] = 'https://'.WEBSITE['NEWADM'].substr($aUrl['sPage'],2);
	$aUrl['sPage_Mod'] = $aUrl['sPage'];

	// 設定加socket帳號

	$sSQL = '	SELECT	sEmploye
			FROM	'. END_MANAGER_COOKIE .'
			WHERE	nUid = :nUid
			AND	sSid = :sSid
			LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nUid', $aAdm['nId'], PDO::PARAM_INT);
	$Result->bindValue(':sSid', $_COOKIE['sSid'], PDO::PARAM_STR);
	sql_query($Result);
	$aCookie = $Result->fetch(PDO::FETCH_ASSOC);

	if($aCookie != false && $aCookie['sEmploye'] == '')
	{
		$oPdo->beginTransaction();
		$sSQL = '	SELECT	sEmploye
				FROM	'. END_MANAGER_COOKIE .'
				WHERE	1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			if($aRows['sEmploye'] != '')
			{
				$sUseUser .= ','.$aRows['sEmploye'];
			}
		}
	
		$sSQL = '	SELECT	nId,
						sAccount
				FROM	'. CLIENT_USER_DATA .'
				WHERE	nOnline = 99
				AND	nId NOT IN ('.$sUseUser.')
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		$aNotUseUser = $Result->fetch(PDO::FETCH_ASSOC);
	
		$aSQL_Array = array(
			'sEmploye'		=> $aNotUseUser['nId'],
		);
	
		$sSQL = '	UPDATE '. END_MANAGER_COOKIE . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nUid = :nUid 
				AND	sSid = :sSid
				AND	sEmploye = \'\'
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aAdm['nId'], PDO::PARAM_INT);
		$Result->bindValue(':sSid', $_COOKIE['sSid'], PDO::PARAM_STR);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);
		$oPdo->commit();
	}
	else
	{
		$sSQL = '	SELECT	nId,
						sAccount
				FROM	'. CLIENT_USER_DATA .'
				WHERE	nId = :nId
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aCookie['sEmploye'], PDO::PARAM_INT);
		sql_query($Result);
		$aNotUseUser = $Result->fetch(PDO::FETCH_ASSOC);
	}
	

	$sSQL = '	SELECT	nId,
					nGame,
					sName0,
					nOnline,
					sOpenTime,
					sCloseTime
			FROM	'. END_GAMES_SETTING .'
			WHERE	nOnline = 1
			AND	sLang = :sLang
			ORDER BY nGame ASC';
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

	$sSQL = '	SELECT	nId,
					nRoom,
					nUid,
					sAccount,
					sSiteId,
					sName0,
					sMsg,
					nCreateTime,
					sCreateTime
			FROM	'. END_CHAT_MSG .'
			WHERE	nCreateTime > :nToday
			AND	nRoom = :nGame	
			ORDER BY nCreateTime ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nToday', NOWTIME-43200, PDO::PARAM_INT);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aMsgData[$aRows['nId']] = $aRows;
	}

	#輸出json
	$sData = json_encode($aData);
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit JqEnterClick';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel JqEscapeClick';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;


	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
	
?>