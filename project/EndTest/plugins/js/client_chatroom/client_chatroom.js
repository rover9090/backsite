
$(document).ready(function()
{
	setInterval(function ()
	{
		CheckMsg();
	},5000);

	// 關閉某聊天室
	$('.JqUserBox').on('click','.JqCloseChat', function(event)
	{
		var sA = 'DEL';
		var nId = $(this).parent().data('id');
		var nOpenId = $('.JqChatMessage').data('id');
		/*
			1.do ajax update to 0
			2.apend new data to userbox
			3.update current message box
		*/
		$.ajax({
			url: $('input[name=sAjax]').val(),
			type: "POST",
			dataType: "json",
			data: {
					'a': sA+nId,
					'nId': nId,
				},
			success: function (data)
			{
				var sHtml = '';
				if (data.nStatus == 1)
				{
					/* update userbox */
					$.each(data.aData,function(LPnId, LPaData)
					{
						var LPsActive = '';
						if (LPaData.nId == nOpenId)
						{
							LPsActive = 'active';
						}
						sHtml += '<div data-id="'+LPaData.nId+'" class="chatUser '+LPsActive+'"> ';
						sHtml += '<div class="BtnAny2 JqCloseChat">X</div> ';
						sHtml += '<span class="JqChatAccount">'+LPaData.sAccount+'</span> ';
						sHtml += '<span>('+LPaData.nAdminStatus+')</span> ';
						sHtml += '</div>';
					});
					$('.JqUserBox').empty().append(sHtml);

					/* update message box */
					var thisData = data.aData.nOpenId;
					if (typeof thisData !== 'undefined')
					{
						sHtml = '<div class="chatMsgTop">';
						sHtml += 	'<div class="chatMsgUser">'+ sUserName +'</div>';
						sHtml += '</div>';
						sHtml += '<div class="chatMsgInner">';
						sHtml += 	'<div class="chatMsgScroll">';
						$.each(thisData.aChat,function(LPid, LPaChat)
						{
							var LPsWho = 'UserMsg';
							if (LPaChat.nWho == 1)
							{
								LPsWho = 'AdmMsg';
							}
							sHtml +='<div class="serviceList Table '+LPsWho+'">';/* AdmMsg UserMsg */
							sHtml +=	'<div>';
							sHtml +=		'<div>';
							sHtml +=			'<div class="serviceListInf">';
							sHtml +=				'<div class="serviceListBot">';
							if (LPaChat.nWho == 1)
							{
								sHtml +=					'<div class="serviceListTime">';
								sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
								sHtml +=					'</div>';
								sHtml +=					'<div class="serviceListMsgBox">';
								sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
								sHtml +=					'</div>';
							}
							else
							{
								sHtml +=					'<div class="serviceListMsgBox">';
								sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
								sHtml +=					'</div>';
								sHtml +=					'<div class="serviceListTime">';
								sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
								sHtml +=					'</div>';
							}
							sHtml +=				'</div>';
							sHtml +=			'</div>';
							sHtml +=		'</div>';
							sHtml +=	'</div>';
							sHtml +='</div>';
						});
						sHtml += 	'</div>';
						sHtml += '</div>';
						$('.JqChatBox').addClass('active');
						$('.JqChatMessage').empty().append(sHtml);
					}
					else
					{
						$('.JqChatBox').removeClass('active');
						$('.JqChatMessage').empty();
					}
					/* message box scroll to bottom */
				}
			},
			error: function (txt)
			{
				console.log(txt);
			}
		});
	});

	// 開啟某聊天室
	$('.JqUserBox').on('click','.JqChatAccount', function(event)
	{
		var sA = 'GetData';
		var nId = $(this).parent().data('id');
		var sUserName = $(this).text();
		$('.chatUser').removeClass('active');
		$(this).parent().addClass('active');
		$('.JqChatMessage').attr('data-id', nId);
		/*
			1.do ajax get this id message data
			2.apend new data to message box
		*/
		$.ajax({
			url: $('input[name=sAjax]').val(),
			type: "POST",
			dataType: "json",
			data: {
					'a':  sA+nId,
					'nId': nId,
				},
			success: function (data)
			{
				var sHtml = '';
				if (data.nStatus == 1)
				{
					/* update message box */
					sHtml += '<div class="chatMsgTop">';
					sHtml += 	'<div class="chatMsgUser">'+ sUserName +'</div>';
					sHtml += '</div>';
					sHtml += '<div class="chatMsgInner">';
					sHtml += 	'<div class="chatMsgScroll">';
					$.each(data.aData,function(LPid, LPaChat)
					{
						var LPsWho = 'UserMsg';
						if (LPaChat.nWho == 1)
						{
							LPsWho = 'AdmMsg';
						}
						sHtml +='<div class="serviceList Table '+LPsWho+'">';/* AdmMsg UserMsg */
						sHtml +=	'<div>';
						sHtml +=		'<div>';
						sHtml +=			'<div class="serviceListInf">';
						sHtml +=				'<div class="serviceListBot">';
						if (LPaChat.nWho == 1)
						{
							sHtml +=					'<div class="serviceListTime">';
							sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
							sHtml +=					'</div>';
							sHtml +=					'<div class="serviceListMsgBox">';
							sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
							sHtml +=					'</div>';
						}
						else
						{
							sHtml +=					'<div class="serviceListMsgBox">';
							sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
							sHtml +=					'</div>';
							sHtml +=					'<div class="serviceListTime">';
							sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
							sHtml +=					'</div>';
						}
						sHtml +=				'</div>';
						sHtml +=			'</div>';
						sHtml +=		'</div>';
						sHtml +=	'</div>';
						sHtml +='</div>';
					});
					sHtml += 	'</div>';
					sHtml += '</div>';
					$('.JqChatBox').addClass('active');
					$('.JqChatMessage').empty().append(sHtml);
					/* message box scroll to bottom */
				}
			},
			error: function (txt)
			{
				console.log(txt);
			}
		});
	});

	// 傳送訊息
	$('.JqSendMsg').on('click', function(event)
	{
		var sA = 'Reply';
		// var nId = $('.JqChatMessage').data('id');
		var nId = $('.JqChatMessage').attr('data-id');
		var sText = $('[name=sText]').val();
		var sUserName = $('.chatUser.active').find('.JqChatAccount').text();
		if (sText != '') // 13 = enter
		{
			var data = {
				'sA' : sA,
				'nId' : nId,
				'sText' : sText,
				'sUserName' : sUserName,
			}
			SendMsg(data);
		}
	});

	// 傳送訊息 enter
	// $('.JqMessage').on('keydown', function(event)
	// {
	// 	var sA = 'Reply';
	// 	var nId = $('.JqChatMessage').data('id');
	// 	var sText = $('[name=sText]').val();
	// 	var sUserName = $('.chatUser.active').find('.JqChatAccount').text();
	// 	// console.log(sText);
	// 	if (sText != '' && event.keyCode == 13 ) // 13 = enter
	// 	{
	// 		var data = {
	// 			'sA' : sA,
	// 			'nId' : nId,
	// 			'sText' : sText,
	// 			'sUserName' : sUserName,

	// 		}
	// 		SendMsg(data);
	// 	}
	// });

	//常用訊息
	$('.JqCannedMsg').on('click', function(event)
	{
		var sText = $('[name=sText]').val();
		$('[name=sText]').val(sText+$(this).text());
	});
})

