<nav>
	<div class="navContainer">
		<div class="navShowBox">
			<div class="navProfileImg">
				<img src="images/player.png?t=<?php echo VTIME;?>" alt="">
			</div>
			<div class="navInfoBox">
				<div class="navInfoBlock">
					<div class="navInfoAccount"><?php echo $aUser['sName0'];?></div>
				</div>
				<div class="navInfoBlock">
					<div class="navInfoMoney">
						<span>$<?php echo aSITECOIN[$aUser['sSiteId']];?></span>
						<span class="JqUserMoney"><?php echo $aUser['nMoney'];?></span>
					</div>
				</div>
			</div>
			<!-- 點擊出現紀錄彈窗 -->
			<div class="bigBtnBox JqWindowBtn" data-kindctrl="9">
				<div class="bigBtnBlock BG JqCheckRecord" style="background-image: url('images//btnBg.png?t=<?php echo VTIME;?>');">
					<div class="bigBtnTxt"><?php echo aCENTER['CHECKRECORD'];?></div>
				</div>
			</div>
		</div>
		<!-- 點選該頁面 class="navMenuBlock" + active -->
		<div class="navMenuBox">
			<!-- <div class="navMenuBlock active">
				<div class="navMenuTxt">全部遊戲</div>
			</div> -->
			<!-- <div class="navMenuBlock">
				<div class="navMenuTxt">百家樂</div>
			</div> -->
			<?php foreach($aLobbyName as $LPnLobby => $LPaData){?>
			<div class="navMenuBlock <?php echo $LPaData['sActive'];?>">
				<a class="navMenuTxt" href="<?php echo $LPaData['sUrl'];?>"><?php echo $LPaData['sName0'];?></a>
			</div>
			<?php }?>
		</div>
		<!-- 在線人數顯示 -->
		<div class="navPersonBox BG" style="background-image: url('images//textBg.png?t=<?php echo VTIME;?>');">
				<span><i class="fas fa-user-alt"></i></span>
				<span class="navPersonTxt"><?php echo $aUser['sName0'];?></span>
			</div>
		</div>
	</div>
</nav>