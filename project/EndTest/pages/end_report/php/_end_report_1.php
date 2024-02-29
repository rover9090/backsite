<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_report.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=> 'plugins/js/js_date/laydate.js',
		'1'	=> 'plugins/js/end_report/end_report.js',
	);

	#參數接收區
	$sStartTime = filter_input_str('sStartTime',	INPUT_REQUEST, date('Y-m-d 00:00:00'));
	$sEndTime 	= filter_input_str('sEndTime',	INPUT_REQUEST, date('Y-m-d 23:59:59'));
	$nStartTime = strtotime($sStartTime);
	$nEndTime 	= strtotime($sEndTime);
	$sSelDay 	= filter_input_str('sSelDay',		INPUT_REQUEST, 'TODAY');
	$sAccount 	= filter_input_str('sAccount',		INPUT_REQUEST, '');	# 查詢的會員帳號
	$nSearchLid = filter_input_str('nSearchLid',	INPUT_REQUEST, 0);	# 查詢的會員Lid
	$sBack	= filter_input_str('sBack',		INPUT_REQUEST, '');	# 查詢的會員帳號
	$sBackLid	= filter_input_str('sBackLid',	INPUT_REQUEST, '');	# 查詢的會員帳號
	$sGameClass = filter_input_str('sGameClass',	INPUT_REQUEST, '');
	$sGame 	= filter_input_str('sGame',		INPUT_REQUEST, '');
	#參數結束

	#給此頁使用的url
	$aUrl   = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_report/php/_end_report_1.php']),
		'sHtml'	=> 'pages/end_report/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_report_1.php',
	);
	#url結束

	#參數宣告區
	$aDay = aDAY;
	$aIdentity = aREPORT['aIDENTITY'];

	$aData = array();
	$aBindArray = array();
	$aTmpUid = array();
	$aLinkData = array();
	$aFsData = array();
	$aLink = array();

	$sHideUid = '0';
	$sCondition = '';
	$sConditionB = '';
	$sSearchLidList = '';

	$nLid = 0;
	$nSumBet = 0;
	$aTotal = array(
		'nDeposit'		=> 0,
		'nWithdrawl'	=> 0,
		'nBetPeople'	=> 0,
		'nBetCount'		=> 0,
		'nBetMoney'		=> 0,
		'nBetWin'		=> 0,# 總輸贏絕對值
		'nBetWin1'		=> 0,# 總輸贏和
		'nAgentFs'		=> 0,
		'nBetFs'		=> 0,
		'nBetProfit'	=> 0,
		'nFee'		=> 0,
		'nWaterFee'		=> 0,
		'nDonate'		=> 0,
		'nMall'		=> 0,
		'nPerXProfit'	=> 0,
		'nPerXFs'		=> 0,
		'nPerResult'	=> 0,
		'nPer'		=> 0,
		'nBankerPlusFee'	=> 0,
	);

	$aSgName = array(
		'1'	=> '會員',
		'2'	=> '代理線',
		'3'	=> '代理自己',
	);

	$sCondition .= ' AND nCreateTime > :nStartTime ';
	$sConditionB .= ' AND nUpdateTime > :nStartTime ';
	$aBindArray['nStartTime']	= $nStartTime;
	$sCondition .= ' AND nCreateTime < :nEndTime ';
	$sConditionB .= ' AND nUpdateTime < :nEndTime ';
	$aBindArray['nEndTime']		= $nEndTime;

	$aPageData = array(
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sSelDay'		=> $sSelDay,
	);
	#宣告結束

	#程式邏輯區
	foreach($aDay as $LPsText => $LPaDate)
	{
		$aDay[$LPsText]['sSelect'] = '';
		if($sSelDay == $LPsText)
		{
			if(($LPaDate['sStartDay'] == $sStartTime) && ($LPaDate['sEndDay'] == $sEndTime))
			{
				$aDay[$LPsText]['sSelect'] = 'active';
			}
		}
	}

	$k=0;
	if ($aAdm['nUid'] != 0)
	{
		$sSQL = '	SELECT	nId,
						sLidLinkList
				FROM		'. CLIENT_USER_LINK .'
				WHERE		nUid = :nUid
				AND		nEndTime = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid',$aAdm['nUid'],PDO::PARAM_INT);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$sSearchLidList = $aRows['sLidLinkList'];
		$nLid = $aRows['nId'];
	}
	else
	{
		// $sSQL = '	SELECT	nId,
		// 				sLidLinkList
		// 		FROM		'. CLIENT_USER_LINK .'
		// 		WHERE		nUid = 1
		// 		AND		nEndTime = 0';
		// $Result = $oPdo->prepare($sSQL);
		// sql_query($Result);
		// $aRows = $Result->fetch(PDO::FETCH_ASSOC);
		// $sSearchLidList = $aRows['sLidLinkList'];
		$sSearchLidList = '';
		$nLid = 0;
	}

	if($sAccount != '')
	{
		$sSQL = '	SELECT	nId,
						sLidLinkList
				FROM		'. CLIENT_USER_LINK .'
				WHERE		nUid = (SELECT nId FROM '. CLIENT_USER_DATA .' WHERE sAccount LIKE :sAccount AND nStatus != 99 LIMIT 1)
				AND		sLidLinkList LIKE :sLidLinkList';

		if ($nSearchLid != 0)
		{
			$sSQL .= ' AND nId = '.$nSearchLid;
		}
		else
		{
			$sSQL .= ' AND nEndTime = 0';
		}

		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sAccount', trim($sAccount,'@') , PDO::PARAM_STR);
		$Result->bindValue(':sLidLinkList', $sSearchLidList.'%' , PDO::PARAM_STR);
		sql_query($Result);
		$aRows = $Result->fetch(PDO::FETCH_ASSOC);
		$nLid = $aRows['nId'];
		$sSearchLidList = $aRows['sLidLinkList'];
	}

	# 要隱藏的會員 #
	// $sSQL = '	SELECT	nUid
	// 		FROM		'. CLIENT_USER_HIDE .'
	// 		WHERE		nOnline = 1';
	// $Result = $oPdo->prepare($sSQL);
	// sql_query($Result);
	// while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	// {
	// 	$sHideUid .= ',' . $aRows['nUid'];
	// }

	# 會員 #
	$sSQL = '	SELECT	Link_.nId,
					Link_.nUid,
					Link_.nPaLid,
					Link_.nLevel,
					Link_.sLidLinkList,
					Link_.nEndTime,
					User_.sAccount,
					User_.nIdentity
			FROM		'. CLIENT_USER_LINK .' Link_,
					'. CLIENT_USER_DATA .' User_
			WHERE		User_.nOnline != 99
			AND		Link_.nUid = User_.nId
			ORDER BY	nLevel ASC';
	$Result = $oPdo->prepare($sSQL);
	# Link_.sLidLinkList LIKE :sLidLinkList
	// $Result->bindValue(':sLidLinkList', $sSearchLidList.'%' , PDO::PARAM_STR);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if ($aRows['nPaLid'] == $nLid || $aRows['nId'] == $nLid)
		{
			$aData[$aRows['nId']] = array(
				'nUid'		=> $aRows['nUid'],
				'nIdentity'		=> $aRows['nIdentity'],
				'sAccount'		=> $aRows['sAccount'],
				'nLevel'		=> $aRows['nLevel'],
				'nFs'			=> 0,
				'nPer'		=> 0,
				'nDeposit'		=> 0,
				'nWithdrawl'	=> 0,
				'aBetPeople'	=> array(),
				'nBetCount'		=> 0,
				'nBetMoney'		=> 0,
				'nBetWin'		=> 0,
				'nBetWin1'		=> 0,
				'nAgentFs'		=> 0,
				'nBetFs'		=> 0,
				'nBetProfit'	=> 0,
				'nFee'		=> 0,
				'nWaterFee'		=> 0,
				'nDonate'		=> 0,
				'nMall'		=> 0,
				'nPerXProfit'	=> 0,
				'nPerXFs'		=> 0,
				'nPerResult'	=> 0,
				'nBankerPlusFee'	=> 0,
			);
			if ($aRows['nEndTime'] !=0)
			{
				$aData[$aRows['nId']]['sAccount'] = $aRows['sAccount'].'@';
			}
		}
		$aTmpUid[$aRows['nUid']] = $aRows['nUid'];
		$aLinkData[$aRows['nId']] = $aRows;
	}

	// if (!empty($aLinkData))
	// {
	// 	$sCondition .= ' AND nLid IN ( '.implode(',',array_keys($aLinkData)).' ) ';
	// }

	if (isset($aData[$nLid]))
	{
		$aData[$nLid.'Self'] = $aData[$nLid];
		$aData[$nLid.'Self']['nIdentity'] = 2;
	}

	if(empty($aTmpUid))
	{
		$sTmpUid = '0';
	}
	else
	{
		$sTmpUid = implode(',',$aTmpUid);
	}

	# 反水 佔成.
	$sSQL = '	SELECT 	nId,
					nUid,
					sWater,
					sPercent,
					sWaterMinus,
					sWaterLink,
					sPercentLink,
					sPercentMinus,
					nEndTime
			FROM		'. CLIENT_USER_FS .'
			WHERE 	nUid IN ( '. $sTmpUid .' )';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aFsData['aFsId'][$aRows['nId']] = $aRows;
		if ($aRows['nEndTime'] == 0)
		{
			$aFsData['aUid'][$aRows['nUid']] = $aRows;
		}
	}

	if(empty($aLinkData))
	{
		$sTmpLinkData = '0';
	}
	else
	{
		$sTmpLinkData = implode(',',array_keys($aLinkData));
	}

	# 充提 (不含 會員轉帳 / 人工轉帳)
	$sSQL = '	SELECT 	nLid,
					nMoney,
					nType0,
					nType3
			FROM 		'. CLIENT_MONEY .'
			WHERE 	nCreateTime >= :nStartTime
			AND		nCreateTime <= :nEndTime
			AND 		nStatus = 1
			AND 		nType0 IN (1,2,3)
			AND 		nType2 = 1
			AND 		nLid IN ( '. $sTmpLinkData .' )';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nStartTime',	$nStartTime,PDO::PARAM_INT);
	$Result->bindValue(':nEndTime',	$nEndTime,	PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sActionType = 'nDeposit';
		if ($aRows['nType3'] == 2)
		{
			$sActionType = 'nWithdrawl';
		}
		$aLink = explode(',',$aLinkData[$aRows['nLid']]['sLidLinkList']);
		foreach ($aLink as $LPnSerial => $LPsLink)
		{
			$nTempLid = (int) $LPsLink;
			if (isset($aData[$nTempLid]))
			{
				$aData[$nTempLid][$sActionType] += $aRows['nMoney'];
			}
		}
		if (isset($aData[$aRows['nLid'].'Self']))
		{
			$aData[$aRows['nLid'].'Self'][$sActionType] += $aRows['nMoney'];
		}
	}

	# 注單 #
	$sSQL = '	SELECT 	nMoney0,
					nMoney1,
					nLid,
					nFsId
			FROM		'. CLIENT_GAMES_DATA .'
			WHERE 	nDone = 1
			AND		nStatus IN (0,1)'.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if($aRows['nMoney1'] > 0) $aRows['nMoney1'] *= 100 / 95;
		$nSumBet += abs($aRows['nMoney1']);
		$aLink = explode(',',$aLinkData[$aRows['nLid']]['sLidLinkList']);
		foreach ($aLink as $LPnSerial => $LPsLink)
		{
			$nTempLid = (int) $LPsLink;
			if (isset($aData[$nTempLid]))
			{
				$aValues = array(
					'nIdentity'	=> $aData[$nTempLid]['nIdentity'],
					'nLid'	=> $nTempLid,
					'aData'	=> $aRows,	# 注單資料
					'sList'	=> $aLinkData[$aRows['nLid']]['sLidLinkList'], # 注單當時候的代理線
					'aWater'	=> $aFsData['aFsId'][$aRows['nFsId']],
				);
				get_ComputeAccount($aValues,$aData,$nTempLid);
			}

			if (isset($aData[$nTempLid.'Self']))
			{
				$aValues = array(
					'nIdentity'	=> $aData[$nTempLid.'Self']['nIdentity'],
					'nLid'	=> $nTempLid,
					'aData'	=> $aRows,	# 注單資料
					'sList'	=> $aLinkData[$aRows['nLid']]['sLidLinkList'], # 注單當時候的代理線
					'aWater'	=> $aFsData['aFsId'][$aRows['nFsId']],
				);
				get_ComputeAccount($aValues,$aData,$nTempLid.'Self');
			}
		}
	}


	# 莊家
	$sSQL = '	SELECT 	nMoney0,
					nMoney1,
					nLid,
					nFsId,
					sMoney1
			FROM		'. CLIENT_GAMES_BANKER .'
			WHERE 	nDone = 1
			AND		nStatus = 1'.$sConditionB;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['aMoney1'] = json_decode($aRows['sMoney1']);
		$aLink = explode(',',$aLinkData[$aRows['nLid']]['sLidLinkList']);
		foreach($aRows['aMoney1'] as $LPnMoney1)
		{
			$aRows['nMoney1'] = $LPnMoney1;
			if($aRows['nMoney1'] > 0) $aRows['nMoney1'] *= 100 / 95;
			$nSumBet += abs($aRows['nMoney1']);
			foreach ($aLink as $LPnSerial => $LPsLink)
			{
				$nTempLid = (int) $LPsLink;
				if (isset($aData[$nTempLid]))
				{
					$aValues = array(
						'nIdentity'	=> $aData[$nTempLid]['nIdentity'],
						'nLid'	=> $nTempLid,
						'aData'	=> $aRows,	# 注單資料
						'sList'	=> $aLinkData[$aRows['nLid']]['sLidLinkList'], # 注單當時候的代理線
						'aWater'	=> $aFsData['aFsId'][$aRows['nFsId']],
					);
					get_ComputeAccount($aValues,$aData,$nTempLid);
				}
				if (isset($aData[$nTempLid.'Self']))
				{
					$aValues = array(
						'nIdentity'	=> $aData[$nTempLid.'Self']['nIdentity'],
						'nLid'	=> $nTempLid,
						'aData'	=> $aRows,	# 注單資料
						'sList'	=> $aLinkData[$aRows['nLid']]['sLidLinkList'], # 注單當時候的代理線
						'aWater'	=> $aFsData['aFsId'][$aRows['nFsId']],
					);
					get_ComputeAccount($aValues,$aData,$nTempLid.'Self');
				}
			}
		}
		foreach ($aLink as $LPnSerial => $LPsLink)
		{
			$nTempLid = (int) $LPsLink;
			if (isset($aData[$nTempLid]))
			{
				$aData[$nTempLid]['nFee'] += $aRows['nMoney0'] * $aSystem['aParam']['nBankerFee'] / 100;
			}
			if (isset($aData[$nTempLid.'Self']))
			{
				$aData[$nTempLid.'Self']['nFee'] += $aRows['nMoney0'] * $aSystem['aParam']['nBankerFee'] / 100;
			}
		}
	}

	# 打賞 #
	$aDonateLog = array();
	$sSQL = '	SELECT 	nUid,
					nMoney0
			FROM		'. CLIENT_GAMES_DONATE .'
			WHERE 	1 '.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aDonateLog[$aRows['nUid']]))
		{
			$aDonateLog[$aRows['nUid']] = array(
				'nSumMoney' => 0
			);
		}
		$aDonateLog[$aRows['nUid']]['nSumMoney'] += $aRows['nMoney0'];
	}

	# 商城 #
	$aMallLog = array();
	$sSQL = '	SELECT 	nUid,
					nSumCost
			FROM		'. CLIENT_MALL_LOG .'
			WHERE 	nStatus = 1 '.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aMallLog[$aRows['nUid']]))
		{
			$aMallLog[$aRows['nUid']] = array(
				'nSumMoney' => 0
			);
		}
		$aMallLog[$aRows['nUid']]['nSumMoney'] += $aRows['nSumCost'];
	}	

	# 買官 #
	$aBankerPlusFeeLog = array();
	$sSQL = '	SELECT 	nUid,
					nDelta
			FROM		'. END_LOG_ACCOUNT .'
			WHERE 	nType1 = 325 '.$sCondition;
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result, $aBindArray);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		if(!isset($aBankerPlusFeeLog[$aRows['nUid']]))
		{
			$aBankerPlusFeeLog[$aRows['nUid']] = array(
				'nBankerPlusFee' => 0
			);
		}
		$aBankerPlusFeeLog[$aRows['nUid']]['nBankerPlusFee'] += abs($aRows['nDelta']);
	}

	foreach ($aLinkData as $LPnLid => $LPaData)
	{
		if(isset($aDonateLog[$aLinkData[$LPnLid]['nUid']]))
		{
			$aLink = explode(',',$aLinkData[$LPnLid]['sLidLinkList']);	
			foreach ($aLink as $LPnSerial => $LPsLink)
			{
				$nTempLid = (int) $LPsLink;
				if(isset($aData[$nTempLid]['nDonate']))
				{
					$aData[$nTempLid]['nDonate'] += $aDonateLog[$aLinkData[$LPnLid]['nUid']]['nSumMoney'];
				}
			}
		}
		if(isset($aMallLog[$aLinkData[$LPnLid]['nUid']]))
		{
			$aLink = explode(',',$aLinkData[$LPnLid]['sLidLinkList']);		
			foreach ($aLink as $LPnSerial => $LPsLink)
			{
				$nTempLid = (int) $LPsLink;
				if(isset($aData[$nTempLid]['nMall']))
				{
					$aData[$nTempLid]['nMall'] += $aMallLog[$aLinkData[$LPnLid]['nUid']]['nSumMoney'];
				}
			}
		}
		if(isset($aBankerPlusFeeLog[$aLinkData[$LPnLid]['nUid']]))
		{
			$aLink = explode(',',$aLinkData[$LPnLid]['sLidLinkList']);		
			foreach ($aLink as $LPnSerial => $LPsLink)
			{
				$nTempLid = (int) $LPsLink;
				if(isset($aData[$nTempLid]['nBankerPlusFee']))
				{
					$aData[$nTempLid]['nBankerPlusFee'] += $aBankerPlusFeeLog[$aLinkData[$LPnLid]['nUid']]['nBankerPlusFee'];
				}
			}
		}
	}
	foreach ($aData as $LPnLid => $LPaData)
	{
		$aData[$LPnLid]['nRealWin'] = $LPaData['nBetWin'] * 0.95;
		$aData[$LPnLid]['nFs'] = $aFsData['aUid'][$LPaData['nUid']]['sWater'];
		$aData[$LPnLid]['nPer'] = round($LPaData['nBetMoney']/$nSumBet,3)*100;
		$aData[$LPnLid]['nBetPeople'] = sizeof($LPaData['aBetPeople']);
		$aData[$LPnLid]['nBetProfit'] = $aData[$LPnLid]['nRealWin'] - $LPaData['nBetWin1'] + $LPaData['nBetFs'] + $LPaData['nAgentFs'];
		$aData[$LPnLid]['nWaterFee'] = $LPaData['nBetWin'] * $aSystem['aParam']['nCompanyLeast'] / 100 + $LPaData['nBetWin1'] * $aSystem['aParam']['nCompanyLeast'] / 100;
		$aData[$LPnLid]['nPerResult'] = ($LPaData['nPerXFs'] + $LPaData['nPerXProfit']);
		if ( $LPaData['nIdentity'] == 1 )
		{
			$aData[$LPnLid]['sAccount'] = '<a href="'.$aUrl['sPage'].'&sAccount='.$LPaData['sAccount'].'&sStartTime='.$sStartTime.'&sEndTime='.$sEndTime.'" data-value="'.$LPnLid.'" class="BtnUser">'.$LPaData['sAccount'].'</a>';
		}
		if (sizeof($LPaData['aBetPeople']) == 0 && 
		$LPaData['nWithdrawl'] == 0 && 
		$LPaData['nDeposit'] == 0 && 
		$LPaData['nIdentity'] != 3 && 
		$aData[$LPnLid]['nDonate'] === 0 && 
		$aData[$LPnLid]['nMall'] === 0)
		{
			unset($aData[$LPnLid]);
			continue;
		}
		if ($LPnLid != $nLid)
		{
			foreach ($aTotal as $LPsType => $LPnValue)
			{
				$aTotal[$LPsType] += $aData[$LPnLid][$LPsType];
			}
		}
	}

	function get_ComputeAccount($aVal,&$aRe_Val,$sAllLine='')
	{
		global $aSystem;
		/*
			$aVal['nIdentity']	層級
			$aVal['nLid']	web_user_link nId
			$aVal['aData']	進來計算的注單
			$aVal['sList']	這張注單當時sLidLinkList
			$aVal['aWater']	這張注單的 web_user_fs 反水/佔成
		*/
		# 我的位置-------------------------------------------
		$aLink = explode(',', $aVal['sList']);
		$aLink = array_flip($aLink);
		$nMyplace = $aLink[str_pad($aVal['nLid'],9,'0',STR_PAD_LEFT)];

		$aBetData = $aVal['aData'];
		$nNowLid = $aVal['nLid'];
		$aVal['nLid'] = $sAllLine;

		if (!empty($aVal))
		{
			# 位置找出反水/佔成-----------------------------------
			$nWaterValue = 0;
			$aPerMinus = $aPerLink = $aWaterLink = array();
			$aMyPlaceData = array(
				'nMyWater'		=> 0, # 這個位置反水
				'nMyWaterMinus'	=> 0, # 這個位置與下線反水差
				'nMyPer'		=> 0, # 這個位置佔成
				'nMyPerMinus'	=> 0, # 這個位置與下線占成差
				'nMyPerNext'	=> 0, # 下一層會員位置站成
			);
			$aCalnAgentFsPer_22 = array(
				'nWater1' => 0,
				'nWater2' => 0,
			);
			if(!empty($aVal['aWater']))
			{
				$nWaterValue= $aVal['aWater']['sWater']; # 這張注單反水值
				$aWaterLink = explode(',', $aVal['aWater']['sWaterLink'].'0'); 	# 這張注單反水線
				$aPerLink 	= explode(',', $aVal['aWater']['sPercentLink'].'0'); 	# 這張注單佔成線
				$aPerMinus 	= explode(',', $aVal['aWater']['sPercentMinus'].'0');	# 這張注單佔成差線

				if (isset($aWaterLink[$nMyplace]))
				{
					$aMyPlaceData['nMyWater'] 	= $aWaterLink[$nMyplace];
					$aMyPlaceData['nMyPer'] 	= $aPerLink[$nMyplace];
					$aMyPlaceData['nMyPerMinus'] 	= $aPerMinus[$nMyplace];
				}
				if (isset($aWaterLink[$nMyplace+1]))
				{
					$aMyPlaceData['nMyWaterMinus']= ($aWaterLink[$nMyplace] - $aWaterLink[$nMyplace + 1]);
					$aMyPlaceData['nMyPerNext'] 	= $aPerLink[$nMyplace + 1];
				}

				# 計算應付下限用(22)
				$aCalnAgentFsPer_22 = array(
					'nWater1' => ( isset($aWaterLink[$nMyplace + 1]) ) ? $aWaterLink[$nMyplace + 1] : 0,
					'nWater2' => ( isset($aWaterLink[$nMyplace + 2]) ) ? $aWaterLink[$nMyplace + 2] : 0,
				);
			}
			else
			{
				trigger_error('Report_list nId : '.$aBetData['nId'].' error nFsId ');
			}

			# 計算注單資料---------------------------------------
			$aTemp = array(
				'nAgentFs' 	=> 0,
				'nAgentFs_try'	=> 0,
				'aChild' 	=> array(
					'1718'=> 0,
					'19'	=> 0,
					'191'	=> 0,
				),
			);

			# 統計投注筆數/投注筆數/有效投注/總派彩/代理反點/投注反點 實際佔成/代理返点占成分担  (8) (9) (10) (11) (12) (17) (18)

			if ( ($aVal['nIdentity'] == 2 && $aBetData['nLid'] == $nNowLid) || $aVal['nIdentity'] == 1 || $aVal['nIdentity'] == 0)
			{
				$aRe_Val[$aVal['nLid']]['aBetPeople'][$aBetData['nLid']] = 1;
				# 投注筆數
				$aRe_Val[$aVal['nLid']]['nBetCount'] ++;
				# 總投注
				$aRe_Val[$aVal['nLid']]['nBetMoney']	+= abs($aBetData['nMoney1']);
				# 總輸贏額
				if($aBetData['nMoney1'] > 0) $aRe_Val[$aVal['nLid']]['nBetWin'] += $aBetData['nMoney1'];
				else $aRe_Val[$aVal['nLid']]['nBetWin1'] 	+= abs($aBetData['nMoney1']);
				# 投注返水
				$aRe_Val[$aVal['nLid']]['nBetFs'] 	+= abs($aBetData['nMoney1']) * ( $nWaterValue / 100 );
				$aTemp['nAgentFs'] += abs($aBetData['nMoney1']) * ( $nWaterValue / 100 );
			}

			if ($aVal['nIdentity'] == 2 )
			{
				# 統計自己的注單
				if ($aBetData['nLid'] != $nNowLid)
				{
					# 代理反水
					$aRe_Val[$aVal['nLid']]['nAgentFs'] += abs($aBetData['nMoney1']) * ( $aMyPlaceData['nMyWaterMinus'] / 100 );
					#----------------------------------------
					$aTemp['nAgentFs'] += abs($aBetData['nMoney1']) * ( $aMyPlaceData['nMyWaterMinus'] / 100 );
					$aTemp['nAgentFs_try'] = abs($aBetData['nMoney1']) * ( $aCalnAgentFsPer_22['nWater1'] / 100 );

				}

				# 實際占成
				$aRe_Val[$aVal['nLid']]['nPerXProfit'] 	+= (-1 * $aBetData['nMoney1']) * ( $aMyPlaceData['nMyPerMinus'] / 100 );
				# 代理返点占成分担
				$aRe_Val[$aVal['nLid']]['nPerXFs'] 	+= ( (-1 * $aTemp['nAgentFs'] * ( $aMyPlaceData['nMyPer'] / 100) ) + ( $aTemp['nAgentFs_try'] * ( $aMyPlaceData['nMyPerMinus'] / 100 )) );
			}
			else
			{
				# 下線的注單計算代理返水
				if ($aVal['nIdentity'] == 1 && ($aBetData['nLid'] != $nNowLid))
				{
					# 代理反水
					$aRe_Val[$aVal['nLid']]['nAgentFs'] += abs($aBetData['nMoney1']) * (($aMyPlaceData['nMyWater'] - $nWaterValue)/100);

					$aTemp['nAgentFs'] += abs($aBetData['nMoney1']) * (($aMyPlaceData['nMyWater'] - $nWaterValue)/100); # 2019-07-16 YL

					#下線的實際佔成+代理反水占成分擔
					$aTemp['aChild']['1718'] =
					( $aBetData['nMoney1'] + ( abs($aBetData['nMoney1']) * ( ($aCalnAgentFsPer_22['nWater1']) / 100 ) ) ) * ( $aMyPlaceData['nMyPerNext'] / 100 );
				}
				# 實際占成
				$aRe_Val[$aVal['nLid']]['nPerXProfit'] 	+= (-1 * $aBetData['nMoney1']) * ( $aMyPlaceData['nMyPer'] / 100 );
				# 代理返点占成分担
				$aRe_Val[$aVal['nLid']]['nPerXFs'] 	+= (-1 * $aTemp['nAgentFs']) * ( $aMyPlaceData['nMyPer'] / 100 );
			}

			# 應付下線
			// $aRe_Val[$aVal['nLid']]['data_22'] += $aTemp['aChild']['1718'] + $aTemp['aChild']['19'] + $aTemp['aChild']['191'];
		}
		return $aRe_Val;
	}

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>