<?php
	set_time_limit(0);
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	error_reporting(E_ALL);
	/**
	 * 現在的問題是 不知道何時會被無限占用
	 */
	#ignore_user_abort(true);
	#ini_set('error_log', dirname(dirname(dirname(__FILE__))).'/error_log.txt');
	# require
	ini_set('error_log', dirname(__FILE__) .'/error_log_socket.txt');
	// require_once(dirname(dirname(__FILE__)) .'/System/System.php');
	#require結束
	#https://skjhmis.blogspot.com/2019/02/php-socket.html
	#error_log('SocketServer.php start');
	#參數宣告區
	// 自動儲存時間
	define('NOWTIME', time());
	define('NOWDATE', date('Y-m-d H:i:s'));
	define('SAVETIME', 10);
	// 自動儲存訊息數
	define('SAVECOUNT', 5);
	// exit;
	$nCounter = microtime(true);
	$nTime = NOWTIME;
	$sNull = NULL;
	$nRoom = 0;
	$nMsgCount = 0; #訊息數量總計
	$aRoom = array();
	$aClientData = array();
	$aSaveMsg = array(
		'chat'=> array(),
		'job'=> array(),
	); #尚未儲存訊息

	define('LOCALPORT','138.2.60.162');
	$aServer = array(
		// 'sIP'		=> '192.168.15.97',#192.168.15.97,213.139.235.71
		// 'sIP'		=> '',
		'sIP'		=> 'localhost',
		'sPort'	=> '9090',
		'nMax'	=> 0, #最大連線數
		'nPeople'	=> array(), #在線人數
	);
	$nStartTime = microtime(true);
	#宣告結束

	#程式邏輯區
	#設定網路模式、socket類型和通訊協定
	$oSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_set_option($oSocket, SOL_SOCKET, SO_REUSEADDR, 1);
	socket_bind($oSocket, 0, $aServer['sPort']);
	// $fp = @fsockopen($aServer['sIP'], $aServer['sPort'], $errno, $errstr, 0.1);
	// if (!$fp) {
	// 	socket_bind($oSocket, 0, $aServer['sPort']);
	// } else {
	// 	fclose($fp);
	// 	exit;
	// }


	#監聽入口
	socket_listen($oSocket);

	#連接的所有清單
	$aClient = array($oSocket);

	echo '<pre>';
	#啟動無限迴圈
	$nIdRunner = 0;
	while (true)
	{
		$nNowTime = microtime(true);
		if($nNowTime - $nStartTime > (7200))# 排程每60秒就刷新這支，這支迴圈59秒就會斷線
		{
			echo '7200close';
			socket_close($oSocket);
			exit;
		}

		$aChange = $aClient;
		socket_select($aChange, $sNull, $sNull, 0, 10);

		#新的連接進場
		if (in_array($oSocket, $aChange))
		{
			#啟用新的連接
			$oNewSocket = socket_accept($oSocket);			
			#進行交握動作
			while($sOriginReceive = socket_read($oNewSocket, 4096))# 連結用socket
			{
				$nIdRunner++;
				$sHead = deWscode($sOriginReceive);
				if($aServerMsg = json_decode($sOriginReceive,true))# 如果是從server傳來的訊息就不交握直接傳送
				{
					$aJsonChat = array();
					// trigger_error(print_r($aServerMsg,true));
					foreach($aServerMsg as $LPaServerMsg)
					{
						$aSendMessage = $LPaServerMsg;						
						$sSendMessage = enWscode(json_encode($aSendMessage));
						$sSendMessage = $nIdRunner.'get success.<br>';
						// sendMsg($sSendMessage,1001,0,'chat');# 傳給browser的
						socket_write($oNewSocket,$sSendMessage,strlen($sSendMessage));#傳給server的
						echo chr(10);
					}
					$sSk = array_search($oSocket, $aChange);
					unset($aChange[$sSk]);
				}
				else
				{
					$aClient[] = $oNewSocket;
					hand_shake($sHead, $oNewSocket, $aServer['sIP'], $aServer['sPort']);

					$sSk = array_search($oSocket, $aChange);
					unset($aChange[$sSk]);
					break;
				}				
			}
		}
		#詢問每個client socket連接
		foreach ($aChange as $sK => $LPsocket)
		{
			#當前連接如有資訊傳送，處理對應動作
			while(socket_recv($LPsocket, $buffer, 4096, 0) >= 1)# 監聽用socket
			{
				#解密動作
				$sDTxt = deWscode($buffer);
				
				$aMessage0 = json_decode($sDTxt, true);
				$aMessage0['sGroupType'] = 'chat';
				
				if(empty($aMessage0['sType']) && empty($aMessage0['aData']))
				{
					continue;
				}
				#echo date('Y-m-d H:i:s ').'#74 Recive data => '.$sDTxt."\n";
				if (!$aMessage0)
				{
					break 2;
				}
				if(!isset($aMessage0['nArray']))
				{
					$aMessage0['aData'] = array($aMessage0);
				}
				foreach($aMessage0['aData'] as $aMessage)
				{
					switch ($aMessage['sType'])
					{
						case 'join':
							$aClientData[$sK] = array(
								'oSocket'	=> $LPsocket,
								'nUid'	=> $aMessage['nUid'],
								'sName0'	=> $aMessage['sName0'],
								'sAccount'	=> $aMessage['sAccount'],
								'nRoomId'	=> $aMessage['nRoomId'],
								'sGroupType'=> $aMessage0['sGroupType'],
								'sCreateTime'=> date('H:i:s'),
							);
							$aRoom[$aMessage0['sGroupType'].$aMessage['nRoomId']][$aMessage['nUid']] = $LPsocket;

							$aSendMessage = array(
								'nRoomId'	=> $aMessage['nRoomId'],
								'sType'	=> 'join',
								'nUid' 	=> $aMessage['nUid'],
								'sName0' 	=> $aMessage['sName0'],
								'sMsg'	=> fnSendChat($aMessage),
								'sCreateTime'=> date('H:i:s'),
							);
							$sSendMessage = enWscode(json_encode($aSendMessage));
							sendMsg($sSendMessage,$aMessage['nRoomId'],0,$aMessage0['sGroupType']);
							break;
						case 'chat':
							$aSendMessage = array(
								'nRoomId'	=> $aMessage['nRoomId'],
								'sType'	=> 'chat',
								'nUid' 	=> $aMessage['nUid'],
								'sPicture' 	=> $aMessage['sPicture'],
								'sName0'	=> $aClientData[$sK]['sName0'],
								'sUserName0'=> $aMessage['sName0'],
								'sMsg'	=> $aMessage['sMsg'],
								'sCreateTime'=> date('H:i:s'),
								'sHtml'	=> '',
								'sMsg'	=> $aMessage['sMsg'],
							);
							$sSendMessage = enWscode(json_encode($aSendMessage));
							sendMsg($sSendMessage,$aMessage['nRoomId'],$aMessage['nTargetUid'],$aMessage0['sGroupType']);

							// save message
							$aMessage['sCreateTime'] = $aSendMessage['sCreateTime'];
							$nMsgCount ++;
							$aSaveMsg[$aMessage0['sGroupType']][] = $aMessage;
							break;
						case 'donate':
							$aSendMessage = array(
								'nRoomId'	=> $aMessage['nRoomId'],
								'sType'	=> 'chat',
								'nUid' 	=> $aMessage['nUid'],
								'sName0'	=> 'system',
								'sUserName0'=> $aMessage['sName0'],
								'sMsg'	=> $aMessage['sMsg'],
								'sCreateTime'=> date('H:i:s'),
								'sHtml'	=> '',
								'sMsg'	=> $aMessage['sMsg'],
							);
							$sSendMessage = enWscode(json_encode($aSendMessage));
							sendMsg($sSendMessage,$aMessage['nRoomId'],$aMessage['nTargetUid'],$aMessage0['sGroupType']);
							$aSaveMsg[$aMessage0['sGroupType']][] = $aMessage;
							break;
						case 'bank':
							if($aMessage['nType0'] == 1)# 集資
							{
								$aSendMessage = array(
									'nRoomId'	=> $aMessage['nRoomId'],
									'sType'	=> 'chat',
									'nUid' 	=> $aMessage['nUid'],
									'sName0'	=> 'system',
									'sUserName0'=> $aMessage['sName0'],
									'sMsg'	=> $aMessage['sMsg'],
									'sCreateTime'=> date('H:i:s'),
									'sHtml'	=> '',
									'sMsg'	=> $aMessage['sMsg'],
								);
								$sSendMessage = enWscode(json_encode($aSendMessage));
								sendMsg($sSendMessage,$aMessage['nRoomId'],$aMessage['nTargetUid'],$aMessage0['sGroupType']);
								$aSaveMsg[$aMessage0['sGroupType']][] = $aMessage;
							}
							else #排莊
							{
								$aSendMessage = array(
									'nRoomId'	=> $aMessage['nRoomId'],
									'sType'	=> 'chat',
									'nUid' 	=> $aMessage['nUid'],
									'sName0'	=> 'system',
									'sUserName0'=> $aMessage['sName0'],
									'sMsg'	=> $aMessage['sMsg'],
									'sCreateTime'=> date('H:i:s'),
									'sHtml'	=> '',
									'sMsg'	=> $aMessage['sMsg'],
								);
								$sSendMessage = enWscode(json_encode($aSendMessage));
								sendMsg($sSendMessage,$aMessage['nRoomId'],$aMessage['nTargetUid'],$aMessage0['sGroupType']);
								$aSaveMsg[$aMessage0['sGroupType']][] = $aMessage;
							}
							break;
						case 'sticker':
							$aSendMessage = array(
								'nRoomId'	=> $aMessage['nRoomId'],
								'sType'	=> 'sticker',
								'nUid' 	=> $aMessage['nUid'],
								'sName0'	=> 'system',
								'sUserName0'=> $aMessage['sName0'],
								'nId'		=> $aMessage['nStickId'],
								'sCreateTime'=> date('H:i:s'),
								'sHtml'	=> '',
							);
							$sSendMessage = enWscode(json_encode($aSendMessage));
							sendMsg($sSendMessage,$aMessage['nRoomId'],$aMessage['nTargetUid'],$aMessage0['sGroupType']);
							$aSaveMsg[$aMessage0['sGroupType']][] = $aMessage;
							break;
						case 'bet':
							
							break;
					}
				}
				break 2; //exist this loop
			}

				// print_r($LPsocket);exit;
			#檢查當前連接是否離線
			$buffer = socket_read($LPsocket, 4096, PHP_NORMAL_READ);
			if ($buffer === false && isset($aClientData[$sK]))
			{
				echo 'fail';
				unset($aRoom[$aClientData[$sK]['sGroupType'].
				$aClientData[$sK]['nRoomId']]
				[$aClientData[$sK]['nUid']]);
				unset($aClientData[$sK]);
				unset($aClient[$sK]);
				break;
			}
		}

		$nLPTime = time() - $nTime;
		if($nLPTime >= SAVETIME || $nMsgCount >= SAVECOUNT)
		{
			# 訊息寫入DB
			// $ch = curl_init('https://adm.abp77.com/Bot/SaveMsg.php');
			// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($aSaveMsg));
			// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			// curl_setopt($ch,CURLOPT_TIMEOUT,1);
			// $result = curl_exec($ch);
			// curl_close($ch);
			// $nTime = time();
			// $nMsgCount = 0;
			// $aSaveMsg = array(
			// 	'chat' => array(),
			// 	'job' => array(),
			// );
			
		}
	}
	//關閉socket
	socket_close($oSocket);

	#發送訊息 (message, groupid , target uid , group type)
	function sendMsg($sMessage,$nRoomId = 0,$nTargetUid = 0,$sGroupType='')
	{
		global $aClient;
		global $aRoom;
		/**
		 * $nGid => 房間ID 如末指定即為全體廣播
		 * 有指定的話就是對該房間的所有人進行區域性廣播
		 */
		if($nRoomId == 0)
		{
			foreach($aClient as $LPoSocket)
			{
				@socket_write($LPoSocket['oSocket'],$sMessage,strlen($sMessage));
			}
		}
		else
		{
			if (!isset($aRoom[$sGroupType.$nRoomId]))
			{
				return false; // 房間不存在
			}

			if ($nTargetUid != 0) // 指定發送對象
			{
				@socket_write($aRoom[$sGroupType.$nRoomId][$nTargetUid],$sMessage,strlen($sMessage));
			}
			else
			{
				// 群組傳送
				foreach($aRoom[$sGroupType.$nRoomId] as $v)
				{
					@socket_write($v,$sMessage,strlen($sMessage));
				}
			}

		}
		return true;
	}

	//解碼用
	function deWscode($text)
	{
		$length = ord($text[1]) & 127;

		if($length == 126)
		{
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		}
		elseif($length == 127)
		{
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		}
		else
		{
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";
		for ($i = 0; $i < strlen($data); ++$i)
		{
			$text .= $data[$i] ^ $masks[$i%4];
		}
		return $text;
	}

	//轉碼處理
	function enWscode($text)
	{
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);

		if($length <= 125)
		{
			$sHead = pack('CC', $b1, $length);
		}
		elseif($length > 125 && $length < 65536)
		{
			$sHead = pack('CCn', $b1, 126, $length);
		}
		elseif($length >= 65536)
		{
			$sHead = pack('CCNN', $b1, 127, $length);
		}

		return $sHead.$text;
	}

	#交握方式
	function hand_shake($sHead,$client_conn, $host, $port)
	{
		$headers = array();
		$aLink = preg_split("/\r\n/", $sHead);

		foreach($aLink as $v)
		{
			$v = chop($v);
			if(preg_match('/\A(\S+): (.*)\z/', $v, $matches))
			{
				$headers[$matches[1]] = $matches[2];
			}
		}

		$secKey = isset($headers['Sec-WebSocket-Key']) ? $headers['Sec-WebSocket-Key'] : '';
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));# SOCKET的魔術常量
		$upgrade =
		"HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		'WebSocket-Origin: '.LOCALPORT.''."\r\n" .
		'WebSocket-Location: ws://'.LOCALPORT.':9090'."\r\n".
		'Sec-WebSocket-Accept:'. $secAccept ."\r\n\r\n";
		echo '<br>【upgrade】'.$upgrade;
		// exit;
		socket_write($client_conn,$upgrade,strlen($upgrade));
	}
function fnSendChat($aMessage)
{
	if(empty($aMessage['sMsg'])) return '';
	$sSendMsg = '<div class="betmsg font-xxs msgMember">
				<div class="coverbox">
					<div class="td imgbox float-left"><img class="img-circle" src="'.$aMessage['sHeadImg'].'"></div>
					<div class="td float-left ifmeright msgMemberInf">
						<div class="dtbox">
							<div class="dt-nickname">'.$aMessage['sName0'].'</div>
						</div>
						<div class="betmsgBlock arrow">
							<div class="betinfobox">
								<div class="betinfo">
									<div>'.
									$aMessage['sMsg'].'</div>
								</div>
							</div>
						</div>
						<div class="dt-time">
							<div class="dt-timeInner">'.NOWCLOCK.'</div>
						</div>
					</div>
				</div>
			</div>';
}
?>