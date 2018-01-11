<?php
set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

include('common.php');

UpdateExpiredSubscriptions($db);

UpdateActiveSubscriptions($db);

$file = fopen('UpdateStatus_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",Updated subscription status of all customers.".$dt.PHP_EOL); fclose($file);

?>