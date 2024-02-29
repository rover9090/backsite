<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_jackpot_manual.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array();
	#js結束

	#參數接收區
	$nJid		= filter_input_str('nJid', INPUT_POST,'1');
	$nGame	= filter_input_str('nGame', INPUT_POST,'1001');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0_act0.php']).'&run_page=1',
		'sBack'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0.php']),
		'sPage'	=> sys_web_encode($aMenuToNo['pages/end_games/php/_client_jackpot_manual_0_upt0.php']),
		'sHtml'	=> 'pages/end_games/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_jackpot_manual_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBank = array();

	$nErr = 0;
	$sErrMsg = '';
	$aType1 = aMANUAL['TYPE1'];
	$aType3 = aMANUAL['TYPE3'];
	#宣告結束

	#程式邏輯區

	unset($aType1['sTitle']);
	unset($aType3['sTitle']);

	$sSQL = '	SELECT	Pot_.nId,
					Pot_.nMoney0 as nMoney
			FROM		'. CLIENT_JACKPOT_SET .' Pot_
			WHERE		Pot_.nOnline != 99
			AND		Pot_.nId = :nJid
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nJid', $nJid, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);

	if($aData === false && $nJid != '')
	{
		$nErr = 1;
		$sErrMsg = NODATA;
	}

	$aValue = array(
		'a'		=> 'INS',
		'nId'		=> $aData['nId'],
		't'		=> NOWTIME,
	);
	$sJWT = sys_jwt_encode($aValue);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	if ($nErr == 1)
	{
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sMsg'] = $sErrMsg;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aJumpMsg['0']['sClicktoClose'] = 1;
		$aJumpMsg['0']['sTitle'] = RIGHTMSG;
		$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
		$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
		$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
		$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
		$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

		$aRequire['Require'] = $aUrl['sHtml'];
	}
	#輸出結束
?>