<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');


class Browsingsource extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('url');	
		$this->load->model('getdata_model');
		
	 }
	 
	
	public function DetermineSource()
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$_SESSION['InternetSource']='WIFI';


		if ($ip=='::1') $_SESSION['InternetSource'] = 'WIFI';
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'http://ip-api.com/json/'.$ip
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		curl_close($curl);
		
		#print_r($resp);
		$result=json_decode($resp);
		
		$ret=$this->getdata_model->InternetSource($result->org,$result->countryCode,$result->isp);
		
		$_SESSION['InternetSource']=$ret;
		
		$host=strtolower(trim($_SERVER['HTTP_HOST']));
		
		#$file = fopen('aaa_ISP.txt',"w"); fwrite($file, "\nISP=".$ret."\nMSISDN=".$this->getdata_model->GetMSISDN()."\nIP=".$ip."\nHost=".$host); fclose($file);

		
		
		if (strtolower(trim($ret))=='airtel')
		{
			if ($host=='localhost')
			{
				redirect('http://localhost/airtellaffhub', 'refresh');
			}else
			{
				redirect('http://airtel.laffhub.com', 'refresh');
				#redirect('Home', 'refresh');
			}
		}elseif (strtolower(trim($ret))=='mtn')
		{
			if ($host=='localhost')
			{
				#redirect('http://localhost/mtnlaffhub/Home', 'refresh');
				redirect('http://localhost/laffhub/Home', 'refresh');
				redirect('Home', 'refresh');
			}else
			{
				#redirect('http://mtn.laffhub.com', 'refresh');
				redirect('http://laffhub.com/Home', 'refresh');
				#redirect('Home', 'refresh');
			}
		}elseif (strtolower(trim($ret))=='wifi')
		{#$file = fopen('aaa_ISP.txt',"w"); fwrite($file, "INSIDE\n=====\nISP=".$ret."\nMSISDN=".$this->getdata_model->GetMSISDN()."\nIP=".$ip."\nHost=".$host); fclose($file);
			if ($host=='localhost')
			{
				redirect('Home', 'refresh');
			}else
			{
				redirect('Home', 'refresh');
			}
		}else
		{
			if ($host=='localhost')
			{
				redirect('http://www.laffhub.com/Home', 'refresh');
			}else
			{
				redirect('http://www.laffhub.com/Home', 'refresh');
			}
		}
	}
	
	public function index()
	{
		$this->DetermineSource();
	}
}