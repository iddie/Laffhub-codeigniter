<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Healthmsg extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
		$this->load->model('healthmsg_model');
	 }
	 
	public function LoadMsgJson()
	{
				
		$ret=$this->healthmsg_model->GetMessages();
		
		$data=array();
		
		if (is_array($ret))
		{#SN,msg,MsgID,MsgStatus
			foreach($ret as $row):
				$sta='';
				
				if($row->MsgStatus==1) $sta='Active'; else $sta='Disabled';
				
				$tp=array($row->SN,$row->msg,$row->MsgID,$sta);
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadMsgJson functions
	
	public function GetMsgID()
	{		
		$Id =$this->getdata_model->GetNextID('health_msgs','msg_id');
		
		if ($Id)
		{
			echo intval($Id);
		}else
		{
			echo '';
		}
	}
	
	public function AddMsg()
	{
		$msg=''; $msg_id=''; $msg_status='';
		
		if ($this->input->post('msg')) $msg = trim($this->input->post('msg'));
		if ($this->input->post('msg_id')) $msg_id = trim($this->input->post('msg_id'));
		if ($this->input->post('msg_status')) $msg_status = trim($this->input->post('msg_status'));
		
		if (!$msg_status) $msg_status='0';
		
		$insertdate=date('Y-m-d');
		
		$rows=NULL;
		
		//Check if record exists
		$sql = "SELECT * FROM health_msgs WHERE UPPER(TRIM(msg))='".strtoupper($this->db->escape_str($msg))."'";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$rows = array("Status"=>"ERROR","Msg"=>'Health message "'.strtoupper($msg).'" exists in the database.');
		}else
		{
			$this->db->trans_start();
									
			$dat=array(
				'msg' => $this->db->escape_str($msg),
				'msg_id' => $this->db->escape_str($msg_id),
				'msg_status'=>$this->db->escape_str($msg_status),
				'status'=>'Not Running',
				'insertdate' =>$insertdate
			);
													
			$this->db->insert('health_msgs', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted adding health message ".strtoupper($msg)." but failed.";
				$rows = array("Status"=>"ERROR","Msg"=>"Health Message Record Could Not Be Added.");
			}else
			{
				$Msg='Health message "'.strtoupper($msg).'" with ID "'.$msg_id.'" was added successfully.';
				$rows = array("Status"=>"OK","Msg"=>"Health Message Record Has Been Added Successfully.");
			}
			
			$this->getdata_model->LogDetails($_SESSION['fullname'],$Msg,$_SESSION['Username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADD HEALTH MESSAGE',$_SESSION['LogID']);
		}
		
		echo json_encode($rows);
	}#End Of AddMsg functions
	
	public function EditMsg()
	{
		$msg=''; $msg_id=''; $msg_status='';
		
		if ($this->input->post('msg')) $msg = trim($this->input->post('msg'));
		if ($this->input->post('msg_id')) $msg_id = trim($this->input->post('msg_id'));
		if ($this->input->post('msg_status')) $msg_status = trim($this->input->post('msg_status'));
		
		if (!$msg_status) $msg_status='0';
		
		//Check if record exists
		$sql = "SELECT * FROM health_msgs WHERE UPPER(TRIM(msg_id))=".strtoupper($this->db->escape_str($msg_id));
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$OldMsg=''; $OldSta='';
			
			$row = $query->row();
							
			if (isset($row))
			{
				if ($row->msg) $OldMsg=$row->msg;
				if ($row->msg_status) $OldSta=$row->msg_status;
			}
			
			
			$this->db->trans_start();

			$dat=array(
				'msg' => $this->db->escape_str($msg),
				'msg_status'=>$this->db->escape_str($msg_status)
				);	
									
			$this->db->where('msg_id', $msg_id);
			$this->db->update('health_msgs', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted editing health message ".strtoupper($OldMsg)." but failed.";
				$rows[] = "Health Message Record Could Not Be Edited.";
			}else
			{
				$Msg="Health message has been edited successfully. Old Values: Health Message => ".$OldMsg."; Health Message Status => ".$OldSta.". Updated values: Health Message => ".$msg."; Health Message Status => ".$msg_status;
				
				$rows[] = strtoupper($OldMsg)."'s Record Was Edited Successfully.";
			}
			
			$this->getdata_model->LogDetails($_SESSION['fullname'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDIT HEALTH MESSAGE',$_SESSION['LogID']);
		}else
		{
			$rows[]="Could Not Edit Health Message Record. Record Does Not Exist.";
		}
		
		echo json_encode($rows);
	}#End Of EditMsg functions
	
	public function DeleteMsg()
	{
		$id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM health_msgs WHERE msg_id=".$id;
		$query = $this->db->query($sql);
		
		$tg='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();				
			if (isset($row)) $tg=$row->msg;
						
			$this->db->trans_start();
			$this->db->delete('health_msgs', array('msg_id' => $id)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted deleting the health message ".strtoupper($tg)." but failed.";
				$rows[] = "Health Message Record Could Not Be Deleted.";
			}else
			{
				$Msg="Health message '".strtoupper($tg)."' has been deleted successfully.";
				
				$rows[] = strtoupper($tg)."'s Record Was Deleted Successfully.";
			}
			
			$this->getdata_model->LogDetails($_SESSION['fullname'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETE HEALTH MESSAGE',$_SESSION['LogID']);
		}else
		{
			$rows[]="Could Not Delete Health Message Record. Record Does Not Exist.";
		}
		
		echo json_encode($rows);
	}#End Of DeleteMsg functions
	
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
#title,longlink,shortlink,description,status,feed_id,filename,schedule_id,pubdate,expiredate			
			$ret=$this->getdata_model->GetActiveFeedRecord();
			
			if (count($ret)>0)
			{
				foreach($ret as $row):
					if($row->schedule_id) $data['schedule_id']=$row->schedule_id;
					if($row->feed_id) $data['feed_id']=$row->feed_id;
					if($row->title) $data['title']=$row->title;
					if($row->filename) $data['filename']=$row->filename;
					if($row->description) $data['description']=$row->description;
					if($row->status) $data['status']=$row->status;
					if($row->longlink) $data['longlink']=$row->longlink;
					if($row->shortlink) $data['shortlink']=$row->shortlink;
					
					if($row->pubdate)
					{
						$pdt=date('d M Y @ H:i',strtotime($row->pubdate));#M-Short, F-Long
						$data['pubdate']=$pdt;
					}
					
					if($row->expiredate)
					{
						$edt=date('d M Y @ H:i',strtotime($row->expiredate));
						$data['expiredate']=$edt;
					}			
					#break;
				endforeach;
			}
#$sql = "SELECT schedule_id,pubdate,expiredate, rss_feed.* FROM rss_feed WHERE feed_id=".$feed_id." ORDER BY feed_id";			
			$data['OldPassword']=$_SESSION['pwd'];
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
					
			$this->load->view('healthmsg_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
