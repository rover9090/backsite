<?php
	$aData = json_decode($sData,true);
?>
<input name="sNumsAjax0" type="hidden" value="<?php echo $sJWTNums; ?>" data-url="<?php echo $aUrl['sNumsAjax0'];?>">
<input name="nGame" type="hidden" value="<?php echo $nGame; ?>">
<!-- 編輯頁面 -->
<div id="JqEditGameTable">
	<div class="Information">
		<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom10">
			<div>
				<div class="Block" >
					<div class="Sel">
						<select name="nGame">
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
					<a href="javascript:void(0)" class="BtnAny JqReturnMain"  onclick="$('.JqMainPage').show();$('.JqSub0Page').hide();$(this).hide();" style="display:none;"><?php echo aMsg['BACK'];?></a>
					<div class="BtnAny JqToggleHeader"><?php echo aGAMECTRL['HIDECTRL'];?></div>
					<div class="endgamectrlInfoBox">
						<span>
							<span><?php echo aGAMECTRL['ADMID'];?>:</span>
							<span class="FontWeight600 special"><?php echo $aGame_Setting[$nGame]['nDealer'];?></span> ,
						</span>
						<span><?php echo aGAMECTRL['PRENO'];?>:<?php echo $aLastDoneNo['nRound']+1;?>,</span>
						<span><?php echo aGAMECTRL['NOWROUND'];?>:<?php echo $nNowRound;?>,</span>
						<span><?php echo aGAMECTRL['NOWNO'];?>:<?php echo $aData['sNo'];?>,</span>
						<span><?php echo aGAMECTRL['aRESULT']['1'];?>:{<?php echo $aPlayerStr['sDataText'];?>}</span>
						<span><?php echo aGAMECTRL['aRESULT']['0'];?>:{<?php echo $aBankerStr['sDataText'];?>}</span>
						<span><label><input class='JqRememberPage' type="checkbox" <?php echo !empty($aLockPage['sCheck'])?'CHECKED':'';?>><?php echo $aLockPage['sText'];?></label></span>
						<?php if(true){?>
						<span><label><input class='JqToggleRead' type="checkbox"><?php echo aGAMECTRL['OLDVER'];?></label></span>
						<?php }?>
					</div>					
				</div>
			</div>
		</form>
	</div>
	<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0" id="JqMainForm">
		<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
		<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
		<!-- <input type="hidden" name="nTotalRoom" value="<?php echo $nTotalRoom;?>"> -->
		<input type="hidden" name="nStatus" value="<?php echo 3;?>">
		<input type="hidden" name="sRoom" value="<?php echo '0';?>">
		<input type="hidden" name="sResult0" value="">
		<input type="hidden" name="sResult1" value="">
		<input type="hidden" class="JqEdit" name="nEdit" value="0">
		<input type="hidden" name="nLastCount" value="<?php echo ($nLastCount>0)?$nLastCount-1:0;?>">
		<input type="hidden" name="nBuild" value="<?php echo $nBuild;?>">
		<input type="hidden" name="nSettle" value="<?php echo $nSettle;?>">
		<input type="hidden" class="JqLotteryCancel" value="<?php echo $aUrl['sReSettle'];?>" data-sNo="<?php echo $aLastDoneNo['sNo'];?>">
		
		<div class="JqMainPage">
			<div class="endgameCtrlBox">				
				<div class="endgameCtrlBlock JqStupidOut red" data-showctrl="7" style="width:calc(100%/2);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['PAUSE'];?></div>
					</div>
				</div>
				<div class="endgameCtrlBlock JqSub0Open" style="width:calc(100%/2);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['EDITNUMS'];?></div>
					</div>
				</div>
				<?php if(false){?>
				<div class="endgameCtrlBlock JqStupidOut red" data-showctrl="1" style="width:calc(100%/3);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['CANCEL'];?></div>
					</div>
				</div>
				<div class="endgameCtrlBlock JqSub0Detect" style="width:calc(100%/4);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['CHECKING'];?></div>
					</div>
				</div>
				<?php }?>
				<div class="endgameCtrlBlock JqStupidOut spe <?php echo $sLastCountClass;?>" data-showctrl="2" style="width:calc(100%/5);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['COUNTDOWN'];?></div>
					</div>
					<div class="endgameCtrlBtnTxt2"><?php echo $nLastCount;?></div>
				</div>
				<div class="endgameCtrlBlock JqStupidOut spe <?php echo $sShufflingClass;?>" data-showctrl="3" style="width:calc(100%/5);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['SHUFFLE'];?></div>
					</div>
					<div class="endgameCtrlBtnTxt2"><?php echo '';?></div>
				</div>
				<div class="endgameCtrlBlock JqStupidOut spe <?php echo $sFinalRoundClass;?>" data-showctrl="4" style="width:calc(100%/5);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo aGAMECTRL['RED'];?></div>
					</div>
					<div class="endgameCtrlBtnTxt2"><?php echo '('.aGAMECTRL['LASTNO'].')';?></div>
				</div>
				<div class="endgameCtrlBlock JqStupidOut spe" data-showctrl="5" style="width:calc(100%/5);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt">
							<div><?php echo aGAMECTRL['CHGADM'];?></div>						
						</div>					
					</div>
					<div class="endgameCtrlBtnTxt2"><?php echo aGAMECTRL['TODAYDONATE'].$aDonateData[$aDealer['nId']]['nSum'];?></div>
				</div>
				<div class="endgameCtrlBlock JqStupidOut spe" data-showctrl="0" style="width:calc(100%/5);">
					<div class="endgameCtrlBtn">
						<div class="endgameCtrlBtnTxt"><?php echo $sConfirmMsg;?></div>
					</div>
					<div class="endgameCtrlBtnTxt2"><?php echo aGAMECTRL['NEXTROUND'].':'.$nNextRound;?></div>
				</div>
			</div>
		</div>

		<div class="Information">
			<div class="InformationScroll">
				<div class="InformationTableBox">
					<div style="display:none;" class="JqSub0Page">
						<table>
							<tr>								
								<td>
									<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[0];?></div>
									<div class="endgamesctrlPlayerPickBox JqBankerCard">
										<div class="endgamesctrlPlayerPick cursorPointer JqNums active" data-Card="" data-Who="Banker" data-Length="0">
											<img src="images/card/back.png" class="JqBanker0Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqNums" data-Card="" data-Who="Banker" data-Length="1">
											<img src="images/card/back.png" class="JqBanker1Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqNums" data-Card="" data-Who="Banker" data-Length="2" style="display:block">
											<img src="images/card/back.png" class="JqBanker2Src">
										</div>
									</div>
								</td>
								<td>
									<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[1];?></div>
									<div class="endgamesctrlPlayerPickBox JqPlayerCard">
										<div class="endgamesctrlPlayerPick cursorPointer JqNums" data-Card="" data-Who="Player" data-Length="0">
											<img src="images/card/back.png" class="JqPlayer0Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqNums" data-Card="" data-Who="Player" data-Length="1">
											<img src="images/card/back.png" class="JqPlayer1Src">
										</div>
										<div class="endgamesctrlPlayerPick cursorPointer JqNums" data-Card="" data-Who="Player" data-Length="2" style="display:block">
											<img src="images/card/back.png" class="JqPlayer2Src">
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="endgamesctrlAsBox">
										<div class="endgamesctrlAs" data-flo="C">
											<img src="images/card/Club.png">
										</div>
										<div class="endgamesctrlAs" data-flo="D">
											<img src="images/card/Diamond.png">
										</div>
										<div class="endgamesctrlAs" data-flo="H">
											<img src="images/card/Heart.png">
										</div>
										<div class="endgamesctrlAs" data-flo="S">
											<img src="images/card/Spade.png">
										</div>
									</div>
									<div class="endgamesctrlFlowerBox">
										<div class="endgamesctrlFlower cursorPointer JqCardA JqEnterCard">
											<img src="images/card/CA.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard2 JqEnterCard">
											<img src="images/card/C2.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard3 JqEnterCard">
											<img src="images/card/C3.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard4 JqEnterCard">
											<img src="images/card/C4.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard5 JqEnterCard">
											<img src="images/card/C5.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard6 JqEnterCard">
											<img src="images/card/C6.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard7 JqEnterCard">
											<img src="images/card/C7.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard8 JqEnterCard">
											<img src="images/card/C8.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard9 JqEnterCard">
											<img src="images/card/C9.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCard10 JqEnterCard">
											<img src="images/card/C10.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCardJ JqEnterCard">
											<img src="images/card/CJ.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCardQ JqEnterCard">
											<img src="images/card/CQ.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqCardK JqEnterCard">
											<img src="images/card/CK.png">
										</div>
										<div class="endgamesctrlFlower cursorPointer JqEnterCard">
											<img src="images/card/back.png">
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
	</form>
