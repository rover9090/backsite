<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sAct'];?>" method="POST" class="MarginBottom20" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sActJWT;?>">
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
						echo '<tr>';
						echo '<td></td>';
						foreach ($aData as $LPsName0 => $LPaData)
						{
							echo '<td>'.$LPsName0.'</td>';
						}
						echo '</tr>';
						foreach ($aData as $LPsName0 => $LPaData)
						{
							echo '<tr>';
							echo '<td>'.$LPsName0.'</td>';
							foreach($aData as $LPsName1 => $true)
							{
								if($LPsName0 === $LPsName1 || empty($aData[$LPsName0][$LPsName1]))
								{
									echo '<td></td>';
								}
								else
								{
									echo '<td>
										<div class="Ipt">
											<input type="number" name="aRate['.$LPsName0.']['.$LPsName1.']" value="'.$aData[$LPsName0][$LPsName1].'">
										</div>
										</td>';
								}								
							}
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
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
<?php echo $aPageList['sHtml'];?>