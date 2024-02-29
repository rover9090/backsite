$(document).ready(function () {
	laydate.render({ elem: '.JqStartTime', type: 'datetime' });
	laydate.render({ elem: '.JqEndTime', type: 'datetime' });
	nSec = $('select[name="nReloadTime"] option:selected').val();

	if($('input[name=sEndTime]').val() == '')
	{
		setInterval(fnAjaxBet, nSec * 1000);
	}

	$('.JqDate').click(function(event)
	{
		$('.JqDate').removeClass('active');
		$(this).addClass('active');
		$('.JqStartTime').val($(this).data('date0'));
		$('.JqEndTime').val($(this).data('date1'));
		$('input[name=sSelDay]').val($(this).data('day'));
	});

	setTimeout(function(){ location.reload(); }, 2*60*1000);

	function fnAjaxBet(){
		let sUrl = $('input[name=sRankJWT]').attr('data-url');
		let sJWT = $('input[name=sRankJWT]').val();
		let nGame = $('input[name=sRankJWT]').attr('data-game');

		if($('input[name=sEndTime]').val() == '')
		{
			$.ajax({
				url: sUrl,
				type: 'POST',
				dataType: 'json',
				data: {
					'sJWT': sJWT,
					'run_page': 1,
					'nGame': nGame,
					'sStartTime':$('input[name=sStartTime]').val(),
					'sEndTime':$('input[name=sEndTime]').val(),
					'sAccount':$('input[name=sAccount]').val(),
				},
				success: function (oRes) {
					// console.log(oRes);
					sTable = RankingTable(oRes['aData']);
					$('.JqCurrentTable').html(sTable);
				},
				error: function (exception) {
					console.log('Exeption:' + exception.responseText);
				}
			});
		}

	}
});

function RankingTable(aData){
	let sHtml = '';
	let sActUrl = $('input[name=sRankJWT]').attr('data-acturl');
	let nTopCount = 50;
	for(let LPsKey in aData){

		sHtml += '<tr>';
		sHtml += '<td class="'+aData[LPsKey]['sColor']+'">'+aData[LPsKey]['sAccount']+'</td>';	//會員帳號
		sHtml += '<td class="'+aData[LPsKey]['sColor']+'">'+aData[LPsKey]['sName0']+'</td>';	//會員名稱
		sHtml += '<td>'+aData[LPsKey]['nCount']+'</td>';							//投注筆數
		sHtml += '<td>'+aData[LPsKey]['nMoney']+'</td>';							//投注金額
		sHtml += '<td>'+aData[LPsKey]['nWin']+'</td>';								//總贏錢
		sHtml += '<td>'+aData[LPsKey]['nLose']+'</td>';								//總輸錢
		sHtml += '<td>'+aData[LPsKey]['nProfit']+'</td>';							//總輸贏
		sHtml += '<td><input type=\"button\" class="BtnAny" onclick="myWindow=window.open(\''+sActUrl+'&nId='+aData[LPsKey]['nUid']+'&nMute=1\')" value="禁言"><input type="button" class="BtnAny" onclick="myWindow=window.open(\''+sActUrl+'&nId='+aData[LPsKey]['nUid']+'&nBlack=1\')" value="黑名單"></td>';							//操作
		sHtml += '</tr>';

		if(LPsKey == nTopCount) break;
	}
	return sHtml;
}