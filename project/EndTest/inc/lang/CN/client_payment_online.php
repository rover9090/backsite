<?php
	define('aPAYMENTONLINE', array(
		'ORDER'		=> '订单编号',
		'USER'		=> '会员名称',
		'PAYMENT'		=> '金流名称',
		'TUNNEL'		=> '选用信道',
		'MONEY'		=> '充值金额',
		'FEE'			=> '手续费',
		'ADMINT'		=> '审核员',
		'HANDCONFIRM'	=> '手动上分',
		'STATUS'		=> array(
			'sTitle'	=> '状态',
			0		=> array(
				'sText'	=> '未审核',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			1		=> array(
				'sText'	=> '回调成功',
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			99		=> array(
				'sText'	=> '拒绝',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'aMsg'		=> array(
			'MANUAL'	=> '确定要手动上分?',
			'NOORDER'	=> '查无订单',
			'NOMEMBER'	=> '查无该会员',
		),
	));
?>