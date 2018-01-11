<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Comedian extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	 
	 public function GetComedian()
	 {
		$comedian=''; $page=1; $rowcount=20;
		
		if ($this->input->post('page')) $page = $this->input->post('page');
		if ($this->input->post('comedian')) $comedian = $this->input->post('comedian');
	
		if (!$comedian)
		{
			redirect('Comedianslist');
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
				
			if ((!$data['Network']) or (!$data['Phone']))
			{
				redirect('Subscriberhome');
			}else
			{
				#Get comedian videos
				$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(comedian)='".$this->db->escape_str($comedian)."')";
				
				$order = " ORDER BY video_title";
				
				$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
				
				
		#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);		
				$query = $this->db->query($sql);
		
				if ($query->num_rows() == 0 )
				{
					$page=intval($page,10)-1;
					
					if (intval($page,10)<1) $page=1;
					
					$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(comedian)='".$this->db->escape_str($comedian)."')";
				
					$order = " ORDER BY video_title";
				
					$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
					
					$query = $this->db->query($sql);
				}
							
				if ($query->num_rows() > 0 )
				{
					$data['ComedianVideos']=$query->result();
					
					if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
			
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
					$data['Comedian']=urldecode($comedian);
					$data['page']=urldecode($page);
					$data['ComedianId']=$id;
					
					if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
					$this->load->view('comedian_view',$data);
					
				}else
				{
					if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
					
					$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
					$data['Comedian']=urldecode($comedian);
					$data['page']=urldecode($page);
					$data['ComedianId']=$id;
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
					
					$data['ComedianVideos']=$query->result();
					$data['Categories']=$this->getdata_model->GetCategories();
					$data['page']=urldecode($page);
					$data['Comedians']=$this->getdata_model->GetComedians();
					$data['ComedianId']=$id;
					$this->load->view('comedian_view',$data);
				}	
			}
		}		
	 }
	 
	 public function ShowComedian()
	 {
		$id=''; $comedian=''; $page=1; $rowcount=20;
		
		if ($this->uri->segment(1)) $id=$this->uri->segment(3);
		if ($this->input->post('page')) $page = $this->input->post('page');
	
		$sql = "SELECT * FROM comedians WHERE (id=".$this->db->escape_str($id).")";
		
	
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->comedian) $comedian=trim($row->comedian);
			
			if (!$comedian)
			{
				redirect('Comedianslist');
			}else
			{
				$data['Network']=$this->getdata_model->GetNetwork();
				$data['Phone']=$this->getdata_model->GetMSISDN();
					
				if ((!$data['Network']) or (!$data['Phone']))
				{
					redirect('Subscriberhome');
				}else
				{
					#Get comedian videos
					$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(comedian)='".$this->db->escape_str($comedian)."')";
					
					$order = " ORDER BY video_title";
				
					$sql=$this->getdata_model->GetPaginationUrl($page,$rowcount,$sql,$order);
					
					$query = $this->db->query($sql);
		#$file = fopen('aaa.txt',"w"); fwrite($file,$sql); fclose($file);				
					if ($query->num_rows() > 0 )
					{
						$data['ComedianVideos']=$query->result();
						
						if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
				
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
						$data['Comedian']=urldecode($comedian);
						$data['ComedianId']=$id;
						
						if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
						$this->load->view('comedian_view',$data);
						
					}else
					{
						$data['ComedianVideos']=$query->result();
						
						if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
						
						$data['page']=urldecode($page);
						$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
						$data['Comedian']=urldecode($comedian);
						$data['ComedianId']=$id;
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
						$data['Comedians']=$this->getdata_model->GetComedians();
						$data['ComedianId']=$id;
						$this->load->view('comedian_view',$data);
					}	
				}	
			}			
		}else
		{
			redirect('Comedianslist');
		}
	 }
		
	public function index()
	{
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();
			
		if ((!$data['Network']) or (!$data['Phone']))
		{
			redirect('Subscriberhome');
		}else
		{
			
		}
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
		
		if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
		if ($_SESSION['subscriber_email']) $data['subscriber_email'] = $_SESSION['subscriber_email'];
		
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();
			
		$_SESSION['Network']=$data['Network'];
		$_SESSION['Phone']=$data['Phone'];
		
		$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
		$data['Categories']=$this->getdata_model->GetCategories();
		$this->load->view('comedian_view',$data);#Fail Page	
	}
}
