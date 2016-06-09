<?php
#Coded By The Nonexistent.
#D3M0N Team.
#You Can Connect From NetCat To The Script.
#run: php -q script.php ip port
#input any for any ip
#now use # in the beginning of your input to run command(client side).
#Example: #exit , #server.shutdown
if (isset($argv[1]) && isset($argv[2]))
{
	$argv_set = true;
	$host = $argv[1];
	if ($host == "any")
	{
		$outhost = "0.0.0.0";
	}
	else
	{
		$outhost = $host;
	}
}
else
{
	$argv_set = false;
}
$config_file = "Em.conf";
if (!file_exists($config_file))
{
	echo "[Emergency Save Server]\n";
	echo "[#]Config File Not Found!\n[+]Started Generating\n";
	$password = readline("[+]Enter Server's Password[MAX:2048B]: ");
	$password = md5($password);
	$name = readline("[+]Enter Server's Name: ");
	$outputfile = readline("[+]Enter Output File's Name: ");
	echo "[+]Done!\n[+]Writing To The Config File\n";
	$config_handle = fopen($config_file, "a");
	fwrite($config_handle, $password . "\n", strlen($password . "\n"));
	fwrite($config_handle, $name . "\n", strlen($name . "\n"));
	fwrite($config_handle, $outputfile . "\n", strlen($outputfile . "\n"));
	fclose($config_handle);
	echo "[+]Restart The Script For Changes To Effect\n";
	exit();
}
else
{
	if ($argv_set)
	{
	    //Getting Values From Config File
		try
		{
			$read_conf_handle = fopen($config_file, "r");
			$password = fgets($read_conf_handle);
			$password = trim($password);
			$name = fgets($read_conf_handle);
			$name = trim($name);
			$outputfile = fgets($read_conf_handle);
			$outputfile = trim($outputfile);
			fclose($read_conf_handle);
		}
		catch (Exception $e)
		{
			echo "[#]Something Has Gone Wrong With The Config File.\n[+]Delete It And Start Again Or Try To Fix It\n";
		}
		echo "[+]Config Loaded Successfully.\n";
		$port = $argv[2];
		$welcome_message = "[.:$name:.]\n[+]Welcome To PHP Emergency Save Service.\n[+]Everything You Input Will Be Hash Saved And The Session Will Leave No Log.\n[+]Stealth Session Started\n[+]Input:\n";
		$isshut = FALSE;
		set_time_limit(0);
		echo "[Emergency Save Server]\n";
		echo "[.:$name:.]\n";
		while(true)
		{
			$lsock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			socket_bind($lsock, $outhost, $port);
			socket_listen($lsock, 3);
			echo "\n";
			echo "[+]Host: " . $host . "\n";
			echo "[+]Port: " . $port . "\n";
			echo "[+]Waiting For Connection.\n";
			$accsock = socket_accept($lsock);
			socket_getpeername($accsock, $caddress, $cport);
			echo "[+]Recived Connection Request From : $caddress  \n";
			$handle = fopen($outputfile, "a");
			fwrite($handle, "[.:$name:.]\n", strlen("[.:$name:.]\n"));
			fwrite($handle, "Connection From : $caddress \n", strlen("Connection From : $caddress \n"));
			socket_write($accsock,"[+]Enter Serevr's Password: " ,strlen("[+]Enter Serevr's Password: "));
			$input_password = $input = socket_read($accsock, 2048);
			$input_password = trim($input_password);
			if ($password == trim(md5($input_password)))
			{
				fwrite($handle, "Inputed Password Was Correct!\n", strlen("Inputed Password Was Correct!\n"));
				echo "[+]Inputed Password Was Correct!\n";
				socket_write($accsock,$welcome_message ,strlen($welcome_message));
				echo "[+]Welcome Message Sent.\n";
				do
				{
					$input = socket_read($accsock, 1024);
					$input = trim($input);
					if (substr($input, 0,1) == "#")
					{
						if ($input == "#exit")
						{
							fwrite($handle, "[+]Exit Command Recived.\n[+]Socket Terminated.\n", strlen("[+]Exit Command Recived.\n[+]Socket Terminated.\n"));
							echo "[+]Exit Command Recived.\n";
						    socket_write($accsock, "[+]All Inputes Saved.\n", strlen("[+]All Iputes Saved.\n"));
						    socket_write($accsock, "[+]Socket Terminated.\n", strlen("[+]Socket Terminated.\n"));
							echo "[+]Terminating Socket.\n";
							socket_close($accsock);
							break;
						}
						elseif ($input == "#server.shutdown")
						{
							echo "\n";
							echo "[+]Server Shutdown Signal Recived.\n[+]Shuting Down The Server.\n[+]All Connections Will Be Closed.\n";
							socket_write($accsock, "[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n", strlen("[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n"));
							fwrite($handle, "[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n", strlen("[+]Server Shutdown Signal Recived.\n[+]All Inputes Saved.\n"));
						    socket_write($accsock, "[+]Socket Terminated.\n", strlen("[+]Socket Terminated.\n"));
							$isshut = TRUE;
							break;
						}
						else
						{
							echo "[#]Command Not Found!\n";
							socket_write($accsock, "[#]Command Not Found!\n", strlen("[#]Command Not Found!\n"));						
						}
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
			else
			{
				fwrite($handle, "Inputed Password Was Wrong!\nUser Disconnected.\n", strlen("Inputed Password Was Wrong!\nUser Disconnected.\n"));
				echo "[+]Inputed Password Was Wrong!\n";
				socket_close($lsock);
				fclose($handle);
			}
		}
	}
	else
	{
		echo "[#]Arguments Error.\n[+]Check Your Input.\n";		
	}
}
#Coded By The Nonexistent.
#D3M0N Team.
?>