</div>
<!-- -->
<!-- 暫停投注 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="7">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nStatus" value="<?php echo 11;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
</form>
<!-- 取消當局99 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="1">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nStatus" value="<?php echo 99;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
</form>
<!-- 當日倒數把數 20=>19=>18-->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="2">
	<input type="hidden" name="sJWT" value="<?php echo $sLastCountJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="nLastCount" value="20">
</form>
<!-- 洗牌中(新局) 0<=>1-->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="3">
	<input type="hidden" name="sJWT" value="<?php echo $sNewDeckJWT;?>">
	<input type="hidden" name="nShuffling" value="<?php echo $nShuffling;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
</form>
<!-- 紅卡=1 只有0=>1-->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="4">
	<input type="hidden" name="sJWT" value="<?php echo $sRedCardJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
</form>
<!-- 換荷官 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="5">
	<input type="hidden" name="sJWT" value="<?php echo $sNewDealerJWT;?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="nDealer" value="">
</form>
<!-- 偵測牌型 -->
<form id="JqDetect"action="<?php echo $aUrl['sAct'];?>" method="post" data-form="6">
	<input type="hidden" name="sJWT" value="<?php echo '';?>">
	<input type="hidden" name="nGame" value="<?php echo $nGame;?>">
	<input type="hidden" name="nDealer" value="">
</form>