function fnCurrentTime(sFormat='') {
	var Current = new Date();
	sMonth = Current.getMonth()+1;
	sMonth = sMonth<10?'0'+sMonth:sMonth.toString();
	sDate = Current.getDate();
	sDate = sDate<10?'0'+sDate:sDate.toString();
	sHours = Current.getHours();
	sHours = sHours<10?'0'+sHours:sHours.toString();
	sMinutes = Current.getMinutes();
	sMinutes = sMinutes<10?'0'+sMinutes:sMinutes.toString();
	sSeconds = Current.getSeconds();
	sSeconds = sSeconds<10?'0'+sSeconds:sSeconds.toString();
	sReturn = sMonth+'/'+sDate+' '+sHours+':'+sMinutes+':'+sSeconds;
	return sReturn;
}