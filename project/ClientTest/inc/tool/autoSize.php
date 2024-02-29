<?php
	ini_set('display_errors','1');
	define('QUALITY',70);
	// ini_set('log_errors', 1);
	error_reporting(E_ALL);
	#取得目錄路徑
	#$sPath = 'images/520x520/2019/06/12/bg.png';
	#$sPath = 'Img/o/2019/06/12/bg.png';
	// $sPath = 'magic/768*432/M_GameCity000/2019/06/13/web_game/318306132059472019.JPG';
	$sPath = substr($_SERVER['REQUEST_URI'], 1);
	$sPath = isset($_GET['sPicture'])?$_GET['sPicture']:'';
	#images/coin/12.png
	// echo 'sPath=',$sPath,'<br>';
	// exit;
	$aPath = explode('/',$sPath);
	$sImgUrl0 = dirname(dirname(dirname(__FILE__))).'/'.$sPath;

	$aType = array('jpg','jpeg','png','bmp','gif');
	$sDirCod = 'magic';
	$nDirCount = count($aPath);
	$sImgUrl = '';
	$sOldImg = '';
	$sOldOriImg = '';
	$sFileName = '';
	$aNewPath = $aPath;

	// $image_info = getimagesize($sImgUrl0); 
	// print_r($image_info); 
	// exit;
	
	$sFileName = $aNewPath[count($aNewPath)-1];
	$sImgUrl = dirname(dirname(dirname(__FILE__))).'/images/resize/coin/'.$sFileName;
	// echo $sImgUrl;exit;
	// unset($aNewPath[count($aNewPath)-1]);
	// echo '<pre>';
	// print_r($aNewPath);
	// echo '</pre><br>';
	// exit;
	// echo 'sImgUrl=',$sImgUrl,'<br>';
	// echo 'sOldImg=',$sOldImg,'<br>';
	// echo '<pre>';
	// print_r($aNewPath);
	// echo '</pre><br>';
	// exit;
	
	$aData = array(
		'oldpath'	=> '',
		'newpath'	=> '',
		'width'	=> '',
		'height'	=> '',
		'oldsize'	=> 0,
	);

	if (file_exists($sImgUrl))
	{
		showThisFile($sImgUrl);
	}
	else
	{
		$aData['oldpath']	= $sImgUrl0;
		$aData['newpath']	= $sImgUrl;
		$aData['width']	= 210;
		$aData['height']	= 210;

		#var_dump($aData);
		AutoCreatImgFile($aData);
	}

	#異常
	if ($nS <> 0)
	{
		echo $nS;
	}
	exit;

	function showThisFile($sPath)
	{
		// echo "true";exit;
		header ("Content-type: image/png");
		$oOldImg = ImageCreateFromPng($sPath);
		imagepng($oOldImg);
		imagedestroy($oOldImg);
	}


	function AutoCreatImgFile($aData)
	{
		$sNewPath = $aData['newpath'];

		$aType = array('jpg','jpeg','png','bmp','gif');

		$nQuality = 80;

		$aOld = array(
			'width'	=> 0,
			'height'	=> 0,
			'type'	=> '',
		);
		#縮圖size
		$aNew = array(
			'width'	=> $aData['width'],
			'height'	=> $aData['height'],
		);

		$aTemp = array();
		if (file_exists($aData['oldpath']))
		{
			$aInfo = getimagesize($aData['oldpath']);
			if (!empty($aInfo))
			{
				$aOld['width']	= $aInfo['0'];
				$aOld['height']	= $aInfo['1'];

				if (isset($aData['oldsize']) && $aData['oldsize'] == 1)
				{
					$aNew['width']	= $aInfo['0'];
					$aNew['height']	= $aInfo['1'];
				}

				$aTemp = explode('/',$aInfo['mime']);

				if (isset($aTemp['1']) && in_array(strtolower($aTemp['1']), $aType))
				{
					$oNewImg = imagecreatetruecolor($aNew['width'], $aNew['height']);
					// $oNewImg = imagecreate($aNew['width'], $aNew['height']);
					imagealphablending($oNewImg, false);
					imagesavealpha($oNewImg, true);
					$trans_background  = imagecolorallocatealpha($oNewImg,0,0,0,127);
					imagefill($oNewImg, 0, 0, $trans_background);
					switch (strtolower($aTemp['1']))
					{
						case 'jpg':
						case 'jpeg':
							$oOldImg = imageCreateFromJpeg($aData['oldpath']);
						break;
						case 'bmp':
							$oOldImg = imageCreateFromBmp($aData['oldpath']);
						break;
						case 'png':
							$oOldImg = imageCreateFromPng($aData['oldpath']);
						break;
						case 'gif':
							$oOldImg = imageCreateFromGif($aData['oldpath']);
						break;
						default:
							$oOldImg = 0;
					}
					// var_dump($oOldImg);

					if ($oOldImg <> 0)
					{
						imagecopyresampled($oNewImg, $oOldImg, 0, 0, 0, 0, $aNew['width'], $aNew['height'], $aOld['width'], $aOld['height']);
						imagepng($oNewImg, $sNewPath, 9);
						// imagedestroy($oOldImg);
						// imagedestroy($oNewImg);
					}
					header ("Content-type: image/jpeg");
					imagejpeg($oNewImg);
					exit;
					
				}
				else
				{
					return false;
				}
			}
		}
	}


?>