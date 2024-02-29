<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url1="<?php echo $aUrl['sLobbyAjax'];?>" data-nNoId="" data-nCheckTime="">
<input name="sBetJWT" type="hidden" value="<?php echo $sBetJWT; ?>" data-url="<?php echo $aUrl['sBetAjax'];?>">
<input name="sBanker0JWT" type="hidden" value="<?php echo $sBanker0JWT; ?>" data-url="<?php echo $aUrl['sBankerAjax'];?>">
<input name="sBanker1JWT" type="hidden" value="<?php echo $sBanker1JWT; ?>" data-url="<?php echo $aUrl['sBankerAjax'];?>">
<input name="sBankerSumJWT" type="hidden" value="<?php echo $sBankerSumJWT; ?>" data-url="<?php echo $aUrl['sBankerAjax'];?>">
<input name="sDonateJWT" type="hidden" value="<?php echo $sDonateJWT; ?>" data-url="<?php echo $aUrl['sDonateAjax'];?>">
<input name="sCenterJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sRateAjax'];?>">
<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sBankerHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBankerHistoryAjax'];?>">
<input name="sDonateHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php #echo $aUrl['sDonateHistoryAjax'];?>">
<input name="sCancelBetJWT" type="hidden" value="<?php echo $sCancelBetJWT; ?>" data-url="<?php echo $aUrl['sCancelAjax'];?>">
<input name="sCancelCollectJWT" type="hidden" value="<?php echo $sCancelCollectJWT; ?>" data-url="<?php echo $aUrl['sCancelAjax'];?>">
<input name="sBetRankingJWT" type="hidden" value="<?php echo $sBetRankingJWT; ?>" data-url="<?php echo $aUrl['sBetRankingAjax'];?>">
<input name="sWatchDealerJWT" type="hidden" value="<?php #echo $sWatchDealerJWT; ?>" data-url="<?php #echo $aUrl['sWatchDealerAjax'];?>" data-dealer="<?php #echo $aData[$nGame]['nDealer'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-picture="<?php echo $aUser['sPicture'];?>" data-mute="<?php echo $aUser['nMute'];?>" data-test="<?php echo $aUser['nTest'];?>">
<input name="aPlayerData" type="hidden" value='<?php echo json_encode($aPlayerData);?>'>
<input name="sRoadJWT" type="hidden" value='<?php echo $sRoadJWT;?>' data-url="<?php echo $aUrl['sRoadAjax'];?>" data-historyJWT="<?php echo $sHistoryJWT;?>">
<input name="sTotalBetJWT" type="hidden" value='<?php echo $sTotalBetJWT;?>' data-url="<?php echo $aUrl['sTotalBetAjax'];?>">
<input name="sChatJWT" type="hidden" value='<?php echo $sChatJWT;?>' data-url='<?php echo $aUrl['sChatAjax'];?>'>
<input name="sStickerJWT" type="hidden" data-url='<?php echo $aUrl['sStickerAjax'];?>' data-buy="<?php echo $sBuyStickerJWT;?>" data-use="<?php echo $sUseStickerJWT;?>">
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
<?php
if(isset($aSetArrayPreFetchImage) && is_array($aSetArrayPreFetchImage))
{
	foreach($aSetArrayPreFetchImage as $LPsUrl)
	{
		echo '<link rel="prefetch" href="'.$LPsUrl.'?t='.VTIME.'" as="image">';
	}
}
?>
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
								if(false)
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
								foreach($aJsonMsg as $LPsUnixTime => $LPaJsonMsg)
								{
									if(NOWTIME < $LPsUnixTime/10000)
									{
										break;
									}
									if(!isset($LPaJsonMsg['sMsg'])) continue;
									echo $LPaJsonMsg['sMsg'];
								}
								?>
							</div>
							<div class="chatFoot DisplayBlockNone" data-kind="road" style="bottom:50px">
								<div class="chatFootContainer">
									<table class="agent-table min" cellspacing="0" cellpadding="0">
										<tr>
											<td class="WindowTit" style="border-right: 2px solid;"></td>
											<?php for($i=1;$i<=9;$i++){?>
											<td class="WindowTit JqRoadResultNo"><?php echo $i;?></td>
											<?php }?>
										</tr>
										<tr>
											<td class="WindowTit" style="border-right: 2px solid;">A</td>
											<?php for($i=0;$i<9;$i++){?>
											<td class="WindowTit JqRoadResultA<?php echo $i;?>">-</td>
											<?php }?>
										</tr>
										<tr>
											<td class="WindowTit" style="border-right: 2px solid;">B</td>
											<?php for($i=0;$i<9;$i++){?>
											<td class="WindowTit JqRoadResultB<?php echo $i;?>">-</td>
											<?php }?>
										</td>
										<tr>
											<td class="WindowTit" style="border-right: 2px solid;">C</td>
											<?php for($i=0;$i<9;$i++){?>
											<td class="WindowTit JqRoadResultC<?php echo $i;?>">-</td>
											<?php }?>
										</td>
									</table>
									<!-- <div class="chatFootSubBtn">
										<div class="chatFootSubBtnTxt JqClose" data-kindctrl="road">
											<i class="fas fa-arrow-down"></i>
										</div>
									</div> -->
								</div>
							</div>
							<div class="chatFoot">
								<div class="chatFootContainer">
									<div class="chatFootTxt">
										<div class="Textarea">
											<textarea name="JqChatText"></textarea>
										</div>
									</div>
									<div class="chatFootSubBtn" data-kindctrl="road">
										<div class="chatFootSubBtnTxt JqRoadDisplayOn">
											路
										</div>
									</div>
									<div class="chatFootSubBtn img JqWindowBtn" data-kindctrl="sticker">
										<div class="chatFootSubBtnTxt">
											<i class="far fa-image"></i>
										</div>
									</div>
									<div class="chatFootSubBtn">
										<div class="chatFootSubBtnTxt JqChatSubmit">
											<i class="fas fa-paper-plane"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="chatStickerBox JqChatStickerBox DisplayBlockNone">
								<div class="chatStickerImg">
									<video playsinline webkit-playsinline id="JqStickerVideo" class="JqStickerVideo" data-use="0" data-videtrigger="0" style="width:100%">
										<source src="" type="video/mp4" height="300">
									</video>
									<!-- <img src="" alt="" class="JqStickerImg"> -->
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
														<div class="betTypeBtnTxt">我要排莊/取消</div>
													</div>
													<div class="betTypeBtn JqWindowBtn" data-kindctrl="collect">
														<div class="betTypeBtnTxt">我要打莊/配莊</div>
													</div>
												</div>
												<div class="betTypeBox">
													<div class="betTypeBtn JqWindowBtn" data-kindctrl="cancelbet">
														<div class="betTypeBtnTxt">取消投注</div>
													</div>
													<div class="betTypeBtn JqWindowBtn" data-kindctrl="cancelcollect">
														<div class="betTypeBtnTxt">取消配莊</div>
													</div>
												</div>
												<div class="betAreaBox">
													<div class="betAreaBlock top">
														<div class="betArea second">
															<div class="betAreaTit">初A總額</div>
															<div class="betAreaAmount JqATotalBet">0</div>
														</div>
														<div class="betArea third">
															<div class="betAreaTit">川B總額</div>
															<div class="betAreaAmount JqBTotalBet">0</div>
														</div>
														<div class="betArea forth">
															<div class="betAreaTit">尾C總額</div>
															<div class="betAreaAmount JqCTotalBet">0</div>
														</div>
													</div>
												</div>			
												<div class="betAreaBox">
													<div class="betAreaBlock top">
														<div class="betArea second JqAZone">
															<div class="betAreaTit">初A</div>
															<div class="betAreaAmount JqAWinBet">0</div>
														</div>
														<div class="betArea third JqBZone">
															<div class="betAreaTit">川B</div>
															<div class="betAreaAmount JqBWinBet">0</div>
														</div>
														<div class="betArea forth JqCZone">
															<div class="betAreaTit">尾C</div>
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

										<?php
										// foreach($aCoinArrayMb as $i)
										$nI = 0;
										foreach($aSetCoinArray as $LPnId => $LPaChip)
										{
											if($nI % 5 == 0)
											{
												echo '
												<div class="betCoinBox JqCoinBox">
													<div class="betCoinBox1 JqCoinBox1">';
											}
												#<link rel="prefetch" href="images/resize/coin/'.$LPnId.'.png?t='.VTIME.'\'?t='.VTIME.'" as="image">
													echo' <div class="betCoinBtn JqCoin JqCoinTdBtn JqCoinTdBtn'.$LPnId.'" data-clip="'.$LPaChip['nValue'].'"  style="width:calc(100%/6);">
															<div class="betCoinBtnInner JqCoinInner">
																<div class="betCoinBtnPic JqCoinPic BG" style="background-image:url(\'images/resize/coin/'.$LPnId.'.png?t='.VTIME.'\');"></div>
															</div>
														</div>';
													// echo' <div class="betCoinBtn JqCoin JqCoinTdBtn JqCoinTdBtn'.$LPnId.'" data-clip="'.$LPaChip['nValue'].'"  style="width:calc(100%/6);">
													// 	<div class="betCoinBtnInner JqCoinInner">
													// 		<div class="betCoinBtnPic JqCoinPic BG" 
													// 		style=" margin: auto;display: flex;justify-content: center;align-items: center; 
													// 		background-image:url(\'images/coin/empty.png?t='.VTIME.'\');">
													// 			<div style="font-weight: 900;font-family: sans-serif;font-size: 40%;">'.$LPaChip['sName0'].'</div>
													// 		</div>
													// 	</div>
													// </div>';
											if($nI % 5 == 4)
											{
												echo '</div>
												</div>';
											}
											$nI++;
										}
										echo' <div class="betCoinBtn"  style="width:calc(100%/6);">
												<div class="betCoinBtnInner">
													<div class="betCoinBtnPic BG"></div>
												</div>
											</div>';
										?>
										<div class="betAreaBox"></div>
										<div class="betAreaBox">
											<div class="betAreaBlock top">
												<div class="betArea second">
													<div class="betAreaTit">初A</div>
													<div class="Ipt">
														<input type="number" value="" class="JqAInputBet" style="text-align:center">
													</div>
												</div>
												<div class="betArea third">
													<div class="betAreaTit">川B</div>
													<div class="Ipt">
														<input type="number" value="" class="JqBInputBet" style="text-align:center">
													</div>
												</div>
												<div class="betArea forth">
													<div class="betAreaTit">尾C</div>
													<div class="Ipt">
														<input type="number" value="" class="JqCInputBet" style="text-align:center">
													</div>
												</div>
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
									<div class="betCtrlBtn JqCancelBet0">
										<div class="betCtrlBtnIcon">
											<img src="images/ICON/cancel.png?t=<?php echo VTIME;?>" alt="">
										</div>
										<div class="betCtrlBtnIconTxt JqCancelBet0"><?php #echo aBET['CANCEL'];?>取消</div>
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
									<tbody class="JqInfo">
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
												<div><?php echo $LPaBetData['nMoney0'];?></div>
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
									<?php foreach($aDefineDonate as $LPnId => $LPnMoney){?>
									<div class="donateBlock JqDonateItem" data-num="<?php echo $LPnMoney; ?>" data-item="<?php echo $LPnId;?>">
										<div class="donatePic">
											<img src="images/donate/<?php echo $LPnId; ?>.png?1" alt="">
										</div>
										<div class="donateMoney">
											<span>$</span>
											<span><?php echo $LPnMoney; ?></span>
										</div>
									</div>
									<?php }?>
									<div class="donateBlock">
										<div class="donatePic" style="line-height: 81px;">打賞金額</div>
										<div class="donateMoney">
											<span class="Ipt">
												<input type="number" value="" class="JqInputDonate" style="text-align:center;border: groove;">
											</span>
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
									// for($nI=0;$nI<$nFile;$nI++)
									foreach($aMall as $LPnId => $LPaMall)
									{
									?>
									<div class="stickerBlock JqStickerItem" data-num="<?php echo $LPnId; ?>" data-src="<?php echo $LPaMall['aImage'][0]['sUrl'];?>">
										<div class="stickerPic">
											<img src="<?php echo $LPaMall['aImage'][1]['sUrl'];?>" alt="" >
											<!-- <video playsinline webkit-playsinline muted autoplay loop style="width:100%">
												<source src="<?php echo $LPaMall['aImage'][0]['sUrl'];?>" type="video/mp4" height="300">
											</video> -->
										</div>
										<div class="stickerMoney">
											<span>$</span>
											<span><?php echo $LPaMall['nPrice0']; ?></span>
										</div>
										<div class="stickerAmount">
											<div class="stickerAmountBtn JqStickerBtn" data-type="-1">
												<i class="fas fa-minus"></i>
											</div>
											<div class="Ipt">
												<input type="number" value="0" class="JqStickerIpt" data-id="<?php echo $LPnId;?>" data-name0="<?php echo $LPaMall['sName0'];?>" data-price0="<?php echo $LPaMall['nPrice0'];?>">
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
										<div class="stickerBtn confirm JqWindowBtn JqConfirmSticker" data-kindctrl="buysticker">
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
										<?php                                                            
										foreach($aMall as $LPnId => $LPaMall)
										{
										?>
											<div class="stickerBagItem JqWindowBtn" data-kindctrl="usesticker" data-num="<?php echo $LPnId;?>" data-sticker="<?php echo $LPaMall['aImage'][0]['sUrl'];?>">
												<div class="stickerBagItemImg">
													<img src="<?php echo $LPaMall['aImage'][1]['sUrl'];?>" alt="">
													<!-- <video playsinline webkit-playsinline muted autoplay loop style="width:100%">
														<source src="<?php echo $LPaMall['aImage'][0]['sUrl'];?>" type="video/mp4" height="300">
													</video> -->
												</div>
												<div class="stickerBagItemAmount JqStickerCount JqSticker<?php echo $LPnId;?>Count">*<?php echo 0; ?></div>
											</div>
										<?php
										}
										?>
									<div class="stickerBagBox">
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
						<!--	當莊紀錄	-->
						<div data-msg="grps" id="mybankerhistory"  class="DisplayBlockNone JqBlock" data-btn="bankerrecord">
							<div id="acclsbtn">
								<div class="detailBox Table">
									<div>
										<div>
											<!-- <a href="javascript:void(0)" style="width:20%;" data-clk="accls" data-type="mybankerhistory1" data-tg="mybankerhistorymsg" data-ajax="/cc7HsK6Qx3vD0Z2tdMeldMqkx8!c-<?php #echo $sCatchKey; ?>?Lang=<?php #echo $sLang; ?>" class="menubtn menubtn-green "><?php #echo aGlobalText['NotSettle']; ?>未結算</a> -->
											<a href="javascript:void(0)" style="width:20%;" data-type="mybankerhistory2" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time());?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="mybankerhistory3" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400) ;?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="mybankerhistory4" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 2) ;?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="mybankerhistory5" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox">投注明細</a>
										</div>
									</div>
								</div>
							</div>

							<!-- ajax後 css不要帶過來 -->
							<div id="mybankerhistorymsg">
								<table class="agent-table min font-xxs" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<th>#</th>
											<th>時間</th>
											<th>
												<div>期數</div>
												<div>入莊額度</div>
											</th>
											<th>輸贏結果</th>
											<!-- <th>狀態</th> -->
										</tr>
									</thead>
									<tbody class="JqBankerInfo">
										<?php
										foreach($aBankerRecordData['aBetData'] as $LPnId => $LPaBetData)
										{
										?>
										<tr>
											<td><?php echo $LPnId;?></td>
											<td><span class="scale08"><?php echo $LPaBetData['sCreateTime'];?></span></td>
											<td>
												<div class="scale08"><?php echo $LPaBetData['sNo'];?></div>
												<div><?php echo $LPaBetData['nMoney0'];?></div>
											</td>
											<td><span class="scale08"><?php echo $LPaBetData['nMoney1'];?></span></td>
											<!-- <td class="chkprize betDetailTxt"><?php #echo $LPaBetData['nStatus'];?></td> -->
										</tr>
										<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<!--	歷史獎號	-->
						<div data-msg="" id=""  class="DisplayBlockNone JqBlock" data-btn="history">
							<div id="acclsbtn">
								<div class="detailBox Table">
									<div>
										<div>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory2" data-tg="myhistorymsg" class="menubtn menubtn-green JqNumHistory"><?php echo date('m-d', time());?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory3" data-tg="myhistorymsg" class="menubtn menubtn-green JqNumHistory"><?php echo date('m-d', time() - 86400) ;?></a>
											<a href="javascript:void(0)" style="width:20%;" data-type="myhistory4" data-tg="myhistorymsg" class="menubtn menubtn-green JqNumHistory"><?php echo date('m-d', time() - 86400 * 2) ;?></a>
										</div>
									</div>
								</div>
							</div>

							<!-- ajax後 css不要帶過來 -->
							<div id="myhistorymsg">
								<table class="agent-table min font-xxs" cellspacing="0" cellpadding="0">
									<thead>
										<tr>
											<th colspan="1" rowspan="2">#</th>
											<th colspan="2" rowspan="2">時間</th>
											<th colspan="3">開牌結果</th>
											<th colspan="6">投注總額</th>
											<th colspan="2" rowspan="2">操作</th>
										</tr>
										<tr>
											<th colspan="1">A</th>
											<th colspan="1">B</th>
											<th colspan="1">C</th>
											<th colspan="2">A</th>
											<th colspan="2">B</th>
											<th colspan="2">C</th>
										</tr>
									</thead>
									<tbody class="JqInfo">
									</tbody>
								</table>
							</div>
						</div>
						<!--	說明	-->
						<div data-msg="grps" id="immedia" class="font-xs font-bolder DisplayBlockNone JqBlock" data-btn="buybanker">
							<!-- <div class="normalBox">
								<h3><span class="mw-headline" style="font-size: 22px;">買官說明：</span></h3>
								<p style="font-size: 22px;">１.買官最低打莊點數為10萬點</p>
								<p style="font-size: 22px;">另扣買官點數3千點</p>
								<br></br>
								<p style="font-size: 22px;">２.如要買官，請於第二墩遊戲結束後告知荷官</p>
								<p style="font-size: 22px;">系統將會幫貴賓繼續安排打莊</p>
								<br></br>
								<p style="font-size: 22px;">３.每次打莊完成後，可買官一次</p>
								<br></br>
								<p style="font-size: 22px;">４.打完笫1墩，第2墩推掉者，不得買官</p>
							</div> -->
							<?php echo $aAnnounce[0]['sContent0'];?>
							<!-- <img src="images/announcement.jpg" alt=""> -->
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
							<div class="WindowTit">確認搶莊/取消搶莊</div>
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
				<div class="WindowTit" style="text-align: center;">投注總額:<span class="JqSumBet"><?php echo $nSumMoney0;?></span></div>
					<div class="Ipt">
						<input type="number" name="nMoney" class="JqMoneyBanker1" style="text-align: center;" placeholder="請填寫集資金額(打莊/配莊)">
					</div>
					<?php
					// foreach($aCoinArrayMb as $i)
					$nI = 0;
					foreach($aCoinBanker as $LPnId => $LPaChip)
					{    
						if($nI % 4 == 0)
						{
							echo '<div class="betCoinBox JqCoinBox">
							<div class="betCoinBox1 JqCoinBox1">';
						}
						$sSumClass = '';
						$sSumAttr = '';
						$sChipName = 'empty';
						if($LPaChip['nValue']==-1)
						{
							$sSumClass = 'JqSumCoin';
							$sSumAttr = 'data-bSumMoney="0"';
							$LPaChip['nValue'] = $nSumMoney;
							$sChipName = 'empty2';
						}
						#<link rel="prefetch" href="images/resize/coin/'.$sChipName.'.png?t='.VTIME.'" as="image">				
						echo' <div class="betCoinBtn JqCoinBanker '.$sSumClass.'" data-clip="'.$LPaChip['nValue'].'" '.$sSumAttr.' style="width:calc(100%/7);">
								<div class="betCoinBtnInner JqCoinInner">
									
									<div class="betCoinBtnPic JqCoinPic BG" 
									style=" margin: auto;display: flex;justify-content: center;align-items: center; 
									background-image:url(\'images/resize/coin/'.$sChipName.'.png?t='.VTIME.'\');">
										<div style="font-weight: 900;font-family: sans-serif;font-size: 40%;">'.$LPaChip['sName0'].'</div>
									</div>
								</div>
							</div>';
						if($nI % 4 == 3)
						{
							echo '</div></div>';
						}
						$nI++;
					}
					?>
				</div>
				<div class="submitBtnBox">
					<table class="jumpMsgBtnBox">
                                    <tbody>
                                          <tr>
								<td class="jumpMsgBtnCell">
									<div class="jumpMsgBtn WordBreakBreakAll">
										<div class="submitBtnTxt JqSubmitBanker1">確認</div>
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
				// for($nI=0;$nI<$nFile;$nI++)
				foreach($aMall as $LPnId => $LPaMall)
				{
				?>
					<div class="stickerBagItem JqStickerBtnGo" data-kindctrl="usesticker" data-num="<?php echo $LPnId;?>" data-sticker="<?php echo $LPaMall['aImage'][0]['sUrl'];?>">
						<div class="stickerBagItemImg">
							<img src="<?php echo $LPaMall['aImage'][1]['sUrl'];?>" alt="">
							<!-- <video playsinline muted autoplay loop style="width:100%">
								<source src="<?php echo $LPaMall['aImage'][0]['sUrl'];?>" type="video/mp4" height="300">
							</video> -->
						</div>
						<div class="stickerBagItemAmount JqStickerCount JqSticker<?php echo $LPnId;?>Count">*<?php echo 0; ?></div>
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
									<div class="submitBtn JqStickerBtnGo submit JqStkConfirm" data-num="" data-sticker="">
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
<div class="WindowBox JqWindowBox" data-kind="buysticker">
	<div class="WindowContainer">
		<div class="WindowContent bet">
			<form action="">
				<div class="betCheckBox">
					<div class="WindowTit"><?php echo '貼圖清單';?></div>
					<div class="infoBox">
						<table class="infoTable">
							<thead class="infoThead">
								<tr>
									<th class="infoTh"><?php echo '貼圖內容';?></th>
									<th class="infoTh"><?php echo '貼圖數量';?></th>
									<th class="infoTh"><?php echo '貼圖金額';?></th>
								</tr>
							</thead>
							<tbody class="JqStickerCheckBoxContent">
							</tbody>
							<tfoot class="infoTfoot">
								<tr>
									<td class="infoTf"><?php echo aCENTER['TOTAL'];?></td>
									<td class="infoTf JqStickerCheckBoxCount">0</td>
									<td class="infoTf JqStickerCheckBoxSum">0</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
				<div class="submitBtnBox">
					<table class="submitBtnTable">
						<tbody>
							<tr>
								<td class="submitBtnTd">
									<div class="submitBtn cancel JqClose" data-kindctrl="buysticker">
										<div class="submitBtnTxt"><?php echo aCENTER['CANCEL'];?></div>
									</div>
								</td>
								<td class="submitBtnTd">
									<div class="submitBtn">
										<input type="button" class="submitBtnTxt JqSubmitSticker" value="<?php echo '購買';?>">
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</form>
		</div>
	</div>
	<div class="WindowBg"></div>
</div>
<div class="WindowBox JqWindowBox" data-kind="cancelbet">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">取消投注</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="cancelbet">
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
										<input type="button" class="submitBtnTxt JqCancelBet" value="確認">
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
<div class="WindowBox JqWindowBox" data-kind="cancelcollect">
	<div class="WindowContainer">
		<div class="WindowTop">
			<table class="WindowTopTable">
				<tbody>
					<tr>
						<td class="WindowTopTd">
							<div class="WindowTit">取消配莊</div>
						</td>
						<td class="WindowCancelTd">
							<div class="WindowCancel JqClose" data-kindctrl="cancelcollect">
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
										<input type="button" class="submitBtnTxt JqCancelCollect" value="確認">
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