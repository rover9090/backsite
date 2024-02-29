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
			<span class="InlineBlockTit"><?php echo aPAYMENTCOMPANY['ADMINNAME'];?></span>
			<div class="Ipt">
				<input type="text" name="sAdmin" value="<?php echo $sAdmin;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aPAYMENTCOMPANY['ACCOUNT'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemberAccount" value="<?php echo $sMemberAccount;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aPAYMENTCOMPANY['MEMO'];?></span>
			<div class="Ipt">
				<input type="text" name="sMemo" value="<?php echo $sMemo;?>" >
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aPAYMENTCOMPANY['BANKNAME'];?></span>
			<div class="Sel">
				<select name="nKid">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<?php
						foreach($aCompany as $LPnId => $LPaDetail)
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
			<span class="InlineBlockTit"><?php echo aPAYMENTCOMPANY['STATUS']['sTitle'];?></span>
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
	</div>
</form>

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
						<th><?php echo aPAYMENTCOMPANY['ACCOUNT'];?></th>
						<th><?php echo aPAYMENTCOMPANY['MONEY'];?></th>
						<th><?php echo aPAYMENTCOMPANY['BANKNAME'];?></th>
						<th><?php echo aPAYMENTCOMPANY['STATUS']['sTitle'];?></th>
						<th><?php echo aPAYMENTCOMPANY['FEE'];?></th>
						<th><?php echo aPAYMENTCOMPANY['ADMINNAME'];?></th>
						<th><?php echo aPAYMENTCOMPANY['MEMO'];?></th>
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
							<td><?php echo $LPaDetail['sCompany'];?></td>
							<td class="<?php echo $aStatus[$LPaDetail['nStatus']]['sClass'];?>"><?php echo $aStatus[$LPaDetail['nStatus']]['sText'];?></td>
							<td><?php echo $LPaDetail['nFee'];?></td>
							<td><?php echo $LPaDetail['sAdmin0'];?></td>
							<td><?php echo $LPaDetail['sMemo'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td><?php echo $LPaDetail['sUpdateTime'];?></td>
							<td>
								<div class="TableBtnFont JqBtnForJump" data-showctrl="<?php echo $LPnId; ?>"><?php echo aPAYMENTCOMPANY['CHECK'];?></div>
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
				<tfoot>
					<tr>
						<td ><?php echo aPAYMENTCOMPANY['PAGETOTALCOUNT'];?></td>
						<td ><?php echo $nPageTotalCount;?></td>
						<td ><?php echo aPAYMENTCOMPANY['PAGETOTALMONEY'];?></td>
						<td ><?php echo $nPageTotalMoney;?></td>
						<td colspan="7"></td>
					</tr>
					<tr>
						<td ><?php echo aPAYMENTCOMPANY['TOTALCOUNT'];?></td>
						<td ><?php echo $nTotalCount;?></td>
						<td ><?php echo aPAYMENTCOMPANY['TOTALMONEY'];?></td>
						<td ><?php echo $nTotalMoney;?></td>
						<td colspan="7"></td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>