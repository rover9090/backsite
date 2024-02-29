<?php
	$INPUT_REQUEST = !empty(INPUT_GET)?INPUT_GET:INPUT_POST;
	define('INPUT_REQUEST', $INPUT_REQUEST); // #Unload.php 使用
	define('SYSLOAD', true); // #Unload.php 使用
	define('aCTRL',	array(
		'FIND'		=> 6,
		'GET'			=> 15,
	));

	// define('SYS',	array(
	// 	'DEFAULTPAGE'	=> 'RLIx201', #預設頁面 (menu)
	// 	'KEY'			=> substr(md5('T20211224LIMEGAME05'),aCTRL['FIND'],aCTRL['GET']), #站內頁面切換密鑰
	// 	'PWDKEY'		=> substr(md5('2021TLIMEGAME051224'),aCTRL['FIND'],aCTRL['GET']), #密碼加密密鑰
	// ));
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
	define('aLINEROBOT', array(
		'REPLYLOCALURL'		=> 'https://demo801.dtap000s3.com/Project/t_linegame06/EndTest/Bot/reply.php',
		'SCHEDULELOCALURL'	=> 'https://demo801.dtap000s3.com/Project/line_base/EndTest/Bot/schedule.php',
		'CHANNELID'			=> '1656844936',
		'CHANNELSECRET'		=> '87088a55b5910e73be6b7e1742df7492',
		'CHANNELTOKEN'		=> 'KHiT2vaVksW2oT2xwpDGXhARmhnma20kk6BRgaDB/hFYWHV26zF37p1NU87W2MC5uSiUjuOV/wnXGvExAq8u7HeRFvOBQxvyfjauepWu/xE02iXuDAMGLJLxslH9p59rP9D/0o45bKOtPTJrxcgMLwdB04t89/1O/w1cDnyilFU=',
		'REPLYURL'	 		=> 'https://api.line.me/v2/bot/message/reply',
		'PUSHURL'	 		=> 'https://api.line.me/v2/bot/message/push',
		'MULTIURL'	 		=> 'https://api.line.me/v2/bot/message/multicast',
		'BORADURL'	 		=> 'https://api.line.me/v2/bot/message/broadcast',
		'ADDBOT'			=> 'https://line.me/R/ti/p/%40026qybwd',#@026qybwd
		'ONLYUSER'			=> '',
	));

	define('aLINELOGIN', array(
		'LIFFID'		=> '1656844931',
		'LIFFURL'		=> array(
			'0'	=> 'https://liff.line.me/1656844931-RdAGGDy9',
			'1'	=> 'https://liff.line.me/1656844931-nqJqqy8g',
		),
	));

	define('COOKIE',	array(
		'CLOSE'	=> NOWTIME - 3600 * 6,
		'REMEMBER'	=> NOWTIME + 3600 * 24,
	));

	#2020-03-10 YL,BQ js要冠上時間 (先改V再改時間)
 	define('VTIME', time());
 	define('aSITE', array(
		'NAME' => 'line06',
		'CREDIT' => '1',
	));

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
			'nPrevNext10ShowStyle'	=> 0, #按鈕顯示方式(上十頁,下十頁) , 0 => 文字 , 1 => icon
			'nPrevNextShowStyle'	=> 0, #按鈕顯示方式(上一頁,下一頁) , 0 => 文字 , 1 => icon
			'nHeadTail'		=> 1, #第一頁&最末頁 , 0 => 不顯示 , 1 => 顯示
			'nPrevNext'		=> 1, #上一頁&下一頁 , 0 => 不顯示 , 1 => 顯示
			'nPrevNext10'	=> 1, #上十頁&下十頁 , 0 => 不顯示 , 1 => 顯示
			'nRecordAmount'	=> 0, #紀錄數量 , 0 => 不顯示 , 1 => 顯示
		),
		'sClass'	      => '', #整個div的class
		'nDataAmount'     => 0, #總計紀錄
		'nPageSize'	      => 20, #一頁幾筆紀錄
		'nTotal'	      => 0, #總頁數
		'nNowNo'		=> filter_input_int('nPageNo', INPUT_GET, 1, 1, 99999), #當前頁數
		'nBeginNo'	      => 1, #開始頁數
		'nEndNo'		=> 0, #結束頁數
		'aVar'		=> array()
	);

	$aJumpMsg = array(
		'0'	=>	array(
			'sBoxClass'	=>	'',
			'sShow'	=>	0,	# 是否直接顯示彈窗 0=>隱藏 , 1=>顯示
			'sTitle'	=>	'Messenge',	# 標題
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
		)
	);
	$aFile = array(
		'sUrl'		=>	IMAGE['URL'].'getImage.php',
		'aFile'		=>	'',
		'sCtrl'		=>	'bmp,BMP,jpg,JPG,png,PNG,gif,GIF',
		'sDir'		=>	substr(md5('t_linegame06'),10,4) . substr(md5('t_linegame06'),20,5),
		'sTable'		=>	'',
	);
?>