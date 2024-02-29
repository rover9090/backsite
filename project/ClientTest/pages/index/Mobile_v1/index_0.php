<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sBetDetailJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetDetailAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sDonateHistoryAjax'];?>">
<input class="JqBoardWidth_0" type="hidden" value="6">
<input class="JqRoad0Width_0" type="hidden" value="12">
<input class="JqRoad1Width_0" type="hidden" value="12">
<input class="JqRoad2Width_0" type="hidden" value="12">
<input class="JqRoad3Width_0" type="hidden" value="12">
<input class="JqBoardWidth_1" type="hidden" value="6">
<input class="JqRoad0Width_1" type="hidden" value="12">
<input class="JqRoad1Width_1" type="hidden" value="12">
<input class="JqRoad2Width_1" type="hidden" value="12">
<input class="JqRoad3Width_1" type="hidden" value="12">
<input class="JqBoardHeight" type="hidden" value="6">
<input class="JqRoad0Height" type="hidden" value="6">
<input class="JqRoad1Height" type="hidden" value="6">
<input class="JqRoad2Height" type="hidden" value="6">
<input class="JqRoad3Height" type="hidden" value="6">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php echo json_encode($aNumsCounting);?>'>
	<input class="JqDefaultGames" type="hidden" value='<?php echo json_encode($aDefaultGames);?>'>
