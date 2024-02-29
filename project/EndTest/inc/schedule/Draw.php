<?php
	ini_set('error_log', dirname(dirname(dirname(__FILE__))).'/error_log.txt');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/System.php');
	require_once(dirname(dirname(__FILE__)).'/#Define.php');
	require_once(dirname(dirname(__FILE__)).'/#DefineTable.php');
	require_once(dirname(dirname(__FILE__)).'/#Function.php');
	$aSystem['nConnect'] = 2;
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/System/ConnectBase.php');
	require_once(dirname(dirname(__FILE__)).'/lang/'.$aSystem['sLang'].'/define.php');

	$nGame = 1001;
	$aData = array();
	$aGamesNums = array();

	$sSQL = '	SELECT 	sName0,
					sCode,
					nOdds
			FROM		'. END_METHHOD_SETTING .'
			WHERE		nGame = :nGame';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	sql_query($Result);
	while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aData[$aRows['sCode']] = $aRows;
	}

	$sSQL = '	SELECT 	sNo,
					sResult
			FROM		'. CLIENT_GAMES_NUMS .'
			WHERE		nGame = :nGame
			AND		nStatus = 4
			ORDER BY	nId DESC
			LIMIT		1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	sql_query($Result);
	$aGamesNums = $Result->fetch(PDO::FETCH_ASSOC);
?>

<div class="Block MarginBottom20">
	<span class="VerticalAlignMiddle">
		<span class="BtnKind"><?php echo '本期期數';?></span>
	</span>
	<span class="VerticalAlignMiddle">
		<span class="BtnKind"><?php echo $aGamesNums['sNo'];?></span>
	</span>
</div>
<div class="Block MarginBottom20">
	<?php
	foreach ($aData as $LPsCode => $LPaDetail)
	{
		switch($LPsCode)
		{
			case	'4R':
				($aGamesNums['sResult'] == '4R' ) ? $sStyle = 'color:red;' : $sStyle = '';
			break;

			case	'4W':
				($aGamesNums['sResult'] == '4W' ) ? $sStyle = 'color:red;' : $sStyle = '';
			break;

			case	'3R1W':
				($aGamesNums['sResult'] == '3R1W' ) ? $sStyle = 'color:red;' : $sStyle = '';
			break;

			case	'3W1R':
				($aGamesNums['sResult'] == '3W1R' ) ? $sStyle = 'color:red;' : $sStyle = '';
			break;

			case	'ODD':
				($aGamesNums['sResult'] == '3R1W' || $aGamesNums['sResult'] == '3W1R') ? $sStyle = 'color:red;' : $sStyle = '';
			break;

			case	'EVEN':
				($aGamesNums['sResult'] != '3R1W' && $aGamesNums['sResult'] != '3W1R')? $sStyle = 'color:red;' : $sStyle = '';
			break;

			default:
				$sStyle = '';
			break;
		}
	?>
		<span class="DisplayInlineBlock VerticalAlignMiddle">
			<span class="BtnKind JqBtnShowOnly" data-value="<?php echo $LPsCode;?>" style="<?php echo $sStyle;?>"><?php echo $LPaDetail['sName0'];?></span>
		</span>
	<?php
	}
	?>
</div>