function SendMsg(data)
{
	var sA = data.sA;
	var nId = data.nId;
	var sText = data.sText;
	var sUserName = data.sUserName;
	$.ajax({
		url: $('input[name=sAjax]').val(),
		type: "POST",
		dataType: "json",
		data: {
				'a':  sA+nId,
				'nId': nId,
				'sText': sText,
			},
		success: function (data)
		{
			var sHtml = '';
			if (data.nStatus == 1)
			{
				$('[name=sText]').val('');
				/* update message box */
				sHtml += '<div class="chatMsgTop">';
				sHtml += 	'<div class="chatMsgUser">'+ sUserName +'</div>';
				sHtml += '</div>';
				sHtml += '<div class="chatMsgInner">';
				sHtml += 	'<div class="chatMsgScroll">';
				$.each(data.aData,function(LPid, LPaChat)
				{
					var LPsWho = 'UserMsg';
					if (LPaChat.nWho == 1)
					{
						LPsWho = 'AdmMsg';
					}
					sHtml +='<div class="serviceList Table '+LPsWho+'">';/* AdmMsg UserMsg */
					sHtml +=	'<div>';
					sHtml +=		'<div>';
					sHtml +=			'<div class="serviceListInf">';
					sHtml +=				'<div class="serviceListBot">';
					if (LPaChat.nWho == 1)
					{
						sHtml +=					'<div class="serviceListTime">';
						sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
						sHtml +=					'</div>';
						sHtml +=					'<div class="serviceListMsgBox">';
						sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
						sHtml +=					'</div>';
					}
					else
					{
						sHtml +=					'<div class="serviceListMsgBox">';
						sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
						sHtml +=					'</div>';
						sHtml +=					'<div class="serviceListTime">';
						sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
						sHtml +=					'</div>';
					}
					sHtml +=				'</div>';
					sHtml +=			'</div>';
					sHtml +=		'</div>';
					sHtml +=	'</div>';
					sHtml +='</div>';
				});
				sHtml += 	'</div>';
				sHtml += '</div>';
				$('.JqChatBox').addClass('active');
				$('.JqChatMessage').empty().append(sHtml);
				/* message box scroll to bottom */
			}
		},
		error: function (txt)
		{
			console.log(txt);
		}
	});
}

