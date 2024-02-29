// let aMember = JSON.parse($('.JqGroupMember').val());
// let nUid =  parseInt($('input[name=nUid]').val()); // self user id
// let nGid = parseInt($('input[name=nGid]').val()); // group id
var Height = $(".JqMsgBox").height();

sJWT = $('input[name=sSocketJWT]').val();
sSocketJSON = $('input[name=sSocketJWT]').attr('data-json');
aSocketJSON = JSON.parse(sSocketJSON);
// console.log('sJWT'+sJWT);
var nGid = parseInt($('.JqnGame').val())+100000;
// var nGid = parseInt($('.JqnGame').val());
var aFilter = JSON.parse($('.JqaFilter').val());

$('html,body').animate({scrollTop:Height},0);


//先建立 main server 的 Photon Interface 物件
////213.139.235.5
let Ms_Pi = new PhotonController.PhotonIf(Photon.ConnectionProtocol.Wss, "ssl2.paipaisss.com:23651");
//建立 main server 的 command logic 物件
let Ms_CMD_Logic = new BaseCmdLogic.MainSrvCmdLogic(Ms_Pi);
//初始設定 Photon Interface 物件的 Callback Function
Ms_Pi.InitCallbackFunction(Ms_CMD_Logic, Ms_CMD_Logic.PeerStatusCallback, Ms_CMD_Logic.ResponseCallback, Ms_CMD_Logic.EventCallback);

$(document).ready(function()
{
	// console.log(aSocketJSON);
	//連線server
	

	var xxx=0;
	setInterval(function(){ /*console.log(xxx);*/xxx++; }, 1000);

	Ms_CMD_Logic.RunConnect(gaUser['aData']['sAccount'],gaUser['aData']['sPassword'],gaUser['aData']['nUid'],nGid);
	Ms_CMD_Logic.SetGameCmdFunc(ProcessMainSrvCmd);

	if(document.addEventListener)
	{
		document.addEventListener('webkitvisibilitychange',function(){
			if(document.webkitVisibilityState == 'visible')
			{
				location.reload();
			}
		});
	}
});

//點擊發送
$('.JqSend').on('click', function(event)
{
	var reg = new RegExp('<(br|div|\/div)>','g');
	var sContent0 = $('.JqChat').val();
	var sentence =[sContent0];
	// var aMatch = CheckKeywords(sentence);
	var aMatch = [];
	var sHtml = $('.JqCopySelfMsg').html();


	$.each(aFilter, function(index, keywords)
	{
		var LPreg = new RegExp(keywords,'gi');
		LPsMsg = sContent0.replace(LPreg,'**');
		if(LPsMsg != sContent0){
			sContent0 = LPsMsg;
		}
	});
	// console.log(sContent0);
	// 敏感字阻擋 阻擋成功
	// if (aMatch.length > 0)
	// {
	// 	$.each(aMatch, function(index, keywords)
	// 	{
	// 		var LPreg = new RegExp(keywords,'g');
	// 		sContent0 = sContent0.replace(LPreg,'<span style="color:#ff0000;">'+keywords+'</span> ');
	// 	});
	// 	$('.JqChat').val(sContent0);
		
	// 	$('.JqJumpMsgBox[data-showmsg=0]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['SNOOZEKEYWORDS']);
	// 	$('.JqJumpMsgBox[data-showmsg=0]').addClass('active');
	// 	$('.JqMsgIptBox').removeClass('active');
	// 	$('.JqEmojiImgBox').removeClass('active');
	// }
	// else // 沒有可阻擋的文字
	// {
		// 替換文字轉表情
		// sContent0 = sContent0.replace(/<img class="EmojiImgIcon" src="images\/emoji\/(\d{1,3})\.png">/g, '[:$1:]');
		if(gaUser['aData']['nMute'] == 1){
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['BANMESSAGE']);
			$('.JqChat').val('');
		}
		else if(gaUser['aData']['nMoney'] <= 0){
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['CHARGEFIRST']);
			$('.JqChat').val('');
		}
		else if (sContent0.length > 0)
		{
			if (sContent0.length > 0 || $('input[name=nImgCount').val() > 0)
			{
	
				if (sContent0.length > 0) // 文字訊息
				{
					Ms_CMD_Logic.SendMessage(sContent0);
	
					//0=訊息type,1=nUid,2=name,3=msg,4=nGame
					var data=[0,gaUser['aData']['nUid'],gaUser['aData']['sName0'],sContent0,nGid];
					// console.log(data);
					$(".JqShowArea").append(SayMessage('self',data));
					$('.JqChat').val('');
				}
	
				if ($('input[name=nImgCount').val() > 0) // 圖片訊息  現在沒有先放者
				{
					// 先上傳再寄 url
					UploadFile();
	
					$('input[name=nImgCount').val(0); // 圖片歸0
					$('.JqFile.DisplayBlockNone').remove();
					$('.JqEmojiContentPhotoBox').empty();
					$('.JqEmojiContentPhotoBox').removeClass('active');
				}
	
				//滑到底
				$('.JqShowArea').animate({scrollTop:$(".JqMsgBox").height()}, 333);
				$('.JqJumpMsgBox').removeClass('active');
				// $('html').animate({scrollTop:$('html').height()}, 333);
				// $('body').removeClass('active');
				// $('.JqMsgIptBox').removeClass('active');
				// $('.JqEmojiImgBox').removeClass('active');
				// $('.JqPhotoOtherBox').removeClass('active'); // 因上傳圖片會擋住原本對話,所以塞個class給他,但送出後必須拉掉
			}
		}

	// }
});

