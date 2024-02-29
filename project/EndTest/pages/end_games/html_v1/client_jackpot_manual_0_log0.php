<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo CREATETIME;?></span>
			<div class="Ipt">
				<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>" autocomplete="off">
			</div>
			<span>~</span>
			<div class="Ipt">
				<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>" autocomplete="off">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aLOG['NO'];?></span>
			<div class="Ipt">
				<input type="text" name="sNo" value="<?php echo $sNo;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aLOG['TYPE0']['sTitle'];?></span>
			<div class="Sel">
				<select name="nType0">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aType0 as $LPnStatus => $LPaDetail)
						{
							if(!is_array($LPaDetail)) continue;
					?>
							<option value="<?php echo $LPnStatus;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aMANUAL['MONEY'];?></span>
			<span class="InlineBlock"><?php echo $aJackpot['nMoney'];?></span>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 新增按鈕 -->
<div class="Block MarginBottom10">
	<a href="<?php echo $aUrl['sManual'];?>" class="BtnAdd"><?php echo $sHeadTitle.RECORD;?></a>
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
						<th><?php echo aLOG['NO'];?></th>
						<th><?php echo aLOG['BETID'];?></th>
						<th><?php echo aLOG['ACCOUNT'];?></th>
						<th><?php echo aLOG['BEFORE'];?></th>
						<th><?php echo aLOG['DELTA'];?></th>
						<th><?php echo aLOG['AFTER'];?></th>
						<th><?php echo aLOG['TYPE0']['sTitle'];?></th>
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
							<td><?php echo $LPaDetail['sNo'];?></td>
							<td><?php echo ($LPaDetail['nType0']==0||$LPaDetail['nType0']==1)?$LPaDetail['nLottryId']:'';?></td>
							<td><?php echo $LPaDetail['sAccount'];?></td>
							<td><?php echo $LPaDetail['nBefore'];?></td>
							<td><?php echo $LPaDetail['nDelta'];?></td>
							<td><?php echo $LPaDetail['nAfter'];?></td>
							<td><?php echo aLOG['TYPE0'][$LPaDetail['nType0']]['sText'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td ><?php echo aLOG['PAGETOTALCOUNT'];?></td>
						<td ><?php echo $nPageTotalCount;?></td>
						<td colspan="1"></td>
						<td ><?php echo aLOG['PAGETOTALMONEY'];?></td>
						<td ><?php echo $nPageTotalBefore;?></td>
						<td ><?php echo $nPageTotalDelta;?></td>
						<td ><?php echo $nPageTotalAfter;?></td>
						<td colspan="2"></td>
					</tr>
					<tr>
						<td ><?php echo aLOG['TOTALCOUNT'];?></td>
						<td ><?php echo $nTotalCount;?></td>
						<td colspan="1"></td>
						<td ><?php echo aLOG['TOTALMONEY'];?></td>
						<td ><?php echo $nTotalBefore;?></td>
						<td ><?php echo $nTotalDelta;?></td>
						<td ><?php echo $nTotalAfter;?></td>
						<td colspan="2"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>