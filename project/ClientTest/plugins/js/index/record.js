$(document).ready(function()
{
      // 下注紀錄 打賞紀錄

	$('.JqCheckRecord').on('click', function()
	{
		let FirstDonatesUrl = $('input[name=sDonateHistoryJWT]').attr('data-url');
		let FirstHistorysUrl = $('input[name=sHistoryJWT]').attr('data-url');
		$(".JqDonateInfo").html('');
		$(".JqInfo").html('');
		$(".JqnDonateSubTotal").html('0');
		$(".JqBetSubTotal").html('0');
		$(".JqWinSubTotal").html('0');

		$.ajax({
			url: FirstDonatesUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				// 'sJWT': sJWT,
				'nStart': 0,
				'nEnd': 10,
				'run_page': 1,
			},
			success: function (oRes) {
				// console.log(oRes['aData']['aDonateData']);
				let sHtml = '';
				$.each(oRes['aData']['aDonateData'],function(i,item){

					sHtml+='<tr>';
					sHtml+='<td class="infoTd">'+item.nId+'</td>';
					sHtml+='<td class="infoTd">'+item.sCreateTime+'</td>';
					sHtml+='<td class="infoTd">'+item.nMoney0+'</td>';
					sHtml+='<td class="infoTd">'+item.sName0+'</td>';
					sHtml+='</tr>';
				});
				$(".JqDonateInfo").append($(sHtml));
				nDonateSubTotal = $(".JqnDonateSubTotal").text();
				$(".JqnDonateSubTotal").html(Number(nDonateSubTotal)+oRes['aData']['nDonateSubTotal']);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});

		$.ajax({
			url: FirstHistorysUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				// 'sJWT': sJWT,
				'nStart': 0,
				'nEnd': 10,
				'run_page': 1,
			},
			success: function (oRes) {
				// console.log(oRes['aData']['aBetData']);
				let sHtml = '';
				$.each(oRes['aData']['aBetData'],function(i,item){

					sClass = '';
					if(item.nStatus == 1)
					{
						sClass = 'FontOrange';
					}

					sHtml+='<tr class="JqWindowBtn JqDetail" data-nid="'+item.nId+'" data-kindctrl="15">';
					sHtml+='<td class="infoTd">';
					sHtml+='<div>'+item.nId+'</div>';
					sHtml+='<div>'+item.sCreateTime+'</div>';
					sHtml+='</td>';
					sHtml+='<td class="infoTd">';
					sHtml+='<div>'+item.sContent0+'</div>';
					sHtml+='<div>'+item.nMoney0+'</div>';
					sHtml+='</td>';
					sHtml+='<td class="infoTd '+sClass+'">';
					sHtml+='<div>'+item.sStatus+'</div>';
					sHtml+='<div>'+item.nMoney2+'</div>';
					sHtml+='</td>';
					sHtml+='</tr>';
				});
				$(".JqInfo").append($(sHtml));
				nBetSubTotal = $(".JqBetSubTotal").text();
				nWinSubTotal = $(".JqWinSubTotal").text();
				$(".JqBetSubTotal").html(Number(nBetSubTotal)+oRes['aData']['nBetSubTotal']);
				$(".JqWinSubTotal").html(Number(nWinSubTotal)+oRes['aData']['nWinSubTotal']);
			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});

		// 下捲之後的動作
		nRange = 10;
		nStart = 10;
		nDonateStart = 10;

	});

	// 下捲之後的動作
	$('.JqRecord').scroll(function(){
	
		if($('.JqRecord').scrollTop() != 0)
		{
			if($('.JqDonateRecord').hasClass('active'))
			{
				let sUrl = $('input[name=sDonateHistoryJWT]').attr('data-url');
	
				if ($('.JqRecord').height() + $('.JqRecord').scrollTop() >= $('.JqRecord').prop('scrollHeight')-30) 
				{
					// console.log($('.JqRecord').height() + $('.JqRecord').scrollTop());
					var nDonateEnd = nDonateStart + nRange;
					$.ajax({
						url: sUrl,
						type: 'POST',
						dataType: 'json',
						data: {
							// 'sJWT': sJWT,
							'nStart': nDonateStart,
							// 'nEnd': nDonateEnd,
							'run_page': 1,
						},
						success: function (oRes) {
							// console.log(oRes['aData']['aDonateData']);
							let sHtml = '';
							$.each(oRes['aData']['aDonateData'],function(i,item){
		
								sHtml+='<tr>';
								sHtml+='<td class="infoTd">'+item.nId+'</td>';
								sHtml+='<td class="infoTd">'+item.sCreateTime+'</td>';
								sHtml+='<td class="infoTd">'+item.nMoney0+'</td>';
								sHtml+='<td class="infoTd">'+item.sName0+'</td>';
								sHtml+='</tr>';
							});
							$(".JqDonateInfo").append($(sHtml));
							if(oRes['aData']['nDonateSubTotal'] != 0)
							{
								nDonateSubTotal = $(".JqnDonateSubTotal").text();
								$(".JqnDonateSubTotal").html(Number(nDonateSubTotal)+oRes['aData']['nDonateSubTotal']);
							}
						},
						error: function (exception) {
							console.log('Exeption:' + exception.responseText);
						}
					});
					nDonateStart = nDonateEnd;
				}
			}
			else
			{
				let sUrl = $('input[name=sHistoryJWT]').attr('data-url');

				if ($('.JqRecord').height() + $('.JqRecord').scrollTop() >= $('.JqRecord').prop('scrollHeight')-30) 
				{
					nEnd = nStart + nRange;
					$.ajax({
						url: sUrl,
						type: 'POST',
						dataType: 'json',
						data: {
							// 'sJWT': sJWT,
							'nStart': nStart,
							// 'nEnd': nEnd,
							'run_page': 1,
						},
						success: function (oRes) {
							// console.log(oRes['aData']['aBetData']);
							let sHtml = '';
							$.each(oRes['aData']['aBetData'],function(i,item){
		
								sClass = '';
								if(item.nStatus == 1)
								{
									sClass = 'FontOrange';
								}
		
								sHtml+='<tr>';
								sHtml+='<td class="infoTd">';
								sHtml+='<div>'+item.nId+'</div>';
								sHtml+='<div>'+item.sCreateTime+'</div>';
								sHtml+='</td>';
								sHtml+='<td class="infoTd">';
								sHtml+='<div>'+item.sContent0+'</div>';
								sHtml+='<div>'+item.nMoney0+'</div>';
								sHtml+='</td>';
								sHtml+='<td class="infoTd '+sClass+'">';
								sHtml+='<div>'+item.sStatus+'</div>';
								sHtml+='<div>'+item.nMoney2+'</div>';
								sHtml+='</td>';
								sHtml+='</tr>';
							});
							$(".JqInfo").append($(sHtml));
							nBetSubTotal = $(".JqBetSubTotal").text();
							nWinSubTotal = $(".JqWinSubTotal").text();
							if(oRes['aData']['nBetSubTotal'] != 0)
							{
								$(".JqBetSubTotal").html(Number(nBetSubTotal)+oRes['aData']['nBetSubTotal']);
								$(".JqWinSubTotal").html(Number(nWinSubTotal)+oRes['aData']['nWinSubTotal']);
							}
						},
						error: function (exception) {
							console.log('Exeption:' + exception.responseText);
						}
					});
					nStart = nEnd;
				}
			}
		}
	});

	$('.JqRecordClose').on('click', function()
	{
		$(".JqDonateInfo").html('');
		$(".JqInfo").html('');
	});

	$("div.recordBox  div.infoBox  table").delegate('tr', 'click', function()
	{
		$(".JqDetailinfo").html('');

		$('.JqWindowBox[data-kind="'+$(this).attr('data-kindctrl')+'"]').addClass("active");
		let nBetId = $(this).attr('data-nid');
		let sUrl = $('input[name=sBetDetailJWT]').attr('data-url');

		$.ajax({
			url: sUrl,
			type: 'POST',
			dataType: 'json',
			data: {
				'nBetId': nBetId,
				'run_page': 1,
			},
			success: function (oRes) {
				// console.log(oRes['aData']['aBetData']);
				let sHtml = '';

				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['IDS']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['nId']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['GAMENAME']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['sGame']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['ROOM']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['nGame']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['BETTIME']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['sCreateTime']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['SETTLETIME']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['sUpdateTime']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['BETMONEY']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['nMoney0']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['WINMONEY']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['nMoney2']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['BETCONTENT']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['sContent0']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['GAMERESULT']+'</td>';
				sHtml+='<td class="infoTd">'+oRes['aData']['aBetData']['sNums']+'</td>';
				sHtml+='</tr>';
				sHtml+='<tr>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['CARDRESULT']+'</td>';
				sHtml+='<td class="infoTd">'+aJSDEFINE['BANKER']+'：'+oRes['aData']['aBetData']['sBanker']+'<br>'+aJSDEFINE['PLAYER']+'：'+oRes['aData']['aBetData']['sPlayer']+'</td>';
				sHtml+='</tr>';

				$(".JqDetailinfo").append($(sHtml));

			},
			error: function (exception) {
				console.log('Exeption:' + exception.responseText);
			}
		});
	});

	// $('.JqDetailClose').on('click', function()
	// {
	// 	$(".JqDetailinfo").html('');
	// });
})