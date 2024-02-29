<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aGAMESETTING['NAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sSearch" value="<?php echo $sSearch;?>" placeholder="<?php echo aGAMESETTING['NAME'];?>">
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
<!-- <div class="Block MarginBottom10">
	<a href="<?php echo $aUrl['sSync'] . '&sJWT=' . $sJWT;?>" class="BtnAdd"><?php echo '同步'.$sHeadTitle;?></a>
</div> -->

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
						<th><?php echo aGAMESETTING['NAME'];?></th>
						<th><?php echo aGAMESETTING['CODE'];?></th>
						<!-- <th><?php echo aGAMESETTING['COUNT'];?></th> -->
						<th><?php echo STATUS;?></th>
						<!-- <th><?php echo aGAMESETTING['SEC'];?></th>
						<th><?php echo aGAMESETTING['SEC1'];?></th>
						<th><?php echo aGAMESETTING['SEC2'];?></th>
						<th><?php echo '限額分類';?></th> -->
						<th><?php echo aGAMESETTING['OPENTIME'];?></th>
						<th><?php echo aGAMESETTING['CLOSETIME'];?></th>
						<!-- <th><?php echo '吃大賠小機率';?></th> -->
						<th><?php echo aGAMESETTING['MAINTENANCE'];?></th>
						<!-- <th><?php echo aGAMESETTING['NIGHTTIME'];?></th> -->
						<th><?php echo OPERATE;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($aData as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPaDetail['nId'];?></td>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $LPaDetail['nGame'];?></td>
							<!-- <td><?php echo $LPaDetail['nCount'];?></td> -->
							<td class="<?php echo $aOnline[$LPaDetail['nOnline']]['sClass'];?>"><?php echo $aOnline[$LPaDetail['nOnline']]['sText'];?></td>
							<!-- <td><?php echo $LPaDetail['nSec'];?></td>
							<td><?php echo $LPaDetail['nSec1'];?></td>
							<td><?php echo $LPaDetail['nSec2'];?></td>
							<td><?php #echo $aGroupKind[$LPaDetail['nGroupId']]['sName0'];?></td> -->
							<td><?php echo $LPaDetail['sOpenTime'];?></td>
							<td><?php echo $LPaDetail['sCloseTime'];?></td>
							<!-- <td><?php echo $LPaDetail['nWinPercent'];?></td> -->
							<td><?php echo $aMance[$LPaDetail['nMaintenance']]['sText'];?></td>
							<!-- <td><?php echo $LPaDetail['sNightTime'];?></td> -->
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