$(document).ready(function()
{
	$('.JqDetailBox').on('click', function()
	{
		let sUrl = $('input[name=sHistoryJWT]').attr('data-url');
		let sType = $(this).attr('data-type');
		let sLineId = $('input[name=sLineId]').val();
		$(".JqInfo").html('');

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				// 'sJWT': sJWT,
				'sType': sType,
				'sLineId': sLineId,
				'run_page': 1,
			},
			success: function (oRes) {
				// console.log(oRes['aData']['aBetData']);
				let sHtml = '';
				$.each(oRes['aData']['aBetData'],function(i,item){

					// sClass = '';
					// if(item.nStatus == 1)
					// {
					// 	sClass = 'FontOrange';
					// }

					sHtml+='<tr>';
					sHtml+='<td>'+item.nId+'</td>';
					sHtml+='<td>'+item.sCreateTime+'</td>';
					sHtml+='<td>'+item.sContent0+'</td>';
					sHtml+='<td>'+item.nMoney1+'</td>';
					sHtml+='<td>'+item.sStatus+'</td>';
					sHtml+='</tr>';
				});
				$(".JqInfo").append($(sHtml));
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});
})