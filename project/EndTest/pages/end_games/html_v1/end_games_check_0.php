<?php
	$aData = json_decode($sData,true);
?>
<input name="sResultJWT" type="hidden" value="<?php echo $sJWT; ?>" data-url="<?php echo $aUrl['sResultAjax0'];?>">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<!-- 編輯頁面 -->
<div id="JqEditGameTable">
	<div class="Information">
		<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
			<div>
				<div class="Block MarginBottom20" >
					<div class="Sel">
						<select name="nGame" onchange="$('.JqOpenWindow').attr('onclick','myWindow=window.open(\''+$('.JqOpenWindow').attr('data-url')+'&nGame='+$(this).val()+'\',\'\',\'width=400,height=400\');')">
							<?php
							foreach ($aGame_Setting as $LPnId => $LPaDetail)
							{
								?>
								<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
								<?php
							}
							?>
						</select>
					</div>
					<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
					<input type="button" class="BtnAny JqOpenWindow" data-url="<?php echo $aUrl['sPage'];?>" onclick="myWindow=window.open('<?php echo $aUrl['sPage'].'&nGame='.$nGame;?>','','width=400,height=400');" class="BtnAny" value="<?php echo '開新視窗';?>">
					<div class="BtnAny JqToggleHeader">隱藏控制項</div>
					<a href="javascript:void(0)" class="BtnAny JqReturnMain"  onclick="$('.JqMainPage').show();$('.JqSub0Page').hide();$(this).hide();" style="display:none;">返回頁面</a>
					<span>荷官編號:<?php echo $aGame_Setting[$nGame]['nDealer'];?>,</span>
					<span>上期期數:<?php echo $aLastDoneNo['nRound']+1;?>,</span>
					<span>閒:{<?php echo $aPlayerStr['sDataText'];?>}</span>
					<span>莊:{<?php echo $aBankerStr['sDataText'];?>}</span>
				</div>
			</div>
		</form>
	</div>
		<div class="Information">
			<div class="InformationScroll">
				<div class="InformationTableBox">
					<div style="" class="JqSub0Page">
						<table>
							<tr>						
								<td>
									<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[1];?></div>
									<div class="endgamesctrlPlayerPickBox JqPlayerCard">
										<div class="endgamesctrlPlayerPick cursorPointer JqCardP1 JqNums active" data-Card="" data-Who="Player" data-Length="0">
											<img src="images/card/back.png" class="JqCardP1Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqCardP2 JqNums" data-Card="" data-Who="Player" data-Length="1">
											<img src="images/card/back.png" class="JqCardP2Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqCardP2 JqNums" data-Card="" data-Who="Player" data-Length="2" style="display:block">
											<img src="images/card/back.png" class="JqCardP3Src">
										</div>
									</div>
								</td>								
							</tr>
							<tr>
								<td>
									<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[0];?></div>
									<div class="endgamesctrlPlayerPickBox JqBankerCard">
										<div class="endgamesctrlPlayerPick cursorPointer JqCardB1 JqNums" data-Card="" data-Who="Banker" data-Length="0">
											<img src="images/card/back.png" class="JqCardB1Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqCardB2 JqNums" data-Card="" data-Who="Banker" data-Length="1">
											<img src="images/card/back.png" class="JqCardB2Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqCardB3 JqNums" data-Card="" data-Who="Banker" data-Length="2" style="display:block">
											<img src="images/card/back.png" class="JqCardB3Src">
										</div>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- 操作選項 -->
</div>