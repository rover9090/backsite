<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ctrl.php');
	#require結束

	#參數接收區
	$nGame	= filter_input_int('nGame',		INPUT_POST,'1001');
	$nStatus	= filter_input_int('nStatus',		INPUT_POST,-1);
	$sResult0	= filter_input_str('sResult0',	INPUT_POST,'0620,0507');#00~10,20~29
	$sResult1	= filter_input_str('sResult1',	INPUT_POST,'0303,0608');
	$sResult2	= filter_input_str('sResult2',	INPUT_POST,'2629,0229');
	$sResult3	= filter_input_str('sResult3',	INPUT_POST,'2327,0720');
	$sRoom	= filter_input_str('sRoom',		INPUT_POST,'0');
	$nTotalRoom	= filter_input_int('nTotalRoom',	INPUT_POST,0);
	$nLastCount	= filter_input_int('nLastCount',	INPUT_POST,0);# 倒數把數
	$nShuffling	= filter_input_int('nShuffling',	INPUT_POST,0);# 是否洗牌中
	$nDealer	= filter_input_str('nDealer',	INPUT_POST,'');# nDealer發牌員編號
	$nBuild	= filter_input_int('nBuild',	INPUT_POST,0);# 是否建立期數
	$nSettle	= filter_input_int('nSettle',	INPUT_POST,0);# 是否過帳
	$nEdit	= filter_input_int('nEdit',		INPUT_POST,0);
	
	$sResult0 = '0620,0507';
	$sResult1 = '0303,0608';
	$sResult2 = '2629,0229';
	$sResult3 = '2327,0720';
	#參數結束

	#參數宣告區
	$aData = array();
	$aEditLog = array(
		CLIENT_GAMES_NUMS	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_GAMES_DATA	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_USER_MONEY	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nLastId = 0;
	$nErr = 0;
	$sMsg = '';
	$aGameSetting = array();
	// $nEndTime = NOWTIME + $aSystem['aParam']['nSingleTime'];
	// $sEndTime = date('Y-m-d H:i:s',$nEndTime);
	// $aBanker = aGAMECTRL['aRESULT0'];
	// $aPlayer = aGAMECTRL['aRESULT1'];
	#宣告結束

	if(!empty($aJWT))
	{
		$sSQL = '	SELECT	nId,
						nGame,
						sName0,
						nOnline,
						sOpenTime,
						sCloseTime,
						nSec,
						nSec1,
						nSec3,
						nDealer,
						nShuffling,
						nDayLastRounds
				FROM	'. END_GAMES_SETTING .'
				WHERE	nOnline = 1
				AND	sLang = :sLang
				AND	nGame = :nGame
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_STR);
		sql_query($Result);
		$aGameSetting = $Result->fetch(PDO::FETCH_ASSOC);
		$aGameSetting['nCloseTime'] = strtotime($aGameSetting['sCloseTime']);
		$aGameSetting['nOpenTime'] = strtotime($aGameSetting['sOpenTime']);
		if($aGameSetting['nCloseTime'] < $aGameSetting['nOpenTime'])
		{
			$aGameSetting['nOpenTime'] -= 86400;
		}
	}

	#程式邏輯區
	if ($aJWT['a'] == 'GAMECTRL' && ($nStatus == 3 || $nStatus == 11))
	{
		# 洗牌中ON不建立
		# 洗牌中ON不過帳

		# 倒數最後一局不建立
		# 紅卡不建立
		# 取消當局不過帳
		# 當天或紅卡後第一局不過帳

		$sSQL = '	SELECT 	nId
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				AND		nStartTime < :NOWTIME
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':NOWTIME',NOWTIME+$aGameSetting['nSec1'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nBetEndTime,
						nEndTime,
						sEndTime,
						sResult,
						sNums,
						nRound,
						nUse,
						nDayLast,
						nFinalRound,
						nPreStartTime,
						nCancel,
						nDeckId,
						nGameSerId
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData !== false)
		{
			$aData['aResult'] = json_decode($aData['sResult'],true);
			$aData['aNums'] = json_decode($aData['sNums'],true);
		}
		else
		{
			$aData['aResult'] = array();
			$aData['aNums'] = array();
			$aData['sNo'] = '';
		}
		
		// if(NOWTIME >= $aGameSetting['nOpenTime'] && NOWTIME >= $aGameSetting['nCloseTime'])
		// {
		// 	if($aData['nUse'] == 1 && $aData['nRound'] != 0 && $aData['nDayLast'] == 0)
		// 	{
		// 		$aJWT['a'] == 'LASTCOUNT';
		// 	}
		// }

		if(!empty($aData['sNo']) && $aData['nStatus'] < 10 && $aData['nBetEndTime'] > NOWTIME && $nStatus < 10)
		{
			$nErr = 1;
			$sMsg .= aMsg['NOTOVER'].'1<br/>';			
		}
		else
		{
			if($nSettle == 1)
			{
				if($nEdit == 0 && false) # 自動判牌
				{
					$aBankerCheck = array();
					$aPlayerCheck = array();
					$sSQL = '	SELECT	nId,
									sNums,
									nUpdateTime
							FROM		'. CLIENT_GAMES_NUMS_TEMP .'
							WHERE		nGame = :nGame
							AND		nUpdateTime > :nLastTime
							LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					$Result->bindValue(':nLastTime', NOWTIME - $aGameSetting['nSec'], PDO::PARAM_INT);
					sql_query($Result);
					$aNums = $Result->fetch(PDO::FETCH_ASSOC);
					$aNums['aNums'] = json_decode($aNums['sNums'],true);
					$aBankerCheck = fnCheckText($aNums['aNums']['sBanker']);
					$aPlayerCheck1 = fnCheckText($aNums['aNums']['A']);
					$aPlayerCheck2 = fnCheckText($aNums['aNums']['B']);
					$aPlayerCheck3 = fnCheckText($aNums['aNums']['C']);
				}
				// if($nEdit == 1)
				// {
					$sBanker = strtoupper($sResult0);
					$sPlayer1 = strtoupper($sResult1);
					$sPlayer2 = strtoupper($sResult2);
					$sPlayer3 = strtoupper($sResult3);
					$aBankerCheck = fnCheckText($sBanker);
					$aPlayerCheck1 = fnCheckText($sPlayer1);
					$aPlayerCheck2 = fnCheckText($sPlayer2);
					$aPlayerCheck3 = fnCheckText($sPlayer3);
				// }
				if($aBankerCheck['nError'] == 1 || $aPlayerCheck1['nError'] == 1 || $aPlayerCheck2['nError'] == 1 || $aPlayerCheck3['nError'] == 1)
				{
					$nErr = 1;
					$sMsg .= aMsg['FORMATERR'].'<br/>'.
					$sResult0.'<br/>'.
					$sResult1.'<br/>'.
					$sResult2.'<br/>'.
					$sResult3.'<br/>'
					;
				}
			}
			# 剩下就是status=99
		}

		if(!empty($aData['sNo']) && ($aData['nFinalRound'] == 1 || $aData['nDayLast'] == 1 || $aData['nStatus'] == 99) && $aData['sNums'] != '' && $aData['nUse'] == 1)
		{
			# 下了最後一局+已經開牌+還沒洗牌就做阻擋
			$nErr = 1;
			$sMsg .= aMsg['NOSHUFFLE'].'<br/>';
		}
		
		// if($aData['nCancel'] == 1 && $aData['nUse'] == 1)
		// {
		// 	# 下了取消當局+還沒洗牌就做阻擋
		// 	$nErr = 1;
		// 	$sMsg .= aMsg['NOSHUFFLE'].'.<br/>';
		// }
		if($nErr != 0)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame.'&nCancel=1';
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			if($aGameSetting['nShuffling'] == 1)
			{
				$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET nShuffling = 0
						WHERE	nGame = :nGame';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
				sql_query($Result);
			}
			# 新局 #
			if($nStatus == 3)
			{
				## 建立新局 ##
				if($nBuild == 1)
				{
					if(isset($aData['nRound']))
					{
						if($aData['nStatus'] > 10)
						{
							if($aData['nStatus'] == 99)
							{
								$nRound = $aData['nRound']-1;
								$nGameSerId = $aData['nGameSerId']-1;
							}
							else
							{
								$nRound = $aData['nRound'];
								$nGameSerId = $aData['nGameSerId'];
							}
						}
						else
						{
							$nRound = $aData['nRound'] + 1;
							$nGameSerId = $aData['nGameSerId']+1;
						}
						$nDeckId = $aData['nDeckId'];
					}
					else
					{
						$nGameSerId = $aData['nGameSerId'] + 1;
						$nDeckId = $aData['nDeckId'] + 1;
						$nRound = 0;
					}
					if(empty($aData))
					{
						$nGameSerId = 0;
						$nDeckId = 0;
						$nRound = 0;
					}
					// $sNo = fnParsingNo($aData['sNo']);
					$nLogCode = 8104401;# 新局 開始搶莊
					$aGameNums_SQL_Array = array(
						'nGame'		=> (int)	$nGame,
						'sNo'			=> (string)	$nGameSerId,
						'nStatus'		=> (int)	0,
						'nPreStartTime'	=> (int)	NOWTIME,
						'sPreStartTime'	=> (string)	NOWDATE,
						'nStartTime'	=> (int)	NOWTIME+$aGameSetting['nSec1'],
						'sStartTime'	=> (string)	date('Y-m-d H:i:s',NOWTIME+$aGameSetting['nSec1']),
						'nBetEndTime'	=> NOWTIME + $aGameSetting['nSec1'] + $aGameSetting['nSec'],
						'sBetEndTime'	=> date('Y-m-d H:i:s',NOWTIME + $aGameSetting['nSec1'] + $aGameSetting['nSec']),
						'nBankerEndTime'	=> NOWTIME + $aGameSetting['nSec1'] + $aGameSetting['nSec'] + $aGameSetting['nSec3'],
						'sBankerEndTime'	=> date('Y-m-d H:i:s',NOWTIME + $aGameSetting['nSec1'] + $aGameSetting['nSec'] + $aGameSetting['nSec3']),
						'sResult'		=> (string)	'',
						// 'nUse'		=> 1,
						'nRound'		=> $nRound,
						'sNums'		=> '',
						'nDayLast'		=> $nLastCount,
						// 'nDeckId'		=> $nDeckId,
						'nGameSerId'	=> $nGameSerId,
					);
					if($nSettle == 1)
					{
						$aGameNums_SQL_Array['sPreNums'] = json_encode(array(
							'sBanker' => $aBankerCheck['sData'],
							'A' => $aPlayerCheck1['sData'],
							'B' => $aPlayerCheck2['sData'],
							'C' => $aPlayerCheck3['sData'],
						));
					}
					// print_r($aGameNums_SQL_Array);
					// echo (NOWTIME+5+30) - NOWTIME;

					$sSQL = 'INSERT INTO '. CLIENT_GAMES_NUMS .' ' . sql_build_array('INSERT', $aGameNums_SQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aGameNums_SQL_Array);
					sql_query($Result);
					$nLastId = $oPdo->lastInsertId();
					$aData['nLastId'] = $nLastId;

					// if($aData['nCancel'] > 0)
					// {
					$aGameNums_SQL_Array = array(
						'nPauseTime1' => NOWTIME,
						'sPauseTime1' => NOWDATE,
					);
					$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
							WHERE	nId = :nId';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aGameNums_SQL_Array);
					sql_query($Result);
					// }

					$aSQL_Array = array(
						'sNo' => $nGameSerId,
						'nStatus' => 1,
					);
					$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nType0 = 0
							AND	nStatus = 0
							ORDER BY nId ASC
							LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					# 產生開關盤訊息
					$aJsonChat = array();
					if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
					{
						$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
						$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
					}
					
					$nOpenTime = microtime(true);
					$nClosing = $aGameSetting['nSec']-$aGameSetting['nSec2'];
					$nMsgCount = count($aJsonChat);
					$aJsonChat[$nOpenTime*10000] = array(
						'nUid' => '0',
						'sMsg' => '
						<div class="betmsg font-xxs msgSystem">
							<div class="coverbox">
								<div class="td imgbox float-left">
									<img class="img-circle" src="images/headImg/0.jpg">
								</div>
								<div class="td float-left ifmeright msgSystemInf">
									<div class="dtbox">
										<div class="dt-nickname">系統機器人</div>
									</div>
									<div class="betmsgBlock arrow">
										<div class="betinfobox img">
											<div class="betinfo">
												<div>
													<div><img src="images/startBet.jpg"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="dt-time">
										<div class="dt-timeInner">'.NOWCLOCK.'</div>
									</div>
								</div>
							</div>
						</div>',
					);

					$sBetMsg = '停止下注<br><br>已停止下注，不再接受下注<br>牌局資訊:<br>局號'.$aGameNums_SQL_Array['sNo'].'<br>下注玩家<br>【初二】<br>【川三】<br>【尾四】';
					$aJsonMsg[($nOpenTime+$nClosing)*10000]['nUid'] = 0;
					$aJsonMsg[($nOpenTime+$nClosing)*10000]['sMsg'] = 
					'<div class="betmsg font-xxs msgMember">
						<div class="coverbox">
							<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
							<div class="td float-left ifmeright msgMemberInf">
								<div class="dtbox">
									<div class="dt-nickname">系統機器人</div>
								</div>
								<div class="betmsgBlock arrow">
									<div class="betinfobox">
										<div class="betinfo">
											<div>'.
											$sBetMsg.'</div>
										</div>
									</div>
								</div>
								<div class="dt-time">
									<div class="dt-timeInner">'.date('H:i:s',$nOpenTime+$nClosing).'</div>
								</div>
							</div>
						</div>
					</div>';
					while($nMsgCount > 20)
					{
						$nMsgCount--;
						unset($aJsonChat[key($aJsonChat)]);
					}
					$sJsonChat = json_encode($aJsonChat);
					$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
					file_put_contents($sFile, $sJsonChat,LOCK_EX);
				}
				
				## 更新獎號 ##
				if($nSettle == 1)
				{
					$nLogCode = 8104403;
					$aGameNums_SQL_Array = array(
						'sNums'	=> (string)	json_encode(array(
							'sBanker' => $aBankerCheck['sData'],
							'A' => $aPlayerCheck1['sData'],
							'B' => $aPlayerCheck2['sData'],
							'C' => $aPlayerCheck3['sData'],
						)),
						'nEndTime'		=> NOWTIME,
						'sEndTime'		=> NOWDATE,
						'nLastest'		=> 1,
					);
					$aData['aResult'] = fnCheckResult($aBankerCheck['aData'],$aPlayerCheck1['aData'],$aPlayerCheck2['aData'],$aPlayerCheck3['aData']);
					$aGameNums_SQL_Array['sResult'] =  (string)json_encode($aData['aResult']);
					$aGameNums_SQL_Array['nStatus'] = $nStatus;# $nStatus
					
					$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nLastest = 0
							WHERE	nGame = :nGame';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
					sql_query($Result);
	
					$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aGameNums_SQL_Array);
					sql_query($Result);
				}				

				$oPdo->commit();

			}
			else
			{
				$aData['nLastId'] = $aData['nId'];

				$aGameNums_SQL_Array = array(
					'nStatus'	=> (int)	$nStatus,
					'nEndTime'		=> NOWTIME,
					'sEndTime'		=> NOWDATE,
					// 'nUse'	=> 0,# nUse保持1才可以被偵測秒數的ajax_1抓到 
					'nRound'	=> -1,
					'nLastest'	=> 0,
					'nCancel'	=> 2,
					'nPauseTime0' => NOWTIME,
					'sPauseTime0' => NOWDATE,
				);							

				# 原本是撈nStatus!=99  打算改撈去掉這行
				# 如果我的最新資料是nCancel>0 && nStatus < 10 會出問題嗎
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
						WHERE	nGame = :nGame
						AND	nId = :nId ';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aGameNums_SQL_Array);
				sql_query($Result);

				$oPdo->commit();

				$nLogCode = 8104404;
				$aActData = array(
					'aOrder'	=> array(),
					'aMember'	=> array(),
				);

				$sSQL = '	SELECT	nId,
								nMoney0,
								nUid
						FROM		'. CLIENT_GAMES_DATA .'
						WHERE		nGame = :nGame
						AND		sNo = :sNo';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
				$Result->bindValue(':sNo', $aData['sNo'], PDO::PARAM_STR);
				sql_query($Result);
				while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
				{
					$aActData['aOrder'][$aRows['nId']] = $aRows;
					if(!isset($aActData['aMember'][$aRows['nUid']]))
					{
						$aActData['aMember'][$aRows['nUid']] = array(
							'nId' => 0,
							'nMoney' => 0,
						);
					}
					$aActData['aMember'][$aRows['nUid']]['nId'] = $aRows['nId'];
					$aActData['aMember'][$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
				}

				# 注單取消 #
				foreach ($aActData['aOrder'] as $LPnId => $LPaData)
				{
					$oPdo->beginTransaction();
					
					$sSQL = '	SELECT	nId,
									nMoney0,
									nStatus,
									nDone
							FROM		'. CLIENT_GAMES_DATA .'
							WHERE		nId = :nId
							AND		nDone = 0
							LIMIT		1
							FOR		UPDATE';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						error_log('index:635 查無注單 id=' . $LPnId);
						continue;
					}

					$aSQL_Array = array(
						'nStatus'		=> (int)	3,	# 取消
						'nDone'		=> (int)	1,	# 結單
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
					);
					$sSQL = '	UPDATE '. CLIENT_GAMES_DATA . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aNowData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);

					$aEditLog[CLIENT_GAMES_DATA]['aOld'][$LPnId] = $LPaData;
					$aEditLog[CLIENT_GAMES_DATA]['aNew'][$LPnId] = $aSQL_Array;

					$oPdo->commit();
				}

				# 會員反款 #
				foreach ($aActData['aMember'] as $LPnUid => $LPaData)
				{
					$nBefore	= 0;
					$nDelta	= 0;
					$nAfter	= 0;

					$oPdo->beginTransaction();

					$sSQL = '	SELECT 	nId
							FROM		'. CLIENT_USER_DATA .'
							WHERE		nId = :nId
							LIMIT		1
							FOR		UPDATE' ;
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId',$LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						error_log('index:670 查無會員 uid='.$LPnUid);
						continue;
					}

					$sSQL = '	SELECT 	nMoney
							FROM		'. CLIENT_USER_MONEY .'
							WHERE		nUid = :nUid
							LIMIT		1
							FOR		UPDATE' ;
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid',$LPnUid,PDO::PARAM_INT);
					sql_query($Result);
					$aOldMoney = $Result->fetch(PDO::FETCH_ASSOC);
					if($aOldMoney === false)
					{
						$oPdo->rollback();
						error_log('index:670 查無會員金額 uid='.$LPnUid);
						continue;
					}

					$nBefore	= (float) $aOldMoney['nMoney'];
					$nDelta	= (float) $LPaData['nMoney'];
					$nAfter	= $nBefore + $nDelta;
					# 補錢 #
					$aNewMoney = array(
						'Money' => (float) $nAfter,
					);
					$aSQL_Array = oTransfer::PointUpdate($LPnUid,$aNewMoney,1,true);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nUid = :nUid LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);

						$aAccLog = array(
							'nUid' 		=> (int)	$LPnUid,
							'nKid' 		=> (int)	$LPaData['nId'],
							'nType0' 		=> (int)	1,
							'nType1' 		=> (int)	300,
							'nType2' 		=> (int)	0,
							'nBefore' 		=> (float)	$nBefore,
							'nDelta' 		=> (float)	$nDelta,
							'nAfter' 		=> (float)	$nAfter,
							'sParams' 		=> (string)	'',
							'nCreateTime' 	=> (int)	NOWTIME,
							'sCreateTime' 	=> (string)	NOWDATE,
							'nCreateDay' 	=> (int)	strtotime('today'),
						);
						DoLogAcc($aAccLog);
					}

					$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPnUid] = $aOldMoney;
					$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPnUid] = $aSQL_Array;

					$oPdo->commit();
				}
				
			}

			#紀錄動作 - 新增
			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']] = $aGameNums_SQL_Array;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']]['nId'] = ($nLastId == 0) ? $aData['nId'] : $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	($nLastId == 0) ? $aData['nId'] : $nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
			exit;
		}		
	}

	if ($aJWT['a'] == 'GAMECTRL' && $nStatus == 99)
	{
		# 取消時要1 退回當局投注注單 2 退單上局全部注單
		# 當日第一局不會按也不該按 可以不用考慮阻擋
		# 當日最後一局已派彩時?
		# 兩局必定有一局已過帳，一局投注中
		# 如果倒數第一筆為已過帳，就省略第二筆
		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nBetEndTime,
						nEndTime,
						sEndTime,
						sResult,
						sNums,
						nRound,
						nUse,
						nDayLast,
						nFinalRound,
						nCancel
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				AND		nStatus < 10
				ORDER BY	nId DESC
				LIMIT		2';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		while($aData = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData['aResult'] = json_decode($aData['sResult'],true);
			$aData['aNums'] = json_decode($aData['sNums'],true);			
		
			# 取消當局後 本局全部退本金 上局全部退款
			$aGameNums_SQL_Array = array(
				'nStatus'	=> (int)	$nStatus,
				'nEndTime'		=> NOWTIME,
				'sEndTime'		=> NOWDATE,
				// 'nUse'	=> 0,# nUse保持1才可以被偵測秒數的ajax_1抓到 
				'nRound'	=> -1,
				'nLastest'	=> 0,
				'nCancel'	=> 1,
				'nPauseTime0' => NOWTIME,
				'sPauseTime0' => NOWDATE,
			);
				
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nGame = :nGame
					AND	nId >= :nId ';
			$Result2 = $oPdo->prepare($sSQL);
			$Result2->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result2->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result2, $aGameNums_SQL_Array);
			sql_query($Result2);

			$nLogCode = 8104404;
			$aActData = array(
				'aOrder'	=> array(),
				'aMember'	=> array(),
			);

			$sSQL = '	SELECT	nId,
							nMoney0,
							nUid
					FROM		'. CLIENT_GAMES_DATA .'
					WHERE		nGame = :nGame
					AND		sNo = :sNo';
			$Result2 = $oPdo->prepare($sSQL);
			$Result2->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result2->bindValue(':sNo', $aData['sNo'], PDO::PARAM_STR);
			sql_query($Result2);
			while($aRows = $Result2->fetch(PDO::FETCH_ASSOC))
			{
				$aActData['aOrder'][$aRows['nId']] = $aRows;
				if(!isset($aActData['aMember'][$aRows['nUid']]))
				{
					$aActData['aMember'][$aRows['nUid']] = array(
						'nId' => 0,
						'nMoney' => 0,
					);
				}
				$aActData['aMember'][$aRows['nUid']]['nId'] = $aRows['nId'];
				$aActData['aMember'][$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
			}

			# 注單取消 #
			foreach ($aActData['aOrder'] as $LPnId => $LPaData)
			{
				$oPdo->beginTransaction();
				
				$sSQL = '	SELECT	nId,
								nMoney0,
								nStatus,
								nDone
						FROM		'. CLIENT_GAMES_DATA .'
						WHERE		nId = :nId
						AND		nDone = 0
						LIMIT		1
						FOR		UPDATE';
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nId', $LPnId, PDO::PARAM_INT);
				sql_query($Result2);
				$aNowData = $Result2->fetch(PDO::FETCH_ASSOC);
				if($aNowData === false)
				{
					$oPdo->rollback();
					error_log('index:635 查無注單 id=' . $LPnId);
					continue;
				}

				$aSQL_Array = array(
					'nStatus'		=> (int)	3,	# 取消
					'nDone'		=> (int)	1,	# 結單
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);
				$sSQL = '	UPDATE '. CLIENT_GAMES_DATA . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nId', $aNowData['nId'], PDO::PARAM_INT);
				sql_build_value($Result2, $aSQL_Array);
				sql_query($Result2);

				$aEditLog[CLIENT_GAMES_DATA]['aOld'][$LPnId] = $LPaData;
				$aEditLog[CLIENT_GAMES_DATA]['aNew'][$LPnId] = $aSQL_Array;

				$oPdo->commit();
			}

			# 會員反款 #
			foreach ($aActData['aMember'] as $LPnUid => $LPaData)
			{
				$nBefore	= 0;
				$nDelta	= 0;
				$nAfter	= 0;

				$oPdo->beginTransaction();

				$sSQL = '	SELECT 	nId
						FROM		'. CLIENT_USER_DATA .'
						WHERE		nId = :nId
						LIMIT		1
						FOR		UPDATE' ;
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nId',$LPnUid,PDO::PARAM_INT);
				sql_query($Result2);
				$aNowData = $Result2->fetch(PDO::FETCH_ASSOC);
				if($aNowData === false)
				{
					$oPdo->rollback();
					error_log('index:670 查無會員 uid='.$LPnUid);
					continue;
				}

				$sSQL = '	SELECT 	nMoney
						FROM		'. CLIENT_USER_MONEY .'
						WHERE		nUid = :nUid
						LIMIT		1
						FOR		UPDATE' ;
				$Result2 = $oPdo->prepare($sSQL);
				$Result2->bindValue(':nUid',$LPnUid,PDO::PARAM_INT);
				sql_query($Result2);
				$aOldMoney = $Result2->fetch(PDO::FETCH_ASSOC);
				if($aOldMoney === false)
				{
					$oPdo->rollback();
					error_log('index:670 查無會員金額 uid='.$LPnUid);
					continue;
				}

				$nBefore	= (float) $aOldMoney['nMoney'];
				$nDelta	= (float) $LPaData['nMoney'];
				$nAfter	= $nBefore + $nDelta;
				# 補錢 #
				$aNewMoney = array(
					'Money' => (float) $nAfter,
				);
				$aSQL_Array = oTransfer::PointUpdate($LPnUid,$aNewMoney,1,true);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nUid = :nUid LIMIT 1';
					$Result2 = $oPdo->prepare($sSQL);
					$Result2->bindValue(':nUid', $LPnUid, PDO::PARAM_INT);
					sql_build_value($Result2, $aSQL_Array);
					sql_query($Result2);

					$aAccLog = array(
						'nUid' 		=> (int)	$LPnUid,
						'nKid' 		=> (int)	$LPaData['nId'],
						'nType0' 		=> (int)	1,
						'nType1' 		=> (int)	300,
						'nType2' 		=> (int)	0,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta,
						'nAfter' 		=> (float)	$nAfter,
						'sParams' 		=> (string)	'',
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
						'nCreateDay' 	=> (int)	strtotime('today'),
					);
					DoLogAcc($aAccLog);
				}

				$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPnUid] = $aOldMoney;
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPnUid] = $aSQL_Array;

				$oPdo->commit();
			}

			#紀錄動作 - 新增
			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']] = $aGameNums_SQL_Array;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']]['nId'] = ($nLastId == 0) ? $aData['nId'] : $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	($nLastId == 0) ? $aData['nId'] : $nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			# 如果倒數第一局已經完成過帳就直接跳過
			if($aData['nStatus'] == 4)
			{
				break;
			}
		}
		header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
		exit;
	}

	# 新局洗牌
	if($aJWT['a'] == 'NEWDECK')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$oPdo->beginTransaction();
		$nErr = 0;

		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nEndTime,
						sEndTime,
						sResult,
						sNums,
						nRound,
						nUse,
						nFinalRound,
						nCancel,
						nDayLast
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if(empty($aData) || ($aData['sResult'] == '' && $aData['nStatus'] == 0) || ($aData['nFinalRound'] == 0 && $aData['nDayLast'] == 0 && $aData['nCancel'] == 0))
		{
			$nErr = 1;
		}

		if(!empty($aData) && $aData['nCancel'] == 1)
		{
			$nErr = 0;
		}

		if($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aMsg['NOTEND'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			if($nShuffling == 1)
			{
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nUse = 0
						WHERE	nGame = :nGame';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
				sql_query($Result);
			}
			$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET nShuffling = :nShuffling
					WHERE	nGame = :nGame';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result->bindValue(':nShuffling', $nShuffling, PDO::PARAM_INT);
			sql_query($Result);

			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;	
			$oPdo->commit();
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
			exit;							
		}
		
		
	}

	# 換荷官
	if($aJWT['a'] == 'NEWDEALER')
	{
		$sSQL = '	SELECT	nId
				FROM	'. END_MANAGER_DATA .'
				WHERE	nId = :nDealer
				AND	nAdmType IN (4,5)
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nDealer', $nDealer, PDO::PARAM_STR);
		sql_query($Result);
		$aDealer = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT	nDealer
				FROM	'. END_GAMES_SETTING .'
				WHERE	nDealer = :nDealer
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nDealer', $nDealer, PDO::PARAM_STR);
		sql_query($Result);
		$aCurrentDealer = $Result->fetch(PDO::FETCH_ASSOC);

		if($aDealer === false && $nDealer != '0')
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aMsg['NOADM'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else if($aCurrentDealer !== false && $nDealer != '0')
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aMsg['HADADM'];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET nDealer = :nDealer
					WHERE	nGame = :nGame';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nDealer', $nDealer, PDO::PARAM_INT);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			sql_query($Result);

			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
			exit;
		}
	}

	# 宣告紅卡
	if($aJWT['a'] == 'REDCARD')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nEndTime,
						sEndTime,
						sResult,
						sNums,
						nRound,
						nUse,
						nFinalRound
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if($aGameSetting['nShuffling'] == 1)
		{
			$nErr = 1;
			$sMsg .= aMsg['SHUFFLING'].'<br/>';
		}

		if($aData === false || $aData['nStatus'] != 0)
		{
			$nErr = 1;
			$sMsg .= aMsg['HADEND'].'<br/>';
		}

		# 放紅卡時 會在當局一開始就提示
		if($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			
		}
		else
		{
			// echo '闖入紅卡';
			// exit;
			if($aData['nFinalRound'] == 0)
			{
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nFinalRound = 1
						WHERE	nId = :nId';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_query($Result);
			}
			else
			{
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nFinalRound = 0
						WHERE	nId = :nId';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_query($Result);
			}
	
			$oPdo->commit();
	
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
			exit;
		}
	}

	# 倒數把數
	if($aJWT['a'] == 'LASTCOUNT')
	{
		if($aGameSetting['nShuffling'] == 1)
		{
			$nErr = 1;
			$sMsg .= aMsg['SHUFFLING'].'<br/>';
		}

		if($nErr == 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			
		}
		else
		{
			$sSQL = '	SELECT 	nId
					FROM 		'. CLIENT_GAMES_NUMS .'
					WHERE 	nGame = :nGame
					ORDER BY	nId DESC
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			sql_query($Result);
			$aData = $Result->fetch(PDO::FETCH_ASSOC);

			$oPdo->beginTransaction();

			$sSQL = '	SELECT 	nId,
							nGame,
							sNo,
							nStatus,
							nStartTime,
							sStartTime,
							nEndTime,
							sEndTime,
							sResult,
							sNums,
							nRound,
							nUse,
							nFinalRound,
							nDayLast
					FROM 		'. CLIENT_GAMES_NUMS .'
					WHERE 	nId = :nId
					FOR UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
			sql_query($Result);
			$aData = $Result->fetch(PDO::FETCH_ASSOC);
	
			if($aData['nDayLast'] === '0')
			{
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nDayLast = :nDayLast
						WHERE	nId = :nId';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nDayLast', $nLastCount, PDO::PARAM_INT);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_query($Result);
				$oPdo->commit();
	
				// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
				// $aJumpMsg['0']['sIcon'] = 'success';
				// $aJumpMsg['0']['sMsg'] = UPTV;
				// $aJumpMsg['0']['sShow'] = 1;
				// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
				// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
				exit;
			}
			else
			{
				$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET nDayLast = 0
						WHERE	nId = :nId';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
				sql_query($Result);
				$oPdo->commit();
	
				// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
				// $aJumpMsg['0']['sIcon'] = 'success';
				// $aJumpMsg['0']['sMsg'] = '取消倒數';
				// $aJumpMsg['0']['sShow'] = 1;
				// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame;
				// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_1.php']).'&nGame='.$nGame); 
				exit;
			}
		}
	}

	function fnCheckResult($aBanker,$aPlayer)
	{
		
		return array('A'=>1,'B'=>1,'C'=>0);
	}

	function fnCheckText($sText)
	{
		$aData = array();
		$aReturn = array(
			'nError' => 0,
			'aData' => array(),
			'sData' => '',
		);
		$nStrlen = strlen($sText);
		if($nStrlen === 9)
		{
			if(!isset(PaiGow_Class::$aPaiBase[$sText[0].$sText[1]]) || !isset(PaiGow_Class::$aPaiBase[$sText[2].$sText[3]]) || !isset(PaiGow_Class::$aPaiBase[$sText[5].$sText[6]]) || !isset(PaiGow_Class::$aPaiBase[$sText[7].$sText[8]]))
			{
				var_dump(PaiGow_Class);
				if(!isset(PaiGow_Class::$aPaiBase[$sText[0].$sText[1]]))
				{
					echo $sText[0].$sText[1];
				}
				if(!isset(PaiGow_Class::$aPaiBase[$sText[2].$sText[3]]))
				{
					echo $sText[2].$sText[3];
				}
				if(!isset(PaiGow_Class::$aPaiBase[$sText[5].$sText[6]]))
				{
					echo $sText[5].$sText[6];
				}
				if(!isset(PaiGow_Class::$aPaiBase[$sText[7].$sText[8]]))
				{
					echo $sText[7].$sText[8];
				}
				$aReturn['nError'] = 1;
			}
			else
			{
				$aReturn['sData'] = $sText;
			}
		}
		else
		{
			$aReturn['nError'] = 1;
		}

		return $aReturn;
	}

	function fnParsingNo($sNo)
	{
		$sInc = substr($sNo,-3);
		$sDate = substr($sNo,0,-3);
		$sTodayDate = date('ymd');
		if($sDate !== $sTodayDate)
		{
			$sDate = $sTodayDate;
			$sInc = '000';
		}
		$sInc = sprintf('%03d',(int)$sInc+1);
		return $sDate.$sInc;
	}
	#程式邏輯結束
?>