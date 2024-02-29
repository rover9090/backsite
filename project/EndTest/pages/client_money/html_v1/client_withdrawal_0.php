<?php $aData = json_decode($sData,true);?>
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
			<span class="InlineBlockTit"><?php echo aWITHDRAWAL['ADMINNAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sAdmin" value="<?php echo $sAdmin;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aWITHDRAWAL['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemberAccount" value="<?php echo $sMemberAccount;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aWITHDRAWAL['MEMO'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemo" value="<?php echo $sMemo;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aWITHDRAWAL['BANKNAME'];?></span>
			<div class="Sel">
				<select name="nKid">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aBank as $LPnId => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aWITHDRAWAL['STATUS']['sTitle'];?></span>
			<div class="Sel">
				<select name="nStatus">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aStatus as $LPnId => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
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
						<th><?php echo aWITHDRAWAL['ACCOUNT'];?></th>
						<th><?php echo aWITHDRAWAL['MONEY'];?></th>
						<th><?php echo aWITHDRAWAL['DETAIL'];?></th>
						<th><?php echo aWITHDRAWAL['STATUS']['sTitle'];?></th>
						<th><?php echo aWITHDRAWAL['FEE'];?></th>
						<th><?php echo aWITHDRAWAL['ADMIN1'];?></th>
						<th><?php echo aWITHDRAWAL['ADMIN2'];?></th>
						<th><?php echo aWITHDRAWAL['MEMO'];?></th>
						<th><?php echo CREATETIME;?></th>
						<th><?php echo UPDATETIME;?></th>
					</tr>
				</thead>
				<?php
				foreach($aData as $LPnId => $LPaDetail)
				{
				?>
					<tbody>
						<tr>
							<td><?php echo $LPnId;?></td>
							<td><?php echo $LPaDetail['sUserAccount'];?></td>
							<td><?php echo $LPaDetail['nMoney'];?></td>
							<td>
								<div class="TableBtnFont JqBtnForJump" data-showctrl="<?php echo $LPnId; ?>">查看</div>
							</td>
							<td class="<?php echo $aStatus[$LPaDetail['nStatus']]['sClass'];?>"><?php echo $aStatus[$LPaDetail['nStatus']]['sText'];?></td>
							<td><?php echo $LPaDetail['nFee'];?></td>
							<td>
								<?php
									if($LPaDetail['nAdmin1'] > 0)
									{
								?>
										<?php echo $LPaDetail['sAdmin1'];?>
								<?php
									}
									else if ($LPaDetail['nStatus'] == 0)
									{
								?>
										<div class="TableBtnBg JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sRiskPass'];?>">
											<i class="fas fa-pen"></i>
										</div>
										<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sRiskDeny'];?>">
											<i class="fas fa-times"></i>
										</div>
								<?php
									}
								?>
							</td>
							<td>
								<?php
									if($LPaDetail['nAdmin2'] > 0)
									{
								?>
										<?php echo $LPaDetail['sAdmin2'];?>
								<?php
									}
									else if($LPaDetail['nStatus'] == 0 && $LPaDetail['nAdmin1'] > 0 && $LPaDetail['nAdmin2'] <= 0)
									{
								?>
										<div class="TableBtnBg JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sMoneyPass'];?>">
											<i class="fas fa-pen"></i>
										</div>
										<div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaDetail['sMoneyDeny'];?>">
											<i class="fas fa-times"></i>
										</div>
								<?php
									}
								?>
							</td>
							<td><?php echo $LPaDetail['sMemo'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
						</tr>
					</tbody>
				<?php
				}
				?>
				<tfoot>
					<tr>
						<td ><?php echo aWITHDRAWAL['PAGETOTALCOUNT'];?></td>
						<td ><?php echo $nPageTotalCount;?></td>
						<td ><?php echo aWITHDRAWAL['PAGETOTALMONEY'];?></td>
						<td ><?php echo $nPageTotalMoney;?></td>
						<td colspan="7"></td>
					</tr>
					<tr>
						<td ><?php echo aWITHDRAWAL['TOTALCOUNT'];?></td>
						<td ><?php echo $nTotalCount;?></td>
						<td ><?php echo aWITHDRAWAL['TOTALMONEY'];?></td>
						<td ><?php echo $nTotalMoney;?></td>
						<td colspan="7"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>