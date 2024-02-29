<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div class="MarginBottom10">
		<?php
		foreach ($aDay as $LPsText => $LPaDate)
		{
			?>
			<span class="JqDate BtnKind <?php echo $LPaDate['sSelect'];?>" data-day="<?php echo $LPsText;?>" data-date0="<?php echo $LPaDate['sStartDay']?>" data-date1="<?php echo $LPaDate['sEndDay']?>">
				<?php echo aDAYTEXT[$LPsText];?>
			</span>
			<?php
		}
		?>
		<input type="hidden" name="sSelDay" value="<?php echo $sSelDay;?>">
	</div>
	<div class="MarginBottom10">
		<div class="Ipt">
			<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>">
		</div>
		<span>~</span>
		<div class="Ipt">
			<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>">
		</div>
	</div>
	<div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 新增按鈕 -->
<div class="Block MarginBottom10">
	<input type="submit" data-url="<?php echo $aUrl['sDo'].'&sJWT='.$sDo_Jwt;?>" class="BtnAdd JqDoBtn" value="<?php echo '結算';?>"></a>
</div>

<!-- 純顯示資訊 -->
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
						<th><?php echo aDEVOTE['BETMONEY_ALL'];?></th>
						<th><?php echo aDEVOTE['DEVOTE_PER'];?></th>
						<th><?php echo aDEVOTE['DEVOTE_ALL'];?></th>
						<th><?php echo STARTTIME;?></th>
						<th><?php echo ENDTIME;?></th>
						<th><?php echo OPERATE;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($aData as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td><?php echo $LPaDetail['nBetMoney'];?></td>
							<td><?php echo $LPaDetail['nDevote'].'%';?></td>
							<td><?php echo $LPaDetail['nMoney'];?></td>
							<td><?php echo $LPaDetail['sStartTime'];?></td>
							<td><?php echo $LPaDetail['sEndTime'];?></td>
							<td>
								<a href="<?php echo $LPaDetail['sDetail'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
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