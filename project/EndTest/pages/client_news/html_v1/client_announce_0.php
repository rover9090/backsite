<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aANNOUNCE['NAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sSearch" value="<?php echo $sSearch;?>" placeholder="<?php echo aANNOUNCE['NAME'];?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aANNOUNCE['KIND'];?></span>
			<div class="Sel">
				<select name="nKid">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach ($aAnnounceKind as $LPnLid => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnLid;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo STATUS;?></span>
			<div class="Sel">
				<select name="nOnline">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach ($aOnline as $LPnStatus => $LPaDetail)
						{
					?>
						<option value="<?php echo $LPnStatus;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 新增按鈕 -->
<div class="Block MarginBottom10">
	<a href="<?php echo $aUrl['sIns'];?>" class="BtnAdd"><?php echo INS.$sHeadTitle;?></a>
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
						<th><?php echo aANNOUNCE['NAME'];?></th>
						<th><?php echo aANNOUNCE['KIND'];?></th>
						<th><?php echo '館別';?></th>
						<th><?php echo STATUS;?></th>
						<?php if(false){?>
						<th><?php echo PICTURE;?></th>
						<?php }?>
						<th><?php echo aANNOUNCE['PUSHTIME'];?></th>
						<th><?php echo ENDTIME;?></th>
						<th><?php echo CREATETIME;?></th>
						<th><?php echo UPDATETIME;?></th>
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
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $LPaDetail['sKind'];?></td>
							<td><?php echo $aRoom[$LPaDetail['nGame']]['sName0'];?></td>
							<td class="<?php echo $aOnline[$LPaDetail['nOnline']]['sClass']; ?>"><?php echo $aOnline[$LPaDetail['nOnline']]['sText'];?></td>
							<?php if(false){?>
							<td>
								<?php
								if(isset($aImage[$LPnId]))
								{
								?>
									<div class="BlockImg MarginTop5">
										<img src="<?php echo $aImage[$LPnId];?>" alt="">
									</div>
								<?php
								}
								?>
							</td>
							<?php }?>
							<td><?php echo $LPaDetail['sPushTime'];?></td>
							<td><?php echo $LPaDetail['sEndTime'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
							<td>
								<a href="<?php echo $LPaDetail['sIns'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
								<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sDel'];?>">
									<i class="fas fa-times"></i>
								</div>
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