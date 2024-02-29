<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit">查詢名稱</span>
			<div class="Ipt">
				<input type="text" name="sMenuName0" placeholder="查詢名稱" value="<?php echo $sMenuName0;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit">狀態</span>
			<div class="Sel">
				<select name="nOnline">
					<?php
					foreach ($aOnline as $LPnOnline => $LPaOnline)
					{
						?>
						<option value="<?php echo $LPnOnline;?>" <?php echo $LPaOnline['sSelect'];?> >
							<?php echo $LPaOnline['sTitle'];?>
						</option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<input type="submit" class="BtnAny" value="查詢">
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
						<th>編號</th>
						<th>目錄名稱</th>
						<th>資料夾名稱</th>
						<th>狀態</th>
						<th>排序</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPnKid => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPnKid;?></td>
							<td><?php echo $LPaData['sMenuName0'];?></td>
							<td><?php echo $LPaData['sMenuTable0'];?></td>
							<td class="<?php echo $aOnline[$LPaData['nOnline']]['sClass'];?>"><?php echo $aOnline[$LPaData['nOnline']]['sTitle'];?></td>
							<td><?php echo $LPaData['nSort'];?></td>
							<td>
								<a href="<?php echo $LPaData['sUptUrl'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
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
