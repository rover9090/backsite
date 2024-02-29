<?php
	define('aSYSBANK', array(
		'NAME'	=> '银行名称',
		'CODE'	=> '银行代码',
		'NUMBER'	=> '银行编号',
		'TYPE0'	=> array(
			'sTitle'	=> '使用状态',
			1		=> array(
				'sText'	=> '通用',
				'sSelect'	=> '',
			),
			2		=> array(
				'sText'	=> '入款专用',
				'sSelect'	=> '',
			),
		),
		# 当前 gd002有用到
		'TYPE1'	=> array(
			'sTitle'	=> '支付宝',
			0		=> array(
				'sText'	=> '否',
				'sSelect'	=> '',
			),
			1		=> array(
				'sText'	=> '是',
				'sSelect'	=> '',
			),
		),
	));
?>