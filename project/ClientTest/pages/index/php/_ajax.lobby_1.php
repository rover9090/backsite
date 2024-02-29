<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1002);
	$nEndTime = filter_input_int('nEndTime', INPUT_POST, 0);
	$nNoId = filter_input_int('nNoId', INPUT_POST, 0);
	$nSettle = filter_input_int('nSettle', INPUT_POST, 0);
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');
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
		'aUser'		=> array(),
		'nNowTime'		=> NOWTIME,
		'nCookieRoom'	=> 0,
	);

	$aUrl = array(
		// 'sBet0'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_0.php']),
		// 'sBet1'		=> sys_web_encode($aMenuToNo['pages/bet/php/_bet_1.php']),
		// 'sIndex'		=> sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']),
	);
	// $aReturn['sUrl'] = $aUrl['sIndex'];
	$aSampleBet = array(
		'BW' => 0,
		'PW' => 0,
		'BP' => 0,
		'PP' => 0,
		'SIX' => 0,
		'DRAW' => 0,
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
		$sSQL = '	SELECT 	User_.nId,
						User_.sName0,
						User_.sAccount,
						User_.nStatus,
						User_.nIdentity,
						User_.nCreateTime,
						User_.sPicture,
						Money_.nMoney
				FROM 		'.CLIENT_USER_DATA.' User_
				JOIN		'.CLIENT_USER_MONEY.' Money_
				ON		User_.nId = Money_.nUid
				WHERE 	User_.sLineId = :sLineId
				AND		nOnline != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLineId', $sLineId, PDO::PARAM_STR);
		sql_query($Result);
		$aUser = $Result->fetch(PDO::FETCH_ASSOC);
		$aUser['nUid'] = $aUser['nId'];
		$aReturn['aUser'] = $aUser;

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

		# 好像應該改結構又覺得很麻煩
		# 考慮加入一個nPreStartTime
		$sSQL = '	SELECT 	nId,nGame,nSec,nSec1,nSec2,nSec3,nShuffling,sDealer,sOpenTime,sCloseTime,nMaintenance
				FROM  	'.END_GAMES_SETTING.'
				WHERE 	sLang = :sLang
				AND		nGame = :nGame
				AND		nOnline = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_STR);
		sql_query($Result);
		while($LPaGames = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$LPaGames['nCloseRoom'] = 0;

			if($nEndTime < NOWTIME && $nEndTime !== 0)
			{
				$sSQL = '	SELECT 	nId,
								sNo,
								nGame,
								sResult,
								nStatus,
								sDealer,
								nRound,
								nStartTime,
								nEndTime,
								sPreNums as sNums,
								nBetEndTime,
								nBankerEndTime,
								nFinalRound,
								nDayLast,
								nCancel,
								nPauseTime1,
								nPauseTime0,
								nPreStartTime
						FROM  	'.CLIENT_GAMES_NUMS2.'
						WHERE 	nId = :nNoId
						AND		nGame = :nGame
						AND		nBankerEndTime = :nEndTime
						LIMIT 1';# DB狀態只有0跟3  少nUse=1
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nGame', $LPaGames['nGame'], PDO::PARAM_INT);
				$Result2->bindValue(':nEndTime', $nEndTime, PDO::PARAM_INT);
				$Result2->bindValue(':nNoId', $nNoId, PDO::PARAM_INT);
				sql_query($Result2);
			}
			else
			{
				$sSQL = '	SELECT 	nId,
								sNo,
								nGame,
								sResult,
								nStatus,
								sDealer,
								nRound,
								nStartTime,
								nEndTime,
								sPreNums as sNums,
								nBetEndTime,
								nBankerEndTime,
								nFinalRound,
								nDayLast,
								nCancel,
								nPauseTime1,
								nPauseTime0,
								nPreStartTime
						FROM  	'.CLIENT_GAMES_NUMS2.'
						WHERE 	nGame = :nGame
						ORDER BY nId DESC
						LIMIT 1';# DB狀態只有0跟3  少nUse=1
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nGame', $LPaGames['nGame'], PDO::PARAM_INT);
				sql_query($Result2);
			}			
			/**
			 * 單純就倒數的角度來看其實沒有問題
			 * 但我會需要撈取已開獎的獎號
			 * A跟B的交界處我需要有A的獎號與B的狀態 
			 * 存入sPreNums表示前一期獎號 
			*/
			while($aRows = $Result2->fetch(PDO::FETCH_ASSOC))
			{
				$aRooms[$aRows['nGame']]['aLastest'] = $aRows;
				
				$aRooms[$LPaGames['nGame']]['aSetting'] = $LPaGames;
				$aRooms[$LPaGames['nGame']]['aSetting']['nShuffling'] = $LPaGames['nShuffling'];
				$aRooms[$LPaGames['nGame']]['aBetData'] = array();
				$aRooms[$LPaGames['nGame']]['aMemberReal'] = array();
				$aRooms[$LPaGames['nGame']]['aBetCount'] = $aSampleBet;
				$aRooms[$LPaGames['nGame']]['aBetMoney'] = $aSampleBet;
				$aRooms[$LPaGames['nGame']]['aNumsArray'] = array();

				$LPnOpenTime = strtotime($LPaGames['sOpenTime']);
				$LPnCloseTime = strtotime($LPaGames['sCloseTime']);
				if($LPnCloseTime < NOWTIME)
				{
					$LPnOpenTime += 86400;
					$LPnCloseTime += 86400;
				}
				if($LPnOpenTime > $LPnCloseTime)
				{
					$LPnOpenTime -= 86400;
				}
				$aRooms[$LPaGames['nGame']]['aSetting']['nOpenTime'] = $LPnOpenTime;
				$aRooms[$LPaGames['nGame']]['aSetting']['nCloseTime'] = $LPnCloseTime;
				if($LPnOpenTime > NOWTIME || $LPaGames['nMaintenance'] == 1)
				{
					$aRooms[$LPaGames['nGame']]['aSetting']['nCloseRoom'] = 1;
				}
				$aRooms[$LPaGames['nGame']]['aLastest']['nSeconds'] = -1;
				$aRooms[$LPaGames['nGame']]['aLastest']['nSeconds1'] = 0;

				$aRooms[$aRows['nGame']]['aResult'] = json_decode($aRows['sResult'],true);
				if($aRows['nStatus'] < 10 && $aRows['nStartTime'] <= NOWTIME)
				{
					$aRooms[$aRows['nGame']]['aLastest']['nSeconds'] = ($aRows['nBankerEndTime']) - NOWTIME;
				}
				if($aRows['nFinalRound'] > 0 || $aRows['nCancel'] > 0 )
				{
					# 最後一局不會有nPauseTime1 只有異動更新的nEndTIme
					$aRooms[$aRows['nGame']]['aLastest']['nSeconds1'] = $aRows['nEndTime'] - NOWTIME;
				}
				else
				{
					$aRooms[$aRows['nGame']]['aLastest']['nSeconds1'] = $aRows['nStartTime'] - NOWTIME;
				}
				
				$aRooms[$aRows['nGame']]['aNums'] = json_decode($aRows['sNums'],true);
			}

			$aRooms[$LPaGames['nGame']]['aSetting'] = $LPaGames;
			
			// if($nGame == $LPaGames['nGame'])
			// {
			// 	$aRooms[$LPaGames['nGame']]['aBetCount'] = $aSampleBet;
			// 	$aRooms[$LPaGames['nGame']]['aBetMoney'] = $aSampleBet;
	
			// 	if(isset($aRooms[$LPaGames['nGame']]['sNo']))
			// 	{
			// 		$sSQL = '	SELECT 	nId,
			// 						sNo,
			// 						sContent0,
			// 						nMoney0
			// 				FROM  	'.CLIENT_GAMES_DATA.'
			// 				WHERE 	sNo = :sNo
			// 				AND		nGame = :nGame';
			// 		$Result2 = $oPdo->prepare($sSQL);
			// 		$Result2->bindValue(':sNo', $aRooms[$LPaGames['nGame']]['sNo'], PDO::PARAM_INT);
			// 		$Result2->bindValue(':nGame', $LPaGames['nGame'], PDO::PARAM_INT);
			// 		sql_query($Result2);
			// 		while($aRows = $Result2->fetch(PDO::FETCH_ASSOC))
			// 		{
			// 			$aRooms[$LPaGames['nGame']]['aBetCount'][$aRows['sContent0']] ++;
			// 			$aRooms[$LPaGames['nGame']]['aBetMoney'][$aRows['sContent0']] += $aRows['nMoney0'];
			// 		}
			// 		foreach($aOdds as $LPsKey => $LPaOdds)
			// 		{
			// 			$aOdds[$LPsKey]['nRoundMaxBet'] -= $aRooms[$LPaGames['nGame']]['aBetMoney'][$LPsKey];
			// 		}
			// 		$aOdds['BW']['nRoundMaxBet'] += $aRooms[$LPaGames['nGame']]['aBetMoney']['PW'];
			// 		$aOdds['PW']['nRoundMaxBet'] += $aRooms[$LPaGames['nGame']]['aBetMoney']['BW'];

			// 	}
			// 	$aRooms[$LPaGames['nGame']]['aOdds'] = $aOdds;
			// }
		}
		if(isset($_COOKIE['nCookieRoom']))
		{
			$aReturn['nCookieRoom'] = $_COOKIE['nCookieRoom'];
		}

		
		# 
		// $result = file_get_contents(dirname(dirname(dirname(dirname(dirname(dirname(__file__)))))) .'/t_linegame06/File/RoomJson.txt');
		// trigger_error(dirname(dirname(dirname(dirname(dirname(dirname(__file__)))))) .'/t_linegame06/File/RoomJson.txt');
		// $sJsonRooms = $result;
		// $aRooms = json_decode($sJsonRooms,true);

		# 讀官方回合機器人
		// $ch = curl_init('https://adm.abp77.com/Bot/MsgJson'.$nGame.'.txt');
		// $ch = curl_init('https://demo801.dtap000s3.com/Project/t_linegame06/EndTest/Bot/MsgJson'.$nGame.'.txt');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
		// curl_setopt($ch, CURLOPT_TIMEOUT,10);

		// $result = curl_exec($ch);
		// curl_close($ch);
		$result = file_get_contents(dirname(dirname(dirname(dirname(dirname(dirname(__file__)))))) .'/t_linegame06/File/MsgJson'.$nGame.'.txt');
		// trigger_error(dirname(dirname(dirname(dirname(dirname(dirname(__file__)))))) .'/t_linegame06/File/MsgJson'.$nGame.'.txt');
		// $aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
		$sJsonSystem = $result;
		$aJsonSystem = json_decode($sJsonSystem, true);
		if(empty($aJsonSystem)) $aJsonSystem = array();
		// $aJsonSystem = array();

		# 讀本地機器人和投注資料和打賞資料
		// $ch = curl_init('https://line01.abp77.com/ClientTest/MsgJson'.$nGame.'.txt');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

		// curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
		// curl_setopt($ch, CURLOPT_TIMEOUT,10);

		// $result = curl_exec($ch);
		// curl_close($ch);
		if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
		{
			$result = file_get_contents(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt');
			$sJsonBet = $result;
			$aJsonBet = json_decode($sJsonBet, true);
		}
		if(empty($aJsonBet)) $aJsonBet = array();
		// $aJsonBet = array();
		if(count($aJsonBet) < 7)
		{
			# 執行假機器人寫入
		}

		$aJsonMsg = $aJsonBet+$aJsonSystem;
		ksort($aJsonMsg);
		$nSize = count($aJsonMsg);
		foreach($aJsonMsg as $LPsUnixTime => $LPaJsonMsg)
		{
			if($LPaJsonMsg['nUid'] == $aUser['nUid'])
			{
				$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('msgMember"','ifme msgSelf"',$LPaJsonMsg['sMsg']);
				$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('dtbox"','dtbox DisplayBlockNone"',$aJsonMsg[$LPsUnixTime]['sMsg']);
				$aJsonMsg[$LPsUnixTime]['sMsg'] = str_replace('td imgbox float-left"','td imgbox float-left DisplayBlockNone "',$aJsonMsg[$LPsUnixTime]['sMsg']);
			}
			if(NOWTIME < $LPsUnixTime/10000)
			{
				break;
			}
		}
		$aRooms[$nGame]['aJsonMsg'] = $aJsonMsg;
		
		
		// $sJsonRooms = file_get_contents(dirname(dirname(dirname(__FILE__))).'/t_linegame06/File/RoomJson.txt');
			// trigger_error(dirname(dirname(dirname(__FILE__))).'/t_linegame06/File/RoomJson.txt');
			// if(!empty($aRooms))
			// {
			// 	break;
			// }

		// }
		if(empty($aRooms))
		{
			trigger_error('empty room');
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))).'/File/RoomFakeBetErrorWeb.txt';
			file_put_contents($sFile, $sJsonRooms);
		}
		$aAnnounce = array();
		foreach($aRooms as $LPnGame => $LPaRooms)
		{
			// if(!isset($aRooms[$LPnGame]['aSetting']))
			// {
			// 	$aAnnounce[$LPnGame] = current($aRooms[$LPnGame]['aAnnounce'])[$aSystem['sLang']];
			// 	unset($aRooms[$LPnGame]);
			// 	continue;
			// }
			// else if(isset($aRooms[$LPnGame]['aAnnounce']))
			// {
			// 	$aAnnounce[$LPnGame] = current($aRooms[$LPnGame]['aAnnounce'])[$aSystem['sLang']];
			// 	unset($aRooms[$LPnGame]['aAnnounce']);
			// }

			// if(empty($aRooms[$LPnGame]['aSetting']))
			// {
			// 	trigger_error($LPnGame.print_r($aRooms,true));
			// }
			if(	empty($aUser['nTest']) &&
				($aRooms[$LPnGame]['aSetting']['nCloseRoom'] == 1 || $aRooms[$LPnGame]['aSetting']['nMaintenance'] == 1))
			{
				$aRooms[$LPnGame]['aSetting']['sBetUrl'] = 'javascript: void(0)';
			}
			else
			{
				if($LPnGame < 2000)
				{
					// $aRooms[$LPnGame]['aSetting']['sBetUrl'] = $aUrl['sBet0'].'&nGame='.$LPnGame;
				}
				else
				{
					// $aRooms[$LPnGame]['aSetting']['sBetUrl'] = $aUrl['sBet1'].'&nGame='.$LPnGame;
				}
			}

			if($aRooms[$LPnGame]['aLastest']['nSeconds1'] < 0 || $aRooms[$LPnGame]['aLastest']['nSeconds'] > 0)
			{
				$aRooms[$LPnGame]['aLastest']['sNums'] = '';
				$aRooms[$LPnGame]['aLastest']['sPreNums'] = '';
				$aRooms[$LPnGame]['aLastest']['sSort'] = '';
				$aRooms[$LPnGame]['aLastest']['sPreSort'] = '';
				$aRooms[$LPnGame]['aLastest']['aNums'] = array();
				$aRooms[$LPnGame]['aLastest']['aPreNums'] = array();
				$aRooms[$LPnGame]['aLastest']['sResult'] = '';
				$aRooms[$LPnGame]['aLastest']['aResult'] = array();
			}
			$aRooms[$LPnGame]['sSettleMsg1'] = '投注紀錄:<br/>';
			$aRooms[$LPnGame]['sSettleMsg2'] = '';
		}
		$aReturn['aAnnounce'] = $aAnnounce;

		if(false){
		// $ch = curl_init('https://adm.abp77.com/Bot/RoomFakeBet.txt');
		$ch = curl_init('https://demo801.dtap000s3.com/Project/t_linegame06/File/RoomFakeBet.txt');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);

		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);

		$result = curl_exec($ch);
		curl_close($ch);
		$sJsonFakeBet = $result;

		// $sJsonFakeBet = file_get_contents(dirname(dirname(__FILE__)).'/File/RoomFakeBet.txt');
		$aFakeBet = json_decode($sJsonFakeBet,true);
		$aReturn['aBetData'] = $aFakeBet;
		foreach($aReturn['aBetData'] as $LPsKey1 => $LPaData)
		{	
			if(isset($LPaData['aEndData']))
			{
				foreach($LPaData['aEndData'] as $LPsKey2 => $LPaData2)
				{
					$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sColor'] = aMETHODCOLOR[$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sContent0']];
					
					$aRooms[$LPnGame]['sSettleMsg1'] .= 
					'<div class="betmsgBlock arrow">
						<div class="betinfobox">
							<div class="betinfo JqBetMsgBlock">
								<div>'.$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sName0'].'</div>
								<div class="'.aMETHODCOLOR[$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sContent0']].' JqBetMsg">'.aMETHODNAME[$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sContent0']].$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['nMoney0'].' '.$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sCreateTime'].'</div>
							</div>
						</div>
					</div>';

					// if($aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sContent0'] === 'PW')
					// {
					// 	$aRooms[$LPnGame]['sSettleMsg2'] .= $aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sName0'].'<div class="'.aMETHODCOLOR['PW'].' JqBetMsg">'.$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['nMoney0'].'</div><br/>';
					// }
				}
			}			
			foreach($LPaData['aBetData'] as $LPsKey2 => $LPaData2)
			{
				$aReturn['aBetData'][$LPsKey1]['aBetData'][$LPsKey2]['sColor'] = aMETHODCOLOR[$aReturn['aBetData'][$LPsKey1]['aBetData'][$LPsKey2]['sContent0']];
				// $aRooms[$LPnGame]['sSettleMsg1'] .= 
				// 	'<div class="betmsgBlock arrow">
				// 		<div class="betinfobox">
				// 			<div class="betinfo JqBetMsgBlock">
				// 				<div>'.$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sName0'].'</div>
				// 				<div class="'.aMETHODCOLOR[$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['sContent0']].' JqBetMsg">'.aBET['SUCCESS'].$aReturn['aBetData'][$LPsKey1]['aEndData'][$LPsKey2]['nMoney0'].'</div>
				// 			</div>
				// 		</div>
				// 	</div>';
			}
		}
		}

		// trigger_error(print_r($aRooms,true));
		// if($nSettle === 1) 
		// {
		
		// }
		if($aRooms === array())
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOROUND'];
		}
		else
		{	
			$aReturn['nError'] = 0;
			$aReturn['aData'] = $aRooms;
		}
		$aReturn['nNowTime'] = (int)(microtime(true)*10000);
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	exit;
	#輸出結束
?>