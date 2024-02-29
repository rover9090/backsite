<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url1="<?php echo $aUrl['sLobbyAjax'];?>" data-nNoId="" data-nEndTime="">
<input name="sBetJWT" type="hidden" value="<?php echo $sBetJWT; ?>" data-url="<?php echo $aUrl['sBetAjax'];?>">
<input name="sBanker0JWT" type="hidden" value="<?php echo $sBanker0JWT; ?>" data-url="<?php echo $aUrl['sBankerAjax'];?>">
<input name="sBanker1JWT" type="hidden" value="<?php echo $sBanker1JWT; ?>" data-url="<?php echo $aUrl['sBankerAjax'];?>">
<input name="sDonateJWT" type="hidden" value="<?php echo $sDonateJWT; ?>" data-url="<?php echo $aUrl['sDonateAjax'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input name="sWatchDealerJWT" type="hidden" value="<?php echo $sWatchDealerJWT; ?>" data-url="<?php #echo $aUrl['sWatchDealerAjax'];?>" data-dealer="<?php #echo $aData[$nGame]['sDealer'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-mute="<?php #echo $aUser['nMute'];?>" data-test="<?php #echo $aUser['nTest'];?>">
<input name="aPlayerData" type="hidden" value='<?php echo json_encode($aPlayerData);?>'>
<input name="sSocketJWT" type="hidden" value='<?php #echo $sSocketJWT;?>' data-json='<?php #echo json_encode($aSocketPack);?>'>
<input name="sChatJWT" type="hidden" value='<?php echo $sChatJWT;?>' data-url='<?php echo $aUrl['sChatAjax'];?>'>
<input type="hidden" class="JqaJsonMsg" value='<?php echo json_encode($aJsonMsgChk);?>' >
<input class="JqIptCommission" type="hidden" value="0">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<input class="JqStream" type="hidden" value="<?php echo $sStreamUrl;?>">
<div>
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
								if(true)
								{
									$nTime = 10;
									foreach($aFakeMsg as $LPaMsg)
									{
										#1系統 2自己 3別人
										if($LPaMsg['nWho'] == 2)
										{
											##### 玩家自己
											$sHTML = '	<div class="betmsg font-xxs ifme msgSelf">
														<div class="coverbox">
															<div class="td float-left ifmeright msgSelfInf">
																<div class="betmsgBlock arrow">
																	<div class="betinfobox">
																		<div class="betinfo">
																			<div>'.$LPaMsg['sMsg'].'</div>
																		</div>
																	</div>                                                                                                      
																</div>
																<div class="dt-time">
																	<div class="dt-timeInner">'. '14:32:'.$nTime .'</div>
																</div>                                                                                              
															</div>
														</div>
													</div>';
										}
										else if($LPaMsg['nWho'] == 1)
										{
											$sHTML = '	<div class="betmsg font-xxs msgSystem">
														<div class="coverbox">
															<div class="td imgbox float-left">
																<img class="img-circle" src="images/headImg/0.jpg">
															</div>
															<div class="td float-left ifmeright msgSystemInf">   
																<div class="dtbox">
																	<div class="dt-nickname">'. '系統機器人' .'</div>                                                                                                      
																</div>                                                                                             
																<div class="betmsgBlock arrow">';
																if(isset($LPaMsg['sImg']) && $LPaMsg['sImg'] != '')
																{
																	$sHTML .= '<div class="betinfobox img">
																				<div class="betinfo">
																					<div>'.$LPaMsg['sImg'].'</div>
																				</div>
																			</div> ';
																}
																else if($LPaMsg['sMsg'] != '')
																{
																	$sHTML .= '<div class="betinfobox">
																				<div class="betinfo">
																					<div>'.$LPaMsg['sMsg'].'</div>
																				</div>
																			</div> ';
																}                                                                                                   
											$sHTML .='                    </div>     
																<div class="dt-time">
																	<div class="dt-timeInner">'. '14:32:'.$nTime .'</div>
																</div>                                                                                           
															</div>
														</div>
													</div>';
										}
										else
										{
											$sHTML = '	<div class="betmsg font-xxs msgMember">
														<div class="coverbox">
															<div class="td imgbox float-left">';
																if($nTime%2==0)
																{
																	$sHTML .= '<img class="img-circle" src="images/headImg/4.jpg">';
																}
																else
																{
																	$sHTML .= '<img class="img-circle" src="images/headImg/5.jpg">';
																}
											$sHTML .=                        '</div>
															<div class="td float-left ifmeright msgMemberInf">
																<div class="dtbox">
																	<div class="dt-nickname">'. $LPaMsg['sName'] .'</div>                                                                                                      
																</div>
																<div class="betmsgBlock arrow">
																	<div class="betinfobox">
																		<div class="betinfo">
																			<div>'.$LPaMsg['sMsg'].'</div>
																		</div>
																	</div>                                                                                                     
																</div>
																<div class="dt-time">
																	<div class="dt-timeInner">'. '14:32:'.$nTime .'</div>
																</div>
															</div>
														</div>
													</div>';
										}
										echo $sHTML;
										$nTime ++;
									}
								}
								?>
							</div>
							<div class="chatFoot">
								<div class="chatFootContainer">
									<div class="chatFootTxt">
										<div class="Textarea">
											<textarea name=""></textarea>
										</div>
									</div>
									<div class="chatFootSubBtn img JqWindowBtn" data-kindctrl="sticker">
										<div class="chatFootSubBtnTxt">
											<i class="far fa-image"></i>
										</div>
									</div>
									<div class="chatFootSubBtn">
										<div class="chatFootSubBtnTxt">
											<i class="fas fa-paper-plane"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="chatStickerBox JqChatStickerBox DisplayBlockNone">
								<div class="chatStickerImg">
									<img src="" alt="" class="JqStickerImg">
								</div>
								<div class="chatStickerBg"></div>
							</div>
						</div>

						<!--	start	-->
						<div id="betform" class="mainBlock DisplayBlockNone JqBlock JqBetBtnBox" data-msg="grps" data-btn="bet">
							<div class="betBigOuter">
								<div class="betMainBox">
									<div class="betMainInner">
										<div class="betPlayBox JqBetPlayerBox">
											<div class="betContainer">
												<div class="betTypeBox">
													<div class="betTypeBtn JqWindowBtn" data-kindctrl="banker">
														<div class="betTypeBtnTxt">我要搶莊</div>
													</div>
													<div class="betTypeBtn JqWindowBtn" data-kindctrl="collect">
														<div class="betTypeBtnTxt">我要集資</div>
													</div>
												</div>
												<div class="betAreaBox">
													<div class="betAreaBlock top">
														<div class="betArea second JqAZone">
															<div class="betAreaTit">初二</div>
															<div class="betAreaAmount JqAWinBet">0</div>
														</div>
														<div class="betArea third JqBZone">
															<div class="betAreaTit">川三</div>
															<div class="betAreaAmount JqBWinBet">0</div>
														</div>
														<div class="betArea forth JqCZone">
															<div class="betAreaTit">尾四</div>
															<div class="betAreaAmount JqCWinBet">0</div>
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
											<a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory1" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>" class="menubtn menubtn-green "><?php #echo aGlobalText['NotSettle']; ?>未結算</a>
											<a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory2" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>&Day=<?php #echo date('Y-m-d', time()); ?>" class="menubtn menubtn-green "><?php #echo date('m-d', time());?>12/27</a>
											<a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory3" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>&Day=<?php #echo date('Y-m-d', time() - 86400); ?>" class="menubtn menubtn-green "><?php #echo date('m-d', time() - 86400) ;?>12/26</a>
											<a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory4" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>&Day=<?php #echo date('Y-m-d', time() - 86400 * 2); ?>" class="menubtn menubtn-green "><?php #echo date('m-d', time() - 86400 * 2) ;?>12/25</a>
											<a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="myhistory5" data-showdate="true" data-tg="myhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?ALL=1&Lang=<?php #echo $sLang; ?>" class="menubtn menubtn-green "><?php #echo aGlobalText['BetDetail']; ?>投注明細</a>
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
										for($nI=0;$nI<44;$nI++)
										{
										?>
										<tr>
											<td>17</td>
											<td><span class="scale08">14:45:35</span></td>
											<td>
												<div class="scale08">2112270414</div>
												<div>尾四</div>
											</td>
											<td><span class="scale08">+44.0000</span></td>
											<td class="chkprize betDetailTxt">中獎</td>
										</tr>
										<tr>
											<td>18</td>
											<td><span class="scale08">13:36:53</span></td>
											<td>
												<div class="scale08">2112270359</div>
												<div>初三</div>
											</td>
											<td><span class="scale08">-10.0000</span></td>
											<td class="chkprize betDetailTxt">未中獎</td>
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
									<div class="donateBlock JqDonateItem" data-num="<?php echo 168; ?>" data-item="1">
										<div class="donatePic">
											<img src="images/donate/<?php echo 1; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 168; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 599; ?>" data-item="2">
										<div class="donatePic">
											<img src="images/donate/<?php echo 2; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 599; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 888; ?>" data-item="3">
										<div class="donatePic">
											<img src="images/donate/<?php echo 3; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 888; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 1000; ?>" data-item="4">
										<div class="donatePic">
											<img src="images/donate/<?php echo 4; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 1000; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 3000; ?>" data-item="5">
										<div class="donatePic">
											<img src="images/donate/<?php echo 5; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 3000; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 5000; ?>" data-item="6">
										<div class="donatePic">
											<img src="images/donate/<?php echo 6; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 5000; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 10000; ?>" data-item="7">
										<div class="donatePic">
											<img src="images/donate/<?php echo 7; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 10000; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 88888; ?>" data-item="8">
										<div class="donatePic">
											<img src="images/donate/<?php echo 8; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 88888; ?></span>
										</div>
									</div>
									<div class="donateBlock JqDonateItem" data-num="<?php echo 99999; ?>" data-item="9">
										<div class="donatePic">
											<img src="images/donate/<?php echo 9; ?>.png" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo 99999; ?></span>
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

						<!-- 貼圖 -->
						<div data-msg="grps" id="buysticker" class="stickerBigBox DisplayBlockNone JqBlock JqStickerBox" data-btn="buysticker">
							<div class="stickerTypeBox">
								<div class="stickerTypeBtn JqStickerTypeBtn active" data-type="0">
									<div class="stickerTypeBtnTxt">購買</div>
								</div>
								<div class="stickerTypeBtn JqStickerTypeBtn" data-type="1">
									<div class="stickerTypeBtnTxt">我的倉庫</div>
								</div>
							</div>
							<div class="stickerBox active DisplayBlockNone JqStickerTypeBox" data-type="0">
								<div class="stickerInner">
									<?php
									$sPath = 'images/sticker/';
									$aFile = glob($sPath.'*.gif',GLOB_MARK);
									$nFile = count($aFile);
									for($nI=0;$nI<$nFile;$nI++)
									{
									?>
									<div class="stickerBlock JqStickerItem" data-num="<?php echo $nI; ?>" data-item="1">
										<div class="stickerPic">
											<img src="images/sticker/<?php echo $nI; ?>.gif?t=<?php echo VTIME; ?>" alt="">
										</div>
										<div class="stickerMoney">
											<span>$</span>
											<span><?php echo ($nI+18)*15; ?></span>
										</div>
										<div class="stickerAmount">
											<div class="stickerAmountBtn JqStickerBtn" data-type="-1">
												<i class="fas fa-minus"></i>
											</div>
											<div class="Ipt">
												<input type="number" value="0" class="JqStickerIpt">
											</div>
											<div class="stickerAmountBtn JqStickerBtn" data-type="1">
												<i class="fas fa-plus"></i>
											</div>
										</div>
									</div>
									<?php
									}
									?>
								</div>
								<div class="stickerCtrlBox">
									<div class="stickerBtnBox">
										<div class="stickerBtn confirm JqStickerSubmit">
											<div class="stickerBtnTxt"><?php #echo aBET['CONFIRM'];?>確定</div>
										</div>
										<div class="stickerBtn clear JqStickerClear">
											<div class="stickerBtnTxt"><?php #echo aBET['CLEAR'];?>清除</div>
										</div>
									</div>
								</div>
							</div>
							<div class="stickerBox bag DisplayBlockNone JqStickerTypeBox" data-type="1">
								<div class="stickerInner">
									<div class="stickerBagBox">
										<?php                                                            
										for($nI=0;$nI<$nFile;$nI++)
										{
										?>
											<div class="stickerBagItem JqWindowBtn" data-kindctrl="usesticker" data-sticker="images/sticker/<?php echo $nI; ?>.gif">
												<div class="stickerBagItemImg">
													<img src="images/sticker/<?php echo $nI; ?>.gif?t=<?php echo VTIME; ?>" alt="">
												</div>
												<div class="stickerBagItemAmount">*<?php echo ($nI+5)*7; ?></div>
											</div>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						</div>

						<!--	說明	-->
						<div data-msg="grps" id="immedia" class="font-xxs DisplayBlockNone JqBlock" data-btn="rule">
							<div class="normalBox">
								<h3><span class="mw-headline">執位</span></h3>
								<p>和打麻將一樣，打天九亦是四人一檯，各參與者應先選好一個位，然後輪流擲骰（兩顆），最大者為莊，可先選位。次者再選位，如此類推。</p>
								<h3><span class="editsection"></span><span class="mw-headline">開局</span></h3>
								<p>在第一局的第一輪開始時，最先出牌的人（頭牌）是由擲骰子決定。而在頭牌之下一位參與者則會被選為該局的莊家，該局勝出的參與者將會是下一局的莊
								家，而其他參與者則稱之為閒家。<br>
								</p>
								<h3><span class="mw-headline">牌頭</span></h3>
								<p>即開牌的方法。一般來說，洗好牌後各人將天九牌疊成一排八隻（面向下），共四排疊起的方式。擲骰者必定是莊（剛勝出對上一局的人）的對家（例如：東
								家勝出，則西家擲骰）。擲骰者於擲骰前必需表示開牌的方法（各家如何取牌）。一般的牌頭有以下數種：</p>
								<ul>
								<li><b>左</b>或<b>右</b>-每位玩家由左方（或右）輪流取兩棟（每棟四隻牌）共八隻牌</li>
								<li><b>一棟棟</b>-每位玩家輪流取一棟（共兩次）合共八隻牌</li>
								<li><b>間棟</b>-第一位玩家取1、3棟；第二位玩家取2、4棟；第三位玩家取5、7棟；第四位玩家取6、8棟</li>
								<li><b>大片／片皮</b>-每位玩家取最上面的八隻牌</li>
								<li><b>中掘／挖心</b>-第一位玩家取中間兩棟共八隻牌，餘家從中心兩旁各取一棟牌</li>
								<li><b>切耳</b>-和中掘相反。第一位玩家取兩旁各一棟共八隻牌，餘家從兩旁各取一棟牌</li>
								<li><b>龍頭鳳尾</b>-將尾的兩棟疊起在頭兩棟（即頭兩棟高八隻，餘下四棟高四隻）</li>
								<li><b>中掘兩邊飛／雙翼齊飛</b>-將1、8棟牌疊起在4、5棟上</li>
								<li><b>舞三獅</b>-將6、7、8棟牌疊起在1、3、5棟上</li>
								</ul>
								<p>依擲骰的數字由擲骰者數起先取牌，其他各玩家跟著取牌。<br>
								</p>
								<h3><span class="mw-headline">打牌</span></h3>
								<p>打天九的原理跟國際橋牌接近，每家有八隻牌，由莊家先出牌，莊家可選擇出單牌或組合牌，閒家必需要跟出（必須依上家打出之種類及／或組合）或選擇墊
								牌，每家出完牌後，最大的一家即勝出該圈，而有權於下一圈出牌。（舉例：如莊家先出斧頭對；第二家無任何比斧頭對大的文對，他則必需從手中剩下的牌中選出
								兩隻墊牌，墊牌時將牌面向下推出，不能讓其他玩家看到；如果第三家有一對梅花對‰，而他又選擇出牌(即打牌)的話，他則可將牌疊在斧頭對的上面；而如果第
								四家亦是墊牌，那麼第三家則贏了兩棟牌，並有權於下一圈先出牌。）值得一提的是打天九的出牌方向是逆時針的,
								但所謂「上家不打，下家不墊」，即未輪到你畤，不能墊牌（或打牌）。</p>
								<h3><span class="mw-headline">結牌</span></h3>
								<p>任何一方只要贏得最後一棟（或兩／三／四隻組合贏出最後兩／三／四棟），即算勝出（稱為結）。但是，有一個重要的條件是：最小要有兩棟才能勝出。所
								以，任何一方如果於頭七隻牌中沒有任何棟，已再沒有權利參與該局遊戲，儘管最後一隻是最大的牌，也必需墊牌（放棄該牌）。若然玩家於頭六隻牌沒有任何棟，
								而最後的兩隻牌是以組合形式打牌，這是容許的。</p>
								<h3><span class="editsection"></span><span class="mw-headline">算帳</span></h3>
								<p>四棟是一個基準，如能贏取4棟視為打和（稱之為歸棟）。輸家的計算方法是以4減去手中的棟數（例如：輸家有2棟，則他於這局輸2個單位，即4-2=
								2）。如果輸家無棟（稱為空棟），則需多賠1棟，共5棟。<br>
								</p>
								<p>若莊家為結者，敗者無論輸贏，都要加倍。如果莊家在終局前已經連續做了 n 次莊（剛贏得的下一任不算），莊家倍數是
								n+1。所以初任就乘二，兩任就乘三，如此類推。</p>
								若結者跟敗者都不是莊家，莊家倍數為一。<br>
							</div>
						</div>                              
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="WindowBox JqWindowBox" data-kind="banker">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">確認搶莊</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="banker">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqSubmitBanker0" value="確認">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg JqClose"></div>
</div>
<div class="WindowBox JqWindowBox" data-kind="collect">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">集資金額(打莊/配莊)</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="collect">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="collectAmBox">
					<div class="Ipt">
						<input type="number" name="nMoney" placeholder="請填寫集資金額(打莊/配莊)">
					</div>
				</div>
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqSubmitBanker1" value="確認">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg JqClose"></div>
</div>

<div class="WindowBox JqWindowBox" data-kind="sticker">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">選擇貼圖</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="sticker">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<div class="stickerBagBox window">
				<?php                                                            
				for($nI=0;$nI<$nFile;$nI++)
				{
				?>
					<div class="stickerBagItem JqStickerBtnGo" data-kindctrl="usesticker" data-sticker="images/sticker/<?php echo $nI; ?>.gif">
						<div class="stickerBagItemImg">
							<img src="images/sticker/<?php echo $nI; ?>.gif?t=<?php echo VTIME; ?>" alt="">
						</div>
						<div class="stickerBagItemAmount">*<?php echo ($nI+5)*7; ?></div>
					</div>
				<?php
				}
				?>
			</div>
		</div>
	</div>
	<div class="WindowBg JqClose"></div>
</div>

<div class="WindowBox JqWindowBox" data-kind="usesticker">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">是否使用此貼圖</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="usesticker">
								<i class="fas fa-times"></i>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="WindowContent">
			<form action="">
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn JqStickerBtnGo submit" data-sticker="">
										<div class="submitBtnTxt">確認</div>
									</div>
								</td>
								<td class="submitBtnTd">
									<div class="submitBtn cancel JqClose">
										<div class="submitBtnTxt">取消</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg JqClose"></div>
</div>