$('.JqContent0').on("keydown", function(e)
{
	if(e.keyCode === 13 && !e.shiftKey)
	{
		e.preventDefault();
		var reg = new RegExp('<(br|div|\/div)>','g');
		var sContent0 = $('.JqChat').val();
		var sentence =[sContent0];
		// var aMatch = CheckKeywords(sentence);
		var aMatch = [];
		var sHtml = $('.JqCopySelfMsg').html();

		$.each(aFilter, function(index, keywords)
		{
			var LPreg = new RegExp(keywords,'gi');
			LPsMsg = sContent0.replace(LPreg,'**');
			if(LPsMsg != sContent0){
				sContent0 = LPsMsg;
			}
		});

		// console.log(sContent0);
		// 敏感字阻擋 阻擋成功
		// if (aMatch.length > 0)
		// {
		// 	$.each(aMatch, function(index, keywords)
		// 	{
		// 		var LPreg = new RegExp(keywords,'g');
		// 		sContent0 = sContent0.replace(LPreg,'<span style="color:#ff0000;">'+keywords+'</span> ');
		// 	});
		// 	$('.JqChat').val(sContent0);
			
		// 	$('.JqJumpMsgBox[data-showmsg=0]').find('.JqJumpMsgContentTxt').html(aJSDEFINE['SNOOZEKEYWORDS']);
		// 	$('.JqJumpMsgBox[data-showmsg=0]').addClass('active');
		// 	$('.JqMsgIptBox').removeClass('active');
		// 	$('.JqEmojiImgBox').removeClass('active');
		// }
		// else // 沒有可阻擋的文字
		// {
			// 替換文字轉表情
			// sContent0 = sContent0.replace(/<img class="EmojiImgIcon" src="images\/emoji\/(\d{1,3})\.png">/g, '[:$1:]');
			if(gaUser['aData']['nMute'] == 1){
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['BANMESSAGE']);
				$('.JqChat').val('');
			}
			else if(gaUser['aData']['nMoney'] <= 0){
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
				$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(aJSDEFINE['CHARGEFIRST']);
				$('.JqChat').val('');
			}
			else if (sContent0.length > 0)
			{
				if (sContent0.length > 0 || $('input[name=nImgCount').val() > 0)
				{

					if (sContent0.length > 0) // 文字訊息
					{
						Ms_CMD_Logic.SendMessage(sContent0);

						var data=[0,gaUser['aData']['nUid'],gaUser['aData']['sName0'],sContent0,nGid];
						// console.log(data);
						//0=訊息type,1=nUid,2=name,3=msg,4=nGame
						$(".JqShowArea").append(SayMessage('self',data));
						$('.JqChat').val('');
					}

					if ($('input[name=nImgCount').val() > 0) // 圖片訊息  現在沒有先放者
					{
						// 先上傳再寄 url
						UploadFile();

						$('input[name=nImgCount').val(0); // 圖片歸0
						$('.JqFile.DisplayBlockNone').remove();
						$('.JqEmojiContentPhotoBox').empty();
						$('.JqEmojiContentPhotoBox').removeClass('active');
					}

					//滑到底
					$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
					$('.JqJumpMsgBox').removeClass('active');
					// $('html').animate({scrollTop:$('html').height()}, 333);
					// $('body').removeClass('active');
					// $('.JqMsgIptBox').removeClass('active');
					// $('.JqEmojiImgBox').removeClass('active');
					// $('.JqPhotoOtherBox').removeClass('active'); // 因上傳圖片會擋住原本對話,所以塞個class給他,但送出後必須拉掉
				}
			}
		// }
	}
});

