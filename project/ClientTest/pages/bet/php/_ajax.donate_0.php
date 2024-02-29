<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	$nMoney = filter_input_str('nMoney', INPUT_POST, '');
	$sAllitem = filter_input_str('sAllitem', INPUT_POST, '');
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');
	// $sPlay = $_POST['sPlay'];
	// $aPlay = json_decode($sPlay,true);

	$sAllitem = substr($sAllitem,0,-1);
	$aItem = explode(",",$sAllitem);
	$nNowGameBet = 0;
	$sServerIp = SOCKETIP;
	$nPort = SOCKETPORT;
	$oSocket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
	$aSocketMsg = array();
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare
	$sDealer = 0;
	$nAllBetMoney = 0;
	$nNowGameBet = 0;
	$sContent = '';
	// $aDonatePic = array(
	// 	'100' => 1,
	// 	'200' => 2,
	// 	'300' => 3,
	// 	'500' => 4,
	// 	'1000' => 5,
	// 	'2000' => 6,
	// 	'3000' => 7,
	// 	'5000' => 8,
	// 	'10000' => 9,
	// );
	$aDonate = $aDefineDonate;
	$nDonateSum = 0;
	$sLastPicName = 9;
	$sDonatePic = 0;

	$sSQL = '	SELECT 	User_.nId,
					User_.sName0,
					User_.sAccount,
					User_.nStatus,
					User_.nIdentity,
					User_.nCreateTime,
					User_.sPicture,
					User_.sSiteId,
					Money_.nMoney
			FROM 		'.CLIENT_USER_DATA.' User_
			JOIN		'.CLIENT_USER_MONEY.' Money_
			ON		User_.nId = Money_.nUid
			WHERE 	User_.sLineId = :sLineId
			AND		nOnline != 99
			AND		nStatus != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLineId', $sLineId, PDO::PARAM_STR);
	sql_query($Result);
	$aUser = $Result->fetch(PDO::FETCH_ASSOC);
	$aUser['nUid'] = $aUser['nId'];
	$aUser['sHeadImg'] = $aUser['sPicture'];
	$aChkLogin_Member = $aUser;

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
		'aUser'		=> $aUser,
		'aSocketMsg'	=> array(),
	);
	#宣告結束

	#程式邏輯區
	if($aChkLogin_Member === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}

	
	if(empty($nMoney))
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['MONEYERROR'].'1';
	}

	if($aJWT['a'] == 'RETURN')
	{
		$aReturn['nError'] = 1;
	}

	if($aJWT['a'] == 'DONATE')
	{

		$aChkLogin_Member['nId'] = $aChkLogin_Member['nUid'];
		$nUid = $aChkLogin_Member['nUid'];
		$aMember = $aChkLogin_Member;

		$sSQL = '	SELECT 	sDealer
				FROM  	'.END_GAMES_SETTING.'
				WHERE 	nGame = :nGame
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
		sql_query($Result);
		$aGame = $Result->fetch(PDO::FETCH_ASSOC);
		$sDealer = ($aGame === false)?0:$aGame['sDealer'];

		$sSQL = '	SELECT 	sAccount,sName0,sPer
				FROM  	'.END_MANAGER_DATA.'
				WHERE 	nId = :sDealer
				LIMIT 	1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sDealer', $sDealer,PDO::PARAM_INT);
		sql_query($Result);
		$aDealer = $Result->fetch(PDO::FETCH_ASSOC);
		// $aDealer = array(
		// 	'sAccount' => 'engineer',
		// 	'sName0' => '工程測試',
		// 	'sPer' => '0',
		// );
		if($aDealer == false)
		{
			$aDealer = array(
				'sPer' => 0,
			);
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NODEALER'];
		}

		if($aGame === false)
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aACTMSG['NOROUND'];
		}
		else if($aReturn['nError'] == 0)
		{
			// donate的表好像不需要 都直接抓end_log_account
			foreach($aItem as $LPnItem)
			{
				if($LPnItem != 99)
				{
					if(!isset($aDonate[$LPnItem]))
					{
						$aReturn['nError'] = 1;
						$aReturn['sMsg'] = aACTMSG['DONATEERR'];
						// $aReturn['sDebug'] = print_r($aItem,true);
						echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
						exit;
					}
					$nDonateSum += $aDonate[$LPnItem];
				}
				else
				{
					$nMoney = floor($nMoney);
					$nDonateSum = $nMoney;
				}
			}
			// error_log($nDonateSum);
			// error_log($nMoney);
			if($nDonateSum != $nMoney)
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aACTMSG['DONATEERR'];
				echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
				exit;
			}		

			$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
			$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
			
			$oPdo->beginTransaction();

			$sSQL = '	SELECT 	nMoney
					FROM  	'.CLIENT_USER_MONEY.'
					WHERE 	nUid = :nUid
					LIMIT 	1
					FOR 		UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid,PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			$aMember['nMoney'] = $aRows['nMoney'];

			if($aMember === false || !oTransfer::PointCheck($aMember['nId']))
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aACTMSG['MONEYERR'];
				echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
				exit;
			}

			if($nMoney < 0 || !is_numeric($nMoney))
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aBET['MONEYERROR'];
				echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
				exit;
			}

			if($aMember['nMoney'] < $nMoney )
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aBET['NOMONEY'];
				echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
				exit;
			}

			$aNewMoney = array(
				'Money' => (float) $aMember['nMoney'] - $nMoney
			);
			$aSQL_Array = oTransfer::PointUpdate($aMember['nId'],$aNewMoney);

			if($aSQL_Array !== false)
			{
				$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nUid = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $aMember['nId'], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nLastId = $oPdo->lastInsertId();

				$aSQL_Array = array(
					'nUid'		=> (int)	$aMember['nId'],
					'nKid'		=> (int)	$nLastId,
					'nType0'		=> (int)	3,
					'nType1'		=> (int)	401,
					'nType2'		=> (int)	0,
					'nBefore'		=> (float)	$aMember['nMoney'],
					'nDelta'		=> (float)	$nMoney * -1,
					'nAfter'		=> (float)	$aMember['nMoney'] - $nMoney,
					'sParams'		=> (string)	'',
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nCreateDay'	=> (int)	strtotime('today'),
					'sSiteId'		=> (string)	$aUser['sSiteId'],
					'sDealer'		=> $sDealer,
				);
				DoLogAcc($aSQL_Array);

				$aReturn['aUser']['nMoney'] = $aNewMoney['Money'];

				$oPdo->commit();

				$sDonateMsg = '';
				foreach($aItem as $LPnItem)
				{
					if($LPnItem != 99)
					{
						$aSQL_Array = array(
							'nUid' => $aUser['nId'],
							'sDealer' => $sDealer,
							'nMoney0' => $aDonate[$LPnItem],
							'nGame' => $nGame,
							'sPer' => $aDealer['sPer'],
							'nCreateTime' => NOWTIME,
							'sCreateTime' => NOWDATE,
						);
					}
					else
					{
						$aSQL_Array = array(
							'nUid' => $aUser['nId'],
							'sDealer' => $sDealer,
							'nMoney0' => $nMoney,
							'nGame' => $nGame,
							'sPer' => $aDealer['sPer'],
							'nCreateTime' => NOWTIME,
							'sCreateTime' => NOWDATE,
						);
					}
	
					$sSQL = 'INSERT INTO '. CLIENT_GAMES_DONATE .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
	
					// foreach($aDonatePic as $LPnMoney => $LPsId)
					// {
					// 	if($nMoney <= $LPnMoney)
					// 	{
					// 		$sDonatePic = $LPsId;
					// 		break;
					// 	}
					// }
					// if($sDonatePic === 0)
					// {
					// 	$sDonatePic = $sLastPicName;
					// }
					if($LPnItem != 99)
					{
						$nDonateSum += $aDonate[$LPnItem];
						$aReturn['aData'][]['sPicUrl'] = 'images/donate/'.$LPnItem.'.png?1';
						$sDonateMsg .= '<div class=" JqBetMsg">[<img class="chatroomContImg" src="images/donate/'.$LPnItem.'.png?1" alt="">'.aBET['DONATESUCCESS'].']'.$aDonate[$LPnItem].'</div>';
					}
					else
					{
						$sDonateMsg .= '<div class=" JqBetMsg">['.aBET['DONATESUCCESS'].']'.number_format($nMoney).'</div>';
					}
				}
				$nMicroTime = (float)microtime(true);
				$aJsonMsg[$nMicroTime*10000] = array(
					'sType'	=> 'donate',
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
												<div>'.$aMember['sName0'].'<br>'.
												$sDonateMsg.'</div>
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
				$aJsonMsg[($nMicroTime)*10000+1] = array(
					'sType'	=> 'donate',
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
												<div>'.SITETITLE.'謝謝老闆打賞'.'
												</div>
											</div>
										</div>
									</div>
									<div class="dt-time">
										<div class="dt-timeInner">'.date('H:i:s',NOWTIME).'</div>
									</div>
								</div>
							</div>
						</div>',
				);
				
				$sJsonMsg = json_encode($aJsonMsg);
				$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
				file_put_contents($sFile, $sJsonMsg,LOCK_EX);

				$sShowScreen = file_get_contents(dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt');
				$aShowScreen = !empty($sShowScreen)?json_decode($sShowScreen,true):array();	
				
				// $aReturn['aSocketMsg'][] = $aJsonMsg[$nMicroTime*10000];
				// $aReturn['aSocketMsg'][] = $aJsonMsg[($nMicroTime)*10000+1];
				// foreach($aShowScreen as $LPsKey => $LPaData)
				// {
				// 	if($nMicroTime - $LPsKey > 3600*10000)
				// 	{
				// 		unset($aShowScreen[$LPsKey]);
				// 	}					
				// }
				$aShowScreen[$nMicroTime*10000]['sMsg'] = '<div>'.$aMember['sName0'].'<br>'.$sDonateMsg.'</div>';
				$aShowScreen[$nMicroTime*10000]['nPlay'] = 0;
				$sShowScreen = json_encode($aShowScreen);
				$sFile = dirname(dirname(dirname(dirname(dirname(__file__))))).'/EndTest/Bot/file/'.$nGame.'ObsMsg.txt';
				file_put_contents($sFile, $sShowScreen,LOCK_EX);

				$aSocketMsg[0] = $aJsonMsg[$nMicroTime*10000];
				$aSocketMsg[1] = $aJsonMsg[($nMicroTime)*10000+1];
				$sSocketMsg = json_encode($aSocketMsg);
				socket_connect($oSocket,$sServerIp,$nPort);
				if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
					trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
				}
				// 分兩次送會收不到 進入發送失敗 原因不明
				// $aSocketMsg[0] = $aJsonMsg[($nMicroTime)*10000+1];
				// $sSocketMsg = json_encode($aSocketMsg);
				// if(!socket_write($oSocket,$sSocketMsg,strlen($sSocketMsg))) {
				// 	trigger_error('訊息發送失敗:'.strlen($sSocketMsg));
				// }
				socket_close($oSocket);
			}
			else
			{
				$aReturn['nError'] = 1;
				$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
				echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
				exit;
			}

			if($aReturn['nError'] == 0)
			{				
				$aReturn['nError'] = 0;
				$aReturn['sMsg'] = aBET['DONATESUCCESS'];
			}
		}
		
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	exit;
?>