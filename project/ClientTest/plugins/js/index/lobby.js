/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3. 
 */
var aNumsRoad = {};
// var aNumsCounting = JSON.parse($('.JqaNumsCounting').val());
var gnGame = ($('.JqnGame').length==1)?$('.JqnGame').val():0;
var gaCheck = {};
var gaUser = {'nIdleRound':sessionStorage.getItem('nIdleRound')==null?0:sessionStorage.getItem('nIdleRound'),'aData':{}};
var sDevice = $('.JqBody').attr('data-device');
var gaBetPool = {};
gaUser['aData']['sName0'] = $('input[name=aUserData]').attr('data-name0');
gaUser['aData']['sAccount'] = $('input[name=aUserData]').attr('data-account');
gaUser['aData']['nUid'] = $('input[name=aUserData]').attr('data-uid');
gaUser['aData']['nMute'] = $('input[name=aUserData]').attr('data-mute');
gaUser['aData']['nMoney'] = $('input[name=aUserData]').attr('data-money');
gaUser['aData']['sJWT'] = $('input[name=aUserData]').attr('data-jwt');
gaUser['aData']['nTest'] = $('input[name=aUserData]').attr('data-test');
gaUser['aData']['sPicture'] = $('input[name=aUserData]').attr('data-picture');
var gaQuitCard = {};
var gaDrawCard = {};
var gaShowCard = {};
var gaJsonMsg = JSON.parse($('.JqaJsonMsg').val());
$(document).ready(function () {

	$('.JqDealerImage').removeClass('active');
	// var aNumsArray = {}; // aNumsArray = 房間=>歷屆期數資料=>資料
	gaCheck[gnGame] = {};
	gaCheck[gnGame]['nCheckCard'] = 0; // 看底牌
	gaCheck[gnGame]['timeLobby'] = null;// 中斷或維護的檢查
	gaCheck[gnGame]['timeoutCount'] = null;// 40和7的秒數倒數
	gaCheck[gnGame]['timeCountInterval'] = null;// 每秒秒數的-1倒數
	gaCheck[gnGame]['nShowStart'] = 0;// 開始投注

	$('.JqChangeBtn').on('click' , function()
	{
		if(!$('.JqChangeMenu').hasClass('active')){
			var aGames = JSON.parse($('.JqPageGames').val());		
			for(let LPsKey in gaCheck){
				if(LPsKey === gnGame) continue;
				clearInterval(gaCheck[LPsKey]['timeLobby']);
				clearTimeout(gaCheck[LPsKey]['timeoutCount']);
				clearInterval(gaCheck[LPsKey]['timeCountInterval']);
				gaCheck[LPsKey]['timeLobby'] = null;
				gaCheck[LPsKey]['timeoutCount'] = null;
				gaCheck[LPsKey]['timeCountInterval'] = null;
			}
			for(let LPsKey in aGames){
				if(LPsKey === gnGame) continue;
				fnAjaxLobby1(0,LPsKey);
			}
		}
	});

	$('.JqChangeMenu').on('click' , function()
	{
		for(let LPsKey in gaCheck){
			if(LPsKey === gnGame) continue;
			clearInterval(gaCheck[LPsKey]['timeLobby']);
			clearTimeout(gaCheck[LPsKey]['timeoutCount']);
			clearInterval(gaCheck[LPsKey]['timeCountInterval']);
			gaCheck[LPsKey]['timeLobby'] = null;
			gaCheck[LPsKey]['timeoutCount'] = null;
			gaCheck[LPsKey]['timeCountInterval'] = null;
		}
	});	

	var aGames = JSON.parse($('.JqDefaultGames').val());
	if(gnGame == 0){
		for(let LPsKey in aGames){
			fnAjaxLobby1(0,LPsKey);
		}
	}
	else{
		gaCheck[gnGame]['timeLobby'] = setInterval(fnAjaxLobby1, 3000,0,gnGame);
		fnAjaxLobby1(0,gnGame);
	}

	
	$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
});

