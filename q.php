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

$message = "You have successfully activated your Daily Laffhub subscription. Watch 3 videos at www.laffhub.com valid for 1day. NO DATA COST. To opt out, text OUT to 2001";

$url='http://localhost/incomingsms/receiver.php?msisdn=2348020566067&message=Laff&shortcode=2001';

#$file = fopen('aaa.txt',"w"); fwrite($file, $url); fclose($file);
#$u='http://localhost:15015/cgi-bin/sendsms?username=laffhub&password=Kn1LfHb&smsc=AIRTEL&to=2348023351689&from=2001&text=Hello+world';
#$res=file_get_contents($u);

#print_r($res);

#exit();


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