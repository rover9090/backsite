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
		<span class="InlineBlockTit">主目錄</span>
		<div class="Ipt">
			<input type="text" value="<?php echo $aMenuKind[$aData['nMid']]['sMenuName0'];?>" disabled>
		</div>
	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit">目錄名稱</span>
		<div class="Ipt">
			<input type="text" value="<?php echo $aData['sListName0'];?>" disabled>
		</div>
	</div>

	<div class="Block MarginBottom20">
		<span class="InlineBlockTit">檔案名稱</span>
		<div class="Ipt">
			<input type="text" value="<?php echo $aData['sListTable0'];?>" disabled>
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
	<div class="Block">
		<span class="InlineBlockTit">附加功能</span>
		<span>[ <?php echo $aType0[$aData['nType0']]['sTitle'];?> ]</span>
	</div>

	<!-- 操作選項 -->
	<div class="EditBtnBox">
		<div class="EditBtn JqStupidOut" data-showctrl="0">
			<i class="far fa-save"></i>
			<span>確認送出</span>
		</div>
		<a href="<?php echo $aUrl['sBack'];?>" class="EditBtn red">
			<i class="fas fa-times"></i>
			<span>取消返回</span>
		</a>
	</div>
</form>