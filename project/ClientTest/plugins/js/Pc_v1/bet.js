var nCoin = 0;// 在games.js控制
var aBetData = {};
var aRepeatData = {};
var aBetLangs = JSON.parse($('.JqaBetLangs').val());
var aPlayerData = JSON.parse($('input[name="aPlayerData"]').val());
$(document).ready(function()
{	
	$('.JqCommission').click(function(){
		nValue = $(this).hasClass('active')?1:0;
		$('.JqIptCommission').val(nValue);
		if(nValue == 1){
			$('.JqBWOdds').hide();
			$('.JqBWOdds1').show();
		}
		else{
			$('.JqBWOdds').show();
			$('.JqBWOdds1').hide();
		}
	});

	$('.JqBankerZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['BW'] != undefined){
			nBetMoney = aBetData['BW'];
		}
		aBetData['BW'] = nBetMoney + nCoin;
		console.log(aBetData);
		$('.JqBankerWinBet').text(aBetData['BW']);
		fnCalcSum();
	});

	$('.JqPlayerZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['PW'] != undefined){
			nBetMoney = aBetData['PW'];
		}
		aBetData['PW'] = nBetMoney + nCoin;
		$('.JqPlayerWinBet').text(aBetData['PW']);
		fnCalcSum();
	});

	$('.JqDrawZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['DRAW'] != undefined){
			nBetMoney = aBetData['DRAW'];
		}
		aBetData['DRAW'] = nBetMoney + nCoin;
		$('.JqDrawBet').text(aBetData['DRAW']);
		fnCalcSum();
	});

	$('.JqBankerPairZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['BP'] != undefined){
			nBetMoney = aBetData['BP'];
		}
		aBetData['BP'] = nBetMoney + nCoin;
		$('.JqBankerPairBet').text(aBetData['BP']);
		fnCalcSum();
	});

	$('.JqPlayerPairZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['PP'] != undefined){
			nBetMoney = aBetData['PP'];
		}
		aBetData['PP'] = nBetMoney + nCoin;
		$('.JqPlayerPairBet').text(aBetData['PP']);
		fnCalcSum();
	});

	$('.JqSixZone').click(function() {
		if($('.JqStopBet').hasClass('active')){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['SIX'] != undefined){
			nBetMoney = aBetData['SIX'];
		}
		aBetData['SIX'] = nBetMoney + nCoin;
		$('.JqSixBet').text(aBetData['SIX']);
		fnCalcSum();
	});

	$('.JqCancelBet').click(function() {
		$('.JqSixBet').text(0);
		$('.JqPlayerPairBet').text(0);
		$('.JqBankerPairBet').text(0);
		$('.JqDrawBet').text(0);
		$('.JqPlayerWinBet').text(0);
		$('.JqBankerWinBet').text(0);
		aBetData = {};
		fnCalcSum();
	});

	$('.JqRepeatBet').click(function() {
		if(Object.keys(aRepeatData).length == 0){
			return;
		}
		aBetData = aRepeatData;
		console.log(aRepeatData);
		$('.JqPlayerPairBet').text('0');
		$('.JqBankerPairBet').text('0');
		$('.JqDrawBet').text('0');
		$('.JqBankerWinBet').text('0');
		$('.JqPlayerWinBet').text('0');
		$('.JqSixBet').text('0');
		fnCalcSum();
		for(let LPsKey in aRepeatData){
			switch (LPsKey) {
				case 'PP':					
					$('.JqPlayerPairBet').text(aRepeatData[LPsKey]);
					break;
				case 'BP':				
					$('.JqBankerPairBet').text(aRepeatData[LPsKey]);
					break;
				case 'DRAW':				
					$('.JqDrawBet').text(aRepeatData[LPsKey]);
					break;
				case 'BW':				
					$('.JqBankerWinBet').text(aRepeatData[LPsKey]);
					break;
				case 'PW':				
					$('.JqPlayerWinBet').text(aRepeatData[LPsKey]);
					break;
				case 'SIX':
					$('.JqSixBet').text(aRepeatData[LPsKey]);
					break;			
				default:
					break;
			}
		}
	});

	$('.JqConfirmBet').click(function() {
		let nBetSum = 0;
		$('.JqBetCheckBoxContent').html('');
		console.log(aBetData);
		for(let LPsKey in aBetData){
			$('.JqBetCheckBoxContent').append('<tr><td class="infoTd">'+aBetLangs[LPsKey]+'</td><td class="infoTd">'+aBetData[LPsKey]+'</td></tr>');
			nBetSum += (aBetData[LPsKey]);
		}
		$('.JqBetCheckBoxSum').text(nBetSum);
		// $('.JqSubmitBet').click();
	});

	// $('.JqBetBtn').on('click',function()
	// {
	// 	$('.JqBetBox').toggleClass('active');
	// });

	$('.JqSubmitBet').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		aRepeatData = aBetData;

		let sUrl = $('input[name=sBetJWT]').attr('data-url');
		let sJWT = $('input[name=sBetJWT]').val();
		let nCommission = $('input.JqIptCommission').val();
		let nBetGroup = $('.JqBetGroup.active').attr('data-nBetGroupId');
		let aCookie = fnGetCookieValue();
		if(aCookie[$('.JqnGame').val()+'nBetGroupId'] != undefined)
		{
			nBetGroup = aCookie[$('.JqnGame').val()+'nBetGroupId'];
		}
		if(nBetGroup == undefined){
			$('.JqBetGroup').removeClass('active');
			$($('.JqBetGroup')[0]).addClass('active');
			nBetGroup = $('.JqBetGroup.active').attr('data-nBetGroupId');
		}

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'aBetData': aBetData,
				'nGame': $('.JqnGame').val(),
				'nCommission':nCommission,
				'nBetGroup':nBetGroup,
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);
				if(oRes['nError'] == 0){
					aBetData = {};
					$('.JqBetCheckBoxSum').text(0);
				}
				for(let LPsKey in oRes['aData']['aBetData']){
					let $Chip = $($('.JqSampleChip').html());
					$Chip[2].innerText = oRes['aData']['aBetData'][LPsKey]['nMoney0'];
					let $AddChip = $( '.JqPlayerBetChip.JqChip'+oRes['aData']['aBetData'][LPsKey]['sContent0']+':not(.JqAdded)');
					$AddChip = $($AddChip[fnGetRandom(0,$AddChip.length-1)]);
					$AddChip.addClass('JqAdded');
					$AddChip.append($Chip);					
				}

				if(oRes['nError'] == 0){
					for(let LPsKey in oRes['aData']['aBetData']){
						gaBetPool[LPsKey] = oRes['aData']['aBetData'][LPsKey];
					}
					// $('.JqPlayerPairMoney').text(oRes['aData']['aBetMoney']['PP']);
					// $('.JqSuperSixMoney').text(oRes['aData']['aBetMoney']['SIX']);
					// $('.JqBankerPairMoney').text(oRes['aData']['aBetMoney']['BP']);
					// $('.JqPlayerWinMoney').text(oRes['aData']['aBetMoney']['PW']);
					// $('.JqDrawMoney').text(oRes['aData']['aBetMoney']['DRAW']);
					// $('.JqBankerWinMoney').text(oRes['aData']['aBetMoney']['BW']);
					// $('.JqPlayerPairCount').text(oRes['aData']['aBetCount']['PP']);
					// $('.JqSuperSixCount').text(oRes['aData']['aBetCount']['SIX']);
					// $('.JqBankerPairCount').text(oRes['aData']['aBetCount']['BP']);
					// $('.JqPlayerWinCount').text(oRes['aData']['aBetCount']['PW']);
					// $('.JqDrawCount').text(oRes['aData']['aBetCount']['DRAW']);
					// $('.JqBankerWinCount').text(oRes['aData']['aBetCount']['BW']);

					$('.JqPlayerPairBet').text('0');
					$('.JqBankerPairBet').text('0');
					$('.JqDrawBet').text('0');
					$('.JqBankerWinBet').text('0');
					$('.JqPlayerWinBet').text('0');
					$('.JqSixBet').text('0');
					gaUser['nIdleRound'] = 0;
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqWatchDealer').on('click',function(){
		
		let sUrl = $('input[name=sWatchDealerJWT]').attr('data-url');
		let sJWT = $('input[name=sWatchDealerJWT]').val();
		let nDealer = $('input[name=sWatchDealerJWT]').attr('data-dealer');

		let nStatus = 0;
		if($(this).hasClass('active'))
		{
			nStatus = 1;
		}

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'nDealer': nDealer,
				'nStatus': nStatus,
				'run_page': 1,
			},
			success: function (oRes) {
				console.log(oRes);
				console.log('成功');
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	fnSettingSeat(1,aPlayerData);
});

