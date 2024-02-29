<?php
	define('aPAYMENTCOMPANY', array(
		'ACCOUNT'		=> 'Tài khoản hội viên', #會員帳號
		'MONEY'		=> 'Số tiền', #金額
		'STATUS'		=> array(
			'sTitle'	=> 'Trạng thái', #狀態
			0		=> array(
				'sText'	=> 'Chưa thẩm hạch', #未審核
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> 'Đã thẩm hạch', #已審核
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> 'Từ chối', #拒絕
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'PAYTYPE'		=> 'Phương thức chi trả', #支付方式
		'TYPE1'		=> array(
			'sTitle'	=> 'Nguồn gốc', #來源
			1		=> array(
				'sText'	=> 'Kiểm tra', #測試
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'Nhân công', #人工
				'sSelect'	=> ''
			),
			3		=> array(
				'sText'	=> 'Chính thức', #正式
				'sSelect'	=> ''
			),
		),
		'FEE'			=> 'Phí thủ tục', #手續費
		'ADMINNAME'		=> 'Tài khoản người quản lý', #管理員帳號
		'MEMO'		=> 'Ghi chú', #備註
		'BANKNAME'		=> 'Tài khoản ngân hàng', #銀行帳號
		'CHECK'		=> 'Kiểm tra', #查看
		'DETAIL'		=> 'Thông tin nhập khoản', #入款資訊
		'TOTALMONEY'	=> 'Tổng số tiền', #總金額
		'PAGETOTALMONEY'	=> 'Số tiền trang này', #本頁金額
		'TOTALCOUNT'	=> 'Tổng số lần', #總筆數
		'PAGETOTALCOUNT'	=> 'Số món trang này', #本頁筆數
	));
?>