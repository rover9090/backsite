<?php

	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
	<div>
		<div class="Block MarginBottom20" >
			<?php
				if(true)
				{
			?>
					<span class="InlineBlockTit"><?php echo aLOTTERY['GAMECHOOSE'];?></span>
					<div class="Sel">
						<select name="nGame">
							<?php
								foreach ($aGameSetting as $LPnGame => $LPaDetail)
								{
									?>
									<option value="<?php echo $LPnGame;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sText'];?></option>
							<?php
								}
								?>
						</select>
					</div>
					<!-- <div class="BtnAny JqToggleHeader">隱藏控制項</div> -->					
			<?php
				}
			?>

			<div class="Block MarginBottom20" >
				<span class="InlineBlockTit"><?php echo aLOTTERY['NO'];?></span>
				<div class="Ipt">
					<input type="text" name="sNo" value="<?php echo $sNo;?>" placeholder="<?php echo aLOTTERY['NO'];?>">
				</div>
			</div>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<?php if(!empty($aData['aNums'])){?>
		<div class="BtnAny JqStupidOut" data-showctrl="result0">
			<span><?php echo '莊家吃彩';?></span>
		</div>
		<?php }?>
	</div>
</form>
<?php
if(!empty($aData['aNums']))
{
?>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="2">
	<input type="hidden" name="sJWT" value="<?php echo $sRestoreJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="sNo" value="<?php echo $sNo;?>">
</form>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="1">
	<input type="hidden" name="sJWT" value="<?php echo $sResettleJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="sNo" value="<?php echo $sNo;?>">
	<input type="hidden" name="nResult0" value="0">
	<input type="hidden" name="nResult1" value="0">
	<input type="hidden" name="nResult2" value="0">
	<input type="hidden" name="nResult3" value="0">
</form>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="sNo" value="<?php echo $sNo;?>">

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
							<th><?php echo aLOTTERY['NOWNO'];?></th>
							<th colspan="3"><?php echo $aData['aNums']['sNo'];?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo STATUS;?></td>
							<td colspan="3" class="<?php echo $aStatus[$aData['aNums']['nStatus']]['sClass'];?>"><?php echo $aStatus[$aData['aNums']['nStatus']]['sText'];?></td>
						</tr>
						<tr>
							<th><?php echo aLOTTERY['NOWRESULT'];?></th>
							<td colspan="3" class="FontRed">
								<table>
									<tr>
										<td><?php echo $aData['aNums']['sNumsText'];?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td><?php echo aLOTTERY['RESULT'];?></td>
							<td colspan="3">
								<table>
									<tr>
										<td><?php echo $aData['aNums']['sResultText'];?></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<?php echo '開局結果';?>
								<br>
								<!-- <div class="JqResult0Txt FontGreen" style="font-weight:bold;">
									莊家沒中彩
								</div>
								<input type="hidden" name="nResult0" value="0"> -->
							</td>
							<td>
								<?php echo '初A';?>
								<br>
								<div class="IptRadio">
									<label class="SwitchWin">
										<input type="radio" name="nResult1" value="1">
										<?php echo 'A贏';?>
									</label>
									<label class="SwitchTie">
										<input type="radio" name="nResult1" value="2">
										<?php echo 'A和';?>
									</label>
									<label class="SwitchLose">
										<input type="radio" name="nResult1" value="0" checked>
										<?php echo 'A輸';?>
									</label>
								</div>
							</td>
							<td>
								<?php echo '川B';?>
								<br>
								<div class="IptRadio">
									<label class="SwitchWin">
										<input type="radio" name="nResult2" value="1" >
										<?php echo 'B贏';?>
									</label>
									<label class="SwitchTie">
										<input type="radio" name="nResult2" value="2" >
										<?php echo 'B和';?>
									</label>
									<label class="SwitchLose">
										<input type="radio" name="nResult2" value="0" checked>
										<?php echo 'B輸';?>
									</label>
								</div>
							</td>
							<td>
								<?php echo '尾C';?>
								<br>
								<div class="IptRadio">
									<label class="SwitchWin">
										<input type="radio" name="nResult3" value="1">
										<?php echo 'C贏';?>
									</label>
									<label class="SwitchTie">
										<input type="radio" name="nResult3" value="2">
										<?php echo 'C和';?>
									</label>
									<label class="SwitchLose">
										<input type="radio" name="nResult3" value="0" checked>
										<?php echo 'C輸';?>
									</label>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- 操作選項 -->
	<div class="EditBtnBox">
		<?php if($aData['aNums']['nStatus'] == 4 && $aData['aNums']['nStatus'] < 99){?>
		<div class="EditBtn JqStupidOut" data-showctrl="0">
			<i class="far fa-save"></i>
			<span><?php echo '還原並重過帳';?></span>
		</div>
		<div class="EditBtn JqStupidOut" data-showctrl="2">
			<i class="far fa-save"></i>
			<span><?php echo '帳務還原';?></span>
		</div>
		
		<?php }?>
		<?php if($aData['aNums']['nStatus'] == 6){?>		
		<div class="EditBtn JqStupidOut" data-showctrl="1">
			<i class="far fa-save"></i>
			<span><?php echo '重新過帳';?></span>
		</div>
		<?php }?>
	</div>
	
</form>
<?php
}
?>
<br>
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo '目前注單'; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aLOTTERY['NAME0'];?></th>
						<th><?php echo aLOTTERY['BETRESULT'];?></th>
						<th><?php echo aLOTTERY['MONEY0'];?></th>
						<th><?php echo aLOTTERY['MONEY1'];?></th>
						<th><?php echo aLOTTERY['BETTIME'];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aBet'] as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $aBetResult[$LPaDetail['sContent0']];?></td>
							<td><?php echo $LPaDetail['nMoney0'];?></td>
							<td><?php echo $LPaDetail['nMoney1'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
						</tr>
					<?php
					}
					?>
					<?php
					foreach ($aData['aBanker'] as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $aBetResult[$LPaDetail['sContent0']];?></td>
							<td><?php echo $LPaDetail['nMoney0'];?></td>
							<td><?php echo $LPaDetail['nMoney1'];?></td>
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo aLOTTERY['ALLBET']; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aLOTTERY['BETRESULT'];?></th>
						<th><?php echo aLOTTERY['COUNT'];?></th>
						<th><?php echo aLOTTERY['MONEY0'];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aBetMoney'] as $LPsType => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo aRESULTNAME[$LPsType];?></td>
							<td><?php echo $aData['aBetCount'][$LPsType];?></td>
							<td><?php echo $aData['aBetMoney'][$LPsType];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>