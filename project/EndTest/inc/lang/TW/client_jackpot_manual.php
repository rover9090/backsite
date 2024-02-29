<?php
	define('aLOG',array(
		'TYPE0'		=> array(
			'sTitle'	=> '來源',
			0		=> array(
				'sText'	=> '投注累積',
				'sSelect'	=> ''
			),
			1		=> array(
				'sText'	=> '派彩',
				'sSelect'	=> ''
			),
			4		=> array(
				'sText'	=> '取消退還',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '人工增加',
				'sSelect'	=> ''
			),
			3		=> array(
				'sText'	=> '人工減少',
				'sSelect'	=> ''
			),
		),
		'NO'	=> '期數',
		'ACCOUNT'	=> '帳號',
		'BETID'	=> '單號',
		'PAGETOTALCOUNT' => '本頁總筆數',
		'PAGETOTALMONEY' => '本頁總金額',
		'TOTALCOUNT' => '總筆數',
		'TOTALMONEY' => '總金額',
		'BEFORE'	=> '原有金額',
		'DELTA'	=> '變動金額',
		'AFTER'	=> '現有金額',
	));
	define('aMANUAL', array(
		'JACKPOTLOG'	=> '彩池帳變紀錄',
		'ACCOUNT'		=> '會員帳號',
		'MONEY'		=> '金額',
		'TYPE3'		=> array(
			'sTitle'	=> '來源',
			1		=> array(
				'sText'	=> '入款',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '出款',
				'sSelect'	=> ''
			),
		),
		'STATUS'		=> array(
			'sTitle'	=> '狀態',
			0		=> array(
				'sText'	=> '未審核',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> '已審核',
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> '拒絕',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'TYPE1'		=> array(
			'sTitle'	=> '類別',
			1		=> array(
				'sText'	=> '測試',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '人工',
				'sSelect'	=> ''
			),
			// 3		=> array(
			// 	'sText'	=> '正式',
			// 	'sSelect'	=> ''
			// ),
		),
		'ADMINNAME'			=> '管理員帳號',
		'MEMO'			=> '備註',
		'TOTALINMONEY'		=> '總充值金額',
		'TOTALINCOUNT'		=> '總充值筆數',
		'TOTALOUTMONEY'		=> '總提款金額',
		'TOTALOUTCOUNT'		=> '總提款筆數',
		'PAGETOTALINMONEY'	=> '本頁充值金額',
		'PAGETOTALINCOUNT'	=> '本頁充值筆數',
		'PAGETOTALOUTMONEY'	=> '本頁提款金額',
		'PAGETOTALOUTCOUNT'	=> '本頁提款筆數',
		'MONEYTOOMUCH'		=> '會員金額不足',
		'MONEYBIGGERZERO'		=> '金額需大於0',
		'TITLEUNFILED'		=> '標題未填寫',
	));
?>