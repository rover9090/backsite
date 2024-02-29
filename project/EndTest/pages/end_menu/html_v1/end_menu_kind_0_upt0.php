<?php $aData = json_decode($sData,true);?>
<!-- 編輯頁面 -->
<form action="<?php echo $aUrl['sAct'];?>" method="POST" data-form="0">
	<input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>" />
	<input type="hidden" name="nt" value="<?php echo NOWTIME;?>" />
	<input type="hidden" name="nId" value="<?php echo $aData['nId'];?>" />
	<!-- Select -->
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit">狀態</span>
		<div class="Sel">
			<select name="nOnline">
				<?php
				foreach ($aOnline as $LPnOnline => $LPaOnline)
				{
					?>
					<option value="<?php echo $LPnOnline;?>" <?php echo $LPaOnline['sSelect'];?> >
						<?php echo $LPaOnline['sTitle'];?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit">目錄名稱</span>
		<div class="Ipt">
			<input type="text" name="sMenuName0" value="<?php echo $aData['sMenuName0'];?>" disabled >
		</div>
	</div>
	<div class="Block">
		<span class="InlineBlockTit">資料夾名稱</span>
		<div class="Ipt">
			<input type="text" name="sMenuTable0" value="<?php echo $aData['sMenuTable0'];?>" disabled >
		</div>
	</div>
	<div class="Block MarginBottom20">
		<span class="InlineBlockTit">排序</span>
		<div class="Ipt">
			<input type="text" name="nSort" value="<?php echo $aData['nSort'];?>" >
		</div>
		<i class="fas fa-question-circle lowupt_notice"></i>
		<span>數字大，先顯示</span>
	</div>
	<!-- 操作選項 -->
	<div class="EditBtnBox">
		<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
			<i class="fas fa-times"></i>
			<span>取消返回</span>
		</a>
	</div>
</form>