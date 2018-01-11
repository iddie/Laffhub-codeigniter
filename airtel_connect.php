<?php
date_default_timezone_set('Africa/Lagos');

error_reporting(-1);
ini_set('display_errors', 'On');
#ini_set('max_execution_time',180);
	
$Username_Charge='2336775_EFLUXZ_NG';
$Password_Charge='e37X0+9$';

#172.24.4.12 port 3110 is for messaging gateway
#172.24.15.10 port 8443 is form charging gateway#

#$msisdn='2348022227157';
#08029991106, 08022220099, 08085569008 - NOT Billing

#2348022227157, 2347082368053, 2348024965428 - Billing But No Credit
#$msisdn='2348083964929'; #2348022227157
$msisdn='2348085558118'; #2348022227157

$cpTid=date('YmdHis').'_'.$msisdn;

$amount='20';#20,100,200,500
	
$wsdl='airtel/ChargingHttpService_ChargingHttp_Service.wsdl';
//$wsdl='http://www.laffhub.com/billing/airtel/ChargingHttpService_ChargingHttp_Service.wsdl';

try
{
	#$location='https://196.46.244.21:8443/ChargingServiceFlowWeb/sca/ChargingExport1';
	$location='https://172.24.15.10:8443/ChargingServiceFlowWeb/sca/ChargingExport1';

	$subscriptiondays='1';
	$eventType='Subscription Purchase';

	$client = new SoapClient($wsdl, array(
			"uri"=>'http://schemas.xmlsoap.org/soap/envelope/',
			"trace"      => 1,
			'login'      => $Username_Charge,
			'password'   => $Password_Charge,
			"location" => $location,
			"stream_context"=> stream_context_create(array('ssl'=> array('verify_peer'=>false,'verify_peer_name'=>false))),
			"exceptions" => 0
			));
		
	//body
	$soapBody = array
	(
		'inputMsg' => array
		(
			'operation' => 'debit', 
			'userId' => $msisdn,
			'contentId' => '111',
			'itemName' => 'LaffHub',
			'contentDescription' => 'LaffHub - Subscription Service',
			'circleId' => '',
			'lineOfBusiness' =>'',
			'customerSegment' => '',
			'contentMediaType' => 'Laffhub', 
			'serviceId' => '1',
			'parentId' => '',
			'actualPrice' => $amount,#20, 100, 200, 500
			'basePrice' => $amount,
			'discountApplied' => '0', 
			'paymentMethod' => '',
			'revenuePercent' => '',
			'netShare' => '',
			'cpId' => '2336775_EFLUXZ_NG',
			'customerClass' => '',
			'eventType' => $eventType, #RE Subscription or Subscription Purchase
			'localTimeStamp' => '',
			'transactionId' => '',
			'subscriptionName' => 'LaffHub',
			'parentType' => '',
			'deliveryChannel' => 'WAP', 
			'subscriptionTypeCode' => 'abcd',
			'contentSize' => '',
			'subscriptionExternalId' => '2',
			'currency' => 'NGN',
			'copyrightId' => 'xxx',
			'cpTransactionId' => $cpTid,
			'copyrightDescription' => 'copyright',
			'sMSkeyword' => 'COMEDY', 
			'srcCode' => 'abcd', 
			'contentUrl' => 'www.laffhub.com',
			'subscriptiondays' => $subscriptiondays
		)
	);
	
	$response = $client->__soapCall("charge", array($soapBody));
	
	$response=json_decode(json_encode($response), True);
		
	$errorcode=''; $errormsg=''; $status=''; $transid=''; $cptransid=''; $message='';
	
	print_r($response); echo '<br><br>';
	
	$result=$response['outputMsg'];
	$status=$result['status'];
	$transid=$result['transactionId'];
	$cptransid=$result['cpTransactionId'];
	
	
	#Array ( [status] => Failure [transactionId] => twss_54e21d846749779 [error] => Array ( [errorCode] => OL404 [errorMessage] => Insufficient Balance.#~#95.45 ) [cpTransactionId] => 20170617155509_2348083964929 )
	
	if (trim(strtolower($status))=='success')
	{
		$errorcode=''; $errormsg=''; 
		
		#Send Confirmation Message
		if (trim($amount)=='20') #20
		{
			$message = "You've successfully activated your DAILY Laffhub subscription. Watch 3 videos at www.laffhub.com valid for 1 day. NO DATA COST. To opt out, text OUT to 2001.";
		}elseif (trim($amount)=='100') #100
		{
			$message = "You've successfully activated your WEEKLY Laffhub subscription. Watch 15 videos at www.laffhub.com valid for 7 days. NO DATA COST. To opt out,text OUT to 2001.";
		}elseif (trim($amount)=='200') #200
		{
			$message = "You've successfully activated your MONTHLY Laffhub subscription. Watch 40 videos at www.laffhub.com. Valid for 30days. NO DATA COST. To opt out,text OUT to 2001";
		}elseif (trim($amount)=='500') #500
		{
			$message = "You've successfully activated your Laffhub subscription. Watch UNLIMITED videos at www.laffhub.com. Valid for 30days. NO DATA COST. To opt out,text OUT to 2001";
		}
		
		$Username = 'laffhub';
		$Password = 'Kn1LfHb';

		$url='http://localhost:15015/cgi-bin/sendsms?username='.$Username.'&password='.$Password.'&smsc=AIRTEL&to='.$msisdn.'&from=2001&text='.urlencode($message);#33321
		
		$curl = curl_init();

		curl_setopt_array($curl, array(CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER, 0, 
			CURLOPT_SSL_VERIFYHOST, 0, CURLOPT_URL => $url));
		
		$response = curl_exec($curl); #Send the request & save response to $resp
		
		curl_close($curl); #Close request to clear up some resources
		
		#$response=array($response);
		
		if (strtolower(trim($response))=='0: accepted for delivery')
		{
			echo $msisdn.' Was Billed Successful. Amount Billed: N'.$amount;
		}else
		{
			print_r($response);
		}
	}elseif (trim(strtolower($status))=='failure')
	{
		#Check for insufficient balance - Error Code = OL404, Error Message = Insufficient Balance.#~#0.0
		
		$errorcode=$result['error']['errorCode'];
		$errormsg=$result['error']['errorMessage'];	
		
		echo 'BILLING FAILED: Error Code:'.$errorcode.'. Message: '.$errormsg;
	}
	
	
	
	#echo 'Transaction Status = '.$status.'<br>Transaction ID = '.$transid.'<br>Error Code = '.$errorcode.'<br>Error Message = '.$errormsg.'<br>CP Transaction ID = '.$cptransid.'<br><br>Generated CP Trans ID. = '.$cpTid;
	
	#echo 
	
	#print_r($result);
		
} catch(Exception $e){#SoapFault $e
	print_r($e->getMessage());#$rows[]=$e;
}
	 
	
?>