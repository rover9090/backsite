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
			<span class="InlineBlockTit"><?php echo NO;?></span>
			<div class="Ipt">
				<input type="number" name="nId" placeholder="<?php echo NO;?>" value="<?php echo $nId;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sAccount" placeholder="<?php echo aLOG['ACCOUNT'];?>" value="<?php echo $sAccount;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['ID'];?></span>
			<div class="Ipt">
				<input type="number" name="sNo" placeholder="<?php echo aLOG['ID'];?>" value="<?php echo $sNo;?>">
			</div>
		</div>
		<?php if(false){?>
		<div class="Block MarginBottom20">
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
		<?php }?>
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
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['TYPE2'];?></span>
			<div class="Sel">
				<select name="nType2">
					<?php
					foreach ($aType2 as $LPnType2 => $LPaType2)
					{
					?>
						<option value="<?php echo $LPnType2;?>" <?php echo $LPaType2['sSelect'];?>><?php echo $LPaType2['sName'];?></option>
					<?php
					}
					?>
				</select>
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<?php
			if($nTotalCount > 0)
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
						<th><?php echo aLOG['TYPE0'];?></th>
						<th><?php echo aLOG['TYPE1'];?></th>
						<th><?php echo aLOG['TYPE2'];?></th>
						<!-- <th><?php echo aLOG['SITE'];?></th> -->
						<th><?php echo aLOG['ACCOUNT'];?></th>
						<th><?php echo aLOG['ID'];?></th>
						<th><?php echo aLOG['BEFORE'];?></th>
						<th><?php echo aLOG['DELTA'];?></th>
						<th><?php echo aLOG['AFTER'];?></th>
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
							<td><?php echo $LPaData['sType0'];?></td>
							<td><?php echo $LPaData['sType1'];?></td>
							<td><?php echo $LPaData['sType2'];?></td>
							<!-- <td><?php echo ($LPaData['sSiteId'] != '') ? $aSite[$LPaData['sSiteId']]['sText'] : '';?></td> -->
							<td><?php echo $LPaData['sAccount'];?></td>
							<td><?php echo $LPaData['sNo'];?> </td>
							<td><?php echo $LPaData['nBefore'];?></td>
							<td><?php echo $LPaData['nDelta'];?></td>
							<td><?php echo $LPaData['nAfter'];?></td>
							<td><?php echo $LPaData['sCreateTime'];?> </td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="5"><?php echo aLOG['SUBTOTAL'];?></td>
						<td><?php echo number_format($aData['aSubTotal']['nBefore'],3);?></td>
						<td><?php echo number_format($aData['aSubTotal']['nDelta'],3);?></td>
						<td><?php echo number_format($aData['aSubTotal']['nAfter'],3);?></td>
						<td colspan="1"></td>
					</tr>
					<tr>
						<td colspan="5"><?php echo aLOG['TOTAL'];?></td>
						<td><?php echo number_format($aData['aTotal']['nBefore'],3);?></td>
						<td><?php echo number_format($aData['aTotal']['nDelta'],3);?></td>
						<td><?php echo number_format($aData['aTotal']['nAfter'],3);?></td>
						<td colspan="1"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>