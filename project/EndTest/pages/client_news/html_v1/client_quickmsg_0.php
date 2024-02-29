<?php $aData = json_decode($sData,true);?>
<!-- 純顯示資訊 -->
<form method="POST" action="<?php echo $aUrl['sAct'];?>" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>">
	<div class="MarginBottom20">
		<!-- <div class="Ipt">
			<input type="text" name="sName0" value="" placeholder="<?php echo '新增訊息標題';?>">
		</div>
		<div class="Ipt">
			<input type="text" name="sMessage" value="" placeholder="<?php echo '訊息內容';?>">
		</div> -->
		<div class="BtnAny JqStupidOut" data-showctrl="0"><?php echo CSUBMIT;?></div>
	</div>
	<div class="Information">
		<table class="InformationTit">
			<tbody>
				<tr>
					<td class="InformationTitCell" style="width:calc(100%/1);">
						<div class="InformationName"><?php echo $sHeadTitle; ?></div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="InformationScroll">
			<div class="InformationTableBox">
				<table>
					<thead>
						<tr>
							<th><?php echo '快捷按鈕';?></th>
							<!-- <th><?php echo '訊息標題';?></th> -->
							<th><?php echo '訊息內容';?></th>
							<!-- <th><?php echo '狀態';?></th> -->
							<th><?php echo '最後修改時間';?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($aData as $LPsName => $LPaData)
						{
						?>
							<tr>
									<td><?php echo '按鈕'.$LPaData['nId'];?></td>
									<!-- <td>
										<div class="Ipt">
											<input type="text" name="aParam[<?php echo $LPaData['nId'];?>][sName0]" value="<?php echo $LPaData['sName0'];?>">
										</div>
									</td> -->
									<td>
										<div class="Ipt">
											<input type="text" name="aParam[<?php echo $LPaData['nId'];?>][sMessage]" value="<?php echo $LPaData['sMessage'];?>">
										</div>
									</td>
									<!-- <td>
										<div class="Sel">
											<select name="aParam[<?php echo $LPaData['nId'];?>][nOnline]">
												<option value="0" class="<?php echo $LPaData['aOnlineSelect'][0]['sClass'];?>" <?php echo $LPaData['aOnlineSelect'][0]['sSelect'];?>><?php echo $aOnline['0']['sText'];?></option>
												<option value="1" class="<?php echo $LPaData['aOnlineSelect'][1]['sClass'];?>" <?php echo $LPaData['aOnlineSelect'][1]['sSelect'];?>><?php echo $aOnline['1']['sText'];?></option>
												<option value="99" class="<?php echo $LPaData['aOnlineSelect'][99]['sClass'];?>" <?php echo $LPaData['aOnlineSelect'][99]['sSelect'];?>><?php echo $aOnline['99']['sText'];?></option>
											</select>
										</div>
									</td> -->
									<td><?php echo $LPaData['sUpdateTime'];?></td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</form>