<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="sBetJWT" type="hidden" value="<?php echo $sBetJWT; ?>" data-url="<?php echo $aUrl['sBetAjax'];?>">
<input name="sDonateJWT" type="hidden" value="<?php echo $sDonateJWT; ?>" data-url="<?php echo $aUrl['sDonateAjax'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input name="sWatchDealerJWT" type="hidden" value="<?php echo $sWatchDealerJWT; ?>" data-url="<?php #echo $aUrl['sWatchDealerAjax'];?>" data-dealer="<?php #echo $aData[$nGame]['nDealer'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-mute="<?php #echo $aUser['nMute'];?>" data-test="<?php #echo $aUser['nTest'];?>">
<input name="aPlayerData" type="hidden" value='<?php echo json_encode($aPlayerData);?>'>
<input name="sSocketJWT" type="hidden" value='<?php #echo $sSocketJWT;?>' data-json='<?php #echo json_encode($aSocketPack);?>'>
<input name="sChatJWT" type="hidden" value='<?php echo $sChatJWT;?>' data-url='<?php echo $aUrl['sChatAjax'];?>'>
<input type="hidden" class="JqaJsonMsg" value='<?php echo json_encode($aJsonMsgChk);?>' >
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

<input type="hidden" name="sAjaxUrl" 	value="bet_act.php">
<input type="hidden" name="sJWT" 		value="<?php echo $sJWT;?>">
<input type="hidden" name="nUid" 		value="<?php echo $nUid;?>">
<input type="hidden" name="sLineId" 	value="<?php echo $sLineId;?>">
<input type="hidden" name="sLiffId" 	value="<?php echo $sNowLiff;?>">
<div class="JqCopyPlayerMsg" style="display:none">
	<div class="betmsg font-xxs msgMember">
		<div class="coverbox">
			<div class="td imgbox float-left" valign="top" width="40"><img class="img-circle" src="[:HEADIMG:]" width="40"></div>
			<div class="td float-left ifmeright msgMemberInf">
				<div class="dtbox">
					<div class="dt-nickname">[:PLAYERNAME:]</div>
				</div>
				<div class="betmsgBlock arrow">
					<div class="betinfobox">
						<div class="betinfo JqBetMsgBlock">
							<div>[:PLAYERNAME:]</div>
							<!-- <div class="FontBlue JqBetMsg">[:CONTENT:][<i class="FontGreen fas fa-check"></i>[:STATUS:]][:MONEY:]</div> -->
						</div>
					</div>
				</div>
				<div class="dt-time">[:TIME:]</div>
			</div>
		</div>
	</div>
</div>
<div class="JqCopySelfMsg" style="display:none">
	<div class="betmsg font-xxs ifme msgService">
		<div class="coverbox">
			<div class="td imgbox float-left" valign="top" width="40"><img class="img-circle" src="[:HEADIMG:]" width="40"></div>
			<div class="td float-left ifmeright msgMemberInf">
				<div class="dtbox">
					<div class="dt-nickname">[:PLAYERNAME:]</div>
				</div>
				<div class="betmsgBlock arrow">
					<div class="betinfobox">
						<div class="betinfo JqBetMsgBlock">
							<div>[:PLAYERNAME:]</div>
							<!-- <div class="FontBlue JqBetMsg">[:CONTENT:][<i class="FontGreen fas fa-check"></i>[:STATUS:]][:MONEY:]</div> -->
						</div>
					</div>
				</div>
				<div class="dt-time">[:TIME:]</div>
			</div>
		</div>
	</div>
</div>
<div class="JqCopySystemMsg" style="display:none">
	<div class="betmsg font-xxs ifme msgService">
		<div class="coverbox">
			<div class="td float-left ifmeright msgServiceInf">
				<div class="betmsgBlock arrow">
					<div class="betinfobox">
						<div class="betinfo">
							<div>[:PLAYERNAME:]</div>
							<div>[:MSG1:]</div>
							<div>[:MSG2:]</div>
						</div>
					</div>
				</div>
				<div class="dt-time">[:TIME:]</div>
			</div>
		</div>
	</div>
