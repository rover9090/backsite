<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nLid" value="<?php echo $nLid;?>">


	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aCANNED['MESSAGETYPE'];?></span>
		<div class="Sel">
			<select name="nKid">
				<?php
				foreach($aKind as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

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
		<span class="InlineBlockTit"><?php echo CHOSELANG;?></span>
		<span class="DisplayInlineBlock VerticalAlignMiddle">
			<?php
			foreach($aLanguage as $LPsLang => $LPaText)
			{
				?>
				<span class="BtnKind JqBtnShowOnly <?php echo $LPaText['sActive'];?>" data-showctrl="<?php echo $LPsLang;?>">
					<?php echo $LPaText['sText'];?>
				</span>
				<?php
			}
			?>
		</span>
	</div>
	<?php
	foreach($aLanguage as $LPsLang => $LPaText)
	{
	?>
		<div class="Block DisplayBlockNone <?php echo $LPaText['sActive'];?>" data-show="<?php echo $LPsLang;?>">
			<span class="InlineBlockTit"><?php echo aCANNED['CONTENT'];?></span>
			<div class="Textarea">
				<textarea name="sContent0[<?php echo $LPsLang;?>]"><?php echo $aData[$LPsLang]['sContent0'];?></textarea>
			</div>
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