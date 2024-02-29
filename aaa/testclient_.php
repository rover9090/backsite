<?php 
// require_once(dirname(dirname(dirname(__FILE__))) .'/System/System.php');
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);
ini_set('error_log', dirname(__FILE__) .'/error_log_socket.txt');

// try {
// 	echo array_pop($aaabc);
//   } catch (Exception $e) {
// 	echo 'Caught exception: ',  $e->getMessage(), "\n";
//   }
  

// $server_ip = '213.139.235.71';
// $port = 8090;
// print_r($_SERVER);
// $server_ip = '168.138.47.39';
$server_ip = 'localhost';
$port = 9090;
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_connect($socket,$server_ip,$port);
$in = "Hello Server!\n";
$aSend = array(
	array(
	'sType' =>'chat',
	'nUid'  =>'3777',
	'sName0'    =>'gino',
	'sAccount'  =>'gino',
	'nTargetUid'=>0,
	'nRoomId'   =>1001,
	'sMsg'  =>'測試文字',
	),
);
$sSend = json_encode($aSend);
$out = '';


if(!socket_write($socket,$sSend,strlen($sSend))) {
	echo "Write failed\n";
	exit;
}
/*  sType: 'chat',
	nUid: aUser['nUid'],
	sName0: aUser['sName0'],
	sAccount: aUser['sAccount'],
	nTargetUid: 0,
	nRoomId: nRoom,
	sMsg: $('.JqChat').val(),

	while(socket_read)無窮迴圈中
	不設定break的話,跳出的條件為socket連線中斷
	在連線尚未中斷之前都會持續卡著
	所以原本以為的卡server可能其實是卡client
 */
$out = socket_read($socket,4096);
echo "Server response success!\n";
echo "Receive message:<br>{$out}";
echo 'end';
socket_close($socket);


  
// echo "Reading response:\n\n";
// $buf = 'This is my buffer.';
// if (false !== ($bytes = socket_recv($socket, $buf, 2048, MSG_WAITALL))) {
//     echo "Read $bytes bytes from socket_recv(). Closing socket...";
// } else {
//     echo "socket_recv() failed; reason: " . socket_strerror(socket_last_error($socket)) . "\n";
// }
// socket_close($socket);
// socket_close($socket);
// exit;
?>