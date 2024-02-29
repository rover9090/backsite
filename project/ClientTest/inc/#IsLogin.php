<?php
	require_once(dirname(dirname(dirname(__file__))) .'/System/Connect/UserClass.php');
	require_once(dirname(dirname(dirname(__file__))) .'/System/Connect/ClientUserClass.php');
	$nS		= filter_input_int('run_page',	INPUT_REQUEST, 0);
	$nDebug 	= filter_input_int('dg',		INPUT_GET, 0);# 1=> 顯示路徑 2=> 顯示路徑 + exit;
	$nIsLoginLobby 	= filter_input_int('nLobby',		INPUT_GET, 0);
	$nGame 	= filter_input_int('nGame',		INPUT_GET, 1001);
	$sLineId 		= filter_input_str('sLineId', INPUT_REQUEST, '');
	$sLinePicture 	= filter_input_str('sLinePicture', INPUT_REQUEST, '');
	$aUser = array('nId' => 0,'sName0'=>'','nMoney'=>'');
	$sNowLiff = LINELIFFBET;
// trigger_error($aRequire['Require']);exit;
	$sLogOutJWT = '';
	$aLobbyName = array();
	$bFind = true;
	$sSid = isset($_COOKIE['sSid']) ? $_COOKIE['sSid'] : '';
	$nVideoOpen = isset($_COOKIE['nVideoOpen']) ? $_COOKIE['nVideoOpen'] : '1';
	// $sUserJWT = isset($_COOKIE['sUserJWT']) ? $_COOKIE['sUserJWT'] : '';
	$oUser = new oClientUser();
	$aLogOutJWT = array(
		'a'		=> 'LOGOUT',
		't'		=> NOWTIME,
		'nExp'	=> NOWTIME + JWTWAIT,
		'nStatus'	=> 0,
		'nHasCookie'=> isset($_COOKIE['sSid']) ?1:0,
	);
	$aLogOutErrorJWT = $aLogOutJWT;
	$aLogOutTimeoutJWT = $aLogOutJWT;
	$aLogOutErrorJWT['nStatus'] = 1;
	$aLogOutTimeoutJWT['nStatus'] = 2;

	
	if ($aSystem['nLogin'] == 1) # 需要判斷登入頁面
	{
		if((int)($nGame/1000) == 2)
		{
			$nIsLoginLobby = 2;
		}
		if($nS == 0)
		{
			$aLobbyName[0] = array(
				'sName0' => aCENTER['BACCARAT'],
				'sActive' => '',
				'sUrl' => sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']).'&nLobby=0',
			);
			$sSQL = '	SELECT 	nId,nLobby,sName0
					FROM  	'.END_GAMES_LOBBY.'
					WHERE 	sLang = :sLang';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aLobbyName[$aRows['nLobby']] = array(
					'sName0' => $aRows['sName0'],
					'sActive' => '',
					'sUrl' => sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']).'&nLobby='.$aRows['nLobby'],
				);
			}
			$aLobbyName[$nIsLoginLobby]['sActive'] = 'active';
		}
		$aRank = array();
		// $aUserJWT = $oJWT->validToken($sUserJWT);
		#判斷有沒有登入		
		// $nUid = $oUser->checkCookie();
		// if($sSid != '' && $nUid == 0)
		// {
		// 	setcookie('nTimeout', 1, COOKIE['REMEMBER']);
		// }
		
		// if ($nUid == 0)
		// {
		// 	$sLogOutTimeoutJWT = sys_jwt_encode($aLogOutTimeoutJWT);
		// 	header('Location:'.sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1&sJWT='.$sLogOutTimeoutJWT);
		// 	exit;	
		// }

		// if(isset($_COOKIE['sUserData']))
		// {
		// 	setcookie('sUserData', $_COOKIE['sUserData'], COOKIE['REMEMBER']);
		// }

		// $sSQL = '	SELECT	nCreateTime
		// 		FROM		'.CLIENT_GAMES_DATA.'
		// 		WHERE		nUid = :nUid
		// 		AND		nCreateTime > :nCreateTime
		// 		ORDER BY nCreateTime DESC
		// 		LIMIT 	1';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':nUid', 		$nUid,	PDO::PARAM_INT);
		// $Result->bindValue(':nCreateTime', 	COOKIE['TIMEOUT'],	PDO::PARAM_INT);
		// sql_query($Result);
		// $aLastBet = $Result->fetch(PDO::FETCH_ASSOC);

		// $sSQL = '	SELECT	nUpdateTime,nCreateTime
		// 		FROM		'.USER_COOKIE.'
		// 		WHERE		nUid = :nUid
		// 		LIMIT 	1';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':nUid', 		$nUid,	PDO::PARAM_INT);
		// sql_query($Result);
		// $aUserCookie = $Result->fetch(PDO::FETCH_ASSOC);

		// if($aLastBet === false)
		// {
		// 	$aLastBet['nCreateTime'] = $aUserCookie['nCreateTime'];
		// }

		// if($aSystem['nConnect'] == 2)
		// {
		// 	// 判斷一秒內連點
		// 	if($aUserCookie['nUpdateTime'] >= (NOWTIME-1))
		// 	{
		// 		unset($aRequire['Require']);

		// 		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']);
		// 		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		// 		$aJumpMsg['0']['sIcon'] = 'error';
		// 		$aJumpMsg['0']['sMsg'] = MANYCLICK;
		// 		$aJumpMsg['0']['sShow'] = 1;
		// 		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		// 	}
		// }

		$sSQL = '	SELECT 	User_.nId,
						User_.sName0,
						User_.sAccount,
						User_.nStatus,
						User_.nIdentity,
						User_.nCreateTime,
						User_.sPicture,
						User_.sSiteId,
						User_.nMute,
						User_.nTest,
						User_.sSiteId,
						Money_.nMoney
				FROM 		'.CLIENT_USER_DATA.' User_
				JOIN		'.CLIENT_USER_MONEY.' Money_
				ON		User_.nId = Money_.nUid
				WHERE 	User_.sLineId = :sLineId
				AND		nOnline != 99
				AND		nStatus != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLineId', $sLineId, PDO::PARAM_STR);
		sql_query($Result);
		$aUser = $Result->fetch(PDO::FETCH_ASSOC);
		if(!empty($aUser) && $sLineId !== '')
		{
			$aUser['aMall'] = array();
			$aUser['nUid'] = $aUser['nId'];
			$aUser['nMoney'] = (float)$aUser['nMoney'];
			$aUser['sName0'] = $aUser['sName0'] == ''?$aUser['sAccount']:$aUser['sName0'];
			$aUser['nVideoOpen'] = $nVideoOpen;
			$nUid = $aUser['nId'];
			$aLogOutErrorJWT['sAccount'] = $aUser['sAccount'];
			$aLogOutJWT['sAccount'] = $aUser['sAccount'];
			$aPoint = oTransfer::PointCheck($aUser['nId']);
			if($sLinePicture != $aUser['sPicture'] && $sLinePicture !== '')
			{
				$aSQL_Array = array(
					'sPicture'		=> (string) $sLinePicture,
					'nUpdateTime'	=> (int) 	NOWTIME,
					'sUpdateTime'	=> (string) NOWDATE,
				);

				$sSQL = '	UPDATE '. CLIENT_USER_DATA .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aUser['sPicture'] = $sLinePicture;
			}

			$sSQL = '	SELECT 	nId,
							nMid
					FROM 		'.CLIENT_MALL_COLLECT.'
					WHERE 	nUid = :nUid
					AND		nOnline = 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				if(!isset($aUser['aMall'][$aRows['nMid']]))
				{
					$aUser['aMall'][$aRows['nMid']] = array('nStock'=>0);
				}
				$aUser['aMall'][$aRows['nMid']]['nStock']++;
			}
			if($aUser['nStatus'] === '1')
			{
				$aJumpMsg['0']['aButton']['0']['sUrl'] = 'javascript:void(0)';
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = USERBANED;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
			{
				if(!isset($_COOKIE['gino']))
				{
					// echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					// exit;
					header('Location: '.'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
					exit;
				}
			}
		}
		else
		{
			if($sLineId !== '')
			{
				$aJumpMsg['0']['aButton']['0']['sUrl'] = 'javascript:void(0)';
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = BINDFIRST;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$aPageRequire = explode('/',$aRequire['Require']); # $aPage會跟#Define.php衝到
				$sPage = isset($aPageRequire[3])?str_replace('.php','',$aPageRequire[3]):'';
				if($sPage == '_bet_record_0')
				{
					echo '<input type="hidden" name="sLineId" 	value="'.$sLineId.'">';
					echo '<input type="hidden" name="sLiffId" 	value="1656844931-nqJqqy8g">';
					echo '<script src="plugins/js/jquery-3.3.1.min.js"></script>';
					echo '<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>';
					echo '<script src="plugins/js/main.js?'.NOWTIME.'"></script>';
					echo '<script src="plugins/js/tool/timeCreate.js?'.NOWTIME.'"></script>';
					exit;
				}

				echo '<input type="hidden" name="sLineId" 	value="'.$sLineId.'">';
				echo '<input type="hidden" name="sLiffId" 	value="'.$sNowLiff.'">';
				echo '<script src="plugins/js/jquery-3.3.1.min.js"></script>';
				echo '<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>';
				echo '<script src="plugins/js/main.js?'.NOWTIME.'"></script>';
				echo '<script src="plugins/js/tool/timeCreate.js?'.NOWTIME.'"></script>';
				exit;
			}
		}		
		
		$aChkLogin_Member = $aUser;

		// $sLogOutErrorJWT = sys_jwt_encode($aLogOutErrorJWT);
		// $sLogOutJWT = sys_jwt_encode($aLogOutJWT);
		// 禁止登入
		// if($aUser === false || $aUser['nStatus'] == 10 || $aPoint == false)
		// {
		// 	header('Location:'.sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1&sJWT='.$sLogOutErrorJWT);
		// 	exit;
		// }

		// if($aUser['nTest'] == 0 && $aLastBet['nCreateTime'] < COOKIE['TIMEOUT'])
		// {
		// 	$sLogOutTimeoutJWT = sys_jwt_encode($aLogOutTimeoutJWT);
		// 	header('Location:'.sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1&sJWT='.$sLogOutTimeoutJWT);
		// 	exit;
		// }
	}

	$aValue = array(
		'a'		=> 'realtimeUPT'.$aUser['nId'],
		't'		=> NOWTIME,
		'nId'		=> $aUser['nId'],
	);
	$sJWT = sys_jwt_encode($aValue);
	$aData = array(
		'sJWT'	=> $sJWT,
	);
	$aUpdateUserPack = array(
		'a'		=> 'updateUser',
		't'		=> NOWTIME,
	);
	$sUpdateUser = sys_jwt_encode($aUpdateUserPack);


	// $sUrl = WEBSITE['WEBURL'].sys_web_encode($aMenuToNo['pages/tool/php/_realtime_0_act0.php']).'&run_page=1';
	// $ch = curl_init($sUrl);
	// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aData));
	// # curl 執行時間
	// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
	// curl_setopt($ch, CURLOPT_TIMEOUT,3);
	// curl_exec($ch);

	switch ($nDebug)
	{
		case '1':
			echo $aRequire['Require'].'<br />';
			break;
		case '2':
			echo $aRequire['Require'].'<br />';exit;
			break;
	}
	if (isset($aRequire['Require']) && $bFind == true)
	{
		require_once($aRequire['Require']);
	}

?>