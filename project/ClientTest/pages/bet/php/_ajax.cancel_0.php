<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	$nGame = filter_input_int('nGame', INPUT_POST, 1001);

	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);

	$aData = array();
	$aNowNums = array();
	$aUserBetData = array();
	$aUserBankerData = array();
	$nSumBetMoney = 0;
	$nSumBankerMoney = 0;
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();

	if($aUser === false)
	{
		$aReturn['nError'] = 1;
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}

	if($aJWT['a'] == 'CANCELBET')
	{
		$sSQL = '	SELECT 	nId,
						sNo
				FROM 		'.CLIENT_GAMES_NUMS.'
				WHERE 	nGame = :nGame
				AND		nStatus = 1
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aNowNums = $aRows;
		}

		if(!empty($aNowNums))
		{
			$sSQL = '	SELECT 	nId,
							nMoney0
					FROM 		'.CLIENT_GAMES_DATA.'
					WHERE 	nGame = :nGame
					AND		nUid = :nUid
					AND		sNo = :sNo
					AND		nDone = 0';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
			$Result->bindValue(':sNo', $aNowNums['sNo'], PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aUserBetData[$aRows['nId']] = $aRows;
				$nSumBetMoney += $aRows['nMoney0'];
				$nKid = $aRows['nId'];
			}
			if(empty($aUserBetData))
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = '尚未投注';
			}
		}
		else
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = '不在可取消投注時間內';
		}

		if($aReturn['nError'] == 0)
		{
			$sBetMsg = '';
			$sBetMsg .= '<div class="FontRed">下注取消</div>';
			# 注單取消 #
			foreach ($aUserBetData as $LPnId => $LPaBetData)
			{
				$oPdo->beginTransaction();
				
				$sSQL = '	SELECT	nId,
								nMoney0,
								sContent0,
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
					trigger_error('index:635 查無注單 id=' . $LPnId);
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

				$aEditLog[CLIENT_GAMES_DATA]['aOld'][$LPnId] = $LPaBetData;
				$aEditLog[CLIENT_GAMES_DATA]['aNew'][$LPnId] = $aSQL_Array;

				$oPdo->commit();

				$sBetMsg .= '<div class=" JqBetMsg">'.aBET[$aNowData['sContent0']].'[<i class="FontRed fas fa-times"></i>'.aBET['CANCEL'].']'.number_format($aNowData['nMoney0']).'</div>';
			}

			# 會員反款 #
			do
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
				$Result->bindValue(':nId',$aUser['nId'],PDO::PARAM_INT);
				sql_query($Result);
				$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
				if($aNowData === false)
				{
					$oPdo->rollback();
					trigger_error('index:670 查無會員 uid='.$aUser['nId']);
					break;
				}
	
				$sSQL = '	SELECT 	nMoney
						FROM		'. CLIENT_USER_MONEY .'
						WHERE		nUid = :nUid
						LIMIT		1
						FOR		UPDATE' ;
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
				sql_query($Result);
				$aOldMoney = $Result->fetch(PDO::FETCH_ASSOC);
				if($aOldMoney === false)
				{
					$oPdo->rollback();
					trigger_error('index:670 查無會員金額 uid='.$aUser['nId']);
					break;
				}
	
				$nBefore	= (float) $aOldMoney['nMoney'];
				$nDelta	= (float) $nSumBetMoney;
				$nAfter	= $nBefore + $nDelta;
				# 補錢 #
				$aNewMoney = array(
					'Money' => (float) $nAfter,
				);
				$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney,1,true);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nUid = :nUid LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
	
					$aAccLog = array(
						'nUid' 		=> (int)	$aUser['nId'],
						'nKid' 		=> (int)	$nKid,
						'nType0' 		=> (int)	1,
						'nType1' 		=> (int)	304,
						'nType2' 		=> (int)	0,
						'nBefore' 		=> (float)	$nBefore,
						'nDelta' 		=> (float)	$nDelta,
						'nAfter' 		=> (float)	$nAfter,
						'sParams' 		=> (string)	'',
						'nCreateTime' 	=> (int)	NOWTIME,
						'sCreateTime' 	=> (string)	NOWDATE,
						'nCreateDay' 	=> (int)	strtotime('today'),
						'sNoTag'		=> (string) $nGame.'_'.$aNowNums['sNo'],
					);
					DoLogAcc($aAccLog);
				}
	
				$aEditLog[CLIENT_USER_MONEY]['aOld'][$aUser['nId']] = $aOldMoney;
				$aEditLog[CLIENT_USER_MONEY]['aNew'][$aUser['nId']] = $aSQL_Array;
	
				$oPdo->commit();

			}while(0);

			#紀錄動作 - 新增
			$aActionLog = array(
				'nWho'		=> (int)	$aUser['nId'],
				'nWhom'		=> (int)	$aUser['nId'],
				'sWhomAccount'	=> (string)	$aUser['sAccount'],
				'nKid'		=> (int)	0,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	7101005,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aReturn['sMsg'] = '成功取消投注';

			$nMicroTime = (float)microtime(true);
			$aJsonMsg[$nMicroTime*10000] = array(
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
												<div>'.$aUser['sName0'].$sBetMsg.'</div>
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

			$nMsgCount = count($aJsonMsg);
			while($nMsgCount > 100)
			{
				$nMsgCount--;
				unset($aJsonMsg[key($aJsonMsg)]);
				
				if($nMsgCount< 0)
				{
					trigger_error('break');
					break;
				}
			}	
			$sJsonMsg = json_encode($aJsonMsg);
			$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonMsg,LOCK_EX);

			$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000];
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.$sSocketMsg);
			}
			socket_close($oSocket);
		}
	}

	if($aJWT['a'] == 'CANCELCOLLECT')
	{
		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];

		$sSQL = '	SELECT 	nId,
						sNo
				FROM 		'.CLIENT_GAMES_NUMS.'
				WHERE 	nGame = :nGame
				AND		nStatus = 2
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aNowNums = $aRows;
		}

		if(!empty($aNowNums))
		{
			$sSQL = '	SELECT 	nId,
							nMoney0
					FROM 		'.CLIENT_GAMES_BANKER.'
					WHERE 	nGame = :nGame
					AND		nUid = :nUid
					AND		sNo = :sNo
					AND		nType0 = 1
					AND		nDone = 0';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
			$Result->bindValue(':sNo', $aNowNums['sNo'], PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aUserBankerData[$aRows['nId']] = $aRows;
				$nSumBankerMoney += $aRows['nMoney0'] + $aRows['nMoney0'] * ($aGameSetting['nBankerFee'] / 100);
				$nKid = $aRows['nId'];
			}
			if(empty($aUserBankerData))
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = '尚未配莊';
			}

			if($aReturn['nError'] == 0)
			{
				$sBetMsg = '';
				$nSumBet = 0;	# 當期總投注
				$nSumBanker = 0;	# 當期總入莊額度

				# 注單取消 #
				foreach ($aUserBankerData as $LPnId => $LPaBankerData)
				{
					$oPdo->beginTransaction();
					
					$sSQL = '	SELECT	nId,
									nMoney0,
									nStatus,
									nDone
							FROM		'. CLIENT_GAMES_BANKER .'
							WHERE		nId = :nId
							AND		nDone = 0
							AND		nType0 = 1
							LIMIT		1
							FOR		UPDATE';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $LPnId, PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						trigger_error('index:635 查無注單 id=' . $LPnId);
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
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aNowData['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
	
					$aEditLog[CLIENT_GAMES_BANKER]['aOld'][$LPnId] = $LPaBankerData;
					$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$LPnId] = $aSQL_Array;
	
					$oPdo->commit();

					$sSQL = '	SELECT 	nMoney0
							FROM 		'. CLIENT_GAMES_DATA .'
							WHERE 	nGame = :nGame
							AND		sNo = :sNo
							AND		nDone = 0';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
					$Result->bindValue(':sNo',$aNowNums['sNo'],PDO::PARAM_STR);
					sql_query($Result);
					while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
					{
						$nSumBet += $aRows['nMoney0'];
					}

					$sSQL = '	SELECT 	nMoney0
							FROM  	'.CLIENT_GAMES_BANKER.'
							WHERE 	sNo = :sNo
							AND		nStatus = 1
							AND		nDone = 0';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':sNo',$aNowNums['sNo'],PDO::PARAM_STR);
					sql_query($Result);
					while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
					{
						$nSumBanker += $aRows['nMoney0'];
					}

					$nSumMoney = $nSumBet-$nSumBanker;
					$sBetMsg = str_replace('[:MONEY:]',0,aBANKERMSG['SHARECANCEL'].'<br/>');				
					$sBetMsg = str_replace('[:MONEY1:]',number_format($nSumMoney),$sBetMsg);
				}
	
				# 會員反款 #
				do
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
					$Result->bindValue(':nId',$aUser['nId'],PDO::PARAM_INT);
					sql_query($Result);
					$aNowData = $Result->fetch(PDO::FETCH_ASSOC);
					if($aNowData === false)
					{
						$oPdo->rollback();
						trigger_error('index:670 查無會員 uid='.$aUser['nId']);
						break;
					}
		
					$sSQL = '	SELECT 	nMoney
							FROM		'. CLIENT_USER_MONEY .'
							WHERE		nUid = :nUid
							LIMIT		1
							FOR		UPDATE' ;
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
					sql_query($Result);
					$aOldMoney = $Result->fetch(PDO::FETCH_ASSOC);
					if($aOldMoney === false)
					{
						$oPdo->rollback();
						trigger_error('index:670 查無會員金額 uid='.$aUser['nId']);
						break;
					}
		
					$nBefore	= (float) $aOldMoney['nMoney'];
					$nDelta	= (float) $nSumBankerMoney;
					$nAfter	= $nBefore + $nDelta;
					# 補錢 #
					$aNewMoney = array(
						'Money' => (float) $nAfter,
					);
					$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney,1,true);
					if($aSQL_Array !== false)
					{
						$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
								WHERE	nUid = :nUid LIMIT 1';
						$Result = $oPdo->prepare($sSQL);
						$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
						sql_build_value($Result, $aSQL_Array);
						sql_query($Result);
		
						$aAccLog = array(
							'nUid' 		=> (int)	$aUser['nId'],
							'nKid' 		=> (int)	$nKid,
							'nType0' 		=> (int)	1,
							'nType1' 		=> (int)	304,
							'nType2' 		=> (int)	0,
							'nBefore' 		=> (float)	$nBefore,
							'nDelta' 		=> (float)	$nDelta,
							'nAfter' 		=> (float)	$nAfter,
							'sParams' 		=> (string)	'',
							'nCreateTime' 	=> (int)	NOWTIME,
							'sCreateTime' 	=> (string)	NOWDATE,
							'nCreateDay' 	=> (int)	strtotime('today'),
							'sNoTag'		=> (string) $nGame.'_'.$aNowNums['sNo'],
						);
						DoLogAcc($aAccLog);
					}
		
					$aEditLog[CLIENT_USER_MONEY]['aOld'][$aUser['nId']] = $aOldMoney;
					$aEditLog[CLIENT_USER_MONEY]['aNew'][$aUser['nId']] = $aSQL_Array;
		
					$oPdo->commit();
	
				}while(0);
	
				#紀錄動作 - 新增
				$aActionLog = array(
					'nWho'		=> (int)	$aUser['nId'],
					'nWhom'		=> (int)	$aUser['nId'],
					'sWhomAccount'	=> (string)	$aUser['sAccount'],
					'nKid'		=> (int)	0,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	7101006,
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$aReturn['sMsg'] = '成功取消配莊';

				$nMicroTime = (float)microtime(true);
				$aJsonMsg[$nMicroTime*10000] = array(
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
													<div>'.$aUser['sName0'].$sBetMsg.'</div>
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
	
				$nMsgCount = count($aJsonMsg);
				while($nMsgCount > 100)
				{
					$nMsgCount--;
					unset($aJsonMsg[key($aJsonMsg)]);
					
					if($nMsgCount< 0)
					{
						trigger_error('break');
						break;
					}
				}	
				$sJsonMsg = json_encode($aJsonMsg);
				$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
				file_put_contents($sFile, $sJsonMsg,LOCK_EX);
	
				$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000];
				$sSocketMsg = json_encode($aSocketMsg);
				socket_connect($oSocket,$sServerIp,$nPort);
				if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
					trigger_error('訊息發送失敗:'.$sSocketMsg);
				}
				socket_close($oSocket);
			}
		}
		else
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = '不在可取消配莊時間內';
		}
	}
			
	$aReturn['aData'] = $aData;

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>