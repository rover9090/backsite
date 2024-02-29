<?php
	define('aPARAM', array(
		'SANDROIDURL'	=> '安卓下載連結',
		'SIOSURL'		=> 'ios下載連結',
		'NAGENTID'		=> '總代理 nUid',
		'NRESERVEINTERVAL'=> '預約時間長度(秒)',
		'NMISSIONTIME'	=> '任務等待時間(秒)',
		'BRECORDDEALING'	=> '任務批次審核中(勿改)',
		'aWITHDRAWAL'	=> array(
			'WITHDRAWALTITLE'		=> '出款設定',
			'NCARDLIMIT'		=> '信用卡綁定張數',
			'NMINWITHDRAWAL'		=> '最低提領金額($)',
			'NDAYWITHDRAWAL'		=> '每日提領次數',
			'NWITHDRAWALFEE'		=> '提領手續費($)',
			'NWITHDRAWALFEETYPE'	=> '出款手續費模式(1:固定 2:百分比)',
		),
		'aRECHARGE'	=> array(
			'aRECHARGETITLE'	=> '入款設定',
			// 'NRECHARGEFEE'	=> 'VIP購買手續費($)',
			'NONLINEPAY'	=> '線上入款開關 0:關閉 1:開啟',
		),
		'aTIMELIMIT'	=> array(
			'aTIMELIMITTITLE'	=> '時間限制',
			// 'NSERVICETIME'	=> '工單提單時間限制(秒)',
			'NRECHARGETIME'	=> '公司入款提單時間限制(秒)',
			'NWITHDRAWALTIME'	=> '出款提單時間限制(秒)',
		),
		'aGAME'	=> array(
			'aGAMETITLE'	=> '遊戲設定',
			'NMINBET'		=> '單人最小投注金額',
			'NMAXPERBET'	=> '單人最高單注金額',
			'NMAXPERGAME'	=> '單人最高單局投注金額',
			'NMAXPERDAY'	=> '單人最高單日投注金額',
			'SNAME1RATE'	=> '輔助顯示幣種',
			'NWATERLEAST'	=> '轉移彩金水量(%)',
			'NBANKERMIN'	=> '最低打莊金額',
			'NBANKERMAX'	=> '最高打莊金額',
			'NLIMITBET'		=> '限制投注上限',
			'NOTHERCOUNT'	=> '配莊人數上限',
			'NOTHERMIN'		=> '配莊最低額度',
			'NOTHERMAX'		=> '配莊最高額度',
			'NBANKERLINECOUNT'=> '排莊人數上限',
			'NRETURNJACKPOT'	=> '吃彩返回彩池比例(%)',
			'NBANKERFEE'	=> '上莊費比例(%)',			
			'NCOMPANYLEAST'	=> '公司佔成(%)',
			'NWATERMAX'		=> '返水總量(%)',
		),
		// 'aSMS'	=> array(
		// 	'aSMSTITLE'		=> '簡訊設定',
		// 	'SSMSACC'		=> '簡訊驗證 API帳號',
		// 	'SSMSPWD'		=> '簡訊驗證 API密碼',
		// 	'NSMSTIME'		=> '簡訊驗證 過期時間(秒)',
		// ),
		// 'aCHATROOM'	=> array(
		// 	'aCHATROOMTITLE'	=> '聊天室設定',
		// 	'NCHATROOM'		=> '聊天室開關 0:關閉 1:開啟',
		// 	'SCHATROOMURL'	=> '聊天QR-Code連結',
		// ),
	));
	define('PARAMNAME',		'參數名稱');
	define('PARAMS',			'設定值');
	define('LASTUPDATETIME',	'最後修改時間');
	define('NODATACHANGED',		'無資料變更');
	define('NEWNAME',			'新增參數名稱');
	define('NEWPARAM',		'參數值');
?>