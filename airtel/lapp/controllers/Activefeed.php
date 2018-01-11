<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Activefeed extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
		$this->load->model('activefeed_model');
	 }
	 
	public function LoadRSSJson()
	{
		$ret=$this->activefeed_model->GetRSS();
		
		$data=array();
		
		if (is_array($ret))
		{#[VIEW],KEY,TITLE,DESCRIPTION,SHORT-LINK,LONG-LINK,STATUS,SCHEDULE-ID,FEED-ID,PUBDATE,EXPDATE
			foreach($ret as $row):
#title,longlink,shortlink,description,STATUS,feed_id,filename,insert_date,schedule_id		
				if ($row->expiredate)
				{
					$edt=date('d M Y @ H:i',strtotime($row->expiredate));#M-Short, F-Long
				}
				
				if ($row->pubdate)
				{
					$pdt=date('d M Y @ H:i',strtotime($row->pubdate));#M-Short, F-Long
				}
		
				$tp=array('<img  style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="15" title="View RSS Feed For The Video "'.strtoupper($row->title).'"\'">',$row->filename,$row->title,$row->description,$row->shortlink,$row->longlink,$row->status,$row->schedule_id,$row->feed_id,$pdt,$edt);
				
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadRSSJson functions
	
	public function SetFeed()
	{
		$ret=$this->getdata_model->CheckForActiveFeed();
	
		if ($ret==true)
		{
			#Get Active Feed
			$rt=$this->getdata_model->GetActiveFeedRecord();
	
			if (count($rt)>0)
			{
				foreach($rt as $row):
					if($row->schedule_id) $_SESSION['schedule_id']=$row->schedule_id;
					if($row->feed_id) $_SESSION['feed_id']=$row->feed_id;
					if($row->title) $_SESSION['title']=$row->title;
					if($row->filename) $_SESSION['filename']=$row->filename;
					if($row->description) $_SESSION['description']=$row->description;
					if($row->status) $_SESSION['status']=$row->status;
					if($row->longlink) $_SESSION['longlink']=$row->longlink;
					if($row->shortlink) $_SESSION['shortlink']=$row->shortlink;
					if($row->pubdate) $_SESSION['pubdate']=$row->pubdate;
					if($row->expiredate) $_SESSION['expiredate']=$row->expiredate;
					
					break;
				endforeach;
				
				echo 'OK';
			}else
			{
				echo 'Setting Of Active Feed Was Not Successful No Active Feed Record.';	
			}	
		}else
		{
			echo 'Setting Of Active Feed Was Not Successful';
		}
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
					
			$this->load->view('activefeed_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
