// $(document).ready(function () { //試試看不要等他
	$.ajax({
		url: sPhpUrl,
		type: 'POST',
		data: {
			'sJWT': sPhpJWT,
			'nGame': nPhpGame,
			'nNoId': nPhpNoId,
		},
		success: function (oRes) {
		},
		error: function (exception) {
			if(exception.statusText == 'timeout'){
				console.log('timeout');
			}
			else{
				console.log('Exeption:' + exception.responseText);
			}
		}
	});
	// alert(sPhpLocation);
	location.href = sPhpLocation;
// });
