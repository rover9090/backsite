$(document).ready(function()
{
	$('.JqChatSubmit').on('click',function()
	{
		let sUrl = $('input[name=sChatJWT]').attr('data-url');
		let sJWT = $('input[name=sChatJWT]').val();

		if($('textarea[name=JqChatText]').val() === ''){
			return;
		}
		if(gaUser['aData']['nMute'] == 1){
			return;
		}
		else if(gaUser['aData']['nMoney'] <= 0){
			return;
		}

		if(gaUser['aData']['nMute'] == 1){
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['BANMESSAGE']);
		}

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
				'sText': $('textarea[name=JqChatText]').val(),
				'sLineId': $('input[name=sLineId]').val(),
				'nGame': $('.JqnGame').val(),
			},
			success: function (oRes) {
				$('textarea[name=JqChatText]').val('');
				gaJsonMsg[oRes['aData']['nMicroTime']] = 0;
				// $(".JqShowArea").append(oRes['aData']['sSendMsg']);
				// $('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});


	$('textarea[name=JqChatText]').on('keydown',function(){
		const keyCode = event.which || event.keyCode;
		if (keyCode === 13 && !event.shiftKey) {
			// Don't generate a new line
			event.preventDefault();

			// Do something else such as send the message to back-end ...
			$('.JqChatSubmit').click();
		}
	});

});