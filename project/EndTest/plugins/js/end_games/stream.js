var player;
$(document).ready(function () {
	if(typeof gnGame === 'undefined') gnGame = $('input[name="nGame"]').val();
	if(gnGame < 2000){
		let sUrl = $('.JqStream').val()+gnGame;
		aSetting = {
			"m3u8": "https://"+sUrl+".m3u8",
			"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
			"webrtc":"webrtc://"+sUrl+"",
			// "webrtc_hd":"webrtc://"+sUrl+"_hd",
			// "webrtc_sd":"webrtc://"+sUrl+"_sd",
			"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
			"poster" : "",
			"mp4":"http://xxx.vod.myqcloud.com/xxx.mp4",
			// "width" :  '480',//视频的显示宽度，请尽量使用视频分辨率宽度
			// "height" : '160',//视频的显示高度，请尽量使用视频分辨率高度
			"autoplay"  : true,
			"live":true,
		};
	}
	else{
		let sUrl = $('.JqStream').val()+'2001';
		aSetting = {
			"m3u8": "https://"+sUrl+".m3u8",
			"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
			"webrtc":"webrtc://"+sUrl+"",
			// "webrtc_hd":"webrtc://"+sUrl+"_hd",
			// "webrtc_sd":"webrtc://"+sUrl+"_sd",
			"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
			"poster" : "",
			// "width" :  '480',//视频的显示宽度，请尽量使用视频分辨率宽度
			// "height" : '160',//视频的显示高度，请尽量使用视频分辨率高度
			"autoplay"  : true,
			"live":false,
		};		
		$('#video-player').css("display", "none");
	}
	// aSetting = {
	// 	// "m3u8": "https://"+sUrl+".m3u8",
	// 	"mp4": "images/video.mp4",
	// 	// "flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
	// 	// "webrtc":"webrtc://"+sUrl+"",
	// 	// "webrtc_hd":"webrtc://"+sUrl+"_hd",
	// 	// "webrtc_sd":"webrtc://"+sUrl+"_sd",
	// 	"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
	// 	"poster" : "",
	// 	// "width" :  '480',//视频的显示宽度，请尽量使用视频分辨率宽度
	// 	// "height" : '320'//视频的显示高度，请尽量使用视频分辨率高度
	// 	"autoplay"  : true,
	// 	"live":false,
	// };	
	player =  new TcPlayer('video-player',aSetting);
	test = setInterval(() => {
		if(player.playing() == false){
			player.play();
		}
		if(player.playing()){
			clearInterval(test);
			player.mute(true);
		}
	}, 1000);
})

fnRemoveFileFromFileList = function(nIndex,$Input0) {
	const dt = new DataTransfer();
	const { files } = $Input0;
	for (let i = 0; i < files.length; i++) {
		const file = files[i];
		if (nIndex !== i) dt.items.add(file) // here you exclude the file. thus removing it.
		$Input0.files = dt.files;
	}
}

var fnRemoveFileFromFileList;
var fnLoadURLToInputField;
var fnAppendImgBlock;
var sDomImageBlock;
var sDomVideoBlock;
var $File0Global;
var fnCapture;
var fnGetImgURL;
$(document).ready(function()
{
	$('#JqFile').on('change' , function(event)
	{
		$('#JqPreviewDiv').html('');
		let nFileCount = event.target.files.length;
		for (let i=0;i<nFileCount;i++) {
			let oFile = event.target.files[i];
			if (!oFile.type) {
				$('#JqFileError').text('瀏覽器不支持預覽');
				return;
			}
			// if (!oFile.type.match('image.*')) {
			// 	$('#JqFileError').text('檔案可能不是圖片');
			// 	return;
			// }
		}

		if (window.FileList && window.File && window.FileReader) {
			$('#JqFileError').text('');
			// $('.JqFile')[0].files === event.target.files
			fnAppendImgBlock(event.target);			
		}
	});

	$('div[data-showctrl="0"][data-shot=1]').on('click' , function(event)
	{
		let video = $('#video-player>div>video')[0];
		let canvas = fnCapture(video, 1);
		let dataImg = canvas.toDataURL('image/jpg');
		let sAppendBlock = sDomImageBlock;
		sAppendBlock = sAppendBlock.replace(/:src:/g,dataImg);
		$('#JqPreviewDiv').html(sAppendBlock);
		$('#JqFile')[0].setAttribute("value", dataImg);
		fnLoadURLToInputField(dataImg);
	});
});

fnAppendImgBlock = function($File0){
	let nFileCount = $File0.files.length;
	for (let i=0;i<nFileCount;i++) {
		let oFile = $File0.files[i];
		const oReader = new FileReader();
		let sAppendBlock = sDomImageBlock;
		sAppendBlock = sAppendBlock.replace(/:index:/g,i.toString());
		oReader.addEventListener('load', event => {
			sAppendBlock = sAppendBlock.replace(/:src:/g,event.target.result);
			sAppendBlock = sAppendBlock.replace(/:title:/g,oFile.name);
			$('#JqPreviewDiv').append(sAppendBlock);
		});
		oReader.readAsDataURL(oFile);
	}
}

sDomImageBlock = 
'<div>'+
	'<div class="BlockImg MarginTop5">'+
		'<img src=":src:" alt="" title=":title:" style="max-width:100%">'+
	'</div>'+
'</div>';

function fnCapture(video, scaleFactor) {
	if (scaleFactor == null) {
	    scaleFactor = 1;
	}
	var w = video.videoWidth * scaleFactor;
	var h = video.videoHeight * scaleFactor;
	var canvas = document.createElement('canvas');
	canvas.width = w;
	canvas.height = h;
	var ctx = canvas.getContext('2d');
	ctx.drawImage(video, 0, 0, w, h);
	return canvas;
}

function fnLoadURLToInputField(url){
	fnGetImgURL(url, (imgBlob)=>{
		// Load img blob to input
		let fileName = new Date().getTime()+'.jpg' // should .replace(/[/\\?%*:|"<>]/g, '-') for remove special char like / \
		let file = new File([imgBlob], fileName,{type:"image/jpeg", lastModified:new Date().getTime()}, 'utf-8');
		let container = new DataTransfer(); 
		container.items.add(file);
		document.querySelector('#JqFile').files = container.files;
		// document.querySelector('#status').files = container.files;
	
	})
}

function fnGetImgURL(url, callback){
	var xhr = new XMLHttpRequest();
	xhr.onload = function() {
		callback(xhr.response);
	};
	xhr.open('GET', url);
	xhr.responseType = 'blob';
	xhr.send();
}