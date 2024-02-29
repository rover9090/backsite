<?php $aData = json_decode($sData,true);?>
<!-- 聊天會員 -->
<input type="hidden" name="sAjax" value="<?php echo $aUrl['sAjax'];?>">
<div class="chatUserBox ">
	<div class="chatTopic"><?php echo aCHAT['SELECTCHAT'];?></div>
	<div class="chatUserScroll JqUserBox">
		<?php
		foreach ($aData as $LPnId => $LPaData)
		{
		?>
			<div class="chatUser" data-id="<?php echo $LPnId;?>">
				<div class="BtnAny2 JqDeleteChat" data-type="delete">x</div>
				<div class="BtnAny2 JqCloseChat" data-type="close">-</div>
				<span class="JqChatAccount"><?php echo $LPaData['sAccount'];?></span>
				<span>(<?php echo $LPaData['nAdminStatus'];?>)</span>
			</div>
		<?php
		}
		?>
	</div>
</div>
<!-- 聊天資訊 -->
<div class="chatMsgBox JqChatBox  active">
	<div class="chatMsgContainer JqChatMessage" data-id="0">

		<div class="chatMsgTop">
			<div class="chatMsgUser JqMsgUser"></div>
		</div>
		<div class="chatMsgInner">
			<div class="chatMsgScroll"></div>
		</div>

	</div>
	<div class="chatMsgSend Table">
		<div>
			<div>
				<div class="chatMsgSendMsg JqMessage">
					<!-- input text -->
					<div class="Textarea">
						<textarea name="sText" placeholder="<?php echo aCHAT['MESSAGE'];?>..."></textarea>
					</div>
				</div>
				<div class="BtnAny JqSendMsg"><?php echo aCHAT['SEND'];?></div>
			</div>
		</div>
	</div>
</div>
<!-- 罐頭訊息 -->
<div class="chatCanBox CannedBox">
	<div class="chatTopic"><?php echo aCHAT['CANMESSAGE'];?></div>
	<div class="chatCanScroll">
		<?php
		foreach ($aKind as $LPnLid => $LPaKind)
		{
		?>
			<div class="chatCanBlock">
				<div class="chatCanSort JqBtnToggleClass"><?php echo $LPaKind['sName0'];?></div>
				<div class="chatCanListBox">
					<?php
					foreach ($LPaKind['aList'] as $LPnId => $LPaCanned)
					{
					?>
						<div class="chatCanList JqCannedMsg"><?php echo $LPaCanned['sContent0'];?></div>
					<?php
					}
					?>
				</div>
			</div>
		<?php
		}
		?>
	</div>
</div>