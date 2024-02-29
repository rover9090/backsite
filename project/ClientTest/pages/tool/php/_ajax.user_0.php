<?php
	#require
	require_once(dirname(dirname(__FILE__)).'/inc/#Require.php');
	#require結束

	#參數接收區
	$sSid = empty($_COOKIE['sSid'])?$_COOKIE['sSid']:'';
	#參數結束

	#給此頁使用的url
	#url結束

	#參數宣告區 - declare

	/**
	 * 回傳陣列 JSON
	 * @var Int nError
	 * 	回傳狀態值
	 * 	0 => 正常 其餘待補
	 * @var String sMsg
	 * 	回傳訊息
	 * @var Array aData
	 * 	回傳陣列
	 * @var Int nAlertType
	 * 	回傳訊息提示類型
	 * 	0 => 不需提示框
	 * @var String sUrl
	 * 	回傳後導頁檔案
	 */
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'sUrl'		=> ''
	);
	#宣告結束

	#程式邏輯區
	// $nUid = $oUser->checkCookie();

	if($aChkLogin_Member === false)
	{
		$aJWT['a'] = 'RETURN';
	}
	if($aJWT['a'] == 'RETURN')
	{		
	}

	if($aJWT['a'] == 'USER' )
	{
		$aReturn['aData'] = $aChkLogin_Member;
	}

	#程式邏輯結束

	#輸出json
	echo json_encode($aReturn,JSON_UNESCAPED_UNICODE);
	#輸出結束
?>