<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
		<meta name="format-detection" content="telephone=no" />
		<link rel="apple-touch-icon-precomposed" href="images/favicon.ico?t=<?php echo VTIME; ?>">
		<link rel="shortcut icon" href="images/favicon.ico?t=<?php echo VTIME; ?>">
		<link rel="bookmark" href="images/favicon.ico?t=<?php echo VTIME; ?>"/>
		<title><?php echo SITETITLE;?></title>
		
		<?php
		require_once('inc/#Css.php');
		require_once('inc/#JsDefine.php');
		require_once('inc/#SetArray.php');

		#if (isset($aCss))
		#{
		#	foreach ($aCss as $LPsUrl)
		#	{
		#		echo '<link href=\''. $LPsUrl .'?t='.VTIME.'\'  media=\'all\' rel=\'stylesheet\' type=\'text/css\' />';
		#	}
		#}
		?>
	</head>
	<body class="JqBody" data-device="Mobile">
		<?php
		$aPageRequire = explode('/',$aRequire['Require']); # $aPage會跟#Define.php衝到
		$sPage = isset($aPageRequire[3])?str_replace('.php','',$aPageRequire[3]):'';
		$sFolder = isset($aPageRequire[1])?$aPageRequire[1]:'';
		if ($nS != 1) # not run_page=1
		{
			if($sPage != 'bet_record_0')
			{	
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/#Header.php');
			}
			require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/bet_0.php');

			if (isset($aRequire['Require']))
			{
				require_once($aRequire['Require']);
				// require_once('pages/'.$sFolder.'/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/'.$sPage.'.php');
			}
			require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/#Footer.php');
			
		}
		require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/#JumpMsg.php');
		?>

		<?php
		require_once('inc/#Js.php');
		// if (isset($aJs))
		// {
		// 	foreach ($aJs as $LPsUrl)
		// 	{
		// 		echo '<script src=\''. $LPsUrl.'?t='.VTIME.'\' type=\'text/javascript\'></script>';
		// 	}
		// }
		?>
	</body>
</html>