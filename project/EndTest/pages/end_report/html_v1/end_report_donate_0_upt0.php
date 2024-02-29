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
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['ADMNAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sName0" placeholder="<?php echo aLOG['ADMNAME'];?>" value="<?php echo $sName0;?>">
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<?php
			if($nTotalCount > 0 && false)
			{
		?>
		<a href="<?php echo $aUrl['sExcel'];?>" class="BtnAny"> <?php echo EXPORTXLS;?> </a>
		<?php
			}
		?>
	</div>
</form>

<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo aLOG['DETAIL']; ?></div>
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
						<th><?php echo aLOG['GAMER'];?></th>
						<th><?php echo aLOG['ADM'];?></th>
						<th><?php echo aLOG['ROOM'];?></th>
						<th><?php echo aLOG['DELTA'];?></th>
						<th><?php echo aLOG['PERCENT1'];?></th>
						<th><?php echo aLOG['BONUS'];?></th>
						<th><?php echo aLOG['CREATETIME'];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aData'] as $LPnId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td class="<?php echo $LPaData['sUserColor'];?>"><?php echo $LPaData['sUser'];?></td>
							<td class="<?php echo $LPaData['sUserColor'];?>"><?php echo $LPaData['sName0'];?></td>
							<td><?php echo $aGamesId[$LPaData['nGame']]['sName0'];?></td>
							<td><?php echo $LPaData['nMoney0'];?></td>
							<td><?php echo $LPaData['sPer'].'%';?></td>
							<td><?php echo $LPaData['sPerMoney'];?></td>
							<td><?php echo $LPaData['sCreateTime'];?> </td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="4"><?php echo aLOG['SUBTOTAL'];?></td>
						<td><?php echo $aData['aSubTotal']['nDelta'];?></td>
						<td></td>
						<td><?php echo $aData['aSubTotal']['sPerMoney'];?></td>
						<td></td>
					</tr>
					<tr>
						<td colspan="4"><?php echo aLOG['TOTAL'];?></td>
						<td><?php echo $aData['aTotal']['nDelta'];?></td>
						<td></td>
						<td><?php echo $aData['aTotal']['sPerMoney'];?></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>