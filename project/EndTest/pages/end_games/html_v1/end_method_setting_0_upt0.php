<?php

	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nId" value="<?php echo $nId;?>">

	<!-- 遊戲名稱 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['NAME0'];?></span>
		<span class="InlineBlockTxt FontRed"><?php echo $aData['sName0'];?></span>
	</div>

	<!-- 遊戲代號 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['CODE'];?></span>
		<span class="InlineBlockTxt FontGreen"><?php echo $aData['nGame'];?></span>
	</div>

	<!-- Select -->
	<!-- Online -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo STATUS;?></span>
		<div class="Sel">
			<select name="nOnline">
				<?php
					foreach($aOnline as $LPnOnline => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnOnline;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sText'];?></option>
				<?php
					}
				?>
			</select>
		</div>
	</div>
	<!-- 賠率 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['ODDS'];?></span>
		<div class="Ipt">
			<input type="text" name="nOdds" value="<?php echo $aData['nOdds'];?>">
		</div>
	</div>
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['ODDS1'];?></span>
		<div class="Ipt">
			<input type="text" name="nOdds1" value="<?php echo $aData['nOdds1'];?>">
		</div>
	</div> -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['MAXBET'];?></span>
		<div class="Ipt"> -->
			<input type="text" name="nMaxBet" value="<?php echo $aData['nMaxBet'];?>">
		<!-- </div>
	</div> -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aMETHOD['MINBET'];?></span>
		<div class="Ipt"> -->
			<input type="text" name="nMinBet" value="<?php echo $aData['nMinBet'];?>">
		<!-- </div>
	</div> -->
	<!-- 操作選項 -->
	<div class="EditBtnBox">
		<div class="EditBtn JqStupidOut" data-showctrl="0">
			<i class="far fa-save"></i>
			<span><?php echo CSUBMIT;?></span>
		</div>
		<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
			<i class="fas fa-times"></i>
			<span><?php echo CBACK;?></span>
		</a>
	</div>
</form>