function CheckMsg()
{
	var sA = 'CheckData';
	var nOpenId = $('.JqChatMessage').attr('data-id');
	var sUserName = $('.chatUser.active').find('.JqChatAccount').text();
	/*
		1.do ajax get today's message data
		2.apend new data to userbox
		3.update current message box
	*/
	$.ajax({
		url: $('input[name=sAjax]').val(),
		type: "POST",
		dataType: "json",
		data: {
				'a': sA,
				'nId': nOpenId,
			},
		success: function (data)
		{
			var sHtml = '';
			if (data.nStatus == 1)
			{
				/* update userbox */
				$.each(data.aData.aChatUser,function(LPnId, LPaData)
				{

					var LPsActive = '';
					if (LPaData.nId == nOpenId)
					{
						LPsActive = 'active';
					}
					sHtml += '<div data-id="'+LPaData.nId+'" class="chatUser '+LPsActive+'"> ';
					sHtml += '<div class="BtnAny2 JqCloseChat">X</div> ';
					sHtml += '<span class="JqChatAccount">'+LPaData.sAccount+'</span> ';
					sHtml += '<span>('+LPaData.nAdminStatus+')</span> ';
					sHtml += '</div>';
				});
				$('.JqUserBox').empty().append(sHtml);

				/* update message box */
				sHtml = '<div class="chatMsgTop">';
				sHtml += 	'<div class="chatMsgUser">'+ sUserName +'</div>';
				sHtml += '</div>';
				sHtml += '<div class="chatMsgInner">';
				sHtml += 	'<div class="chatMsgScroll">';
				$.each(data.aData.aChatMessage,function(LPid, LPaChat)
				{
					var LPsWho = 'UserMsg';
					if (LPaChat.nWho == 1)
					{
						LPsWho = 'AdmMsg';
					}
					sHtml +='<div class="serviceList Table '+LPsWho+'">';/* AdmMsg UserMsg */
					sHtml +=	'<div>';
					sHtml +=		'<div>';
					sHtml +=			'<div class="serviceListInf">';
					sHtml +=				'<div class="serviceListBot">';
					if (LPaChat.nWho == 1)
					{
						sHtml +=					'<div class="serviceListTime">';
						sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
						sHtml +=					'</div>';
						sHtml +=					'<div class="serviceListMsgBox">';
						sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
						sHtml +=					'</div>';
					}
					else
					{
						sHtml +=					'<div class="serviceListMsgBox">';
						sHtml +=						'<div class="serviceListMsg">'+LPaChat.sText+'</div>';
						sHtml +=					'</div>';
						sHtml +=					'<div class="serviceListTime">';
						sHtml +=						'<div class="serviceListTimeTxt">'+LPaChat.sTime+'</div>';
						sHtml +=					'</div>';
					}
					sHtml +=				'</div>';
					sHtml +=			'</div>';
					sHtml +=		'</div>';
					sHtml +=	'</div>';
					sHtml +='</div>';
				});
				sHtml += 	'</div>';
				sHtml += '</div>';
				$('.JqChatBox').addClass('active');
				$('.JqChatMessage').empty().append(sHtml);

				/* message box scroll to bottom */
			}
		},
		error: function (txt)
		{
			console.log(txt);
		}
	});
}