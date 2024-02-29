<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	require_once('inc/lang/'.$aSystem['sLang'].'/bet.php');
	#require結束

	#參數接收區
	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	$sText = filter_input_str('sText', INPUT_POST, '');
	$sLineId = filter_input_str('sLineId', INPUT_POST, '');

	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sBetAjax'		=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.bet_0.php']),
		'sBankerAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.banker_0.php']),
		'sCancelAjax'	=> sys_web_encode($aMenuToNo['pages/bet/php/_ajax.cancel_0.php']),
	);
	#url結束

	#參數宣告區 - declare
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
	$aBetJWT = array(
		'a'		=> 'BET',
		't'		=> NOWTIME,
	);
	$sBetJWT = sys_jwt_encode($aBetJWT);
	$aBanker0JWT = array(
		'a'		=> 'BANKER0',
		't'		=> NOWTIME,
	);
	$sBanker0JWT = sys_jwt_encode($aBanker0JWT);
	$aBanker1JWT = array(
		'a'		=> 'BANKER1',
		't'		=> NOWTIME,
	);
	$sBanker1JWT = sys_jwt_encode($aBanker1JWT);
	$aCancelBetJWT = array(
		'a'		=> 'CANCELBET',
		't'		=> NOWTIME,
	);
	$sCancelBetJWT = sys_jwt_encode($aCancelBetJWT);
	$aCancelCollectJWT = array(
		'a'		=> 'CANCELCOLLECT',
		't'		=> NOWTIME,
	);
	$sCancelCollectJWT = sys_jwt_encode($aCancelCollectJWT);

	$aBanker = array(
		'nId'	=>0,
		'sNo'	=>'',
		'nUid'=>0,
	);

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
	);
	#宣告結束

	#程式邏輯區
	if($aChkLogin_Member === false)
	{
		$aJWT['a'] = 'RETURN';
		$aReturn['sMsg'] = aBET['ACCOUNTERROR'];
	}

	if($aJWT['a'] == 'RETURN')
	{
		$aReturn['nError'] = 1;
	}

	if($aJWT['a'] == 'CHAT')
	{

		$aChkLogin_Member['nId'] = $aChkLogin_Member['nUid'];
		$nUid = $aChkLogin_Member['nUid'];
		$aMember = $aChkLogin_Member;

		
		if($sText === '')
		{
			$aReturn['nError'] = 1;
			$aReturn['sMsg'] = aBET['NOMSG'];
		}
		else if($aReturn['nError'] == 0)
		{
			$sSQL = '	SELECT 	nId,
							sName0
					FROM 		'.END_CHAT_FILTER.'
					WHERE 	nOnline = 1';
			$Result = $oPdo->prepare($sSQL);
			// $Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				// $aFilter[$aRows['sName0']] = $aRows['sName0'];
				$sText = str_replace($aRows['sName0'],'**',$sText);
			}

			$sJsonMsg = file_get_contents(dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt');
			$aJsonMsg = !empty($sJsonMsg)?json_decode($sJsonMsg,true):array();
			// trigger_error($sJsonMsg);
			
			$nMicroTime = microtime(true);
			$nSendTime = ($nMicroTime+2)*10000;
			$sSendMsg = '<div class="betmsg font-xxs msgMember">
				<div class="coverbox">
					<div class="td imgbox float-left"><img class="img-circle" src="'.$aUser['sHeadImg'].'"></div>
					<div class="td float-left ifmeright msgMemberInf">
						<div class="dtbox">
							<div class="dt-nickname">'.$aMember['sName0'].'</div>
						</div>
						<div class="betmsgBlock arrow">
							<div class="betinfobox">
								<div class="betinfo">
									<div>'.
									$sText.'</div>
								</div>
							</div>
						</div>
						<div class="dt-time">
							<div class="dt-timeInner">'.NOWCLOCK.'</div>
						</div>
					</div>
				</div>
			</div>';
			$aJsonMsg[$nSendTime] = array(
				'nRefresh' => '1',
				'nUid' => $aMember['nId'],
				'sMsg' => $sSendMsg,
			);
			$sJsonMsg = json_encode($aJsonMsg);
			$sFile = dirname(dirname(dirname(dirname(__file__)))).'/MsgJson'.$nGame.'.txt';
			file_put_contents($sFile, $sJsonMsg,LOCK_EX);
			
			$sSendMsg = str_replace('msgMember"','ifme msgSelf"',$sSendMsg);
			$sSendMsg = str_replace('dtbox"','dtbox DisplayBlockNone"',$sSendMsg);
			$sSendMsg = str_replace('td imgbox float-left"','td imgbox float-left DisplayBlockNone "',$sSendMsg);
			
			// $sSendMsg = str_replace('msgMember"','ifme msgService"',$sSendMsg);
			if($aReturn['nError'] == 0)
			{				
				$aReturn['nError'] = 0;
				// $aReturn['sMsg'] = aBET['DONATESUCCESS'];
				$aReturn['aData']['sSendMsg'] = $sSendMsg;
				$aReturn['aData']['nMicroTime'] = (int)$nSendTime;
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
							nUid
					FROM  	'.CLIENT_GAMES_BANKER.'
					WHERE 	sNo = :sNo
					AND		nStatus = 1
					AND		nDone = 0
					AND		nType0 = 0
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sNo',$aGame['sNo'],PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aBanker = $aRows;
			}

			if(preg_match('/[abcABC]{1,3}[0-9]{3,7}/',$sText) && $aBanker['nUid'] != $aUser['nId']) # 聊天室輸入投注
			{
				$nErr = 0;
				$aBetData = array();
				$aText = array();

				// $aText = explode(" ",$sText);
				$aText[0] = preg_replace('/[0-9]+/', '', $sText);
				$aText[1] = preg_replace('/[abcABC]+/', '', $sText);

				$aBetText = preg_split("//",$aText[0],-1,PREG_SPLIT_NO_EMPTY);
				$nMoney = $aText[1];

				if(count($aBetText)>3)
				{
					$nErr = 1;
				}
				if(!is_numeric($nMoney))
				{
					$nErr = 1;
				}
				if($nErr == 0)
				{
					foreach($aBetText as $LPnOrder => $LPsBet)
					{
						if($LPsBet === 'a' ||$LPsBet === 'A')
						{
							$aBetData['A'] = (int) $aText[1];
						}
						elseif($LPsBet === 'b' ||$LPsBet === 'B')
						{
							$aBetData['B'] = (int) $aText[1];
						}
						elseif($LPsBet === 'c' ||$LPsBet === 'C')
						{
							$aBetData['C'] = (int) $aText[1];
						}
					}

					$aParam = array(
						'sJWT'	=> $sBetJWT,
						'nGame'	=> $nGame,
						'sLineId'	=> $sLineId,
						'aBetData'  => $aBetData,
						'run_page'	=> 1,
					);
				
					$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.bet_0.RLBe';
	
					$ch = curl_init($sUrl);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
					curl_setopt($ch, CURLOPT_TIMEOUT,3);
					$result = curl_exec($ch);
					curl_close($ch);
				}
			}
			elseif($sText === 'W' || $sText === 'w' || $sText === '排隊' || $sText === '排莊')  # 聊天室輸入W/w => 排莊
			{
				$aParam = array(
					'sJWT'	=> $sBanker0JWT,
					'nGame'	=> $nGame,
					'sLineId'	=> $sLineId,
					'run_page'	=> 1,
				);
			
				$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.banker_0.RLBe';

				$ch = curl_init($sUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
				curl_setopt($ch, CURLOPT_TIMEOUT,3);
				$result = curl_exec($ch);
				curl_close($ch);
			
				$aReturn2 = json_decode($result,true);
			
				if(!empty($aReturn2))
				{
				}
			}
			elseif(preg_match('/[pP]{1}[0-9]{3,7}/',$sText) && $aBanker['nUid'] != $aUser['nId'])  # 聊天室輸入配莊
			{

				$nErr = 0;
				$aBetData = array();

				// $aText = explode(" ",$sText);
				$aText[0] = preg_replace('/[0-9]+/', '', $sText);
				$aText[1] = preg_replace('/[pP]+/', '', $sText);
				$aBetText = preg_split("//",$aText[0],-1,PREG_SPLIT_NO_EMPTY);
				$nMoney = $aText[1];

				if(count($aBetText)>1)
				{
					$nErr = 1;
				}
				if(!is_numeric($nMoney))
				{
					$nErr = 1;
				}
				if($nErr == 0)
				{
					$aParam = array(
						'sJWT'	=> $sBanker1JWT,
						'nGame'	=> $nGame,
						'sLineId'	=> $sLineId,
						'nMoney'  	=> $nMoney,
						'run_page'	=> 1,
					);
				
					$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.banker_0.RLBe';
	
					$ch = curl_init($sUrl);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
					curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
					curl_setopt($ch, CURLOPT_TIMEOUT,3);
					$result = curl_exec($ch);
					curl_close($ch);
				}
			}
			elseif($aBanker['nUid'] == $aUser['nId'] && ($sText === '殺' || is_numeric($sText)))	# 莊家直接輸入打莊金額
			{
				$aGameSetting = GameSet_Class::fnCallSetting($nGame);
				$aGameSetting = $aGameSetting[$nGame];

				if($sText === '殺' || $sText > $aGameSetting['nBankerMax'])
				{
					$sText = $aGameSetting['nBankerMax'];
				}

				$nMoney = $sText;

				$aParam = array(
					'sJWT'	=> $sBanker1JWT,
					'nGame'	=> $nGame,
					'sLineId'	=> $sLineId,
					'nMoney'  	=> $nMoney,
					'run_page'	=> 1,
				);
			
				$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.banker_0.RLBe';

				$ch = curl_init($sUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
				curl_setopt($ch, CURLOPT_TIMEOUT,3);
				$result = curl_exec($ch);
				curl_close($ch);
			}
			elseif($sText === '取消配莊' || $sText === '取消配')
			{
				$aParam = array(
					'sJWT'	=> $sCancelCollectJWT,
					'nGame'	=> $nGame,
					'sLineId'	=> $sLineId,
					'run_page'	=> 1,
				);
			
				$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.cancel_0.RLBe';

				$ch = curl_init($sUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
				curl_setopt($ch, CURLOPT_TIMEOUT,3);
				$result = curl_exec($ch);
				curl_close($ch);
			}
			elseif($sText === '取消下注' || $sText === '取消下')
			{
				$aParam = array(
					'sJWT'	=> $sCancelBetJWT,
					'nGame'	=> $nGame,
					'sLineId'	=> $sLineId,
					'run_page'	=> 1,
				);
			
				$sUrl = $aSystem['aWebsite']['sUrl3'].'?_ajax.cancel_0.RLBe';

				$ch = curl_init($sUrl);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aParam));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
				curl_setopt($ch, CURLOPT_TIMEOUT,3);
				$result = curl_exec($ch);
				curl_close($ch);				
			}
		}
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	exit;
?>