<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Searchresult extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function Search()
	{
		$searchstring=''; $page=1; $rowcount=25;
		
		if ($this->input->post('page')) $page = $this->input->post('page');
		if ($this->input->post('searchstring')) $searchstring = $this->input->post('searchstring');
		
		if (!$searchstring)
		{
			redirect('Subscriberhome');
		}else
		{
			#Get videos
			$sql = "SELECT * FROM videos WHERE (TRIM(video_title) LIKE '%".$this->db->escape_str($searchstring)."%') AND (play_status=1) AND (encoded=1) OR (TRIM(comedian) LIKE '%".$this->db->escape_str($searchstring)."%') AND (play_status=1) AND (encoded=1)";
			
			$order = " ORDER BY video_title";
			
			$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
				
			$query = $this->db->query($sql);
		
			if ($query->num_rows() == 0 )
			{
				$page=intval($page,10)-1;
				
				if (intval($page,10)<1) $page=1;
				
				$sql = "SELECT * FROM videos WHERE (TRIM(video_title) LIKE '%".$this->db->escape_str($searchstring)."%') AND (play_status=1) AND (encoded=1) OR (TRIM(comedian) LIKE '%".$this->db->escape_str($searchstring)."%') AND (play_status=1) AND (encoded=1)";
			
				$order = " ORDER BY video_title";
			
				$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
				
				$query = $this->db->query($sql);
			}
			
						
			if ($query->num_rows() > 0 )
			{
				$data['SearchResult']=$query->result();
				
				if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];

                $data['Network']='';
                $data['Phone']='';

                if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

                    $data['Network']=getenv('AIRTEL_NETWORK');
                    $data['Phone']=getenv('AIRTEL_MSISDN');

                }else{

                    $data['Network']=$this->getdata_model->GetNetwork();
                    $data['Phone']=$this->getdata_model->GetMSISDN();
                }
					
				$_SESSION['Network']=$data['Network'];
				$_SESSION['Phone']=$data['Phone'];
				
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
				
				$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
				$data['searchstring']=urldecode($searchstring);
				$data['page']=urldecode($page);
				
				if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
				$this->load->view('searchresult_view',$data);
				
			}else
			{
                $data['Network']='';
                $data['Phone']='';

                if (($_SERVER['HTTP_HOST'] == 'localhost') or ($_SERVER['HTTP_HOST'] == 'localhost:8888'))  {

                    $data['Network']=getenv('AIRTEL_NETWORK');
                    $data['Phone']=getenv('AIRTEL_MSISDN');

                }else{

                    $data['Network']=$this->getdata_model->GetNetwork();
                    $data['Phone']=$this->getdata_model->GetMSISDN();
                }

				if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
				
				$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
				$data['page']=urldecode($page);
				$data['searchstring']=urldecode($searchstring);
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
				
					
				$_SESSION['Network']=$data['Network'];
				$_SESSION['Phone']=$data['Phone'];
				
				$data['SearchResult']=$query->result();
				$data['page']=urldecode($page);
				$this->load->view('searchresult_view',$data);
			}	
		}		
	}
	 
	public function index()
	{
		redirect('Subscriberhome');
	}
}