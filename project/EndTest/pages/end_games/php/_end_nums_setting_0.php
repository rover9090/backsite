<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_nums_setting.php');
	// require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/sys_game_name.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
	);
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST,	1001);
	$sNo		= filter_input_str('sNo', 	INPUT_REQUEST,	'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_nums_setting_0.php']),
		'sRepost'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_nums_setting_0.php',
	);

	#url結束

	#參數宣告區

	$aData = array();
	$aBind = array();
	$aGame_Setting = array();
	$aLastGame = array(
		'sNo' => '',
		'sOpenTime' => '',
		'sCloseTime' => '',
	);
	$aNewGame = array(
		'sNo' => '',
		'sOpenTime' => '',
		'sCloseTime' => '',
	);

	$nCount = 0;
	$sCondition = '';
	$aPage['aVar'] = array(
		'nGame'	=> $nGame,
		'sNo'		=> $sNo,
	);
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$sNoIdList = '0';
	$sNoList = '\'0\'';

	$aStatus = aPERIOD['aStaus'];
	unset($aStatus['sText']);
	$aOnline = aPERIOD['aOnline'];
	unset($aOnline['sText']);
	$aBetType = aPERIOD['aBetType'];
	unset($aBetType['sText']);
	$aNoBanker = array();

	#宣告結束

	#程式邏輯區
	if($sNo != '')
	{
		$sCondition .= ' AND sNo LIKE :sNo';
		$aBind['sNo'] = '%'.$sNo.'%';
		$aPage['aVar']['sNo'] = $sNo;
	}

	if($nGame != '')
	{
		$sCondition .= ' AND nGame = :nGame';
		$aBind['nGame'] = $nGame;
		$aPage['aVar']['nGame'] = $nGame;
	}

	# 遊戲種類
	$sSQL = '	SELECT	nId,
					nGame,
					sName0,
					nOnline,
					sOpenTime,
					sCloseTime
			FROM	'. END_GAMES_SETTING .'
			WHERE	nOnline = 1
			ORDER	BY sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if ($nGame < 0)# 預設
		{
			$nGame = $aRows['nId'];
		}
		$aRows['sLang'] = $aRows['sName0'];
		$aGame_Setting[$aRows['nGame']] = $aRows;
		$aGame_Setting[$aRows['nGame']]['sSelect'] = '';
		if($nGame == $aRows['nGame'])
		{
			$aGame_Setting[$aRows['nGame']]['sSelect'] = 'selected';
		}
	}

	if ($nGame < 2000)
	{
		$sSQL = '	SELECT	1
				FROM	'. CLIENT_GAMES_NUMS .'
				WHERE	1
				' . $sCondition . '
				ORDER	BY nId DESC';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBind);
		sql_query($Result);
	}
	else
	{
		$sSQL = '	SELECT	1
				FROM	'. CLIENT_GAMES_NUMS2 .'
				WHERE	nPreStartTime < :NOWTIME
				' . $sCondition . '
				ORDER	BY nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
		sql_build_value($Result,$aBind);
		sql_query($Result);
	}
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nCount++;
	}
	$aPage['nDataAmount'] = $nCount;

	$aRESULTNAME = array(
		'Banker' => aRESULTNAME['BANKER'],
		'Others' => aRESULTNAME['OTHERS'],
		'A' => aRESULTNAME['A'],
		'B' => aRESULTNAME['B'],
		'C' => aRESULTNAME['C'],
	);
		# 取期數 #
	if ($nGame < 2000)
	{
		$sSQL = '	SELECT	nId,
						sNo,
						nGame,
						nStatus,
						sNums,
						sResult,
						sStartTime as sOpenTime,
						sPauseTime1 as sCloseTime
				FROM		'. CLIENT_GAMES_NUMS .'
				WHERE		1
						' . $sCondition . '
				ORDER	BY	nId DESC
				LIMIT '.$nPageStart.','.$aPage['nPageSize'];
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBind);
		sql_query($Result);
	}
	else
	{
		$sSQL = '	SELECT	nId,
						sNo,
						nGame,
						nStatus,
						sNums,
						sResult,
						sStartTime as sOpenTime,
						sPauseTime1 as sCloseTime
				FROM		'. CLIENT_GAMES_NUMS2 .'
				WHERE		nPreStartTime < :NOWTIME
						' . $sCondition . '
				ORDER	BY	nId DESC
				LIMIT '.$nPageStart.','.$aPage['nPageSize'];
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
		sql_build_value($Result,$aBind);
		sql_query($Result);
	}
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sNoList .= ',\''.$aRows['sNo'].'\'';
		$sNoIdList .= ','.$aRows['nId'].'';
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sRepost'] = $aUrl['sRepost'].'&sNo='.$aRows['sNo'];
		$LPaNums = json_decode($aRows['sNums'],true);
		$LPaResult = json_decode($aRows['sResult'],true);
		$aData[$aRows['nId']]['sNumsText'] = '';
		$aData[$aRows['nId']]['sImage'] = '';
		$aData[$aRows['nId']]['sResultText'] = '';
		$aNoBanker[$aRows['sNo']]['sName0'] = '';
		$aNoBanker[$aRows['sNo']]['sMoney0'] = '';
		if(isset($LPaNums['A'])){			
			$aData[$aRows['nId']]['sNumsText'] = aNUMSNAME['Z'][$LPaNums['Z']].','.aNUMSNAME['A'][$LPaNums['A']].','.aNUMSNAME['B'][$LPaNums['B']].','.aNUMSNAME['C'][$LPaNums['C']];
			if(isset($LPaResult['Money0']) && $LPaResult['Money1'])
			{
				$aData[$aRows['nId']]['sResultText'] = '下注額度：';
				foreach($LPaResult['Money0'] as $LPsKey => $LPnResult)
				{
					$aData[$aRows['nId']]['sResultText'] .= $aRESULTNAME[$LPsKey].$LPnResult.',';
				}
				$aData[$aRows['nId']]['sResultText'] .= '純贏：';
				foreach($LPaResult['Money1'] as $LPsKey => $LPaResult)
				{
					foreach($LPaResult as $LPsKey2 => $LPnResult2)
					{
						$aData[$aRows['nId']]['sResultText'] .= $aRESULTNAME[$LPsKey].$LPsKey2.$LPnResult2.',';
					}
				}
			}
			// {"Money0":{"Banker":20000,"Others":0,"A":1000,"B":800,"C":600},"Money1":{"Banker":{"A":1000,"B":-800,"C":600},"Others":{"A":0,"B":0,"C":0}}}
		}
		else
		{
			$aData[$aRows['nId']]['sResult'] = '';
			$aData[$aRows['nId']]['sNumsText'] = '';
			$aData[$aRows['nId']]['sResultText'] = '';
		}
	}
	if(!empty($aData))
	{
		$aLastGame['sOpenTime'] = reset($aData)['sOpenTime'];
		$aLastGame['sCloseTime'] = reset($aData)['sCloseTime'];
		$aLastGame['sNo'] = reset($aData)['sNo'];
		$aNewGame['sOpenTime'] = date('Y-m-d H:i:s',strtotime($aLastGame['sOpenTime'])+86400);
		$aNewGame['sCloseTime'] = date('Y-m-d H:i:s',strtotime($aLastGame['sCloseTime'])+86400);
		$aNewGame['sNo'] = $aLastGame['sNo']+1;
	}
	else
	{
		$aNewGame['sOpenTime'] = date('Y-m-d',NOWTIME).' '.$aGame_Setting[$nGame]['sOpenTime'].':00';
		$aNewGame['sCloseTime'] = date('Y-m-d',NOWTIME).' '.$aGame_Setting[$nGame]['sCloseTime'].':00';
		$aNewGame['sNo'] = date('Ymd',NOWTIME);
	}

	$sSQL = '	SELECT	nId,
					nKid,
					sTable,
					sFile,
					nCreateTime,
					nType0
			FROM		'. CLIENT_IMAGE_CTRL .'
			WHERE		sTable LIKE \''. CLIENT_GAMES_NUMS .'\'
			AND		nKid IN ('.$sNoIdList.')
			AND		nType0 = 0';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['nKid']]['sImageUrl'] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'];			
		$aData[$aRows['nKid']]['sImage'] = '<img src="'.$aData[$aRows['nKid']]['sImageUrl'].'" alt="" title=":title:" >';
	}

	$sSQL = '	SELECT	nId,
					nUid,
					sNo,
					nMoney0,
					(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER.'.nUid) as sName0
			FROM		'. CLIENT_GAMES_BANKER .'
			WHERE		sNo IN ('.$sNoList.')
			AND		nType0 = 0
			AND		nStatus = 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aNoBanker[$aRows['sNo']] = $aRows;
		$aNoBanker[$aRows['sNo']]['sMoney0'] = '('.floor($aRows['nMoney0']).')';
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);

	$aJumpMsg['0']['nClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	$aJumpMsg['0']['sArticle'] = '<img src="" class="JqImgSrc" style="width: 100%;">';
	// $aJumpMsg['0']['sArticle'] = '';
	// $aJumpMsg['0']['sMsg'] = '<span></span>';
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>