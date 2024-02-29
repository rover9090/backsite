<?php
	#require
	// require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/index.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare
	$aRooms = array();
	$aSampleBet = array(
		'BW' => 0,
		'PW' => 0,
		'BP' => 0,
		'PP' => 0,
		'SIX' => 0,
		'DRAW' => 0,
	);
	$aOdds = array();
	$aMembers = array();
	/**
	 * 回傳陣列 JSON
	 * @var Int nError
	 * 	回傳狀態值
	 * 	0 => 正常 其餘待補
	 * @var String sMsg
	 * 	回傳訊息
	 * @var Array aData
	 * 	回傳陣列
	 * @var Int nAlertType
	 * 	回傳訊息提示類型
	 * 	0 => 不需提示框
	 * @var String sUrl
	 * 	回傳後導頁檔案
	 */
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);
	#宣告結束

	#程式邏輯區

	if($aJWT['a'] == 'RETURN')
	{		
	}

	/**
	 * 每次ajax都回傳最新資料就好
	 * 初始資料在index解決
	 */
	if($aJWT['a'] == 'LOBBY' )
	{
		// $sSQL = '	SELECT 	nums.nId,
		// 				nums.sNo,
		// 				nums.nGame,
		// 				nums.sResult,
		// 				nums.nEndTime,
		// 				nums.nStatus,
		// 				nums.nRound,
		// 				dealer.sName
		// 		FROM  	'.CLIENT_GAMES_NUMS.' as nums, '.CLIENT_GAMES_DEALER.' as dealer
		// 		WHERE 	nums.nDealer = dealer.nId
		// 		AND		nums.nStatus = 0
		// 		AND		nums.nUse = 1';

		## 本來是找nStatus < 3

		$sJsonRooms = file_get_contents(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/File/RoomJson.txt');
		$aRoomsTxt = json_decode($sJsonRooms,true);

		// $sSQL = '	SELECT 	_cookie.nUid,
		// 				_cookie.sAccount,
		// 				_data.nRoom
		// 		FROM  	'.CLIENT_USER_COOKIE.' _cookie, '.CLIENT_USER_DATA.' _data
		// 		WHERE 	_data.nId = _cookie.nUid
		// 		AND		_data.nRoom = :nGame';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		// sql_query($Result);
		// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		// {
		// 	$aMembers[$aRows['nUid']] = $aRows;
		// }
		$aMembers = isset($aRoomsTxt[$nGame]['aMemberReal'])?$aRoomsTxt[$nGame]['aMemberReal']:array();

		// $sSQL = '	SELECT 	sCode,
		// 				nOdds,
		// 				nOdds1,
		// 				nMaxBet,
		// 				nMinBet,
		// 				nRoundMaxBet
		// 		FROM 		'.END_METHOD_SETTING.'
		// 		WHERE 	nGame = :nGame';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
		// sql_query($Result);
		// while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		// {
		// 	if($aRows['sCode'] == 'L' || $aRows['sCode'] == 'S') continue;
		// 	$aRows['nRoundMaxBet'] = (float)$aRows['nRoundMaxBet'];
		// 	$aOdds[$aRows['sCode']] = $aRows;
		// }

		// $sSQL = '	SELECT 	nId,nRoom,nGame,nSec,nSec1,nShuffling,nDealer
		// 		FROM  	'.END_GAMES_SETTING.'
		// 		WHERE 	sLang = :sLang
		// 		AND		nGame = :nGame
		// 		AND		nOnline = 1
		// 		ORDER BY sName0 ASC';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		// $Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		// sql_query($Result);
		// while($LPaGames = $Result->fetch(PDO::FETCH_ASSOC))
		// {
		// 	$sSQL = '	SELECT 	nId,
		// 					sNo,
		// 					nGame,
		// 					sResult,
		// 					nStatus,
		// 					nDealer,
		// 					nRound,
		// 					nStartTime,
		// 					nEndTime,
		// 					sNums,
		// 					nBetEndTime,
		// 					nFinalRound,
		// 					nDayLast,
		// 					nCancel,
		// 					nPauseTime1,
		// 					nPauseTime0,
		// 					nPreStartTime
		// 			FROM  	'.CLIENT_GAMES_NUMS.'
		// 			WHERE 	nGame = :nGame
		// 			AND		nPreStartTime <= :NOWTIME
		// 			ORDER BY nPreStartTime DESC
		// 			LIMIT 1';# 必須要連異常狀態都抓取我才能判斷現在是哪種異常顯示
		// 			#AND		nUse = 1
		// 			/*
		// 			nGame = :nGame
		// 			AND		nStartTime < :NOWTIME
		// 			ORDER BY nStartTime DESC					
		// 			*/
		// 	$Result2 = $oPdo->prepare($sSQL);
		// 	$Result2->bindValue(':nGame', $LPaGames['nGame'], PDO::PARAM_INT);
		// 	$Result2->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
		// 	sql_query($Result2);
		// 	while($aRows = $Result2->fetch(PDO::FETCH_ASSOC))
		// 	{
		// 		$aRooms[$aRows['nGame']] = $aRows;
		// 		$aRooms[$aRows['nGame']]['aResult'] = json_decode($aRows['sResult'],true);
		// 		$aRooms[$aRows['nGame']]['nSeconds'] = -1;
		// 		$aRooms[$aRows['nGame']]['nSeconds1'] = 0;
		// 		if($aRows['nStatus'] < 10 && $aRows['nStartTime'] <= NOWTIME)
		// 		{
		// 			$aRooms[$aRows['nGame']]['nSeconds'] = ($aRows['nStartTime']+$LPaGames['nSec']) - NOWTIME;
		// 		}
		// 		$aRooms[$aRows['nGame']]['nSeconds1'] = $aRows['nPreStartTime'] + $LPaGames['nSec1'] - NOWTIME; # 微調一秒鐘
				
		// 		$aRooms[$aRows['nGame']]['aNums'] = $aRows['sNums']==''?array():json_decode($aRows['sNums'],true);
		// 	}

		// 	$aRooms[$LPaGames['nGame']]['aSetting'] = $LPaGames;
		// 	$aRooms[$LPaGames['nGame']]['aSetting']['sNowDate'] = NOWDATE;
		// 	// $aRooms[$LPaGames['nGame']]['aMembers'] = $aMembers;
		// 	$aRooms[$LPaGames['nGame']]['nMembersCount'] = count($aMembers);
		// }

		$aRooms[$nGame] = $aRoomsTxt[$nGame]['aLastest'];
		$aRooms[$nGame]['aSetting'] = $aRoomsTxt[$nGame]['aSetting'];
		$aRooms[$nGame]['aSetting']['sNowDate'] = NOWDATE;
		$aRooms[$nGame]['nMembersCount'] = count($aMembers);

		if($aRooms === array())
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = '找不到期數';
		}
		else
		{	
			$aReturn['nError'] = 0;
			$aReturn['aData'] = $aRooms;
		}
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	exit;
	#輸出結束
?>