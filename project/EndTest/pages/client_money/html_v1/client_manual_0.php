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
			<span class="InlineBlockTit"><?php echo aMANUAL['ADMINNAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sAdmin" value="<?php echo $sAdmin;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aMANUAL['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemberAccount" value="<?php echo $sMemberAccount;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aMANUAL['MEMO'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemo" value="<?php echo $sMemo;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aMANUAL['TYPE1']['sTitle'];?></span>
			<div class="Sel">
				<select name="nType1">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aType1 as $LPnStatus => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnStatus;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aMANUAL['TYPE3']['sTitle'];?></span>
			<div class="Sel">
				<select name="nType3">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aType3 as $LPnStatus => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnStatus;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aMANUAL['STATUS']['sTitle'];?></span>
			<div class="Sel">
				<select name="nStatus">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aStatus as $LPnStatus => $LPaDetail)
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
						<th><?php echo aMANUAL['ACCOUNT'];?></th>
						<th><?php echo aMANUAL['MONEY'];?></th>
						<th><?php echo aMANUAL['TYPE1']['sTitle'];?></th>
						<th><?php echo aMANUAL['TYPE3']['sTitle'];?></th>
						<th><?php echo aMANUAL['STATUS']['sTitle'];?></th>
						<th><?php echo aMANUAL['ADMINNAME'];?></th>
						<th><?php echo aMANUAL['MEMO'];?></th>
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
							<td><?php echo $LPaDetail['sUserAccount'];?></td>
							<td><?php echo $LPaDetail['nMoney'];?></td>
							<td><?php echo aMANUAL['TYPE1'][$LPaDetail['nType1']]['sText'];?></td>
							<td><?php echo aMANUAL['TYPE3'][$LPaDetail['nType3']]['sText'];?></td>
							<td class="<?php echo $aStatus[$LPaDetail['nStatus']]['sClass'];?>"><?php echo $aStatus[$LPaDetail['nStatus']]['sText'];?></td>
							<td><?php echo $LPaDetail['sAdmin0'];?></td>
							<td><?php echo $LPaDetail['sMemo'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
							<td>
								<?php
									if($LPaDetail['nStatus'] == 0)
									{
								?>
										<a href="<?php echo $LPaDetail['sPass'];?>" class="TableBtnBg">
											<i class="fas fa-pen"></i>
										</a>
										<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sDeny'];?>">
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
				<tfoot>
					<tr>
						<td ><?php echo aMANUAL['PAGETOTALINCOUNT'];?></td>
						<td ><?php echo $nPageTotalInCount;?></td>
						<td ><?php echo aMANUAL['PAGETOTALINMONEY'];?></td>
						<td ><?php echo $nPageTotalInMoney;?></td>
						<td colspan="7"></td>
					</tr>
					<tr>
						<td ><?php echo aMANUAL['PAGETOTALOUTCOUNT'];?></td>
						<td ><?php echo $nPageTotalOutCount;?></td>
						<td ><?php echo aMANUAL['PAGETOTALOUTMONEY'];?></td>
						<td ><?php echo $nPageTotalOutMoney;?></td>
						<td colspan="7"></td>
					</tr>
					<tr>
						<td ><?php echo aMANUAL['TOTALINCOUNT'];?></td>
						<td ><?php echo $nTotalInCount;?></td>
						<td ><?php echo aMANUAL['TOTALINMONEY'];?></td>
						<td ><?php echo $nTotalInMoney;?></td>
						<td colspan="7"></td>
					</tr>
					<tr>
						<td ><?php echo aMANUAL['TOTALOUTCOUNT'];?></td>
						<td ><?php echo $nTotalOutCount;?></td>
						<td ><?php echo aMANUAL['TOTALOUTMONEY'];?></td>
						<td ><?php echo $nTotalOutMoney;?></td>
						<td colspan="7"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>