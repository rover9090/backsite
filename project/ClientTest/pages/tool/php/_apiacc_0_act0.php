<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/oCoreOpenssl.php');

	// $sAccount	= filter_input_str('sAccount',	INPUT_POST, '', 16);
	$sCypher 		= filter_input_str('cypher',			INPUT_REQUEST);
	$sJData =  oCoreOpenssl::AESdecrypt(SYS['KEY'],$sCypher);
	
	$aReturnData = array();
	$aDonateData = array();
	$aUrl = array(
	);
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'aUser'		=> $aUser,
	);
	$aBetData = array();
	$aData = json_decode($sJData,true);
	$aNums1 = array();
	$aNums2 = array();
	$sNums1 = '0';
	$sNums2 = '0';

	#登入
	if($sJData === false)
	{
		$aReturn['nError'] = 100;
		echo json_encode($aReturn);#加密錯誤
		exit;
	}
	
	if($aData === false || !isset($aData['nStartTime']) || !isset($aData['nEndTime']) || !isset($aData['sSite']))
	{
		$aReturn['nError'] = 101;
		echo json_encode($aReturn);#json陣列錯誤
		exit;
	}

	$sSQL = '	SELECT 	nId,
					sNo,
					nUid,
					nGame,
					nType1,
					nMoney0,
					nMoney1,
					sContent0,
					nStatus,
					sNums,
					sCreateTime,
					sUpdateTime as sEndTime,
					nGroup0,
					(SELECT sAccount FROM '.CLIENT_USER_DATA.' as USER WHERE USER.nId = GAMES.nUid) as sAccount,
					(SELECT sNums FROM '.CLIENT_GAMES_NUMS.' as NUMS WHERE NUMS.nGame = GAMES.nGame AND NUMS.sNo = GAMES.sNo) as sCardNums
			FROM 		'.CLIENT_GAMES_DATA.' as GAMES
			WHERE 	sSiteId = :sSite
			AND		nGame < 2000
			AND		nCreateTime > :nStartTime
			AND		nCreateTime <= :nEndTime
			AND		nDone = 1
			AND		nStatus < 3';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sSite', $aData['sSite'], PDO::PARAM_STR);
	$Result->bindValue(':nStartTime', $aData['nStartTime'], PDO::PARAM_INT);
	$Result->bindValue(':nEndTime', $aData['nEndTime'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRow = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBetData[] = $aRow;
	}

	$sSQL = '	SELECT 	nId,
					sNo,
					nUid,
					nGame,
					nType1,
					nMoney0,
					nMoney1,
					sContent0,
					nStatus,
					sNums,
					sCreateTime,
					sUpdateTime as sEndTime,
					nGroup0,
					(SELECT sAccount FROM '.CLIENT_USER_DATA.' as USER WHERE USER.nId = GAMES.nUid) as sAccount,
					(SELECT sNums FROM '.CLIENT_GAMES_NUMS2.' as NUMS WHERE NUMS.nGame = GAMES.nGame AND NUMS.sNo = GAMES.sNo) as sCardNums
			FROM 		'.CLIENT_GAMES_DATA.' as GAMES
			WHERE 	sSiteId = :sSite
			AND		nGame >= 2000
			AND		nCreateTime > :nStartTime
			AND		nCreateTime <= :nEndTime
			AND		nDone = 1
			AND		nStatus < 3';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sSite', $aData['sSite'], PDO::PARAM_STR);
	$Result->bindValue(':nStartTime', $aData['nStartTime'], PDO::PARAM_INT);
	$Result->bindValue(':nEndTime', $aData['nEndTime'], PDO::PARAM_INT);
	sql_query($Result);
	while($aRow = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBetData[] = $aRow;
	}

	$sSQL = '	SELECT 	DONATE.nId,
					DONATE.nUid,
					DONATE.nMoney0,
					DONATE.sCreateTime,
					DONATE.nDealer,
					DONATE.nGame,
					USER.sAccount,
					USER.sSiteId
			FROM 		'. CLIENT_GAMES_DONATE .' as DONATE, '.CLIENT_USER_DATA.' as USER
			WHERE		USER.nId = DONATE.nUid
			AND		USER.sSiteId = :sSite
			AND		DONATE.nCreateTime > :nStartTime
			AND		DONATE.nCreateTime <= :nEndTime
			AND 		DONATE.nMoney0 != 0
			ORDER BY 	DONATE.nCreateTime DESC, nId DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sSite', $aData['sSite'], PDO::PARAM_STR);
	$Result->bindValue(':nStartTime', $aData['nStartTime'], PDO::PARAM_INT);
	$Result->bindValue(':nEndTime', $aData['nEndTime'], PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aRows['nMoney0'] = (float)$aRows['nMoney0'];
		$aDonateData[$aRows['nId']] = $aRows;
	}

	$aReturn['aData'] = $aBetData;
	$aReturn['aData2'] = $aDonateData;
	echo json_encode($aReturn);
	exit;

?>