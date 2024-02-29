$(document).ready(function()
{
	// 取得會員LINEID
	let sLineId = $('input[name=sLineId]').val();
	let liffId = $('input[name=sLiffId]').val();
	console.log(liffId);
	liff.init({
		liffId: liffId
	}).then(() => {
		if (!sLineId) {
			if (!liff.isLoggedIn()) {
				liff.login();
			}
			else {
				liff.getProfile().then(profile => {
					sLineId = profile.userId;
					sLinePicture = profile.pictureUrl;
					if(liffId != '1656844931-nqJqqy8g')
					{
						sHead = 'http'+window.location.origin.substr(5);
						// sHead = window.location.origin;
						sUrl = `${sHead}${window.location.pathname}?_bet_0.RLBe&sLineId=${sLineId}&sLinePicture=${sLinePicture}`;
						if(isiPhone()){
							liff.openWindow({
								url: sUrl,
								external: true
							});
						}
						else{
							location.href = sUrl;
						}
					}
					else
					{
						location.href = `${window.location.origin}${window.location.pathname}?_bet_record_0.RLBe&sLineId=${sLineId}&sLinePicture=${sLinePicture}`;
					}
				})
			}
		}
	});

	setInterval(function(){
		$('.JqCurrentTime').text(fnCurrentTime('m/d H:i:s'));
	}, 1000);

	// 彈窗按鈕 + class
	$.each($('.JqJumpMsgBox'),function(index,item)
	{
		if($(item).find('.JqJumpMsgBtn').length > 1)
		{
			$(item).find('.JqJumpMsgBtn').addClass('noOne');
		}
	});

	$('.JqRefreshSite').on('click',function()
	{
		$('.JqRefreshSite').attr('disabled',true);
		location.reload();
	});
	
	// 彈窗 - 關閉
	$('.JqClose').on('click',function()
	{
		if($(this).parents().hasClass('JqDetailTr'))
		{
			// 是否在表格裡的查看明細
			$(this).parents('.JqDetailTr').removeClass('active');
		}
		else
		{
			$(this).parents('.JqJumpMsgBox').removeClass('active');
		}
		$('.JqHtml').removeClass('active');
	});

	// 開關彈窗頁面
	$('.JqWindowBtn').on('click', function ()
	{
		$('.JqWindowBox[data-kind="'+$(this).attr('data-kindctrl')+'"]').addClass("active");
		if($(this).attr('data-kindctrl') == 'usesticker')
		{
			let sImg = $(this).attr('data-sticker');
			$('.JqStickerBtnGo.submit').attr('data-sticker',sImg);
		}
	});

	$('.JqClose').on('click', function ()
	{		
		var $nKind = $(this).attr('data-kindctrl');
		$('.JqWindowBox[data-kind="'+$nKind+'"]').removeClass("active");
		$(this).parents('.JqWindowBox').removeClass("active");
		$(this).parents('.JqJumpMsgBox').removeClass('active');
	});

	// 彈窗 - 防呆
	$('.JqStupidOut').on('click',function()
	{
		$('.JqJumpMsgBox[data-showmsg="'+ $(this).data('showctrl') +'"]').addClass('active');
	});

	// 彈窗 - 顯示彈窗
	$('.JqBtnForJump').on('click',function()
	{
		$('.JqJumpMsgBox[data-showmsg="'+ $(this).data('showctrl') +'"]').addClass('active');
	});

	// 刪除鈕 防呆
	$('.JqReplaceS').on('click',function()
	{
		$('.JqReplaceO').attr('href',$(this).data('replace'));
	});

	// 牌路切換
	// $('.JqBtnRaodS').on('click',function()
	// {
	// 	$(this).toggleClass('active');
	// 	if($(this).hasClass('active'))
	// 	{
	// 		$('.JqGameRoadBox,.JqAnalyBox').addClass('active');
	// 		fnGameSetRoadCss();
	// 		fnGameSetAskCss();
	// 	}
	// 	else
	// 	{
	// 		$('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
	// 	}
	// 	// $('.JqGameRoadBox,.JqAnalyBox').addClass('active');
	// 	// $('.JqBlock').removeClass('active');
	// 	// fnGameSetRoadCss();
	// 	// fnGameSetAskCss();
	// });

	// 區塊
	// $('.JqFooterBtn').on('click',function()
	// {
	// 	$('.JqBlock').removeClass('active');
	// 	$('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
	// 	$('.JqBlock[data-btn="'+$(this).attr('data-btn')+'"]').addClass('active');
	// 	if($(this).attr('data-btn') == 'bet')
	// 	{
	// 		fnGameSetChipCss();
	// 	}
	// });

	$('.JqMenuBtn').on('click',function()
	{
		$('.JqBlock').removeClass('active');
		$('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
		$('.JqBlock[data-btn="'+$(this).attr('data-btn')+'"]').addClass('active');
		if($(this).attr('data-btn') == 'bet')
		{
			fnGameSetChipCss();
		}
		// else if($(this).attr('data-btn') == 'gameroad')
		// {
		// 	fnGameSetRoadCss();
		// 	fnGameSetAskCss();
		// }
	});

	$('.JqJumpBet').on('click',function()
	{
		$('.JqBlock').removeClass('active');
		$('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
		$('.JqBlock[data-btn="bet"]').addClass('active');
		fnGameSetChipCss();		
	});


	// 點擊籌碼
	$('.JqCoin').on('click',function()
	{
		$('.JqCoin').removeClass('active');
		$(this).addClass('active');
	});

	// 籌碼
	$('.JqCoinBox').on('click','.JqWindowBtn[data-kindctrl="12"]', function ()
	{
		$('.JqWindowBox[data-kind="'+$(this).attr('data-kindctrl')+'"]').addClass("active");
	});

	//////////////////////////// 寬高度設置 ////////////////////////////
	var nScreenWidth = $(window).width();

	// 視訊
	// var nHeaderHeight = $('.JqHeader').outerHeight(true);
	// var nBetHeight = $('.JqBetBtnBox').outerHeight(true);
	// var nMarqueeHeight = $('.JqMarquee').outerHeight(true);
	// var nFooterHeight = $('.JqFooter').outerHeight(true);
	// var nSumMinus = nHeaderHeight+nBetHeight+nMarqueeHeight+nFooterHeight;
	// $('.JqVideoBox').css('height','calc(100vh - '+nSumMinus+'px)');

	////////////// 籌碼 //////////////
	fnGameSetChipCss = function()
	{
		var nCoinWid = $('.JqCoin').width();
		$('.JqCoinInner').css({'height':(nCoinWid - 8)+'px','width':(nCoinWid - 8)+'px'});
		$('.JqCoinPic').css({'height':(nCoinWid - 14)+'px','width':(nCoinWid - 14)+'px'});
	};
	fnGameSetChipCss();

	$(window).resize(function() {
		if(window.innerHeight < window.innerWidth){
			// 切換成全螢幕
			$(".mainVideoOtherBox").css({"position":"absolute","top":"0px"});
			$('.vcp-player.touchable').find('video').css({"width":"100vw","height":"100vh"});
			$('.JqmainMenuBox').css({"z-index":"-1"});
			$('.mainVideoTmp').css({"z-index":"0","background-color":"black","width":"100vw","height":"100vh"});
		}
		else{
			$(".mainVideoOtherBox").css({"position":"","top":""});
			$('.vcp-player.touchable').find('video').css({"width":"inherit","height":"inherit"});
			$('.JqmainMenuBox').css({"z-index":""});
			$('.mainVideoTmp').css({"z-index":"","background-color":"","width":"","height":""});
		}
	});
	if(window.innerHeight < window.innerWidth){
		$(".mainVideoOtherBox").css({"position":"absolute","top":"0px"});
		$('.vcp-player.touchable').find('video').css({"width":"100vw","height":"100vh"});
		$('.JqmainMenuBox').css({"z-index":"-1"});
	}

	// 棋盤路
	fnGameSetRoadCss = function()
	{
		var nMainWid = $('.JqMainRoadBox').width()/parseInt($('.JqMainRoadBox').attr('data-col'));
		$('.JqMainRoad').css({'height':(nMainWid)+'px','width':(nMainWid)+'px'});
		$('.JqResultBox').css({'height':(nMainWid-2)+'px','width':(nMainWid-2)+'px'}); // 文字框

		// 文字大小
		if(nMainWid < 23)
		{
			$('.JqResultWord').css('font-size',(nMainWid-4)+"px");
		}
		else
		{
			$('.JqResultWord').css('font-size',(nMainWid/1.2)+"px");
		}

		$('.JqPatternRoad').css({'height':((nMainWid-1)/2)+'px','width':((nMainWid-1)/2)+'px'}); // 圖案格子

		$('.JqBigPattern').css({'height':(nMainWid/2)+'px','width':(nMainWid/2)+'px'}); // 大路圖案
		if((nMainWid/2) < 15)
		{
			$('.JqBigPattern').css('border-width','2px');
		}

		if((nScreenWidth > 767) && (nScreenWidth < 1024))
		{
			$('.JqBigPattern').css('border-width','5px');
		}
		else if(nScreenWidth >= 1024)
		{
			$('.JqBigPattern').css('border-width','6px');
		}

		// 四分之一格
		$('.JqRoadSep').css('height',(((nMainWid/2)-2)/2)+'px');

		// 四分之一格圖案
		$('.JqSmallPattern').css({'height':(((nMainWid/2)-2)/2)+'px','width':(((nMainWid/2)-2)/2)+'px'});
		if((((nMainWid/2)-2)/2) > 7)
		{
			$('.JqSmallPattern').css('border-width','2px');
		}

		if(nScreenWidth >= 1024)
		{
			$('.JqSmallPattern').css('border-width','3px');
		}
		
		// 點
		$.each($('.JqGameDot'),function(i,dot)
		{
			var nDotVal = $(dot).parent('.JqRoadItem').width();
			if(nDotVal/2.5 > 6)
			{
				$(dot).css({'height':(nDotVal/2.5)+'px','width':(nDotVal/2.5)+'px'});
			}
			else
			{				
				$(dot).css({'height':'5px','width':'5px','border-width':'1px'});
			}
		});
	}
	fnGameSetRoadCss();

	// 下方問路區塊
	fnGameSetAskCss = function()
	{
		var nAskWid = parseInt($('.JqAskTypeSample').width())-2;
		$('.JqAskCircleH').css({'width':nAskWid+'px','height':nAskWid+'px','border-width':'1px'}); // 空心
		if((nScreenWidth > 767) && (nScreenWidth < 1024))
		{
			$('.JqAskCircleH').css('border-width','4px');
		}
		else if(nScreenWidth >= 1024)
		{
			$('.JqAskCircleH').css('border-width','5px');
		}

		$('.JqAskCircleS').css({'width':nAskWid+'px','height':nAskWid+'px'}); // 實心
		$('.JqAskLine').css({'width':nAskWid+'px','height':nAskWid+'px'}); // 斜線
	};

	fnGameSetAskCss();
});
function capture(video, scaleFactor) {
	if (scaleFactor == null) {
	    scaleFactor = 1;
	}
	var w = video.videoWidth * scaleFactor;
	var h = video.videoHeight * scaleFactor;
	var canvas = document.createElement('canvas');
	canvas.width = w;
	canvas.height = h;
	var ctx = canvas.getContext('2d');
	ctx.drawImage(video, 0, 0, w, h);
	return canvas;
  }

  /**
   * Invokes the <code>capture</code> function and attaches the canvas element to the DOM.
   */
function shoot() {
	// var video = document.getElementById(videoId);
	var video = $('#video-player>div>video')[0];
	// var output = document.getElementById('output');
	var canvas = capture(video, 1);
	console.log(canvas.toDataURL('image/jpg'));
	// canvas.onclick = function() {
	//     window.open(this.toDataURL('image/jpg'));
	// };
	// snapshots.unshift(canvas);
	// output.innerHTML = '';
	// for (var i = 0; i < 4; i++) {
	//     output.appendChild(snapshots[i]);
	// }
}

function isiPhone(){
	return (
	    (navigator.platform.indexOf("iPhone") != -1) ||
	    (navigator.platform.indexOf("iPod") != -1)
	);
}