<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

class Airtelsettings extends CI_Controller {
		
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	 	
	public function Update()
	{
		$messaging_username=''; $messaging_password=''; $billing_username=''; $ret=''; $billing_password='';
		$username=''; $UserFullName=''; $wsdl_path=''; $billing_location=''; $cpId=''; $opt_out_msg='';
		$messaging_url='';
		
		if ($this->input->post('username')) $username = $this->input->post('username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('messaging_username')) $messaging_username = $this->input->post('messaging_username');		
		if ($this->input->post('messaging_password')) $messaging_password = $this->input->post('messaging_password');		
		if ($this->input->post('billing_username')) $billing_username = $this->input->post('billing_username');		
		if ($this->input->post('billing_password')) $billing_password = $this->input->post('billing_password');		
		if ($this->input->post('wsdl_path')) $wsdl_path = $this->input->post('wsdl_path');		
		if ($this->input->post('billing_location')) $billing_location = $this->input->post('billing_location');			
		if ($this->input->post('cpId')) $cpId = $this->input->post('cpId');		
		if ($this->input->post('opt_out_msg')) $opt_out_msg = $this->input->post('opt_out_msg');
		if ($this->input->post('messaging_url')) $messaging_url = $this->input->post('messaging_url');
		
		$Msg='';
		
		//Check if record exists
		$sql = "SELECT * FROM airtel_settings";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
			
			$dat=array(
				'messaging_username' => $this->db->escape_str($messaging_username),
				'messaging_password' => $this->db->escape_str($messaging_password),
				'billing_username' => $this->db->escape_str($billing_username),
				'billing_password' => $this->db->escape_str($billing_password),
				'wsdl_path' => $this->db->escape_str($wsdl_path),					
				'billing_location' => $this->db->escape_str($billing_location),
				'cpId' => $this->db->escape_str($cpId),
				'messaging_url' => $this->db->escape_str($messaging_url),
				'opt_out_msg' => $this->db->escape_str($opt_out_msg)
			);
			
			$this->db->insert('airtel_settings', $dat);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted inserting record into the 'airtel_settings' table but failed.";
				$ret = 'Updating Airtel Settings Was Not Successful.';
			}else
			{
				$_SESSION['messaging_username'] = $messaging_username;
				$_SESSION['messaging_password'] = $messaging_password;
				$_SESSION['billing_username']  = $billing_username;
				$_SESSION['billing_password'] = $billing_password;				
				$_SESSION['wsdl_path'] = $wsdl_path;
				$_SESSION['billing_location'] = $billing_location;
				$_SESSION['messaging_url'] = $messaging_url;
				$_SESSION['cpId'] = $cpId;
				$_SESSION['opt_out_msg'] = $opt_out_msg;				
							
				$Msg="User '".$UserFullName."(".$username.")' inserted record into the 'airtel_settings' table.";
		
				$ret = 'OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'INSERTED AIRTEL SETTINGS',$_SESSION['LogID']);
		}else#Update
		{
			#Get Old Values
			$row = $query->row();
			
			$msgun=''; $msgpwd=''; $billun=''; $billpwd=''; $wsdl=''; $loc=''; $cid=''; $opt=''; $murl='';
					
			if (isset($row))
			{	
				if ($row->messaging_username) $msgun = $row->messaging_username;
				if ($row->messaging_password) $msgpwd = $row->messaging_password;
				if ($row->billing_username) $billun = $row->billing_username;
				if ($row->billing_password) $billpwd = $row->billing_password;
				if ($row->wsdl_path) $wsdl = $row->wsdl_path;
				if ($row->billing_location) $loc = $row->billing_location;
				if ($row->messaging_url) $murl = $row->messaging_url;
				if ($row->cpId) $cid = $row->cpId;
				if ($row->opt_out_msg) $opt = $row->opt_out_msg;
			}
						
			$BeforeValues="Messaging Username = ".$msgun."; Messaging Password = ".$msgpwd."; Billing Username = ".$billun."; Billing Password = ".$billpwd."; WSDL Path = ".$wsdl."; Billing Location = ".$loc."; Content Partner ID = ".$cid."; Opt Out Message = ".$opt."; Messaging URL = ".$murl;				
				
			$AfterValues="Messaging Username = ".$messaging_username."; Messaging Password = ".$messaging_password."; Billing Username = ".$billing_username."; Billing Password = ".$billing_password."; WSDL Path = ".$wsdl_path."; Billing Location = ".$billing_location."; Content Partner ID = ".$cpId."; Opt Out Message = ".$opt_out_msg."; Messaging URL = ".$messaging_url;
						
			//Update transactions
			$this->db->trans_start();			
						
			$dat=array(
				'messaging_username' => $this->db->escape_str($messaging_username),
				'messaging_password' => $this->db->escape_str($messaging_password),
				'billing_username' => $this->db->escape_str($billing_username),
				'billing_password' => $this->db->escape_str($billing_password),
				'wsdl_path' => $this->db->escape_str($wsdl_path),					
				'billing_location' => $this->db->escape_str($billing_location),
				'messaging_url' => $this->db->escape_str($messaging_url),
				'cpId' => $this->db->escape_str($cpId),
				'opt_out_msg' => $this->db->escape_str($opt_out_msg)
			);
			
			$this->db->update('airtel_settings', $dat);
			
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted editing airtel settings record but failed.";
				$ret = 'Updating Airtel Settings Was Not Successful.';
			}else
			{
				$_SESSION['messaging_username'] = $messaging_username;
				$_SESSION['messaging_password'] = $messaging_password;
				$_SESSION['billing_username']  = $billing_username;
				$_SESSION['billing_password'] = $billing_password;				
				$_SESSION['wsdl_path'] = $wsdl_path;
				$_SESSION['billing_location'] = $billing_location;
				$_SESSION['messaging_url'] = $messaging_url;
				$_SESSION['cpId'] = $cpId;
				$_SESSION['opt_out_msg'] = $opt_out_msg;
							
				$Msg="User '".$UserFullName."(".$username.")' updated airtel settings record. Old Values => ".$BeforeValues.". Updated values => ".$AfterValues;
		
				$ret = 'OK';	
			}
						
						
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED AIRTEL SETTINGS',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
			
	public function index()
	{		
		if ($_SESSION['username'])
		{
			$data['username']=$_SESSION['username'];
									
			$data['AddItem'] = '0'; $data['EditItem'] = '0'; $data['DeleteItem'] = '0';
			$data['Upload_Video'] = '0'; $data['CreateUser'] = '0'; $data['SetParameters'] = '0';
			$data['ViewLogReport'] = '0'; $data['ClearLogFiles'] = '0'; $data['ViewReports'] = '0';
			$data['CreatePublisher'] = '0'; $data['CreateComedian'] = '0'; $data['CreateCategory'] = '0';
			$data['ApproveVideo'] = '0'; $data['ApproveComment'] = '0'; $data['AddBanners'] = '0';
			$data['ModifyStaticPage'] = '0'; $data['AddArticlesToBlog'] = '0';
			$data['CheckDailyReports'] = '0'; $data['AddMobileOperator'] = '0'; $data['CreateEvents'] = '0';
						
			if ($_SESSION['username']) $data['username'] = $_SESSION['username'];
			if ($_SESSION['firstname']) $data['firstname'] = $_SESSION['firstname'];
			if ($_SESSION['lastname']) $data['lastname'] = $_SESSION['lastname'];
			if ($_SESSION['UserFullName']) $data['UserFullName'] = $_SESSION['UserFullName'];
			if ($_SESSION['pwd']) $data['pwd'] = $_SESSION['pwd'];
			if ($_SESSION['phone']) $data['phone'] = $_SESSION['phone'];
			if ($_SESSION['email']) $data['email'] = $_SESSION['email'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['accountstatus']) $data['accountstatus'] = $_SESSION['accountstatus'];
			if ($_SESSION['role']) $data['role'] = $_SESSION['role'];
			
			#################################
			#Permissions
			if ($_SESSION['AddItem']==1) $data['AddItem'] = $_SESSION['AddItem'];
			if ($_SESSION['EditItem']==1) $data['EditItem'] = $_SESSION['EditItem'];
			if ($_SESSION['DeleteItem']== 1) $data['DeleteItem'] = $_SESSION['DeleteItem'];
			if ($_SESSION['Upload_Video']== 1) $data['Upload_Video'] = $_SESSION['Upload_Video'];
			if ($_SESSION['CreateUser']==1) $data['CreateUser'] = $_SESSION['CreateUser'];
			if ($_SESSION['SetParameters']== 1) $data['SetParameters'] = $_SESSION['SetParameters'];
			if ($_SESSION['ViewLogReport']== 1) $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
			if ($_SESSION['ClearLogFiles']==1) $data['ClearLogFiles'] = $_SESSION['ClearLogFiles'];
			if ($_SESSION['ViewReports']==1) $data['ViewReports'] = $_SESSION['ViewReports'];
			if ($_SESSION['CreatePublisher']==1) $data['CreatePublisher'] = $_SESSION['CreatePublisher'];
			if ($_SESSION['CreateComedian']== 1) $data['CreateComedian'] = $_SESSION['CreateComedian'];
			if ($_SESSION['CreateCategory']== 1) $data['CreateCategory'] = $_SESSION['CreateCategory'];
			if ($_SESSION['ApproveVideo']==1) $data['ApproveVideo'] = $_SESSION['ApproveVideo'];
			if ($_SESSION['ApproveComment']==1) $data['ApproveComment'] = $_SESSION['ApproveComment'];
			if ($_SESSION['AddBanners']== 1) $data['AddBanners'] = $_SESSION['AddBanners'];
			if ($_SESSION['ModifyStaticPage']== 1) $data['ModifyStaticPage'] = $_SESSION['ModifyStaticPage'];
			if ($_SESSION['AddArticlesToBlog']== 1) $data['AddArticlesToBlog'] = $_SESSION['AddArticlesToBlog'];
			if ($_SESSION['CheckDailyReports']== 1) $data['CheckDailyReports'] = $_SESSION['CheckDailyReports'];
			if ($_SESSION['AddMobileOperator']== 1) $data['AddMobileOperator'] = $_SESSION['AddMobileOperator'];
			if ($_SESSION['CreateEvents']== 1) $data['CreateEvents'] = $_SESSION['CreateEvents'];
			###############################
			
			if ($_SESSION['companyname']) $data['companyname'] = $_SESSION['companyname'];
			if ($_SESSION['companyemail']) $data['companyemail'] = $_SESSION['companyemail'];
			if ($_SESSION['companyphone']) $data['companyphone'] = $_SESSION['companyphone'];
			if ($_SESSION['website']) $data['website'] = $_SESSION['website'];
			if ($_SESSION['companylogo']) $data['companylogo'] = $_SESSION['companylogo'];
			if ($_SESSION['RefreshDuration']) $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
			if ($_SESSION['default_network']) $data['default_network'] = $_SESSION['default_network'];
			if ($_SESSION['no_of_videos_per_day']) $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
			if ($_SESSION['google_shortener_api']) $data['google_shortener_api'] = $_SESSION['google_shortener_api'];
			if ($_SESSION['jw_api_key']) $data['jw_api_key'] = $_SESSION['jw_api_key'];
			if ($_SESSION['jw_api_secret']) $data['jw_api_secret'] = $_SESSION['jw_api_secret'];
			if ($_SESSION['jw_player_id']) $data['jw_player_id'] = $_SESSION['jw_player_id'];			
			if ($_SESSION['emergency_emails']) $data['emergency_emails'] = $_SESSION['emergency_emails'];
			if ($_SESSION['emergency_no']) $data['emergency_no'] = $_SESSION['emergency_no'];			
			if ($_SESSION['sms_url']) $data['sms_url'] = $_SESSION['sms_url'];
			if ($_SESSION['sms_username']) $data['sms_username'] = $_SESSION['sms_username'];
			if ($_SESSION['sms_password']) $data['sms_password'] = $_SESSION['sms_password'];			
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];			
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
					
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
		
			$data['messaging_username']='';	$data['messaging_password']=''; $data['messaging_url']='';
			$data['billing_username']='';	$data['billing_password']='';
			$data['wsdl_path']='';			$data['billing_location']='';
			$data['cpId']='';				$data['opt_out_msg']='';
				
			$ret=$this->getdata_model->GetAirtelSettings();
			
			foreach($ret as $rw)
			{
				if ($rw->messaging_username) $data['messaging_username']=$rw->messaging_username;
				if ($rw->messaging_password) $data['messaging_password']=$rw->messaging_password;
				if ($rw->billing_username) $data['billing_username']=$rw->billing_username;
				if ($rw->billing_password) $data['billing_password']=$rw->billing_password;
				if ($rw->wsdl_path) $data['wsdl_path']=$rw->wsdl_path;
				if ($rw->billing_location) $data['billing_location']=$rw->billing_location;
				if ($rw->messaging_url) $data['messaging_url']=$rw->messaging_url;
				if ($rw->cpId) $data['cpId']=$rw->cpId;
				if ($rw->opt_out_msg) $data['opt_out_msg']=$rw->opt_out_msg;
				
				break;	
			}
			
			$this->load->view('airtelsettings_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
