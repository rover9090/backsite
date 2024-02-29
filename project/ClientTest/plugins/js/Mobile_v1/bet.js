var nCoin = 0;
var aBetData = {};
var aRepeatData = {};
var aBetLangs = JSON.parse($('.JqaBetLangs').val());
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
		$('.JqBankerWinBet').text(aBetData['BW']);
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
	});

	$('.JqCancelBet').click(function() {
		$('.JqSixBet').text(0);
		$('.JqPlayerPairBet').text(0);
		$('.JqBankerPairBet').text(0);
		$('.JqDrawBet').text(0);
		$('.JqPlayerWinBet').text(0);
		$('.JqBankerWinBet').text(0);
		aBetData = {};
	});

	$('.JqRepeatBet').click(function() {
		if(Object.keys(aRepeatData).length == 0){
			return;
		}
		aBetData = aRepeatData;
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
	});

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
					$('.JqBetBtn').text(0);
				}

				console.log(oRes['aData']);			
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
				$this.attr('disabled',false);
			}
		});
	});

	$('.JqWatchDealer').on('click',function()
	{
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
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});
});

function fnCalcSum(){
	let nBetSum = 0;
	// $('.JqBetCheckBoxContent').html('');
	console.log(aBetData);
	for(let LPsKey in aBetData){
		// $('.JqBetCheckBoxContent').append('<tr><td class="infoTd">'+aBetLangs[LPsKey]+'</td><td class="infoTd">'+aBetData[LPsKey]+'</td></tr>');
		nBetSum += (aBetData[LPsKey]);
	}
	$('.JqBetCheckBoxSum').text(nBetSum);
}