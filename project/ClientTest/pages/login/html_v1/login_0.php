<div class="applyBox">
	<div class="reserveFormBox">
		<div class="BackBtnBox">
			<a class="BackBtn" href="<?php echo $aUrl['sPage'];?>">
				<div class="BackBtnTxt">
					<span class="BackBtnTxtIcon"><<</span>
					<span class="BackBtnTxtWord"><?php echo GOBACK;?></span>
				</div>
			</a>
		</div>
		<div class="activityDetailTit WordBreakBreakAll"><?php echo $aData['sName0'];?>
		</div>
		<div class="activityListInfBox WordBreakBreakAll">
			<div class="activityListInf">
				<span class="activityListInfTit"><?php echo aACTIVITY['ACTIVITY_TIME'];?> :</span>
			</div>
			<div class="activityListInf">
				<span class="activityListInfTxt"><?php echo $aData['sStartTime'].' ~ '.$aData['sEndTime'];?></span>
			</div>
		</div>
		<form action="<?php echo $aUrl['sAct'];?>" method="POST" data-form="0">
			<input type="hidden" name="nId" value="<?php echo $nId;?>">
			<input type="hidden" name="sJWT" class="" value="<?php echo $sJWT;?>">
			<table class="FormTable">
				<tbody>
					<tr>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['NAME'];?></div>
						</td>
						<td class="FormTdCell2">
							<div class="Ipt">
								<input type="text" name="sRealName" value="<?php echo $aUserData['sRealName'];?>">
							</div>
						</td>
					</tr>
					<tr>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['PHONE'];?></div>
						</td>
						<td class="FormTdCell2">
							<div class="Ipt">
								<input type="number" name="sPhone" value="<?php echo $aUserData['sPhone'];?>">
							</div>
						</td>
					</tr>
					<?php
					if($aData['nType0'] === '0' && $aData['nPrice'] === '0')
					{
						echo '<input type="hidden" name="nType0" value="1">';
					}
					else
					{
					?>
					<tr>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['TYPE0'];?></div>
						</td>
						<td class="FormTdCell2">
							<div class="Sel">
								<select name="nType0">
									<option value="-1"><?php echo PLEASESELECT;?></option>
									<option value="0"><?php echo aACTIVITY_FROM['ONLINE_PAY'];?></option>
									<option value="1"><?php echo aACTIVITY_FROM['SPOT_PAY'];?></option>
								</select>
								<div class="SelDecro"></div>
							</div>
						</td>
					</tr>
					<?php 
					}
					if($aData['nType0'] === '1')
					{
					?>
					<tr>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['MONEY0'];?></div>
						</td>
						<td class="FormTdCell2">
							<div class="Ipt">
								<input type="number" name="nMoney" value="">
							</div>
						</td>
					</tr>
					<?php 
					}
					else
					{
						if($aData['nPrice'] > 0)
						{
							echo '<input type="hidden" name="nMoney" value="'.$aData['nPrice'].'">';
						}
					?>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['PRICE0'];?></div>
						</td>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo $aData['nPrice'];?>元</div>
						</td>
					<?php	
					}
					?>
					<tr>
						<td class="FormTdCell1">
							<div class="FormTit"><?php echo aACTIVITY_FROM['MEMO'];?></div>
						</td>
						<td class="FormTdCell2">
							<div class="Ipt">
								<input type="text" name="sMemo" value="">
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="WindowBtnBox">
				<table class="WindowBtnTable">
					<tbody>
						<tr>
							<td class="WindowBtnTd">
								<div class="WindowBtn">
									<input type="button" class="BtnAny JqStupidOut" value="<?php echo SUBMIT;?>" data-showctrl="0">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</form>
	</div>
</div>