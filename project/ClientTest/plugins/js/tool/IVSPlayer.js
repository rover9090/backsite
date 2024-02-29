
const domain = 'meet.jit.si';
const options = {
	roomName: 'gino33456781231',
	width: "100%",
	height: "100%",
	parentNode: document.querySelector('#video-player'),
	interfaceConfigOverwrite: {
		INITIAL_TOOLBAR_TIMEOUT: 500,
		TOOLBAR_TIMEOUT: 5000,
		DISPLAY_WELCOME_FOOTER: false,
		VERTICAL_FILMSTRIP: true,
		TOOLBAR_BUTTONS: [
			'microphone', 'camera', 'closedcaptions', 'desktop', 'embedmeeting', 'fullscreen',
			'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
			'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
			'videoquality', 'filmstrip', 'invite', 'feedback', 'stats', 'shortcuts',
			'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security'		
		],
	},
	configOverwrite: {
		prejoinPageEnabled: false, //This here
		enableNoAudioDetection: false,
		enableNoisyMicDetection: false,
		disableShortcuts: true,
		disableInitialGUM: true,
		resolution: 1080,
		constraints: {
			video: {
				height: {
					ideal: 1080,
					max: 10802,
					min: 240
				}
		    }
		},
		notifications: [
			/*
			'connection.CONNFAIL', // shown when the connection fails,
		      'dialog.cameraNotSendingData', // shown when there's no feed from user's camera
			'dialog.kickTitle', // shown when user has been kicked
			'dialog.liveStreaming', // livestreaming notifications (pending, on, off, limits)
			'dialog.lockTitle', // shown when setting conference password fails
			'dialog.maxUsersLimitReached', // shown when maximmum users limit has been reached
			'dialog.micNotSendingData', // shown when user's mic is not sending any audio
			'dialog.passwordNotSupportedTitle', // shown when setting conference password fails due to password format
			'dialog.recording', // recording notifications (pending, on, off, limits)
			'dialog.remoteControlTitle', // remote control notifications (allowed, denied, start, stop, error)
			'dialog.reservationError',
			'dialog.serviceUnavailable', // shown when server is not reachable
			'dialog.sessTerminated', // shown when there is a failed conference session
			'dialog.tokenAuthFailed', // show when an invalid jwt is used
			'dialog.transcribing', // transcribing notifications (pending, off)
			'dialOut.statusMessage', // shown when dial out status is updated.
			'liveStreaming.busy', // shown when livestreaming service is busy
			'liveStreaming.failedToStart', // shown when livestreaming fails to start
			'liveStreaming.unavailableTitle', // shown when livestreaming service is not reachable
			'lobby.joinRejectedMessage', // shown when while in a lobby, user's request to join is rejected
			'lobby.notificationTitle', // shown when lobby is toggled and when join requests are allowed / denied
			'localRecording.localRecording', // shown when a local recording is started
			'notify.disconnected', // shown when a participant has left
			'notify.grantedTo', // shown when moderator rights were granted to a participant
			'notify.invitedOneMember', // shown when 1 participant has been invited
			'notify.invitedThreePlusMembers', // shown when 3+ participants have been invited
			'notify.invitedTwoMembers', // shown when 2 participants have been invited
			'notify.kickParticipant', // shown when a participant is kicked
			'notify.mutedRemotelyTitle', // shown when user is muted by a remote party
			'notify.mutedTitle', // shown when user has been muted upon joining,
		      'notify.newDeviceAudioTitle', // prompts the user to use a newly detected audio device
		    //   'notify.newDeviceCameraTitle', // prompts the user to use a newly detected camera
			'notify.passwordRemovedRemotely', // shown when a password has been removed remotely
			'notify.passwordSetRemotely', // shown when a password has been set remotely
			'notify.raisedHand', // shown when a partcipant used raise hand,
			'notify.startSilentTitle', // shown when user joined with no audio
			'prejoin.errorDialOut',
			'prejoin.errorDialOutDisconnected',
			'prejoin.errorDialOutFailed',
			'prejoin.errorDialOutStatus',
			'prejoin.errorStatusCode',
			'prejoin.errorValidation',
			'recording.busy', // shown when recording service is busy
			'recording.failedToStart', // shown when recording fails to start
			'recording.unavailableTitle', // shown when recording service is not reachable
			'toolbar.noAudioSignalTitle', // shown when a broken mic is detected
			'toolbar.noisyAudioInputTitle', // shown when noise is detected for the current microphone
			'toolbar.talkWhileMutedPopup', // shown when user tries to speak while muted
			'transcribing.failedToStart' // shown when transcribing fails to start
			*/
		]
  	},
	
};
const api = new JitsiMeetExternalAPI(domain, options);
// api.executeCommand('subject', ' ');
// api.executeCommand('toggleFilmStrip');
// api.executeCommand('toggleTileView');
/*
// This shows how to include the Amazon IVS Player with a script tag from our CDN
// If self hosting, you may not be able to use the create() method since it requires
// that file names do not change and are all hosted from the same directory.

(function (IVSPlayerPackage) {
	// First, check if the browser supports the IVS player.
	if (!IVSPlayerPackage.isPlayerSupported) {
	    console.warn("The current browser does not support the IVS player.");
	    return;
	}
  
	const PlayerState = IVSPlayerPackage.PlayerState;
	const PlayerEventType = IVSPlayerPackage.PlayerEventType;
  
	// Initialize player
	const player = IVSPlayerPackage.create();
	console.log("IVS Player version:", player.getVersion());
	player.attachHTMLVideoElement(document.getElementById("video-player"));
  
	// Attach event listeners
	player.addEventListener(PlayerState.PLAYING, function () {
	    console.log("Player State - PLAYING");
	});
	player.addEventListener(PlayerState.ENDED, function () {
	    console.log("Player State - ENDED");
	});
	player.addEventListener(PlayerState.READY, function () {
	    console.log("Player State - READY");
	});
	player.addEventListener(PlayerEventType.ERROR, function (err) {
	    console.warn("Player Event - ERROR:", err);
	});
	player.addEventListener(PlayerEventType.TEXT_METADATA_CUE, (cue) => {
	    const metadataText = cue.text;
	    const position = player.getPosition().toFixed(2);
	    console.log(
		  `PlayerEvent - TEXT_METADATA_CUE: "${metadataText}". Observed ${position}s after playback started.`
	    );
	});
  
	// Setup stream and play
	player.setAutoplay(true);
	player.load(
	    "https://a1191c8f6a5f.us-west-2.playback.live-video.net/api/video/v1/us-west-2.689429214736.channel.Hfu9N6JSprIW.m3u8"
	);
	player.setVolume(0.5);
  })(window.IVSPlayer);
  */