<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

header("Access-Control-Allow-Origin: *");


class Pubhome extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->load->model('getdata_model');
		
	}
	
	public function PublisherFaceBookLogin()
	{#name, id, email,gender
		$email=''; $facebook_id=''; $email=''; $gender='';
		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('id')) $facebook_id = $this->input->post('id');
		if ($this->input->post('name')) $name = $this->input->post('name');
		if ($this->input->post('gender')) $gender = $this->input->post('gender');
		
		$LogDate=date('Y-m-d H:i:s');
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['LogID']='';
		$_SESSION['publisher_email']='';
		$_SESSION['publisher_name']='';
		$_SESSION['publisher_phone']='';
		$_SESSION['publisher_pwd'] = '';
		$_SESSION['publisher_status'] = '0';
		$_SESSION['facebook_id'] = '';
		$_SESSION['datecreated'] = '';		
				
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
		
		$sql="SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."') AND (TRIM(facebook_id)='".$this->db->escape_str($facebook_id)."')";
		
		$query = $this->db->query($sql);
		
		#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);
										
		if ($query->num_rows()>0)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				if($row->publisher_status != 1) 
				{
					$ret = "Account has been disabled. Please contact our support team at <a href='mailto:support@laffhub.com'>support@laffhub.com</a>.";
				}else
				{
					if ($row->publisher_name) $_SESSION['publisher_name'] = $row->publisher_name;
					if ($row->publisher_phone) $_SESSION['publisher_phone'] = $row->publisher_phone;
					if ($row->facebook_id) $_SESSION['facebook_id'] = $row->facebook_id;	
					if ($row->publisher_email) $_SESSION['publisher_email'] = $row->publisher_email;					
					if ($row->publisher_regdate) $_SESSION['datecreated'] = $row->publisher_regdate;
					if ($row->publisher_status) $_SESSION['publisher_status'] = $row->publisher_status;
										
					$_SESSION['LogID']=uniqid();
																								
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
						
					$this->getdata_model->PublisherLogDetails($nm,'Publisher Login',$_SESSION['publisher_email'],$LogDate,$remote_ip,$remote_host,'PUBLISER FACEBOOK LOGIN',$_SESSION['LogID']);																			
										
					$ret='OK';
				}
			}					
		}else
		{
			$ret="Login Failed: Invalid authentication information. Please make sure that you registered on LaffHub using the facebook sign up button.";
		}
		
		echo $ret;
	}#End Of PublisherFaceBookLogin functions
	
	public function PublisherLogin()
	{
		$email=''; $pwd=''; $appId='';
		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('pwd')) $pwd = $this->input->post('pwd');
		
		$LogDate=date('Y-m-d H:i:s');
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
		$_SESSION['LogID']='';
		$_SESSION['publisher_email']='';
		$_SESSION['publisher_name']='';
		$_SESSION['publisher_phone']='';
		$_SESSION['publisher_pwd'] = '';
		$_SESSION['publisher_status'] = '0';
		$_SESSION['datecreated'] = '';		
				
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
		$sql="SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."') AND (TRIM(`publisher_pwd`)='".$this->db->escape_str($pwd)."')";

#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);	

		$query = $this->db->query($sql);
										
		if ($query->num_rows()>0)
		{
			$row = $query->row();
			
			if (isset($row))
			{
				if($row->publisher_status != 1) 
				{
					$ret = "Account has been disabled. Please contact our support team at <a href='mailto:support@laffhub.com'>support@laffhub.com</a>.";
				}else
				{
					if ($row->publisher_name) $_SESSION['publisher_name'] = $row->publisher_name;
					if ($row->publisher_phone) $_SESSION['publisher_phone'] = $row->publisher_phone;
					if ($row->publisher_pwd) $_SESSION['publisher_pwd'] = $row->publisher_pwd;	
					if ($row->publisher_email) $_SESSION['publisher_email'] = $row->publisher_email;					
					if ($row->publisher_regdate) $_SESSION['datecreated'] = $row->publisher_regdate;
					if ($row->publisher_status) $_SESSION['publisher_status'] = $row->publisher_status;
										
					$_SESSION['LogID']=uniqid();
																								
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
						if ($row->fb_appid) $_SESSION['fb_appid'] = $row->fb_appid;
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
						
					$this->getdata_model->PublisherLogDetails($nm,'Publisher Login',$_SESSION['publisher_email'],$LogDate,$remote_ip,$remote_host,'PUBLISER LOGIN',$_SESSION['LogID']);																			
										
					$ret='OK';
				}
			}					
		}else
		{
			$ret="Login Failed: Invalid authentication information. Please check your email and password.";
		}
		
		echo $ret;
	}#End Of PublisherLogin functions
	
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
				
				if ($row->fb_appid) $appId=$row->fb_appid;//Facebook App ID
				if ($row->fb_appsecret) $data['appSecret']=$row->fb_appsecret;//Facebook App Secret
			}
			
			

			if (strtolower(trim($_SERVER['HTTP_HOST']))=='localhost')
			{
				$data['appId']='250754245335621';//Facebook App ID
			}elseif (strtolower(trim($_SERVER['HTTP_HOST']))=='healthyliving.ng')
			{
				#$file = fopen('aaa.txt',"w"); fwrite($file,"Using healthyliving.ng" ); fclose($file);
				$data['appId']='210076916146163';//Facebook App ID
			}else
			{
				#$file = fopen('aaa.txt',"w"); fwrite($file,"Using laffhub.com" ); fclose($file);
				$data['appId']=$appId;//Facebook App ID
			}
		}
				
		$this->load->view('pubhome_view',$data);
	}
}
