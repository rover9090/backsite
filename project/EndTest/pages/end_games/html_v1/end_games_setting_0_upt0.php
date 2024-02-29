<?php

	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nLid" value="<?php echo $nLid;?>">

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
	<!-- 遊戲代號 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['CODE'];?></span>
		<?php
			if($nLid == 0)
			{
		?>
				<div class="Ipt">
					<input type="text" name="nGame" value="<?php echo $aData[$aSystem['sLang']]['nGame'];?>">
				</div>
		<?php
			}
			else
			{
				echo '<span>'.$aData[$aSystem['sLang']]['nGame'].'</span>';
			}
		?>
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
			<!-- 遊戲名稱 -->
			<div class="Block MarginBottom20">
				<div class="Block DisplayBlockNone <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
					<span class="InlineBlockTit"><?php echo aGAMESETTING['NAME'];?></span>
					<div class="Ipt">
						<input type="text" name="sName0[<?php echo $LPsLang;?>]" value="<?php echo $aData[$LPsLang]['sName0'];?>">
					</div>
				</div>
			</div>
	<?php
		}
	?>
	
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo '維護狀態';?></span>
		<div class="Sel">
			<select name="nMaintenance">
				<?php
					foreach($aMance as $LPnMaintenance => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnMaintenance;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sText'];?></option>
				<?php
					}
				?>
			</select>
		</div>
	</div>
	
	<!-- 限額分類 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php #echo '限額分類';?></span>
		<div class="Sel">
			<select name="nGroupId">
				<?php
					// foreach($aGroupKind as $LPnGroupId => $LPaDetail)
					// {
				?>
						<option value="<?php # echo $LPnGroupId;?>" <?php #echo $LPaDetail['sSelect'];?>><?php #echo $LPaDetail['sName0'];?></option>
				<?php
					// }
				?>
			</select>
		</div>
	</div> -->
	<!-- 遊戲球數 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['COUNT'];?></span>
		<div class="Sel">
			<select name="nCount">
				<?php
					foreach($aCountBall as $LPnMode => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnMode;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sText'];?></option>
				<?php
					}
				?>
			</select>
		</div>
	</div> -->

	<!-- 每期秒數 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['SEC'];?></span>
		<div class="Ipt">
			<input type="text" name="nSec" value="<?php echo $aData[$aSystem['sLang']]['nSec'];?>">
		</div>
	</div> -->

	<!-- 過場秒數 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['SEC1'];?></span>
		<div class="Ipt">
			<input type="text" name="nSec1" value="<?php echo $aData[$aSystem['sLang']]['nSec1'];?>">
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['SEC2'];?></span>
		<div class="Ipt">
			<input type="text" name="nSec2" value="<?php echo $aData[$aSystem['sLang']]['nSec2'];?>">
		</div>
	</div> -->
	<!-- 開盤時間 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['OPENTIME'];?></span>
		<div class="Ipt">
			<input type="text" name="sOpenTime" class="JqStartTime" value="<?php echo $aData[$aSystem['sLang']]['sOpenTime'];?>">
		</div>
	</div>

	<!-- 關盤時間 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['CLOSETIME'];?></span>
		<div class="Ipt">
			<input type="text" name="sCloseTime" class="JqStartTime" value="<?php echo $aData[$aSystem['sLang']]['sCloseTime'];?>">
		</div>
	</div>

	<!-- 夜晚影片切換時間 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aGAMESETTING['NIGHTTIME'];?></span>
		<div class="Ipt">
			<input type="text" name="sNightTime" class="JqStartTime" value="<?php echo $aData[$aSystem['sLang']]['sNightTime'];?>">
		</div>
	</div> -->

	<!-- 吃大賠小機率 -->
	<!-- <div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo '吃大賠小機率';?></span>
		<div class="Ipt">
			<input type="number" name="nWinPercent" value="<?php echo $aData[$aSystem['sLang']]['nWinPercent'];?>">
		</div>
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