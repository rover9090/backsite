<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ctrl.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'plugins/js/end_games/end_games_ctrl.js',
		'plugins/js/end_games/TcPlayer-2.4.1.js',
		'plugins/js/end_games/stream.js',
	);
	#js結束

	#參數接收區
	$nLobby	= filter_input_int('nLobby', 		INPUT_REQUEST, '1');
	$nRoom	= filter_input_int('nRoom', 		INPUT_REQUEST, '1');
	$nGame	= filter_input_int('nGame', 		INPUT_REQUEST, '1001');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sBankerStr'=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0_ajax0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_ctrl_0.php',
	);
	#url結束

	#參數宣告區
	$aData = array(
		'sNo'			=> '',
		'nStatus'		=> 3,
		'nNextStatus'	=> 0,
	);
	$aValue = array(
		'a'	=> 'NEXT1',
		't'	=> NOWTIME,
	);
	$sNext1JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEXT2',
		't'	=> NOWTIME,
	);
	$sNext2JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEXT3',
		't'	=> NOWTIME,
	);
	$sNext3JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEXT3_5',
		't'	=> NOWTIME,
	);
	$sNext3_5JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'INS0',
		't'	=> NOWTIME,
	);
	$sIns0JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEXT99',
		't'	=> NOWTIME,
	);
	$sNext99JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'RESULT0',
		't'	=> NOWTIME,
	);
	$sResult0JWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'RESULT0FIFTH',
		't'	=> NOWTIME,
	);
	$sResult0FifthJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEWDEALER',
		't'	=> NOWTIME,
	);
	$sNewDealerJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'BANKERLEAST',
		't'	=> NOWTIME,
	);
	$sBankerLeastJWT = sys_jwt_encode($aValue);# 用最低額入莊
	$aValue = array(
		'a'	=> 'SHOWBANKERS',
		't'	=> NOWTIME,
	);
	$sShowBankersJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'NEWBANKER',
		't'	=> NOWTIME,
	);
	$sNewBankerJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'BANKERCONTINUE',
		't'	=> NOWTIME,
	);
	$sBankerContinueJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'BANKERSTR',
		't'	=> NOWTIME,
		'nGame' => $nGame,
	);
	$sBankerStrJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'QUICKMSG',
		't'	=> NOWTIME,
	);
	$sQuickMsgJWT = sys_jwt_encode($aValue);
	$nCount = 0;
	$aStatus = array(
		'0' => array('sText'=>'新局/莊家限注','sClass'=>''),
		'1' => array('sText'=>'開始下注','sClass'=>''),
		'2' => array('sText'=>'開始集資','sClass'=>''),
		'3' => array('sText'=>'結束集資','sClass'=>''),
		'3_5' => array('sText'=>'待結算','sClass'=>''),
		'4' => array('sText'=>'已結算','sClass'=>''),
		'91' => array('sText'=>'結算中','sClass'=>''),
		'99' => array('sText'=>'取消當局/打和返款','sClass'=>''),
	);
	$aNextStatus = array(
		'0' => array('sText'=>'限注完成，開始下注','sClass'=>''),
		'1' => array('sText'=>'下注結束，進入集資','sClass'=>''),
		'2' => array('sText'=>'集資結束，進入開牌','sClass'=>''),
		'3' => array('sText'=>'開牌結束，進入結算','sClass'=>''),
		'3_5' => array('sText'=>'結算中，新局/莊家限注','sClass'=>''),
		'4' => array('sText'=>'已結算，新局/莊家限注','sClass'=>''),
		'91' => array('sText'=>'結算中，新局/莊家限注','sClass'=>''),
		'99' => array('sText'=>'已取消，新局/莊家限注','sClass'=>''),
	);
	$aNums = array();
	$aBankers = array();
	$sBankerStr = '排莊列表';
	$aQuickMsg = array();
	$sQuickDom = '';

	$aGameSetting = array();
	$sBankerContinue = '';
	$aPrevBanker = array(
		array(
			'sName0' => '',
		),
		array(
			'sName0' => '',
		),
	);
	$aBanker = array(
		'sName0' => '',
	);
	$aLimit = array(
		'' => '不限',
		'A' => '限A',
		'B' => '限B',
		'C' => '限C',
	);
	$aResultText = array(
		'Z0' => '莊家沒中彩',
		'Z1' => '莊家吃彩',
		'A0' => 'A輸',
		'A1' => 'A贏',
		'A2' => 'A和',
		'B0' => 'B輸',
		'B1' => 'B贏',
		'B2' => 'B和',
		'C0' => 'C輸',
		'C1' => 'C贏',
		'C2' => 'C和',
	);

	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit JqEnterClick';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel JqEscapeClick';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	#宣告結束

	#程式邏輯區
	$sStreamUrl = 'vt.abp77.com/live/tianjiu01';
	$aGameSetting = GameSet_Class::fnCallSetting();

	$sSQL = '	SELECT	sAccount,
					sName0
			FROM		'. END_MANAGER_DATA .'
			WHERE		nId = :nId
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $aGameSetting[$nGame]['sDealer'], PDO::PARAM_INT);
	sql_query($Result);
	$aDealer = $Result->fetch(PDO::FETCH_ASSOC);
	if($aDealer == false)
	{
		$aDealer = array(
			'sName0' => '',
		);
	}

	$sSQL = '	SELECT	nId,
					nGame,
					sNo,
					nStatus,
					sNums,
					sResult,
					nRound,
					nUse,
					sLimit,
					nBankerEndTime,
					sPreStartTime,
					sStartTime,
					sBetEndTime,
					sBankerEndTime,
					sEndTime,
					sEndTime1
			FROM		'. CLIENT_GAMES_NUMS .'
			WHERE		nGame = :nGame
			ORDER BY	nId DESC
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);

	if($aData === false)
	{
		$aData = array(
			'sNo'			=> 0,
			'nStatus'		=> 3,
			'nNextStatus'	=> 0,
			'sLimit'		=> '',
		);
		$sJWT = $sIns0JWT;
	}
	else
	{
		$aData['sImage'] = '';
		$aData['nShot'] = 0;
		
		$sSQL = '	SELECT	nId,
						nKid,
						sTable,
						sFile,
						nCreateTime,
						nType0
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		sTable LIKE \''. CLIENT_GAMES_NUMS .'\'
				AND		nKid = :nKid
				AND		nType0 = 0
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nKid', $aData['nId'], PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData['sImage'] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'];			
			$aData['sImage'] = '<img src="'.$aData['sImage'].'" alt="" title=":title:" style="max-width:40%">';
		}

		switch ($aData['nStatus']) {
			case '0':
				$aData['nNextStatus'] = 1;
				$sJWT = $sNext1JWT;
				break;
			case '1':
				$aData['nNextStatus'] = 2;
				$sJWT = $sNext2JWT;
				# 詢問是否連莊
				break;
			case '2':
				$aData['nNextStatus'] = 3;
				$sJWT = $sNext3JWT;
				break;
			case '3':
				if($aData['sEndTime'] === '')
				{
					$aData['nNextStatus'] = '3_5';
					$sJWT = $sNext3_5JWT;
					$aData['sImage'] .= '<input type="file"  id="JqFile" name="aFile[]" onclick="event.preventDefault();">';
					$aData['nShot'] = 1;
				}
				else
				{
					$aData['nNextStatus'] = 0;
					$sJWT = $sIns0JWT;
					$aData['nStatus'] = '3_5';
				}
				break;
			case '4':
				$aData['nNextStatus'] = 0;
				$sJWT = $sIns0JWT;
				break;
			case '99':
				$aData['nNextStatus'] = 0;
				$sJWT = $sIns0JWT;
				break;
			default :
				$aData['nNextStatus'] = 0;
				$sJWT = $sIns0JWT;
				break;
		}
		$aData['aNums'] = json_decode($aData['sNums'],true);
		$aResultCheck['A'.$aData['aNums']['A']] = 'checked';
		$aResultCheck['B'.$aData['aNums']['B']] = 'checked';
		$aResultCheck['C'.$aData['aNums']['C']] = 'checked';
	}
	
	$sSQL = '	SELECT	nId,
					nUid,
					nContinue,
					nBuyContinue,
					(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,
					(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
			FROM		'. CLIENT_GAMES_BANKER_ORDER .'
			WHERE		nGame = :nGame
			AND		nStatus = 1
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	$aBanker = $Result->fetch(PDO::FETCH_ASSOC);
	if($aBanker !== false)
	{
		$aBanker['nMoneyNow'] = (float)$aBanker['nMoneyNow'];
		$aBanker['sName0'] = $aBanker['sName0'].'('.$aBanker['nMoneyNow'].')(第'.$aBanker['nContinue'].'墩)';
		if($aBanker['nBuyContinue'] > 0)
		{
			$aBanker['sName0'] .= '-買官';
		}
	}
	else
	{
		$aBanker = array(
			'sName0' => '',
		);
	}

	$nI = 1;
	$sSQL = '	SELECT 	nId,nUid,nLid,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
			FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
			WHERE 	nGame = :nGame
			AND		nStatus = 0
			ORDER BY nCreateTime ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nMoneyNow'] = (float)$aRows['nMoneyNow'];
		$aBankerList[$nI] = $aRows;
		if($nI === 1)
		{
			$sBankerStr .= '<br><span class="FontGreen">*'.$nI.'.['.$aRows['sName0'].']('.$aRows['nMoneyNow'].'</span>)';
		}
		else
		{
			$sBankerStr .= '<br>'.$nI.'.['.$aRows['sName0'].']('.$aRows['nMoneyNow'].')';
		}
		$nI++;
	}
	$aData['sBankerStr'] = $sBankerStr;
	if(!isset($aBankerList[1])) $aBankerList[1] = array('sName0'=>'');
	
	$sSQL = '	SELECT	nId,
					sNo,
					nUid,
					nStatus,
					nContinue,
					(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as sName0,
					(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as nMoneyNow
			FROM		'. CLIENT_GAMES_BANKER .'
			WHERE		nGame = :nGame
			AND		sNo != :sNo
			AND		nType0 = 0
			ORDER BY nUpdateTime DESC
			LIMIT		1';# status=
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	$Result->bindValue(':sNo', $aData['sNo'], PDO::PARAM_INT);
	sql_query($Result);
	$aPrevBanker[0] = $Result->fetch(PDO::FETCH_ASSOC);# 上期已過帳莊家
	$aPrevBanker[0]['sName0Money'] = $aPrevBanker[0]['sName0'].'('.$aPrevBanker[0]['nMoneyNow'].')(第'.$aPrevBanker[0]['nContinue'].'墩)';

	# 前三期莊家
	$aJackPotUser = array();
	$sSQL = '	SELECT 	nId,sNo,nUid,nLid,nType0,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as sName0,(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as nMoneyNow
			FROM 		'. CLIENT_GAMES_BANKER .'
			WHERE 	nGame = :nGame
			AND		nStatus = 1
			AND		nDone = 1
			AND		sNo != \'\'
			AND		nType0 = 0
			ORDER BY CAST(sNo AS unsigned) DESC
			LIMIT 3';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
	sql_query($Result);
	$aJackPotUser[0] = $Result->fetch(PDO::FETCH_ASSOC);
	if($aJackPotUser[0] === false) $aJackPotUser[0] = array('nUid'=>0,'sNo'=>0,'sName0'=>'');
	$aJackPotUser[1] = $Result->fetch(PDO::FETCH_ASSOC);
	if($aJackPotUser[1] === false) $aJackPotUser[1] = array('nUid'=>0,'sNo'=>0,'sName0'=>'');
	$aJackPotUser[2] = $Result->fetch(PDO::FETCH_ASSOC);
	if($aJackPotUser[2] === false) $aJackPotUser[2] = array('nUid'=>0,'sNo'=>0,'sName0'=>'');

	$sSQL = '	SELECT	nId,
					sMessage
			FROM		'. CLIENT_QUICKMSG .'
			WHERE		nOnline = 1
			ORDER BY nId ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $aGameSetting[$nGame]['sDealer'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		// $aRows['sName0'] = $aRows['sName0']===''?$aRows['nId']:$aRows['sName0'];
		// $sQuickDom .= '<div><label><input type="radio" value="'.$aRows['nId'].'" name="nId"><span>'.$aRows['sName0'].':'.$aRows['sMessage'].'</span></label></div>';

		$aQuickMsg[$aRows['nId']] = $aRows;
		$aJumpMsg['quickmsg'.$aRows['nId']] = $aJumpMsg['0'];
		$aJumpMsg['quickmsg'.$aRows['nId']]['sTitle'] = '快捷訊息';
		if($aRows['sMessage'] != '')
		{
			$aJumpMsg['quickmsg'.$aRows['nId']]['sMsg'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="quickmsg'.$aRows['nId'].'">
				<input type="hidden" name="sJWT" value="'.$sQuickMsgJWT.'">
				<input type="hidden" name="nGame" value="'.$nGame.'">
				<input type="hidden" name="nId"  value="'.$aRows['nId'].'" >
				<div>
					<span>送出訊息：'.$aRows['sMessage'].'</span>
				</div>
			</form>';
		}
		else
		{
			$aJumpMsg['quickmsg'.$aRows['nId']]['sMsg'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="quickmsg'.$aRows['nId'].'">
				<input type="hidden" name="sJWT" value="'.$sQuickMsgJWT.'">
				<input type="hidden" name="nGame" value="'.$nGame.'">
				<input type="hidden" name="nId"  value="'.$aRows['nId'].'" >
				<div>
					<span>尚未設定快捷訊息</span>
				</div>
			</form>';			
		}
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aJumpMsg['2'] = $aJumpMsg['0'];
	$aJumpMsg['limit'] = $aJumpMsg['0'];
	$aJumpMsg['limit']['sTitle'] = '不限注';
	$aJumpMsg['limit']['aButton']['0']['sClass'] = 'JqClose JqEnterClick JqLimit';
	$aJumpMsg['limitB'] = $aJumpMsg['0'];
	$aJumpMsg['limitA'] = $aJumpMsg['0'];
	$aJumpMsg['limitA']['sTitle'] = '確認限注A';
	$aJumpMsg['limitA']['aButton']['0']['sClass'] = 'JqClose JqEnterClick JqLimitA';
	$aJumpMsg['limitB'] = $aJumpMsg['0'];
	$aJumpMsg['limitB']['sTitle'] = '確認限注B';
	$aJumpMsg['limitB']['aButton']['0']['sClass'] = 'JqClose JqEnterClick JqLimitB';
	$aJumpMsg['limitC'] = $aJumpMsg['0'];
	$aJumpMsg['limitC']['sTitle'] = '確認限注C';
	$aJumpMsg['limitC']['aButton']['0']['sClass'] = 'JqClose JqEnterClick JqLimitC';
	$aJumpMsg['2']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="2">
			<input type="hidden" name="sJWT" value="'.$sNewDealerJWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			<div>'.aGAMECTRL['PUTADMID'].'</div>
			<div class="Ipt"><input inputmode="Decimal" type="number" name="sDealer"></div>
			</form>';
	$aJumpMsg['showbanker'] = $aJumpMsg['0'];
	$aJumpMsg['showbanker']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="showbanker">
			<input type="hidden" name="sJWT" value="'.$sShowBankersJWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			</form>';
	$aJumpMsg['newbanker'] = $aJumpMsg['0'];
	$aJumpMsg['newbanker']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="newbanker">
			<input type="hidden" name="sJWT" value="'.$sNewBankerJWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			</form>';
	$aJumpMsg['99'] = $aJumpMsg['0'];
	$aJumpMsg['99']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="99">
			<input type="hidden" name="sJWT" value="'.$sNext99JWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			</form>';
	// $aJumpMsg['shotvideo'] = $aJumpMsg['0'];
	// $aJumpMsg['shotvideo']['sArticle'] = '
	// 		<form  action="'.$aUrl['sAct'].'" method="post" data-form="shotvideo" enctype="multipart/form-data">
	// 		<input type="hidden" name="sJWT" value="'.$sShotVideoJWT.'">
	// 		<input type="file"  id="JqFile" name="aFile[]">
	// 		<input type="hidden" name="nGame" value="'.$nGame.'">
	// 		<div id="JqPreviewDiv">
	// 		</div>
	// 		</form>';
	$aJumpMsg['result0'] = $aJumpMsg['0'];
	$aJumpMsg['result0']['sTitle'] = '莊家吃彩';
	$aJumpMsg['result0']['sMsg'] = '
	<form  action="'.$aUrl['sAct'].'" method="post" data-form="result0">
	<input type="hidden" name="sJWT" value="'.$sResult0JWT.'">
	<input type="hidden" name="nGame" value="'.$nGame.'">
	<div class="Sel">
		<select name="nBankerId">
			<option value="'.$aJackPotUser[0]['nId'].'">'.$aJackPotUser[0]['sNo'].':'.$aJackPotUser[0]['sName0'].'</option>
			<option value="'.$aJackPotUser[1]['nId'].'">'.$aJackPotUser[1]['sNo'].':'.$aJackPotUser[1]['sName0'].'</option>
			<option value="'.$aJackPotUser[2]['nId'].'">'.$aJackPotUser[2]['sNo'].':'.$aJackPotUser[2]['sName0'].'</option>
		</select>
	</div>
	</form>';
	$aJumpMsg['result0_fifth'] = $aJumpMsg['0'];
	$aJumpMsg['result0_fifth']['sTitle'] = '莊家吃1/5彩';
	$aJumpMsg['result0_fifth']['sMsg'] = '
	<form  action="'.$aUrl['sAct'].'" method="post" data-form="result0_fifth">
	<input type="hidden" name="sJWT" value="'.$sResult0FifthJWT.'">
	<input type="hidden" name="nGame" value="'.$nGame.'">
	<div class="Sel">
		<select name="nBankerId">
			<option value="'.$aJackPotUser[0]['nId'].'">'.$aJackPotUser[0]['sNo'].':'.$aJackPotUser[0]['sName0'].'</option>
			<option value="'.$aJackPotUser[1]['nId'].'">'.$aJackPotUser[1]['sNo'].':'.$aJackPotUser[1]['sName0'].'</option>
			<option value="'.$aJackPotUser[2]['nId'].'">'.$aJackPotUser[2]['sNo'].':'.$aJackPotUser[2]['sName0'].'</option>
		</select>
	</div>
	</form>';
	$aJumpMsg['bankerleast'] = $aJumpMsg['0'];
	$aJumpMsg['bankerleast']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="bankerleast">
			<input type="hidden" name="sJWT" value="'.$sBankerLeastJWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			</form>';
	$aJumpMsg['bankercontinue'] = $aJumpMsg['0'];
	$aJumpMsg['bankercontinue']['sArticle'] = '
			<form  action="'.$aUrl['sAct'].'" method="post" data-form="bankercontinue">
			<input type="hidden" name="sJWT" value="'.$sBankerContinueJWT.'">
			<input type="hidden" name="nGame" value="'.$nGame.'">
			</form>';
	// $aJumpMsg['quickmsg'] = $aJumpMsg['0'];
	// $aJumpMsg['quickmsg']['sTitle'] = '快捷訊息';
	// $aJumpMsg['quickmsg']['sMsg'] = '
	// <form  action="'.$aUrl['sAct'].'" method="post" data-form="quickmsg">
	// <input type="hidden" name="sJWT" value="'.$sQuickMsgJWT.'">
	// <input type="hidden" name="nGame" value="'.$nGame.'">
	// '.$sQuickDom.'
	// </form>';
	// $aJumpMsg['0']['sMsg'] = CSUBMIT.'?';

	$aJumpMsg['0']['sArticle'] = '			
			<div id="JqPreviewDiv">
			</div>
			</form>';
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>