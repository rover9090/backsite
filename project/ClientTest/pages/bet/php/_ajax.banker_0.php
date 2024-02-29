<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	$nMoney = filter_input_int('nMoney', INPUT_POST, 0);
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');
	$bSumMoney = filter_input_int('bSumMoney', INPUT_POST, 0);
	$nMoneyFee = 0;
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();
	$aBanker = array();
	$aBankers = array();
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare
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
		'sMsg'		=> '',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
		'sTime'		=> date('H:i:s'),
		'nType0'		=> 0,
		'aUser'		=> $aUser,
		'aSocketMsg'	=> array(),
	);
	$aEditLog = array();
	#宣告結束

	#程式邏輯區
	if($aUser === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}
	
	if($aJWT['a'] == 'RETURN')
	{
		$aReturn['nError'] = 1;
	}
	# 搶莊加入排莊
	if($aJWT['a'] == 'BANKER0')
	{
		$aEditLog = array(
			CLIENT_GAMES_BANKER_ORDER => array(
				'aNew' => array(),
			),
		);
		$nLineCount = 0;
		$nSelfLineCount = 0;
		$sSQL = '	SELECT	nId
				FROM		'. CLIENT_USER_LINK .'
				WHERE		nUid = :nUid
				AND		nEndTime = 0
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
		sql_query($Result);
		$aLink = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT	nId,sPercentLink as sPerLink
				FROM		'.CLIENT_USER_FS.'
				WHERE		nUid = :nUid
				AND		nEndTime = 0
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aFs = $Result->fetch(PDO::FETCH_ASSOC);

		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		$aGameSetting['nCloseRoom'] = 0;
		if($aGameSetting['sOpenTime'] !== $aGameSetting['sCloseTime'])
		{
			$nOpenTime = strtotime($aGameSetting['sOpenTime']);
			$nCloseTime = strtotime($aGameSetting['sCloseTime']);
			if($nCloseTime < NOWTIME)
			{
				$nOpenTime += 86400;
				$nCloseTime += 86400;
			}
			if($nOpenTime > $nCloseTime)
			{
				$nOpenTime -= 86400;
			}
			$aGameSetting['nOpenTime'] = $nOpenTime;
			$aGameSetting['nCloseTime'] = $nCloseTime;
			$aGameSetting['nCloseRoom'] = ($nOpenTime > NOWTIME)?1:0;
		}
		if($aGameSetting['nMaintenance'] == '1') $aGameSetting['nCloseRoom'] = 1;

		$sSQL = '	SELECT	nUid
				FROM		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE		nStatus = 0';
		$Result = $oPdo->prepare($sSQL);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nLineCount++;
			if($aRows['nUid'] === $aUser['nId'])
			{
				$nSelfLineCount++;
			}
		}

		if($nGame > 2000)
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime
					FROM  	'.CLIENT_GAMES_NUMS2.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nUse = 1
					AND		nPauseTime1 > :NOWTIME';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime
					FROM  	'.CLIENT_GAMES_NUMS.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nStatus <3
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}

		// if($aGame === false)
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = aACTMSG['NOROUND'];
		// }
		// else if($aGame['nBetEndTime'] >= NOWTIME && ($aGame['nBetEndTime']-$aGameSetting['nSec2']) <= NOWTIME)###
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = str_replace('[:SEC:]',$aGameSetting['nSec2'],aACTMSG['CLOSING']);
		// }
		// else if($aGame['nStartTime'] >= NOWTIME || $aGame['nBetEndTime'] < NOWTIME)
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = aACTMSG['NOTNOW'];
		// }
		if($aGameSetting['nCloseRoom'] == 1)# && $aUser['nTest'] == 0
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MAINTENANCE'];
		}
		else if($aUser['nStatus'] > 0)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MEMBEROFFLINE'];
		}
		else if(!oTransfer::PointCheck($aUser['nId']))
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MONEYERR'];
		}
		else if($aUser['nMoney'] < $aGameSetting['nBankerMin'])
		{
			$aReturn['nError'] = 1;			
			$aReturn['sMsg'] = str_replace('[:MONEY:]',$aGameSetting['nBankerMin'],aACTMSG['BANKERNOTENOUGH']);
		}
		else if($nLineCount >= $aGameSetting['nBankerLineCount'])
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = str_replace('[:COUNT:]',$aGameSetting['nBankerLineCount'],aACTMSG['BANKERLINEMAX']);
		}		
		else if($nSelfLineCount >= 1)
		{
			$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER_ORDER . ' SET nStatus = 9
					WHERE	nUid = :nUid AND nStatus = 0 AND nContinue = 0 LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
			sql_query($Result);

			$aBanker_Order_Old = array(
				'nStatus' => 0,
			);
			$aBanker_Order_New = array(
				'nStatus' => 9,
			);
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aOld'][$aUser['nId']] = $aBanker_Order_Old;
			$aEditLog[CLIENT_GAMES_BANKER_ORDER]['aNew'][$aUser['nId']] = $aBanker_Order_New;

			$aActionLog = array(
				'nWho'		=> (int)	$aUser['nId'],
				'nWhom'		=> (int)	$aUser['nId'],
				'sWhomAccount'	=> (string)	$aUser['sAccount'],
				'nKid'		=> (int)	0,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	7101004,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$sBetActionMsg = str_replace('[:NAME0:]',$aUser['sName0'],aBANKERMSG['CANCELBANKERSUCCESS']);
			$aReturn['nError'] = 0;
			$aReturn['sMsg'] = aACTMSG['CANCELBANKER'];
		}
		else
		{			
			$oPdo->beginTransaction();
			$aSQL_Array = array(
				'nUid'		=> (int)	$aUser['nId'],
				'nLid'		=> (int)	$aLink['nId'],
				'nFsId'		=> (int)	$aFs['nId'],
				'sPerLink'		=> (string) $aFs['sPerLink'],
				'nContinue'		=> (int)	0,
				'nStatus'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'sSiteId'		=> (string)	$aUser['sSiteId'],
			);

			$sSQL = 'INSERT INTO '. CLIENT_GAMES_BANKER_ORDER .' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nBankerId = $oPdo->lastInsertId();
			$oPdo->commit();
			$aEditLog = array(
				CLIENT_GAMES_BANKER_ORDER => array(
					'aNew' => array(
						$nBankerId => $aSQL_Array
					),
				),
			);

			$aActionLog = array(
				'nWho'		=> (int)	$aUser['nId'],
				'nWhom'		=> (int)	$aUser['nId'],
				'sWhomAccount'	=> (string)	$aUser['sAccount'],
				'nKid'		=> (int)	$nBankerId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	7101002,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			
			$sBetActionMsg = str_replace('[:NAME0:]',$aUser['sName0'],aBANKERMSG['LINEUPSUCCESS']);
			$aReturn['nError'] = 0;
			$aReturn['sMsg'] = aBANKERALERT['LINEUPSUCCESS'];
		}

		if($aReturn['nError'] == 0)
		{
			$aJsonMsg = array();
			if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
			{
				$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
				$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
			}

			$nMicroTime = (float)microtime(true);
			$aJsonMsg[$nMicroTime*10000] = array(
				'sType' => 'bank',
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
											<div>'.$aUser['sName0'].'<br>'.
											$sBetActionMsg.'</div>
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
			
			$sBetMsg = GameSet_Class::fnCallBankerList($nGame);
			$aJsonMsg[$nMicroTime*10000+1] = array(
				'sType' => 'bank',
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
											<div>'.
											$sBetMsg.'
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
			$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000+1];
			$sSocketMsg = json_encode($aSocketMsg);
			socket_connect($oSocket,$sServerIp,$nPort);
			if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				trigger_error('訊息發送失敗:'.$sSocketMsg);
			}
			socket_close($oSocket);
		}		
	}

	# 輸入集資金額(打莊/配莊)
	if($aJWT['a'] == 'BANKER1')
	{
		$aPlayer = array();
		$nSumBet = 0;		
		$nSumBanker = 0;
		$nMoneyFee = 0;
		$aJsonMsg = array();
		$sSQL = '	SELECT	nId
				FROM		'. CLIENT_USER_LINK .'
				WHERE		nUid = :nUid
				AND		nEndTime = 0
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
		sql_query($Result);
		$aLink = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT	nId,sPercentLink as sPerLink
				FROM		'.CLIENT_USER_FS.'
				WHERE		nUid = :nUid
				AND		nEndTime = 0
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aFs = $Result->fetch(PDO::FETCH_ASSOC);

		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		
		$aGameSetting['nCloseRoom'] = 0;
		if($aGameSetting['sOpenTime'] !== $aGameSetting['sCloseTime'])
		{
			$nOpenTime = strtotime($aGameSetting['sOpenTime']);
			$nCloseTime = strtotime($aGameSetting['sCloseTime']);
			if($nCloseTime < NOWTIME)
			{
				$nOpenTime += 86400;
				$nCloseTime += 86400;
			}
			if($nOpenTime > $nCloseTime)
			{
				$nOpenTime -= 86400;
			}
			$aGameSetting['nOpenTime'] = $nOpenTime;
			$aGameSetting['nCloseTime'] = $nCloseTime;
			$aGameSetting['nCloseRoom'] = ($nOpenTime > NOWTIME)?1:0;
		}
		if($aGameSetting['nMaintenance'] == '1') $aGameSetting['nCloseRoom'] = 1;

		if($nGame > 2000)
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							nBankerEndTime,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS2.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nUse = 1
					AND		nPauseTime1 > :NOWTIME';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							nBankerEndTime,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nStatus <3
					ORDER BY nId DESC
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}

		$sSQL = '	SELECT 	nId,
						sNo,
						nUid,
						nStatus,
						nMoney0,
						nMoney1,
						nType0,
						(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid) as sName0
				FROM  	'.CLIENT_GAMES_BANKER.'
				WHERE 	sNo = :sNo
				AND		nStatus = 1
				AND		nDone = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			if($aRows['nType0'] === '0')
			{
				$aBanker = $aRows;
			}
			// $aBankers[$aRows['nUid']] = $aRows;
		}
		if($aBanker === array())
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = '流程錯誤';
			$aReturn['sMsg-1'] = '流程錯誤';
			$aBanker['nUid'] = 0;
		}
		else
		{
			if(isset($aBanker['nMoney'])) $aBanker['nMoney0'] = (float)$aBanker['nMoney0'];
			
			if(isset($aBanker['nMoney1']) && $aBanker['nMoney1'] === '0.000')
			{			
				$sSQL = '	SELECT 	nMoney0,nUid
						FROM 		'. CLIENT_GAMES_DATA .'
						WHERE 	nGame = :nGame
						AND		sNo = :sNo
						AND		nDone = 0
						ORDER BY	nId DESC';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
				$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
				sql_query($Result);
				while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
				{
					$aPlayer[$aRows['nUid']] = 1;
					$nSumBet += $aRows['nMoney0'];
				}
			}
			else
			{
				$nSumBet = $aBanker['nMoney1'];
			}
		}

		$sSQL = '	SELECT	nUid,nContinue,nBuyContinue
				FROM		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE		nGame = :nGame
				AND		nStatus = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aBankerOrder = $Result->fetch(PDO::FETCH_ASSOC);
				
		if(file_exists(dirname(dirname(dirname(dirname(__file__)))) .'/MsgJson'.$nGame.'.txt'))
		{
			$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
			$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
		}

		$oPdo->beginTransaction();

		$sSQL = '	SELECT 	nUid,nMoney0,nMoney1,nType0
				FROM  	'.CLIENT_GAMES_BANKER.'
				WHERE 	sNo = :sNo
				AND		nStatus = 1
				AND		nDone = 0
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBanker += $aRows['nMoney0'];
			if($aRows['nType0'] === '0')
			{
				$aBanker['nMoney0'] = $aRows['nMoney0'];
				$aBanker['nMoney1'] = $aRows['nMoney1'];
			}
			$aBankers[$aRows['nUid']] = $aRows;
		}

		$sSQL = '	SELECT 	nMoney
				FROM  	'.CLIENT_USER_MONEY.'
				WHERE 	nUid = :nUid
				LIMIT 1
				FOR UPDATE';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aUser['nId'],PDO::PARAM_INT);
		sql_query($Result);
		$aUserMoney = $Result->fetch(PDO::FETCH_ASSOC);

		/*
		1 投注>自身>輸入>最低 一般情況A
		2 投注>自身>最低>輸入 不阻擋,輸入加為最低 BLOCK
		; 3 投注>最低>輸入>自身 不阻擋,輸入降為自身
		4 投注>最低>自身>輸入 不阻擋,輸入加為自身 BLOCK
		; 5 投注>輸入>最低>自身 不阻擋,輸入降為自身
		; 6 投注>輸入>自身>最低 不阻擋,輸入降為自身

		7 自身>投注>輸入>最低 一般情況B
		8 自身>投注>最低>輸入 不阻擋,輸入加為最低 BLOCK
		;;9 自身>最低>輸入>投注 不阻擋,輸入降為投注
		10自身>最低>投注>輸入 不阻擋,輸入加為投注 BLOCK
		;;11自身>輸入>最低>投注 不阻擋,輸入降為投注
		;;12自身>輸入>投注>最低 不阻擋,輸入降為投注

		;13輸入>投注>自身>最低 不阻擋,輸入降為自身
		; 14輸入>投注>最低>自身 不阻擋,輸入降為自身
		;;15輸入>最低>自身>投注 不阻擋,輸入降為投注
		; 16輸入>最低>投注>自身 不阻擋,輸入降為自身
		;;17輸入>自身>最低>投注 不阻擋,輸入降為投注
		;18輸入>自身>投注>最低 不阻擋,輸入降為自身

		; 19最低>投注>輸入>自身 不阻擋,輸入降為自身
		20最低>投注>自身>輸入 不阻擋,輸入加為自身 BLOCK
		;;21最低>自身>輸入>投注 不阻擋,輸入降為投注
		22最低>自身>投注>輸入 不阻擋,輸入加為投注 BLOCK
		;;23最低>輸入>自身>投注 不阻擋,輸入降為投注
		; 24最低>輸入>投注>自身 不阻擋,輸入降為自身
		*/

		if($aBanker['nUid'] != $aUser['nId'])
		{
			$nLeastBanker = $aGameSetting['nOtherMin'];
			$nMaximumBanker = $aGameSetting['nOtherMax'];
		}
		else
		{
			// $nLeastBanker = ($aBankerOrder['nBuyContinue']>0)?$aGameSetting['nBankerPlusMin']:$aGameSetting['nBankerMin'];
			// $nMaximumBanker = ($aBankerOrder['nBuyContinue']>0)?$aGameSetting['nBankerMax']:$aGameSetting['nBankerMax'];
			$nLeastBanker = ($aBankerOrder['nContinue']>2)?$aGameSetting['nBankerPlusMin']:$aGameSetting['nBankerMin'];
			$nMaximumBanker = ($aBankerOrder['nContinue']>2)?$aGameSetting['nBankerMax']:$aGameSetting['nBankerMax'];
		}		
		$nMoneyFee = $nMoney * $aGameSetting['nBankerFee'] / 100;
		$nRemainBet = $nSumBet - $nSumBanker;
		$nUserLimitBet = floor($aUserMoney['nMoney'] / (1+($aGameSetting['nBankerFee']/100)));

		if($nMoney > $nMaximumBanker)
		{
			$nMoney = $nMaximumBanker;
			$nMoneyFee = $nMoney * ($aGameSetting['nBankerFee'] / 100);
			$aReturn['sMsg'] = '輸入金額過高，設定為打莊金額上限【'.$nMaximumBanker.'】';
		}
		# 3,5,6,13,14,16,18,19,24
		if($nMoney > $nUserLimitBet)
		{
			$aReturn['nError'] = 9;
			$nMoney = $nUserLimitBet;
			$nMoneyFee = $nMoney * ($aGameSetting['nBankerFee'] / 100);
			$aReturn['sMsg'] = '輸入金額過高，設定為自身金額上限【'.$nMoney.'】';
		}

		# 9,11,12,15,17,21,23
		if($nMoney > $nRemainBet)
		{
			$aReturn['nError'] = 9;
			$nMoney = $nRemainBet;
			$nMoneyFee = $nMoney * ($aGameSetting['nBankerFee'] / 100);
			$aReturn['sMsg'] = '輸入金額過高，設定為可配投注金額上限【'.$nMoney.'】';
		}
		$nLeastBanker = min(array($nLeastBanker,$nRemainBet,$nUserLimitBet));

		# 輸入金額過小 => 直接block
		if($nMoney < $nLeastBanker)
		{
			# 打莊額度最低MONEY 
			$aReturn['nError'] = 1;
			if($aBanker['nUid'] != $aUser['nId'])
			{
				$aReturn['sMsg'] = str_replace('[:MONEY:]',$nLeastBanker,aBANKERALERT['SHARENOTENOUGH']);
			}
			else
			{
				$aReturn['sMsg'] = str_replace('[:MONEY:]',$nLeastBanker,aBANKERALERT['BANKERNOTENOUGH']);
			}				
		}

		if($aReturn['nError'] === 1)
		{			
		}
		else if($aGame === false)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOROUND'];
		}
		else if($aGame['nStatus'] !== '2')
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['BANKERNOTNOW'];
		}
		else if($aGameSetting['nCloseRoom'] == 1)# && $aUser['nTest'] == 0
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MAINTENANCE'];
		}
		else if($aUser['nStatus'] > 0)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MEMBEROFFLINE'];
		}
		else if(!oTransfer::PointCheck($aUser['nId']))
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['MONEYERR'];
		}
		// else if(isset($aPlayer[$aUser['nId']]))
		// {
		// 	$aReturn['nError'] = 1;
		// 	$aReturn['sMsg'] = '已投注，不可集資';
		// }
		else if($aBanker['nMoney0'] == 0 && $aBanker['nUid'] != $aUser['nId'])
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aBANKERALERT['PRIMEFIRST'];
		}
		else if(isset($aBanker) && $aBanker['nMoney0'] > 0 && $aBanker['nUid'] == $aUser['nId'])
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aBANKERALERT['PRIMEALREADY'];
		}
		else if($aBanker['nUid'] != $aUser['nId'] && isset($aBankers[$aUser['nId']]))
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aBANKERALERT['SHAREALREADY'];
		}
		
		if($aReturn['nError'] != 1)
		{
			$nSumMoney = $nRemainBet - $nMoney;
			if($nSumMoney < 0) $nSumMoney = 0;
			if($aBanker['nUid'] != $aUser['nId'])# 加入配莊
			{
				$aReturn['sMsg'] .= '<br>'.str_replace('[:MONEY:]',number_format($nSumMoney),aBANKERALERT['SHARESUCCESS']);
			}
			else
			{
				$aReturn['sMsg'] .= '<br>'.str_replace('[:MONEY:]',number_format($nSumMoney),aBANKERALERT['BANKERSUCCESS']);
			}
			// echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
			// exit;
			$aEditLog = array(
				CLIENT_GAMES_BANKER => array(
					'aNew' => array(
					),
				),
				CLIENT_USER_MONEY => array(
					'aNew' => array(
					),
					'aOld' => array(
					),
				),
			);
			# CLIENT_GAMES_BANKER跟著切期數時一起修正玩家的Status狀態
			# status=0可以找到還沒排莊過的玩家
			# status=1可以找到已經入莊過的玩家
			# nType0=0 排莊玩家
			# nType0=1 配莊玩家
			# nType0=0時 nMoney1為投注總額度
			# nType0=1時 nMoney1為扣除莊家後總額度
			# nMoney2=莊家額度/總配莊額度

			# 需要的統計:投注列表/=> 過帳時再一次弄的負擔比較小

			// $oPdo->beginTransaction();			
			$aEditLog[CLIENT_USER_MONEY]['aOld'][$aUser['nId']] = $aUserMoney;
			
			if($aUserMoney === false || $aUserMoney['nMoney'] < ($nMoney + $nMoneyFee))
			{
				$oPdo->rollBack();
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = str_replace('[:MONEY:]',$nMoney,aBANKERALERT['NOTENOUGH']);
			}
			else
			{
				$aNewMoney = array(
					'Money' => (float) $aUserMoney['nMoney'] - $nMoney - $nMoneyFee
				);
				$aSQL_Array = oTransfer::PointUpdate($aUser['nId'],$aNewMoney);
				if($aSQL_Array !== false)
				{
					$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nUid = :nUid ORDER BY nId ASC LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nUid', $aUser['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$aEditLog[CLIENT_USER_MONEY]['aNew'][$aUser['nId']] = $aSQL_Array;
				}

				if($aBanker['nUid'] != $aUser['nId'])# 加入配莊
				{					
					$aSQL_Array = array(
						'sNo'			=> (string)	$aGame['sNo'],
						'nUid'		=> (int)	$aUser['nId'],
						'nLid'		=> (int)	$aLink['nId'],
						'nFsId'		=> (int)	$aFs['nId'],
						'sPerLink'		=> (string) $aFs['sPerLink'],
						'nMoney0'		=> (int)	$nMoney,
						'nStatus'		=> (int)	1,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
						'nType0'		=> (int)	1,
						'sSiteId'		=> (string)	$aUser['sSiteId'],
					);
		
					$sSQL = 'INSERT INTO '. CLIENT_GAMES_BANKER .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nBankerId = $oPdo->lastInsertId();
					$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$nBankerId] = $aSQL_Array;
				}
				else # 莊家打莊
				{					
					$aSQL_Array = array(
						'sNo'			=> (string)	$aGame['sNo'],
						'nMoney0'		=> (int)	$nMoney,
						'nMoney2'		=> (int)	$nMoney,
						'nStatus'		=> (int)	1,
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
					);
					if($aBanker['nMoney1'] === '0')
					{
						$aSQL_Array['nMoney1'] = $nSumBet;
					}

					$sSQL = '	UPDATE '. CLIENT_GAMES_BANKER .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aBanker['nId'], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nBankerId = $aBanker['nId'];
					$aEditLog[CLIENT_GAMES_BANKER]['aOld'][$nBankerId] = $aBanker;
					$aEditLog[CLIENT_GAMES_BANKER]['aNew'][$nBankerId] = $aSQL_Array;
				}
				
				$oPdo->commit();
				
				$aSQL_Array = array(
					'nUid'		=> (int)	$aUser['nId'],
					'nKid'		=> (int)	$nBankerId,
					'nType0'		=> (int)	1,
					'nType1'		=> (int)	321,
					'nType2'		=> (int)	0,
					'nType3'		=> (int)	1,
					'nBefore'		=> (float)	$aUserMoney['nMoney'],
					'nDelta'		=> (float)	$nMoney * -1,
					'nAfter'		=> (float)	$aUserMoney['nMoney'] - $nMoney,
					'sParams'		=> (string)	'',
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nCreateDay'	=> (int)	strtotime('today'),
					'sSiteId'		=> (string)	$aUser['sSiteId'],
					'sNoTag'		=> (string) $nGame.'_'.$aGame['sNo'],
				);
				DoLogAcc($aSQL_Array);
				$aParams = array(
					'nUid'	=> $aUser['nId'],
					'nBanker'	=> $nBankerId,
					'nMoney0'	=> $nMoney,
					'nBankerFee'=> $nMoneyFee,
				);
				$aAccLogArray = array(
					'nUid' 		=> (int)	$aUser['nId'],
					'nKid' 		=> (int)	$nBankerId,
					'nType0' 		=> (int)	1,
					'nType1' 		=> (int)	322,
					'nType2' 		=> (int)	0,
					'nType3' 		=> (int)	1,
					'nBefore' 		=> (float)	$aUserMoney['nMoney'] - $nMoney,
					'nDelta' 		=> (float)	-1 * $nMoneyFee,
					'nAfter' 		=> (float)	$aUserMoney['nMoney'] - $nMoney - $nMoneyFee,
					'sParams' 		=> (string)	json_encode($aParams),
					'nCreateTime' 	=> (int)	NOWTIME,
					'sCreateTime' 	=> (string)	NOWDATE,
					'nCreateDay' 	=> (int)	strtotime('today'),
					'sSiteId'		=> (string)	$aUser['sSiteId'],
					'sNoTag'		=> (string) $nGame.'_'.$aGame['sNo'],
				);
				DoLogAcc($aAccLogArray);
				$aActionLog = array(
					'nWho'		=> (int)	$aUser['nId'],
					'nWhom'		=> (int)	$aUser['nId'],
					'sWhomAccount'	=> (string)	$aUser['sAccount'],
					'nKid'		=> (int)	$nBankerId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	7101003,
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
				
				$nSumMoney = $nRemainBet - $nMoney;
				if($nSumMoney < 0) $nSumMoney = 0;
				if($aBanker['nUid'] != $aUser['nId'])# 加入配莊
				{
					$sBetMsg = str_replace('[:MONEY:]',number_format($nMoney),aBANKERMSG['SHARESUCCESS'].'<br/>');				
					$sBetMsg = str_replace('[:MONEY1:]',number_format($nSumMoney),$sBetMsg);
				}
				else
				{
					$sBetMsg = str_replace('[:MONEY:]',number_format($nMoney),aBANKERMSG['BANKERSUCCESS'].'<br/>');
					$sBetMsg = str_replace('[:MONEY1:]',number_format($nSumMoney),$sBetMsg);
				}

				# 打莊成功/配莊成功訊息
				$nMicroTime = (float)microtime(true);
				$aJsonMsg[$nMicroTime*10000] = array(
					'sType' => 'bank',
					'nRefresh' => '1',
					'nSumMoney1' => $nSumMoney,
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
												<div>'.$aUser['sName0'].'('.number_format($aUserMoney['nMoney'] - $nMoney - $nMoneyFee).')'.'<br>'.
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

				if($aBanker['nUid'] == $aUser['nId'])
				{
					$aJsonMsg[$nMicroTime*10000+1] = array(
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
				}

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
				if($aBanker['nUid'] == $aUser['nId'])
				{
					$aSocketMsg[] = $aJsonMsg[$nMicroTime*10000+1];
				}
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
			$oPdo->rollBack();
		}
	}

	# 更新全額
	if($aJWT['a'] == 'BANKERSUM')
	{
		$nSumBet = 0;
		$nSumBanker = 0;

		$aGameSetting = GameSet_Class::fnCallSetting($nGame);
		$aGameSetting = $aGameSetting[$nGame];
		if($nGame > 2000)
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							nBankerEndTime,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS2.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nUse = 1
					AND		nPauseTime1 > :NOWTIME';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			$sSQL = '	SELECT 	nId,
							sNo,
							sResult,
							nEndTime,
							nStartTime,
							sStartTime,
							nBetEndTime,
							sBetEndTime,
							nBankerEndTime,
							nStatus
					FROM  	'.CLIENT_GAMES_NUMS.'
					WHERE 	nGame = :nGame
					AND		nStartTime <= :NOWTIME
					AND		nStatus <3
					ORDER BY nId DESC
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':NOWTIME',NOWTIME,PDO::PARAM_INT);
			$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			sql_query($Result);
			$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		}

		$sSQL = '	SELECT 	nMoney0,nUid
				FROM 		'. CLIENT_GAMES_DATA .'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				AND		nDone = 0
				ORDER BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBet += $aRows['nMoney0'];
		}

		$sSQL = '	SELECT	nUid
				FROM		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE		nGame = :nGame
				AND		nStatus = 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame',$nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aBankerOrder = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT 	nMoney0
				FROM  	'.CLIENT_GAMES_BANKER.'
				WHERE 	sNo = :sNo
				AND		nDone = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$nSumBanker += $aRows['nMoney0'];
		}

		$nSumMoney = $nSumBet-$nSumBanker;
		if($nSumMoney < 0) $nSumMoney = 0;

		$nBankerMax = ($aBankerOrder['nUid'] === $aUser['nId'])?$aGameSetting['nBankerMax']:$aGameSetting['nOtherMax'];
		if($nSumMoney >= $nBankerMax)
		{
			if($aUser['nMoney'] >= $nBankerMax * (1+($aGameSetting['nBankerFee']/100)))
			{
				$nSumBanker = $nBankerMax;
				$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
			}
			else
			{
				$nSumBanker = floor($aUser['nMoney'] / (1+($aGameSetting['nBankerFee']/100)));
				$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
			}
		}
		else
		{
			if($aUser['nMoney'] >= $nSumMoney * (1+($aGameSetting['nBankerFee']/100)))
			{
				$nSumBanker = $nSumMoney;
				$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
			}
			else
			{
				$nSumBanker = floor($aUser['nMoney'] / (1+($aGameSetting['nBankerFee']/100)));
				$nMoneyFee = $nSumBanker * ($aGameSetting['nBankerFee'] / 100);
			}
		}
		$aReturn['nSumMoney'] = $nSumMoney;
		$aReturn['nSumSelf'] = $nSumBanker;
	}
	
	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>