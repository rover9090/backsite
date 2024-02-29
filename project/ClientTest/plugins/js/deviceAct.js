$(document).ready(function()
{
      var sDevice             = window.navigator.userAgent;
      var nDevicePixelRatio   = window.devicePixelRatio;
      var nScreenWidth        = window.screen.width;
      var nScreenHeight       = window.screen.height;
      var bFull               = false;

      aDevice.forEach(function(oItem)
      {
            var sReDevice = new RegExp(oItem.sDevice,'gi');
            if(sReDevice.test(sDevice) && nDevicePixelRatio && nDevicePixelRatio === oItem.nDevicePixelRatio && nScreenWidth === oItem.nScreenWidth && nScreenHeight === oItem.nScreenHeight)
            {
                  // Action
                  bFull = oItem.bFull;
            }
            else if(sReDevice.test(sDevice))
            {
                  // Action
            }

            if(bFull)
            {
                  $('.JqFooter').css('padding','0 0 45px 0');
            }
      });

      
})