</div>
<div class="Container">
	<div class="SubMenuBox">
		<div class="SubMenuInner">
			<?php 
			foreach($aLobbyName as $LPnLobby => $LPaData)
			{
			?>
			<a class="SubMenuBtn <?php echo $LPaData['sActive'];?>" href="<?php echo $LPaData['sUrl'];?>">
				<div class="SubMenuBtnTxt" ><?php echo $LPaData['sName0'];?></div>
			</a>
			<?php }?>
			<!-- <a href="./" class="SubMenuBtn active">
				<div class="SubMenuBtnTxt">全部遊戲</div>
			</a>
			<a href="./" class="SubMenuBtn">
				<div class="SubMenuBtnTxt">百家樂</div>
			</a> -->
		</div>
	</div>
	<div class="ContainerHasMenu">
		<!-- 置頂選單 -->
		<div class="indexMenuBox">
			<div class="indexMenuBtnBox">
				<!-- 點選該頁面 class="indexMenuBtn" + active -->
				<div class="indexMenuBtn active">
					<div class="indexMenuBtnTxt"><?php echo aINDEX['HOTTOP'];?></div>	
				</div>
				<div class="indexMenuBtn">
					<div class="indexMenuBtnTxt"><?php echo aINDEX['NICETOP'];?></div>	
				</div>
				<div class="indexMenuBtn">
					<div class="indexMenuBtnTxt"><?php echo aINDEX['BETTOP'];?></div>	
				</div>
			</div>
		</div>
		<div class="indexGameMenuBox">
			<?php
			foreach($aData as $LPsKey => $LPaValue)
			{
				if($nIsLoginLobby > 0 && $LPaValue['nGame'][0] != $nIsLoginLobby)
				{
					continue;
				}
			?>
			<a class="indexGameBox" href="<?php echo $LPaValue['sBetUrl'];?>">
				<div class="indexGameTopBox">
					<div class="indexRoomBox">
						<div class="indexRoomBoxTxt">
							<span><?php echo aINDEX['BACCARAT'];?></span>
							<span><?php echo $LPaValue['nGame'];?></span>
						</div>
					</div>
					<div class="indexCountBox BG" style="background-image: url('images/countdown.png?t=<?php echo VTIME;?>');">
						<div class="indexCountInner">
							<span class="indexCountTit"><?php echo aINDEX['COUNTDOWN'];?></span>
							<span class="JqClock_<?php echo $LPaValue['nGame'];?>">00</span>
						</div>
					</div>
				</div>
				<div class="indexGameContBlock">
					<div class="indexGameContImg BG" style="background-image:url('images/dealer/Dealer<?php echo ($LPaValue['nGame']<2000)? $LPaValue['nGame']%1000:$LPaValue['nGame']%1000+4;?>.png?t=<?php echo VTIME;?>')">
						<?php
						if(!empty($LPaValue['sName']))
						{
						?>	
						<div class="indexGameImgName">
							<div class="indexGameImgTxt"><?php echo $LPaValue['sName'];?></div>
						</div>
						<?php
						}
						?>
					</div>
					<div class="indexDataBox JqRoadBox" data-col="18">
						<div class="RoadBox">
							<?php
							for($i=0;$i<6;$i++)
							{
							?>    <div class="RoadRow">
									<?php for($j=0;$j<6;$j++){?>
									<div class="RoadItem JqRoadItem JqBoard_<?php echo $LPaValue['nGame'];?> <?php echo 'JqNo'.($j*6+$i);?>"></div>
									<?php }?>
									<?php for($j=0;$j<12;$j++){?>
									<div class="RoadItem JqRoadItem JqRoad0_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></div>
									<?php }?>
								</div>
							<?php
							}
							?>
							<?php
							for($i=0;$i<3;$i++)
							{
							?>
								<div class="RoadRow">
									<?php for($j=0;$j<6;$j++){?>
									<div class="RoadItem JqRoadItem">
										<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
										<div class="RoadFourBox">
											<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
										</div>
									</div>
									<?php }?>
									<?php for($j=0;$j<6;$j++){?>
									<div class="RoadItem JqRoadItem">
										<div class="RoadFourBox">
											<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
										</div>
									</div>
									<?php }?>
									<?php for($j=0;$j<6;$j++){?>
									<div class="RoadItem JqRoadItem">
										<div class="RoadFourBox">
											<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
											<div class="RoadFourBlock JqRoadSep JqRoad3_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
										</div>
									</div>
									<?php }?>
								</div>
							<?php
							}
							?>
						</div>
					</div>
					<div class="RoomStatusBox ing JqOpenNow_<?php echo $LPsKey;?>">
						<div class="RoomStatusTxt"><?php echo aINDEX['OPENING'];?></div>
					</div>
					<div class="RoomStatusBox shuffle JqShuffling_<?php echo $LPsKey;?>">
						<div class="RoomStatusTxt"><?php echo aINDEX['SHUFFLING'];?></div>
					</div>
					<div class="RoomStatusBox already JqComingSoon_<?php echo $LPsKey;?>">
						<div class="RoomStatusTxt"><?php echo aINDEX['COMING'];?></div>
					</div>
					<div class="RoomStatusBox maintain JqCloseRoom_<?php echo $LPsKey;?> <?php echo $LPaValue['sClose'];?>">
						<div class="RoomStatusTxt"><?php echo aINDEX['MAINTENANCE'];?></div>
					</div>
				</div>
				<div class="indexGameInfoBox">
					<div class="indexGameInfoBlock">
						<div class="indexGameInfoTit"><?php echo aINDEX['BANKER'];?></div>
						<div class="indexGameInfoVal BgRed">
							<div class="indexGameInfoValTxt JqBankerWin" data-nGame="<?php echo $LPaValue['nGame'];?>"><?php echo $aNumsCounting[$LPaValue['nGame']]['BW'];?></div>
						</div>
					</div>
					<div class="indexGameInfoBlock">
						<div class="indexGameInfoTit"><?php echo aINDEX['DRAW'];?></div>
						<div class="indexGameInfoVal BgGreenImp">
							<div class="indexGameInfoValTxt JqDraw" data-nGame="<?php echo $LPaValue['nGame'];?>"><?php echo $aNumsCounting[$LPaValue['nGame']]['DRAW'];?></div>
						</div>
					</div>
					<div class="indexGameInfoBlock">
						<div class="indexGameInfoTit"><?php echo aINDEX['PLAYER'];?></div>
						<div class="indexGameInfoVal BgBlue">
							<div class="indexGameInfoValTxt JqPlayerWin" data-nGame="<?php echo $LPaValue['nGame'];?>"><?php echo $aNumsCounting[$LPaValue['nGame']]['PW'];?></div>
						</div>
					</div>
					<div class="indexGameInfoBlock">
						<div class="indexGameInfoTit"><?php echo aINDEX['SUM'];?></div>
						<div class="indexGameInfoVal BgBlack">
							<div class="indexGameInfoValTxt JqGamesAll" data-nGame="<?php echo $LPaValue['nGame'];?>"><?php echo $aNumsCounting[$LPaValue['nGame']]['PW']+$aNumsCounting[$LPaValue['nGame']]['DRAW']+$aNumsCounting[$LPaValue['nGame']]['BW'];?></div>
						</div>
					</div>
				</div>
				<!-- 開牌中或洗牌中出現 -->
				<!-- <div class="indexGameShowBg">
					<div class="indexGameShowBox">
						<div class="indexGameShowTxt">開牌中</div>
					</div>
				</div> -->
			</a>
			<?php
			}
			?>
		</div>
	</div>
</div>