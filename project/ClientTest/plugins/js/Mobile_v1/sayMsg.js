function sayMsg(data)
{
	var aFilter = JSON.parse($('.JqaFilter').val());
	console.log(data);
	let sHtml = $('.JqCopyOtherMsg').html();
	if (gaUser['aData']['nUid'] == data['nUid'])
	{
		sHtml = $('.JqCopySelfMsg').html();
	}
	if (data['sMsg'] == '[:invite job:]')
	{
		sHtml = $('.JqCopyInviteMsg').html();
	}
	var sName0 = data['sName0']==''?data['sAccount']:data['sName0'];
	var sContent0 = data['sMsg'];

	$.each(aFilter, function(index, keywords)
	{
		var LPreg = new RegExp(keywords,'gi');
		LPsMsg = sContent0.replace(LPreg,'**');
		if(LPsMsg != sContent0){
			sContent0 = LPsMsg;
		}
	});

	if(gaUser['aData']['nMute'] == 1){
		$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
		$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['BANMESSAGE']);
		$('.JqChat').val('');
	}
	else if(gaUser['aData']['nMoney'] <= 0){
		$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
		$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['CHARGEFIRST']);
		$('.JqChat').val('');
	}
	else if (sContent0.length > 0)
	{
		if (sContent0.length > 0 || $('input[name=nImgCount').val() > 0)
		{

			if (sContent0.length > 0) // 文字訊息
			{
				sHtml = sHtml.replace('[[::nUid::]]',data['nUid']);
				sHtml = sHtml.replace('[[::sMsg::]]',sContent0);
				sHtml = sHtml.replace('[[::sName0::]]',sName0);
				sHtml = sHtml.replace('[[::sCreateTime::]]',data['sCreateTime']);
				sHtml = sHtml.replace('[[::sHeadImage::]]','<img src="'+data['sHeadImage']+'" >');

				// Ms_CMD_Logic.SendMessage(sContent0);

				// var data=[0,gaUser['aData']['nUid'],gaUser['aData']['sName0'],sContent0,nGid];
				// console.log(data);
				//0=訊息type,1=nUid,2=name,3=msg,4=nGame
				// $(".JqShowArea").append(SayMessage('self',data));
				$(".JqShowArea").append(sHtml);
				$('.JqChat').val('');
			}

			if ($('input[name=nImgCount').val() > 0) // 圖片訊息  現在沒有先放者
			{
				// 先上傳再寄 url
				UploadFile();

				$('input[name=nImgCount').val(0); // 圖片歸0
				$('.JqFile.DisplayBlockNone').remove();
				$('.JqEmojiContentPhotoBox').empty();
				$('.JqEmojiContentPhotoBox').removeClass('active');
			}

			//滑到底
			$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			$('.JqJumpMsgBox').removeClass('active');
			// $('html').animate({scrollTop:$('html').height()}, 333);
			// $('body').removeClass('active');
			// $('.JqMsgIptBox').removeClass('active');
			// $('.JqEmojiImgBox').removeClass('active');
			// $('.JqPhotoOtherBox').removeClass('active'); // 因上傳圖片會擋住原本對話,所以塞個class給他,但送出後必須拉掉
		}
	}

	
	// 塞進聊天室窗
	// $(".JqShowArea").append($(sHtml));
	// $('.JqShowArea').animate({scrollTop:$('.JqShowArea').outerHeight()}, 333);
	// fnRunningMsg($(sHtml),3);
}



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