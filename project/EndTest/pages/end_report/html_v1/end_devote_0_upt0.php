<?php
	$aData = json_decode($sData,true);
?>
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
						<th><?php echo ACCOUNT;?></th>
						<th><?php echo NAME;?></th>
						<th><?php echo aDEVOTE['BETMONEY'];?></th>
						<th><?php echo aDEVOTE['SCALE'];?></th>
						<th><?php echo aDEVOTE['DEVOTE'];?></th>
						<th><?php echo CREATETIME;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($aData as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td><?php echo $LPaDetail['sAccount'];?></td>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $LPaDetail['nBetMoney'];?></td>
							<td><?php echo ($LPaDetail['nPercent']*100).'%';?></td>
							<td><?php echo $LPaDetail['nMoney'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- 操作選項 -->
<div class="EditBtnBox">
	<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
		<span><?php echo BACK;?></span>
	</a>
</div>