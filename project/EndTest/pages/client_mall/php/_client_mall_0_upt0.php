<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_mall.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		'0' => 'plugins/js/client_mall/client_mall.js',
	);
	#js結束

	#參數接收區
	$nId			= filter_input_int('nId', 	INPUT_REQUEST,-1);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sUpt'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_upt0.php']),
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_mall/php/_client_mall_0.php']),
		'sHtml'	=> 'pages/client_mall/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_mall_0_upt0.php',
	);
	#url結束

	#參數宣告區
	$aData = array();
	$aBind = array();
	$nCount = 0;
	$sCondition = '';
	$aImage = array();
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'submit';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aOnline = aONLINE;
	$aValue = array(
		'a'		=> 'UPT',
		't'		=> NOWTIME,
		'nId'		=> $nId
	);
	$sUptJWT = sys_jwt_encode($aValue);
	$aValue = array(
		'a'		=> 'INS',
		't'		=> NOWTIME,
		'nId'		=> $nId
	);
	$sInsJWT = sys_jwt_encode($aValue);
	#宣告結束

	#程式邏輯區
	$sSQL = '	SELECT	nId,
					sName0,
					nPrice0,
					nOnline,
					sCreateTime,
					sUpdateTime,
					sContent0,
					nCoverPic
			FROM		'. CLIENT_MALL .'
			WHERE		nId = :nId';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nId', $nId, PDO::PARAM_INT);
	sql_query($Result);
	$aData = $Result->fetch(PDO::FETCH_ASSOC);


	if($nId === -1 && $aData === false)
	{
		$aData = array(
			'sName0' => '',
			'nPrice0' => '',
			'aImage' => array(),
			'sDelUrl' => '',
			'sDelUrl' => '',
			'nStatus' => '0',
		);
	}
	else if($nId !== -1 && $aData === false)
	{
		$aData['aImage'] = array('0'=>array('sUrl' => '','sDelUrl'=>''));
		$aJumpMsg['0']['sTitle'] = ERRORMSG;
		$aJumpMsg['0']['sIcon'] = 'error';
		$aJumpMsg['0']['sMsg'] = NODATA;
		$aJumpMsg['0']['sShow'] = 1;
		$aJumpMsg['0']['aButton']['0']['sUrl'] = $aUrl['sBack'];
		$aJumpMsg['0']['aButton']['0']['sText'] = CONFIRM;
	}
	else
	{
		$aValue = array(
			'a'		=> 'DELIMG',
			't'		=> NOWTIME,
			'nId'		=> $nId,
		);
		$LPsJWT = sys_jwt_encode($aValue);
		$aData['sDelUrl'] = $aUrl['sAct'].'&sJWT='.$LPsJWT;
		$sSQL = '	SELECT	nId,
						nKid,
						sTable,
						sFile,
						nCreateTime,
						nType0
				FROM		'. CLIENT_IMAGE_CTRL .'
				WHERE		nKid = :nKid
				AND		sTable LIKE \''. CLIENT_MALL .'\'
				ORDER	BY	nId DESC';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nKid', $nId, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aData['aImage'][$aRows['nType0']]['sUrl'] = IMAGE['URL'].'images/'.$aFile['sDir'].'/'.date('Y/m/d/',$aRows['nCreateTime']).$aRows['sTable'].'/'.$aRows['sFile'];				
		}
		$aJumpMsg['1'] = $aJumpMsg['0'];
		$aJumpMsg['1']['aButton']['0']['sClass'] = 'JqReplaceO';
		$aOnline[$aData['nOnline']]['sSelect'] = 'SELECTED';
	}
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>