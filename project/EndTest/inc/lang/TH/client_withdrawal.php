<?php
	define('aWITHDRAWAL', array(
		'ACCOUNT'		=> 'บัญชีสมาชิก', #會員帳號
		'MONEY'		=> 'ยอดเงิน', #金額
		'BANKNAME'		=> 'ชื่อธนาคาร', #銀行名稱
		'SUBNAME'		=> 'สาขาธนาคาร', #分行名稱
		'USERNAME'		=> 'ชื่อผู้ใช้งานบัตร', #持卡人姓名
		'CARDNUMBER'	=> 'เลขบัตร', #銀行卡號
		'COPY'		=> 'คัดลอด', #複製
		'DETAIL'		=> 'รายละเอียด', #詳細
		'CLOSE'		=> 'ปิด', #關閉
		'OUTDETAIL'		=> 'ข้อมูลการถอนเงิน', #出款資訊
		'STATUS'		=> array(
			'sTitle'	=> 'สถานะ', #狀態
			0		=> array(
				'sText'	=> 'ยังไม่ตรวจสอบ', #未審核
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> 'ตรวจสอบเเล้ว', #已審核
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> 'ยกเลิก', #拒絕
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'PAYTYPE'		=> 'รูปแบบการชำระเงิน', #支付方式
		'FEE'			=> 'คั่าธรรมเนียม', #手續費
		'ADMINNAME'		=> 'บัญชีผู้ดูแล', #管理員帳號
		'ADMIN1'		=> 'ผู้ดูแลระบบตรวจสอบควบคุมความเสี่ยง', #風控審核管理員
		'ADMIN2'		=> 'ตรวจสอบการเงิน', #財務審核管理員
		'MEMO'		=> 'หมายเหตุ', #備註
		'TOTALMONEY'	=> 'ยอดเงินรวม', #總金額
		'PAGETOTALMONEY'	=> 'ยอดเงินหน้านี้', #本頁金額
		'TOTALCOUNT'	=> 'จำนวนยอด', #總筆數
		'PAGETOTALCOUNT'	=> 'จำนวนยอดหน้านี้', #本頁筆數
		'ACCOUNTERR'	=> 'บัญชีธนาคารผิดพลาด', #銀行帳號異常
		'ACCOUNTNOTFOUND' => 'ตรวจสอบว่าไม่มีบัญชีธนาคาร', #查無銀行帳號
	));
?>