<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_announce.php');
	#require結束

	#參數接收區
	$nId		= filter_input_int('nId',		INPUT_REQUEST,0);
	$nLid		= filter_input_int('nLid',		INPUT_REQUEST,0);
	$nKid		= filter_input_int('nKid',		INPUT_POST,0);
	$nGame	= filter_input_int('nGame',		INPUT_POST,0);
	$nOnline	= filter_input_int('nOnline',		INPUT_POST,0);
	$sPushTime	= filter_input_str('sPushTime',	INPUT_POST,'');
	$sEndTime	= filter_input_str('sEndTime',	INPUT_POST,'');
	$aName 	= array();
	$aContent 	= array();
	if(isset($_POST['sName0']))
	{
		$aName = $_POST['sName0'];
	}
	if(isset($_POST['sContent0']))
	{
		$aContent = $_POST['sContent0'];
	}
	#參數結束

	#參數宣告區
	$aId = array();
	$aData = array();
	$aEditLog = array(
		CLIENT_ANNOUNCE	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$nPushTime = 0;
	$nPushTime = strtotime($sPushTime);
	$nEndTime = strtotime($sEndTime);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		$nLid = 0;#開始預設 = 0;
		foreach(aLANG as $LPsLang => $LPsText)
		{
			$oPdo->beginTransaction();
			$aSQL_Array = array(
				'sName0'		=> (string)	$aName[$LPsLang],
				'sContent0'		=> (string)	$aContent[$LPsLang],
				'nOnline'		=> (int)	$nOnline,
				'nGame'		=> (int)	$nGame,
				'nKid'		=> (int)	$nKid,
				'nLid'		=> (int)	$nLid,
				'sLang'		=> (string)	$LPsLang,
				'nPushTime'		=> (int)	$nPushTime,
				'sPushTime'		=> (string)	$sPushTime,
				'nEndTime'		=> (int)	$nEndTime,
				'sEndTime'		=> (string)	$sEndTime,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = 'INSERT INTO '. CLIENT_ANNOUNCE . ' ' . sql_build_array('INSERT', $aSQL_Array );
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
				$sSQL = '	UPDATE '. CLIENT_ANNOUNCE . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $nLid, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
			}

			// 新增圖片
			if(isset($_FILES['sFile_'.$LPsLang]) && $_FILES['sFile_'.$LPsLang]['name'] != '')
			{
				$aFile['sTable'] = CLIENT_ANNOUNCE;
				$aFile['aFile'] = $_FILES['sFile_'.$LPsLang];
				$aFileInfo = goImage($aFile);
				if($aFileInfo['error'] == 'error')
				{
					$oPdo->rollback();

					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = aImgErr['error'];
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				}
				else
				{
					$aTmp = explode('.',$aFileInfo['sFilename']);
					$aFileInfo['sFilename'] = str_replace(end($aTmp),'png',$aFileInfo['sFilename']);
					$sFileName = $aFileInfo['sFilename'];

					$aSQL_Array = array(
						'nKid'		=> (int)	$nLastId,
						'sTable'		=> (string)	CLIENT_ANNOUNCE,
						'sFile'		=> (string)	$sFileName,
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'  	=> (string)	NOWDATE,
					);

					$sSQL = 'INSERT INTO ' . CLIENT_IMAGE_CTRL . ' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nImageLastId = $oPdo->lastInsertId();

					#紀錄動作 - 新增
					$aEditLog[CLIENT_IMAGE_CTRL]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_IMAGE_CTRL]['aNew']['nId'] = $nImageLastId;
					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	0,
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$nImageLastId,
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8102104,	// 新增圖片
						'sParam'		=> (string)	json_encode($aEditLog),
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					DoActionLog($aActionLog);
				}
			}

			#紀錄動作 - 新增
			$aEditLog[CLIENT_ANNOUNCE]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_ANNOUNCE]['aNew']['nId'] = $nLastId;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLastId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102101,
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
		$aJumpMsg['0']['sMsg'] = INSV;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'UPT'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						sName0,
						nOnline,
						nKid,
						nLid,
						sLang,
						nPushTime,
						sPushTime,
						nEndTime,
						sEndTime,
						sContent0,
						nUpdateTime,
						sUpdateTime,
						nGame
				FROM 		'. CLIENT_ANNOUNCE .'
				WHERE 	nLid = :nLid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nLid',$nLid,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aId[$aRows['sLang']] = $aRows['nId'];
			$aData[$aRows['nId']] = $aRows;
		}

		foreach(aLANG as $LPsLang => $LPsText)
		{
			$oPdo->beginTransaction();

			$aSQL_Array = array(
				'sName0'		=> (string)	$aName[$LPsLang],
				'nOnline'		=> (int)	$nOnline,
				'nGame'		=> (int)	$nGame,
				'nKid'		=> (int)	$nKid,
				'sContent0'		=> (string)	$aContent[$LPsLang],
				'nPushTime'		=> (int)	$nPushTime,
				'sPushTime'		=> (string)	$sPushTime,
				'nEndTime'		=> (int)	$nEndTime,
				'sEndTime'		=> (string)	$sEndTime,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			if(isset($aId[$LPsLang]))
			{
				$sSQL = '	UPDATE '. CLIENT_ANNOUNCE . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nId LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nId', $aId[$LPsLang], PDO::PARAM_INT);
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

				$sSQL = 'INSERT INTO '. CLIENT_ANNOUNCE .' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$aId[$LPsLang] = $oPdo->lastInsertId();
				$aData[$aId[$LPsLang]] = $aSQL_Array;
			}

			// 新增圖片
			if(isset($_FILES['sFile_'.$LPsLang]) && $_FILES['sFile_'.$LPsLang]['name'] != '')
			{
				$aFile['sTable'] = CLIENT_ANNOUNCE;
				$aFile['aFile'] = $_FILES['sFile_'.$LPsLang];
				$aFileInfo = goImage($aFile);
				if($aFileInfo['error'] == 'error')
				{
					$oPdo->rollback();

					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = aImgErr['error'];
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
				}
				else
				{
					$aTmp = explode('.',$aFileInfo['sFilename']);
					$aFileInfo['sFilename'] = str_replace(end($aTmp),'png',$aFileInfo['sFilename']);
					$sFileName = $aFileInfo['sFilename'];

					$aSQL_Array = array(
						'nKid'		=> (int)	$aId[$LPsLang],
						'sTable'		=> (string)	CLIENT_ANNOUNCE,
						'sFile'		=> (string)	$sFileName,
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'  	=> (string)	NOWDATE,
					);

					$sSQL = 'INSERT INTO ' . CLIENT_IMAGE_CTRL . ' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$nImageLastId = $oPdo->lastInsertId();

					#紀錄動作 - 新增
					$aEditLog[CLIENT_IMAGE_CTRL]['aNew'] = $aSQL_Array;
					$aEditLog[CLIENT_IMAGE_CTRL]['aNew']['nId'] = $nImageLastId;
					$aActionLog = array(
						'nWho'		=> (int)	$aAdm['nId'],
						'nWhom'		=> (int)	0,
						'sWhomAccount'	=> (string)	'',
						'nKid'		=> (int)	$nImageLastId,
						'sIp'			=> (string)	USERIP,
						'nLogCode'		=> (int)	8102104,	// 新增圖片
						'sParam'		=> (string)	json_encode($aEditLog),
						'nType0'		=> (int)	0,
						'nCreateTime'	=> (int)	NOWTIME,
						'sCreateTime'	=> (string)	NOWDATE,
					);
					DoActionLog($aActionLog);
				}
			}

			#紀錄動作 - 新增
			$aEditLog[CLIENT_ANNOUNCE]['aOld'] = $aData[$aId[$LPsLang]];
			$aEditLog[CLIENT_ANNOUNCE]['aNew'] = $aSQL_Array;
			$aEditLog[CLIENT_ANNOUNCE]['aNew']['nId'] = $aId[$LPsLang];
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$aId[$LPsLang],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102102,
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
		$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']);
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}

	if ($aJWT['a'] == 'DEL'.$nLid)
	{
		$sSQL = '	SELECT 	nId,
						nLid,
						nOnline,
						nUpdateTime,
						sUpdateTime
				FROM 		'. CLIENT_ANNOUNCE .'
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$aEditLog[CLIENT_ANNOUNCE]['aOld'] = $aData;
			$aSQL_Array = array(
				'nOnline'		=> (int)	99,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);

			$sSQL = '	UPDATE '. CLIENT_ANNOUNCE . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nLid = :nLid ';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nLid', $nLid, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			$aEditLog[CLIENT_ANNOUNCE]['aNew'] = $aSQL_Array;
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nLid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102103,
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
			$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0.php']);
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}

	if ($aJWT['a'] == 'DELIMG'.$nId)
		{
			$sSQL = '	SELECT	nKid,
							sTable,
							sFile,
							nCreateTime,
							nType0
					FROM		'. CLIENT_IMAGE_CTRL .'
					WHERE		sTable LIKE \''. CLIENT_ANNOUNCE .'\'
					AND		nKid = :nKid
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nKid', $nId, PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			if($aRows === false)
			{
				$aJumpMsg['0']['sTitle'] = ERRORMSG;
				$aJumpMsg['0']['sIcon'] = 'error';
				$aJumpMsg['0']['sMsg'] = NODATA;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
			else
			{
				$sSQL = '	DELETE FROM	'. CLIENT_IMAGE_CTRL . '
						WHERE 	nKid = :nKid
						AND		sTable LIKE \'' . CLIENT_ANNOUNCE . '\'
						LIMIT		1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nKid', $nId, PDO::PARAM_INT);
				sql_query($Result);

				$aEditLog[CLIENT_IMAGE_CTRL]['aOld'] = $aRows;
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$nId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102105,	// 刪除圖片
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);

				$aData = array(
					'sImgUrl'	=> date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'],
					'delImg'	=> 1,
					'sUrl'	=> $aFile['sUrl']
				);
				// print_r($aData);exit;
				delImage($aData);

				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = DELV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = sys_web_encode($aMenuToNo['pages/client_news/php/_client_announce_0_upt0.php']).'&nLid='.$nLid;
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	#程式邏輯結束
?>