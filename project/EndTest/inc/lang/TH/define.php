<?php
	define('INS', 				'เพิ่ม'); #新增
	define('UPT', 				'แก้ไข'); #修改
	define('DEL', 				'ลบ'); #刪除
	define('SUCCESS', 			'สำเร็จ'); #成功
	define('FAIL', 				'ล้มเหลว'); #失敗
	define('PLEASESELECT', 			'กรุณาเลือก'); #請選擇
	define('ALL', 				'ทั้งหมด'); #全部
	define('PARAMSERR', 			'เลขไม่ถูกต้อง'); #參數異常
	define('EXPORTXLS',			'ส่งมอบ Excel '); #匯出 Excel # 2019-10-24 YL
	define('UNFILLED', 			'ยังไม่ได้กรอก'); #未填寫
	define('FORMATEERR', 			'รูปแบบไม่ถูกต้อง'); #格式錯誤
	define('NODATA', 				'ไม่พบข้อมูล'); #查無資料
	define('RIGHTMSG',			''); #確認訊息
	define('ERRORMSG',			''); #錯誤訊息

	# 語言
	define('CHOSELANG', 			'เลือกภาษา'); #選擇語言

	# 分頁
	define('FIRSTPAGE', 			'หน้าแรก'); #第一頁
	define('PREPAGE', 			'หน้าก่อนนี้'); #上一頁
	define('NEXTPAGE', 			'หน้าถัดไป'); #下一頁
	define('BEFORETEN', 			'10 หน้าแรก'); #前10頁
	define('NEXTTEN', 			'11 หน้าหลัง'); #下10頁
	define('LASTPAGE', 			'หน้าสุดท้าย'); #最末頁

	define('NO',	 			''); #編號
	define('ACCOUNT', 			'บัญชี'); #帳號
	define('NAME', 				'ชื่อเล่น'); #名稱
	define('KIND', 				'หมวดหมู่'); #分類
	define('CREATETIME',			'เวลาสร้าง'); #建立時間
	define('UPDATETIME',			'เวลาอัปเดท'); #更新時間
	define('STARTTIME',			'เวลาที่เริ่ม'); #開始時間
	define('ENDTIME',				'เวลาสิ้นสุด'); #結束時間
	define('OPERATE',				'ดำเนินการ'); #操作
	define('STATUS', 				'สถานะ'); #狀態
	define('SUBMIT', 				'ส่งมอบ '); #送出
	define('CONFIRM', 			'ยืนยัน'); #確認
	define('DENY', 				'ยกเลิก'); #拒絕
	define('OPEN', 				'เริ่มต้น'); #開啟
	define('CLOSE', 				'ปิด'); #關閉
	define('CANCEL', 				'ยกเลิก'); #取消
	define('BACK', 				'กลับ'); #返回
	define('SEARCH', 				'ค้นหา'); #查詢
	define('CSUBMIT', 			'ยืนยันส่งมอบ'); #確認送出
	define('CBACK', 				'ยกเลิกย้อนกลับ'); #取消返回
	define('MONEYIN', 			'ฝากเงิน'); #入款
	define('MONEYOUT', 			'ถอนเงิน'); #出款
	define('SERVICE', 			'ข้อความจากคอลเซ็นเตอร์'); #客服訊息
	define('SORT', 				'ลำดับ'); #排序
	define('SORTRULE', 			'ขนาดตัวเลขแสดงก่อน'); #數字大，先顯示
	define('ILLEGALVISIT', 			'การเข้าสู่ระบบที่ไม่ถูกต้อง'); #非法入侵
	define('HEADERPASSWORD', 		'แก้ไขรหัสผ่าน'); #修改密碼
	define('HEADERLOGOUT', 			'ออกจากระบบ'); #登出

	define('INSV', 				'เพิ่มสำเร็จ'); #新增成功
	define('UPTV', 				'แก้ไขสำเร็จ'); #修改成功
	define('DELV', 				'ลบสำเร็จ'); #刪除成功
	define('DENYV', 				'ยกเลิกสำเร็จ'); #拒絕成功
	define('PASSV', 				'ผ่านสำเร็จ'); #通過成功

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> 'ออนไลน์', #上線
			'sSelect'	=> '',
			'sClass'	=> '',
		),
		0	=> array(
			'sText'	=> 'ออฟไลน์', #下線
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> 'เมื่อวาน', #昨天
		'TODAY'	=> 'วันนี้', #今天
		'LASTWEEK'	=> 'อาทิตย์ที่แล้ว', #上週
		'THISWEEK'	=> 'อาทิตย์นี้', #本週
		'LASTMONTH'	=> 'เดือนที่แล้ว', #上月
		'THISMONTH'	=> 'เดือนนี้', #本月
	));

	# 各種圖片錯誤 #
	define('aIMGERROR',array(
		'ERROR'	=> 'การอัปโหลดรูปภาพล้มเหลวโปรดอัปโหลดอีกครั้ง (หากเกิดข้อผิดพลาดนี้ซ้ำ ๆ โปรดเปลี่ยนรูปภาพ)', #圖片上傳失敗，請重新上傳(如重複出現此錯誤，請更換圖片)
		'TYPE'	=> 'รูปแบบภาพไม่ตรงกันโปรดอัปโหลดอีกครั้ง', #圖片格式不符，請重新上傳
		'SIZE'	=> 'ขนาดภาพไม่ตรงกันโปรดอัปโหลดอีกครั้ง', #圖片大小不符，請重新上傳
		'INISIZE'	=> 'ขนาดภาพเกินขีดจำกัดของ ini โปรดอัปโหลดอีกครั้ง', #圖片大小超出ini限制，請重新上傳
		'FORMSIZE'	=> 'ขนาดรูปภาพเกินขีด จำกัด ของแบบฟอร์มโปรดอัปโหลดอีกครั้ง', #圖片大小超出表單限制，請重新上傳
		'PARTIAL'	=> 'อัพโหลดรูปภาพเพียงบางส่วนโปรดอัปโหลดอีกครั้ง', #圖片只有部份被上傳，請重新上傳
		'NOFILE'	=> 'ยังไม่ได้อัปโหลดรูปภาพโปรดอัพโหลดอีกครั้ง', #圖片沒有被上傳，請重新上傳
		'TMPDIR'	=> 'ไม่พบรูปภาพในโฟลเดอร์ชั่วคราวโปรดอัปโหลดอีกครั้ง', #圖片找不到臨時資料夾，請重新上傳
		'CANTWRITE'	=> 'ไม่สามารถอ่านไฟล์ภาพได้โปรดอัปโหลดอีกครั้ง', #圖片文件寫入失敗，請重新上傳
		'LEASTONE'	=> 'โปรดอัปโหลดภาพอย่างน้อยหนึ่งภาพ', #請至少上傳一張圖片
		'LIMIT'	=> 'ขนาดภาพต้องน้อยกว่า 3M', #圖片尺寸需小於3M
	));

	define('aMENULANG', array(
		# 主目錄語系 sMenuTable0		=> 對應顯示名字
		'aKIND' => array(
			'end_manager_data'	=> 'ตั้งค่าบทบาท', #管理權限
			'client_money'		=> 'จัดการการเงิน', #金流管理
			'end_report'		=> 'การจัดการรายงาน', #報表管理
			'client_user_data'	=> 'การจัดการสมาชิก', #會員管理
			'client_chatroom'		=> 'การจัดการคอลเซ็นเตอร์', #客服管理
			'client_news'		=> 'การจัดการข่าวสาร', #消息管理
			'end_log'			=> 'การจัดการบันทึกประจำวัน', #日誌管理
			'end_developer'		=> 'การจัดการพื้นที่', #環境管理
			'end_menu'			=> 'การจัดการเมนู', #目錄管理
		),
		# 子目錄語系 sListTable0		=> 對應顯示名字
		'aLIST' => array(
			'end_manager_data_0'              		=> 'จัดการบัญชี', #管理帳號
			'end_manager_password_0'          		=> 'แก้ไขรหัสผ่าน', #修改密碼
			'client_announce_0'               		=> 'การจัดการประกาศ', #公告管理
			'client_announce_kind_0'          		=> 'หมวดหมู่ประกาศ', #公告分類
			'client_user_data_0'              		=> 'การจัดการสมาชิก', #會員管理
			'end_permission_0'                		=> 'สิทธิบทบาท', #層級權限
			'client_broadcast_kind_0'         		=> 'การจัดการหมวดหมู่โฆษณา', #廣告分類
			'client_broadcast_0'              		=> 'การจัดการโฆษณา', #廣告管理
			'end_menu_kind_0'                 		=> 'การจัดการเมนูหลัก', #主目錄管理
			'end_menu_list_0'                 		=> 'การจัดการเมนูรอง', #子目錄管理
			'end_manager_login_0'             		=> 'ประวัติการเข้าสู่ระบบหลังบ้าน', #後台登入日誌
			'end_log_0'                       		=> 'ประวัติการดำเนินการหลังบ้าน', #後台操作日誌
			'client_log_0'                    		=> 'บันทึกการทำงานของสมาชิก', #會員操作日誌
			'client_chatroom_0'               		=> 'การจัดการการสนทนา', #對話管理
			'client_chatroom_history_0'       		=> 'ประวัติข้อความ', #歷史訊息
			'client_payment_company_setting_0'		=> 'ตั้งค่าบริษัทฝากเงิน', #公司入款設置
			'client_payment_online_setting_0' 		=> 'ตั้งค่าฝากเงินออนไลน์', #線上入款設置
			'client_payment_company_0'        		=> 'บริษัทฝากเงิน', #公司入款
			'client_payment_online_0'         		=> 'ฝากเงินออนไลน์', #線上入款
			'client_withdrawal_0'             		=> 'จัดการการถอนเงิน', #出款管理
			'sys_bank_0'                      		=> 'ตั้งค่าธนาคาร', #銀行設置
			'end_canned_kind_0'               		=> 'การจัดประเภทข้อความทั่วไป', #常用訊息分類
			'end_canned_0'                    		=> 'ข้อความทั่วไป ', #常用訊息
			'client_manual_0'                 		=> 'ฝากถอนด้วยตนเอง', #人工充提
			'sys_param_0'                     		=> 'การตั้งค่าสิ่งแวดล้อม (สำหรับโปรแกรมเมอร์)', #環境設置(工程用)
			'client_user_login_0'             		=> 'ประวัติล็อกอินสมาชิก', #會員登入日誌
			'end_log_account_0'               		=> 'บัญชีรายรับรายจ่ายระบบ', #帳務系統
			'end_report_0'                    		=> 'รายงานสถิติ', #報表統計
			'client_user_link_0'              		=> 'ตรวจสอบเลเวลสมาชิก', #會員層級查詢
			'client_user_bank_0'              		=> 'บัญชีธนาคารสมาชิก', #會員銀行帳號
			'end_sync_0'                      		=> 'จัดการทั้งหมด', #同步管理
			'sys_param_1'                     		=> 'ตั้งค่าสภาพแวดล้อม', #環境設置
			'end_developer_truncate_0'        		=> 'ล้างข้อมูลทั้งหมด', #清除資料
			'client_payment_online_tunnel_0'		=> '', #線上入款通道設置
			'client_payment_withdrawal_tunnel_0'	=> '', #代付出款通道設置
		),
	));

	define('aLOGNUMS', $aSystem['aLogNums']);
?>