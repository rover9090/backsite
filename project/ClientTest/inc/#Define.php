<?php
	define('SYSLOAD', true); // #Unload.php 使用
	define('aCTRL',	array(
		'FIND'		=> 6,
		'GET'			=> 15,
	));

	define('SYS',	array(
		'DEFAULTPAGE'	=> 'RLIx201', #預設頁面 (menu)
		'KEY'			=> substr(md5('T20210205LIMEGAME03'),aCTRL['FIND'],aCTRL['GET']), #站內頁面切換密鑰
		'PWDKEY'		=> substr(md5('2021TLIMEGAME030205'),aCTRL['FIND'],aCTRL['GET']), #密碼加密密鑰
	));
	# jwt
	define('JWTALG','SHA256');
	define('JWTWAIT',300);
	define('aKEYCTRL',array(
		'LEFT'		=> 7,
		'LEFTLEN'		=> 6,
		'RIGHT'		=> 20,
		'RIGHTLEN'		=> 8,
	));

	#line api token
	// define('aLINEROBOT', array(
	// 	'REPLYLOCALURL'		=> 'https://demo801.monopoly168.com/Project/056ingly/EndTest/Bot/reply.php',
	// 	'SCHEDULELOCALURL'	=> 'https://demo801.monopoly168.com/Project/line_base/EndTest/Bot/schedule.php',
	// 	'CHANNELID'			=> '1656807386',
	// 	'CHANNELSECRET'		=> '939573800b22c5f8ae6d6d1111decdd5',
	// 	'CHANNELTOKEN'		=> 'h7mZ5WKYXOloBd5BU13iZiZq6tA4TDE3qkL+3D8f1t80U8EjJYBurHBUxLZNJmPNQ/AXIgd6EItwErzbVU9sitHA3ugQyx5xkdDBUX+w37j6giR1C6eDoRr7gXCoEs0eLM8E/DbkPwqJUWIubE2SZgdB04t89/1O/w1cDnyilFU=',
	// 	'REPLYURL'	 		=> 'https://api.line.me/v2/bot/message/reply',
	// 	'PUSHURL'	 		=> 'https://api.line.me/v2/bot/message/push',
	// 	'MULTIURL'	 		=> 'https://api.line.me/v2/bot/message/multicast',
	// 	'BORADURL'	 		=> 'https://api.line.me/v2/bot/message/broadcast',
	// 	'ADDBOT'			=> 'https://line.me/R/ti/p/%40056ingly',#056ingly
	// 	'ONLYUSER'			=> '',
	// ));

	// define('aLINELOGIN', array(
	// 	'LIFFID'		=> '1656807556',
	// 	'LIFFURL'		=> array(
	// 		'0'	=> 'https://liff.line.me/1656807556-vRAzG1Gy'
	// 	),
	// ));

	
	# LINE
	define('LINELIFFBET','1656844931-RdAGGDy9');

	define('COOKIE',	array(
		'TIMEOUT'	=> NOWTIME - 60*10,
		'CLOSE'	=> NOWTIME - 60*60,
		'REMEMBER'	=> NOWTIME + 3600*24,
	));

	#2020-03-10 YL,BQ js要冠上時間 (先改V再改時間)
 	define('VTIME', '20210618V01'.NOWTIME);

	define('aICON', array(
		'ENDMANAGER'		=> 'fas fa-cogs',
		'ENDNAVIGATION'		=> 'folder-open',
		'CLIENTUSERDATA'		=> 'users',
		'ENDREPORT'			=> 'file-alt',
		'CLIENTCASHFLOW'		=> 'money-check-alt',
		'SYSWEBSITE'		=> 'server',
		'CLIENTLOTTERY'		=> 'splotch',
		'ENDLOG'			=> 'book',
		'ENDDEVELOPER'		=> 'wrench'
	));

	$aPage = array(
		'nStyle'	      	=> 1, #1 = 清單式 ,  2 = 下拉式
		'aButton'	      	=> array(
			'nHeadTailShowStyle'	=> 0, #按鈕顯示方式(第一頁,最末頁) , 0 => 文字 , 1 => icon
			'nPrevNext10ShowStyle'	=> 1, #按鈕顯示方式(上十頁,下十頁) , 0 => 文字 , 1 => icon
			'nPrevNextShowStyle'	=> 1, #按鈕顯示方式(上一頁,下一頁) , 0 => 文字 , 1 => icon
			'nHeadTail'		=> 1, #第一頁&最末頁 , 0 => 不顯示 , 1 => 顯示
			'nPrevNext'		=> 1, #上一頁&下一頁 , 0 => 不顯示 , 1 => 顯示
			'nPrevNext10'	=> 1, #上十頁&下十頁 , 0 => 不顯示 , 1 => 顯示
			'nRecordAmount'	=> 0, #紀錄數量 , 0 => 不顯示 , 1 => 顯示
		),
		'sClass'	      => '', #整個div的class
		'nDataAmount'     => 0, #總計紀錄
		'nPageSize'	      => 10, #一頁幾筆紀錄
		'nTotal'	      => 0, #總頁數
		'nNowNo'		=> filter_input_int('nPageNo', INPUT_GET, 1, 1, 99999), #當前頁數
		'nBeginNo'	      => 1, #開始頁數
		'nEndNo'		=> 0, #結束頁數
		'aVar'		=> array(),
	);
	$aJumpMsg = array(
		'0'	=>	array(
			'sBoxClass'	=>	'',
			'sShow'	=>	0,	# 是否直接顯示彈窗 0=>隱藏 , 1=>顯示
			'sTitle'	=>	'Message',	# 標題
			'sIcon'	=>	'',	# 成功=>success,失敗=>error
			'sMsg'	=>	'',	# 訊息
			'sArticle'	=>	'',	# 較長文字
			'aButton'	=>	array(
				'0'	=>	array(
					'sClass'	=>	'',	# 若為取消=>cancel,點擊關閉不換頁=>JqClose,送出form=>submit
					'sUrl'		=>	'',	# 跳轉之url
					'sText'	=>	'',	# 顯示之文字
				),
			),
			'nClicktoClose'	=>	0,	# 是否點擊任意一處即可關閉 0=>否 , 1=>是
		),
	);
	$aFile = array(
		'sUrl'		=>	IMAGE['URL'].'getImage.php',
		'aFile'		=>	'',
		'sCtrl'		=>	'bmp,BMP,jpg,JPG,png,PNG,gif,GIF',
		'sDir'		=>	substr(md5('t_linegame06'),10,4).substr(md5('t_linegame06'),20,5),
		'sTable'		=>	'',
	);

	define('TEST_LINEID','Uf460ba1d5365d72176af6c01635821dd');
	define('COOKIE_DOMAIN','/Project/t_linegame06/ClientTest');

	define('aSITECOIN', array(
		'local' => 'NTD',
		'' => 'NTD',
		'abp' => 'NTD',
		'abpen' => 'RM',
		'abpcn' => 'CNY',
		'line05' => 'NTD',
		'atm' => 'NTD',
		'ATM' => 'NTD',
		'abpline01' => 'NTD',
		'abpline02' => 'NTD',
	));

	define('aSITE', array(
		'NAME' => 'line06',
		'CREDIT' => '1',
	));

	$aDefineDonate = array(
		1 => '168',
		2 => '520',
		3 => '888',
		4 => '1688',
		5 => '5000',
		6 => '9999',
		7 => '58889',
	);

	$aDefineCoinBanker = array(
		array(
			'nValue' => '10000',
			'sName0' => '1萬',
		),
		// array(
		// 	'nValue' => '15000',
		// 	'sName0' => '1萬5',
		// ),
		array(
			'nValue' => '20000',
			'sName0' => '2萬',
		),
		// array(
		// 	'nValue' => '25000',
		// 	'sName0' => '25K',
		// ),
		// array(
		// 	'nValue' => '30000',
		// 	'sName0' => '3萬',
		// ),
		// array(
		// 	'nValue' => '35000',
		// 	'sName0' => '35K',
		// ),
		// array(
		// 	'nValue' => '40000',
		// 	'sName0' => '4萬',
		// ),
		// array(
		// 	'nValue' => '45000',
		// 	'sName0' => '45K',
		// ),
		// array(
		// 	'nValue' => '50000',
		// 	'sName0' => '5萬',
		// ),
		// array(
		// 	'nValue' => '60000',
		// 	'sName0' => '60K',
		// ),
		// array(
		// 	'nValue' => '70000',
		// 	'sName0' => '70K',
		// ),
		// array(
		// 	'nValue' => '80000',
		// 	'sName0' => '80K',
		// ),
		array(
			'nValue' => '100000',
			'sName0' => '10萬',
		),
		// array(
		// 	'nValue' => '150000',
		// 	'sName0' => '15萬',
		// ),
		// array(
		// 	'nValue' => '200000',
		// 	'sName0' => '20萬',
		// ),
		// array(
		// 	'nValue' => '250000',
		// 	'sName0' => '25萬',
		// ),
		// array(
		// 	'nValue' => '300000',
		// 	'sName0' => '30萬',
		// ),
		// array(
		// 	'nValue' => '400000',
		// 	'sName0' => '40萬',
		// ),
		// array(
		// 	'nValue' => '500000',
		// 	'sName0' => '50萬',
		// ),
		array(
			'nValue' => '-1',
			'sName0' => '',
		),
	);
?>