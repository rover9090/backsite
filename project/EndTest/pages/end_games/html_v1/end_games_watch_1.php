<input name="sResultJWT" type="hidden" value="<?php echo $sJWT; ?>" data-url="<?php echo $aUrl['sResultAjax0'];?>">
<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>">
<input class="JqBoardWidth_0" type="hidden" value="20">
<input class="JqRoad0Width_0" type="hidden" value="20">
<input class="JqRoad1Width_0" type="hidden" value="20">
<input class="JqRoad2Width_0" type="hidden" value="40">
<input class="JqRoad3Width_0" type="hidden" value="40">
<input class="JqBoardWidth_1" type="hidden" value="0">
<input class="JqRoad0Width_1" type="hidden" value="10">
<input class="JqRoad1Width_1" type="hidden" value="0">
<input class="JqRoad2Width_1" type="hidden" value="0">
<input class="JqRoad3Width_1" type="hidden" value="0">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<input class="JqnRead" type="hidden" value="<?php echo $nRead;?>">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php echo json_encode($aNumsCounting);?>'>
</div>

<div class="Information">
	<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
		<div>
			<div class="Block MarginBottom20" >
				<div class="Sel">
					<select name="nGame" onchange="$('.JqOpenWindow').attr('onclick','myWindow=window.open(\''+$('.JqOpenWindow').attr('data-url')+'&nGame='+$(this).val()+'\',\'\');')">
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
				<input type="button" class="BtnAny JqOpenWindow" data-url="<?php echo $aUrl['sPage'];?>" onclick="myWindow=window.open('<?php echo $aUrl['sPage'].'&nGame='.$nGame;?>','');" class="BtnAny" value="<?php echo aLOTTERY['OPENWINDOW'];?>">				<div class="BtnAny JqToggleHeader"><?php echo aLOTTERY['HIDECTRL'];?></div>
				<div class="BtnAny JqToggleHint">
					<span class="DisplayInlineBlockNone active"><?php echo aLOTTERY['SHOWHINT'];?></span>
					<span class="DisplayInlineBlockNone "><?php echo aLOTTERY['CLOSEHINT'];?></span>
				</div>
				<div class="endgamectrlInfoBox">
					<span><?php echo aLOTTERY['NOWNO'];?><span class="JqNo"></span>,</span>
					<span><?php echo aLOTTERY['NOWROUND'];?><span class="JqRound"></span>,</span>
					<span class="JqNOWDATE"></span>
					<?php if(true){?>
					<span><label><input class='JqToggleRead' type="checkbox"><?php echo aLOTTERY['OLDVER'];?></label></span>
					<?php }?>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="endgamewatchBox JqBotBlock">
	<div class="InformationTableBox">
		<table>
			<tr>	
				<td>
					<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[0];?></div>
					<div class="endgamesctrlPlayerPickBox JqBankerCard">
						<div class="endgamesctrlPlayerPick cursorPointer JqCardB0 JqNums" data-Card="" data-Who="Banker" data-Length="0">
							<img src="images/card/back.png" class="JqCardB0Src">
						</div>
						<div class="endgamesctrlPlayerPick cursorPointer JqCardB1 JqNums" data-Card="" data-Who="Banker" data-Length="1">
							<img src="images/card/back.png" class="JqCardB1Src">
						</div>
						<div class="endgamesctrlPlayerPick cursorPointer JqCardB2 JqNums" data-Card="" data-Who="Banker" data-Length="2" style="display:block">
							<img src="images/card/back.png" class="JqCardB2Src">
						</div>
					</div>
				</td>								
				<td>
					<div class="endgamesctrlPlayerPickTopic"><?php echo $aTextareaHint[1];?></div>
					<div class="endgamesctrlPlayerPickBox JqPlayerCard">
						<div class="endgamesctrlPlayerPick cursorPointer JqCardP0 JqNums" data-Card="" data-Who="Player" data-Length="0">
							<img src="images/card/back.png" class="JqCardP0Src">
						</div>
						<div class="endgamesctrlPlayerPick cursorPointer JqCardP1 JqNums" data-Card="" data-Who="Player" data-Length="1">
							<img src="images/card/back.png" class="JqCardP1Src">
						</div>
						<div class="endgamesctrlPlayerPick cursorPointer JqCardP2 JqNums" data-Card="" data-Who="Player" data-Length="2" style="display:block">
							<img src="images/card/back.png" class="JqCardP2Src">
						</div>
					</div>
				</td>					
			</tr>
		</table>
	</div>
