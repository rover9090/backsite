<?php
	#require
	// require_once(dirname(dirname(dirname(dirname(__file__)))).'/inc/#Unload.php');
	#require結束

	$nDealer	= filter_input_int('nDealer', 	INPUT_POST, '0');
	$nStatus	= filter_input_int('nStatus', 	INPUT_POST, '0');

	$aData = array();
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> '',
	);

	if($aJWT['a'] == 'WATCH')
	{
		$sSQL = '	SELECT 	nId
				FROM 		'.CLIENT_WATCH_DEALER.'
				WHERE 	nUid = :nUid
				AND		nDealer = :nDealer
				LIMIT 1';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nUid', $aUser['nUid'],PDO::PARAM_INT);
		$Result->bindValue(':nDealer', $nDealer,PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData[$aRows['nId']] = $aRows;
		}

		if(!empty($aData))
		{
			$aSQL_Array = array(
				'nStatus'		=> $nStatus,
				'nUpdateTime'	=> NOWTIME,
				'sUpdateTime'	=> NOWDATE,
			);

			$sSQL = '	UPDATE	'. CLIENT_WATCH_DEALER .'
					SET		'. sql_build_array('UPDATE', $aSQL_Array) . '
					WHERE 	nUid = :nUid
					AND		nDealer = :nDealer
					LIMIT 	1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $aUser['nUid'],PDO::PARAM_INT);
			$Result->bindValue(':nDealer', $nDealer,PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
		}
		else
		{
			$aSQL_Array = array(
				'nUid'		=> (int) $aUser['nUid'],
				'nDealer'		=> (int) $nDealer,
				'nStatus'		=> (int) $nStatus,
				'nCreateTime'	=> NOWTIME,
				'sCreateTime'	=> NOWDATE,
				'nUpdateTime'	=> NOWTIME,
				'sUpdateTime'	=> NOWDATE,
			);

			$sSQL = 'INSERT INTO '.CLIENT_WATCH_DEALER.' ' . sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
		}
	}

	$aReturn['aData'] = $aUser;

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
	exit;
?>