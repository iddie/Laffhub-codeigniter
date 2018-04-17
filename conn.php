<?php

$db = new mysqli('127.0.0.1', 'laffhub_laffuser', 'vUzm6Nh^^y*v', 'laffhub_laffhubdb');

if($db->connect_errno > 0)
{
	#die('Unable to connect to database [' . $db->connect_error . ']');
	$m='Unable to connect to database [' . $db->connect_error . ']';
	
	$file = fopen('laffhub_dberror.txt',"w");fwrite($file,date('d M Y @ H:i:s')." => ".$m.PHP_EOL);	fclose($file);
}


$notificationdb = new mysqli("10.0.0.120", 'laffhub', 'l@ffhub5tr3@m1ng', 'airtelnotifications');

if($notificationdb->connect_errno > 0)
{
	#die('Unable to connect to database [' . $db->connect_error . ']');
	$m='Unable to connect to database [' . $notificationdb->connect_error . ']';
	$file = fopen('notification_dberror.txt',"a");fwrite($file,date('d M Y @ H:i:s')." => ".$m.PHP_EOL);	fclose($file);
}

?>