</div>
<div class="endgamewatchInner">
	<!-- 問路按鈕 -->
	<div class="gameAskBox">
		<div class="gameAskBlock blue JqAskBlock">
			<div class="gameAskTit"><?php echo aLOTTERY['NEXT0'];?></div>
			<div class="gameAskIconBox">
				<div class="gameAskIconBlock">
					<div class="GameBigCircle Blue JqAskPW1"></div>
				</div>
				<div class="gameAskIconBlock">
					<div class="GameSmallCircle BgBlue JqAskPW2"></div>
				</div>
				<div class="gameAskIconBlock">
					<div class="GameHo BG red JqAskPW3"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="gameRoadBox JqRoadOuterBox" data-col="40">
		<!-- 棋盤路 -->
		<div class="gameRoadBlock RoadBox first">
			<?php
			for($i=0;$i<6;$i++)
			{
			?>    <div class="RoadRow">
					<?php for($j=0;$j<20;$j++){?>
					<div class="RoadItem JqRoadItem JqMainRoad JqBoard_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'JqNo'.($j*6+$i);?>"></div>
					<?php }?>
				</div>
			<?php
			}
			?>
		</div>
		<!-- 大路 -->
		<div class="gameRoadBlock RoadBox second">
			<?php
			for($i=0;$i<6;$i++)
			{
			?>
				<div class="RoadRow">
					<?php for($j=0;$j<20;$j++){?>
					<div class="RoadItem JqRoadItem JqBigRoad JqRoad0_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></div>
					<?php }?>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>
<div class="endgamewatchInner">
	<div class="gameAskBox">
		<div class="gameAskBlock red JqAskBlock">
			<div class="gameAskTit"><?php echo aLOTTERY['NEXT1'];?></div>
			<div class="gameAskIconBox">
				<div class="gameAskIconBlock">
					<div class="GameBigCircle Red JqAskBW1"></div>
				</div>
				<div class="gameAskIconBlock">
					<div class="GameSmallCircle BgRed JqAskBW2"></div>
				</div>
				<div class="gameAskIconBlock">
					<div class="GameHo BG blue JqAskBW3"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="gameRoadBox JqRoadOuterBox" data-col="40">			
		<div class="gameRoadBlock RoadBox third">
			<!-- 大眼仔路 -->
			<?php
			for($i=0;$i<6;$i++)
			{
			?>
				<div class="RoadRow">
					<?php for($j=0;$j<20;$j++){?>
					<div class="RoadItem JqRoadItem JqPatternRoad JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></div>
					
					<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
					<!-- <div class="RoadItem JqRoadItem JqPatternRoad">
						<div class="RoadFourBox">
							<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
						</div>
					</div> -->
					<?php }?>
				</div>
			<?php
			}
			?>
		</div>
		<div class="gameRoadBlock RoadBox ">
			<!-- 小路(前8個gameGridTd) -->
			<?php
			for($i=0;$i<3;$i++)
			{
			?>
				<div class="RoadRow">
					<?php for($j=0;$j<20;$j++){?>
					<div class="RoadItem JqRoadItem JqPatternRoad">
						<div class="RoadFourBox">
							<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
						</div>
					</div>
					<?php }?>
				</div>
			<?php
			}
			?>
			<!-- 蟑螂路(後8個gameGridTd) -->
			<?php
			for($i=0;$i<3;$i++)
			{
			?>
				<div class="RoadRow">
					<!-- 斜線-->
					<?php for($j=0;$j<20;$j++){?>
					<div class="RoadItem JqRoadItem JqPatternRoad">
						<div class="RoadFourBox">
							<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
							<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
						</div>
					</div>
					<?php }?>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>