<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_user_data.php');
	#require end

	#參數接收區
	$nId		= filter_input_int('nId',			INPUT_REQUEST,0);
	$nMoney	= filter_input_int('nMoney',			INPUT_REQUEST,0);
	$sMemo	= filter_input_str('sMemo',			INPUT_REQUEST,'');
	$nType3	= filter_input_int('nType3',			INPUT_REQUEST,-1);
	#參數結束

	#參數宣告區
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_act2.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_user_data/php/_client_user_data_0_upt2.php']),
		'sHtml'	=> 'pages/client_user_data/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_user_data_0_upt2.php',
	);
	$aData = array();
	$aMemberData = array();
	$aEditLog = array(
	);
	$nErr = 0;
	$sErrMsg = '';
	$nBefore = 0;
	$nDelta = 0;
	$nAfter = 0;
	$nUid = 0;
	$nLogType = 0;
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		$nUid = $aJWT['nUid'];
		$nLogCode = $nType3 === 1?8103301:8103302;# 存入:提領
		do
		{
			if($nMoney <= 0)
			{
				$nErr = 1;
				$sMsg = aUSER['LESSTHANZERO'];
				break;
			}
			if($nType3 != 1 && $nType3 !== 2)
			{
				$nErr = 1;
				$sMsg = aUSER['TYPE3UNFILED'];
				break;
			}


			$sSQL = '	SELECT	nId as nUid,
							sName0
					FROM		'. CLIENT_USER_DATA .' 
					WHERE		nId = :nUid
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
			sql_query($Result);
			$aMemberData = $Result->fetch(PDO::FETCH_ASSOC);

			$sSQL = '	SELECT	nId as nUid,
							sName0
					FROM		'. CLIENT_USER_DATA .' 
					WHERE		nId = :nUid
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
			sql_query($Result);
			$aAdmData = $Result->fetch(PDO::FETCH_ASSOC);
			if($aAdm['nUid'] === '0') $aAdmData = array('nUid'=>0,'sName0'=>'');

			$oPdo->beginTransaction();

			$sSQL = '	SELECT	nMoney
					FROM		'. CLIENT_USER_MONEY .' 
					WHERE		nUid = :nUid
					LIMIT		1
					FOR UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
			sql_query($Result);
			$aMemberMoney = $Result->fetch(PDO::FETCH_ASSOC);

			$sSQL = '	SELECT	nMoney
					FROM		'. CLIENT_USER_MONEY .' 
					WHERE		nUid = :nUid
					LIMIT		1
					FOR UPDATE';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aAdm['nUid'], PDO::PARAM_INT);
			sql_query($Result);
			$aAdmMoney = $Result->fetch(PDO::FETCH_ASSOC);
			if($aAdm['nUid'] === '0') $aAdmMoney = array('nMoney'=>0);

			if($nType3 === 1 && $aAdmMoney['nMoney'] < $nMoney)
			{
				$nErr = 1;
				$sMsg = aUSER['TRANSFERINERR'];
				break;
			}

			if($nType3 === 2 && $aMemberMoney['nMoney'] < $nMoney)
			{
				$nErr = 1;
				$sMsg = aUSER['TRANSFEROUTERR'];
				break;
			}

			if($nType3 === 1)# adm轉出  member轉入
			{
				$aNewMoney = array(
					'Money' => (float) $aAdmMoney['nMoney'] - $nMoney,
				);	
				$aAdmSQL_Array = oTransfer::PointUpdate($aAdmData['nUid'],$aNewMoney,1,true);
				$aAdmMoney['nDelta'] = $nMoney * -1;
				$aAdmMoney['nAfter'] = $aAdmMoney['nMoney'] - $nMoney;
				$aAdmData['nLogType'] = 207;

				$aNewMoney = array(
					'Money' => (float) $aMemberMoney['nMoney'] + $nMoney,
				);	
				$aMemberSQL_Array = oTransfer::PointUpdate($aMemberData['nUid'],$aNewMoney,1,true);
				$aMemberMoney['nDelta'] = $nMoney;
				$aMemberMoney['nAfter'] = $aMemberMoney['nMoney'] + $nMoney;
				$aMemberData['nLogType'] = 208;
			}
			else if($nType3 === 2)# adm轉入 member轉出
			{
				$aNewMoney = array(
					'Money' => (float) $aAdmMoney['nMoney'] + $nMoney,
				);	
				$aAdmSQL_Array = oTransfer::PointUpdate($aAdmData['nUid'],$aNewMoney,1,true);
				$aAdmMoney['nDelta'] = $nMoney;
				$aAdmMoney['nAfter'] = $aAdmMoney['nMoney'] + $nMoney;
				$aAdmData['nLogType'] = 208;

				$aNewMoney = array(
					'Money' => (float) $aMemberMoney['nMoney'] - $nMoney,
				);
				$aMemberSQL_Array = oTransfer::PointUpdate($aMemberData['nUid'],$aNewMoney,1,true);
				$aMemberMoney['nDelta'] = $nMoney * -1;
				$aMemberMoney['nAfter'] = $aMemberMoney['nMoney'] - $nMoney;
				$aMemberData['nLogType'] = 207;
			}

			$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aAdmSQL_Array ).'
					WHERE	nUid = :nUid LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aAdmData['nUid'], PDO::PARAM_INT);
			sql_build_value($Result, $aAdmSQL_Array);
			sql_query($Result);

			$sSQL = '	UPDATE '. CLIENT_USER_MONEY .' SET ' . sql_build_array('UPDATE', $aMemberSQL_Array ).'
					WHERE	nUid = :nUid LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aMemberData['nUid'], PDO::PARAM_INT);
			sql_build_value($Result, $aMemberSQL_Array);
			sql_query($Result);

			$oPdo->commit();

			$aSQL_Array = array(
				'nUid'			=> (int)	$nUid,
				'nMoney'			=> (float)	$nMoney,
				'nStatus'			=> (int)	1,
				'nType0'			=> (int)	5,# 1 = 公司 2 = 線上 3 = 出款 4 = 人工 5=money(點數client_user_money)	
				'nType1'			=> (int)	2,# 人工
				'nType3'			=> (int)	$nType3,
				'sMemo'			=> (string)	$sMemo,
				'nCreateTime'		=> (int)	NOWTIME,
				'sCreateTime'		=> (string)	NOWDATE,
				'nUpdateTime'		=> (int)	NOWTIME,
				'sUpdateTime'		=> (string)	NOWDATE,
				'nCreateDay'		=> (int)	strtotime('today')
			);

			$sSQL = 'INSERT INTO '. CLIENT_MONEY . ' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();
			$aEditLog[CLIENT_MONEY]['aNew'][$nLastId] = $aSQL_Array;

			$aAdmAccLog = array(
				'nUid' 		=> (int)	$aAdmData['nUid'],
				'nKid' 		=> (int)	$nLastId,
				'nType0' 		=> (int)	2,
				'nType1' 		=> (int)	$aAdmData['nLogType'],
				'nType2' 		=> (int)	0,
				'nBefore' 		=> (float)	$aAdmMoney['nMoney'],
				'nDelta' 		=> (float)	$aAdmMoney['nDelta'],
				'nAfter' 		=> (float)	$aAdmMoney['nAfter'],
				'sParams' 		=> (string)	'',
				'nCreateTime' 	=> (int)	NOWTIME,
				'sCreateTime' 	=> (string)	NOWDATE,
				'nCreateDay' 	=> (int)	strtotime('today'),
			);
			DoLogAcc($aAdmAccLog);

			$aMemberAccLog = array(
				'nUid' 		=> (int)	$aMemberData['nUid'],
				'nKid' 		=> (int)	$nLastId,
				'nType0' 		=> (int)	2,
				'nType1' 		=> (int)	$aMemberData['nLogType'],
				'nType2' 		=> (int)	0,
				'nBefore' 		=> (float)	$aMemberMoney['nMoney'],
				'nDelta' 		=> (float)	$aMemberMoney['nDelta'],
				'nAfter' 		=> (float)	$aMemberMoney['nAfter'],
				'sParams' 		=> (string)	'',
				'nCreateTime' 	=> (int)	NOWTIME,
				'sCreateTime' 	=> (string)	NOWDATE,
				'nCreateDay' 	=> (int)	strtotime('today'),
			);
			DoLogAcc($aMemberAccLog);

			$aActionLog = array(
				'nWho'		=> (int)	$aAdmData['nUid'],
				'nWhom'		=> (int)	$aMemberData['nUid'],
				'sWhomAccount'	=> (string)	$aMemberData['sName0'],
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode, 
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);


		}while(false);

		if ($nErr === 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sPage'].'&nId='.$nUid;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = UPTV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>