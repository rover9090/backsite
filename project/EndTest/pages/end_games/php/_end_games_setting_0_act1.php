<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_setting.php');
	#require

	if ($aJWT['a'] == 'GetGamesSetting')
	{
		$aGameSetting = array();
		$aCurlSetting = array();
		$aData = array();
		$aEditLog = array();
		$aJumpMsg['0']['sMsg'] = '參數異常';

		# CURL 公司 同步專案目錄
		$aPostData = array(
			'nPid' => $aSystem['aWebsite']['nId'],
		);

		$sUrl =  COMPANY['URL'].'API/cpy/GetGamesSetting.php';
		$ch = curl_init($sUrl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');# GET || POST
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aPostData));
		# curl 執行時間
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,3);
		curl_setopt($ch, CURLOPT_TIMEOUT,3);
		$result = curl_exec($ch);
		$aReturn = json_decode($result,true);

		$aCurlSetting = $aReturn['aData'];
		// echo '<pre>';print_r($aCurlSetting);echo '</pre>';exit;

		if($aReturn['nStatus'] == 1 && !empty($aCurlSetting))
		{
			$sSQL = '	SELECT	nId,
							nLid,
							nGame,
							sName0,
							nOnline,
							nCount,
							nSec,
							sOpenTime,
							sCloseTime,
							sNightTime
					FROM 		'. END_GAMES_SETTING .'
					WHERE 	nOnline != 99';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aData[$aRows['nId']] = $aRows;
			}

			$sSQL = 'TRUNCATE ' . END_GAMES_SETTING;
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);

			$nLid = 0;#開始預設 = 0;

			foreach($aCurlSetting as $LPnId => $LPaData)
			{
				$oPdo->beginTransaction();

				foreach(aLANG as $LPsLang => $LPsText)
				{

					$aSQL_Array = array(
						'nLid'		=> (int)	$LPnId,
						'sLang'		=> (string)	$LPsLang,
						'nGame'		=> (int)	$LPaData['nGame'],
						'sName0'		=> (string)	$LPaData['sName0'],
						'nOnline'		=> (int)	$LPaData['nOnline'],
						'nCount'		=> (int)	$LPaData['nCount'],
						'nSec'		=> (int)	$LPaData['nSec'],
						'sOpenTime'		=> (string)	$LPaData['sOpenTime'],
						'sCloseTime'	=> (string)	$LPaData['sCloseTime'],
						'sNightTime'	=> (string)	$LPaData['sNightTime'],
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
						'nUpdateTime'	=> (int)	NOWTIME,
						'sUpdateTime'	=> (string)	NOWDATE,
					);
	
					$sSQL = 'INSERT INTO '. END_GAMES_SETTING . ' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
	
					//log ??
					$aEditLog[END_GAMES_SETTING]['aOld'] = $aData;
					$aEditLog[END_GAMES_SETTING]['aNew'] = $aSQL_Array;
					$aEditLog[END_GAMES_SETTING]['aNew']['nId'] = $LPnId;
					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	0,
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$LPnId,
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8104001,
						'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					DoActionLog($aActionLog);
				}

				$oPdo->commit();
			}
		
			$aJumpMsg['0']['sMsg'] = '同步成功';
		}
		
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_setting_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
?>