$(document).ready(function()
{
	$('.JqPromoBtn').on('click', function(event)
	{
		$('input[name=nType]').val($(this).data('value'));
		if ($(this).data('value') === 1)
		{
			$('.JqPromoCode').removeClass('active');
		}
		else
		{
			$('.JqPromoCode').addClass('active');
		}
	});
})