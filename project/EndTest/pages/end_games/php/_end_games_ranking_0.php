<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_games_ranking.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_games/end_games_ranking.js',
	);
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '0');
	$sStartTime = filter_input_str('sStartTime',	INPUT_POST,'');
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_POST,'');
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_POST, 'TODAY');
	$sAccount 	= filter_input_str('sAccount',		INPUT_POST, '');	# 查詢的會員帳號
	$sSite 	= filter_input_str('sSite',		INPUT_POST, '');	# 查詢的會員帳號
	$nSearchType = filter_input_int('nSearchType', 	INPUT_REQUEST, '4');# 0用投注量 1用投注筆數 2用贏錢 3用輸錢 4用輸贏
	$nReloadTime	= filter_input_int('nReloadTime', 	INPUT_REQUEST, '5');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ranking_0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ranking_0_act0.php']).'&run_page=1',
		'sAjax0'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_games_ranking_0_ajax0.php']).'&run_page=1',
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_games_ranking_0.php',
	);
	#url結束

	#參數宣告區
	$aValue = array(
		'a'	=> 'RANKING',
		't'	=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aDay = aDAY;
	$nTopCount = 50;
	$aData = array();
	$aRawData = array();
	$aAllMember = array();
	$aBindArray = array();
	$nCount = 0;
	$sCondition = '';
	$aReloadSetting = array(
		'1' => array(
			'sName0' => '1秒',
			'sSelect' => '',
		),
		'3' => array(
			'sName0' => '3秒',
			'sSelect' => '',
		),
		'5' => array(
			'sName0' => '5秒',
			'sSelect' => '',
		),
		'10' => array(
			'sName0' => '10秒',
			'sSelect' => '',
		),
		'15' => array(
			'sName0' => '15秒',
			'sSelect' => '',
		),
	);

	if($sStartTime !== '')
	{
		$sCondition .= ' AND nCreateTime > :nStartTime ';
		$aBindArray['nStartTime']	= $nStartTime;
	}

	if($sEndTime !== '')
	{
		$sCondition .= ' AND nCreateTime < :nEndTime ';
		$aBindArray['nEndTime']		= $nEndTime;
	}

	if($nGame > 0)
	{
		$sCondition .= ' AND nGame = :nGame ';
		$aBindArray['nGame'] = $nGame;
	}
	if($sSite != '')
	{
		$sCondition .= ' AND sSiteId = :sSite ';
		$aBindArray['sSite'] = $sSite;
	}



	$aPageData = array(
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sSelDay'		=> $sSelDay,
		'nGame'		=> $nGame,
		'sSite'		=> $sSite,
	);
	
	$aGame_Setting = array();
	#宣告結束

	#程式邏輯區
	$aReloadSetting[$nReloadTime]['sSelect'] = 'SELECTED';
	$sSQL = '	SELECT	nGame,
					sName0
			FROM		'. END_GAMES_SETTING .'
			WHERE		sLang = :sLang
			AND		nOnline = 1
			ORDER	BY sName0 ASC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aGameSetting[$aRows['nGame']]['sText'] = $aRows['sName0'];

		if($nGame == $aRows['nGame'])
		{
			$aGameSetting[$aRows['nGame']]['sSelect'] = 'selected';
		}
		else
		{
			$aGameSetting[$aRows['nGame']]['sSelect'] = '';
		}
	}

	$sSQL = '	SELECT 	nId,
					sName0,
					sAccount,
					sSiteId,
					nBlack
			FROM		'. CLIENT_USER_DATA .'
			WHERE 	nStatus = 0
			AND		nOnline = 1';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAllMember[$aRows['nId']] = $aRows;
		$aAllMember[$aRows['nId']]['sColor'] = '';

		$nAccountType = substr($aRows['sAccount'],-1);
		if($nAccountType == '0')
		{
			$aAllMember[$aRows['nId']]['sColor'] = 'FontGreen';
		}
		if($aRows['nBlack'] == 1)
		{
			$aAllMember[$aRows['nId']]['sColor'] = 'FontRed';
		}
	}

	# 注單 #
	$sSQL = '	SELECT 	nUid,
					sSiteId,
					nGame,
					nMoney0,
					nMoney1
			FROM		'. CLIENT_GAMES_DATA .'
			WHERE 	nDone = 1
			AND		nStatus IN (0,1,2)'.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aAllMember[$aRows['nUid']]))
		{
			continue;
		}
		if($nSearchType == 0)
		{
			$nSearchAmount = $aRows['nMoney0'];
		}
		else if($nSearchType == 1)
		{
			$nSearchAmount = 1;		
		}
		else if($nSearchType == 2 && $aRows['nMoney1'] > 0)
		{
			$nSearchAmount = $aRows['nMoney1'];
		}
		else if($nSearchType == 3 && $aRows['nMoney1'] < 0)
		{
			$nSearchAmount = $aRows['nMoney1'];
		}
		else if($nSearchType == 4)
		{
			$nSearchAmount = $aRows['nMoney1'];
		}

		if(!isset($aRawData[$aRows['nUid']]))
		{
			$aRawData[$aRows['nUid']] = 0;
			$aData[$aRows['nUid']] = array(
				'nMoney' => 0,
				'nCount' => 0,
				'nWin' => 0,
				'nLose' => 0,
				'nProfit' => 0,
			);
		}

		$aRawData[$aRows['nUid']] += $nSearchAmount;
		$aData[$aRows['nUid']]['nMoney'] += $aRows['nMoney0'];
		$aData[$aRows['nUid']]['nCount'] ++;
		if( $aRows['nMoney1'] > 0)
		{
			$aData[$aRows['nUid']]['nWin'] += $aRows['nMoney1'];
		}
		if( $aRows['nMoney1'] < 0)
		{
			$aData[$aRows['nUid']]['nLose'] += $aRows['nMoney1'];
		}
		$aData[$aRows['nUid']]['nProfit'] += $aRows['nMoney1'];
	}

	arsort($aRawData);

	foreach($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if($sSelDay == $LPsText)
		{
			if(($LPaDate['sStartDay']==$sStartTime)&&($LPaDate['sEndDay']==$sEndTime))
			{
				$aDay[$LPsText]['sSelect'] = 'active';
			}
		}
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>