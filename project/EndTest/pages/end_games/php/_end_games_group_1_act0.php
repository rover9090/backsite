<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_group.php');
	#require??

	#參數接收區
	$nLid		= filter_input_int('nLid',	INPUT_REQUEST,0);
	$aName 	= array();
	if(isset($_POST['sName0']))
	{
		$aName = $_POST['sName0'];
	}
	$nOnline	= filter_input_int('nOnline',	INPUT_POST, 1);
	$nKid		= filter_input_int('nKid',	INPUT_POST, 0);
	$nSort	= filter_input_int('nSort',	INPUT_POST, 0);
	// $nMoney0	= filter_input_int('nMoney0',	INPUT_POST, 0);
	// $nMoney1	= filter_input_int('nMoney1',	INPUT_POST, 0);
	$aMoney0 	= array();
	if(isset($_POST['aMoney0']))
	{
		$aMoney0 = $_POST['aMoney0'];
	}
	$aMoney1 	= array();
	if(isset($_POST['aMoney1']))
	{
		$aMoney1 = $_POST['aMoney1'];
	}

	#參數結束

	#參數宣告區
	$aId = array();
	$aData = array();
	$aEditLog = array(
		END_GAMES_GROUP	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);

	$aBetContent = array(
		'BW' => aBETCONTENT['BW'],
		'PW' => aBETCONTENT['PW'],
		'DRAW' => aBETCONTENT['DRAW'],
		'BP' => aBETCONTENT['BP'],
		'PP' => aBETCONTENT['PP'],
		'SIX' => aBETCONTENT['SIX'],
	);

	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		$nLid = 0;#開始預設 = 0;
		foreach(aLANG as $LPsLang => $LPsText)
		{
			foreach($aBetContent as $LPsBetContent => $LPsBetText)
			{
				$oPdo->beginTransaction();
				$aSQL_Array = array(
					'sName0'		=> (string)	$aName[$LPsLang],
					'nLid'		=> (int)	$nLid,
					'nKid'		=> (int)	$nKid,
					'sMethod'		=> (string)	$LPsBetContent,
					'sLang'		=> (string)	$LPsLang,
					'nOnline'		=> (int)	$nOnline,
					'nSort'		=> (int)	$nSort,
					'nMoney0'		=> (int)	$aMoney0[$LPsBetContent],
					'nMoney1'		=> (int)	$aMoney1[$LPsBetContent],
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);

				$sSQL = 'INSERT INTO '. END_GAMES_GROUP . ' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nLastId = $oPdo->lastInsertId();

				if($nLid == 0)
				{
					$nLid = $nLastId;

					# 更新 TW nLid
					$aSQL_Array = array(
						'nLid' => (int) $nLid,
					);
					$sSQL = '	UPDATE '. END_GAMES_GROUP . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $nLid, PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}

				//log ??
				$aEditLog[END_GAMES_GROUP]['aNew'] = $aSQL_Array;
				$aEditLog[END_GAMES_GROUP]['aNew']['nId'] = $nLastId;
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$nLastId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102201,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$oPdo->commit();
			}
		}

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = INSV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1.php']).'&nKindId='.$nKid;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'UPT'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						sLang,
						sMethod
				FROM 		'. END_GAMES_GROUP .'
				WHERE 	nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aId[$aRows['sLang']][$aRows['sMethod']] = $aRows['nId'];
		}

		foreach(aLANG as $LPsLang => $LPsText)
		{
			foreach($aBetContent as $LPsBetContent => $LPsBetText)
			{
				$oPdo->beginTransaction();

				$aSQL_Array = array(
					'sName0'		=> (string)	$aName[$LPsLang],
					'nKid'		=> (int)	$nKid,
					'nOnline'		=> (int)	$nOnline,
					'nSort'		=> (int)	$nSort,
					'nMoney0'		=> (int)	$aMoney0[$LPsBetContent],
					'nMoney1'		=> (int)	$aMoney1[$LPsBetContent],
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);

				if(isset($aId[$LPsLang][$LPsBetContent]))
				{
					$sSQL = '	UPDATE '. END_GAMES_GROUP . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
							WHERE	nId = :nId LIMIT 1';
					$Result = $oPdo->prepare($sSQL);
					$Result->bindValue(':nId', $aId[$LPsLang][$LPsBetContent], PDO::PARAM_INT);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
				}
				//沒的話新增
				else
				{
					$aSQL_Array['nCreateTime']	= (int)	NOWTIME;
					$aSQL_Array['sCreateTime']	= (string)	NOWDATE;
					$aSQL_Array['sLang']		= (string)	$LPsLang;
					$aSQL_Array['nLid']		= (int)	$nLid;
					$aSQL_Array['sName0']		= (string)	$aName[$LPsLang];
					$aSQL_Array['sMethod']		= (string)	$LPsBetContent;
					$aSQL_Array['nOnline']		= (int)	$nOnline;
					$aSQL_Array['nSort']		= (int)	$nSort;
					$aSQL_Array['nMoney0']		= (int)	$aMoney0[$LPsBetContent];
					$aSQL_Array['nMoney1']		= (int)	$aMoney1[$LPsBetContent];
					$aSQL_Array['nKid']		= (int)	$nKid;

					$sSQL = 'INSERT INTO '. END_GAMES_GROUP .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$aId[$LPsLang][$LPsBetContent] = $oPdo->lastInsertId();
				}

				#紀錄動作 - 更新
				$aEditLog[END_GAMES_GROUP]['aNew'] = $aSQL_Array;
				$aEditLog[END_GAMES_GROUP]['aNew']['nId'] = $aId[$LPsLang][$LPsBetContent];
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$aId[$LPsLang],
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102202,
					'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
				$oPdo->commit();
			}
		}

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = UPTV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1.php']).'&nKindId='.$nKid;
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'DEL'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						nLid,
						nKid,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'. END_GAMES_GROUP .'
				WHERE 	nOnline != 99
				AND 		nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['nId']] = $aRows;
			$nKid = $aRows['nKid'];
		}

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[END_GAMES_GROUP]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_GAMES_GROUP . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nLid = :nLid ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			$aEditLog[END_GAMES_GROUP]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102003,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_group_1.php']).'&nKindId='.$nKid;
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>