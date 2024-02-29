<?php
	define('aMANAGER', array(
		'SEARCH'		=> 'Tra cứu', #查詢
		'SEARCHACCOUNT'	=> 'Tra cứu Tài khoản quản lý viên', #查詢管理員帳號
		'ACCOUNT'		=> 'Số tài khoản người quản lý', #管理員帳號
		'ACCOUNTINFO'	=> 'Số tài khoản người quản lý (thiết lập xong không được sửa đổi)', #管理員帳號 (建立後不可修改)
		'ADMTYPE'		=> 'Phân cấp người quản lý', #管理員層級
		'STATUS'		=> 'Trạng thái', #狀態
		'CREATETIME'	=> 'Thời gian thiết lập', #建立時間
		'UPDATETIME'	=> 'Thời gian cập nhật', #更新時間
		'OPERATE'		=> 'Thao tác', #操作
		'ALL'			=> 'Toàn bộ', #全部
		'OFFLINE'		=> 'Thoát mạng (offline)', #下線
		'ONLINE'		=> 'Lên mạng (online)', #上線
		'CONFIRM0'		=> 'Xác nhận xóa bỏ ??', #確認刪除
		'CONFIRM1'		=> 'Xác nhận gửi đi ??', #確認送出
		'NAME'		=> 'Tên người quản lý', #管理員名稱
		'PASSWORD'		=> 'Mật mã người quản lý', #管理員密碼
		'NEWPASSWORD'	=> 'Mật mã người quản lý mới', #新管理員密碼
		'CONFIRMPASSWORD'	=> 'Xác nhận lại mật mã', #再次確認密碼
		'NOTE'		=> 'Thay đổi thông tin hãy nhập mật mã đăng nhập hậu đài', #變更資料請輸入後台登入密碼。
		'CONTROLITEM'	=> 'Hạng mục có thể kiểm soát', #可控制項目
		'SELECTALL'		=> 'Chọn toàn bộ', #全選
		'INSERTSUCCESS'	=> 'Thêm thành công', #新增成功
		'UPDATESUCCESS'	=> 'Sửa thành công', #修改成功
		'DELETESUCCESS'	=> 'Xóa thành công', #刪除成功
		'SUBMIT'		=> 'Xác nhận gửi đi', #確認送出
		'CANCEL'		=> 'Hủy bỏ quay lại', #取消返回

		'HIDEUSER'		=> 'Ẩn tài khoản', #隱藏帳號
		'HIDE0'		=> 'Không ẩn', #不隱藏
		'HIDE1'		=> 'Ẩn ', #隱藏
		'IP'			=> 'Ràng buộc IP', #綁定IP
		'GOOGLE'		=> 'Google nghiệm chứng', #Google驗證
		'GOOGLENOUSE'	=> 'Không khởi động', #不啟用
		'GOOGLEUSE'		=> 'Khởi động', #啟用
	));
	define('aERROR', array(
		'EMPTYACCOUNT'	=> 'Tài khoản người quản lý không được để trống', #管理員帳號不可為空
		'EMPTYNAME'		=> 'Tên người quản lý không được để trống', #管理員名稱不可為空
		'EMPTYPASSWORD'	=> 'Hãy nhập mật mã người quản lý', #請輸入管理員密碼
		'USED'		=> 'Tài khoản này đã được sử dụng', #此帳號已被使用
		'ACCOUNTFORMATE'	=> 'Định dạng tài khoản người quản lý sai, hãy nhập 6-16 số chữ cái', #管理員帳號格式錯誤，請輸入6-16碼英數字
		'PASSWORDFORMATE'	=> 'Định dạng tài khoản người quản lý mới sai, hãy nhập 6-16 số chữ cái ', #新管理員密碼格式錯誤，請輸入6-16碼英數字
		'UNMATCH'		=> 'Mật mã người quản lý mới và mật mã xác nhận đối chiếu sai', #新管理員密碼與再次確認密碼比對錯誤
		'NODATA'		=> 'Kiểm tra không có thông tin', #查無相關資料
		'ERRORPASSWORD'	=> 'Mật mã người quản lý nhập sai', #管理員密碼輸入錯誤
	));

	define('aGOOGLE', array(
		'STEP1'	=> 'Hãy tải Google Authenticator app trước', #請先下載 Google Authenticator app
		'STEP2'	=> 'Máy ảnh quét Qrcode', #相機掃描Qrcode
		'STEP3'	=> 'Phía dưới nhập mật mã nghiệm chứng để nghiểm chứng', #下方輸入驗證碼進行驗證
		'CODE'	=> 'Hãy nhập mật mã nghiệm chứng Google tiến hành nghiệm chứng', #請輸入google驗證碼 進行驗證
		'SEND'	=> 'Gửi đi nghiệm chứng', #送出驗證
	));
?>