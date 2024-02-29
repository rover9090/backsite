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
			<span class="InlineBlockTit"><?php echo ACCOUNT;?></span>
			<div class="Ipt">
				<input type="text" name="sAccount" value="<?php echo $sAccount;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aRECORD['NAME'];?></span>
			<div class="Sel">
				<select name="nAid">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach ($aActivity as $LPnLid => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnLid;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
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
						<th><?php echo aRECORD['NAME'];?></th>
						<th><?php echo aRECORD['GIVE'];?></th>
						<th><?php echo aRECORD['USE'];?></th>
						<th><?php echo CREATETIME;?></th>
						<th><?php echo UPDATETIME;?></th>
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
							<td><?php echo $LPaDetail['sActivity'];?></td>
							<td><?php echo $LPaDetail['nGive'];?></td>
							<td><?php echo $LPaDetail['nUse'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
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