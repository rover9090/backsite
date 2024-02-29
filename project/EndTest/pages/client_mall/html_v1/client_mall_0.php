<?php
	$aData = json_decode($sData,true);
?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
	<div>
		<?php if(false){?>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aPICTURE['DESCRIPTION'];?></span>
			<div class="Ipt">
				<input type="text" name="sDescription" value="<?php echo $sDescription;?>" placeholder="<?php echo aPICTURE['DESCRIPTION'];?>">
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aPICTURE['KIND'];?></span>
			<div class="Sel">
				<select name="nKid" onchange="this.form.submit()">
					<option value="-1" ><?php echo PLEASESELECT;?></option>
					<!-- <option value="0" ><?php echo ALL;?></option> -->
					<?php
						foreach ($aPictureKind as $LPnLid => $LPaDetail)
						{
					?>
							<option value="<?php echo $LPnLid;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>
		<div class="Block MarginBottom20" >
			<span class="InlineBlockTit"><?php echo aPICTURE['INDEX'];?></span>
			<div class="Sel">
				<select name="nIndex" onchange="this.form.submit()">
					<option value="-1" <?php echo $aIndex['-1']['sSelect'];?>><?php echo aPICTURE_INDEX['-1'];?></option>
					<option value="1" <?php echo $aIndex['1']['sSelect'];?>><?php echo aPICTURE_INDEX['1'];?></option>
				</select>
			</div>
			<span class="FontRed"><?php echo aPICTURE['INDEXDISPLAY'];?></span>
		</div>
		
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<?php }?>
	</div>
</form>
<!-- 新增按鈕 -->
<div class="Block MarginBottom10">
	<a href="<?php echo $aUrl['sUpt'];?>" class="BtnAdd"><?php echo INS.$sHeadTitle;?></a>
</div>

<!-- 純顯示資訊 -->
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo $sHeadTitle; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<tbody>
					<?php 
					$i=0;
					foreach($aData as $LPnId => $LPaDetail)
					{
						if($i % $nTdCount === 0)
						{
							echo '<tr>';
						}
						?>
							<td>
								<div class="MarginBottom10">
									<div>
										<?php if(isset($LPaDetail['sFileType']) && ($LPaDetail['sFileType'] === '.mp4' || $LPaDetail['sFileType'] === '.MP4')){?>
										<video controls height="300" width="300">
											<source src="<?php echo $aImage[$LPnId];?>" type="video/mp4" >
										</video>
										<?php }else{?>
										<img src="<?php echo $aImage[$LPnId];?>" alt="">
										<?php }?>
									</div>
								</div>
								<div class="TableBtnBg MarginBottom10">
									<a href="<?php echo $aUrl['sUpt'].'&nId='.$LPnId;?>" class="FontWhite">							
										<?php echo '編輯';?>
									</a>
								</div>
								<div class="MarginBottom10">
									<?php echo $LPaDetail['sName0'].'/'.$LPaDetail['nPrice0'];?>
								</div>
							</td>
						<?php 
						if($i % $nTdCount === $nTdCount_m1)
						{
							echo '</tr>';
						}
						$i++;
					}
					if(count($aData)%$nTdCount != 0)
					{
						for($nAdd=1;$nAdd<=($nTdCount-(count($aData)%$nTdCount));$nAdd++)
						{
							echo '<td></td>';
						}
						echo '</tr>';
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php echo $aPageList['sHtml'];?>