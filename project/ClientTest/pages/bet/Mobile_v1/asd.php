<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url="<?php echo $aUrl['sLobbyAjax'];?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input class="JqRoad0Width" type="hidden" value="12">
<input class="JqRoad1Width" type="hidden" value="12">
<input class="JqRoad2Width" type="hidden" value="12">
<input class="JqRoad3Width" type="hidden" value="12">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php echo json_encode($aNumsCounting);?>'>
</div>

<div class="gameContainer">
	<div class="gameMainBox">
		<!-- 視頻區，荷官休息時換為下方圖片(兩個不同時存在) -->
		<div class="gameVideoBox"></div>
		<!-- 荷官休息時圖片 -->
		<div class="gameBreakBox BG" style="background-image: url('images/break.jpg?t=<?php echo VTIME;?>');"></div>
		<!-- 左上方選單列表 -->
		<div class="gameMenuBox Left">
			<a class="gameMenuBlock JqBtnToggleClass" href="<?php echo $aUrl['sIndex'];?>">
				<div class="gameMenuIcon">
					<img src="images/ICON/back.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuActive">
					<img src="images/ICON/back_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</a>
			<div class="gameMenuBlock JqChatCtrl">
				<div class="gameMenuIcon">
					<img src="images/ICON/chat.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuActive">
					<img src="images/ICON/chat_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<div class="gameMenuBlock JqBtnToggleClass">
				<div class="gameMenuIcon">
					<img src="images/ICON/exemption.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuActive">
					<img src="images/ICON/exemption_act.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
		</div>
		<!-- 右上方選單列表 -->
		<div class="gameMenuBox Right">
			<div class="gameMenuBlock JqBtnToggleClass">
				<div class="gameMenuIcon">
					<img src="images/ICON/menu.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameMenuActive">
					<img src="images/ICON/close.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="gameListMenuBox">
					<div class="gameListMenuBlock JqWindowBtn" data-kindctrl="1">
						<table class="gameListMenuTable">
							<tbody>
								<tr>
									<td class="gameListIconTd">
										<div class="gameListIcon">
											<img src="images/ICON/rule.png?t=<?php echo VTIME;?>" alt="">
										</div>
									</td>
									<td class="gameListMenuTd">規則</td>
									<td class="gameListArrowTd"><i class="fas fa-chevron-right"></i></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="gameListMenuBlock JqWindowBtn" data-kindctrl="0">
						<table class="gameListMenuTable">
							<tbody>
								<tr>
									<td class="gameListIconTd">
										<div class="gameListIcon">
											<img src="images/ICON/music.png?t=<?php echo VTIME;?>" alt="">
										</div>
									</td>
									<td class="gameListMenuTd">聲音</td>
									<td class="gameListArrowTd"><i class="fas fa-chevron-right"></i></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="gameMenuBlock JqWindowBtn" data-kindctrl="10">
				<div class="gameMenuIcon">
					<img src="images/ICON/net.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
			<div class="gameMenuBlock JqWindowBtn" data-kindctrl="11">
				<div class="gameMenuIcon">
					<img src="images/ICON/quota.png?t=<?php echo VTIME;?>" alt="">
				</div>
			</div>
		</div>
		<!-- 開牌區，開牌時下方倒數會隱藏 -->
		<div class="gameCardBox">
			<div class="gameCardTitBox">
				<table class="gameCardTitTable">
					<tbody>
						<tr>
							<td class="gameCardTitTd first">
								<span class="gameCardTit">閑</span>
								<span class="gameCardNum">99</span>
							</td>
							<td class="gameCardTitTd second">
								<span class="gameCardNum">99</span>
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
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
							</td>
							<td class="gameCardContTd">
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
								<div class="gameCard">
									<img src="images/card/52.png?t=<?php echo VTIME;?>" alt="">
									<img src="images/card/0.png?t=<?php echo VTIME;?>" alt="">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<!-- 倒數，下注時出現，開牌時消失 -->
		<div class="gameTimeBox" style="display:none;">
			<div class="gameTimeBlock">
				<div class="gameTime" style="border:4px solid #29ABE2;"></div>
				<div class="gameTimeTxt">50</div>
			</div>
		</div>
	</div>
	<div class="betContainerBg">
		<div class="betContainer">
			<!-- 錢和關注荷官 -->
			<div class="moneyLoveBox">
				<table class="moneyLoveTable">
					<tbody>
						<tr>
							<td class="moneyTd">
								<div class="moneyIconBg">
									<div class="moneyIcon">$</div>
								</div>
								<div class="moneyTxt">
									<span>TWD</span>
									<span>54698464</span>
								</div>
							</td>
							<td class="loveTd">
								<!-- 點擊變色並加入到關注列表 -->
								<div class="loveBlock JqBtnToggleClass">
									<span class="loveIcon"><i class="fas fa-heart"></i></span>
									<span class="loveTxt">關注荷官</span>
									<span class="loveName">xxxxxxx</span>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- 下注區 -->
			<div class="betBox">
				<table class="betTable">
					<tbody>
						<tr class="betTr">
							<!-- 藍字 + FontBlue；紅字 + FontRed；綠字 + FontGreen -->
							<td class="betTd big JqCngBtn" style="width:calc(100%/2);">
								<div class="betTit FontBlue">閑對</div>
								<div class="betRate FontBlue">
									<span>1</span>
									<span>:</span>
									<span>11</span>
								</div>
								<div class="betCoinBlock">
									<table class="betCoinTable">
										<tbody>
											<tr>
												<td class="betIconTd">
													<div class="betCoinIcon">
														<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="betCoinTd">
													<span class="JqPlayerPairMoney">0</span>
													<span>/</span>
													<span class="JqPlayerPair" data-nGame="<?php echo $aData['nGame'];?>"><?php echo (int)$aNumsCounting[$aData['nGame']]['PP'];?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="betTd big JqCngBtn" style="width:calc(100%/2);">
								<div class="betTit FontRed">庄對</div>
								<div class="betRate FontRed">
									<span>1</span>
									<span>:</span>
									<span>11</span>
								</div>
								<div class="betCoinBlock">
									<table class="betCoinTable">
										<tbody>
											<tr>
												<td class="betIconTd">
													<div class="betCoinIcon">
														<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="betCoinTd">
													<span class="JqBankerPairMoney">0</span>
													<span>/</span>
													<span class="JqBankerPair" data-nGame="<?php echo $aData['nGame'];?>"><?php echo (int)$aNumsCounting[$aData['nGame']]['BP'];?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
				<table class="betTable">
					<tbody>
						<tr>
							<!-- 點擊在betTd + active -->
							<td class="betTd JqCngBtn" style="width:calc(100%/3);">
								<div class="betTit FontBlue">閑</div>
								<div class="betRate FontBlue">
									<span>1</span>
									<span>:</span>
									<span>1</span>
								</div>
								<div class="betCoinBlock">
									<table class="betCoinTable">
										<tbody>
											<tr>
												<td class="betIconTd">
													<div class="betCoinIcon">
														<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="betCoinTd">
													<span class="JqPlayerWinMoney">0</span>
													<span>/</span>
													<span class="JqPlayerWin" data-nGame="<?php echo $aData['nGame'];?>"><?php echo (int)$aNumsCounting[$aData['nGame']]['PW'];?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="betTd JqCngBtn" style="width:calc(100%/3);">
								<div class="betTit FontGreen">和</div>
								<div class="betRate FontGreen">
									<span>1</span>
									<span>:</span>
									<span>8</span>
								</div>
								<div class="betCoinBlock">
									<table class="betCoinTable">
										<tbody>
											<tr>
												<td class="betIconTd">
													<div class="betCoinIcon">
														<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="betCoinTd">
													<span class="JqDrawMoney">0</span>
													<span>/</span>
													<span class="JqDraw" data-nGame="<?php echo $aData['nGame'];?>"><?php echo (int)$aNumsCounting[$aData['nGame']]['DRAW'];?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
							<td class="betTd JqCngBtn" style="width:calc(100%/3);">
								<div class="betTit FontRed">庄</div>
								<div class="betRate FontRed">
									<span>1</span>
									<span>:</span>
									<span>0.95</span>
								</div>
								<div class="betCoinBlock">
									<table class="betCoinTable">
										<tbody>
											<tr>
												<td class="betIconTd">
													<div class="betCoinIcon">
														<img src="images/ICON/betIcon.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="betCoinTd">
													<span class="JqBankerWinMoney">0</span>
													<span>/</span>
													<span class="JqBankerWin" data-nGame="<?php echo $aData['nGame'];?>"><?php echo (int)$aNumsCounting[$aData['nGame']]['BW'];?></span>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- 籌碼區 -->
			<div class="gameSelBox">
				<table class="gameSelTable">
					<tbody>
						<tr>
							<?php
							for($i=1;$i<=6;$i++)
							{
								echo' <td class="gameCoinTd" style="width:calc(100%/7);">
										<div class="gameCoinBg JqCoin">
											<div class="gameCoinBlock">
												<img src="images/coin/'.$i.'.png?t=<?php echo VTIME;?>" alt="">
											</div>
										</div>
									</td>';
							}
							?>
							<!-- 籌碼設置鈕 -->
							<td class="gameSelCoinBtnTd" style="width:calc(100%/7);">
								<div class="gameSelCoinBtn JqWindowBtn" data-kindctrl="12">
									<img src="images/ICON/coin_btn.png?t=<?php echo VTIME;?>" alt="">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!-- 正中間字幕(停止下注、開始下注、免傭金開關提示) -->
			<div class="gameCenterTxtBox JqStopBet DisplayInlineBlockNone active">
				<div class="gameCenterTxtBlock">
					<img src="images/textBg.png?t=<?php echo VTIME;?>" alt="">
					<div class="gameCenterTxt">停止下注</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 按鈕區 -->  
	<div class="gameBtnBox">
		<table class="gameBtnTable">
			<tbody>
				<tr>  
					<td class="gameBtnTd JqChangeBtn">
						<div class="gameChangeBtn">
							<div class="gameBtnIcon change">
								<img src="images/ICON/change.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="gameBtnTxt">換桌</div>
						</div>
					</td>
					<td class="gameBtnTd JqWindowBtn" data-kindctrl="13">
						<div class="gameBtn">
							<div class="gameBtnIcon">
								<img src="images/ICON/check.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="gameBtnTxt">確定</div>
						</div>
					</td>
					<td class="gameBtnTd">
						<div class="gameBtn">
							<div class="gameBtnIcon">
								<img src="images/ICON/repeat.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="gameBtnTxt">重複</div>
						</div>
					</td>
					<td class="gameBtnTd">
						<div class="gameBtn">
							<div class="gameBtnIcon">
								<img src="images/ICON/cancel.png?t=<?php echo VTIME;?>" alt="">
							</div>
							<div class="gameBtnTxt">取消</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- 遊戲結果 -->
	<div class="gameResultBox">
		<table class="gameResultTable">
			<tbody>
				<tr>
					<!-- 棋盤路 -->
					<td class="gameResultTd first">
						<table class="gameGridTable">
							<tbody>
								<?php
									for($i=0;$i<6;$i++)
									{
									?>	<tr class="gameGridTr">
											<?php for($j=0;$j<12;$j++){?>
											<td class="gameGridTd JqBoard_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'JqNo'.($j*6+$i);?>"></td>
											<?php }?>
										</tr>
									<?php
									}
								?>
							</tbody>
						</table>
					</td>
					<td class="gameResultTd second">
						<table class="gameGridTable">
							<tbody>
								<!-- 大路 -->
								<?php
									for($i=0;$i<6;$i++)
									{
									?>
										<tr class="gameGridTr">
											<?php for($j=0;$j<18;$j++){?>
											<td class="gameGridTd JqRoad0_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.$j.' y'.$i;?>"></td>
											<?php }?>
										</tr>
									<?php
									}
								?>
								<!-- 大眼仔路(前6個gameGridTd) + 小路(中6個gameGridTd) + 蟑螂路(後7個gameGridTd) -->
								<?php
									for($i=0;$i<3;$i++)
									{
									?>
										<tr class="gameGridTr">
											<?php for($j=0;$j<6;$j++){?>
											<td class="gameGridTd">
												<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
												<table class="gameFourTable">
													<tbody>
														<tr>
															<td class="JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></td>
															<td class="JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></td>
														</tr>
														<tr>
															<td class="JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></td>
															<td class="JqRoad1_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></td>
														</tr>
													</tbody>
												</table>
											</td>
											<?php }?>
											<?php for($j=0;$j<6;$j++){?>
											<td class="gameGridTd">
												<table class="gameFourTable">
													<tbody>
														<tr>
															<td class="JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></td>
															<td class="JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></td>
														</tr>
														<tr>
															<td class="JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></td>
															<td class="JqRoad2_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></td>
														</tr>
													</tbody>
												</table>
											</td>
											<?php }?>
											<?php for($j=0;$j<6;$j++){?>
											<td class="indexGameSmallTd">
												<table class="gameFourTable">
													<tbody>
														<tr>
															<td class="JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2);?>"></td>
															<td class="JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2);?>"></td>
														</tr>
														<tr>
															<td class="JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2).' y'.($i*2+1);?>"></td>
															<td class="JqRoad3_<?php echo $aData[$nGame]['nGame'];?> <?php echo 'x'.($j*2+1).' y'.($i*2+1);?>"></td>
														</tr>
													</tbody>
												</table>
											</td>
											<?php }?>
										</tr>
									<?php
									}
								?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- 遊戲資訊+問路按鈕 -->
	<div class="gameInfoBox">
		<table class="gameInfoTable">
			<tbody>
				<tr>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgRed">
							<div class="gameInfoTit">庄</div>
						</div>
						<div class="gameInfoNum FontRedImp">13</div>
					</td>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgBlue">
							<div class="gameInfoTit">閑</div>
						</div>
						<div class="gameInfoNum FontBlueImp">13</div>
					</td>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgGreenImp">
							<div class="gameInfoTit">和</div>
						</div>
						<div class="gameInfoNum FontGreenImp">13</div>
					</td>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgRed">
							<div class="gameInfoTit">庄對</div>
						</div>
						<div class="gameInfoNum FontRedImp">13</div>
					</td>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgBlue">
							<div class="gameInfoTit">閑對</div>
						</div>
						<div class="gameInfoNum FontBlueImp">13</div>
					</td>
					<td class="gameInfoTd">
						<div class="gameInfoTitBox BgBlackImp">
							<div class="gameInfoTit">總</div>
						</div>
						<div class="gameInfoNum FontBlack">13</div>
					</td>
					<!-- 問路按鈕 -->
					<td class="gameAskTd">
						<div class="gameAskBlueBox">
							<div class="gameAskTit">閑問路</div>
							<div class="gameAskIconBox">
								<table class="gameAskIconTable">
									<tbody>
										<tr>
											<td class="gameAskIconTd">
												<div class="GameBigCircle Blue"></div>
											</td>
											<td class="gameAskIconTd">
												<div class="GameSmallCircle BgBlue"></div>
											</td>
											<td class="gameAskIconTd">
												<div class="GameSmallLine BgRed"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</td>
					<td class="gameAskTd">
						<div class="gameAskRedBox">
							<div class="gameAskTit">庄問路</div>
							<div class="gameAskIconBox">
								<table class="gameAskIconTable">
									<tbody>
										<tr>
											<td class="gameAskIconTd">
												<div class="GameBigCircle Red"></div>
											</td>
											<td class="gameAskIconTd">
												<div class="GameSmallCircle BgRed"></div>
											</td>
											<td class="gameAskIconTd">
												<div class="GameSmallLine BgBlue"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- 聊天室 -->
	<div class="chatroomBox JqChatBox">
		<div class="chatroomContBox">
			<div class="chatroomChatBox">
			<!-- 左邊 -->
			<?php
				for($i=1;$i<5;$i++)
				{
					echo '<div class="chatroomChatBlock Left">
							<div class="chatroomContBlock">
								<div class="chatroomCont">
									<div class="chatroomContTxt">請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息</div>
								</div>
							</div>
						</div>';
				}
			?>
			<!-- 右邊 -->
			<div class="chatroomChatBlock Right">
				<div class="chatroomContBlock">
					<div class="chatroomCont">
						<div class="chatroomContTxt">請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息請輸入訊息</div>
					</div>
				</div>
			</div>
		</div>
		<div class="chatroomSendBox">
			<table class="chatroomSendTable">
				<tbody>
					<tr>
						<td class="chatroomSendTd">
							<div class="chatroomSendText">
								<textarea name="sText" placeholder="請輸入訊息"></textarea>
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
	</div>                                                
