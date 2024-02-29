<?php
$aData = json_decode($sData,true);
require_once(dirname(__FILE__).'/mall_header_0.php');
?>
<div class="ContainerActiveInner shopBox">
	<div class="TopicBox">
		<table class="TopicTable">
			<tbody>
				<tr>
					<td class="TopicTdIcon">
						<div class="TopicIcon">
							<img src="images/titleDecro.png" alt="">
						</div>
					</td>
					<td class="TopicTdTit">
						<div class="TopicTit"><?php echo aMALLLIST['GOODS'];?></div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<form action="<?php echo $aUrl['sList'];?>" method="POST">
		<table class="FormTable">
			<tr>
				<td class="FormTdCell1">
					<div class="FormTit"><?php echo aMALLLIST['CLASSIFY'];?></div>
				</td>
				<td class="FormTdCell2">
					<div class="Sel">
						<select name="nKid" onchange="this.form.submit()">
						<?php foreach($aMallKind as $LPnMKid => $LPaMallKind){?>
							<option value="<?php echo $LPnMKid;?>" <?php echo $LPaMallKind['sSelect'];?>><?php echo $LPaMallKind['sName0'];?></option>
						<?php }?>
						</select>
						<div class="SelDecro"></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="FormTdCell1">
					<div class="FormTit"><?php echo aMALLLIST['ORDERBY'];?></div>
				</td>
				<td class="FormTdCell2">
					<div class="Sel">
						<select name="sSort" onchange="this.form.submit()">
						<?php foreach($aSortAttr as $LPsSort => $LPaSortAttr){?>
							<option value="<?php echo $LPsSort;?>" <?php echo $LPaSortAttr['sSelect'];?>><?php echo $LPaSortAttr['sLang'];?></option>
						<?php }?>
						</select>
						<div class="SelDecro"></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="FormTdCell1">
					<div class="FormTit"><?php echo aMALLLIST['GOODSNAME'];?></div>
				</td>
				<td class="FormTdCell2">
					<div class="Ipt">
						<input type="text" name="sName0" value="<?php echo $sName0;?>">
					</div>
				</td>
			</tr>
		</table>
		<div class="BtnActBox">
			<input type="submit" class="BtnAny2" value="<?php echo aMALLLIST['SEARCH'];?>">
		</div>
	</form>
	<div class="shopItemBox">
		<table class="shopItemTable">
			<tbody>
			<?php 
			$i=0;
			foreach($aData as $LPnId => $LPaData){
				if($i % $nTdAmount === 0)
				{
					echo '<tr>';
				}
				?>
				<td class="shopItemTd" style="width:calc(100%/<?php echo $nTdAmount;?>);">
					<?php if($LPaData['nStock'] === '0'){?>
					<div class="" style="">
						<div class="jumpMsgContainer">
							<div class="jumpMsgInner">
								<div class="jumpMsgContent">
									<div class="jumpMsgContentTxt"><?php echo aMALLLIST['SOLD_OUT'];?></div>
								</div>
							</div>
						</div>
						<div class="" style="position: absolute; background: rgba(0,0,0,0.7);width: 100%;height: 100%;z-index: 3;"></div>
					</div>
					<?php }?>
					<a href="<?php echo $aUrl['sUpt'].'&nId='.$LPnId;?>" class="shopItemHref">
						<div class="shopItemImg BG" style="background-image:url('<?php echo $LPaData['sPictureUrl'];?>');"></div>
						<div class="shopItemInf">
							<div class="shopItemInfName"><?php echo $LPaData['sName0'];?></div>
							<div class="shopItemInfAmount">
								<span>$</span>
								<span><?php echo $LPaData['nPrice0'];?></span>
							</div>
						</div>
					</a>
					<div class="shopItemBtnBox">
					<?php if($LPaData['nType0'] === '0'){?>
						<a class="shopItemBtn JqCartRightNow" href="javascript:void(0)" data-mid="<?php echo $LPnId;?>" data-url="<?php echo $aUrl['sUpt1'];?>">
							<div class="shopItemBtnTxt"><?php echo aMALLLIST['BUY'];?></div>
						</a>
					<?php }else{?>
						<a class="shopItemBtn" href="<?php echo $aUrl['sUpt3'].'&nId='.$LPnId;?>">
							<div class="shopItemBtnTxt"><?php echo aMALLLIST['BUY'];?></div>
						</a>
					<?php }?>
					</div>
					<?php if($LPaData['nType0'] === '0'){?>
					<div class="shopItemBtnCart JqBtnCart <?php echo $LPaData['sActive'];?>" data-mid="<?php echo $LPnId;?>">
						<i class="fas fa-shopping-cart"></i>
					</div>
					<?php }?>
				</td>
				<?php
				if($i % $nTdAmount === 1)
				{
					echo '</tr>';
				}
				$i++;
			}
				?>
			</tbody>                  
		</table>            
	</div>
</div>