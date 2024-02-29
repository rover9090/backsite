<?php
	/**
	 * 非line開啟直接檔掉 待補
	 */

	ini_set('error_log', dirname(dirname(__FILE__)) .'/error_log.txt');
	require_once(dirname(dirname(dirname(__FILE__))) .'/System/System.php');
	require_once('#Define.php');
	require_once('#DefineTable.php');
	require_once('#Function.php');
	/*
		處理關於頁面判斷
		1讀 2讀寫 0不連線
	*/
	$aSystem['nConnect'] = 1;
	require_once(dirname(dirname(dirname(__FILE__))) .'/System/ConnectBase.php');
	require_once(dirname(dirname(dirname(__FILE__))) .'/System/Connect/ClientUserClass.php');

	$sJWT 	= filter_input_str('sJWT', INPUT_REQUEST,'');
	$bPass = true;
	$oJWT = new cJwt();
	if($sJWT != '')
	{
		$aJWT = $oJWT->validToken($sJWT);
		if ($aJWT === false)
		{
			$bPass = false;
			# 返回上一頁
			header('Location:'.$aJWT['b']);
			exit;
		}
	}
	// require_once('#SetArray.php');
?>