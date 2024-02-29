<div class="WindowBox JqWindowBox" data-kind="11">
	<div class="WindowContainer">
		<div class="WindowTop record">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<!-- 當頁class WindowTopTd + active -->
						<td class="WindowTopTd limit">
							<div class="WindowTitBlock">
								<div class="WindowTit"><?php echo aCENTER['LIMIT'];?></div>
							</div>
						</td>
						<?php 
						foreach($aBetGroup as $LPnLid => $LPaData)
						{
							?>
							<td class="WindowTopTd limit JqBetGroup <?php echo $LPaData['sActive'];?>" data-nBetGroupId="<?php echo $LPnLid;?>" 
							data-BWnMoney0="<?php echo $LPaData['BW']['nMoney0'];?>" 
							data-BWnMoney1="<?php echo $LPaData['BW']['nMoney1'];?>"
							data-PWnMoney0="<?php echo $LPaData['PW']['nMoney0'];?>"
							data-PWnMoney1="<?php echo $LPaData['PW']['nMoney1'];?>"
							data-DRAWnMoney0="<?php echo $LPaData['DRAW']['nMoney0'];?>"
							data-DRAWnMoney1="<?php echo $LPaData['DRAW']['nMoney1'];?>"
							data-BPnMoney0="<?php echo $LPaData['BP']['nMoney0'];?>"
							data-BPnMoney1="<?php echo $LPaData['BP']['nMoney1'];?>"
							data-PPnMoney0="<?php echo $LPaData['PP']['nMoney0'];?>"
							data-PPnMoney1="<?php echo $LPaData['PP']['nMoney1'];?>"
							data-SIXnMoney0="<?php echo $LPaData['SIX']['nMoney0'];?>"
							data-SIXnMoney1="<?php echo $LPaData['SIX']['nMoney1'];?>"
							>
								<div class="WindowTitBlock">
									<div class="WindowTit"><?php echo $LPaData['sName0'];?></div>
								</div>
							</td>
							<?php 
						}
						?>
						<?php 
						if(false)
						{
							?>
							<td class="WindowTopTd limit">
								<div class="WindowTitBlock">
									<div class="WindowTit"><?php echo aCENTER['SETTING'];?></div>
								</div>
							</td>
							<?php 
						}
						?>
						<td class="WindowCancelTd limit">
							<div class="WindowCancel JqClose" data-kindctrl="11">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<!-- 限額 -->
				<div class="limitBox">
					<div class="infoBox">
						<table class="infoTable">
							<thead class="infoThead">
								<tr>
									<th class="infoTh"><?php echo aCENTER['NAME'];?></th>
									<th class="infoTh"><?php echo aCENTER['ODDS'];?></th>
									<th class="infoTh"><?php echo aCENTER['MINBET'];?></th>
									<th class="infoTh"><?php echo aCENTER['MAXBET'];?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="infoTd"><?php echo aCENTER['BK'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['BW']['nOdds']-1;?>/1:<?php echo $aOdds['BW']['nOdds1']-1;?></td>
									<td class="infoTd JqBWMoney0"><?php echo $aBetGroup[$nBetGroupId]['BW']['nMoney0'];?></td>
									<td class="infoTd JqBWMoney1"><?php echo $aBetGroup[$nBetGroupId]['BW']['nMoney1'];?></td>
								</tr>
								<tr>
									<td class="infoTd"><?php echo aCENTER['PL'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['PW']['nOdds']-1;?></td>
									<td class="infoTd JqPWMoney0"><?php echo $aBetGroup[$nBetGroupId]['PW']['nMoney0'];?></td>
									<td class="infoTd JqPWMoney1"><?php echo $aBetGroup[$nBetGroupId]['PW']['nMoney1'];?></td>
								</tr>
								<tr>
									<td class="infoTd"><?php echo aCENTER['DRAW'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['DRAW']['nOdds']-1;?></td>
									<td class="infoTd JqDRAWMoney0"><?php echo $aBetGroup[$nBetGroupId]['DRAW']['nMoney0'];?></td>
									<td class="infoTd JqDRAWMoney1"><?php echo $aBetGroup[$nBetGroupId]['DRAW']['nMoney1'];?></td>
								</tr>
								<tr>
									<td class="infoTd"><?php echo aCENTER['BP'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['BP']['nOdds']-1;?></td>
									<td class="infoTd JqBPMoney0"><?php echo $aBetGroup[$nBetGroupId]['BP']['nMoney0'];?></td>
									<td class="infoTd JqBPMoney1"><?php echo $aBetGroup[$nBetGroupId]['BP']['nMoney1'];?></td>
								</tr>
								<tr>
									<td class="infoTd"><?php echo aCENTER['PP'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['PP']['nOdds']-1;?></td>
									<td class="infoTd JqPPMoney0"><?php echo $aBetGroup[$nBetGroupId]['PP']['nMoney0'];?></td>
									<td class="infoTd JqPPMoney1"><?php echo $aBetGroup[$nBetGroupId]['PP']['nMoney1'];?></td>
								</tr>
								<tr>
									<td class="infoTd"><?php echo aCENTER['SIX'];?></td>
									<td class="infoTd">1:<?php echo $aOdds['SIX']['nOdds']-1;?></td>
									<td class="infoTd JqSIXMoney0"><?php echo $aBetGroup[$nBetGroupId]['SIX']['nMoney0'];?></td>
									<td class="infoTd JqSIXMoney1"><?php echo $aBetGroup[$nBetGroupId]['SIX']['nMoney1'];?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- 設定 -->
				<!-- display:none暫時隱藏，切換至設定需display:block，換上方limitBox隱藏 -->
				<div class="limitSetBox" style="display:none;">
					<table class="limitSetTable">
						<tbody>
							<tr class="limitSetTr">
								<!-- 點選class limitSetBlock + active -->
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
							</tr>
							<tr class="limitSetTr">
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
							</tr>
							<tr class="limitSetTr">
								<td class="limitSetTd" style="width:calc(100%/3);"></td>
								<td class="limitSetTd" style="width:calc(100%/3);">
									<div class="limitSetBlock JqLimit">
										<div class="limitSetTxt">10-1,000</div>
									</div>
								</td>
								<td class="limitSetTd" style="width:calc(100%/3);"></td>
							</tr>
						</tbody>
					</table>
					<div class="submitBtnBox">
						<table class="submitBtnTable">
							<tbody>
								<tr>
									<td class="submitBtnTd">
										<div class="submitBtn">
											<input type="submit" class="submitBtnTxt" value="<?php echo aCENTER['CONFIRM'];?>">
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>