<div class="WindowBox JqWindowBox" data-kind="12">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit"><?php echo aCENTER['COINSET'];?></div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="12">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="coinBox">
					<div class="coinBoxTxt"><?php echo aCENTER['SEL5COIN'];?></div>
					<table class="changeImgTable">
						<tbody>
						<?php
						$nCount = 1;
						$nTdAmount = 6; #一列幾欄
						$nTotalAmount = 13; #資料總數
						for($nI=2;$nI<=$nTotalAmount;$nI++)
						{
							if($nCount%$nTdAmount==1)
							{
								echo '<tr class="changeImgTr">';
							}
							?>
								<td class="changeImgTd" style="width:calc(100%/<?php echo $nTdAmount;?>);">
									<label>
										<div class="changeImgBg">
											<div class="changeImgBlock">
												<img src="images/coin/<?php echo $nI;?>.png?t=<?php echo VTIME;?>" alt="">
											</div>
										</div>
										<div class="checkBlock">
											<input type="checkbox" class="JqCoinSet" data-file="<?php echo $nI;?>" data-clip="<?php echo $aSetCoinArray[$nI]['nValue'];?>">
										</div>
									</label>
								</td>
								<?php
									if($nCount%$nTdAmount==0)
									{
										echo '</tr>';
									}
									$nCount ++;
								}
								if($nTotalAmount%$nTdAmount!=0)
								{
									for($nAdd=1;$nAdd<=($nTdAmount-($nTotalAmount%$nTdAmount));$nAdd++)
									{
										echo '<td class="changeImgTd" style="width:calc(100%/'.$nTdAmount.');"></td>';
									}
									echo '</tr>';
								}
								?>
						</tbody>
					</table>
				</div>
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqCoinSetSubmit JqClose" value="<?php echo aCENTER['SAVE'];?>">
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