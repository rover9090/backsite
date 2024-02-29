<?php
	define('aMANUAL', array(
		'ACCOUNT'		=> 'Tài khoản hội viên', #會員帳號
		'MONEY'		=> 'Số tiền', #金額
		'TYPE3'		=> array(
			'sTitle'	=> 'Nguồn gốc', #來源
			1		=> array(
				'sText'	=> 'Nhập khoản', #入款
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'Xuất khoản', #出款
				'sSelect'	=> ''
			),
		),
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
		'TYPE1'		=> array(
			'sTitle'	=> 'Loại', #類別
			1		=> array(
				'sText'	=> 'Kiểm tra', #測試
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> 'Nhân công', #人工
				'sSelect'	=> ''
			),
			// 3		=> array(
			// 	'sText'	=> '正式',
			// 	'sSelect'	=> ''
			// ),
		),
		'ADMINNAME'			=> 'Tài khoản người quản lý', #管理員帳號
		'MEMO'			=> 'Ghi chú', #備註
		'TOTALINMONEY'		=> 'Tổng số tiền nạp', #總充值金額
		'TOTALINCOUNT'		=> 'Tổng số lần nạp', #總充值筆數
		'TOTALOUTMONEY'		=> 'Tổng số tiền rút', #總提款金額
		'TOTALOUTCOUNT'		=> 'Tổng số lần rút', #總提款筆數
		'PAGETOTALINMONEY'	=> 'Số tiền nạp trang này', #本頁充值金額
		'PAGETOTALINCOUNT'	=> 'Số lần nạp trang n', #本頁充值筆數
		'PAGETOTALOUTMONEY'	=> 'Số tiền rút trang này', #本頁提款金額
		'PAGETOTALOUTCOUNT'	=> 'Số lần rút trang này', #本頁提款筆數
		'MONEYTOOMUCH'		=> 'Số tiền hội viên không đủ', #會員金額不足
		'MONEYBIGGERZERO'		=> 'Số tiền cần lớn hơn 0', #金額需大於0
		'TITLEUNFILED'		=> 'Tiêu đề chưa viết', #標題未填寫
	));
?>