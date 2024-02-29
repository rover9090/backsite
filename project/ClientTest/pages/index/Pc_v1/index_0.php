<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sBetDetailJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetDetailAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input class="JqBoardWidth_0" type="hidden" value="8">
<input class="JqRoad0Width_0" type="hidden" value="20">
<input class="JqRoad1Width_0" type="hidden" value="40">
<input class="JqRoad2Width_0" type="hidden" value="20">
<input class="JqRoad3Width_0" type="hidden" value="20">
<input class="JqBoardWidth_1" type="hidden" value="6">
<input class="JqRoad0Width_1" type="hidden" value="6">
<input class="JqRoad1Width_1" type="hidden" value="6">
<input class="JqRoad2Width_1" type="hidden" value="6">
<input class="JqRoad3Width_1" type="hidden" value="6">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php echo json_encode($aNumsCounting);?>'>
	<input class="JqDefaultGames" type="hidden" value='<?php echo json_encode($aDefaultGames);?>'>
</div>
<div class="indexBox">
	<div class="indexContainer">
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
				<div class="indexGameMenuBlock">
					<a class="indexGameBox" href="<?php echo $LPaValue['sBetUrl'];?>">
						<div class="gameCenterTxtBox BG JqComingSoon_<?php echo $LPsKey;?> DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=20210618V011627074576');">
							<div class="gameCenterTxt"><?php echo aINDEX['COMING'];?></div>
						</div>
						<div class="gameCenterTxtBox BG JqCloseRoom_<?php echo $LPsKey;?> DisplayInlineBlockNone <?php echo $LPaValue['sClose'];?>" style="background-image: url('images/textBg.png?t=20210618V011627074576');">
							<div class="gameCenterTxt"><?php echo aINDEX['MAINTENANCE'];?></div>
						</div>
						<div class="indexGameTopBox">
							<div class="indexRoomBox">
								<div class="indexRoomBoxTxt">
									<span><?php echo aINDEX['BACCARAT'];?></span>
									<span><?php echo $LPaValue['nGame'];?></span>
								</div>
							</div>
							<div class="indexCountBox BG" style="background-image: url('images/countdown.png?t=<?php echo VTIME;?>');">
								<div class="indexCountTxt">
									<span><?php echo aINDEX['COUNTDOWN'];?></span>
									<span class="JqClock_<?php echo $LPaValue['nGame'];?>">00</span>
								</div>
							</div>
						</div>
						<div class="indexDataBox">
							<div class="indexGameContImg BG" style="background-image:url('images/dealer/Dealer<?php echo ($LPaValue['nGame']<2000)? $LPaValue['nGame']%1000:$LPaValue['nGame']%1000+4;?>.png?t=<?php echo VTIME;?>')">
								<div class="indexGameImgName">
									<div class="indexGameImgTxt"><?php echo $LPaValue['sName'];?></div>
								</div>
							</div>
							<!-- 棋盤路 -->
							<div class="indexGameContbig JqMainRoadBox" data-col="8">
								<div class="RoadBox">
									<?php
									for($i=0;$i<6;$i++)
									{
									?>    <div class="RoadRow">
											<?php for($j=0;$j<8;$j++){?>
											<div class="RoadItem JqRoadItem JqMainRoad JqBoard_<?php echo $LPaValue['nGame'];?> <?php echo 'JqNo'.($j*6+$i);?>" style="width:calc(100%/8);"></div>
											<?php }?>
										</div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="indexGameContSmall">
								<!-- 大路 -->
								<div class="RoadBox big">
									<?php
									for($i=0;$i<6;$i++)
									{
									?>
										<div class="RoadRow">
											<?php for($j=0;$j<20;$j++){?>
											<div class="RoadItem JqRoadItem JqPatternRoad JqRoad0_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>" style="width:calc(100%/20);"></div>
											<?php }?>
										</div>
									<?php
									}
									?>
								</div>
								<!-- 大眼仔路 -->
								<div class="RoadBox bigEye">
									<?php
									for($i=0;$i<3;$i++)
									{
									?>
										<div class="RoadRow">
											<?php for($j=0;$j<20;$j++){?>
											<div class="RoadItem JqRoadItem JqPatternRoad" style="width:calc(100%/20);">
												<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
												<div class="RoadFourBox">
													<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
												</div>
											</div>
											<?php }?>
										</div>
									<?php
									}
									?>
								</div>
								<!-- 小路(前10個indexGameSmallTd) + 蟑螂路(後10個indexGameSmallTd) -->
								<div class="RoadBox mix">
									<?php
									for($i=0;$i<3;$i++)
									{
									?>
										<div class="RoadRow">
											<?php for($j=0;$j<10;$j++){?>
											<div class="RoadItem JqRoadItem JqPatternRoad" style="width:calc(100%/10);">
												<div class="RoadFourBox">
													<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
													<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $LPaValue['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
												</div>
											</div>
											<?php }?>
											<!-- 斜線-->
											<?php for($j=0;$j<10;$j++){?>
											<div class="RoadItem JqRoadItem JqPatternRoad" style="width:calc(100%/10);">
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

							<div class="RoomStatusBox JqOpenNow_<?php echo $LPsKey;?>">
								<div class="RoomStatusTxt"><?php echo aINDEX['OPENING'];?></div>
							</div>
							<div class="RoomStatusBox shuffle JqShuffling_<?php echo $LPsKey;?>">
								<div class="RoomStatusTxt"><?php echo aINDEX['SHUFFLING'];?></div>
							</div>
						</div>
						<div class="indexGameInfoBox">
							<?php if(false){?>
							<div class="indexGameInfoTitTd">
								<div class="indexGameInfoTit">									
									<span>局數</span>
									<span>11</span>									
								</div>
							</div>
							<?php }?>
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
						<!-- 開牌中或洗牌中出現   72局進入洗牌中-->
						<div class="indexGameShowBg DisplayBlockNone JqShuffleDiv">
							<div class="indexGameShowBox">
								<div class="indexGameShowTxt"><?php echo aINDEX['SHUFFLING'];?></div>
							</div>
						</div>
					</a>
				</div>
			<?php
			}
			?>
		</div>
	</div>
</div>