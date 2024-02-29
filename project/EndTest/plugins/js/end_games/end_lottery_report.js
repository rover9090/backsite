$(document).ready(function () {
	
	// 莊家結果
	$('input[name="nResult1"]').on('click' , function()
	{
		$('input[name="nResult1"][type="hidden"]').val($(this).val())
	});
	$('input[name="nResult2"]').on('click' , function()
	{
		$('input[name="nResult2"][type="hidden"]').val($(this).val())
	});
	$('input[name="nResult3"]').on('click' , function()
	{
		$('input[name="nResult3"][type="hidden"]').val($(this).val())
	});
});