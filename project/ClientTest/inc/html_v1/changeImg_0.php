<div class="WindowBox JqWindowBox" data-kind="3">
      <div class="WindowContainer">
            <div class="WindowTop">
                  <table class="WindowTopTable">
                        <tbody>
                              <tr>
                                    <td class="WindowTopTd">
                                          <div class="WindowTit"><?php echo aCENTER['CHGIMG'];?></div>
                                    </td>
                                    <td class="WindowCancelTd">
                                          <div class="WindowCancel JqClose" data-kindctrl="3">
                                                <i class="fas fa-times"></i>
                                          </div>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
            <div class="WindowContent">
                  <form action="">
                        <div class="changeImgBox">
                              <table class="changeImgTable">
                                    <tbody>
                                    <?php
                                    $nCount = 1;
                                    $nTdAmount = 5; #一列幾欄
                                    $nTotalAmount = 10; #資料總數
                                    for($nI=1;$nI<=$nTotalAmount;$nI++)
                                    {
                                          if($nCount%$nTdAmount==1)
                                          {
                                                echo '<tr class="changeImgTr">';
                                          }
                                          ?>
                                                <td class="changeImgTd" style="width:calc(100%/<?php $nTdAmount;?>);">
                                                      <!-- 目前或選中之頭像class changeImgBg + active -->
                                                      <div class="changeImgBg JqCngBtn">
                                                            <div class="changeImgBlock">
                                                                  <img src="images/player.png?t=<?php echo VTIME;?>" alt="">
                                                            </div>
                                                      </div>
                                                </td>
                                                <?php
                                                      if($nCount%$nTdAmount==0)
                                                      {
                                                            echo '</tr>';
                                                      }
                                                      $nCount ++;
                                                }
                                                if($nTotalAmount%$nTdAmount!=0)
                                                {
                                                      for($nAdd=1;$nAdd<=($nTdAmount-($nTotalAmount%$nTdAmount));$nAdd++)
                                                      {
                                                            echo '<td class="changeImgTd" style="width:calc(100%/'.$nTdAmount.');"></td>';
                                                      }
                                                      echo '</tr>';
                                                }
                                                ?>
                                    </tbody>
                              </table>
                        </div>
                        <div class="submitBtnBox">
                              <table class="submitBtnTable">
                                    <tbody>
                                          <tr>
                                                <td class="submitBtnTd">
                                                      <div class="submitBtn">
                                                            <input type="submit" class="submitBtnTxt" value="<?php echo aCENTER['CONFIRMFIX'];?>">
                                                      </div>
                                                </td>
                                          </tr>
                                    </tbody>
                              </table>
                        </div>
                  </form>
            </div>
      </div>
      <div class="WindowBg"></div>
</div>