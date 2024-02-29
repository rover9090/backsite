$(document).ready(function () {
	laydate.render({ elem: '.JqStartTime', type: 'datetime' });
	laydate.render({ elem: '.JqEndTime', type: 'datetime' });

	$(".JqCopy").click(function (event)
	{
		var oCopyText = $(this).parent().siblings('.JqCopyMe').children('div');
		var Range = document.createRange();
		Range.selectNodeContents(oCopyText[0]);
		var Sel = window.getSelection();
		Sel.removeAllRanges();
		Sel.addRange(Range);
		document.execCommand('copy');
	});
});