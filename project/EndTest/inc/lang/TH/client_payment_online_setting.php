<?php
	define('aPAYMENTONLINESETTING', array(
		'NAME'		=> 'ชื่อระบบการเงิน', #金流名稱
		'ACCOUNT'		=> 'ชื่อร้านค้า', #商戶號
		'CODE'		=> 'รหัสการเงิน', #金流代碼
		'WITHDRAWAL'	=> '', #代付開關
		'FEE'			=> 'ค่าธรรมเนียม', #手續費
		'FEETYPE'		=> array(
			'sTitle'	=> 'รูปแบบค่าธรรมเนียม', #手續費模式
			1		=> array(
				'sText'	=> 'หลัก', #固定值
				'sCheck'	=> '',
			),
			2		=> array(
				'sText'	=> 'ร้อยละ', #百分比
				'sCheck'	=> '',
			),
		),
		'sKey0'		=> 'KEY ร้าน', #商戶KEY
		'sKey1'		=> 'รหัสผ่านKEY', #加密KEY
		'sKey2'		=> 'KEY1อื่น ๆ', #其它KEY1
		'sKey3'		=> 'KEY2อื่น ๆ', #其它KEY2
		'sKey4'		=> 'KEY3อื่น ๆ', #其它KEY3
		'sKey5'		=> 'KEY4อื่น ๆ', #其它KEY4
		'sSign'		=> '', #api密鑰
		'MAX'			=> 'ยอดสูงสุด', #單筆上限
		'MIN'			=> 'ยอดตำ่สุด', #單筆下限
		'DAYLIMITTIMES'	=> 'จำนวนครั้งต่อวัน', #每日提單次數上限
		'DAYLIMITMONEY'	=> 'จำนวนยอดเงินต่อวัน', #每日提單金額上限
		'MEMO'		=> '', #備註
	));
?>