<?php
	define('aWITHDRAWAL', array(
		'ACCOUNT'		=> 'Tài khoản hội viên', #會員帳號
		'MONEY'		=> 'Số tiền', #金額
		'BANKNAME'		=> 'Tên ngân hàng', #銀行名稱
		'SUBNAME'		=> 'Tên chi nhánh', #分行名稱
		'USERNAME'		=> 'Họ tên người cầm thẻ', #持卡人姓名
		'CARDNUMBER'	=> 'Số thẻ ngân hàng', #銀行卡號
		'COPY'		=> 'Sao chép', #複製
		'DETAIL'		=> 'Chi tiết', #詳細
		'CLOSE'		=> 'Tắt', #關閉
		'OUTDETAIL'		=> 'Thông tin xuất khoản', #出款資訊
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
		'FEE'			=> 'Phí thủ tục', #手續費
		'ADMINNAME'		=> 'Tài khoản người quản lý', #管理員帳號
		'ADMIN1'		=> 'Người quản lý thẩm hạch rủi ro', #風控審核管理員
		'ADMIN2'		=> 'Người quản lý thẩm hạch tài vụ', #財務審核管理員
		'MEMO'		=> 'Ghi chú', #備註
		'TOTALMONEY'	=> 'Tổng số tiền', #總金額
		'PAGETOTALMONEY'	=> 'Số tiền trang này', #本頁金額
		'TOTALCOUNT'	=> 'Tổng số lần', #總筆數
		'PAGETOTALCOUNT'	=> 'Số lần trang này', #本頁筆數
		'ACCOUNTERR'	=> 'Tài khoản ngân hàng bất thường', #銀行帳號異常
		'ACCOUNTNOTFOUND' => 'Tra không có tài khoản ngân hàng', #查無銀行帳號
	));
?>