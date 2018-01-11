<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Browsingsource extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		
		$this->load->helper('url');
		$this->load->model('getdata_model');
	 }
	 
	public function LoadSession()
	{
		$sql="SELECT * FROM settings";

		$query = $this->db->query($sql);
	
		$row = $query->row();
		
		if (isset($row))
		{
			if ($row->no_of_videos_per_day) $_SESSION['no_of_videos_per_day']=$row->no_of_videos_per_day;
			if ($row->companyname) $_SESSION['companyname'] = $row->companyname;
			if ($row->companyemail) $_SESSION['companyemail'] = $row->companyemail;
			if ($row->companyphone) $_SESSION['companyphone'] = $row->companyphone;
			if ($row->website) $_SESSION['website'] = $row->website;
			if ($row->companylogo) $_SESSION['companylogo'] = $row->companylogo;
			if ($row->RefreshDuration) $_SESSION['RefreshDuration'] = $row->RefreshDuration;
			if ($row->default_network) $_SESSION['default_network'] = $row->default_network;
			if ($row->google_shortener_api) $_SESSION['google_shortener_api'] = $row->google_shortener_api;
			if ($row->jw_api_key) $_SESSION['jw_api_key'] = $row->jw_api_key;
			if ($row->jw_api_secret) $_SESSION['jw_api_secret'] = $row->jw_api_secret;
			if ($row->jw_player_id) $_SESSION['jw_player_id'] = $row->jw_player_id;		
			if ($row->emergency_emails) $_SESSION['emergency_emails'] = $row->emergency_emails;
			if ($row->emergency_no) $_SESSION['emergency_no'] = $row->emergency_no;						
			if ($row->sms_url) $_SESSION['sms_url'] = $row->sms_url;
			if ($row->sms_username) $_SESSION['sms_username'] = $row->sms_username;
			if ($row->sms_password) $_SESSION['sms_password'] = $row->sms_password;						
			if ($row->input_bucket) $_SESSION['input_bucket'] = $row->input_bucket;
			if ($row->output_bucket) $_SESSION['output_bucket'] = $row->output_bucket;
			if ($row->thumbs_bucket) $_SESSION['thumbs_bucket'] = $row->thumbs_bucket;
			if ($row->aws_key) $_SESSION['aws_key'] = $row->aws_key;
			if ($row->aws_secret) $_SESSION['aws_secret'] = $row->aws_secret;
			if ($row->jwplayer_key) $_SESSION['jwplayer_key'] = $row->jwplayer_key;
		}
		
		#Get Distribution Details
		$sql="SELECT * FROM streaming_domain";

		$query = $this->db->query($sql);
	
		$row = $query->row();
		
		if (isset($row))
		{
			if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
			if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
			if ($row->origin) $_SESSION['origin'] = $row->origin;
		}
	}
	
	public function DetermineSource()
	{		
		$ret=$this->getdata_model->GetNetwork();
		
		$_SESSION['InternetSource']=$ret;

		#$file = fopen('aaa_ISP.txt',"a"); fwrite($file, "ISP=".$ret."\nMSISDN=".$this->getdata_model->GetMSISDN()."\n"); fclose($file);

		
		$host=strtolower(trim($_SERVER['HTTP_HOST']));
		
		if (strtolower(trim($ret))=='airtel')
		{
			$this->LoadSession();
	#$file = fopen('aaa.txt',"a"); fwrite($file,$host); fclose($file);
			if ($host=='localhost')
			{
				redirect('http://localhost/airtellaffhub/Subscriberhome', 'refresh');
			}elseif ($host=='192.168.43.165')
			{
				redirect('http://192.168.43.165/airtellaffhub/Subscriberhome', 'refresh');
			}
			{
				redirect('http://airtel.laffhub.com/Subscriberhome', 'refresh');
			}
			
			#redirect('Subscriberhome', 'refresh');
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
				redirect('http://localhost/laffhub/Home', 'refresh');
			}else
			{
				redirect('https://laffhub.com/Home', 'refresh');
			}
		}else
		{
			if ($host=='localhost')
			{
				redirect('http://localhost/laffhub/Home', 'refresh');
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
