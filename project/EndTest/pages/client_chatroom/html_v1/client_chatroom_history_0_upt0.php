<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<div class="chatMsgUptBox">
	<div class="chatMsgContainer">
		<div class="chatMsgTop">
			<div class="chatMsgUser"><?php echo $aData['sAccount'];?></div>
		</div>
		<div class="chatMsgInner">
			<div class="chatMsgScroll">
				<?php
				foreach ($aData['aChat'] as $LPnId => $LPaChat)
				{
					?>
					<div class="serviceList Table <?php echo $LPaChat['sWho'];?>">
						<div>
							<div>
								<div class="serviceListInf">
									<div class="serviceListBot">
										<?php
										if($LPaChat['sWho'] == 'UserMsg')
										{
											echo '<div class="serviceListMsgBox">';
											echo 		'<div class="serviceListMsg">'.$LPaChat['sText'].'</div>';
											echo '</div>';
											echo '<div class="serviceListTime">';
											echo 		'<div class="serviceListTimeTxt">'.$LPaChat['sTime'].'</div>';
											echo '</div>';
										}
										else
										{
											echo '<div class="serviceListTime">';
											echo 		'<div class="serviceListTimeTxt">'.$LPaChat['sTime'].'</div>';
											echo '</div>';
											echo '<div class="serviceListMsgBox">';
											echo 		'<div class="serviceListMsg">'.$LPaChat['sText'].'</div>';
											echo '</div>';
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<!-- 操作選項 -->
<div class="EditBtnBox">
	<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
		<i class="fas fa-times"></i>
		<span><?php echo CBACK;?></span>
	</a>
</div>