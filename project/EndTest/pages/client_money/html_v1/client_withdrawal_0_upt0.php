<?php
	$aData = json_decode($sData,true);
?>

<div class="Information MarginBottom30">
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aWITHDRAWALUPT['ACCOUNT'];?></th>
						<th><?php echo aWITHDRAWALUPT['MONEY'];?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $aData['sAccount'];?></td>
						<td><?php echo $aData['nMoney'];?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="Information MarginBottom30">
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aWITHDRAWALUPT['CARDNUMER'];?></th>
						<th><?php echo aWITHDRAWALUPT['CARDNAME'];?></th>
						<th><?php echo aWITHDRAWALUPT['BANK'];?></th>
						<th><?php echo aWITHDRAWALUPT['BANKBRANCH'];?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $aData['sCardNumber'];?></td>
						<td><?php echo $aData['sCardName'];?></td>
						<td><?php echo $aData['sBankName'];?></td>
						<td><?php echo $aData['sBranchName'];?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nId" value="<?php echo $nId;?>">
	<div class="Information ">
		<div class="InformationScroll">
			<div class="InformationTableBox">
				<table>
					<thead>
						<tr>
							<th><?php echo aWITHDRAWALUPT['AMOUNT'];?></th>
							<th><?php echo aWITHDRAWALUPT['PAYMENT'];?></th>
							<th><?php echo aWITHDRAWALUPT['MEMO'];?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $aMoney['nMoney'];?></td>
							<td>
								<div class="Sel">
									<select name="nPid">
										<option value="-1" disabled selected><?php echo PLEASESELECT;?></option>
										<?php
											foreach($aPayment as $LPnId => $LPaDetail)
											{
										?>
												<option value="<?php echo $LPnId;?>" ><?php echo $LPaDetail['sName0'];?></option>
										<?php
											}
										?>
									</select>
								</div>
							</td>
							<td>
								<div class="Ipt">
									<input type="text" name="sMemo">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- 操作選項 -->
	<div class="EditBtnBox">

		<div class="EditBtn JqStupidOut" data-showctrl="0">
			<i class="far fa-save"></i>
			<span><?php echo CSUBMIT;?></span>
		</div>

		<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
			<i class="fas fa-times"></i>
			<span><?php echo CBACK;?></span>
		</a>
	</div>
</form>

