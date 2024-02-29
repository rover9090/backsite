<?php
	define('aPAYMENTONLINESETTING', array(
		'NAME'		=> '金流名称',
		'ACCOUNT'		=> '商户号',
		'CODE'		=> '金流代码',
		'WITHDRAWAL'	=> '代付开关',
		'FEE'			=> '手续费',
		'FEETYPE'		=> array(
			'sTitle'	=> '手续费模式',
			1		=> array(
				'sText'	=> '固定值',
				'sCheck'	=> '',
			),
			2		=> array(
				'sText'	=> '百分比',
				'sCheck'	=> '',
			),
		),
		'sKey0'		=> '商户KEY',
		'sKey1'		=> '加密KEY',
		'sKey2'		=> '其它KEY1',
		'sKey3'		=> '其它KEY2',
		'sKey4'		=> '其它KEY3',
		'sKey5'		=> '其它KEY4',
		'sSign'		=> 'api密钥',
		'MAX'			=> '单笔上限',
		'MIN'			=> '单笔下限',
		'DAYLIMITTIMES'	=> '每日提单次数上限',
		'DAYLIMITMONEY'	=> '每日提单金额上限',
		'MEMO'		=> '备注',
	));
?>