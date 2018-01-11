<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");


class Adminlogin extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->load->model('getdata_model');
		
	}
		
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
				
		$_SESSION['AddItem'] = '0'; $_SESSION['EditItem'] = '0';
		$_SESSION['DeleteItem'] = '0'; $_SESSION['Upload_Video'] = '0';
		$_SESSION['CreateUser'] = '0'; $_SESSION['SetParameters'] = '0';
		$_SESSION['ViewLogReport'] = '0'; $_SESSION['ClearLogFiles'] = '0';
		$_SESSION['ViewReports'] = '0'; $_SESSION['CreatePublisher'] = '0';
		$_SESSION['CreateComedian'] = '0'; $_SESSION['CreateCategory'] = '0';
		$_SESSION['ApproveVideo'] = '0'; $_SESSION['ApproveComment'] = '0';
		$_SESSION['AddBanners'] = '0'; $_SESSION['ModifyStaticPage'] = '0';
		$_SESSION['AddArticlesToBlog'] = '0'; $_SESSION['CheckDailyReports'] = '0';
		$_SESSION['AddMobileOperator'] = '0'; $_SESSION['CreateEvents'] = '0';
		
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
					
					#Permissions
					if ($row->AddItem) $_SESSION['AddItem'] = $row->AddItem;
					if ($row->EditItem) $_SESSION['EditItem'] = $row->EditItem;
					if ($row->DeleteItem) $_SESSION['DeleteItem'] = $row->DeleteItem;
					if ($row->Upload_Video) $_SESSION['Upload_Video'] = $row->Upload_Video;
					if ($row->CreateUser) $_SESSION['CreateUser'] = $row->CreateUser;
					if ($row->SetParameters) $_SESSION['SetParameters'] = $row->SetParameters;
					if ($row->ViewLogReport) $_SESSION['ViewLogReport'] = $row->ViewLogReport;
					if ($row->ClearLogFiles) $_SESSION['ClearLogFiles'] = $row->ClearLogFiles;
					if ($row->ViewReports) $_SESSION['ViewReports'] = $row->ViewReports;
					if ($row->CreatePublisher) $_SESSION['CreatePublisher'] = $row->CreatePublisher;
					if ($row->CreateComedian) $_SESSION['CreateComedian'] = $row->CreateComedian;
					if ($row->CreateCategory) $_SESSION['CreateCategory'] = $row->CreateCategory;
					if ($row->ApproveVideo) $_SESSION['ApproveVideo'] = $row->ApproveVideo;
					if ($row->ApproveComment) $_SESSION['ApproveComment'] = $row->ApproveComment;
					if ($row->AddBanners) $_SESSION['AddBanners'] = $row->AddBanners;
					if ($row->ModifyStaticPage) $_SESSION['ModifyStaticPage'] = $row->ModifyStaticPage;
					if ($row->AddArticlesToBlog) $_SESSION['AddArticlesToBlog'] = $row->AddArticlesToBlog;
					if ($row->CheckDailyReports) $_SESSION['CheckDailyReports'] = $row->CheckDailyReports;
					if ($row->AddMobileOperator) $_SESSION['AddMobileOperator'] = $row->AddMobileOperator;
					if ($row->CreateEvents) $_SESSION['CreateEvents'] = $row->CreateEvents;
	
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
		#Get Company DetailsSecret
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()> 0 )
		{
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
				if ($row->jwplayer_key) $_SESSION['jwplayer_key'] = $row->jwplayer_key;
				
				if ($row->input_bucket) $_SESSION['input_bucket'] = $row->input_bucket;
				if ($row->output_bucket) $_SESSION['output_bucket'] = $row->output_bucket;
				if ($row->thumbs_bucket) $_SESSION['thumbs_bucket'] = $row->thumbs_bucket;
				if ($row->aws_key) $_SESSION['aws_key'] = $row->aws_key;
				if ($row->aws_secret) $_SESSION['aws_secret'] = $row->aws_secret;
			}
		}
				
		$this->load->view('adminlogin_view',$data);
	}
}
