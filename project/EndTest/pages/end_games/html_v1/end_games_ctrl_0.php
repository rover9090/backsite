<?php

	$aData = json_decode($sData,true);
?>
<input name="sBankerStrAjax" type="hidden" value="<?php echo $sBankerStrJWT;# 去要排莊列表跟總投注額 ?>" data-url="<?php echo $aUrl['sBankerStr'];?>">
<!-- 編輯頁面 -->
<div class="Information">
	<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20" enctype="multipart/form-data">
		<div>
			<!-- <div class="Block MarginBottom20" >
				<div class="Sel">
					<select name="nGame">
						<?php
						if(false){
						foreach ($aGameSetting as $LPnId => $LPaDetail)
						{
							?>
							<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
							<?php
						}
						}
						?>
					</select>
				</div>
			</div>
			<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>"> -->
			<div class="BtnAny JqToggleHeader"><?php echo aGAMECTRL['HIDECTRL'];?></div>
			<!-- <div class="BtnAny JqStupidOut" data-showctrl="2">
				<span><?php echo '交換荷官';?></span>
			</div> -->
			<div class="BtnAny">
				<a class="FontWhite" href="<?php echo $aUrl['sAct'].'&nGame='.$nGame.'&sJWT='.$sNewDealerJWT;?>">
					<span><?php echo '交換荷官';?></span>
				</a>
			</div>
			<div class="BtnAny JqStupidOut" data-showctrl="showbanker">
				<span><?php echo '顯示排莊列表';?></span>
			</div>
			<div class="BtnAny JqStupidOut" data-showctrl="newbanker">
				<span><?php echo '替補當前莊家';?></span>
			</div>
			<div class="BtnAny JqStupidOut" data-showctrl="bankercontinue">
				<span><?php echo '莊家買官';?></span>
			</div>
			<div class="BtnAny JqStupidOut" data-showctrl="result0">
				<span><?php echo '莊家吃彩';?></span>
			</div>
			<div class="BtnAny JqStupidOut" data-showctrl="result0_fifth">
				<span><?php echo '莊家吃1/5彩';?></span>
			</div>
			<?php if($aData['nStatus'] === '2'){?>
			<div class="BtnAny JqStupidOut" data-showctrl="bankerleast">
				<span><?php echo '莊家最低額度打莊';?></span>
			</div>
			<?php }?>
			<?php foreach($aQuickMsg as $LPnId => $LPsMessage){ ?>
			<div class="BtnAny JqStupidOut" data-showctrl="quickmsg<?php echo $LPnId;?>">
				<span><?php echo '快捷'.$LPnId;?></span>
			</div>
			<?php }?>
			<?php if(false){?>
			<div class="BtnAny JqStupidOut" data-showctrl="shotvideo">
				<span><?php echo '直播截圖';?></span>
			</div>
			<?php }?>
		</div>
	</form>
</div>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="2">
	<input type="hidden" name="sJWT" value="<?php echo $sNewDealerJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
</form>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="99">
	<input type="hidden" name="sJWT" value="<?php echo $sNext99JWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="nStatus" value="99">
