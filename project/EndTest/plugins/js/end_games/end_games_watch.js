var gaTimer = {'nNowSecond':0,'bCounting':false};
var gaCheck = {};

$(document).ready(function () {
	nGame = $('.JqnGame').val();
	gaCheck[nGame] ={};
	gaCheck[nGame]['nFinTimer'] = 0;
	gaCheck[nGame]['nStopNow'] = 0;
	nSec = $('select[name="nReloadTime"] option:selected').val();
	let aCARDFLO = {
		'':'',
	};
	// 莊家結果
	fnAjaxBet();
	setInterval(fnAjaxBet, nSec * 1000);

	setInterval(CountDown, 1000);

	setTimeout(function(){ location.reload(); }, 2*60*1000);
});

function fnAjaxBet(){
	let sUrl = $('input[name=sBetJWT]').attr('data-url');
	let sJWT = $('input[name=sBetJWT]').val();
	let nGame = $('.JqnGame').val();
	let nSec = $('select[name="nReloadTime"] option:selected').val();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		data: {
			'sJWT': sJWT,
			'run_page': 1,
			'nGame': nGame,
			'run_page':1,
		},
		success: function (oRes) {
			if(oRes['nError'] != 0){
				console.log(oRes['sMsg']);
				return;
			}
			console.log(oRes['aData'],oRes['aData']['aNums']['nSeconds'],oRes['aData']['aNums']['nSeconds1']);
			let aData = oRes['aData'];
			nSumCount = 0;
			nSumMoney = 0;
			nSumPerMoney = 0;
			for(var LPsKey in aData['aBetCount']){
				$('.JqCount[data-type='+LPsKey+']').text(aData['aBetCount'][LPsKey]);
				nSumCount += aData['aBetCount'][LPsKey];
			}
			// for(var LPsKey in aData['aBetMoney']){
			// 	$('.JqMoney[data-type='+LPsKey+']').text(aData['aBetMoney'][LPsKey]);				
			// 	nSumMoney += aData['aBetMoney'][LPsKey];
			// }
			for(var LPsKey in aData['aPerMoney']){
				$('.JqPerMoney[data-type='+LPsKey+']').text(aData['aPerMoney'][LPsKey]);				
				nSumPerMoney += aData['aPerMoney'][LPsKey];
			}
			$('.JqCount[data-type=SUM]').text(nSumCount);
			// $('.JqMoney[data-type=SUM]').text(nSumMoney);
			$('.JqPerMoney[data-type=SUM]').text(nSumPerMoney);
			sTable = fnGenerateBetTable(aData);
			$('.JqCurrentBet').html(sTable);

			$('.JqCountDown').text(nSec);
			
			// console.log(aData['aNums']);
			// 處理倒數秒數
			if(gaTimer['bCounting'] == false){
				if(aData['aNums']['nSeconds'] !== undefined && aData['aNums']['nSeconds'] > 0){
					console.log('ina');
					gaTimer['nNowSecond'] = aData['aNums']['nSeconds'];
					gaTimer['bCounting'] = true;
					fnClockCountDown(nGame,null);
					fnOpenBet();
					$('.JqClock_'+nGame).removeClass('FontRedImp');
				}
				else if(aData['aNums']['nSeconds1'] !== undefined && aData['aNums']['nSeconds1'] > 0){
					console.log('inb');
					gaTimer['nNowSecond'] = aData['aNums']['nSeconds1'];
					gaTimer['bCounting'] = true;
					$('.JqClock_'+nGame).addClass('FontRedImp');
					fnClockCountDown(nGame,null,1);
				}
			}
			else{
				if(aData['aNums']['nSeconds'] !== undefined && aData['aNums']['nSeconds'] > 0){
					gaTimer['nNowSecond'] = aData['aNums']['nSeconds'];
					fnOpenBet();
					$('.JqClock_'+nGame).removeClass('FontRedImp');
				}
				else if(aData['aNums']['nSeconds1'] !== undefined && aData['aNums']['nSeconds1'] > 0){
					gaTimer['nNowSecond'] = aData['aNums']['nSeconds1'];
					$('.JqClock_'+nGame).addClass('FontRedImp');
				}
			}
			
			fnCheckRoundStatus(aData['aNums'],null);
		},
		error: function (exception) {
			console.log('Exeption:' + exception.responseText);
		}
	});
}

