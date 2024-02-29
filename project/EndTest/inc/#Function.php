<?php
	#----- 網址加/解密 -----#
	function sys_web_encode($sUrl)
	{
		global 	$nWebUrlType,
				$aMenuToUrl;


		switch($nWebUrlType)
		{
			case 1 :
				return sys_enCode1($sUrl);
				break;
			case 2 :
				return sys_enCode2($aMenuToUrl[$sUrl]);
				break;
		}
	}

	function sys_web_decode($aRequire)
	{
		global 	$nWebUrlType;

		switch($nWebUrlType)
		{
			case 1 :
				return sys_deCode1($aRequire);
				break;
			case 2 :
				return sys_deCode2($aRequire['Param']);
				break;
		}
	}

	// method 1
	function sys_enCode1($sUrl)
	{
		if(false)
		{
			return '/?'.$sUrl;
		}
		else
		{
			$sTemp = './?';

			if ($sUrl == '')
			{
				$sTemp = '';
			}
			else
			{
				$sTemp .= base64_encode($sUrl.'_'.substr(md5($sUrl.SYS['KEY']),aCTRL['FIND'],aCTRL['GET']));
			}

			return $sTemp;
		}
	}

	function sys_deCode1($aRequire)
	{
		$sRe = SYS['DEFAULTPAGE'];
		if ($aRequire['Param'] <> '')
		{
			if(false)
			{
				$sRe = $aRequire['Param'];
			}
			else
			{
				$nS = 0;
				$sRe = base64_decode($aRequire['Param']);
				$aTemp = explode('_', $sRe);
				if (sizeof($aTemp) <> 2)
				{
					$nS = 1;
				}
				else
				{
					$sSys_code = substr(md5($aTemp['0'].SYS['KEY']),aCTRL['FIND'],aCTRL['GET']);
					if ($aTemp['1'] <> $sSys_code)
					{
						$nS = 1;
					}
				}

				if (!isset($aRequire['MenuToUrl'][$aTemp['0']]))
				{
					$nS = 1;
				}

				if ($nS == 0)
				{
					$sRe = $aTemp['0'];
				}
			}
		}

		if (isset($aRequire['MenuToUrl'][$sRe]))
		{
			return $sRe;
		}
		else
		{
			exit;
		}
	}

	// method 2
	function sys_enCode2($sUrl)
	{
		global	$aMenuToNo,
				$aSystem;

		$sEnPage = preg_replace("/\\d+/",'', $aMenuToNo[$sUrl]);

		$aToken = explode('/'.$aSystem['sHtml'].$aSystem['nVer'].'/', $sUrl);

		if(count($aToken) != 2)
		{
			$aToken = explode('/php/', $sUrl);
		}
		else
		{
			$sEnPage .= '_'.$aSystem['nVer'];
		}

		$sEnUrl = str_replace('php', $sEnPage, $aToken[1]);

		return './?'.$sEnUrl;
	}

	function sys_deCode2($sUrl)
	{
		global	$aMenuIndex,
				$aSystem,
				$aMenuToNo,
				$aMenuToUrl;

		if($sUrl == '')
		{
			return SYS['DEFAULTPAGE'];
			exit;
		}

		$sDeUrl = 'pages/[FILE]/[TYPE]/[PHP].php';

		$aToken = explode('.', $sUrl);

		if(count($aToken) != 2)
		{
			$aTmp = $aToken;
			unset($aToken);

			$aToken[1] = array_pop($aTmp);
			$aToken[0] = '';

			foreach($aTmp as $LPsToken)
			{
				$aToken[0] .= ($aToken[0] != '') ? '.'.$LPsToken : $LPsToken;
			}
		}

		$aTmp = explode('_', $aToken[1]);

		$sFile = $aMenuIndex[$aTmp[0]];
		$sType = (count($aTmp) == 2) ? $aSystem['sHtml'].$aSystem['nVer'] : 'php';
		$sPhp = $aToken[0];

		$sDeUrl = str_replace('[FILE]',	$sFile,	$sDeUrl);
		$sDeUrl = str_replace('[TYPE]',	$sType,	$sDeUrl);
		$sDeUrl = str_replace('[PHP]',	$sPhp,	$sDeUrl);

		if (isset($aMenuToNo[$sDeUrl]))
		{
			return $aMenuToNo[$sDeUrl];
		}
		else
		{
			exit;
		}
	}

	function sendmsg($aData, $sType)
	{
		#sType => 'Reply'
		#sType => 'Push'
		$sUrl = LINEREAPI;

		if ($sType === 'Push')
		{
			$sUrl = LINEPUSHAPI;
		}

		$ch = curl_init($sUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($aData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json; charser=UTF-8',
			'Authorization: Bearer ' . LINETOKEN
		));
		$result = curl_exec($ch);
		curl_close($ch);
		// error_log($result);
	}
?>