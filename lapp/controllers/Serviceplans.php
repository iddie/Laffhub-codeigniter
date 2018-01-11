<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

class Serviceplans extends CI_Controller {
		
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	 
	public function LoadPlans()
	{
		$network='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
		
		$sql="SELECT * FROM plans WHERE (TRIM(network)='".$this->db->escape_str($network)."')";
			
		$query = $this->db->query($sql);
		
		$response=$query->result_array();
		
		$data=array();
		
		if (is_array($response))
		{
			$sn=-1;
			
			foreach ($response as $row)
			{
				$Status=''; $sel='';
				
				if ($row['plan_status']) $Status=$row['plan_status'];
				
				if ($Status==1)
				{
					$Status="<font color='#249A47'>Active</font>";
				}else
				{
					$Status="<font color='#BD1111'>Not Active</font>";
				}
				
				$sn++;
				
				$sel='<i onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square" title="Select '.strtoupper($row['plan']).' Plan Record"></i>';
				
				$tp=array($sel,$row['network'],$row['plan'],$row['duration'],$row['no_of_videos'],$Status,$row['plan_status'],$row['id']);
				
				$data['data'][]=$tp;
			}	
		}
		
		echo json_encode($data);
	}
	
	public function DeletePlan()
	{
		$id=''; $ret='';
		
		if ($this->input->post('id')) $id = $this->input->post('id');
		if ($this->input->post('Username')) $Username = trim($this->input->post('Username'));
		if ($this->input->post('UserFullName')) $UserFullName = trim($this->input->post('UserFullName'));
		
		//Check if record exists
		$sql = "SELECT * FROM plans WHERE id=".$id;
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();				
			if ($row->plan) $pl=$row->plan;
			if ($row->network) $nt=$row->network;
			
			$this->db->trans_start();
			$this->db->delete('plans', array('id' => $id)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted deleting service plan ".strtoupper($pl)." for ".strtoupper($nt)." but failed.";
				$ret="Service Plan Record Could Not Be Deleted.";
			}else
			{
				$Msg="Service plan ".strtoupper($pl)." for ".strtoupper($nt)." was deleted successfully by ".strtoupper($UserFullName."(".$Username.")").".";
				
				$ret = 'OK';
			}
#($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)				
			$this->getdata_model->LogDetails($UserFullName,$Msg,$Username,$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETED SERVICE PLAN',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete Service Plan Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of DeletePlan functions
	
	public function EditPlan()
	{
		$network=''; $plan=''; $duration=''; $plan_status=''; $Username=''; $UserFullName='';
		$no_of_videos=''; $ret=''; $id='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('plan')) $plan = trim($this->input->post('plan'));
		if ($this->input->post('duration')) $duration = trim($this->input->post('duration'));
		if ($this->input->post('no_of_videos')) $no_of_videos = trim($this->input->post('no_of_videos'));
		if ($this->input->post('plan_status')) $plan_status = trim($this->input->post('plan_status'));
		
		if ($this->input->post('Username')) $Username = trim($this->input->post('Username'));
		if ($this->input->post('UserFullName')) $UserFullName = trim($this->input->post('UserFullName'));
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		if (!$plan_status) $plan_status='0';
		
		//Check if record exists
		$sql = "SELECT * FROM plans WHERE id=".$id;
		$query = $this->db->query($sql);
		
		$ret='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();				
			
			$nt=''; $pl=''; $du=''; $vid=''; $st='';
			
			if ($row->network) $nt=trim($row->network);
			if ($row->plan) $pl=trim($row->plan);
			if ($row->duration) $du=trim($row->duration);
			if ($row->no_of_videos) $vid=trim($row->no_of_videos);
			if ($row->plan_status) $st=trim($row->plan_status);
			
			if (!$st) $st='0';
			
			$OldValues='Network='.$nt.'; Service Plan='.$pl.'; Plan Duration='.$du.'; No Of Videos To Watch='.$vid.'; Plan Status='.$st;
			$NewValues='Network='.$network.'; Service Plan='.$plan.'; Plan Duration='.$duration.'; No Of Videos To Watch='.$no_of_videos.'; Plan Status='.$plan_status;
			
			$this->db->trans_start();

			$dat=array(
				'network' => $this->db->escape_str($network),
				'plan' => $this->db->escape_str($plan),
				'duration' => $this->db->escape_str($duration),
				'no_of_videos' => $this->db->escape_str($no_of_videos),
				'plan_status' => $this->db->escape_str($plan_status)
				);
										
			$this->db->where('id', $id);
			$this->db->update('plans', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted editing service plan ".strtoupper($pl)." for ".strtoupper($nt)." but failed.";
				$ret="Service Plan Record Could Not Be Edited.";
			}else
			{
				$Msg="Service plan has been edited successfully by ".strtoupper($UserFullName."(".$Username.")").". Old Values: ".$OldValues.". Updated values: ".$NewValues;
								
				$ret = 'OK';
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDITED SERIVCE PLAN',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Edit Service Plan Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of EditPlan functions
	
	public function AddPlan()
	{
		$network=''; $plan=''; $duration=''; $plan_status=''; $Username=''; $UserFullName='';
		$no_of_videos=''; $ret='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('plan')) $plan = trim($this->input->post('plan'));
		if ($this->input->post('duration')) $duration = trim($this->input->post('duration'));
		if ($this->input->post('no_of_videos')) $no_of_videos = trim($this->input->post('no_of_videos'));
		if ($this->input->post('plan_status')) $plan_status = trim($this->input->post('plan_status'));
		
		if ($this->input->post('Username')) $Username = trim($this->input->post('Username'));
		if ($this->input->post('UserFullName')) $UserFullName = trim($this->input->post('UserFullName'));
		
		if (!$plan_status) $plan_status='0';
		
		//Check if record exists
		$sql = "SELECT * FROM plans WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(plan)='".$this->db->escape_str($plan)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret='Service plan <b>'.strtoupper($plan).'</b> for '.strtoupper($network).' exists in the database.';
		}else
		{
			$this->db->trans_start();
									
			$dat=array(
				'network' => $this->db->escape_str($network),
				'plan' => $this->db->escape_str($plan),
				'duration' => $this->db->escape_str($duration),
				'no_of_videos' => $this->db->escape_str($no_of_videos),
				'plan_status' => $this->db->escape_str($plan_status)
				);		
								
			$this->db->insert('plans', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted adding service plan ".strtoupper($plan)." for ".strtoupper($network)." but failed.";
				$ret = "Service Plan Record Could Not Be Added.";
			}else
			{
				$Msg="Service plan ".strtoupper($plan)." for ".strtoupper($network)." was added successfully by ".strtoupper($UserFullName."(".$Username.")").".";
				$ret = "OK";
			}
			
			$this->getdata_model->LogDetails($UserFullName,$Msg,$Username,$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADDED SERVICE PLAN',$_SESSION['LogID']);
		}
		
		echo $ret;
	}#End Of AddPlan functions
			
	public function index()
	{		
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
			if ($_SESSION['google_shortener_api']) $data['google_shortener_api'] = $_SESSION['google_shortener_api'];
			if ($_SESSION['jw_api_key']) $data['jw_api_key'] = $_SESSION['jw_api_key'];
			if ($_SESSION['jw_api_secret']) $data['jw_api_secret'] = $_SESSION['jw_api_secret'];
			if ($_SESSION['jw_player_id']) $data['jw_player_id'] = $_SESSION['jw_player_id'];			
			if ($_SESSION['emergency_emails']) $data['emergency_emails'] = $_SESSION['emergency_emails'];
			if ($_SESSION['emergency_no']) $data['emergency_no'] = $_SESSION['emergency_no'];			
			if ($_SESSION['sms_url']) $data['sms_url'] = $_SESSION['sms_url'];
			if ($_SESSION['sms_username']) $data['sms_username'] = $_SESSION['sms_username'];
			if ($_SESSION['sms_password']) $data['sms_password'] = $_SESSION['sms_password'];			
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];			
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
													
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
			
			$this->load->view('serviceplans_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
