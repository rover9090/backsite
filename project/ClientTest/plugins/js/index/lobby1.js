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
		console.log('set'+gnGame);
		gaCheck[gnGame]['timeLobby'] = setInterval(fnAjaxLobby1, 5000,0,gnGame);
		fnAjaxLobby1(0,gnGame);
	}

	
	// $('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
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
			'nEndTime': $('input[name=sLobbyJWT]').attr('data-nEndTime'),
			'run_page': 1,
		},
		timeout:10000,
		success: function (oRes) {
			gaUser['aData'] = oRes['aUser'];
			$('input[name=aUserData]').attr('data-mute',oRes['aUser']['nMute']);
			$('.JqUserMoney').text(oRes['aUser']['nMoney']);
			LPsKey = sGame;			

			$('input[name=sLobbyJWT]').attr('data-nNoId',oRes['aData'][LPsKey]['nId']);
			$('input[name=sLobbyJWT]').attr('data-nEndTime',oRes['aData'][LPsKey]['nBankerEndTime']);

			if(oRes['aData'][LPsKey]['aLastest']['nSeconds'] > 0){
				if(gnGame === LPsKey){				
					clearInterval(gaCheck[LPsKey]['timeLobby']);
					gaCheck[gnGame]['timeLobby'] = setInterval(fnAjaxLobby1, 5000,0,gnGame,'chk1');
				}
				clearTimeout(gaCheck[LPsKey]['timeoutCount']);
				clearInterval(gaCheck[LPsKey]['timeCountInterval']);
				gaCheck[LPsKey]['timeoutCount'] = setTimeout(fnAjaxLobby1, (oRes['aData'][LPsKey]['aLastest']['nSeconds'])*1000,1,LPsKey,'chk2',1);

				gaCheck[LPsKey]['timeCountInterval'] = null;
				fnStartCountDown(oRes['aData'],LPsKey);
				$('.JqClock_'+LPsKey).removeClass('FontRed');
				$('.JqOpenNow_'+LPsKey).removeClass('active');
				$('.JqClock_'+LPsKey).text(oRes['aData'][LPsKey]['aLastest']['nSeconds']);

				if(gaCheck[gnGame]['nShowStart'] === 0){
					gaCheck[gnGame]['nShowStart'] = 1;
				}
			}
			else{
				gaCheck[gnGame]['nShowStart'] = 0;
				if(oRes['aData'][LPsKey]['aLastest']['nSeconds1'] > 0){
					clearTimeout(gaCheck[LPsKey]['timeoutCount']);
					clearInterval(gaCheck[LPsKey]['timeCountInterval']);
					gaCheck[LPsKey]['timeoutCount'] = setTimeout(fnAjaxLobby1, (oRes['aData'][LPsKey]['aLastest']['nSeconds1'])*1000,1,LPsKey,'chk3');
					gaCheck[LPsKey]['timeCountInterval'] = null;
					fnStartCountDown(oRes['aData'],LPsKey);
				}
				else{
					if(oRes['aData'][LPsKey]['aLastest']['nSeconds'] < -600 && oRes['aData'][LPsKey]['aLastest']['nSeconds'] > oRes['aData'][LPsKey]['aLastest']['nSeconds1']){
						clearInterval(gaCheck[LPsKey]['timeLobby']);
						clearTimeout(gaCheck[LPsKey]['timeoutCount']);
						clearInterval(gaCheck[LPsKey]['timeCountInterval']);
						gaCheck[LPsKey]['timeoutCount'] = null;
						gaCheck[LPsKey]['timeCountInterval'] = null;
						gaCheck[LPsKey]['timeLobby'] = setInterval(fnAjaxLobby1, 20000,1,LPsKey,'chk4');
					}
					else{
						if(oRes['aData'][LPsKey]['aLastest']['nStatus'] == 0){
							clearInterval(gaCheck[LPsKey]['timeLobby']);
							gaCheck[LPsKey]['timeLobby'] = setInterval(fnAjaxLobby1, 2000,1,LPsKey,'chk4else');
						}
						else{
						}
						
					}
				}
			}
			
			if(gnGame == LPsKey){
				
				fnCheckRoundStatus(oRes['aData'][LPsKey],LPsKey);

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

			if(oRes['aData'][LPsKey]['aSetting']['nShuffling'] == '1'){
				$('.JqShuffling_'+LPsKey).addClass('active');
			}
			else{
				$('.JqShuffling_'+LPsKey).removeClass('active');
			}

			$('.JqDealerImage').removeClass('active');

			// $('.JqCloseRoom_'+LPsKey).parents('a').attr('href',oRes['aData'][LPsKey]['aSetting']['sBetUrl']);
			// if(oRes['aData'][LPsKey]['aSetting']['nCloseRoom'] == 1 || oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 1){
			// 	if((nComing = oRes['aData'][LPsKey]['aSetting']['nOpenTime'] - oRes['nNowTime']) <= 600 &&
			// 	oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
			// 		$('.JqCloseRoom_'+LPsKey).removeClass('active');
			// 		$('.JqComingSoon_'+LPsKey).addClass('active');
			// 		// $('.JqClock_'+LPsKey).text(nComing);
			// 	}
			// 	else{
			// 		$('.JqCloseRoom_'+LPsKey).addClass('active');
			// 	}
			// 	if(oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
			// 		let sOpenTime = aJSDEFINE[sDevice+'_OPENROOM'].replace('[:TIME:]',oRes['aData'][LPsKey]['aSetting']['sOpenTime']);
			// 		$('.JqCloseRoom_'+LPsKey).find('div').text(sOpenTime);
			// 	}
			// }
			// else{
			// 	$('.JqCloseRoom_'+LPsKey).removeClass('active');
			// 	$('.JqComingSoon_'+LPsKey).removeClass('active');					
			// }


			let nNowTime = oRes['nNowTime'];
			for(let LPsKey in oRes['aData'][gnGame]['aJsonMsg']){
				if(gaJsonMsg[LPsKey] !== undefined){
					continue;
				}
				if(LPsKey > nNowTime){
					gaJsonMsg[LPsKey] = true;
					setTimeout(function(oRes,LPsKey){
						$(".JqShowArea").append(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['sMsg']);
						if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nUid'] === gaUser['aData']['nUid']){
							$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
						}
					}, LPsKey-nNowTime,oRes,LPsKey);
				}
				else{
					gaJsonMsg[LPsKey] = true;
					$(".JqShowArea").append(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['sMsg']);
					if(oRes['aData'][gnGame]['aJsonMsg'][LPsKey]['nUid'] === gaUser['aData']['nUid']){
						$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
					}
				}
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

function fnGetGrades(aNums)
{
	$aBanker = [];
	$aBankerGrade = [];
	$nBankerGrade = 0;
	$aPlayer = [];
	$aPlayerGrade = [];
	$nPlayerGrade = 0;
	$aReturn = [];
	nBankerPair = 0;
	nPlayerPair = 0;
	$aGrade = {
		'A' : 1,
		'2' : 2,
		'3' : 3,
		'4' : 4,
		'5' : 5,
		'6' : 6,
		'7' : 7,
		'8' : 8,
		'9' : 9,
		'10': 0,
		'J' : 0,
		'Q' : 0,
		'K' : 0,
		'0' : 0,
	};

	if(aNums['sBanker'] !== undefined){
		$aBanker = aNums['sBanker'].split(',');
		for(let $LPnKey in $aBanker)
		{
			if($aBanker[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardB'+($LPnKey+1).toString();
			$aBankerGrade[$LPnKey] = $aGrade[$aBanker[$LPnKey].substr(1)];
			$nBankerGrade += $aBankerGrade[$LPnKey];
			if($(sClassName).length > 0 && !$(sClassName).hasClass('spin')){
				$(sClassName+'Src').attr('src','images/card/'+$aBanker[$LPnKey]+'.png');
				$nBankerGrade %= 10;
				if($LPnKey == 2){
					$(sClassName).addClass('active');
					setTimeout(function(sClassName,$nBankerGrade){
						$(sClassName).addClass('spin');
						$('.JqBankerGrade').text($nBankerGrade);
					},1000,sClassName,$nBankerGrade);
				}
				else{
					$(sClassName).addClass('active');
					$(sClassName).addClass('spin');
					if($LPnKey == 1){
						$('.JqBankerGrade').text($nBankerGrade);
					}
				}
			}
		}
	}

	if(aNums['sPlayer'] !== undefined){
		$aPlayer = aNums['sPlayer'].split(',');
		for(let $LPnKey in $aPlayer)
		{
			if($aPlayer[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardP'+($LPnKey+1).toString();
			$aPlayerGrade[$LPnKey] = $aGrade[$aPlayer[$LPnKey].substr(1)];
			$nPlayerGrade += $aPlayerGrade[$LPnKey];
			if($(sClassName).length > 0 && !$(sClassName).hasClass('spin')){
				$(sClassName+'Src').attr('src','images/card/'+$aPlayer[$LPnKey]+'.png');
				$nPlayerGrade %= 10;
				if($LPnKey == 2){
					$(sClassName).addClass('active');
					setTimeout(function(sClassName,$nPlayerGrade){
						$(sClassName).addClass('spin');
						$('.JqPlayerGrade').text($nPlayerGrade);
					},1000,sClassName,$nPlayerGrade);
				}
				else{
					$(sClassName).addClass('active');
					$(sClassName).addClass('spin');
					if($LPnKey == 1){
						$('.JqPlayerGrade').text($nPlayerGrade);
					}
				}
			}
		}
	}

	if($nPlayerGrade > $nBankerGrade){
		$('.JqPlayerZone').addClass('active');
	}
	else if($nPlayerGrade < $nBankerGrade){
		$('.JqBankerZone').addClass('active');
		if($nBankerGrade == 6){
			$('.JqSixZone').addClass('active');
		}	
	}
	else{
		$('.JqDrawZone').addClass('active');
	}
	
	if($aBanker[1] != undefined && $aBanker[0].substr(1) == $aBanker[1].substr(1)){
		$('.JqBankerPairZone').addClass('active');
	}
	if($aPlayer[1] != undefined && $aPlayer[0].substr(1) == $aPlayer[1].substr(1)){
		$('.JqPlayerPairZone').addClass('active');
	}
}

function fnAskRoad(oRoad){
	let sRoad = JSON.stringify(oRoad);
	let aDeepCopyPW = JSON.parse(sRoad);
	let aDeepCopyBW = JSON.parse(sRoad);
	let oAskRoadPW = new cRoad();
	let oAskRoadBW = new cRoad();
	let aResultColor = {true:'Red',false:'Blue'};
	for(let LPsKey in oAskRoadPW){
		oAskRoadPW[LPsKey] = aDeepCopyPW[LPsKey];
		oAskRoadBW[LPsKey] = aDeepCopyBW[LPsKey];
	}
	oAskRoadPW.fnInsert(['PW']);
	oAskRoadBW.fnInsert(['BW']);
	nLen1 = oAskRoadPW.aRoadArray[1].length-1;
	nLen2 = oAskRoadPW.aRoadArray[2].length-1;
	nLen3 = oAskRoadPW.aRoadArray[3].length-1;
	$('.JqAskPW1,.JqAskPW2,.JqAskPW3,.JqAskBW1,.JqAskBW2,.JqAskBW3').removeClass('Blue Red BgBlue BgRed blue red');
	if(nLen1 >= 0){
		$('.JqAskPW1').addClass(aResultColor[oAskRoadPW.aRoadArray[1][nLen1].bResult]);
		$('.JqAskBW1').addClass(aResultColor[oAskRoadBW.aRoadArray[1][nLen1].bResult]);
	}
	if(nLen2 >= 0){
		$('.JqAskPW2').addClass('Bg'+aResultColor[oAskRoadPW.aRoadArray[2][nLen2].bResult]);
		$('.JqAskBW2').addClass('Bg'+aResultColor[oAskRoadBW.aRoadArray[2][nLen2].bResult]);
	}
	if(nLen3 >= 0){
		$('.JqAskPW3').addClass('Bg'+aResultColor[oAskRoadPW.aRoadArray[3][nLen3].bResult]);
		$('.JqAskBW3').addClass('Bg'+aResultColor[oAskRoadBW.aRoadArray[3][nLen3].bResult]);
	}
	
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
		$('.JqCancelRound').addClass('active');
		clearTimeout(gaCheck[LPsKey]['timeoutCount']);
		clearInterval(gaCheck[LPsKey]['timeCountInterval']);
		gaCheck[LPsKey]['timeoutCount'] = null;
		gaCheck[LPsKey]['timeCountInterval'] = null;
	}
	else if(aNums['aLastest']['nCancel'] == '2'){
		$('.JqStopBet').removeClass('active');
		$('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').addClass('active');
		clearTimeout(gaCheck[LPsKey]['timeoutCount']);
		clearInterval(gaCheck[LPsKey]['timeCountInterval']);
		gaCheck[LPsKey]['timeoutCount'] = null;
		gaCheck[LPsKey]['timeCountInterval'] = null;
	}
	else{
		$('.JqCancelRound').removeClass('active');
		$('.JqPauseRound').removeClass('active');
	}

	if(aNums['aSetting']['nShuffling'] == '1'){
		$('.JqCard').removeClass('active');
		$('.JqCard').removeClass('spin');
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

function fnGenerateChip(aBetData){
	if($('.JqPlayerBetChip').length == 0){
		let aBetCount = {'PP':0,'SIX':0,'BP':0,'PW':0,'DRAW':0,'BW':0};
		let aBetMoney = {'PP':0,'SIX':0,'BP':0,'PW':0,'DRAW':0,'BW':0};
		for(let LPsKey in aBetData){
			// if(gaBetPool[LPsKey] == undefined){
			// 	gaBetPool[LPsKey] = aBetData[LPsKey];
			// 	var sHtml = $('.JqCopyPlayerMsg').html();
			// 	sHtml = sHtml.replace('[:PLAYERNAME:]',aBetData[LPsKey]['sName0']);
			// 	sHtml = sHtml.replace('[:PLAYERNAME:]',aBetData[LPsKey]['sName0']);
			// 	sHtml = sHtml.replace('[:HEADIMG:]',aBetData[LPsKey]['sPicture']);
			// 	sHtml = sHtml.replace('[:TIME:]',fnCurrentTime('m/d H:i:s'));
			// 	$sHtml = $(sHtml);
				
			// 	// for(let i in aBetData[LPsKey]){
			// 		var sContentMsg = '<div class="[:COLOR:] JqBetMsg">[:CONTENT:][<i class="FontGreen fas fa-check"></i>[:STATUS:]][:MONEY:]</div>';
			// 		sContentMsg = sContentMsg.replace('[:COLOR:]',aBetData[LPsKey]['sColor']);
			// 		sContentMsg = sContentMsg.replace('[:CONTENT:]',aJSDEFINE[aBetData[LPsKey]['sContent0']]);
			// 		sContentMsg = sContentMsg.replace('[:STATUS:]',aJSDEFINE['SUCCESS']);
			// 		sContentMsg = sContentMsg.replace('[:MONEY:]',aBetData[LPsKey]['nMoney0']);
			// 		console.log($sHtml.find('.JqBetMsgBlock').html());
			// 		$sHtml.find('.JqBetMsgBlock').append(sContentMsg);
			// 	// }
			// 	$(".JqShowArea").append($sHtml);
			// }
		}
		for(let LPsKey in gaBetPool){
			aBetCount[gaBetPool[LPsKey]['sContent0']]++;
			aBetMoney[gaBetPool[LPsKey]['sContent0']]+=parseFloat(gaBetPool[LPsKey]['nMoney0']);
		}
		$('.JqPlayerPairMoney').text(aBetMoney['PP']);
		$('.JqSixMoney').text(aBetMoney['SIX']);
		$('.JqBankerPairMoney').text(aBetMoney['BP']);
		$('.JqPlayerWinMoney').text(aBetMoney['PW']);
		$('.JqDrawMoney').text(aBetMoney['DRAW']);
		$('.JqBankerWinMoney').text(aBetMoney['BW']);
		$('.JqPlayerPairCount').text(aBetCount['PP']);
		$('.JqSixCount').text(aBetCount['SIX']);
		$('.JqBankerPairCount').text(aBetCount['BP']);
		$('.JqPlayerWinCount').text(aBetCount['PW']);
		$('.JqDrawCount').text(aBetCount['DRAW']);
		$('.JqBankerWinCount').text(aBetCount['BW']);

	}
	// for(let LPsKey in aBetData){
	// 	if(gaBetPool[LPsKey] == undefined){
	// 		gaBetPool[LPsKey] = aBetData[LPsKey];
	// 		for(i=0;i<6;i++){
	// 			let nRandomBox = fnGetRandom(0,6);
	// 			$PutinBox = $($('.JqChip'+aBetData[LPsKey]['sContent0'])[nRandomBox]);
	// 			if($PutinBox.html() == ''){
	// 				let $Chip = $($('.JqSampleChip').html());
	// 				$Chip[2].innerText = aBetData[LPsKey]['nMoney0'];
	// 				$PutinBox.append($Chip);
	// 				$PutinBox.addClass('JqAdded');
	// 				break;
	// 			}
	// 		}			
	// 	}
	// }
}

function fnRandomChip(){
	// let aChips = [500,1000,5000,10000,20000,50000];
	let aChips = [100,200,300,500,600,1000];
	let nLen_1 = aChips.length - 1;
	// let nReturn = aChips[fnGetRandom(0,nLen_1)] * fnGetRandom(1,2) + aChips[fnGetRandom(0,nLen_1)] * fnGetRandom(0,1);
	let nReturn = aChips[fnGetRandom(0,nLen_1)];
	return nReturn;
};

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