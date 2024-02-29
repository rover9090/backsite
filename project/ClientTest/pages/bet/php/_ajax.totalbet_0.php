<?php
	#require
	require_once('inc/#Require.php');
	$sNowLiff = LINELIFFBET;
	#require結束

	$nGame = filter_input_int('nGame', INPUT_POST, 1001);
	$sNo   = filter_input_int('sNo', INPUT_POST, '');

	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);

	$aData = array(
		'A' => array(
			'nTotalMoney' => 0,
		),
		'B' => array(
			'nTotalMoney' => 0,
		),
		'C' => array(
			'nTotalMoney' => 0,
		),
	);

	if($aJWT['a'] == 'TOTALBET')
	{
		$sSQL = '	SELECT 	nMoney0,
						sContent0
				FROM 		'.CLIENT_GAMES_DATA.'
				WHERE 	nGame = :nGame
				AND		sNo = :sNo
				AND		nDone = 0';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		$Result->bindValue(':sNo', $sNo, PDO::PARAM_STR);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['sContent0']]['nTotalMoney'] += $aRows['nMoney0'];
		}
	}
			
	$aReturn['aData'] = $aData;

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>