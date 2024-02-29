<?php
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) .'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/login.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/oCoreOpenssl.php');

	// $sAccount	= filter_input_str('sAccount',	INPUT_POST, '', 16);
	$sCypher 		= filter_input_str('cypher',			INPUT_REQUEST);
	$sJData =  oCoreOpenssl::AESdecrypt(SYS['KEY'],$sCypher);
	
	$aReturnData = array();
	$aUrl = array(
		'sApiLogin1'		=> sys_web_encode($aMenuToNo['pages/tool/php/_apilogin_0_act1.php']),
	);
	$aReturn = array(
		'nError'		=> 0,
		'sMsg'		=> 'Error',
		'aData'		=> array(),
		'nAlertType'	=> 0,
		'aUser'		=> $aUser,
	);
	$aPaFsData = array();
	$aLinkData = array();
	$aData = json_decode($sJData,true);

	$sAccount		= '';
	$sName		= '';
	$sSite		= '';

	#登入
	if($sJData === false)
	{
		$aReturn['nError'] = 100;
		echo json_encode($aReturn);#加密錯誤
		exit;
	}
	
	if($aData === false || !isset($aData['sAccount']) || !isset($aData['sName']) || !isset($aData['sSite']))
	{
		$aReturn['nError'] = 101;
		$aReturn['aData'] = $aData;
		echo json_encode($aReturn);#json陣列錯誤
		exit;
	}

	$sName		= $aData['sName'];
	$sSite		= $aData['sSite'];
	$sAccount = $sSite.'-'.trim(strtolower($aData['sAccount']));

	$sSQL = '	SELECT	1
			FROM		' . CLIENT_USER_DATA . '
			WHERE		sAccount = :sAccount
			AND		nOnline != 99';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sAccount', $sAccount, PDO::PARAM_STR);
	sql_query($Result);
	$aAccount = $Result->fetch(PDO::FETCH_ASSOC);

	if($aAccount !== false)
	{
		$aReturn['nError'] = '201';
		echo json_encode($aReturn);#檢測到帳號重複
		exit;
	}

	// if($aData['sSite'] == 'abp')
	// {
		if(!isset($aData['sPerLink']) || !isset($aData['sValueLink']))
		{
			$aReturn['nError'] = 102;
			$aReturn['aData'] = $aData;
			echo json_encode($aReturn);#json陣列錯誤
			exit;
		}
		else
		{
			if($aData['sValueLink'][strlen($aData['sValueLink'])-1] == ',')
			{
				$aData['sValueLink'] = substr($aData['sValueLink'],0,-1);
			}
			if($aData['sPerLink'][strlen($aData['sPerLink'])-1] == ',')
			{
				$aData['sPerLink'] = substr($aData['sPerLink'],0,-1);
			}
			$aPaFsData = array(
				'sPromoWater' => 0,
				'sPromoPercent' => 0,
				'sWaterLink' => $aData['sValueLink'],
				'sPercentLink' => $aData['sPerLink'],
			);
		}
	// }


	$oPdo->beginTransaction();
	$aRegister = array(
		'sAccount'		=> $sAccount,
		'sPassword'		=> oCypher::ReHash(md5(''),NULL,1),
		'sSiteId'		=> $sSite,
		'sName0'		=> $sName,
		'aPaLinkData'	=> $aLinkData,
		'aPaFsData'		=> $aPaFsData,
		'nStatus'		=> 0,
		'nIdentity'		=> 1, // 後台新增的皆為代理
		'nFrom'		=> 1,	// 0:後台 1:前台
		'nAdmin'		=> 0,
	);
	$oUser->register($aRegister);
	$oPdo->commit();

	echo json_encode($aReturn);
	exit;

?>