<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="sBetJWT" type="hidden" value="<?php echo $sBetJWT; ?>" data-url="<?php echo $aUrl['sBetAjax'];?>">
<input name="sDonateJWT" type="hidden" value="<?php echo $sDonateJWT; ?>" data-url="<?php echo $aUrl['sDonateAjax'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input name="sWatchDealerJWT" type="hidden" value="<?php echo $sWatchDealerJWT; ?>" data-url="<?php echo $aUrl['sWatchDealerAjax'];?>" data-dealer="<?php echo $aData[$nGame]['nDealer'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-mute="<?php echo $aUser['nMute'];?>" data-test="<?php echo $aUser['nTest'];?>">
<input name="aPlayerData" type="hidden" value='<?php echo json_encode($aPlayerData);?>'>
<input name="sSocketJWT" type="hidden" value='<?php echo $sSocketJWT;?>' data-json='<?php echo json_encode($aSocketPack);?>'>
<input class="JqBoardWidth_0" type="hidden" value="6">
<input class="JqRoad0Width_0" type="hidden" value="18">
<input class="JqRoad1Width_0" type="hidden" value="12">
<input class="JqRoad2Width_0" type="hidden" value="12">
<input class="JqRoad3Width_0" type="hidden" value="12">
<input class="JqBoardWidth_1" type="hidden" value="0">
<input class="JqRoad0Width_1" type="hidden" value="10">
<input class="JqRoad1Width_1" type="hidden" value="0">
<input class="JqRoad2Width_1" type="hidden" value="0">
<input class="JqRoad3Width_1" type="hidden" value="0">
<input class="JqIptCommission" type="hidden" value="0">
<input class="JqnSortIndex" type="hidden" value="<?php echo $nSortIndex;?>">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<input class="JqStream" type="hidden" value="<?php echo $sStreamUrl;?>">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php #echo json_encode($aNumsCounting);?>'>
	<input class="JqaBetLangs" type="hidden" value='<?php echo json_encode($aBetLangs);?>'>
	<input class="JqaFilter" type="hidden" value='<?php echo json_encode($aFilter);?>'>
	<input class="JqDefaultGames" type="hidden" value='<?php echo json_encode($aDefaultGames);?>'>
	<input class="JqPageGames" type="hidden" value='<?php echo json_encode($aPageGames);?>'>
</div>
<div class="JqCopyOtherMsg" style="display:none">
	<div class="chatroomChatBlock" data-uid="[[::nUid::]]">
		<div class="chatroomContBlock">
			<div class="chatroomCont">
				<div class="chatroomContTxt">[[::sName0::]]:[[::sMsg::]]</div>
			</div>
		</div>
	</div>
</div>
<div class="JqCopySelfMsg" style="display:none">
	<div class="chatroomChatBlock" data-uid="[[::nUid::]]">
		<div class="chatroomContBlock">
			<div class="chatroomCont">
				<div class="chatroomContTxt Self">[[::sName0::]]:[[::sMsg::]]</div>
			</div>
		</div>
	</div>
</div>
<div class="gameContainer">
	<div class="gameMainBox JqViewBox">
		<?php if(false){?>
		<!-- <div class="betLastBox BG DisplayBlockNone JqFinalRound">
			<div class="betLastTxt">最後一局</div>
		</div> -->
		<?php }?>

		<!-- 開牌區 -->
		<div class="betFinal1Box  JqFinalBox">
			<div class="betFinal1Inner">
				<div class="gambleVerNowBlock player">
					<div class="gambleVerNowTopic">
						<div class="gambleVerNowTopicNum JqPlayerGrade">0</div>
						<div class="gambleVerNowTopicTxt"><?php echo aBET['PL'];?></div>
					</div>
					<div class="gambleVerNowCardBox">
						<div class="gambleVerCard JqVerCard" data-num="<?php echo $aTableCard[0]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[0]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardP1 JqCard JqCard1 DisplayInlineBlock">
								<img src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardP1Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
						<div class="gambleVerCard JqVerCard" data-num="<?php echo $aTableCard[2]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[2]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardP2 JqCard JqCard1 DisplayInlineBlock">
								<img class="" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardP2Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
						<div class="gambleVerCard hor JqVerCard" data-num="<?php echo $aTableCard[4]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[4]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardP3 JqCard DisplayBlock">
								<img class="" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardP3Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
					</div>
				</div>
				<div class="gambleVerNowBlock bank">
					<div class="gambleVerNowTopic">
						<div class="gambleVerNowTopicTxt"><?php echo aBET['BK'];?></div>
						<div class="gambleVerNowTopicNum JqBankerGrade">0</div>						
					</div>
					<div class="gambleVerNowCardBox">
						<div class="gambleVerCard JqVerCard" data-num="<?php echo $aTableCard[1]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[1]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardB1 JqCard JqCard1 DisplayInlineBlock">
								<img class="" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardB1Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
						<div class="gambleVerCard JqVerCard" data-num="<?php echo $aTableCard[3]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[3]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardB2 JqCard JqCard1 DisplayInlineBlock">
								<img class="" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardB2Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
						<div class="gambleVerCard hor JqVerCard" data-num="<?php echo $aTableCard[5]['nSort'];?>">
							<div class="gambleVerCardNo JqVerCardNum DisplayBlockNone active"><?php echo $aTableCard[5]['nSort']+1;?></div>
							<div class="betFinal1Card JqCardB3 JqCard DisplayBlock">
								<img class="" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
								<img class="JqCardB3Src" src="" alt="">
							</div>
							<div class="gambleVerCardInfo JqVerCardInfo">
								<div class="gambleVerCardInfoTxt">i</div>
							</div>
						</div>
					</div>
				</div>			
			</div>			
		</div>

		<div class="Window2Box middle JqWindowBox" data-kind="verifyinfo">
			<div class="Window2Container">
				<div class="Window2Top">
					<div class="Window2TopTit">
						<div class="Window2TitTxt"><?php echo aBET['CHAINDESCRIPTION'];?></div>
					</div>
					<div class="Window2Cancel JqClose" data-kindctrl="verifyinfo">
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="Window2Content"></div>
			</div>
			<div class="Window2Bg"></div>
		</div>

		<div class="Window2Box verall middle JqWindowBox" data-kind="verifyall">
			<div class="Window2Container">
				<div class="Window2Top">
					<div class="Window2TopTit">
						<div class="Window2TitTxt"><?php echo aBET['THECARDS'];?>(<?php echo aBET['ENCRYPT'];?>)</div>
					</div>
					<div class="Window2Cancel JqClose" data-kindctrl="verifyall">
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="Window2Fontword">
					<div class="Window2FontwordTxt">
						<div><?php echo aBET['DESCRIPTION0'];?></div>
						<div><?php echo aBET['DESCRIPTION1'];?></div>
					</div>
				</div>
				<div class="Window2Content">
					<div class="verallBox">
						<div class="verallCardBox">
							<?php
							foreach($aDeck as $LPnSort => $LPaDeck)
							{
								if($LPaDeck['sCardCode'] == 'RED')
								{
									#某張紅色的
								?>
									<div class="verallCardBlock" data-num="<?php echo $LPnSort; ?>">
										<div class="verallCardBlockMain forbid">
											<div class="verallCardBlockImg BG" style="background-image: url('images/card/back.png');"></div>
										</div>
									</div>
								<?php
								}
								else
								{
									if($LPaDeck['nStatus'] === '1')
									{
								?>
									<div class="verallCardBlock JqVerCard JqCardBook" data-sCardCode="<?php echo $LPaDeck['sCardCode'];?>" data-num="<?php echo $LPnSort;?>" data-sCardKey="<?php echo $LPaDeck['sCardKey'];?>" data-sCardPublic="<?php echo $LPaDeck['sCardPublic'];?>">
										<div class="verallCardBlockNo"><?php echo $LPnSort+1; ?></div>
										<div class="verallCardBlockMain">
												<div class="verallCardBlockImg BG JqDivSrc" style="background-image: url('images/card/<?php echo $LPaDeck['sCardCode'];?>.png');"></div>
											<div class="verallCardBlockInfo">i</div>
										</div>
									</div>
									<?php
									}
									else
									{
									?>
									<div class="verallCardBlock JqVerCard JqCardBook" data-sCardCode="**" data-num="<?php echo $LPnSort; ?>" data-sCardKey="<?php echo $sCardKeyHide;?>" data-sCardPublic="<?php echo $LPaDeck['sCardPublic'];?>">
										<div class="verallCardBlockNo"><?php echo $LPnSort+1; ?></div>
										<div class="verallCardBlockMain">
												<div class="verallCardBlockImg BG JqDivSrc" style="background-image: url('images/card/back.png');"></div>
											<div class="verallCardBlockInfo">i</div>
										</div>
									</div>
									<?php
									}
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<div class="Window2Bg"></div>
		</div>
		
		<div class="Window2Box middle JqWindowBox" data-kind="verifycard">
			<div class="Window2Container">
				<div class="Window2Top">
					<div class="Window2TopTit">
						<div class="Window2TitTxt"><?php echo aBET['CARDVERIFY'];?></div>
					</div>
					<div class="Window2Cancel JqClose" data-kindctrl="verifycard">
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="Window2Content">
					<!--  驗證成功 verCardBox + success
						驗證失敗 verCardBox + error -->
					<div class="verCardBox JqVerifyResult">
						<div class="verCardBlock early">
							<div class="verCardBlockEarlyBox">
								<div class="verCardBlockEarlyCard">
									<div class="verCardBlockEarlyCardImg">
										<img class="JqCardImgSrc_Pick" src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
									</div>
									<div class="verCardBlockEarlyCardInfo">i</div>
								</div>
								<div class="verCardBlockEarlyNo">
									<div class="verCardBlockEarlyNoNum JqCardSort_Pick">078</div>
									<div class="verCardBlockEarlyNoBtn JqVerAll">
										<div class="verCardBlockEarlyNoBtnTxt"><?php echo aBET['WATCHCARD'];?></div>
									</div>
								</div>
							</div>
							<div class="verCardBlockDetail">
								<div class="verCardBlockTit">
									<i class="far fa-bell"></i>
									<div class="verCardBlockTitTxt"><?php echo aBET['PUBLISHPASSWORD'];?></div>
								</div>
								<div class="verCardBlockDetailTxt JqsCardPublic_Pick">46487684g4ds6g4sd8g74f6ds4g65d4g65df4gs65d4g65sd4g65sd4g65sd4g65sd4g65sd4g65sd4gf65sd4g65se47ge6r4g6e4g5arr143gv13v21z465v4z5v4fsdv54adf65v4v654asd65v4a65sfdvsdf</div>
								<div class="verCardBtnCopy">
									<div class="verCardBtnCopyTxt JqCopy_Public"><?php echo aBET['COPY'];?></div>
								</div>
							</div>
							<div class="verCardExplainBox">
								<div class="verCardExplain">
									<i class="fas fa-question-circle"></i>
								</div>
								<div class="verCardExplainBlok"><?php echo aBET['DESCRIPTION2'];?></div>
							</div>
						</div>
						<div class="verCardBlock">
							<div class="verCardBlockDecodeBox">
								<?php if(false){?>
								<div class="verCardBlockDecodeCard">
									<div class="verCardBlockDecodeNo">078</div>
									<div class="verCardBlockDecodeImg">
										<img src="images/card/back.png?t=<?php echo VTIME;?>" alt="">
									</div>
									<div class="verCardBlockEarlyCardInfo">i</div>
								</div>
								<?php }?>
								<div class="verCardBlockDecodeInf">
									<div class="verCardBlockTit">
										<i class="fas fa-key"></i>
										<div class="verCardBlockTitTxt"><?php echo aBET['CARDPOINT0'];?></div>
									</div>
									<div class="verCardBlockDetailTxt JqsCardKey_Pick"><?php echo $sCardKeyHide;?></div>                                                
									<?php
									if(false)
									{
										#已開牌之卡牌
									?>
										<div class="verCardBlockDecodeBtnBox">
											<div class="verCardBtnCopy">
												<div class="verCardBtnCopyTxt"><?php echo aBET['COPY'];?></div>
											</div>
											<div class="verCardBtnChange JaVerChange" data-num="">
												<div class="verCardBtnChangeTxt">試試別張牌</div>
											</div>
										</div>
									<?php
									}
									?>
								</div>                                    
							</div>
							<div class="verCardBlockDetail">
								<div class="verCardBlockTit">
									<i class="fas fa-unlock-alt"></i>
									<div class="verCardBlockTitTxt"><?php echo aBET['CARDPOINT1'];?></div>
								</div>
								<div class="verCardBlockDetailTxt"><?php echo aBET['DESCRIPTION3'];?></div>
							</div>
						</div>
					</div>
					<div class="verCardBtnBox">
						<!-- 
							可驗證時 verCardBtn + active
							驗證成功 verCardBox + success
							驗證失敗 verCardBox + error
						-->
						<div class="verCardBtn JqVerifyCard">
							<i class="fas fa-check-double"></i>
							<div class="verCardBtnTxt"><?php echo aBET['VERIFY'];?>/<?php echo aBET['SUCCESS'];?>/<?php echo aBET['FAIL'];?></div>
						</div>
						<?php if(false){?>
							<div class="verCardBtn">
								<i class="fas fa-undo"></i>
								<div class="verCardBtnTxt">重置</div>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
			<div class="Window2Bg"></div>
		</div>

		<!-- 視頻區，荷官休息時換為下方圖片(兩個不同時存在) -->
		<!-- <div class="gameVideoBox"></div> -->
		<div id="video-player" class="gameVideoBox blockchain" controls playsinline></div>

		<!-- 荷官休息時圖片 -->
		<div class="gameBreakBox BG JqDealerImage" style="background-image: url('images/break.jpg?t=<?php echo VTIME;?>');"></div>
		
		<!-- 左上方選單列表 -->
		<div class="betMenuBox Left">
			<a class="betMenuBlock JqBtnToggleClass" href="<?php echo $aUrl['sIndex'];?>">
				<div class="betMenuBtn origin">
					<img src="images/ICON/back.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="betMenuBtn pick">
					<img src="images/ICON/back_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</a>
			<div class="betMenuBlock icon JqVerAll">
				<i class="fas fa-check-double"></i>
			</div>
		</div>
		
		<!-- 右上方選單列表 -->
		<div class="betMenuBox Right">
			<div class="betMenuBlock JqBtnToggleClass">
				<div class="betMenuBtn origin">
					<img src="images/ICON/menu.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="betMenuBtn pick">
					<img src="images/ICON/close.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="betMenuSubBox">
					<div class="betMenuSubList JqWindowBtn" data-kindctrl="1">
						<div class="betMenuSubListIcon">
							<img src="images/ICON/rule.png?t=<?php echo VTIME;?>" alt="">
						</div>
						<div class="betMenuSubListTit"><?php echo aBET['RULE'];?></div>
						<div class="betMenuSubListArrow"><i class="fas fa-chevron-right"></i></div>
					</div>
					<div class="betMenuSubList JqWindowBtn" data-kindctrl="0">
						<div class="betMenuSubListIcon">
							<img src="images/ICON/music.png?t=<?php echo VTIME;?>" alt="">
						</div>
						<div class="betMenuSubListTit"><?php echo aBET['SOUND'];?></div>
						<div class="betMenuSubListArrow"><i class="fas fa-chevron-right"></i></div>
					</div>
					<div class="betMenuSubList JqWindowBtn" data-kindctrl="10">
						<div class="betMenuSubListIcon">
							<img src="images/ICON/net.png?t=<?php echo VTIME;?>" alt="">
						</div>
						<div class="betMenuSubListTit"><?php echo aBET['NETWORK'];?></div>
						<div class="betMenuSubListArrow"><i class="fas fa-chevron-right"></i></div>
					</div>
				</div>
			</div>
			<!-- <div class="betMenuBlock JqWindowBtn" data-kindctrl="10">
				<div class="betMenuBtn origin">
					<img src="images/ICON/net.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div> -->			
			<div class="betMenuBlock JqWindowBtn" data-kindctrl="11">
				<div class="betMenuBtn origin">
					<img src="images/ICON/quota.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<div class="betMenuBlock JqDonateBtn">
				<div class="betMenuBtn origin">
					<img src="images/ICON/donate.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<div class="betMenuBlock JqChatCtrl">
				<div class="betMenuBtn origin">
					<img src="images/ICON/chat.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="betMenuBtn pick">
					<img src="images/ICON/chat_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<div class="betMenuBlock JqBtnToggleClass JqCommission">
				<div class="betMenuBtn origin">
					<img src="images/ICON/exemption.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="betMenuBtn pick">
					<img src="images/ICON/exemption_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
		</div>
		
		<!-- 倒數(下注時出現，開牌時消失) -->
		<div class="gameTimeBox DisplayBlockNone JqClock">
			<div class="gameTimeBlock">
				<div class="gameTime"></div>
				<div class="gameTimeTxt JqClock_<?php echo $nGame;?>">0</div>
			</div>
			<div class="gameTotalBet DisplayBlockNone JqDayLastBlock">
				<div class="gameTotalBetTxt">
					<span><?php echo aBET['TODAYCOUNTDOWN'];?></span>
					<span class="JqDayLast">0</span>
				</div>
			</div>
		</div>
	</div>

	<div class="DisplayBlockNone JqDonateBox" data-selectitem="">
		<div class="donateBox">
			<div class="donateInner JqScrollAxisX">
				<div class="donateBlock JqDonateItem" data-num="<?php echo 100; ?>" data-item="1">
					<div class="donatePic">
						<img src="images/donate/<?php echo 1; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 100; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 200; ?>" data-item="2">
					<div class="donatePic">
						<img src="images/donate/<?php echo 2; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 200; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 300; ?>" data-item="3">
					<div class="donatePic">
						<img src="images/donate/<?php echo 3; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 300; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 500; ?>" data-item="4">
					<div class="donatePic">
						<img src="images/donate/<?php echo 4; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 500; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 1000; ?>" data-item="5">
					<div class="donatePic">
						<img src="images/donate/<?php echo 5; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 1000; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 2000; ?>" data-item="6">
					<div class="donatePic">
						<img src="images/donate/<?php echo 6; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 2000; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 3000; ?>" data-item="7">
					<div class="donatePic">
						<img src="images/donate/<?php echo 7; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 3000; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 5000; ?>" data-item="8">
					<div class="donatePic">
						<img src="images/donate/<?php echo 8; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 5000; ?></span>
					</div>
				</div>
				<div class="donateBlock JqDonateItem" data-num="<?php echo 10000; ?>" data-item="9">
					<div class="donatePic">
						<img src="images/donate/<?php echo 9; ?>.png" alt="">
					</div>
					<div class="donateMoney">
						<span>$</span>
						<span><?php echo 10000; ?></span>
					</div>
				</div>
			</div>
			<div class="donateCtrlBox">
				<div class="donateTotal">
					<div><?php echo aBET['SUMMONEY'];?></div>
					<div class="JqDonateMoney">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="donateBtn confirm JqDonateSubmit">
						<div class="donateBtnTxt"><?php echo aBET['CONFIRM'];?></div>
					</div>
					<div class="donateBtn clear JqDonateClear">
						<div class="donateBtnTxt"><?php echo aBET['CLEAR'];?></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="betMainBox">
		<div class="betPlayBox JqBetPlayerBox">			
			<div class="betContainer">
				<!-- 錢和關注荷官 -->
				<div class="moneyLoveBox">
					<div class="moneyIconBgBox">
						<div class="moneyIconBg">
							<div class="moneyIcon">$</div>
						</div>
						<div class="moneyTxt">
							<span><?php echo aBET['DOLLARS'];?></span>
							<span class="JqUserMoney"><?php echo $aUser['nMoney'];?></span>
						</div>
					</div>
					<?php 
					if(false)
					{
					?>
					<div class="loveBox">
						<!-- 點擊變色並加入到關注列表 -->
						<div class="loveBlock JqWatchDealer JqBtnToggleClass <?php echo $sWatchClass;?>">
							<div class="loveIcon"><i class="fas fa-heart"></i></div>
							<div class="loveTxt">關注荷官</div>
							<div class="loveName"><?php echo $aData[$nGame]['sName'];?></div>
						</div>
					</div>
					<?php
					}
					?>
				</div>
				<!-- 下注區 -->
				<div class="betAreaBox">
					<div class="betAreaBlock top">
						<!-- 藍字 + FontBlue；紅字 + FontRed；綠字 + FontGreen -->
						<div class="betArea JqCngBtn JqPlayerPairZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontBlue"><?php echo aBET['PP'];?></div>
							<div class="betAreaRate FontBlue">
								<span>1</span>
								<span>:</span>
								<span>11</span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqPlayerPairMoney">0</span>
									<span>/</span>
									<span class="JqPlayerPairCount">0</span>
								</div>
							</div>
							<div class="FontBlue JqPlayerPairBet JqBetBtn">0</div>
						</div>
						<div class="betArea JqCngBtn JqSixZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontYellow"><?php echo aBET['SIX'];?></div>
							<div class="betAreaRate FontYellow">
								<span>1</span>
								<span>:</span>
								<span>12</span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqSixMoney">0</span>
									<span>/</span>
									<span class="JqSixCount">0</span>
								</div>
							</div>
							<div class="FontYellow JqSixBet JqBetBtn">0</div>
						</div>
						<div class="betArea JqCngBtn JqBankerPairZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontRed"><?php echo aBET['BP'];?></div>
							<div class="betAreaRate FontRed">
								<span>1</span>
								<span>:</span>
								<span>11</span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqBankerPairMoney">0</span>
									<span>/</span>
									<span class="JqBankerPairCount">0</span>
								</div>
							</div>
							<div class="FontRed JqBankerPairBet JqBetBtn">0</div>
						</div>
					</div>
					<div class="betAreaBlock bottom">
						<!-- 點擊在betArea + active -->
						<div class="betArea JqCngBtn JqPlayerZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontBlue"><?php echo aBET['PL'];?></div>
							<div class="betAreaRate FontBlue">
								<span>1</span>
								<span>:</span>
								<span>1</span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqPlayerWinMoney">0</span>
									<span>/</span>
									<span class="JqPlayerWinCount">0</span>
								</div>
							</div>
							<div class="FontBlue JqPlayerWinBet JqBetBtn">0</div>
						</div>
						<div class="betArea JqCngBtn JqDrawZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontGreen"><?php echo aBET['DRAW'];?></div>
							<div class="betAreaRate FontGreen">
								<span>1</span>
								<span>:</span>
								<span>8</span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqDrawMoney">0</span>
									<span>/</span>
									<span class="JqDrawCount">0</span>
								</div>
							</div>
							<div class="FontGreen JqDrawBet JqBetBtn">0</div>
						</div>
						<div class="betArea JqCngBtn JqBankerZone" style="width:calc(100%/3);">
							<div class="betAreaTit FontRed"><?php echo aBET['BK'];?></div>
							<div class="betAreaRate FontRed">
								<span>1</span>
								<span>:</span>
								<span class="JqBWOdds"><?php echo ($aOdds['BW']['nOdds']-1);?></span>
								<span class="JqBWOdds1" style="display:none"><?php echo ($aOdds['BW']['nOdds1']-1);?></span>
							</div>
							<div class="betAreaCoinBox">
								<div class="betAreaCoinIcon">
									<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="betAreaCoinVal">
									<span class="JqBankerWinMoney">0</span>
									<span>/</span>
									<span class="JqBankerWinCount">0</span>
								</div>
							</div>
							<div class="FontRed JqBankerWinBet JqBetBtn">0</div>
						</div>
					</div>
				</div>			
				<!-- 文字狀態 -->
				<div class="betStatusBox JqStopBet DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
					<div class="betStatusMsg"><?php echo aBET['STOPBET'];?></div>
				</div>				
				<div class="betStatusBox JqShuffling DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
					<div class="betStatusMsg"><?php echo aBET['SHUFFLING'];?></div>
				</div>
				<div class="betStatusBox JqCancelRound DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
					<div class="betStatusMsg"><?php echo aBET['CANCELROUND'];?></div>
				</div>
				<div class="betStatusBox JqPauseRound DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
					<div class="betStatusMsg"><?php echo aBET['PAUSEBET'];?></div>
				</div>
			</div>
		</div>

		<!-- 籌碼區 -->
		<div class="betCoinBox JqCoinBox">
			<div class="JqCoinBox1" style="width:calc(100%/7*6);display: contents;">
				<?php
				// for($i=2;$i<=7;$i++)
				foreach($aCoinArrayMb as $i)
				{
					echo' <div class="betCoinBtn JqCoin JqCoinTdBtn JqCoinTdBtn'.$i.'" data-clip="'.$aClip[$i].'"  style="width:calc(100%/7);">
							<div class="betCoinBtnInner JqCoinInner">
								<div class="betCoinBtnPic JqCoinPic BG" style="background-image:url(\'images/coin/'.$i.'.png?t='.VTIME.'\');"></div>
							</div>
						</div>';
				}
				?>
			</div>
			<!-- 籌碼設置鈕 -->
			<div class="betCoinBtn pic" style="width:calc(100%/7);">
				<div class="betCoinBtnPic JqCoinPic JqWindowBtn BG" data-kindctrl="12" style="background-image:url('images/ICON/coin_btn.png?t=<?php echo VTIME;?>');"></div>
			</div>
		</div>
	</div>

	<!-- 按鈕區 -->  
	<div class="betCtrlBox JqCtrlBox">
		<div class="betCtrlBtn JqChangeBtn">
			<div class="betCtrlBtnIcon change">
				<img src="images/ICON/changPh.png?t=<?php echo VTIME;?>" alt="">
			</div>
			<div class="betCtrlBtnIconTxt"><?php echo aBET['CHANGE'];?></div>
		</div>
		<div class="betCtrlBtn JqWindowBtn JqConfirmBet" data-kindctrl="13">
			<div class="betCtrlBtnIcon">
				<img src="images/ICON/check.png?t=<?php echo VTIME;?>" alt="">
			</div>
			<div class="betCtrlBtnIconTxt"><?php echo aBET['CONFIRM'];?></div>
		</div>
		<div class="betCtrlBtn JqRepeatBet">
			<div class="betCtrlBtnIcon">
				<img src="images/ICON/repeat.png?t=<?php echo VTIME;?>" alt="">
			</div>
			<div class="betCtrlBtnIconTxt"><?php echo aBET['DUPLICATE'];?></div>
		</div>
		<div class="betCtrlBtn JqCancelBet">
			<div class="betCtrlBtnIcon">
				<img src="images/ICON/cancel.png?t=<?php echo VTIME;?>" alt="">
			</div>
			<div class="betCtrlBtnIconTxt JqCancelBet"><?php echo aBET['CANCEL'];?></div>
		</div>
	</div>

	<!-- 遊戲結果 -->
	<div class="gameRoadBox JqGameRoadBox">
		<!-- 棋盤路 -->
		<div class="gameRoad first JqMainRoadBox" data-col="6">
			<div class="RoadBox">
				<?php
				for($i=0;$i<6;$i++)
				{?>
					<div class="RoadRow">
						<?php for($j=0;$j<6;$j++){?>
						<div class="RoadItem JqRoadItem JqMainRoad JqBoard_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'JqNo'.($j*6+$i);?>"></div>											
						<?php }?>
					</div>
				<?php
				}
				?>
			</div>
		</div>
		<div class="gameRoad second">
			<div class="RoadBox big">
				<!-- 大路 -->
				<?php
				for($i=0;$i<6;$i++)
				{
				?>
					<div class="RoadRow">
						<?php for($j=0;$j<18;$j++){?>
						<div class="RoadItem JqRoadItem JqPatternRoad JqRoad0_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></div>
						<?php }?>
					</div>
				<?php
				}
				?>
			</div>
			<div class="RoadBox">
				<!-- 大眼仔路 + 小路 + 蟑螂路 -->
				<?php
				for($i=0;$i<6;$i++)
				{
				?>
					<div class="RoadRow">
						<?php for($j=0;$j<6;$j++){?>
						<div class="RoadItem JqRoadItem JqPatternRoad">
							<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
							<div class="RoadFourBox">
								<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
							</div>
						</div>
						<?php }?>
						<?php for($j=0;$j<6;$j++){?>
						<div class="RoadItem JqRoadItem JqPatternRoad">
							<div class="RoadFourBox">
								<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
								<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
							</div>
						</div>
						<?php }?>
						<?php for($j=0;$j<6;$j++){?>
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

	<!-- 遊戲資訊+問路按鈕 -->
	<div class="gameAnalyBox JqAnalyBox">
		<div class="gameAnalyItemBox">
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgRed">
					<div class="gameAnalyItemTxt"><?php echo aBET['BK'];?></div>
				</div>
				<div class="gameAnalyItemVal FontRedImp JqBankerWin" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['BW'];?></div>
			</div>
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgBlue">
					<div class="gameAnalyItemTxt"><?php echo aBET['PL'];?></div>					
				</div>
				<div class="gameAnalyItemVal FontBlueImp JqPlayerWin" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['PW'];?></div>
			</div>
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgGreenImp">
					<div class="gameAnalyItemTxt"><?php echo aBET['DRAW'];?></div>					
				</div>
				<div class="gameAnalyItemVal FontGreenImp JqDraw" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['DRAW'];?></div>
			</div>
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgRed">
					<div class="gameAnalyItemTxt"><?php echo aBET['BP'];?></div>
				</div>
				<div class="gameAnalyItemVal FontRedImp JqBankerPair" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['BP'];?></div>
			</div>
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgBlue">
					<div class="gameAnalyItemTxt"><?php echo aBET['PP'];?></div>
				</div>
				<div class="gameAnalyItemVal FontBlueImp JqPlayerPair" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['PP'];?></div>
			</div>
			<div class="gameAnalyItem">
				<div class="gameAnalyItemTit BgBlackImp">
					<div class="gameAnalyItemTxt"><?php echo aBET['SUM'];?></div>
				</div>
				<div class="gameAnalyItemVal FontBlack JqGamesAll" data-nGame="<?php echo $nGame;?>"><?php echo $aNumsCounting[$nGame]['PW']+$aNumsCounting[$nGame]['BW']+$aNumsCounting[$nGame]['DRAW'];?></div>
			</div>
		</div>
		<div class="gameAnalyAskBox">
			<div class="gameAskType blue JqAskBlock">
				<div class="gameAskTypeTit"><?php echo aBET['NEXTPL'];?></div>
				<div class="gameAskTypeIconBox">
					<div class="gameAskTypeIconBlock JqAskTypeSample">
						<div class="GameBigCircle Blue JqAskCircleH JqAskPW1"></div>
					</div>
					<div class="gameAskTypeIconBlock">
						<div class="GameSmallCircle BgBlue JqAskCircleS JqAsk JqAskPW2"></div>
					</div>
					<div class="gameAskTypeIconBlock">
						<div class="GameHo BG red JqAskLine JqAskPW3"></div>
					</div>
				</div>
			</div>
			<div class="gameAskType red JqAskBlock">
				<div class="gameAskTypeTit"><?php echo aBET['NEXTBK'];?></div>
				<div class="gameAskTypeIconBox">
					<div class="gameAskTypeIconBlock">
						<div class="GameBigCircle Red JqAskCircleH JqAskBW1"></div>
					</div>
					<div class="gameAskTypeIconBlock">
						<div class="GameSmallCircle BgRed JqAskCircleS JqAsk JqAskBW2"></div>
					</div>
					<div class="gameAskTypeIconBlock">
						<div class="GameHo BG blue JqAskLine JqAskBW3"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 聊天室 -->
	<div class="chatroomBox JqChatBox">
		<div class="chatroomContBox JqMainBox">
			<div class="chatroomChatBox JqMsgBox">
				<div class="chatroomAnchorBox JqShowArea">
					<?php
					foreach($aMsgData as $LPnOrder => $LPaMsg)
					{
					?>
					<div class="chatroomChatBlock" data-uid="<?php echo $LPaMsg['nUid'];?>">
						<div class="chatroomContBlock">
							<div class="chatroomCont">
								<div class="chatroomContTxt <?php echo ($aUser['nId'] == $LPaMsg['nUid'])?'Self':'';?>"><?php echo $aMebmer[$LPaMsg['nUid']]['sName0'];?>:<?php echo $LPaMsg['sMsg'];?></div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
				</div>
				<!-- <div class="chatroomChatBlock JqTest1">
					<div class="chatroomContBlock">
						<div class="chatroomCont">
							<div class="chatroomContTxt JqTest2">請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息</div>
						</div>
					</div>
				</div> -->
			<?php
				// for($i=1;$i<5;$i++)
				// {
				// 	echo '<div class="chatroomChatBlock Left">
				// 			<div class="chatroomContBlock">
				// 				<div class="chatroomCont">
				// 					<div class="chatroomContTxt">請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息</div>
				// 				</div>
				// 			</div>
				// 		</div>';
				// }
			?>
			<!-- 右邊 -->
			<!-- <div class="chatroomChatBlock Right">
				<div class="chatroomContBlock">
					<div class="chatroomCont">
						<div class="chatroomContTxt">請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息</div>
					</div>
				</div>
			</div> -->
		</div>
		<?php
		if(false)
		{
		?>
		<div class="chatroomSendBox">
			<table class="chatroomSendTable">
				<tbody>
					<tr>
						<td class="chatroomSendTd">
							<div class="chatroomSendText">
								<textarea name="sText" placeholder="<?php echo aBET['TYPEMESSAGE'];?>"></textarea>
								<div class="chatroomIcon">
									<i class="far fa-grin-alt"></i>
								</div>
							</div>
						</td>
						<td class="chatroomSendTd btn">
							<div class="sendBtnBlock">
								<div class="sendBtn">
									<img src="images/ICON/send.png?t=<?php echo VTIME;?>" alt="">
								</div> 
								<div class="sendBtnHover">
									<img src="images/ICON/send_act.png?t=<?php echo VTIME;?>" alt="">
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
		}
		?>
	</div>                                                
</div>

<!-- 換桌列表 -->
<div class="changeMenuBox JqChangeMenu" data-col="20">
	<!-- 置頂選單 -->
	<div class="indexMenuBox">
		<!-- 點選該頁面 class="indexMenuBtn" + active -->
		<div class="indexMenuBtnBox">
			<div class="indexMenuBtn active">
				<div class="indexMenuBtnTxt"><?php echo aBET['HOTTOP'];?></div>	
			</div>
			<div class="indexMenuBtn">
				<div class="indexMenuBtnTxt"><?php echo aBET['NICETOP'];?></div>	
			</div>
			<div class="indexMenuBtn">
				<div class="indexMenuBtnTxt"><?php echo aBET['BETTOP'];?></div>	
			</div>
		</div>
		<div class="changeMenuBtnClose JqCancel">
			<i class="fas fa-times"></i>
		</div>
	</div>
	<div class="changeBlockBox JqChangeBlockBox">
		<?php
		foreach($aData as $LPsKey => $LPaValue)
		{
		?>
		<!-- 正在的那桌 changeListBlock + active -->
		<a class="changeBlock JqChangeList <?php if($LPsKey == $nGame) echo 'active';?>" href="<?php echo $aUrl['sBet'].'&nGame='.$LPsKey;?>">
			<div class="changeTopBox">
				<div class="changeTitBox">
					<span><?php echo aBET['BACCARAT'];?></span>
					<span><?php echo $LPaValue['nGame'];?></span>
				</div>
				<div class="changeNumBox JqClock_<?php echo $LPaValue['nGame'];?>">00</div>
			</div>
			<div class="changeRoadBox">
				<div class="RoadBox">
					<?php
					for($i=0;$i<6;$i++)
					{
					?>
						<div class="RoadRow">
							<?php for($j=0;$j<10;$j++){?>
							<div class="RoadItem JqRoadItem JqRoad0_<?php echo $LPaValue['nGame'].'_sub';?> <?php echo 'x'.$j.' y'.$i;?>"></div>
							<?php }?>
							<?php if(false){?>
							<div class="RoadItem JqRoadItem">
								<!-- 藍圈: GameBigCircle class加Blue；紅圈加Red-->
								<div class="GameBigCircle Blue"></div>
								<!-- 藍點再加Blue；紅點加Red-->
								<div class="GameDot Blue"></div>
								<div class="GameDot Red"></div>
								<!-- 綠斜線-->
								<div class="GameHo BG green"></div>
							</div>
							<?php }?>
						</div>
					<?php
					}
					?>
				</div>
				<div class="RoomStatusBox ing JqOpenNow JqOpenNow_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt"><?php echo aBET['OPENING'];?></div>
				</div>
				<div class="RoomStatusBox now JqChangeNow">
					<div class="RoomStatusTxt"><?php echo aBET['LOCATE'];?></div>
				</div>
				<div class="RoomStatusBox shuffle JqShuffling_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt"><?php echo aBET['SHUFFLING'];?></div>
				</div>
				<div class="RoomStatusBox ing JqCloseRoom_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt"><?php echo aBET['MAINTENANCE'];?></div>
				</div>
				<div class="RoomStatusBox ing JqComingSoon_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt"><?php echo aBET['COMINGSOON'];?></div>
				</div>
			</div>			
		</a>
		<?php
		}
		?>
	</div>
</div>
<div class="jumpMsgBox JqJumpMsgBox JqWindowBox" data-kind="chatroom">
	<div class="jumpMsgContainer">
		<div class="jumpMsgInner">
			<div class="jumpMsgTop">
				<div class="jumpMsgTit"><?php echo aBET['TYPING'];?></div>
			</div>
			<div class="jumpMsgContent">
				<div class="chatroomSendBox">
					<table class="chatroomSendTable">
						<tbody>
							<tr>
								<td class="chatroomSendTd">
									<div class="chatroomSendText">
										<textarea name="sText" class="JqChat JqContent0" placeholder="<?php echo aBET['TYPEMESSAGE'];?>"></textarea>
										<div class="chatroomIcon">
											<i class="far fa-grin-alt"></i>
										</div>
									</div>
								</td>
								<td class="chatroomSendTd btn">
									<div class="sendBtnBlock JqSend">
										<div class="sendBtn ">
											<img src="images/ICON/send.png?t=<?php echo VTIME;?>" alt="">
										</div> 
										<div class="sendBtnHover">
											<img src="images/ICON/send_act.png?t=<?php echo VTIME;?>" alt="">
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<!-- <table class="jumpMsgBtnBox">
				<tbody>
					<tr>
						<td class="jumpMsgBtnCell" style="width:calc(100%/2);max-width:calc(100%/2);min-width:calc(100%/2);">
							<div class="jumpMsgBtn noOne WordBreakBreakAll JqClose" data-kindctrl="chatroom">
								<span class="jumpMsgBtnTxt">關閉</span>
							</div>
						</td>
						<td class="jumpMsgBtnCell" style="width:calc(100%/2);max-width:calc(100%/2);min-width:calc(100%/2);">
							<div class="jumpMsgBtn noOne WordBreakBreakAll">
								<input type="button" class="jumpMsgBtnTxt submit" onclick="$(this).attr('disabled',true);$('form[data-form=\'<?php #echo $LPnId;?>\']').submit();" value="送出">
							</div>
						</td>
					</tr>
				</tbody>
			</table> -->
		</div>
	</div>
	<div class="jumpMsgBg JqClose"></div>
</div>