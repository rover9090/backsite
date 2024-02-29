<?php
	require_once('UserClass.php');

	class oClientUser extends oUser
	{
		static public $aCurrentFs = array();

		public function register($aData)
		{
			global $oPdo,$aSystem;

			$aTempAccount = explode('-',$aData['sAccount']);
			$nCredit = isset($aTempAccount[2])?$aTempAccount[2]:0;
			$aSQL_Array = array(
				'sName0'		=> (string)	isset($aData['sName0'])?$aData['sName0']:$aData['sAccount'],
				'sPassword'		=> (string)	$aData['sPassword'],
				'sAccount'		=> (string)	$aData['sAccount'],
				'nOnline'		=> (int)	1,
				'nStatus'		=> (int)	isset($aData['nStatus'])?$aData['nStatus']:0,
				'nBlack'		=> (int)	isset($aData['nBlack'])?$aData['nBlack']:0,
				'nMute'		=> (int)	isset($aData['nMute'])?$aData['nMute']:0,
				'nIdentity'		=> (int)	isset($aData['nIdentity'])?$aData['nIdentity']:0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
				'sSiteId'		=> (string) $aData['sSiteId'],
				'sLineId'		=> (string) $aData['sLineId'],
				'sPicture'		=> (string) isset($aData['sPicture'])?$aData['sPicture']:'',
				'nCredit'		=> $nCredit,
			);
			$aEditLog[CLIENT_USER_DATA]['aNew'] = $aSQL_Array;

			$sSQL = 'INSERT INTO '.CLIENT_USER_DATA.' '. sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nUid = $oPdo->lastInsertId();

			$aNewMoney = array(
				'Money' => (float) 0,
				'Water' => (float) 0,
			);

			$aSQL_Array = oTransfer::PointUpdate($nUid,$aNewMoney,1);
			$aSQL_Array['nUid'] = $nUid;

			$sSQL = 'INSERT INTO '.CLIENT_USER_MONEY.' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			// Link
			$aPaLinkData = $aData['aPaLinkData'];
			if(!empty($aPaLinkData))
			{
				$aSQL_Array = array(
					'nUid'		=> (int)	$nUid,
					'nLevel'		=> (int)	$aPaLinkData['nLevel'] + 1,
					'sLinkList'		=> (string)	$aPaLinkData['sLinkList'] .','. str_pad($nUid,9,'0',STR_PAD_LEFT),
					'nPa'			=> (int)	$aPaLinkData['nUid'],
					'nGrandPa'		=> (int)	$aPaLinkData['nPa'],
					'nPaLid'		=> (int)	$aPaLinkData['nId'],
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
			}
			else
			{
				$aSQL_Array = array(
					'nUid'		=> (int)	$nUid,
					'nLevel'		=> (int)	1,
					'sLinkList'		=> (string)	str_pad($nUid,9,'0',STR_PAD_LEFT),
					'nPa'			=> (int)	0,
					'nGrandPa'		=> (int)	0,
					'nPaLid'		=> (int)	0,
					'nCreateTime'	=> (int)	NOWTIME,
					'sCreateTime'	=> (string)	NOWDATE,
				);
			}
			$aEditLog[CLIENT_USER_LINK]['aNew'] = $aSQL_Array;

			$sSQL = 'INSERT INTO '. CLIENT_USER_LINK .' '. sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			if(!empty($aPaLinkData))
			{
				$aSQL_Array = array(
					'sLidLinkList'	=> (string) $aPaLinkData['sLidLinkList'] .','. str_pad($nLastId,9,'0',STR_PAD_LEFT)
				);
			}
			else
			{
				$aSQL_Array = array(
					'sLidLinkList'	=> (string) str_pad($nLastId,9,'0',STR_PAD_LEFT)
				);			
			}
			$aEditLog[CLIENT_USER_LINK]['aNew']['sLidLinkList'] = $aSQL_Array['sLidLinkList'];

			$sSQL = '	UPDATE '. CLIENT_USER_LINK .' SET '. sql_build_array('UPDATE', $aSQL_Array).'
					WHERE nId = :nId LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nLastId,PDO::PARAM_INT);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);

			// Fs
			$aPaFsData = $aData['aPaFsData'];

			$aSelfFs = array(
				'sWater'	=> $aPaFsData['sPromoWater'],
				'sPercent'	=> $aPaFsData['sPromoPercent'],
			);

			if(isset( $aPaFsData['sWaterLink']))
			{
				$TMPaFsLink = array(
					'sWaterLink'	=> $aPaFsData['sWaterLink'].','.$aSelfFs['sWater'],
					'sPercentLink'	=> $aPaFsData['sPercentLink'].','.$aSelfFs['sPercent'],
				);
			}
			else
			{
				$TMPaFsLink = array(
					'sWaterLink'	=> $aSelfFs['sWater'],
					'sPercentLink'	=> $aSelfFs['sPercent'],
				);
			}
			self::$aCurrentFs = array($nUid,$aSelfFs,$TMPaFsLink);
			$aSQL_Array = self::waterCalc($TMPaFsLink,$aSelfFs);
			$aSQL_Array['nUid'] = $nUid;
			$aSQL_Array['nCreateTime'] = NOWTIME;
			$aSQL_Array['sCreateTime'] = NOWDATE;

			$aEditLog[CLIENT_USER_FS]['aNew'] = $aSQL_Array;

			$sSQL = 'INSERT INTO '. CLIENT_USER_FS .' '. sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();

			$sSQL = '	SELECT 	nId
					FROM 		client_group_ctrl
					WHERE 	1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRow = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aTemp[$aRow['nId']] = $aRow['nId'];
			}

			foreach($aTemp as $LPnGid)
			{
				$aSQL_Array = array(
					'nGid'		=> $LPnGid,
					'nUid'		=> $nUid,
					'nStatus'		=> 1,
					'nCreateTime'	=> NOWTIME,
					'sCreateTime'	=> NOWDATE,
					'nUpdateTime'	=> NOWTIME,
					'sUpdateTime'	=> NOWDATE,
				);
			
				$sSQL = 'INSERT INTO client_user_group_list ' . sql_build_array('INSERT', $aSQL_Array );
				$Result = $oPdo->prepare($sSQL);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);
			}
			

			// 如果是代理 建manager資料
			if($aData['nIdentity'] == 1)
			{
				$aData['nUid'] = $nUid;
				self::addEndAgent($aData);
			}

			if($aData['nFrom'] == 1)
			{
				// 前台新增
				$nLogCode = 7100301;
				$nWho = $nUid;
			}
			else
			{
				// 後台新增
				$nLogCode = 8103101;
				$nWho = $aData['nAdmin'];
			}
			$aSQL_Array = array(
				'nWho'		=> (int)	$nWho,
				'nWhom'		=> (int)	$nUid,
				'sWhomAccount'	=> (string)	$aData['sName0'],
				'nKid'		=> (int)	$nUid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	$nLogCode,
				'sParam'		=> (string)	json_encode($aEditLog),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aSQL_Array);
		}

		public function getLinkData($nUid)
		{
			global $oPdo;
			$sSQL = '	SELECT 	nId,
							nUid,
							nLevel,
							sLinkList,
							nPa,
							nPaLid,
							sLidLinkList
					FROM 		'. CLIENT_USER_LINK .'
					WHERE 	nUid = :nUid
					AND		nEndTime = 0';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
			sql_query($Result);
			$aData = $Result->fetch(PDO::FETCH_ASSOC);

			return $aData;
		}

		public function getFsData($nUid)
		{
			global $oPdo;
			$sSQL = '	SELECT 	nId,
							nUid,
							sWater,
							sWaterLink,
							sPercent,
							sPercentLink
					FROM 		client_user_fs
					WHERE 	nUid = :nUid
					AND		nEndTime = 0';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
			sql_query($Result);
			$aData = $Result->fetch(PDO::FETCH_ASSOC);

			return $aData;
		}

		public function addEndAgent($aData)
		{
			global $oPdo,$aAdm;

			$aEditLog = array();
			$nAdmType = 3; // 代理

			$aSQL_Array = array(
				'sAccount'		=> (string)	$aData['sAccount'],
				'sPassword'		=> (string)	$aData['sPassword'],
				'nOnline'		=> (int)	1,
				'nAdmType'		=> (int)	$nAdmType,
				'nUid'		=> (int)	$aData['nUid'],
				'sName0'		=> (string)	$aData['sName0'],
				'sIp'			=> (string)	'',
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
				'nUpdateTime'	=> (int)	NOWTIME,
				'sUpdateTime'	=> (string)	NOWDATE,
			);
			$sSQL = 'INSERT INTO '. END_MANAGER_DATA .' ' . sql_build_array('INSERT', $aSQL_Array );
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nUid = $oPdo->lastInsertId();
			$aEditLog[END_MANAGER_DATA]['aNew'] = $aSQL_Array;
			$aEditLog[END_MANAGER_DATA]['aNew']['nId'] = $nUid;


			# sys_google_verify
			$aSQL_Array = array(
				'nUid'		=> (int)	$nUid,
				'sKey'		=> (string)	'',
				'sTable'		=> (string)	END_MANAGER_DATA,
				'nOnline'		=> (int)	0,
				'nStatus'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			// if ($nGoogle == 1) # 啟用
			// {
			// 	$aSQL_Array['sKey'] = $oGg->createSecret();
			// 	$aSQL_Array['nOnline'] = 1;
			// 	$sChangePage = sys_web_encode($aMenuToNo['pages/end_manager_data/php/_end_manager_data_0_upt0.php']).'&nId='.$nLastId;
			// }

			$sSQL = 'INSERT INTO '. SYS_GOOGLE_VERIFY .' ' . sql_build_array('INSERT', $aSQL_Array);
			$Result = $oPdo->prepare($sSQL);
			sql_build_value($Result, $aSQL_Array);
			sql_query($Result);
			$nLastId = $oPdo->lastInsertId();
			$aEditLog[SYS_GOOGLE_VERIFY]['aNew'] = $aSQL_Array;
			$aEditLog[SYS_GOOGLE_VERIFY]['aNew']['nId'] = $nLastId;


			$sSQL = '	SELECT 	sControl
					FROM 		'. END_PERMISSION .'
					WHERE 	nId = :nId';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nId', $nAdmType, PDO::PARAM_INT);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aTempCtrl = explode('|',$aRows['sControl']);
				foreach ($aTempCtrl as $LPsCtrl)
				{
					$LPaTemp = explode('_',$LPsCtrl);
					$aControl[$LPaTemp[0]] = explode(',',$LPaTemp[1]);
				}
			}

			foreach ($aControl as $LPnMkid => $LPaMlid)
			{
				foreach ($LPaMlid as $LPnMlid)
				{
					$aSQL_Array = array(
						'nUid'		=> $nUid,
						'nMkid'		=> $LPnMkid,
						'nMlid'		=> $LPnMlid,
						'nCreateTime'	=> NOWTIME,
						'sCreateTime'	=> NOWDATE,
					);

					$sSQL = 'INSERT INTO '. END_MENU_CTRL .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result = $oPdo->prepare($sSQL);
					sql_build_value($Result, $aSQL_Array);
					sql_query($Result);
					$LPnLastId = $oPdo->lastInsertId();
					$aEditLog[END_MENU_CTRL]['aNew'][$LPnLastId] = $aSQL_Array;
				}
			}

			# 紀錄動作 - 新增
			$aActionLog = array(
				'nWho'		=> (int)	$aAdm['nId'],
				'nWhom'		=> (int)	$nUid,
				'sWhomAccount'	=> (string)	$aData['sName0'],
				'nKid'		=> (int)	$nUid,
				'sIp'			=> (string)	USERIP,
				'nLogCode'		=> (int)	8101101,
				'sParam'		=> (string)	json_encode($aEditLog,JSON_UNESCAPED_UNICODE),
				'nType0'		=> (int)	0,
				'nCreateTime'	=> (int)	NOWTIME,
				'sCreateTime'	=> (string)	NOWDATE,
			);
			DoActionLog($aActionLog);
		}

		static function waterCalc($aFsLink,$aReturn2)
		{
			foreach($aReturn2 as $k => $v)
			{
				$aReturn[$k.'Link'] = $aFsLink[$k.'Link'];

				$aVal = explode(',',$aReturn[$k.'Link']);
				$aVal = $aVal2 = self::waterCalc_Minus($aVal);

				if(count($aVal2) > 2) ## nPa=0的狀況下  [0]是自己 [1]是'' [2]才是空
				{
					$aVal2[1] += $aVal2[0];
				}
				$aVal2[0] = 0;

				$aReturn[$k.'Minus'] = implode(',',$aVal);
				$aReturn[$k.'Minus2'] = implode(',',$aVal2);
				$aReturn[$k] = $v;
			}
			return $aReturn;
		}

		static function waterCalc_Minus($aVal)
		{
			global $oPdo;
			global $bRollBack;
			for($i=0;$i<count($aVal)-1;$i++)
			{
				$aVal[$i] = empty($aVal[$i])?0:$aVal[$i];
				$aVal[$i+1] = empty($aVal[$i+1])?0:$aVal[$i+1];
				$aVal[$i] = $aVal[$i] - $aVal[$i+1];
				if($aVal[$i] < 0)
				{
					trigger_error('返水計算有誤:'.json_encode(self::$aCurrentFs),E_USER_NOTICE);
					$bRollBack = true;
				}
			}
			// unset($aVal[$i]);
			return $aVal;
		}

		function UpdateFs($aNewWater,$aThisLink,$nLogId)
		{
			global $oPdo;

			$aLinkList = explode(',',$aThisLink['sLinkList']);
			$sLinkList = '0';

			for( $i=0; $i<count($aLinkList); $i++)
			{
				$sLinkList .= ','.((int)$aLinkList[$i]);
			}

			$aMember = array();
			$aMemberPa = array();
			$aCalcPack = array(
				'sPercent'	=> '',
				'sWater'	=> ''
			);
			$aCalcLinkPack = array(
				'sPercentLink'	=> '',
				'sWaterLink'	=> ''
			);
			$nNowLevel = 1;
			$sUidList = '0';
			## Top-Down排序的寫法可以自動偵錯
			// echo '<pre>';
			$sSQL = '	SELECT 	Fs_.nUid,
							Fs_.sWater,
							Fs_.sPercent,
							Link_.nLevel,
							Link_.nPa
					FROM   	'. CLIENT_USER_FS .' Fs_,
							'. CLIENT_USER_LINK .' Link_
					WHERE 	Link_.nUid = Fs_.nUid
					AND 		Fs_.nEndTime = 0
					AND 		Link_.nEndTime = 0
					AND 		(Link_.sLinkList LIKE :nUid OR Link_.nUid IN ('.$sLinkList.'))
					ORDER BY 	Link_.nLevel ASC';	## LINK只為了寫LOG用
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nUid',sprintf('%%%09d%%',$aThisLink['nUid']), PDO::PARAM_STR);
			sql_query($Result);
			while ($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$sUidList .= ','.$aRows['nUid'];
				if($nNowLevel != $aRows['nLevel'])	## 如果LEVEL晉升
				{
					# MEMBER晉升為PA  清空原始MEMBER
					$aMemberPa = $aMember;
					$aMember = array();
				}
				$nNowLevel = $aRows['nLevel'];	## 紀錄當前LEVEL

				## LOOP人的Per/Value
				$aCalcPack['sWater'] = $aRows['sWater'];
				$aCalcPack['sPercent'] = $aRows['sPercent'];
				if($aRows['nLevel'] == $aThisLink['nLevel']) ## LOOP到自己時要換新的Per/Value
				{
					$aCalcPack['sWater'] = $aNewWater['sWater'];
					$aCalcPack['sPercent'] = $aNewWater['sPercent'];
				}

				## LOOP人的LINK,預設LVL1的情況
				$aCalcLinkPack['sWaterLink'] = ''.$aCalcPack['sWater'];
				$aCalcLinkPack['sPercentLink'] = ''.$aCalcPack['sPercent'];
				if($aRows['nPa'] != 0)	## 如果有老爸,存老爸的LINK
				{
					if($aMemberPa[$aRows['nPa']]['new']['sWater'] < $aCalcPack['sWater'])
					{
						$aCalcPack['sWater'] = $aMemberPa[$aRows['nPa']]['new']['sWater'];
					}
					if($aMemberPa[$aRows['nPa']]['new']['sPercent'] < $aCalcPack['sPercent'])
					{
						$aCalcPack['sPercent'] = $aMemberPa[$aRows['nPa']]['new']['sPercent'];
					}
					$aCalcLinkPack['sWaterLink'] = $aMemberPa[$aRows['nPa']]['new']['sWaterLink'].','.$aCalcPack['sWater'];
					$aCalcLinkPack['sPercentLink'] = $aMemberPa[$aRows['nPa']]['new']['sPercentLink'].','.$aCalcPack['sPercent'];
				}

				$aMember[$aRows['nUid']]['old'] = $aRows;	## 變更前資料存下來
				if($aRows['nLevel'] >= $aThisLink['nLevel'] && !isset($aMember[$aRows['nUid']]['new']))	## 如果對象是自己或下線 而且 沒有被INSERT過才會有變動值
				{
					if(!isset($aMember[$aRows['nUid']]['new']))		## 先把舊的移除
					{
						$aSQL_Array = array(
							'nEndTime' => NOWTIME,
							'sEndTime' => NOWDATE,
							'nLogId1'  => $nLogId,
						);
						$sSQL = '	UPDATE '. CLIENT_USER_FS .'
								SET '. sql_build_array('UPDATE',$aSQL_Array) .'
								WHERE nUid = :id
								AND nEndTime = 0';
						$Result2 = $oPdo->prepare($sSQL);
						$Result2->bindValue(':id',$aRows['nUid'], PDO::PARAM_INT);
						sql_build_value($Result2, $aSQL_Array);
						sql_query($Result2);
					}
					self::$aCurrentFs = array($aRows['nUid'],$aCalcPack,$aCalcLinkPack);
					$aMember[$aRows['nUid']]['new'] = $aSQL_Array = self::waterCalc($aCalcLinkPack,$aCalcPack);
					$aSQL_Array['nUid'] = $aRows['nUid'];
					$aSQL_Array['nLogId0'] = $nLogId;
					$aSQL_Array['nCreateTime'] = NOWTIME;
					$aSQL_Array['sCreateTime'] = NOWDATE;

					$sSQL = 'INSERT INTO '. CLIENT_USER_FS .' ' . sql_build_array('INSERT', $aSQL_Array );
					$Result2 = $oPdo->prepare($sSQL);
					sql_build_value($Result2, $aSQL_Array);
					sql_query($Result2);
				}
				else
				{
					self::$aCurrentFs = array($aRows['nUid'],$aCalcPack,$aCalcLinkPack);
					$aMember[$aRows['nUid']]['new'] = self::waterCalc($aCalcLinkPack,$aCalcPack);
				}
			}

			####### 關閉推廣連結 ######

			// $sSQL = 'UPDATE ' .CLIENT_PROMO_DATA. '
			// SET nOnline = 0
			// WHERE nUid IN ('.$sUidList.')';
			// $Result = $oPdo->prepare($sSQL);
			// sql_query($Result);
		}

	}
?>