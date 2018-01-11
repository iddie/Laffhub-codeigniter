<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
        $data['Network']='';
        $data['Phone']='';

        if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

            $data['Network']=getenv('MTN_NETWORK');
            $data['Phone']=getenv('MTN_MSISDN');

        }else{

            $data['Network']=$this->getdata_model->GetNetwork();
            $data['Phone']=$this->getdata_model->GetMSISDN();
        }

		$this->getdata_model->LoadSubscriberSession($data['Phone']);
		
		if ($_SESSION['subscriber_email']) $data['subscriber_email']=$_SESSION['subscriber_email'];
		if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
		if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];
		if ($_SESSION['subscriber_plan']) $data['subscriber_plan'] = $_SESSION['subscriber_plan'];
		
		if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
		if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
		if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
		if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
		
		$data['subscribe_date'] = ''; $data['exp_date'] = ''; $data['subscriptionstatus'] = '';
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
		$this->load->view('profile_view',$data);#Fail Page
	}
}
