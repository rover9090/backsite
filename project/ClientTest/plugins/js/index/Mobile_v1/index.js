$(document).ready(function()
{
	var nScreenWidth = $(window).width();

	// 棋盤路
	var nMainWid = $('.JqRoadBox').width()/parseInt($('.JqRoadBox').attr('data-col'));
	$('.JqRoadItem').css('height',nMainWid+'px');
	$('.JqResultBox').css({'height':(nMainWid-2)+'px','width':(nMainWid-2)+'px'}); // 文字框

	// 文字大小
	if(nMainWid < 18)
	{
		$('.JqResultWord').css('font-size',"12px");
	}
	else
	{
		$('.JqResultWord').css('font-size',(nMainWid-4)+"px");
	}

	if(nScreenWidth < 360)
	{
		$('.JqResultWord').css('transform','translate(-50%, -50%) scale(.8)');
	}

	$('.JqBigPattern').css({'height':(nMainWid-1)+'px','width':(nMainWid-1)+'px'}); // 大路圖案
	if((nScreenWidth > 767) && (nScreenWidth < 1024))
	{
		$('.JqBigPattern').css('border-width','5px');
	}
	else if(nScreenWidth >= 1024)
	{
		$('.JqBigPattern').css('border-width','6px');
	}

	// // 四分之一格
	$('.JqRoadSep').css('height',((nMainWid-2)/2)+'px');

	// 四分之一格圖案
	$('.JqSmallPattern').css({'height':((nMainWid-2)/2)+'px','width':((nMainWid-2)/2)+'px'});
	if(((nMainWid-2)/2) > 7)
	{
		$('.JqSmallPattern').css('border-width','2px');
	}
	
	// 點
	$.each($('.JqGameDot'),function(i,dot)
	{
		var nDotVal = $(dot).parent('.JqRoadItem').width();
		if(nDotVal/2.5 > 5)
		{
			$(dot).css({'height':(nDotVal/2.5)+'px','width':(nDotVal/2.5)+'px'});
		}
		else
		{
			$(dot).css({'height':'5px','width':'5px','border-width':'1px'});
		}
	});

	// 下注紀錄 打賞紀錄

	// var nRange = 10;
	// var nStart = 10;
	// var nDonateStart = 10;
	// $('.JqRecord').scroll(function(){

	// 	if($('.JqDonateRecord').hasClass('active'))
	// 	{
	// 		let sUrl = $('input[name=sDonateHistoryJWT]').attr('data-url');

	// 		if ($('.JqRecord').height() + $('.JqRecord').scrollTop() >= $('.JqRecord').prop('scrollHeight')-20) 
	// 		{
	// 			// console.log($('.JqRecord').height() + $('.JqRecord').scrollTop());
	// 			var nEnd = nDonateStart + nRange;
	// 			$.ajax({
	// 				url: sUrl,
	// 				type: 'POST',
	// 				dataType: 'json',
	// 				data: {
	// 					// 'sJWT': sJWT,
	// 					'nStart': nDonateStart,
	// 					'nEnd': nEnd,
	// 					'run_page': 1,
	// 				},
	// 				success: function (oRes) {
	// 					// console.log(oRes['aData']['aDonateData']);
	// 					let sHtml = '';
	// 					$.each(oRes['aData']['aDonateData'],function(i,item){
	
	// 						sHtml+='<tr>';
	// 						sHtml+='<td class="infoTd">'+item.nId+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.sCreateTime+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.nMoney0+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.sName0+'</td>';
	// 						sHtml+='</tr>';
	// 					});
	// 					$(".JqDonateInfo").append($(sHtml));
	// 				},
	// 				error: function (exception) {
	// 					console.log('Exeption:' + exception.responseText);
	// 				}
	// 			});
	// 			nDonateStart = nEnd + 1;
	// 		}
	// 	}
	// 	else
	// 	{
	// 		let sUrl = $('input[name=sHistoryJWT]').attr('data-url');

	// 		if ($('.JqRecord').height() + $('.JqRecord').scrollTop() >= $('.JqRecord').prop('scrollHeight')-20) 
	// 		{
	// 			// console.log($('.JqRecord').height() + $('.JqRecord').scrollTop());
	// 			console.log(sUrl);

	// 			var nEnd = nStart + nRange;
	// 			$.ajax({
	// 				url: sUrl,
	// 				type: 'POST',
	// 				dataType: 'json',
	// 				data: {
	// 					// 'sJWT': sJWT,
	// 					'nStart': nStart,
	// 					'nEnd': nEnd,
	// 					'run_page': 1,
	// 				},
	// 				success: function (oRes) {
	// 					// console.log(oRes['aData']['aBetData']);
	// 					let sHtml = '';
	// 					$.each(oRes['aData']['aBetData'],function(i,item){
	
	// 						sClass = '';
	// 						if(item.nStatus == 1)
	// 						{
	// 							sClass = 'FontOrange';
	// 						}
	
	// 						sHtml+='<tr>';
	// 						sHtml+='<td class="infoTd">'+item.nId+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.sCreateTime+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.sContent0+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.nMoney0+'</td>';
	// 						sHtml+='<td class="infoTd">'+item.nMoney2+'</td>';
	// 						sHtml+='<td class="infoTd '+sClass+'">'+item.sStatus+'</td>';
	// 						sHtml+='</tr>';
	// 					});
	// 					$(".JqInfo").append($(sHtml));
	// 				},
	// 				error: function (exception) {
	// 					console.log('Exeption:' + exception.responseText);
	// 				}
	// 			});
	// 			nStart = nEnd + 1;
	// 		}
	// 	}
	// });
});

function fnGameSetRoadCss(){
	var nScreenWidth = $(window).width();

	// 棋盤路
	var nMainWid = $('.JqRoadBox').width()/parseInt($('.JqRoadBox').attr('data-col'));
	$('.JqRoadItem').css('height',nMainWid+'px');
	$('.JqResultBox').css({'height':(nMainWid-2)+'px','width':(nMainWid-2)+'px'}); // 文字框

	// 文字大小
	if(nMainWid < 18)
	{
		$('.JqResultWord').css('font-size',"12px");
	}
	else
	{
		$('.JqResultWord').css('font-size',(nMainWid-4)+"px");
	}

	if(nScreenWidth < 360)
	{
		$('.JqResultWord').css('transform','translate(-50%, -50%) scale(.8)');
	}

	$('.JqBigPattern').css({'height':(nMainWid-1)+'px','width':(nMainWid-1)+'px'}); // 大路圖案
	if((nScreenWidth > 767) && (nScreenWidth < 1024))
	{
		$('.JqBigPattern').css('border-width','5px');
	}
	else if(nScreenWidth >= 1024)
	{
		$('.JqBigPattern').css('border-width','6px');
	}

	// // 四分之一格
	$('.JqRoadSep').css('height',((nMainWid-2)/2)+'px');

	// 四分之一格圖案
	$('.JqSmallPattern').css({'height':((nMainWid-2)/2)+'px','width':((nMainWid-2)/2)+'px'});
	if(((nMainWid-2)/2) > 7)
	{
		$('.JqSmallPattern').css('border-width','2px');
	}
	
	// 點
	$.each($('.JqGameDot'),function(i,dot)
	{
		var nDotVal = $(dot).parent('.JqRoadItem').width();
		if(nDotVal/2.5 > 5)
		{
			$(dot).css({'height':(nDotVal/2.5)+'px','width':(nDotVal/2.5)+'px'});
		}
		else
		{
			$(dot).css({'height':'5px','width':'5px','border-width':'1px'});
		}
	});
}