function fnCalcSum(){
	let nBetSum = 0;
	// $('.JqBetCheckBoxContent').html('');
	// console.log(aBetData);
	for(let LPsKey in aBetData){
		// $('.JqBetCheckBoxContent').append('<tr><td class="infoTd">'+aBetLangs[LPsKey]+'</td><td class="infoTd">'+aBetData[LPsKey]+'</td></tr>');
		nBetSum += (aBetData[LPsKey]);
	}
	$('.JqBetCheckBoxSum').text(nBetSum);
}

// function fnSettingSeat(nInit=0,aPlayerData){
// 	let nLen = $('.JqSeat').length;
// 	let LPaPlayerData,$LPThisSeat,aNowSeat = {};
// 	for(let i=0;i<nLen;i++){
// 		if($($('.JqSeat')[i]).find('.JqSeatName').text() != ''){
// 			aNowSeat[$($('.JqSeat')[i]).find('.JqSeatName').text()] = {};
// 		}
// 	}
// 	if(nInit === 1){
// 		// aUser在Socket那隻有讀取
// 		let $JqUserSeat = $($('.JqSeat')[fnGetRandom(0,nLen-1)]);
// 		$JqUserSeat.addClass('JqAdded active');
// 		$JqUserSeat.addClass('JqUser');
// 		$JqUserSeat.parent().addClass('here');
// 		$JqUserSeat.find('.JqSeatName').text(aUser['sName0']);
// 		$JqUserSeat.find('.JqSeatMoney').text(aUser['nMoney']);
// 		aNowSeat[aUser['sName0']] = {};
// 		for(let i=0;i<nLen;i++){ // 座位循環
// 			$JqEmptySeat = $('.JqSeat:not(.JqAdded)');
// 			LPlen = $JqEmptySeat.length;
// 			if(LPlen == 0) break;
// 			$LPThisSeat = $($JqEmptySeat[fnGetRandom(0,LPlen-1)]);
// 			if($LPThisSeat.hasClass('JqUser')){
// 				continue;
// 			}
// 			// if(fnGetRandom(0,100)<50){		
// 				LPaPlayerData = fnIdlePlayer(aPlayerData,aNowSeat);
// 				$LPThisSeat.attr('data-i',i);
// 				$LPThisSeat.addClass('JqAdded active');
// 				$LPThisSeat.find('.JqSeatName').text(LPaPlayerData['sName0']);
// 				$LPThisSeat.find('.JqSeatMoney').text(LPaPlayerData['nMoney']);
// 				aNowSeat[LPaPlayerData['sName0']] = {};
// 			// }
// 		}
// 	}
// 	else{
// 		for(let i=0;i<nLen;i++){ // 座位循環
// 			$LPThisSeat = $($('.JqSeat')[i]);
// 			if($LPThisSeat.hasClass('JqUser')){
// 				continue;
// 			}
// 			if(!$LPThisSeat.hasClass('JqAdded')){// 空的位子
// 				if(fnGetRandom(0,1000)<20){// 20坐入
// 					LPaPlayerData = fnIdlePlayer(aPlayerData,aNowSeat);
// 					$LPThisSeat.attr('data-i',LPaPlayerData['sKey']);
// 					$LPThisSeat.addClass('JqAdded active');
// 					$LPThisSeat.find('.JqSeatName').text(LPaPlayerData['sName0']);
// 					$LPThisSeat.find('.JqSeatMoney').text(LPaPlayerData['nMoney']);	
// 					aNowSeat[LPaPlayerData['sName0']] = {};
// 				}
// 			}
// 			else{
// 				if(fnGetRandom(0,1000)<10){// 有人的位子 10離開
// 					delete aNowSeat[$LPThisSeat.find('.JqSeatName').text()];
// 					$LPThisSeat.removeClass('JqAdded active');
// 					$LPThisSeat.find('.JqSeatName').text('');
// 					$LPThisSeat.find('.JqSeatMoney').text('');
// 				}
// 			}
// 		}
// 	}
// }