function fnDrawBoard(aNumsArrayUnit,LPsKey,aDebug={}){
	if(aNumsArrayUnit == undefined) aNumsArrayUnit={};
	let nWidth = $('.JqBoardWidth_0').val();
	let nBoxLen = nWidth * 6;
	let nLen = Object.keys(aNumsArrayUnit).length;// 0-72 最近累積的期數
	let i;
	let aNumsCounting = {"BW":0,"PW":0,"DRAW":0,"BP":0,"PP":0,"SIX":0};
	let nBoardLen = nWidth * 6;
	if(nLen <= (nBoxLen-6)){
		i=0;
	}
	else{
		i = Math.ceil((nLen-(nBoxLen))/6)*6;// x軸永遠預留一格
	}
	tmp = i;
	for(let j=0;j<nBoardLen;j++){
		$('.JqBoard_'+LPsKey+'.JqNo'+j).html('');		
	}	
	for(let j=0;i<nLen;i++,j++){
		if(aNumsArrayUnit[i] !== undefined){
			for(let LPsKey2 in aNumsArrayUnit[i]['aResult']){
				$('.JqBoard_'+LPsKey+'.JqNo'+j).append(aBoardCell[aNumsArrayUnit[i]['aResult'][LPsKey2]]);
			}
		}
	}
	for(let i=0;i<nLen;i++){
		if(aNumsArrayUnit[i] === undefined){
			console.log(aDebug,i);
			console.log(aNumsArrayUnit);
			aNumsArrayUnit[i] = {};
			aNumsArrayUnit[i]['aResult'] = [];
		}
		if(aNumsArrayUnit[i]['aResult'] == undefined){
			// aNumsArrayUnit[i]['aResult'] = [];
			// clearInterval(timeLobby1);
			// return;
		}
		for(let LPsKey2 in aNumsArrayUnit[i]['aResult']){ // Cannot read property 'aResult' of undefined from 192
			aNumsCounting[aNumsArrayUnit[i]['aResult'][LPsKey2]]++;
		}
	}
	
	$('.JqBankerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"]);
	$('.JqPlayerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PW"]);
	$('.JqPlayerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PP"]);
	$('.JqBankerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BP"]);
	$('.JqDraw[data-ngame="'+LPsKey+'"]').text(aNumsCounting["DRAW"]);
	$('.JqSix[data-ngame="'+LPsKey+'"]').text(aNumsCounting["DRAW"]);
	$('.JqGamesAll[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"] + aNumsCounting["PW"] + aNumsCounting["DRAW"]);
}


function fnAjaxLobby1(nRetry=0,sGame=null,debug='',nShowSettle=0){
	let sUrl = $('input[name=sLobbyJWT]').attr('data-url1');
	let sJWT = $('input[name=sLobbyJWT]').val();

	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		data: {
			'sJWT': sJWT,
			'nGame': sGame,
			'nShowSettle': nShowSettle,
			'sLineId': $('input[name=sLineId]').val(),
			'nNoId': $('input[name=sLobbyJWT]').attr('data-nNoId'),
			'nCheckTime': $('input[name=sLobbyJWT]').attr('data-nCheckTime'),
			'run_page': 1,
		},
		timeout:10000,
		success: function (oRes) {
			gaUser['aData'] = oRes['aUser'];
			$('input[name=aUserData]').attr('data-mute',oRes['aUser']['nMute']);
			$('.JqMyMoney0').text(oRes['aUser']['nMoney']);
			LPsKey = sGame;

			if(oRes['aData'][LPsKey]['aSetting']['nShuffling'] == '1'){
				$('.JqShuffling_'+LPsKey).addClass('active');
			}
			else{
				$('.JqShuffling_'+LPsKey).removeClass('active');
			}

			$('.JqATotalBet').html(oRes['aData'][LPsKey]['aBetData']['A']['nTotalMoney']);
			$('.JqBTotalBet').html(oRes['aData'][LPsKey]['aBetData']['B']['nTotalMoney']);
			$('.JqCTotalBet').html(oRes['aData'][LPsKey]['aBetData']['C']['nTotalMoney']);

			$('.JqDealerImage').removeClass('active');

			// $('.JqCloseRoom_'+LPsKey).parents('a').attr('href',oRes['aData'][LPsKey]['aSetting']['sBetUrl']);
			if(oRes['aData'][LPsKey]['aSetting']['nCloseRoom'] == 1 || oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 1){
				if(oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
					$('.JqCloseRoom_'+LPsKey).removeClass('active');
					$('.JqComingSoon_'+LPsKey).addClass('active');
					// $('.JqClock_'+LPsKey).text(nComing);
				}
				else{
					$('.JqCloseRoom_'+LPsKey).addClass('active');
				}
				if(oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
					let sOpenTime = aJSDEFINE[sDevice+'_OPENROOM'].replace('[:TIME:]',oRes['aData'][LPsKey]['aSetting']['sOpenTime']);
					$('.JqCloseRoom_'+LPsKey).find('div').text(sOpenTime);
				}
			}
			else{
				$('.JqCloseRoom_'+LPsKey).removeClass('active');
				$('.JqComingSoon_'+LPsKey).removeClass('active');					
			}
			$('.JqStickerCount').text('*0');
			for(let LPsKey in oRes['aUser']['aMall']){
				$('.JqSticker'+LPsKey+'Count').text('*'+oRes['aUser']['aMall'][LPsKey]['nStock']);
			}


			let nNowTime = oRes['nNowTime'];
			for(let LPsKey in oRes['aData'][gnGame]['aJsonMsg']){
				if(gaJsonMsg[LPsKey] !== undefined){
					continue;
				}
				if(LPsKey > nNowTime){
					gaJsonMsg[LPsKey] = true;				
					setTimeout(function(oRes,LPsKey){
						fnChatAct(oRes,LPsKey);
					}, LPsKey-nNowTime,oRes,LPsKey);
				}
				else{
					gaJsonMsg[LPsKey] = true;
					fnChatAct(oRes,LPsKey);
				}
			}

			if(typeof oRes['aData'][LPsKey]['aLastest'] === 'undefined') return;

			$('input[name=sLobbyJWT]').attr('data-nNoId',oRes['aData'][LPsKey]['aLastest']['nId']);
			$('input[name=sLobbyJWT]').attr('data-nCheckTime',oRes['aData'][LPsKey]['aLastest']['nCheckTime']);

			
			if(gnGame == LPsKey){
				
				fnCheckRoundStatus(oRes['aData'][LPsKey],LPsKey);
				$('.JqJackpotMoney').text(oRes['aData'][LPsKey]['aJackpot']['nMoney0']);
				$('.JqNo').text(oRes['aData'][LPsKey]['aLastest']['sNo']);
				if(oRes['aData'][LPsKey]['aLastest']['nRound'] == undefined){
					$('.JqRound').text(0);
				}
				else{
					$('.JqRound').text(parseInt(oRes['aData'][LPsKey]['aLastest']['nRound'])+1);
				}
				
				$('.JqNo').text(oRes['aData'][LPsKey]['aLastest']['sNo']);
				$('.JqRound').text(parseInt(oRes['aData'][LPsKey]['aLastest']['nRound'])+1);
			}
			return;

			
		},
		error: function (exception, status, error) {
			if(exception.statusText == 'timeout'){
				console.log('timeout');
				if(nRetry == 1){
					fnAjaxLobby1(1,sGame);
				}
			}
			else if(exception.readyState == 4){
				console.log('Exeption:' + exception.responseText);
				for(let LPsKey in gaCheck){
					clearInterval(gaCheck[LPsKey]['timeLobby']);
					clearTimeout(gaCheck[LPsKey]['timeoutCount']);
					clearInterval(gaCheck[LPsKey]['timeCountInterval']);
				}
				// $('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				// $('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['IDLE10MIN']);
				sessionStorage.setItem('nIdleRound',0);
				$('.JqMsg0Box').click(function(){
					console.log(1);
					// window.location = './?';
				});
				setTimeout(() => {
					console.log(1);
					// window.location = './?';
				}, 3000);
			}
		}
	});
}

// 
function fnCloseBet(){
	$('.JqStopBet').addClass('active');
	// $('.JqCard').css('transform','rotateY(0deg)');
	// $('.JqCardP3').css('transform','rotateZ(90deg)');
	// $('.JqCardB3').css('transform','rotateZ(90deg)');
	$('.JqClock').removeClass('active');
	$('.JqFinalBox').addClass('active');
	// $('.JqPlayerGrade').text(0);
	// $('.JqBankerGrade').text(0);
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



function fnCheckRoundStatus(aNums,nGame){
	if(aNums['aLastest']['nFinalRound'] == '1' || aNums['aLastest']['nDayLast'] == '1'){
		$('.JqFinalRound').addClass('active');
	}else{
		$('.JqFinalRound').removeClass('active');
	}

	if(parseInt(aNums['aLastest']['nDayLast']) > 0){
		$('.JqDayLastBlock').addClass('active');
		if(aNums['aLastest']['nDayLast'] == '1' && aNums['aLastest']['sNums'] != ''){
			$('.JqDayLast').text(0);
		}
		else{
			$('.JqDayLast').text(parseInt(aNums['aLastest']['nDayLast']));
		}
	}else{
		$('.JqDayLastBlock').removeClass('active');
	}

	if(aNums['aLastest']['nCancel'] == '1'){
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').removeClass('active');
		// $('.JqCancelRound').addClass('active');
		clearTimeout(gaCheck[LPsKey]['timeoutCount']);
		clearInterval(gaCheck[LPsKey]['timeCountInterval']);
		gaCheck[LPsKey]['timeoutCount'] = null;
		gaCheck[LPsKey]['timeCountInterval'] = null;
	}
	else if(aNums['aLastest']['nCancel'] == '2'){
		$('.JqStopBet').removeClass('active');
		// $('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').addClass('active');
		clearTimeout(gaCheck[LPsKey]['timeoutCount']);
		clearInterval(gaCheck[LPsKey]['timeCountInterval']);
		gaCheck[LPsKey]['timeoutCount'] = null;
		gaCheck[LPsKey]['timeCountInterval'] = null;
	}
	else{
		// $('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').removeClass('active');
	}

	if(aNums['aSetting']['nShuffling'] == '1'){
		$('.JqCard').removeClass('active');
		$('.JqCard').removeClass('spin');
		$('.JqShuffling').addClass('active');
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').removeClass('active');
		// $('.JqCancelRound').removeClass('active');
		$('.JqPlayerGrade').text(0);
		$('.JqBankerGrade').text(0);
		$('.JqFinalBox').removeClass('active');
	}
	else{
		$('.JqShuffling').removeClass('active');
	}
}



function fnGetRandom(nMin,nMax){
	nReturn = Math.floor(Math.random() * nMax)+nMin+1;
	return nReturn;
};

function fnStartCountDown(aData,nGame){
	if(aData[nGame]['aLastest']['nSeconds'] > 0){
		$('.JqClock_'+nGame).text(aData[nGame]['aLastest']['nSeconds']);
	}
	else if(aData[nGame]['aLastest']['nSeconds1'] > 0){
		$('.JqClock_'+nGame).text(aData[nGame]['aLastest']['nSeconds1']);
	}
	gaCheck[nGame]['timeCountInterval'] = setInterval(function (aData) {// 用object就能pass ref
		aData[nGame]['aLastest']['nSeconds']--;
		aData[nGame]['aLastest']['nSeconds1']--;
		let nSec = 0;
		if(aData[nGame]['aLastest']['nSeconds'] > 0){
			nSec = aData[nGame]['aLastest']['nSeconds'];
		}
		else if(aData[nGame]['aLastest']['nSeconds1'] > 0){
			nSec = aData[nGame]['aLastest']['nSeconds1'];
		}
		$('.JqClock_'+nGame).text(nSec);
		if (aData[nGame]['aLastest']['nSeconds'] < 0 && aData[nGame]['aLastest']['nSeconds1'] < 0) {
			clearInterval(gaCheck[nGame]['timeCountInterval']);
			gaCheck[nGame]['timeCountInterval'] = null;
			return;
		}
	}, 1000,aData);
}

function fnChatAct(oRes,LPsKey){
	return;
	console.log('oops');
	if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['sMsg'] !== undefined){
		// $(".JqShowArea").append(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['sMsg']);
		if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nUid'] === gaUser['aData']['nUid'] ||
		oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nRefresh'] !== undefined){
			// $('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
		}
		if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['sMsg'].indexOf('JqJumpBet')){
			$('.JqJumpBet').on('click',function(){
				// $('.JqBlock').removeClass('active');
				// $('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
				// $('.JqBlock[data-btn="bet"]').addClass('active');
				// fnGameSetChipCss();
			});
		}
	}else if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nUse'] !== undefined){
		// let tmpSrc = $('.JqStickerItem[data-num='+oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nUse']+']').attr('data-src');
		// $('.JqWindowBox,.JqBlock').removeClass('active');
		// $('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
		// $('.JqStickerVideo>source').attr('src',tmpSrc);
		// $('.JqStickerVideo').attr('src',tmpSrc);
		// $('.JqStickerVideo').get(0).play();
	}
	if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['bSticker'] !== undefined){
		if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['bSticker'] == 0 && $('.JqStickerVideo').attr('data-use') == 0){
			// $('.JqChatStickerBox').removeClass('active');
		}
	}
	if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nSumMoney0'] !== undefined){
		// $('.JqCoinBanker.JqSumCoin').attr('data-clip',oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nSumMoney0']);
		// $('.JqSumBet').text(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nSumMoney0']);
	}
	if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nSumMoney1'] !== undefined){
		// $('.JqCoinBanker.JqSumCoin').attr('data-clip',oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nSumMoney1']);
	}
}