function fnGenerateBetTable(aBetData){
	let sHtml = '';
	let sActUrl = $('input[name=sActUrl]').attr('data-url');
	for(let LPsKey in aBetData['aBetData']){

		sMuteUrl = sActUrl+'&nId='+aBetData['aUser'][aBetData['aBetData'][LPsKey]['nUid']]['nId']+'&nMute=1';
		sBlackUrl = sActUrl+'&nId='+aBetData['aUser'][aBetData['aBetData'][LPsKey]['nUid']]['nId']+'&nBlack=1';

		sHtml += '<tr>';
		sHtml += '<td>'+aBetData['aUser'][aBetData['aBetData'][LPsKey]['nUid']]['sAccountDom']+'</td>';//名稱
		sHtml += '<td>'+aBetData['aUser'][aBetData['aBetData'][LPsKey]['nUid']]['sNameDom']+'</td>';//名稱
		sHtml += '<td>'+aBetData['aBetData'][LPsKey]['sContent0']+'</td>';//下注內容
		sHtml += '<td>'+aBetData['aBetData'][LPsKey]['nMoney0']+'</td>';//下注金額
		// sHtml += '<td>'+aBetData['aBetData'][LPsKey]['nPer']+'</td>';//占成
		// sHtml += '<td>'+aBetData['aBetData'][LPsKey]['nPerMoney']+'</td>';//投注占成
		sHtml += '<td>'+aBetData['aBetData'][LPsKey]['sCreateTime']+'</td>';//下注時間
		sHtml += '<td>'+'<input type="button" class="BtnAny JqWatchMute" onclick="myWindow=window.open(\''+sMuteUrl+'\')" value="禁言"><input type="button" class="BtnAny JqWatchBlack" onclick="myWindow=window.open(\''+sBlackUrl+'\')" value="黑名單">'+'</td>';//操作
		sHtml += '</tr>';
	}
	for(let LPsKey in aBetData['aBankerData']){

		sMuteUrl = sActUrl+'&nId='+aBetData['aUser'][aBetData['aBankerData'][LPsKey]['nUid']]['nId']+'&nMute=1';
		sBlackUrl = sActUrl+'&nId='+aBetData['aUser'][aBetData['aBankerData'][LPsKey]['nUid']]['nId']+'&nBlack=1';

		sHtml += '<tr>';
		sHtml += '<td>'+aBetData['aUser'][aBetData['aBankerData'][LPsKey]['nUid']]['sAccountDom']+'</td>';//名稱
		sHtml += '<td>'+aBetData['aUser'][aBetData['aBankerData'][LPsKey]['nUid']]['sNameDom']+'</td>';//名稱
		sHtml += '<td>'+aBetData['aBankerData'][LPsKey]['sContent0']+'</td>';//下注內容
		sHtml += '<td>'+aBetData['aBankerData'][LPsKey]['nMoney0']+'</td>';//下注金額
		// sHtml += '<td>'+aBetData['aBankerData'][LPsKey]['nPer']+'</td>';//占成
		// sHtml += '<td>'+aBetData['aBankerData'][LPsKey]['nPerMoney']+'</td>';//投注占成
		sHtml += '<td>'+aBetData['aBankerData'][LPsKey]['sCreateTime']+'</td>';//下注時間
		sHtml += '<td>'+'<input type="button" class="BtnAny JqWatchMute" onclick="myWindow=window.open(\''+sMuteUrl+'\')" value="禁言"><input type="button" class="BtnAny JqWatchBlack" onclick="myWindow=window.open(\''+sBlackUrl+'\')" value="黑名單">'+'</td>';//操作
		sHtml += '</tr>';
	}
	return sHtml;
}

function fnClockCountDown(nGame,nSecondnull,nSettle=0){
	let nSecond = gaTimer['nNowSecond'];
	if(gaCheck[nGame]['nStopNow'] == 1){
		// console.log('go',nGame,nSecond);
		gaCheck[nGame]['nFinTimer'] = 0;
		gaCheck[nGame]['nStopNow'] = 0;
		$('.JqClock_'+nGame).removeClass('FontRedImp');
		$('.JqClock_'+nGame).text(0);
		return;
	}
	if(nSettle == 0){
		if(nSecond < 0){
			$('.JqStopBet').addClass('active');
			$('.JqClock').removeClass('active');
			$('.JqCard').addClass('active');
			$('.JqFinalBox').addClass('active');
			$('.JqOpenNow_'+nGame).addClass('active');// 開牌中
			gaTimer['bCounting'] = false;
			return;
		}
		$('.JqOpenNow_'+nGame).removeClass('active');
		$('.JqClock_'+nGame).text(nSecond);
	}
	else{
		if(nSecond < 0){
			gaCheck[nGame]['nFinTimer'] = 0;
			$('.JqClock_'+nGame).removeClass('FontRedImp');
			gaTimer['bCounting'] = false;
			return;
		}
		$('.JqClock_'+nGame).text(nSecond);
	}
	gaTimer['nNowSecond']--;
	// console.log(gaTimer['nNowSecond']);
	setTimeout(fnClockCountDown,1000,nGame,nSecond-1,nSettle);
}

function fnCheckRoundStatus(aNums,nGamenull){
	let nGame = $('.JqnGame').val();
	if(aNums['nFinalRound'] == '1' || aNums['nDayLast'] == '1'){
		$('.JqFinalRound').addClass('active');
	}else{
		$('.JqFinalRound').removeClass('active');
	}

	if(parseInt(aNums['nDayLast']) > 0){
		$('.JqDayLastBlock').addClass('active');
		$('.JqDayLast').text(parseInt(aNums['nDayLast']));
	}else{
		$('.JqDayLastBlock').removeClass('active');
	}

	if(aNums['nCancel'] == '1'){
		$('.JqStopBet').removeClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nStopNow'] = 1;
	}
	else if(aNums['nCancel'] == '2'){
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').addClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nStopNow'] = 1;
		if(aNums['nPauseTime1'] > 0){
			gaCheck[nGame]['nStopNow'] = 0;
		}
	}
	else{
		$('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').removeClass('active');
	}

	if(aNums['nShuffling'] == '1'){
		$('.JqShuffling').addClass('active');
		$('.JqStopBet').removeClass('active');
		$('.JqPlayerGrade').text(0);
		$('.JqBankerGrade').text(0);
		$('.JqFinalBox').removeClass('active');
	}
	else{
		$('.JqShuffling').removeClass('active');
	}
}

function fnOpenBet(){
	$('.JqStopBet').removeClass('active');
	// $('.JqCard').css('transform','rotateY(0deg)');
	// $('.JqCardP3').css('transform','rotateZ(90deg)');
	// $('.JqCardB3').css('transform','rotateZ(90deg)');
	$('.JqCard').removeClass('active');
	$('.JqCard').removeClass('spin');
	$('.JqClock').addClass('active');
	$('.JqFinalBox').removeClass('active');
	$('.JqPlayerGrade').text(0);
	$('.JqBankerGrade').text(0);
}

function CountDown(){
	let nSec = $('.JqCountDown').text();

	if(nSec != 0)
	{
		$('.JqCountDown').text(nSec-1);
	}
}