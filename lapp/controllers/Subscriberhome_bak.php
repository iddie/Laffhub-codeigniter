<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriberhome extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	 
	public function RateVideo()
	{
		$video_code=''; $email=''; $phone=''; $rating='';
		
		if ($this->input->post('video_code'))$video_code = trim($this->input->post('video_code'));
		if ($this->input->post('email'))$email = trim($this->input->post('email'));
		if ($this->input->post('phone'))$phone = trim($this->input->post('phone'));
		if ($this->input->post('rating'))$rating = trim($this->input->post('rating'));
		
		$rows='';
		
		#$file = fopen('aaa.txt',"w"); fwrite($file,"Video Code=".$video_code."\nEmail=".$email."\nPhone=".$phone."\nRating=".$rating); fclose($file);

		//Check if record exists
		if ($phone)
		{
			$phone=$this->getdata_model->CleanPhoneNo($phone);
			
			$sql = "SELECT * FROM user_ratings WHERE (TRIM(video_code)='".$this->db->escape_str($video_code)."') AND (TRIM(msisdn)='".$this->db->escape_str($phone)."')";
		}elseif ($email)
		{
			$sql = "SELECT * FROM user_ratings WHERE (TRIM(video_code)='".$this->db->escape_str($video_code)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
		}		
		
		$query = $this->db->query($sql);
		
		$VideoTitle=$this->getdata_model->GetVideoTitle($video_code);
				
		$subscriber_id=''; $Msg='';
#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);			
		if ($phone)
		{
			$subscriber_id=$phone;
		}elseif ($email)
		{
			$subscriber_id=$email;
		}
		
		if ($query->num_rows() > 0 )#Update
		{
			$this->db->trans_start();
			
			if ($phone)
			{
				$dat=array(
					'video_code' => $this->db->escape_str($video_code),
					'msisdn' => $this->db->escape_str($phone),
					'email' => $this->db->escape_str($email),
					'rating' => $this->db->escape_str($rating)
					);							
				
				$this->db->where(array('video_code' => $this->db->escape_str($video_code),'msisdn' => $this->db->escape_str($phone)));
			}elseif ($email)
			{
				$dat=array(
					'video_code' => $this->db->escape_str($video_code),
					'email' => $this->db->escape_str($email),
					'msisdn' => $this->db->escape_str($phone),
					'rating' => $this->db->escape_str($rating)
					);							
				
				$this->db->where(array('video_code' => $this->db->escape_str($video_code),'email' => $this->db->escape_str($email)));
			}			
			
			$this->db->update('user_ratings', $dat);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Updating Of Rating Of The Video '".strtoupper($VideoTitle)." By Subscriber ".$subscriber_id." Failed.";
				
				$rows = "Rating Of Video Was Not Successful.";
			}else
			{
				$Msg="Updating Of Rating Of The Video '".strtoupper($VideoTitle)." By Subscriber ".$subscriber_id." Was Successful.";
				
				$rows='OK';
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($subscriber_id,$Msg,$subscriber_id,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED VIDEO RATING',$_SESSION['LogID']);
		}else#Insert
		{				
			$this->db->trans_start();
			
			if ($phone)
			{
				$dat=array(
					'video_code' => $this->db->escape_str($video_code),
					'msisdn' => $this->db->escape_str($phone),
					'email' => $this->db->escape_str($email),
					'rating' => $this->db->escape_str($rating)
					);
			}elseif ($email)
			{
				$dat=array(
					'video_code' => $this->db->escape_str($video_code),
					'email' => $this->db->escape_str($email),
					'msisdn' => $this->db->escape_str($phone),
					'rating' => $this->db->escape_str($rating)
					);
			}														
			
			$this->db->insert('user_ratings', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="Rating Of The Movie '".strtoupper($MovieTitle)." By Subscriber ".$subscriber_id." Failed.";
				
				$rows = "Rating Of Video Was Not Successful.";
			}else
			{
				$Msg="Rating Of The Movie '".strtoupper($MovieTitle)." By Subscriber ".$subscriber_id." Was Successful.";
				
				$rows='OK';
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($subscriber_id,$Msg,$subscriber_id,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'RATED VIDEO',$_SESSION['LogID']);
		}
		
		echo $rows;
	}
	
	public function index()
	{
		
		$category='';
		
		if ($this->uri->segment(3)) $category=$this->uri->segment(3,'');
		
		
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
				
		
		if ($_SESSION['subscriber_email']) $data['subscriber_email']=$_SESSION['subscriber_email'];
			
		if ($_SESSION['subscriber_name']) $data['subscriber_name'] = $_SESSION['subscriber_name'];
		if ($_SESSION['subscriber_pwd']) $data['subscriber_pwd'] = $_SESSION['subscriber_pwd'];
		if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
		if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];
		if ($_SESSION['facebook_id']) $data['facebook_id'] = $_SESSION['facebook_id'];

		if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
		if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
		if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
		if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
		if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
		
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();
		
		$data['subscribe_date'] = ''; $data['exp_date'] = '';
		$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
			
		$result=$this->getdata_model->GetSubscriptionDate($data['subscriber_email'],$data['Phone']);
							
		if (is_array($result))
		{
			$td=date('Y-m-d H:i:s');
			
			foreach($result as $row)
			{
				if ($row->subscribe_date) $dt = date('F d, Y',strtotime($row->subscribe_date));
				
				$data['subscribe_date'] = $dt;
				
				if ($row->exp_date) $edt = date('F d, Y',strtotime($row->exp_date));
				$data['exp_date'] = $edt;
				
				if ($td > date('Y-m-d H:i:s',strtotime($row->exp_date)))
				{
					if ($row->subscriptionstatus==1)
					{
						#Update Subscription Date
						$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'0');
					}
				}else
				{
					$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
				}

				break;
			}
		}
		
		$data['Categories']=$this->getdata_model->GetCategories();
		
		$_SESSION['Network']=$data['Network'];
		$_SESSION['Phone']=$data['Phone'];
		
		
		$data['PopularMovies']=$this->getdata_model->GetPopularMovies();
		
		$data['LatestVideos']=$this->getdata_model->GetLatestVideos($data['subscriber_email'],$data['Phone']);
		
		$RecMovies = $this->getdata_model->RecommendedMovies($data['subscriber_email'],$data['Phone']);
		
		#$file = fopen('aaa.txt',"a"); fwrite($file,"OUTPUT\n======\n"); fclose($file);
		
		if (count($RecMovies)>0)
		{
			$codes='';
			#Array([5946fbb8b5a05024206078] => 5 [5946fdad9fd52862073812] => 4 [5946fb1cdcba0075373600] => 4)
			foreach ($RecMovies as $cd=>$vl):
				if ($cd)
				{
					if ($codes=='') $codes="'".$cd."'"; else $codes .= ','."'".$cd."'";
				}
			endforeach;
			
			if ($codes)
			{
				$sql="SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (video_code IN (".$codes."))";
				
				$query = $this->db->query($sql);
				
				$data['RecommendedMovies']=$query->result();
			}
		}
		#print_r($data['RecommendedMovies']);
		
		$this->load->view('subscriberhome_view',$data);
	}
}
