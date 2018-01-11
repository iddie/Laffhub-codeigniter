<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class Createthumbnails extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	   
	public function LoadVideosJson()
	{
		$category=''; $InputBucket=''; $ThumbBucket='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
		if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));
		
		$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$category."')";
		
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
			
			if ($row->domain_name) $domainname = $row->domain_name;
			
			$sn=-1;
			
			foreach($response as $row) 
			{
				$title='&nbsp;'; $category=''; $filename='';$preview_url='';
				$encoded=''; $thumbnail=''; $status='';  $vcd=''; $id='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['filename']) $filename=$row['filename'];
				if ($row['category']) $category=$row['category'];
				if ($row['video_code']) $vcd=$row['video_code'];
				if ($row['id']) $id=$row['id'];			
				if ($row['encoded']) $encoded=$row['encoded'];
				if ($row['thumbnail']) $thumbnail=$row['thumbnail'];
				if ($row['video_status']) $status=$row['video_status'];
				
				if ($encoded==1) $encoded='Yes'; else $encoded='No';
				if ($distributed==1) $distributed='Yes'; else $distributed='No';
				
#$file = fopen('aaa.txt',"w"); fwrite($file, "Domain Name=".$domainname."\nFilename=".$filename."\nStatus=".$status."\nCategory=".$category."\nInput Bucket=".$InputBucket."\nPublisher=".$publisher);fclose($file);		
		
				if ($domainname && $filename && (trim(strtolower($status))=='encoded'))
				{
					#$filename='bigbuck.mp4';
					$arr = explode('.', basename($filename));
					$ext=array_pop($arr);				
					$fn=str_replace('.'.$ext,'',basename($filename));
										
					$preview_url='https://'.$domainname.'/'.$category.'/'.$fn;
										
					$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$category.'/'.$thumbnail;
	
	#$file = fopen('aaa.txt',"a"); fwrite($file,$preview_url."\n\n".$preview_img); fclose($file);				
					
					#$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo_Encoded(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
					
					$video='<img style="cursor:pointer;" src="'.$preview_img.'" height="50px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo_Encoded(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
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
						$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$category.'/'.$thumbnail;
			
	
			
						#$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
						
						$video='<img style="cursor:pointer;" src="'.$preview_img.'" height="50px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
					}else
					{
						$video='';
					}
				}
				
				#$view='<i onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square"></i>';
				
				
				#$fileurl='https://s3-us-west-2.amazonaws.com/'.$InputBucket.'/'.$category.'/'.$filename;
				#$fileurl='https://'.$domainname.'/'.$category.'/'.$fn;
				$fileurl='http://s3-us-west-2.amazonaws.com/'.$InputBucket.'/'.urlencode($category).'/'.$filename;
				
				$view='<button title="Click to create thumbnail for the video '.strtoupper($title).'" onClick="CreateThumb(\''.$fileurl.'\',\''.$vcd.'\',\''.$id.'\',\''.$preview_img.'\',\''.$category.'\');" style=" font-size:14px; height:50px; cursor:pointer; font-weight:bold;" class="btn btn-primary">Create Thumbnail</button>';

	#[SELECT],Preview,Category,Title,VideoStatus,thumbnailFilename,VideoCode				

				$tp=array($view,$video,$category,$title,$status,$video,$thumbnail,$vcd);

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
	
	public function CreateThumbnail()
	{#imagepath,category,id,videocode,fileurl
		$category=''; $bucket=''; $thumbucket='';
		$imagepath=''; $id=''; $videocode=''; $fileurl='';
				
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('imagepath')) $imagepath = trim($this->input->post('imagepath'));
		if ($this->input->post('id')) $id = trim($this->input->post('id'));
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('fileurl')) $fileurl = trim($this->input->post('fileurl'));		
		if ($this->input->post('bucket')) $bucket = trim($this->input->post('bucket'));
		if ($this->input->post('thumbucket')) $thumbucket = trim($this->input->post('thumbucket'));
			
		$path_parts = pathinfo($fileurl);
		$filename=$path_parts['basename'];
		$filenoext=$path_parts['filename'];
		$thumbnail='temp_videos/'.$filenoext.'.jpg';
		
		#$cmd="ffmpeg -i $fileurl -deinterlace -an -ss 1 -t 00:00:05 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1";
		$cmd="ffmpeg -ss 00:00:30 -i $fileurl -vf scale=200:-1 -q:v 4 -y -strict experimental -threads 1 -an -r 1 -vframes 1 $thumbnail 2>&1";
		
		if (shell_exec($cmd))
		{#$file = fopen('aaa.txt',"w"); fwrite($file, $thumbnail."\n".$fileurl."\n".$category.'/'.$filenoext.'.jpg'); fclose($file);
			
			#Get AWS Keys
			$key=''; $secret='';		
			$sql="SELECT aws_key,aws_secret FROM settings";
				
			$query = $this->db->query($sql);
					
			if ( $query->num_rows()> 0 )  //Build Array of results
			{
				$row = $query->row();
				
				if ($row->aws_key) $key = $row->aws_key;
				if ($row->aws_secret) $secret = $row->aws_secret;
			}
			
			// Instantiate an Amazon S3 client.
			$s3 = new S3Client([
				'version' => '2006-03-01',
				'region'  => 'us-west-2',
				'scheme' => 'http',
				'credentials' => [
					'key'    => $key,
					'secret' => $secret
					]
			]);
			
			try {
				if (file_exists($thumbnail))
				{
					$s3->putObject(array(
						 'Bucket'=>$thumbucket,
						 'Key' =>  $category.'/'.$filenoext.'.jpg',#Object Key
						 'SourceFile' => $thumbnail,
						 'StorageClass' => 'STANDARD',
						 'ACL'          => 'public-read'
					));
				}
				
				$ret='OK';
				
				unlink($thumbnail);
	
			} catch (S3Exception $e) 
			{
				$ret=$e->getMessage();
			}			
		}else
		{
			$ret='Thumbnail Creation Was Not Successful!';
		}
				
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'CREATED VIDEO THUMBNAIL',$_SESSION['LogID']);			
	
		echo $ret;
	}#End CreateThumbnail		
	
	public function index()
	{
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
			$data['OldPassword']=$_SESSION['pwd'];

			$this->load->view('createthumbnails_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
