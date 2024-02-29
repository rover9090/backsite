<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_withdrawal.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
		1	=> 'plugins/js/client_money/client_withdrawal.js',
	);
	#js結束

	#參數接收區
	$sStartTime		= filter_input_str('sStartTime', 		INPUT_REQUEST,date('Y-m-d 00:00:00'));
	$sEndTime		= filter_input_str('sEndTime', 		INPUT_REQUEST,date('Y-m-d 23:59:59'));
	$nKid			= filter_input_int('nKid', 			INPUT_REQUEST,0);
	$nStatus		= filter_input_int('nStatus', 		INPUT_REQUEST,-1);
	$sAdmin		= filter_input_str('sAdmin', 			INPUT_REQUEST,'');
	$sMemberAccount	= filter_input_str('sMemberAccount', 	INPUT_REQUEST,'');
	$sMemo		= filter_input_str('sMemo', 			INPUT_REQUEST,'');
	#參數結束

	#給此頁使用的url
	$aUrl = array(
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_withdrawal_0.php']),
		'sHtml'	=> 'pages/client_money/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_withdrawal_0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aBank = array();
	$aMemberData = array();
	$aAllAdminData = array();
	$aSearchBankCard = array(0=>0);
	$aBankCard = array();
	$sCondition = '';
	$sAdmCondition = '';
	$aAdmBind = array();
	$aBind = array();
	$sTempDetail = '<div class="DetailBoxTable Table">
				<div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['BANKNAME'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::sBankName::]]([[::sCode::]])</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['SUBNAME'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::sName2::]]</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['USERNAME'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::sName1::]]</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['CARDNUMBER'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::sName0::]]</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['MONEY'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::nMoney::]]</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aWITHDRAWAL['FEE'].'</div>
						</div>
						<div class="DetailBoxCell2 JqCopyMe">
							<div>[[::nFee::]]</div>
						</div>
						<div class="DetailBoxCell3">
							<div class="JqCopy">['.aWITHDRAWAL['COPY'].']</div>
						</div>
					</div>
				</div>
			</div>';
	$sAdmIds = '0';
	$nPageStart = $aPage['nNowNo'] * $aPage['nPageSize'] - $aPage['nPageSize'];
	$aJumpMsg['0']['sClicktoClose'] = 1;
	$aJumpMsg['0']['sTitle'] = RIGHTMSG;
	$aJumpMsg['0']['sMsg'] = CSUBMIT.'?';
	$aJumpMsg['0']['aButton']['0']['sClass'] = 'JqReplaceO';
	$aJumpMsg['0']['aButton']['0']['sUrl'] = '';
	$aJumpMsg['0']['aButton']['0']['sText'] = SUBMIT;
	$aJumpMsg['0']['aButton']['1']['sClass'] = 'JqClose cancel';
	$aJumpMsg['0']['aButton']['1']['sText'] = CANCEL;
	$aStatus = aWITHDRAWAL['STATUS'];
	$nTotalMoney = 0;
	$nPageTotalMoney = 0;
	$nTotalCount = 0;
	$nPageTotalCount = 0;

	$aValue = array(
		'a'		=> 'EXCEL',
		't'		=> NOWTIME,
	);
	$sExcelJWT = sys_jwt_encode($aValue);
	$sExcelVar = '&sJWT='.$sExcelJWT;
	#宣告結束

	#程式邏輯區
	$sCondition .= ' AND Money_.nCreateTime >= :nStartTime AND Money_.nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$sExcelVar .= '&sStartTime='.$sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$sExcelVar .= '&nEndTime='.$nEndTime;
	$aBind['nEndTime'] = $nEndTime;


	if($nKid > 0)
	{
		$sSQL = '	SELECT	nId
				FROM		'. CLIENT_USER_BANK .'
				WHERE		nOnline != 99
				AND 		nBid = :nBid';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':nBid', $nKid, PDO::PARAM_INT);
		sql_query($Result);
		while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
		{
			$aSearchBankCard[$aRows['nId']] = $aRows['nId'];
		}
		$sCondition .= ' AND Money_.nKid IN ( '.implode(',', $aSearchBankCard).' ) ';
		$aPage['aVar']['nKid'] = $nKid;
		$sExcelVar .= '&nKid='.$nKid;
		$aSearchBankCard = array(0=>0);

	}

	if($nStatus > -1)
	{
		$sCondition .= ' AND Money_.nStatus = :nStatus ';
		$aPage['aVar']['nStatus'] = $nStatus;
		$sExcelVar .= '&nStatus='.$nStatus;
		$aBind['nStatus'] = $nStatus;
	}

	if($sAdmin != '')
	{
		$aPage['aVar']['sAdmin'] = $sAdmin;
		$sExcelVar .= '&sAdmin='.$sAdmin;
		$sAdmCondition .= '	AND sAccount LIKE :sAdmin ';
		$aAdmBind['sAdmin'] = '%'.$sAdmin.'%';
	}
	else
	{
		$sAdmIds = '-1';
	}

	if($sMemberAccount != '')
	{
		$aPage['aVar']['sMemberAccount'] = $sMemberAccount;
		$sExcelVar .= '&sMemberAccount='.$sMemberAccount;
		$sCondition .= '	AND User_.sAccount LIKE :sMemberAccount ';
		$aBind['sMemberAccount'] = '%'.$sMemberAccount.'%';
	}

	if($sMemo != '')
	{
		$aPage['aVar']['sMemo'] = $sMemo;
		$sExcelVar .= '&sMemo='.$sMemo;
		$sCondition .= '	AND Money_.sMemo LIKE :sMemo ';
		$aBind['sMemo'] = '%'.$sMemo.'%';
	}

	$aUrl['sExcel'] = $aUrl['sAct'].$sExcelVar;

	unset($aStatus['sTitle']);
	foreach($aStatus as $LPnStatus => $LPaDetail)
	{
		if($nStatus == $LPnStatus)
		{
			$aStatus[$LPnStatus]['sSelect'] = 'selected';
		}
	}

	$sSQL = '	SELECT	nId,
					sAccount
			FROM		'. END_MANAGER_DATA .'
			WHERE		nOnline != 99
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	// sql_build_value($Result,$aAdmBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aAllAdminData[$aRows['nId']] = $aRows;
	}

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
		$sCondition .= ' AND ( Money_.nAdmin1 IN ( ' . $sAdmIds . ' ) OR Money_.nAdmin2 IN ( ' . $sAdmIds . ' ) ) ';
	}


	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nAdmin1,
					Money_.nAdmin2,
					Money_.nMoney,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 3
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nTotalMoney += $aRows['nMoney'];
		$nTotalCount ++;
	}

	$sSQL = '	SELECT	nId,
					sName0,
					sCode
			FROM		'. SYS_BANK .'
			WHERE		nType0 = 1
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBank[$aRows['nId']] = $aRows;
		$aBank[$aRows['nId']]['sSelect'] = '';
		if($nKid == $aRows['nId'])
		{
			$aBank[$aRows['nId']]['sSelect'] = 'selected';
		}
	}

	$aPage['nDataAmount'] = $nTotalCount;

	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nMoney,
					Money_.nStatus,
					Money_.nKid,
					Money_.sPayType,
					Money_.nAdmin1,
					Money_.nAdmin2,
					Money_.nFee,
					Money_.sMemo,
					Money_.nType1,
					Money_.sCreateTime,
					Money_.sUpdateTime,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 3
			AND 		Money_.nUid = User_.nId
					'. $sCondition .'
			ORDER	BY	Money_.nId DESC '.sql_limit($nPageStart, $aPage['nPageSize']);
	$Result = $oPdo->prepare($sSQL);
	sql_build_value($Result,$aBind);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$nPageTotalMoney += $aRows['nMoney'];
		$nPageTotalCount ++;
		$aData[$aRows['nId']] = $aRows;
		$aData[$aRows['nId']]['sBank'] = '';
		$aData[$aRows['nId']]['sAdmin1'] = '';
		$aData[$aRows['nId']]['sAdmin2'] = '';

		if($aRows['nAdmin1'] <= 0)
		{
			$aValue = array(
				'a'		=> 'RISKPASS'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sLPJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sRiskPass'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;

			$aValue = array(
				'a'		=> 'RISKDENY'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sLPJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sRiskDeny'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;
		}
		else
		{
			$aData[$aRows['nId']]['sAdmin1'] = $aAllAdminData[$aRows['nAdmin1']]['sAccount'];
		}

		if($aRows['nAdmin2'] <= 0 && $aRows['nAdmin1'] > 0)
		{
			$aValue = array(
				'a'		=> 'MONEYPASS'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sLPJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sMoneyPass'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;

			$aValue = array(
				'a'		=> 'MONEYDENY'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sLPJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sMoneyDeny'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sLPJWT;
		}
		else if($aRows['nAdmin2'] > 0 && $aRows['nAdmin1'] > 0)
		{
			$aData[$aRows['nId']]['sAdmin2'] = $aAllAdminData[$aRows['nAdmin2']]['sAccount'];
		}

		$aSearchBankCard[$aRows['nKid']] = $aRows['nKid'];
	}

	# 會員銀行卡
	$sSQL = '	SELECT 	nId,
					sName0,
					sName1,
					sName2,
					nBid
			FROM 		'. CLIENT_USER_BANK .'
			WHERE 	nId IN ( '.implode(',', $aSearchBankCard).' )';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aBankCard[$aRows['nId']] = $aRows;
		$aBankCard[$aRows['nId']]['sBankName'] = $aBank[$aRows['nBid']]['sName0'];

		$LPaTemp = array(
			'[[::sBankName::]]',
			'[[::sCode::]]',
			'[[::sName2::]]',
			'[[::sName1::]]',
			'[[::sName0::]]',
		);
		$LPaReplace = array(
			$aBank[$aRows['nBid']]['sName0'],
			$aBank[$aRows['nBid']]['sCode'],
			$aRows['sName2'],
			$aRows['sName1'],
			$aRows['sName0'],
		);
		$aBankCard[$aRows['nId']]['sDetail'] = str_replace($LPaTemp, $LPaReplace, $sTempDetail);
	}

	foreach ($aData as $LPnId => $LPaData)
	{
		$LPaTemp = array(
			'[[::nMoney::]]',
			'[[::nFee::]]',
		);
		$LPaReplace = array(
			$LPaData['nMoney'],
			$LPaData['nFee']
		);
		$LPsDetail = str_replace($LPaTemp, $LPaReplace, $aBankCard[$LPaData['nKid']]['sDetail']);
		$aJumpMsg[$LPnId] = array(
			'sBoxClass'	=>	'',
			'sShow'	=>	0,	# 是否直接顯示彈窗 0=>隱藏 , 1=>顯示
			'sTitle'	=>	$LPaData['sUserAccount'].' '.aWITHDRAWAL['DETAIL'],	# 標題
			'sIcon'	=>	'',	# 成功=>success,失敗=>error
			'sMsg'	=>	'',	# 訊息
			'sArticle'	=>	$LPsDetail,	# 較長文字
			'aButton'	=>	array(
				'0'	=>	array(
					'sClass'	=>	'JqClose',	# 若為取消=>cancel,點擊關閉不換頁=>JqClose,送出form=>submit
					'sUrl'	=>	'',	# 跳轉之url
					'sText'	=>	aWITHDRAWAL['CLOSE'],	# 顯示之文字
				),
			),
			'nClicktoClose'	=>	1,	# 是否點擊任意一處即可關閉 0=>否 , 1=>是
		);
	}

	$aPageList = pageSet($aPage, $aUrl['sPage']);
	#程式邏輯結束

	#輸出json
	$sData = json_encode($aData);
	$aRequire['Require'] = $aUrl['sHtml'];
	#輸出結束
?>