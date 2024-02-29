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
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aUSER['BLACK1'];?></span>
			<div class="Sel">
				<select name="nBlack">
					<?php
					foreach ($aBlack as $LPnBlack => $LPaBlack)
					{
						?>
						<option value="<?php echo $LPnBlack;?>" <?php echo $LPaBlack['sSelect'];?> ><?php echo $LPaBlack['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aUSER['MUTE1'];?></span>
			<div class="Sel">
				<select name="nMute">
					<?php
					foreach ($aMute as $LPnMute => $LPaMute)
					{
						?>
						<option value="<?php echo $LPnMute;?>" <?php echo $LPaMute['sSelect'];?> ><?php echo $LPaMute['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aUSER['IDENTITY'];?></span>
			<div class="Sel">
				<select name="nIdentity">
					<?php
					foreach ($aIdentity as $LPnIdentity => $LPaIdentity)
					{
						?>
						<option value="<?php echo $LPnIdentity;?>" <?php echo $LPaIdentity['sSelect'];?> ><?php echo $LPaIdentity['sTitle'];?></option>
						<?php
					}
					?>
				</select>
			</div>
		</div>
		<label for="inputIncludeDown" class="IptCheckbox">
			<input type="checkbox" id="inputIncludeDown" name="nInclude" value="1" <?php echo $sInclude;?>>
			<span><?php echo aUSER['INCLUDE'];?></span>
		</label>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 新增按鈕 -->
<?php
if($aAdm['nAdmType'] == 1 || $aAdm['nAdmType'] == 2)
{
?>
<div class="BtnAddBox MarginBottom10">
	<a href="<?php echo $aUrl['sIns'];?>" class="BtnAdd"><?php echo INS.$sHeadTitle;?></a>
</div>
<?php
}
?>

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
						<!-- <th><?php echo '['.aUSER['LEVEL'].'] '.aUSER['IDENTITY'];?></th> -->
						<th><?php echo ACCOUNT;?></th>
						<th><?php echo aUSER['NAME'];?></th>
						<!-- <th><?php echo aUSER['PA'];?></th> -->
						<th><?php echo STATUS;?></th>
						<th><?php echo aUSER['BLACK1'];?></th>
						<th><?php echo aUSER['MUTE1'];?></th>
						<th><?php echo aUSER['MONEY'];?></th>
						<th><?php echo CREATETIME;?></th>
						<th><?php echo UPDATETIME;?></th>
						<th><?php echo OPERATE;?></th>
						<!-- <th><?php echo aUSER['TRANSFER'];?></th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData as $LPnId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPnId;?></td>
							<!-- <td><?php # echo '['.$LPaData['nLevel'].'] '.$LPaData['sIdentity'];?></td> -->
							<td class="<?php echo $LPaData['sColor'];?>"><?php echo $LPaData['sAccount'];?></td>
							<td class="<?php echo $LPaData['sColor'];?>"><?php echo $LPaData['sName0'];?></td>
							<!-- <td><a href="<?php #echo $aPaData[$LPaData['nPa']]['sUrl']; ?>"><?php #echo $aPaData[$LPaData['nPa']]['sName0']; ?></a></td> -->
							<td class="<?php echo $aStatus[$LPaData['nStatus']]['sClass'];?>"><?php echo $LPaData['sStatus'];?></td>
							<td class="<?php echo $aBlack[$LPaData['nBlack']]['sClass'];?>"><?php echo $LPaData['sBlack'];?></td>
							<td class="<?php echo $aMute[$LPaData['nMute']]['sClass'];?>"><?php echo $LPaData['sMute'];?></td>
							<td><?php echo $LPaData['nMoney'];?></td>
							<td><?php echo $LPaData['sCreateTime'];?></td>
							<td><?php echo $LPaData['sUpdateTime'];?></td>
							<td>
								<a href="<?php echo $LPaData['sIns'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
							</td>
							<!-- <td>
							<?php #if($LPaData['nPa'] === $aAdm['nUid']){?>
								<a href="<?php # echo $aUrl['sUpt2'].'&nId='.$LPaData['nId'];?>" class="TableBtnBg">
									<i class="fas fa-pen"></i>
								</a>
							<?php #}?>
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