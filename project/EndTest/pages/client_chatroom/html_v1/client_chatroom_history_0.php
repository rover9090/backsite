<?php $aData = json_decode($sData,true);?>

<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo CREATETIME;?></span>
			<div class="Ipt">
				<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>">
			</div>
			<span>~</span>
			<div class="Ipt">
				<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aHISTORY['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sAccount" placeholder="<?php echo aHISTORY['ACCOUNT'];?>" value="<?php echo $sAccount;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aHISTORY['MESSAGE'];?></span>
			<div class="Ipt">
				<input type="text" name="sText" placeholder="<?php echo aHISTORY['MESSAGE'];?>" value="<?php echo $sText;?>">
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>

<!-- 純顯示資訊 -->
<div class="Information">
	<input type="hidden" name="sPageTitle" id="sPageTitle" value="<?php echo $sHeadTitle;?>">
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
						<th><?php echo aHISTORY['ACCOUNT'];?></th>
						<th><?php echo aHISTORY['LASTCHAT'];?></th>
						<th><?php echo aHISTORY['UPDATETIME'];;?></th>
						<th><?php echo aHISTORY['OPERATE'];;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPnId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td><?php echo $LPaData['sAccount'];?></td>
							<td><?php echo $LPaData['sLastChat'];?></td>
							<td><?php echo $LPaData['sUpdateTime'];?></td>
							<td>
								<a href="<?php echo $LPaData['sUptUrl'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
								<?php
								/*
								<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaData['sDelUrl'];?>">
									<i class="fas fa-times"></i>
								</div>
								*/
								?>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>