</div>
<div class="ContentMainBox">
	<div class="betbg">
		<div class="wrap">
			<div class="contentbox">
				<div id="msgbox" class="msgbox pk10boxmsg pk10">
					<div class="workBox">
						<div class="masker"></div>

						<!--	chat	-->
						<div data-msg="grps" id="betmsglist" class="msg chatBox DisplayBlockNone JqBlock active JqMsgBox" data-btn="chatroom">
							<div class="JqShowArea" style="position: absolute;width: 100%;bottom: 62px;height: unset;max-height: 100%;overflow-y: auto;padding: 0px 40px 0px 0px;">
								<?php
								// if(!empty($aRobotResult))
								foreach($aJsonMsg as $LPsUnixTime => $LPaJsonMsg)
								{
									if(NOWTIME < $LPsUnixTime/10000)
									{
										break;
									}
									echo $LPaJsonMsg['sMsg'];
								}
								foreach($aRecordData['aBetList'] as $LPnId => $LPaData){
									echo '<div class="betmsg font-xxs msgMember">
											<div class="coverbox">
												<div class="td imgbox float-left" valign="top" width="40">
													<img class="img-circle" src="'.$aMebmer[$LPaData['nUid']]['sPicture'].'" width="40">
												</div>
												<div class="td float-left ifmeright msgMemberInf">
													<div class="dtbox">
														<div class="dt-nickname">'.$aMebmer[$LPaData['nUid']]['sName0'].'</div>
													</div>
													<div class="betmsgBlock arrow">
														<div class="betinfobox">
															<div class="betinfo JqBetMsgBlock">
																<div>'.$aMebmer[$LPaData['nUid']]['sName0'].'</div>
																<div class="'.aMETHODCOLOR[$LPaData['sContent0']].' JqBetMsg">'.
																	aBET[$LPaData['sContent0']].'[<i class="FontGreen fas fa-check"></i>'.aBET['SUCCESS'].']'.$LPaData['nMoney0'].
																'</div>
															</div>
														</div>
													</div>
													<div class="dt-time">'.$LPaData['sUpdateTime'].'</div>
												</div>
											</div>
										</div>';
								}
								if(false)
								{
									// foreach($aRobotResult as $k => $v)
									for($nI=0;$nI<3;$nI++)
									{
										// if($v['sName'] == aGlobalText['Services'])
										if($nI%3==0)
										{
											##### 玩家自己

											switch($nI%4)
											{
												case 1:
													$sSet	= '關閉';
													$sShowMsg = '請耐心等待開獎';
													break;
												case 2:
													$sSet	= '開放';
													$sShowMsg = '祝各位中大獎';
													break;
												case 0:
												case 3:
													$sSet	= '';
													$sShowMsg = '剩10秒，即將關閉';
													break;
											}
											$sHTML = '	<div class="betmsg font-xxs ifme msgService">
														<div class="coverbox">
															<div class="td float-left ifmeright msgServiceInf">
																<div class="betmsgBlock arrow">
																	<div class="betinfobox">
																		<div class="betinfo">
																			<div>'. '玩家名稱' .'</div>'.
																			'<div>'.$sSet.'</div>'.
																			'<div>'.$sShowMsg.'</div>
																		</div>
																	</div>
																</div>
																<div class="dt-time">'. '14:32:25' .'</div>
															</div>
														</div>
													</div>';
											
										}
										else
										{
											$sHTML = '	<div class="betmsg font-xxs msgMember">
														<div class="coverbox">
															<div class="td imgbox float-left" valign="top" width="40"><img class="img-circle" src="images/headImg/4.jpg" width="40"></div>
																<div class="td float-left ifmeright msgMemberInf">
																	<div class="dtbox">
																		<div class="dt-nickname">'. '玩家名稱' .'</div>                                                                                                      
																	</div>
																	<div class="betmsgBlock arrow">
																		<div class="betinfobox">
																			<div class="betinfo">
																				<div>'. '玩家名稱' .'</div>';
																				switch($nI%4)
																				{
																					#投注成功內容文字顏色請照投注字面顏色
																					case 1:
																						$sHTML .= '<div class="FontBlue">'. '閑對[<i class="FontGreen fas fa-check"></i>成功]10000' .'</div>';
																						break;
																					case 2:
																						$sHTML .= '<div class="FontRed">'. '庄對[<i class="FontGreen fas fa-check"></i>成功]10000' .'</div>';
																						break;
																					case 0:
																						$sHTML .= '<div class="FontYellow">'. '超級六[<i class="FontGreen fas fa-check"></i>成功]10000' .'</div>';
																						break;
																					case 3:
																						$sHTML .= '<div class="FontGreen">'. '和[<i class="FontGreen fas fa-check"></i>成功]10000' .'</div>';
																						break;
																				}                                                                                                                  
											$sHTML .= '						</div>
																	</div>                                                                                                     
																</div>
																<div class="dt-time">'. '14:32:22' .'</div>
															</div>
														</div>
													</div>';
										}
										echo $sHTML;
									}
								}
								?>
							</div>
							<div class="chatFoot">
								<div class="chatFootContainer">
									<div class="chatFootTxt">
										<div class="Textarea">
											<textarea name="JqChatText" class=""></textarea>
										</div>
									</div>
									<div class="chatFootSubBtn JqChatSubmit">
										<div class="chatFootSubBtnTxt">
											<i class="fas fa-paper-plane"></i>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--	start	-->
						<div id="betform" class="mainBlock DisplayBlockNone JqBlock JqBetBtnBox" data-msg="grps" data-btn="bet">
							<div class="betBigOuter">
								<div class="betMainBox">
									<div class="betMainInner">
										<div class="betPlayBox JqBetPlayerBox">
											<!-- 開牌區 -->
											<div class="betFinalBox DisplayBlockNone JqFinalBox">
												<div class="betFinalInner">
													<div class="betFinalBlock player">
														<div class="betFinalTop">
															<div class="betFinalTit"><?php #echo aBET['PL'];?>閑</div>
															<div class="betFinalSum JqPlayerGrade">0</div>
														</div>
														<div class="betFinalCardBox">
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
													</div>
													<div class="betFinalIcon">
														<img src="images/ICON/VS.png?t=<?php echo VTIME;?>" alt="">
													</div>
													<div class="betFinalBlock bank">
														<div class="betFinalTop">
															<div class="betFinalSum JqBankerGrade">0</div>
															<div class="betFinalTit"><?php #echo aBET['BK'];?>庄</div>						
														</div>
														<div class="betFinalCardBox">
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
													</div>			
												</div>			
											</div>
											<div class="betContainer">
												<!-- 下注區 -->
												<div class="betAreaBox">
													<div class="betAreaBlock top">
														<!-- 藍字 + FontBlue；紅字 + FontRed；綠字 + FontGreen -->
														<div class="betArea JqCngBtn JqPlayerPairZone" style="width:calc(100%/3);">
															<div class="betAreaTit FontBlue"><?php #echo aBET['PP'];?>閑對</div>
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
															<div class="betAreaTit FontYellow"><?php #echo aBET['SIX'];?>超級六</div>
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
															<div class="betAreaTit FontRed"><?php #echo aBET['BP'];?>庄對</div>
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
															<div class="betAreaTit FontBlue"><?php #echo aBET['PL'];?>閑</div>
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
															<div class="betAreaTit FontGreen"><?php #echo aBET['DRAW'];?>和</div>
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
															<div class="betAreaTit FontRed"><?php #echo aBET['BK'];?>庄</div>
															<div class="betAreaRate FontRed">
																<span>1</span>
																<span>:</span>
																<span class="JqBWOdds"><?php #echo ($aOdds['BW']['nOdds']-1);?>0.95</span>
																<span class="JqBWOdds1" style="display:none"><?php #echo ($aOdds['BW']['nOdds1']-1);?>1</span>
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
													<div class="betStatusMsg"><?php #echo aBET['STOPBET'];?>停止下注</div>
												</div>
												<div class="betStatusBox JqShuffling DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
													<div class="betStatusMsg"><?php #echo aBET['SHUFFLING'];?>洗牌中</div>
												</div>
												<div class="betStatusBox JqCancelRound DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
													<div class="betStatusMsg"><?php #echo aBET['CANCELROUND'];?>本局取消</div>
												</div>
												<div class="betStatusBox JqPauseRound DisplayInlineBlockNone  BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME;?>');">
													<div class="betStatusMsg"><?php #echo aBET['PAUSEBET'];?>投注暫停</div>
												</div>
											</div>
										</div>

										<!-- 籌碼區 -->
										<div class="betCoinBox JqCoinBox">
											<div class="betCoinBox1 JqCoinBox1">
												<?php
												// foreach($aCoinArrayMb as $i)
												foreach($aSetCoinArray as $LPnId => $LPaChip)
												{
													if($LPnId == 7)
													{
														break;
													}
													echo' <div class="betCoinBtn JqCoin JqCoinTdBtn JqCoinTdBtn'.$LPnId.'" data-clip="'.$LPaChip['nValue'].'"  style="width:calc(100%/7);">
															<div class="betCoinBtnInner JqCoinInner">
																<div class="betCoinBtnPic JqCoinPic BG" style="background-image:url(\'images/coin/'.$LPnId.'.png?t='.VTIME.'\');"></div>
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
								</div>
								<!-- 按鈕區 -->  
								<div class="betCtrlBox JqCtrlBox">
									<div class="betCtrlBtn JqWindowBtn JqConfirmBet" data-kindctrl="13">
										<div class="betCtrlBtnIcon">
											<img src="images/ICON/check.png?t=<?php echo VTIME;?>" alt="">
										</div>
										<div class="betCtrlBtnIconTxt"><?php #echo aBET['CONFIRM'];?>確定</div>
									</div>
									<div class="betCtrlBtn JqRepeatBet">
										<div class="betCtrlBtnIcon">
											<img src="images/ICON/repeat.png?t=<?php echo VTIME;?>" alt="">
										</div>
										<div class="betCtrlBtnIconTxt"><?php #echo aBET['DUPLICATE'];?>重複</div>
									</div>
									<div class="betCtrlBtn JqCancelBet">
										<div class="betCtrlBtnIcon">
											<img src="images/ICON/cancel.png?t=<?php echo VTIME;?>" alt="">
										</div>
										<div class="betCtrlBtnIconTxt JqCancelBet"><?php #echo aBET['CANCEL'];?>取消</div>
									</div>
								</div>
							</div>
						</div>

						<div id="Loading" class="hide"><?php #echo aGlobalTip['Waiting']; ?>請耐心等待開獎</div>

						<!--	紀錄	-->
						<div data-msg="grps" id="myhistory"  class="DisplayBlockNone JqBlock" data-btn="betrecord">
							<div id="acclsbtn">
								<div class="detailBox Table">
									<div>
										<div>
											<!-- <a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory1" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>" class="menubtn menubtn-green "><?php #echo aGlobalText['NotSettle']; ?>未結算</a> -->
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory2" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time());?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory3" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400) ;?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory4" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 2) ;?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory5" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox">投注明細</a>
										</div>
									</div>
								</div>
							</div>

							<!-- ajax後 css不要帶過來 -->
							<div id="myhistorymsg">
								<script>
									var sURL = '';
									var sURL = '/cc7HsK6AzqPExaStdMeldMqkx8!ckXx9sg==-ZJmtfnaApmqpmLRkZadicphynOJia2mXmbJorbnKfcWdyQ==?No=202112270415&Lang=tw&Send=1';
								</script>
								<table class="agent-table min font-xxs" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<th>#</th>
											<th>時間</th>
											<th>
												<div>期數</div>
												<div>投注內容</div>
											</th>
											<th>輸贏結果</th>
											<th>狀態</th>
										</tr>
									</thead>
									<tbody>
										<?php
										foreach($aRecordData['aBetData'] as $LPnId => $LPaBetData)
										{
										?>
										<tr>
											<td><?php echo $LPnId;?></td>
											<td><span class="scale08"><?php echo $LPaBetData['sCreateTime'];?></span></td>
											<td>
												<div class="scale08"><?php echo $LPaBetData['sNo'];?></div>
												<div><?php echo aBET[$LPaBetData['sContent0']];?></div>
											</td>
											<td><span class="scale08"><?php echo $LPaBetData['nMoney1'];?></span></td>
											<td class="chkprize betDetailTxt"><?php echo aSTATUS[$LPaBetData['nStatus']];?></td>
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>

						<!--	打賞	-->
						<div data-msg="grps" id="donate" class="donateBigBox DisplayBlockNone JqBlock JqDonateBox" data-btn="donate" data-selectitem="">
							<div class="donateBox">
								<div class="donateInner">
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
										<div><?php #echo aBET['SUMMONEY'];?>總金額</div>
										<div class="JqDonateMoney">0</div>
									</div>
									<div class="donateBtnBox">
										<div class="donateBtn confirm JqDonateSubmit">
											<div class="donateBtnTxt"><?php #echo aBET['CONFIRM'];?>確定</div>
										</div>
										<div class="donateBtn clear JqDonateClear">
											<div class="donateBtnTxt"><?php #echo aBET['CLEAR'];?>清除</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- 牌路 -->
						<div data-msg="grps" id="gameroad" class="DisplayBlockNone JqBlock" data-btn="gameroad">
							<div class="gameRoadBox JqGameRoadBox">
								<!-- 棋盤路 -->
								<div class="gameRoad first JqMainRoadBox" data-col="6">
									<div class="RoadBox">
										<?php
											for($i=0;$i<6;$i++)
											{
											?>    <div class="RoadRow">
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
											<div class="gameAnalyItemTxt"><?php #echo aBET['BK'];?>庄</div>
										</div>
										<div class="gameAnalyItemVal FontRedImp JqBankerWin" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['BW'];?></div>
									</div>
									<div class="gameAnalyItem">
										<div class="gameAnalyItemTit BgBlue">
											<div class="gameAnalyItemTxt"><?php #echo aBET['PL'];?>閑</div>					
										</div>
										<div class="gameAnalyItemVal FontBlueImp JqPlayerWin" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['PW'];?></div>
									</div>
									<div class="gameAnalyItem">
										<div class="gameAnalyItemTit BgGreenImp">
											<div class="gameAnalyItemTxt"><?php #echo aBET['DRAW'];?>和</div>					
										</div>
										<div class="gameAnalyItemVal FontGreenImp JqDraw" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['DRAW'];?></div>
									</div>
									<div class="gameAnalyItem">
										<div class="gameAnalyItemTit BgRed">
											<div class="gameAnalyItemTxt"><?php #echo aBET['BP'];?>庄對</div>
										</div>
										<div class="gameAnalyItemVal FontRedImp JqBankerPair" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['BP'];?></div>
									</div>
									<div class="gameAnalyItem">
										<div class="gameAnalyItemTit BgBlue">
											<div class="gameAnalyItemTxt"><?php #echo aBET['PP'];?>閑對</div>
										</div>
										<div class="gameAnalyItemVal FontBlueImp JqPlayerPair" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['PP'];?></div>
									</div>
									<div class="gameAnalyItem">
										<div class="gameAnalyItemTit BgBlackImp">
											<div class="gameAnalyItemTxt"><?php #echo aBET['SUM'];?>總</div>
										</div>
										<div class="gameAnalyItemVal FontBlack JqGamesAll" data-nGame="<?php #echo $nGame;?>">14<?php #echo $aNumsCounting[$nGame]['PW']+$aNumsCounting[$nGame]['BW']+$aNumsCounting[$nGame]['DRAW'];?></div>
									</div>
								</div>
								<div class="gameAnalyAskBox">
									<div class="gameAskType blue JqAskBlock">
										<div class="gameAskTypeTit"><?php #echo aBET['NEXTPL'];?>閑問路</div>
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
										<div class="gameAskTypeTit"><?php #echo aBET['NEXTBK'];?>庄問路</div>
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
						</div>

						<!--	說明	-->
						<div data-msg="grps" id="immedia" class="font-xxs DisplayBlockNone JqBlock" data-btn="rule">
							<div class="normalBox">
								人面牌及十點牌（K、Q、J、10）都算做0點，A牌算作1點，其餘所有牌均以面值來計算，如果有必要，則按下面列出的特定規則給雙方發第三張牌，總點數最接近9點的一方獲勝。贏家將贏得與原賭注相等的賭金。但是，每次下注（莊家）者贏得賭注之後，需要從贏錢中扣除5%傭金。如果雙方的總點數相同，下注打平者獲勝，將支付8倍賭金。此時，下注在（閑家）和（莊家）者既不會贏錢也不會輸錢。
								（閑家）在6或7點停牌時，如果莊家的總點數是0、1、2、3、4、5，則必須拿牌，如果總點數是6、7、8、9，則停牌。如果莊家的總點數是0、1、2，則必須拿牌，然後遵循莊家規則。
							</div>
						</div>                              
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if(false){?>
<div class="gameContainer">
	<div class="gameMainBox JqViewBox">

		<!-- <div class="betLastBox BG DisplayBlockNone JqFinalRound">
			<div class="betLastTxt">最後一局</div>
		</div> -->

		<!-- 視頻區，荷官休息時換為下方圖片(兩個不同時存在) -->
		<!-- <div class="gameVideoBox"></div> -->
		<div id="video-player" class="gameVideoBox" controls playsinline></div>

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
			<!-- 開牌區 -->
			<div class="betFinalBox DisplayBlockNone JqFinalBox">
				<div class="betFinalInner">
					<div class="betFinalBlock player">
						<div class="betFinalTop">
							<div class="betFinalTit"><?php echo aBET['PL'];?></div>
							<div class="betFinalSum JqPlayerGrade">0</div>
						</div>
						<div class="betFinalCardBox">
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
					</div>
					<div class="betFinalIcon">
						<img src="images/ICON/VS.png?t=<?php echo VTIME;?>" alt="">
					</div>
					<div class="betFinalBlock bank">
						<div class="betFinalTop">
							<div class="betFinalSum JqBankerGrade">0</div>
							<div class="betFinalTit"><?php echo aBET['BK'];?></div>						
						</div>
						<div class="betFinalCardBox">
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
					</div>			
				</div>			
			</div>
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
<?php }?>