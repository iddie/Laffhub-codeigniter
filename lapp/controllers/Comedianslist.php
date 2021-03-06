<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Comedianslist extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
		if (!$_SESSION['subscriber_email'])
		{
			redirect('Home');
		}else
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			$data['Network']=$this->getdata_model->GetNetwork();
			$data['Phone']=$this->getdata_model->GetMSISDN();
				
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
			
			$data['AdminRoot']=$this->getdata_model->GetAdminRoot();
			$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
			$data['Categories']=$this->getdata_model->GetCategories();
			$data['Comedians']=$this->getdata_model->GetComedians();
			$this->load->view('comedianslist_view',$data);	
		}
	}
}
