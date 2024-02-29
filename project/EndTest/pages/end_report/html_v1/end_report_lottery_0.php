<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20" id="searchForm">
	<div class="MarginBottom10">
		<?php
		foreach ($aDay as $LPsText => $LPaDate)
		{
			?>
			<span class="JqDate BtnKind <?php echo $LPaDate['sSelect'];?>" data-day="<?php echo $LPsText;?>" data-date0="<?php echo $LPaDate['sStartDay']?>" data-date1="<?php echo $LPaDate['sEndDay']?>">
				<?php echo aDAYTEXT[$LPsText];?>
			</span>
			<?php
		}
		?>
		<input type="hidden" name="sSelDay" value="<?php echo $sSelDay;?>">
	</div>
	<div class="MarginBottom10">
		<div class="Ipt">
			<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>">
		</div>
		<span>~</span>
		<div class="Ipt">
			<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>">
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['NAME0'];?>
		</div>
		<div class="Ipt">
			<input type="text" name="sAccount" value="<?php echo $sAccount;?>">
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo '莊家暱稱';?>
		</div>
		<div class="Ipt">
			<input type="text" name="sBankerAccount" value="<?php echo $sBankerAccount;?>">
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['NO'];?>
		</div>
		<div class="Ipt">
			<input type="text" name="sNo" value="<?php echo $sNo;?>">
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['GAME'];?>
		</div>
		<div class="Sel">
			<select name="nGame">
				<option value="-1"><?php echo ALL;?></option>
				<?php
				foreach ($aGame as $LPGame => $LPaGame)
				{
					?>
					<option value="<?php echo $LPGame;?>" <?php echo $LPaGame['sSelect'];?>><?php echo $LPaGame['sText'];?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<!-- <div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['SITE'];?>
		</div>
		<div class="Sel">
			<select name="sSiteId">
				<option value=""><?php echo ALL;?></option>
				<?php
				#foreach ($aSite as $LPsSiteId => $LPaSite)
				#{
					?>
					<option value="<?php #echo $LPsSiteId;?>" <?php #echo $LPaSite['sSelect'];?>><?php #echo $LPaSite['sText'];?></option>
					<?php
				#}
				?>
			</select>
		</div>
	</div> -->
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['METHOD'];?>
		</div>
		<div class="Sel">
			<select name="sMethod">
				<option value=""><?php echo ALL;?></option>
				<?php
				foreach ($aMethod as $LPsMethod => $LPaMethod)
				{
					?>
					<option value="<?php echo $LPsMethod;?>" <?php echo $LPaMethod['sSelect'];?>><?php echo $LPaMethod['sText'];?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['DONE'];?>
		</div>
		<div class="Sel">
			<select name="nDone">
				<option value="-1"><?php echo ALL;?></option>
				<?php
				foreach ($aDone as $LPnStatus => $LPaStatus)
				{
					?>
					<option value="<?php echo $LPnStatus;?>" <?php echo $LPaStatus['sSelect'];?>><?php echo $LPaStatus['sText'];?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aLOTTERY['STATUS'];?>
		</div>
		<div class="Sel">
			<select name="nStatus">
				<option value="-1"><?php echo ALL;?></option>
				<?php
				foreach ($aStatus as $LPnStatus => $LPaStatus)
				{
					?>
					<option value="<?php echo $LPnStatus;?>" <?php echo $LPaStatus['sSelect'];?>><?php echo $LPaStatus['sText'];?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
</form>
<div class="Information MarginBottom20">
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
						<th><?php echo aLOTTERY['ID'];?></th>
						<th><?php echo aLOTTERY['TIME'];?></th>
						<!-- <th><?php #echo aLOTTERY['SITE'];?></th> -->
						<th><?php echo aLOTTERY['NAME0'];?></th>
						<!-- <th><?php #echo aLOTTERY['PA'];?></th> -->
						<th><?php echo aLOTTERY['NO'];?></th>
						<th><?php echo '莊家暱稱';?></th>
						<th><?php echo aLOTTERY['GAME'];?></th>
						<th><?php echo aLOTTERY['METHOD'];?></th>
						<th><?php echo aLOTTERY['NUMS'];?></th>
						<th><?php echo aLOTTERY['MONEY0'];?></th>
						<th><?php echo aLOTTERY['WIN'];?></th>
						<th><?php echo aLOTTERY['MONEY1'];?></th>
						<!-- <th><?php #echo aLOTTERY['FS'];?></th> -->
						<!-- <th><?php #echo aLOTTERY['PER'];?></th> -->
						<!-- <th><?php #echo aLOTTERY['PERMONEY'];?></th> -->
						<th><?php echo aLOTTERY['DONE'];?></th>
						<th><?php echo aLOTTERY['STATUS'];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aData'] as $LPsId => $LPaData)
					{
						?>
						<tr>
							<td><?php echo $LPaData['nId'];?></td>
							<td><?php echo $LPaData['sCreateTime'];?></td>
							<!-- <td><?php #echo $aSite[$LPaData['sSiteId']]['sText'];?></td> -->
							<td><?php echo $aUserName[$LPaData['nUid']];?></td>
							<!-- <td><?php #echo (!empty($aUid2Pa[$LPaData['nUid']])) ? $aUid2Pa[$LPaData['nUid']] : '';?></td> -->
							<td><?php echo $LPaData['sNo'];?></td>
							<td><?php echo $aBankerName[$LPaData['sNo']];?></td>
							<td><?php echo $LPaData['sGame'];?></td>
							<td class="FontGreen"><?php echo $LPaData['sMethod'];?></td>
							<td class="FontRed"><?php echo $LPaData['sNumsText'];?></td>
							<td><?php echo $LPaData['nMoney0'];?></td>
							<td><?php echo $LPaData['nWin'];?></td>
							<td><?php echo $LPaData['nMoney1'];?></td>
							<!-- <td><?php #echo $LPaData['nWater'];?></td> -->
							<!-- <td><?php #echo $LPaData['nPer'];?></td> -->
							<!-- <td><?php #echo $LPaData['nPerMoney'];?></td> -->
							<td><?php echo $LPaData['sDone'];?></td>
							<td class="<?php echo $LPaData['sClass'];?>"><?php echo $LPaData['sStatus'];?></td>
						</tr>
						<?php
					}
					?>
					<tr>
						<td colspan="8"><?php echo aLOTTERY['SUBTOTAL'];?></td>
						<td><?php echo number_format($aData['aSubTotal']['nMoney0'],3);?></td>
						<td><?php echo number_format($aData['aSubTotal']['nWin'],3);?></td>
						<!-- <td><?php #echo number_format($aData['aSubTotal']['nMoney1'],3);?></td> -->
						<!-- <td><?php #echo number_format($aData['aSubTotal']['nWater'],3);?></td> -->
						<td colspan="3"></td>
					</tr>

					<tr>
						<td colspan="8"><?php echo aLOTTERY['TOTAL'];?></td>
						<td><?php echo number_format($aData['aTotal']['nMoney0'],3);?></td>
						<td><?php echo number_format($aData['aTotal']['nWin'],3);?></td>
						<!-- <td><?php #echo number_format($aData['aTotal']['nMoney1'],3);?></td> -->
						<!-- <td><?php #echo number_format($aData['aTotal']['nWater'],3);?></td> -->
						<td colspan="3"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>