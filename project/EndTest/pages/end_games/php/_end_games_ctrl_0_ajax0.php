<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__file__)))) .'/inc/#Unload.php');
	#require end

	#參數接收區
	$nGame	= filter_input_int('nGame', 	INPUT_REQUEST, '1001');
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
	$aReturnData = array();
	$sBankerStr = '排莊列表';
	#宣告結束

	#程式邏輯區
	if ($aJWT['a'] == 'BANKERSTR')
	{
		$nI = 1;
		$sSQL = '	SELECT 	nId,nUid,nLid,(SELECT sName0 FROM '.CLIENT_USER_DATA.' WHERE nId = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as sName0,(SELECT nMoney FROM '.CLIENT_USER_MONEY.' WHERE nUid = '.CLIENT_GAMES_BANKER_ORDER.'.nUid LIMIT 1) as nMoneyNow
				FROM 		'. CLIENT_GAMES_BANKER_ORDER .'
				WHERE 	nGame = :nGame
				AND		nStatus = 0
				ORDER BY nCreateTime ASC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aRows['nMoneyNow'] = (float)$aRows['nMoneyNow'];
			$aBanker[$aRows['nId']] = $aRows;
			if($nI === 1)
			{
				$sBankerStr .= '<br><span class="FontGreen">*'.$nI.'.['.$aRows['sName0'].']('.$aRows['nMoneyNow'].'</span>)';
			}
			else
			{
				$sBankerStr .= '<br>'.$nI.'.['.$aRows['sName0'].']('.$aRows['nMoneyNow'].')';
			}
			$nI++;
		}
		$aReturnData['sBankerStr'] = $sBankerStr;

		$aReturn['aData'] = $aReturnData;

		echo json_encode($aReturn);
		exit;
	}
?>