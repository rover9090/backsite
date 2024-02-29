var player;
$(document).ready(function () {

	nOldWidth = $(window).width();
	// let nNewWidth = $(window).height();

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
			// "height" : '320'//视频的显示高度，请尽量使用视频分辨率高度
			"autoplay"  : true,
			"live":true,
		};

		if($(window).width() > $(window).height())
		{
			aSetting = {
				"m3u8": "https://"+sUrl+".m3u8",
				"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
				"webrtc":"webrtc://"+sUrl+"",
				// "webrtc_hd":"webrtc://"+sUrl+"_hd",
				// "webrtc_sd":"webrtc://"+sUrl+"_sd",
				"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
				"poster" : "",
				"mp4":"http://xxx.vod.myqcloud.com/xxx.mp4",
				"width" :  $(window).width(),//视频的显示宽度，请尽量使用视频分辨率宽度
				"height" : $(window).height(),//视频的显示高度，请尽量使用视频分辨率高度
				"autoplay"  : true,
				"live":true,
			};			
		}
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
			// "height" : '320'//视频的显示高度，请尽量使用视频分辨率高度
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
	$('.vcp-controls-panel').hide();//把播放控制bar隱藏
	test = setInterval(() => {
		if(player.playing() == false){
			player.play();

		}
		if(player.playing()){
			clearInterval(test);
			$('.vcp-error-tips').remove();
			$('.vcp-loading').remove();
			$('.vcp-poster').remove();
			// if(gaCookie['nSound0'] !== undefined && gaCookie['nSound0'] == 0){
			// 	player.mute(true);
			// }		
		}
		
	}, 1000);

	// timeCheckScreen = setInterval(fnFullScreen, 1000);
	// fnFullScreen(nOldWidth);
});

// function fnFullScreen(){

// 	// console.log(nOriginWidth);
// 	console.log($(window).width());

// 	// if($(window).width() != nOriginWidth && player.playing() == true)
// 	// {
// 	// 	location.reload();
// 	// }
// 	// if($(window).width() > $(window).height() && player.playing() == false)
// 	// {
// 	// 	// console.log($(window).width());
// 	// 	// console.log($(window).height());
// 	// 	// player.destroy();

// 	// 	let sUrl = $('.JqStream').val()+gnGame;

// 	// 	aSetting = {
// 	// 		"m3u8": "https://"+sUrl+".m3u8",
// 	// 		"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
// 	// 		"webrtc":"webrtc://"+sUrl+"",
// 	// 		// "webrtc_hd":"webrtc://"+sUrl+"_hd",
// 	// 		// "webrtc_sd":"webrtc://"+sUrl+"_sd",
// 	// 		"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
// 	// 		"poster" : "",
// 	// 		"mp4":"http://xxx.vod.myqcloud.com/xxx.mp4",
// 	// 		// "width" :  480,//视频的显示宽度，请尽量使用视频分辨率宽度
// 	// 		// "height" : 320,//视频的显示高度，请尽量使用视频分辨率高度
// 	// 		"autoplay"  : true,
// 	// 		"live":true,
// 	// 	};
		
// 	// 	player =  new TcPlayer('video-player',aSetting);
// 	// 	$('.vcp-controls-panel').hide();//把播放控制bar隱藏
// 	// 	test = setInterval(() => {
// 	// 		if(player.playing() == false){
// 	// 			player.play();
// 	// 		}
// 	// 		if(player.playing()){
// 	// 			clearInterval(test);
// 	// 		}
			
// 	// 	}, 1000);
// 	// }
// }

function fnFullScreen(nOldWidth){

	setInterval(function(){

		if($(window).width() != nOldWidth && player.playing() == true)
		{
			nOldWidth = $(window).width();
			// location.reload();
			player.destroy();

			let sUrl = $('.JqStream').val()+gnGame;

			if($(window).width() > $(window).height())
			{
				aSetting = {
					"m3u8": "https://"+sUrl+".m3u8",
					"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
					"webrtc":"webrtc://"+sUrl+"",
					// "webrtc_hd":"webrtc://"+sUrl+"_hd",
					// "webrtc_sd":"webrtc://"+sUrl+"_sd",
					"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
					"poster" : "",
					"mp4":"http://xxx.vod.myqcloud.com/xxx.mp4",
					"width" :  $(window).width(),//视频的显示宽度，请尽量使用视频分辨率宽度
					"height" : $(window).height(),//视频的显示高度，请尽量使用视频分辨率高度
					"autoplay"  : true,
					"live":true,
				};

				$('.userinfo').addClass('DisplayBlockNone');
			}
			else
			{
				aSetting = {
					"m3u8": "https://"+sUrl+".m3u8",
					"flv": "https://"+sUrl+".flv", //增加了一个 flv 的播放地址，用于PC平台的播放 请替换成实际可用的播放地址
					"webrtc":"webrtc://"+sUrl+"",
					// "webrtc_hd":"webrtc://"+sUrl+"_hd",
					// "webrtc_sd":"webrtc://"+sUrl+"_sd",
					"autoplay" : true,      //iOS 下 safari 浏览器，以及大部分移动端浏览器是不开放视频自动播放这个能力的
					"poster" : "",
					"mp4":"http://xxx.vod.myqcloud.com/xxx.mp4",
					// "width" :  $(window).width(),//视频的显示宽度，请尽量使用视频分辨率宽度
					// "height" : $(window).height(),//视频的显示高度，请尽量使用视频分辨率高度
					"autoplay"  : true,
					"live":true,
				};

				$('.userinfo').removeClass('DisplayBlockNone');
			}

			player =  new TcPlayer('video-player',aSetting);
			$('.vcp-controls-panel').hide();//把播放控制bar隱藏
			if(player.playing() == false){
				player.play();
			}
		}

	},1000);
}