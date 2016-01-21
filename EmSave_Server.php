<?php
#Coded By The Nonexistent.
#D3M0N Team.
#You Can Connect From NetCat To The Script.
#run: php -q script.php
$host = "0.0.0.0";
$port = 1246;
$welcome_message = "[+]Welcome To PHP Emergency Save Service.\n[+]Everything You Input Will Be Hash Saved And The Session Will Leave No Log.\n[+]Stealth Session Started\n[+]Input:\n";
$isshut = FALSE;
set_time_limit(0);
echo "[Emergency Save Server]\n";
while(true)
{
	$lsock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	socket_bind($lsock, $host, $port);
	socket_listen($lsock, 3);
	echo "\n";
	echo "[+]Waiting For Connection.\n";
	$accsock = socket_accept($lsock);
	socket_getpeername($accsock, $caddress, $cport);
	echo "[+]Recived Connection Request From : $caddress  \n";
	socket_write($accsock,$welcome_message ,strlen($welcome_message));
	echo "[+]Welcome Message Sent.\n";
	$handle = fopen("stealth_log.txt", "a");
	fwrite($handle, "Connection From : $caddress \n", strlen("Connection From : $caddress \n"));
	do
	{
		$input = socket_read($accsock, 1024);
		$input = trim($input);
		if ($input == "exit")
		{
			fwrite($handle, "[+]Exit Command Recived.\n[+]Socket Terminated.\n", strlen("[+]Exit Command Recived.\n[+]Socket Terminated.\n"));
			echo "[+]Exit Command Recived.\n";
		    socket_write($accsock, "[+]All Inputes Saved.\n", strlen("[+]All Iputes Saved.\n"));
		    socket_write($accsock, "[+]Socket Terminated.\n", strlen("[+]Socket Terminated.\n"));
			echo "[+]Terminating Socket.\n";
			socket_close($accsock);
			break;
		}
		elseif ($input == "server.shutdown")
		{
			echo "\n";
			echo "[+]Server Shutdown Signal Recived.\n[+]Shuting Down The Server.\n[+]All Connections Will Be Closed.\n";
			socket_write($accsock, "[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n", strlen("[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n"));
		    socket_write($accsock, "[+]Socket Terminated.\n", strlen("[+]Socket Terminated.\n"));
			$isshut = TRUE;
			break;
		}
		else
		{
			$write = "[+]User Input: " . $input . "\n";
			echo $write;
			fwrite($handle, $write, strlen($write));
		} 
	} while(true);
	socket_close($lsock);
	fclose($handle);
	echo "[+]Socket Terminated.\n";
	echo "\n";
	if ($isshut)
	{
		break;
	}
}
#Coded By The Nonexistent.
#D3M0N Team.
?>
