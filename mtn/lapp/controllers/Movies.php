<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Movies extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
		if ($_SESSION['subscriber_email'])
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
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
			
			$data['subscribe_date'] = ''; $data['exp_date'] = ''; $data['subscriptionstatus'] = '';
			$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
			
			$data['Network']=$this->getdata_model->GetNetwork();
			$data['Phone']=$this->getdata_model->GetMSISDN();
		
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
			$data['PopularMovies']=$this->getdata_model->GetPopularMovies();
		
			
			$this->load->view('movies_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
