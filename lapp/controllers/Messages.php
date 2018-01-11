<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function LoadMessages()
	{
		$network=''; $plan='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
		if ($this->input->post('plan')) $plan = $this->input->post('plan');
			
		$sql = "SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(plan)='".$this->db->escape_str($plan)."')";
		
		$query = $this->db->query($sql);
		
		$response=$query->result();
		
		echo json_encode($response);
	}#End Of LoadMessages functions
	
	public function DeleteMessage()
	{
		$network=''; $plan=''; $ret='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
		if ($this->input->post('plan')) $plan = $this->input->post('plan');
		
		//Check if record exists
		$sql = "SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(plan)='".$this->db->escape_str($plan)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$this->db->trans_start();
			$this->db->delete('subscriber_messages', array('network' => $network,'plan'=>$plan)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted deleting messages for the service plan ".strtoupper($plan."(".$network.")")." but failed.";
				$ret = "Service Plan Messages Record Could Not Be Deleted.";
			}else
			{
				$Msg="Messages for the service plan ".strtoupper($plan."(".$network.")")."' has been deleted successfully.";				
				$ret = 'OK';
			}
#($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)				
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETED SERVICE PLAN MESSAGES',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete Service Plan Messages Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of DeleteMessage functions
	
	public function EditMessage()
	{
		$network=''; $plan=''; $subscription=''; $renewal=''; $insufficent_balance=''; $ret='';
		$expiry_notice=''; $expiry_notice_24hrs=''; $fallback_notice=''; $upsell_notice='';
		$wrong_keyword=''; $Username=''; $UserFullName=''; $id='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
		if ($this->input->post('plan')) $plan = $this->input->post('plan');
		if ($this->input->post('subscription')) $subscription = $this->input->post('subscription');
		if ($this->input->post('renewal')) $renewal = $this->input->post('renewal');
		if ($this->input->post('insufficent_balance')) $insufficent_balance = $this->input->post('insufficent_balance');
		if ($this->input->post('expiry_notice')) $expiry_notice = $this->input->post('expiry_notice');
		if ($this->input->post('expiry_notice_24hrs')) $expiry_notice_24hrs = $this->input->post('expiry_notice_24hrs');
		if ($this->input->post('fallback_notice')) $fallback_notice = $this->input->post('fallback_notice');
		if ($this->input->post('upsell_notice')) $upsell_notice = $this->input->post('upsell_notice');
		if ($this->input->post('wrong_keyword')) $wrong_keyword = $this->input->post('wrong_keyword');
		if ($this->input->post('Username')) $Username = $this->input->post('Username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM subscriber_messages WHERE id=".$id;
		$query = $this->db->query($sql);
		
		$ret='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
			
			$nt=''; $pl=''; $sb=''; $rn=''; $bal=''; $ex=''; $ex24=''; $fall=''; $up=''; $wr='';
						
			if ($row->network) $nt=$row->network;
			if ($row->plan) $pl=$row->plan;
			if ($row->subscription) $sb=$row->subscription;
			if ($row->renewal) $rn=$row->renewal;
			if ($row->insufficent_balance) $bal=$row->insufficent_balance;
			if ($row->expiry_notice) $ex=$row->expiry_notice;
			if ($row->expiry_notice_24hrs) $ex24=$row->expiry_notice_24hrs;
			if ($row->fallback_notice) $fall=$row->fallback_notice;
			if ($row->upsell_notice) $up=$row->upsell_notice;
			if ($row->wrong_keyword) $wr=$row->wrong_keyword;
			
			$oldValues='Network='.$nt."; Service Plan=".$pl."; Subcription=".$sb."; Renewal=".$rn."; Insufficient Balance=".$bal."; Exipry Notice=".$ex."; 24 Hours Before Expiry=".$ex24."; Fallback=".$fall."; Upsell Notice=".$up."; Wrong Keyword=".$wr;
			
			$NewValues='Network='.$network."; Service Plan=".$plan."; Subcription=".$subscription."; Renewal=".$renewal."; Insufficient Balance=".$insufficent_balance."; Exipry Notice=".$expiry_notice."; 24 Hours Before Expiry=".$expiry_notice_24hrs."; Fallback=".$fallback_notice."; Upsell Notice=".$upsell_notice."; Wrong Keyword=".$wrong_keyword;
						
			$this->db->trans_start();

			$dat=array(
				'network' => $this->db->escape_str($network),
				'plan' => $this->db->escape_str($plan),
				'subscription' => $this->db->escape_str($subscription),
				'renewal' => $this->db->escape_str($renewal),
				'insufficent_balance' => $this->db->escape_str($insufficent_balance),
				'expiry_notice' => $this->db->escape_str($expiry_notice),
				'expiry_notice_24hrs' => $this->db->escape_str($expiry_notice_24hrs),
				'fallback_notice' => $this->db->escape_str($fallback_notice),
				'upsell_notice' => $this->db->escape_str($upsell_notice),
				'wrong_keyword' => $this->db->escape_str($wrong_keyword)
			);	
										
			$this->db->where('id', $id);
			$this->db->update('subscriber_messages', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted editing messages for the service plan ".strtoupper($pl."(".$nt.")")." but failed.";
				$ret = "Service Plan Messages Record Could Not Be Edited.";
			}else
			{
				$Msg="Messages for the service plan ".strtoupper($pl."(".$nt.")")." were edited successfully. Old Values: ".$oldValues."; New Values: ".$NewValues;
				
				$ret = 'OK';
			}
			
			$this->getdata_model->LogDetails($UserFullName,$Msg,$Username,$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDITED SERVICE PLAN MESSAGES',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Edit Service Plan Messages Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of EditMessage functions
	
	public function AddMessage()
	{
		$network=''; $plan=''; $subscription=''; $renewal=''; $insufficent_balance=''; $ret='';
		$expiry_notice=''; $expiry_notice_24hrs=''; $fallback_notice=''; $upsell_notice='';
		$wrong_keyword=''; $Username=''; $UserFullName='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
		if ($this->input->post('plan')) $plan = $this->input->post('plan');
		if ($this->input->post('subscription')) $subscription = $this->input->post('subscription');
		if ($this->input->post('renewal')) $renewal = $this->input->post('renewal');
		if ($this->input->post('insufficent_balance')) $insufficent_balance = $this->input->post('insufficent_balance');
		if ($this->input->post('expiry_notice')) $expiry_notice = $this->input->post('expiry_notice');
		if ($this->input->post('expiry_notice_24hrs')) $expiry_notice_24hrs = $this->input->post('expiry_notice_24hrs');
		if ($this->input->post('fallback_notice')) $fallback_notice = $this->input->post('fallback_notice');
		if ($this->input->post('upsell_notice')) $upsell_notice = $this->input->post('upsell_notice');
		if ($this->input->post('wrong_keyword')) $wrong_keyword = $this->input->post('wrong_keyword');
		if ($this->input->post('Username')) $Username = $this->input->post('Username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
				
		//Check if record exists
		$sql = "SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(plan)='".$this->db->escape_str($plan)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret='Service plan messages exist in the database.';
		}else
		{
			$this->db->trans_start();
									
			$dat=array(
				'network' => $this->db->escape_str($network),
				'plan' => $this->db->escape_str($plan),
				'subscription' => $this->db->escape_str($subscription),
				'renewal' => $this->db->escape_str($renewal),
				'insufficent_balance' => $this->db->escape_str($insufficent_balance),
				'expiry_notice' => $this->db->escape_str($expiry_notice),
				'expiry_notice_24hrs' => $this->db->escape_str($expiry_notice_24hrs),
				'fallback_notice' => $this->db->escape_str($fallback_notice),
				'upsell_notice' => $this->db->escape_str($upsell_notice),
				'wrong_keyword' => $this->db->escape_str($wrong_keyword)
			);	
								
			$this->db->insert('subscriber_messages', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted adding messages for the service plan ".strtoupper($plan."(".$network.")")." but failed.";
				$ret = "Service Plan Messages Record Could Not Be Added.";
			}else
			{
				$Msg="Messages for the service plan ".strtoupper($plan."(".$network.")")." were added successfully.";
				$ret = "OK";
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADDED SERVICE PLAN MESSAGES',$_SESSION['LogID']);
		}
		
		echo $ret;
	}#End Of AddMessage functions
	 	
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
			
//$file = fopen('aaa.txt',"w"); fwrite($file, $_SESSION['password']); fclose($file);				
			$this->load->view('messages_view',$data);
		}else
		{
			redirect('Userhome');
		}
	}
}
