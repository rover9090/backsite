<?php
	define('aMANUAL', array(
		'ACCOUNT'		=> 'บัญชีสมาชิก', #會員帳號
		'MONEY'		=> 'ยอดเงิน', #金額
		'TYPE3'		=> array(
			'sTitle'	=> 'ท่ีมา', #來源
			1		=> array(
				'sText'	=> 'ฝากเงิน ', #入款
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'ถอนเงิน ', #出款
				'sSelect'	=> ''
			),
		),
		'STATUS'		=> array(
			'sTitle'	=> 'สถานะ', #狀態
			0		=> array(
				'sText'	=> 'ยังไม่ตรวจสอบ', #未審核
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> 'ตรวจสอบแล้ว', #已審核
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> 'ยกเลิก', #拒絕
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'TYPE1'		=> array(
			'sTitle'	=> 'หมวดหมู่', #類別
			1		=> array(
				'sText'	=> 'ทดลอง', #測試
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'โดยคนงาน', #人工
				'sSelect'	=> ''
			),
			// 3		=> array(
			// 	'sText'	=> '正式',
			// 	'sSelect'	=> ''
			// ),
		),
		'ADMINNAME'			=> 'บัญชีผู้ดำเนินการ', #管理員帳號
		'MEMO'			=> 'หมายเหตุ', #備註
		'TOTALINMONEY'		=> 'จำนวนเงินที่ฝากทั้งหมด', #總充值金額
		'TOTALINCOUNT'		=> 'ฝากเงินทั้งหมด', #總充值筆數
		'TOTALOUTMONEY'		=> 'ยอดถอนทั้งหมด', #總提款金額
		'TOTALOUTCOUNT'		=> 'จำนวนการถอนทั้งหมด', #總提款筆數
		'PAGETOTALINMONEY'	=> 'จำนวนยอดฝากในหน้านี้', #本頁充值金額
		'PAGETOTALINCOUNT'	=> 'จำนวนการครั้งฝากเงินในหน้านี้', #本頁充值筆數
		'PAGETOTALOUTMONEY'	=> 'จำนวนเงินที่ถอนในหน้านี้', #本頁提款金額
		'PAGETOTALOUTCOUNT'	=> 'จำนวนการถอนทั้งหมด', #本頁提款筆數
		'MONEYTOOMUCH'		=> 'ยอดเงินสมาชิกไม่เพียงพอ', #會員金額不足
		'MONEYBIGGERZERO'		=> 'จำนวนเงินต้องมากกว่า 0', #金額需大於0
		'TITLEUNFILED'		=> 'ไม่มีชื่อ', #標題未填寫
	));
?>