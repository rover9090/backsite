<?php
	define('aPAYMENTCOMPANY', array(
		'ACCOUNT'		=> '会员帐号',
		'MONEY'		=> '金额',
		'STATUS'		=> array(
			'sTitle'	=> '状态',
			0		=> array(
				'sText'	=> '未审核',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> '已审核',
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> '拒绝',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'PAYTYPE'		=> '支付方式',
		'TYPE1'		=> array(
			'sTitle'	=> '来源',
			1		=> array(
				'sText'	=> '测试',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '人工',
				'sSelect'	=> ''
			),
			3		=> array(
				'sText'	=> '正式',
				'sSelect'	=> ''
			),
		),
		'FEE'			=> '手续费',
		'ADMINNAME'		=> '管理员帐号',
		'MEMO'		=> '备注',
		'BANKNAME'		=> '银行帐号',
		'CHECK'		=> '查看',
		'DETAIL'		=> '入款信息',
		'TOTALMONEY'	=> '总金额',
		'PAGETOTALMONEY'	=> '本页金额',
		'TOTALCOUNT'	=> '总笔数',
		'PAGETOTALCOUNT'	=> '本页笔数',
	));
?>