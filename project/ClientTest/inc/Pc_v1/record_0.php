<div class="WindowBox JqWindowBox" data-kind="9">
	<div class="WindowContainer">
		<div class="WindowTop record">
			<form action="<?php echo './?'.$_SERVER['QUERY_STRING'].'&#record';?>" method="post">
				<table class="WindowTopTable">
					<tbody>
						<tr>
							<!-- 當頁class WindowTopTd + active -->
							<td class="WindowTopTd record JqBetRecord active">
								<div class="WindowTitBlock">
									<div class="WindowTit"><?php echo aCENTER['BETRECORD'];?></div>
								</div>
							</td>
							<td class="WindowTopTd record JqDonateRecord">
								<div class="WindowTitBlock">
									<div class="WindowTit"><?php echo aCENTER['DONATERECORD'];?></div>
								</div>
							</td>
							<td class="WindowSelTd">
								<?php if(false){?>
								<div class="Sel day">
									<select name="day" id="" onChange="this.form.submit()">
										<option value="1" <?php echo ($nDay==1)?'selected':'';?>>今天</option>
										<option value="2" <?php echo ($nDay==2)?'selected':'';?>>昨天</option>
									</select>
									<div class="SelDecro"></div>
								</div>
								<?php }?>
							</td>
							<td class="WindowCancelTd record">
								<div class="WindowCancel JqClose JqRecordClose" data-kindctrl="9">
									<i class="fas fa-times"></i>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div>
		<div class="WindowContent record JqRecord">
			<!-- 投注紀錄 -->
			<div class="recordBox JqBetRecordBox">
				<div class="infoBox">
					<table class="infoTable">
						<thead class="infoThead">
							<tr>
								<th class="infoTh">
									<div><?php echo aCENTER['IDS'];?></div>
									<div><?php echo aCENTER['BETTIME'];?></div>
								</th>
								<th class="infoTh">
									<div><?php echo aCENTER['BETMONEY'];?></div>
									<div><?php echo aCENTER['BETCONTENT'];?></div>
								</th>
								<th class="infoTh">
									<div><?php echo aCENTER['BETSTATUS'];?></div>
									<div><?php echo aCENTER['BETWIN'];?></div>
								</th>
							</tr>
						</thead>
						<tbody class="JqInfo">
						<?php
							// foreach($aRecordData['aBetData'] as $LPaData)
							if(false)
							// for($i=1;$i<7;$i++)
							{
							?>    <tr>
									<td class="infoTd"><?php echo $LPaData['nId'];?></td>
									<td class="infoTd"><?php echo $LPaData['sCreateTime'];?></td>
									<td class="infoTd"><?php echo $LPaData['nMoney0'];?></td>
									<td class="infoTd"><?php echo aBETCONTENT[$LPaData['sContent0']];?></td>
									<td class="infoTd"><?php echo $LPaData['nMoney0']+$LPaData['nMoney1'];?></td>
									<!-- 中獎class infoTd + FontOrange -->
									<?php if($LPaData['nMoney1'] > 0){?>
									<td class="infoTd FontOrange"><?php echo aCENTER['WIN'];?></td>
									<?php }else{?>
									<td class="infoTd"><?php echo aCENTER['LOSE'];?></td>
									<?php }?>
								</tr>
							<?php
							}
						?>
						</tbody>
						<tfoot class="infoTfoot">
							<tr>
								<td class="infoTf"><?php echo aCENTER['SUBTOTAL'];?></td>
								<td class="infoTf JqBetSubTotal"><?php echo 0; #echo $aRecordData['nBetSubTotal'];?></td>
								<td class="infoTf JqWinSubTotal"><?php echo 0;#echo $aRecordData['nWinSubTotal'];?></td>
							</tr>
							<tr>
								<td class="infoTf"><?php echo aCENTER['TOTAL'];?></td>
								<td class="infoTf JqBetTotal"><?php echo 0;?></td>
								<td class="infoTf JqBetTotal"><?php echo 0;?></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<!-- 資金流水 -->
			<!-- display:none暫時隱藏，切換至資金流水需display:block，換上方recordBox隱藏 -->
			<div class="moneyBox JqDonateRecordBox" style="display:none;">
				<div class="infoBox">
					<table class="infoTable">
						<thead class="infoThead">
							<tr>
								<th class="infoTh"><?php echo aCENTER['IDS2'];?></th>
								<th class="infoTh"><?php echo aCENTER['DONATETIME'];?></th>
								<th class="infoTh"><?php echo aCENTER['DONATEMONEY'];?></th>
								<th class="infoTh"><?php echo aCENTER['ADMNAME'];?></th>
							</tr>
						</thead>
						<tbody class="JqDonateInfo">
						<?php
							// foreach($aDonateData['aDonateData'] as $LPaData)
							if(false)
							{
							?>    <tr>
									<td class="infoTd"><?php #echo $LPaData['nId'];?></td>
									<td class="infoTd"><?php #echo $LPaData['sCreateTime'];?></td>
									<td class="infoTd"><?php #echo $LPaData['nMoney0'];?></td>
									<td class="infoTd"><?php #echo $aDealer[$LPaData['nDealer']]['sName0'];?></td>
								</tr>
							<?php
							}
						?>
						</tbody>
						<tfoot class="infoTfoot">
							<tr>
								<td class="infoTf"></td>
								<td class="infoTf"><?php echo aCENTER['SUBTOTAL'];?></td>
								<td class="infoTf JqnDonateSubTotal"><?php echo 0;#echo $aDonateData['nDonateSubTotal'];?></td>
								<td class="infoTf"></td>
							</tr>
							<tr>
								<td class="infoTf"></td>
								<td class="infoTf"><?php echo aCENTER['TOTAL'];?></td>
								<td class="infoTf JqnDonateTotal"><?php echo 0;?></td>
								<td class="infoTf"></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<?php #echo $aRecordPageList['sHtml'];?>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>