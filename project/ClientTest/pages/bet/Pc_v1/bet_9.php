<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="sBetJWT" type="hidden" value="<?php echo $sBetJWT; ?>" data-url="<?php echo $aUrl['sBetAjax'];?>">
<input name="sDonateJWT" type="hidden" value="<?php echo $sDonateJWT; ?>" data-url="<?php echo $aUrl['sDonateAjax'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input name="sWatchDealerJWT" type="hidden" value="<?php echo $sWatchDealerJWT; ?>" data-url="<?php echo $aUrl['sWatchDealerAjax'];?>" data-dealer="<?php echo $aData[$nGame]['nDealer'];?>">
<input name="aRoomData" type="hidden" data-name="<?php echo $aRoomData['sName'];?>" data-password="<?php echo $aRoomData['sPassword'];?>" data-host="<?php echo $aRoomData['sHost'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-mute="<?php echo $aUser['nMute'];?>">
<input name="aPlayerData" type="hidden" value='<?php echo json_encode($aPlayerData);?>'>
<input name="sSocketJWT" type="hidden" value='<?php echo $sSocketJWT;?>' data-json='<?php echo json_encode($aSocketPack);?>'>
<input class="JqBoardWidth_0" type="hidden" value="12">
<input class="JqRoad0Width_0" type="hidden" value="16">
<input class="JqRoad1Width_0" type="hidden" value="32">
<input class="JqRoad2Width_0" type="hidden" value="16">
<input class="JqRoad3Width_0" type="hidden" value="16">
<input class="JqBoardWidth_1" type="hidden" value="0">
<input class="JqRoad0Width_1" type="hidden" value="10">
<input class="JqRoad1Width_1" type="hidden" value="0">
<input class="JqRoad2Width_1" type="hidden" value="0">
<input class="JqRoad3Width_1" type="hidden" value="0">
<input class="JqIptCommission" type="hidden" value="0">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php #echo json_encode($aNumsCounting);?>'>
	<input class="JqaBetLangs" type="hidden" value='<?php echo json_encode($aBetLangs);?>'>
	<input class="JqaFilter" type="hidden" value='<?php echo json_encode($aFilter);?>'>
</div>
<div class="JqCopyOtherMsg" style="display:none">
	<div class="chatroomChatBlock Left" data-uid="[[::nUid::]]">
		<div class="chatroomContBlock">
			<div class="chatroomCont">
				<div class="chatroomContTxt">[[::sName0::]]:[[::sMsg::]]</div>
			</div>
		</div>
	</div>
</div>
<div class="JqCopySelfMsg" style="display:none">
	<div class="chatroomChatBlock Right" data-uid="[[::nUid::]]">
		<div class="chatroomContBlock">
			<div class="chatroomCont">
				<div class="chatroomContTxt">[[::sName0::]]:[[::sMsg::]]</div>
			</div>
		</div>
	</div>
</div>
<div class="JqSampleChip" style="display:none">
	<div class="gambleOptBlockPlayerChipImg">
		<img src="images/coin/empty.png" alt="">
	</div>
	<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">100000</div>
