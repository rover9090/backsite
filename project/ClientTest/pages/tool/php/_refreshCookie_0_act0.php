<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	#require結束
	#參數接收區
	#參數結束

	#參數宣告區
	$nErr = 0;
	$sMsg = '';

	$aData = array();
	#宣告結束
	if (isset($aJWT['nId']) && isset($aJWT['sSid']))
	{
		setcookie('sSid', $aJWT['sSid'], COOKIE['REMEMBER']);
		$oUser = new oUser();
		$oUser->updateCookie(array('sSid'=>$aJWT['sSid']));
		exit;
	}
?>