<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage']; ?>" method="POST" class="Form">
	<div class="Search">
		<div class="Block MarginBottom20" >
			<div class="Sel">
				<select name="sSearchType">
					<?php
					foreach ($aSearchType as $LPsSearchType => $LPaSearchType)
					{
						?>
						<option value="<?php echo $LPsSearchType;?>" <?php echo $LPaSearchType['sSelect'];?> ><?php echo $LPaSearchType['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
			<div class="Ipt">
				<input type="text" name="sSearch" value="<?php echo $sSearch;?>" placeholder="<?php echo NAME;?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo STATUS;?></span>
			<div class="Sel">
				<select name="nStatus">
					<?php
					foreach ($aStatus as $LPnStatus => $LPaStatus)
					{
						?>
						<option value="<?php echo $LPnStatus;?>" <?php echo $LPaStatus['sSelect'];?> ><?php echo $LPaStatus['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<!-- <div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo '上下線';?></span>
			<div class="Sel">
				<select name="nStatus">
					<?php
					foreach ($aOnline as $LPnOnline => $LPaOnline)
					{
						?>
						<option value="<?php echo $LPnOnline;?>" <?php echo $LPaOnline['sSelect'];?> ><?php echo $LPaOnline['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div> -->
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 新增按鈕 -->

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
						<th><?php echo aUSER['ONLINEOFFLINE'];?></th>
						<th><?php echo ACCOUNT;?></th>
						<th><?php echo aUSER['NAME'];?></th>
						<th><?php echo STATUS;?></th>
						<th><?php echo aUSER['MONEY'];?></th>
						<th><?php echo CREATETIME;?></th>
						<th><?php echo UPDATETIME;?></th>
						<th><?php echo aUSER['APPEAR'];?></th>
						<!-- <th><?php echo OPERATE;?></th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPnId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td><?php echo $LPaData['sOnline'];?></td>
							<td><?php echo $LPaData['sAccount'];?></td>
							<td><?php echo $LPaData['sName0'];?></td>
							<td class="<?php echo $aStatus[$LPaData['nStatus']]['sClass'];?>"><?php echo $LPaData['sStatus'];?></td>
							<td><?php echo $LPaData['nMoney'];?></td>
							<td><?php echo $LPaData['sCreateTime'];?></td>
							<td><?php echo $LPaData['sUpdateTime'];?></td>
							<td><?php echo $LPaData['nRoom'];?></td>
							<!-- <td>
								<a href="<?php #echo $LPaData['sIns'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
							</td> -->
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