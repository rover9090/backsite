<?php
	$aData = json_decode($sData,true);
?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="post" enctype="multipart/form-data" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $nId===-1?$sInsJWT:$sUptJWT;?>">
	<input type="hidden" name="sLang" value="TW">

	<!-- Select -->
	<?php if(true){?>
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
	<?php }?>
	<div class="Block MarginBottom20" >
		<span class="InlineBlockTit"><?php echo '標題';?></span>
		<div class="Ipt">
			<input name="sName0" type="text" value="<?php echo $aData['sName0'];?>">
		</div>
	</div>
	<div class="Block MarginBottom20" >
		<span class="InlineBlockTit"><?php echo '價格';?></span>
		<div class="Ipt">
			<input name="nPrice0" type="number" value="<?php echo $aData['nPrice0'];?>">
		</div>
	</div>
	<?php if(!empty($aData['aImage'])){?>
	<div class="Block MarginBottom20" >
		<div class="TableBtnBg MarginBottom10 red JqStupidOut JqReplaceS" data-showctrl="1" data-replace="<?php echo $aData['sDelUrl'];?>">
			<i class="fas fa-times"></i>
			刪除圖片影片
		</div>
	</div>
	<?php }?>
	<?php if(!empty($aData['aImage'][0]['sUrl'])){?>
	<div class="Block MarginBottom20" >
		<video controls>
			<source src="<?php echo $aData['aImage'][0]['sUrl'];?>" type="video/mp4" height="300">
		</video>
	</div>
	<?php }?>
	<?php if(!empty($aData['aImage'][1]['sUrl'])){?>
	<div class="Block MarginBottom20" >
		<img src="<?php echo $aData['aImage'][1]['sUrl'];?>" alt height="300" width="300" title="" style="width:auto">
	</div>
	<?php }?>
	<!-- 上傳檔案 -->
	<div class="Block MarginBottom20">
		<input type="file"  id="JqFile" accept=".mp4, .gif, .jpg" name="aFile[]" multiple>
		<div class="FontRed"><?php echo aIMGERROR['LIMIT'];?></div>
		<div class="FontRed" id="JqFileError"></div>
		<span class="EditBtn JqResetFile red" class="">
			<i class="far fa-save"></i>
			<span><?php echo aPICTURE['RESET'];?></span>
		</span>
	</div>
	<!-- 上傳檔案 -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aPICTURE['IMAGE'];?></span>	
		<div id="JqPreviewDiv">
		</div>
	</div>

	<!-- 操作選項 -->
	<div class="EditBtnBox">
		<div class="EditBtn JqStupidOut" data-showctrl="0">
			<i class="far fa-save"></i>
			<span><?php echo CSUBMIT;?></span>
		</div>
		<a href="<?php echo $aUrl['sPage'];?>" class="EditBtn red">
			<i class="fas fa-times"></i>
			<span><?php echo CBACK;?></span>
		</a>
	</div>
</form>