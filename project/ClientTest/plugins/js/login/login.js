$(document).ready(function()
{
      $('.JqLogin').on('click', function ()
	{		
		$('input[type="password"]').val(md5($('input[type="password"]').val()));
            $(this).closest('form').submit();
	});
});