<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

class Blacklist extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	public function VerifyNumbers()
	{
		$network=''; $numbers='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('numbers')) $numbers = trim($this->input->post('numbers'));
		
		$nm=explode(',',$numbers);
		
		$ex='';
				
		if (count($nm) > 0)
		{
			foreach($nm as $ph):
				if ($ph)
				{
					$ph=$this->getdata_model->CleanPhoneNo($ph);
					
					$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($ph)."')";
		
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0 )
					{
						if ($ex=='') $ex=$ph; else $ex .= ', '.$ph;
					}
				}
			endforeach;
			
			if ($ex=='')
			{
				if (count($nm)==1)
				{
					$ret=array('status'=>'OK','msg'=>'The phone number, <b>'.$numbers.'</b>, does not exist in subscription table.','Nos'=>$ex);
				}else
				{
					$ret=array('status'=>'OK','msg'=>'<b>NONE</b> of the phone numbers exists in subscription table.','Nos'=>$ex);
				}
			}else#Exists
			{
				if (count(explode(',',$ex)) == count($nm))
				{
					if (count($nm)==1)
					{
						$ret=array('status'=>'OK','msg'=>'The phone number exists in subscription table.','Nos'=>$ex);
					}else
					{
						$ret=array('status'=>'OK','msg'=>'<b>ALL</b> the phone numbers exist in subscription table.','Nos'=>$ex.'.');
					}					
				}else
				{
					if (count($nm)==1)
					{
						$ret=array('status'=>'OK','msg'=>'The phone number exists in subscription table.','Nos'=>$ex);
					}else
					{
						$ret=array('status'=>'OK','msg'=>'The following phone numbers exist in subscription table: <b>'.$ex.'</b>.','Nos'=>$ex);
					}
				}
			}			
		}else
		{
			$ret=array('status'=>'Failed','msg'=>'Verification was not successful. No msisdn sent.','Nos'=>$ex);
		}
		
		echo json_encode($ret);
	}
			
	public function LoadBlacklistedNumbers()
	{
		$network='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		
		$sql = "SELECT msisdn FROM blacklist WHERE (TRIM(network)='".$network."') ORDER BY msisdn";
		
		$query = $this->db->query($sql);
		
		echo json_encode($query->result());
	}
	
	public function WhitelistNumbers()
	{
		$network=''; $numbers='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('numbers')) $numbers = trim($this->input->post('numbers'));
		
		$nm=explode(',',$numbers);
				
		if (count($nm) > 0)
		{
			foreach($nm as $ph):
				if ($ph)
				{
					$ph=$this->getdata_model->CleanPhoneNo($ph);
					
					$sql = "SELECT * FROM blacklist WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($ph)."')";
		
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0 )
					{
						$this->db->trans_start();	
						$this->db->where(array('network'=>$network,'msisdn'=>$ph));				
						$this->db->delete('blacklist');
						
						$this->db->trans_complete();					
					}
				}
			endforeach;
			
			$ret=array('status'=>'OK','msg'=>'Phone number(s) were whitelisted successfully.');				
		}else
		{
			$ret=array('status'=>'Failed','msg'=>'Whitelisting was not successful.');
		}
		
		echo json_encode($ret);
	}
	
	public function BlacklistNumbers()
	{
		$network=''; $numbers='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('numbers')) $numbers = trim($this->input->post('numbers'));
		
		$nm=explode(',',$numbers);
		$bad=array();
		
		if (count($nm) > 0)
		{
			$cnt=0;
			
			foreach($nm as $ph):
				if ($ph)
				{
					$ph=$this->getdata_model->CleanPhoneNo($ph);
					
					$sql = "SELECT * FROM blacklist WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($ph)."')";
		
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0 )
					{
						$bad[]=$ph;
					}else
					{
						$this->db->trans_start();
	
						$dat=array('network' => $network, 'msisdn' => $ph, 'blacklist_date' => date('Y-m-d H:i:s'));			
						$this->db->insert('blacklist', $dat);
						
						$this->db->trans_complete();
						
						#Delete from subscriptions table
						$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($ph)."')";
		
						$query = $this->db->query($sql);
						
						if ($query->num_rows() > 0 )
						{
							$this->db->trans_start();
		
							$this->db->where(array('network' => $this->db->escape_str($network),'msisdn' => $this->db->escape_str($ph)));
							$this->db->delete('subscriptions');
							
							$this->db->trans_complete();
							
							################ Update Daily Revenue Report ##################
							$cancelled='0';
								
							#CANCELLED							
							if (strtolower($network)=='airtel')
							{
								$sql = "SELECT * FROM airtel_daily_revenue WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".date('Y-m-d')."'";
							}elseif (strtolower($network)=='mtn')
							{
								
							}elseif (strtolower($network)=='etisalat')
							{
								
							}elseif (strtolower($network)=='wifi')
							{
								$sql = "SELECT * FROM daily_revenue WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".date('Y-m-d')."'";
							}
							
							$query = $this->db->query($sql);
							
							if ($query->num_rows() > 0)
							{
								$row = $query->row();
							
								if ($row->cancelled) $cancelled=$row->cancelled;
								
								$cancelled += 1;
							
								$this->db->trans_start();
				
								$dat=array('cancelled' => $this->db->escape_str($cancelled));
								
								$where = "DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".date('Y-m-d')."'";
								$this->db->where($where);
																								
								if (strtolower($network)=='airtel')
								{
									$this->db->update('airtel_daily_revenue', $dat); 
								}elseif (strtolower($network)=='mtn')
								{
									
								}elseif (strtolower($network)=='etisalat')
								{
									
								}elseif (strtolower($network)=='wifi')
								{
									$this->db->update('daily_revenue', $dat);
								}
								
								$this->db->trans_complete();
							}else
							{
								$cancelled=1;
								$this->db->trans_start();
				
								$dat=array(
									'cancelled' => $this->db->escape_str($cancelled),
									'subscribe_date' => date('Y-m-d')
								);

								if (strtolower($network)=='airtel')
								{
									$this->db->insert('airtel_daily_revenue', $dat); 
								}elseif (strtolower($network)=='mtn')
								{
									
								}elseif (strtolower($network)=='etisalat')
								{
									
								}elseif (strtolower($network)=='wifi')
								{
									$this->db->insert('daily_revenue', $dat); 
								}
								
								$this->db->trans_complete();
							}
						}
						
						$cnt++;
					}
				}
			endforeach;
			
			if (count($bad) >0)
			{
				$s='';
				
				foreach($bad as $p):
					if ($p)
					{
						if (trim($s)=='') $s=$p; else $s .= ', '.$p;
					}
				endforeach;
				
				if ($cnt > 0)
				{
					$ret=array('status'=>'OK','msg'=>'Phone number(s) blacklisted successfully. The following numbers were, however, not blacklisted because they have already been blacklisted: '.$s);	
				}else
				{
					$ret=array('status'=>'Failed','msg'=>'Blacklisting was not successful. The following numbers were not blacklisted because they have already been blacklisted: <b class="redtext">'.$s.'</b>');	
				}				
			}else
			{
				if ($cnt > 0)
				{
					$ret=array('status'=>'OK','msg'=>'Phone number(s) blacklisted successfully.');	
				}else
				{
					$ret=array('status'=>'Failed','msg'=>'Blacklisting was not successful.');	
				}				
			}			
		}else
		{
			$ret=array('status'=>'Failed','msg'=>'Blacklisting was not successful. No phone number entered.');
		}
		
		echo json_encode($ret);
	}
		
	public function index()
	{#$file = fopen('aaa.txt',"w"); fwrite($file,'Almost'); fclose($file);
		#$file = fopen('aaa.txt',"w"); fwrite($file, __DIR__); fclose($file);
		if ($_SESSION['username'])
		{
			$data['username']=$_SESSION['username'];
			
			$data['AddItem'] = '0'; $data['EditItem'] = '0'; $data['DeleteItem'] = '0';
			$data['Upload_Video'] = '0'; $data['CreateUser'] = '0'; $data['SetParameters'] = '0';
			$data['ViewLogReport'] = '0'; $data['ClearLogFiles'] = '0'; $data['ViewReports'] = '0';
			$data['CreatePublisher'] = '0'; $data['CreateComedian'] = '0'; $data['CreateCategory'] = '0';
			$data['ApproveVideo'] = '0'; $data['ApproveComment'] = '0'; $data['AddBanners'] = '0';
			$data['ModifyStaticPage'] = '0'; $data['AddArticlesToBlog'] = '0';
			$data['CheckDailyReports'] = '0'; $data['AddMobileOperator'] = '0'; $data['CreateEvents'] = '0';
					
			if ($_SESSION['username']) $data['username'] = $_SESSION['username'];
			if ($_SESSION['firstname']) $data['firstname'] = $_SESSION['firstname'];
			if ($_SESSION['lastname']) $data['lastname'] = $_SESSION['lastname'];
			if ($_SESSION['UserFullName']) $data['UserFullName'] = $_SESSION['UserFullName'];
			if ($_SESSION['pwd']) $data['pwd'] = $_SESSION['pwd'];
			if ($_SESSION['phone']) $data['phone'] = $_SESSION['phone'];
			if ($_SESSION['email']) $data['email'] = $_SESSION['email'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['accountstatus']) $data['accountstatus'] = $_SESSION['accountstatus'];
			if ($_SESSION['role']) $data['role'] = $_SESSION['role'];
			
			
			#################################
			#Permissions
			if ($_SESSION['AddItem']==1) $data['AddItem'] = $_SESSION['AddItem'];
			if ($_SESSION['EditItem']==1) $data['EditItem'] = $_SESSION['EditItem'];
			if ($_SESSION['DeleteItem']== 1) $data['DeleteItem'] = $_SESSION['DeleteItem'];
			if ($_SESSION['Upload_Video']== 1) $data['Upload_Video'] = $_SESSION['Upload_Video'];
			if ($_SESSION['CreateUser']==1) $data['CreateUser'] = $_SESSION['CreateUser'];
			if ($_SESSION['SetParameters']== 1) $data['SetParameters'] = $_SESSION['SetParameters'];
			if ($_SESSION['ViewLogReport']== 1) $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
			if ($_SESSION['ClearLogFiles']==1) $data['ClearLogFiles'] = $_SESSION['ClearLogFiles'];
			if ($_SESSION['ViewReports']==1) $data['ViewReports'] = $_SESSION['ViewReports'];
			if ($_SESSION['CreatePublisher']==1) $data['CreatePublisher'] = $_SESSION['CreatePublisher'];
			if ($_SESSION['CreateComedian']== 1) $data['CreateComedian'] = $_SESSION['CreateComedian'];
			if ($_SESSION['CreateCategory']== 1) $data['CreateCategory'] = $_SESSION['CreateCategory'];
			if ($_SESSION['ApproveVideo']==1) $data['ApproveVideo'] = $_SESSION['ApproveVideo'];
			if ($_SESSION['ApproveComment']==1) $data['ApproveComment'] = $_SESSION['ApproveComment'];
			if ($_SESSION['AddBanners']== 1) $data['AddBanners'] = $_SESSION['AddBanners'];
			if ($_SESSION['ModifyStaticPage']== 1) $data['ModifyStaticPage'] = $_SESSION['ModifyStaticPage'];
			if ($_SESSION['AddArticlesToBlog']== 1) $data['AddArticlesToBlog'] = $_SESSION['AddArticlesToBlog'];
			if ($_SESSION['CheckDailyReports']== 1) $data['CheckDailyReports'] = $_SESSION['CheckDailyReports'];
			if ($_SESSION['AddMobileOperator']== 1) $data['AddMobileOperator'] = $_SESSION['AddMobileOperator'];
			if ($_SESSION['CreateEvents']== 1) $data['CreateEvents'] = $_SESSION['CreateEvents'];
			###############################
			
			if ($_SESSION['companyname']) $data['companyname'] = $_SESSION['companyname'];
			if ($_SESSION['companyemail']) $data['companyemail'] = $_SESSION['companyemail'];
			if ($_SESSION['companyphone']) $data['companyphone'] = $_SESSION['companyphone'];
			if ($_SESSION['website']) $data['website'] = $_SESSION['website'];
			if ($_SESSION['companylogo']) $data['companylogo'] = $_SESSION['companylogo'];
			if ($_SESSION['RefreshDuration']) $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
			if ($_SESSION['default_network']) $data['default_network'] = $_SESSION['default_network'];
			if ($_SESSION['no_of_videos_per_day']) $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];

			$data['VideoCategories'] = $this->getdata_model->GetVideoCategories();

			$this->load->view('blacklist_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
