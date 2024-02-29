aCookie = fnGetCookieValue();
var aResult = {'aPlayer':[],'aBanker':[]};
var aCardStatus = {'nCleaned':0};

$(document).ready(function () {
	if($('.JqToggleHeader').length > 0){
		$('.JqHeader').hide();
		$('.JqNavContentContainer').toggleClass('active');
	}
	$('.JqToggleHeader').on('click' , function()
	{
		$('.JqHeader').toggle();
		$('.JqNavContentContainer').toggleClass('active');
	});
	// if($('.JqTextareaResult').length > 0) {
	// 	$('.JqTextareaResult').focus();
	// }
	// $('.JqMainPage').hide();
	// $('.JqSub0Page').show();
	// $('.JqReturnMain').show();
	
	$('.JqStupidOut').on('click' , function()
	{
		$(this).addClass('green');
	});

	$('.JqClose').on('click' , function()
	{
		$('.JqStupidOut.green').removeClass('green')
	});

	$('.JqResult0_1').on('click' , function()
	{
		console.log();
		$('.JqResult0Txt').addClass('FontRed');
		$('.JqResult0Txt').removeClass('FontGreen');
		$('.JqResult0Txt').text($(this).text());
		$('input[name="nResult0"]').val(1);
	});

	$('.JqResult0_0').on('click' , function()
	{
		$('.JqResult0Txt').addClass('FontGreen');
		$('.JqResult0Txt').removeClass('FontRed');
		$('.JqResult0Txt').text($(this).text());
		$('input[name="nResult0"]').val(0);
	});

	$('#JqFullScreen').on('click' , function()
	{
		var elem = document.getElementById("JqEditGameTable");
		// var elem = document.body;
		if (document.fullscreenElement) {
			document.cookie = 'bFS=';
			document.exitFullscreen()
				.then(() => console.log("Document Exited from Full screen mode"))
				.catch((err) => console.error(err))
		} else {
			if (elem.requestFullscreen) {
				elem.requestFullscreen();
			} else if (elem.msRequestFullscreen) {
				elem.msRequestFullscreen();
			} else if (elem.mozRequestFullScreen) {
				elem.mozRequestFullScreen();
			} else if (elem.webkitRequestFullscreen) {
				elem.webkitRequestFullscreen();
			}
			document.cookie = 'bFS=1';
		}		
	});
	setTimeout(function(){ $('#JqFullScreen').click(); }, 1000);
	

	// 控制
	$('.JqButtonCtrl').on('click' , function()
	{
		$('input[name=nStatus]').val($(this).data('val'));
		$('.JqButtonCtrl').removeClass('BtnAny2');
		$('.JqButtonCtrl').addClass('BtnAny');
		$(this).addClass('BtnAny2');
	});

	$('.JqSub0Open').on('click' , function()
	{
		$('.JqMainPage').hide();
		$('.JqSub0Page').show();
		$('.JqReturnMain').show();
	});

	sRes0 = $('input[name=sResult0]').val();

	$('.JqPutDealerId').on('keypress keyup' , function(e)
	{
		let sValue = $(this).val();
		let nPressCode = (e.keyCode ? e.keyCode : e.which);
		$('input[name="nDealer"]').val(sValue);
		console.log($('input[name="nDealer"]').val());

		if(nPressCode == 13)
		{
			$('.EditBtn.JqStupidOut[data-showctrl="0"]').click();
			$(this).blur();
			$('.JqJumpMsgBox[data-showmsg="0"]').find('.JqEnterClick').focus();
		}
	});

	$('[data-flo]').on('click' , function()
	{
		let sCard;
		// clearInterval(timeNums);// 打開編輯頁面就停止要獎源
		sFlower = $(this).attr('data-flo')[0];
		nLength = $('.JqEnterCard').length;
		for(let i=0;i<13;i++){
			$this = $($('.JqEnterCard')[i]);
			sCard = $this.find('img').attr('src');
			sCard = sCard.replace(/[CDHS]([\dAJQK])/g, sFlower+'$1');
			$this.find('img').attr('src',sCard);
		}

	});

	$('.JqEnterCard').on('click' , function()
	{
		$('.JqEdit').val(1);
		// clearInterval(timeNums);// 打開編輯頁面就停止要獎源
		sCard = $(this).find('img').attr('src');
		aCard = sCard.split('/');
		sCardSign = aCard[2].split('.')[0];
		$ListCard = $($('.JqNums[data-Card=""]')[0]);
		if($('.JqNums.active').length > 0){
			$('.JqNums.active').find('img').attr('src','images/card/'+sCardSign+'.png');
			$('.JqNums.active').attr('data-Card',sCardSign);
			sWho = $('.JqNums.active').attr('data-Who');
			if(sCardSign == 'back'){
				aResult['a'+sWho][$('.JqNums.active').attr('data-Length')] = '';
			}
			else{
				aResult['a'+sWho][$('.JqNums.active').attr('data-Length')] = sCardSign;
			}

			$('input[name=sResult0]').val(aResult['aBanker'].join(','));
			$('input[name=sResult1]').val(aResult['aPlayer'].join(','));

			$('.JqNums').removeClass('active');
			$($('.JqNums[data-Card=""]')[0]).addClass('active');
		}
	});

	$('.JqNums').on('click' , function()
	{
		// clearInterval(timeNums);// 打開編輯頁面就停止要獎源
		$('.JqNums').removeClass('active');
		$(this).addClass('active');
	});

	$('.JqEnterClick').on('keypress' , function(e)
	{
		let nPressCode = (e.keyCode ? e.keyCode : e.which);
		$JumpBox = $('.jumpMsgBox.JqJumpMsgBox.active');
		if(nPressCode == 13)
		{
			$JumpBox.find('.JqEnterClick').click();
		}
	});

	$('.JqEnterClick').on('click' , function(e)
	{
		// clearInterval(timeNums);
		// clearInterval(timeIfPause);		
	});

	$(".JqRememberPage").change(function()
	{
		if($(this).is(":checked"))
		{
			fnUpdateCookie('nGame',$('input[name=nGame]').val(),86400);
		}
		else
		{
			fnDeleteCookie('nGame');
		}
	});

	$('input[name="nResult1"]').on('click' , function(e)
	{		
		$('input[name="nResult1"]').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('input[name="nResult2"]').on('click' , function(e)
	{		
		$('input[name="nResult2"]').parent().removeClass('active');
		$(this).parent().addClass('active');
	});
	$('input[name="nResult3"]').on('click' , function(e)
	{		
		$('input[name="nResult3"]').parent().removeClass('active');
		$(this).parent().addClass('active');
	});

	$('.JqLimitA').on('click' , function(e)
	{
		$('input[name="sLimit"]').val('A');
		$('div[data-showctrl="limit"],div[data-showctrl="limitA"],div[data-showctrl="limitB"],div[data-showctrl="limitC"]').removeClass('BtnAny2');
		$('div[data-showctrl="limitA"]').addClass('BtnAny2');
	});
	$('.JqLimitB').on('click' , function(e)
	{
		$('input[name="sLimit"]').val('B');
		$('div[data-showctrl="limit"],div[data-showctrl="limitA"],div[data-showctrl="limitB"],div[data-showctrl="limitC"]').removeClass('BtnAny2');
		$('div[data-showctrl="limitB"]').addClass('BtnAny2');
	});
	$('.JqLimitC').on('click' , function(e)
	{
		$('input[name="sLimit"]').val('C');
		$('div[data-showctrl="limit"],div[data-showctrl="limitA"],div[data-showctrl="limitB"],div[data-showctrl="limitC"]').removeClass('BtnAny2');
		$('div[data-showctrl="limitC"]').addClass('BtnAny2');
	});
	$('.JqLimit').on('click' , function(e)
	{
		$('input[name="sLimit"]').val('');
		$('div[data-showctrl="limit"],div[data-showctrl="limitA"],div[data-showctrl="limitB"],div[data-showctrl="limitC"]').removeClass('BtnAny2');
		$('div[data-showctrl="limit"]').addClass('BtnAny2');
	});
	// setTimeout(function(){ location.reload(); }, 2*60*1000);
	
	// timeNums = setInterval(fnAjaxNums, 1500);
	// timeIfPause = setInterval(fnAjaxIfPause, 5000);
	timeBankerStr = setInterval(fnAjaxBankerStr, 3000);
});

function fnAjaxIfPause(){
	if($('.JqToggleRead').is(":checked")){
		return;
	}
	let sUrl = $('input[name=sNumsAjax0]').attr('data-url');
	let sJWT = $('input[name=sNumsAjax0]').val();
	let nGame = $('input[name=nGame]').val();
	let aIssetNums = {};
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1500,
		data: {
			'sJWT': sJWT,
			'nGame': nGame,
			'run_page': 1,
		},
		success: function (oRes) {
			if(oRes['aData']['nPauseTime0'] > 0){
				// if(!timeNums){
				// 	timeNums = setInterval(fnAjaxNums, 1500);
				// }
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

function fnAjaxNums(nRetry=0){
	let sUrl = $('input[name=sNumsAjax0]').attr('data-url');
	let sJWT = $('input[name=sNumsAjax0]').val();
	let nGame = $('input[name=nGame]').val();
	let aIssetNums = {};
	console.log('nums');
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1500,
		data: {
			'sJWT': sJWT,
			'nGame': nGame,
			'run_page': 1,
		},
		success: function (oRes) {
			// console.log(oRes['aData']);
			if(oRes['aData']['nSeconds'] > 0 || oRes['aData']['nSeconds1'] > 0){
				aCardStatus['nCleaned'] = 0;
				if(!$('.JqToggleRead').is(":checked")){
					// clearInterval(timeNums);
					timeNums = null;
					if(oRes['aData']['nSeconds'] > 0){
						console.log(oRes['aData']['nSeconds']);
						setTimeout(fnAjaxNums, (oRes['aData']['nSeconds'])*1000,1);
						// timeIfPause = setInterval(fnAjaxIfPause, 5000);
					}
					if(oRes['aData']['nSeconds1'] > 0){
						console.log(oRes['aData']['nSeconds1']);
						setTimeout(fnAjaxNums, (oRes['aData']['nSeconds1'])*1000,1);
					}
				}
			}
			else{
				// clearInterval(timeIfPause);
				// timeIfPause = null;
				// if(!timeNums){
				// 	timeNums = setInterval(fnAjaxNums, 1500);					
				// 	console.log('456');
				// }
			}
			if(oRes['aData']['sNums'] == '' && aCardStatus['nCleaned'] == 0){
				aCardStatus['nCleaned'] = 1;
				for(let i=0;i<=2;i++){
					sClassName1 = '.JqBanker'+(i).toString();
					sClassName2 = '.JqPlayer'+(i).toString();
					$(sClassName1+'Src,'+sClassName2+'Src').attr('src','images/card/back.png');
				}
				$('input[name=sResult0]').val('');
				$('input[name=sResult1]').val('');
				return;
			}
			aNums = oRes['aData']['aNums'];
			if(aNums != undefined){
				aNums['aBanker'] = aNums['sBanker'].split(',');
				aNums['aPlayer'] = aNums['sPlayer'].split(',');
				aIssetNums['aBanker'] = $('input[name=sResult0]').val().split(',');
				aIssetNums['aPlayer'] = $('input[name=sResult1]').val().split(',');
				// console.log(aIssetNums);
				if(aIssetNums['aBanker'][2] !== undefined && aNums['aBanker'][2] === undefined){
					aNums['aBanker'][2] = aIssetNums['aBanker'][2];
					aNums['sBanker'] = aIssetNums['aBanker'].join(',');
				}
				if(aIssetNums['aPlayer'][2] !== undefined && aNums['aPlayer'][2] === undefined){
					aNums['aPlayer'][2] = aIssetNums['aPlayer'][2];
					aNums['sPlayer'] = aIssetNums['aPlayer'].join(',');
				}
				/**
				 * 差不多每一千筆就會發生三次有牌誤判為無牌
				 * 當我偵測到有牌時不會被無牌的新判定覆蓋
				 */
				aResult['aBanker'] = aNums['aBanker'];
				aResult['aPlayer'] = aNums['aPlayer'];

				for(let i=0;i<3;i++){
					if(aNums['aBanker'][i] !== undefined && aNums['aBanker'][i] !== ''){
						$('.JqBanker'+i+'Src').attr('src','images/card/'+aNums['aBanker'][i]+'.png');
					}else{
						$('.JqBanker'+i+'Src').attr('src','images/card/back.png');
					}
				}
	
				for(let i=0;i<3;i++){
					if(aNums['aPlayer'][i] !== undefined && aNums['aPlayer'][i] !== ''){
						$('.JqPlayer'+i+'Src').attr('src','images/card/'+aNums['aPlayer'][i]+'.png');
					}else{
						$('.JqPlayer'+i+'Src').attr('src','images/card/back.png');
					}
				}
	
				$('input[name=sResult0]').val(aNums['sBanker']);
				$('input[name=sResult1]').val(aNums['aPlayer']);
			}			
		},
		error: function (exception) {
			if(exception.statusText == 'timeout'){
				console.log('timeout');
				if(nRetry == 1){
					fnAjaxNums(1);
					console.log('789');
				}
			}
			else{
				console.log('Exeption:' + exception.responseText);
			}
		}
	});
}

function fnGetCookieValue() {
	let aCookieObj = [];
	let aCookieAry = document.cookie.split(';');
	let aCookie;
	let nCookieLength = aCookieAry.length;
	
	for (let i=0, l=aCookieAry.length; i<l; ++i) {
		aCookie = jQuery.trim(aCookieAry[i]);
		aCookie = aCookie.split('=');
		aCookieObj[aCookie[0]] = aCookie[1];
	}
	aCookieObj.length = nCookieLength;
	
	return aCookieObj;
}

function fnUpdateCookie(sName,sValue,sExpireMs='') {
	if(sExpireMs === ''){
		sExpireMs = aJSDEFINE['COOKIE_REMEMBER'] * 1000;
		document.cookie = sName+'='+sValue+'; expires='+sExpireMs;
	}
	else{
		document.cookie = sName+'='+sValue+'; expires='+sExpireMs;
	}
}

function fnDeleteCookie(sName) {
	let oDate = new Date();
	oDate.setTime(oDate.getTime() - 1);
	let sExpires = 'expires='+oDate.toGMTString();
	document.cookie = sName+'=0; expires='+sExpires;
}

function fnAjaxBankerStr(){
	if($('input[name=sBankerStrAjax]').length <= 0) return;
	let sUrl = $('input[name=sBankerStrAjax]').attr('data-url');
	let sJWT = $('input[name=sBankerStrAjax]').val();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		timeout:1000,
		data: {
			'sJWT': sJWT,
			'run_page': 1,
		},
		success: function (oRes) {
			$('.JqBankerStr').html(oRes['aData']['sBankerStr']);
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