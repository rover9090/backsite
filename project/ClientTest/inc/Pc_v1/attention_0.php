<div class="WindowBox Middle JqWindowBox" data-kind="7">
      <div class="WindowContainer">
            <div class="WindowTop">
                  <table class="WindowTopTable">
                        <tbody>
                              <tr>
                                    <td class="WindowTopTd">
                                          <div class="WindowTit"><?php echo aCENTER['WATCHDEALER'];?></div>
                                    </td>
                                    <td class="WindowCancelTd">
                                          <div class="WindowCancel JqClose" data-kindctrl="7">
                                                <i class="fas fa-times"></i>
                                          </div>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
            <div class="WindowContent">
                  <div class="attentionBox">
                  <?php
                        for($i=1;$i<7;$i++)
                        {
                        ?>    <div class="attentionBlock">
                                    <div class="attentionImg">
                                          <img src="images/Croupier.png?t=<?php echo VTIME;?>" alt="">
                                    </div>
                                    <table class="attentionTable">
                                          <tbody>
                                                <tr>
                                                      <td class="attentionTd">xxxxxx</td>
                                                      <td class="attentionTd"><?php echo aCENTER['ONLINE'];?></td>
                                                      <td class="attentionIconTd">
                                                            <div class="attentionIcon">
                                                                  <i class="fas fa-minus"></i>
                                                            </div>
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                        <?php
                        }
                  ?>
                  </div>
            </div>
      </div>
      <div class="WindowBg"></div>
</div>