<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	#require end

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '1001');
	$nRead	= filter_input_int('nRead', 	INPUT_REQUEST, '0');
	// $nGame = trim($sNo);
	#參數結束

	#參數宣告區
	$sNo = '';
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> ''
	);
	$aReturnData = array(
	);
	$sUserList = '0';
	#宣告結束

	#程式邏輯區
	if($nGame > 2000)
	{
		exit;
	}
	if ($aJWT['a'] == 'NUMSAJAX')
	{
		$sJsonRooms = file_get_contents(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/File/RoomJson.txt');
		$aRooms = json_decode($sJsonRooms,true);

		// $sSQL = '	SELECT 	nId,nSec,nSec1
		// 		FROM  	'.END_GAMES_SETTING.'
		// 		WHERE 	sLang = :sLang
		// 		AND		nGame = :nGame';
		// $Result = $oPdo->prepare($sSQL);
		// $Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
		// $Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		// sql_query($Result);
		// $aGameSetting = $Result->fetch(PDO::FETCH_ASSOC);

		$aGameSetting = $aRooms[$nGame]['aSetting'];

		// $sSQL = '	SELECT 	nId,
		// 				sNo,
		// 				nStartTime,
		// 				nBetEndTime,
		// 				nPreStartTime
		// 		FROM  	'.CLIENT_GAMES_NUMS.'
		// 		WHERE 	nGame = :nGame
		// 		AND		nUse = 1
		// 		AND		nStatus < 10
		// 		AND		nPreStartTime <= :NOWTIME
		// 		ORDER BY nPreStartTime DESC
		// 		LIMIT 1';
		// $Result2 = $oPdo->prepare($sSQL);
		// $Result2->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		// $Result2->bindValue(':NOWTIME', NOWTIME, PDO::PARAM_INT);
		// sql_query($Result2);
		// $aNums = $Result2->fetch(PDO::FETCH_ASSOC);
		$aNums = $aRooms[$nGame]['aLastest'];
		
		$aReturn['aData']['sNums'] = '';
		$nSeconds = $aRooms[$nGame]['aLastest']['nSeconds'];
		$nSeconds1 = $aRooms[$nGame]['aLastest']['nSeconds1'];
		// $nSeconds = 0;
		// if($aNums['nStartTime'] <= NOWTIME)
		// {
		// 	$nSeconds = ($aNums['nStartTime'] + $aGameSetting['nSec']) - NOWTIME;
		// }
		// $nSeconds1 = $aNums['nPreStartTime'] + $aGameSetting['nSec1'] - NOWTIME;
		// $aReturn['aData']['nSeconds']	= $nSeconds;
		// $aReturn['aData']['nSeconds1'] = $nSeconds1;

		$aReturn['aData']['nSeconds']	= $nSeconds;
		$aReturn['aData']['nSeconds1'] = $nSeconds1;

		if($aNums != false)
		{
			if($nSeconds <= 0)
			{
				$aReturn['sJoker'] = 1;
				$aNumsTemp = fnGetJoker($nGame,$aGameSetting);
				$aReturn['aData']['aNums'] = $aNumsTemp;
				$sNumsTemp = json_encode($aNums);
				$aReturn['aData']['sNums'] = $sNumsTemp;
			}					
		}
		

		echo json_encode($aReturn);
		exit;
	}

function fnGetJoker($nGame,$aGameSetting)
{
	$aDB = array(
		'dbHost'		=>	'192.168.15.97:8869',
		// 'dbHost'		=>	'213.139.235.71:8869',
		'dbName' 		=>	'Poker',
		'dbUser'		=>	'Poker',
		'dbPassword' 	=>	'6MwYJSEME856zCW6',
	);
	$oPdo1 = oPdo_Connection($aDB);
	unset($aDB);

	$aCardResult = array();

	$sSQL = '	SELECT 	sno,
					card1,
					card2,
					card3,
					card4,
					card5,
					card6,
					time_stamp,
					game
			FROM		card_result
			WHERE		game = :game
			AND		time_stamp > :LASTTIME
			ORDER BY 	sno DESC
			LIMIT 1';
	$Result1 = $oPdo1->prepare($sSQL);
	$Result1->bindValue(':game',$nGame-1000,PDO::PARAM_INT);
	$Result1->bindValue(':LASTTIME',NOWTIME - $aGameSetting['nSec1'],PDO::PARAM_INT);
	sql_query($Result1);
	$aCardResult = $Result1->fetch(PDO::FETCH_ASSOC);
	
	$aCardResult = fnChangeNums($aCardResult);
	$aBanker = array(
		$aCardResult['card4'],
		$aCardResult['card3'],
	);
	$aPlayer = array(
		$aCardResult['card2'],
		$aCardResult['card1'],
	);
	if($aCardResult['card6'] != '0')
	{
		$aBanker[2] = $aCardResult['card6'];
	}
	if($aCardResult['card5'] != '0')
	{
		$aPlayer[2] = $aCardResult['card5'];
	}

	$aNums = array(
		'sBanker' => implode(',',$aBanker),
		'sPlayer' => implode(',',$aPlayer),
	);
	
	return $aNums;
}

function fnChangeNums($aNums)
{
	$aTrans = array(
		'201' => 'CA',
		'202' => 'C2',
		'203' => 'C3',
		'204' => 'C4',
		'205' => 'C5',
		'206' => 'C6',
		'207' => 'C7',
		'208' => 'C8',
		'209' => 'C9',
		'210' => 'C10',
		'211' => 'CJ',
		'212' => 'CQ',
		'213' => 'CK',

		'401' => 'DA',
		'402' => 'D2',
		'403' => 'D3',
		'404' => 'D4',
		'405' => 'D5',
		'406' => 'D6',
		'407' => 'D7',
		'408' => 'D8',
		'409' => 'D9',
		'410' => 'D10',
		'411' => 'DJ',
		'412' => 'DQ',
		'413' => 'DK',

		'301' => 'HA',
		'302' => 'H2',
		'303' => 'H3',
		'304' => 'H4',
		'305' => 'H5',
		'306' => 'H6',
		'307' => 'H7',
		'308' => 'H8',
		'309' => 'H9',
		'310' => 'H10',
		'311' => 'HJ',
		'312' => 'HQ',
		'313' => 'HK',

		'101' => 'SA',
		'102' => 'S2',
		'103' => 'S3',
		'104' => 'S4',
		'105' => 'S5',
		'106' => 'S6',
		'107' => 'S7',
		'108' => 'S8',
		'109' => 'S9',
		'110' => 'S10',
		'111' => 'SJ',
		'112' => 'SQ',
		'113' => 'SK',
		'0' => '',
	);
	$aGameTrans = array(
		'1' => '1001',
		'2' => '1002',
		'3' => '1003',
	);

	// foreach($aNums as $LPsKey => $LPsNums)
	for($i=1;$i<=6;$i++)
	{
		if(!empty($aNums['card'.$i]) && isset($aTrans[$aNums['card'.$i]]))
		{
			$aNums['card'.$i] = $aTrans[$aNums['card'.$i]];
		}
	}

	if(isset($aGameTrans[$aNums['game']]))
	{
		$aNums['game'] = $aGameTrans[$aNums['game']];
	}
	return $aNums;
}
?>