<?php $aData = json_decode($sData,true);?>
<!-- 查詢期數 -->
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
	<div>
		<div class="Ipt">
			<input type="text" name="sNo" value="<?php echo $sNo;?>" placeholder="<?php echo aPERIOD['NO'];?>">
		</div>
		<?php if(true){?>
		<div class="Sel">
			<select name="nGame">
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
		<?php }?>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
	</div>
</form>
<!-- 純顯示資訊 -->
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo $sHeadTitle; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aPERIOD['NAME0'];?></th>
						<th><?php echo aPERIOD['NO'];?></th>
						<th><?php echo aPERIOD['BANKERNAME'];?></th>
						<th><?php echo aPERIOD['NUMBER'];?></th>
						<th><?php echo OPERATE;?></th>
						<th><?php echo aPERIOD['RESULT'];?></th>
						<th><?php echo '結算圖片';?></th>
						<th><?php echo aPERIOD['aStaus']['sText'];?></th>
						<th><?php echo aPERIOD['OPENTIME'];?>/<?php echo aPERIOD['CLOSETIME'];?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach($aData as $LPnId => $LPaDetail)
					{
						?>
						<tr>
							<td><?php echo $LPaDetail['nGame'];?></td>
							<td><?php echo $LPaDetail['sNo'];?></td>
							<td><?php echo $aNoBanker[$LPaDetail['sNo']]['sName0'].$aNoBanker[$LPaDetail['sNo']]['sMoney0'];?></td>
							<td>
								<div><?php echo $LPaDetail['sNumsText'];?></div>
							</td>
							<td>
							<?php
							if($LPaDetail['nStatus'] >= 4 && $aAdm['nAdmType'] !== '4')
							{
								?>
								<a href="<?php echo $LPaDetail['sRepost'];?>"><?php echo LOTTERY_REJUDGE;?></a>
								<?php
							}
							?>
							</td>
							<td><div><?php echo $LPaDetail['sResultText'];?></div></td>
							<td><div class="JqStupidOut" data-showctrl="0" onclick="$('div[data-showmsg=\'0\']>.jumpMsgContainer').css({'width': 'initial','max-height': 'initial'});$('div[data-showmsg=\'0\']').find('.JqImgSrc').attr('src',$(this).attr('data-src'));" data-src="<?php echo isset($LPaDetail['sImageUrl'])?$LPaDetail['sImageUrl']:'';?>"><?php echo $LPaDetail['sImage'];?></div></td>
							<td class="<?php echo $aStatus[$LPaDetail['nStatus']]['sClass'];?>" ><?php echo $aStatus[$LPaDetail['nStatus']]['sText'];?></td>
							<td>
								<div><?php echo $LPaDetail['sOpenTime'];?></div>
								<div><?php echo $LPaDetail['sCloseTime'];?></div>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>