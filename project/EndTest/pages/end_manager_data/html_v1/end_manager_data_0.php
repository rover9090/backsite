<?php $aData = json_decode($sData,true);?>
<form action="<?php echo $aUrl['sPage'];?>" method="POST" class="Form MarginBottom20">
      <div>
            <div class="Ipt">
                  <input type="text" name="sAccount" placeholder="<?php echo aMANAGER['SEARCHACCOUNT'];?>" value="<?php echo $sAccount;?>">
            </div>
            <div class="Sel">
                  <select name="nOnline">
                        <?php
                        foreach ($aOnline as $LPnOnline => $LPaOnline)
                        {
                              ?>
                              <option value="<?php echo $LPnOnline;?>" <?php echo $LPaOnline['sSelect'];?> ><?php echo $LPaOnline['sTitle'];?></option>
                              <?php
                        }
                        ?>
                  </select>
            </div>
            <div class="Sel">
                   <select name="nAdmType">
                        <?php
                        foreach ($aAdmType as $LPnAdmType => $LPaAdmType)
                        {
                              ?>
                              <option value="<?php echo $LPnAdmType;?>" <?php echo $LPaAdmType['sSelect'];?> ><?php echo $LPaAdmType['sName0'];?></option>
                              <?php
                        }
                        ?>
                  </select>
            </div>
            <input type="submit" class="BtnAny" value="<?php echo aMANAGER['SEARCH'];?>">
      </div>
</form>
<!-- 新增按鈕 -->
<div class="Block MarginBottom10">
      <a href="<?php echo $aUrl['sIns'];?>" class="BtnAdd"><?php echo INS.$sHeadTitle;?></a>
</div>
<!-- 純顯示資訊 -->
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
                                    <th><?php echo NO;?></th>
                                    <th><?php echo aMANAGER['ACCOUNT'];?></th>
                                    <th><?php echo aMANAGER['NAME'];?></th>
                                    <th><?php echo aMANAGER['ADMTYPE'];?></th>
                                    <th><?php echo '紅利%數';?></th>
                                    <th><?php echo aMANAGER['STATUS'];?></th>
                                    <th><?php echo aMANAGER['CREATETIME'];?></th>
                                    <th><?php echo aMANAGER['UPDATETIME'];?></th>
                                    <th><?php echo aMANAGER['OPERATE'];?></th>
                              </tr>
                        </thead>
                        <tbody>
                              <?php
                              foreach ($aData as $LPnId => $LPaData)
                              {
                                    ?>
                                    <tr>
                                          <td><?php echo $LPnId;?></td>
                                          <td><?php echo $LPaData['sAccount'];?></td>
                                          <td><?php echo $LPaData['sName0'];?></td>
                                          <td><?php echo $LPaData['sAdmType'];?></td>
                                          <td><?php echo $LPaData['sPer'].'%';?></td>
                                          <td class="<?php echo $aOnline[$LPaData['nOnline']]['sClass'];?>"><?php echo $aOnline[$LPaData['nOnline']]['sTitle'];?></td>
                                          <td><?php echo $LPaData['sCreateTime'];?></td>
                                          <td><?php echo $LPaData['sUpdateTime'];?></td>
                                          <td>
                                                <a href="<?php echo $LPaData['sUptUrl'];?>" class="TableBtnBg">
                                                      <i class="fas fa-pen"></i>
                                                </a>
                                                <div class="TableBtnBg red JqStupidOut JqReplaceS" data-showctrl="0" data-replace="<?php echo $LPaData['sDelUrl'];?>">
                                                      <i class="fas fa-times"></i>
                                                </div>
                                          </td>
                                    </tr>
                                    <?php
                              }
                              ?>
                        </tbody>
                  </table>
            </div>
      </div>
</div>
<?php echo $aPageList['sHtml'];?>