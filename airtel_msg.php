<?php
date_default_timezone_set('Africa/Lagos');

error_reporting(-1);
ini_set('display_errors', 'On');

#http://cms-mtnplayni.imimobile.net/SubscriberInfoAPI/Default.aspx?msisdn=<msisdn>&req=CHECK
#$msisdn='2348022227157';
#08029991106, 08022220099, 08085569008 - NOT Billing

#2348022227157, 2347082368053, 2348024965428, 2348083964929 - Billing But No Credit
$msisdn='2348023351689'; #2348022227157

#$url='https://172.24.4.12:3110';

$Username = 'laffhub';
$Password = 'Kn1LfHb';

$message = "Your LaffHub test is confirmed successfully.";

#$url='http://34.224.189.244:15015/cgi-bin/sendsms?username='.$Username.'&password='.$Password.'&smsc=AIRTEL&to='.$msisdn.'&from=LaffHub&text='.urlencode($message);#33321

#$file = fopen('aaa.txt',"w"); fwrite($file, $url); fclose($file);

#$res=file_get_contents($url);

#print_r($res);

#exit();


$url = 'http://34.224.189.244:15015/cgi-bin/sendsms=';

$data = array('username' => $Username, 'password' => $Password, 'smsc' => 'AIRTEL', 'to' => $msisdn,'from' => 'LaffHub Test', 'text' =>urlencode($message));
// use key 'http' even if you send the request to https://...
$options = array(
  'http' => array(
    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    'method'  => 'POST',
    'content' => http_build_query($data),
  ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

print_r($result);


exit();
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_SSL_VERIFYPEER, 0,
	CURLOPT_SSL_VERIFYHOST, 0,
    CURLOPT_URL => $url
));

$response = curl_exec($curl); #Send the request & save response to $resp

curl_close($curl); #Close request to clear up some resources

#$response=array($response);

print_r($response);

?>