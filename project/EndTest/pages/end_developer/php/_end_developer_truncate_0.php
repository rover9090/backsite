<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=>	'plugins/js/end_developer/end_developer_truncate.js',
	);

	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_developer/php/_end_developer_truncate_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/end_developer/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_developer_truncate_0.php',
	);

	$aValue = array(
		'a'	=> 'TRUNCATE',
		't' => NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aUrl['sAct'] .= '&sJWT='.$sJWT;
	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

	$aTruncate = array(
		'MANAGER'		=> '管理權限',
		'USER'		=> '會員管理',
		'MONEY'		=> '金流管理',
		'GAME'		=> '遊戲管理',
		'LOG'			=> '日誌',
		'OTHER'		=> '其他',
		'SCHEDULE'		=> '排程',
		'PASSWORD'		=> '請輸入管理員密碼',
		'INPUTUSER'		=> '其他保留會員帳號 請用逗號\'，\'隔開',
		'INPUTMANAGER'	=> '其他保留管理帳號 請用逗號\'，\'隔開',
		'ALL'			=> '全選',
		'DEALV'		=> '處理完成',
		'NODEL'		=> '不刪',
	);

	$aData = array(
		'Manager'	=> array(
			END_MANAGER_DATA		=> true,
			END_MANAGER_DETAIL	=> true,
			END_MANAGER_COOKIE	=> true,
			END_MENU_CTRL		=> true,
			SYS_GOOGLE_VERIFY		=> true,
		),
		'User'=> array(
			CLIENT_USER_DATA		=> true,
			CLIENT_USER_FS		=> true,
			CLIENT_USER_HIDE		=> true,
			CLIENT_USER_LINK		=> true,
			CLIENT_USER_MONEY		=> true,
			CLIENT_USER_BANK		=> true,
			CLIENT_USER_COOKIE	=> true,
			CLIENT_USER_VERIFY	=> true,
			CLIENT_USER_PROMO		=> true,
			CLIENT_USER_PROMO_FS	=> true,
		),
		'Money' => array(
			CLIENT_MONEY		=> true,
			CLIENT_PAYMENT		=> true,
		),
		'Game' => array(
			CLIENT_GAMES_DATA		=> true,
			CLIENT_GAMES_NUMS		=> true,
			CLIENT_GAMES_NUMS2	=> true,
			END_CHAT_MSG		=> true,
			CLIENT_GAMES_DONATE	=> true,
			CLIENT_GAMES_BANKER	=> true,		
			CLIENT_MALL			=> true,
			CLIENT_MALL_KIND		=> true,
			CLIENT_MALL_LOG		=> true,
			CLIENT_MALL_COLLECT	=> true,
			CLIENT_JACKPOT_LOG	=> true,
			CLIENT_JACKPOT_SET	=> true,
		),
		'Log' => array(
			END_LOG			=> true,
			END_LOG_ACCOUNT		=> true,
			END_MANAGER_LOGIN		=> true,
			CLIENT_USER_LOGIN		=> true,
		),
		'Other'=> array(
			CLIENT_ANNOUNCE		=> true,
			CLIENT_ANNOUNCE_KIND	=> true,
			CLIENT_BROADCAST 		=> true,
			CLIENT_WITHDRAWAL_LIST 	=> true,
			END_CHATROOM		=> true,
			END_CANNED			=> true,
			END_CANNED_KIND		=> true,
			CLIENT_IMAGE_CTRL		=> true,
			CLIENT_DATA_CTRL		=> true,
			END_CHAT_FILTER		=> true,
		),
		'Schedule'=> array(
			SYS_MOVE_RECORD		=> true,
			END_LOG_MOVE		=> true,
			END_LOG_ACCOUNT_MOVE	=> true,
			END_MANAGER_LOGIN_MOVE 	=> true,
			CLIENT_USER_LOGIN_MOVE 	=> true,
		),
	);

	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
?>