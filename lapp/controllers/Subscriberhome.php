<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriberhome extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	 	
	public function index()
	{
#$file = fopen('aaa.txt',"a"); fwrite($file,$email); fclose($file);		
		$category=''; #$email='promo@laffhub.com';

		if ($this->uri->segment(3)) $category=$this->uri->segment(3,'');
	
		#$this->getdata_model->LoadSubscriberSession($email);
		
		if ($_SESSION['subscriber_email'])
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			$this->getdata_model->CheckSubscriptionDate($data['subscriber_email'],'');
			
			$ret=$this->getdata_model->CheckForBlackList($data['Network'],$data['subscriber_email']);
			
			if ($ret==true)#Blacklisted
			{
				$this->load->view('blist',$data);	
			}else#Not Blacklisted
			{
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
										
						if ($td >= date('Y-m-d H:i:s',strtotime($row->exp_date)))
						{
							if ($row->subscriptionstatus==1)
							{
								#Update Subscription Date
								$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'0');
							}
						}else
						{
							if (!$row->subscriptionstatus)
							{
								$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'1');
								$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
							}else
							{
								$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
							}
						}
		
						break;
					}
				}
				
				$sql="SELECT * FROM settings";
		
				$query = $this->db->query($sql);
		
				$row = $query->row();
				
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
				
				#Get Distribution Details
				$sql="SELECT * FROM streaming_domain";
		
				$query = $this->db->query($sql);
			
				$row = $query->row();
				
				if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
				if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
				if ($row->origin) $_SESSION['origin'] = $row->origin;
				
				$data['Categories']=$this->getdata_model->GetCategories();
				
				$_SESSION['Network']=$data['Network'];
				$_SESSION['Phone']=$data['Phone'];
				
				$data['PopularMovies']=$this->getdata_model->GetPopularMovies();				
				$data['LatestVideos']=$this->getdata_model->GetLatestVideos();
				$data['ComedySkits']=$this->getdata_model->GetComedySkits();
                $data['JustForLaughs']=$this->getdata_model->GetJustForLaughs();
                $data['Arewa']=$this->getdata_model->GetArewa();
                $data['StandUpComedy']=$this->getdata_model->GetStandUpComedy();
                $data['ComedyNews']=$this->getdata_model->GetComedyNews();
                $data['FeaturedVideos']=$this->getdata_model->GetFeaturedVideos();

				
				#Determine Site To Launch
				$ret=$data['Network'];
				
				$host=strtolower(trim($_SERVER['HTTP_HOST']));
		
		#$file = fopen('aaa.txt',"a"); fwrite($file, "Base URL=".base_url()); fclose($file);				
		
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
						redirect('https://mtn.laffhub.com/Subscriberhome', 'refresh');
					}
				}elseif (strtolower(trim($ret))=='wifi')
				{
					$this->load->view('subscriberhome_view',$data);
				}
                elseif (strtolower(trim($ret))=='etisalat')
                {
                    redirect('http://comedy.cloud9.com.ng', 'refresh');
                }
                else
				{
                    if ($host=='localhost')
                    {
                        redirect('http://localhost/laffhub/Subscriberhome', 'refresh');
                    }else
                    {
                        redirect('http://laffhub.com/Subscriberhome', 'refresh');
                    }
				}
			}
		}else
		{

		    $sql="SELECT * FROM settings";

            $query = $this->db->query($sql);

            $row = $query->row();

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

            #Get Distribution Details
            $sql="SELECT * FROM streaming_domain";

            $query = $this->db->query($sql);

            $row = $query->row();

            if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
            if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
            if ($row->origin) $_SESSION['origin'] = $row->origin;

            $data['Categories']=$this->getdata_model->GetCategories();

            $_SESSION['Network']=$data['Network'];
            $_SESSION['Phone']=$data['Phone'];

            if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
            if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
            if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
            if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
            if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];

            $data['subscriber_email']='';
            $data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
            $data['Network']=$this->getdata_model->GetNetwork();

            $data['PopularMovies']=$this->getdata_model->GetPopularMovies();
            $data['LatestVideos']=$this->getdata_model->GetLatestVideos();
            $data['ComedySkits']=$this->getdata_model->GetComedySkits();
            $data['JustForLaughs']=$this->getdata_model->GetJustForLaughs();
            $data['Arewa']=$this->getdata_model->GetArewa();
            $data['StandUpComedy']=$this->getdata_model->GetStandUpComedy();
            $data['ComedyNews']=$this->getdata_model->GetComedyNews();
            $data['FeaturedVideos']=$this->getdata_model->GetFeaturedVideos();

            #Determine Site To Launch
            $ret=$data['Network'];

            $host=strtolower(trim($_SERVER['HTTP_HOST']));

            #$file = fopen('aaa.txt',"a"); fwrite($file, "Base URL=".base_url()); fclose($file);

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
                    redirect('https://mtn.laffhub.com/Subscriberhome', 'refresh');
                }
            }elseif (strtolower(trim($ret))=='wifi')
            {
                $this->load->view('subscriberhome_view',$data);
            }
            elseif (strtolower(trim($ret))=='etisalat')
            {
                redirect('http://comedy.cloud9.com.ng', 'refresh');

            }else
            {
                if ($host=='localhost')
                {
                    redirect('http://localhost/laffhub/Subscriberhome', 'refresh');
                }else
                {
                    redirect('http://laffhub.com/Subscriberhome', 'refresh');
                }
            }

		}
	}
}
