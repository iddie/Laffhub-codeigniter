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
		$ret=$this->getdata_model->GetNetwork();
		
		$host=strtolower(trim($_SERVER['HTTP_HOST']));
		
		#$file = fopen('aaa_ISP.txt',"w"); fwrite($file, "\nISP=".$ret."\nMSISDN=".$this->getdata_model->GetMSISDN()."\nIP=".$ip."\nHost=".$host); fclose($file);

				
		if (strtolower(trim($ret))=='airtel')
		{
			if ($host=='localhost')
			{
				redirect('http://localhost/airtellaffhub/Subscriberhome', 'refresh');
			}else
			{
				redirect('http://airtel.laffhub.com/Subscriberhome', 'refresh');
			}
		}elseif (strtolower(trim($ret))=='mtn')
		{
			if ($host=='localhost')
			{
				redirect('http://localhost/mtnlaffhub/Subscriberhome', 'refresh');				
			}else
			{
				redirect('http://mtn.laffhub.com/Subscriberhome', 'refresh');
			}
		}elseif (strtolower(trim($ret))=='wifi')
		{
			if ($host=='localhost')
			{
				redirect('Home', 'refresh');
			}else
			{
				redirect('https://laffhub.com/Home', 'refresh');
				
			}
		}else
		{
			if ($host=='localhost')
			{
				redirect('Home', 'refresh');
			}else
			{
				redirect('https://laffhub.com/Home', 'refresh');
			}
		}
	}
	
	public function index()
	{
		$this->DetermineSource();
	}
}