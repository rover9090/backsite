<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
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
	<div>
		<div class="Ipt">
			<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>">
		</div>
		<span>~</span>
		<div class="Ipt">
			<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>">
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aREPORT['NAME0'];?>
		</div>
		<div class="Ipt">
			<input type="text" name="sAccount" value="<?php echo $sAccount;?>">
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>

<!-- Copy -->
<div class="Information MarginBottom20">
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
						<th colspan="3"><?php echo aREPORT['MEMBERDATA'];?></th>
						<!-- <th colspan="2"><?php echo aREPORT['MONEY'];?></th> -->
						<th colspan="1"><?php echo aREPORT['RESULT'];?></th>
						<th colspan="3"><?php echo aREPORT['MEMBERCOUNT'];?></th>
						<th colspan="4"><?php echo aREPORT['PAY'];?></th>
					</tr>
					<tr>
						<th><?php echo aREPORT['LEVEL'];?></th>
						<th><?php echo aREPORT['IDENTITY'];?></th>
						<th><?php echo aREPORT['NAME0'];?></th>
						<!-- <th><?php echo aREPORT['PERCENT'];?></th> -->
						<!-- <th><?php echo aREPORT['WATER'];?></th> -->
						<!-- <th><?php echo aREPORT['DEPOSIT'];?></th> -->
						<!-- <th><?php echo aREPORT['WITHDRAWL'];?></th> -->
						<!-- <th><?php echo aREPORT['COUNT'];?></th> -->
						<!-- <th><?php echo aREPORT['REALMONEY'];?></th> -->
						<!-- <th><?php echo aREPORT['WINMONEY'];?></th> -->
						<!-- <th><?php echo aREPORT['WINMONEY1'];?></th> -->
						<th><?php echo aREPORT['REALRESULT'];?></th>
						<th><?php echo aREPORT['LOTTERYMONEY'];?></th>
						<th><?php echo aREPORT['PROXYWATER'];?></th>
						<th><?php echo aREPORT['BETTINGWATER'];?></th>
						<!-- <th><?php echo aREPORT['PROFIT'];?></th> -->
						<!-- <th><?php echo aREPORT['WATERFEE'];?></th> -->
						<th><?php echo aREPORT['DONATE'];?></th>
						<th><?php echo aREPORT['MALL'];?></th>
						<th><?php echo aREPORT['FEE'];?></th>
						<th><?php echo aREPORT['BANKERPLUSFEE'];?></th>
						<!-- <th><?php echo aREPORT['REALPERCENT'];?></th> -->
						<!-- <th><?php echo aREPORT['PROXYPERCENT'];?></th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPnUid => $LPaData)
					{
					?>
						<tr>
							<td><?php echo $LPaData['nLevel'];?></td>
							<td><?php echo $aIdentity[$LPaData['nIdentity']];?></td>
							<td><?php echo $LPaData['sAccount'];?></td>
							<!-- <td><?php echo $LPaData['nPer'];?></td> -->
							<!-- <td><?php echo $LPaData['nFs'];?></td> -->
							<!-- <td><?php echo $LPaData['nDeposit'];?></td> -->
							<!-- <td><?php echo $LPaData['nWithdrawl'];?></td> -->
							<!-- <td><?php echo $LPaData['nBetCount'];?> / <?php echo $LPaData['nBetPeople'];?></td> -->
							<!-- <td><?php echo round($LPaData['nRealWin'],3);?></td> -->
							<!-- <td><?php echo round($LPaData['nBetWin'],3);?></td> -->
							<!-- <td><?php echo round($LPaData['nBetWin1'],3);?></td> -->
							<td data-test="<?php echo $LPaData['nBetWin1'].','.$LPaData['nRealWin'].','.$LPaData['nDonate'].','.$LPaData['nMall'].','.$LPaData['nFee'].','.$LPaData['nBankerPlusFee'].','.$LPaData['nAgentFs'].','.$LPaData['nBetFs'];?>"><?php echo round($LPaData['nBetWin1']-$LPaData['nRealWin']+$LPaData['nDonate']+$LPaData['nMall']+$LPaData['nFee']+$LPaData['nBankerPlusFee']-$LPaData['nAgentFs']-$LPaData['nBetFs'],3);?></td>
							<td><?php echo round($LPaData['nBetMoney'],3);?></td>
							<td><?php echo ($LPaData['nIdentity'] == 1)?round($LPaData['nAgentFs'],3):'';?></td>
							<td><?php echo ($LPaData['nIdentity'] == 0)?round($LPaData['nBetFs'],3):'';?></td>
							<!-- <td><?php echo round($LPaData['nBetProfit'],3);?></td> -->
							<!-- <td><?php echo round($LPaData['nWaterFee'],3);?></td> -->
							<td><?php echo $LPaData['nDonate'];?></td>
							<td><?php echo $LPaData['nMall'];?></td>
							<td><?php echo $LPaData['nFee'];?></td>
							<td><?php echo $LPaData['nBankerPlusFee'];?></td>
							<!-- <td><?php echo $LPaData['nPerXProfit'];?></td> -->
							<!-- <td><?php echo $LPaData['nPerXFs'];?></td> -->
						</tr>
					<?php
					}
					?>					
				</tbody>
				<tfoot>
					<tr>
						<th colspan="3"><?php echo '總計';?></th>
						<th></th>
						<th><?php echo round($aTotal['nBetMoney'],3);?></th>
						<th><?php echo round($aTotal['nAgentFs'],3);?></th>
						<th></th>
						<th><?php echo $aTotal['nDonate'];?></th>
						<th><?php echo $aTotal['nMall'];?></th>
						<th><?php echo $aTotal['nFee'];?></th>
						<th><?php echo $aTotal['nBankerPlusFee'];?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>