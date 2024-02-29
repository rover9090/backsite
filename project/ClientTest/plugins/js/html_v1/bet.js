var nCoin = 0;
var aBetData = {};
var aRepeatData = {};
var aBetLangs = JSON.parse($('.JqaBetLangs').val());

$(document).ready(function () {

	// let aPlay = {};
	// let nTotolMoney = 0;
	// let nNowChip = 0;
	// let bCanBet = false;

	// // 籌碼
	// $('.JqChip').on('click', function () {
	// 	$('.JqChip').removeClass('active');
	// 	$(this).toggleClass('active');
	// 	nNowChip = $(this).data('money');
	// 	bCanBet = true;
	// });

	// $('.JqBtn').on('click', function () {
	// 	if(bCanBet)
	// 	{
	// 		if (!$(this).hasClass('active'))
	// 		{
	// 			$(this).addClass('active');
	// 		}

	// 		var nBetMoney = $(this).find('.JqBetMoney').text();
	// 		nBetMoney = parseFloat(nBetMoney) + parseFloat(nNowChip);
	// 		nTotolMoney += parseFloat(nNowChip);
	// 		$(this).find('.JqBetMoney').text(nBetMoney);
	// 		$('.JqTotalMoney').text(nTotolMoney);
	// 		aPlay[$(this).data('code')] = nBetMoney;
	// 	}
	// 	else
	// 	{
	// 		alert(aJSDEFINE['NOCHIP']);
	// 	}
	// });

	// $('.JqClear').click(function () {
	// 	$('.JqChip').removeClass('active');
	// 	$('.JqBtn').removeClass('active');
	// 	$('.JqBetMoney').text(0);
	// 	$('.JqTotalMoney').text(0);
	// 	bCanBet = false;
	// 	nTotolMoney = 0;
	// 	aPlay = {};
	// 	$('.JqConFirm').show();
	// });

	// $('.JqConFirm').click(function () {
	// 	$(this).hide();
	// 	var sUrl = $('input[name=sAjaxUrl]').val();
	// 	var sJWT = $('input[name=sJWT]').val();
	// 	var nUid = $('input[name=nUid]').val();
	// 	$.ajax({
	// 		url: sUrl,
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		data: {
	// 			aPlay: aPlay,
	// 			sJWT: sJWT,
	// 			nUid: nUid,
	// 		},
	// 		success: function (oRes) {

	// 			// alert(oRes.sMsg);
	// 			$('.JqJumpMsgBox').addClass('active');
	// 			$('.JqJumpMsgContentTxt').text(oRes.sMsg);
	// 		},
	// 		error: function (exception) {
	// 			console.log('Exeption:' + exception);
	// 		}
	// 	});
	// });

	//////////////

	$('.JqAZone').click(function() {
		if($('.JqStopBet').hasClass('active') || nCoin <= 0){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['A'] != undefined){
			nBetMoney = aBetData['A'];
		}
		aBetData['A'] = nBetMoney + nCoin;
		$('.JqAWinBet').text(aBetData['A']);
	});

	$('.JqBZone').click(function() {
		if($('.JqStopBet').hasClass('active') || nCoin <= 0){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['B'] != undefined){
			nBetMoney = aBetData['B'];
		}
		aBetData['B'] = nBetMoney + nCoin;
		$('.JqBWinBet').text(aBetData['B']);
	});

	$('.JqCZone').click(function() {
		if($('.JqStopBet').hasClass('active') || nCoin <= 0){
			return;
		}
		let nBetMoney = 0;
		if(aBetData['C'] != undefined){
			nBetMoney = aBetData['C'];
		}
		aBetData['C'] = nBetMoney + nCoin;
		$('.JqCWinBet').text(aBetData['C']);
	});

	$('.JqCancelBet0').click(function() {
		$('.JqCWinBet').text(0);
		$('.JqBWinBet').text(0);
		$('.JqAWinBet').text(0);
		$('.JqCInputBet').val('');
		$('.JqBInputBet').val('');
		$('.JqAInputBet').val('');
		aBetData = {};
	});

	$('.JqClose').click(function() {
		let nKindCtrl = $('.JqClose').attr('data-kindctrl');

		if(nKindCtrl == 13){
			$('.JqCWinBet').text(0);
			$('.JqBWinBet').text(0);
			$('.JqAWinBet').text(0);
			$('.JqCInputBet').val('');
			$('.JqBInputBet').val('');
			$('.JqAInputBet').val('');
			aBetData = {};
		}
	});

	$('.JqRepeatBet').click(function() {
		if(Object.keys(aRepeatData).length == 0){
			return;
		}
		aBetData = aRepeatData;
		$('.JqCWinBet').text('0');
		$('.JqAWinBet').text('0');
		$('.JqBWinBet').text('0');
		fnCalcSum();
		for(let LPsKey in aRepeatData){
			switch (LPsKey) {
				case 'C':
					$('.JqCWinBet').text(aRepeatData[LPsKey]);
					break;
				case 'A':
					$('.JqAWinBet').text(aRepeatData[LPsKey]);
					break;
				case 'B':
					$('.JqBWinBet').text(aRepeatData[LPsKey]);
					break;
				default:
					break;
			}
		}
	});

	$('.JqConfirmBet').click(function() {
		let nAInputBet = $('.JqAInputBet').val();
		let nBInputBet = $('.JqBInputBet').val();
		let nCInputBet = $('.JqCInputBet').val();
		let nBetSum = 0;

		if(nAInputBet == '' && nBInputBet == '' && nCInputBet == '')
		{
			aBetData = {};
			if($('.JqAWinBet').html() != 0){
				aBetData['A'] = parseInt($('.JqAWinBet').html());
			}
			if($('.JqBWinBet').html() != 0){
				aBetData['B'] = parseInt($('.JqBWinBet').html());
			}
			if($('.JqCWinBet').html() != 0){
				aBetData['C'] = parseInt($('.JqCWinBet').html());
			}
		}
		else
		{
			aBetData = {};
			if(nAInputBet != ''){
				aBetData['A'] = parseInt(nAInputBet);
			}
			if(nBInputBet != ''){
				aBetData['B'] = parseInt(nBInputBet);
			}
			if(nCInputBet != ''){
				aBetData['C'] = parseInt(nCInputBet);
			}
		}
		
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
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').html('');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').append(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);
				if(oRes['nError'] == 0){
					aBetData = {};
					$('.JqBetBtn').text(0);
				}

				if(oRes['nError'] == 0){
					for(let LPsKey in oRes['aData']['aBetData']){
						gaBetPool[LPsKey] = oRes['aData']['aBetData'][LPsKey];
					}
					// $('.JqCWinMoney').text(oRes['aData']['aBetMoney']['C']);
					// $('.JqBWinMoney').text(oRes['aData']['aBetMoney']['B']);
					// $('.JqAWinMoney').text(oRes['aData']['aBetMoney']['A']);
					// $('.JqCWinCount').text(oRes['aData']['aBetCount']['C']);
					// $('.JqBWinCount').text(oRes['aData']['aBetCount']['B']);
					// $('.JqAWinCount').text(oRes['aData']['aBetCount']['A']);
					
					$('.JqCWinBet').text('0');
					$('.JqAWinBet').text('0');
					$('.JqBWinBet').text('0');
					$('.JqSixBet').text('0');
					gaUser['nIdleRound'] = 0;

					$('.JqBetBtnBox').removeClass('active');
					$('.JqMsgBox').addClass('active');
					$('.JqMyMoney0').text(oRes['aUser']['nMoney']);
					$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	// 搶莊
	$('.JqSubmitBanker0').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sBanker0JWT]').attr('data-url');
		let sJWT = $('input[name=sBanker0JWT]').val();

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nGame': $('.JqnGame').val(),
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);				
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
				$('.JqWindowBox').removeClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);

				if(oRes['nError'] == 0){
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	// 集資
	$('.JqSubmitBanker1').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sBanker1JWT]').attr('data-url');
		let sJWT = $('input[name=sBanker1JWT]').val();

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nGame': $('.JqnGame').val(),
				'nMoney': $('.JqMoneyBanker1').val(),
				'sLineId': $('input[name=sLineId]').val(),
				'bSumMoney': $('.JqCoinBanker.JqSumCoin').attr('bSumMoney'),
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').html(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);

				if(oRes['nError'] == 0){
					gaUser['nIdleRound'] = 0;

					$('.JqBetBtnBox').removeClass('active');
					$('.JqMsgBox').addClass('active');
					$('.JqMyMoney0').text(oRes['aUser']['nMoney']);
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	$('.JqDonateSubmit').on('click',function()
	{
		let nInputDonate = $('.JqInputDonate').val();
		let nMoney = $('.JqDonateMoney').text();
		let sAllitem = $('.JqDonateBox').attr('data-selectitem');
		// nMoney = 20;

		let sUrl = $('input[name=sDonateJWT]').attr('data-url');
		let sJWT = $('input[name=sDonateJWT]').val();
		$('.JqDonateBox').attr('data-selectitem','');
		$('.JqInputDonate').val('');

		if(nInputDonate == '')
		{
			console.log(sJWT,nMoney,sAllitem);
		}
		else
		{
			nMoney = nInputDonate;
			sAllitem = '99,';
			console.log(nInputDonate);
		}
		
		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nMoney': nMoney,
				'sAllitem': sAllitem,
				'sLineId': $('input[name=sLineId]').val(),
				'nGame': $('.JqnGame').val(),
			},
			success: function (oRes) {
				// console.log(oRes);
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);
				let aMessage = {'nArray':1,'aData':{}};
				$('.JqDonateBox').removeClass('active');
				$('.JqMsgBox ').addClass('active');
				$('.JqDonateMoney').text(0);
				$('.JqWindowBox,.JqBlock').removeClass('active');
				$('.JqBlock[data-btn="chatroom"]').addClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);
				// fnSocketMsg(oRes['aSocketMsg'],'donate');
				// 這裡是從客戶端發送,其實也可以改寫成從伺服器端發送
				// console.log(oRes['aSocketMsg'],'send');
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqWindowBtn').on('click',function() {
		let nKindCtrl = $(this).attr('data-kindctrl');

		if(nKindCtrl == 'collect')
		{
			let sUrl = $('input[name=sBankerSumJWT]').attr('data-url');
			let sJWT = $('input[name=sBankerSumJWT]').val();

			$.ajax({
				url: sUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					'sJWT': sJWT,
					'nGame': $('.JqnGame').val(),
					'sLineId': $('input[name=sLineId]').val(),
					'run_page': 1,
				},
				success: function (oRes) {
					$('.JqSumBet').text(oRes['nSumMoney']);
					$('.JqSumCoin').attr('data-clip',oRes['nSumSelf']);
				},
				error: function (exception) {
					console.log('Exeption:' + exception.responseText);
				}
			});
		}
	});
	
	// 取消投注
	$('.JqCancelBet').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sCancelBetJWT]').attr('data-url');
		let sJWT = $('input[name=sCancelBetJWT]').val();

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nGame': $('.JqnGame').val(),
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {
				console.log($(".JqShowArea")[0].scrollHeight);
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').html(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				
				if(oRes['nError'] == 0){
					gaUser['nIdleRound'] = 0;
					
					$('.JqBetBtnBox').removeClass('active');
					$('.JqMsgBox').addClass('active');
				}
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	// 取消配莊
	$('.JqCancelCollect').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sCancelCollectJWT]').attr('data-url');
		let sJWT = $('input[name=sCancelCollectJWT]').val();

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nGame': $('.JqnGame').val(),
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').html(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				
				if(oRes['nError'] == 0){
					gaUser['nIdleRound'] = 0;
					
					$('.JqBetBtnBox').removeClass('active');
					$('.JqMsgBox').addClass('active');
				}
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});
	// $('.JqWatchDealer').on('click',function()
	// {
	// 	let sUrl = $('input[name=sWatchDealerJWT]').attr('data-url');
	// 	let sJWT = $('input[name=sWatchDealerJWT]').val();

	// 	let nStatus = 0;
	// 	if($(this).hasClass('active'))
	// 	{
	// 		nStatus = 1;
	// 	}

	// 	$.ajax({
	// 		url: sUrl,
	// 		type: 'POST',
	// 		dataType: 'json',
	// 		data: {
	// 			'sJWT': sJWT,
	// 			'nStatus': nStatus,
	// 			'run_page': 1,
	// 		},
	// 		success: function (oRes) {
	// 		},
	// 		error: function (exception) {
	// 			console.log('Exeption:' + exception.responseText);
	// 		}
	// 	});
	// });

	// timeTotalbet = setInterval(fnAjaxTotalbet, 2000);
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

function fnAjaxTotalbet(){

	let sUrl = $('input[name=sTotalBetJWT]').attr('data-url');
	let sJWT = $('input[name=sTotalBetJWT]').val();
	let sLineId = $('input[name=sLineId]').val();
	let sNo = $('.JqNo').text();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1000,
		data: {
			'sJWT': sJWT,
			'sLineId': sLineId,
			'sNo': sNo,
			'run_page': 1,
		},
		success: function (oRes) {
			$('.JqATotalBet').html(oRes['aData']['A']['nTotalMoney']);
			$('.JqBTotalBet').html(oRes['aData']['B']['nTotalMoney']);
			$('.JqCTotalBet').html(oRes['aData']['C']['nTotalMoney']);
		},
		error: function (exception) {
			if(exception.statusText == 'timeout'){
				console.log('timeout');
			}
			else{
				console.log('Exeption:' + exception.responseText);
			}
		}
	});
}