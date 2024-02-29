/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3.
 */
var aNumsRoad = {};
var aNumsArray = JSON.parse($('.JqaNumsArray').val());
var aNumsCounting = JSON.parse($('.JqaNumsCounting').val());
var gnGame = $('.JqnGame').val();
var gaCheck = {};

$('.JqHtml').addClass('hideScrollbar');

$(document).ready(function () {
	timeLobby = setInterval(fnAjaxLobby, 1000);
	for(var LPsKey in aNumsArray){
		gaCheck[LPsKey] = {};
		gaCheck[LPsKey]['nWhiteTimer'] = 0; // 紅色倒數
		gaCheck[LPsKey]['nRedTimer'] = 0; // 紅色倒數
		gaCheck[LPsKey]['nStopNow'] = 0; // 全部倒數一起停止
	}
	$('.JqToggleHeader').on('click' , function()
	{
		$('.JqHeader').toggle();
		$('.JqNavContentContainer').toggleClass('active');
	});

	// setTimeout(function(){ location.reload(); }, 2*60*1000);

	// if(parseInt(gnGame) >= 1003){
		var player =  new TcPlayer('video-player', {
			"m3u8": "https://vt.abp77.com/live/test"+gnGame+".m3u8",
			"flv": "https://vt.abp77.com/live/test"+gnGame+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
			"webrtc":"webrtc://vt.abp77.com/live/test"+gnGame,
			"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
			"poster" : "http://www.test.com/myimage.jpg",
			// "width" :  '480',//视频的显示宽度，请尽量使用视频分辨率宽度
			// "height" : '320'//视频的显示高度，请尽量使用视频分辨率高度
		});
		player.mute(true)
	// }
	//////////////////////////

	//////////////////////////
	window.addEventListener('storage', reloadOnTenantChange, false);

});

// 偵測當我localstorage變換時
function reloadOnTenantChange(event) {
	// console.log(event.oldValue);
	if (event.key == "sJson"+gnGame && event.oldValue != event.newValue) {
		let sNewRes = event.newValue;
		let oRes = JSON.parse(sNewRes);
		if(oRes['aLastest']['nSeconds'] >= 0){
			$('.JqClock_'+gnGame).removeClass('FontRed');
			$('.JqClock_'+gnGame).text(oRes['aLastest']['nSeconds']);
			fnOpenBet();			
		}
		else{
			if(oRes['aLastest']['nSeconds1'] < 0){
				$('.JqClock_'+gnGame).text(0);
			}
		}
		if(oRes['aLastest']['nSeconds1'] > 0){
			gaCheck[gnGame]['nRedTimer'] = 1;
			$('.JqClock_'+gnGame).addClass('FontRed');
			$('.JqClock_'+gnGame).text(oRes['aLastest']['nSeconds1']);
		}

		if(oRes['aLastest']['nSeconds'] <= 0){// && !$('.JqFinalBox').hasClass('active')
			fnCloseBet();
		}
		fnCheckRoundStatus2(oRes['aLastest'],gnGame,oRes['aSetting']);

		$('.JqNo').text(oRes['aLastest']['sNo']);
		if(oRes['aLastest']['nRound'] == undefined){
			$('.JqRound').text(0);
		}
		else{
			$('.JqRound').text(parseInt(oRes['aLastest']['nRound'])+1);
			}
		

		if(oRes['aSetting']['nShuffling'] == '1'){
			$('.JqShuffling_'+gnGame).addClass('active');
		}
		else{
			$('.JqShuffling_'+gnGame).removeClass('active');
		}

		$('.JqNOWDATE').text(oRes['aSetting']['sNowDate']);
		$('.JqMembersCount').text(Object.keys(oRes['aMemberReal']).length);
	}
}

