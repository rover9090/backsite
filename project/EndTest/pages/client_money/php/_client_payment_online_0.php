<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_payment_online.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
		1	=> 'plugins/js/client_money/client_payment_online.js'
	);
	#js結束

	#參數接收區
	$sStartTime		= filter_input_str('sStartTime', 	INPUT_REQUEST,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 	INPUT_REQUEST,date('Y-m-d 23:59:59'));
	$sAdmin		= filter_input_str('sAdmin', 		INPUT_REQUEST,'');
	$sMemberAccount	= filter_input_str('sMemberAccount',INPUT_REQUEST,'');
	$sOrder		= filter_input_str('sOrder',		INPUT_REQUEST,'');
	$nKid			= filter_input_int('nKid', 		INPUT_REQUEST,0);
	$nStatus		= filter_input_int('nStatus', 	INPUT_REQUEST,-1);
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_online_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_online_0.php']),
		'sHtml'	=> 'pages/client_money/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_payment_online_0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aPayment = array();
	$aTunnel = array();
	$aAllAdminData = array();
	$aAdmBind = array();
	$aBind = array();
	$sAdmIds = '0';
	$sCondition = '';
	$sAdmCondition = '';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];

	$aSearch = array(
		'sStartTime'	=> $sStartTime,
		'sEndTime'		=> $sEndTime,
		'sAdmin'		=> $sAdmin,
		'sMemberAccount'	=> $sMemberAccount,
		'sOrder'		=> $sOrder,
		'nKid'		=> $nKid,
		'nStatus'		=> $nStatus,
	);
	$sSearch = '';

	#彈跳視窗
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;

	$aStatus = aPAYMENTONLINE['STATUS'];
	unset($aStatus['sTitle']);
	$nTotalMoney = 0;
	$nPageTotalMoney = 0;
	$nTotalCount = 0;
	$nPageTotalCount = 0;
	#宣告結束

	#程式邏輯區

	foreach($aSearch as $LPsKey => $LPsValue)
	{
		$sSearch .= '&' . $LPsKey . '=' . $LPsValue;
	}

	$sCondition .= ' AND Money_.nCreateTime >= :nStartTime AND Money_.nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$aBind['nEndTime'] = $nEndTime;

	if($sAdmin != '')
	{
		$sAdmCondition .= '	AND sAccount LIKE :sAdmin ';
		$aAdmBind['sAdmin'] = '%'.$sAdmin.'%';
		$aPage['aVar']['sAdmin'] = $sAdmin;
	}

	if($sMemberAccount != '')
	{
		$sCondition .= '	AND User_.sAccount LIKE :sMemberAccount ';
		$aBind['sMemberAccount'] = '%'.$sMemberAccount.'%';
		$aPage['aVar']['sMemberAccount'] = $sMemberAccount;
	}

	if($sOrder != '')
	{
		$sCondition .= '	AND Money_.sOrder LIKE :sOrder ';
		$aBind['sOrder'] = '%'.$sOrder.'%';
		$aPage['aVar']['sOrder'] = $sOrder;
	}

	if($nKid > 0)
	{
		$sCondition .= ' AND Money_.nKid = :nKid ';
		$aBind['nKid'] = $nKid;
		$aPage['aVar']['nKid'] = $nKid;
	}

	if($nStatus > -1)
	{
		$sCondition .= ' AND Money_.nStatus = :nStatus ';
		$aBind['nStatus'] = $nStatus;
		$aPage['aVar']['nStatus'] = $nStatus;
		$aStatus[$nStatus]['sSelect'] = 'selected';
	}

	# 取會員 #
	$sSQL = '	SELECT	nId,
					sAccount
			FROM		'. END_MANAGER_DATA .'
			WHERE		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAllAdminData[$aRows['nId']] = $aRows;
	}

	# 取管理員 #
	$sSQL = '	SELECT	nId,
					sAccount
			FROM		'. END_MANAGER_DATA .'
			WHERE		nOnline != 99
					'. $sAdmCondition .'
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aAdmBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sAdmIds .= ','.$aRows['nId'];
	}

	if($sAdmin != '')
	{
		$sCondition .= ' AND Money_.nAdmin0 IN ( ' . $sAdmIds . ' ) ';
	}

	# 取金流 #
	$sSQL = '	SELECT	nId,
					sName0
			FROM		'. CLIENT_PAYMENT .'
			WHERE		nType0 = 2
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aPayment[$aRows['nId']] = $aRows;
		$aPayment[$aRows['nId']]['sSelect'] = '';
		if($nKid == $aRows['nId'])
		{
			$aPayment[$aRows['nId']]['sSelect'] = 'selected';
		}
	}

	# 取通道 #
	$sSQL = '	SELECT	nId,
					nPid,
					sKey,
					sValue
			FROM		'. CLIENT_PAYMENT_TUNNEL .'
			WHERE		1
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aTunnel[$aRows['nPid']][$aRows['sKey']] = $aRows;
	}

	# 統計 #
	$sSQL = '	SELECT	Money_.nId,
					Money_.nMoney,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 2
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nTotalCount ++;
		$nTotalMoney += $aRows['nMoney'];
	}

	$aPage['nDataAmount'] = $nTotalCount;

	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nMoney,
					Money_.nStatus,
					Money_.nKid,
					Money_.sOrder,
					Money_.sPayType,
					Money_.nFee,
					Money_.nAdmin0,
					Money_.sCreateTime,
					Money_.sUpdateTime,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 2
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nPageTotalCount ++;
		$nPageTotalMoney += $aRows['nMoney'];

		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sPayment'] 	= $aPayment[$aRows['nKid']]['sName0'];
		$aData[$aRows['nId']]['sTunnel'] 	= $aTunnel[$aRows['nKid']][$aRows['sPayType']]['sValue'];
		$aData[$aRows['nId']]['aStatus'] 	= $aStatus[$aRows['nStatus']];

		$aData[$aRows['nId']]['sAdmin0'] = '';
		$aData[$aRows['nId']]['sPass'] = '';
		$aData[$aRows['nId']]['sCancel'] = '';

		if($aRows['nAdmin0'] > 0)
		{
			$aData[$aRows['nId']]['sAdmin0'] = $aAllAdminData[$aRows['nAdmin0']]['sAccount'];
			if($aRows['nStatus'] == 1)
			{
				$aData[$aRows['nId']]['aStatus']['sText'] = aPAYMENTONLINE['HANDCONFIRM'];
			}
		}

		if($aRows['nStatus'] == 0)
		{
			$aValue = array(
				'a'		=> 'PASS'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sPass'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT . $sSearch;

			$aValue = array(
				'a'		=> 'CANCEL'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sCancel'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT . $sSearch;
		}
	}

	foreach ($aData as $LPnId => $LPaDetail)
	{
		$aJumpMsg[$LPnId] = array(
			'sBoxClass'	=>	'',
			'sShow'	=>	0,	# 是否直接顯示彈窗 0=>隱藏 , 1=>顯示
			'sTitle'	=>	'',	# 標題
			'sIcon'	=>	'',	# 成功=>success,失敗=>error
			'sMsg'	=>	aPAYMENTONLINE['aMsg']['MANUAL'],	# 訊息
			'sArticle'	=>	'',	# 較長文字
			'aButton'	=>	array(
				'0'	=>	array(
					'sClass'	=>	'',	# 若為取消=>cancel,點擊關閉不換頁=>JqClose,送出form=>submit
					'sUrl'	=>	$LPaDetail['sPass'],	# 跳轉之url
					'sText'	=>	CONFIRM,	# 顯示之文字
				),
				'1'	=>	array(
					'sClass'	=>	'cancel',	# 若為取消=>cancel,點擊關閉不換頁=>JqClose,送出form=>submit
					'sUrl'	=>	$LPaDetail['sCancel'],	# 跳轉之url
					'sText'	=>	DENY,	# 顯示之文字
				),
			),
			'nClicktoClose'	=>	1,	# 是否點擊任意一處即可關閉 0=>否 , 1=>是
		);

		if($LPaDetail['nStatus'] != 0)
		{
			$aJumpMsg[$LPnId]['aButton'] = array(
				'0'	=>	array(
					'sClass'	=>	'JqClose',	# 若為取消=>cancel,點擊關閉不換頁=>JqClose,送出form=>submit
					'sUrl'	=>	'',	# 跳轉之url
					'sText'	=>	CLOSE,	# 顯示之文字
				),
			);
		}
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>