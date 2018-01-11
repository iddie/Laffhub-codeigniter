<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }

	public function GetCategory()
	{
		$category=''; $page=1; $rowcount=20;
		
		if ($this->input->post('page')) $page = $this->input->post('page');
		if ($this->input->post('category')) $category = $this->input->post('category');
	
		if (!$category)
		{
			redirect('Categories');
		}else
		{
			if (!$_SESSION['subscriber_email'])
			{
				redirect('Home');
			}else
			{
				$data['subscriber_email'] = $_SESSION['subscriber_email'];
				
				#Get category videos
				$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(category)='".$this->db->escape_str($category)."')";
				
				$order = " ORDER BY video_title";
				
				$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
						
				$query = $this->db->query($sql);
				
	#$file = fopen('aaa.txt',"w"); fwrite($file,$sql."\n".$query->num_rows()); fclose($file);	
	
				if ($query->num_rows() == 0 )
				{
					$page=intval($page,10)-1;
					
					if (intval($page,10)<1) $page=1;
					
					$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(category)='".$this->db->escape_str($category)."')";
				
					$order = " ORDER BY video_title";
				
					$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
					
					$query = $this->db->query($sql);
				}
				
							
				if ($query->num_rows() > 0 )
				{
					$data['CategoryVideos']=$query->result();
					
					$data['Network']=$this->getdata_model->GetNetwork();
					$data['Phone']=$this->getdata_model->GetMSISDN();
						
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
					$data['Categories']=$this->getdata_model->GetCategories();
					$data['Category']=urldecode($category);
					$data['page']=urldecode($page);
									
					if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
					$this->load->view('category_view',$data);
					
				}else
				{
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
					
					$data['CategoryVideos']=$query->result();
					$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
					$data['Category']=urldecode($category);
					$data['page']=urldecode($page);
					$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';				
					$data['Categories']=$this->getdata_model->GetCategories();
					
					$_SESSION['Network']=$data['Network'];
					$_SESSION['Phone']=$data['Phone'];
		
					$this->load->view('category_view',$data);
				}	
			}
		}		
	 }
	 
  public function ShowCategories()
 {
	$id=''; $category=''; $page=1; $rowcount=20;
	
	if ($this->uri->segment(1)) $category=urldecode($this->uri->segment(3));
	if ($this->input->post('page')) $page = $this->input->post('page');


	if (!$category)
	{
		redirect('Categories');
	}else
	{
		if (!$_SESSION['subscriber_email'])
		{
			redirect('Home');
		}else
		{
			$data['subscriber_email'] = $_SESSION['subscriber_email'];
		
			#Get category videos
			$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(category)='".$this->db->escape_str($category)."') ";	
			
			$order = " ORDER BY video_title";
				
			$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
			
			#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);	
			
			$query = $this->db->query($sql);
				
			if ($query->num_rows() > 0 )
			{	
				$data['CategoryVideos']=$query->result();
						
				$data['Network']=$this->getdata_model->GetNetwork();
				$data['Phone']=$this->getdata_model->GetMSISDN();
					
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
				
				$data['page']=urldecode($page);
				$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
				$data['Categories']=$this->getdata_model->GetCategories();
				$data['Category']=urldecode($category);
					
				if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
				$this->load->view('category_view',$data);			
			}else
			{
				$data['CategoryVideos']=$query->result();
				
				$data['Network']=$this->getdata_model->GetNetwork();
				$data['Phone']=$this->getdata_model->GetMSISDN();
				if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
				
				$data['page']=urldecode($page);
				$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
				$data['Category']=urldecode($category);
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
				
				$data['Categories']=$this->getdata_model->GetCategories();
				show_404();
				
				// $this->load->view('category_view',$data);
			}		
		}		
	}
 }
		
	public function index()
	{
		if (!$_SESSION['subscriber_email'])
		{
			redirect('Home');
		}else
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			$data['Categories']=$this->getdata_model->GetCategories();
			$this->load->view('category_view',$data);#Fail Page		
		}		
	}
}
