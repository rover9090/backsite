<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<div class="Information tab MarginBottom20">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell  active" style="width:calc(100%/2);">
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
				<td class="InformationTitCell " style="width:calc(100%/2);">
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
	<input type="hidden" name="nId" value="<?php echo $aData['nId'];?>" />

	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo STATUS;?></span>
		<div class="Sel">
			<select name="nStatus">
				<?php
				foreach ($aStatus as $LPnStatus => $LPaStatus)
				{
					?>
					<option value="<?php echo $LPnStatus;?>" <?php echo $LPaStatus['sSelect'];?> >
						<?php echo $LPaStatus['sTitle'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['BLACK1'];?></span>
		<div class="Sel">
			<select name="nBlack">
				<?php
				foreach ($aBlack as $LPnBlack => $LPaBlack)
				{
					?>
					<option value="<?php echo $LPnBlack;?>" <?php echo $LPaBlack['sSelect'];?> >
						<?php echo $LPaBlack['sTitle'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['TEST1'];?></span>
		<div class="Sel">
			<select name="nTest">
				<?php
				foreach ($aTest as $LPnTest => $LPaTest)
				{
					?>
					<option value="<?php echo $LPnTest;?>" <?php echo $LPaTest['sSelect'];?> >
						<?php echo $LPaTest['sTitle'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['MUTE1'];?></span>
		<div class="Sel">
			<select name="nMute">
				<?php
				foreach ($aMute as $LPnMute => $LPaMute)
				{
					?>
					<option value="<?php echo $LPnMute;?>" <?php echo $LPaMute['sSelect'];?> >
						<?php echo $LPaMute['sTitle'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo ACCOUNT;?></span>
		<?php
			if($nId == 0)
			{
		?>
		<div class="Ipt">
			<input type="text" name="sAccount" value="<?php echo $aData['sAccount'];?>" placeholder="<?php echo aUSER['NAMEFORMAT'];?>">
		</div>
		<?php
			}
			else
			{
				echo $aData['sAccount'];
			}
		?>

	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['NAME'];?></span>
		<?php
			if($nId == 0 || true)
			{
		?>
		<div class="Ipt">
			<input type="text" name="sName0" value="<?php echo $aData['sName0'];?>" placeholder="">
		</div>
		<?php
			}
			else
			{
				echo $aData['sName0'];
				echo '<input type="hidden" name="sName0" value="'.$aData['sName0'].'" placeholder="">';
			}
		?>

	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['MONEY'];?></span>
		<div class="Ipt">
			<input type="text" name="nMoney" value="<?php echo $aData['nMoney'];?>" placeholder="">
		</div>

	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit"><?php echo aUSER['IDENTITY'];?></span>
		<span><?php echo $aIdentity[$aData['nIdentity']];?></span>
	</div>


	<?php
	if($aAdm['nAdmType'] == 1 || $aAdm['nAdmType'] == 2)
	{
	?>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo aUSER['HIDEMEMBER'];?></span>
			<label for="nType99" class="IptRadio">
				<input type="radio" id="nType99" name="nType" <?php echo $aData['aType']['99']?> value="99">
				<span><?php echo aUSER['NOHIDE'];?></span>
			</label>
			<label for="nType1" class="IptRadio">
				<input type="radio" id="nType1" name="nType" <?php echo $aData['aType']['1']?> value="1">
				<span><?php echo aUSER['HIDE'];?></span>
			</label>
		</div>
	<?php
	}
	?>

	<?php
	if($nId == 0)
	{
		if($aSystem['aParam']['nUserLevel'] == 1)
		{
	?>
		<div class="Block">
			<span class="InlineBlockTit"><?php echo aUSER['PA'];?></span>
			<div class="Ipt">
				<input type="text" name="sPa" placeholder="<?php echo aUSER['PA'];?>">
			</div>
		</div>
	<?php
		}
	}
	else
	{
	?>
		<div class="Block MarginBottom20">
			<span class="InlineBlockTit"><?php echo CREATETIME;?></span>
			<span class="BlockTxt"><?php echo $aData['sCreateTime'];?></span>
		</div>
		<div class="Block">
			<span class="InlineBlockTit"><?php echo UPDATETIME;?></span>
			<span class="BlockTxt"><?php echo $aData['sUpdateTime'];?></span>
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