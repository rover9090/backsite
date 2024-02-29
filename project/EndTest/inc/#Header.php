<?php
	$aLogoutValue = array(
		'a'   => 'LOGOUT',
		't'   => NOWTIME,
		'sAccount' => $aAdm['sAccount'],
	);
	$sLogOut = sys_jwt_encode($aLogoutValue);
	$sLogOutUrl = sys_web_encode($aMenuToNo['pages/login/php/_login_0_act0.php']).'&run_page=1&sJWT='.$sLogOut;
	$sPasswordUrl = sys_web_encode($aMenuToNo['pages/end_manager_data/php/_end_manager_password_0.php']);

	$sSoundUrl = sys_web_encode($aMenuToNo['pages/tool/php/_sound_0_act0.php']).'&run_page=1';
	$aSoundValue = array(
		'a'   => 'SOUND',
		't'   => NOWTIME,
	);
	$sSoundJWT = sys_jwt_encode($aSoundValue);

	$sRechargeCheck = '';
	$sWithdrawalCheck = '';
	$sServiceCheck = '';
	$sSoundClass = '';

	if( isset($_COOKIE['soundRecharge']) && $_COOKIE['soundRecharge'] == 1)
	{
		$sRechargeCheck = 'checked';
	}

	if( isset($_COOKIE['soundWithdrawal']) && $_COOKIE['soundWithdrawal'] == 1)
	{
		$sWithdrawalCheck = 'checked';
	}

	if( isset($_COOKIE['soundService']) && $_COOKIE['soundService'] == 1)
	{
		$sServiceCheck = 'checked';
	}

	if( isset($_COOKIE['soundCtrl']) && $_COOKIE['soundCtrl'] == 1)
	{
		$sSoundClass = 'active';
	}
?>
<header class="JqHeader">
	<div class="headerContainer">
		<input type="hidden" name="sSoundUrl" value="<?php echo $sSoundUrl;?>">
		<input type="hidden" name="sSoundJWT" value="<?php echo $sSoundJWT;?>">

		<div class="headerBtnNav JqHeaderBtnNav">
			<i class="fas fa-bars"></i>
		</div>

		<div class="headerPageTit"><?php echo $sHeadTitle;?></div>

		<div class="headerCtrlBtn JqHeaderCtrlBtn"><i class="fas fa-sliders-h"></i></div>

		<div class="headerCtrlBox JqHeaderCtrlBox">
			<div class="headerCtrlContainer">
				<div class="headerCtrlBtnClose JqHeaderCtrlBtnClose"><i class="fas fa-times"></i></div>
				<!-- 聲音 -->
				<!-- 有聲音就 headerCircle + active -->
				<?php if(false){?>
				<div class="headerCircle JqSoundCtrl <?php echo $sSoundClass;?>">
					<i class="fas fa-volume-up"></i>
					<i class="fas fa-volume-mute"></i>
				</div>

				<div class="headerSwitch">
					<audio id="soundRecharge" data-tar="Recharge">
						<source src="inc/sound/recharge.mp3" type="audio/mpeg"></audio>
					<audio id="soundWithdrawal" data-tar="Withdrawal">
						<source src="inc/sound/withdrawal.mp3" type="audio/mpeg"></audio>
					<audio id="soundService" data-tar="Service">
						<source src="inc/sound/service.mp3" type="audio/mpeg"></audio>
					<!-- 要ㄎㄠ開關從這ㄍlabel開始 -->
					<label class="SwitchHorizontal">
						<input type="checkbox" name="soundRecharge" <?php echo $sRechargeCheck;?> data-tar="Recharge">
						<div class="SwitchHorizontalSlider">
							<div class="SwitchHorizontalTxt JqSoundOptionCtrl"><?php echo MONEYIN;?></div>
						</div>
					</label>
					<label class="SwitchHorizontal">
						<input type="checkbox" name="soundWithdrawal" <?php echo $sWithdrawalCheck;?> data-tar="Withdrawal">
						<div class="SwitchHorizontalSlider">
							<div class="SwitchHorizontalTxt JqSoundOptionCtrl"><?php echo MONEYOUT;?></div>
						</div>
					</label>
					<label class="SwitchHorizontal">
						<input type="checkbox" name="soundService" <?php echo $sServiceCheck;?> data-tar="Service">
						<div class="SwitchHorizontalSlider">
							<div class="SwitchHorizontalTxt JqSoundOptionCtrl"><?php echo SERVICE;?></div>
						</div>
					</label>
				</div>
				<?php }?>
				<div class="headerLangBox MarginRight10">
					<div class="Sel">
						<select name="sLang" id="JqLang">
							<?php
							foreach (aLANG as $LPsLang => $LPsLangName)
							{
								?>
								<option <?php echo ($aSystem['sLang'] == $LPsLang)? 'selected' :'';?> value="<?php echo $LPsLang?>"><?php echo $LPsLangName;?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<?php if(isset($aAdm['nMoney'])){?>
				<div class="headerLangBox MarginRight10">
					<div class="InlineBlockTit"><?php echo MONEY;?></div>
					<div class="BlockTxt"><?php echo $aAdm['nMoney'];?></div>
				</div>
				<?php }?>
			</div>
			<div class="headerCtrlBg JqHeaderCtrlBg"></div>
		</div>		

		<div class="headerInf JqBtnToggleClass JqCommandList">
			<div class="headerInfName">
				<i class="fas fa-user MarginRight5"></i>
				<span><?php echo $aAdm['sAccount'];?></span>
			</div>
			<div class="headerInfMenu JqCommandListCtrl">
				<div class="headerInfMenuList">
					<a href="<?php echo $sPasswordUrl;?>"><?php echo HEADERPASSWORD;?></a>
				</div>
				<div class="headerInfMenuList">
					<a href="<?php echo $sLogOutUrl;?>"><?php echo HEADERLOGOUT?></a>
				</div>
			</div>
		</div>
	</div>
</header>