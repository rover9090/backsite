<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_setting.php');
	#require結束

	#參數接收區
	$nLid		= filter_input_int('nLid',		INPUT_REQUEST,0);
	$aName 	= array();
	if(isset($_POST['sName0']))
	{
		$aName = $_POST['sName0'];
	}
	$sName0	= filter_input_str('sName0',		INPUT_POST,'');
	$nOnline	= filter_input_int('nOnline',		INPUT_POST,0);
	$nCount	= filter_input_int('nCount',		INPUT_POST,0);
	$nSec		= filter_input_int('nSec',		INPUT_POST,0);
	$nSec1	= filter_input_int('nSec1',		INPUT_POST,0);
	$nSec2	= filter_input_int('nSec2',		INPUT_POST,0);
	$nGroupId	= filter_input_int('nGroupId',	INPUT_POST,0);
	$sOpenTime	= filter_input_str('sOpenTime',	INPUT_POST,'');
	$sCloseTime	= filter_input_str('sCloseTime',	INPUT_POST,'');
	$sNightTime	= filter_input_str('sNightTime',	INPUT_POST,'');
	$nMaintenance= filter_input_int('nMaintenance',	INPUT_POST,'');
	$nWinPercent= filter_input_int('nWinPercent',	INPUT_POST,0);
	#參數結束

	#參數宣告區
	$aData = array();
	$aId = array();
	$aEditLog = array(
		END_GAMES_SETTING	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'UPT'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						sLang
				FROM 		'. END_GAMES_SETTING .'
				WHERE 	nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aId[$aRows['sLang']] = $aRows['nId'];
		}

		foreach(aLANG as $LPsLang => $LPsText)
		{
			$oPdo->beginTransaction();

			$aSQL_Array = array(
				'sName0'		=> (string)	$aName[$LPsLang],
				'nOnline'		=> (int)	$nOnline,
				'nCount'		=> (int)	$nCount,
				'nSec'		=> (int)	$nSec,
				'nSec1'		=> (int)	$nSec1,
				'nSec2'		=> (int)	$nSec2,
				'nGroupId'		=> (int)	$nGroupId,
				'sOpenTime'		=> (string)	$sOpenTime,
				'sCloseTime'	=> (string)	$sCloseTime,
				'sNightTime'	=> (string)	$sNightTime,
				'nMaintenance'	=> (int)	$nMaintenance,
				'nWinPercent'	=> (int)	$nWinPercent,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			if(isset($aId[$LPsLang]))
			{
				$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aId[$LPsLang], PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
			}
			//沒的話新增
			else
			{
				$aSQL_Array['nLid']		= (int)	$nLid;
				$aSQL_Array['sLang']		= (string)	$LPsLang;
				$aSQL_Array['nCreateTime']	= (int)	NOWTIME;
				$aSQL_Array['sCreateTime']	= (string)	NOWDATE;

				$sSQL = 'INSERT INTO '. END_GAMES_SETTING .' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aId[$LPsLang] = $oPdo->lastInsertId();
			}

			#紀錄動作 - 更新
			$aEditLog[END_GAMES_SETTING]['aNew'] = $aSQL_Array;
			$aEditLog[END_GAMES_SETTING]['aNew']['nId'] = $aId[$LPsLang];
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aId[$LPsLang],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104102,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$oPdo->commit();
		}

		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sIcon'] = 'success';
		$aJumpMsg['0']['sMsg'] = UPTV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'DEL'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'. END_GAMES_SETTING .'
				WHERE 	nOnline != 99
				AND 		nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['nId']] = $aRows;
		}

		if (empty($aData))
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = NODATA;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[END_GAMES_SETTING]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. END_GAMES_SETTING . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nLid = :nLid ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[END_GAMES_SETTING]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8104103,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>