function fnSettingSeat(nInit=0,aPlayerData){
	let nLen = $('.JqSeat').length;
	let LPaPlayerData,$LPThisSeat,aNowSeat = {};
	for(let i=0;i<nLen;i++){
		if($($('.JqSeat')[i]).find('.JqSeatName').text() != ''){
			aNowSeat[$($('.JqSeat')[i]).find('.JqSeatName').text()] = {};
		}
	}
	if(nInit === 1){
		// aUser在Socket那隻有讀取
		let $JqUserSeat = $($('.JqSeat')[fnGetRandom(0,nLen-1)]);
		$JqUserSeat.addClass('JqAdded active');
		$JqUserSeat.addClass('JqUser');
		$JqUserSeat.parent().addClass('here');
		$JqUserSeat.find('.JqSeatName').text(gaUser['aData']['sName0']);
		$JqUserSeat.find('.JqSeatMoney').text(gaUser['aData']['nMoney']);
		aNowSeat[gaUser['aData']['sName0']] = {};
		for(let i=0;i<nLen;i++){ // 座位循環
			$LPThisSeat = $($('.JqSeat')[i]);
			if($LPThisSeat.hasClass('JqUser')){
				continue;
			}
			if(fnGetRandom(0,100)<50){		
				LPaPlayerData = fnIdlePlayer(aPlayerData,aNowSeat);
				$LPThisSeat.attr('data-i',i);
				$LPThisSeat.addClass('JqAdded active');
				$LPThisSeat.find('.JqSeatName').text(LPaPlayerData['sName0']);
				$LPThisSeat.find('.JqSeatMoney').text(LPaPlayerData['nMoney']);
				aNowSeat[LPaPlayerData['sName0']] = {};
			}
		}
	}
	else{
		for(let i=0;i<nLen;i++){ // 座位循環
			$LPThisSeat = $($('.JqSeat')[i]);
			if($LPThisSeat.hasClass('JqUser')){
				continue;
			}
			if(!$LPThisSeat.hasClass('JqAdded')){// 空的位子
				if(fnGetRandom(0,1000)<20){// 20坐入
					LPaPlayerData = fnIdlePlayer(aPlayerData,aNowSeat);
					if(LPaPlayerData == undefined || LPaPlayerData['sName0'] == undefined){
						console.log(aPlayerData);
					}
					// $LPThisSeat.attr('data-i',LPaPlayerData['sKey']);
					$LPThisSeat.addClass('JqAdded active');
					$LPThisSeat.find('.JqSeatName').text(LPaPlayerData['sName0']);
					$LPThisSeat.find('.JqSeatMoney').text(LPaPlayerData['nMoney']);	
					aNowSeat[LPaPlayerData['sName0']] = {};
				}
			}
			else{
				if(fnGetRandom(0,1000)<10){// 有人的位子 10離開
					delete aNowSeat[$LPThisSeat.find('.JqSeatName').text()];
					$LPThisSeat.removeClass('JqAdded active');
					$LPThisSeat.find('.JqSeatName').text('');
					$LPThisSeat.find('.JqSeatMoney').text('');
				}
			}
		}
	}
}

function fnIdlePlayer(aPlayerData,aNowSeat){
	for(let LPsKey in aPlayerData){
		if(aNowSeat[LPsKey] == undefined){
			return aPlayerData[LPsKey];
		}
	}
	// let aPlayerTemp = [];
	// let i=0;
	// for(let LPsKey in aPlayerData){
	// 	if(aPlayerData[LPsKey]['nSit'] == 0){
	// 		aPlayerTemp[i] = aPlayerData[LPsKey];
	// 		aPlayerTemp[i]['sKey'] = LPsKey;
	// 		i++;
	// 	}
	// }
	// let nLen = aPlayerTemp.length;
	// let aReturn = aPlayerTemp[fnGetRandom(0,nLen-1)];
	// console.log(aReturn);
	// if(aReturn != undefined){
	// 	aPlayerData[aReturn['sKey']]['nSit'] = 1;
	// }
	// return aPlayerData[sKey];
}