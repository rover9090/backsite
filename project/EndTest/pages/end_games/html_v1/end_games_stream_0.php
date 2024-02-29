<input name="sLobbyJWT" type="hidden" value="<?php echo $sLobbyJWT; ?>" data-url1="<?php echo $aUrl['sLobbyAjax1'];?>">
<input name="aRoomData" type="hidden" data-name="<?php echo $aRoomData['sName'];?>" data-password="<?php echo $aRoomData['sPassword'];?>" data-host="<?php echo $aRoomData['sHost'];?>">
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<div>
	<input class="JqaNumsArray" type="hidden" value='<?php echo json_encode($aNumsArray);?>'>
	<input class="JqaNumsCounting" type="hidden" value='<?php echo json_encode($aNumsCounting);?>'>
</div>
<div class="Information">
	<form action="<?php echo $aUrl['sPage_Mod'];?>" method="POST" class="Form MarginBottom20">
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
				<input type="button" class="BtnAny JqOpenWindow" data-url="<?php echo $aUrl['sPage_Mod'];?>" onclick="myWindow=window.open('<?php echo $aUrl['sPage_Mod'].'&nGame='.$nGame;?>','');" class="BtnAny" value="<?php echo '開新視窗';?>">
				<?php if(false){?><a href="javascript:void(0)" class="BtnAny"  onclick="window.open('https:\/\/meet.jit.si\/<?php echo $aRoomData['sName'];?>#userInfo.displayName=%22HOST%22&amp;');">開啟視訊</a>
					<?php }?>
				<div class="BtnAny JqToggleHeader"><?php echo aGAMECTRL['HIDECTRL'];?></div>
				<div class="endgamectrlInfoBox">
					<span><?php echo aGAMECTRL['NOWNO'];?><span class="JqNo"></span>,</span>
					<span><?php echo aGAMECTRL['ROUND'];?><span class="JqRound"></span>,</span>
					<!-- <span>房間人數<span class="JqMembersCount"></span>,</span> -->
					<span class="JqNOWDATE"></span>
						<?php if(true){?>
					<span><label><input class='JqToggleRead' type="checkbox"><?php echo aGAMECTRL['OLDVER'];?></label></span>
					<?php }?>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="endgamesstreambox">
	<div class="JqFinalBox"></div>
	<!-- 視頻區，荷官休息時換為下方圖片(兩個不同時存在) -->			
	<div class="gameTimeBox JqTimeBox">
		<div class="gameTimeBlock">
			<div class="gameTime"></div>
			<div class="gameTimeTxt JqClock_<?php echo $nGame;?>">0</div>		
		</div>
		<div class="gameTotalBet DisplayBlockNone JqDayLastBlock">
			<div class="gameTotalBetTxt">
				<span><?php echo aGAMECTRL['TODAYCOUNT'];?></span>
				<span class="JqDayLast">0</span>
			</div>
		</div>
	</div>
	<div class="LastBox BG DisplayBlockNone JqFinalRound">
		<div class="LastTxt"><?php echo aGAMECTRL['LASTNO'];?></div>
	</div>
	<div class="StatusBox JqStopBet DisplayInlineBlockNone BG" style="background-image:url('images/textBg.png?t=<?php echo VTIME; ?>');">
		<div class="StatusMsg"><?php echo aGAMECTRL['CLOSEBET'];?></div>
	</div>
	<div class="StatusBox BG JqShuffling DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
		<div class="StatusMsg"><?php echo aGAMECTRL['SHUFFLING'];?></div>
	</div>
	<div class="StatusBox BG JqCancelRound DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
		<div class="StatusMsg"><?php echo aGAMECTRL['CANCELBET'];?></div>
	</div>
	<div class="StatusBox BG JqPauseRound DisplayInlineBlockNone" style="background-image: url('images/textBg.png?t=<?php echo VTIME;?>');">
		<div class="StatusMsg"><?php echo aGAMECTRL['STOPBET'];?></div>
	</div>
	<div id="video-player" class="gameVideoBox"></div>
</div>