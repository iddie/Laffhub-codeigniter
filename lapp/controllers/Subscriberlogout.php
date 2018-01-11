<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriberlogout extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
	 }
	
	public function index()
	{		
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['facebook_id'] = '';
		$_SESSION['LogID']='';
		$_SESSION['subscriber_email']='';
		$_SESSION['subscriber_name']='';
		$_SESSION['subscriber_pwd'] = '';
		$_SESSION['subscriber_status'] = '0';
		$_SESSION['datecreated'] = '';
		
		$_SESSION['Network']='';
		$_SESSION['Phone']='';
				
		$_SESSION['network'] = '';
		$_SESSION['jwplayer_key']='';		
		$_SESSION['distribution_Id']='';
		$_SESSION['domain_name']='';
		$_SESSION['origin']='';
		
		$_SESSION['subscribe_date']='';
		$_SESSION['exp_date'] = '';
		
		$_SESSION['RemoteIP']=$remote_ip;
		$_SESSION['RemoteHost']=$remote_host;
		$_SESSION['LogIn']=date('Y-m-d H:i:s');
		
		session_destroy();
		
		redirect('Home');
	}
}
