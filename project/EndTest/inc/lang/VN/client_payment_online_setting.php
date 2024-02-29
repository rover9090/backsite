<?php
	define('aPAYMENTONLINESETTING', array(
		'NAME'		=> 'Tên phương thức thu tiền', #金流名稱
		'ACCOUNT'		=> 'Mã số khách hàng', #商戶號
		'CODE'		=> 'Mã số phương thức thu tiền', #金流代碼
		'WITHDRAWAL'	=> '', #代付開關
		'FEE'			=> 'Phí thủ tục', #手續費
		'FEETYPE'		=> array(
			'sTitle'	=> 'Loại hình phí thủ tục', #手續費模式
			1		=> array(
				'sText'	=> 'Giá trị cố định', #固定值
				'sCheck'	=> '',
			),
			2		=> array(
				'sText'	=> 'Tỉ lệ %', #百分比
				'sCheck'	=> '',
			),
		),
		'sKey0'		=> 'KEY khách hàng', #商戶KEY
		'sKey1'		=> 'KEY thêm mật mã', #加密KEY
		'sKey2'		=> 'KEY khác 1', #其它KEY1
		'sKey3'		=> 'KEY khác 2', #其它KEY2
		'sKey4'		=> 'KEY khác 3', #其它KEY3
		'sKey5'		=> 'KEY khác 4', #其它KEY4
		'sSign'		=> '', #api密鑰
		'MAX'			=> 'Giới hạn trên mỗi lần', #單筆上限
		'MIN'			=> 'Giới hạn dưới mỗi lần', #單筆下限
		'DAYLIMITTIMES'	=> 'Giới hạn trên số lần rút trong ngày', #每日提單次數上限
		'DAYLIMITMONEY'	=> 'Giới hạn trên số tiền rút trong ngày', #每日提單金額上限
		'MEMO'		=> '', #備註
	));
?>