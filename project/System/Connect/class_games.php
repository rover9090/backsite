<?php
class GameSet_Class
{
	static function fnCallSetting($nGame=0)
	{
		global $aSystem,$oPdo;
		$aGameSetting = array();
		if($nGame > 0)
		{
			$sSQL = '	SELECT	nId,
							nGame,
							sName0,
							nOnline,
							sOpenTime,
							sCloseTime,
							sDealer,
							nSec,
							nSec1,
							nSec2,
							nSec3,
							nMaintenance,
							nShuffling
					FROM	'. END_GAMES_SETTING .'
					WHERE	nOnline = 1
					AND	sLang = :sLang
					AND	nGame = :nGame
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aGameSetting[$aRows['nGame']] = $aRows;
				$aGameSetting[$aRows['nGame']]['nWaterLeast'] = $aSystem['aParam']['nWaterLeast'];
				$aGameSetting[$aRows['nGame']]['nCompanyLeast'] = $aSystem['aParam']['nCompanyLeast'];
				$aGameSetting[$aRows['nGame']]['nBankerMin'] = $aSystem['aParam']['nBankerMin'];
				$aGameSetting[$aRows['nGame']]['nBankerMax'] = $aSystem['aParam']['nBankerMax'];
				$aGameSetting[$aRows['nGame']]['nLimitBet'] = $aSystem['aParam']['nLimitBet'];
				$aGameSetting[$aRows['nGame']]['nOtherCount'] = $aSystem['aParam']['nOtherCount'];
				$aGameSetting[$aRows['nGame']]['nOtherMin'] = $aSystem['aParam']['nOtherMin'];
				$aGameSetting[$aRows['nGame']]['nOtherMax'] = $aSystem['aParam']['nOtherMax'];
				$aGameSetting[$aRows['nGame']]['nMinBet'] = $aSystem['aParam']['nMinBet'];
				$aGameSetting[$aRows['nGame']]['nMaxPerBet'] = $aSystem['aParam']['nMaxPerBet'];
				$aGameSetting[$aRows['nGame']]['nBankerLineCount'] = $aSystem['aParam']['nBankerLineCount'];
				$aGameSetting[$aRows['nGame']]['nBankerFee'] = $aSystem['aParam']['nBankerFee'];
				$aGameSetting[$aRows['nGame']]['nCompanyLeast'] = $aSystem['aParam']['nCompanyLeast'];
				$aGameSetting[$aRows['nGame']]['nWaterMax'] = $aSystem['aParam']['nWaterMax'];
				$aGameSetting[$aRows['nGame']]['nBankerPlusFee'] = $aSystem['aParam']['nBankerPlusFee'];
				$aGameSetting[$aRows['nGame']]['nBankerPlusMin'] = $aSystem['aParam']['nBankerPlusMin'];
				$aGameSetting[$aRows['nGame']]['nReturnJackpot'] = $aSystem['aParam']['nReturnJackpot'];
			}
		}
		else
		{
			$sSQL = '	SELECT	nId,
							nGame,
							sName0,
							nOnline,
							sOpenTime,
							sCloseTime,
							sDealer,
							nSec,
							nSec1,
							nSec2,
							nSec3,
							nMaintenance,
							nShuffling
					FROM	'. END_GAMES_SETTING .'
					WHERE	nOnline = 1
					AND	sLang = :sLang
					ORDER	BY nId ASC';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':sLang', $aSystem['sLang'], PDO::PARAM_STR);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aGameSetting[$aRows['nGame']] = $aRows;
				$aGameSetting[$aRows['nGame']]['nWaterLeast'] = $aSystem['aParam']['nWaterLeast'];
				$aGameSetting[$aRows['nGame']]['nCompanyLeast'] = $aSystem['aParam']['nCompanyLeast'];
				$aGameSetting[$aRows['nGame']]['nBankerMin'] = $aSystem['aParam']['nBankerMin'];
				$aGameSetting[$aRows['nGame']]['nBankerMax'] = $aSystem['aParam']['nBankerMax'];
				$aGameSetting[$aRows['nGame']]['nLimitBet'] = $aSystem['aParam']['nLimitBet'];
				$aGameSetting[$aRows['nGame']]['nOtherCount'] = $aSystem['aParam']['nOtherCount'];
				$aGameSetting[$aRows['nGame']]['nOtherMin'] = $aSystem['aParam']['nOtherMin'];
				$aGameSetting[$aRows['nGame']]['nOtherMax'] = $aSystem['aParam']['nOtherMax'];
				$aGameSetting[$aRows['nGame']]['nMinBet'] = $aSystem['aParam']['nMinBet'];
				$aGameSetting[$aRows['nGame']]['nMaxPerBet'] = $aSystem['aParam']['nMaxPerBet'];
				$aGameSetting[$aRows['nGame']]['nBankerLineCount'] = $aSystem['aParam']['nBankerLineCount'];
				$aGameSetting[$aRows['nGame']]['nBankerFee'] = $aSystem['aParam']['nBankerFee'];
				$aGameSetting[$aRows['nGame']]['nCompanyLeast'] = $aSystem['aParam']['nCompanyLeast'];
				$aGameSetting[$aRows['nGame']]['nWaterMax'] = $aSystem['aParam']['nWaterMax'];
				$aGameSetting[$aRows['nGame']]['nBankerPlusFee'] = $aSystem['aParam']['nBankerPlusFee'];
				$aGameSetting[$aRows['nGame']]['nBankerPlusMin'] = $aSystem['aParam']['nBankerPlusMin'];
				$aGameSetting[$aRows['nGame']]['nReturnJackpot'] = $aSystem['aParam']['nReturnJackpot'];
			}
		}

		return $aGameSetting;
	}

	static function fnCallJackpotSet($nGame=0)
	{
		global $aSystem,$oPdo;
		$aReturn = array();
		if($nGame > 0)
		{
			$sSQL = '	SELECT	nGame,nMoney0
					FROM	'. CLIENT_JACKPOT_SET .'
					WHERE	nGame = :nGame
					LIMIT 1';
			$Result = $oPdo->prepare($sSQL);
			$Result->bindValue(':nGame', $nGame, PDO::PARAM_INT);
			sql_query($Result);
			$aRows = $Result->fetch(PDO::FETCH_ASSOC);
			$aReturn[$nGame] = $aRows;
		}
		else
		{
			$sSQL = '	SELECT	nGame,nMoney0
					FROM	'. CLIENT_JACKPOT_SET .'
					WHERE	1';
			$Result = $oPdo->prepare($sSQL);
			sql_query($Result);
			while($aRows = $Result->fetch(PDO::FETCH_ASSOC))
			{
				$aReturn[$aRows['nGame']] = $aRows;
			}
		}
		return $aReturn;
	}

}
?> 