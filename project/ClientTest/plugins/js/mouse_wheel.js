$(document).ready(function()
{
      // 橫向滾動卷軸
      $('.JqScrollAxisX').mousewheel(function(e)
      {
            var scrollPos = $(this).scrollLeft();
            var moveAmount = 25;
            if(e.deltaY > 0)
            {
                  $(this).scrollLeft((scrollPos-moveAmount));
            }
            else
            {
                  $(this).scrollLeft((scrollPos+moveAmount));
            }
            e.preventDefault();
      });
});