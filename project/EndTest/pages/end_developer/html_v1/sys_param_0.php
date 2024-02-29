<?php $aData = json_decode($sData,true);?>
<!-- 純顯示資訊 -->
<form method="POST" action="<?php echo $aUrl['sAct'];?>" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>">
	<div class="MarginBottom20">
		<div class="Ipt">
			<input type="text" name="sName0" value="" placeholder="<?php echo '新增參數名稱';?>">
		</div>
		<div class="Ipt">
			<input type="text" name="sParam" value="" placeholder="<?php echo '參數值';?>">
		</div>
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
							<th><?php echo NO;?></th>
							<th><?php echo '參數名稱';?></th>
							<th><?php echo '設定值';?></th>
							<th><?php echo '最後修改時間';?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($aData as $LPsName => $LPaData)
						{
						?>
							<tr>
								<?php
								if (strpos($LPsName, 'TITLE') !== false)
								{
								echo '<td colspan="4" class="BgBlue FontWhite">'.$LPaData['sName1'].'</td>';
								}
								else
								{
								?>
									<td><?php echo $LPaData['nId'];?></td>
									<td><?php echo $LPaData['sName1'];?></td>
									<td>
										<div class="Ipt">
											<input type="text" name="aParam[<?php echo $LPaData['sName0'];?>]" value="<?php echo $LPaData['sParam'];?>">
										</div>
									</td>
									<td><?php echo $LPaData['sUpdateTime'];?></td>
								<?php
								}
								?>
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