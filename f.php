<?php
error_reporting(E_ALL); ini_set('display_errors', 1); 

#$ip='197.211.56.37';
$ip=$_SERVER['REMOTE_ADDR'];

$url='http://pro.ip-api.com/json/'.$ip.'?key=5ulj4xXAgcXFzcV';


$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $url
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
curl_close($curl);

#print_r($resp);
$result=json_decode($resp);


echo 'INPUT IP='.$ip.
	'<br>IP='.$result->query.
	'<br>ISP='.$result->isp.
	'<br>Group='.$result->org.
	'<br>Country='.$result->country.
	'<br>Country Code='.$result->countryCode.
	'<br>Status='.$result->status.
	'<br>Region Name='.$result->regionName.
	'<br>Latitude='.$result->lat.
	'<br>Longitude='.$result->lon.
	'<br>Time Zone='.$result->timezone;




exit();

$ret=InternetSource($result->org,$result->countryCode,$result->isp);

echo 'Internet Source = '.$ret;


function InternetSource($org,$countryCode,$isp)
{
		 if (!$org and !$countryCode and $isp) return 'WIFI';
		 
		 #GLO      =>  Country Code=NG; $org=globacom; ISP=Globacom Limited; IP=197.211.56.44
		 #ETISALAT =>  Country Code=NG; $org=Emts-nigeria; ISP=EMTS Limited / Etisalat Nigeria; IP=41.190.2.246
		 #AIRTEL   =>  Country Code=NG; $org=Airtel Networks Limited; ISP=Airtel Networks Limited; IP=105.112.41.142
		 #MTN      =>  Country Code=NG; $org=MTN Nigeria; ISP=MTN Nigeria;  IP=197.210.46.18
		 
		 $org=trim(strtolower($org));
		 $countryCode=trim(strtolower($countryCode));
		 $isp=trim(strtolower($isp));
		 
		 if (($org=='airtel networks limited') and ($countryCode=='ng') and ($isp=='airtel networks limited'))#Airtel
		 {
			 $sub=stristr($isp,'airtel');
			 
			 if ($sub !== FALSE) return 'Airtel'; else return 'WIFI';
		 }elseif (($org=='mtn nigeria') and ($countryCode=='ng') and ($isp=='mtn nigeria'))#MTN
		 {
			 $sub=stristr($isp,'mtn');
			 
			 if ($sub !== FALSE) return 'MTN'; else return 'WIFI';
		 }elseif (($org=='emts-nigeria') and ($countryCode=='ng') and ($isp=='emts limited / etisalat nigeria'))#Etisalat
		 {
			 $sub=stristr($isp,'etisalat');
			 
			 if ($sub !== FALSE) return 'Etisalat'; else return 'WIFI';
		 }elseif (($org=='globacom') and ($countryCode=='ng') and ($isp=='globacom limited'))#GLO
		 {
			 return 'WIFI';
		 }else
		 {
			 return 'WIFI';
		 }
	 }
?>