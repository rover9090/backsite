<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'].'&nSearch=1';?>" method="POST" class="MarginBottom20">
	<input type="hidden" name="sRankJWT" data-url="<?php echo $aUrl['sAjax0'];?>" data-acturl="<?php echo $aUrl['sAct'];?>" data-game="<?php echo $nGame;?>"value="<?php echo $sJWT;?>">
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
	<div>
		<div class="Ipt">
			<input type="text" name="sStartTime" class="JqStartTime" value="<?php echo $sStartTime;?>">
		</div>
		<span>~</span>
		<div class="Ipt">
			<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $sEndTime;?>">
		</div>
	</div>
	<div class="Block MarginBottom20" >
		<span class="InlineBlockTit"><?php echo aRANKING['PLSSELECT'];?></span>
		<div class="Sel">
			<select name="nGame">
				<option value="0"><?php echo aRANKING['NOCHOICE'];?></option>
				<?php
				foreach ($aGameSetting as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="Sel">
			<select name="nReloadTime">
			<?php
				foreach ($aReloadSetting as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
				}
			?>
			</select>
		</div>
	</div>
	<div class="MarginBottom10">
		<div class="InlineBlockTxt">
			<?php echo aRANKING['NAME0'];?>
		</div>
		<div class="Ipt">
			<input type="text" name="sAccount" value="<?php echo $sAccount;?>">
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>

<!-- Copy -->
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
						<th><?php echo aRANKING['ACCOUNT'];?></th>
						<th><?php echo aRANKING['USERNAME'];?></th>
						<th><?php echo aRANKING['AMOUNT'];?></th>
						<th><?php echo aRANKING['MONEY'];?></th>
						<th><?php echo aRANKING['WIN'];?></th>
						<th><?php echo aRANKING['LOSE'];?></th>
						<th><?php echo aRANKING['PROFIT'];?></th>
						<th><?php echo OPERATE;?></th>
					</tr>
				</thead>
				<tbody class="JqCurrentTable">
					<?php
					$i=0;
					foreach ($aRawData as $LPnUid => $LPaData)
					{
						if($i++ > $nTopCount)
						{
							break;
						}
					?>
						<tr data-uid="<?php echo $LPnUid;?>">
							<td class="<?php echo $aAllMember[$LPnUid]['sColor'];?>"><?php echo $aAllMember[$LPnUid]['sAccount'];?></td>
							<td class="<?php echo $aAllMember[$LPnUid]['sColor'];?>"><?php echo $aAllMember[$LPnUid]['sName0'];?></td>
							<td><?php echo $aData[$LPnUid]['nCount'];?></td>
							<td><?php echo $aData[$LPnUid]['nMoney'];?></td>
							<td><?php echo $aData[$LPnUid]['nWin'];?></td>
							<td><?php echo $aData[$LPnUid]['nLose'];?></td>
							<td><?php echo $aData[$LPnUid]['nProfit'];?></td>
							<td>
								<input type="button" class="BtnAny" onclick="myWindow=window.open('<?php echo $aUrl['sAct'].'&nId='.$LPnUid.'&nMute=1';?>')" value="<?php echo '禁言';?>">
								<input type="button" class="BtnAny" onclick="myWindow=window.open('<?php echo $aUrl['sAct'].'&nId='.$LPnUid.'&nBlack=1';?>')" value="<?php echo '黑名單';?>">
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