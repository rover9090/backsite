<input name="sHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBetHistoryAjax'];?>">
<input name="sBankerHistoryJWT" type="hidden" value="<?php echo ''; ?>" data-url="<?php echo $aUrl['sBankerHistoryAjax'];?>">
<input name="aUserData" type="hidden" data-money="<?php echo $aUser['nMoney'];?>" data-account="<?php echo $aUser['sAccount'];?>" data-uid="<?php echo $aUser['nUid'];?>" data-name0="<?php echo $aUser['sName0'];?>" data-picture="<?php echo $aUser['sPicture'];?>" data-mute="<?php echo $aUser['nMute'];?>" data-test="<?php echo $aUser['nTest'];?>">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">

<input type="hidden" name="sJWT" 		value="<?php echo $sJWT;?>">
<input type="hidden" name="nUid" 		value="<?php echo $nUid;?>">
<input type="hidden" name="sLineId" 	value="<?php echo $sLineId;?>">
<input type="hidden" name="sLiffId" 	value="<?php echo $sNowLiff;?>">
<div class="betbg">
	<div class="wrap">
		<div class="contentbox">
			<div id="msgbox" class="msgbox pk10boxmsg pk10">
				<div class="workBox">
					<div id="acclsbtn">
						<div class="detailBox Table">
								<div>
									<div>
										<a href="javascript:void(0)" style="width:33.3%;" class="menubtn menubtn-green JqBetHistoty"><?php echo '投注紀錄';?></a>
										<a href="javascript:void(0)" style="width:33.3%;" class="menubtn menubtn-green JqBankerHistoty"><?php echo '打莊紀錄';?></a>
										<a href="javascript:void(0)" style="width:33.3%;" class="menubtn menubtn-green JqJackPotHistoty"><?php echo '拉彩紀錄';?></a>
									</div>
								</div>
							</div>
						</div>
					<div class="masker"></div>
					<!--	紀錄	-->
					<div data-msg="grps" id="myhistory"  class="DisplayBlockNone active JqBlock" data-btn="betrecord">
						<div id="acclsbtn">
							<div class="detailBox Table">
								<div>
									<div>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory1" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time());?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory2" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory3" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 2) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory4" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 3) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory5" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 4) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory6" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 5) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory7" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox"><?php echo date('m-d', time() - 86400 * 6) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="myhistory8" data-tg="myhistorymsg" class="menubtn menubtn-green JqDetailBox">投注明細</a>
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

					<!--	當莊紀錄	-->
					<div data-msg="grps" id="mybankerhistory"  class="DisplayBlockNone JqBlock" data-btn="bankerrecord">
						<div id="acclsbtn">
							<div class="detailBox Table">
								<div>
									<div>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory1" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time());?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory2" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory3" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 2) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory4" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 3) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory5" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 4) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory6" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 5) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory7" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox"><?php echo date('m-d', time() - 86400 * 6) ;?></a>
										<a href="javascript:void(0)" style="width:12.5%;" data-type="mybankerhistory8" data-tg="mybankerhistorymsg" class="menubtn menubtn-green JqBankerDetailBox">打莊明細</a>
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

					<!--	彩金紀錄	-->
					<div data-msg="grps" id="jackpothistory"  class="DisplayBlockNone JqBlock" data-btn="jackpotrecord">

						<!-- ajax後 css不要帶過來 -->
						<div id="jackpothistory">
							<table class="agent-table min font-xxs" cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th>會員</th>
										<th>時間</th>
										<th>
											<div>期數</div>
											<div>狀態</div>
										</th>
										<th>累計彩金</th>
										<th>派彩金額</th>
										<th>剩餘彩金</th>
										<!-- <th>狀態</th> -->
									</tr>
								</thead>
								<tbody class="JqBankerInfo">
									<?php
									foreach($aJackPotRecordData as $LPnId => $LPaJackPotData)
									{
									?>
									<tr>
										<td><?php echo $aMemberName[$LPaJackPotData['nUid']]['sName0'];?></td>
										<td><span class="scale08"><?php echo $LPaJackPotData['sCreateTime'];?></span></td>
										<td>
											<div class="scale08"><?php echo $LPaJackPotData['sNo'];?></div>
											<div><?php echo '派彩';?></div>
										</td>
										<td><span class="scale08"><?php echo $LPaJackPotData['nBefore'];?></span></td>
										<td><span class="scale08"><?php echo abs($LPaJackPotData['nDelta']);?></span></td>
										<td><span class="scale08"><?php echo $LPaJackPotData['nAfter'];?></span></td>
										<!-- <td class="chkprize betDetailTxt"><?php #echo $LPaBetData['nStatus'];?></td> -->
									</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
