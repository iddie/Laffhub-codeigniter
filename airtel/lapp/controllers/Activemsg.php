<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Activemsg extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
		$this->load->model('activemsg_model');
	 }	
	
	public function LoadMsgJson()
	{
				
		$ret=$this->activemsg_model->GetMessages();
		
		$data=array();
		
		if (is_array($ret))
		{	//SN,msg,msg_id		
			foreach($ret as $row):
				$sta='';
				
				if (trim(strtolower($row->status))=='running')
				{
					$sta='<font color="#136A09">'.$row->status.'</font>';
				}else
				{
					$sta='<font color="#ff0000">'.$row->status.'</font>';
				}
				
				$tp=array(stripslashes($row->msg), $row->msg_id,$sta);
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadMsgJson functions
	
	public function SetActiveMsg()
	{
		$ret=$this->getdata_model->CheckForActiveMsg();
	
		if ($ret==true)
		{
			$rows = array("Status"=>"OK","Msg"=>"Active Health Message/Tip Was Set Successfully.");
			$Msg='Active Health Message/Tip Was Set Successfully.';	
		}else
		{
			$Msg='Setting Of Active Health Message/Tip Was Not Successful.';
			$rows = array("Status"=>"ERROR","Msg"=>"Setting Of Active Health Message/Tip Was Not Successful.");
		}
		
		$this->getdata_model->LogDetails($_SESSION['fullname'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'SET CURRENT NATURAL HEALTH TIP',$_SESSION['LogID']);
		
		echo json_encode($rows);
	}
	
	public function index()
	{		
		if ($_SESSION['username'])
		{
			$data['username']=$_SESSION['username'];
									
			$data['Upload_Video']='0'; $data['CreateUser']='0'; $data['SetParameters']='0';
			$data['accountstatus'] = '0'; $data['ViewLogReport']='0';
						
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
			if ($_SESSION['Upload_Video']==1) $data['Upload_Video'] = $_SESSION['Upload_Video'];
			if ($_SESSION['CreateUser']==1) $data['CreateUser'] = $_SESSION['CreateUser'];
			if ($_SESSION['SetParameters']== 1) $data['SetParameters'] = $_SESSION['SetParameters'];
			if ($_SESSION['ViewLogReport']== 1) $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
			
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
			
			$data['OldPassword']=$_SESSION['pwd'];
			
			$data['ActiveMsg']=$this->getdata_model->GetCurrentMsg();
			$data['ActiveMsgID']=$this->getdata_model->GetMsgID($data['ActiveMsg']);
			$data['ActiveStatus']=$this->getdata_model->GetMsgStatus($data['ActiveMsgID']);
			
			$data['ActivePublishDate']=$this->getdata_model->GetActiveMsgPublishDate($data['ActiveMsgID']);
			$data['ActiveExpireDate']=$this->getdata_model->GetActiveMsgExpireDate($data['ActiveMsgID']);

			
			if ($data['ActivePublishDate'])
			{
				$pdt=date('d M Y @ H:i',strtotime($data['ActivePublishDate']));#M-Short, F-Long
				$data['ActivePublishDate']=$pdt;
			}
			
			if ($data['ActiveExpireDate'])
			{
				$edt=date('d M Y @ H:i',strtotime($data['ActiveExpireDate']));
				$data['ActiveExpireDate']=$edt;
			}
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
					
			$this->load->view('activemsg_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
