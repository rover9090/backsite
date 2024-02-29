<?php
	define('aSYSBANK', array(
		'NAME'	=> 'Tên ngân hàng', #銀行名稱
		'CODE'	=> 'Mã số ngân hàng', #銀行代碼
		'TYPE0'	=> array(
			'sTitle'	=> 'Trạng thái sử dụng', #使用狀態
			1		=> array(
				'sText'	=> 'Thông dụng', #通用
				'sSelect'	=> '',
			),
			2		=> array(
				'sText'	=> 'Nhập khoản chuyên dụng', #入款專用
				'sSelect'	=> '',
			),
		),
		# 目前 gd002有用到
		'TYPE1'	=> array(
			'sTitle'	=> '支付寶', #支付寶
			0		=> array(
				'sText'	=> '否', #否
				'sSelect'	=> '',
			),
			1		=> array(
				'sText'	=> '是', #是
				'sSelect'	=> '',
			),
		),
	));
?>