$('.JqDonateSubmit').on('click',function()
{
	let nMoney = $('.JqDonateMoney').text();
	let sAllitem = $('.JqDonateBox').attr('data-selectitem');
	// nMoney = 20;

	let sUrl = $('input[name=sDonateJWT]').attr('data-url');
	let sJWT = $('input[name=sDonateJWT]').val();
	$('.JqDonateBox').attr('data-selectitem','');

	$.ajax({
		url: sUrl,
		type: 'POST',
		dataType: 'json',
		data: {
			'sJWT': sJWT,
			'run_page': 1,
			'nMoney': nMoney,
			'sAllitem': sAllitem,
			'nGame': $('.JqnGame').val(),
		},
		success: function (oRes) {
			// console.log(oRes);
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').addClass('active');
			$('.JqJumpMsgBox[data-showmsg="msg0Box"]').find('.JqJumpMsgContentTxt').text(oRes['sMsg']);
			let aMessage = {'nArray':1,'aData':{}};
			$('.JqDonateBox').removeClass('active');
			$('.JqDonateMoney').text(0);
			$('.JqWindowBox').removeClass('active');
			$('.JqUserMoney').text(oRes['aUser']['nMoney']);

			if(oRes['nError'] == 0){
				for(let i in oRes['aData']){
					aMessage['aData'][i] = {
						sType: 'donate',
						nUid: gaUser['aData']['nUid'],
						sName0: oRes['aUser']['sName0'],
						sAccount: gaUser['aData']['sAccount'],
						nTargetUid: 0,
						nRoomId: $('.JqnGame').val(),
						sMsg: ' 打賞 <img class="chatroomContImg" src="'+oRes['aData'][i]['sPicUrl']+'">',
					}

					Ms_CMD_Logic.SendMessage(aMessage['aData'][i]['sMsg']);

					var data=[0,gaUser['aData']['nUid'],gaUser['aData']['sName0'],aMessage['aData'][i]['sMsg'],nGid];
					if(gaUser['aData']['sName0'] == '')
					{
						data=[0,gaUser['aData']['nUid'],gaUser['aData']['sAccount'],aMessage['aData'][i]['sMsg'],nGid];
					}
					// console.log(data);
					//0=訊息type,1=nUid,2=name,3=msg,4=nGame
					$(".JqShowArea").append(SayMessage('self',data));
				};
			}
		},
		error: function (exception) {
			console.log('Exeption:' + exception.responseText);
		}
	});
});

function ProcessMainSrvCmd(vals, pi)
{
	// console.log('----');
	console.log(vals);
	if(vals[3] == '') return;
	// 0=訊息type,1=nUid,2=name,3=msg,4=nGame

	switch(vals[0])
	{
		case 103:
		// 接收訊息
			if (/*(vals[6] == '0' || vals[6] == gaUser['aData']['nUid']) &&*/ vals[4] == nGid && vals[3] != '')
			{
				var data=[0,vals[1],vals[2],vals[3],vals[4]];
				$(".JqShowArea").append(SayMessage('other',data));
				$(".JqBtnDown").addClass('active');// scroll down
				$('.JqShowArea').animate({scrollTop:$(".JqShowArea")[0].scrollHeight}, 333);
			}
			break;
		case 106:
		// 成員變動
			// location.reload();
			// if (vals[1] == 1 && aMember[vals[2]] === undefined) // 新成員加入
			// {
			// 	aMember[vals[2]]['nId']		 = vals[2];
			// 	aMember[vals[2]]['sAccount']	 = vals[3];
			// 	aMember[vals[2]]['sHeadImage'] = vals[4];
			// }
			// if (vals[1] == 0) // 成員離開
			// {
			// 	location.reload();
			// }
			break;
	}
}

