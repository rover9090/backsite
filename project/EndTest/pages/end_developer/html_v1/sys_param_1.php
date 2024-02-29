<?php $aData = json_decode($sData,true);?>
<!-- 純顯示資訊 -->
<form method="POST" action="<?php echo $aUrl['sAct'];?>" data-form="0">
      <input type="hidden" name="sJWT" value="<?php echo $sJWTAct;?>">
      <div class="MarginBottom20">
            <div class="BtnAny JqStupidOut" data-showctrl="0"><?php echo CSUBMIT;?></div>
      </div>
      <div class="Information">
            <table class="InformationTit">
                  <tbody>
                        <tr>
                              <td class="InformationTitCell" style="width:calc(100%/1);">
                                    <div class="InformationName"><?php echo $sHeadTitle; ?></div>
                              </td>
                        </tr>
                  </tbody>
            </table>
            <div class="InformationScroll">
                  <div class="InformationTableBox">
                        <table>
                              <thead>
                                    <tr>
                                          <th><?php echo PARAMNAME;?></th>
                                          <th><?php echo PARAMS;?></th>
                                          <th><?php echo LASTUPDATETIME;?></th>
                                    </tr>
                              </thead>
                              <tbody>
                                    <?php
                                    foreach ($aData as $LPsName => $LPaData)
                                    {
                                          ?>
                                          <tr>
                                           <?php
                                           if (strpos($LPsName, 'TITLE') !== false)
                                           {
                                                echo '<td colspan="3">'.$LPaData['sName1'].'</td>';
                                           }
                                           else
                                           {
                                                 ?>
                                                 <td><?php echo $LPaData['sName1'];?></td>
                                                 <td>
                                                       <div class="Ipt">
                                                             <input type="text" name="aParam[<?php echo $LPaData['sName0'];?>]" value="<?php echo $LPaData['sParam'];?>">
                                                       </div>
                                                 </td>
                                                 <td><?php echo $LPaData['sUpdateTime'];?></td>
                                                 <?php
                                           }
                                           ?>
                                          </tr>
                                          <?php
                                    }
                                    ?>
                              </tbody>
                        </table>
                  </div>
            </div>
      </div>
</form>