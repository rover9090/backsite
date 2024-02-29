<?php
	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0" enctype="multipart/form-data">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nLid" value="<?php echo $nLid;?>">

	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aANNOUNCE['KIND'];?></span>
		<div class="Sel">
			<select name="nKid">
				<?php
					foreach($aData['aAnnounceKind'] as $LPnId => $LPaDetail)
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
		<span class="InlineBlockTit"><?php echo '館別';?></span>
		<div class="Sel">
			<select name="nGame">
				<?php
					foreach($aRoom as $LPnRoom => $LPaDetail)
					{
				?>
						<option value="<?php echo $LPnRoom;?>" <?php echo $LPaDetail['sSelect'];?>><?php echo $LPaDetail['sName0'];?></option>
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
			<div class="Block DisplayBlockNone MarginBottom20 <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
				<span class="InlineBlockTit"><?php echo aANNOUNCE['NAME'];?></span>
				<div class="Ipt">
					<input type="text" name="sName0[<?php echo $LPsLang;?>]" value="<?php echo $aData[$LPsLang]['sName0'];?>">
				</div>
			</div>
			<div class="Block DisplayBlockNone MarginBottom20 <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
				<div class="InlineBlockTit"><?php echo aANNOUNCE['CONTENT'];?></div>
				<div class="Textarea">
					<textarea name="sContent0[<?php echo $LPsLang;?>]" class="ckeditor MarginTop5"><?php echo $aData[$LPsLang]['sContent0'];?></textarea>
				</div>
			</div>
			<?php if(false){?>
			<div class="Block DisplayBlockNone MarginBottom20 <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
				<span class="InlineBlockTit"><?php echo PICTURE;?></span>
				<?php
					if(!isset($aImage[$LPsLang]))
					{
						# 尚未有圖
				?>
						<input type="file" name="sFile_<?php echo $LPsLang;?>">
						<span class="FontRed"><?php echo aIMGERROR['LIMIT'];?></span>
				<?php
					}
					else
					{
						# 有
					?>
						<a class="BtnAny2" href="<?php echo $aImage[$LPsLang]['sDel'];?>">
							<i class="fas fa-times"></i>
						</a>
						<div class="BlockImg MarginTop5">
							<img src="<?php echo $aImage[$LPsLang]['sUrl'];?>" alt="">
						</div>
					<?php
					}
				?>
			</div>
			<?php }?>
	<?php
		}
	?>

	<div class="Block MarginBottom20" >
		<span class="InlineBlockTit"><?php echo aANNOUNCE['PUSHTIME'];?></span>
		<div class="Ipt">
			<input type="text" name="sPushTime" class="JqStartTime" value="<?php echo $sPushTime;?>" autocomplete="off">
		</div>
	</div>

	<div class="Block MarginBottom20" >
		<span class="InlineBlockTit"><?php echo ENDTIME;?></span>
		<div class="Ipt">
			<input type="text" name="sEndTime" class="JqEndTime" value="<?php echo $aData[$LPsLang]['sEndTime'];?>" autocomplete="off">
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