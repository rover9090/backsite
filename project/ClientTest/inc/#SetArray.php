<?php
	// $aSetUrl = array(
	// 	'sLogin'	      => sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1',
	// 	'sUpdateUser'	=> sys_web_encode($aMenuToNo['pages/center/php/_ajax.update_user_0.php']),
	// );

	$sCatchKey = '';
	$sLang = '';
	
	$aMenu = array(
		'chatroom' => array(
			'sText'  => '聊天',
			'sIcon'  => '',
			'sDataTg'=> 'list',
			'sId'    => 'gotobetmsg',
			'sAjax'  => '',
			'sClass' => 'chatroom',
		),
		'bet' => array(
			'sText'  => '下注',
			'sIcon'  => '',
			'sDataTg'=> 'bet',
			'sId'    => 'gotobet',
			'sAjax'  => '',
			'sClass' => 'bet',
		),
		// 'betRecord' => array(
		// 	'sText'  => '紀錄',
		// 	'sIcon'  => '',
		// 	'sDataTg'=> 'myhistory',
		// 	'sId'    => 'home',
		// 	'sAjax'  => '/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-'.$sCatchKey.'?Lang='.$sLang,
		// 	'sClass' => 'betrecord',
		// ),
		'donate' => array(
			'sText'  => '打賞',
			'sIcon'  => '',
			'sDataTg'=> 'donate',
			'sId'    => 'donate',
			'sAjax'  => '',
			'sClass' => 'donate',
		),
		// 'rule' => array(
		// 	'sText'  => '',
		// 	'sIcon'  => '<i class="far fa-question-circle"></i>',
		// 	'sDataTg'=> 'rule',
		// 	'sId'    => 'home',
		// 	'sAjax'  => '/cc7HsK6Qx3vD0Z2thNedldg=-'.$sCatchKey.'?Lang='.$sLang,
		// 	'sClass' => 'rule',
		// ),
		// 'bankerrecord' => array(
		// 	'sText'  => '打莊',
		// 	'sIcon'  => '',
		// 	'sDataTg'=> 'myhistory',
		// 	'sId'    => 'home',
		// 	'sAjax'  => '/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-'.$sCatchKey.'?Lang='.$sLang,
		// 	'sClass' => 'bankerrecord',
		// ),
		'history' => array(
			'sText'  => '期數',
			'sIcon'  => '',
			'sDataTg'=> '',
			'sId'    => '',
			'sAjax'  => '',
			'sClass' => 'history',
		),
		'buybanker' => array(
			'sText'  => '公告',
			'sIcon'  => '',
			'sDataTg'=> '',
			'sId'    => '',
			'sAjax'  => '',
			'sClass' => 'buybanker',
		),
	);

	$aSetCoinArray = array(
		// 1 => array(
		// 	'nValue' => '1',
		// 	'sName0' => '1',
		// ),
		// 2 => array(
		// 	'nValue' => '5',
		// 	'sName0' => '5',
		// ),
		// 3 => array(
		// 	'nValue' => '10',
		// 	'sName0' => '10',
		// ),
		// 4 => array(
		// 	'nValue' => '20',
		// 	'sName0' => '20',
		// ),
		// 5 => array(
		// 	'nValue' => '50',
		// 	'sName0' => '50',
		// ),
		6 => array(
			'nValue' => '100',
			'sName0' => '1百',
		),
		// 7 => array(
		// 	'nValue' => '500',
		// 	'sName0' => '500',
		// ),
		8 => array(
			'nValue' => '1000',
			'sName0' => '1千',
		),
		// 9 => array(
		// 	'nValue' => '5000',
		// 	'sName0' => '5000',
		// ),
		10 => array(
			'nValue' => '10000',
			'sName0' => '1萬',
		),
		// 11 => array(
		// 	'nValue' => '20000',
		// 	'sName0' => '20000',
		// ),
		12 => array(
			'nValue' => '50000',
			'sName0' => '5萬',
		),
		13 => array(
			'nValue' => '100000',
			'sName0' => '10萬',
		),
		// 14 => array(
		// 	'nValue' => '200000',
		// 	'sName0' => '200K',
		// ),
		// 15 => array(
		// 	'nValue' => '500000',
		// 	'sName0' => '500K',
		// ),
		// 16 => array(
		// 	'nValue' => '1000000',
		// 	'sName0' => '1000K',
		// ),
	);
	$aCoinBanker = $aDefineCoinBanker;
	$aCoinBanker[count($aCoinBanker)-1]['sName0'] = FULLAMOUNT;

	$aSetArrayPreFetchImage = array();
	foreach($aCoinBanker as $LPnId => $LPaChip)
	{
		$sChipName = 'empty';
		if($LPaChip['nValue']==-1)
		{
			$sChipName = 'empty2';
		}
		$aSetArrayPreFetchImage[] = 'images/resize/coin/'.$sChipName.'.png';
	}
	foreach($aSetCoinArray as $LPnId => $LPaChip)
	{
		$aSetArrayPreFetchImage[] = 'images/resize/coin/'.$LPnId.'.png';		
	}

	$aFakeMsg = array(
		array(
			'nWho'	=> 1, #1系統 2自己 3別人
			'sMsg'	=> '溫馨小提醒各位老闆 為了保持直播流暢清晰度直播影片連結請定時重新開啟喔',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '',
			'sImg'	=> '<div><img src="images/stopBet.jpg"></div>',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '👋停止下注👋
			<br><br>已停止下注，不再接受下注
			<br>__牌局資訊__
			<br>局號|2022-01-07-5520
			<br>__下注有效玩家__
			<br>【A初】順序如下
			<br>[小贏贏]下注 A 金額|694
			<br>[瑜]下注 A 金額|20,000
			<br>[韓]下注 A 金額|19,929
			<br>[龍]下注 A 金額|23,000
			<br>[台灣賭神戴子郎]下注 A 金額|906
			<br>[愉瑜不悅]下注 A 金額|60,000
			<br>[春風]下注 A 金額|10,000
			<br>[偷天神探]下注 A 金額|6,466
			<br>[虎虎生風]下注 A 金額|5,000
			<br><br>【B川】順序如下
			<br>[順]下注 B 金額|1,000
			<br><br>【C尾】順序如下
			<br>[666]下注 C 金額|5,000
			<br>[林大量]下注 C 金額|150,000
			<br>[龍]下注 C 金額|23,000
			<br>[國粹手]下注 C 金額|3,000
			<br>[咬咬咬]下注 C 金額|10,765
			<br>[齊]下注 C 金額|500
			<br>[自]下注 C 金額|145,850
			<br>[??]下注 C 金額|63,226
			<br>[💰來💰]下注 C 金額|6,000
			<br>[聖]下注 C 金額|200
			<br>[Han]下注 C 金額|2,586
			<br><br>__下注總表__
			<br>A下注總金額|588,880
			<br>B下注總金額|1,000
			<br>C下注總金額|441,492
			<br>全場下注總金額|1,031,372
			<br>押注|35
			<br><br>
			<br>🌹設定打莊金額🌹
			<br>請莊家【李大頭】設定打莊金額
			<br>額度範圍:
			<br>20,000~500,000',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '李大頭',
			'sMsg'	=> '3豆子用力搖兩下 莊閒2',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '開始配莊',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '🌟開放配莊🌟
						<br>莊家額度【30000】設定完成
						<br><br>剩餘額度
						<br>【1,001,372】，請老闆開始配莊
						<br>最多20位配莊 ▸ 配莊額度最低【10,000】最高【150,000】',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '騰',
			'sMsg'	=> 'P20000',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '嘟嘟巴拉紮愣扎盟梭哈',
			'sMsg'	=> 'P10000',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '猛虎不夜眠',
			'sMsg'	=> 'P55555',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '騰(20,000)
						<br>配莊成功 ✅
						<br>下注剩餘額度:981,372',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '💰💰💰',
			'sMsg'	=> 'P80000',
		),
		array(
			'nWho'	=> 2,
			'sMsg'	=> 'P18888888',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> 'YC元相開發有限公司',
			'sMsg'	=> 'P16666',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '??小凱??(10,000)
			<br>配莊成功 ✅
			<br>下注剩餘額度:971,372',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '💰💰💰(80,000)
			<br>配莊成功 ✅
			<br>下注剩餘額度:835,817',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '俊鳴',
			'sMsg'	=> 'P10000',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '八寶',
			'sMsg'	=> 'P50000',
		),
		array(
			'nWho'	=> 2,
			'sMsg'	=> 'P16816816816',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> 'YC元相開發有限公司(16,666)
			<br>配莊成功 ✅
			<br>下注剩餘額度:819,151',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '成吉思汗(50,000)
			<br>配莊成功 ✅
			<br>下注剩餘額度:769,151',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '俊鳴(10,000)
			<br>配莊成功 ✅
			<br>下注剩餘額度:759,151',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '秦',
			'sMsg'	=> 'P10000',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '阿wei',
			'sMsg'	=> 'P12888',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '阿wei(12,888)
			<br>配莊成功 ✅
			<br>下注剩餘額度:625,152',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '逸凱',
			'sMsg'	=> '排隊',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '逸凱
			<br>排隊成功 ✅',
		),
		array(
			'nWho'	=> 3,
			'sName'	=> '王大拿',
			'sMsg'	=> 'P125152',
		),
		array(
			'nWho'	=> 2,
			'sMsg'	=> 'P131416813141681311681341681314168',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '王大拿(125,152)
			<br>配莊成功 ✅
			<br>下注剩餘額度:500,000',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '停配',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '🌹莊家資訊🌹
			<br>__莊家__
			<br>李大頭配額|30,000
			<br>__配莊__
			<br>配莊1:騰 額度|20,000
			<br>配莊2:??小凱?? 額度|10,000
			<br>配莊3:猛虎不夜眠 額度|55,555
			<br>配莊4:💰💰💰 額度|80,000
			<br>配莊5:YC元相開發有限公司 額度|16,666
			<br>配莊6:成吉思汗 額度|50,000
			<br>配莊7:俊鳴 額度|10,000
			<br>配莊8:Lin 額度|10,000
			<br>配莊9:阿平 額度|111,111
			<br>配莊10:阿wei 額度|12,888
			<br>配莊11:王大拿 額度|125,152
			<br>配莊總額|501,372
			<br>上莊總配額|531,372',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '結算',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '🌟最新戰績🌟
			<br>局號|2022-01-07-5520
			<br>__莊配輸贏__
			<br>莊家【李大頭】(609233)|-30,000
			<br>配莊1【??小凱??】(71354)|-8,227
			<br>配莊2【猛虎不夜眠】(2005981)|-45,707
			<br>配莊3【💰💰💰】(362682)|-65,818
			<br>配莊4【YC元相開發有限公司】(444894)|-13,712
			<br>配莊5【成吉思汗】(387750)|-41,136
			<br>配莊6【俊鳴】(111639)|-8,227
			<br>配莊7【Lin】(159644)|-8,227
			<br>配莊8【阿平】(52795)|-91,414
			<br>配莊9【阿wei】(59590)|-10,600
			<br>配莊10【王大拿】(454955)|-102,966
			<br>輸贏總和|-442492
			<br><br>__玩家輸贏__
			<br>____________
			<br>【A 初 和】
			<br>[小贏贏] | 00
			<br>[瑜] | 00
			<br>[韓] | 00
			<br>[龍] | 00
			<br>[台灣賭神戴子郎] | 00
			<br>[愉瑜不悅] | 00
			<br>[春風] | 00
			<br>[偷天神探] | 00
			<br>[虎虎生風] | 00
			<br>[字昂] | 00
			<br>[法國賭神，皮亞卡宋] | 00
			<br>[畇畇] | 00
			<br>[逆流而上] | 00
			<br>[賓果] | 00
			<br>[林大量] | 00
			<br>[藍] | 00
			<br>[喵] | 00
			<br>[寶] | 00
			<br>[天] | 00
			<br>[順] | 00
			<br>[阿文他老母的老母???♀????♀????♀?] | 00
			<br><br>____________
			<br>【B 川 勝】
			<br>[順]|+950
			<br><br>____________
			<br>【C 尾 勝】
			<br>[666]|+4,751
			<br>[林大量]|+142,500
			<br>[龍]|+21,850
			<br>[國粹手]|+2,850
			<br>[咬咬咬]|+10,228
			<br>[齊]|+475
			<br>[自]|+138,558
			<br>[??]|+60,064
			<br>[💰來💰]|+5,700
			<br>[聖]|+190
			<br>[Han]|+2,457
			<br>[順]|+9,500
			<br>[字昂]|+20,298
			<br>__輸贏總表__
			<br>【A 初】 |00
			<br>【B 川】 |950
			<br>【C 尾】 |419,421
			<br>【輸贏總和】 |420,371
			<br>押注 |35
			<br>上莊費用
			<br>莊|-300
			<br>配莊1|-200
			<br>配莊2|-100
			<br>配莊3|-558
			<br>配莊4|-800
			<br>配莊5|-167
			<br>配莊6|-500
			<br>配莊7|-100
			<br>配莊8|-100
			<br>配莊9|-1111
			<br>配莊10|-129
			<br>配莊11|-1252',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '排莊列表',
		),
		array(
			'nWho'	=> 1,
			'sMsg'	=> '👑排莊列表👑
			<br>1.[李大頭](609,233)
			<br>2.[騰](4,361)
			<br>3.[天緯+](470,434)
			<br>4.[王大拿](454,955)
			<br>5.[字昂](191,665)
			<br>6.[俊鳴](111,639)
			<br>7.[淦天雷](200,663)
			<br>8.[阿府](147,643)
			<br>9.[成吉思汗](387,750)
			<br>10.[阿平](52,795)
			<br>11.[林大量](993,329)
			<br>12.[威融](1,533,291)
			<br>13.[💰💰💰](362,682)
			<br>14.[小古](518,430)
			<br>15.[瑜](238,336)
			<br>16.[逸凱](780,430)',
		),
		array(
			'nWho'	=> 1,
			'sImg'	=> '<div class="JqMenuBtn" data-btn="bet"><img src="images/startBet.jpg"></div>',
			'sMsg'	=> '',
		),
	);

	$aJumpMsg['msg0Box'] = $aJumpMsg['0'];
	$aJumpMsg['msg9Box'] = $aJumpMsg['0'];

	$aJumpMsg['msg0Box']['sTitle'] = RIGHTMSG;
	// $aJumpMsg['msg0Box']['sTitle'] = '正確訊息';
	$aJumpMsg['msg0Box']['sMsg'] = '?';
	$aJumpMsg['msg0Box']['aButton']['0']['sClass'] = 'JqClose JqMsg0Box';
	$aJumpMsg['msg0Box']['aButton']['0']['sText'] = CONFIRM;
	// $aJumpMsg['msg0Box']['aButton']['0']['sText'] = '確認';
	$aJumpMsg['msg0Box']['sShow'] = 0;

	$aJumpMsg['msg9Box']['sTitle'] = NOTICE;
	// $aJumpMsg['msg9Box']['sTitle'] = '注意';
	$aJumpMsg['msg9Box']['sArticle'] = '?';
	$aJumpMsg['msg9Box']['sShow'] = 0;
	$aJumpMsg['msg9Box']['aButton']['0']['sClass'] = 'JqClose JqMsg9Box JqTestClean';
	$aJumpMsg['msg9Box']['aButton']['0']['sText'] = CONFIRM;
	// $aJumpMsg['msg9Box']['aButton']['0']['sText'] = '確認';
	// if(empty($aUser['nTest']))
	// {
	// 	unset($aJumpMsg['msg9Box']['aButton']);
	// }
?>