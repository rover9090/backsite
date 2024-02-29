<?php
	#require
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/#Unload.php');
	require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/inc/lang/'.$aSystem['sLang'].'/client_payment_company.php');
	#require結束

	#給此頁運用的css，按先後順序排列陣列
	$aCss = array();
	#css 結束

	#給此頁運用的js，按先後順序排列陣列
	$aJs = array(
		0	=> 'plugins/js/js_date/laydate.js',
		1	=> 'plugins/js/client_money/client_payment_company.js'
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
		'sAct'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_company_0_act0.php']).'&run_page=1',
		'sPage'	=> sys_web_encode($aMenuToNo['pages/client_money/php/_client_payment_company_0.php']),
		'sHtml'	=> 'pages/client_money/'.$aSystem['sHtml'].$aSystem['nVer'].'/client_payment_company_0.php',
	);
	#url結束

	#參數宣告區
	$nStartTime = strtotime($sStartTime);
	$nEndTime = strtotime($sEndTime);
	$aData = array();
	$aCompany = array();
	$aMemberData = array();
	$aAllAdminData = array();
	$sCondition = '';
	$sAdmCondition = '';
	$aAdmBind = array();
	$aBind = array();
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
	$aStatus = aPAYMENTCOMPANY['STATUS'];
	$sTempDetail = '<div class="DetailBoxTable Table">
				<div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aPAYMENTCOMPANY['ACCOUNT'].'</div>
						</div>
						<div class="DetailBoxCell2">
							<div>[[::sAccount::]]</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aPAYMENTCOMPANY['MONEY'].'</div>
						</div>
						<div class="DetailBoxCell2">
							<div>[[::nMoney::]]</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aPAYMENTCOMPANY['FEE'].'</div>
						</div>
						<div class="DetailBoxCell2">
							<div>[[::nFee::]]</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aPAYMENTCOMPANY['BANKNAME'].'</div>
						</div>
						<div class="DetailBoxCell2">
							<div>[[::sCompany::]]</div>
						</div>
					</div>
					<div>
						<div class="DetailBoxCell1">
							<div>'.aPAYMENTCOMPANY['MEMO'].'</div>
						</div>
						<div class="DetailBoxCell2">
							<div>[[::sMemo::]]</div>
						</div>
					</div>
				</div>
			</div>';
	$nTotalMoney = 0;
	$nPageTotalMoney = 0;
	$nTotalCount = 0;
	$nPageTotalCount = 0;
	#宣告結束

	#程式邏輯區

	$sCondition .= ' AND Money_.nCreateTime >= :nStartTime AND Money_.nCreateTime <= :nEndTime';
	$aPage['aVar']['sStartTime'] = $sStartTime;
	$aBind['nStartTime'] = $nStartTime;
	$aPage['aVar']['sEndTime'] = $sEndTime;
	$aBind['nEndTime'] = $nEndTime;

	if($nKid > 0)
	{
		$sCondition .= ' AND Money_.nKid = :nKid ';
		$aPage['aVar']['nKid'] = $nKid;
		$aBind['nKid'] = $nKid;
	}

	if($nStatus > -1)
	{
		$sCondition .= ' AND Money_.nStatus = :nStatus ';
		$aPage['aVar']['nStatus'] = $nStatus;
		$aBind['nStatus'] = $nStatus;
	}

	if($sAdmin != '')
	{
		$aPage['aVar']['sAdmin'] = $sAdmin;
		$sAdmCondition .= '	AND sAccount LIKE :sAdmin ';
		$aAdmBind['sAdmin'] = '%'.$sAdmin.'%';
	}

	if($sMemberAccount != '')
	{
		$aPage['aVar']['sMemberAccount'] = $sMemberAccount;
		$sCondition .= '	AND User_.sAccount LIKE :sMemberAccount ';
		$aBind['sMemberAccount'] = '%'.$sMemberAccount.'%';
	}

	if($sMemo != '')
	{
		$aPage['aVar']['sMemo'] = $sMemo;
		$sCondition .= '	AND Money_.sMemo LIKE :sMemo ';
		$aBind['sMemo'] = '%'.$sMemo.'%';
	}

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
		$sCondition .= ' AND Money_.nAdmin0 IN ( ' . $sAdmIds . ' ) ';
	}

	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nAdmin0,
					Money_.nMoney,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 1
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

	$sSQL = '	SELECT	nId,
					sName0
			FROM		'. CLIENT_PAYMENT .'
			WHERE		nType0 = 1
			ORDER	BY	nId DESC';
	$Result = $oPdo->prepare($sSQL);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$aCompany[$aRows['nId']] = $aRows;
		$aCompany[$aRows['nId']]['sSelect'] = '';
		if($nKid == $aRows['nId'])
		{
			$aCompany[$aRows['nId']]['sSelect'] = 'selected';
		}
	}

	$aPage['nDataAmount'] = $nTotalCount;

	$sSQL = '	SELECT	Money_.nId,
					Money_.nUid,
					Money_.nMoney,
					Money_.nStatus,
					Money_.nKid,
					Money_.sPayType,
					Money_.nAdmin0,
					Money_.nFee,
					Money_.sMemo,
					Money_.nType1,
					Money_.sCreateTime,
					Money_.sUpdateTime,
					User_.sAccount as sUserAccount
			FROM		'. CLIENT_MONEY .' Money_,
					'. CLIENT_USER_DATA .' User_
			WHERE		Money_.nType0 = 1
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
		$aData[$aRows['nId']]['sCompany'] = $aCompany[$aRows['nKid']]['sName0'];
		$aData[$aRows['nId']]['sAdmin0'] = '';
		$aData[$aRows['nId']]['sDetail'] = '';
		$aData[$aRows['nId']]['sPass'] = '';
		$aData[$aRows['nId']]['sCancel'] = '';

		if($aRows['nAdmin0'] > 0)
		{
			$aData[$aRows['nId']]['sAdmin0'] = $aAllAdminData[$aRows['nAdmin0']]['sAccount'];
		}

		if($aRows['nStatus'] == 0)
		{
			$aValue = array(
				'a'		=> 'PASS'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sPass'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT;

			$aValue = array(
				'a'		=> 'CANCEL'.$aRows['nId'],
				't'		=> NOWTIME,
			);
			$sJWT = sys_jwt_encode($aValue);
			$aData[$aRows['nId']]['sCancel'] = $aUrl['sAct'].'&nId='.$aRows['nId'].'&sJWT='.$sJWT;
		}

		$LPaTemp = array(
			'[[::sAccount::]]',
			'[[::nMoney::]]',
			'[[::nFee::]]',
			'[[::sCompany::]]',
			'[[::sMemo::]]',
		);

		$LPaReplace = array(
			$aRows['sUserAccount'],
			$aRows['nMoney'],
			$aRows['nFee'],
			$aData[$aRows['nId']]['sCompany'],
			$aData[$aRows['nId']]['sMemo'],
		);
		$aData[$aRows['nId']]['sDetail'] = str_replace($LPaTemp, $LPaReplace, $sTempDetail);
	}

	foreach ($aData as $LPnId => $LPaDetail)
	{
		$aJumpMsg[$LPnId] = array(
			'sTitle'	=>	$LPaDetail['sUserAccount'].' '.aPAYMENTCOMPANY['DETAIL'],
			'sArticle'	=>	$aData[$LPnId]['sDetail'],
			'aButton'	=>	array(
				'0'	=>	array(
					'sUrl'	=>	$LPaDetail['sPass'],
					'sText'	=>	CONFIRM,
				),
				'1'	=>	array(
					'sClass'	=>	'cancel',
					'sUrl'	=>	$LPaDetail['sCancel'],
					'sText'	=>	DENY,
				),
			),
			'nClicktoClose'	=>	1,
		);

		if($LPaDetail['nStatus'] != 0)
		{
			$aJumpMsg[$LPnId]['aButton'] = array(
				'0'	=>	array(
					'sClass'	=>	'JqClose',
					'sText'	=>	CLOSE,
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