<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->

<div class="Information tab MarginBottom20">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/2);">
					<a href="<?php echo $aUrl['sUpt0'].'&nId='.$nId;?>" class="" >
						<div class="InformationName">
							<?php echo aUSER['TAB0'];?>
						</div>
					</a>
				</td>
			<?php
			if($nId != 0)
			{
			?>
				<td class="InformationTitCell active" style="width:calc(100%/2);">
					<a href="<?php echo $aUrl['sUpt1'].'&nId='.$nId;?>" class="" >
						<div class="InformationName">
							<?php echo aUSER['TAB1'];?>
						</div>
					</a>
				</td>
			<?php
			}
			?>
			</tr>
		</tbody>
	</table>
</div>

<form action="<?php echo $aUrl['sAct'];?>" method="POST" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>" />
	<input type="hidden" name="nt" value="<?php echo NOWTIME;?>" />
	<input type="hidden" name="nId" value="<?php echo $nId;?>" />

	<div class="Block MarginBottom20">
		<span class="BlockLineTit"><?php echo aUSER['NAME'];?></span>
		<span><?php echo $aData['sName0'];?></span>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['FS'];?></span>
		<div class="Ipt">
			<input type="text" name="sWater" value="<?php echo $aData['sWater'];?>">
		</div>
		<span class="InlineBlockTit">%</span>
		<?php
			if($aUser['nPa'] != 0)
			{
		?>
		<span class="InlineBlockTit"><?php echo '，'.aUSER['VALUELIMIT'].$aPaFs['sWater'].'%';?></span>
		<?php
			}
		?>

	</div>
	<?php
		if(false)
		{
	?>
			<div class="Block MarginBottom20">
				<span class="InlineBlockTit"><?php echo aUSER['PERCENT'];?></span>
				<div class="Ipt">
					<input type="text" name="sPercent" value="<?php echo $aData['sPercent'];?>">
				</div>
				<span class="InlineBlockTit">%</span>
				<?php
					if($aUser['nPa'] != 0)
					{
				?>
				<span class="InlineBlockTit"><?php echo '，'.aUSER['VALUELIMIT'].$aPaFs['sPercent'].'%';?></span>
				<?php
					}
				?>
			</div>
	<?php
		}
	?>

	<?php
		if($nId != $aAdm['nUid'])
		{
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
	<?php		
		}
		
	?>
</form>