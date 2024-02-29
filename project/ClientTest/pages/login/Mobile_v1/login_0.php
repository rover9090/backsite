<div class="loginBox BG" style="background-image: url('images/login/loginBg_mobile.jpg?t=<?php echo VTIME;?>');">
	<div class="loginContainer">
		<div class="loginLogoBox">
			<img src="images/logo.png?t=<?php echo VTIME;?>" alt="">
		</div>
		<form action="<?php echo $aUrl['sAct'];?>" method="post">
			<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
			<div class="loginTxtBox">
				<div class="loginInputBg">
					<img src="images/login/inputBg.png?t=<?php echo VTIME;?>" alt="">
				</div>
				<div class="loginInputCont">
					<div class="loginIptBox">
						<div class="loginIptBlock">
							<img src="images/login/input.png?t=<?php echo VTIME;?>" alt="">      
							<input type="text" name="sAccount" placeholder="<?php echo aLOGIN['ACCOUNT'];?>">
						</div>
						<div class="loginIptBlock">
							<img src="images/login/input.png?t=<?php echo VTIME;?>" alt="">
							<input type="password" name="sPassword" placeholder="<?php echo aLOGIN['PASSWORD'];?>">
						</div>
					</div>
					<div class="bigBtnBox">
						<div class="bigBtnBlock">
							<img src="images/btnBg.png?t=<?php echo VTIME;?>" alt="">
							<input type="submit" class="bigBtnTxt JqLogin" value="<?php echo aLOGIN['LOGIN'];?>">
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>