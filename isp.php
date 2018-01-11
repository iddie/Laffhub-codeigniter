<?php
	
#$var = file_get_contents('https://www.whoismyisp.org/ip/197.211.57.2');
#echo $var;
	
	$ip=$_SERVER['REMOTE_ADDR']; 

	$curl = curl_init();
			
	curl_setopt_array($curl, array(
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_URL => 'http://pro.ip-api.com/json/'.$ip.'?key=5ulj4xXAgcXFzcV'
	));
	// Send the request & save response to $resp
	$resp = curl_exec($curl);
	curl_close($curl);
	
	
	$result=json_decode($resp);
	
	print_r($result);
	#$result=json_decode($resp);
?>
