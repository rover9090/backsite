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
	// $('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight},0);

	// 限額調整組別
	$('.JqBetGroup').on('click', function()
	{
		$('.JqBetGroup').removeClass('active');
		$(this).addClass('active');
		let BWnMoney0 = $(this).attr('data-BWnMoney0');
		let BWnMoney1 = $(this).attr('data-BWnMoney1');
		$('.JqBWMoney0').text(BWnMoney0);
		$('.JqBWMoney1').text(BWnMoney1);
	});

	// 點擊籌碼
	$('.JqCoin').on('click',function()
	{
		$('.JqCoin').removeClass('active')
		$(this).addClass('active');
	});

	$('.JqCoinBanker').on('click',function()
	{
		console.log('click');
		$('.JqCoinBanker').removeClass('active')
		$(this).addClass('active');
		$('.JqMoneyBanker1').val(parseFloat($(this).attr('data-clip')));
		if($(this).hasClass('JqSumCoin')){
			$('.JqCoinBanker.JqSumCoin').attr('bSumMoney',1);
		}
		else{
			$('.JqCoinBanker.JqSumCoin').attr('bSumMoney',0);
		}
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
		$('.JqInputDonate').val('');
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
