<?php
	$sMarquee = '';
	$sVideoOpen = '';
	$sVideoClose = '';
	$aJackpotMoney = array(
		'nMoney2' => 0,
	);
	$sSQL = '	SELECT 	nId,
					sName0
			FROM 		'.CLIENT_BROADCAST.'
			WHERE 	nOnline = 1
			AND		nKid = 17
			AND		sLang = :sLang
			ORDER BY nSort DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'],PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sMarquee .= $aRows['sName0'].' ';
	}

	$sSQL = '	SELECT 	nMoney2
			FROM 		'.CLIENT_JACKPOT_SET.'
			WHERE 	nOnline = 1
			AND		nGame = :nGame
			LIMIT 1';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':nGame', $nGame,PDO::PARAM_INT);
	sql_query($Result);
	$aJackpotMoney = $Result->fetch(PDO::FETCH_ASSOC);

	if($aUser['nVideoOpen'] == '0')
	{
		$sVideoClose = 'active';
	}
	else
	{
		$sVideoOpen = 'active';
	}
?>
<div class="userinfobox JqHeader">
	<div class="userinfo font-xxs" cellpadding="0" cellspacing="0" width="100%">
		<div class="imgbox">
			<img class="rwd-img imgtest" src="<?php echo $aUser['sPicture'];?>?t=<?php echo VTIME;?>">
		</div>
		<div class="userInfCell userInfCell1">
			<div class="userInfCellInner" id="username">
				<div class="userInfCellMain">
					<div class="userInfCellLeft">
						<span class="scale08"><?php #echo aGlobalText['Account']; ?>帳號</span>
					</div>
					<div class="userInfCellRight">
						<span class="JqMyName0">
							<?php
							// if(!empty($aShowMember['sUserName']))
							// {
							// 	echo $aShowMember['sUserName'];
							// }
							// else
							// {
							// 	echo $aShowMember['sAccount'];
							// }
							echo $aUser['sName0'];
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="userInfCell userInfCell2">
			<div class="userInfCellInner">
				<div class="userInfCellMain">
					<div class="userInfCellLeft">
						<span class="scale08"><?php #echo aGlobalText['Money']; ?>錢包</span>
					</div>
					<div class="userInfCellRight">
						<span><span id="nMoney" class="JqMyMoney0" data-dls="true"><?php echo (float)$aUser['nMoney'];?></span><span><?php #echo $sSysVNK;?></span></span>
					</div>
				</div>
			</div>
		</div>
		<div class="headerBtnMenu JqMenuBtn" data-btn="buysticker">
			<div class="headerBtnMenuInner">購買</div>
		</div>
	</div>
	<!-- 視訊 -->
	<?php
	if($aUser['nUid'] == $aSystem['aParam']['nClientDealerId']) # 荷官獨立出來不會被聊天室干擾
	{
	?>
		<div class="mainMenuBtn JqVideoBtn" style="display: none;">
			<div class="mainMenuBtnInner">
				<div class="JqVideoBtnOpen mainMenuBtnText DisplayBlockNone active">開</div>
				<div class="JqVideoBtnClose mainMenuBtnText DisplayBlockNone">關</div>
			</div>
		</div>
	<?php
	}
	?>
	<?php
	if($aUser['nUid'] != $aSystem['aParam']['nClientDealerId'])
	{
	?>
	<div class="mainVideoOtherBox" >
		
		<!-- 暫時顯示之圖片 -->
		<?php 
		if(isset($_COOKIE['gino']))
		{
		?>
		<div class="mainVideoTmp" style="overflow: auto;">
			<div style="height:3000px;width:100%"></div>
			<!-- <img src="images/videoTmp.png" alt=""> -->
		</div>
		<?php }else{ ?>
		<div class="mainVideoTmp">
			<!-- <img src="images/videoTmp.png" alt=""> -->
		</div>
		<?php }?>
		<div id="video-player" class="mainVideoBox JqVideoBox"  playsinline="">
			<!-- ***********視頻放至此*********** -->
		</div>
		<div class="JqmainMenuBox mainMenuBox2">
		<?php
			foreach($aMenu as $LPaData)
			{
				if($LPaData['sClass'] === 'donate') {
					?>
					<div class="mainMenuBtn JqMenuBtn" id="<?php echo $LPaData['sId']; ?>" data-clk="grp" data-tg="<?php echo $LPaData['sDataTg']; ?>" data-ajax="<?php echo $LPaData['sAjax']; ?>" data-btn="<?php echo $LPaData['sClass']; ?>">
							<div class="mainMenuBtnInner <?php echo $LPaData['sClass']; ?>">
								<?php
								if($LPaData['sText'] != '')
								{
									echo '<div class="mainMenuBtnText">'.$LPaData['sText'].'</div>';
								}
								if($LPaData['sIcon'] != '')
								{
									echo $LPaData['sIcon'];
								}
								?>
							</div>
						</div>
					<?php
						}
				if($LPaData['sClass'] === 'history') {
			?>
				<div class="mainMenuBtn JqMenuBtn JqNumHistory" id="<?php echo $LPaData['sId']; ?>" data-clk="grp" data-tg="<?php echo $LPaData['sDataTg']; ?>" data-ajax="<?php echo $LPaData['sAjax']; ?>" data-btn="<?php echo $LPaData['sClass']; ?>">
					<div class="mainMenuBtnInner <?php echo $LPaData['sClass']; ?>">
						<?php
						if($LPaData['sText'] != '')
						{
							echo '<div class="mainMenuBtnText">'.$LPaData['sText'].'</div>';
						}
						if($LPaData['sIcon'] != '')
						{
							echo $LPaData['sIcon'];
						}
						?>
					</div>
				</div>
			<?php
				}
			}
			?>
		</div>
		<div class="JqmainMenuBox mainMenuBox">
			<?php
			foreach($aMenu as $LPaData)
			{
				if($LPaData['sClass'] === 'donate' || $LPaData['sClass'] === 'history') continue;
			?>
				<div class="mainMenuBtn JqMenuBtn" id="<?php echo $LPaData['sId']; ?>" data-clk="grp" data-tg="<?php echo $LPaData['sDataTg']; ?>" data-ajax="<?php echo $LPaData['sAjax']; ?>" data-btn="<?php echo $LPaData['sClass']; ?>">
					<div class="mainMenuBtnInner <?php echo $LPaData['sClass']; ?>">
						<?php
						if($LPaData['sText'] != '')
						{
							echo '<div class="mainMenuBtnText">'.$LPaData['sText'].'</div>';
						}
						if($LPaData['sIcon'] != '')
						{
							echo $LPaData['sIcon'];
						}
						?>
					</div>
				</div>
			<?php
			}
			?>
			<div class="mainMenuBtn JqVideoBtn <?php echo $sVideoOpen;?>">
				<div class="mainMenuBtnInner">
					<div class="JqVideoBtnOpen mainMenuBtnText DisplayBlockNone <?php echo $sVideoOpen;?>">開</div>
					<div class="JqVideoBtnClose mainMenuBtnText DisplayBlockNone <?php echo $sVideoClose;?>">關</div>
				</div>
			</div>
			<div class="mainMenuBtn">
				<div class="mainMenuBtnInner JqRefreshSite">
					<i class="fas fa-redo"></i>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	?>
	<div class="nowInfBox JqMarquee">
		<div class="font-xxs padding-tb-5 nowInfBlock Opening" style="display: none;">
			<div class="nowInfLeft">
				前期正在開獎或休盤中，距離
				<span class="prize_no"></span>
				期開放投注時間
				<span id="o_mm">00</span>
				分
				<span id="o_ss">00</span>
				秒
			</div>                  
			<?php
				// $sTimeLeftText = str_replace('[::No::]', '<span class="text-red prize_no"></span>', aGlobalTip['Open&TimeLeft']);
				// $sTimeLeftText = str_replace('[::Min::]', '<span class="text-red" id="o_mm">00</span>', $sTimeLeftText);
				// $sTimeLeftText = str_replace('[::Sec::]', '<span class="text-red" id="o_ss">00</span>', $sTimeLeftText);
				// echo $sTimeLeftText;
			?>
		</div>
		<div class="font-xxs padding-tb-5 nowInfBlock">
			<div class="nowInfLeft">
				第
				<span class="nowInfPeriod nowhno JqNo" data-auto-renew="true" id="experiod"></span>
				期,
				本週彩金
				<span class="JqJackpotMoney">0</span>
				,
				總累計彩金
				<span class="JqJackpotMoney2"><?php echo $aJackpotMoney['nMoney2'];?></span>
				<?php if(false){?>
				<!-- 距離
				<span class="nowInfPeriod nowhno JqNo" data-auto-renew="true" id="experiod"></span>
				期結束
				<span class="nowInfTime" id="d_mm">00</span>
				分
				<span class="nowInfTime JqClock_<?php echo $nGame;?>" id="d_ss">00</span>
				秒 -->
				<?php }?>
			</div>
			<?php
			if(false){
			?>
			<div class="nowInfRight">
				<span class="nowInfNowTime JqCurrentTime" id="nowtime">12/24 17:08:04</span>
			</div>   
			<?php
			}
			?>               
			<?php
				// $sTimeLeftText = str_replace('[::No::]', '<span class="nowInfPeriod nowhno" data-auto-renew="true" id="experiod"></span>', aGlobalTip['TimeLeft']);
				// $sTimeLeftText = str_replace('[::Min::]', '<span class="nowInfTime" id="d_mm">00</span>', $sTimeLeftText);
				// $sTimeLeftText = str_replace('[::Sec::]', '<span class="nowInfTime" id="d_ss">00</span>', $sTimeLeftText);
				// echo $sTimeLeftText;
			?>
		</div>
	</div>
</div>