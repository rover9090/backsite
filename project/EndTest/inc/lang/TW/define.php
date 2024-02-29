<?php
	define('INS', 				'新增');
	define('UPT', 				'修改');
	define('DEL', 				'刪除');
	define('SUCCESS', 			'成功');
	define('FAIL', 				'失敗');
	define('PLEASESELECT', 			'請選擇');
	define('ALL', 				'全部');
	define('PARAMSERR', 			'參數異常');
	define('EXPORTXLS',			'匯出 Excel'); # 2019-10-24 YL
	define('UNFILLED', 			'未填寫');
	define('FORMATEERR', 			'格式錯誤');
	define('NODATA', 				'查無資料');
	define('RIGHTMSG',			'確認訊息');
	define('ERRORMSG',			'錯誤訊息');

	# 語言
	define('CHOSELANG', 			'選擇語言');

	# 分頁
	define('FIRSTPAGE', 			'第一頁');
	define('PREPAGE', 			'上一頁');
	define('NEXTPAGE', 			'下一頁');
	define('BEFORETEN', 			'前十頁');
	define('NEXTTEN', 			'下十頁');
	define('LASTPAGE', 			'最末頁');
	define('RECORD',				'紀錄'); #紀錄

	define('NO',	 			'編號');
	define('ACCOUNT', 			'帳號');
	define('PASSWORD', 			'密碼');
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
	define('DENY', 				'拒絕');
	define('OPEN', 				'開啟');
	define('CLOSE', 				'關閉');
	define('CANCEL', 				'取消');
	define('BACK', 				'返回');
	define('SEARCH', 				'查詢');
	define('CSUBMIT', 			'確認送出');
	define('CBACK', 				'取消返回');
	define('MONEYIN', 			'入款');
	define('MONEYOUT', 			'出款');
	define('SERVICE', 			'客服訊息');
	define('SORT', 				'排序');
	define('SORTRULE', 			'數字大，先顯示');
	define('ILLEGALVISIT', 			'非法入侵');
	define('HEADERPASSWORD', 		'修改密碼');
	define('HEADERLOGOUT', 			'登出');
	define('PICTURE',				'圖片');
	define('MONEY',				'金額');
	define('TIMEOUT',				'操作逾時');

	define('INSV', 				'新增成功');
	define('UPTV', 				'修改成功');
	define('DELV', 				'刪除成功');
	define('DENYV', 				'拒絕成功');
	define('PASSV', 				'通過成功');

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> '開啟',
			'sSelect'	=> '',
			'sClass'	=> 'FontGreen',
		),
		0	=> array(
			'sText'	=> '關閉',
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
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
		'typeErr'	=> '圖片格式不符，請重新上傳',
		'SIZE'	=> '圖片大小不符，請重新上傳',
		'INISIZE'	=> '圖片大小超出ini限制，請重新上傳',
		'FORMSIZE'	=> '圖片大小超出表單限制，請重新上傳',
		'PARTIAL'	=> '圖片只有部份被上傳，請重新上傳',
		'NOFILE'	=> '圖片沒有被上傳，請重新上傳',
		'TMPDIR'	=> '圖片找不到臨時資料夾，請重新上傳',
		'CANTWRITE'	=> '圖片文件寫入失敗，請重新上傳',
		'LEASTONE'	=> '請至少上傳一張圖片',
		'sizeErr'	=> '圖片尺寸需小於3M',
		'LIMIT'	=> '圖片尺寸需小於3M',
	));

	define('aMENULANG', array(
		# 主目錄語系 sMenuTable0		=> 對應顯示名字
		'aKIND' => array(
			'end_manager_data'	=> '管理權限',
			'client_money'		=> '金流管理',
			'end_report'		=> '報表管理',
			'client_user_data'	=> '人員管理',
			'client_chatroom'		=> '客服管理',
			'client_news'		=> '消息管理',
			'end_log'			=> '日誌管理',
			'end_developer'		=> '環境管理',
			'end_menu'			=> '目錄管理',
			'end_games'			=> '遊戲管理',
			'end_games'			=> '遊戲管理',
			'client_mall'		=> '商店管理',
		),
		# 子目錄語系 sListTable0		=> 對應顯示名字
		'aLIST' => array(
			'end_manager_data_0'				=> '管理帳號',
			'end_manager_password_0'			=> '修改密碼',
			'client_announce_0'				=> '公告管理',
			'client_announce_kind_0'			=> '公告分類',
			'client_user_data_0'				=> '人員管理',
			'client_user_promo_0'				=> '推廣代碼',
			'end_permission_0'				=> '層級權限',
			'client_broadcast_kind_0'			=> '廣告分類',
			'client_broadcast_0'				=> '廣告管理',
			'end_menu_kind_0'					=> '主目錄管理',
			'end_menu_list_0'					=> '子目錄管理',
			'end_manager_login_0'				=> '後台登入日誌',
			'end_log_0'						=> '後台操作日誌',
			'client_log_0'					=> '會員操作日誌',
			'client_chatroom_0'				=> '對話管理',
			'client_chatroom_history_0'			=> '歷史訊息',
			'client_payment_company_setting_0'		=> '公司入款設置',
			'client_payment_company_0'			=> '公司入款',
			'client_payment_online_setting_0'		=> '線上入款設置',
			'client_payment_online_0'			=> '線上入款',
			'client_withdrawal_0'				=> '出款管理',
			'sys_bank_0'					=> '銀行設置',
			'end_canned_kind_0'				=> '常用訊息分類',
			'end_canned_0'					=> '常用訊息',
			'client_manual_0'					=> '人工充提',
			'sys_param_0'					=> '環境設置(工程用)',
			'client_user_login_0'				=> '會員登入日誌',
			'end_log_account_0'				=> '帳務系統',
			'end_report_0'					=> '報表統計',
			'client_user_link_0'				=> '會員層級查詢',
			'client_user_bank_0'				=> '會員銀行帳號',
			'end_sync_0'					=> '同步管理',
			'sys_param_1'					=> '環境設置',
			'end_developer_truncate_0'			=> '清除資料',
			'client_payment_online_tunnel_0'		=> '線上入款通道設置',
			'client_payment_withdrawal_tunnel_0'	=> '代付出款通道設置',
			'end_report_lottery_0'				=> '注單管理',
			'end_games_sync_0'				=> '同步遊戲',
			'end_games_setting_0'				=> '遊戲設定',
			'end_method_setting_0'				=> '玩法設置',
			'end_games_rule_0'				=> '遊戲說明',
			'end_games_ctrl_0'				=> '遊戲控制',
			'end_games_ctrl_1'				=> '遊戲控制',
			'end_games_odds_0'				=> '賠率設置',
			'client_game_limit_0'				=> '限額管理',
			'end_nums_setting_0'		 		=> '期數控制',
			'client_donate_0'					=> '打賞管理',
			'end_lottery_report_0'				=> '重新過帳',
			'end_chatroom_0'					=> '聊天視窗',
			'end_report_donate_0'				=> '打賞列表',
			'end_report_donate_1'				=> '主播打賞列表',
			'end_games_watch_0'				=> '及時投注管理',
			'end_games_ranking_0'				=> '投注排行',
			'client_user_black_0'				=> '黑名單管理',
			'end_games_watch_1'				=> '牌路',
			'end_games_check_0'				=> '判牌',
			'end_games_stream_0'				=> '視訊',
			'end_games_table_0'				=> '遊戲桌號',
			'end_games_group_0'				=> '投注限額',
			'end_chat_filter_0'				=> '字詞過濾',
			'client_user_cookie_0'				=> '在線名單',
			'end_games_table_1'				=> '遊戲桌號',
			'end_games_group_1'				=> '投注限額',
			'client_user_robot_0'				=> '機器人管理',
			'end_exchange_rate_0'				=> '匯率管理',
			'client_mall_log_0'				=> '消費使用紀錄',
			'client_mall_0'					=> '商店列表',
			'end_devote_0'					=> '貢獻報表',
			'client_jackpot_manual_0'			=> '彩池充提',
			'client_jackpot_manual_0_log0'		=> '彩池帳變',
			'client_quickmsg_0'				=> '快捷訊息管理',
		),
	));

	define('aLOGNUMS', $aSystem['aLogNums']);

	define('aBETMETHODINMSG',array(
		'A' => '初A',
		'B' => '川B',
		'C' => '尾C',
	));
	define('aBETMETHODRESULT',array(
		'0' => '負',
		'1' => '勝',
		'2' => '和',
	));

	define('aNUMSNAME',array(
		'Z' => array(0=>'彩池未中獎',1=>'彩池中獎'),
		'A' => array(0=>'A門負',1=>'A門勝',2=>'A門和'),
		'B' => array(0=>'B門負',1=>'B門勝',2=>'B門和'),
		'C' => array(0=>'C門負',1=>'C門勝',2=>'C門和'),
	));

	define('aSITE_WHERE',array(
		'line06' => '本地',
	));

	
	define('aRESULTNAME',array(
		'BANKER' => '莊家',
		'OTHERS' => '配莊',
		'A' => 'A門',
		'B' => 'B門',
		'C' => 'C門',
	));
?>