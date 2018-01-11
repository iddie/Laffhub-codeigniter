<?php
date_default_timezone_set('Africa/Lagos');

error_reporting(-1); ini_set('display_errors', 'On');

#http://cms-mtnplayni.imimobile.net/SubscriberInfoAPI/Default.aspx?msisdn=<msisdn>&req=CHECK
$msisdn='2347036520964';

$checkurl='http://cms-mtnplayni.imimobile.net/SubscriberInfoAPI/Default.aspx?msisdn='.$msisdn.'&req=CHECK';

#suburl='http://cms-mtnplayni.imimobile.net/SubscriberInfoAPI/Default.aspx?msisdn='.$msisdn.'&req=SUB&svcid=LF&channel=WAP';

// Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
	CURLOPT_SSL_VERIFYPEER, 0,
	CURLOPT_SSL_VERIFYHOST, 0,
    CURLOPT_URL => $checkurl
));

$response = curl_exec($curl); #Send the request & save response to $resp

curl_close($curl); #Close request to clear up some resources

#$results=array($response);

print_r($response);
?>