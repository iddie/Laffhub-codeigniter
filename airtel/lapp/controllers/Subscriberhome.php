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
		$category='';
		
		if ($this->uri->segment(3)) $category=$this->uri->segment(3,'');
		
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();
		
		$this->getdata_model->CheckSubscriptionDate('',$data['Phone']);
		
		$ret=$this->getdata_model->CheckForBlackList($data['Network'],$data['Phone']);
#$file = fopen('aaa.txt',"w"); fwrite($file,$ret); fclose($file);		
		if ($ret==true)#Blacklisted
		{
			$this->load->view('blist',$data);	
		}else#Not Blacklisted
		{
			$this->getdata_model->LoadSubscriberSession($data['Phone']);		
			
			if ($_SESSION['subscriber_email']) $data['subscriber_email']=$_SESSION['subscriber_email'];		
			if ($_SESSION['subscriber_name']) $data['subscriber_name'] = $_SESSION['subscriber_name'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];
	
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
			if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
			if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
			if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
			
			
			
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
			
			$data['Categories']=$this->getdata_model->GetCategories();
			
			$_SESSION['Network']=$data['Network'];
			$_SESSION['Phone']=$data['Phone'];
			
			
			$data['PopularMovies']=$this->getdata_model->GetPopularMovies();
			
			$data['LatestVideos']=$this->getdata_model->GetLatestVideos();
			$data['CategoryRandomVideos']=$this->getdata_model->GetRandomCategoryVideos();
            $data['ComedySkits']=$this->getdata_model->GetComedySkits();
            $data['JustForLaughs']=$this->getdata_model->GetJustForLaughs();
            $data['Arewa']=$this->getdata_model->GetArewa();
            $data['StandUpComedy']=$this->getdata_model->GetStandUpComedy();
            $data['ComedyNews']=$this->getdata_model->GetComedyNews();
            $data['FeaturedVideos']=$this->getdata_model->GetFeaturedVideos();
						
			#$file = fopen('aaa.txt',"a"); fwrite($file,"OUTPUT\n======\n"); fclose($file);
						
			
			#Determine Site To Launch
			$ret=$data['Network'];
			
			$host=strtolower(trim($_SERVER['HTTP_HOST']));
	
	#$file = fopen('aaa.txt',"a"); fwrite($file, "Base URL=".base_url()); fclose($file);				
	
			if (strtolower(trim($ret))=='airtel')
			{			
				$this->load->view('subscriberhome_view',$data);			
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
	}
}