</form>
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0" enctype="multipart/form-data">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nStatus" value="<?php echo $aData['nNextStatus'];?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input class="JqStream" type="hidden" value="<?php echo $sStreamUrl;?>">
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
					<tbody>
						<tr>
							<th><?php echo aGAMECTRL['ROUND'];?></th>
							<th colspan="3"><?php echo $aData['sNo'];?></th>
							<th rowspan="4" id="video-player" class="mainVideoBox JqVideoBox" playsinline="">
							</th>
						</tr>
						<tr>
							<th><?php echo aGAMECTRL['NEXTROUND'];?></th>
							<th colspan="3"><?php echo $aData['sNo']+1;?></th>
						</tr>
						<tr>
							<th><?php echo aGAMECTRL['DEALER'];?></th>
							<th colspan="3"><?php echo $aDealer['sName0'];?></th>
						</tr>
						<tr>
							<th><?php echo '上把莊家';?></th>
							<th colspan="3"><?php echo $aPrevBanker[0]['sName0Money'];?></th>
						</tr>
						<tr>
							<th><?php echo '莊家名稱';?></th>
							<th colspan="3"><?php echo $aBanker['sName0'];?></th>
						</tr>
						<tr>
							<th><?php echo '新局時間';?></th>
							<th colspan="3"><?php echo $aData['sPreStartTime'];?></th>
							<th rowspan="16" class="JqBankerStr"><?php echo $aData['sBankerStr'];?></th>
						</tr>
						<tr>
							<th><?php echo '開始下注時間';?></th>
							<th colspan="3"><?php echo $aData['sStartTime'];?></th>
						</tr>
						<tr>
							<th><?php echo '下注結束/開始集資時間';?></th>
							<th colspan="3"><?php echo $aData['sBetEndTime'];?></th>
						</tr>
						<tr>
							<th><?php echo '集資結束時間';?></th>
							<th colspan="3"><?php echo $aData['sBankerEndTime'];?></th>
						</tr>
						<tr>
							<th><?php echo '開牌時間';?></th>
							<th colspan="3"><?php echo $aData['sEndTime'];?></th>
						</tr>
						<tr>
							<th><?php echo '結算時間';?></th>
							<th colspan="3"><?php echo $aData['sEndTime1'];?></th>
						</tr>
						<tr>
							<th><?php echo '結算圖片';?></th>
							<th colspan="3"><?php echo $aData['sImage'];?></th>
						</tr>
						<tr>
							<td><?php echo STATUS;?></td>
							<td colspan="3" class="<?php echo $aStatus[$aData['nStatus']]['sClass'];?>"><?php echo $aStatus[$aData['nStatus']]['sText'];?></td>
						</tr>
						<tr>
							<td><?php echo '限制投注';?></td>
							<td colspan="3"><?php echo $aLimit[$aData['sLimit']];?></td>
						</tr>
						<tr>
							<td><?php echo aGAMECTRL['CTRL'];?></td>
							<td colspan="3">
								<div class="BtnAny2 JqButtonCtrl"><?php echo $aNextStatus[$aData['nStatus']]['sText'];?></div>
							<?php
							if($aData['nNextStatus'] != 0 && false)
							{
							?>
								<div class="BtnAny JqButtonCtrl"><?php echo $aStatus[99]['sText'];?></div>
							<?php
							}
							?>
							</td>
						</tr>						
						<?php
						if($aData['nStatus'] == 0)
						{
						?>
							<tr>
								<td>
									<?php echo '開局結果';?>
									<br>
									<input type="hidden" name="sLimit" value="">
									<div class="BtnAny BtnAny2 JqStupidOut" data-showctrl="limit">
										<span><?php echo '不限注';?></span>
									</div>
								</td>
								<td>
									<?php echo '初A';?>
									<br>
									<div class="BtnAny JqStupidOut" data-showctrl="limitA">
										<span><?php echo '限A';?></span>
									</div>
								</td>
								<td>
									<?php echo '川B';?>
									<br>
									<div class="BtnAny JqStupidOut" data-showctrl="limitB">
										<span><?php echo '限B';?></span>
									</div>
								</td>
								<td>
									<?php echo '尾C';?>
									<br>
									<div class="BtnAny JqStupidOut" data-showctrl="limitC">
										<span><?php echo '限C';?></span>
									</div>
								</td>
							</tr>
						<?php
						}
						else if($aData['nStatus'] == 3 && empty($aData['aNums']))
						{
						?>
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
						<?php
						}
						else if(!empty($aData['aNums']))
						{
						?>
							<tr>
								<td>
									<?php echo '開局結果';?>
									<!-- <br>
									<div class="JqResult0Txt <?php echo $aData['aNums']['Z']==='0'?'FontGreen':'FontRed';?>" style="font-weight:bold;">
										<?php echo $aResultText['Z'.$aData['aNums']['Z']];?>
									</div> -->
								</td>
								<td>
									<?php echo '初A';?>
									<br>
									<?php echo $aResultText['A'.$aData['aNums']['A']];?>
								</td>
								<td>
									<?php echo '川B';?>
									<br>
									<?php echo $aResultText['B'.$aData['aNums']['B']];?>
								</td>
								<td>
									<?php echo '尾C';?>
									<br>
									<?php echo $aResultText['C'.$aData['aNums']['C']];?>
								</td>
							</tr>
						<?php }?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- 操作選項 -->
</form>
<div class="EditBtnBox">
	<div class="EditBtn JqStupidOut" data-showctrl="0" <?php echo $aData['nShot']?'data-shot="1"':'';?>>
		<i class="far fa-save"></i>
		<span><?php echo $aNextStatus[$aData['nStatus']]['sText'];?></span>
	</div>
	<div class="BtnAny2 JqStupidOut" data-showctrl="99">
		<span><?php echo '取消本局';?></span>
	</div>
</div>
