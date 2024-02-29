<?php
	define('aWITHDRAWAL', array(
		'ACCOUNT'		=> '会员帐号',
		'MONEY'		=> '金额',
		'BANKNAME'		=> '银行名称',
		'SUBNAME'		=> '分行名称',
		'USERNAME'		=> '持卡人姓名',
		'CARDNUMBER'	=> '银行卡号',
		'COPY'		=> '复制',
		'DETAIL'		=> '详细',
		'CLOSE'		=> '关闭',
		'OUTDETAIL'		=> '出款信息',
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
		'FEE'			=> '手续费',
		'ADMINNAME'		=> '管理员帐号',
		'ADMIN1'		=> '风控审核管理员',
		'ADMIN2'		=> '财务审核管理员',
		'MEMO'		=> '备注',
		'TOTALMONEY'	=> '总金额',
		'PAGETOTALMONEY'	=> '本页金额',
		'TOTALCOUNT'	=> '总笔数',
		'PAGETOTALCOUNT'	=> '本页笔数',
		'ACCOUNTERR'	=> '银行帐号异常',
		'ACCOUNTNOTFOUND' => '查无银行帐号',
	));
?>