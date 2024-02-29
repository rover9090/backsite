/*
<!-- 庄:GameTxtBox class加BgRed；和:加BgGreen；閑:加BgBlue-->
<div class="GameTxtBox BgBlue">
	<div class="GameTxt">閑</div>
	<!-- 藍點在GameDot class加Blue；紅點加Red-->
	<div class="GameDot JqGameDot Blue"></div>
	<div class="GameDot JqGameDot Red"></div>
</div>
*/
aBoardCell = {'BW':'','PW':'','DRAW':''};
aBoardCell['BW'] = ''+
'<div class="GameTxtBox JqResultBox BgRed">' +
'	<div class="GameTxt JqResultWord">庄</div>' +
'</div>';
aBoardCell['PW'] = ''+
'<div class="GameTxtBox JqResultBox BgBlue">' +
'	<div class="GameTxt JqResultWord">閑</div>' +
'</div>';
aBoardCell['DRAW'] = ''+
'<div class="GameTxtBox JqResultBox BgGreen">' +
'	<div class="GameTxt JqResultWord">和</div>' +
'</div>';
aBoardCell['BP'] = '<div class="GameDot JqGameDot Red"></div>';
aBoardCell['PP'] = '<div class="GameDot JqGameDot Blue"></div>';

/**
<!-- 藍圈: GameBigCircle class加Blue；紅圈加Red-->
<div class="GameBigCircle Blue">
	<!-- 點點GameDot；藍點再加Blue；紅點加Red-->
	<div class="GameDot JqGameDot Blue"></div>
	<div class="GameDot JqGameDot Red"></div>
	<!-- 綠斜線-->
	<div class="GameHo BG green"></div>
</div>
 */

aRoad0Cell = {'BW':'','PW':'','DRAW':'','BP':'','PP':''};
aRoad0Cell['BW'] = '<div class="GameBigCircle JqBigPattern Red"></div>';
aRoad0Cell['BP'] = '<div class="GameDot JqGameDot Red"></div>';
aRoad0Cell['PW'] = '<div class="GameBigCircle JqBigPattern Blue"></div>';
aRoad0Cell['PP'] = '<div class="GameDot JqGameDot Blue"></div>';
aRoad0Cell['DRAW'] = '<div class="GameHo BG green"></div>';

/**
<!-- 藍圈: GameSmallCircle class加Blue；紅圈加Red-->
<!-- 左上class加LftTop；左下加LftBot；右上加RgtTop；右下加RgtBot -->
<div class="GameSmallCircle Blue LftTop"></div>
<div class="GameSmallCircle Red LftBot"></div>
<div class="GameSmallCircle Blue RgtTop"></div>
<div class="GameSmallCircle Blue RgtBot"></div>
 */
aRoad1Cell = {'BW':'','PW':''};
aRoad1Cell['BW'] = '<div class="GameSmallCircle JqSmallPattern Red"></div>';
aRoad1Cell['PW'] = '<div class="GameSmallCircle JqSmallPattern Blue"></div>';

/**
<!-- 藍實心: GameSmallCircle class加BgBlue；紅實心加BgRed-->
<!-- 左上class加LftTop；左下加LftBot；右上加RgtTop；右下加RgtBot -->
<div class="GameSmallCircle BgRed LftTop"></div>
<div class="GameSmallCircle BgBlue LftBot"></div>
<div class="GameSmallCircle BgBlue RgtTop"></div>
<div class="GameSmallCircle BgBlue RgtBot"></div>
 */
aRoad2Cell = {'BW':'','PW':''};
aRoad2Cell['BW'] = '<div class="GameSmallCircle JqSmallPattern BgRed"></div>';
aRoad2Cell['PW'] = '<div class="GameSmallCircle JqSmallPattern BgBlue"></div>';

/**
<!-- 斜線-->
<div class="GameHo BG red"></div>
<div class="GameHo BG blue"></div>
 */
aRoad3Cell = {'BW':'','PW':''};
aRoad3Cell['BW'] = '<div class="GameHo BG red"></div>';
aRoad3Cell['PW'] = '<div class="GameHo BG blue"></div>';