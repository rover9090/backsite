<?php

	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nLid" value="<?php echo $nLid;?>">

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
		<span class="InlineBlockTit"><?php echo KIND;?></span>
		<div class="Sel">
			<select name="nKid">
				<?php
					foreach($aKind as $LPnLid => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnLid;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sName0'];?></option>
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
				foreach(aLANG as $LPsLang => $LPsText)
				{
					$sActive = '';
					if($aSystem['sLang'] == $LPsLang)
					{
						$sActive = 'active';
					}
			?>
					<span class="BtnKind JqBtnShowOnly <?php echo $sActive;?>" data-showctrl="<?php echo $LPsLang;?>"><?php echo $LPsText;?></span>
			<?php
				}
			?>
		</span>
	</div>
	<?php
		foreach(aLANG as $LPsLang => $LPsText)
		{
			$sActive = '';
			if($aSystem['sLang'] == $LPsLang)
			{
				$sActive = 'active';
			}
	?>
			<div class="Block MarginBottom20 DisplayBlockNone <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
				<span class="InlineBlockTit"><?php echo aGAMESGROUP['GROUP'];?></span>
				<div class="Ipt">
					<input type="text" name="sName0[<?php echo $LPsLang;?>]" value="<?php echo $aName[$LPsLang];?>">
				</div>
			</div>
	<?php
		}
	?>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESGROUP['SORT'];?></span>
		<div class="Ipt">
			<input type="text" name="nSort" value="<?php echo $nSort;?>">
		</div>
	</div>
	<?php 
		foreach($aBetContent as $LPsMethod => $LPsText)
		{
	?>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo $LPsText.' '.aGAMESGROUP['MIN'];?></span>
			<div class="Ipt">
				<input type="text" name="aMoney0[<?php echo $LPsMethod;?>]" value="<?php echo $aData[$aSystem['sLang']][$LPsMethod]['nMoney0'];?>">
			</div>
		</div>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo $LPsText.' '.aGAMESGROUP['MAX'];?></span>
			<div class="Ipt">
				<input type="text" name="aMoney1[<?php echo $LPsMethod;?>]" value="<?php echo $aData[$aSystem['sLang']][$LPsMethod]['nMoney1'];?>">
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