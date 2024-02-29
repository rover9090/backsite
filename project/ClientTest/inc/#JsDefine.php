<?php
	require_once(dirname(__FILE__).'/lang/'.$aSystem['sLang'].'/JsDefine.php');

	echo '<script>';
	echo 'var aJSDEFINE = new Array();';
	echo 'aJSDEFINE[\'COOKIE_CLOSE\'] = \'' . COOKIE['CLOSE'] . '\';';
	echo 'aJSDEFINE[\'COOKIE_REMEMBER\'] = \'' . COOKIE['REMEMBER'] . '\';';
	foreach(aJSDEFINE as $LPsKey => $LPsDefine)
	{
		echo 'aJSDEFINE[\''.$LPsKey.'\'] = \'' . $LPsDefine . '\';';
	}
	if(isset($aJsDefine))
	{
		foreach($aJsDefine as $LPsKey => $LPsDefine)
		{
			echo 'aJSDEFINE[\''.$LPsKey.'\'] = \'' . $LPsDefine . '\';';
		}
	}
	echo '</script>';
?>