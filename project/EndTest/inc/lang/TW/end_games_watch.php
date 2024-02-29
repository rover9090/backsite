<?php
	define('aLOTTERY', array(
		'GAMECHOOSE'	=> '遊戲選擇',
		'NO'			=> '期數',
		'NOWNO'		=> '目前期數',
		'CTRL'		=> '控制',
		'RESULT'		=> '輸贏結果',

		'HIDECTRL'		=> '隱藏控制項',
		'SHOWHINT'		=> '打開提示',
		'CLOSEHINT'		=> '關閉提示',
		'NOWNO'		=> '目前期數',
		'NOWROUND'		=> '目前局數',
		'OLDVER'		=> '打勾使用舊版',
		'NEXT0'		=> '庄問路',
		'NEXT1'		=> '庄閑問路',
		'RES0'		=> '莊家結果',
		'RES1'		=> '閒家結果',
		'OPENWINDOW'	=> '開新視窗',

		'TODAYCOUNT'	=> '本日倒數把數',
		'CLOSEBET'		=> '停止下注',
		'SHUFFLING'		=> '洗牌中',
		'CANCELBET'		=> '本局取消',
		'STOPBET'		=> '投注暫停',
		'NOWBET'		=> '目前注單',
		'BAN'			=> '禁言',
		'BLACK'		=> '黑名單',
		'SUM'			=> '總計',
		'REFRESHCOUNT'	=> '刷新倒數',
		'LASTNO'		=> '最後一局',
		'SEC'			=> '秒',


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
			'A'=>'A門',
			'B'=>'B門',
			'C'=>'C門',
			'BANKER'=>'莊家',
			'OTHERS'=>'配莊',
		),
		'aBETRESULT'	=> array(
			'A'=>'A門',
			'B'=>'B門',
			'C'=>'C門',
			'BANKER'=>'莊家',
			'OTHERS'=>'配莊',
		),
		'NOWRESULT'	=> '目前結果',
		'ACCOUNT'	=> '帳號',
		'NAME0'	=> '暱稱',
		'BETRESULT'	=> '下注內容',
		'COUNT'	=> '投注筆數',
		'MONEY0'	=> '下注金額',
		'MONEY1'	=> '派獎結果',
		'BETTIME'	=> '下注時間',
		'ALLBET'	=> '總投注',
		'PER'		=> '占成',
		'PERMONEY'	=> '投注占成',
	));

	define('aMsg',array(
		'NODATA'	=> '查無期數',
		'NOTOVER'	=> '前一期尚未結束',
		'STATUSERR'	=> '修改狀態不可小於當前狀態',
		'RESULTERR'	=> '獎號異常',
		'NOROOM'	=> '請選擇房間',
		'BANKERERR'	=> '莊家數字錯誤',
		'PLAYERERR'	=> '閒家數字錯誤',
		'NOROUND'	=> '找不到期數',
	));

	define('aRESULTTYPE',array(
		'A'=>'A門',
		'B'=>'B門',
		'C'=>'C門',
		'BANKER'=>'莊家',
		'OTHERS'=>'配莊',
	));
?>