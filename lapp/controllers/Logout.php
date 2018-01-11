<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
	 }
	
	public function index()
	{		
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['LogID']='';
		$_SESSION['username']='';
		$_SESSION['firstname']='';
		$_SESSION['lastname']='';
		$_SESSION['companyname'] = '';
		$_SESSION['pwd'] = '';
		$_SESSION['name'] = '';
		$_SESSION['email'] = '';
		$_SESSION['phone'] = '';
		$_SESSION['state'] = '';
		$_SESSION['datecreated'] = '';		
		$_SESSION['role'] = '0';
		$_SESSION['Upload_Video'] = '0';
		$_SESSION['UserFullName']='';
				
		$_SESSION['CreateUser'] = '';
		$_SESSION['SetParameters'] = '';
		$_SESSION['ViewLogReport'] = '';
		
		$_SESSION['no_of_videos_per_day'] = '';
		$_SESSION['companyname'] = '';
		$_SESSION['companyemail'] = '';
		$_SESSION['companyphone'] = '';
		$_SESSION['website'] = '';
		$_SESSION['companylogo'] = '';
		$_SESSION['RefreshDuration'] = '';
		$_SESSION['default_network'] = '';
		$_SESSION['google_shortener_api']='';
		$_SESSION['jw_api_key'] = '';
		$_SESSION['jw_api_secret'] = '';
		$_SESSION['jw_player_id'] = '';
		$_SESSION['emergency_emails']='';
		$_SESSION['emergency_no']='';		
		$_SESSION['sms_url']='';
		$_SESSION['sms_username']='';
		$_SESSION['sms_password']='';
		$_SESSION['input_bucket']='';
		$_SESSION['output_bucket']='';
		$_SESSION['thumbs_bucket']='';
		$_SESSION['aws_key']='';
		$_SESSION['aws_secret']='';
		$_SESSION['jwplayer_key']='';
		
		$_SESSION['RemoteIP']='';
		$_SESSION['RemoteHost']='';
		$_SESSION['LogIn']='';
		
		session_destroy();
		
		redirect('Home');
	}
}
