<?php
	define('INS', 				'新增');
	define('UPT', 				'修改');
	define('DEL', 				'删除');
	define('SUCCESS', 			'成功');
	define('FAIL', 				'失败');
	define('ALL', 				'全部');
	define('PLEASESELECT', 			'请选择');
	define('PARAMSERR', 			'参数异常');
	define('EXPORTXLS',			'汇出 Excel'); # 2019-10-24 YL
	define('UNFILLED', 			'未填写');
	define('FORMATEERR', 			'格式错误');
	define('NODATA', 				'查无资料');
	define('NODATAYET', 			'尚无资料');
	define('RIGHTMSG', 			'确认消息');
	define('ERRORMSG', 			'错误消息');
	define('MAINTENANCE', 			'网站维护中');
	define('MANYCLICK', 			'送出太多');

	# 语言
	define('CHOSELANG', 			'选择语言');
	# 分页
	define('FIRSTPAGE', 			'第一页');
	define('PREPAGE', 			'上一页');
	define('NEXTPAGE', 			'下一页');
	define('BEFORETEN', 			'前10页');
	define('NEXTTEN', 			'下10页');
	define('LASTPAGE', 			'最末页');
	define('RECORD',				'纪录'); #纪录

	define('ACCOUNT', 			'帐号');
	define('NAME', 				'名称');
	define('KIND', 				'分类');
	define('CREATETIME',			'建立时间');
	define('UPDATETIME',			'更新时间');
	define('STARTTIME',			'开始时间');
	define('ENDTIME',				'结束时间');
	define('OPERATE',				'操作');
	define('STATUS', 				'状态');
	define('SUBMIT', 				'送出');
	define('CONFIRM', 			'确认');
	define('CANCEL', 				'取消');
	define('BACK', 				'返回');
	define('CLOSE', 				'关闭');
	define('SEARCH', 				'查询');
	define('CSUBMIT', 			'确认送出');
	define('CDELETE', 			'确认删除');
	define('CBACK', 				'取消返回');
	define('GOBACK',				'回上一页');

	define('INSV', 				'新增成功');
	define('UPTV', 				'修改成功');
	define('DELV', 				'删除成功');
	define('NOTICE', 				'临时通知');

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> '上线',
			'sSelect'	=> '',
		),
		0	=> array(
			'sText'	=> '下线',
			'sSelect'	=> '',
		),
	));

	define('aMENU',	array(
		'USERRANK'		=> '升级会员',
		'USERBANK'		=> '银行帐号',
		'USERBANKADD'	=> '新增银行帐号',
		'USERLINK'		=> '我的团队',
		'BROADCASR'		=> '最新消息',
		'PROMO'		=> '分享连结',
		'MISSOINS'		=> '专区',
		'MYMISSOINS'	=> '我的任务',
		'CHATROOM'		=> '线上客服',
		'RECHARGE'		=> '充值',
		'WITHDRAWAL'	=> '提现',
		'INFO'		=> '个人资料',
		'CHANGETRANSPWD'	=> '修改交易密码',
		'CHANGEPWD'		=> '修改登入密码',
		'FUNDSRECORD'	=> '交易纪录',
		'SERVICE'		=> '联络我们',
		'DOWNLOAD'		=> '下载连结',

		'INDEX'		=> '首页',
		'MISSION'		=> '任务',
		'SERVICE'		=> '客服',
		'CENTER'		=> '会员',

	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> '昨天',
		'TODAY'	=> '今天',
		'LASTWEEK'	=> '上週',
		'THISWEEK'	=> '本週',
		'LASTMONTH'	=> '上月',
		'THISMONTH'	=> '本月',
	));

	# 各种图片错误 #
	define('aIMGERROR',array(
		'ERROR'	=> '图片上传失败，请重新上传(如重複出现此错误，请更换图片)',
		'TYPE'	=> '图片格式不符，请重新上传',
		'SIZE'	=> '图片大小不符，请重新上传',
		'INISIZE'	=> '图片大小超出ini限制，请重新上传',
		'FORMSIZE'	=> '图片大小超出表单限制，请重新上传',
		'PARTIAL'	=> '图片只有部份被上传，请重新上传',
		'NOFILE'	=> '图片没有被上传，请重新上传',
		'TMPDIR'	=> '图片找不到临时资料夹，请重新上传',
		'CANTWRITE'	=> '图片文件写入失败，请重新上传',
		'LEASTONE'	=> '请至少上传一张图片',
	));

	define('aLOGNUMS', $aSystem['aLogNums']);

	define('aSITEMENU',array(
		'INDEX' => '首页',
		'ANNOUNCE' => '最新消息',
		'PICTURE' => '活动剪影',
		'ACTIVITY' => '活动报名',
		'RESERVE' => '问事预约',
		'MALL' => '宫庙周边',
		'ORDER' => '我的纪录',
	));

	define('aRECORDKIND',array(
		'RESERVE' => '问事预约',
		'ACTIVITY' => '活动报名',
		'MALLLOG' => '宫庙周边',
		'MALLLIT' => '点灯纪录',
	));

	define('aBETCONTENT',array(
		'BW' => '庄赢',
		'PW' => '閒赢',
		'DRAW' => '打和',
		'BP' => '庄对',
		'PP' => '閒对',
		'SIX' => '超级六',
	));
?>