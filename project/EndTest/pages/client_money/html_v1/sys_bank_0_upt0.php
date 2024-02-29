<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nId" value="<?php echo $nId;?>">

	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo STATUS;?></span>
		<div class="Sel">
			<select name="nOnline">
				<?php
					foreach($aOnline as $LPnStatus => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnStatus;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sText'];?></option>
				<?php
					}
				?>
			</select>
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aSYSBANK['NAME'];?></span>
		<div class="Ipt">
			<input type="text" name="sName0" value="<?php echo $aData['sName0'];?>">
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aSYSBANK['CODE'];?></span>
		<div class="Ipt">
			<input type="text" name="sCode" value="<?php echo $aData['sCode'];?>">
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aSYSBANK['NUMBER'];?></span>
		<div class="Ipt">
			<input type="text" name="sBankNumber" value="<?php echo $aData['sBankNumber'];?>">
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aSYSBANK['TYPE0']['sTitle'];?></span>
		<div class="Sel">
			<select name="nType0">
				<?php
					foreach($aBankType as $LPnType => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnType;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sText'];?></option>
				<?php
					}
				?>
			</select>
		</div>
	</div>

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