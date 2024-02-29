<?php
	$sMarquee = '';

	$sSQL = '	SELECT 	nId,
					sName0
			FROM 		'.CLIENT_BROADCAST.'
			WHERE 	nOnline = 1
			AND		nKid = 17
			AND		sLang = :sLang
			ORDER BY nSort DESC';
	$Result = $oPdo->prepare($sSQL);
	$Result->bindValue(':sLang', $aSystem['sLang'],PDO::PARAM_STR);
	sql_query($Result);
	while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
	{
		$sMarquee .= $aRows['sName0'].' ';
	}
?>
<header class="BG" style="background-image: url('images/header/headerBg.png?t=<?php echo VTIME;?>');">
	<a class="headerLogoBox" href="./?">
		<img src="images/logo.png?t=<?php echo VTIME;?>" alt="">
	</a>
	<div class="marqueeBox">
		<marquee class="marqeeTxt" behavior="" direction="" scrollamount="10" onmouseover="this.stop()" onmouseout="this.start()">
			<span><?php echo $sMarquee?></span>
		</marquee>
	</div>
	<div class="headerBtnBox">
		<table class="headerBtnTable">
			<tbody>
				<tr>
					<td class="headerBtnTd">
						<div class="headerBtnBlock JqWindowBtn" data-kindctrl="0">
							<div class="headerBtn">
								<img src="images/ICON/music.png?t=<?php echo VTIME;?>" class="normal" alt="">
								<img src="images/ICON/music_act.png?t=<?php echo VTIME;?>" class="hover" alt="">
							</div>
						</div>
					</td>
					<td class="headerBtnTd">
						<div class="headerBtnBlock JqWindowBtn" data-kindctrl="1">
							<div class="headerBtn">
								<img src="images/ICON/rule.png?t=<?php echo VTIME;?>" class="normal" alt="">
								<img src="images/ICON/rule_act.png?t=<?php echo VTIME;?>" class="hover" alt="">
							</div>
						</div>
					</td>
					<?php
					if(false)
					{
					?>
					<td class="headerBtnTd">
						<div class="headerBtnBlock">
							<div class="headerBtn">
								<img src="images/ICON/fullScreen.png?t=<?php echo VTIME;?>" class="normal" alt="">
								<img src="images/ICON/fullScreen_act.png?t=<?php echo VTIME;?>" class="hover" alt="">
							</div>
						</div>
					</td>
					<?php
					}
					?>
					<?php
					if(true)
					{
					?>
					<td class="headerBtnTd">
						<div class="headerBtnBlock JqBtnToggleClass">
							<div class="headerBtn">
								<img src="images/ICON/setting.png?t=<?php echo VTIME;?>" class="normal" alt="">
								<img src="images/ICON/setting_act.png?t=<?php echo VTIME;?>" class="hover" alt="">
							</div>
							<div class="headerBtnMenuBox">
								<div class="headerBtnMenuBlock JqWindowBtn" data-kindctrl="2">
									<table class="headerBtnMenuTable">
										<tbody>
											<tr>
												<td class="headerMenuIconTd">
													<div class="headerMenuIcon">
														<img src="images/ICON/center.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="headerMenuTd JqCenter"><?php echo aCENTER['CENTER'];?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<?php if(false){?>
								<div class="headerBtnMenuBlock JqWindowBtn" data-kindctrl="6">
									<table class="headerBtnMenuTable">
										<tbody>
											<tr>
												<td class="headerMenuIconTd">
													<div class="headerMenuIcon">
														<img src="images/ICON/leaderboard.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="headerMenuTd JqBetRanking"><?php echo aCENTER['RANKING1'];?></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="headerBtnMenuBlock JqWindowBtn" data-kindctrl="7">
									<table class="headerBtnMenuTable">
										<tbody>
											<tr>
												<td class="headerMenuIconTd">
													<div class="headerMenuIcon">
														<img src="images/ICON/love.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="headerMenuTd">關注荷官</td>
											</tr>
										</tbody>
									</table>
								</div>
								<?php }?>
								<div class="headerBtnMenuBlock JqWindowBtn" data-kindctrl="8">
									<table class="headerBtnMenuTable">
										<tbody>
											<tr>
												<td class="headerMenuIconTd">
													<div class="headerMenuIcon">
														<img src="images/ICON/lang.png?t=<?php echo VTIME;?>" alt="">
													</div>
												</td>
												<td class="headerMenuTd"><?php echo aCENTER['LANGSET'];?></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</td>
					<?php
					}
					?>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="headerExitBox BG" style="background-image: url('images/header/exitBg.png?t=<?php echo VTIME;?>');">
		<a class="headerBtn" href="<?php echo $aSetUrl['sLogin'].'&sJWT='.$sLogOutJWT;?>">
			<img src="images/ICON/exit.png?t=<?php echo VTIME;?>" class="normal" alt="">
			<img src="images/ICON/exit_act.png?t=<?php echo VTIME;?>" class="hover" alt="">
		</a>
	</div>
</header>