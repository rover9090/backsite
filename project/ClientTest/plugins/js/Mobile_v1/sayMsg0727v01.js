function sayMsg(data)
{
	var aFilter = JSON.parse($('.JqaFilter').val());
	console.log(data);
	let sHtml = $('.JqCopyOtherMsg').html();
	if (aUser['nUid'] == data['nUid'])
	{
		sHtml = $('.JqCopySelfMsg').html();
	}
	if (data['sMsg'] == '[:invite job:]')
	{
		sHtml = $('.JqCopyInviteMsg').html();
	}

	sHtml = sHtml.replace('[[::nUid::]]',data['nUid']);
	sHtml = sHtml.replace('[[::sMsg::]]',data['sMsg']);
	sHtml = sHtml.replace('[[::sName0::]]',data['sName0']);
	sHtml = sHtml.replace('[[::sCreateTime::]]',data['sCreateTime']);
	sHtml = sHtml.replace('[[::sHeadImage::]]','<img src="'+data['sHeadImage']+'" >');

	$.each(aFilter, function(index, keywords)
	{		
		var LPreg = new RegExp(keywords,'g');
		sHtml = sHtml.replace(LPreg,'<span style="background-color:yellow;">'+keywords+'</span>');
	});
	
	// 塞進聊天室窗
	$(".JqShowArea").append($(sHtml));
	$('.JqShowArea').animate({scrollTop:$('.JqShowArea').outerHeight()}, 333);
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