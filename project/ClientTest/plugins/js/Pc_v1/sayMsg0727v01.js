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
		sHtml = sHtml.replace(LPreg,'<span style="background-color:black;">'+keywords+'</span>');
	});
	
	// 塞進聊天室窗
	$(".JqShowArea").append(sHtml);
	$('.JqShowArea').animate({scrollTop:$('.JqShowArea').outerHeight()}, 333);
}