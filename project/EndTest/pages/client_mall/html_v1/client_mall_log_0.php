<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20" id="searchForm">
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
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo '會員帳號';?>
		</div>
		<div class="Ipt">
			<input type="text" name="sAccount" value="<?php echo $sAccount;?>">
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
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
						<th><?php echo '單號';?></th>
						<th><?php echo '會員帳號';?></th>
						<!-- <th><?php echo '會員暱稱';?></th> -->
						<th><?php echo '商品名稱';?></th>
						<th><?php echo '數量';?></th>
						<th><?php echo '單價';?></th>
						<th><?php echo '總價';?></th>
						<!-- <th><?php echo '編號8';?></th> -->
						<th><?php echo '日期';?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPsId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPaData['nId'];?></td>
							<td><?php echo $aUserDetail[$LPaData['nUid']]['sAccount'];?></td>
							<!-- <td><?php echo $aUserDetail[$LPaData['nUid']]['sName0'];?></td> -->
							<td><?php echo $aMall[$LPaData['nMid']]['sName0'];?></td>
							<td><?php echo $LPaData['nCount'];?></td>
							<td><?php echo $LPaData['nPrice'];?></td>
							<td><?php echo $LPaData['nSumCost'];?></td>
							<!-- <td><?php echo $LPaData['nStatus'];?></td> -->
							<td><?php echo $LPaData['sCreateDay'];?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="5"><?php echo '小計';?></td>
						<td><?php echo number_format($nSubTotal,3);?></td>
						<td colspan="1"></td>
					</tr>

					<tr>
						<td colspan="5"><?php echo '總計';?></td>
						<td><?php echo number_format($nTotal,3);?></td>
						<td colspan="1"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>