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
		<!-- <div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sAccount" placeholder="<?php echo aLOG['ACCOUNT'];?>" value="<?php echo $sAccount;?>">
			</div>
		</div> -->
		<!-- <div class="Block MarginBottom20">
			<div class="InlineBlockTxt">
				<?php echo aLOG['SITE'];?>
			</div>
			<div class="Sel">
				<select name="sSiteId">
					<option value=""><?php echo ALL;?></option>
					<?php
					foreach ($aSite as $LPsSiteId => $LPaSite)
					{
						?>
						<option value="<?php echo $LPsSiteId;?>" <?php echo $LPaSite['sSelect'];?>><?php echo $LPaSite['sText'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['TYPE0'];?></span>
			<div class="Sel">
				<select name="nType0">
					<?php
					foreach ($aType0 as $LPnType0 => $LPaType0)
					{
					?>
						<option value="<?php echo $LPnType0;?>" <?php echo $LPaType0['sSelect'];?>><?php echo $LPaType0['sName'];?></option>
					<?php
					}
					?>
				</select>
			</div>            
		</div> -->
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<?php
			if($nTotalCount > 0)
			{
		?>
		<!-- <a href="<?php echo $aUrl['sExcel'];?>" class="BtnAny"> <?php echo EXPORTXLS;?> </a> -->
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
						<th><?php echo aLOG['ADMACCOUNT'];?></th>
						<th><?php echo aLOG['ADMNAME'];?></th>
						<th><?php echo aLOG['DONATESUM'];?></th>
						<th><?php echo aLOG['PERCENT'];?></th>
						<th><?php echo aLOG['BONUS'];?></th>
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
							<td>
								<a href='<?php echo $LPaData['sUpt'];?>'><?php echo $LPaData['sName0'];?></a>
							</td>
							<td><?php echo $LPaData['nDoate'];?></td>
							<td><?php echo $LPaData['sPer'].'%';?> </td>
							<td><?php echo $LPaData['nTotalPer'];?> </td>
						</tr>
						<?php
					}
					?>
					<?php if(false){?>
					<tr>
						<td colspan="5"><?php echo aLOG['SUBTOTAL'];?></td>
						<td><?php echo number_format($aData['aSubTotal']['nBefore'],3);?></td>
						<td><?php echo number_format($aData['aSubTotal']['nDelta'],3);?></td>
						<td><?php echo number_format($aData['aSubTotal']['nAfter'],3);?></td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td colspan="5"><?php echo aLOG['TOTAL'];?></td>
						<td><?php echo number_format($aData['aTotal']['nBefore'],3);?></td>
						<td><?php echo number_format($aData['aTotal']['nDelta'],3);?></td>
						<td><?php echo number_format($aData['aTotal']['nAfter'],3);?></td>
						<td colspan="2"></td>
					</tr>
					<?php }?>
					<tr>
						<td colspan="3"><?php echo aLOG['TOTAL'];?></td>
						<td><?php echo $nTotalMoney;?></td>
						<td colspan="2"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>