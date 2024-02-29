<?php
	define('aGAMECTRL', array(
		'HIDECTRL'		=> '隱藏控制項',
		'ADMID'		=> '荷官編號',
		'PRENO'		=> '上期局數',
		'PREROUND'		=> '上期局數',
		'NOWROUND'		=> '目前局數',
		'OLDVER'		=> '打勾使用舊版',
		'PAUSE'		=> '暫停投注',
		'EDITNUMS'		=> '修改牌型',
		'COUNTDOWN'		=> '倒數把數',
		'CANCEL'		=> '取消當局',
		'CHECKING'		=> '偵測牌型',
		'SHUFFLE'		=> '洗牌',
		'RED'			=> '紅卡',
		'LASTNO'		=> '最後一局',
		'CHGADM'		=> '換荷官',
		'TODAYDONATE'	=> '本日打賞',
		'REMEMBER'		=> '記住這個頁面',
		'PUT0'		=> '輸入莊家結果',
		'PUT1'		=> '輸入閒家結果',
		
		'NEXT0'		=> '庄問路',
		'NEXT1'		=> '庄閑問路',
		'SETTLENEW'		=> '結算後新局',
		'PUTADMID'		=> '請輸入荷官編號,',
		'NOWNO'		=> '目前期數',
		'CTRL'		=> '控制',
		'RESULT'		=> '莊家結果',
		'ROUND'		=> '現在局數',
		'NEXTROUND'		=> '下一局',
		'DEALER'		=> '荷官名稱',
		'aSTATUS'		=> array(
			0	=> array(
				'sText'	=> '新局(開始下注)',
				'sClass'	=> 'FontRed',
			),
			1	=> array(
				'sText'	=> '下注結束',
				'sClass'	=> 'FontOrange',
			),
			2	=> array(
				'sText'	=> '結算',
				'sClass'	=> 'FontGreen',
			),
			3	=> array(
				'sText'	=> '待結算',
				'sClass'	=> 'FontYellow',
			),
			4	=> array(
				'sText'	=> '已完成',
				'sClass'	=> 'FontBule',
			),
			5	=> array(
				'sText'	=> '已完成(重新開獎中)',
				'sClass'	=> '',
			),
			6	=> array(
				'sText'	=> '待重新過帳',
				'sClass'	=> '',
			),
			7	=> array(
				'sText'	=> '重新過帳完成',
				'sClass'	=> '',
			),
			8	=> array(
				'sText'	=> '重新過帳講完話',
				'sClass'	=> '',
			),
			99	=> array(
				'sText'	=> '異常終止',
				'sClass'	=> '',
			),
		),
		'aRESULT'	=> array(
			'0'		=> '莊',
			'1'		=> '閒',
			'2'		=> '和',
			// '3'		=> '莊對',
			// '4'		=> '閒對',
			// '5'		=> '小',
			// '6'		=> '大',
			// '7'		=> '幸運6',
		),
	));

	define('aMsg',array(
		'NODATA'	=> '查無期數',
		'NOTOVER'	=> '前一期尚未結束',
		'STATUSERR'	=> '修改狀態不可小於當前狀態',
		'FORMATERR'	=> '格式錯誤',
		'NOSHUFFLE' => '請先洗牌',
		'NEWNO'	=> '新局',
		'SETTLE'	=> '結算',
		'RESTART'	=> '暫停再開',
		'BACK' 	=> '返回頁面',
		'NOTEND'	=> '尚未結束此局',
		'NOADM'	=> '找不到荷官資料',
		'HADADM'	=> '此荷官已上線',
		'SHUFFLING' => '洗牌中',
		'HADEND' 	=> '此局已結束',
		'BANKERNOTENOUGH' => '莊家餘額不足當莊，最低[:MONEY:]',
	));

	define('aRESULTTYPE',array(
		'BW'=>'莊贏',
		'PW'=>'閒贏',
		'PP'=>'閒對',
		'BP'=>'莊對',
		'DRAW'=>'打和',
		'SIX'=>'超級六',
	));

	define('aMETHODNAME',array(
		'A' => '初A',
		'B' => '川B',
		'C' => '尾C',
	));

	define('aPICTURE', array(
		'NAME'	=> '圖片名稱',
		'KIND'	=> '圖片分類',
		'CONTENT'	=> '圖片內容',
		'LINK'	=> '圖片連結',
		'SYNC'	=> '同步圖片',
		'DESCRIPTION'=> '描述',
		'IMAGE'	=> '圖片',
		'RESET'	=> '重設檔案',
		'UPLOAD'	=> '確認上傳',
		'LANGERROR'	=> '找不到對應的語言',
		'NOIMG'	=> '請上傳圖片',
		'INDEX'	=> '位置',
		'DELIMG'	=> '刪除圖片',
		'ADDTOINDEX'	=> '加到首頁',
		'OUTOFINDEX'	=> '移出首頁',
		'SELECTTOCOVER'	=> '設為封面',
		'OUTOFCOVER'	=> '移出封面',
		'REMOVEFIRST'	=> '請先移除舊圖片',
		'IMAGE_NOT_FOUND' => '找不到圖片'
	));
?>