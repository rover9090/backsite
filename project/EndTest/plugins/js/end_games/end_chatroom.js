$(document).ready(function()
{
	$('.JqToggleHeader').on('click' , function()
	{
		$('.JqHeader').toggle();
		$('.JqNavContentContainer').toggleClass('active');
	});

	var bConnect = false;
	const nMaxSec = 15;
	var nConnectCountDown = nMaxSec;

	var nRoom = $('.JqnGame').val();
	$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight},0);

	setTimeout(function(){ location.reload(); }, 5*60*1000);	



	// if(window.WebSocket)
	// {
	// 	// wk = new WebSocket(sUrl);
	// 	// console.log(wk);
	// 	// setInterval(fnPushSth, 1000);

	// 	fnConnect();
	// 	//建立連線
	// 	function fnConnect(){
	// 		wk = new WebSocket(sUrl);
	// 		console.log(wk);
	// 		console.log('123');
	// 		wk.onopen = function(event)
	// 		{
	// 			console.log("伺服器連線成功");
	// 			bConnect = true;
	// 			/**
	// 			 * 連線啟用成功後將對話進行分類
	// 			 * 傳送此房間的ID給SERVER端
	// 			 */

	// 			let auth = {
	// 				sType:'join',
	// 				nRoomId:nRoom,
	// 				nUid:aUser['nUid'],
	// 				sName0: aUser['sName0'],
	// 				sAccount: '',
	// 			}
	// 			console.log(aUser);
	// 			wk.send(JSON.stringify(auth));
	// 			console.log(aUser['sName0']+'加入聊天 發送資訊給Server:'+JSON.stringify(auth));
	// 		}

	// 		//異常
	// 		wk.onerror = function(event)
	// 		{
	// 			console.log("伺服器連線失敗");
	// 		}

	// 		//關閉連線
	// 		wk.onclose = function(event)
	// 		{
	// 			console.log('關閉伺服器連線');
	// 			bConnect = false;
	// 			fnConnect();
	// 		}

	// 		//接收伺服端訊息
	// 		wk.onmessage = function(event)
	// 		{
	// 			let aJson = JSON.parse(event.data);
	// 			console.log('接收Server訊息 '+JSON.stringify(aJson));
	// 			switch(aJson['sType'])
	// 			{
	// 				case 'join':
	// 					console.log(aJson['sName0']+'加入成功');
	// 					break;
	// 				case 'chat':
	// 					sayMsg(aJson);
	// 					break;
	// 				case 'donate':
	// 					sayMsg(aJson);
	// 					break;
	// 			}
	// 		}

	// 	}
		
		
	// 	//發送
	// 	$('.JqSend').click(function(event)
	// 	{
	// 		if (sendMsg())
	// 		{
	// 			$('.JqChat').val('');
	// 			//滑到底
	// 			$('.JqMainBox').animate({scrollTop:$(".JqMsgBox").height()}, 333);

	// 			$('.JqJumpMsgBox').removeClass('active');
	// 		}
	// 	});
	// 	//按enter發送
	// 	$('.JqContent0').on("keydown", function(e)
	// 	{
	// 		if(e.keyCode === 13 && !e.shiftKey)
	// 		{
	// 			e.preventDefault();
	// 			if (sendMsg())
	// 			{
	// 				$('.JqChat').val('');
	// 				//滑到底
	// 				$('.JqMainBox').animate({scrollTop:$(".JqMsgBox").height()}, 333);
	// 				$('.JqJumpMsgBox').removeClass('active');
	// 			}
	// 			return false;
	// 		}
	// 	});

	// 	//發送訊息
	// 	function sendMsg(message)
	// 	{
	// 		if (message === undefined)
	// 		{
	// 			var reg = new RegExp('<(br|div|\/div)>','g');
	// 			var sContent0 = $('.JqChat').val();
	// 			var sentence =[sContent0];
	// 			// var aMatch = CheckKeywords(sentence);

	// 			// if (aMatch.length > 0)
	// 			// {
	// 			// 	$.each(aMatch, function(index, keywords)
	// 			// 	{
	// 			// 		var LPreg = new RegExp(keywords,'g');
	// 			// 		sContent0 = sContent0.replace(LPreg,'<div style="color:#ff0000;">'+keywords+'</div> ');
	// 			// 	});
	// 			// 	$('.JqChat').html(sContent0);
	// 			// 	$('.JqJumpMsgBox[data-showmsg=0]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['SNOOZEKEYWORDS']);
	// 			// 	$('.JqJumpMsgBox[data-showmsg=0]').addClass('active');
	// 			// 	$('.JqMsgIptBox').removeClass('active');
	// 			// 	$('.JqEmojiImgBox').removeClass('active');
	// 			// }

	// 			sContent0 = sContent0.replace(reg,'');
	// 			if (sContent0 != ''/* && aMatch.length == 0*/)
	// 			{
	// 				message = {
	// 					sType: 'chat',
	// 					nUid: aUser['nUid'],
	// 					sName: aUser['sName0'],
	// 					nTargetUid: 0,
	// 					nRoomId: nRoom,
	// 					sMsg: $('.JqChat').val(),
	// 				}
	// 			}
	// 		}

	// 		console.log(message);
	// 		if (message !== undefined)
	// 		{
	// 			wk.send(JSON.stringify(message));
	// 			console.log('發送訊息 '+ JSON.stringify(message));
	// 			return true;
	// 		}
	// 	}
	// 	//放入訊息
	// 	// function sayMsg(data)
	// 	// {
	// 	// 	console.log(data);
	// 	// 	let sHtml = $('.JqCopyOtherMsg').html();
	// 	// 	if (aUser['nUid'] == data['nUid'])
	// 	// 	{
	// 	// 		sHtml = $('.JqCopySelfMsg').html();
	// 	// 	}
	// 	// 	if (data['sMsg'] == '[:invite job:]')
	// 	// 	{
	// 	// 		sHtml = $('.JqCopyInviteMsg').html();
	// 	// 	}

	// 	// 	sHtml = sHtml.replace('[[::nUid::]]',data['nUid']);
	// 	// 	sHtml = sHtml.replace('[[::sMsg::]]',data['sMsg']);
	// 	// 	sHtml = sHtml.replace('[[::sName0::]]',data['sName0']);
	// 	// 	sHtml = sHtml.replace('[[::sCreateTime::]]',data['sCreateTime']);
	// 	// 	sHtml = sHtml.replace('[[::sHeadImage::]]','<img src="'+data['sHeadImage']+'" >');
	// 	// 	// 塞進聊天室窗
	// 	// 	$(".JqShowArea").append(sHtml);
	// 	// }

	// 	// function fnPushSth()
	// 	// {
	// 	// 	console.log('--1');
	// 	// 	nConnectCountDown--;
	// 	// 	if(nConnectCountDown <= 0)
	// 	// 	{
	// 	// 		console.log('push');
	// 	// 		wk.send('');
	// 	// 		nConnectCountDown = nMaxSec;
	// 	// 	}
	// 	// }
	// }
	// else
	// {
	// 	console.log('Browser 不支援');
	// }
})

