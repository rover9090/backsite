var fnGetCookieValue;
var fnUpdateCookie;
var fnDeleteCookie;
function fnGetCookieValue() {
	let aCookieObj = [];
	let aCookieAry = document.cookie.split(';');
	let aCookie;
	let nCookieLength = aCookieAry.length;
	
	for (let i=0, l=aCookieAry.length; i<l; ++i) {
		aCookie = jQuery.trim(aCookieAry[i]);
		aCookie = aCookie.split('=');
		aCookieObj[aCookie[0]] = aCookie[1];
	}
	aCookieObj.length = nCookieLength;
	
	return aCookieObj;
}

function fnUpdateCookie(sName,sValue,sExpireMs='') {
	if(sExpireMs === ''){
		sExpireMs = aJSDEFINE['COOKIE_REMEMBER'] * 1000;
		document.cookie = sName+'='+sValue+'; expires='+sExpireMs;
	}
	else{
		document.cookie = sName+'='+sValue+'; expires='+sExpireMs;
	}
}

function fnDeleteCookie(sName) {
	let oDate = new Date();
	oDate.setTime(oDate.getTime() - 1);
	let sExpires = 'expires='+oDate.toGMTString();
	document.cookie = sName+'=0; expires='+sExpires;
}