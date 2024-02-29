<?php
	define('INS', 				'添加');
	define('UPT', 				'修改');
	define('DEL', 				'删除');
	define('SUCCESS', 			'成功');
	define('FAIL', 				'失败');
	define('PLEASESELECT', 			'请选择');
	define('ALL', 				'全部');
	define('PARAMSERR', 			'参数异常');
	define('EXPORTXLS',			'汇出 Excel'); # 2019-10-24 YL
	define('UNFILLED', 			'未填写');
	define('FORMATEERR', 			'格式错误');
	define('NODATA', 				'查无数据');
	define('RIGHTMSG',			'确认消息');
	define('ERRORMSG',			'错误消息');

	# 语言
	define('CHOSELANG', 			'选择语言');

	# 分页
	define('FIRSTPAGE', 			'第一页');
	define('PREPAGE', 			'上一页');
	define('NEXTPAGE', 			'下一页');
	define('BEFORETEN', 			'前十页');
	define('NEXTTEN', 			'下十页');
	define('LASTPAGE', 			'最末页');

	define('NO',	 			'编号');
	define('ACCOUNT', 			'帐号');
	define('NAME', 				'名称');
	define('KIND', 				'分类');
	define('CREATETIME',			'创建时间');
	define('UPDATETIME',			'更新时间');
	define('STARTTIME',			'开始时间');
	define('ENDTIME',				'结束时间');
	define('OPERATE',				'操作');
	define('STATUS', 				'状态');
	define('SUBMIT', 				'送出');
	define('CONFIRM', 			'确认');
	define('DENY', 				'拒绝');
	define('OPEN', 				'开启');
	define('CLOSE', 				'关闭');
	define('CANCEL', 				'取消');
	define('BACK', 				'返回');
	define('SEARCH', 				'查找');
	define('CSUBMIT', 			'确认送出');
	define('CBACK', 				'取消返回');
	define('MONEYIN', 			'入款');
	define('MONEYOUT', 			'出款');
	define('SERVICE', 			'客服消息');
	define('SORT', 				'排序');
	define('SORTRULE', 			'数字大，先显示');
	define('ILLEGALVISIT', 			'非法入侵');
	define('HEADERPASSWORD', 		'修改密码');
	define('HEADERLOGOUT', 			'登出');

	define('INSV', 				'添加成功');
	define('UPTV', 				'修改成功');
	define('DELV', 				'删除成功');
	define('DENYV', 				'拒绝成功');
	define('PASSV', 				'通过成功');

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> '上线',
			'sSelect'	=> '',
			'sClass'	=> 'FontGreen',
		),
		0	=> array(
			'sText'	=> '下线',
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> '昨天',
		'TODAY'	=> '今天',
		'LASTWEEK'	=> '上周',
		'THISWEEK'	=> '本周',
		'LASTMONTH'	=> '上月',
		'THISMONTH'	=> '本月',
	));

	# 各种图片错误 #
	define('aIMGERROR',array(
		'ERROR'	=> '图片上传失败，请重新上传(如重复出现此错误，请更换图片)',
		'TYPE'	=> '图片格式不符，请重新上传',
		'SIZE'	=> '图片大小不符，请重新上传',
		'INISIZE'	=> '图片大小超出ini限制，请重新上传',
		'FORMSIZE'	=> '图片大小超出表单限制，请重新上传',
		'PARTIAL'	=> '图片只有部份被上传，请重新上传',
		'NOFILE'	=> '图片没有被上传，请重新上传',
		'TMPDIR'	=> '图片找不到临时文件夹，请重新上传',
		'CANTWRITE'	=> '图片文档写入失败，请重新上传',
		'LEASTONE'	=> '请至少上传一张图片',
		'LIMIT'	=> '图片尺寸需小于3M',
	));

	define('aMENULANG', array(
		# 主目录语系 sMenuTable0		=> 对应显示名字
		'aKIND' => array(
			'end_manager_data'	=> '管理权限',
			'client_money'		=> '金流管理',
			'end_report'		=> '报表管理',
			'client_user_data'	=> '会员管理',
			'client_chatroom'		=> '客服管理',
			'client_news'		=> '消息管理',
			'end_log'			=> '日志管理',
			'end_developer'		=> '环境管理',
			'end_menu'			=> '目录管理',
		),
		# 子目录语系 sListTable0		=> 对应显示名字
		'aLIST' => array(
			'end_manager_data_0'				=> '管理帐号',
			'end_manager_password_0'			=> '修改密码',
			'client_announce_0'				=> '公告管理',
			'client_announce_kind_0'			=> '公告分类',
			'client_user_data_0'				=> '会员管理',
			'end_permission_0'				=> '层级权限',
			'client_broadcast_kind_0'			=> '广告分类',
			'client_broadcast_0'				=> '广告管理',
			'end_menu_kind_0'					=> '主目录管理',
			'end_menu_list_0'					=> '子目录管理',
			'end_manager_login_0'				=> '後台登录日志',
			'end_log_0'						=> '後台操作日志',
			'client_log_0'					=> '会员操作日志',
			'client_chatroom_0'				=> '对话管理',
			'client_chatroom_history_0'			=> '历史消息',
			'client_payment_company_setting_0'		=> '公司入款设置',
			'client_payment_company_0'			=> '公司入款',
			'client_payment_online_setting_0'		=> '线上入款设置',
			'client_payment_online_0'			=> '线上入款',
			'client_withdrawal_0'				=> '出款管理',
			'sys_bank_0'					=> '银行设置',
			'end_canned_kind_0'				=> '常用消息分类',
			'end_canned_0'					=> '常用消息',
			'client_manual_0'					=> '人工充提',
			'sys_param_0'					=> '环境设置(工程用)',
			'client_user_login_0'				=> '会员登录日志',
			'end_log_account_0'				=> '帐务系统',
			'end_report_0'					=> '报表统计',
			'client_user_link_0'				=> '会员层级查找',
			'client_user_bank_0'				=> '会员银行帐号',
			'end_sync_0'					=> '同步管理',
			'sys_param_1'					=> '环境设置',
			'end_developer_truncate_0'			=> '清除数据',
			'client_payment_online_tunnel_0'		=> '线上入款信道设置',
			'client_payment_withdrawal_tunnel_0'	=> '代付出款信道设置',
		),
	));

	define('aLOGNUMS', $aSystem['aLogNums']);

?>