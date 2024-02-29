/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3. 
 */
var gaRoomCounting = {};
var aNumsRoad = {};
var gnGame = $('.JqnGame').val();
var aCardStatus = {'nCleaned':0};
var timeoutCount = null;
var timeCountInterval = null;
var timeCountInterval1 = null;
var timeLobby = null;
$(document).ready(function () {
	sessionStorage.setItem('nCount',0);

	// fnAjaxResult();
	timeLobby = setInterval(fnAjaxResult, 1000);
	timeIfPause = setInterval(fnAjaxIfPause, 5000);

	if(localStorage.getItem('sToggleHint') !== null){
		$('.JqToggleHint').addClass('active');
		$('.JqToggleHint').find('span:nth-child(1)').removeClass('active');
		$('.JqToggleHint').find('span:nth-child(2)').addClass('active');
	}

	$('.JqToggleHint').on('click' , function()
	{
		if($(this).hasClass('active')){
			localStorage.removeItem('sToggleHint');
			$(this).removeClass('active');
			$(this).find('span:nth-child(2)').removeClass('active');
			$(this).find('span:nth-child(1)').addClass('active');			
			$('.JqCardP2').removeClass('active');
			$('.JqCardB2').removeClass('active');
		}
		else{
			localStorage.setItem('sToggleHint','1');
			$(this).addClass('active');
			$(this).find('span:nth-child(1)').removeClass('active');
			$(this).find('span:nth-child(2)').addClass('active');
		}
	});

	$('.JqToggleRead').on('click' , function()
	{
		if(timeLobby === null){
			timeLobby = setInterval(fnAjaxResult, 1000);
		}
		if($('.JqToggleRead').is(":checked")){
			clearTimeout(timeoutCount);	
			timeoutCount = null;
			clearInterval(timeCountInterval);
			timeCountInterval = null;
		}
	});

	setInterval(function(){
		let oDate = new Date();
		//獲取當前年
		let nYear=oDate.getFullYear();
		//獲取當前月
		let nMonth=(oDate.getMonth()+1).toString();//月份記得+1
		nMonth = nMonth.length>1?nMonth:'0'+nMonth;
		//獲取當前日
		let nDate=oDate.getDate().toString();
		nDate = nDate.length>1?nDate:'0'+nDate;
		let nH=oDate.getHours().toString();	//獲取當前小時數(0-23)
		nH = nH.length>1?nH:'0'+nH;
		let ni=oDate.getMinutes().toString();	//獲取當前分鐘數(0-59)
		ni = ni.length>1?ni:'0'+ni;
		var ns=oDate.getSeconds().toString();
		ns = ns.length>1?ns:'0'+ns;
		$('.JqNOWDATE').text(nYear+'-'+nMonth+'-'+nDate+' '+nH+':'+ni+':'+ns);
	}, 1000);

	// setTimeout(function(){ location.reload(); }, 10*60*1000);
});

