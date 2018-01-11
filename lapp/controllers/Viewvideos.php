<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

class Viewvideos extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	public function LoadVideosJson()
	{
		$category=''; $publisher_email=''; $InputBucket=''; $status='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('status')) $status = trim($this->input->post('status'));
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
		if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));
		
		if (!$status) $status = '0';
		
		$crit='';
		
		if ($category) $crit=" (TRIM(category)='".$category."')";
		
		if (strtolower($status) <> 'all')
		{			
			if (trim($crit) == '')
			{
				$crit=" (play_status=".$status.")";
			}else
			{
				$crit .= " AND (play_status=".$status.")";
			}
		}
		
		#$sql = "SELECT DATE_FORMAT(date_created,'%d %b %Y %H:%i') AS DateCreated,videos.* FROM videos ";
		$sql = "SELECT DATE_FORMAT(date_created,'%d %b %Y') AS DateCreated,videos.* FROM videos WHERE (TRIM(publisher_email)='".$publisher_email."') ";
		
		if (trim($crit)!='') $sql .= " AND ".$crit;
		
		$sql .= " ORDER BY video_title";
#$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);
		$query = $this->db->query($sql);
		
		$response=$query->result_array();		
		
		$data=array();
		
		if (is_array($response))
		{
			$domainname='';
			
			#Get $domainname
			$sql="SELECT domain_name FROM streaming_domain";

			$query = $this->db->query($sql);
		
			$row = $query->row();
			
			if (isset($row))
			{
				if ($row->domain_name) $domainname = $row->domain_name;
			}
			
			foreach($response as $row) 
			{
				$title='&nbsp;'; $category=''; $size=''; $duration=''; $datecreated=''; $play_status='';
				$encoded=''; $filename=''; $status=''; $description=''; $preview_url=''; $id='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['category']) $category=$row['category'];
				if ($row['size']) $size=$row['size'];
				
				if ($size != '') $size=number_format(floatval($size)/1048576,2)."MB";
				
				if ($row['duration']) $duration=$row['duration'];
							
				if ($row['DateCreated']) $datecreated=$row['DateCreated'];
				
				if ($row['encoded']) $encoded=$row['encoded'];
				if ($row['play_status']) $play_status=$row['play_status'];
				if ($row['filename']) $filename=$row['filename'];
				if ($row['video_status']) $status=$row['video_status'];
				if ($row['description']) $description=$row['description'];
				
				if ($distributed==1) $distributed='Yes'; else $distributed='No';
				
				if ($play_status==1)
				{
					$play_status='<span style="color:#256709;">Active</span>';
				}else
				{
					$play_status='<span style="color:#B32D19;">Not Active</span>';
				}
				
				#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
				#if ($domainname && $filename && (trim(strtolower($status))=='encoded'))
				#https://s3-us-west-2.amazonaws.com/laffhub-videos/Comedy/Alzheimers_Risk_081711.mp4
				#https://s3-us-west-2.amazonaws.com/laffhub-thumbs/Comedy/Alzheimers_Risk_081711.jpg
				
				if ($domainname && $filename && (trim(strtolower($status))=='encoded'))
				{
					#$filename='bigbuck.mp4';
					$arr = explode('.', basename($filename));
					$ext=array_pop($arr);				
					$fn=str_replace('.'.$ext,'',basename($filename));
					#$fn.'_360p.'.$ext
					
					
					
					$preview_url='https://'.$domainname.'/'.$category.'/'.$fn;
					$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$category.'/'.$fn.'.jpg';
					
					$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo_Encoded(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
				}else
				{
					if ($category and $filename and $InputBucket)
					{
						#$filename='bigbuck.mp4';
						$arr = explode('.', basename($filename));
						$ext=array_pop($arr);				
						$fn=str_replace('.'.$ext,'',basename($filename));
						#$fn.'_360p.'.$ext
						
						
						
						#$preview_url='https://'.$domainname.'/'.$category.'/'.$fn;
						$preview_url='https://s3-us-west-2.amazonaws.com/'.$InputBucket.'/'.$category.'/'.$fn;
						$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$category.'/'.$fn.'.jpg';
			
	#$file = fopen('aaa.txt',"a"); fwrite($file, $preview_url."\n");fclose($file);
			
						$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
					}else
					{
						$video='';
					}
				}
				
				
				
				if ($row['id']) $id=$row['id'];	

	#Title,Category,Size,Duration,Date_Created,Domain_Name,Filename,Status,[Preview]				

				$tp=array($title,$category,$size,$duration,$datecreated,$filename,$play_status,$video);

				$data['data'][]=$tp;
			}
		}
		
		echo json_encode($data);
	}#End Of LoadVideosJson functions
			
	public function GetCategories()
	{
		$sql = "SELECT * FROM video_categories ORDER BY category";
		
		$query = $this->db->query($sql);
		
		echo json_encode($query->result());
	}
		
	public function index()
	{#$file = fopen('aaa.txt',"w"); fwrite($file,'Almost'); fclose($file);
		#$file = fopen('aaa.txt',"w"); fwrite($file, __DIR__); fclose($file);
		if ($_SESSION['publisher_email'])
		{
			$data['publisher_email']=$_SESSION['publisher_email'];
			
			$data['publisher_status'] = '0';
					
			if ($_SESSION['publisher_name']) $data['publisher_name'] = $_SESSION['publisher_name'];
			if ($_SESSION['publisher_phone']) $data['publisher_phone'] = $_SESSION['publisher_phone'];
			if ($_SESSION['publisher_pwd']) $data['publisher_pwd'] = $_SESSION['publisher_pwd'];			
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['publisher_status']) $data['publisher_status'] = $_SESSION['publisher_status'];
						
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

			$this->load->view('viewvideos_view',$data);
		}else
		{
			redirect("Dashboard");
		}
	}
}
