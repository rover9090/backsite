<?php
	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" data-form="0" enctype="multipart/form-data">
	<input type="hidden" name="sJWT" value="<?php echo $sJWT;?>">
	<input type="hidden" name="nLid" value="<?php echo $nLid;?>">
	<input type="hidden" name="nFileCount" value="<?php echo $nFileCount;?>">

	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aBROADCAST['KIND'];?></span>
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
            <span class="InlineBlockTit"><?php echo aBROADCAST['SYNC'];?></span>
            <label for="nSync" class="IptCheckbox">
                  <input type="checkbox" name="nSync" value="1" <?php echo $aData['sSyncCheck'];?> >
            </label>
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
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aBROADCAST['LINK'];?></span>
		<span class="DisplayInlineBlock VerticalAlignMiddle">
			<div class="Ipt">
				<input type="text" name="sUrl" value="<?php echo $aData['sUrl'];?>">
			</div>
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
				<span class="InlineBlockTit"><?php echo aBROADCAST['NAME'];?></span>
				<div class="Ipt">
					<input type="text" name="sName0[<?php echo $LPsLang;?>]" value="<?php echo $aData[$LPsLang]['sName0'];?>">
				</div>
			</div>
			<!-- 上傳檔案 -->
			<?php
				for($LPnI = 0;$LPnI < $nFileCount; $LPnI++)
				{
			?>
					<div class="Block DisplayBlockNone <?php echo $sActive;?>" data-show="<?php echo $LPsLang;?>">
						<span class="InlineBlockTit"><?php echo aBROADCAST['IMAGE'];?></span>
						<?php
							if(!isset($aImage[$aData[$LPsLang]['nId']]))
							{
								# 尚未有圖
						?>
								<input type="file" name="sFile<?php echo $LPsLang.$LPnI;?>">
								<span class="FontRed"><?php echo aIMGERROR['LIMIT'];?></span>
						<?php
							}
							else
							{
								# 有圖
						?>
								<a class="BtnAny2" href="<?php echo $aImage[$aData[$LPsLang]['nId']][$LPnI]['sDel'];?>">
									<i class="fas fa-times"></i>
								</a>
								<div class="BlockImg MarginTop5">
									<img src="<?php echo IMAGE['URL'].'images/'.$aFile['sDir'].'/'.$aImage[$aData[$LPsLang]['nId']][$LPnI]['sUrl'];?>" alt="">
								</div>

						<?php
							}
						?>
					</div>
			<?php
				}
			?>

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