<?php
	define('aMANUAL', array(
		'ACCOUNT'		=> '会员帐号',
		'MONEY'		=> '金额',
		'TYPE3'		=> array(
			'sTitle'	=> '来源',
			1		=> array(
				'sText'	=> '入款',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '出款',
				'sSelect'	=> ''
			),
		),
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
		'TYPE1'		=> array(
			'sTitle'	=> '类别',
			1		=> array(
				'sText'	=> '测试',
				'sSelect'	=> ''
			),
			2		=> array(
				'sText'	=> '人工',
				'sSelect'	=> ''
			),
			// 3		=> array(
			// 	'sText'	=> '正式',
			// 	'sSelect'	=> ''
			// ),
		),
		'ADMINNAME'			=> '管理员帐号',
		'MEMO'			=> '备注',
		'TOTALINMONEY'		=> '总充值金额',
		'TOTALINCOUNT'		=> '总充值笔数',
		'TOTALOUTMONEY'		=> '总提款金额',
		'TOTALOUTCOUNT'		=> '总提款笔数',
		'PAGETOTALINMONEY'	=> '本页充值金额',
		'PAGETOTALINCOUNT'	=> '本页充值笔数',
		'PAGETOTALOUTMONEY'	=> '本页提款金额',
		'PAGETOTALOUTCOUNT'	=> '本页提款笔数',
		'MONEYTOOMUCH'		=> '会员金额不足',
		'MONEYBIGGERZERO'		=> '金额需大于0',
		'TITLEUNFILED'		=> '标题未填写',
	));
?>