<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');

//Do not REMOVE Controller
class Xyz extends CI_Controller
{
    public function test()
    {
        header('Content-type: application/json');
        $msisdn = '2349029458935';
        $amount = '20';
        $duration = 1;
        $event_type = 'Subscription Purchase';
        $Username_Charge = '';
        $Password_Charge = '';
        $cpId='';
        $location='';
        $wsdl='';
        $messaging_url='';
        $query = $this->db->get('airtel_settings');
        $airtel_data = $query->row();
        $Password_Charge=trim($airtel_data->billing_password);
        $Username_Charge=trim($airtel_data->billing_username);
        $cpId=trim($airtel_data->cpId);
        $location=trim($airtel_data->billing_location);
        $wsdl=trim($airtel_data->wsdl_path);
        $messaging_url=$airtel_data->messaging_url;
        $cpTid=date('YmdHis').'_'.$msisdn;
        try {
            $options=array(
                'uri'=>'http://efluxz.com/billingservice',
                'location' => $messaging_url
            );
            $client=new SoapClient(null, $options);
            $param=array(
                'username'			=> $Username_Charge,
                'password'			=> $Password_Charge,
                'location'			=> $location,
                'wsdl'				=> $wsdl,
                'userId' 			=> $msisdn,
                'amount' 			=> $amount,
                'cpId'  			=> $cpId,
                'eventType' 		=> $event_type,
                'subscriptiondays'	=> $duration
            );
            $result=$client->BillAirtelUser($param);
            echo json_encode($result);
        } catch (Exception $e) {
            return array('Status' => 'FAILED','errorCode' => 'FFF','errorMessage' => $e->getMessage());
        }
    }
    public function header()
    {
        if(ENVIRONMENT=='production'){
            log_message('debug',json_encode($_SERVER));
        if(array_key_exists('HTTP_MSISDN',$_SERVER)){
            echo json_encode([
                'status'=>'success',
                'network'=>'MTN',
                'msisdn'=>$_SERVER['HTTP_MSISDN'],
                'env'=>ENVIRONMENT
            ]);
        }
        else if(array_key_exists('HTTP_X_UP_CALLING_LINE_ID',$_SERVER))
        {
            echo json_encode([
                'status'=>'success',
                'network'=>'AIRTEL',
                'msisdn'=>$_SERVER['HTTP_X_UP_CALLING_LINE_ID'],
                'env'=>ENVIRONMENT
            ]);
        }
        else{
            echo json_encode([
                'status'=>'fail',
                'msisdn'=>null,
                'env'=>ENVIRONMENT
            ]);
        }
        }
        else{
            echo file_get_contents('http://laffhub.com/xyz/header');
        }
    }
    public function post()
    {
        $ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://localhost:8085/airtel_se_app/subscribe?msisdn");
curl_setopt($ch, CURLOPT_POST, 1);
// In real life you should use something like:
curl_setopt($ch, CURLOPT_POSTFIELDS, 
         http_build_query(array('msisdn' => '2349029458935','service'=>17)));

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);
echo $server_output;
    }
    public function sms()
    {
        $message = 'Laffhub SMS Test';
        $msisdn = '2349029458935';
        $Username = ''; $Password = ''; $messaging_url='';
		
		$sql="SELECT * FROM airtel_settings";		
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row_array();
							
			if ($row['messaging_password']) $Password=trim($row['messaging_password']);
			if ($row['messaging_username']) $Username=trim($row['messaging_username']);
			if ($row['messaging_url']) $messaging_url=trim($row['messaging_url']);
		}	
		
		$options=array(
			'uri'=>'http://efluxz.com/billingservice',
			'location' => $messaging_url
		);
		
		$client=new SoapClient(NULL,$options);
		
		$param=array(
			'msisdn' 		=> $msisdn, 
			'message' 		=> $message,
			'Username'  	=> $Username,
			'Password' 		=> $Password
			);
			
		$result=$client->SendMsgToAirtelUser($param);
		
		return $result;
    }
}
