<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {	
	
	function __construct() 
	{#$file = fopen('aaa_ISP.txt',"w"); fwrite($file, "B4 HOME GETMODEL"); fclose($file);
		parent::__construct();
		$this->load->helper('url'); 	
		$this->load->model('getdata_model');
		
		#$file = fopen('aaa_ISP.txt',"a"); fwrite($file, "\n\nAFTER MODEL"); fclose($file);
	 }
	
	public function UserLogin()
	{
		$email=''; $pwd='';
		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('pwd')) $pwd = $this->input->post('pwd');
		
		$LogDate=date('Y-m-d H:i:s');
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['LogID']='';
		$_SESSION['subscriber_email']='';
		$_SESSION['subscriber_name']='';
		$_SESSION['subscriber_pwd'] = '';
		$_SESSION['subscriber_plan'] = '';
		$_SESSION['subscriber_status'] = '0';
		$_SESSION['subscription_status'] = '0';
		$_SESSION['datecreated'] = '';	
		$_SESSION['SubscriberPhone']=$this->getdata_model->GetNetwork();	
		$_SESSION['facebook_id'] = '';
				
		$_SESSION['network'] = '';
		$_SESSION['jwplayer_key']='';		
		$_SESSION['distribution_Id']='';
		$_SESSION['domain_name']='';
		$_SESSION['origin']='';
		$_SESSION['thumbs_bucket'] = '';
		
		$_SESSION['subscribe_date']='';
		$_SESSION['exp_date'] = '';
		
		$_SESSION['RemoteIP']=$remote_ip;
		$_SESSION['RemoteHost']=$remote_host;
		$_SESSION['LogIn']=date('Y-m-d H:i:s');
		
		$sql="SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(`pwd`)='".$this->db->escape_str($pwd)."')";

#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);	

		$query = $this->db->query($sql);
										
		if ($query->num_rows()>0)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				if($row->accountstatus != 1) 
				{
					$ret = "Account has been disabled. Please contact our support team at <a href='mailto:support@laffhub.com'>support@laffhub.com</a>.";
				}else
				{
					if ($row->name) $_SESSION['subscriber_name'] = $row->name;
					if ($row->pwd) $_SESSION['subscriber_pwd'] = $row->pwd;	
					if ($row->email) $_SESSION['subscriber_email'] = $row->email;					
					if ($row->reg_date) $_SESSION['datecreated'] = $row->reg_date;
					if ($row->accountstatus) $_SESSION['subscriber_status'] = $row->accountstatus;
										
					$_SESSION['LogID']=uniqid();
																								
					$sql="SELECT * FROM settings";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if (isset($row))
					{
						if ($row->jwplayer_key) $_SESSION['jwplayer_key'] = $row->jwplayer_key;
						if ($row->thumbs_bucket) $_SESSION['thumbs_bucket'] = $row->thumbs_bucket;
					}
							
					#Get Distribution Details
					$sql="SELECT * FROM streaming_domain";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if ($_SESSION['subscriber_name']) $nm=$_SESSION['subscriber_name']; else $nm=$_SESSION['subscriber_email'];
					
					if (isset($row))
					{
						if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
						if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
						if ($row->origin) $_SESSION['origin'] = $row->origin;
					}
					
					##Get Subscription Plan
					$sql="SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if ($_SESSION['subscriber_name']) $nm=$_SESSION['subscriber_name']; else $nm=$_SESSION['subscriber_email'];
										
					if ( $query->num_rows()> 0 )
					{
						if ($row->subscribe_date) $_SESSION['subscribe_date']=$row->subscribe_date;
						if ($row->exp_date) $_SESSION['exp_date'] = $row->exp_date;
						if ($row->subscriptionstatus) $_SESSION['subscription_status']=$row->subscriptionstatus;
						if ($row->plan) $_SESSION['subscriber_plan'] = $row->plan;
					}
					
					$_SESSION['subscription_status']=$this->getdata_model->CheckSubscriptionDate($email,'');
											
					$this->getdata_model->LogDetails($nm,'Subscriber Login',$_SESSION['subscriber_email'],$LogDate,$remote_ip,$remote_host,'SUBSCRIBER LOGIN',$_SESSION['LogID']);																			
										
					$ret='OK';
				}
			}					
		}else
		{
			$ret="Login Failed: Invalid authentication information. Please check your email and password.";
		}
		
		echo $ret;
	}#End Of UserLogin functions
	
	
	public function SubscriberFaceBookLogin()
	{#name, id, email,gender
		$email=''; $facebook_id=''; $email=''; $gender='';
		#echo $this->input->post('id');
		 
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('id')) $facebook_id = $this->input->post('id');
		if ($this->input->post('name')) $name = $this->input->post('name');
		if ($this->input->post('gender')) $gender = $this->input->post('gender');
		
		$LogDate=date('Y-m-d H:i:s');
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['facebook_id'] = '';
		$_SESSION['LogID']='';
		$_SESSION['subscriber_email']='';
		$_SESSION['subscriber_name']='';
		$_SESSION['subscriber_pwd'] = '';
		$_SESSION['subscriber_status'] = '0';
		$_SESSION['subscription_status'] = '0';
		$_SESSION['datecreated'] = '';	
		$_SESSION['SubscriberPhone']=$this->getdata_model->GSMPhoneNo(getenv('HTTP_MSISDN'));		
				
		$_SESSION['network'] = '';
		$_SESSION['jwplayer_key']='';		
		$_SESSION['distribution_Id']='';
		$_SESSION['domain_name']='';
		$_SESSION['origin']='';
		$_SESSION['thumbs_bucket'] = '';
		
		$_SESSION['subscribe_date']='';
		$_SESSION['exp_date'] = '';
		
		$_SESSION['RemoteIP']=$remote_ip;
		$_SESSION['RemoteHost']=$remote_host;
		$_SESSION['LogIn']=date('Y-m-d H:i:s');
		
		$sql="SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(facebook_id)='".$this->db->escape_str($facebook_id)."')";
		
		$query = $this->db->query($sql);
		
		#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);
										
		if ($query->num_rows()>0)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				if($row->accountstatus != 1) 
				{
					$ret = "Account has been disabled. Please contact our support team at <a href='mailto:support@laffhub.com'>support@laffhub.com</a>.";
				}else
				{
					if ($row->name) $_SESSION['subscriber_name'] = $row->name;
					if ($row->pwd) $_SESSION['subscriber_pwd'] = $row->pwd;	
					if ($row->email) $_SESSION['subscriber_email'] = $row->email;
					if ($row->facebook_id) $_SESSION['facebook_id'] = $row->facebook_id;					
					if ($row->reg_date) $_SESSION['datecreated'] = $row->reg_date;
					if ($row->accountstatus) $_SESSION['subscriber_status'] = $row->accountstatus;
										
					$_SESSION['LogID']=uniqid();
																								
					$sql="SELECT * FROM settings";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if (isset($row))
					{
						if ($row->jwplayer_key) $_SESSION['jwplayer_key'] = $row->jwplayer_key;
						if ($row->thumbs_bucket) $_SESSION['thumbs_bucket'] = $row->thumbs_bucket;
					}
							
					#Get Distribution Details
					$sql="SELECT * FROM streaming_domain";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if ($_SESSION['subscriber_name']) $nm=$_SESSION['subscriber_name']; else $nm=$_SESSION['subscriber_email'];
					
					if (isset($row))
					{
						if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
						if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
						if ($row->origin) $_SESSION['origin'] = $row->origin;
					}
					
					##Get Subscription Plan
					$sql="SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";

					$query = $this->db->query($sql);
				
					$row = $query->row();
					
					if ($_SESSION['subscriber_name']) $nm=$_SESSION['subscriber_name']; else $nm=$_SESSION['subscriber_email'];
					
					if ( $query->num_rows()> 0 )
					{
						if ($row->subscribe_date) $_SESSION['subscribe_date']=$row->subscribe_date;
						if ($row->exp_date) $_SESSION['exp_date'] = $row->exp_date;
						if ($row->subscriptionstatus) $_SESSION['subscription_status']=$row->subscriptionstatus;
					}
					
					$_SESSION['subscription_status']=$this->getdata_model->CheckSubscriptionDate($email,'');
											
					$this->getdata_model->LogDetails($nm,'Subscriber Login',$_SESSION['subscriber_email'],$LogDate,$remote_ip,$remote_host,'SUBSCRIBER FACEBOOK LOGIN',$_SESSION['LogID']);																			
										
					$ret='OK';
				}
			}					
		}else
		{
			$ret="Login Failed: Invalid authentication information. Please make sure that you registered on LaffHub using the facebook sign up button.";
		}
		
		echo $ret;
	}#End Of SubscriberFaceBookLogin functions
	
	public function myLogin()
	{
		$username=''; $pwd=''; $fullname='';
		
		$action = $this->input->post('action');
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('pwd')) $pwd = $this->input->post('pwd');
		if ($this->input->post('name')) $fullname = $this->getdata_model->DataCleaner(trim($this->input->post('name')));
		$LogDate=date('Y-m-d H:i:s');
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
		
		$_SESSION['distribution_Id']='';
		$_SESSION['domain_name']='';
		$_SESSION['origin']='';
		
		
		$_SESSION['RemoteIP']=$remote_ip;
		$_SESSION['RemoteHost']=$remote_host;
		$_SESSION['LogIn']=date('Y-m-d H:i:s');
		
		#$Name,$Activity,$Username,$Company
		$query = $this->db->query("SELECT * FROM userinfo WHERE (username='".$this->db->escape_str($username)."') AND (TRIM(`pwd`)='".$this->db->escape_str($pwd)."')");
										
		if ($query->num_rows()>0)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				if($row->accountstatus != 1) 
				{
					$ret = "Account has been disabled. Please contact our support team at support@laffhub.com.";
				}else
				{
					if ($row->username) $_SESSION['username'] = $row->username;
					if ($row->firstname) $_SESSION['firstname'] = $row->firstname;
					if ($row->lastname) $_SESSION['lastname'] = $row->lastname;
					if ($row->pwd) $_SESSION['pwd'] = $row->pwd;	
					if ($row->email) $_SESSION['email'] = $row->email;
					if ($row->phone) $_SESSION['phone'] = $row->phone;
					if ($row->datecreated) $_SESSION['datecreated'] = $row->datecreated;
					if ($row->accountstatus) $_SESSION['accountstatus'] = $row->accountstatus;
					if ($row->role) $_SESSION['role'] = $row->role;
					if ($row->Upload_Video) $_SESSION['Upload_Video'] = $row->Upload_Video;
					if ($row->CreateUser) $_SESSION['CreateUser'] = $row->CreateUser;
					if ($row->SetParameters) $_SESSION['SetParameters'] = $row->SetParameters;
					if ($row->ViewLogReport) $_SESSION['ViewLogReport'] = $row->ViewLogReport;
					
					$nm=$_SESSION['firstname'].' '.$_SESSION['lastname'];
					
					$_SESSION['LogID']=uniqid();
					$_SESSION['UserFullName']=$nm;
					
																			
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
					
					#Get Active Feed
					$ret=$this->getdata_model->GetActiveFeedRecord();
			
					if (count($ret)>0)
					{
						foreach($ret as $row):
							if($row->schedule_id) $_SESSION['schedule_id']=$row->schedule_id;
							if($row->feed_id) $_SESSION['feed_id']=$row->feed_id;
							if($row->title) $_SESSION['title']=$row->title;
							if($row->filename) $_SESSION['filename']=$row->filename;
							if($row->description) $_SESSION['description']=$row->description;
							if($row->status) $_SESSION['status']=$row->status;
							if($row->longlink) $_SESSION['longlink']=$row->longlink;
							if($row->shortlink) $_SESSION['shortlink']=$row->shortlink;
							if($row->pubdate) $_SESSION['pubdate']=$row->pubdate;
							if($row->expiredate) $_SESSION['expiredate']=$row->expiredate;
							
							break;
						endforeach;
					}
						
					$this->getdata_model->LogDetails($nm,'User Login',$_SESSION['username'],$LogDate,$remote_ip,$remote_host,'USER LOGIN',$_SESSION['LogID']);																			
										
					$ret='OK';
				}
			}					
		}else
		{
				$ret="Login Failed: Invalid authentication information. Please check your email and password.";
			}
		
		echo $ret;
	}#End Of myLogin functions
	
	public function index()
	{
		#if (!isset($_SESSION['InternetSource'])) redirect('Browsingsource');
		
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();	
		
		#Get facebook App ID and App Secret
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()> 0 )######### Facebook API Configuration ##########
		{
			$row = $query->row();
			
			if (isset($row)) $appId=$row->fb_appid;//Facebook App ID
		}
		
		if (strtolower(trim($_SERVER['HTTP_HOST']))=='localhost')
		{
			$data['appId']='250754245335621';//Facebook App ID
		}elseif (strtolower(trim($_SERVER['HTTP_HOST']))=='healthyliving.ng')
		{
			$data['appId']='210076916146163';//Facebook App ID
		}else
		{
			$data['appId']=$appId;//Facebook App ID
		}
				
		$data['Categories']=$this->getdata_model->GetCategories();
		
		
		
		#Determine Site To Launch
		$ret=$data['Network'];
		
		$host=strtolower(trim($_SERVER['HTTP_HOST']));

			
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
			$this->load->view('home_view',$data);
		}else
		{
			$this->load->view('home_view',$data);
		}
	}
}
