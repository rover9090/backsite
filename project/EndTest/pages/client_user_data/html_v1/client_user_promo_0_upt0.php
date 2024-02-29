<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="POST" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>" />
	<input type="hidden" name="nt" value="<?php echo NOWTIME;?>" />
	<input type="hidden" name="nId" value="<?php echo $aData['nId'];?>" />
	<input type="hidden" name="nType" value="1">

	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo STATUS;?></span>
		<div class="Sel">
			<select name="nOnline">
				<?php
				foreach ($aOnline as $LPnOnline => $LPaOnline)
				{
					?>
					<option value="<?php echo $LPnOnline;?>" <?php echo $LPaOnline['sSelect'];?> >
						<?php echo $LPaOnline['sText'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<?php
		if($nId == 0)
		{
	?>
			<div class="Block MarginBottom20">
				<span class="InlineBlockTit"><?php echo aPROMO['PROMOCODE'];?></span>
				<span class="DisplayInlineBlock VerticalAlignMiddle">
					<span class="BtnKind JqBtnShowOnly JqPromoBtn active" data-value="1"><?php echo aPROMO['RANDOM'];?></span>
				</span>
				<span class="DisplayInlineBlock VerticalAlignMiddle">
					<span class="BtnKind JqBtnShowOnly JqPromoBtn" data-value="0"><?php echo aPROMO['MANUAL'];?></span>
				</span>
			</div>
			<div class="Block MarginBottom20 JqPromoCode DisplayBlockNone">
				<span class="InlineBlockTit"><?php echo aPROMO['PROMOCODE'];?></span>

				<div class="Ipt">
					<input type="text" name="sPromoCode" placeholder="<?php echo aPROMO['PROMOCODEFORMAT'];?>">
				</div>

			</div>
	<?php
		}
		else
		{
	?>
			<div class="Block MarginBottom20 JqPromoCode">
				<span class="InlineBlockTit"><?php echo aPROMO['PROMOCODE'];?></span>
				<span><?php echo $aData['sPromoCode'];?></span>
			</div>
	<?php
		}
	?>



	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aPROMO['FS'];?></span>
		<div class="Ipt">
			<input type="text" name="sWater" value="<?php echo $aData['aFsData']['sWater'];?>">
		</div>
		<span class="InlineBlockTit">%</span>
		<span class="InlineBlockTit"><?php echo '，'.aPROMO['VALUELIMIT'].$aData['aSelfFs']['sWater'].'%';?></span>
	</div>
	<?php
		if(false)
		{
	?>
			<div class="Block MarginBottom20">
				<span class="InlineBlockTit"><?php echo aPROMO['PERCENT'];?></span>
				<div class="Ipt">
					<input type="text" name="sPercent" value="<?php echo $aData['aFsData']['sPercent'];?>">
				</div>
				<span class="InlineBlockTit">%</span>
				<span class="InlineBlockTit"><?php echo '，'.aPROMO['VALUELIMIT'].$aData['aSelfFs']['sPercent'].'%';?></span>
			</div>
	<?php
		}
	?>

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