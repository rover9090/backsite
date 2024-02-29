<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/lang/'.$aSystem['sLang'].'/client_mall.php');
	#require結束

	#參數接收區
	$nId		= filter_input_int('nId',		INPUT_REQUEST,0);
	$nKid		= filter_input_int('nKid',		INPUT_REQUEST,0);
	$nOnline	= filter_input_int('nOnline',		INPUT_POST,1);
	$nIndex	= filter_input_int('nIndex',		INPUT_REQUEST,0);
	$nSort	= filter_input_int('nSort',		INPUT_REQUEST,0);
	$sName0 	= filter_input_str('sName0',	INPUT_POST,'');
	$nPrice0 	= filter_input_str('nPrice0',	INPUT_POST,'');
	$nImgType0 	= filter_input_int('nImgType0',	INPUT_POST,0);
	$aFileRaw = !empty($_FILES['aFile']['name'][0])?$_FILES['aFile']:array('name'=>array());
	$sLang	= $aSystem['sLang'];

	#參數結束

	#參數宣告區
	# $aFile in #define.php
	$nErr = 0;
	$nFileCount = count($aFileRaw['name']);
	$aData = array();
	$aImgData = array();
	$aCoverPic = array();
	$aEditLog = array(
		CLIENT_IMAGE_CTRL	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
		CLIENT_MALL	=> array(
			'aOld' => array(),
			'aNew' => array(),
		),
	);
	$aUrl = array(
		'sBack'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_upt0.php']).'&nId='.$nId,
		'sUpt'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_upt0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_act0.php']).'&run_page=1',
		'sList'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0.php']),
	);
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'INS')
	{
		if(!isset(aLANG[$sLang]))
		{
			$nErr = 1;
			$sMsg = aPICTURE['LANGERROR'];
		}

		if($nFileCount === 0)
		{
			$nErr = 1;
			$sMsg = aPICTURE['NOIMG'];
		}

		if($nErr === 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->beginTransaction();
	
			do
			{
				$aSQL_Array = array(
					'nKid'		=> (int)	1,
					'nOnline'		=> (int)	$nOnline,
					'sLang'		=> (string)	$sLang,
					'sName0'		=> (string)	$sName0,
					'nPrice0'		=> (string)	$nPrice0,
					'sContent0'		=> '',
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
					'nUpdateTime'	=> (int)	NOWTIME,
					'sUpdateTime'	=> (string)	NOWDATE,
				);
		
				$sSQL = 'INSERT INTO '. CLIENT_MALL . ' ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
				$nLastId = $oPdo->lastInsertId();
				$aEditLog[CLIENT_MALL]['aNew'][$nLastId] = $aSQL_Array;
					
				for($i=0;$i<$nFileCount;$i++)
				{
					$tmp = explode("/", $aFileRaw["type"][$i]);
					$sExtension = strtolower(end($tmp));
					$aFile['sTable'] = CLIENT_MALL;
					$aFile['aFile'] = array(
						'name' 	=> $aFileRaw['name'][$i],
						'type' 	=> $aFileRaw['type'][$i],
						'size' 	=> $aFileRaw['size'][$i],
						'tmp_name' 	=> $aFileRaw['tmp_name'][$i],
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
						break 2;
					}
					// else
					// {
					// 	$aTmp = explode('.',$aFileInfo['sFilename']);
					// 	$aFileInfo['sFilename'] = str_replace(end($aTmp),'png',$aFileInfo['sFilename']);
					// }

					if($sExtension === 'mp4')
					{
						$nImgType0 = 0;
					}
					else if($sExtension === 'gif')
					{
						$nImgType0 = 1;
					}
					else
					{
						$nImgType0 = 9;
					}


					$aSQL_Array = array(
						'nKid'		=> (int)	$nLastId,
						'sTable'		=> (string)	CLIENT_MALL,
						'sFile'		=> (string)	$aFileInfo['sFilename'],
						'nType0'		=> (int)	$i,
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
				}
		
				#紀錄動作 - 新增
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$nLastId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102401,#01新增02修改03刪除04上傳圖片05刪除圖片
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);				
		
				$oPdo->commit();
				
				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = INSV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sList'];
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}while(false);

		}
	}

	if ($aJWT['a'] == 'UPT')
	{
		$nError = 0;
		$nId = $aJWT['nId'];
		// $sSQL = '	SELECT	nId,
		// 				nKid,
		// 				sTable,
		// 				sFile,
		// 				nCreateTime,
		// 				nType0
		// 		FROM		'. CLIENT_IMAGE_CTRL .'
		// 		WHERE		sTable LIKE \''. CLIENT_MALL .'\'
		// 		AND		nKid = :nImgKid
		// 		LIMIT		1';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':nImgKid', $nId, PDO::PARAM_INT);
		// $Result->bindValue(':nType0', $nImgType0, PDO::PARAM_INT);
		// sql_query($Result);
		// $aImgData = $Result->fetch(PDO::FETCH_ASSOC);

		$sSQL = '	SELECT 	nId,
						sName0,
						nPrice0,
						nKid,
						nOnline,
						sCreateTime,
						sUpdateTime,
						sLang
				FROM 		'. CLIENT_MALL .'
				WHERE 	nId = :nId';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nId',$nId,PDO::PARAM_INT);
		sql_query($Result);
		$aData = $Result->fetch(PDO::FETCH_ASSOC);
		if($aData === false)
		{
			$nErr = 1;
			$sMsg = NODATA.$nId;
		}
		$aEditLog[CLIENT_MALL]['aOld'][$aData['nId']] = $aData;

		if(!isset(aLANG[$sLang]))
		{
			$nErr = 1;
			$sMsg = aPICTURE['LANGERROR'];
		}

		// if($aImgData !== false && $nFileCount > 0)
		// {
		// 	$nErr = 1;
		// 	$sMsg = aPICTURE['REMOVEFIRST'];
		// }

		if($nErr === 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			// print_r($_POST);
			// print_r($aJWT);
			// exit;
			$oPdo->beginTransaction();
			$aSQL_Array = array(
				'sName0'		=> (string)	$sName0,
				'nPrice0'		=> (string)	$nPrice0,
				// 'sLang'		=> (string)	$sLang,
				'nOnline'		=> (int)	$nOnline,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
	
			$sSQL = '	UPDATE '. CLIENT_MALL . ' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
					WHERE	nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$aEditLog[CLIENT_MALL]['aNew'][$aData['nId']] = $aSQL_Array;

			#紀錄動作 - 更新
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$nId,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102402,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);

			for($i=0;$i<$nFileCount;$i++)
			{
				$tmp = explode("/", $aFileRaw["type"][$i]);
				$sExtension = strtolower(end($tmp));
				$aFile['sTable'] = CLIENT_MALL;
				$aFile['aFile'] = array(
					'name' 	=> $aFileRaw['name'][$i],
					'type' 	=> $aFileRaw['type'][$i],
					'size' 	=> $aFileRaw['size'][$i],
					'tmp_name' 	=> $aFileRaw['tmp_name'][$i],
				);
				$aFileInfo = goImage($aFile);
				if($aFileInfo['error'] !== '')
				{
					$nError = 1;
					$aJumpMsg['0']['sTitle'] = ERRORMSG;
					$aJumpMsg['0']['sIcon'] = 'error';
					$aJumpMsg['0']['sMsg'] = aIMGERROR[$aFileInfo['error']];
					$aJumpMsg['0']['sShow'] = 1;
					$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sList'];
					$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
					break;
				}
				// else
				// {
				// 	$aTmp = explode('.',$aFileInfo['sFilename']);
				// 	$aFileInfo['sFilename'] = str_replace(end($aTmp),'png',$aFileInfo['sFilename']);
				// }
				if($sExtension === 'mp4')
				{
					$nImgType0 = 0;
				}
				else if($sExtension === 'gif')
				{
					$nImgType0 = 1;
				}
				else
				{
					$nImgType0 = 9;
				}

				$aSQL_Array = array(
					'nKid'		=> (int)	$aData['nId'],
					'sTable'		=> (string)	CLIENT_MALL,
					'sFile'		=> (string)	$aFileInfo['sFilename'],
					'nType0'		=> (int)	$nImgType0,
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
				#紀錄動作 - 新增
				$aActionLog = array(
					'nWho'		=> (int)	$aAdm['nId'],
					'nWhom'		=> (int)	0,
					'sWhomAccount'	=> (string)	'',
					'nKid'		=> (int)	$nImageLastId,
					'sIp'			=> (string)	USERIP,
					'nLogCode'		=> (int)	8102404,#01新增02修改03刪除04上傳圖片05刪除圖片
					'sParam'		=> (string)	json_encode($aEditLog),
					'nType0'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
				DoActionLog($aActionLog);
			}

			$oPdo->commit();
			
			if($nError === 0)
			{
				$aJumpMsg['0']['sTitle'] = RIGHTMSG;
				$aJumpMsg['0']['sIcon'] = 'success';
				$aJumpMsg['0']['sMsg'] = UPTV;
				$aJumpMsg['0']['sShow'] = 1;
				$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sList'];
				$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
			}
		}
	}

	if ($aJWT['a'] == 'DELIMG')
	{
		$nId = $aJWT['nId'];
		$aImgData = array();
		$sSQL = '	SELECT	nId,
						nKid,
						sTable,
						sFile,
						nCreateTime,
						nType0
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		sTable LIKE \''. CLIENT_MALL .'\'
				AND		nKid = :nImgKid
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nImgKid', $nId, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aImgData[$aRows['nId']] = $aRows;
			$aEditLog[CLIENT_IMAGE_CTRL]['aOld'][$aRows['nId']] = $aRows;
		}
		if($aImgData === array())
		{
			$nErr = 1;
			$sMsg = aPICTURE['IMAGE_NOT_FOUND'];
		}

		if($nErr === 1)
		{
			$aJumpMsg['0']['sTitle'] = ERRORMSG;
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = $sMsg;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
		else
		{
			$oPdo->beginTransaction();

			$sSQL = '	DELETE FROM	'. CLIENT_IMAGE_CTRL . '
					WHERE 	nKid = :nImgId
					LIMIT		1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nImgId', $nId, PDO::PARAM_INT);
			sql_query($Result);

			foreach($aImgData as $LPaImgData)
			{
				$aData = array(
					'sImgUrl'	=> date('Y/m/d/',$LPaImgData['nCreateTime']).$LPaImgData['sTable'].'/'.$LPaImgData['sFile'],
					'delImg'	=> 1,
					'sUrl'	=> $aFile['sUrl']
				);
				delImage($aData);
			}

			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	0,
				'sWhomAccount'	=> (string)	'',
				'nKid'		=> (int)	$LPaImgData['nId'],
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8102405,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
			$oPdo->commit();

			$aJumpMsg['0']['sTitle'] = RIGHTMSG;
			$aJumpMsg['0']['sIcon'] = 'success';
			$aJumpMsg['0']['sMsg'] = DELV;
			$aJumpMsg['0']['sShow'] = 1;
			$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sList'];
			$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
		}
	}
	#程式邏輯結束
?>