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
					sHtml+='<td><span class="scale08">'+item.sCreateTime+'</span></td>';
					sHtml+='<td><div class="scale08">'+item.sNo+'</div><div>'+item.sContent0+'</div><div>'+item.nMoney0+'</div></td>';
					sHtml+='<td><span class="scale08">'+item.nMoney1+'</span></td>';
					sHtml+='<td class=""chkprize betDetailTxt"">'+item.sStatus+'</td>';
					sHtml+='</tr>';
				});
				$(".JqInfo").append($(sHtml));
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('[data-kindctrl="road"]').on('click', function()
	{
		let sUrl = $('input[name="sRoadJWT"]').attr('data-url');
		let sJWT = $('input[name="sRoadJWT"]').val();
		let sLineId = $('input[name=sLineId]').val();
		$('[data-kind="road"]').toggleClass('active');
		if($('[data-kind="road"]').hasClass('active')){
			nHeightPx = parseInt($('[data-kind="road"]').height());
			nBottomPx = parseInt($('.JqShowArea').css('bottom'));
			$('.JqShowArea').css('bottom',(nBottomPx+96)+'px');
		}
		else{			
			nHeightPx = parseInt($('[data-kind="road"]').height());
			nBottomPx = parseInt($('.JqShowArea').css('bottom'));
			console.log(nBottomPx);
			$('.JqShowArea').css('bottom',(nBottomPx-96)+'px');
		}

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'sLineId': sLineId,
				'run_page': 1,
			},
			success: function (oRes) {
				for(let i=0;i<15;i++){
					if(oRes['aData']['A'][i] === undefined) continue;
					$('.JqRoadResultA'+i).text(oRes['aData']['A'][i]['sText']);
					$('.JqRoadResultB'+i).text(oRes['aData']['B'][i]['sText']);
					$('.JqRoadResultC'+i).text(oRes['aData']['C'][i]['sText']);
					$('.JqRoadResultA'+i).removeClass('FontRedImp FontBlueImp FontGreenImp');
					$('.JqRoadResultB'+i).removeClass('FontRedImp FontBlueImp FontGreenImp');
					$('.JqRoadResultC'+i).removeClass('FontRedImp FontBlueImp FontGreenImp');
					$('.JqRoadResultA'+i).addClass(oRes['aData']['A'][i]['sClass']);
					$('.JqRoadResultB'+i).addClass(oRes['aData']['B'][i]['sClass']);
					$('.JqRoadResultC'+i).addClass(oRes['aData']['C'][i]['sClass']);
				}
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqNumHistory').on('click', function()
	{
		let sUrl = $('input[name="sRoadJWT"]').attr('data-url');
		let sJWT = $('input[name="sRoadJWT"]').attr('data-historyJWT');
		let sLineId = $('input[name=sLineId]').val();
		let sType = $(this).attr('data-type');

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'sJWT': sJWT,
				'sType': sType,
				'sLineId': sLineId,
				'run_page': 1,
			},
			success: function (oRes) {
				let sHtml = '';
				// JS遇到數字key陣列就是變成由小到大
				for(let LPsKey in oRes['aData']){
					sHtml = oRes['aData'][LPsKey]+sHtml;
				}
				$('div[data-btn="history"]').find('tbody.JqInfo').html(sHtml);

				$('div[data-btn="history"]').find('a[data-showimg]').on('click',function()
				{
					let sImgUrl = $(this).attr('data-showimg');
					// $('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
					// $('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').html('<img src="'+sImgUrl+'" class="" style="width: 100%;">');
					$('.JqJumpMsgBox[data-showmsg="msg9Box"]').addClass('active');
					$('.JqJumpMsgBox[data-showmsg="msg9Box"]').find('.JqJumpMsgContentArticle').html('<img src="'+sImgUrl+'" class="" style="width: 100%;">');
				});			
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqBankerDetailBox').on('click', function()
	{
		let sUrl = $('input[name=sBankerHistoryJWT]').attr('data-url');
		let sType = $(this).attr('data-type');
		let sLineId = $('input[name=sLineId]').val();
		$(".JqBankerInfo").html('');

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
				let sHtml = '';
				$.each(oRes['aData']['aBetData'],function(i,item){

					// sClass = '';
					// if(item.nStatus == 1)
					// {
					// 	sClass = 'FontOrange';
					// }

					sHtml+='<tr>';
					sHtml+='<td>'+item.nId+'</td>';
					sHtml+='<td><span class="scale08">'+item.sCreateTime+'</span></td>';
					sHtml+='<td><div class="scale08">'+item.sNo+'</div><div>'+item.nMoney0+'</div></td>';
					sHtml+='<td><span class="scale08">'+item.nMoney1+'</span></td>';
					sHtml+='</tr>';
				});
				$(".JqBankerInfo").append($(sHtml));
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	$('.JqBetHistoty').on('click', function()
	{
			$('#mybankerhistory').removeClass('active');
			$('#jackpothistory').removeClass('active');
			$('#myhistory').addClass('active');
	});

	$('.JqBankerHistoty').on('click', function()
	{
			$('#myhistory').removeClass('active');
			$('#jackpothistory').removeClass('active');
			$('#mybankerhistory').addClass('active');
	});

	$('.JqJackPotHistoty').on('click', function()
	{
			$('#myhistory').removeClass('active');
			$('#mybankerhistory').removeClass('active');
			$('#jackpothistory').addClass('active');
	});
})