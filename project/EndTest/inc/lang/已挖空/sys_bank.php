<?php
	define('aSYSBANK', array(
		'NAME'	=> '', #銀行名稱
		'CODE'	=> '', #銀行代碼
		'TYPE0'	=> array(
			'sTitle'	=> '', #使用狀態
			1		=> array(
				'sText'	=> '', #通用
				'sSelect'	=> '',
			),
			2		=> array(
				'sText'	=> '', #入款專用
				'sSelect'	=> '',
			),
		),
		# 目前 gd002有用到
		'TYPE1'	=> array(
			'sTitle'	=> '', #支付寶
			0		=> array(
				'sText'	=> '', #否
				'sSelect'	=> '',
			),
			1		=> array(
				'sText'	=> '', #是
				'sSelect'	=> '',
			),
		),
	));
?>