<?php
	$aData = json_decode($sData,true);
?>
<?php
	if($nId != 0)
	{
?>
		<div class="Information MarginBottom30">
			<div class="InformationScroll">
				<div class="InformationTableBox">
					<table>
						<thead>
							<tr>
								<th><?php echo ACCOUNT;?></th>
								<th><?php echo aUSER['MONEY'];?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $aAdmData['sName0'];?></td>
								<td><?php echo $aAdmData['nMoney'];?></td>
							</tr>
							<tr>
								<td><?php echo $aData['sName0'];?></td>
								<td><?php echo $aData['nMoney'];?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!-- 編輯頁面 -->
		<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
			<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
			<div class="Information ">
				<div class="InformationScroll">
					<div class="InformationTableBox">
						<table>
							<thead>
								<tr>
									<th><?php echo aUSER['MONEY'];?></th>
									<th><?php echo aUSER['TYPE3'];?></th>
									<th><?php echo aUSER['MEMO'];?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<div class="Ipt">
											<input type="number" name="nMoney">
										</div>
									</td>
									<td>
										<div class="Sel">
											<select name="nType3">
												<option value="-1" disabled selected><?php echo PLEASESELECT;?></option>
												<option value="1" ><?php echo aUSER['TRANSFERIN'];?></option>
												<option value="2" ><?php echo aUSER['TRANSFEROUT'];?></option>
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
<?php
	}
?>

