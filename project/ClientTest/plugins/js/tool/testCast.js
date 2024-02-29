var livePusher = new TXLivePusher();
livePusher.setRenderView('id_local_video');

// document.getElementById('id_local_video').getElementsByTagName('video')[0].muted = true;

// 设置视频质量
livePusher.setVideoQuality('720p');
// 设置音频质量
livePusher.setAudioQuality('standard');
// 自定义设置帧率
livePusher.setProperty('setVideoFPS', 25);

// 打开摄像头
livePusher.startCamera();
// 打开麦克风
livePusher.startMicrophone();


// livePusher.startPush('rtmp://139147.livepush.myqcloud.com/live/test');

var hasVideo = false;
var hasAudio = false;
var isPush = false;
livePusher.setObserver({
	onCaptureFirstAudioFrame: function() {
		hasAudio = true;
		if (hasVideo && !isPush) {
			isPush = true;
			livePusher.startPush('webrtc://139147.livepush.myqcloud.com/live/test');
			// livePusher.startPush('rtmp://139147.livepush.myqcloud.com/live/test');
			// livePusher.startPush('webrtc://vt.abp77.com/live/test');
			var deviceManager = livePusher.getDeviceManager();
			deviceManager.switchCamera('e1da974a59546793445b183100e777d2225cb78e9e624ecdc4a8fc4a5cb1f1bc');
		}
	},
	onCaptureFirstVideoFrame: function() {
		hasVideo = true;
		if (hasAudio && !isPush) {
			isPush = true;
			livePusher.startPush('webrtc://139147.livepush.myqcloud.com/live/test');
			// livePusher.startPush('rtmp://139147.livepush.myqcloud.com/live/test');
			// livePusher.startPush('webrtc://vt.abp77.com/live/test');
			var deviceManager = livePusher.getDeviceManager();
			deviceManager.switchCamera('e1da974a59546793445b183100e777d2225cb78e9e624ecdc4a8fc4a5cb1f1bc');
		}
	}
});