</div>

<!-- 換桌列表 -->
<div class="changeMenuBox JqChangeMenu">
	<!-- 置頂選單 -->
	<div class="indexMenuBox">
		<table class="indexMenuTable">
			<tbody>
				<tr>
					<!-- 點選該頁面 class="indexMenuTd" + active -->
					<td class="indexMenuTd active">熱門置頂</td>
					<td class="indexMenuIconTd">|</td>
					<td class="indexMenuTd">好路置頂</td>
					<td class="indexMenuIconTd">|</td>
					<td class="indexMenuTd">投注置頂</td>
					<td class="changeCancelTd">
						<div class="changeCancel JqCancel">
							<i class="fas fa-times"></i>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="changeListBox">
		<table class="changeListTable">
			<tbody> 
			<?php
				$nCount = 1;
				$nTdAmount = 2; #一列幾欄
				$nTotalAmount = 21; #資料總數
				for($nI=1;$nI<=$nTotalAmount;$nI++)
				{
					if($nCount%$nTdAmount==1)
					{
						echo '<tr>';
					}
					?>                      
					<td class="changeListTd" style="width:calc(100%/<?php $nTdAmount;?>);">
						<!-- 正在的那桌 changeListBlock + active -->
						<a class="changeListBlock JqChangeList" href="?sFolder=game&sPage=game_0">
							<div class="changeTitBlock">
								<table class="changeTitTable">
									<tbody>
										<tr>
											<td class="changeTitTd">
												<span>百家樂</span>
												<span>11</span>
											</td>
											<td class="changeNumTd">11</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="changeContBlock">
								<table class="changeContTable">
									<tbody>
									<?php
										for($i=1;$i<7;$i++)
										{
										?>
											<tr class="changeContTr">
												<td class="changeContTd">
													<!-- 藍圈: GameBigCircle class加Blue；紅圈加Red-->
													<div class="GameBigCircle Blue"></div>
														<!-- 點點GameDot class需加small；藍點再加Blue；紅點加Red-->
														<div class="GameDot small Blue"></div>
														<div class="GameDot small Red"></div>
														<!-- 綠斜線-->
														<div class="GameLine BgGreen"></div>
												</td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
												<td class="changeContTd"></td>
											</tr>
										<?php
										}
									?>
									</tbody>
								</table>
							</div>
							<!-- 開牌中 -->
							<div class="changeShowBox">
								<div class="changeShowTxt">開牌中</div>
							</div>
							<!-- 正在此桌 -->
							<div class="changeNowBox BgBlue JqChangeNow">
								<div class="changeNowTxt">您在此桌</div>
							</div>
						</a>
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
							echo '<td class="changeListTd" style="width:calc(100%/'.$nTdAmount.');"></td>';
						}
						echo '</tr>';
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
</div>