function fnAjaxIfPause(){
	if($('.JqToggleRead').is(":checked")){
		return;
	}
	let sUrl = $('input[name=sResultJWT]').attr('data-url');
	let sJWT = $('input[name=sResultJWT]').val();
	let nGame = $('.JqnGame').val();
	let nRead = $('.JqnRead').val();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1500,
		data: {
			'sJWT': sJWT,
			'nGame': nGame,
			'nRead':nRead,
			'run_page': 1,
		},
		success: function (oRes) {
			if(oRes['aData']['aLastest']['nPauseTime0'] > 0){
				clearInterval(timeCountInterval);
				clearTimeout(timeoutCount);	
				timeoutCount = null;
				timeCountInterval = null;
				if(timeLobby === null){
					timeLobby = setInterval(fnAjaxResult, 1000);
				}
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

function fnStartCountDown(aData){
	clearInterval(timeCountInterval);
	clearInterval(timeCountInterval1);
	timeCountInterval = setInterval(function (aData) {// 用object就能pass ref
		aData['aLastest']['nSeconds']--;
		aData['aLastest']['nSeconds1']--;
		if (aData['aLastest']['nSeconds'] < 0) {
			if(sessionStorage.getItem('nCount') >= 5){
				sessionStorage.setItem('nCount',0);
				location.reload();
				return;
			}
			let nCount = parseInt(sessionStorage.getItem('nCount'))+1;
			sessionStorage.setItem('nCount',nCount.toString());
			clearInterval(timeCountInterval);
			aData = null;
			timeCountInterval = null;
			return;
		}
		localStorage.setItem("sJson"+gnGame, JSON.stringify(aData));
	}, 1000,aData);
}

function fnStartCountDown1(aData){
	clearInterval(timeCountInterval);
	clearInterval(timeCountInterval1);
	timeCountInterval1 = setInterval(function (aData) {// 用object就能pass ref
		aData['aLastest']['nSeconds']--;
		aData['aLastest']['nSeconds1']--;
		if (aData['aLastest']['nSeconds1'] <= 0) {
			clearInterval(timeCountInterval1);
			aData = null;
			timeCountInterval1 = null;
			return;
		}
		localStorage.setItem("sJson"+gnGame, JSON.stringify(aData));
	}, 1000,aData);
}

function fnAjaxResult(nRetry=0){
	let sUrl = $('input[name=sResultJWT]').attr('data-url');
	let sJWT = $('input[name=sResultJWT]').val();
	let nRead = $('.JqnRead').val();
	if(nRetry == 1){
		timeoutCount = null;
	}
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1500,
		data: {
			'sJWT': sJWT,
			'nGame': gnGame,
			'nRead':nRead,
			'run_page': 1,
		},
		success: function (oRes) {

			// 存入localStorage
			localStorage.setItem("sJson"+gnGame, JSON.stringify(oRes['aData']));

			aNums = oRes['aData']['aLastest'];
			aHint = oRes['aData']['aLastest']['aHint'];
			if($('.JqToggleHint').hasClass('active')){
				if(aHint != undefined && aHint['nPlayer'] == 1){
					$('.JqCardP2').addClass('active');
				}
				else{
					$('.JqCardP2').removeClass('active');
				}
				if(aHint != undefined && aHint['nBanker'] == 1){
					$('.JqCardB2').addClass('active');
				}
				else{
					$('.JqCardB2').removeClass('active');
				}
			}

			if(oRes['aData']['aLastest']['nSeconds'] <= 0){
				clearInterval(timeIfPause);
				timeIfPause = null;
				if(oRes['aData']['aLastest']['nSeconds1'] <= 0){
					if(timeLobby === null){
						timeLobby = setInterval(fnAjaxResult, 1000);
					}
				}
				else{
					clearTimeout(timeoutCount);	
					clearInterval(timeLobby);
					timeLobby = null;
					fnStartCountDown1(oRes['aData']);
					timeoutCount = setTimeout(fnAjaxResult, (oRes['aData']['aLastest']['nSeconds1'])*1000,1);
				}
				if(aNums['sNums'] === ''){
					aNums['aShowNums'] = aNums['aPreNums'];
					aNums['sShowNums'] = aNums['sPreNums'];
				}
				else{
					aNums['aShowNums'] = aNums['aNums'];
					aNums['sShowNums'] = aNums['sNums'];
				}
				if(aNums['aShowNums'] != undefined){
					aNums['aShowNums']['aBanker'] = aNums['aShowNums']['sBanker'].split(',');
					aNums['aShowNums']['aPlayer'] = aNums['aShowNums']['sPlayer'].split(',');
		
					for(let i=0;i<3;i++){
						if(aNums['aShowNums']['aBanker'][i] !== undefined && aNums['aShowNums']['aBanker'][i] !== ''){
							$('.JqCardB'+i+'Src').attr('src','images/card/'+aNums['aShowNums']['aBanker'][i]+'.png');
						}else{
							$('.JqCardB'+i+'Src').attr('src','images/card/back.png');
						}
					}
		
					for(let i=0;i<3;i++){
						if(aNums['aShowNums']['aPlayer'][i] !== undefined && aNums['aShowNums']['aPlayer'][i] !== ''){
							$('.JqCardP'+i+'Src').attr('src','images/card/'+aNums['aShowNums']['aPlayer'][i]+'.png');
						}else{
							$('.JqCardP'+i+'Src').attr('src','images/card/back.png');
						}
					}
		
					$('input[name=sResult0]').val(aNums['aShowNums']['sBanker']);
					$('input[name=sResult1]').val(aNums['aShowNums']['aPlayer']);
				}
				else{				
					for(let i=0;i<3;i++){
						sClassName1 = '.JqCardB'+(i).toString();
						sClassName2 = '.JqCardP'+(i).toString();
						$(sClassName1+'Src,'+sClassName2+'Src').attr('src','images/card/back.png');
					}
				}
				$('.JqNo').text(oRes['aData']['aLastest']['sNo']);
				$('.JqNo').text(oRes['aData']['aLastest']['sNo']);
				if(oRes['aData']['aLastest']['nRound'] == undefined){
					$('.JqRound').text(0);
				}
				else{
					$('.JqRound').text(parseInt(oRes['aData']['aLastest']['nRound'])+1);
				}
			}
			else{			
				if(!$('.JqToggleRead').is(":checked")){
					if(timeIfPause === null){
						timeIfPause = setInterval(fnAjaxIfPause, 5000);
					}
					clearInterval(timeLobby);
					fnStartCountDown(oRes['aData']);
					clearTimeout(timeoutCount);
					timeoutCount = setTimeout(fnAjaxResult, (oRes['aData']['aLastest']['nSeconds'])*1000,1);
					timeLobby = null;
				}
				$('.JqCardP0Src').attr('src','images/card/back.png');
				$('.JqCardP1Src').attr('src','images/card/back.png');
				$('.JqCardP2Src').attr('src','images/card/back.png');
				$('.JqCardB0Src').attr('src','images/card/back.png');
				$('.JqCardB1Src').attr('src','images/card/back.png');
				$('.JqCardB2Src').attr('src','images/card/back.png');
			}
			
			// $('.JqNOWDATE').text(oRes['aData']['sNowDate']);

			// 重畫
			aNumsArray[gnGame] = oRes['aData']['aNumsArray'];

			aNumsRoad[gnGame] = new cRoad();
			gaRoomCounting[gnGame] = false;
			fnDrawBoard(aNumsArray[gnGame],gnGame);
			for(var LPsKey2 in aNumsArray[gnGame]){
				aNumsRoad[gnGame].fnInsert(aNumsArray[gnGame][LPsKey2]['aResult']);
			}
			aNumsRoad[gnGame]['aPrintRoad0'] = cRoad.fnDrawPic(aNumsRoad[gnGame].aNumberArray,6);

			aNumsRoad[gnGame]['aPrintRoad1'] = cRoad.fnDrawPic(aNumsRoad[gnGame].aRoadArray[1],6);
			aNumsRoad[gnGame]['aPrintRoad2'] = cRoad.fnDrawPic(aNumsRoad[gnGame].aRoadArray[2],6);
			aNumsRoad[gnGame]['aPrintRoad3'] = cRoad.fnDrawPic(aNumsRoad[gnGame].aRoadArray[3],6);
			// 大路一定都會出 用大路判斷
			if($('.JqRoad0_'+gnGame+'_sub').length > 0){
				fnDrawRoad(aNumsRoad[gnGame],gnGame,1);
			}
			if($('.JqRoad0_'+gnGame).length > 0){
				fnDrawRoad(aNumsRoad[gnGame],gnGame);
			}
			fnAskRoad(aNumsRoad[gnGame]);
			fnGameSetRoadCss();
		},
		error: function (exception) {
			if(exception.statusText == 'timeout'){
				console.log('timeout');
				if(nRetry == 1){
					fnAjaxResult(1);
				}
			}
			else{
				console.log('Exeption:' + exception.responseText);
			}
		}
	});
}

function fnGetGrades($aNums)
{
	$aBanker = [];
	$aBankerGrade = [];
	$nBankerGrade = 0;
	$aPlayer = [];
	$aPlayerGrade = [];
	$nPlayerGrade = 0;
	$aReturn = [];
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

	if($aNums['sBanker'] !== undefined){
		$aBanker = $aNums['sBanker'].split(',');
		for(let $LPnKey in $aBanker)
		{
			if($aBanker[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardB'+($LPnKey).toString();
			$aBankerGrade[$LPnKey] = $aGrade[$aBanker[$LPnKey].substr(1)];
			$nBankerGrade += $aBankerGrade[$LPnKey];
			if($(sClassName).length > 0){
				$(sClassName+'Src').attr('src','images/card/'+$aBanker[$LPnKey]+'.png');
				$nBankerGrade %= 10;
				if($LPnKey == 2){
					$('.JqBankerGrade').text($nBankerGrade);
				}
				else{
					if($LPnKey == 1){
						$('.JqBankerGrade').text($nBankerGrade);
					}
				}
			}
		}
	}
	else{
		for(let i=0;i<3;i++){
			sClassName = '.JqCardB'+(i).toString();
			$(sClassName+'Src').attr('src','images/card/back.png');
		}
	}

	if($aNums['sPlayer'] !== undefined){
		$aPlayer = $aNums['sPlayer'].split(',');
		for(let $LPnKey in $aPlayer)
		{
			if($aPlayer[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardP'+($LPnKey).toString();
			$aPlayerGrade[$LPnKey] = $aGrade[$aPlayer[$LPnKey].substr(1)];
			$nPlayerGrade += $aPlayerGrade[$LPnKey];
			if($(sClassName).length > 0){
				$(sClassName+'Src').attr('src','images/card/'+$aPlayer[$LPnKey]+'.png');
				$nPlayerGrade %= 10;
				if($LPnKey == 2){
					$('.JqPlayerGrade').text($nPlayerGrade);
				}
				else{
					if($LPnKey == 1){
						$('.JqPlayerGrade').text($nPlayerGrade);
					}
				}
			}
		}
	}
	else{
		for(let i=0;i<3;i++){
			sClassName = '.JqCardP'+(i).toString();
			$(sClassName+'Src').attr('src','images/card/back.png');
		}
	}
}