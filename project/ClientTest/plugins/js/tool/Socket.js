
var wk;
var fnSocketMsg;
$(document).ready(function()
{
	var bConnect = false;
	const nMaxSec = 15;
	var nConnectCountDown = nMaxSec;

	var nRoom = $('.JqnGame').val();
	var Height = $(".JqMsgBox").height();
	$('.JqMainBox').animate({scrollTop:Height},0);
	var wk;
	if( typeof(WebSocket) != "function" )
	{
    		$('body').html("<h1>Error</h1><p>Your browser does not support HTML5 Web Sockets. Try Google Chrome instead.</p>");
	}

	var sUrl = 'ws://213.139.235.77:8090';
	// var sUrl = 'ws://demo801.dtap000s3.com:8090/Project/t_linegame06/EndTest/Bot/SocketServer.php';
	// var sUrl = 'ws://demo801.dtap000s3.com:8090';
	// http://demo801.dtap000s3.com/Project/t_linegame06/EndTest/Bot/SocketServer.php
	// var wk;

	if(window.WebSocket)
	{
		// wk = new WebSocket(sUrl);
		// console.log(wk);
		// setInterval(fnPushSth, 1000);

		fnConnect();
		//建立連線
		function fnConnect(){
			wk = new WebSocket(sUrl);
			console.log(wk);
			wk.onopen = function(event)
			{
				console.log("伺服器連線成功");
				bConnect = true;
				/**
				 * 連線啟用成功後將對話進行分類
				 * 傳送此房間的ID給SERVER端
				 */

				let auth = {
					sType:'join',
					nRoomId:nRoom,
					nUid:gaUser['aData']['nUid'],
					sName0: gaUser['aData']['sName0'],
					sAccount: gaUser['aData']['sAccount'],
					sPicture: gaUser['aData']['sPicture'],
				}
				console.log(gaUser['aData']);
				wk.send(JSON.stringify(auth));
				console.log(gaUser['aData']['sName0']+'加入聊天 發送資訊給Server:'+JSON.stringify(auth));
			}

			//異常
			wk.onerror = function(event)
			{
				console.log("伺服器連線失敗");
			}

			//關閉連線
			wk.onclose = function(event)
			{
				console.log('關閉伺服器連線');
				bConnect = false;
				delete wk;
				fnConnect();
			}

			//接收伺服端訊息
			wk.onmessage = function(event)
			{
				let aJson = JSON.parse(event.data);
				let scrollHeight = $(".JqShowArea")[0].scrollHeight;
				let scrollTop = $(".JqShowArea")[0].scrollTop;
				let offsetHeight = $(".JqShowArea")[0].offsetHeight;
				console.log('接收Server訊息 '+JSON.stringify(aJson));

				switch(aJson['sType'])
				{
					case 'join':
						console.log(aJson['sName0']+'加入成功');
						break;
					case 'chat':
						sayMsg(aJson);
						break;
					case 'donate':
						$('.JqBlock').removeClass('active');
						$('.JqBlock[data-btn="chatroom"]').addClass('active');
						$(".JqShowArea").append(aJson['sMsg']);
						if((scrollHeight - scrollTop) == offsetHeight)
						{
							$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
						}
						break;
					case 'server':
						$(".JqShowArea").append(aJson['sMsg']);
						if((scrollHeight - scrollTop) == offsetHeight)
						{
							$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
						}
						if(aJson['bSticker'] !== undefined){
							// $('.JqStickerVideo').attr('data-use')使用中會切成1  播放完畢會切成0
							if(aJson['bSticker'] == 0){
								if($('.JqStickerVideo').attr('data-use') == 1){
									$('.JqChatStickerBox').removeClass('active');
									$('.JqStickerVideo').attr('data-use',0);
								}
							}
						}
						if(aJson['nSumMoney0'] !== undefined){
							$('.JqCoinBanker.JqSumCoin').attr('data-clip',aJson['nSumMoney0']);
							$('.JqSumBet').text(aJson['nSumMoney0']);
						}
						if(aJson['nSumMoney1'] !== undefined){
							$('.JqCoinBanker.JqSumCoin').attr('data-clip',aJson['nSumMoney1']);
						}
						if(aJson['sMsg'] !== undefined && aJson['sMsg'].indexOf('JqJumpBet')){
							$('.JqJumpBet').on('click',function(){
								$('.JqBlock').removeClass('active');
								$('.JqGameRoadBox,.JqAnalyBox').removeClass('active');
								$('.JqBlock[data-btn="bet"]').addClass('active');
								fnGameSetChipCss();
							});
						}
						break;
					case 'bank':
						$('.JqBlock').removeClass('active');
						$('.JqBlock[data-btn="chatroom"]').addClass('active');
						$(".JqShowArea").append(aJson['sMsg']);						
						$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
						break;
					case 'sticker':
						// let tmpSrc = $('.JqStickerItem[data-num='+aJson['nUse']+']').attr('data-src');
						// // $('.JqWindowBox,.JqBlock').removeClass('active');
						// $('.JqBlock').removeClass('active');
						// $('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
						// $('#JqStickerVideo>source').attr('src',tmpSrc);
						// $('#JqStickerVideo').attr('src',tmpSrc);
						// $('#JqStickerVideo').get(0).play();
						(function(nUse){
							$('.JqStickerVideo').queue(function(){
								// if(gaUser['aData']['sName0'] === 'Gino'){
								// 	alert(1);
								// }
								if($('.JqVideoBtn').hasClass('active')){
									let tmpSrc = $('.JqStickerItem[data-num='+nUse+']').attr('data-src');
									// $('.JqWindowBox,.JqBlock').removeClass('active');
									$('.JqBlock').removeClass('active');
									$('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
									$('.JqStickerVideo>source').attr('src',tmpSrc);
									$('.JqStickerVideo').attr('src',tmpSrc);
									$('.JqStickerVideo').get(0).play();
									// dequq放在sticker.js的$('.JqStickerVideo').on('ended')
								}
							});
						})(aJson['nUse']);
						
						// let tmpSrc = $('.JqStickerItem[data-num='+aJson['nUse']+']').attr('data-src');
						// $('.JqWindowBox,.JqBlock').removeClass('active');
						// $('.JqBlock[data-btn="chatroom"],.JqChatStickerBox').addClass('active');
						// $('.JqStickerVideo>source').attr('src',tmpSrc);
						// $('.JqStickerVideo').attr('src',tmpSrc);
						// $('.JqStickerVideo').get(0).play();
						break;
				}
			}

		}
		
		//發送
		$('.JqChatSubmit').click(function(event)
		{
			console.log(3);
			if (sendMsg())
			{
				// $('textarea[name=JqChatText]').val('');
				//滑到底
				// $('.JqMainBox').animate({scrollTop:$(".JqMsgBox").height()}, 333);

				// $('.JqJumpMsgBox').removeClass('active');

				$('textarea[name=JqChatText]').val('');
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			}
		});
		//按enter發送
		$('textarea[name=JqChatText]').on('keydown', function(e)
		{
			// console.log(1);
			// if(e.keyCode === 13 && !e.shiftKey)
			// {
			// 	e.preventDefault();
			// 	if (sendMsg())
			// 	{
			// 		$('textarea[name=JqChatText]').val('');
			// 		$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			// 	}
			// 	return false;
			// }
		});
		// $('.JqDonateSubmit').on('click',function()
		// {
		// 	let nInputDonate = $('.JqInputDonate').val();
		// 	let nMoney = $('.JqDonateMoney').text();
		// 	let sAllitem = $('.JqDonateBox').attr('data-selectitem');
		// 	// nMoney = 20;
	
		// 	let sUrl = $('input[name=sDonateJWT]').attr('data-url');
		// 	let sJWT = $('input[name=sDonateJWT]').val();
		// 	$('.JqDonateBox').attr('data-selectitem','');
		// 	$('.JqInputDonate').val('');
	
		// 	if(nInputDonate == '')
		// 	{
		// 		console.log(sJWT,nMoney,sAllitem);
		// 	}
		// 	else
		// 	{
		// 		nMoney = nInputDonate;
		// 		sAllitem = '99,';
		// 		console.log(nInputDonate);
		// 	}
			
		// 	$.ajax({
		// 		url: sUrl,
		// 		type: 'POST',
		// 		dataType: 'json',
		// 		data: {
		// 			'sJWT': sJWT,
		// 			'run_page': 1,
		// 			'nMoney': nMoney,
		// 			'sAllitem': sAllitem,
		// 			'sLineId': $('input[name=sLineId]').val(),
		// 			'nGame': $('.JqnGame').val(),
		// 		},
		// 		success: function (oRes) {
		// 			// console.log(oRes);
		// 			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
		// 			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);
		// 			let aMessage = {'nArray':1,'aData':{}};
		// 			$('.JqDonateBox').removeClass('active');
		// 			$('.JqMsgBox ').addClass('active');
		// 			$('.JqDonateMoney').text(0);
		// 			$('.JqWindowBox').removeClass('active');
		// 			$('.JqUserMoney').text(oRes['aUser']['nMoney']);
		// 			fnSocketMsg(oRes['aSocketMsg'],'donate');
		// 			console.log(oRes['aSocketMsg'],'send');
		// 		},
		// 		error: function (exception) {
		// 			console.log('Exeption:' + exception.responseText);
		// 		}
		// 	});
		// });


		fnSocketMsg = function(aSocketMsg,sType){
		// function fnSocketMsg(aSocketMsg,sType){
			// aMessage = {
			// };
			for(let i in aSocketMsg){
				// aMessage[i] = {
				// 	sType: sType,
				// 	nUid: gaUser['aData']['nUid'],
				// 	sName0: gaUser['aData']['sName0'],
				// 	sAccount: gaUser['aData']['sAccount'],
				// 	nTargetUid: 0,
				// 	nRoomId: $('.JqnGame').val(),
				// 	sMsg: aSocketMsg[i]['sMsg'],
				// 	nMsgUid: aSocketMsg[i]['nUid'],
				// 	nRefresh: aSocketMsg[i]['nRefresh'],
				// }
				let aMessage = {
					sType:sType,
					nRoomId:nRoom,
					nUid:gaUser['aData']['nUid'],
					sName0: gaUser['aData']['sName0'],
					sAccount: gaUser['aData']['sAccount'],
					sPicture: gaUser['aData']['sPicture'],
					sMsg: aSocketMsg[i]['sMsg'],
					nMsgUid: aSocketMsg[i]['nUid'],
					nRefresh: aSocketMsg[i]['nRefresh'],
				}
				wk.send(JSON.stringify(aMessage));
			};
		}

		//發送訊息
		function sendMsg(message)
		{
			if (message === undefined)
			{
				var reg = new RegExp('<(br|div|\/div)>','g');
				var sContent0 = $('textarea[name=JqChatText]').val();
				var sentence =[sContent0];
				// var aMatch = CheckKeywords(sentence);

				// if (aMatch.length > 0)
				// {
				// 	$.each(aMatch, function(index, keywords)
				// 	{
				// 		var LPreg = new RegExp(keywords,'g');
				// 		sContent0 = sContent0.replace(LPreg,'<div style="color:#ff0000;">'+keywords+'</div> ');
				// 	});
				// 	$('textarea[name=JqChatText]').html(sContent0);
				// 	$('.JqJumpMsgBox[data-showmsg=0]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['SNOOZEKEYWORDS']);
				// 	$('.JqJumpMsgBox[data-showmsg=0]').addClass('active');
				// 	$('.JqMsgIptBox').removeClass('active');
				// 	$('.JqEmojiImgBox').removeClass('active');
				// }

				console.log(sContent0);
				sContent0 = sContent0.replace(reg,'');
				if (sContent0 != ''/* && aMatch.length == 0*/)
				{
					message = {
						sType: 'chat',
						nUid: gaUser['aData']['nUid'],
						sName0: gaUser['aData']['sName0'],
						sAccount: gaUser['aData']['sAccount'],
						sPicture: gaUser['aData']['sPicture'],
						nTargetUid: 0,
						nRoomId: nRoom,
						sMsg: $('textarea[name=JqChatText]').val(),
					}
				}
			}

			console.log(message);
			if(gaUser['aData']['nMute'] == 1){
				alert('已被管理員禁言');
			}
			else if(gaUser['aData']['nMoney'] <= 0){
				alert('請先充值額度');
			}
			else if (message !== undefined)
			{
				wk.send(JSON.stringify(message));
				console.log('發送訊息 '+ JSON.stringify(message));
				return true;
			}
		}
		//放入訊息
		function sayMsg(data)
		{
			// console.log(data);
			let sHtml = '<div class="betmsg font-xxs msgMember">'+
				'<div class="coverbox">'+
					'<div class="td imgbox float-left"><img class="img-circle" src="'+data['sPicture']+'"></div>'+
					'<div class="td float-left ifmeright msgMemberInf">'+
						'<div class="dtbox">'+
							'<div class="dt-nickname">'+data['sName0']+'</div>'+
						'</div>'+
						'<div class="betmsgBlock arrow">'+
							'<div class="betinfobox">'+
								'<div class="betinfo">'+
									'<div>'+data['sMsg']+'</div>'+
								'</div>'+
							'</div>'+
						'</div>'+
						'<div class="dt-time">'+
							'<div class="dt-timeInner">'+data['sCreateTime']+'</div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
			if(data['nUid'] === gaUser['aData']['nUid']){
				sHtml = sHtml.replace('msgMember"','ifme msgSelf"');
				sHtml = sHtml.replace('dtbox"','dtbox DisplayBlockNone"');
				sHtml = sHtml.replace('td imgbox float-left"','td imgbox float-left DisplayBlockNone "');
				// $('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			}
			let scrollHeight = $(".JqShowArea")[0].scrollHeight;
			let scrollTop = $(".JqShowArea")[0].scrollTop;
			let offsetHeight = $(".JqShowArea")[0].offsetHeight;
			// 塞進聊天室窗
			console.log(sHtml);
			$(".JqShowArea").append(sHtml);
			// 全部訊息都拉到最底
			// console.log($(".JqShowArea")[0].scrollHeight);
			// if(data['nUid'] === gaUser['aData']['nUid']){
			// }
			if((scrollHeight - scrollTop) == offsetHeight)
			{
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
				// console.log('TEST');
			}
		}

		// function fnPushSth()
		// {
		// 	console.log('--1');
		// 	nConnectCountDown--;
		// 	if(nConnectCountDown <= 0)
		// 	{
		// 		console.log('push');
		// 		wk.send('');
		// 		nConnectCountDown = nMaxSec;
		// 	}
		// }
	}
	else
	{
		console.log('Browser 不支援');
	}
})

function fnRunningMsg($MsgBox,nSec,nRatio=100,nDelta=1) {
	if(nDelta == 1){
		nDelta = 100/(nSec*120);
	}
	console.log(nSec,nRatio,nDelta);
	nRatio-=nDelta;
	if (nRatio > -150) {
		console.log('1234');
		requestAnimationFrame(this.fnRunningMsg.bind(this, $MsgBox,nSec,nRatio,nDelta));
	}
	$MsgBox.css('left',nRatio+'%');
}

/*

function fnRunningMsg($MsgBox,nSec,nRatio=100,start=performance.now()) {
	const elapsed = (performance.now() - start);
	nRatio = 100 - elapsed/(nSec*100)*300;

	if (nRatio > -200) {
		console.log(performance.now());
		requestAnimationFrame(this.fnRunningMsg.bind(this, $MsgBox,nSec,nRatio,start));
	}
	$MsgBox.css('left',nRatio+'%');
}
fnRunningMsg($('.JqTest2'),9);

*/