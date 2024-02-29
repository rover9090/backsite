<?php
	define('INS', 				''); #新增
	define('UPT', 				''); #修改
	define('DEL', 				''); #刪除
	define('SUCCESS', 			''); #成功
	define('FAIL', 				''); #失敗
	define('PLEASESELECT', 			''); #請選擇
	define('ALL', 				''); #全部
	define('PARAMSERR', 			''); #參數異常
	define('EXPORTXLS',			''); #匯出 Excel # 2019-10-24 YL
	define('UNFILLED', 			''); #未填寫
	define('FORMATEERR', 			''); #格式錯誤
	define('NODATA', 				''); #查無資料
	# 語言
	define('CHOSELANG', 			''); #選擇語言
	# 分頁
	define('FIRSTPAGE', 			''); #第一頁
	define('PREPAGE', 			''); #上一頁
	define('NEXTPAGE', 			''); #下一頁
	define('BEFORETEN', 			''); #前10頁
	define('NEXTTEN', 			''); #下10頁
	define('LASTPAGE', 			''); #最末頁

	define('ACCOUNT', 			''); #帳號
	define('NAME', 				''); #名稱
	define('KIND', 				''); #分類
	define('CREATETIME',			''); #建立時間
	define('UPDATETIME',			''); #更新時間
	define('STARTTIME',			''); #開始時間
	define('ENDTIME',				''); #結束時間
	define('OPERATE',				''); #操作
	define('STATUS', 				''); #狀態
	define('SUBMIT', 				''); #送出
	define('CONFIRM', 			''); #確認
	define('DENY', 				''); #拒絕
	define('OPEN', 				''); #開啟
	define('CLOSE', 				''); #關閉
	define('CANCEL', 				''); #取消
	define('BACK', 				''); #返回
	define('SEARCH', 				''); #查詢
	define('CSUBMIT', 			''); #確認送出
	define('CBACK', 				''); #取消返回
	define('MONEYIN', 			''); #入款
	define('MONEYOUT', 			''); #出款
	define('SERVICE', 			''); #客服訊息
	define('SORT', 				''); #排序
	define('SORTRULE', 			''); #數字大，先顯示
	define('ILLEGALVISIT', 			''); #非法入侵
	define('HEADERPASSWORD', 		''); #修改密碼
	define('HEADERLOGOUT', 			''); #登出

	define('INSV', 				''); #新增成功
	define('UPTV', 				''); #修改成功
	define('DELV', 				''); #刪除成功
	define('DENYV', 				''); #拒絕成功
	define('PASSV', 				''); #通過成功

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> '', #上線
			'sSelect'	=> '',
			'sClass'	=> '',
		),
		0	=> array(
			'sText'	=> '', #下線
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> '', #昨天
		'TODAY'	=> '', #今天
		'LASTWEEK'	=> '', #上週
		'THISWEEK'	=> '', #本週
		'LASTMONTH'	=> '', #上月
		'THISMONTH'	=> '', #本月
	));

	# 各種圖片錯誤 #
	define('aIMGERROR',array(
		'ERROR'	=> '', #圖片上傳失敗，請重新上傳(如重複出現此錯誤，請更換圖片)
		'TYPE'	=> '', #圖片格式不符，請重新上傳
		'SIZE'	=> '', #圖片大小不符，請重新上傳
		'INISIZE'	=> '', #圖片大小超出ini限制，請重新上傳
		'FORMSIZE'	=> '', #圖片大小超出表單限制，請重新上傳
		'PARTIAL'	=> '', #圖片只有部份被上傳，請重新上傳
		'NOFILE'	=> '', #圖片沒有被上傳，請重新上傳
		'TMPDIR'	=> '', #圖片找不到臨時資料夾，請重新上傳
		'CANTWRITE'	=> '', #圖片文件寫入失敗，請重新上傳
		'LEASTONE'	=> '', #請至少上傳一張圖片
		'LIMIT'	=> '', #圖片尺寸需小於3M
	));

	define('aMENULANG', array(
		# 主目錄語系 sMenuTable0		=> 對應顯示名字
		'aKIND' => array(
			'end_manager_data'	=> '', #管理權限
			'client_money'		=> '', #金流管理
			'end_report'		=> '', #報表管理
			'client_user_data'	=> '', #會員管理
			'client_missions'		=> '', #任務管理
			'client_service'		=> '', #工單管理
			'client_chatroom'		=> '', #客服管理
			'client_news'		=> '', #消息管理
			'end_log'			=> '', #日誌管理
			'end_developer'		=> '', #環境管理
			'end_menu'			=> '', #目錄管理
		),
		# 子目錄語系 sListTable0		=> 對應顯示名字
		'aLIST' => array(
			'end_manager_data_0'              => '', #管理帳號
			'end_manager_password_0'          => '', #修改密碼
			'client_announce_0'               => '', #公告管理
			'client_announce_kind_0'          => '', #公告分類
			'client_user_data_0'              => '', #會員管理
			'client_missions_kind_0'          => '', #任務分類
			'client_missions_0'               => '', #任務管理
			'end_permission_0'                => '', #層級權限
			'client_user_rank_0'              => '', #會員等級(工程用)
			'client_broadcast_kind_0'         => '', #廣告分類
			'client_broadcast_0'              => '', #廣告管理
			'end_menu_kind_0'                 => '', #主目錄管理
			'end_menu_list_0'                 => '', #子目錄管理
			'end_manager_login_0'             => '', #後台登入日誌
			'end_log_0'                       => '', #後台操作日誌
			'client_service_kind_0'           => '', #工單分類
			'client_service_0'                => '', #工單管理
			'client_log_0'                    => '', #會員操作日誌
			'client_chatroom_0'               => '', #對話管理
			'client_chatroom_history_0'       => '', #歷史訊息
			'client_payment_company_setting_0'=> '', #公司入款設置
			'client_payment_online_setting_0' => '', #線上入款設置
			'client_payment_company_0'        => '', #公司入款
			'client_payment_online_0'         => '', #線上入款
			'client_withdrawal_0'             => '', #出款管理
			'sys_bank_0'                      => '', #銀行設置
			'end_canned_kind_0'               => '', #常用訊息分類
			'end_canned_0'                    => '', #常用訊息
			'client_manual_0'                 => '', #人工充提
			'client_missions_record_0'        => '', #任務審核
			'sys_param_0'                     => '', #環境設置(工程用)
			'client_user_login_0'             => '', #會員登入日誌
			'end_log_account_0'               => '', #帳務系統
			'end_report_0'                    => '', #報表統計
			'client_social_media_kind_0'      => '', #社群分類
			'client_missions_multiple_record_0'=> '', #批次任務審核
			'client_user_link_0'              => '', #會員層級查詢
			'client_user_bank_0'              => '', #會員銀行帳號
			'client_user_mission_0'           => '', #會員任務管理
			'client_missions_url_0'           => '', #罐頭網址
			'client_withdrawal_list_0'        => '', #提現名單
			'end_sync_0'                      => '', #同步管理
			'client_user_rank_1'              => '', #會員等級
			'sys_param_1'                     => '', #環境設置
			'end_developer_truncate_0'        => '', #清除資料
		),
	));

	define('aLOGNUMS', $aSystem['aLogNums']);
?>