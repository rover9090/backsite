<?php
	#require
	require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) .'/System/Connect/UserClass.php');
	$sNowLiff = LINELIFFBET;
	#require結束

	#Js
	$aJs = array();
	$aUrl = array();

	#參數接收區
	$sLineId 		= filter_input_str('sLineId', INPUT_REQUEST, '');
	$sLinePicture 	= filter_input_str('sLinePicture', INPUT_REQUEST, '');
	#參數結束

	#參數宣告區
	$aData = array();
	$aPicture = array();
	// $oUser = new oClientUser();
	#宣告結束

	#程式邏輯區
	if($sLineId != '')
	{
		$sSQL = '	SELECT 	User_.nId,
						User_.sName0,
						User_.sAccount,
						User_.nStatus,
						User_.nIdentity,
						User_.nCreateTime,
						User_.sPicture,
						Money_.nMoney
				FROM 		'.CLIENT_USER_DATA.' User_
				JOIN		'.CLIENT_USER_MONEY.' Money_
				ON		User_.nId = Money_.nUid
				WHERE 	User_.sLineId = :sLineId
				AND		nOnline != 99';
		$Result = $oPdo->prepare($sSQL);
		$Result->bindValue(':sLineId', $sLineId, PDO::PARAM_STR);
		sql_query($Result);
		$aUser = $Result->fetch(PDO::FETCH_ASSOC);
		
		if($aUser !== false && $sLineId !== '')
		{
			$aUser['nUid'] = $aUser['nId'];
			$nUid = $aUser['nId'];
			// 更新頭貼
			if($sLinePicture != $aUser['sPicture'])
			{
				$aSQL_Array = array(
					'sPicture'		=> (string) $sLinePicture,
					'nUpdateTime'	=> (int) 	NOWTIME,
					'sUpdateTime'	=> (string) NOWDATE,
				);

				$sSQL = '	UPDATE '. CLIENT_USER_DATA .' SET ' . sql_build_array('UPDATE', $aSQL_Array ).'
						WHERE	nId = :nUid LIMIT 1';
				$Result = $oPdo->prepare($sSQL);
				$Result->bindValue(':nUid', $nUid, PDO::PARAM_INT);
				sql_build_value($Result, $aSQL_Array);
				sql_query($Result);

			}

			$aLoginData = array(
				'sAccount' => $aUser['sAccount'],
				'sPassword' => oCypher::ReHash($aUser['nCreateTime']),
				'nRemember' => 0,
			);

			$nStatus = $oUser->login($aLoginData);
			echo '<script>location.href = "./"</script>';
		}
		else if($aUser === false && $sLineId !== '')
		{
			$aJumpMsg['0']['sIcon'] = 'error';
			$aJumpMsg['0']['sMsg'] = '請先綁定';
			$aJumpMsg['0']['sShow'] = 0;
			$aJumpMsg['0']['aButton'] = array();
			$aJumpMsg['0']['nClicktoClose'] = 0;
			$aJumpMsg['0']['sShow'] = 1;			
			$aJumpMsg['0']['aButton']['0']['sClass'] = '';
			$aJumpMsg['0']['aButton']['0']['sUrl'] = 'javascript:void(0);';
			// $aJumpMsg['0']['aButton']['0']['sText'] = '確認';
		}
	}
?>
<script src="plugins/js/jquery-3.3.1.min.js"></script>
<script src="https://static.line-scdn.net/liff/edge/2/sdk.js"></script>
<input type="hidden" name="sLineId" 	value="<?php echo $sLineId;?>">
<input type="hidden" name="sLiffId" 	value="<?php echo $sNowLiff;?>">
<script>
$(document).ready(function()
{
	// 取得會員LINEID
	let sLineId = $('input[name=sLineId]').val();
	if (!sLineId) {
		let liffId = $('input[name=sLiffId]').val();
		liff.init({
			liffId: liffId
		}).then(() => {
			if (!liff.isLoggedIn()) {
				liff.login();
			}
			else {
				liff.getProfile().then(profile => {
					sLineId = profile.userId;
					sLinePicture = profile.pictureUrl;
					location.href = `${window.location.origin}${window.location.pathname}?_line_login_0_act0.WXLg&sLineId=${sLineId}&sLinePicture=${sLinePicture}`;
				})
			}
		});
	}
});
</script>