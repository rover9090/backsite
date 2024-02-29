<?php
	define('INS', 				'Tăng thêm'); #新增
	define('UPT', 				'Chỉnh sửa'); #修改
	define('DEL', 				'Xóa bỏ'); #刪除
	define('SUCCESS', 			'Thành công'); #成功
	define('FAIL', 				'Thất bại'); #失敗
	define('PLEASESELECT', 			'Hãy lựa chọn'); #請選擇
	define('ALL', 				'Toàn bộ'); #全部
	define('PARAMSERR', 			'Tham số bất thường'); #參數異常
	define('EXPORTXLS',			'Chuyển ra Exel'); #匯出 Excel # 2019-10-24 YL
	define('UNFILLED', 			'Chưa điền'); #未填寫
	define('FORMATEERR', 			'Định dạng sai'); #格式錯誤
	define('NODATA', 				'Kiểm tra không có thông tin'); #查無資料
	define('RIGHTMSG',			'確認訊息');
	define('ERRORMSG',			'錯誤訊息');

	# 語言
	define('CHOSELANG', 			'Lựa chọn ngôn ngữ'); #選擇語言

	# 分頁
	define('FIRSTPAGE', 			'Trang đầu'); #第一頁
	define('PREPAGE', 			'Trang trước'); #上一頁
	define('NEXTPAGE', 			'Trang sau'); #下一頁
	define('BEFORETEN', 			'10 trang trước'); #前10頁
	define('NEXTTEN', 			'10 trang sau'); #下10頁
	define('LASTPAGE', 			'Trang cuối'); #最末頁

	define('NO',	 			'編號');
	define('ACCOUNT', 			'Số tài khoản'); #帳號
	define('NAME', 				'Tên gọi'); #名稱
	define('KIND', 				'Phân loại'); #分類
	define('CREATETIME',			'Thời gian thiết lập'); #建立時間
	define('UPDATETIME',			'Thời gian cập nhật'); #更新時間
	define('STARTTIME',			'Thời gian bắt đầu'); #開始時間
	define('ENDTIME',				'Thời gian kết thúc'); #結束時間
	define('OPERATE',				'Thao tác'); #操作
	define('STATUS', 				'Trạng thái'); #狀態
	define('SUBMIT', 				'Gửi đi'); #送出
	define('CONFIRM', 			'Xác nhận'); #確認
	define('DENY', 				'Từ chối'); #拒絕
	define('OPEN', 				'Mở'); #開啟
	define('CLOSE', 				'Đóng'); #關閉
	define('CANCEL', 				'Hủy'); #取消
	define('BACK', 				'Trở về'); #返回
	define('SEARCH', 				'Tra cứu'); #查詢
	define('CSUBMIT', 			'Xác nhận gửi đi'); #確認送出
	define('CBACK', 				'Hủy bỏ quay lại'); #取消返回
	define('MONEYIN', 			'Nhập khoản'); #入款
	define('MONEYOUT', 			'Xuất khoản'); #出款
	define('SERVICE', 			'Tin nhắn người phục vụ'); #客服訊息
	define('SORT', 				'Xếp thứ tự'); #排序
	define('SORTRULE', 			'Số to, hiển thị trước'); #數字大，先顯示
	define('ILLEGALVISIT', 			'Xâm nhập phi pháp'); #非法入侵
	define('HEADERPASSWORD', 		'Sửa đổi mật mã'); #修改密碼
	define('HEADERLOGOUT', 			'Đăng xuất'); #登出

	define('INSV', 				'Thêm thành công'); #新增成功
	define('UPTV', 				'Sửa thành công'); #修改成功
	define('DELV', 				'Xóa thành công'); #刪除成功
	define('DENYV', 				'Từ chối thành công'); #拒絕成功
	define('PASSV', 				'Thông qua thành công'); #通過成功

	define('aONLINE',	array(
		1	=> array(
			'sText'	=> 'online', #上線
			'sSelect'	=> '',
			'sClass'	=> '',
		),
		0	=> array(
			'sText'	=> 'offline', #下線
			'sSelect'	=> '',
			'sClass'	=> 'FontRed',
		),
	));

	define('aDAYTEXT', array(
		'YESTERDAY'	=> 'Hôm qua', #昨天
		'TODAY'	=> 'Hôm nay', #今天
		'LASTWEEK'	=> 'Tuần trước', #上週
		'THISWEEK'	=> 'Tuần này', #本週
		'LASTMONTH'	=> 'Tháng trước', #上月
		'THISMONTH'	=> 'Tháng này', #本月
	));

	# 各種圖片錯誤 #
	define('aIMGERROR',array(
		'ERROR'	=> 'Ảnh tải lên thất bại, hãy đăng tải lại (nếu xuất hiện nhiều lần lỗi này, hãy thay ảnh khác)', #圖片上傳失敗，請重新上傳(如重複出現此錯誤，請更換圖片)
		'TYPE'	=> 'Định dạng ảnh không phù hợp, hãy đăng tải lại', #圖片格式不符，請重新上傳
		'SIZE'	=> 'Kích thước ảnh không phù hợp, hãy đăng tải lại', #圖片大小不符，請重新上傳
		'INISIZE'	=> 'Kích thước ảnh vượt quá giới hạn ini, hãy đăng tải lại', #圖片大小超出ini限制，請重新上傳
		'FORMSIZE'	=> 'Kích thước ảnh vượt quá giới hạn bảng biểu, hãy đăng tải lại', #圖片大小超出表單限制，請重新上傳
		'PARTIAL'	=> 'Ảnh chỉ được đăng tải một phần, hãy đăng tải lại', #圖片只有部份被上傳，請重新上傳
		'NOFILE'	=> 'Ảnh chưa được đăng tải, hãy đăng tải lại', #圖片沒有被上傳，請重新上傳
		'TMPDIR'	=> 'Ảnh không tìm thấy tệp dữ liệu tạm thời, hãy đăng tải lại', #圖片找不到臨時資料夾，請重新上傳
		'CANTWRITE'	=> 'Ảnh nhập thấp bại, hãy đăng tải lại', #圖片文件寫入失敗，請重新上傳
		'LEASTONE'	=> 'Hãy đăng tải ít nhất một bức ảnh', #請至少上傳一張圖片
		'LIMIT'	=> 'Kích thước ảnh cần nhỏ hơn 3M', #圖片尺寸需小於3M
	));

	define('aMENULANG', array(
		# 主目錄語系 sMenuTable0		=> 對應顯示名字
		'aKIND' => array(
			'end_manager_data'	=> 'Quyền hạn quản lý', #管理權限
			'client_money'		=> 'Quản lý phương thức chi trả', #金流管理
			'end_report'		=> 'Quản lý bảng biểu', #報表管理
			'client_user_data'	=> 'Quản lý hội viên', #會員管理
			'client_chatroom'		=> 'Quản lý phục vụ', #客服管理
			'client_news'		=> 'Quản lý thông tin', #消息管理
			'end_log'			=> 'Quản lý tạp chí', #日誌管理
			'end_developer'		=> 'Quản lý môi trường', #環境管理
			'end_menu'			=> 'Quản lý mục lục', #目錄管理
		),
		# 子目錄語系 sListTable0		=> 對應顯示名字
		'aLIST' => array(
			'end_manager_data_0'              		=> 'Tài khoản quản lý', #管理帳號
			'end_manager_password_0'          		=> 'Sửa đổi mật mã', #修改密碼
			'client_announce_0'               		=> 'Quản lý thông báo', #公告管理
			'client_announce_kind_0'          		=> 'Phân loại thông báo', #公告分類
			'client_user_data_0'              		=> 'Quản lý hội viên', #會員管理
			'end_permission_0'                		=> 'Quyền hạn tầng cấp', #層級權限
			'client_broadcast_kind_0'         		=> 'Phân loại quảng cáo', #廣告分類
			'client_broadcast_0'              		=> 'Quản lý quảng cáo', #廣告管理
			'end_menu_kind_0'                 		=> 'Quản lý mục lục chính', #主目錄管理
			'end_menu_list_0'                 		=> 'Quản lý mục lục nhỏ', #子目錄管理
			'end_manager_login_0'             		=> 'Nhật ký đăng nhập hậu đài', #後台登入日誌
			'end_log_0'                       		=> 'Nhật ký thao tác hậu đài', #後台操作日誌
			'client_log_0'                    		=> 'Nhật ký thao tác hội viên', #會員操作日誌
			'client_chatroom_0'               		=> 'Quản lý đối thoại', #對話管理
			'client_chatroom_history_0'       		=> 'Tin nhắn lịch sử', #歷史訊息
			'client_payment_company_setting_0'		=> 'Thiết đặt nhập khoản công ty', #公司入款設置
			'client_payment_online_setting_0' 		=> 'Thiết đặt nhập khoản trực tuyến', #線上入款設置
			'client_payment_company_0'        		=> 'Công ty nhập khoản', #公司入款
			'client_payment_online_0'         		=> 'Trực tuyến nhập khoản', #線上入款
			'client_withdrawal_0'             		=> 'Quản lý xuất khoản', #出款管理
			'sys_bank_0'                      		=> 'Thiết lập ngân hàng', #銀行設置
			'end_canned_kind_0'               		=> 'Phân loại tin nhắn thường dùng', #常用訊息分類
			'end_canned_0'                    		=> 'Tin nhắn thường dùng', #常用訊息
			'client_manual_0'                 		=> 'Nạp rút nhân công', #人工充提
			'sys_param_0'                     		=> 'Thiết đặt môi trường (dành cho công trình)', #環境設置(工程用)
			'client_user_login_0'             		=> 'Nhật ký đăng nhập hội viên', #會員登入日誌
			'end_log_account_0'               		=> 'Hệ thống sổ sách', #帳務系統
			'end_report_0'                    		=> 'Thống kê bảng biểu', #報表統計
			'client_user_link_0'              		=> 'Tra cứu tầng cấp hội viên', #會員層級查詢
			'client_user_bank_0'              		=> 'Tài khoản ngân hàng hội viên', #會員銀行帳號
			'end_sync_0'                      		=> 'Đồng bộ quản lý', #同步管理
			'sys_param_1'                     		=> 'Cài đặt môi trường', #環境設置
			'end_developer_truncate_0'        		=> 'Xóa tư liệu', #清除資料
			'client_payment_online_tunnel_0'		=> '', #線上入款通道設置
			'client_payment_withdrawal_tunnel_0'	=> '', #代付出款通道設置
		),
	));

	define('aLOGNUMS', $aSystem['aLogNums']);
?>