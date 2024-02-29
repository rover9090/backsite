/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3. 
 */
var gaRoomCounting = {};
var aNumsRoad = {};
var aNumsArray = JSON.parse($('.JqaNumsArray').val());
var aNumsCounting = JSON.parse($('.JqaNumsCounting').val());
var gnGame = $('.JqnGame').val();
$(document).ready(function () {
	$('.JqToggleHeader').on('click' , function()
	{
		$('.JqHeader').toggle();
		$('.JqNavContentContainer').toggleClass('active');
	});
	// var aNumsArray = {}; // aNumsArray = 房間=>歷屆期數資料=>資料
	for(var LPsKey in aNumsArray){
		aNumsRoad[LPsKey] = new cRoad();
		gaRoomCounting[LPsKey] = false;
		fnDrawBoard(aNumsArray[LPsKey],LPsKey);
		for(var LPsKey2 in aNumsArray[LPsKey]){
			aNumsRoad[LPsKey].fnInsert(aNumsArray[LPsKey][LPsKey2]['aResult']);
		}
		aNumsRoad[LPsKey]['aPrintRoad0'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aNumberArray,6);

		aNumsRoad[LPsKey]['aPrintRoad1'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[1],6);
		aNumsRoad[LPsKey]['aPrintRoad2'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[2],6);
		aNumsRoad[LPsKey]['aPrintRoad3'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[3],6);
		// 大路一定都會出 用大路判斷
		if($('.JqRoad0_'+LPsKey+'_sub').length > 0){
			fnDrawRoad(aNumsRoad[LPsKey],LPsKey,1);
		}
		if($('.JqRoad0_'+LPsKey).length > 0){
			fnDrawRoad(aNumsRoad[LPsKey],LPsKey);
		}
		if(LPsKey == gnGame){
			fnAskRoad(aNumsRoad[LPsKey]);
			fnGameSetRoadCss();
		}
	}
	// fnAjaxLobby();
	// timeLobby = setInterval(fnAjaxLobby, 2000);

	//////////////////////////

	//////////////////////////
});

