<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Videos extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	public function LoadVideosJson()
	{
		$response=$this->getdata_model->GetVideosFromJWPlayer();
		
		$data=array();
		
		if (is_array($response))
		{
			$jwSecret=''; $jwKey=''; $jwPlayerid='';
		
			$sql="SELECT jw_api_key,jw_api_secret,jw_player_id FROM settings";
				
			$query = $this->db->query($sql);
					
			if ( $query->num_rows()> 0 )  //Build Array of results
			{
				$row = $query->row();
				
				if ($row->jw_api_key) $jwKey = $row->jw_api_key;
				if ($row->jw_api_secret) $jwSecret = $row->jw_api_secret;
				if ($row->jw_player_id) $jwPlayerid = $row->jw_player_id;
			}
					
			
			for ($i=0; $i<sizeof($response['videos']); $i++) 
			{
				$key='&nbsp;'; $title='&nbsp;'; $views='&nbsp;'; $description='&nbsp;'; $date='&nbsp;'; 
				$duration='&nbsp;'; $mediatype='&nbsp;'; $size='&nbsp;'; $status='&nbsp;';
				$sourceformat='&nbsp;'; $video='&nbsp;'; $publish_date=''; $schedule_id=''; $category='$nbsp;';
				
				if ($response['videos'][$i]['key']) $key=$response['videos'][$i]['key'];
				if ($response['videos'][$i]['title']) $title=$response['videos'][$i]['title'];
				if ($response['videos'][$i]['views']) $views=$response['videos'][$i]['views'];
				if ($response['videos'][$i]['description']) $description=$response['videos'][$i]['description'];
				
				if ($response['videos'][$i]['date'])
				{
					$date=date('d M Y',$response['videos'][$i]['date']);
					$publish_date=date('Y-m-d H:i:s',$response['videos'][$i]['date']);
				}
				
				if ($response['videos'][$i]['duration']) $duration=$response['videos'][$i]['duration'];
				if ($response['videos'][$i]['mediatype']) $mediatype=$response['videos'][$i]['mediatype'];
				if ($response['videos'][$i]['size']) $size=$response['videos'][$i]['size'];
				if ($response['videos'][$i]['status']) $status=$response['videos'][$i]['status'];
				if ($response['videos'][$i]['sourceformat']) $sourceformat=$response['videos'][$i]['sourceformat'];
				if ($response['videos'][$i]['custom']['Category'])
				{
					$category=$response['videos'][$i]['custom']['Category'];
					$cat=$response['videos'][$i]['custom']['Category'];
				}
				
				$Msg='';
				
				if (trim(strtolower($mediatype))=='video')
				{
					$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$key.'\',\''.$jwPlayerid.'\',\''.$title.'\')">';
	
	#//VIDEO-KEY,VIDEO-TITLE,DESCRIPTION,DATE PUBLISHED,VIDEO-FORMAT,VIDEO-SIZE,DURATION,VIEWS,LINK,VIDEO-STATUS,CATEGORY				
					
					$tp=array($key,$title,$description,$date,$sourceformat,$category,$size,$duration,$views,$video,$status);
	
					$data['data'][]=$tp;
					
					#Save Record To Videos table
					//Check if record exists
					$sql = "SELECT * FROM videos WHERE (TRIM(video_key)='".$this->db->escape_str($key)."')";
					#$file = fopen('aaa.txt',"a"); fwrite($file, $sql."\n"); fclose($file);
					$query = $this->db->query($sql);
								
					if ($query->num_rows() == 0 )#Insert
					{
						$schedule_id=intval($this->getdata_model->GetNextID('videos','schedule_id'));
						
						$this->db->trans_start();
			
						$dat=array(
							'schedule_id' => $this->db->escape_str($schedule_id),
							'insert_date' => date('Y-m-d H:i:s'),
							'video_key' => $this->db->escape_str(str_replace('&nbsp;','',$key)),
							'video_title' => $this->db->escape_str(str_replace('&nbsp;','',$title)),
							'video_description' => $this->db->escape_str(str_replace('&nbsp;','',$description)),
							'video_status' => $this->db->escape_str(str_replace('&nbsp;','',$status)),
							'publish_date' => $this->db->escape_str(str_replace('&nbsp;','',$publish_date)),					
							'duration' => $this->db->escape_str(str_replace('&nbsp;','',$duration)),
							'mediatype' => $this->db->escape_str(str_replace('&nbsp;','',$mediatype)),
							'size' => $this->db->escape_str(str_replace('&nbsp;','',$size)),
							'category' => $this->db->escape_str(str_replace('&nbsp;','',$cat)),
							'sourceformat' => $this->db->escape_str(str_replace('&nbsp;','',$sourceformat))
						);
						
						$this->db->insert('videos', $dat);
						
						$this->db->trans_complete();
						
						$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' inserted the video record with title ".$title." and key ".$key;
					}else#Update
					{
						#Get Old Values
						$row = $query->row();
						
						$OldKey=''; $OldTitle=''; $OldDesc=''; $OldStatus=''; $OldDate=''; $OldDuration=''; 
						$OldMedia=''; $OldSize=''; $OldFormat=''; $OldCat='';
					
						if (isset($row))
						{				
							if ($row->video_key) $OldKey = $row->video_key;
							if ($row->video_title) $OldTitle = $row->video_title;
							if ($row->video_description) $OldDesc = $row->video_description;
							if ($row->video_status) $OldStatus = $row->video_status;
							if ($row->publish_date) $OldDate = $row->publish_date;
							if ($row->duration) $OldDuration = $row->duration;
							if ($row->mediatype) $OldMedia = $row->mediatype;
							if ($row->size) $OldSize = $row->size;
							if ($row->sourceformat) $OldFormat = $row->sourceformat;		
							if ($row->category) $OldCat = $row->category;			
						}
						
						$BeforeValues="Video Key = ".$OldKey."; Video Title = ".$OldTitle."; Video Description = ".$OldDesc."; Video Status = ".$OldStatus."; Date Published = ".$OldDate."; Video Duration = ".$OldDuration."; Media Type = ".$OldMedia."; Videos Size = ".$OldSize."; Video format = ".$OldFormat."; Video Category = ".$OldCat;				

						$AfterValues="Video Key = ".str_replace('&nbsp;','',$key)."; Video Title = ".str_replace('&nbsp;','',$title)."; Video Description = ".str_replace('&nbsp;','',$description)."; Video Status = ".str_replace('&nbsp;','',$status)."; Date Published = ".str_replace('&nbsp;','',$publish_date)."; Video Duration = ".str_replace('&nbsp;','',$duration)."; Media Type = ".str_replace('&nbsp;','',$mediatype)."; Videos Size = ".str_replace('&nbsp;','',$size)."; Video format = ".str_replace('&nbsp;','',$sourceformat)."; Video Category = ".str_replace('&nbsp;','',$cat);
						
						$this->db->trans_start();
			
						$dat=array(
							'video_key' => $this->db->escape_str(str_replace('&nbsp;','',$key)),
							'video_title' => $this->db->escape_str(str_replace('&nbsp;','',$title)),
							'video_description' => $this->db->escape_str(str_replace('&nbsp;','',$description)),
							'video_status' => $this->db->escape_str(str_replace('&nbsp;','',$status)),
							'publish_date' => $this->db->escape_str(str_replace('&nbsp;','',$publish_date)),					
							'duration' => $this->db->escape_str(str_replace('&nbsp;','',$duration)),
							'mediatype' => $this->db->escape_str(str_replace('&nbsp;','',$mediatype)),
							'size' => $this->db->escape_str(str_replace('&nbsp;','',$size)),
							'category' => $this->db->escape_str(str_replace('&nbsp;','',$cat)),
							'sourceformat' => $this->db->escape_str(str_replace('&nbsp;','',$sourceformat))
						);
						
						$this->db->where('video_key', $key);
						$this->db->update('videos', $dat);
						
						$this->db->trans_complete();
						
						$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' updated the video record with title ".$OldTitle." and key ".$OldKey;
					}
					
					$remote_ip=$_SERVER['REMOTE_ADDR'];
					$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
					$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATE VIDEO',$_SESSION['LogIn']);
				}
			}
		}
		
		echo json_encode($data);
	}#End Of LoadVideosJson functions
	
	public function index()
	{
		//$file = fopen('aaa.txt',"w"); fwrite($file, $_SESSION['email']); fclose($file);
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
			
			$data['VideoCategories'] = $this->getdata_model->GetVideoCategories();
			$data['OldPassword']=$_SESSION['pwd'];
			
			$this->load->view('videos_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
