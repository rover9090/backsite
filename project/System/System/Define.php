<?php
	define('NOWTIME',			time());
	define('NOWCLOCK',		date('H:i:s',NOWTIME));
	define('NOWDATE',			date('Y-m-d H:i:s',NOWTIME));
	define('USERIP',			getUserIp());
	define('MSGCOUNT',		20);
	define('SOCKETIP',		'213.139.235.77');
	define('SOCKETPORT',		'8090');

	define('aLANG', array(
		'TW'		=>	'繁體中文',
		'CN'		=>	'简体中文',
		// 'EN'		=>	'English',
		// 'VN'		=>	'Tiếng việt',
	));

	define('aCURRENCY', array(
		'TW'		=>	array(
			'sSymbol'	=>	'$',
			'sCode'	=>	'',
			'sUnit'	=>	'',
		),
		'CN'		=>	array(
			'sSymbol'	=>	'$', #¥
			'sCode'	=>	'',
			'sUnit'	=>	'',
		),
		'EN'		=>	array(
			'sSymbol'	=>	'$',
			'sCode'	=>	'',
			'sUnit'	=>	'',
		),
		'VN'		=>	array(
			'sSymbol'	=>	'$',
			'sCode'	=>	'',
			'sUnit'	=>	'/K', #K
		),
		'TH'		=>	array(
			'sSymbol'	=>	'$',
			'sCode'	=>	'',
			'sUnit'	=>	'',
		),
	));
	
	define('COMPANY', array(
		'URL'	=> 'http://demo801.dtap000s3.com/Project/cpy/',
	));

	define('PAY', array(
		'URL'			=> 'http://payv2.ntt1199.com/API/index.php',
		'PAYOUT_URL'	=> 'http://payv2.ntt1199.com/API/payout/index.php',
	));

	define('WEBSITE', array(
		'ADMURL'	=> 'http://demo801.dtap000s3.com/Project/t_linegame06/EndTest/',
		'WEBURL'	=> 'http://demo801.dtap000s3.com/Project/t_linegame06/ClientTest/',
	));

	define('QRCODE', array(
		'URL'	=> 'http://demo801.dtap000s3.com/Project/t_linegame06/System/Plugins/QRcode/',
	));

	define('IMAGE', array(
		'URL'	=> 'https://gameimg.mitowngame.com/',
		// 'URL'	=> 'https://img2.monopoly168.com/',
	));

	define('GOOGLE', array(
		'URL'	=> 'http://goline.ness9999.com/End/',
	));

	$sSTime = 'Y-m-d 00:00:00';
	$sETime = 'Y-m-d 23:59:59';
	define('aDAY', array(
		#昨日
		'YESTERDAY'	=> array(
			'sStartDay'		=> date($sSTime, strtotime('-1 days')),
			'sEndDay'		=> date($sETime, strtotime('-1 days')),
		),
		#今日
		'TODAY'	=> array(
			'sStartDay'		=> date($sSTime, NOWTIME),
			'sEndDay'		=> date($sETime, NOWTIME),
		),
		#上週
		'LASTWEEK'	=> array(
			'sStartDay'		=> date($sSTime, strtotime('-2 Sunday')),
			'sEndDay'		=> date($sETime, strtotime('Saturday last week')),
		),
		#本週
		'THISWEEK'	=> array(
			'sStartDay'		=> date($sSTime, strtotime('Sunday last week')),
			'sEndDay'		=> date($sETime, strtotime('Saturday this week')),
		),
		#上月
		'LASTMONTH'	=> array(
			'sStartDay'		=> date($sSTime, strtotime('first day of last month')),
			'sEndDay'		=> date($sETime, strtotime('last day of last month')),
		),
		#本月
		'THISMONTH'	=> array(
			'sStartDay'		=> date($sSTime, strtotime('first day of this month')),
			'sEndDay'		=> date($sETime, strtotime('last day of this month')),
		),
	)); 

	define('aCARDSIGN',array(
		'七1',# 0
		'七2',# 1
		'九1',# 2
		'九2',# 3
		'人',# 4
		'八1',# 5
		'八2',# 6
		'三',# 7
		'五1',#8
		'五2',# 9
		'六',# 10
		'天',# 11
		'地',# 12
		'斧頭',# 13
		'板凳',# 14
		'長三',# 15
		'紅頭十',# 016
		'高腳七',# 17
		'梅',# 18
		'銅錘六',# 19
		'鵝',# 20
	));
?>