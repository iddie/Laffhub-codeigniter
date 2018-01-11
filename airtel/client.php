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
	
	$Password='e37X0+9$';
	$Username='2336775_EFLUXZ_NG';
	$cpId='2336775_EFLUXZ_NG';
	$airtellocation='https://172.24.15.10:8443/ChargingServiceFlowWeb/sca/ChargingExport1';
	$wsdl='http://laffhub.com/billing/airtel/ChargingHttpService_ChargingHttp_Service.wsdl';
	
	try
	{
		$options=array(
			'uri'=>'http://efluxz.com/billingservice',
			'location' => 'http://144.217.72.146/server.php'
		);
		
		$client=new SoapClient(NULL,$options);
		
		#$msisdn='2348083964929';
		$msisdn='2348023351689'; #2348022227157
		
		$amount='20';
		$subscriptiondays='1';
		$eventType='Subscription Purchase';
		
		$param=array(
			'username'			=> $Username,
			'password'			=> $Password,
			'location'			=> $airtellocation,
			'wsdl'				=> $wsdl,
			'userId' 			=> $msisdn, 
			'amount' 			=> $amount,
			'cpId'  			=> $cpId,
			'eventType' 		=> $eventType,
			'subscriptiondays'	=> $subscriptiondays
			);
		
		$result=$client->BillAirtelUser($param);
		
		#echo $result['status'].'<br>';
		print_r($result['description']); echo '<br><br>';
		
		#print_r($client->Sum(7,3)); echo '<br>';
		#print_r($result);
	}catch (SoapFault $e){
		print_r($e);
		// or other error handling
	}
	
?>