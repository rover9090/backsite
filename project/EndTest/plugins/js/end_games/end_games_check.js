/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3. 
 */
var gaRoomCounting = {};
var aNumsRoad = {};
var gnGame = $('.JqnGame').val();
$(document).ready(function () {
	fnAjaxResult();
	timeLobby = setInterval(fnAjaxResult, 1000);
	// $('.JqToggleHeader').on('click' , function()
	// {
	// 	$('.JqHeader').toggle();
	// 	$('.JqNavContentContainer').toggleClass('active');
	// });

	//////////////////////////

	//////////////////////////
});

function fnAjaxResult(){
	let sUrl = $('input[name=sResultJWT]').attr('data-url');
	let sJWT = $('input[name=sResultJWT]').val();
	let nRead = $('.JqnRead').val();
	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		data: {
			'sJWT': sJWT,
			'nGame': gnGame,
			'nRead':nRead,
			'run_page': 1,
		},
		success: function (oRes) {
			if(oRes['aData']['sNums'] == ''){
				for(let i=1;i<=3;i++){
					sClassName1 = '.JqCardB'+(i).toString();
					sClassName2 = '.JqCardP'+(i).toString();
					$(sClassName1+'Src,'+sClassName2+'Src').attr('src','images/card/back.png');
				}
				return;
			}
			aNums = JSON.parse(oRes['aData']['sNums']);
			if(aNums['sBanker'] != undefined && aNums['sPlayer'] != undefined && aNums['sBanker'] != '' && aNums['sPlayer'] != ''){
				aNums['aBanker'] = aNums['sBanker'].split(',');
				aNums['aPlayer'] = aNums['sPlayer'].split(',');
	
				for(let i=0;i<3;i++){
					j=i+1;
					if(aNums['aBanker'][i] !== undefined && aNums['aBanker'][i] !== ''){
						$('.JqCardB'+j+'Src').attr('src','images/card/'+aNums['aBanker'][i]+'.png');
					}
				}
	
				for(let i=0;i<3;i++){
					j=i+1;
					if(aNums['aPlayer'][i] !== undefined && aNums['aPlayer'][i] !== ''){
						$('.JqCardP'+j+'Src').attr('src','images/card/'+aNums['aPlayer'][i]+'.png');
					}
				}
			}
			////////////////////////////////////
			// if(oRes.aData[gnGame]['sNums'] !== undefined){
			// 	if(oRes.aData[gnGame]['sNums'] !== ''){
			// 		fnGetGrades(oRes.aData[gnGame]['aNums']);
			// 	}
			// 	else{
			// 		for(let i=1;i<=3;i++){
			// 			sClassName1 = '.JqCardB'+(i).toString();
			// 			sClassName2 = '.JqCardP'+(i).toString();
			// 			$(sClassName1+'Src,'+sClassName2+'Src').attr('src','images/card/back.png');
			// 		}
			// 	}
			// }
			// else{
			// 	for(let i=1;i<=3;i++){
			// 		sClassName1 = '.JqCardB'+(i).toString();
			// 		sClassName2 = '.JqCardP'+(i).toString();
			// 		$(sClassName1+'Src,'+sClassName2+'Src').attr('src','images/card/back.png');
			// 	}
			// }
			
		},
		error: function (exception) {
			console.log('Exeption:' + exception.responseText);
		}
	});
}

function fnGetGrades($aNums)
{
	$aBanker = [];
	$aBankerGrade = [];
	$nBankerGrade = 0;
	$aPlayer = [];
	$aPlayerGrade = [];
	$nPlayerGrade = 0;
	$aReturn = [];
	$aGrade = {
		'A' : 1,
		'2' : 2,
		'3' : 3,
		'4' : 4,
		'5' : 5,
		'6' : 6,
		'7' : 7,
		'8' : 8,
		'9' : 9,
		'10': 0,
		'J' : 0,
		'Q' : 0,
		'K' : 0,
		'0' : 0,
	};

	if($aNums['sBanker'] !== undefined){
		$aBanker = $aNums['sBanker'].split(',');
		for(let $LPnKey in $aBanker)
		{
			if($aBanker[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardB'+($LPnKey+1).toString();
			$aBankerGrade[$LPnKey] = $aGrade[$aBanker[$LPnKey].substr(1)];
			$nBankerGrade += $aBankerGrade[$LPnKey];
			if($(sClassName).length > 0){
				$(sClassName+'Src').attr('src','images/card/'+$aBanker[$LPnKey]+'.png');
				$nBankerGrade %= 10;
				if($LPnKey == 2){
					$('.JqBankerGrade').text($nBankerGrade);
				}
				else{
					if($LPnKey == 1){
						$('.JqBankerGrade').text($nBankerGrade);
					}
				}
			}
		}
	}
	else{
		for(let i=1;i<=3;i++){
			sClassName = '.JqCardB'+(i).toString();
			$(sClassName+'Src').attr('src','images/card/back.png');
		}
	}

	if($aNums['sPlayer'] !== undefined){
		$aPlayer = $aNums['sPlayer'].split(',');
		for(let $LPnKey in $aPlayer)
		{
			if($aPlayer[$LPnKey] == '') continue;
			$LPnKey = parseInt($LPnKey);
			sClassName = '.JqCardP'+($LPnKey+1).toString();
			$aPlayerGrade[$LPnKey] = $aGrade[$aPlayer[$LPnKey].substr(1)];
			$nPlayerGrade += $aPlayerGrade[$LPnKey];
			if($(sClassName).length > 0){
				$(sClassName+'Src').attr('src','images/card/'+$aPlayer[$LPnKey]+'.png');
				$nPlayerGrade %= 10;
				if($LPnKey == 2){
					$('.JqPlayerGrade').text($nPlayerGrade);
				}
				else{
					if($LPnKey == 1){
						$('.JqPlayerGrade').text($nPlayerGrade);
					}
				}
			}
		}
	}
	else{
		for(let i=1;i<=3;i++){
			sClassName = '.JqCardP'+(i).toString();
			$(sClassName+'Src').attr('src','images/card/back.png');
		}
	}
}