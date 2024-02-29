<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ctrl.php');
	#require結束

	#參數接收區
	$nId		= filter_input_int('nId',		INPUT_POST,0);
	$nGame	= filter_input_int('nGame',		INPUT_POST,'1001');
	$nBankerId	= filter_input_int('nBankerId',	INPUT_POST,0);
	$nStatus	= filter_input_int('nStatus',		INPUT_POST,-1);
	$nResult1	= filter_input_int('nResult1',	INPUT_POST,0);
	$nResult2	= filter_input_int('nResult2',	INPUT_POST,0);
	$nResult3	= filter_input_int('nResult3',	INPUT_POST,0);
	$nResult0	= filter_input_int('nResult0',	INPUT_POST,0);# 是否中彩金
	$sLimit	= filter_input_str('sLimit',		INPUT_POST,'');
	#參數結束
// 	echo '<pre>';
// print_r($aJWT);exit;
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
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();
	#宣告結束

	#程式邏輯區
	# 遊戲開始 跟莊家確認是否限額 不限額則由荷官進入下個階段
	if($aJWT['a'] == 'INS0')
	{
		$nError = 0;
		$aNewData = array();
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aNewBanker = array();

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
						nGameSerId,
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1
				FOR UPDATE'; # 鎖住表避免重複insert
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData !== false && $aData['nStatus'] < 3)
		{
			$nError = 1;
			$sMsg = '期數狀態錯誤';
		}
		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nFsId,
						sPerLink,
						sSiteId,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nGame = :nGame
				AND		nStatus = 0
				ORDER BY nCreateTime ASC
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aBanker = $Result->fetch(PDO::FETCH_ASSOC);
		
		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nFsId,
						sPerLink,
						sSiteId,
						nContinue,
						nBuyContinue,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nGame = :nGame
				AND		nStatus = 1
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aPrevBanker = $Result->fetch(PDO::FETCH_ASSOC);

		$nBankerContinue = 0;
		if($aPrevBanker !== false && (int)$aPrevBanker['nContinue'] % 2 === 1)
		{
			$nBankerContinue = 1;
		}
		else if($aPrevBanker !== false && (int)$aPrevBanker['nContinue'] % 2 === 0 && (int)$aPrevBanker['nContinue'] === $aPrevBanker['nBuyContinue'] * 2)
		{
			$nBankerContinue = 1;
		}
		else
		{
			if($aBanker === false)
			{
				$nError = 1;
				$sMsg = '無人排莊';
			}
		}

		if($nError === 0)
		{
			if($aData['nStatus'] > 10)
			{
				$nRound = $aData['nRound']+1;
				$nGameSerId = $aData['nGameSerId']+1;
			}
			else
			{
				$nRound = $aData['nRound'] + 1;
				$nGameSerId = $aData['nGameSerId']+1;
			}
	
			$aGameNums_SQL_Array = array(
				'nGame'		=> (int)	$nGame,
				'sNo'			=> (string)	$nGameSerId,
				'nRound'		=> $nRound,
				'nStatus'		=> (int)	0,
				'nPreStartTime'	=> (int)	NOWTIME,
				'sPreStartTime'	=> (string)	NOWDATE,
				'sResult'		=> (string)	'',
				'sNums'		=> '',
				'nGameSerId'	=> $nGameSerId,
				'sDealer'		=> $aGameSetting[$nGame]['sDealer'],
			);
			$sSQL = 'INSERT INTO '. CLIENT_GAMES_NUMS .' ' . sql_build_array('INSERT', $aGameNums_SQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $oPdo->lastInsertId();

			$aOld_SQL_Array = array(
				'nPauseTime1'	=> NOWTIME,
				'sPauseTime1'	=> NOWDATE,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aOld_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aOld_SQL_Array);
			sql_query($Result);


			if(isset($aPrevBanker) && $nBankerContinue)
			{
				# 要從上前莊家沿用的情況
				# 上把莊家存在且下到第1墩/第3墩
				$aSQL_Array = array(
					'nContinue' => $aPrevBanker['nContinue']+1,
				);
				$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aPrevBanker['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nBankerId = $aPrevBanker['nId'];

				$aSQL_Array_BET = array(
					'sNo' 		=> $aNewData['sNo'],
					'nUid'		=> (int)	$aPrevBanker['nUid'],
					'nLid'		=> (int)	$aPrevBanker['nLid'],
					'nFsId'		=> (int)	$aPrevBanker['nFsId'],
					'sPerLink'		=> (string) $aPrevBanker['sPerLink'],
					'nContinue'		=> (int)	$aPrevBanker['nContinue']+1,
					'nStatus'		=> (int)	1,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nType0'		=> (int)	0,
					'sSiteId'		=> (string)	$aPrevBanker['sSiteId'],
				);
	
				$sSQL = 'INSERT INTO ' . CLIENT_GAMES_BANKER . ' ' . sql_build_array('INSERT', $aSQL_Array_BET );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array_BET);
				sql_query($Result);
				$nBankerBetId = $oPdo->lastInsertId();
				$aNewBanker = $aPrevBanker;
			}
			else
			{
				# 要從排莊列表抓新莊家的情況
				# 莊家打滿2墩/4墩
				# 沒有上把莊家
				$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET nStatus = 2
						WHERE	nStatus = 1 LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				sql_query($Result);

				$aSQL_Array = array(
					'sNo' => $aNewData['sNo'],
					'nStatus' => 1,
					'nContinue' => 1,
				);
				$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aBanker['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nBankerId = $aBanker['nId'];

				$aSQL_Array_BET = array(
					'sNo' 		=> $aNewData['sNo'],
					'nUid'		=> (int)	$aBanker['nUid'],
					'nLid'		=> (int)	$aBanker['nLid'],
					'nFsId'		=> (int)	$aBanker['nFsId'],
					'sPerLink'		=> (string) $aBanker['sPerLink'],
					'nContinue'		=> (int)	1,
					'nStatus'		=> (int)	1,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nType0'		=> (int)	0,
					'sSiteId'		=> (string)	$aBanker['sSiteId'],
				);
	
				$sSQL = 'INSERT INTO ' . CLIENT_GAMES_BANKER . ' ' . sql_build_array('INSERT', $aSQL_Array_BET );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array_BET);
				sql_query($Result);
				$nBankerBetId = $oPdo->lastInsertId();
				$aNewBanker = $aBanker;
			}
			$oPdo->commit();
			
			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']] = $aOld_SQL_Array;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aNewData['nId']] = $aNewData;
			
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aOld'][$aBanker['nId']] = $aBanker;
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aNew'][$nBankerId] = $aSQL_Array;
			$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$nBankerBetId] = $aSQL_Array_BET;

			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			}
			
			$nOpenTime = microtime(true);
			$nMsgCount = count($aJsonChat);
			$aJsonChat[$nOpenTime*10000] = array(
				'sType'	=> 'server',
				'bSticker' => '1',
				'nRefresh' => '1',
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
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											請當前莊家:'.$aNewBanker['sName0'].' 選擇是否要限注<br><br>
											請莊家選擇先打1或2墩<br><br>
											莊家可要求莊先開牌<br>
											莊家指定開牌順序<br>
											位置1、2、3、4<br>
											或者選擇<br>
											5.平開<br>
											6.挖心開<br>
											7.螃蟹開<br>
											8.蜈蚣開<br>
											9.大樓開<br>
											十秒內若莊家無回復<br>
											此局一律作廢
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
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nOpenTime*10000,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			$nLogCode = 8104401;# 新局 選擇限額
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aNewData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$sFile = dirname(dirname(dirname(dirname(__file__)))) .'/Bot/file/'.$nGame.'Data.txt';
			file_put_contents($sFile, json_encode($aNewData));

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	# 開始投注
	if($aJWT['a'] == 'NEXT1')
	{
		$aNewData = array();
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);

		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nFsId,
						sPerLink,
						sSiteId,
						nContinue,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nStatus = 1
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		$aBanker = $Result->fetch(PDO::FETCH_ASSOC);
		if($aBanker === false)
		{
			$nError = 1;
			$sMsg = '找不到莊家';
		}

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
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData['nStatus'] !== '0')
		{
			$nError = 1;
			$sMsg = '期數狀態錯誤';
		}
		
		if($nError === 0)
		{
			# 開始投注 投注結束自動進入配莊
			$aGameNums_SQL_Array = array(
				'nStartTime'	=> NOWTIME,
				'sStartTime'	=> NOWDATE,
				'nStatus'		=> 1,
				'sLimit'		=> $sLimit,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $aData['nId'];
			
			$oPdo->commit();
			// $oPdo->rollBack();

			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			}
			
			$nOpenTime = microtime(true);
			$nMsgCount = count($aJsonChat);
			if($sLimit === '')
			{
				$sBetMsg = '【當前莊家不限注】';				
			}
			else
			{
				$sBetMsg = str_replace('[:DOOR:]',aMETHODNAME[$sLimit],'【莊家限注[:DOOR:][:MONEY:]】');
				$sBetMsg = str_replace('[:MONEY:]',number_format($aGameSetting[$nGame]['nLimitBet']),$sBetMsg);
			}
			$aJsonChat[$nOpenTime*10000] = array(
				'sType'	=> 'server',
				'bSticker' => '0',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											'.$sBetMsg.'
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
			$aJsonChat[$nOpenTime*10000+1] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo img">
										<div>
											<div class="JqJumpBet"><img src="images/startBet.jpg"></div>
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
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nOpenTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nOpenTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
				trigger_error('訊息發送失敗:'.$sSocketMsg);
			}
			socket_close($oSocket);


			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aNewData['nId']] = $aNewData;
			$nLogCode = 8104406;# 開始投注
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aNewData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	# 開始集資
	if($aJWT['a'] == 'NEXT2')
	{
		$aBetData = array();
		$aNewData = array();
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);

		$sSQL = '	SELECT 	nId,sNo
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$aMasterBanker = array();
		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nMoney0,
						nMoney1,
						nMoney2,
						nType0,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				AND		nStatus = 1
				AND		nType0 = 0
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aMasterBanker = $aRows;
		}

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
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData['nStatus'] !== '1')
		{
			$nError = 1;
			$sMsg = '期數狀態錯誤';
		}
		
		if($nError === 0)
		{
			# 開始投注 投注結束自動進入配莊
			$aGameNums_SQL_Array = array(
				'nBetEndTime'	=> NOWTIME,
				'sBetEndTime'	=> NOWDATE,
				'nStatus'		=> 2,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $aData['nId'];

			$oPdo->commit();
			// $oPdo->rollBack();
			$sSQL = '	SELECT 	nId,sContent0,nMoney0,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_DATA.'.nUid LIMIT 1) as sName0
					FROM 		'. CLIENT_GAMES_DATA .'
					WHERE 	nGame = :nGame
					AND		sNo = :sNo
					ORDER BY	nId DESC';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aRows['nMoney0'] = (float)$aRows['nMoney0'];
				$aBetData[$aRows['sContent0']][$aRows['nId']] = $aRows;
			}

			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			}
			
			$nOpenTime = microtime(true);
			$nMsgCount = count($aJsonChat);
			
			$nCloseTime = microtime(true);
			$aJsonChat[$nCloseTime*10000] = array(
				'sType'	=> 'server',
				'bSticker' => '0',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo img">
										<div>
											<div><img src="images/stopBet.jpg"></div>
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
			$sBetMsg = '已停止下注，不再接受下注<br>牌局資訊：<br>局號'.$aData['sNo'].'<br>下注玩家<br>';
			$sBetMsg .= '【初A】';	
			$sTmpMsg = '';
			$nTmpCount = 0;
			$nTmpMoneySum = 0;
			if(isset($aBetData['A']))
			{
				$nTmpMoney = 0;
				foreach($aBetData['A'] as $LPnId => $LPaData)
				{
					$sBetMsg .= '<br/>['.$LPaData['sName0'].']下注初A|'.number_format($LPaData['nMoney0']);
					$nTmpMoney += $LPaData['nMoney0'];
					$nTmpCount++;
				}
				$sTmpMsg .= '<br>初A下注總金額：'.number_format($nTmpMoney);
				$nTmpMoneySum += $nTmpMoney;
			}					
			$sBetMsg .= '<br>【川B】';
			if(isset($aBetData['B']))
			{
				$nTmpMoney = 0;
				foreach($aBetData['B'] as $LPnId => $LPaData)
				{
					$sBetMsg .= '<br/>['.$LPaData['sName0'].']下注川B|'.number_format($LPaData['nMoney0']);
					$nTmpMoney += $LPaData['nMoney0'];
					$nTmpCount++;
				}
				$sTmpMsg .= '<br>川B下注總金額：'.number_format($nTmpMoney);
				$nTmpMoneySum += $nTmpMoney;
			}
			$sBetMsg .= '<br>【尾C】';
			if(isset($aBetData['C']))
			{
				$nTmpMoney = 0;
				foreach($aBetData['C'] as $LPnId => $LPaData)
				{
					$sBetMsg .= '<br/>['.$LPaData['sName0'].']下注尾C|'.number_format($LPaData['nMoney0']);
					$nTmpMoney += $LPaData['nMoney0'];
					$nTmpCount++;
				}
				$sTmpMsg .= '<br>尾C下注總金額：'.number_format($nTmpMoney);
				$nTmpMoneySum += $nTmpMoney;
			}
			$nBankerMax = $nTmpMoneySum>$aGameSetting[$nGame]['nBankerMax']?$aGameSetting[$nGame]['nBankerMax']:$nTmpMoneySum;
			$nBankerMin = $nBankerMax<$aGameSetting[$nGame]['nBankerMin']?$nBankerMax:$aGameSetting[$nGame]['nBankerMin'];
			$sBetMsg .= '<br>下注總表'.$sTmpMsg.'<br>下注總額：'.number_format($nTmpMoneySum).'<br>押注'.$nTmpCount.'<br><br>請莊家【'.$aMasterBanker['sName0'].'】設定打莊金額'.number_format($nBankerMin).'~'.number_format($nBankerMax);
			
			$aJsonChat[$nCloseTime*10000+1]['nSumMoney0'] = $nTmpMoneySum;
			$aJsonChat[$nCloseTime*10000+1]['nUid'] = '0';
			$aJsonChat[$nCloseTime*10000+1]['nRefresh'] = '1';
			$aJsonChat[$nCloseTime*10000+1]['sMsg'] = 
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
							<div class="dt-timeInner">'.date('H:i:s',$nOpenTime).'</div>
						</div>
					</div>
				</div>
			</div>';
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nCloseTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nCloseTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aNewData['nId']] = $aNewData;
			$nLogCode = 8104407;# 停止下注 開始集資
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aNewData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	# 集資結束
	if($aJWT['a'] == 'NEXT3')
	{
		$sMsg = '';
		$aNewData = array();
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);		

		$sSQL = '	SELECT 	nId
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		if(true)
		{


		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nBetEndTime,
						sBetEndTime,
						sResult,
						sNums,
						nRound,
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData === false || $aData['nStatus'] !== '2')
		{
			$nError = 1;
			$sMsg = '期數狀態錯誤';
		}

		$nSumBet = 0;
		$sSQL = '	SELECT 	nId,sContent0,nMoney0
				FROM 		'. CLIENT_GAMES_DATA .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				ORDER BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBet += $aRows['nMoney0'];
		}
		
		# 不確定寫法 再驗證
		$aMasterBanker = array();
		$aBankers = array();
		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nMoney0,
						nMoney1,
						nMoney2,
						nType0,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				AND		nStatus = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aRows['nMoney0'] = (float)$aRows['nMoney0'];
			if($aRows['nType0'] === '0')
			{
				$aMasterBanker = $aRows;
			}
			else
			{
				$aBankers[$aRows['nId']] = $aRows;
			}
		}

		if($aMasterBanker === array() || 
		($nSumBet > $aGameSetting[$nGame]['nBankerMin'] && $aMasterBanker['nMoney0'] < $aGameSetting[$nGame]['nBankerMin']) ||
		($nSumBet <= $aGameSetting[$nGame]['nBankerMin'] && $aMasterBanker['nMoney0'] != $nSumBet))# float
		{
			$nError = 1;
			$sMsg = '莊家尚未輸入打莊金額';			
		}
		else
		{
			if($aData !== false && $aData['nBetEndTime'] > NOWTIME)
			{
				$nError = 1;
				$sMsg = '發生未預期的錯誤';
				trigger_error('未預期的錯誤');# 下注時間還沒結束，莊家不該有額度
			}
		}

		if($nError === 0)
		{
			# 待結算
			$aGameNums_SQL_Array = array(
				'nBankerEndTime'	=> NOWTIME,
				'sBankerEndTime'	=> NOWDATE,
				'nStatus'		=> 3,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $aData['nId'];
			
			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			}
			
			$nBankerEndTime = microtime(true);
			$nMsgCount = count($aJsonChat);

			$sBetMsg = '莊家資訊<br/>莊家<br/>'.$aMasterBanker['sName0'].'|'.number_format($aMasterBanker['nMoney0']).'<br/>配莊';
			$nI = 1;
			// trigger_error(print_r($aBankers,true));
			foreach($aBankers as $LPnId => $LPaData)
			{
				$sBetMsg .= '<br/>配莊'.$nI.':'.$LPaData['sName0'].'|'.number_format($LPaData['nMoney0']);
				$nI++;
			}
			$aJsonChat[$nBankerEndTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo img">
										<div>
											<div><img src="images/stopBanker.jpg"></div>
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
			$aJsonChat[$nBankerEndTime*10000+1] = array(
				'sType'	=> 'server',
				'bSticker' => '1',
				'nRefresh' => '1',
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
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											'.$sBetMsg.'
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
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nBankerEndTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nBankerEndTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aNewData['nId']] = $aNewData;
			$nLogCode = 8104405;# 集資結束
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aNewData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		}

	}

	# 待過帳 發ajax自動過帳
	if($aJWT['a'] == 'NEXT3_5')
	{
		// print_r($_POST);
		// exit;
		$aNewData = array();
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aJs = array(
			'plugins/js/end_games/run_page_settle.js',
		);
		$aValue = array(
			'a'	=> 'SETTLE',
			't'	=> NOWTIME,
		);
		$aPhpData = array(
			'sPhpJWT' => sys_jwt_encode($aValue),
			'sPhpUrl' => './Bot/settle.php',
			'nPhpGame' => $nGame,
			'nPhpNoId' => 0,
			'sPhpLocation' => sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame,
		);		

		$aFileRaw = !empty($_FILES['aFile']['name'][0])?$_FILES['aFile']:array('name'=>array());
		$sLang	= $aSystem['sLang'];

		$sSQL = '	SELECT 	nId,sNo
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);		

		$nSumBet = 0;
		$sSQL = '	SELECT 	nId,sContent0,nMoney0
				FROM 		'. CLIENT_GAMES_DATA .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				ORDER BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBet += $aRows['nMoney0'];
		}
		
		if(true)
		{

		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nId,
						nGame,
						sNo,
						nStatus,
						nStartTime,
						sStartTime,
						nBetEndTime,
						sBetEndTime,
						sResult,
						sNums,
						nRound,
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData === false || $aData['nStatus'] !== '3')
		{
			$nError = 1;
			$sMsg = '期數狀態錯誤';
		}
		
		# 不確定寫法 再驗證
		$aMasterBanker = array();
		$aBankers = array();
		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nMoney0,
						nMoney1,
						nMoney2,
						nType0,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				AND		nStatus = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aRows['nMoney0'] = (float)$aRows['nMoney0'];
			if($aRows['nType0'] === '0')
			{
				$aMasterBanker = $aRows;
			}
			else
			{
				$aBankers[$aRows['nId']] = $aRows;
			}
		}

		if($aMasterBanker === array() || ($aMasterBanker['nMoney0'] == 0 && $nSumBet > 0))# float
		{
			$nError = 1;
			$sMsg = '莊家尚未填寫額度';
		}
		else
		{
			if($aData !== false && $aData['nBetEndTime'] > NOWTIME)
			{
				$nError = 1;
				$sMsg = '發生未預期的錯誤';
				trigger_error('未預期的錯誤');# 下注時間還沒結束，莊家不該有額度
			}
		}

		if($nError === 0)
		{
			// echo '<pre>';
			// print_r($aFileRaw);
			$bImgStatus = fnUploadImg($aData,$aFileRaw);
			if($bImgStatus === false)
			{
				$nError = 1;
				$sMsg = '圖片格式錯誤';
			}
		}

		if($nError === 0)
		{
			$aResult = array(
				'Z' => $nResult0,
				'A' => $nResult1,
				'B' => $nResult2,
				'C' => $nResult3,
			);
			$sJsonResult = json_encode($aResult);
			# 待結算
			$aGameNums_SQL_Array = array(
				'nEndTime'	=> NOWTIME,
				'sEndTime'	=> NOWDATE,
				'sNums'		=> $sJsonResult,
				'sResult'		=> $sJsonResult,
				'nStatus'		=> 3,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $aData['nId'];

			$oPdo->commit();
			
			# 產生開關盤訊息
			// $aJsonChat = array();
			// if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			// {
			// 	$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
			// 	$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			// }
			
			// $nBankerEndTime = microtime(true);
			// $nMsgCount = count($aJsonChat);

			// $sBetMsg = '莊家資訊<br/>莊家<br/>'.$aMasterBanker['sName0'].'|'.$aMasterBanker['nMoney0'].'<br/>配莊';
			// $nI = 1;
			// foreach($aBankers as $LPnId => $LPaData)
			// {
			// 	$sBetMsg .= '<br/>配莊'.$nI.':'.$LPaData['sName0'].'|'.$LPaData['nMoney0'];
			// 	$nI++;
			// }
			// $aJsonChat[$nBankerEndTime*10000] = array(
			// 	'nUid' => '0',
			// 	'sMsg' => '
			// 	<div class="betmsg font-xxs msgSystem">
			// 		<div class="coverbox">
			// 			<div class="td imgbox float-left">
			// 				<img class="img-circle" src="images/headImg/0.jpg">
			// 			</div>
			// 			<div class="td float-left ifmeright msgSystemInf">
			// 				<div class="dtbox">
			// 					<div class="dt-nickname">系統機器人</div>
			// 				</div>
			// 				<div class="betmsgBlock arrow">
			// 					<div class="betinfobox">
			// 						<div class="betinfo">
			// 							<div>
			// 								'.$sBetMsg.'
			// 							</div>
			// 						</div>
			// 					</div>
			// 				</div>
			// 				<div class="dt-time">
			// 					<div class="dt-timeInner">'.NOWCLOCK.'</div>
			// 				</div>
			// 			</div>
			// 		</div>
			// 	</div>',
			// );
			// while($nMsgCount > MSGCOUNT)
			// {
			// 	$nMsgCount--;
			// 	unset($aJsonChat[key($aJsonChat)]);
			// }
			// $sJsonChat = json_encode($aJsonChat);
			// $sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			// file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aNewData['nId']] = $aNewData;
			$nLogCode = 8104408;# 待過帳
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aNewData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aPhpData['nPhpNoId'] = $aData['nId'];

			echo '<script>';
			foreach($aPhpData as $LPsPhpKey => $LPsPhpData)
			{
				echo 'var '.$LPsPhpKey.' = \''.$LPsPhpData.'\';';
			}
			echo '</script>';
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
		$oPdo->commit();
		// $oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		}

	}

	# 退單打和
	if($aJWT['a'] == 'NEXT99')
	{
		$aNewData = array();
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];

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
						nUse
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$aData['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		
		if($aData['nStatus'] <= 3)
		{
			# 待結算
			$aGameNums_SQL_Array = array(
				'nEndTime'	=> NOWTIME,
				'sEndTime'	=> NOWDATE,
				'nStatus'		=> 99,
				'nCancel'	=> 1,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aGameNums_SQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aGameNums_SQL_Array);
			sql_query($Result);
			$aNewData = $aGameNums_SQL_Array;
			$aNewData['nId'] = $aData['nId'];

			$oPdo->commit();

			$nLogCode = 8104404;
			$aActData = array(
				'aOrder'	=> array(),
				'aBanker'	=> array(),
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

			# 莊家退款
			$sSQL = '	SELECT	nId,
							nMoney0,
							nUid,
							nLid,
							nFsId,
							sPerLink,
							sSiteId,
							nType0
					FROM		'. CLIENT_GAMES_BANKER .'
					WHERE		nGame = :nGame
					AND		sNo = :sNo';
			$Result2 = $oPdo->prepare($sSQL);
			$Result2->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result2->bindValue(':sNo', $aData['sNo'], PDO::PARAM_STR);
			sql_query($Result2);
			while($aRows = $Result2->fetch(PDO::FETCH_ASSOC))
			{
				$aActData['aBanker'][$aRows['nId']] = $aRows;
				if(!isset($aActData['aMember'][$aRows['nUid']]))
				{
					$aActData['aMember'][$aRows['nUid']] = array(
						'nId' => 0,
						'nMoney' => 0,
					);
				}
				$aActData['aMember'][$aRows['nUid']]['nId'] = $aRows['nId'];
				$aActData['aMember'][$aRows['nUid']]['nMoney'] += $aRows['nMoney0'] + $aRows['nMoney0'] * ($aGameSetting['nBankerFee'] / 100);
			}

			# 注單取消 #
			foreach ($aActData['aBanker'] as $LPnId => $LPaData)
			{
				$oPdo->beginTransaction();

				if($LPaData['nType0'] === '0')
				{
					$sSQL = '	SELECT	nId,
									nMoney0,
									nStatus,
									nDone
							FROM		'. CLIENT_GAMES_BANKER .'
							WHERE		nId = :nId
							AND		nDone = 0
							LIMIT		1
							FOR		UPDATE';
					$Result2 = $oPdo->prepare($sSQL);
					$Result2->bindValue(':nId', $LPnId, PDO::PARAM_INT);
					sql_query($Result2);
					$aNowData = $Result2->fetch(PDO::FETCH_ASSOC);

					$aSQL_Array = array(
						'nDone'		=> 	0,
						'sNo'			=> 	'',
						'nMoney0'		=> (int)	0,
						'nMoney1'		=> (int)	0,
						'nMoney2'		=> (int)	0,
						'nMoney3'		=> (int)	0,
						'nStatus'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
		
					$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result2 = $oPdo->prepare($sSQL);
					$Result2->bindValue(':nId', $LPnId, PDO::PARAM_INT);
					sql_build_value($Result2, $aSQL_Array);
					sql_query($Result2);
				
					$aEditLog[CLIENT_GAMES_BANKER]['aOld'][$LPnId] = $LPaData;
					$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$LPnId] = $aSQL_Array;
				}
				else
				{
					$sSQL = '	SELECT	nId,
									nMoney0,
									nStatus,
									nDone
							FROM		'. CLIENT_GAMES_BANKER .'
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
					$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result2 = $oPdo->prepare($sSQL);
					$Result2->bindValue(':nId', $aNowData['nId'], PDO::PARAM_INT);
					sql_build_value($Result2, $aSQL_Array);
					sql_query($Result2);

					$aEditLog[CLIENT_GAMES_BANKER]['aOld'][$LPnId] = $LPaData;
					$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$LPnId] = $aSQL_Array;
				}
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
						'sNoTag'		=> (string) $nGame.'_'.$aData['sNo'],
					);
					DoLogAcc($aAccLog);
				}

				$aEditLog[CLIENT_USER_MONEY]['aOld'][$LPnUid] = $aOldMoney;
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$LPnUid] = $aSQL_Array;

				$oPdo->commit();
			}

			#紀錄動作 - 新增
			$aEditLog[CLIENT_GAMES_NUMS]['aOld'][$aData['nId']] = $aData;
			$aEditLog[CLIENT_GAMES_NUMS]['aNew'][$aData['nId']] = $aNewData;
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

			$aJsonChat = array();
			$nMsgCount = 0;
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'bSticker' => '1',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo img">
										<div>
											<img src="images/stopBet.jpg">
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
			$aJsonChat[$nNowTime*10000+1] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											本局取消											
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
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);
			
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
			// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
			// $aJumpMsg['0']['sIcon'] = 'success';
			// $aJumpMsg['0']['sMsg'] = UPTV;
			// $aJumpMsg['0']['sShow'] = 1;
			// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
			// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	# 換荷官
	if($aJWT['a'] == 'NEWDEALER')
	{
		$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET sDealer = :sDealer
				WHERE	nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sDealer', $aAdm['nId'], PDO::PARAM_INT);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);

		header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
		exit;
		// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
		// $aJumpMsg['0']['sIcon'] = 'success';
		// $aJumpMsg['0']['sMsg'] = UPTV;
		// $aJumpMsg['0']['sShow'] = 1;
		// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
		// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	# 顯示排莊列表
	if($aJWT['a'] == 'SHOWBANKERS')
	{
		$aBankers = array();
		$sSQL = '	SELECT 	nId,nUid,nLid,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nGame = :nGame
				AND		nStatus = 0
				ORDER BY nCreateTime ASC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aBankers[$aRows['nId']] = $aRows;
		}

		# 產生開關盤訊息
		$aJsonChat = array();
		if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
		{
			$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
			$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
			$nMsgCount = count($aJsonChat);
		}
		
		$nNowTime = microtime(true);
		$sBetMsg = '排莊列表';
		$nI = 1;
		foreach($aBankers as $LPaData)
		{
			$sBetMsg .= '<br>'.$nI.'.['.$LPaData['sName0'].']('.number_format($LPaData['nMoneyNow']).')';
			$nI++;
		}
		$aJsonChat[$nNowTime*10000] = array(
			'sType'	=> 'server',
			'nRefresh' => '1',
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
							<div class="betinfobox">
								<div class="betinfo">
									<div>
										'.$sBetMsg.'
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

		while($nMsgCount > MSGCOUNT)
		{
			$nMsgCount--;
			unset($aJsonChat[key($aJsonChat)]);
		}
		$sJsonChat = json_encode($aJsonChat);
		$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
		file_put_contents($sFile, $sJsonChat,LOCK_EX);		
		
		$aSocketMsg[] = array(
			'nGame' => '1001',
			'nRefresh' => '1',
			'nUid' => '0',
			'sKey' => $nNowTime*10000,
		);
		$sSocketMsg = json_encode($aSocketMsg);
		socket_connect($oSocket,$sServerIp,$nPort);
		if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
			trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
		}
		socket_close($oSocket);

		header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
		exit;
		// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
		// $aJumpMsg['0']['sIcon'] = 'success';
		// $aJumpMsg['0']['sMsg'] = UPTV;
		// $aJumpMsg['0']['sShow'] = 1;
		// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame.'&nShowBanker=1';
		// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	# 替補當前莊家
	if($aJWT['a'] == 'NEWBANKER')
	{
		$nError = 0;
		$sSQL = '	SELECT 	nId,nStatus,sNo
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData['nStatus'] !== '0')
		{
			$nError = 1;
			$sMsg = '已有投注無法替換';
		}
		if($aData['nStatus'] == '4' || $aData['nStatus'] == '99')
		{
			$nError = 1;
			$sMsg = '先開新局才能替換莊家';			
		}

		$sSQL = '	SELECT 	nId,
						nUid,
						nLid,
						nFsId,
						sPerLink,
						sSiteId,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,
						(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nGame = :nGame 
				AND 		nStatus = 0 
				ORDER BY nCreateTime ASC 
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aNewBanker = $Result->fetch(PDO::FETCH_ASSOC);
		if($aNewBanker === false)
		{
			$nError = 1;
			$sMsg = '無人排莊';
		}

		if($nError == 0)
		{
			$oPdo->beginTransaction();
			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET nStatus = 2
					WHERE	nGame = :nGame AND nStatus = 1 LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			sql_query($Result);

			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET nStatus = 1,nContinue = 1,sNo = :sNo
					WHERE	nId = :nId';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId',$aNewBanker['nId'],PDO::PARAM_INT);
			$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_STR);
			sql_query($Result);

			echo 1;
			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER . ' SET nStatus = 3
					WHERE	nGame = :nGame
					AND	sNo = :sNo';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_STR);
			sql_query($Result);
			echo 2;
			$aSQL_Array_BET = array(
				'sNo' 		=> $aData['sNo'],
				'nUid'		=> (int)	$aNewBanker['nUid'],
				'nLid'		=> (int)	$aNewBanker['nLid'],
				'nFsId'		=> (int)	$aNewBanker['nFsId'],
				'sPerLink'		=> (string) $aNewBanker['sPerLink'],
				'nContinue'		=> (int)	1,
				'nStatus'		=> (int)	1,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nType0'		=> (int)	0,
				'sSiteId'		=> (string)	$aNewBanker['sSiteId'],
			);
			$sSQL = 'INSERT INTO ' . CLIENT_GAMES_BANKER . ' ' . sql_build_array('INSERT', $aSQL_Array_BET );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array_BET);
			sql_query($Result);
			$nBankerBetId = $oPdo->lastInsertId();
			// $aNewBanker = $aBanker;
			echo 3;
			$oPdo->commit();

			$aBankers = array();
			$sSQL = '	SELECT 	nId,nUid,nLid,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
					FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
					WHERE 	nGame = :nGame
					AND		nStatus = 0
					ORDER BY nCreateTime ASC';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aBankers[$aRows['nId']] = $aRows;
			}

			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
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
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											替補莊家為：'.$aNewBanker['sName0'].'('.$aNewBanker['nMoneyNow'].')
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
			$sBetMsg = '排莊列表';
			$nI = 1;
			foreach($aBankers as $LPaData)
			{
				$sBetMsg .= '<br>'.$nI.'.['.$LPaData['sName0'].']('.$LPaData['nMoneyNow'].')';
				$nI++;
			}
			$aJsonChat[$nNowTime*10000+1] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
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
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											'.$sBetMsg.'
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
			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);			

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
		}
		else
		{
			// $oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		// $aJumpMsg['0']['sTitle'] = RIGHTMSG;
		// $aJumpMsg['0']['sIcon'] = 'success';
		// $aJumpMsg['0']['sMsg'] = UPTV;
		// $aJumpMsg['0']['sShow'] = 1;
		// $aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
		// $aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	# 莊家最低額度打莊
	if($aJWT['a'] == 'BANKERLEAST')
	{
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		$nSumBet = 0;
		$nSumBanker = 0;

		$sSQL = '	SELECT 	nId,sNo
				FROM 		'. CLIENT_GAMES_NUMS .'
				WHERE 	nGame = :nGame
				ORDER BY	nId DESC
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT 	nId,sContent0,nMoney0
				FROM 		'. CLIENT_GAMES_DATA .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				ORDER BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBet += $aRows['nMoney0'];
		}

		$sSQL = '	SELECT 	nId,
						sNo,
						nUid,
						nStatus,
						nMoney0,
						nMoney1,
						nType0,
						sSiteId,
						(SELECT sAccount FROM '.CLIENT_USER_DATA.' WHERE '.CLIENT_USER_DATA.'.nId = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as sAccount
				FROM  	'.CLIENT_GAMES_BANKER.'
				WHERE 	nType0 = 0
				AND		sNo = :sNo
				AND		nStatus = 1
				AND		nDone = 0
				ORDER BY nId ASC
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aData['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		$aBanker = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT 	nId,nUid,nContinue,nBuyContinue
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nStatus = 1
				ORDER BY nId DESC
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		$aBankerContinue = $Result->fetch(PDO::FETCH_ASSOC);

		$oPdo->beginTransaction();
		$sSQL = '	SELECT 	nMoney
				FROM  	'.CLIENT_USER_MONEY.'
				WHERE 	nUid = :nUid
				LIMIT 1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aBanker['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);
		$aEditLog[CLIENT_USER_MONEY]['aOld'][$aBanker['nUid']] = $aUserMoney;

		if($aUserMoney === false)
		{
			$nError = 1;
			$sBetMsg = NODATA;
		}
		if($aBanker['nMoney0'] > 0)
		{
			$nError = 1;
			$sBetMsg = '莊家已有額度';
		}

		if($nError)
		{
			$oPdo->rollBack();
		}
		else
		{
			$nLeastBanker = ($aBankerContinue['nBuyContinue']>0)?$aGameSetting['nBankerPlusMin']:$aSystem['aParam']['nBankerMin'];
			// 投注額>莊家最低額度=正常
			// 投注額<莊家最低額度=例外
			if($nSumBet >= $nLeastBanker)
			{
				if($aUserMoney['nMoney'] >= $nLeastBanker * (1+($aGameSetting['nBankerFee']/100)))
				{
					$nSumBanker = $nLeastBanker;
					$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
				}
				else
				{
					$nSumBanker = floor($aUserMoney['nMoney'] / (1+($aGameSetting['nBankerFee']/100)));
					$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
				}
			}
			else
			{
				if($aUserMoney['nMoney'] >= $nSumBet * (1+($aGameSetting['nBankerFee']/100)))
				{
					$nSumBanker = $nSumBet;
					$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
				}
				else
				{
					$nSumBanker = floor($aUserMoney['nMoney'] / (1+($aGameSetting['nBankerFee']/100)));
					$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
				}
			}
			// $nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);

			$aNewMoney = array(
				'Money' => (float) $aUserMoney['nMoney'] - $nSumBanker - $nMoneyFee
			);
			$aSQL_Array = oTransfer::PointUpdate($aBanker['nUid'],$aNewMoney);
			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aBanker['nUid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$aBanker['nUid']] = $aSQL_Array;
			}
			$oPdo->commit();
		
			$aSQL_Array = array(
				'sNo'			=> (string)	$aData['sNo'],
				'nMoney0'		=> (int)	$nSumBanker,
				'nMoney2'		=> (int)	$nSumBanker,
				'nStatus'		=> (int)	1,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aBanker['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$nBankerId = $aBanker['nId'];
			$aEditLog[CLIENT_GAMES_BANKER]['aOld'][$nBankerId] = $aBanker;
			$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$nBankerId] = $aSQL_Array;		

			$aSQL_Array = array(
				'nUid'		=> (int)	$aBanker['nUid'],
				'nKid'		=> (int)	$aBanker['nId'],
				'nType0'		=> (int)	1,
				'nType1'		=> (int)	321,
				'nType2'		=> (int)	0,
				'nBefore'		=> (float)	$aUserMoney['nMoney'],
				'nDelta'		=> (float)	$nSumBanker * -1,
				'nAfter'		=> (float)	$aUserMoney['nMoney'] - $nSumBanker,
				'sParams'		=> (string)	'',
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nCreateDay'	=> (int)	strtotime('today'),
				'sSiteId'		=> (string)	$aBanker['sSiteId'],
				'sNoTag'		=> (string) $nGame.'_'.$aData['sNo'],
			);
			DoLogAcc($aSQL_Array);
			$aActionLog = array(
				'nWho'		=> (int)	$aBanker['nUid'],
				'nWhom'		=> (int)	$aBanker['nUid'],
				'sWhomAccount'	=> (string)	$aBanker['sAccount'],
				'nKid'		=> (int)	$aBanker['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	7101003,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$aParams = array(
				'nUid'	=> $aBanker['nUid'],
				'nBanker'	=> $aBanker['nId'],
				'nMoney0'	=> $nSumBanker,
				'nBankerFee'=> $nMoneyFee,
			);
			$aAccLogArray = array(
				'nUid'		=> (int)	$aBanker['nUid'],
				'nKid'		=> (int)	$aBanker['nId'],
				'nType0' 		=> (int)	1,
				'nType1' 		=> (int)	322,
				'nType2' 		=> (int)	0,
				'nType3' 		=> (int)	1,
				'nBefore' 		=> (float)	$aUserMoney['nMoney'] - $nSumBanker,
				'nDelta' 		=> (float)	-1 * $nMoneyFee,
				'nAfter' 		=> (float)	$aUserMoney['nMoney'] - $nSumBanker - $nMoneyFee,
				'sParams' 		=> (string)	json_encode($aParams),
				'nCreateTime' 	=> (int)	NOWTIME,
				'sCreateTime' 	=> (string)	NOWDATE,
				'nCreateDay' 	=> (int)	strtotime('today'),
				'sSiteId'		=> (string)	$aBanker['sSiteId'],
				'sNoTag'		=> (string) $nGame.'_'.$aData['sNo'],
			);
			DoLogAcc($aAccLogArray);
			// $oPdo->rollBack();

			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}

			$nSumMoney = $nSumBet-$nSumBanker;
			$sBetMsg = str_replace('[:MONEY:]',number_format($nSumBanker),'開放配莊<br/>莊家額度【[:MONEY:]】設定完成<br/>剩餘額度【[:MONEY1:]】'.'<br/>');
			$sBetMsg = str_replace('[:MONEY1:]',number_format($nSumMoney),$sBetMsg);
			# 打莊成功/配莊成功訊息
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => 0,
				'sMsg' => '<div class="betmsg font-xxs msgMember">
						<div class="coverbox">
							<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
							<div class="td float-left ifmeright msgMemberInf">
								<div class="dtbox">
									<div class="dt-nickname">系統機器人</div>
								</div>
								<div class="betmsgBlock arrow">
									<div class="betinfobox">
										<div class="betinfo">
											<div>'.$aBanker['sAccount'].'('.number_format($nSumBanker).')'.'<br>'.
											$sBetMsg.'</div>
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
			$aJsonChat[$nNowTime*10000+1] = array(
				'sType'	=> 'server',
				'bSticker' => '0',
				'nRefresh' => '1',
				'nUid' => '0',
				'sMsg' => '<div class="betmsg font-xxs msgSystem">
					<div class="coverbox">
						<div class="td imgbox float-left">
							<img class="img-circle" src="images/headImg/0.jpg">
						</div>
						<div class="td float-left ifmeright msgSystemInf">
							<div class="dtbox">
								<div class="dt-nickname">系統機器人</div>
							</div>
							<div class="betmsgBlock arrow">
								<div class="betinfobox">
									<div class="betinfo img">
										<div>
											<div class="JqJumpBet"><img src="images/startBanker.jpg"></div>
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

			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+1,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);
		}

		// header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
		// exit;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = $sBetMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	# 莊家買官
	if($aJWT['a'] == 'BANKERCONTINUE')
	{
		$nError = 0;
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];

		$sSQL = '	SELECT 	nId,nUid,nContinue,nBuyContinue,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nStatus = 1
				ORDER BY nId DESC
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		$aBanker = $Result->fetch(PDO::FETCH_ASSOC);

		if($aBanker['nContinue'] !== '2')
		{
			$nError = 1;
			$sMsg = '第二墩才能決定買官';
		}

		if($aBanker['nBuyContinue'] > 0)
		{
			$nError = 1;
			$sMsg = '操作失敗<br>已買官';
		}

		$oPdo->beginTransaction();
		
		$sSQL = '	SELECT 	nMoney
				FROM  	'.CLIENT_USER_MONEY.'
				WHERE 	nUid = :nUid
				LIMIT 1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aBanker['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);
		$aEditLog[CLIENT_USER_MONEY]['aOld'][$aBanker['nUid']] = $aUserMoney;

		if($aUserMoney['nMoney'] < $aGameSetting['nBankerPlusFee'] + $aGameSetting['nBankerPlusMin'])
		{
			$nError = 1;
			$sMsg = '餘額不足'.($aGameSetting['nBankerPlusFee'] + $aGameSetting['nBankerPlusMin']);
		}

		$aNewMoney = array(
			'Money' => (float) $aUserMoney['nMoney'] - $aGameSetting['nBankerPlusFee']
		);
		$aSQL_Array = oTransfer::PointUpdate($aBanker['nUid'],$aNewMoney);
		if($aSQL_Array !== false && $nError == 0)
		{
			$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nUid = :nUid LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aBanker['nUid'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$aEditLog[CLIENT_USER_MONEY]['aNew'][$aBanker['nUid']] = $aSQL_Array;

			$oPdo->commit();

			$aSQL_Array = array(
				'nBuyContinue' => $aBanker['nBuyContinue']+1,
			);
			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aBanker['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aOld'][$aBanker['nId']] = $aBanker;
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aNew'][$aBanker['nId']] = $aSQL_Array;

			$aAccLog = array(
				'nUid' 		=> (int)	$aBanker['nUid'],
				'nKid' 		=> (int)	$aBanker['nId'],
				'nType0' 		=> (int)	1,
				'nType1' 		=> (int)	325,
				'nType2' 		=> (int)	0,
				'nBefore' 		=> (float)	$aUserMoney['nMoney'],
				'nDelta' 		=> (float)	-1 * $aGameSetting['nBankerPlusFee'],
				'nAfter' 		=> (float)	$aUserMoney['nMoney'] - $aGameSetting['nBankerPlusFee'],
				'sParams' 		=> (string)	'',
				'nCreateTime' 	=> (int)	NOWTIME,
				'sCreateTime' 	=> (string)	NOWDATE,
				'nCreateDay' 	=> (int)	strtotime('today'),
			);
			DoLogAcc($aAccLog);

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$aBanker['nUid'],
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aBanker['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104422,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			$sContinueMsg = $aBanker['sName0'].'買官，扣除'.$aGameSetting['nBankerPlusFee'];
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => 0,
				'sMsg' => '<div class="betmsg font-xxs msgMember">
						<div class="coverbox">
							<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
							<div class="td float-left ifmeright msgMemberInf">
								<div class="dtbox">
									<div class="dt-nickname">系統機器人</div>
								</div>
								<div class="betmsgBlock arrow">
									<div class="betinfobox">
										<div class="betinfo">
											<div>'.$sContinueMsg.'</div>
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
			
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);
			
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
		}
		else
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}		
	}

	# 莊家派彩
	if($aJWT['a'] == 'RESULT0')
	{
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		$aNewMemberData = array('nMoney0'=>0);
				
		$sSQL = '	SELECT	nId,
						nUid,
						sNo,
						sSiteId,
						(SELECT sAccount FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as sAccount
				FROM		'. CLIENT_GAMES_BANKER .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nBankerId, PDO::PARAM_INT);
		sql_query($Result);
		$aMasterBanker = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT 	nId,sNums
				FROM  	'. CLIENT_GAMES_NUMS .'
				WHERE 	sNo = :sNo';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aMasterBanker['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$aNums = json_decode($aData['sNums'],true);
		$aNums['Z'] = 1;
		
		$aSQL_Array = array(
			'sNums' 		=> json_encode($aNums),
		);
		$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nId = :nId LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$sSQL = '	SELECT 	nId,nMoney0
				FROM  	'. CLIENT_JACKPOT_SET .'
				WHERE 	nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aJackpot = $Result->fetch(PDO::FETCH_ASSOC);

		$nJackpotMoney = $aJackpot['nMoney0'] * (100 - $aGameSetting['nReturnJackpot']) / 100;
		$nReturnJackpot = $aJackpot['nMoney0'] * $aGameSetting['nReturnJackpot'] / 100;
		$nJackpotMoney = fnCeilBit($nJackpotMoney,3);

		$aNewMemberData['nMoney0'] += $nJackpotMoney;
		$aAccLogArray = array(
			'nUid' 		=> (int)	$aMasterBanker['nUid'],
			'nKid' 		=> (int)	$aMasterBanker['nId'],
			'nType0' 		=> (int)	1,
			'nType1' 		=> (int)	309,
			'nType2' 		=> (int)	0,
			'nBefore' 		=> (float)	0,
			'nDelta' 		=> (float)	$nJackpotMoney,
			'nAfter' 		=> (float)	0,
			'sParams' 		=> (string)	'',
			'nCreateTime' 	=> (int)	NOWTIME,
			'sCreateTime' 	=> (string)	NOWDATE,
			'nCreateDay' 	=> (int)	strtotime('today'),
			'sSiteId'		=> (string)	$aMasterBanker['sSiteId'],
			'sNoTag'		=> (string) $nGame.'_'.$aMasterBanker['sNo'],
		);

		$aSQL_Array = array(
			'nGame'		=> $nGame,
			'sNo'			=> $aMasterBanker['sNo'],
			'nLottryId'		=> $aMasterBanker['nId'],
			'nUid'		=> $aMasterBanker['nUid'],
			'nBefore'		=> $aJackpot['nMoney0'],
			'nDelta'		=> -1 * $nJackpotMoney,
			'nAfter'		=> $nReturnJackpot,
			'nType0'		=> 1,
			'nCreateTime'	=> NOWTIME,
			'sCreateTime'	=> NOWDATE,
		);

		$sSQL = 'INSERT INTO '. CLIENT_JACKPOT_LOG .' ' . sql_build_array('INSERT', $aSQL_Array );
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$aSQL_Array = array(
			'nMoney0' 		=> $nReturnJackpot,
			'nUpdateTime'	=> NOWTIME,
			'sUpdateTime'	=> NOWDATE,
		);
		$sSQL = '	UPDATE '. CLIENT_JACKPOT_SET . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nId = :nId LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aJackpot['nId'], PDO::PARAM_INT);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$oPdo->beginTransaction();
		$sSQL = '	SELECT 	nMoney
				FROM  	'.CLIENT_USER_MONEY.'
				WHERE 	nUid = :nUid
				LIMIT 1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aMasterBanker['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);
		$aEditLog[CLIENT_USER_MONEY]['aOld'][$aMasterBanker['nUid']] = $aUserMoney;
		$aAccLogArray['nBefore'] = $aUserMoney['nMoney'];

		if($aUserMoney === false)
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aNewMoney = array(
				'Money' => (float) $aUserMoney['nMoney'] + $nJackpotMoney
			);
			$aAccLogArray['nAfter'] = $aNewMoney['Money'];
			$aSQL_Array = oTransfer::PointUpdate($aMasterBanker['nUid'],$aNewMoney);
			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aMasterBanker['nUid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$aMasterBanker['nUid']] = $aSQL_Array;
			}
			$oPdo->commit();

			DoLogAcc($aAccLogArray);
			$aActionLog = array(
				'nWho'		=> (int)	$aMasterBanker['nUid'],
				'nWhom'		=> (int)	$aMasterBanker['nUid'],
				'sWhomAccount'	=> (string)	$aMasterBanker['sAccount'],
				'nKid'		=> (int)	$aMasterBanker['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104421,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			$sJackpotMoney = number_format($nJackpotMoney,3);
			$sJackpotMsg = '💥恭喜'.$aMasterBanker['sAccount'].'得彩💥<br>彩金'.$sJackpotMoney;
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => 0,
				'sMsg' => '<div class="betmsg font-xxs msgMember">
						<div class="coverbox">
							<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
							<div class="td float-left ifmeright msgMemberInf">
								<div class="dtbox">
									<div class="dt-nickname">系統機器人</div>
								</div>
								<div class="betmsgBlock arrow">
									<div class="betinfobox">
										<div class="betinfo">
											<div>'.$sJackpotMsg.'</div>
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
			$aJsonChat[$nNowTime*10000+1] = $aJsonChat[$nNowTime*10000];
			$aJsonChat[$nNowTime*10000+2] = $aJsonChat[$nNowTime*10000];
			
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+1,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+2,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			$sShowScreen = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt');
			$aShowScreen = !empty($sShowScreen)?json_decode($sShowScreen,true):array();	
			
			$aShowScreen[$nNowTime]['sMsg'] = '<div>'.$aMasterBanker['sAccount'].'<br>'.$sJackpotMsg.'</div>';
			$aShowScreen[$nNowTime]['nPlay'] = 0;
			$aShowScreen[$nNowTime]['nType0'] = 1;
			$aShowScreen[$nNowTime+1] = $aShowScreen[$nNowTime];
			$aShowScreen[$nNowTime+2] = $aShowScreen[$nNowTime];
			$sShowScreen = json_encode($aShowScreen);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt';
			file_put_contents($sFile, $sShowScreen,LOCK_EX);
			
			// $oPdo->rollBack();
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
		}
	}

	# 莊家派彩1/5
	if($aJWT['a'] == 'RESULT0FIFTH')
	{
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		$aNewMemberData = array('nMoney0'=>0);
				
		$sSQL = '	SELECT	nId,
						nUid,
						sNo,
						sSiteId,
						(SELECT sAccount FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid LIMIT 1) as sAccount
				FROM		'. CLIENT_GAMES_BANKER .'
				WHERE		nId = :nId
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nBankerId, PDO::PARAM_INT);
		sql_query($Result);
		$aMasterBanker = $Result->fetch(PDO::FETCH_ASSOC);
		
		$sSQL = '	SELECT 	nId,sNums
				FROM  	'. CLIENT_GAMES_NUMS .'
				WHERE 	sNo = :sNo';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aMasterBanker['sNo'],PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);

		$aNums = json_decode($aData['sNums'],true);
		$aNums['Z'] = 1;
		
		$aSQL_Array = array(
			'sNums' 		=> json_encode($aNums),
		);
		$sSQL = '	UPDATE '. CLIENT_GAMES_NUMS . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nId = :nId LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aData['nId'], PDO::PARAM_INT);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$sSQL = '	SELECT 	nId,nMoney0
				FROM  	'. CLIENT_JACKPOT_SET .'
				WHERE 	nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aJackpot = $Result->fetch(PDO::FETCH_ASSOC);
		$nJackpotMoneyFifth = $aJackpot['nMoney0'] / 5;
		$nJackpotMoneyFifth = fnCeilBit($nJackpotMoneyFifth,3);
		$nRemainJackpot = $aJackpot['nMoney0'] - $nJackpotMoneyFifth;

		$nJackpotMoney = $nJackpotMoneyFifth * (100 - $aGameSetting['nReturnJackpot']) / 100;
		$nReturnJackpot = ($nJackpotMoneyFifth * $aGameSetting['nReturnJackpot'] / 100) + $nRemainJackpot;

		$aNewMemberData['nMoney0'] += $nJackpotMoney;
		$aAccLogArray = array(
			'nUid' 		=> (int)	$aMasterBanker['nUid'],
			'nKid' 		=> (int)	$aMasterBanker['nId'],
			'nType0' 		=> (int)	1,
			'nType1' 		=> (int)	309,
			'nType2' 		=> (int)	0,
			'nBefore' 		=> (float)	0,
			'nDelta' 		=> (float)	$nJackpotMoney,
			'nAfter' 		=> (float)	0,
			'sParams' 		=> (string)	'',
			'nCreateTime' 	=> (int)	NOWTIME,
			'sCreateTime' 	=> (string)	NOWDATE,
			'nCreateDay' 	=> (int)	strtotime('today'),
			'sSiteId'		=> (string)	$aMasterBanker['sSiteId'],
			'sNoTag'		=> (string) $nGame.'_'.$aMasterBanker['sNo'],
		);

		$aSQL_Array = array(
			'nGame'		=> $nGame,
			'sNo'			=> $aMasterBanker['sNo'],
			'nLottryId'		=> $aMasterBanker['nId'],
			'nUid'		=> $aMasterBanker['nUid'],
			'nBefore'		=> $aJackpot['nMoney0'],
			'nDelta'		=> -1 * $nJackpotMoney,
			'nAfter'		=> $aJackpot['nMoney0'] - $nJackpotMoney,
			'nType0'		=> 1,
			'nCreateTime'	=> NOWTIME,
			'sCreateTime'	=> NOWDATE,
		);

		$sSQL = 'INSERT INTO '. CLIENT_JACKPOT_LOG .' ' . sql_build_array('INSERT', $aSQL_Array );
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$aSQL_Array = array(
			'nMoney0' 		=> $nReturnJackpot,
			'nUpdateTime'	=> NOWTIME,
			'sUpdateTime'	=> NOWDATE,
		);
		$sSQL = '	UPDATE '. CLIENT_JACKPOT_SET . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
				WHERE	nId = :nId LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $aJackpot['nId'], PDO::PARAM_INT);
		sql_build_value($Result, $aSQL_Array);
		sql_query($Result);

		$oPdo->beginTransaction();
		$sSQL = '	SELECT 	nMoney
				FROM  	'.CLIENT_USER_MONEY.'
				WHERE 	nUid = :nUid
				LIMIT 1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aMasterBanker['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);
		$aEditLog[CLIENT_USER_MONEY]['aOld'][$aMasterBanker['nUid']] = $aUserMoney;
		$aAccLogArray['nBefore'] = $aUserMoney['nMoney'];

		if($aUserMoney === false)
		{
			$oPdo->rollBack();
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aNewMoney = array(
				'Money' => (float) $aUserMoney['nMoney'] + $nJackpotMoney
			);
			$aAccLogArray['nAfter'] = $aNewMoney['Money'];
			$aSQL_Array = oTransfer::PointUpdate($aMasterBanker['nUid'],$aNewMoney);
			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aMasterBanker['nUid'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$aMasterBanker['nUid']] = $aSQL_Array;
			}
			$oPdo->commit();

			DoLogAcc($aAccLogArray);
			$aActionLog = array(
				'nWho'		=> (int)	$aMasterBanker['nUid'],
				'nWhom'		=> (int)	$aMasterBanker['nUid'],
				'sWhomAccount'	=> (string)	$aMasterBanker['sAccount'],
				'nKid'		=> (int)	$aMasterBanker['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104421,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			$sJackpotMoney = number_format($nJackpotMoney,3);
			$sJackpotMsg = '💥恭喜'.$aMasterBanker['sAccount'].'得彩💥<br>彩金'.$sJackpotMoney;
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
				'nUid' => 0,
				'sMsg' => '<div class="betmsg font-xxs msgMember">
						<div class="coverbox">
							<div class="td imgbox float-left"><img class="img-circle" src="images/headImg/0.jpg"></div>
							<div class="td float-left ifmeright msgMemberInf">
								<div class="dtbox">
									<div class="dt-nickname">系統機器人</div>
								</div>
								<div class="betmsgBlock arrow">
									<div class="betinfobox">
										<div class="betinfo">
											<div>'.$sJackpotMsg.'</div>
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
			$aJsonChat[$nNowTime*10000+1] = $aJsonChat[$nNowTime*10000];
			$aJsonChat[$nNowTime*10000+2] = $aJsonChat[$nNowTime*10000];
			
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);

			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+1,
			);
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000+2,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			$sShowScreen = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt');
			$aShowScreen = !empty($sShowScreen)?json_decode($sShowScreen,true):array();	
			
			$aShowScreen[$nNowTime]['sMsg'] = '<div>'.$aMasterBanker['sAccount'].'<br>'.$sJackpotMsg.'</div>';
			$aShowScreen[$nNowTime]['nPlay'] = 0;
			$aShowScreen[$nNowTime]['nType0'] = 1;
			$aShowScreen[$nNowTime+1] = $aShowScreen[$nNowTime];
			$aShowScreen[$nNowTime+2] = $aShowScreen[$nNowTime];
			$sShowScreen = json_encode($aShowScreen);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt';
			file_put_contents($sFile, $sShowScreen,LOCK_EX);

			// $oPdo->rollBack();
			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
		}
	}

	# 對話快捷鈕
	if($aJWT['a'] == 'QUICKMSG')
	{
		$aBankers = array();
		$sSQL = '	SELECT	nId,
						sName0,
						sMessage
				FROM		'. CLIENT_QUICKMSG .'
				WHERE		nId = :nId
				AND		nOnline = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
		sql_query($Result);
		$aMessage = $Result->fetch(PDO::FETCH_ASSOC);

		if($aMessage === false)
		{
			$nErr = 1;
			$sMsg = NODATA;
		}
		elseif($aMessage['sMessage'] == '')
		{
			$nErr = 1;
			$sMsg = '尚未設定快捷訊息';
		}

		if($nErr === 0)
		{
			# 產生開關盤訊息
			$aJsonChat = array();
			if(file_exists(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt'))
			{
				$sJsonChat = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt');
				$aJsonChat = !empty($sJsonChat)?json_decode($sJsonChat,true):array();
				$nMsgCount = count($aJsonChat);
			}
			
			$nNowTime = microtime(true);
			$aJsonChat[$nNowTime*10000] = array(
				'sType'	=> 'server',
				'nRefresh' => '1',
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
								<div class="betinfobox">
									<div class="betinfo">
										<div>
											'.$aMessage['sMessage'].'
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

			while($nMsgCount > MSGCOUNT)
			{
				$nMsgCount--;
				unset($aJsonChat[key($aJsonChat)]);
			}
			$sJsonChat = json_encode($aJsonChat);
			$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))) .'/File/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonChat,LOCK_EX);		
			
			$aSocketMsg[] = array(
				'nGame' => '1001',
				'nRefresh' => '1',
				'nUid' => '0',
				'sKey' => $nNowTime*10000,
			);
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
			}
			socket_close($oSocket);

			header('Location: '.sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']).'&nGame='.$nGame); 
			exit;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ctrl_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束

	function fnUploadImg($aGame,$aFileRaw)
	{
		global $oPdo;
		global $aJumpMsg;
		global $aFile;
		global $aAdm;
		$aFile['sTable'] = CLIENT_GAMES_NUMS;
		$aFile['aFile'] = array(
			'name' 	=> $aFileRaw['name'][0],
			'type' 	=> $aFileRaw['type'][0],
			'size' 	=> $aFileRaw['size'][0],
			'tmp_name' 	=> $aFileRaw['tmp_name'][0],
		);
		$aFileInfo = goImage($aFile);
		if($aFileInfo['error'] !== '')
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = aIMGERROR[$aFileInfo['error']];
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sList'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			return false;
		}

		$sSQL = '	SELECT	nId
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		sTable LIKE \''. CLIENT_GAMES_NUMS .'\'
				AND		nKid = :nKid
				AND		nType0 = 0
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nKid', $aGame['nId'], PDO::PARAM_INT);
		sql_query($Result);
		$aImage = $Result->fetch(PDO::FETCH_ASSOC);
		if($aImage === false)
		{
			$aSQL_Array = array(
				'nKid'		=> (int)	$aGame['nId'],
				'sTable'		=> (string)	CLIENT_GAMES_NUMS,
				'sFile'		=> (string)	$aFileInfo['sFilename'],
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'  	=> (string)	NOWDATE,
			);

			$sSQL = 'INSERT INTO ' . CLIENT_IMAGE_CTRL . ' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nImageLastId = $oPdo->lastInsertId();
			$aSQL_Array['nId'] = $nImageLastId;

			$aEditLog[CLIENT_IMAGE_CTRL]['aNew'][$nImageLastId] = $aSQL_Array;
			$nLastId = $nImageLastId;
		}
		else
		{
			$aEditLog[CLIENT_IMAGE_CTRL]['aOld'][$aImage['nId']] = $aImage;
			$aSQL_Array = array(
				'nId'			=> $aImage['nId'],
				'nKid'		=> (int)	$aGame['nId'],
				'sTable'		=> (string)	CLIENT_GAMES_NUMS,
				'sFile'		=> (string)	$aFileInfo['sFilename'],
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'  	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_IMAGE_CTRL . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $aImage['nId'], PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_IMAGE_CTRL]['aNew'][$aImage['nId']] = $aSQL_Array;
			$nLastId = $aImage['nId'];
		}
		
		#紀錄動作 - 新增
		$aActionLog = array(
			'nWho'		=> (int)	$aAdm['nId'],
			'nWhom'		=> (int)	0,
			'sWhomAccount'	=> (string)	'',
			'nKid'		=> (int)	$nLastId,
			'sIp'			=> (string)	USERIP,
			'nLogCode'		=> (int)	8102404,#01新增02修改03刪除04上傳圖片05刪除圖片
			'sParam'		=> (string)	json_encode($aEditLog),
			'nType0'		=> (int)	0,
			'nCreateTime'	=> (int)	NOWTIME,
			'sCreateTime'	=> (string)	NOWDATE,
		);
		DoActionLog($aActionLog);
		return true;
	}
?>