<div class="WindowBox Small JqWindowBox" data-kind="4">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit"><?php echo aCENTER['CHGNICKNAME'];?></div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="4">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="changeNameBox">
					<div class="centerInfoBlock">
						<div class="centerInfoTit"><?php echo aCENTER['NICKNAME'];?></div>
						<div class="centerInfoTxtBox">
							<div class="Ipt">
								<input type="text" class="JqUpdateUserName0" placeholder="<?php echo aCENTER['PUTNICKNAME'];?>">  
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" class="JqUpdateUserJWT" data-url="<?php echo $aSetUrl['sUpdateUser'];?>" value="<?php echo $sUpdateUser;?>">
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqUpdateUser" value="<?php echo aCENTER['CONFIRM'];?>">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>