function fnAjaxLobby(){
	let sNewRes = localStorage.getItem("sJson"+gnGame);
	let oRes = JSON.parse(sNewRes);
	if(sNewRes !== undefined){
		return;
		if(oRes['aLastest']['nSeconds'] >= 0){
			$('.JqClock_'+gnGame).removeClass('FontRed');
			$('.JqClock_'+gnGame).text(oRes['aLastest']['nSeconds']);
			fnOpenBet();			
		}
		else{
			if(oRes['aLastest']['nSeconds1'] < 0){
				$('.JqClock_'+gnGame).text(0);
			}
		}
		if(oRes['aLastest']['nSeconds1'] > 0){
			gaCheck[gnGame]['nRedTimer'] = 1;
			$('.JqClock_'+gnGame).addClass('FontRed');
			$('.JqClock_'+gnGame).text(oRes['aLastest']['nSeconds1']);
		}

		if(oRes['aLastest']['nSeconds'] <= 0){// && !$('.JqFinalBox').hasClass('active')
			fnCloseBet();
		}
		fnCheckRoundStatus2(oRes['aLastest'],gnGame,oRes['aSetting']);

		$('.JqNo').text(oRes['aLastest']['sNo']);
		if(oRes['aLastest']['nRound'] == undefined){
			$('.JqRound').text(0);
		}
		else{
			$('.JqRound').text(parseInt(oRes['aLastest']['nRound'])+1);
			}
		

		if(oRes['aSetting']['nShuffling'] == '1'){
			$('.JqShuffling_'+gnGame).addClass('active');
		}
		else{
			$('.JqShuffling_'+gnGame).removeClass('active');
		}

		$('.JqNOWDATE').text(oRes['aSetting']['sNowDate']);
		$('.JqMembersCount').text(Object.keys(oRes['aMemberReal']).length);
	}
	else{
		let sUrl = $('input[name=sLobbyJWT]').attr('data-url1');
		let sJWT = $('input[name=sLobbyJWT]').val();
		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			timeout:1500,
			data: {
				'sJWT': sJWT,
				'nGame': gnGame,
				'run_page': 1,
			},
			success: function (oRes) {
				for(var LPsKey in oRes['aData']){// LPsKey = 房間號碼
					// console.log(oRes['aData'][LPsKey]['nSeconds'],oRes['aData'][LPsKey]['nSeconds1']);
					// if(oRes['aData'][LPsKey]['nSeconds'] > 0 &&
					// 	gaCheck[LPsKey]['nWhiteTimer'] == 0){
					// 	gaCheck[LPsKey]['nWhiteTimer'] = 1;
					// 	fnClockCountDown(LPsKey,oRes['aData'][LPsKey]['nSeconds']);
					// 	if(gnGame == LPsKey){
					// 		fnOpenBet();
					// 	}
					// }
					// if(oRes['aData'][LPsKey]['nSeconds1'] > 0 &&
					// 	gaCheck[LPsKey]['nRedTimer'] == 0){
					// 	gaCheck[LPsKey]['nRedTimer'] = 1;
					// 	$('.JqClock_'+gnGame).addClass('FontRed');
					// 	fnClockCountDown(LPsKey,oRes.aData[LPsKey]['nSeconds1'],1);
					// }
					if(oRes['aData'][LPsKey]['nSeconds'] >= 0){
						$('.JqClock_'+LPsKey).removeClass('FontRed');
						$('.JqClock_'+LPsKey).text(oRes.aData[LPsKey]['nSeconds']);
						if(gnGame == LPsKey){
							fnOpenBet();
						}
					}
					else{
						if(oRes['aData'][LPsKey]['nSeconds1'] < 0){
							$('.JqClock_'+LPsKey).text(0);
						}
					}
					if(oRes['aData'][LPsKey]['nSeconds1'] > 0){
						gaCheck[LPsKey]['nRedTimer'] = 1;
						$('.JqClock_'+LPsKey).addClass('FontRed');
						$('.JqClock_'+LPsKey).text(oRes.aData[LPsKey]['nSeconds1']);
					}
					if(gnGame == LPsKey){
						if(oRes['aData'][LPsKey]['nSeconds'] <= 0){// && !$('.JqFinalBox').hasClass('active')
							fnCloseBet();
						}
						fnCheckRoundStatus(oRes.aData[LPsKey],LPsKey);
	
						$('.JqNo').text(oRes.aData[LPsKey]['sNo']);
						if(oRes.aData[LPsKey]['nRound'] == undefined){
							$('.JqRound').text(0);
						}
						else{
							$('.JqRound').text(parseInt(oRes.aData[LPsKey]['nRound'])+1);
						}
					}
	
					if(oRes.aData[LPsKey]['aSetting']['nShuffling'] == '1'){
						$('.JqShuffling_'+LPsKey).addClass('active');
					}
					else{
						$('.JqShuffling_'+LPsKey).removeClass('active');
					}
	
					$('.JqNOWDATE').text(oRes.aData[LPsKey]['aSetting']['sNowDate']);
					$('.JqMembersCount').text(oRes.aData[LPsKey]['nMembersCount']);
				}
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

	
}

//
function fnCloseBet(){
	$('.JqStopBet').addClass('active');
	// $('.JqCard').css('transform','rotateY(0deg)');
	// $('.JqCardP3').css('transform','rotateZ(90deg)');
	// $('.JqCardB3').css('transform','rotateZ(90deg)');
	$('.JqClock').removeClass('active');
	$('.JqFinalBox').addClass('active');
	$('.JqPlayerGrade').text(0);
	$('.JqBankerGrade').text(0);
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


function fnClockCountDown(nGame,nSecond,nSettle=0){
	if(gaCheck[nGame]['nStopNow'] == 1){
		gaCheck[nGame]['nWhiteTimer'] = 0;
		gaCheck[nGame]['nRedTimer'] = 0;
		gaCheck[nGame]['nStopNow'] = 0;
		$('.JqClock_'+nGame).removeClass('FontRed');
		$('.JqClock_'+nGame).text(0);
		return;
	}
	if(nSettle == 0){
		if(nSecond < 0 || gaCheck[nGame]['nWhiteTimer'] == 0){
			if(nGame==gnGame){
				$('.JqStopBet').addClass('active');
				$('.JqClock').removeClass('active');
				$('.JqCard').addClass('active');
				$('.JqFinalBox').addClass('active');
			}
			$('.JqOpenNow_'+nGame).addClass('active');// 開牌中
			gaCheck[nGame]['nWhiteTimer'] = 0;
			return;
		}
		$('.JqOpenNow_'+nGame).removeClass('active');
		$('.JqClock_'+nGame).text(nSecond);
	}
	else{
		if(nSecond < 0 || gaCheck[nGame]['nRedTimer'] == 0 || gaCheck[nGame]['nWhiteTimer'] == 1){
			gaCheck[nGame]['nRedTimer'] = 0;
			$('.JqClock_'+nGame).removeClass('FontRed');
			return;
		}
		$('.JqClock_'+nGame).text(nSecond);
	}
	setTimeout(fnClockCountDown,1000,nGame,nSecond-1,nSettle);
}

function fnCheckRoundStatus(aNums,nGame){
	if(aNums['nFinalRound'] == '1' || aNums['nDayLast'] == '1'){
		$('.JqFinalRound').addClass('active');
	}else{
		$('.JqFinalRound').removeClass('active');
	}

	if(parseInt(aNums['nDayLast']) > 0){
		$('.JqDayLastBlock').addClass('active');
		if(aNums['nDayLast'] == '1' && aNums['sNums'] != ''){
			$('.JqDayLast').text(0);
		}
		else{
			$('.JqDayLast').text(parseInt(aNums['nDayLast']));
		}
	}else{
		$('.JqDayLastBlock').removeClass('active');
	}

	if(aNums['nCancel'] == '1'){
		$('.JqStopBet').removeClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nWhiteTimer'] = 0;
		gaCheck[nGame]['nRedTimer'] = 0;
	}
	else if(aNums['nCancel'] == '2'){
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').addClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nWhiteTimer'] = 0;
		gaCheck[nGame]['nRedTimer'] = 0;
	}
	else{
		$('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').removeClass('active');
	}

	if(aNums['aSetting']['nShuffling'] == '1'){
		$('.JqShuffling').addClass('active');
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').removeClass('active');
		$('.JqCancelRound').removeClass('active');
		$('.JqPlayerGrade').text(0);
		$('.JqBankerGrade').text(0);
		$('.JqFinalBox').removeClass('active');
	}
	else{
		$('.JqShuffling').removeClass('active');
	}
}

function fnCheckRoundStatus2(aNums,nGame,aSetting){
	if(aNums['nFinalRound'] == '1' || aNums['nDayLast'] == '1'){
		$('.JqFinalRound').addClass('active');
	}else{
		$('.JqFinalRound').removeClass('active');
	}

	if(parseInt(aNums['nDayLast']) > 0){
		$('.JqDayLastBlock').addClass('active');
		if(aNums['nDayLast'] == '1' && aNums['sNums'] != ''){
			$('.JqDayLast').text(0);
		}
		else{
			$('.JqDayLast').text(parseInt(aNums['nDayLast']));
		}
	}else{
		$('.JqDayLastBlock').removeClass('active');
	}

	if(aNums['nCancel'] == '1'){
		$('.JqStopBet').removeClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nWhiteTimer'] = 0;
		gaCheck[nGame]['nRedTimer'] = 0;
	}
	else if(aNums['nCancel'] == '2'){
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').addClass('active');
		$('.JqCancelRound').addClass('active');
		gaCheck[nGame]['nWhiteTimer'] = 0;
		gaCheck[nGame]['nRedTimer'] = 0;
	}
	else{
		$('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').removeClass('active');
	}

	if(aSetting['nShuffling'] == '1'){
		$('.JqShuffling').addClass('active');
		$('.JqStopBet').removeClass('active');
		$('.JqPauseRound').removeClass('active');
		$('.JqCancelRound').removeClass('active');
		$('.JqPlayerGrade').text(0);
		$('.JqBankerGrade').text(0);
		$('.JqFinalBox').removeClass('active');
	}
	else{
		$('.JqShuffling').removeClass('active');
	}
}