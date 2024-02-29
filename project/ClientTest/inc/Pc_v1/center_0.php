<div class="WindowBox JqWindowBox" data-kind="2">
      <div class="WindowContainer">
            <div class="WindowTop">
                  <table class="WindowTopTable">
                        <tbody>
                              <tr>
                                    <td class="WindowTopTd">
                                          <div class="WindowTit"><?php echo aCENTER['CENTER'];?></div>
                                    </td>
                                    <td class="WindowCancelTd">
                                          <div class="WindowCancel JqClose" data-kindctrl="2">
                                                <i class="fas fa-times"></i>
                                          </div>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
            <div class="WindowContent">
                  <!-- 頭像 -->
                  <div class="centerImgBox">
                        <div class="centerImgBlock JqWindowBtn" data-kindctrl="3">
                              <div class="centerImg">
                                    <img src="images/player.png?t=<?php echo VTIME;?>" alt="">
                              </div>
                              <div class="centerImgEdtBox">
                                    <div class="centerImgEdt">
                                          <i class="fas fa-edit"></i>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <!-- 純顯示，點進編輯後才可輸入 -->
                  <div class="centerBox">
                        <div class="centerInfoBlock">
                              <div class="centerInfoTit"><?php echo aCENTER['NICKNAME'];?></div>
                              <div class="centerInfoTxtBox">
                                    <table class="centerInfoTxtTable">
                                          <tbody>
                                                <tr>
                                                      <td class="centerInfoTxtTd"><?php echo $aUser['sName0'];?></td>
                                                      <td class="centerInfoIconTd">
                                                            <div class="centerInfoIcon JqWindowBtn" data-kindctrl="4">
                                                                  <i class="fas fa-edit"></i>
                                                            </div>
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                              <div class="centerLevelBox">
                                    <span class="centerLevelIcon">
                                          <img src="images/ICON/rank.png?t=<?php echo VTIME;?>" alt="">
                                    </span>
                                    <span class="centerLevelTxt"><?php echo aCENTER['LEVEL'];?></span>
                              </div>
                        </div>
                        <div class="centerInfoBlock">
                              <div class="centerInfoTit"><?php echo aCENTER['RANKING'];?></div>
                              <div class="centerInfoTxtBox">
                                    <table class="centerInfoTxtTable">
                                          <tbody>
                                                <tr>
                                                      <td class="centerInfoTxtTd"><?php echo aCENTER['BACCARAT'];?></td>
                                                      <td class="centerInfoNum">
                                                            <span class="JqWinRate">0</span>
                                                            <span>%</span>
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                        <?php if(false){?>
                        <div class="centerInfoBlock">
                              <div class="centerInfoTxtBox">
                                    <table class="centerInfoTxtTable">
                                          <tbody>
                                                <tr>
                                                      <td class="centerInfoTxtTd">
                                                            <div class="centerInfoTit"><?php echo aCENTER['PASSWORD'];?></div>
                                                      </td>
                                                      <td class="centerInfoIconTd">
                                                            <div class="centerInfoIcon JqWindowBtn" data-kindctrl="5">
                                                                  <i class="fas fa-edit"></i>
                                                            </div>
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                        <?php }?>
                  </div>
            </div>
      </div>
      <div class="WindowBg"></div>
</div>