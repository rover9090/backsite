<?php $aData = json_decode($sData,true);?>

<div class="Information MarginBottom10">
	<div class="InformationTit Table">
		<div>
			<div>
				<div class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo INDEXMEMBER;?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aINDEX['TOTALMEMBER'];?></th>
						<th><?php echo aINDEX['TODAYMEMBER'];?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $aData['nTotalUser'];?></td>
						<td><?php echo $aData['nTodayUser'];?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="Information MarginBottom10">
	<div class="InformationTit Table">
		<div>
			<div>
				<div class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo INDEXMONEY;?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aINDEX['RECHARGECOUNT'];?></th>
						<th><?php echo aINDEX['RECHARGEMONEY'];?></th>
						<th><?php echo aINDEX['WITHDRAWALCOUNT'];?></th>
						<th><?php echo aINDEX['WITHDRAWALMONEY'];?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $aData['nRechargeCount'];?></td>
						<td><?php echo $aData['nRechargeMoney'];?></td>
						<td><?php echo $aData['nWithdrawalCount'];?></td>
						<td><?php echo $aData['nWithdrawalMoney'];?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>