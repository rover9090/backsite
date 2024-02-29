<div class="WindowBox Small JqWindowBox" data-kind="8">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit"><?php echo aCENTER['LANGSET'];?></div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="8">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="langBox">
					<div class="Sel">
						<select name="lang" onchange="location=this.value;">
						<?php
							foreach(aLANG as $LPsLang => $LPsName)
							{
								$sLangUrl = sys_web_encode($aMenuToNo['pages/index/php/_index_0.php']).'&sLang='.$LPsLang;
						?>
							<option value="<?php echo $sLangUrl;?>" <?php echo ($aSystem['sLang'] == $LPsLang)?'selected':'';?>><?php echo $LPsName;?></option>
						<?php
							}
						?>
						</select>
						<div class="SelDecro"></div>
					</div>
				</div>
				<?php if(false){?>
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="submit" class="submitBtnTxt" value="<?php echo aCENTER['CONFIRM'];?>">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php }?>
			</form>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>