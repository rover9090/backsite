<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');

	$sAccount	= filter_input_str('sAccount',	INPUT_POST, '', 16);
	$sRawPassword = filter_input_str('sPassword', INPUT_POST, '');

	$nRemember	= 0;
	$sMsg = '';
	if(isset($_POST['nRemember']))
	{
		$nRemember = 1;
	}

	#登入
	if ($aJWT['a'] == 'LOGIN')
	{
		$aLoginData = array(
			'sAccount' => $sAccount,
			'sPassword' => oCypher::ReHash($sRawPassword,NULL,1),
			'nRemember' => $nRemember,
		);
		
		$nStatus = $oUser->login($aLoginData);
		if($nStatus === 1)
		{
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = aLOGIN['LOGINV'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			#$sMsg = aLOGIN['LOGINF'];
			#if($nLogin == -1)
			#{
			#	$sMsg = aLOGIN['LOGINDISABLE'];
			#}
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aERROR[$nStatus];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/login/php/_login_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	#登出
	if ($aJWT['a'] == 'LOGOUT')
	{
		$nHasCookie = $aJWT['nHasCookie'];
		$sAccount = isset($aJWT['sAccount'])?$aJWT['sAccount']:'';
		if(isset($_COOKIE['sUserData']))
		{
			$aUserJWT = $oJWT->validToken($_COOKIE['sUserData']);
		}
		
		$aData = array(
			'sAccount'	=> $sAccount,
		);
		$oUser->logout($aData);
		if(isset($aUserJWT['sBackurl']))
		{
			$sBackurl = $aUserJWT['sBackurl'];
		}
		else
		{
			$sBackurl = sys_web_encode($aMenuToNo['pages/login/php/_login_0.php']);
		}

		// setcookie('sUserData', '', 0);
		$sMsg = aLOGIN['LOGOUTV'];
		if ($aJWT['nStatus'] == 1)
		{
			$sMsg = aLOGIN['LOGINDISABLE'];
		}
		else if($aJWT['nStatus'] == 2 && ($nHasCookie == 1 || isset($_COOKIE['nTimeout'])))
		{
			// $sMsg = '閒置超過10分鐘並未投注';
			$sMsg = aLOGIN['TIMEOUT'];
			// setcookie('nTimeout', null, 0);
		}
		else
		{
			// trigger_error(print_r($aUserJWT,true).print_r($_COOKIE,true));
			if(stripos($_SERVER['HTTP_USER_AGENT'],'line') !== false)
			{
				echo '<script src="./plugins/js/jquery-3.3.1.min.js"></script>';
				echo '<script>
				alert(window.opener);
				window.opener=null;
				window.close();
				alert(\'close\')</script>';
				echo '<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>';
				echo '<script>alert(\'3\')</script>';
				echo '<script>
				liff.init({
					liffId:  \'1655984342-nQqb6Qwm\'
				}).then(() => {
				// 	alert(\'asdf\');
				// 	liff.closeWindow();
				});
				</script>';
				exit;
				
			}
			header('Location:'.$sBackurl);
			exit;	
		}

		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = $sMsg;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $sBackurl;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
?>