<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
		<meta name="format-detection" content="telephone=no" />
		<link rel="apple-touch-icon-precomposed" href="images/favicon.ico?t=<?php echo VTIME; ?>">
		<link rel="shortcut icon" href="images/favicon.ico?t=<?php echo VTIME; ?>">
		<link rel="bookmark" href="images/favicon.ico?t=<?php echo VTIME; ?>"/>
		<title><?php echo $aSystem['sTitle'];?></title>
		
		<?php
		require_once('inc/#Css.php');
		require_once('inc/#JsDefine.php');
		#if (isset($aCss))
		#{
		#	foreach ($aCss as $LPsUrl)
		#	{
		#		echo '<link href=\''. $LPsUrl .'?t='.VTIME.'\'  media=\'all\' rel=\'stylesheet\' type=\'text/css\' />';
		#	}
		#}
		?>
	</head>
	<body class="JqBody BG" data-device="Pc" style="background-image: url('images/bg.jpg?t=<?php echo VTIME;?>');">
		<?php
		$aPageRequire = explode('/',$aRequire['Require']); # $aPage會跟#Define.php衝到
		$sPage = isset($aPageRequire[3])?str_replace('.php','',$aPageRequire[3]):'';
		$sFolder = isset($aPageRequire[1])?$aPageRequire[1]:'';
		if ($nS != 1) # not run_page=1
		{
			require_once('inc/#SetArray.php');			

			if (!in_array($sPage,$aNoHeader) && $sRe[0] == 'R')
			{
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/#Header.php');
			}

			if (!in_array($sPage,$aNoNav) && $sRe[0] == 'R')
			{
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/#Nav.php');
			}

			if (isset($aRequire['Require']))
			{
				require_once($aRequire['Require']);
				// require_once('pages/'.$sFolder.'/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/'.$sPage.'.php');
			}

			//若為首頁及遊戲頁面引入header選單彈窗頁面
			if (($sPage === 'index_0' || $sPage === 'bet_0' || $sPage === 'bet_1') && $sRe[0] == 'R')
			{
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/sound_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/rule_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/center_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/changeImg_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/changeName_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/changepwd_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/rank_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/attention_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/lang_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/record_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/betdetail_0.php');
			}

			//若為遊戲頁面引入聊天室、聊天室旁選單、選擇籌碼及確認下注彈窗頁面
			if (($sPage === 'bet_0' || $sPage === 'bet_1') && $sRe[0] == 'R')
			{
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/net_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/limit_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/coin_0.php');
				require_once('inc/'.$aSystem['sClientHtml'].$aSystem['nClientVer'].'/bet_0.php');
			}

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