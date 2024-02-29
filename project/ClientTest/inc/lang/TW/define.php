<?php
	define('INS', 				'新增');
	define('UPT', 				'修改');
	define('DEL', 				'刪除');
	define('SUCCESS', 			'成功');
	define('FAIL', 				'失敗');
	define('ALL', 				'全部');
	define('PLEASESELECT', 			'請選擇');
	define('PARAMSERR', 			'參數異常');
	define('EXPORTXLS',			'匯出 Excel'); # 2019-10-24 YL
	define('UNFILLED', 			'未填寫');
	define('FORMATEERR', 			'格式錯誤');
	define('NODATA', 				'查無資料');
	define('NODATAYET', 			'尚無資料');
	define('RIGHTMSG', 			'確認消息');
	define('ERRORMSG', 			'錯誤消息');
	define('MAINTENANCE', 			'網站維護中');
	define('MANYCLICK', 			'送出太多');
	define('BINDFIRST', 			'請先綁定會員');
	define('USERBANED', 			'會員已停權');
	define('SITETITLE', 			'永億娛樂');

	# 語言
	define('CHOSELANG', 			'選擇語言');
	# 分頁
	define('FIRSTPAGE', 			'第一頁');
	define('PREPAGE', 			'上一頁');
	define('NEXTPAGE', 			'下一頁');
	define('BEFORETEN', 			'前10頁');
	define('NEXTTEN', 			'下10頁');
	define('LASTPAGE', 			'最末頁');
	define('RECORD',				'紀錄'); #紀錄

	define('ACCOUNT', 			'帳號');
	define('NAME', 				'名稱');
	define('KIND', 				'分類');
	define('CREATETIME',			'建立時間');
	define('UPDATETIME',			'更新時間');
	define('STARTTIME',			'開始時間');
	define('ENDTIME',				'結束時間');
	define('OPERATE',				'操作');
	define('STATUS', 				'狀態');
	define('SUBMIT', 				'送出');
	define('CONFIRM', 			'確認');
	define('CANCEL', 				'取消');
	define('BACK', 				'返回');
	define('CLOSE', 				'關閉');
	define('SEARCH', 				'查詢');
	define('CSUBMIT', 			'確認送出');
	define('CDELETE', 			'確認刪除');
	define('CBACK', 				'取消返回');
	define('GOBACK',				'回上一頁');

	define('INSV', 				'新增成功');
	define('UPTV', 				'修改成功');
	define('DELV', 				'刪除成功');
	define('NOTICE', 				'臨時通知');

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> '上線',
			'sSelect'	=> '',
		),
		0	=> array(
			'sText'	=> '下線',
			'sSelect'	=> '',
		),
	));

	define('aMENU',	array(
		'USERRANK'		=> '升級會員',
		'USERBANK'		=> '銀行帳號',
		'USERBANKADD'	=> '新增銀行帳號',
		'USERLINK'		=> '我的團隊',
		'BROADCASR'		=> '最新消息',
		'PROMO'		=> '分享連結',
		'MISSOINS'		=> '專區',
		'MYMISSOINS'	=> '我的任務',
		'CHATROOM'		=> '線上客服',
		'RECHARGE'		=> '充值',
		'WITHDRAWAL'	=> '提現',
		'INFO'		=> '個人資料',
		'CHANGETRANSPWD'	=> '修改交易密碼',
		'CHANGEPWD'		=> '修改登入密碼',
		'FUNDSRECORD'	=> '交易紀錄',
		'SERVICE'		=> '聯絡我們',
		'DOWNLOAD'		=> '下載連結',

		'INDEX'		=> '首頁',
		'MISSION'		=> '任務',
		'SERVICE'		=> '客服',
		'CENTER'		=> '會員',

	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> '昨天',
		'TODAY'	=> '今天',
		'LASTWEEK'	=> '上週',
		'THISWEEK'	=> '本週',
		'LASTMONTH'	=> '上月',
		'THISMONTH'	=> '本月',
	));

	# 各種圖片錯誤 #
	define('aIMGERROR',array(
		'ERROR'	=> '圖片上傳失敗，請重新上傳(如重複出現此錯誤，請更換圖片)',
		'TYPE'	=> '圖片格式不符，請重新上傳',
		'SIZE'	=> '圖片大小不符，請重新上傳',
		'INISIZE'	=> '圖片大小超出ini限制，請重新上傳',
		'FORMSIZE'	=> '圖片大小超出表單限制，請重新上傳',
		'PARTIAL'	=> '圖片只有部份被上傳，請重新上傳',
		'NOFILE'	=> '圖片沒有被上傳，請重新上傳',
		'TMPDIR'	=> '圖片找不到臨時資料夾，請重新上傳',
		'CANTWRITE'	=> '圖片文件寫入失敗，請重新上傳',
		'LEASTONE'	=> '請至少上傳一張圖片',
	));

	define('aLOGNUMS', $aSystem['aLogNums']);

	define('aSITEMENU',array(
		'INDEX' => '首頁',
		'ANNOUNCE' => '最新消息',
		'PICTURE' => '活動剪影',
		'ACTIVITY' => '活動報名',
		'RESERVE' => '問事預約',
		'MALL' => '宮廟周邊',
		'ORDER' => '我的紀錄',
	));

	define('aRECORDKIND',array(
		'RESERVE' => '問事預約',
		'ACTIVITY' => '活動報名',
		'MALLLOG' => '宮廟周邊',
		'MALLLIT' => '點燈紀錄',
	));

	define('aBETCONTENT',array(
		'A' => '初A',
		'B' => '川B',
		'C' => '尾C',
	));

	define('aSTATUS',array(
		0 => '未中獎',
		1 => '中獎',
		2 => '打和',
		3 => '取消',
	));
	define('FULLAMOUNT', '全額');
?>