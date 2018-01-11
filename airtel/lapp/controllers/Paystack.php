<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Paystack extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function Update()
	{//verify_url,payment_currency,PublicKey,SecretKey
		$username=''; $UserFullName=''; $PublicKey=''; $SecretKey=''; $payment_currency=''; $verify_url=''; $ret='';
		
		if ($this->input->post('username')) $username = $this->input->post('username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('PublicKey')) $PublicKey = $this->input->post('PublicKey');
		if ($this->input->post('SecretKey')) $SecretKey = $this->input->post('SecretKey');
		if ($this->input->post('payment_currency')) $payment_currency = $this->input->post('payment_currency');
		if ($this->input->post('verify_url')) $verify_url = $this->input->post('verify_url');
		
		$Msg='';
				
		//Check if record exists
		$sql = "SELECT * FROM paystack_settings";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
			
			$dat=array(
				'PublicKey' => $this->db->escape_str($PublicKey),
				'SecretKey' => $this->db->escape_str($SecretKey),				
				'payment_currency' => $this->db->escape_str($payment_currency),
				'verify_url' => $this->db->escape_str($verify_url)
			);
		
			$this->db->insert('paystack_settings', $dat);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted inserting record into the Paystack settings table but failed.";
				$ret = 'Updating Paystack Settings Was Not Successful.';
			}else
			{
							
				$Msg="User '".$UserFullName."(".$username.")' inserted record into the Paystack settings table.";
		
				$ret = 'OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'INSERTED PAYSTACK SETTINGS',$_SESSION['LogID']);
		}else#Update
		{
			#Get Old Values
			$row = $query->row();
			
			$OldPk=''; $OldSk=''; $OldCur=''; $OldURL='';
		
			if (isset($row))
			{	
				if ($row->PublicKey) $OldPk = $row->PublicKey;
				if ($row->SecretKey) $OldSk = $row->SecretKey;
				if ($row->payment_currency) $OldCur = $row->payment_currency;
				if ($row->verify_url) $OldURL = $row->verify_url;
			}
						
			$BeforeValues="Public Key = ".$OldPk."; Secret Key = ".$OldSk."; Transaction Currency = ".$OldCur."; Verification URL = ".$OldURL;
				
			$AfterValues="Public Key = ".$PublicKey."; Secret Key = ".$SecretKey."; Transaction Currency = ".$payment_currency."; Verification URL = ".$verify_url;
						
			//Update transactions
			$this->db->trans_start();			
			#$where = "username='".$this->db->escape_str($username)."'";
			
			$dat=array(
				'PublicKey' => $this->db->escape_str($PublicKey),
				'SecretKey' => $this->db->escape_str($SecretKey),				
				'payment_currency' => $this->db->escape_str($payment_currency),
				'verify_url' => $this->db->escape_str($verify_url)
			);
			
			$this->db->update('paystack_settings', $dat);
			
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted editing Paystack settings record but failed.";
				$ret = 'Updating Paystack Settings Was Not Successful.';
			}else
			{				
				$Msg="User '".$UserFullName."(".$username.")' updated Paystack settings record. Old Values => ".$BeforeValues.". Updated values => ".$AfterValues;
		
				$ret = 'OK';	
			}
						
						
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED PAYSTACK SETTINGS',$_SESSION['LogID']);
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
			
			#Get PayStack Settings
			$PayStackSettings = $this->getdata_model->GetPaystackSettings();
				
			if (count($PayStackSettings)>0)
			{
				foreach($PayStackSettings as $row):
					if ($row->PublicKey) $data['PublicKey']=$row->PublicKey;
					if ($row->payment_currency) $data['payment_currency']=$row->payment_currency;					
					if ($row->SecretKey) $data['SecretKey']=$row->SecretKey;
					if ($row->verify_url) $data['verify_url']=$row->verify_url;
					
												
					break;
				endforeach;	
			}
						
			$this->load->view('paystack_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
