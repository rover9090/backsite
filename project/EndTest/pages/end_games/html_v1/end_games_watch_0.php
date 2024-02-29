<?php

	$aData = json_decode($sData,true);
?>
<input type="hidden" name="sBetJWT" data-url="<?php echo $aUrl['sAjax0'];?>" value="<?php echo $sJWT;?>">
<input type="hidden" name="sActUrl" data-url="<?php echo $aUrl['sAct'];?>">
<input type="hidden" class="JqnGame" value="<?php echo $nGame;?>">
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="MarginBottom20">
	<div class="Block MarginBottom15 endgameswatch0Search" >
		<div class="Sel">
			<select name="nGame" onchange="$('.JqOpenWindow').attr('onclick','myWindow=window.open(\''+$('.JqOpenWindow').attr('data-url')+'&nGame='+$(this).val()+'\',\'\');')">
				<?php
				foreach ($aGameSetting as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
				}
				?>
			</select>
		</div>
		<div class="Sel">
			<select name="nReloadTime">
			<?php
				foreach ($aReloadSetting as $LPnId => $LPaDetail)
				{
					?>
					<option value="<?php echo $LPnId;?>" <?php echo $LPaDetail['sSelect'];?> ><?php echo $LPaDetail['sName0'];?></option>
					<?php
				}
			?>
			</select>
		</div>
		<input type="submit" class="BtnAny" value="<?php echo SEARCH;?>">
		<input type="button" class="BtnAny JqOpenWindow" data-url="<?php echo $aUrl['sPage'];?>" onclick="myWindow=window.open('<?php echo $aUrl['sPage'].'&nGame='.$nGame;?>','');" class="BtnAny" value="<?php echo aLOTTERY['OPENWINDOW'];?>">
		<div class="BtnAny JqToggleHeader"><?php echo aLOTTERY['HIDECTRL'];?></div>
		<div class="InlineBlock" >
			<span class="InlineBlock"><?php echo aLOTTERY['REFRESHCOUNT'].'('.aLOTTERY['SEC'].')：';?></span>
			<span class="InlineBlock JqCountDown"><?php echo $nReloadTime;?></span>					
		</div>
		<div class="InlineBlock" >
			<span class="InlineBlock VerticalAlignMiddle MarginRight10"><?php echo $aGameSetting[$nGame]['sName0'];?></span>
			<span class="InlineBlock VerticalAlignMiddle MarginRight10"><?php echo aLOTTERY['NO'];?></span>
			<span class="InlineBlock VerticalAlignMiddle"><?php echo $aData['aNums']['sNo'];?></span>					
		</div>
		<div class="LastBox DisplayBlockNone JqFinalRound">
			<div class="LastTxt"><?php echo aLOTTERY['LASTNO'];?></div>
		</div>
		<?php if(false){?>
		<div class="gameTimeBox JqTimeBox">
			<div class="gameTimeBlock">
				<div class="gameTime"></div>
				<div class="gameTimeTxt JqClock_<?php echo $nGame;?>">0</div>		
			</div>			
		</div>
		<?php }?>
	</div>
</form>

