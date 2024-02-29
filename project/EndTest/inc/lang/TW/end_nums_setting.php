<?php
	define('aPERIOD', array(
		'BANKERNAME'		=> '莊家',
		'NAME0'		=> '遊戲',
		'ROUND'		=> '局數',
		'NO'			=> '期數',
		'NUMBER'		=> '開獎牌型',
		'BANKER'		=> '莊家牌型',
		'PLAYER0'		=> '閒家牌型',
		'RESULT'		=> '輸贏結果',
		'aStaus'		=> array(
			'sText'	=> '狀態',
			0		=> array(
				'sText'	=> '開始下注',
				'sSelect'	=> '',
				'sClass'	=> 'FontOrange',
			),
			1		=> array(
				'sText'	=> '下注結束',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlack',
			),
			2		=> array(
				'sText'	=> '結算',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			3		=> array(
				'sText'	=> '待結算',
				'sSelect'	=> '',
				'sClass'	=> 'FontPink',
			),
			4		=> array(
				'sText'	=> '已完成',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
			5		=> array(
				'sText'	=> '已完成',
				'sSelect'	=> '',
				'sClass'	=> 'FontGreen',
			),
			6		=> array(
				'sText'	=> '待重新過帳',
				'sSelect'	=> '',
				'sClass'	=> 'FontOrange',
			),
			7		=> array(
				'sText'	=> '重新過帳完成',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlack',
			),
			8		=> array(
				'sText'	=> '重新過帳講完話',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			11		=> array(
				'sText'	=> '期數暫停',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			99		=> array(
				'sText'	=> '異常終止',
				'sSelect'	=> '',
				'sClass'	=> 'FontPink',
			),
		),
		'OPENTIME'		=> '開盤時間',
		'CLOSETIME'		=> '關盤時間',
		'UPDATETIME'	=> '獎號更新時間',
		'aOnline'		=> array(
			'sText'	=> '開關盤狀態',
			1		=> array(
				'sText'	=> '開盤',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			0		=> array(
				'sText'	=> '停盤',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
		'aBetType'		=> array(
			'sText'	=> '下注狀態',
			2		=> array(
				'sText'	=> '已退組',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
			1		=> array(
				'sText'	=> '有人下注',
				'sSelect'	=> '',
				'sClass'	=> 'FontBlue',
			),
			0		=> array(
				'sText'	=> '無人下注',
				'sSelect'	=> '',
				'sClass'	=> 'FontRed',
			),
		),
	));

	define('LOTTERY_GIVENUMS', '人工開球');
	define('LOTTERY_JUDGE', '人工過帳');
	define('CONFIRM_JUDGE', '確認過帳');
	define('LOTTERY_REJUDGE', '人工重過帳');
	define('CONFIRM_REJUDGE', '確認重過帳');
	define('LOTTERY_REFUND', '退組');
	define('CONFIRM_REFUND', '確認退組');
	define('HASBET_2', '已退組完成');
	define('HASBET_0', '無下注紀錄');
	define('OFF_REFUND', '關盤再退組');

	define('aACTTEXT', array(
		'NoNo'		=> '查無期數',
		'NoGame'		=> '查無遊戲',
		'NotBackMoney'	=> '尚未退組',
		'TIME_REVERSE'	=> '關盤時間需大於開盤時間',
	));

	define('sDELPERIOD', 	'刪除期數');

	

	define('aCARDFLO',array(
		'SA' => '黑桃A', 
		'HA' => '紅心A', 
		'DA' => '方塊A', 
		'CA' => '梅花A', 
		'S2' => '黑桃2', 
		'H2' => '紅心2', 
		'D2' => '方塊2', 
		'C2' => '梅花2', 
		'S3' => '黑桃3', 
		'H3' => '紅心3', 
		'D3' => '方塊3', 
		'C3' => '梅花3', 
		'S4' => '黑桃4', 
		'H4' => '紅心4', 
		'D4' => '方塊4', 
		'C4' => '梅花4', 
		'S5' => '黑桃5', 
		'H5' => '紅心5', 
		'D5' => '方塊5', 
		'C5' => '梅花5', 
		'S6' => '黑桃6', 
		'H6' => '紅心6', 
		'D6' => '方塊6', 
		'C6' => '梅花6', 
		'S7' => '黑桃7', 
		'H7' => '紅心7', 
		'D7' => '方塊7', 
		'C7' => '梅花7', 
		'S8' => '黑桃8', 
		'H8' => '紅心8', 
		'D8' => '方塊8', 
		'C8' => '梅花8', 
		'S9' => '黑桃9', 
		'H9' => '紅心9', 
		'D9' => '方塊9', 
		'C9' => '梅花9', 
		'S10' => '黑桃10', 
		'H10' => '紅心10', 
		'D10' => '方塊10', 
		'C10' => '梅花10', 
		'SJ' => '黑桃J', 
		'HJ' => '紅心J', 
		'DJ' => '方塊J', 
		'CJ' => '梅花J', 
		'SQ' => '黑桃Q', 
		'HQ' => '紅心Q', 
		'DQ' => '方塊Q', 
		'CQ' => '梅花Q', 
		'SK' => '黑桃K', 
		'HK' => '紅心K', 
		'DK' => '方塊K', 
		'CK' => '梅花K', 
	));
?>