function fnDrawBoard(aNumsArray,LPsKey,aDebug={}){
	let nWidth = $('.JqBoardWidth_0').val();
	let nBoxLen = nWidth * 6;
	let nLen = Object.keys(aNumsArray).length;// 0-72 最近累積的期數
	let i;
	let aNumsCounting = {"BW":0,"PW":0,"DRAW":0,"BP":0,"PP":0};
	let nBoardLen = nWidth * 6;
	if(nLen <= (nBoxLen-6)){
		i=0;
	}
	else{
		i = Math.ceil((nLen-(nBoxLen-6))/6)*6;// x軸永遠預留一格
	}
	tmp = i;
	for(let j=0;j<nBoardLen;j++){
		$('.JqBoard_'+LPsKey+'.JqNo'+j).html('');		
	}	
	for(let j=0;i<nLen;i++,j++){
		if(aNumsArray[i] !== undefined){
			for(let LPsKey2 in aNumsArray[i]['aResult']){
				$('.JqBoard_'+LPsKey+'.JqNo'+j).append(aBoardCell[aNumsArray[i]['aResult'][LPsKey2]]);
			}
		}
	}
	for(let i=0;i<nLen;i++){
		if(aNumsArray[i] === undefined){
			console.log('debug',aDebug,i);
			console.log(aNumsArray);
		}
		for(let LPsKey2 in aNumsArray[i]['aResult']){ // Cannot read property 'aResult' of undefined from 192
			aNumsCounting[aNumsArray[i]['aResult'][LPsKey2]]++;
		}
	}
	
	$('.JqBankerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"]);
	$('.JqPlayerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PW"]);
	$('.JqPlayerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PP"]);
	$('.JqBankerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BP"]);
	$('.JqDraw[data-ngame="'+LPsKey+'"]').text(aNumsCounting["DRAW"]);
	$('.JqGamesAll[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"] + aNumsCounting["PW"] + aNumsCounting["DRAW"]);
}

function fnDrawRoad(aPrintRoad,LPsKey,nSub=0){
	let nLen,nWidth,nDelta,sSub;
	sSub = (nSub==0)?'':'_sub';
	nLen = aPrintRoad['aPrintRoad0']['aPrintRoad'].length;
	nWidth = $('.JqRoad0Width_'+nSub).val();
	nDelta = Object.keys(aPrintRoad['aPrintRoad0']['aPrintBoard']).length - nWidth + 1;
	if(nDelta < 0){
		nDelta = 0;
	}
	for(let i=0;i<nWidth;i++){
		for(let j=0;j<6;j++){
			$('.JqRoad0_'+LPsKey+sSub+'.x'+i+'.y'+j).html('');
		}
	}
	for(let i=0;i<nLen;i++){
		if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['draw'] == true)
		{
			$('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['DRAW']);
		}
		else if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['bResult'] == true){
			$('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['BW']);
		}
		else if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['bResult'] == false){
			$('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['PW']);
		}
		if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['BP'] !== undefined){
			$('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['BP']);
		}
		if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['PP'] !== undefined){
			$('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['PP']);
		}		
	}

	if(nSub == 1){
		return;
	}

	nLen = aPrintRoad['aPrintRoad1']['aPrintRoad'].length;
	nWidth = $('.JqRoad1Width_'+nSub).val();
	nDelta = Object.keys(aPrintRoad['aPrintRoad1']['aPrintBoard']).length - nWidth + 1;
	if(nDelta < 0){
		nDelta = 0;
	}
	for(let i=0;i<nWidth;i++){
		for(let j=0;j<6;j++){
			$('.JqRoad1_'+LPsKey+'.x'+i+'.y'+j).html('');
		}
	}
	for(let i=0;i<nLen;i++){
		if(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['bResult'] == true){
			$('.JqRoad1_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['y']).append(aRoad1Cell['BW']);
		}
		else if(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['bResult'] == false){
			$('.JqRoad1_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['y']).append(aRoad1Cell['PW']);
		}	
	}

	nLen = aPrintRoad['aPrintRoad2']['aPrintRoad'].length;
	nWidth = $('.JqRoad2Width_'+nSub).val();
	nDelta = Object.keys(aPrintRoad['aPrintRoad2']['aPrintBoard']).length - nWidth + 1;
	if(nDelta < 0){
		nDelta = 0;
	}
	for(let i=0;i<nWidth;i++){
		for(let j=0;j<6;j++){
			$('.JqRoad2_'+LPsKey+'.x'+i+'.y'+j).html('');
		}
	}
	for(let i=0;i<nLen;i++){
		if(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['bResult'] == true){
			$('.JqRoad2_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['y']).append(aRoad2Cell['BW']);
		}
		else if(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['bResult'] == false){
			$('.JqRoad2_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['y']).append(aRoad2Cell['PW']);
		}	
	}

	nLen = aPrintRoad['aPrintRoad3']['aPrintRoad'].length;
	nWidth = $('.JqRoad3Width_'+nSub).val();
	nDelta = Object.keys(aPrintRoad['aPrintRoad3']['aPrintBoard']).length - nWidth + 1;
	if(nDelta < 0){
		nDelta = 0;
	}
	for(let i=0;i<nWidth;i++){
		for(let j=0;j<6;j++){
			$('.JqRoad3_'+LPsKey+'.x'+i+'.y'+j).html('');
		}
	}
	for(let i=0;i<nLen;i++){
		if(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['bResult'] == true){
			$('.JqRoad3_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['y']).append(aRoad3Cell['BW']);
		}
		else if(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['bResult'] == false){
			$('.JqRoad3_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['y']).append(aRoad3Cell['PW']);
		}	
	}
}

function fnAjaxLobby(){
	return;
	let sUrl = $('input[name=sLobbyJWT]').attr('data-url');
	let sJWT = $('input[name=sLobbyJWT]').val();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		data: {
			'sJWT': sJWT,
			'nGame': gnGame,
			'run_page': 1,
		},
		success: function (oRes) {
			for(var LPsKey in oRes.aData){// LPsKey = 房間號碼				
				if(oRes.aData[LPsKey]['sNo'] == undefined && oRes.aData[LPsKey]['aSetting']['nShuffling'] == 1){
					aNumsArray[LPsKey] = {};
					aNumsRoad[LPsKey] = new cRoad();
					fnResetRoad(LPsKey);
				}
				if(oRes.aData[LPsKey]['sResult'] !== '' && oRes.aData[LPsKey]['nRound'] != undefined && aNumsArray[LPsKey][oRes.aData[LPsKey]['nRound']] == undefined){
					aNumsArray[LPsKey][oRes.aData[LPsKey]['nRound']] = oRes.aData[LPsKey];

					fnDrawBoard(aNumsArray[LPsKey],LPsKey,oRes.aData[LPsKey]);// Cannot read property 'aResult' of undefined
					aNumsRoad[LPsKey].fnInsert(oRes.aData[LPsKey]['aResult']);
					
					aNumsRoad[LPsKey]['aPrintRoad0'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aNumberArray);

					aNumsRoad[LPsKey]['aPrintRoad1'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[1]);
					aNumsRoad[LPsKey]['aPrintRoad2'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[2]);
					aNumsRoad[LPsKey]['aPrintRoad3'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[3]);
					if($('.JqRoad0_'+LPsKey+'_sub').length > 0){
						fnDrawRoad(aNumsRoad[LPsKey],LPsKey,1);
					}
					// 疑似上下對調就會爆炸 有空再實驗
					if($('.JqRoad0_'+LPsKey).length > 0){
						fnDrawRoad(aNumsRoad[LPsKey],LPsKey);
					}
					
					if(gnGame == LPsKey){
						fnAskRoad(aNumsRoad[LPsKey]);
						$('.JqBetBtn').text(0);
						fnGameSetRoadCss();
					}
				}

			}
		},
		error: function (exception) {
			console.log('Exeption:' + exception.responseText);
		}
	});
}
function fnGameSetRoadCss(){
	var nMainWid = $('.JqRoadOuterBox').width()/parseInt($('.JqRoadOuterBox').attr('data-col'));

	$('.JqMainRoad').css({'height':(nMainWid)+'px','width':(nMainWid)+'px'}); //棋盤路
	$('.JqResultBox').css({'height':(nMainWid-2)+'px','width':(nMainWid-2)+'px'}); // 文字框

	// 文字大小
	if(nMainWid < 23)
	{
		$('.JqResultWord').css('font-size',(nMainWid-5)+"px");
	}

	$('.JqRoadOuterBox').find('.JqBigRoad').css({'height':(nMainWid)+'px','width':(nMainWid)+'px'}); // 大路
	$('.JqRoadOuterBox').find('.JqBigPattern').css({'height':nMainWid+'px','width':nMainWid+'px'}); // 大路圖案

	$('.JqPatternRoad').css({'height':(nMainWid)+'px','width':(nMainWid)+'px'}); // 圖案格子

	// 四分之一格
	$('.JqRoadSep').find('.JqSmallPattern').css({'height':((nMainWid-2)/2)+'px','width':((nMainWid-2)/2)+'px'});
	$('.JqRoadSep').css('height',(nMainWid-2)/2+'px');

	// 閒莊路
	var nAskHeight = parseInt($('.JqAskBlock').height());
	$('.JqAskBlock').css('padding',((nMainWid*6 - nAskHeight + 7)/2)+"px 10px");

	////////////// 共同擁有 //////////////
	// 點
	$.each($('.JqGameDot'),function(i,dot)
	{
		var nDotVal = parseInt($(dot).parent('.JqRoadItem').height());
		if(nDotVal/2.5 <= 5)
		{
			$(dot).css({'height':'5px','width':'5px','border-width':'1px'});
		}
		else
		{
			$(dot).css({'height':(nDotVal/2.5)+'px','width':(nDotVal/2.5)+'px'});
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

function fnResetRoad(nGame){
	fnDrawBoard({},nGame);
	let aLNumsRoad = {};
	aLNumsRoad['aPrintRoad0'] = {'aPrintRoad':{},'aPrintBoard':{}};
	aLNumsRoad['aPrintRoad1'] = {'aPrintRoad':{},'aPrintBoard':{}};
	aLNumsRoad['aPrintRoad2'] = {'aPrintRoad':{},'aPrintBoard':{}};
	aLNumsRoad['aPrintRoad3'] = {'aPrintRoad':{},'aPrintBoard':{}};
	fnDrawRoad(aLNumsRoad,nGame);	
	$('.JqAskPW1,.JqAskPW2,.JqAskPW3,.JqAskBW1,.JqAskBW2,.JqAskBW3').removeClass('Blue Red BgBlue BgRed blue red');
}