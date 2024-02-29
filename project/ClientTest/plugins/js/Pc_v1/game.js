var fnGameSetRoadCss;
var aCookie = fnGetCookieValue();
var aCoinShow = {
	'aFile':[2,3,4,5,6],
	'aClip':[5,10,20,50,100],
};
if(aCookie['sjCoinShowPc'] !== undefined){
	aCoinShow = JSON.parse(aCookie['sjCoinShowPc']);
}
$(document).ready(function()
{	
	$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight},0);

	$($('.JqCoinTdBtn')[0]).children('.JqCoin').addClass('active');
	nCoin = parseFloat($($('.JqCoinTdBtn')[0]).attr('data-clip'));

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

	// 籌碼橫向滾動
	$('.JqCoinScroll').mousewheel(function(e)
	{
		var scrollPos = $(this).scrollLeft();
		var moveAmount = 60;
		if(e.deltaY > 0)
		{
			$(this).scrollLeft((scrollPos-moveAmount));
		}
		else
		{
			$(this).scrollLeft((scrollPos+moveAmount));
		}
		e.preventDefault();   //停止冒泡
	});

	//點擊左右鍵
	$('.JqArrow').on('click', function()
	{
		var scrollPos = $('.JqCoinScroll').scrollLeft();
		var moveAmount = 120;
		var left = (scrollPos+moveAmount);
		var right = (scrollPos-moveAmount);
		if($(this).attr('data-ctrl') == '-1')
		{
			$('.JqCoinScroll').animate({scrollLeft: right}, 333);
			if($('.JqCoinScroll').scrollLeft() === 0)
			{
				$('.JqCoinScroll').scrollLeft() === 0;
			}
		}
		else if($(this).attr('data-ctrl') == '1')
		{
			$('.JqCoinScroll').animate({scrollLeft: left}, 333);
			if($('.JqCoinScroll').scrollLeft() === $('.JqCoinScroll').width())
			{		
				$('.JqCoinScroll').scrollLeft() === $('.JqCoinScroll').width();	
			}
		}
	});

	// 籌碼橫向拖曳
	var drag=function(obj)
	{  
		obj.bind("mousedown",start);  
		function start(e)
		{  
	    		//判断是否点击鼠标左键 
			if(e.button==0)
			{   
		  		gapX=e.clientX;
		  		startx = $(this).scrollLeft();  // scroll的初始位置
				//movemove事件绑定到obj上  
				obj.bind("mousemove",move);   
				obj.bind("mouseup",stop);                           
	    		}  
	    		return false;	//阻止默认事件或冒泡  
		}  
		function move(e)
		{  
	    		var left = e.clientX-gapX; 	//移动的相对距离
	    		$(this).scrollLeft(startx - left );
	    		return false;	//阻止默认事件或冒泡  
		}  
		function stop()
		{  
			//解除绑定  
			obj.unbind("mousemove",move);  
			obj.unbind("mouseup",stop);  
		}  
  	}  
  	obj=$('.JqCoinScroll');  
  	drag(obj);	//调用jQuery自定义函数  


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
		if(nLen < 3){
			return;
		}
		aCoinShow = {
			'aFile':[],
			'aClip':[],
		};
		for(let i=0;i<nLen;i++){
			if(i>=5){
				$($CoinSet[i]).prop('checked',false);
				continue;
			}
			aCoinShow['aFile'][i] = $($CoinSet[i]).attr('data-file');
			aCoinShow['aClip'][i] = $($CoinSet[i]).attr('data-clip');
			sCoinDom += 
			'<div class="betChipBtn JqCoinTdBtn JqCoinTdBtn'+i+'" data-clip="'+aCoinShow['aClip'][i]+'">'+
				'<div class="betChipBtnBg JqCoin">'+
					'<div class="betChipBtnImg BG" style="background-image: url(\'images/coin/'+aCoinShow['aFile'][i]+'.png\');"></div>'+
				'</div>'+
			'</div>';
			// '<div class="batCtrlChipItem JqCoinTdBtn JqCoinTdBtn'+i+'" data-clip="'+aCoinShow['aClip'][i]+'">'+
			// 	'<div class="batCtrlChipItemBg JqCoin">'+
			// 		'<div class="batCtrlChipItemImg BG" style="background-image: url(\'images/coin/'+aCoinShow['aFile'][i]+'.png\');"></div>'+
			// 	'</div>'+
			// '</div>';
		}
		fnUpdateCookie('sjCoinShowPc',JSON.stringify(aCoinShow));
		sCoinDom += '<div class="betChipBtn JqWindowBtn" data-kindctrl="12">'+
					'<div class="betChipBtnSet">'+
						'<img src="images/ICON/coin_btn.png" alt="">'+
					'</div>'+
				'</div>';
		// 替換COIN
		$('.JqCoinBox').html(sCoinDom);
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

	// 遊戲資訊收合及倒數
	$('.JqInfoIcon').on('click',function()
	{
		$('.JqInfoBox').toggleClass('active');
		$('.JqTimeBox').toggleClass('active');
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

	// 玩家牌桌籌碼金額寬高度
	// $('.JqPlayerBetChipNum').removeClass('sizectrl');
	// $.each($('.JqPlayerBetChip'),function(i,chip)
	// {
	// 	if($(chip).find('.JqPlayerBetChipNum').width() > $(chip).width())
	// 	{			
	// 		var nPer = ($(chip).find('.JqPlayerBetChipNum').width() - $(chip).width())/$(chip).width()
	// 		$(chip).find('.JqPlayerBetChipNum').css('transform','translate(-50%,-50%) scale('+(1 - nPer)+')');
	// 	}
	// });

	//////////////////////////// 寬高度設置 ////////////////////////////

	////////////// 換桌區塊 //////////////
	var nCngMainRoadWid = (parseInt($('.JqChangeMenu').width()) - 14)/parseInt($('.JqChangeMenu').attr('data-col'));
	$('.JqChangeMenu').find('.JqBigRoad').css('height',nCngMainRoadWid+'px'); // 大路
	$('.JqChangeMenu').find('.JqBigPattern').css({'height':nCngMainRoadWid+'px','width':nCngMainRoadWid+'px'}); // 大路圖案

	////////////// 下方區塊 //////////////
	// 路 寬度 高度

	fnGameSetRoadCss = function(){
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
		$('.JqAskBlock').css('padding',((nMainWid*3 - nAskHeight + 3.5)/2)+"px 1.04vw");

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
	fnGameSetRoadCss();
	var nWindwHeight = $(window).height();
	var nBetHeight = $('.JqBetBox').height();
	var nGameMainHeight = nWindwHeight - nBetHeight - 70;
	$('.JqGameMainBox').css('height',nGameMainHeight+'px');

	// 牌路下注切換
	$('.JqBetBtn').on('click', function()
	{
		if($(this).hasClass('active'))
		{
			$(this).removeClass('active');
			$('.JqBetBox').removeClass('active');
			$('.JqViewRoad').addClass('active');
		}
		else
		{
			$(this).addClass('active');
			$('.JqBetBox').addClass('active');
			$('.JqViewRoad').removeClass('active');
		}
		fnGameSetRoadCss();
	});
});
