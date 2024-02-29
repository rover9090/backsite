<div class="WindowBox JqWindowBox" data-kind="13">
	<div class="WindowContainer">
		<div class="WindowContent bet">
			<form action="">
				<div class="betCheckBox">
					<div class="WindowTit"><?php echo aCENTER['BETLIST'];?></div>
					<div class="infoBox">
						<table class="infoTable">
							<thead class="infoThead">
								<tr>
									<th class="infoTh"><?php echo aCENTER['BETCONTENT'];?></th>
									<th class="infoTh"><?php echo aCENTER['BETMONEY'];?></th>
								</tr>
							</thead>
							<tbody class="JqBetCheckBoxContent">
							<?php
								if(false)
								{
								?>     <tr>
										<td class="infoTd">庄1:11</td>
										<td class="infoTd">100,000,000</td>
									</tr>
								<?php
								}
							?>
							</tbody>
							<tfoot class="infoTfoot">
								<tr>
									<td class="infoTf"><?php echo aCENTER['TOTAL'];?></td>
									<td class="infoTf JqBetCheckBoxSum">0</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn cancel JqClose" data-kindctrl="13">
										<div class="submitBtnTxt"><?php echo aCENTER['CANCEL'];?></div>
									</div>
								</td>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqSubmitBet" value="<?php echo aCENTER['BET'];?>">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>