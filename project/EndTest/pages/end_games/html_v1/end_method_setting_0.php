<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aMETHOD['GAME'];?></span>
			<div class="Sel">
				<select name="nGame">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach ($aSetting as $LPnGame => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnGame;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
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
	<?php
	foreach($aData as $LPnGame => $LPaData)
	{
	?>
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo $aSetting[$LPnGame]['sName0']; ?></div>
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
						<th><?php echo aMETHOD['NAME0'];?></th>
						<th><?php echo aMETHOD['ODDS'];?></th>
						<!-- <th><?php echo aMETHOD['ODDS1'];?></th> -->
						<!-- <th><?php echo aMETHOD['MAXBET'];?></th> -->
						<!-- <th><?php echo aMETHOD['MINBET'];?></th> -->
						<th><?php echo STATUS;?></th>
						<th><?php echo aMETHOD['UPDATETIME'];?></th>
						<th><?php echo OPERATE;?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($LPaData as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPaDetail['nId'];?></td>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $LPaDetail['nOdds'];?></td>
							<!-- <td><?php echo $LPaDetail['nOdds1'];?></td> -->
							<!-- <td><?php echo $LPaDetail['nMaxBet'];?></td> -->
							<!-- <td><?php echo $LPaDetail['nMinBet'];?></td> -->
							<td class="<?php echo $aOnline[$LPaDetail['nOnline']]['sClass'];?>"><?php echo $aOnline[$LPaDetail['nOnline']]['sText'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
							<td>
								<a href="<?php echo $LPaDetail['sIns'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
								<?php
								if(false)
								{
								?>
								<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sDel'];?>">
									<i class="fas fa-times"></i>
								</div>
								<?php
								}
								?>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	<?php
	}
	?>
</div>
<?php #echo $aPageList['sHtml'];?>