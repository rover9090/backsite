var aStickerSend = {};
$(document).ready(function()
{
	// 貼圖 - type
	$('.JqStickerTypeBtn').on('click',function()
	{
		$('.JqStickerTypeBtn,.JqStickerTypeBox').removeClass('active');
		$(this).addClass('active');
		$('.JqStickerTypeBox[data-type="'+$(this).attr('data-type')+'"]').addClass('active');
	});

	// 貼圖 點選金額
	$('.JqStickerBtn').on('click',function()
	{
		let nAmount = parseInt($(this).closest('.JqStickerItem').find('.JqStickerIpt').val());
		if(parseInt($(this).attr('data-type')) == 1)
		{
			$(this).closest('.JqStickerItem').find('.JqStickerIpt').val(nAmount+1);
		}
		else if(parseInt($(this).attr('data-type')) == -1 && nAmount > 0)
		{
			$(this).closest('.JqStickerItem').find('.JqStickerIpt').val(nAmount-1);
		}
	});

	// 貼圖 清除
	$('.JqStickerClear').on('click',function()
	{
		$('.JqStickerIpt').val(0);
	});

	// 購買預覽
	$('.JqConfirmSticker').click(function() {
		let nStickerSum = 0;
		let nStickerCount = 0;
		$('.JqStickerCheckBoxContent').html('');
		let aStickerData = {};
		let length = $('.JqStickerIpt').length;
		for(let i=0;i<length;i++){
			$LPthis = $($('.JqStickerIpt')[i]);
			if($LPthis.val() > 0){
				aStickerSend[$LPthis.attr('data-id')] = $LPthis.val();
				aStickerData[$LPthis.attr('data-id')] = {
					'sName0':$LPthis.attr('data-name0'),
					'nPrice0':$LPthis.attr('data-price0'),
					'nCount':$LPthis.val(),
				};
			}
		}
		console.log(aStickerData,aStickerSend);
		for(let LPsKey in aStickerData){
			let LPnSum = aStickerData[LPsKey]['nPrice0']*aStickerData[LPsKey]['nCount'];
			$('.JqStickerCheckBoxContent').append('<tr><td class="infoTd">'+aStickerData[LPsKey]['sName0']+'</td><td class="infoTd">'+aStickerData[LPsKey]['nCount']+'</td><td class="infoTd">'+LPnSum+'</td></tr>');
			nStickerSum += LPnSum;
			nStickerCount += parseInt(aStickerData[LPsKey]['nCount']);
		}
		$('.JqStickerCheckBoxCount').text(nStickerCount);
		$('.JqStickerCheckBoxSum').text(nStickerSum);
	});

	// 購買貼圖
	$('.JqSubmitSticker').click(function() {
		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sStickerJWT]').attr('data-url');
		let sJWT = $('input[name=sStickerJWT]').attr('data-buy');
		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'aStickerData': aStickerSend,
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);				
				$('.JqWindowBox').removeClass('active');
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);
				if(oRes['nError'] == 0){
					aStickerSend = {};
					$('.JqStickerIpt').val(0);
					// $('.JqStickerBox').removeClass('active');
					// $('.JqMsgBox').addClass('active');
					$('.JqStickerTypeBox').removeClass('active');
					$('.JqStickerTypeBox[data-type="1"]').addClass('active');
					$('.JqStickerTypeBtn').removeClass('active');
					$('.JqStickerTypeBtn[data-type="1"]').addClass('active');
					$('.JqMyMoney0').text(oRes['aUser']['nMoney']);
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	// 指定貼圖
	$('[data-kindctrl="usesticker"]').on('click',function()
	{
		let sStickerUrl = $(this).attr('data-sticker');
		let sStickerId = $(this).attr('data-num');
		$('.JqStkConfirm.JqStickerBtnGo').attr('data-sticker',sStickerUrl);
		$('.JqStkConfirm.JqStickerBtnGo').attr('data-num',sStickerId);
	});

	// 使用貼圖
	$('.JqStickerBtnGo').on('click',function()
	{
		let sStickerUrl = $(this).attr('data-sticker');
		// $('.JqWindowBox,.JqBlock').removeClass('active');
		// $('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
		// $('.JqStickerVideo').attr('src',sStickerUrl);
		// $('.JqStickerVideo').get(0).play();
		let nMid = $(this).attr('data-num');
		// setTimeout(function()
		// {
		// 	$('.JqChatStickerBox').removeClass('active');
		// },3000);

		$this = $(this);
		$this.attr('disabled',true);

		let sUrl = $('input[name=sStickerJWT]').attr('data-url');
		let sJWT = $('input[name=sStickerJWT]').attr('data-use');
		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'nMid': nMid,
				'sLineId': $('input[name=sLineId]').val(),
				'run_page':1,
			},
			success: function (oRes) {				
				$('.JqUserMoney').text(oRes['aUser']['nMoney']);
				if(oRes['nError'] == 0){
					aStickerSend = {};
					$('.JqStickerIpt').val(0);
					$('.JqStickerBox').removeClass('active');
					$('.JqMsgBox').addClass('active');
					$('.JqMyMoney0').text(oRes['aUser']['nMoney']);
					$('.JqWindowBox').removeClass('active');
					// gaJsonMsg[oRes['aData']['nMicroTime']] = true;
					// // 播放動畫
					// let tmpSrc = $('.JqStickerItem[data-num='+oRes['aData']['nMid']+']').attr('data-src');
					// $('.JqWindowBox,.JqBlock').removeClass('active');
					// $('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
					// $('.JqStickerVideo>source').attr('src',tmpSrc);
					// $('.JqStickerVideo').attr('src',tmpSrc);
					// $('.JqStickerVideo').get(0).play();
					// $('.JqStickerVideo').attr('data-use',1);
				}else{
					$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
					$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);				
					$('.JqWindowBox').removeClass('active');
				}
				$this.attr('disabled',false);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
				$this.attr('disabled',false);
			}
		});
	});

	$('.JqStickerVideo').on('ended',function()
	{
		$('.JqStickerVideo').dequeue();
		$(this).attr('data-use',0);
		$('.JqChatStickerBox').removeClass('active');
	});
	$('.JqChatStickerBox').click(function(event)
	{
		if($(this).hasClass('active')){
			if($('#JqStickerVideo').attr('src') !== undefined){
				$('#JqStickerVideo').get(0).play();
			}
			else{
				$('.JqStickerVideo').attr('data-use',0);
				$('.JqChatStickerBox').removeClass('active');
				$('.JqStickerVideo').dequeue();
			}
		}
	});

	$('div').click(function(event)
	{
		// 對整個網頁文本做click偵測
		// 針對iOS的影片只能藉由gesture觸發做對應
		// video或audio元素只要被trigger過一次就可以以腳本播放
		if($('.JqStickerVideo').attr('data-videtrigger') == 0){
			$('#JqStickerVideo').get(0).play();
			$('.JqStickerVideo').attr('data-videtrigger',1);
		}
	});

	$('.JqVideoBtn').click(function(event)
	{
		if($(this).hasClass('active')){
			$('.JqVideoBtn').removeClass('active');
			$('.JqVideoBtnClose').addClass('active');
			$('.JqVideoBtnOpen').removeClass('active');
			fnUpdateCookie('nVideoOpen','0');
		}
		else{
			$('.JqVideoBtn').addClass('active');
			$('.JqVideoBtnOpen').addClass('active');
			$('.JqVideoBtnClose').removeClass('active');
			fnUpdateCookie('nVideoOpen','1');
		}
	});
});