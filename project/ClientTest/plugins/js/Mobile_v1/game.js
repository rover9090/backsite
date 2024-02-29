var fnGameSetRoadCss;
var aCookie = fnGetCookieValue();
var aCoinShow = {
	'aFile':[2,3,4,5,6],
	'aClip':[5,10,20,50,100],
};
if(aCookie['sjCoinShowMb'] !== undefined){
	aCoinShow = JSON.parse(aCookie['sjCoinShowMb']);
}
$(document).ready(function()
{
	$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight},0);

	// 限額調整組別
	$('.JqBetGroup').on('click', function()
	{
		fnUpdateCookie($('.JqnGame').val()+'nBetGroupId',$(this).attr('data-nbetgroupid'));

		$('.JqBetGroup').removeClass('active');
		$(this).addClass('active');
		let BWnMoney0 = $(this).attr('data-BWnMoney0');
		let BWnMoney1 = $(this).attr('data-BWnMoney1');
		let PWnMoney0 = $(this).attr('data-PWnMoney0');
		let PWnMoney1 = $(this).attr('data-PWnMoney1');
		let DRAWnMoney0 = $(this).attr('data-DRAWnMoney0');
		let DRAWnMoney1 = $(this).attr('data-DRAWnMoney1');
		let BPnMoney0 = $(this).attr('data-BPnMoney0');
		let BPnMoney1 = $(this).attr('data-BPnMoney1');
		let PPnMoney0 = $(this).attr('data-PPnMoney0');
		let PPnMoney1 = $(this).attr('data-PPnMoney1');
		let SIXnMoney0 = $(this).attr('data-SIXnMoney0');
		let SIXnMoney1 = $(this).attr('data-SIXnMoney1');
		$('.JqBWMoney0').text(BWnMoney0);
		$('.JqBWMoney1').text(BWnMoney1);
		$('.JqPWMoney0').text(PWnMoney0);
		$('.JqPWMoney1').text(PWnMoney1);
		$('.JqDRAWMoney0').text(DRAWnMoney0);
		$('.JqDRAWMoney1').text(DRAWnMoney1);
		$('.JqBPMoney0').text(BPnMoney0);
		$('.JqBPMoney1').text(BPnMoney1);
		$('.JqPPMoney0').text(PPnMoney0);
		$('.JqPPMoney1').text(PPnMoney1);
		$('.JqSIXMoney0').text(SIXnMoney0);
		$('.JqSIXMoney1').text(SIXnMoney1);
	});

	// 點擊籌碼
	$('.JqCoin').on('click',function()
	{
		$('.JqCoin').removeClass('active')
		$(this).addClass('active');
	});

	$('.JqCoinTdBtn').click(function() {
		nCoin = parseFloat($(this).attr('data-clip'));
	});

	$('.JqCoinSetSubmit').click(function(){
		$CoinSet = $('input.JqCoinSet:checked');
		nLen = $CoinSet.length;
		sCoinDom = '';
		if(nLen == 0){
			return;
		}
		aCoinShow = {
			'aFile':[],
			'aClip':[],
		};
		for(let i=0;i<nLen;i++){
			if(i>=6){
				$($CoinSet[i]).prop('checked',false);
				continue;
			}
			aCoinShow['aFile'][i] = $($CoinSet[i]).attr('data-file');
			aCoinShow['aClip'][i] = $($CoinSet[i]).attr('data-clip');
			
			sCoinDom += '<div class="betCoinBtn JqCoin JqCoinTdBtn JqCoinTdBtn'+i+'" data-clip="'+aCoinShow['aClip'][i]+'"  style="width:calc(100%/7);">'+
				'<div class="betCoinBtnInner JqCoinInner">'+
					'<div class="betCoinBtnPic JqCoinPic BG" style="background-image:url(\'images/coin/'+aCoinShow['aFile'][i]+'.png\');"></div>'+
				'</div>'+
			'</div>';
		}
		fnUpdateCookie('sjCoinShowMb',JSON.stringify(aCoinShow));
		// 替換COIN
		$('.JqCoinBox1').html(sCoinDom);
		var nCoinWid = $('.JqCoin').width();
		$('.JqCoinInner').css({'height':(nCoinWid - 8)+'px','width':(nCoinWid - 8)+'px'});
		$('.JqCoinPic').css({'height':(nCoinWid - 14)+'px','width':(nCoinWid - 14)+'px'});
		nCoin = 0;
		$('.JqCoinTdBtn').click(function() {
			nCoin = parseFloat($(this).attr('data-clip'));
		});
		$('.JqCoin').on('click',function()
		{
			$('.JqCoin').removeClass('active')
			$(this).addClass('active');
		});
	});

	// 換桌列表出現
	$('.JqChangeBtn').on('click',function()
	{
		event.stopPropagation();  //阻止冒泡
		$('.JqChangeMenu').addClass('active');
		$('.JqCancel').on('click',function()
		{
			$('.JqChangeMenu').removeClass('active');
		});
		// 點空白處自動關閉
		var flag = true;
		$(document).bind('click',function(e)
		{
			if($(e.target).closest('.JqChangeMenu').length == 0 && flag == true)
			{
				$('.JqChangeMenu').removeClass('active');
				flag = false;
				for(let LPsKey in gaCheck){
					if(LPsKey === gnGame) continue;
					clearInterval(gaCheck[LPsKey]['timeLobby']);
					clearTimeout(gaCheck[LPsKey]['timeoutCount']);
					clearInterval(gaCheck[LPsKey]['timeCountInterval']);
					gaCheck[LPsKey]['timeLobby'] = null;
					gaCheck[LPsKey]['timeoutCount'] = null;
					gaCheck[LPsKey]['timeCountInterval'] = null;
				}
			}
		});
	});

	// 換桌列表正在的那桌
	$('.JqChangeList.active').find('.JqChangeNow').addClass('active');

	// 聊天室顯示隱藏
	$('.JqChatCtrl').on('click', function ()
	{
		// $(this).toggleClass('active');
		$('.JqJumpMsgBox[data-kind="chatroom"]').addClass('active');
	});

	// 限額設定
	$('.JqLimit').on('click',function()
	{
		$('.JqLimit').removeClass('active')
		$(this).addClass('active');
	});

	// 打賞顯示
	$('.JqDonateBtn').on('click',function()
	{
		$('.JqDonateBox').toggleClass('active');
	});

	// 打賞 點選金額
	$('.JqDonateItem').on('click',function()
	{
		let sAllitem = $('.JqDonateBox').attr('data-selectitem');
		let item = $(this).attr('data-item');
		sAllitem = sAllitem + item + ',';
		$('.JqDonateBox').attr('data-selectitem',sAllitem);
		var nNow = parseInt($('.JqDonateMoney').text());
		var nNum = parseInt($(this).attr('data-num'));
		$('.JqDonateMoney').text(nNow+nNum);
	});

	// 打賞 清除
	$('.JqDonateClear').on('click',function()
	{
		$('.JqDonateMoney').text(0);
		$('.JqDonateBox').attr('data-selectitem','');
	});

	//////////////////////////// 寬高度設置 ////////////////////////////

	////////////// 籌碼 //////////////
	var nCoinWid = $('.JqCoin').width();
	$('.JqCoinInner').css({'height':(nCoinWid - 8)+'px','width':(nCoinWid - 8)+'px'});
	$('.JqCoinPic').css({'height':(nCoinWid - 14)+'px','width':(nCoinWid - 14)+'px'});

	////////////// 換桌區塊 //////////////
	var nCngMainRoadWid = (parseInt($('.JqChangeMenu').width()) - 20)/parseInt($('.JqChangeMenu').attr('data-col'));
	$('.JqChangeMenu').find('.JqRoadItem').css({'height':nCngMainRoadWid+'px','width':nCngMainRoadWid+'px'}); // 大路
	$('.JqChangeMenu').find('.JqBigPattern').css({'height':nCngMainRoadWid+'px','width':nCngMainRoadWid+'px'}); // 大路圖案

	////////////// 下方區塊 //////////////
	var nScreenWidth = $(window).width();

	// 棋盤路
	fnGameSetRoadCss = function(){
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

	////////////// 直播專區 //////////////
	var nBetPlayerHeight = $('.JqBetPlayerBox').outerHeight(true);
	var nCoinHeight = $('.JqCoinBox').outerHeight(true);
	var nCtrlHeight = $('.JqCtrlBox').outerHeight(true);
	var nGameRoadHeight = $('.JqGameRoadBox').outerHeight(true);
	var nAnalyHeight = $('.JqAnalyBox').outerHeight(true);
	var nSumMinus = nBetPlayerHeight+nCoinHeight+nCtrlHeight+nGameRoadHeight+nAnalyHeight;
	var nClinetWidth = document.body.clientHeight;
	if((nClinetWidth-nSumMinus) < 230)
	{
		$('.JqViewBox,.JqChatBox').css('height','260px');
	}
	else
	{
		$('.JqViewBox,.JqChatBox').css('height',(nClinetWidth-nSumMinus)+'px');
	}	

});
