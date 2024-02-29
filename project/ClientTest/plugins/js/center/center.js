
$(document).ready(function()
{
	$('.JqCenter').click(function() {

		let sUrl = $('input[name=sCenterJWT]').attr('data-url');

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'run_page': 1,
			},
			success: function (oRes) {
				$('.JqWinRate').text(oRes['aData']['nRate']);

			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqBetRanking').click(function() {

		let sUrl = $('input[name=sBetRankingJWT]').attr('data-url');
		let sJWT = $('input[name=sBetRankingJWT]').val();

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'run_page': 1,
			},
			success: function (aData) {
				$.each(aData['aData'], function(rank,aDetail){
					$('.JqRankName'+rank).text(aDetail['sName0']);
					$('.JqRankMoney'+rank).text(aDetail['nProfit']);
				});

			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqUpdateUser').click(function() {

		let sUrl = $('.JqUpdateUserJWT').attr('data-url');
		let sJWT = $('.JqUpdateUserJWT').val();
		let sName0 = $('.JqUpdateUserName0').val();
		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'run_page': 1,
				'sName0':sName0,
				'sJWT':sJWT,
			},
			success: function (oRes) {
				if(oRes['nError'] > 0){
					$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
					$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);
				}
				else{
					location.reload();
				}

			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});
});
