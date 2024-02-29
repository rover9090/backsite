<script src="plugins/js/jquery-3.3.1.min.js"></script>
<script src="plugins/js/jquery.mousewheel.min.js"></script>
<script src="plugins/js/tool/cookie.js"></script>
<script src="plugins/js/main.js?t=<?php echo VTIME;?>"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<!-- <script src="https://player.live-video.net/1.3.1/amazon-ivs-player.min.js"></script> -->
<!-- <script src="inc/JsDefine.php?sLang=<?php #echo $aSystem['sLang'].'&t='.VTIME;?>"></script> -->
<!-- <script src='https://meet.jit.si/external_api.js'></script> -->
<script src="plugins/js/deviceSpecifications.js"></script>
<script src="plugins/js/deviceAct.js"></script>
<?php
if($sPage == 'game_0')
{
?>
      <!-- 首頁 -->
      <script src="plugins/js/<?php echo $aSystem['sClientHtml'].$aSystem['nClientVer']; ?>/game.js?<?php echo VTIME;?>"></script>
<?php
}
if (isset($aJs))
{
      foreach ($aJs as $LPsUrl)
      {
            echo '<script src=\''. $LPsUrl.'?t='.VTIME.'\' type=\'text/javascript\'></script>';
      }
}
?>