// function fnRunningMsg($MsgBox,nSec,nRatio=100,nDelta=1) {
// 	if(nDelta == 1){
// 		nDelta = 100/(nSec*120);
// 	}
// 	console.log(nSec,nRatio,nDelta);
// 	nRatio-=nDelta;
// 	if (nRatio > -150) {
// 		console.log('1234');
// 		requestAnimationFrame(this.fnRunningMsg.bind(this, $MsgBox,nSec,nRatio,nDelta));
// 	}
// 	$MsgBox.css('left',nRatio+'%');
// }

// function sayMsg(data)
// {
// 	var aFilter = JSON.parse($('.JqaFilter').val());
// 	console.log(data);
// 	let sHtml = $('.JqCopyOtherMsg').html();
// 	if (aUser['nUid'] == data['nUid'])
// 	{
// 		sHtml = $('.JqCopySelfMsg').html();
// 	}
// 	if (data['sMsg'] == '[:invite job:]')
// 	{
// 		sHtml = $('.JqCopyInviteMsg').html();
// 	}
// 	var sName0 = data['sName0']==''?data['sAccount']:data['sName0'];
// 	// console.log(sHtml);

// 	$.each(aFilter, function(index, keywords)
// 	{
// 		var LPreg = new RegExp(keywords,'gi');
// 		LPsMsg = data['sMsg'].replace(LPreg,'**');
// 		if(LPsMsg != data['sMsg']){
// 			data['sMsg'] = LPsMsg;
// 		}
// 	});

// 	if(data['sName0'] == 'system' && data['sMsg'].indexOf("打賞") >=0 )
// 	{
// 		sHtml = sHtml.replace('FontWhite','FontYellow');
// 	}
// 	sHtml = sHtml.replace('[[::nUid::]]',data['nUid']);
// 	sHtml = sHtml.replace('[[::sMsg::]]',data['sMsg']);
// 	sHtml = sHtml.replace('[[::sName0::]]',data['sName0']);
// 	sHtml = sHtml.replace('[[::sCreateTime::]]',data['sCreateTime']);
// 	sHtml = sHtml.replace('[[::sHeadImage::]]','<img src="'+data['sHeadImage']+'" >');
// 	// 塞進聊天室窗
// 	$(".JqShowArea").append(sHtml);
// 	$(".JqShowArea").animate({ scrollTop: $(".JqShowArea")[0].scrollHeight}, 1);
// }
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