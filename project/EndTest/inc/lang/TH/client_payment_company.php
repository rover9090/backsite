<?php
	define('aPAYMENTCOMPANY', array(
		'ACCOUNT'		=> 'บัญชีสมาชิก', #會員帳號
		'MONEY'		=> 'ยอดเงิน', #金額
		'STATUS'		=> array(
			'sTitle'	=> 'สถานะ', #狀態
			0		=> array(
				'sText'	=> 'รอการตรวจสอบ', #未審核
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> 'ตรวจสอบสำเร็จ', #已審核
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> 'ยกเลิก', #拒絕
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'PAYTYPE'		=> 'วิธีการชำระเงิน', #支付方式
		'TYPE1'		=> array(
			'sTitle'	=> 'ที่มา', #來源
			1		=> array(
				'sText'	=> 'ทดลอง', #測試
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'โดยใช้คน', #人工
				'sSelect'	=> ''
			),
			3		=> array(
				'sText'	=> 'เป็นทางการ', #正式
				'sSelect'	=> ''
			),
		),
		'FEE'			=> 'ค่าธรรมเนียม', #手續費
		'ADMINNAME'		=> 'บัญชีผู้ดูแลระบบ', #管理員帳號
		'MEMO'		=> 'หมายเหตุ', #備註
		'BANKNAME'		=> 'เลขบัญชีธนาคาร', #銀行帳號
		'CHECK'		=> 'ค้นหา', #查看
		'DETAIL'		=> 'ข้อมูลการฝาก', #入款資訊
		'TOTALMONEY'	=> 'ยอดเงินรวม', #總金額
		'PAGETOTALMONEY'	=> 'ยอดเงินหน้านี้', #本頁金額
		'TOTALCOUNT'	=> 'จำนวนยอด', #總筆數
		'PAGETOTALCOUNT'	=> 'จำนวนยอดหน้านี้', #本頁筆數
	));
?>