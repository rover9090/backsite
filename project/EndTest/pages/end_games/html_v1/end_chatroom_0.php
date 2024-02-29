<?php
	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<div class="Information">
	<form action="<?php echo $aUrl['sPage_Mod'];?>" method="POST" class="Form MarginBottom20" data-url="<?php echo $aUrl['sPage_Mod'];?>">
		<div class="Sel">
			<select name="nGame" >
				<?php
				foreach ($aGame_Setting as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> data-url="<?php echo $aUrl['sPage_Mod'].'&nGame='.$LPnId;?>"><?php echo $LPaDetail['sName0'];?></option>
					<?php
				}
				?>
			</select>
		</div>
		<a href="javascript:void(0)" class="BtnAny" onclick="window.location.href=$('select[name=\'nGame\'] option:selected').attr('data-url')"><?php echo SEARCH;?></a>
		<input type="button" class="BtnAny JqOpenWindow" data-url="<?php echo $aUrl['sPage_Mod'];?>" onclick="myWindow=window.open('<?php echo $aUrl['sPage_Mod'].'&nGame='.$nGame;?>','');" class="BtnAny" value="<?php echo '開新視窗';?>">
		<div class="BtnAny JqToggleHeader"><?php echo aCHAT['HIDECTRL'];?></div>
	</form>
</div>
<div class="JqCopyOtherMsg" style="display:none;">
	<div class="chatroomChatBlock Left" data-uid="[[::nUid::]]">
		<div class="chatroomContBlock">
			<div class="chatroomCont">
				<div class="chatroomContTxt FontWhite">[[::sName0::]] : [[::sMsg::]] <span style="font-size: 24px;color:green">[[::sCreateTime::]]</span></div>
			</div>
		</div>
	</div>
</div>
<input class="JqnGame" type="hidden" value="<?php echo $nGame;?>">
<input name="aUserData" type="hidden" data-uid="<?php echo $aAdm['nId'];?>" data-name0="<?php echo $aAdm['sAccount'];?>" data-password="<?php echo $aAdm['sPassword'];?>">
<input name="aSocketUser" type="hidden" data-uid="<?php echo $aNotUseUser['nId'];?>" data-account="<?php echo $aNotUseUser['sAccount'];?>">
<div class="chatRoomBox2 JqMainBox">
	<div class="chatRoomInner2 JqMsgBox JqShowArea">
		<?php foreach($aMsgData as $LPnId => $LPaMsgData){?>
			<div class="chatroomChatBlock Left" data-uid="<?php echo $LPaMsgData['nUid'];?>">
				<div class="chatroomContBlock">
					<div class="chatroomCont">
						<div class="chatroomContTxt FontWhite"><?php echo $LPaMsgData['sName0'];?> : <?php echo $LPaMsgData['sMsg'];?> <span style="font-size: 24px;color:green"><?php echo $LPaMsgData['sCreateTime'];?></span></div>
					</div>
				</div>
			</div>
		<?php }?>
		<?php if(false){?>
		<div class="chatroomChatBlock Left" data-uid="[[::nUid::]]">
			<div class="chatroomContBlock">
				<div class="chatroomCont">
					<div class="chatroomContTxt">[[::sName0::]] : [[::sMsg::]]</div>
				</div>
			</div>
		</div>
		<?php }?>
	</div>
</div>