<!-- 編輯頁面 -->
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo aLOTTERY['ALLBET']; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aLOTTERY['BETRESULT'];?></th>
						<th><?php echo aLOTTERY['COUNT'];?></th>
						<th><?php echo aLOTTERY['MONEY0'];?></th>
						<!-- <th><?php #echo aLOTTERY['PERMONEY'];?></th> -->
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aBetMoney'] as $LPsType => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo aRESULTTYPE[$LPsType];?></td>
							<td class="JqCount" data-type="<?php echo $LPsType;?>"><?php echo $aData['aBetCount'][$LPsType];?></td>
							<td class="JqMoney" data-type="<?php echo $LPsType;?>"><?php echo $aData['aBetMoney'][$LPsType];?></td>
							<!-- <td class="JqPerMoney" data-type="<?php #echo $LPsType;?>"><?php #echo $aData['aPerMoney'][$LPsType];?></td> -->
						</tr>
					<?php
					}
					?>					
					<tr>
						<td><?php echo aLOTTERY['SUM'];?></td>
						<td class="JqCount" data-type="SUM"><?php echo $aData['nBetCountSum'];?></td>
						<td class="JqMoney" data-type="SUM"><?php echo $aData['nBetMoneySum'];?></td>
						<!-- <td class="JqPerMoney" data-type="SUM"><?php #echo $aData['nPerMoneySum'];?></td> -->
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
if(true)
{
?>
<div class="endgameswatch0Box">
	<div class="gameTotalBet DisplayBlockNone JqDayLastBlock">
		<div class="gameTotalBetTxt">
			<span><?php echo aLOTTERY['TODAYCOUNT'];?></span>
			<span class="JqDayLast">0</span>
		</div>
	</div>	
	<div class="StatusBox JqStopBet DisplayBlockNone">
		<div class="StatusMsg"><?php echo aLOTTERY['CLOSEBET'];?></div>
	</div>
	<div class="StatusBox JqShuffling DisplayBlockNone">
		<div class="StatusMsg"><?php echo aLOTTERY['SHUFFLING'];?></div>
	</div>
	<div class="StatusBox JqCancelRound DisplayBlockNone">
		<div class="StatusMsg"><?php echo aLOTTERY['CANCELBET'];?></div>
	</div>
	<div class="StatusBox JqPauseRound DisplayBlockNone">
		<div class="StatusMsg"><?php echo aLOTTERY['STOPBET'];?></div>
	</div>
</div>
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo aLOTTERY['NOWBET'];?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo aLOTTERY['ACCOUNT'];?></th>
						<th><?php echo aLOTTERY['NAME0'];?></th>
						<th><?php echo aLOTTERY['BETRESULT'];?></th>
						<th><?php echo aLOTTERY['MONEY0'];?></th>
						<!-- <th><?php #echo aLOTTERY['PER'];?></th> -->
						<!-- <th><?php #echo aLOTTERY['PERMONEY'];?></th> -->
						<th><?php echo aLOTTERY['BETTIME'];?></th>
						<th><?php echo OPERATE;?></th>
					</tr>
				</thead>
				<tbody class="JqCurrentBet" data-sNo="0">
					<?php
					foreach ($aData['aBetData'] as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $aData['aUser'][$LPaDetail['nUid']]['sAccountDom'];?></td>
							<td><?php echo $aData['aUser'][$LPaDetail['nUid']]['sNameDom'];?></td>
							<td><?php echo $aBetResult[$LPaDetail['sContent0']];?></td>
							<td><?php echo $LPaDetail['nMoney0'];?></td>
							<!-- <td><?php #echo $LPaDetail['nPer'];?></td> -->
							<!-- <td><?php #echo $LPaDetail['nPerMoney'];?></td> -->
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td>
								<input type="button" class="BtnAny JqWatchMute" onclick="myWindow=window.open('<?php echo $aData['aUser'][$LPaDetail['nUid']]['sMuteUrl'];?>')" value="<?php echo aLOTTERY['BAN'];?>">
								<input type="button" class="BtnAny JqWatchBlack" onclick="myWindow=window.open('<?php echo $aData['aUser'][$LPaDetail['nUid']]['sBlackUrl'];?>')" value="<?php echo aLOTTERY['BLACK'];?>">
							</td>
						</tr>
					<?php
					}
					?>
					<?php
					foreach ($aData['aBankerData'] as $LPnId => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $aData['aUser'][$LPaDetail['nUid']]['sAccountDom'];?></td>
							<td><?php echo $aData['aUser'][$LPaDetail['nUid']]['sNameDom'];?></td>
							<td><?php echo $aBetResult[$LPaDetail['sContent0']];?></td>
							<td><?php echo $LPaDetail['nMoney0'];?></td>
							<!-- <td><?php #echo $LPaDetail['nPer'];?></td> -->
							<!-- <td><?php #echo $LPaDetail['nPerMoney'];?></td> -->
							<td><?php echo $LPaDetail['sCreateTime'];?></td>
							<td>
								<input type="button" class="BtnAny JqWatchMute" onclick="myWindow=window.open('<?php echo $aData['aUser'][$LPaDetail['nUid']]['sMuteUrl'];?>')" value="<?php echo aLOTTERY['BAN'];?>">
								<input type="button" class="BtnAny JqWatchBlack" onclick="myWindow=window.open('<?php echo $aData['aUser'][$LPaDetail['nUid']]['sBlackUrl'];?>')" value="<?php echo aLOTTERY['BLACK'];?>">
							</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
}
?>
<?php
if(false)
{
?>
<div class="Information">
	<table class="InformationTit">
		<tbody>
			<tr>
				<td class="InformationTitCell" style="width:calc(100%/1);">
					<div class="InformationName"><?php echo '會員投注量'; ?></div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="InformationScroll">
		<div class="InformationTableBox">
			<table>
				<thead>
					<tr>
						<th><?php echo '會員名稱';?></th>
						<th><?php echo '投注筆數';?></th>
						<th><?php echo '投注金額';?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($aData['aBetRank'] as $LPsType => $LPaDetail)
					{
					?>
						<tr>
							<td><?php echo $LPaDetail['sName0'];?></td>
							<td><?php echo $LPaDetail['nCount'];?></td>
							<td><?php echo $LPaDetail['nMoney'];?></td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php
}
?>