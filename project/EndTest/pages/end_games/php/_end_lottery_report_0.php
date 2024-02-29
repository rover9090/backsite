<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/end_lottery_report.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0'	=>	'plugins/js/end_games/end_lottery_report.js',
		'1'	=>	'plugins/js/end_games/end_games_ctrl.js',
	);
	#js結束

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '1001');
	$sNo		= filter_input_str('sNo', 	INPUT_REQUEST, '');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_end_lottery_report_0_act0.php']).'&run_page=1',
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/end_lottery_report_0.php',
	);
	#url結束

	#參數宣告區
	$aRESULTNAME = array(
		'Banker' => aRESULTNAME['BANKER'],
		'Others' => aRESULTNAME['OTHERS'],
		'A' => aRESULTNAME['A'],
		'B' => aRESULTNAME['B'],
		'C' => aRESULTNAME['C'],
	);
	$aGameSetting = array();
	$aData = array(
		'aNums' => array(),
		'aBet' => array(),
		'aBanker' => array(),
		'aBetCount' => array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
			'BANKER' => 0,
			'OTHERS' => 0,
		),
		'aBetMoney' => array(
			'A' => 0,
			'B' => 0,
			'C' => 0,
			'BANKER' => 0,
			'OTHERS' => 0,
		),
	);
	$aValue = array(
		'a'	=> 'LOTTERYREPORT',
		't'	=> NOWTIME,
		'sNo'	=> $sNo,
	);
	$sJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'	=> 'LOTTERYRESETTLE',
		't'	=> NOWTIME,
		'sNo'	=> $sNo,
	);
	$sResettleJWT = sys_jwt_encode($aValue);# form1
	$aValue = array(
		'a'	=> 'LOTTERYRESTORE',
		't'	=> NOWTIME,
		'sNo'	=> $sNo,
	);
	$sRestoreJWT = sys_jwt_encode($aValue);# form2
	$nCount = 0;
	$aStatus = aLOTTERY['aSTATUS'];
	$aStatus[-1] = array(
		'sText'	=> '',
		'sClass'	=> '',
	);
	$aBetResult = aRESULTNAME;

	#宣告結束

	#程式邏輯區
	$aGameSetting = GameSet_Class::fnCallSetting();
	foreach($aGameSetting as $LPsKey => $LPaData)
	{
		$aGameSetting[$LPsKey]['sText'] = $LPaData['sName0'];

		if($nGame == $LPsKey)
		{
			$aGameSetting[$LPsKey]['sSelect'] = 'selected';
		}
		else
		{
			$aGameSetting[$LPsKey]['sSelect'] = '';
		}
	}

	$sNo = trim($sNo);
	if($sNo != '')
	{
		$aBindArray['nGame'] = $nGame;
		$aBindArray['sNo'] = $sNo;

		$sSQL = '	SELECT	nId,
						sNo,
						sResult,
						sNums,
						nStatus
				FROM		'. CLIENT_GAMES_NUMS .'
				WHERE		nGame = :nGame
				AND		sNo LIKE :sNo
				LIMIT		1';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBindArray);
		sql_query($Result);
		$aData['aNums'] = $Result->fetch(PDO::FETCH_ASSOC);		
	
		if($aData['aNums'] === false)
		{
			$aData['aNums']['sNo'] = '查無期數';
			$aData['aNums']['nStatus'] = -1;
			$aData['aNums']['aNums'] = array();
			$aData['aNums']['aResult'] = array();
			$aData['aNums']['sNumsText'] = '';
			$aData['aNums']['sResultText'] = '';
		}
		else
		{
			$aData['aNums']['aNums'] = json_decode($aData['aNums']['sNums'],true);
			$aData['aNums']['aResult'] = json_decode($aData['aNums']['sResult'],true);
			$aData['aNums']['sNumsText'] = '';
			$aData['aNums']['sResultText'] = '';
			if(isset($aData['aNums']['aNums']['A'])){			
				$aData['aNums']['sNumsText'] = aNUMSNAME['Z'][$aData['aNums']['aNums']['Z']].','.aNUMSNAME['A'][$aData['aNums']['aNums']['A']].','.aNUMSNAME['B'][$aData['aNums']['aNums']['B']].','.aNUMSNAME['C'][$aData['aNums']['aNums']['C']];
				if(isset($aData['aNums']['aResult']['Money0']) && $aData['aNums']['aResult']['Money1'])
				{
					$aData['aNums']['sResultText'] = '下注額度：';
					foreach($aData['aNums']['aResult']['Money0'] as $LPsKey => $LPnResult)
					{
						$aData['aNums']['sResultText'] .= $aRESULTNAME[$LPsKey].$LPnResult.',';
					}
					$aData['aNums']['sResultText'] .= '<br>純贏：';
					foreach($aData['aNums']['aResult']['Money1'] as $LPsKey => $aData['aNums']['aResult'])
					{
						foreach($aData['aNums']['aResult'] as $LPsKey2 => $LPnResult2)
						{
							$aData['aNums']['sResultText'] .= $aRESULTNAME[$LPsKey].$LPsKey2.$LPnResult2.',';
						}
					}
				}
			}
		}
	
		$sSQL = '	SELECT	Game_.nId,
						Game_.nMoney0,
						Game_.nMoney1,
						Game_.sContent0,
						Game_.sCreateTime,
						User_.sName0
				FROM		'. CLIENT_GAMES_DATA .' Game_,
						'. CLIENT_USER_DATA .' User_
				WHERE		Game_.nUid = User_.nId
				AND		Game_.sNo LIKE :sNo
				AND		Game_.nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBindArray);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aRows['nMoney0'] = (float)$aRows['nMoney0'];
			$aRows['nMoney1'] = (float)$aRows['nMoney1'];
			$aData['aBet'][$aRows['nId']] = $aRows;
			$aData['aBetMoney'][$aRows['sContent0']] += $aRows['nMoney0'];
			$aData['aBetCount'][$aRows['sContent0']] ++;
		}

		$sSQL = '	SELECT	Game_.nId,
						Game_.nMoney0,
						Game_.nMoney1,
						Game_.nMoney2,
						Game_.nType0,
						Game_.sCreateTime,
						User_.sName0
				FROM		'. CLIENT_GAMES_BANKER .' Game_,
						'. CLIENT_USER_DATA .' User_
				WHERE		Game_.nUid = User_.nId
				AND		Game_.sNo LIKE :sNo
				AND		Game_.nGame = :nGame';
		$Result = $oPdo->prepare($sSQL);
		sql_build_value($Result,$aBindArray);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$LPsContent0 = $aRows['nType0'] === '0'?'BANKER':'OTHERS';
			$aRows['nMoney0'] = (float)$aRows['nMoney0'];
			$aRows['nMoney1'] = (float)$aRows['nMoney1'];
			$aData['aBanker'][$aRows['nId']] = $aRows;
			$aData['aBanker'][$aRows['nId']]['sContent0'] = $LPsContent0;
			$aData['aBetMoney'][$LPsContent0] += $aRows['nMoney0'];
			$aData['aBetCount'][$LPsContent0] ++;
		}
	}

	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aJumpMsg['result0'] = $aJumpMsg['0'];
	$aJumpMsg['result0']['sMsg'] = '莊家是否吃彩';
	$aJumpMsg['result0']['aButton']['0']['sClass'] = 'JqClose JqResult0_1';
	$aJumpMsg['result0']['aButton']['0']['sText'] = '莊家吃彩';
	$aJumpMsg['result0']['aButton']['1']['sClass'] = 'JqClose JqResult0_0 cancel JqEscapeClick';
	$aJumpMsg['result0']['aButton']['1']['sText'] = '莊家沒中彩';

	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aJumpMsg['1'] = $aJumpMsg['0'];#LOTTERYRESETTLE重過帳
	$aJumpMsg['2'] = $aJumpMsg['0'];#LOTTERYRESTORE還原
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>