</div>
<div class="gameBox">
	<div class="gameMainBox JqGameMainBox">
		<div class="gameMainContainer">
			<!-- 視頻區，荷官休息時換為下方圖片(兩個不同時存在) -->
			<!-- <div id="video-player" class="gameVideoBox"></div> -->
			<div id="id_local_video" style="width:100%;height:500px;display:flex;align-items:center;justify-content:center;"></div>

			<!-- 荷官休息時圖片 -->
			<div class="gameBreakBox BG JqDealerImage" style="background-image: url('images/break.jpg?t=<?php echo VTIME;?>');"></div>
			<!-- 左上方遊戲資訊 -->
			<div class="betInfoBox JqInfoBox">
				<!-- 簡略 -->
				<div class="betInfoBlock outline">
					<div class="betInfoBtnSwitch JqInfoIcon">
						<img src="images/ICON/repeat_1.png?t=<?php echo VTIME;?>" alt="">
					</div>
					<div class="betInfoBlockBox">
						<div class="betInfoList BgBlack">
							<div class="betInfoTit">局數</div>
							<div class="betInfoVal JqGamesAll" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['BW']+$aNumsCounting[$nGame]['PW']+$aNumsCounting[$nGame]['DRAW'];?></div>
						</div>
						<div class="betInfoList BgBlue">
							<div class="betInfoTit">閑</div>
							<div class="betInfoVal JqPlayerWin" data-nGame="<?php echo $nGame;?>"><?php #echo (int)$aNumsCounting[$nGame]['PW'];?></div>
						</div>
						<div class="betInfoList BgGreenImp">
							<div class="betInfoTit">和</div>
							<div class="betInfoVal JqDraw" data-nGame="<?php echo $nGame;?>"><?php #echo (int)$aNumsCounting[$nGame]['DRAW'];?></div>
						</div>
						<div class="betInfoList BgRed">
							<div class="betInfoTit">庄</div>
							<div class="betInfoVal JqBankerWin" data-nGame="<?php echo $nGame;?>"><?php #echo (int)$aNumsCounting[$nGame]['BW'];?></div>
						</div>
						<div class="betInfoList BgBlue">
							<div class="betInfoTit">閑對</div>
							<div class="betInfoVal JqPlayerPair" data-nGame="<?php echo $nGame;?>"><?php #echo (int)$aNumsCounting[$nGame]['PP'];?></div>
						</div>
						<div class="betInfoList BgRed">
							<div class="betInfoTit">庄對</div>
							<div class="betInfoVal JqBankerPair" data-nGame="<?php echo $nGame;?>"><?php #echo (int)$aNumsCounting[$nGame]['BP'];?></div>
						</div>
					</div>
				</div>
				<!-- 詳細 -->
				<div class="betInfoBlock detail">
					<div class="betInfoBtnSwitch JqInfoIcon">
						<img src="images/ICON/repeat.png?t=<?php echo VTIME;?>" alt="">
					</div>
					<div class="betInfoDetail">
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">庄</div>
							<div class="betInfoDetailListVal">
								<span class="JqBWMoney0"><?php echo $aBetGroup[key($aBetGroup)]['BW']['nMoney0'];?></span> -
								<span class="JqBWMoney1"><?php echo $aBetGroup[key($aBetGroup)]['BW']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">閑</div>
							<div class="betInfoDetailListVal">
								<span class="JqPWMoney0"><?php echo $aBetGroup[key($aBetGroup)]['PW']['nMoney0'];?></span> -
								<span class="JqPWMoney1"><?php echo $aBetGroup[key($aBetGroup)]['PW']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">和</div>
							<div class="betInfoDetailListVal">
								<span class="JqDRAWMoney0"><?php echo $aBetGroup[key($aBetGroup)]['DRAW']['nMoney0'];?></span> -
								<span class="JqDRAWMoney1"><?php echo $aBetGroup[key($aBetGroup)]['DRAW']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">庄對</div>
							<div class="betInfoDetailListVal">
								<span class="JqBPMoney0"><?php echo $aBetGroup[key($aBetGroup)]['BP']['nMoney0'];?></span> -
								<span class="JqBPMoney1"><?php echo $aBetGroup[key($aBetGroup)]['BP']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">閑對</div>
							<div class="betInfoDetailListVal">
								<span class="JqPPMoney0"><?php echo $aBetGroup[key($aBetGroup)]['PP']['nMoney0'];?></span> -
								<span class="JqPPMoney1"><?php echo $aBetGroup[key($aBetGroup)]['PP']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">超級六</div>
							<div class="betInfoDetailListVal">
								<span class="JqSIXMoney0"><?php echo $aBetGroup[key($aBetGroup)]['SIX']['nMoney0'];?></span> -
								<span class="JqSIXMoney1"><?php echo $aBetGroup[key($aBetGroup)]['SIX']['nMoney1'];?></span>
							</div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">房號</div>
							<div class="betInfoDetailListVal">百家樂<?php echo $nGame;?></div>
						</div>
						<div class="betInfoDetailList">
							<div class="betInfoDetailListTit">牌局號-局數</div>
							<div class="betInfoDetailListVal">
								<span class="JqNo"><?php echo isset($aData[$nGame]['sNo'])?$aData[$nGame]['sNo']:'';?></span> / 
								<span class="JqRound"><?php echo isset($aData[$nGame]['nRound'])?$aData[$nGame]['nRound']:'';?></span>
							</div>
						</div>
						<?php
						if(false)
						{
						?>
						<div class="betInfoDetailList like">
							<div class="betInfoDetailListTit">關注荷官</div>
							<div class="betInfoDetailListVal JqDealerName0"><?php echo isset($aData[$nGame]['sName0'])?$aData[$nGame]['sName0']:'';?></div>
							<!-- 點擊變色並加入到關注列表 -->
							<div class="betInfoIcon JqWatchDealer JqBtnToggleClass <?php echo $sWatchClass;?>">
								<i class="fas fa-heart"></i>
							</div>
						</div>
						<?php
						}
						?>
					</div>
					<div class="gameInfoDetailBlockBox">
						<div class="gameInfoDetailBlock BgBlue">
							<div>閑</div>
							<div class="JqPlayerWin" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['PW'];?></div>
						</div>
						<div class="gameInfoDetailBlock BgGreenImp">
							<div>和</div>
							<div class="JqDraw" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['DRAW'];?></div>
						</div>
						<div class="gameInfoDetailBlock BgRed">
							<div>庄</div>
							<div class="JqBankerWin" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['BW'];?></div>
						</div>
						<div class="gameInfoDetailBlock BgBlue">
							<div>閑對</div>
							<div class="JqPlayerPair" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['PP'];?></div>
						</div>
						<div class="gameInfoDetailBlock BgRed">
							<div>庄對</div>
							<div class="JqBankerPair" data-nGame="<?php echo $nGame;?>"><?php #echo $aNumsCounting[$nGame]['BP'];?></div>
						</div>
					</div>
				</div>
			</div>
			<!-- 右上方選單列表 -->
			<div class="gameMenuBox">
				<div class="gameMenuBlock JqWindowBtn" data-kindctrl="10">
					<img src="images/ICON/net.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuBlock JqWindowBtn" data-kindctrl="11">
					<img src="images/ICON/quota.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuBlock JqCommission JqBtnToggleClass">
					<div class="gameMenuIcon">
						<img src="images/ICON/exemption.png?t=<?php echo VTIME;?>" alt="">
					</div>
					<div class="gameMenuActive">
						<img src="images/ICON/exemption_act.png?t=<?php echo VTIME;?>" alt="">
					</div>
				</div>				
				<div class="gameMenuBlock icon JqDonateBtn JqBtnToggleClass">
					<div class="gameMenuIcon">
						<img src="images/ICON/donate.png?t=<?php echo VTIME;?>" alt="">
					</div>
					<div class="gameMenuActive">
						<img src="images/ICON/chat.png?t=<?php echo VTIME;?>" alt="">
					</div>
				</div>
				<div class="gameMenuBlock text JqBetBtn active">
					<div class="gameMenuOrig">
						<div class="gameMenuTxt">下注</div>
					</div>
					<div class="gameMenuActive">
						<div class="gameMenuTxt">牌路</div>
					</div>
				</div>
				<div class="gameMenuBlock icon JqChangeBtn">
					<img src="images/ICON/change.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<!-- 開牌區 -->
			<div class="gameCardBox JqFinalBox DisplayBlockNone">
				<div class="gameCardTitBox">
					<table class="gameCardTitTable">
						<tbody>
							<tr>
								<td class="gameCardTitTd first">
									<span class="gameCardTit">閑</span>
									<span class="gameCardNum JqPlayerGrade">0</span>
								</td>
								<td class="gameCardTitTd second">
									<span class="gameCardNum JqBankerGrade">0</span>
									<span class="gameCardTit">庄</span>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="gameCardTitIcon">
						<img src="images/ICON/VS.png?t=<?php echo VTIME;?>" alt="">
					</div>
				</div>
				<div class="gameCardContBox">
					<table class="gameCardContTable">
						<tbody>
							<tr>
								<td class="gameCardContTd">
									<div class="betFinal1CardBox">
										<div class="betFinal1Card JqCardP1 JqCard JqCard1 DisplayInlineBlock VisibleNone">
											<img src="images/card/back.png" alt="">
											<img class="JqCardP1Src" src="" alt="">
										</div>
										<div class="betFinal1Card JqCardP2 JqCard JqCard1 DisplayInlineBlock VisibleNone">
											<img class="" src="images/card/back.png" alt="">
											<img class="JqCardP2Src" src="" alt="">
										</div>
										<div class="betFinal1Card JqCardP3 JqCard DisplayBlock VisibleNone">
											<img class="" src="images/card/back.png" alt="">
											<img class="JqCardP3Src" src="" alt="">
										</div>
									</div>
								</td>
								<td class="gameCardContTd">
									<div class="betFinal1CardBox">
										<div class="betFinal1Card JqCardB1 JqCard JqCard1 DisplayInlineBlock VisibleNone">
											<img class="" src="images/card/back.png" alt="">
											<img class="JqCardB1Src" src="" alt="">
										</div>
										<div class="betFinal1Card JqCardB2 JqCard JqCard1 DisplayInlineBlock VisibleNone">
											<img class="" src="images/card/back.png" alt="">
											<img class="JqCardB2Src" src="" alt="">
										</div>
										<div class="betFinal1Card JqCardB3 JqCard DisplayBlock VisibleNone">
											<img class="" src="images/card/back.png" alt="">
											<img class="JqCardB3Src" src="" alt="">
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- <div class="betLastBox BG JqFinalRound DisplayBlockNone ">
				<div class="betLastTxt">最後一局</div>
			</div> -->
			
			<!-- 倒數 -->
			<div class="gameTimeBox JqTimeBox">
				<div class="gameTimeBlock">
					<div class="gameTime" style="border:8px solid #29ABE2;"></div>
					<div class="gameTimeTxt JqClock_<?php echo $nGame;?>">0</div>
					
				</div>
				<!-- <div class="gameTotalBet">
					<div class="gameTotalBetTxt">
						<span>本局投注</span>
						<span class="JqBetCount">11</span>
					</div>
				</div> -->
				<div class="gameTotalBet DisplayBlockNone JqDayLastBlock">
					<div class="gameTotalBetTxt">
						<span>本日倒數把數</span>
						<span class="JqDayLast">0</span>
					</div>
				</div>
			</div>
		</div>
		<!-- 聊天室 / 打賞 -->
		<div class="chaDonBox">
			<div class="chatroomBox DisplayBlockNone active JqChatBox">
				<div class="chatroomContBox JqMainBox">
					<div class="chatroomChatBox JqMsgBox">
						<div class="chatroomAnchorBox JqShowArea"></div>
					<!-- 左邊 -->
					<?php
						// echo '<div class="chatroomChatBlock Left" data-uid="[[::nUid::]]">
						// 		<div class="chatroomContBlock">
						// 			<div class="chatroomCont">
						// 				<div class="chatroomContTxt">[[::sMsg::]]</div>
						// 			</div>
						// 		</div>
						// 	</div>';
						
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
				</div>
				<div class="chatroomSendBox">
					<table class="chatroomSendTable">
						<tbody>
							<tr>
								<td class="chatroomSendTd">
									<div class="chatroomSendText">
										<textarea name="sText" class="JqChat JqContent0" placeholder="請輸入訊息"></textarea>
										<div class="chatroomIcon">
											<i class="far fa-grin-alt"></i>
										</div>
									</div>
								</td>
								<td class="chatroomSendTd btn">
									<div class="sendBtnBlock PC JqSend">
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
							<span>總金額</span>
							<span class="JqDonateMoney">0</span>
						</div>
						<div class="donateBtnBox">
							<div class="donateBtn confirm JqDonateSubmit">
								<div class="donateBtnTxt">確認</div>
							</div>
							<div class="donateBtn clear JqDonateClear">
								<div class="donateBtnTxt">清除</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>

	<div class="DisplayBlockNone"><!--JqBetBox -->
		<div class="donateBox">			
			<div class="donateCtrlBox JqBankerZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqBankerWinMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqBankerWinCount FontBlackImp">0</span>
					</div>
					<div class="JqBankerWinBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm">
						<div class="donateBlock " data-content="BW">
							<div class="donateMoney">
								<span>莊贏</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="donateCtrlBox JqPlayerZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqPlayerWinMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqPlayerWinCount FontBlackImp">0</span>
					</div>
					<div class="JqPlayerWinBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm ">
						<div class="donateBlock " data-content="PW">
							<div class="donateMoney">
								<span>閒贏</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="donateCtrlBox JqDrawZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqDrawMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqDrawCount FontBlackImp">0</span>
					</div>
					<div class="JqDrawBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm ">
						<div class="donateBlock " data-content="DRAW">
							<div class="donateMoney">
								<span>打和</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="donateCtrlBox JqBankerPairZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqBankerPairMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqBankerPairCount FontBlackImp">0</span>
					</div>
					<div class="JqBankerPairBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm ">
						<div class="donateBlock " data-content="BP">
							<div class="donateMoney">
								<span>莊對</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="donateCtrlBox JqPlayerPairZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqPlayerPairMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqPlayerPairCount FontBlackImp">0</span>
					</div>
					<div class="JqPlayerPairBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm ">
						<div class="donateBlock " data-content="PP">
							<div class="donateMoney">
								<span>閒對</span>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="donateCtrlBox JqSixZone">
				<div class="donateTotal">
					<div class="chatroomSendText" style="text-align: center;">
						<span class="JqSuperSixMoney FontBlackImp">0</span>
						<span class="FontBlackImp">/</span>
						<span class="JqSuperSixCount FontBlackImp">0</span>
					</div>
					<div class="JqSixBet" style="text-align: center;color: #FBEBC6;">0</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm ">
						<div class="donateBlock " data-content="SIX">
							<div class="donateMoney">
								<span>SUPER 6</span>
							</div>
						</div>
					</div>					
				</div>
			</div>
			<div class="donateCtrlBox">
				<div class="donateTotal">
					<div class="JqBetCheckBoxSum chatroomSendText" style="text-align: center;">0
					</div>
				</div>
				<div class="donateBtnBox">
					<div class="confirm">
						<div class="donateBlock JqSumMoney">
							<div class="donateMoney">
								<span>總金額</span>
							</div>
						</div>
					</div>					
				</div>
			</div>
			<?php if(false){?>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="BW">
						<div class="donateMoney" >
							<span>莊贏</span>
						</div>
					</div>
				</div>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="PW">
						<div class="donateMoney" >
							<span>閒贏</span>
						</div>
					</div>
				</div>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="DRAW">
						<div class="donateMoney" >
							<span>打和</span>
						</div>
					</div>
				</div>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="BP">
						<div class="donateMoney" >
							<span>莊對</span>
						</div>
					</div>
				</div>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="PP">
						<div class="donateMoney" >
							<span>閒對</span>
						</div>
					</div>
				</div>
				<div class="donateInner JqScrollAxisX">				
					<div class="donateBlock JqBetContent" data-content="SIX">
						<div class="donateMoney" >
							<span>SUPER 6</span>
						</div>
					</div>
				</div>
				<div class="donateCtrlBox">
					<div class="donateTotal">
						<span>總金額</span>
						<span class="JqShowContent"></span>
						<div class="chatroomSendText">
							<input type="number" class="JqBetMoney">
						</div>
					</div>
					<div class="donateBtnBox">
						<div class="donateBtn confirm JqBetSubmit">
							<div class="donateBtnTxt">確認</div>
						</div>
						<div class="donateBtn clear JqBetClear">
							<div class="donateBtnTxt">清除</div>
						</div>
					</div>
				</div>
			<?php }?>
		</div>
	</div>	

	<!-- 籌碼選擇及遊戲結果 -->
	<div class="betBotBox JqBotBlock">
		<!-- 0710 -->
		<div class="DisplayBlockNone JqBetBox active">
			<div class="gambleBox">
				<div class="gambleBetBox">
					<div class="gambleOptBox">
						<div class="gambleOptTop">
							<div class="gambleOptBlock">
								<div class="gambleOptBlockTxt">1</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="0">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
							<div class="gambleOptNumBox">
								<div class="gambleOptNumBlock JqPlayerPairZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontBlue JqPlayerPairBet">0</div>
										<div class="gambleOptBlockOdds FontBlue">1:11</div>
										<div class="gambleOptBlockTxt FontBlue">閑對</div>
									</div>
									<div class="gambleOptBlockPlayerBox">
										<div class="gambleOptBlockPlayerTop">
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">100000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">1000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">1000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">10000</div> -->
												</div>
											</div>
										</div>
										<div class="gambleOptBlockPlayerBot">
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">1000000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">10000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">1000</div> -->
												</div>
											</div>
											<div class="gambleOptBlockPlayerBlock">
												<div class="gambleOptBlockPlayerNo"></div>
												<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPP">
													<!-- <div class="gambleOptBlockPlayerChipImg">
														<img src="images/coin/empty.png" alt="">
													</div>
													<div class="gambleOptBlockPlayerChipNum JqPlayerBetChipNum">1000</div> -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- .gambleOptNumBlock + active 表開出此獎號 -->
								<div class="gambleOptNumBlock JqSixZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontYellow JqSixBet">0</div>
										<div class="gambleOptBlockTxt FontYellow">SUPER 6</div>
										<div class="gambleOptBlockOdds FontYellow">1:12</div>
									</div>
									<div class="gambleOptBlockPlayerBox">
												<div class="gambleOptBlockPlayerTop">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
												</div>
												<div class="gambleOptBlockPlayerBot">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo6</div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipSIX"></div>
													</div>
												</div>
									</div>                 
								</div>
								<div class="gambleOptNumBlock JqBankerPairZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontRed JqBankerPairBet">0</div>
										<div class="gambleOptBlockTxt FontRed">庄對</div>
										<div class="gambleOptBlockOdds FontRed">1:11</div>
									</div>
									<div class="gambleOptBlockPlayerBox">
												<div class="gambleOptBlockPlayerTop">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
												</div>
												<div class="gambleOptBlockPlayerBot">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBP"></div>
													</div>
												</div>
									</div>                      
								</div>
								<div class="gambleOptNumBlock JqPlayerZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontBlue JqPlayerWinBet">0</div>
										<div class="gambleOptBlockOdds FontBlue">1:1</div>
										<div class="gambleOptBlockTxt FontBlue">閑</div>
									</div>
									<div class="gambleOptBlockPlayerBox">
												<div class="gambleOptBlockPlayerTop">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
												</div>
												<div class="gambleOptBlockPlayerBot">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipPW"></div>
													</div>
												</div>
									</div>                             
								</div>
								<div class="gambleOptNumBlock JqDrawZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontGreen JqDrawBet">0</div>
										<div class="gambleOptBlockTxt FontGreen">和</div>
										<div class="gambleOptBlockOdds FontGreen">1:8</div>
									</div>
									<div class="gambleOptBlockPlayerBox">
												<div class="gambleOptBlockPlayerTop">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
												</div>
												<div class="gambleOptBlockPlayerBot">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipDRAW"></div>
													</div>
												</div>
									</div>                            
								</div>
								<div class="gambleOptNumBlock JqBankerZone">
									<div class="gambleOptBlockNum">
										<div class="gambleOptBlockBet FontRed JqBankerWinBet">0</div>
										<div class="gambleOptBlockTxt FontRed">庄</div>
										<div class="gambleOptBlockOdds FontRed JqBWOdds">1:<?php echo ($aOdds['BW']['nOdds']-1);?></div>
										<div class="gambleOptBlockOdds FontRed JqBWOdds1" style="display:none">1:<?php echo ($aOdds['BW']['nOdds1']-1);?></div>
									</div>
									<div class="gambleOptBlockPlayerBox">
												<div class="gambleOptBlockPlayerTop">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
												</div>
												<div class="gambleOptBlockPlayerBot">
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
													<div class="gambleOptBlockPlayerBlock">
														<div class="gambleOptBlockPlayerNo"></div>
														<div class="gambleOptBlockPlayerChip JqPlayerBetChip JqChipBW"></div>
													</div>
												</div>
									</div>
								</div>
							</div>
							<div class="gambleOptBlock">
								<div class="gambleOptBlockTxt">8</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="6">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>                        
						</div>
						<div class="gambleOptSeatBox">
							<div class="gambleOptSeat">
								<div class="gambleOptSeatTxt">2</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="1">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
							<div class="gambleOptSeat">
								<div class="gambleOptSeatTxt">3</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="2">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
							<div class="gambleOptSeat">
								<div class="gambleOptSeatTxt">5</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="3">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
							<div class="gambleOptSeat">
								<div class="gambleOptSeatTxt">6</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="4">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
							<div class="gambleOptSeat">
								<div class="gambleOptSeatTxt">7</div>
								<div class="gambleOptSeatPlayer JqSeat" data-i="5">
									<div class="gambleOptSeatPlayerName JqSeatName"></div>
									<div class="gambleOptSeatPlayerBala JqSeatMoney"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="gameCenterTxtBox BG JqStopBet DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
						<div class="gameCenterTxt">停止下注</div>
					</div>					
					<div class="gameCenterTxtBox BG JqShuffling DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
						<div class="gameCenterTxt">洗牌中</div>
					</div>
					<div class="gameCenterTxtBox BG JqCancelRound DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
						<div class="gameCenterTxt">本局取消</div>
					</div>
					<div class="gameCenterTxtBox BG JqPauseRound DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
						<div class="gameCenterTxt">投注暫停</div>
					</div>
				</div>
				<div class="batCtrlBtnBox">						
					<div class="betChipBtnBox JqCoinBox">
						<?php
						// for($i=2;$i<=6;$i++)
						foreach($aCoinArrayPc as $i)
						{
							echo' <div class="betChipBtn JqCoinTdBtn JqCoinTdBtn'.$i.'" data-clip="'.$aClip[$i].'">
									<div class="betChipBtnBg JqCoin">
										<div class="betChipBtnImg BG" style="background-image: url(\'images/coin/'.$i.'.png?t='.VTIME.'\');"></div>
									</div>
								</div>';
						}
						?>
						<div class="betChipBtn JqWindowBtn" data-kindctrl="12">
							<div class="betChipBtnSet">
								<img src="images/ICON/coin_btn.png" alt="">
							</div>
						</div>
					</div>
					<div class="betCtrlBox">
						<!-- 下注鈕 -->
						<div class="betCtrlBtn JqWindowBtn JqConfirmBet" data-kindctrl="13">
							<div class="betCtrlBtnIcon">
								<img src="images/ICON/check.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="betCtrlBtnTxt">確定</div>
						</div>
						<div class="betCtrlBtn JqRepeatBet">
							<div class="betCtrlBtnIcon">
								<img src="images/ICON/repeat.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="betCtrlBtnTxt">重複</div>
						</div>
						<div class="betCtrlBtn JqCancelBet">
							<div class="betCtrlBtnIcon">
								<img src="images/ICON/cancel.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="betCtrlBtnTxt">取消</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="DisplayBlockNone JqViewRoad">
			<div class="gameResultBox">
				<!-- 問路按鈕 -->
				<div class="gameAskBox">
					<div class="gameAskBlock blue JqAskBlock">
						<div class="gameAskTit">閑問路</div>
						<div class="gameAskIconBox">
							<div class="gameAskIconBlock">
								<div class="GameBigCircle  JqAskPW1"></div>
							</div>
							<div class="gameAskIconBlock">
								<div class="GameSmallCircle  JqAskPW2"></div>
							</div>
							<div class="gameAskIconBlock">
								<div class="GameHo BG  JqAskPW3"></div>
							</div>
						</div>
					</div>
					<div class="gameAskBlock red JqAskBlock">
						<div class="gameAskTit">庄問路</div>
						<div class="gameAskIconBox">
							<div class="gameAskIconBlock">
								<div class="GameBigCircle  JqAskBW1"></div>
							</div>
							<div class="gameAskIconBlock">
								<div class="GameSmallCircle  JqAskBW2"></div>
							</div>
							<div class="gameAskIconBlock">
								<div class="GameHo BG  JqAskBW3"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="gameRoadBox JqRoadOuterBox" data-col="44">
					<!-- 棋盤路 -->
					<div class="gameRoadBlock RoadBox first">
						<?php
						for($i=0;$i<6;$i++)
						{
						?>    <div class="RoadRow">
								<?php for($j=0;$j<12;$j++){?>
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
								<?php for($j=0;$j<16;$j++){?>
								<div class="RoadItem JqRoadItem JqBigRoad JqRoad0_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></div>
								<?php }?>
							</div>
						<?php
						}
						?>
					</div>
					<div class="gameRoadBlock RoadBox third">
						<!-- 大眼仔路 -->
						<?php
						for($i=0;$i<3;$i++)
						{
						?>
							<div class="RoadRow">
								<?php for($j=0;$j<16;$j++){?>
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
							</div>
						<?php
						}
						?>
						<!-- 小路(前8個gameGridTd) + 蟑螂路(後8個gameGridTd) -->
						<?php
						for($i=0;$i<3;$i++)
						{
						?>
							<div class="RoadRow">
								<?php for($j=0;$j<8;$j++){?>
								<div class="RoadItem JqRoadItem JqPatternRoad">
									<div class="RoadFourBox">
										<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></div>
										<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></div>
										<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></div>
										<div class="RoadFourBlock JqRoadSep JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></div>
									</div>
								</div>
								<?php }?>
								<!-- 斜線-->
								<?php for($j=0;$j<8;$j++){?>
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
		</div>
	</div>
</div>

<!-- 換桌列表 -->
<div class="changeMenuBox JqChangeMenu" data-col="20">
	<!-- 置頂選單 -->
	<div class="indexMenuBox">
		<!-- 點選該頁面 class="indexMenuBtn" + active -->
		<div class="indexMenuBtnBox">
			<div class="indexMenuBtn active">
				<div class="indexMenuBtnTxt">熱門置頂</div>	
			</div>
			<div class="indexMenuBtn">
				<div class="indexMenuBtnTxt">好路置頂</div>	
			</div>
			<div class="indexMenuBtn">
				<div class="indexMenuBtnTxt">投注置頂</div>	
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
			<!-- 正在的那桌 changeBlock + active -->
			<a class="changeBlock JqChangeList <?php if($LPsKey == $nGame) echo 'active';?>" href="<?php echo $aUrl['sBet'].'&nGame='.$LPsKey;?>">
				<div class="changeTopBox">
					<div class="changeTitBox">
						<span>百家樂</span>
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
								<div class="RoadItem JqRoadItem JqBigRoad JqRoad0_<?php echo $LPaValue['nGame'].'_sub';?> <?php echo 'x'.$j.' y'.$i;?>" style="width:10%;"></div>
								<?php }?>
								<?php if(false){?>
								<div class="RoadItem JqRoadItem">
									<!-- 藍圈: GameBigCircle class加Blue；紅圈加Red-->
									<div class="GameBigCircle Blue"></div>
									<!-- 點點GameDot class需加small；藍點再加Blue；紅點加Red-->
									<div class="GameDot small Blue"></div>
									<div class="GameDot small Red"></div>
									<!-- 綠斜線-->
									<div class="GameHo BG green"></div>
								</div>
								<?php }?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<div class="RoomStatusBox JqOpenNow_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt">開牌中</div>
				</div>
				<div class="RoomStatusBox now JqChangeNow">
					<div class="RoomStatusTxt">您在此桌</div>
				</div>
				<div class="RoomStatusBox shuffle JqShuffling_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt">洗牌中</div>
				</div>
				<div class="RoomStatusBox JqCloseRoom_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt">維護中</div>
				</div>
				<div class="RoomStatusBox JqComingSoon_<?php echo $LPsKey;?>">
					<div class="RoomStatusTxt">即將開盤</div>
				</div>
			</a>
		<?php
		}
		?>
	</div>
</div>