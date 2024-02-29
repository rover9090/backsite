<?php
$sLineId	= filter_input_str('sLineId', INPUT_POST,'');
$sLineName	= filter_input_str('sLineName', INPUT_POST,'');

$aReturn = array(
	'nError' => 1,
	'aData' => array(),
);

if ($aJWT['a'] == 'initJWT')
{
	unset($_POST['sJWT']);
	$sJWT_Cart = sys_jwt_encode($_POST);
	setcookie('sLineData',$sJWT_Cart,COOKIE['REMEMBER'], COOKIE_DOMAIN);

	if($aUserData !== false)
	{
		$aReturn['aData'] = $aUserData;
	}
	else
	{
		trigger_error('應該在is_login就註冊了不該來這裡');
		$aReturn['nError'] = 1;
	}
	$aReturn['nError'] = 0;

}
echo json_encode($aReturn);
exit;
?>