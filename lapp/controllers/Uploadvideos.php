<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require_once('getID3/getid3/getid3.php');
require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class Uploadvideos extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
		$this->load->model('Videos_model');
	 }
	   
      
	function CreateThumbnail($fileName,$width) 
    {
		#Get Image Width And Height
		$image_info = getimagesize($fileName);
		$imgWidth = $image_info[0];
		$imgHeight = $image_info[1];
		
		$ratio = $width / $imgWidth;
		
		$newHeight=$imgHeight * $ratio;
		$newWidth=$width;
		
        $this->load->library('image_lib');
        $config['image_library'] = 'gd2';
        $config['source_image'] = $fileName;       
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
		$config['thumb_marker'] = '';
		$config['width'] = $newWidth;
        $config['height'] = $newHeight;
                    
        $this->image_lib->initialize($config);
		
		return $this->image_lib->resize()     ;
    }
		
	public function LoadVideoDetailsJson()
	{
		$category=''; $files=''; $publisher_email='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('files')) $files = trim($this->input->post('files'));
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		
		$response=$this->Videos_model->GetVideoDetail($category,$files,$publisher_email);
		
		$data=array();
		
		if (is_array($response))
		{
			$sn=-1;
			
			foreach($response as $row) 
			{
				$title=''; $category=''; $domainname='';
				$filename=''; $status=''; $description=''; $preview_url=''; $play_status='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['category']) $category=$row['category'];						
				if ($row['filename']) $filename=$row['filename'];
				if ($row['video_status']) $status=$row['video_status'];
				if ($row['description']) $description=$row['description'];
				if ($row['domain_name']) $domainname=$row['domain_name'];
				if ($row['play_status']) $play_status=$row['play_status'];
				
				if ($play_status==1)
				{
					$play_status='<span style="color:#256709;">Active</span>';
				}else
				{
					$play_status='<span style="color:#B32D19;">Not Active</span>';
				}
				
				#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
				if ($domainname && $filename) $preview_url='https://'.$domainname.'/'.$filename;
				
				$sn++;
				
				if ($title)
				{
					#$view='<img onClick="GetRow(\''.$sn.'\');" style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Click To Select '.strtoupper($title).'">';	
					
					$view='<i onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square" title="Click To Select '.strtoupper($title).'"></i>';
					
				}else
				{
					#$view='<img onClick="GetRow(\''.$sn.'\');" style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Click To Select Video">';
					
					$view='<i onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square" title="Click To Select Video"></i>';
				}
				
	
	#Category,Title,Description,Filename,Status			

				$tp=array($view,$category,$title,$description,$filename,$play_status);

				$data['data'][]=$tp;
			}
		}
		
		echo json_encode($data);
	}#End Of LoadVideoDetailsJson functions
	
	public function LoadVideosJson()
	{
		$category=''; $publisher_email=''; $InputBucket='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
		if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));
		
		$response=$this->Videos_model->GetVideos($category,$publisher_email);
		
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
	
	public function GetComedians()
	{
		$sql = "SELECT * FROM comedians ORDER BY comedian";
		
		$query = $this->db->query($sql);
		
		echo json_encode($query->result());
	}
	
	public function AddVideos()
	{
		$category=''; $bucket=''; $comedian='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('bucket')) $bucket = trim($this->input->post('bucket'));
		if ($this->input->post('comedian')) $comedian = trim($this->input->post('comedian'));
					
		if (isset($_FILES['video_file'])) $Video = $_FILES['video_file'];
		
		$Vid=''; $Msg='';
		
		if ($Video)
		{
			$video_filename = "temp_videos/".$Video['name'];
			
			#$ext = explode('.', basename($video_filename));
			
			#$fn="companylogo.".array_pop($ext);
			
			$target ="temp_videos/". basename($video_filename);
			
			if(move_uploaded_file($Video['tmp_name'], $target))
			{
				$Vid=basename($video_filename);					
				#$file = fopen('aaa.txt',"a"); fwrite($file, $video_filename."\n".$vid."\n\n"); fclose($file);
				
				$ret='OK';
			}else
			{
				$ret='Video Upload Was Not Successful. Cannot Upload!';
			}
		}else
		{
			$Vid='';
			
			$ret='Video Upload Was Not Successful. No Video!';
		}
		
		echo $ret;
	}
	
	public function UploadVideo()
	{
		$category=''; $bucket=''; $thumbucket=''; $publisher_email=''; $PublisherName=''; $comedian='';
		
		// 'images' refers to your file input name attribute
		if (empty($_FILES['txtVideos'])) {
			echo json_encode(['error'=>'No files found for upload.']); 
			// or you can throw an exception 
			return; // terminate
		}
		
		// get the files posted
		$videos = $_FILES['txtVideos'];
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('bucket')) $bucket = trim($this->input->post('bucket'));
		if ($this->input->post('thumbucket')) $thumbucket = trim($this->input->post('thumbucket'));
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		if ($this->input->post('PublisherName')) $PublisherName = trim($this->input->post('PublisherName'));
		if ($this->input->post('comedian')) $comedian = trim($this->input->post('comedian'));
		
		if ($comedian)
		{
			if (strtolower($comedian) == 'undefined') $comedian='';
		}
		
		$success = null;// a flag to see if everything is ok
		$paths= [];// file paths to store
		$thumbpaths= [];// file paths to store
		$uploadfiles= '';// thumbnail paths to store
		$filenames = $videos['name'];// get file names
		
		$cnt=0;
		
		// loop and process files
		for($i=0; $i < count($filenames); $i++):
			$videoname=str_replace(' ','_',basename($videos['name'][$i]));	
			$target ="temp_videos/".$videoname;
							
			if(move_uploaded_file($videos['tmp_name'][$i], $target)) 
			{
				$thumbname='';
				#Create Thumbnail
				$path_parts = pathinfo($videoname);
				$filenoext=$path_parts['filename'];			
				$thumbnail='temp_videos/'.$filenoext.'.jpg';
				$thumbname=$filenoext.'.jpg';
				
				
				#$cmd="ffmpeg -i $target -deinterlace -an -ss 1 -t 00:00:05 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1";
				
				########## Concatenate videos
				$intro=base_url()."asset/Laffhub_Intro_final.mp4";
				
				#$cmd_intro="ffmpeg -i $intro -c copy -bsf:v h264_mp4toannexb -f mpegts intermediate1.ts";
				#$cmd_target="ffmpeg -i $target -c copy -bsf:v h264_mp4toannexb -f mpegts intermediate2.ts";
				
				#$cmd='ffmpeg -i "concat:intermediate1.ts|intermediate2.ts|intermediate1.ts" -c copy -bsf:a aac_adtstoasc $target';
				
				$output ="temp_videos/output.mp4";
				
				#$cmd_cat='ffmpeg -i '.$intro.' -c copy -bsf:v h264_mp4toannexb -f mpegts temp_videos/intermediate1.ts & ffmpeg -i '.$target.' -c copy -bsf:v h264_mp4toannexb -f mpegts temp_videos/intermediate2.ts & ffmpeg -i "concat:temp_videos/intermediate1.ts|temp_videos/intermediate2.ts|temp_videosintermediate1.ts" -c copy -bsf:a aac_adtstoasc '.$output;
											
				$cmd_cat="ffmpeg -i $intro -c copy -bsf:v h264_mp4toannexb -f mpegts temp_videos/intermediate1.ts";				
				shell_exec($cmd_cat);
				
				$cmd_cat="ffmpeg -i $target -c copy -bsf:v h264_mp4toannexb -f mpegts temp_videos/intermediate2.ts";
				shell_exec($cmd_cat);
				
				$cmd_cat="ffmpeg -i \"concat:temp_videos/intermediate1.ts|temp_videos/intermediate2.ts|temp_videos/intermediate1.ts\" -c copy -bsf:a aac_adtstoasc ".$output;
				shell_exec($cmd_cat);
				########## End concatenation
				
				#Create input.txt
				#$x=str_replace('.','',str_replace(' ','',microtime()));
				#$textfile=substr($x,1).'.txt';
								
				#$file = fopen($textfile,"w");
				#fwrite($file, $intro."\n".base_url()."temp_videos/".$videoname."\n".$intro);
				#fclose($file);
				
				#$cmd_cat="ffmpeg -f concat -safe 0 -i mylist.txt -c copy $output";
				#$cmd_cat="ffmpeg -f concat -i $textfile -fflags +genpts $output";
				#$cmd="ffmpeg -i "concat:intermediate1.ts|intermediate2.ts|intermediate[...].ts|intermediate[n-part].ts" -c copy -bsf:a aac_adtstoasc finalVideo.mp4";
				
				$tflag=false;
			
				$merged=false;
				
				if (file_exists($output))
				{
					$cmd="ffmpeg -i $target -ss 00:00:03.75 -vf scale=400:-1 -q:v 4 -y -strict experimental -threads 1 -an -r 1 -vframes 1 $thumbnail 2>&1";	
					
					if (shell_exec($cmd))
					{
						$tflag=true;
						
						$thumbpaths[]=$thumbnail;
						
						/*	
						#Reduce size to 150
						$ret=$this->CreateThumbnail($thumbnail,200);
						
						if ($ret)
						{
							$tflag=true;
						
							$thumbpaths[]=$thumbnail;
						}
						*/
					}
					
					$getID3 = new getID3;
					$fileinfo = $getID3->analyze($output);				
					$duration=$fileinfo['playtime_string'];	
					
					$success = true;
					$paths[] = $target;
	
					#Upload
					$r=$this->UploadToS3($videoname,$category,$output,$bucket,$thumbnail,$thumbname,$thumbucket);
					
					###########Insert/Update Video#######################				
					$sql = "SELECT * FROM videos WHERE (TRIM(filename)='".$this->db->escape_str($videoname)."') AND (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(publisher_email)='".$this->db->escape_str($publisher_email)."')";
					$query = $this->db->query($sql);	
					
					$dt=date('Y-m-d H:i:s');
					$cd=str_replace('.','',uniqid('',TRUE));
					
					$this->db->trans_start();
					
					if ($query->num_rows() > 0 )#Update
					{
						$dat=array(
							'category' => $this->db->escape_str($category),
							'size' => $this->db->escape_str($fileinfo['filesize']),#filesize($target)
							'duration' => $this->db->escape_str($duration),
							'date_created' => $dt,
							'filename' => $this->db->escape_str($videoname),
							'comedian' => $this->db->escape_str($comedian),
							'video_status' => 'Uploaded',
							'update_date' => $dt,
							'thumbnail' => $this->db->escape_str($thumbname)
						);
						
						$this->db->where(array('filename'=>$videoname,'category'=>$category,'publisher_email'=>$publisher_email));
						$this->db->update('videos', $dat);
					}else#Insert
					{
						$dat=array(
							'publisher_email' => $this->db->escape_str($publisher_email),
							'category' => $this->db->escape_str($category),
							'size' => $this->db->escape_str($fileinfo['filesize']),#filesize($target)
							'duration' => $this->db->escape_str($duration),
							'comedian' => $this->db->escape_str($comedian),
							'date_created' => $dt,
							'filename' => $this->db->escape_str($videoname),
							'video_status' => 'Uploaded',
							'video_code' => $cd,
							'insert_date' => $dt,
							'thumbnail' => $this->db->escape_str($thumbname)
						);
						
						$this->db->insert('videos', $dat);
					}
					
					$this->db->trans_complete();
					
					if (trim($uploadfiles)=='') $uploadfiles=$videoname; else $uploadfiles .= '^'.$videoname;
					
					if (file_exists('temp_videos/intermediate1.ts')) unlink('temp_videos/intermediate1.ts');
					if (file_exists('temp_videos/intermediate2.ts'))unlink('temp_videos/intermediate2.ts');
					if (file_exists($output))unlink($output);
										
					$cnt++;
				}else
				{
					$success = false;
					break;
				}
			} else 
			{
				$success = false;
				break;
			}
		endfor;
				
		if ($success === true)
		{
			$output = array('uploaded' => 'OK','FileCount'=>$cnt,'UploadFiles'=>$uploadfiles );
			
			if (count($paths)>0)
			{
				foreach ($paths as $file) {	unlink($file);	}
			}
			
			if (count($thumbpaths)>0)
			{
				foreach ($thumbpaths as $tf) {	unlink($tf);	}
			}
		}elseif ($success === false) 
		{
			$output = ['error'=>'Error while uploading videos. Contact the system administrator.'];
			// delete any uploaded files
			if (count($paths)>0)
			{
				foreach ($paths as $file) {	unlink($file);	}
			}
			
			if (count($thumbpaths)>0)
			{
				foreach ($thumbpaths as $tf) {	unlink($tf);	}
			}
		} else 
		{
			$output = ['error'=>'No videos were processed.'];
		}
		
		
		$this->getdata_model->LogDetails($PublisherName,$Msg,$publisher_email,date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'UPLOADED VIDEO TO S3',$_SESSION['LogID']);			
	
		// return a json encoded response for plugin to process successfully
		echo json_encode($output);
	}#End UploadVideo
	
	function UploadToS3($filename,$category,$filepath,$bucket,$thumbnail,$thumbname,$thumbucket)
	{
		#$file = fopen('aaa.txt',"w"); fwrite($file, $thumbucket."\n".$category.'/'.$thumbname."\n".$thumbnail); fclose($file);
		
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
			$s3->putObject(array(
				 'Bucket'=>$bucket,
				 'Key' =>  $category.'/'.$filename,#Object Key
				 'SourceFile' => $filepath,
				 'StorageClass' => 'STANDARD',
				 'ACL'          => 'public-read',
				 'Metadata'     => array(    
					'x-amz-meta-Category' => $category
				)
			));
			
			if ($thumbname)
			{
				$s3->putObject(array(
					 'Bucket'=>$thumbucket,
					 'Key' =>  $category.'/'.$thumbname,#Object Key
					 'SourceFile' => $thumbnail,
					 'StorageClass' => 'STANDARD',
					 'ACL'          => 'public-read'
				));
			}
			
			return 'OK';
	
		} catch (S3Exception $e) {
			 // Catch an S3 specific exception.
			return $e->getMessage();
		}
	}
	
	public function UpdateVideo()
	{
		$category=''; $filename=''; $video_title=''; $description=''; $publisher_email=''; $publisher_name='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('filename')) $filename = trim($this->input->post('filename'));
		if ($this->input->post('video_title')) $video_title = trim($this->input->post('video_title'));
		if ($this->input->post('description')) $description = trim($this->input->post('description'));
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		if ($this->input->post('publisher_name')) $publisher_name = trim($this->input->post('publisher_name'));
		
		#Update Video
		$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(filename)='".$this->db->escape_str($filename)."') AND (TRIM(publisher_email)='".$this->db->escape_str($publisher_email)."')";
		$query = $this->db->query($sql);	
		
		$Msg='';
		
		$this->db->trans_start();
					
		if ($query->num_rows() > 0 )#Update
		{			
			$dat=array(
				'video_title' => $this->db->escape_str($video_title),
				'description' => $this->db->escape_str($description)
				);
				
			$this->db->where(array('category'=>$category,'filename'=>$filename,'publisher_email'=>$publisher_email));
			$this->db->update('videos', $dat);
			
			$this->db->trans_complete();
			
			$dirname='temp_videos';
			
			if (is_dir($dirname))
			{
				$dir_handle = opendir($dirname);
				  
			   if ($dir_handle)
			   {
				   while($file = readdir($dir_handle)) 
				   {					   
					  if ($file != "." && $file != "..") 
					  {
						 if (is_file($dirname."/".$file)) unlink($dirname."/".$file);
					  }
				   }   
			   }
			   
			   closedir($dir_handle);			   
			}
						
			$Msg=$publisher_name.'('.$publisher_email.') updated the video with filename "'.strtoupper($filename).' successfully.';
			
			$ret='OK';
		}else
		{
			$Msg=$publisher_name.'('.$publisher_email.') could not update the video with filename "'.strtoupper($filename).'. Video record was not found in the database.';
			
			$ret='Video Update Was Not Successful. Video Record Was Not Found In The Database.';
		}
		
		$this->getdata_model->LogDetails($publisher_name,$Msg,$publisher_email,date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'UPDATED VIDEO DETAILS',$_SESSION['LogID']);			
	
		echo $ret;
	}#End UpdateVideo
	
	
	public function CheckForVideoDetails($filename,$category)
	{
		$sql = "SELECT video_title,description FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(filename)='".$this->db->escape_str($filename)."')";
		$query = $this->db->query($sql);	
							
		if ($query->num_rows() > 0 )#Update
		{
			$video_title=''; $description='';
			
			$row = $query->row();
			
			if ($row->video_title) $video_title = $row->video_title;
			if ($row->description) $description = $row->description;
			
			if ((trim($video_title) != '') || (trim($description) != '')) $ret='YES'; else $ret='NO';
		}else
		{
			$ret='NO';
		}
		
		return $ret;
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

			$this->load->view('uploadvideos_view',$data);
		}else
		{
			redirect("Dashboard");
		}
	}
}
