<?php
	date_default_timezone_set('Africa/Lagos');
	
	error_reporting(E_ALL); ini_set('display_errors', 1);
	
	#####################################################
	#Get values from database
	/*$sql="SELECT * FROM airtel_settings";		
		
	$query = $this->db->query($sql);
				
	if ($query->num_rows() > 0 )
	{
		$row = $query->row_array();
						
		if ($row['billing_password']) $Password=trim($row['billing_password']);
		if ($row['billing_username']) $Username=trim($row['billing_username']);
		if ($row['cpId']) $cpId=trim($row['cpId']);
		if ($row['billing_location']) $airtellocation=trim($row['billing_location']);
		if ($row['wsdl_path']) $wsdl=trim($row['wsdl_path']);
	}*/
	################################################
	
	$Password='Kn1LfHb';
	$Username='laffhub';
	
	try
	{
		$options=array(
			'uri'=>'http://efluxz.com/billingservice',
			'location' => 'http://144.217.72.146/server.php'
		);
		
		$client=new SoapClient(NULL,$options);
		
		#$msisdn='2348083964929';
		$msisdn='2348023351689'; #2348022227157
		$message = "Your attempt to opt out from Laffhub failed. You have no subscription on Laffhub service. Just joking";
		
		$param=array(
			'msisdn' 		=> $msisdn, 
			'message' 		=> $message,
			'Username'  	=> $Username,
			'Password' 		=> $Password
			);
		
		$result=$client->SendMsgToAirtelUser($param);
		
		#echo $result['status'].'<br>';
		print_r($result); echo '<br><br>';
		
		#print_r($client->Sum(7,3)); echo '<br>';
		#print_r($result);
	}catch (SoapFault $e){
		print_r($e);
		// or other error handling
	}
	
?>