// Say message
function SayMessage(type,data,vals)
{	
	//參數1:發話者id ,參數2:發話者暱稱，參數3:聊天文字內容 ，參數4：group_id（驗證用） ，參數5: 訊息時間 ，參數6: 指定可看會員id(0:大家可看)
	var sHtml = $('.JqCopyOtherMsg').html();
	if (gaUser['aData']['nUid'] == data[1])
	{
		sHtml = $('.JqCopySelfMsg').html();
	}
	if (data[3] == '[:invite job:]')
	{
		sHtml = $('.JqCopyInviteMsg').html();
	}

	data[3] = data[3].replace(/\[:?(\d{1,3}):\]/g, '<img class="EmojiImgIcon" src="images/emoji/$1.png">');

	if (typeof data[5] === 'undefined')
	{
		var myDate = new Date();
		//獲取當前年
		var year=myDate.getFullYear();
		//獲取當前月
		var month=myDate.getMonth()+1;//月份記得+1
		//獲取當前日
		var date=myDate.getDate();
		var h=myDate.getHours();	//獲取當前小時數(0-23)
		var m=myDate.getMinutes();	//獲取當前分鐘數(0-59)
		var s=myDate.getSeconds();
		data[5] = year+'/'+month+'/'+date+' '+h+':'+m+':'+s;
	}

	sHtml = sHtml.replace('[[::nUid::]]',data[1]);
	// sHtml = sHtml.replace('[[::sName0::]]',aMember[data[1]]['sName0']);
	sHtml = sHtml.replace('[[::sName0::]]',data[2]);
	sHtml = sHtml.replace('[[::sMsg::]]',data[3]);
	// sHtml = sHtml.replace('[[::sHeadImage::]]',aMember[data[1]]['sHeadImage']);
	sHtml = sHtml.replace('[[::sCreateTime::]]',data[5]);

	return sHtml;
}

// 上傳圖片
function UploadFile()
{
	var myDate = new Date();
	var nTime = myDate.getTime();
	var sUrl = $('#JqImageForm').attr('action');
	if (true)
	{
		//0=訊息type,1=nUid,2=name,3=msg,4=nGame
		var data=[0,gaUser['aData']['nUid'],gaUser['aData']['sName0'],'<div class="JqUploading" data-time="'+nTime+'"><div class="MarginBottom10">'+aJSDEFINE['IMGUPLOADING']+'...</div> <div class="barouter Jqouter"><div class="barinner Jqinner"></div></div></div>',nGid];
		$(".JqShowArea").append(SayMessage('',data));
		$(this).addClass('active');

		$.ajax({
			url: sUrl,
			type: "POST",
			dataType: "json",
			data: new FormData(document.getElementById('JqImageForm')),
			processData: false,
			contentType : false,
			xhr: function() {
				myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload)
				{
					myXhr.upload.addEventListener('progress', function (e){
						var inner = $('.JqShowArea').find('.JqUploading[data-time='+nTime+']').find(".Jqinner")[0];
						var maxwidth = parseInt($('.Jqouter').width());
						if (e.lengthComputable)
						{
							inner.style.width = ((e.loaded / e.total) * (maxwidth-2)) + 'px';
						}
					}, false);
				}
				return myXhr;
			},
			success: function (result)
			{
				var maxwidth = parseInt($('.Jqouter').width());
				$('.JqShowArea').find('.JqUploading[data-time='+nTime+']').find('.Jqinner').width(maxwidth+'px');
				if (result.nStatus == '1')
				{
					var sContent = '';
					$.each(result.aData, function(index, LPsUrl)
					{
						/* iterate through array or object */
						sContent += '<img src="'+LPsUrl+'">';
					});
					$('.JqShowArea').find('.JqUploading[data-time='+nTime+']').html(sContent).removeClass('JqUploading');
					Ms_CMD_Logic.SendMessage(sContent);
				}
				else
				{
					$('.JqShowArea').find('.JqUploading[data-time='+nTime+']').html(aJSDEFINE['IMGUPLOADFAILED']).removeClass('JqUploading');
					$('.JqJumpMsgBox[data-showmsg=0]').find('.JqJumpMsgContentTxt').html(result.sMsg);
					$('.JqJumpMsgBox[data-showmsg=0]').addClass('active');
				}
			},
			error: function (txt)
			{
				console.log(txt);
			}
		});

	}
}

function CheckKeywords(sentence)
{
	const matched = [];
	for (var index = 0; index < sentence.length; index++)
	{
		for (var outerIndex = 0; outerIndex < aSNOOZEKEYWORDS.length; outerIndex++)
		{
			if (sentence[index].includes(aSNOOZEKEYWORDS[outerIndex]))
			{
				matched.push(aSNOOZEKEYWORDS[outerIndex]);
			}
		}
	}
	return matched;
}
function RefreshSend(){
	console.log('hi');
	Ms_CMD_Logic.RefreshSend2();
	// Ms_CMD_Logic.RunConnect(gaUser['aData']['sAccount'],gaUser['aData']['sPassword'],gaUser['aData']['nUid'],nGid);
	// Ms_CMD_Logic.SetGameCmdFunc(ProcessMainSrvCmd);
}