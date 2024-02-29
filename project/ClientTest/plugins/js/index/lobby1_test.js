/**
 * 1. PHP讀取全部遊戲的近72期數資料
 * 2. 將輸贏存入陣列 並得出當前狀態
 * 3. 
 */
 var aNumsRoad = {};
 var aNumsArray = JSON.parse($('.JqaNumsArray').val());
 // var aNumsCounting = JSON.parse($('.JqaNumsCounting').val());
 var gnGame = ($('.JqnGame').length==1)?$('.JqnGame').val():0;
 var gaCheck = {};
 var gaUser = {'nIdleRound':0,'aData':{}};
 var sDevice = $('.JqBody').attr('data-device');
 var gaBetPool = {};
 gaUser['aData']['sName0'] = $('input[name=aUserData]').attr('data-name0');
 gaUser['aData']['sAccount'] = $('input[name=aUserData]').attr('data-account');
 gaUser['aData']['nUid'] = $('input[name=aUserData]').attr('data-uid');
 gaUser['aData']['nMute'] = $('input[name=aUserData]').attr('data-mute');
 gaUser['aData']['nMoney'] = $('input[name=aUserData]').attr('data-money');
 gaUser['aData']['sJWT'] = $('input[name=aUserData]').attr('data-jwt');
 gaUser['aData']['nTest'] = $('input[name=aUserData]').attr('data-test');
 $(document).ready(function () {
	 $('.JqDealerImage').addClass('active');
	 // var aNumsArray = {}; // aNumsArray = 房間=>歷屆期數資料=>資料
	 for(var LPsKey in aNumsArray){
		 gaCheck[LPsKey] = {};
		 gaCheck[LPsKey]['nCheckCard'] = 0; // 看底牌
		 
		 aNumsRoad[LPsKey] = new cRoad();
		 fnDrawBoard(aNumsArray[LPsKey],LPsKey);
		 for(var LPsKey2 in aNumsArray[LPsKey]){
			 aNumsRoad[LPsKey].fnInsert(aNumsArray[LPsKey][LPsKey2]['aResult']);
		 }
		 aNumsRoad[LPsKey]['aPrintRoad0'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aNumberArray,6);
 
		 aNumsRoad[LPsKey]['aPrintRoad1'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[1],6);
		 aNumsRoad[LPsKey]['aPrintRoad2'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[2],6);
		 aNumsRoad[LPsKey]['aPrintRoad3'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[3],6);
		 // 大路一定都會出 用大路判斷
		 if($('.JqRoad0_'+LPsKey+'_sub').length > 0){
			 fnDrawRoad(aNumsRoad[LPsKey],LPsKey,1);
		 }
		 if($('.JqRoad0_'+LPsKey).length > 0){
			 fnDrawRoad(aNumsRoad[LPsKey],LPsKey);
		 }
		 if(LPsKey == gnGame){
			 fnAskRoad(aNumsRoad[LPsKey]);
		 }
	 }
	 if(gnGame>0){
		 gaCheck[gnGame]['nSortIndex'] = parseInt($('.JqnSortIndex').val());
	 }
 
	 $('.JqCopy_Public').click(function() {
		 $Range = $('.JqsCardPublic_Pick');
		 var range = document.createRange();	// display:none會抓不到 微妙
		 range.selectNodeContents($Range[0]); 
		 var sel = window.getSelection();
		 sel.removeAllRanges();
		 sel.addRange(range);
		 document.execCommand('copy');
		 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
		 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text('複製成功');
	 });
 
	 $('.JqVerCard').click(function(){
		 nSort = parseInt($(this).attr('data-num'));
		 $CardBookData = $('.JqVerCard.JqCardBook[data-num="'+nSort+'"]');
		 sCardPublic = $CardBookData.attr('data-scardpublic');
		 sCardKey = $CardBookData.attr('data-scardkey');
		 sCardCode = $CardBookData.attr('data-scardcode');
		 $('.JqsCardPublic_Pick').text(sCardPublic);
		 $('.JqsCardKey_Pick').text(sCardKey);
		 $('.JqCardSort_Pick').text(nSort+1);
		 $('.JqVerifyCard').find('div').text('驗證');
		 $('.JqVerifyResult').removeClass('success');
		 $('.JqVerifyResult').removeClass('error');
		 if(sCardCode == '**'){
			 $('.JqCardImgSrc_Pick').attr('src','images/card/back.png');
			 $('.JqVerifyCard').removeClass('active');
		 }
		 else{
			 $('.JqCardImgSrc_Pick').attr('src','images/card/'+sCardCode+'.png');
			 $('.JqVerifyCard').addClass('active');
		 }
	 });
 
	 $('.JqVerifyCard').click(function(){
		 if($(this).hasClass('active')){
			 $('.JqVerifyCard').find('div').text('驗證成功');
			 $('.JqVerifyResult').addClass('success');
		 }
	 });
 
	 fnAjaxLobby1();
	 // timeLobby = setInterval(fnAjaxLobby, 2000);
	 timeLobby1 = setInterval(fnAjaxLobby1, 1000);
 
	 //////////////////////////
 
	 //////////////////////////
 });
 
 function fnDrawBoard(aNumsArrayUnit,LPsKey,aDebug={}){
	 if(aNumsArrayUnit == undefined) aNumsArrayUnit={};
	 let nWidth = $('.JqBoardWidth_0').val();
	 let nBoxLen = nWidth * 6;
	 let nLen = Object.keys(aNumsArrayUnit).length;// 0-72 最近累積的期數
	 let i;
	 let aNumsCounting = {"BW":0,"PW":0,"DRAW":0,"BP":0,"PP":0,"SIX":0};
	 let nBoardLen = nWidth * 6;
	 if(nLen <= (nBoxLen-6)){
		 i=0;
	 }
	 else{
		 i = Math.ceil((nLen-(nBoxLen-6))/6)*6;// x軸永遠預留一格
	 }
	 tmp = i;
	 for(let j=0;j<nBoardLen;j++){
		 $('.JqBoard_'+LPsKey+'.JqNo'+j).html('');		
	 }	
	 for(let j=0;i<nLen;i++,j++){
		 if(aNumsArrayUnit[i] !== undefined){
			 for(let LPsKey2 in aNumsArrayUnit[i]['aResult']){
				 $('.JqBoard_'+LPsKey+'.JqNo'+j).append(aBoardCell[aNumsArrayUnit[i]['aResult'][LPsKey2]]);
			 }
		 }
	 }
	 for(let i=0;i<nLen;i++){
		 if(aNumsArrayUnit[i] === undefined){
			 console.log(aDebug,i);
			 console.log(aNumsArrayUnit);
			 aNumsArrayUnit[i] = {};
			 aNumsArrayUnit[i]['aResult'] = [];
		 }
		 if(aNumsArrayUnit[i]['aResult'] == undefined){
			 // aNumsArrayUnit[i]['aResult'] = [];
			 // clearInterval(timeLobby);
			 // clearInterval(timeLobby1);
			 // return;
		 }
		 for(let LPsKey2 in aNumsArrayUnit[i]['aResult']){ // Cannot read property 'aResult' of undefined from 192
			 aNumsCounting[aNumsArrayUnit[i]['aResult'][LPsKey2]]++;
		 }
	 }
	 
	 $('.JqBankerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"]);
	 $('.JqPlayerWin[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PW"]);
	 $('.JqPlayerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["PP"]);
	 $('.JqBankerPair[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BP"]);
	 $('.JqDraw[data-ngame="'+LPsKey+'"]').text(aNumsCounting["DRAW"]);
	 $('.JqSix[data-ngame="'+LPsKey+'"]').text(aNumsCounting["DRAW"]);
	 $('.JqGamesAll[data-ngame="'+LPsKey+'"]').text(aNumsCounting["BW"] + aNumsCounting["PW"] + aNumsCounting["DRAW"]);
 }
 
 function fnDrawRoad(aPrintRoad,LPsKey,nSub=0){
	 let nLen,nWidth,nDelta,sSub;
	 sSub = (nSub==0)?'':'_sub';
	 nLen = aPrintRoad['aPrintRoad0']['aPrintRoad'].length;
	 nWidth = $('.JqRoad0Width_'+nSub).val();
	 nDelta = Object.keys(aPrintRoad['aPrintRoad0']['aPrintBoard']).length - nWidth + 1;
	 if(nDelta < 0){
		 nDelta = 0;
	 }
	 for(let i=0;i<nWidth;i++){
		 for(let j=0;j<6;j++){
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+i+'.y'+j).html('');
		 }
	 }
	 for(let i=0;i<nLen;i++){
		 if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['draw'] == true)
		 {
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['DRAW']);
		 }
		 else if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['bResult'] == true){
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['BW']);
		 }
		 else if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['bResult'] == false){
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['PW']);
		 }
		 if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['BP'] !== undefined){
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['BP']);
		 }
		 if(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['PP'] !== undefined){
			 $('.JqRoad0_'+LPsKey+sSub+'.x'+(aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad0']['aPrintRoad'][i]['y']).append(aRoad0Cell['PP']);
		 }		
	 }
 
	 if(nSub == 1){
		 return;
	 }
 
	 nLen = aPrintRoad['aPrintRoad1']['aPrintRoad'].length;
	 nWidth = $('.JqRoad1Width_'+nSub).val();
	 nDelta = Object.keys(aPrintRoad['aPrintRoad1']['aPrintBoard']).length - nWidth + 1;
	 if(nDelta < 0){
		 nDelta = 0;
	 }
	 for(let i=0;i<nWidth;i++){
		 for(let j=0;j<6;j++){
			 $('.JqRoad1_'+LPsKey+'.x'+i+'.y'+j).html('');
		 }
	 }
	 for(let i=0;i<nLen;i++){
		 if(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['bResult'] == true){
			 $('.JqRoad1_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['y']).append(aRoad1Cell['BW']);
		 }
		 else if(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['bResult'] == false){
			 $('.JqRoad1_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad1']['aPrintRoad'][i]['y']).append(aRoad1Cell['PW']);
		 }	
	 }
 
	 nLen = aPrintRoad['aPrintRoad2']['aPrintRoad'].length;
	 nWidth = $('.JqRoad2Width_'+nSub).val();
	 nDelta = Object.keys(aPrintRoad['aPrintRoad2']['aPrintBoard']).length - nWidth + 1;
	 if(nDelta < 0){
		 nDelta = 0;
	 }
	 for(let i=0;i<nWidth;i++){
		 for(let j=0;j<6;j++){
			 $('.JqRoad2_'+LPsKey+'.x'+i+'.y'+j).html('');
		 }
	 }
	 for(let i=0;i<nLen;i++){
		 if(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['bResult'] == true){
			 $('.JqRoad2_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['y']).append(aRoad2Cell['BW']);
		 }
		 else if(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['bResult'] == false){
			 $('.JqRoad2_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad2']['aPrintRoad'][i]['y']).append(aRoad2Cell['PW']);
		 }	
	 }
 
	 nLen = aPrintRoad['aPrintRoad3']['aPrintRoad'].length;
	 nWidth = $('.JqRoad3Width_'+nSub).val();
	 nDelta = Object.keys(aPrintRoad['aPrintRoad3']['aPrintBoard']).length - nWidth + 1;
	 if(nDelta < 0){
		 nDelta = 0;
	 }
	 for(let i=0;i<nWidth;i++){
		 for(let j=0;j<6;j++){
			 $('.JqRoad3_'+LPsKey+'.x'+i+'.y'+j).html('');
		 }
	 }
	 for(let i=0;i<nLen;i++){
		 if(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['bResult'] == true){
			 $('.JqRoad3_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['y']).append(aRoad3Cell['BW']);
		 }
		 else if(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['bResult'] == false){
			 $('.JqRoad3_'+LPsKey+'.x'+(aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['x']-nDelta)+'.y'+aPrintRoad['aPrintRoad3']['aPrintRoad'][i]['y']).append(aRoad3Cell['PW']);
		 }	
	 }
 }
 
  
 function fnAjaxLobby1(){
	 let sUrl = $('input[name=sLobbyJWT]').attr('data-url1');
	 let sJWT = $('input[name=sLobbyJWT]').val();
	 $.ajax({
		 url: sUrl,
		 type: 'POST',
		 dataType: 'json',
		 data: {
			 'sJWT': sJWT,
			 'nGame': gnGame,
			 'run_page': 1,
		 },
		 success: function (oRes) {
			 gaUser['aData'] = oRes['aUser'];			
			 $('input[name=aUserData]').attr('data-mute',oRes['aUser']['nMute'])
			 $('.JqUserMoney').text(oRes['aUser']['nMoney']);
			 if(oRes['aUser']['nTest'] != 1 && gnGame != oRes['nCookieRoom'] && gnGame != 0)
			 {
				 clearInterval(timeLobby1);
				 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text('已進入其他房間');
				 $('.JqMsg0Box').click(function(){
					 window.location = oRes['sUrl'];					
				 });
				 return;
			 }
			 if(gaUser['nIdleRound'] >= 10){
				 clearInterval(timeLobby1);
				 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				 $('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text('閒置超過10局並未投注');
				 $('.JqMsg0Box').click(function(){
					 window.location = oRes['sUrl'];					
				 });
				 return;
			 }
			 aCookie = fnGetCookieValue();
			 if(typeof(oRes['aAnnounce'][gnGame]) != 'undefined' || typeof(oRes['aAnnounce'][0]) != 'undefined'){
				 if(typeof(oRes['aAnnounce'][0]) != 'undefined')
				 {
					 $('.JqJumpMsgBox[data-showmsg="msg9Box"]').find('.JqJumpMsgContentArticle').html(oRes['aAnnounce'][0]['sContent0']);
				 }
				 else
				 {
					 $('.JqJumpMsgBox[data-showmsg="msg9Box"]').find('.JqJumpMsgContentArticle').html(oRes['aAnnounce'][gnGame]['sContent0']);
				 }
				 if(aCookie['nAnnounceAlert'] != gnGame && aCookie['nAnnounceAlert'] != 0)
				 {
					 $('.JqJumpMsgBox[data-showmsg="msg9Box"]').addClass('active');
					 $('.JqTestClean').click(function(){
						 fnUpdateCookie('nAnnounceAlert',gnGame);
					 });
				 }
			 }
 
			 for(var LPsKey in oRes['aData']){// LPsKey = 房間號碼
				 if(oRes['aData'][LPsKey]['aLastest'] == undefined){
					 console.log(LPsKey);
				 }
				 if(oRes['aData'][LPsKey]['aLastest']['nSeconds'] >= 0){
					 $('.JqClock_'+LPsKey).removeClass('FontRed');
					 $('.JqOpenNow_'+LPsKey).removeClass('active');
					 $('.JqClock_'+LPsKey).text(oRes['aData'][LPsKey]['aLastest']['nSeconds']);
				 }
				 else{
					 if(!$('.JqClock_'+LPsKey).hasClass('FontRed')){
						 $('.JqClock_'+LPsKey).text(0);
					 }
					 if(oRes['aData'][LPsKey]['aLastest']['nSeconds1'] < 0){
						 $('.JqClock_'+LPsKey).text(0);
					 }
				 }
				 if(oRes['aData'][LPsKey]['aLastest']['nSeconds1'] >= 0){
					 if(!$('.JqClock_'+LPsKey).hasClass('FontRed')){
						 $('.JqClock_'+LPsKey).addClass('FontRed');
					 }
					 $('.JqClock_'+LPsKey).text(oRes['aData'][LPsKey]['aLastest']['nSeconds1']);
				 }
 
				 if(gnGame == LPsKey){
					 if(typeof fnSettingSeat !== 'undefined'){
						 fnSettingSeat(0,oRes['aData'][LPsKey]['aMember']);
					 }
					 if(oRes['aData'][LPsKey]['aLastest']['nSeconds'] >= 0){
						 if(oRes['aData'][LPsKey]['aLastest']['nSeconds'] > oRes['aData'][LPsKey]['aSetting']['nSec2']){
							 fnGenerateChip(oRes['aBetData'][LPsKey]['aBetData']);
						 }
						 fnOpenBet();
						 $('.JqPlayerZone').removeClass('active');
						 $('.JqBankerZone').removeClass('active');
						 $('.JqDrawZone').removeClass('active');
						 $('.JqSixZone').removeClass('active');
						 $('.JqBankerPairZone').removeClass('active');
						 $('.JqPlayerPairZone').removeClass('active');
						 gaCheck[LPsKey]['nCheckCard'] = 0;
 
						 $('.JqCardP1').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+0);
						 $('.JqCardP1').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+0+1);
						 $('.JqCardB1').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+1);
						 $('.JqCardB1').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+1+1);
						 $('.JqCardP2').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+2);
						 $('.JqCardP2').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+2+1);
						 $('.JqCardB2').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+3);
						 $('.JqCardB2').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+3+1);
						 $('.JqCardP3').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+4);
						 $('.JqCardP3').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+4+1);
						 $('.JqCardP3').parent('.JqVerCard').css('visibility','hidden');
						 $('.JqCardB3').parent('.JqVerCard').attr('data-num',gaCheck[gnGame]['nSortIndex']+5);
						 $('.JqCardB3').siblings('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+5+1);
						 $('.JqCardB3').parent('.JqVerCard').css('visibility','hidden');
						 $('.JqBlockReadyCard1').show();
						 $('.JqBlockReadyCard1').attr('data-num',gaCheck[gnGame]['nSortIndex']+4);
						 $('.JqBlockReadyCard1').find('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+4+1);
						 $('.JqBlockReadyCard1').css('top','450px');
						 $('.JqBlockReadyCard1').css('left','calc(100% - 20vw - 90px)');
						 $('.JqBlockReadyCard2').show();
						 $('.JqBlockReadyCard2').attr('data-num',gaCheck[gnGame]['nSortIndex']+5);
						 $('.JqBlockReadyCard2').find('.JqVerCardNum').text(gaCheck[gnGame]['nSortIndex']+5+1);
						 $('.JqBlockReadyCard2').css('top','550px');
						 $('.JqBlockReadyCard2').css('left','calc(100% - 20vw - 90px)');
						 /*
						 替換自己的Sort
						 */
					 }
					 else{
						 if(oRes['aData'][LPsKey]['aSetting']['nShuffling'] == '0'){
							 fnCloseBet();						
						 }
					 }
					 if(oRes['aData'][LPsKey]['aLastest']['nSeconds1'] > 0){
						 $('.JqFinalBox').addClass('active');
						 $('.JqPlayerBetChip').html('');
						 $('.JqPlayerBetChip').removeClass('JqAdded');
						 if(gaCheck[LPsKey]['nCheckCard'] == 0){
							 gaCheck[LPsKey]['nCheckCard'] = 1;
							 gaBetPool = [];
							 if(oRes['aUser']['nTest'] == 0){
								 gaUser['nIdleRound']++;
							 }
							 if(oRes['aData'][LPsKey]['aLastest']['nPauseTime1'] == oRes['aData'][LPsKey]['aLastest']['nBetEndTime']){
								 fnGetGrades(oRes['aData'][LPsKey]['aLastest']['aPreNums'],oRes['aData'][LPsKey]['aVerify']);								
							 }
							 else{
								 fnGetGrades(oRes['aData'][LPsKey]['aLastest']['aNums'],oRes['aData'][LPsKey]['aVerify']);
							 }
						 }
					 }
					 fnCheckRoundStatus(oRes['aData'][LPsKey],LPsKey);
 
					 $('.JqNo').text(oRes['aData'][LPsKey]['aLastest']['sNo']);
					 if(oRes['aData'][LPsKey]['aLastest']['nRound'] == undefined){
						 $('.JqRound').text(0);
					 }
					 else{
						 $('.JqRound').text(parseInt(oRes['aData'][LPsKey]['aLastest']['nRound'])+1);
					 }
 
					 // console.log(oRes['aBetData']);
					 // $('.JqPlayerPairMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['PP']);
					 // $('.JqSuperSixMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['SIX']);
					 // $('.JqBankerPairMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['BP']);
					 // $('.JqPlayerWinMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['PW']);
					 // $('.JqDrawMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['DRAW']);
					 // $('.JqBankerWinMoney').text(oRes['aBetData'][LPsKey]['aBetMoney']['BW']);
					 // $('.JqPlayerPairCount').text(oRes['aBetData'][LPsKey]['aBetCount']['PP']);
					 // $('.JqSuperSixCount').text(oRes['aBetData'][LPsKey]['aBetCount']['SIX']);
					 // $('.JqBankerPairCount').text(oRes['aBetData'][LPsKey]['aBetCount']['BP']);
					 // $('.JqPlayerWinCount').text(oRes['aBetData'][LPsKey]['aBetCount']['PW']);
					 // $('.JqDrawCount').text(oRes['aBetData'][LPsKey]['aBetCount']['DRAW']);
					 // $('.JqBankerWinCount').text(oRes['aBetData'][LPsKey]['aBetCount']['BW']);
 
					 // $('.JqPlayerRoundLimit').text(oRes['aData'][LPsKey]['aOdds']['PW']['nRoundMaxBet']);
					 // $('.JqBankerRoundLimit').text(oRes['aData'][LPsKey]['aOdds']['BW']['nRoundMaxBet']);
					 $('.JqNo').text(oRes['aData'][LPsKey]['aLastest']['sNo']);
					 $('.JqRound').text(parseInt(oRes['aData'][LPsKey]['aLastest']['nRound'])+1);
				 }
 
				 if(oRes['aData'][LPsKey]['aSetting']['nShuffling'] == '1'){
					 $('.JqShuffling_'+LPsKey).addClass('active');
				 }
				 else{
					 $('.JqShuffling_'+LPsKey).removeClass('active');
				 }
 
				 // if(oRes['aData'][LPsKey]['aSetting']['nDealer'] == 0)
				 // {
					 // $('.JqDealerImage').addClass('active');
				 // }
				 // else
				 // {
				 // 	$('.JqDealerImage').removeClass('active');
				 // }
 
				 $('.JqCloseRoom_'+LPsKey).parents('a').attr('href',oRes['aData'][LPsKey]['aSetting']['sBetUrl']);
				 if(oRes['aData'][LPsKey]['aSetting']['nCloseRoom'] == 1 || oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 1){
					 if((nComing = oRes['aData'][LPsKey]['aSetting']['nOpenTime'] - oRes['nNowTime']) <= 600 &&
					 oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
						 $('.JqCloseRoom_'+LPsKey).removeClass('active');
						 $('.JqComingSoon_'+LPsKey).addClass('active');
						 $('.JqClock_'+LPsKey).text(nComing);
					 }
					 else{
						 $('.JqCloseRoom_'+LPsKey).addClass('active');
					 }
					 if(oRes['aData'][LPsKey]['aSetting']['nMaintenance'] == 0){
						 let sOpenTime = aJSDEFINE[sDevice+'_OPENROOM'].replace('[:TIME:]',oRes['aData'][LPsKey]['aSetting']['sOpenTime']);
						 $('.JqCloseRoom_'+LPsKey).find('div').text(sOpenTime);
					 }
				 }
				 else{
					 $('.JqCloseRoom_'+LPsKey).removeClass('active');
					 $('.JqComingSoon_'+LPsKey).removeClass('active');					
				 }
 
				 // 重畫
				 aNumsArray[LPsKey] = oRes['aData'][LPsKey]['aNumsArray'];
				 aNumsRoad[LPsKey] = new cRoad();
				 fnDrawBoard(aNumsArray[LPsKey],LPsKey);
				 for(var LPsKey2 in aNumsArray[LPsKey]){
					 aNumsRoad[LPsKey].fnInsert(aNumsArray[LPsKey][LPsKey2]['aResult']);
				 }
				 aNumsRoad[LPsKey]['aPrintRoad0'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aNumberArray,6);
 
				 aNumsRoad[LPsKey]['aPrintRoad1'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[1],6);
				 aNumsRoad[LPsKey]['aPrintRoad2'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[2],6);
				 aNumsRoad[LPsKey]['aPrintRoad3'] = cRoad.fnDrawPic(aNumsRoad[LPsKey].aRoadArray[3],6);
				 // 大路一定都會出 用大路判斷
				 if($('.JqRoad0_'+LPsKey+'_sub').length > 0){
					 fnDrawRoad(aNumsRoad[LPsKey],LPsKey,1);
				 }
				 if($('.JqRoad0_'+LPsKey).length > 0){
					 fnDrawRoad(aNumsRoad[LPsKey],LPsKey);
				 }
				 if(LPsKey == gnGame){
					 fnAskRoad(aNumsRoad[LPsKey]);
				 }
				 fnGameSetRoadCss();
			 }
		 },
		 error: function (exception) {
			 console.log('Exeption:' + exception.responseText);
		 }
	 });
 }
 
 // 
 function fnCloseBet(){
	 $('.JqStopBet').addClass('active');
	 // $('.JqCard').css('transform','rotateY(0deg)');
	 // $('.JqCardP3').css('transform','rotateZ(90deg)');
	 // $('.JqCardB3').css('transform','rotateZ(90deg)');
	 // $('.JqClock').removeClass('active');
	 $('.JqFinalBox').addClass('active');
	 // $('.JqPlayerGrade').text(0);
	 // $('.JqBankerGrade').text(0);
 }
 
 function fnOpenBet(){
	 $('.JqStopBet').removeClass('active');
	 // $('.JqCard').css('transform','rotateY(0deg)');
	 // $('.JqCardP3').css('transform','rotateZ(90deg)');
	 // $('.JqCardB3').css('transform','rotateZ(90deg)');
	 $('.JqCard').removeClass('active');
	 $('.JqCard').removeClass('spin');
	 $('.JqClock').addClass('active');
	 // $('.JqFinalBox').removeClass('active');
	 $('.JqPlayerGrade').text(0);
	 $('.JqBankerGrade').text(0);
 }
 
 function fnClockCountDown(nGame,nSecond,nSettle=0){
	 if(nSettle == 0){
		 if(nSecond < 0 || gaCheck[nGame]['nWhiteTimer'] == 0){
			 if(nGame==gnGame){
				 $('.JqStopBet').addClass('active');
				 $('.JqClock').removeClass('active');
				 $('.JqCard1').addClass('active');
				 $('.JqFinalBox').addClass('active');
			 }
			 $('.JqOpenNow_'+nGame).addClass('active');// 開牌中
			 gaCheck[nGame]['nWhiteTimer'] = 0;
			 return;
		 }
		 $('.JqClock_'+nGame).removeClass('FontRed');
		 $('.JqOpenNow_'+nGame).removeClass('active');
		 $('.JqClock_'+nGame).text(nSecond);
	 }
	 else{
		 if(nSecond < 0 || gaCheck[nGame]['nRedTimer'] == 0 || gaCheck[nGame]['nWhiteTimer'] == 1){
			 gaCheck[nGame]['nRedTimer'] = 0;
			 $('.JqClock_'+nGame).removeClass('FontRed');
			 return;
		 }
		 $('.JqClock_'+nGame).text(nSecond);
	 }
	 setTimeout(fnClockCountDown,1000,nGame,nSecond-1,nSettle);
 }
 
 function fnGetGrades(aNums,aVerify=undefined)
 {
	 $aBanker = [];
	 $aBankerGrade = [];
	 $nBankerGrade = 0;
	 $aPlayer = [];
	 $aPlayerGrade = [];
	 $nPlayerGrade = 0;
	 $aReturn = [];
	 nBankerPair = 0;
	 nPlayerPair = 0;
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
 
	 if(aNums['sBanker'] !== undefined){
		 $aBanker = aNums['sBanker'].split(',');
		 for(let $LPnKey in $aBanker)
		 {
			 if($aBanker[$LPnKey] == '') continue;
			 $LPnKey = parseInt($LPnKey);
			 sClassName = '.JqCardB'+($LPnKey+1).toString();
			 $aBankerGrade[$LPnKey] = $aGrade[$aBanker[$LPnKey].substr(1)];
			 $nBankerGrade += $aBankerGrade[$LPnKey];
			 if($(sClassName).length > 0 && !$(sClassName).hasClass('spin')){
				 $(sClassName+'Src').attr('src','images/card/'+$aBanker[$LPnKey]+'.png');
				 $nBankerGrade %= 10;
				 if($LPnKey == 2){
					 $(sClassName).addClass('active');
					 setTimeout(function(sClassName,$nBankerGrade){
						 $(sClassName).addClass('spin');
						 $('.JqBankerGrade').text($nBankerGrade);
					 },2000,sClassName,$nBankerGrade);
				 }
				 else{
					 $(sClassName).addClass('active');
					 setTimeout(function(sClassName,$nBankerGrade){
						 $(sClassName).addClass('spin');
						 $('.JqBankerGrade').text($nBankerGrade);
					 },1000,sClassName,$nBankerGrade);
					 // $(sClassName).addClass('spin');
					 // if($LPnKey == 1){
					 // 	$('.JqBankerGrade').text($nBankerGrade);
					 // }
				 }
			 }
		 }
	 }
 
	 if(aNums['sPlayer'] !== undefined){
		 $aPlayer = aNums['sPlayer'].split(',');
		 for(let $LPnKey in $aPlayer)
		 {
			 if($aPlayer[$LPnKey] == '') continue;
			 $LPnKey = parseInt($LPnKey);
			 sClassName = '.JqCardP'+($LPnKey+1).toString();
			 $aPlayerGrade[$LPnKey] = $aGrade[$aPlayer[$LPnKey].substr(1)];
			 $nPlayerGrade += $aPlayerGrade[$LPnKey];
			 if($(sClassName).length > 0 && !$(sClassName).hasClass('spin')){
				 $(sClassName+'Src').attr('src','images/card/'+$aPlayer[$LPnKey]+'.png');
				 $nPlayerGrade %= 10;
				 if($LPnKey == 2){
					 $(sClassName).addClass('active');
					 setTimeout(function(sClassName,$nPlayerGrade){
						 $(sClassName).addClass('spin');
						 $('.JqPlayerGrade').text($nPlayerGrade);
					 },2000,sClassName,$nPlayerGrade);
				 }
				 else{
					 $(sClassName).addClass('active');
					 setTimeout(function(sClassName,$nPlayerGrade){
						 $(sClassName).addClass('spin');
						 $('.JqPlayerGrade').text($nPlayerGrade);
					 },1000,sClassName,$nPlayerGrade);
					 // $(sClassName).addClass('spin');
					 // if($LPnKey == 1){
					 // 	$('.JqPlayerGrade').text($nPlayerGrade);
					 // }
				 }
			 }
		 }
	 }
 
	 setTimeout(function(){
		 if($nPlayerGrade > $nBankerGrade){
			 $('.JqPlayerZone').addClass('active');
		 }
		 else if($nPlayerGrade < $nBankerGrade){
			 $('.JqBankerZone').addClass('active');
			 if($nBankerGrade == 6){
				 $('.JqSixZone').addClass('active');
			 }	
		 }
		 else{
			 $('.JqDrawZone').addClass('active');
		 }
	 
		 if($aBanker[1] != undefined && $aBanker[0].substr(1) == $aBanker[1].substr(1)){
			 $('.JqBankerPairZone').addClass('active');
		 }
		 if($aPlayer[1] != undefined && $aPlayer[0].substr(1) == $aPlayer[1].substr(1)){
			 $('.JqPlayerPairZone').addClass('active');
		 }
	 },3000);
	 
 
	 let nswitch = 0;
	 if(aVerify != undefined){
		 if($('.JqCardB3').hasClass('active') && !$('.JqCardP3').hasClass('active')){
			 console.log('switch');
			 nswitch = 1;
			 nTempIndex = $('.JqCardB3').parent('.JqVerCard').attr('data-num');
			 $('.JqCardB3').parent('.JqVerCard').attr('data-num',$('.JqCardP3').parent('.JqVerCard').attr('data-num'));
			 $('.JqCardB3').siblings('.JqVerCardNum').text($('.JqCardP3').parent('.JqVerCard').attr('data-num')+1);
			 $('.JqCardP3').parent('.JqVerCard').attr('data-num',nTempIndex);
			 $('.JqCardP3').siblings('.JqVerCardNum').text(nTempIndex+1);
			 console.log(nTempIndex);
		 }
		 /*
		 1 把nSort對應的key,public,圖片都替換掉
		 */
		 nTempIndex = 0;
		 for(let LPnIndex in aVerify){
			 if(LPnIndex > nTempIndex){
				 nTempIndex = LPnIndex;
			 }
			 console.log(LPnIndex,nTempIndex);
			 $('.JqVerCard[data-num="'+LPnIndex+'"]').find('.JqVerCardImgSrc').attr('src','images/card/'+aVerify[LPnIndex]['sCardCode']+'.png');
			 $('.JqVerCard[data-num="'+LPnIndex+'"]').find('.JqVerCardDivSrc').css('background-image','url(\'images/card/'+aVerify[LPnIndex]['sCardCode']+'.png\')');
			 $('.JqVerCard[data-num="'+LPnIndex+'"]').attr('data-scardcode',aVerify[LPnIndex]['sCardCode']);
			 $('.JqVerCard[data-num="'+LPnIndex+'"]').attr('data-scardkey',aVerify[LPnIndex]['sCardKey']);
			 $('.JqVerCard[data-num="'+LPnIndex+'"]').attr('data-scardpublic',aVerify[LPnIndex]['sCardPublic']);
		 }
		 // console.log(aVerify,nTempIndex);
		 gaCheck[gnGame]['nSortIndex'] = parseInt(nTempIndex)+1;
	 }
	 sCard1 = $('.JqBlockReadyCard1').attr('data-num');
	 sCard2 = $('.JqBlockReadyCard2').attr('data-num');
	 if($('.JqVerCard[data-num="'+sCard1+'"]').find('.JqCard ').hasClass('active'))
	 {
		 $('.JqBlockReadyCard1').animate(
		 {
			 'top':$('.JqVerCard[data-num="'+sCard1+'"]').offset().top + 40+'px',
			 'left':$('.JqVerCard[data-num="'+sCard1+'"]').offset().left+'px'
		 },500);
		 setTimeout(function(){
			 $('.JqVerCard[data-num="'+sCard1+'"]').css('visibility','visible');
			 $('.JqBlockReadyCard1').hide();
		 },500);
	 }
	 if($('.JqVerCard[data-num="'+sCard2+'"]').find('.JqCard ').hasClass('active'))
	 {
		 $('.JqBlockReadyCard2').animate(
		 {
			 'top':$('.JqVerCard[data-num="'+sCard2+'"]').offset().top + 40+'px',
			 'left':$('.JqVerCard[data-num="'+sCard2+'"]').offset().left+'px'
		 },500);		
		 setTimeout(function(){
			 $('.JqVerCard[data-num="'+sCard2+'"]').css('visibility','visible');
			 $('.JqBlockReadyCard2').hide();
		 },500);
	 }
	 if(nswitch == 1){
 
		 // alert('switch')
	 }
 }
 
 function fnAskRoad(oRoad){
	 let sRoad = JSON.stringify(oRoad);
	 let aDeepCopyPW = JSON.parse(sRoad);
	 let aDeepCopyBW = JSON.parse(sRoad);
	 let oAskRoadPW = new cRoad();
	 let oAskRoadBW = new cRoad();
	 let aResultColor = {true:'Red',false:'Blue'};
	 for(let LPsKey in oAskRoadPW){
		 oAskRoadPW[LPsKey] = aDeepCopyPW[LPsKey];
		 oAskRoadBW[LPsKey] = aDeepCopyBW[LPsKey];
	 }
	 oAskRoadPW.fnInsert(['PW']);
	 oAskRoadBW.fnInsert(['BW']);
	 nLen1 = oAskRoadPW.aRoadArray[1].length-1;
	 nLen2 = oAskRoadPW.aRoadArray[2].length-1;
	 nLen3 = oAskRoadPW.aRoadArray[3].length-1;
	 $('.JqAskPW1,.JqAskPW2,.JqAskPW3,.JqAskBW1,.JqAskBW2,.JqAskBW3').removeClass('Blue Red BgBlue BgRed blue red');
	 if(nLen1 >= 0){
		 $('.JqAskPW1').addClass(aResultColor[oAskRoadPW.aRoadArray[1][nLen1].bResult]);
		 $('.JqAskBW1').addClass(aResultColor[oAskRoadBW.aRoadArray[1][nLen1].bResult]);
	 }
	 if(nLen2 >= 0){
		 $('.JqAskPW2').addClass('Bg'+aResultColor[oAskRoadPW.aRoadArray[2][nLen2].bResult]);
		 $('.JqAskBW2').addClass('Bg'+aResultColor[oAskRoadBW.aRoadArray[2][nLen2].bResult]);
	 }
	 if(nLen3 >= 0){
		 $('.JqAskPW3').addClass('Bg'+aResultColor[oAskRoadPW.aRoadArray[3][nLen3].bResult]);
		 $('.JqAskBW3').addClass('Bg'+aResultColor[oAskRoadBW.aRoadArray[3][nLen3].bResult]);
	 }
	 
 }
 
 function fnCheckRoundStatus(aNums,nGame){
	 if(aNums['aLastest']['nFinalRound'] == '1' || aNums['aLastest']['nDayLast'] == '1'){
		 $('.JqFinalRound').addClass('active');
	 }else{
		 $('.JqFinalRound').removeClass('active');
	 }
 
	 if(parseInt(aNums['aLastest']['nDayLast']) > 0){
		 $('.JqDayLastBlock').addClass('active');
		 if(aNums['aLastest']['nDayLast'] == '1' && aNums['aLastest']['sNums'] != ''){
			 $('.JqDayLast').text(0);
		 }
		 else{
			 $('.JqDayLast').text(parseInt(aNums['aLastest']['nDayLast']));
		 }
	 }else{
		 $('.JqDayLastBlock').removeClass('active');
	 }
 
	 if(aNums['aLastest']['nCancel'] == '1'){
		 $('.JqStopBet').removeClass('active');
		 $('.JqPauseRound').removeClass('active');
		 $('.JqCancelRound').addClass('active');
		 gaCheck[nGame]['nWhiteTimer'] = 0;
		 gaCheck[nGame]['nRedTimer'] = 0;
	 }
	 else if(aNums['aLastest']['nCancel'] == '2'){
		 $('.JqStopBet').removeClass('active');
		 $('.JqCancelRound').removeClass('active');
		 $('.JqPauseRound').addClass('active');
		 gaCheck[nGame]['nWhiteTimer'] = 0;
		 gaCheck[nGame]['nRedTimer'] = 0;
	 }
	 else{
		 $('.JqCancelRound').removeClass('active');
		 $('.JqPauseRound').removeClass('active');
	 }
 
	 if(aNums['aSetting']['nShuffling'] == '1'){
		 $('.JqCard').removeClass('active');
		 $('.JqCard').removeClass('spin');
		 $('.JqShuffling').addClass('active');
	 //  $('.JqStopBet').removeClass('active');
		 $('.JqPauseRound').removeClass('active');
		 $('.JqCancelRound').removeClass('active');
		 $('.JqPlayerGrade').text(0);
		 $('.JqBankerGrade').text(0);
		 // $('.JqFinalBox').removeClass('active');
	 }
	 else{
		 $('.JqShuffling').removeClass('active');
	 }
 }
 
 function fnResetRoad(nGame){
	 fnDrawBoard({},nGame);
	 let aLNumsRoad = {};
	 aLNumsRoad['aPrintRoad0'] = {'aPrintRoad':{},'aPrintBoard':{}};
	 aLNumsRoad['aPrintRoad1'] = {'aPrintRoad':{},'aPrintBoard':{}};
	 aLNumsRoad['aPrintRoad2'] = {'aPrintRoad':{},'aPrintBoard':{}};
	 aLNumsRoad['aPrintRoad3'] = {'aPrintRoad':{},'aPrintBoard':{}};
	 fnDrawRoad(aLNumsRoad,nGame);	
	 $('.JqAskPW1,.JqAskPW2,.JqAskPW3,.JqAskBW1,.JqAskBW2,.JqAskBW3').removeClass('Blue Red BgBlue BgRed blue red');
 }
 
 function fnGenerateChip(aBetData){
	 if($('.JqPlayerBetChip').length == 0){
		 let aBetCount = {'PP':0,'SIX':0,'BP':0,'PW':0,'DRAW':0,'BW':0};
		 let aBetMoney = {'PP':0,'SIX':0,'BP':0,'PW':0,'DRAW':0,'BW':0};
		 for(let LPsKey in aBetData){
			 if(gaBetPool[LPsKey] == undefined){
				 gaBetPool[LPsKey] = aBetData[LPsKey];		
			 }
		 }
		 for(let LPsKey in gaBetPool){
			 aBetCount[gaBetPool[LPsKey]['sContent0']]++;
			 aBetMoney[gaBetPool[LPsKey]['sContent0']]+=parseFloat(gaBetPool[LPsKey]['nMoney0']);
		 }
		 $('.JqPlayerPairMoney').text(aBetMoney['PP']);
		 $('.JqSixMoney').text(aBetMoney['SIX']);
		 $('.JqBankerPairMoney').text(aBetMoney['BP']);
		 $('.JqPlayerWinMoney').text(aBetMoney['PW']);
		 $('.JqDrawMoney').text(aBetMoney['DRAW']);
		 $('.JqBankerWinMoney').text(aBetMoney['BW']);
		 $('.JqPlayerPairCount').text(aBetCount['PP']);
		 $('.JqSixCount').text(aBetCount['SIX']);
		 $('.JqBankerPairCount').text(aBetCount['BP']);
		 $('.JqPlayerWinCount').text(aBetCount['PW']);
		 $('.JqDrawCount').text(aBetCount['DRAW']);
		 $('.JqBankerWinCount').text(aBetCount['BW']);
 
		 return;//手機板
	 }
	 for(let LPsKey in aBetData){
		 if(gaBetPool[LPsKey] == undefined){
			 gaBetPool[LPsKey] = aBetData[LPsKey];
			 for(i=0;i<6;i++){
				 let nRandomBox = fnGetRandom(0,6);
				 $PutinBox = $($('.JqChip'+aBetData[LPsKey]['sContent0'])[nRandomBox]);
				 if($PutinBox.html() == ''){
					 let $Chip = $($('.JqSampleChip').html());
					 $Chip[2].innerText = aBetData[LPsKey]['nMoney0'];
					 $PutinBox.append($Chip);
					 $PutinBox.addClass('JqAdded');
					 break;
				 }
			 }			
		 }
	 }
	 // if(fnGetRandom(0,100) <= 30){
	 // 	let nBoxLen = $('.JqPlayerBetChip').length;
	 // 	let nRandomChip = fnRandomChip();
	 // 	let nRandomBox = fnGetRandom(0,nBoxLen-1);
	 // 	let $Chip = $($('.JqSampleChip').html());
	 // 	$Chip[2].innerText = nRandomChip;
	 // 	if($('.JqPlayerBetChip')[nRandomBox].innerText == ''){
	 // 		$($('.JqPlayerBetChip')[nRandomBox]).append($Chip);
	 // 		$($('.JqPlayerBetChip')[nRandomBox]).addClass('JqAdded');
	 // 	}
	 // }	
 }
 
 function fnRandomChip(){
	 // let aChips = [500,1000,5000,10000,20000,50000];
	 let aChips = [100,200,300,500,600,1000];
	 let nLen_1 = aChips.length - 1;
	 // let nReturn = aChips[fnGetRandom(0,nLen_1)] * fnGetRandom(1,2) + aChips[fnGetRandom(0,nLen_1)] * fnGetRandom(0,1);
	 let nReturn = aChips[fnGetRandom(0,nLen_1)];
	 return nReturn;
 };
 
 function fnGetRandom(nMin,nMax){
	 nReturn = Math.floor(Math.random() * nMax)+nMin+1;
	 return nReturn;
 };