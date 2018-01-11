<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require_once('getID3/getid3/getid3.php');
require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class Videos extends CI_Controller {
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
		$category=''; $files='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('files')) $files = trim($this->input->post('files'));
		
		$response=$this->Videos_model->GetVideoDetail($category,$files);
		
		$data=array();
		
		if (is_array($response))
		{
			$sn=-1;
			
			foreach($response as $row) 
			{
				$title=''; $category=''; $domainname='';
				$filename=''; $status=''; $description=''; $preview_url='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['category']) $category=$row['category'];						
				if ($row['filename']) $filename=$row['filename'];
				if ($row['video_status']) $status=$row['video_status'];
				if ($row['description']) $description=$row['description'];
				if ($row['domain_name']) $domainname=$row['domain_name'];
				
				#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
				if ($domainname && $filename) $preview_url='https://'.$domainname.'/'.$filename;
				
				$sn++;
				
				$view='<img onClick="GetRow(\''.$sn.'\');" style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Select '.strtoupper($title).')">';
	
	#Category,Title,Description,Filename,Status			

				$tp=array($view,$category,$title,$description,$filename,$status);

				$data['data'][]=$tp;
			}
		}
		
		echo json_encode($data);
	}#End Of LoadVideoDetailsJson functions
	
	public function LoadVideosJson()
	{
		$category='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		
		$response=$this->Videos_model->GetVideos($category);
		
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
				$title='&nbsp;'; $category=''; $size=''; $duration=''; $datecreated=''; 
				$encoded=''; $filename=''; $status=''; $description=''; $preview_url=''; $id='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['category']) $category=$row['category'];
				if ($row['size']) $size=$row['size'];
				
				if ($size != '') $size=number_format(floatval($size)/1048576,2)."MB";
				
				if ($row['duration']) $duration=$row['duration'];
							
				if ($row['DateCreated']) $datecreated=$row['DateCreated'];
				
				if ($row['encoded']) $encoded=$row['encoded'];
				if ($row['filename']) $filename=$row['filename'];
				if ($row['video_status']) $status=$row['video_status'];
				if ($row['description']) $description=$row['description'];
				
				if ($encoded==1) $encoded='Yes'; else $encoded='No';
				if ($distributed==1) $distributed='Yes'; else $distributed='No';
				
				#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
				if ($domainname && $filename && (trim(strtolower($status))=='encoded'))
				{
					#$filename='bigbuck.mp4';
					$arr = explode('.', basename($filename));
					$ext=array_pop($arr);				
					$fn=str_replace('.'.$ext,'',basename($filename));
					#$fn.'_360p.'.$ext
					
					
					
					$preview_url='https://'.$domainname.'/'.$category.'/'.$fn;
					$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\')">';
				}else
				{
					$video='';
				}
				
				if ($row['id']) $id=$row['id'];	

	#Title,Category,Size,Duration,Date_Created,Domain_Name,Encoded,Distributed,Filename,Status,[Preview]				

				$tp=array($title,$category,$size,$duration,$datecreated,$encoded,$filename,$status,$video);

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
	
	public function AddVideos()
	{
		$category=''; $bucket='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('bucket')) $bucket = trim($this->input->post('bucket'));
					
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
		$category=''; $bucket=''; $thumbucket='';
		
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
				#echo $path_parts['dirname'], "<br>";##Directory Name
				#echo $path_parts['basename'], "<br>";#Filename alone
				#$ext=$path_parts['extension'];
				$filenoext=$path_parts['filename'];			
				$thumbnail='temp_videos/'.$filenoext.'.jpg';
				$thumbname=$filenoext.'.jpg';
				
				
				#$cmd="ffmpeg -i $target -deinterlace -an -ss 1 -t 00:00:05 -r 1 -y -vcodec mjpeg -f mjpeg $thumbnail 2>&1";
				$cmd="ffmpeg -i $target -ss 00:00:03.75 -vf scale=200:-1 -q:v 4 -y -strict experimental -threads 1 -an -r 1 -vframes 1 $thumbnail 2>&1";
				
				$tflag=false;
				
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
				$fileinfo = $getID3->analyze($target);				
				$duration=$fileinfo['playtime_string'];
				
				$success = true;
				$paths[] = $target;

				#Upload
				$r=$this->UploadToS3($videoname,$category,$target,$bucket,$thumbnail,$thumbname,$thumbucket);
				
				###########Insert/Update Video#######################				
				$sql = "SELECT * FROM videos WHERE (TRIM(filename)='".$this->db->escape_str($videoname)."') AND (TRIM(category)='".$this->db->escape_str($category)."')";
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
						'video_status' => 'Uploaded',
						'update_date' => $dt,
						'thumbnail' => $this->db->escape_str($thumbname)
					);
					
					$this->db->where(array('filename'=>$videoname,'category'=>$category));
					$this->db->update('videos', $dat);
				}else#Insert
				{
					$dat=array(
						'category' => $this->db->escape_str($category),
						'size' => $this->db->escape_str($fileinfo['filesize']),#filesize($target)
						'duration' => $this->db->escape_str($duration),
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
				
				$cnt++;
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
		
		
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'UPLOADED VIDEO TO S3',$_SESSION['LogID']);			
	
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
		$category=''; $filename=''; $video_title=''; $$description='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('filename')) $filename = trim($this->input->post('filename'));
		if ($this->input->post('video_title')) $video_title = trim($this->input->post('video_title'));
		if ($this->input->post('description')) $description = trim($this->input->post('description'));
		
		#Update Video
		$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(filename)='".$this->db->escape_str($filename)."')";
		$query = $this->db->query($sql);	
		
		$Msg='';
		
		$this->db->trans_start();
					
		if ($query->num_rows() > 0 )#Update
		{			
			$dat=array(
				'video_title' => $this->db->escape_str($video_title),
				'description' => $this->db->escape_str($description)
				);
				
			$this->db->where(array('category'=>$category,'filename'=>$filename));
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
						
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') updated the video with filename "'.strtoupper($filename).' successfully.';
			
			$ret='OK';
		}else
		{
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') could not update the video with filename "'.strtoupper($filename).'. Video record was not found in the database.';
			
			$ret='Video Update Was Not Successful. Video Record Was Not Found In The Database.';
		}
		
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'UPDATED VIDEO DETAILS',$_SESSION['LogID']);			
	
		echo $ret;
	}#End UpdateVideo
	
	public function LoadPipeLines()
	{
		#Get AWS Keys
		$access=''; $secret='';
		
		$sql="SELECT aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
		}
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));

		$result = $client->listPipelines(['Ascending' => 'true']);
		
		$data=array();
#$file = fopen('aaa.txt',"w"); fwrite($file,count($result['Pipelines'])."\n".getcwd()."\cacert.pem"); fclose($file);	
		foreach ($result['Pipelines'] as $row)
		{#[Name],[Id],[InputBucket],[OutputBucket],[Status]
			$Name=''; $Id=''; $InputBucket=''; $OutputBucket=''; $Status='';
			
			if ($row['Name']) $Name=$row['Name'];
			if ($row['Id']) $Id=$row['Id'];						
			if ($row['InputBucket']) $InputBucket=$row['InputBucket'];
			
			#$ContentConfig=$result['Pipeline']['ContentConfig'];
			#$OutputBucket=$result['Pipeline']['ContentConfig']['Bucket'];
		
			if ($row['ContentConfig']['Bucket']) $OutputBucket=$row['ContentConfig']['Bucket'];
			if ($row['Status']) $Status=$row['Status'];

			$tp=array($Name,$Id,$InputBucket,$OutputBucket,$Status);

			$data['data'][]=$tp;
		}
		
		echo json_encode($data);
	}
	
	public function CreatePipeLine()
	{
		$PipelineName='';
		
		if ($this->input->post('PipelineName')) $PipelineName = trim($this->input->post('PipelineName'));
		
		#Get AWS Keys
		$access=''; $secret=''; $input_bucket=''; $output_bucket=''; $thumbs_bucket='';
		
		$sql="SELECT input_bucket,output_bucket,thumbs_bucket,aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
			
			if ($row->input_bucket) $input_bucket = $row->input_bucket;
			if ($row->output_bucket) $output_bucket = $row->output_bucket;
			if ($row->thumbs_bucket) $thumbs_bucket = $row->thumbs_bucket;
		}
		
		$flag=TRUE;
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));
		
		$result = $client->listPipelines(['Ascending' => 'true']);

		$p=''; $Msg='';
		$arrPipes=array();
		
		foreach ($result['Pipelines'] as $pipe) 
		{
			$arrPipes[]=$pipe['Name'];
			if ($p=='') $p=$pipe['Name']; else $p .= '<br>'.$pipe['Name'];
		}
		
		if (count($arrPipes)>0)
		{
			if (in_array($PipelineName,$arrPipes)===TRUE) $flag=TRUE; else $flag=FALSE;
		}else
		{
			$flag=FALSE;
		}
		
		if ($flag==FALSE)
		{
			#arn:aws:iam::991818255754:role/Elastic_Transcoder_Default_Role
			/*$result = $client->createPipeline(array(    
				'Name' => 'IdongTest',// Name is required    
				'InputBucket' => 'healthytips-videos',// InputBucket is required
				'OutputBucket' => 'healthytips-output',
				'Role' => 'arn:aws:iam::991818255754:role/Elastic_Transcoder_Default_Role'// Role is required
			));*/
			
			$result = $client->createPipeline(array(    
			'Name' => $PipelineName,// Name is required    
			'InputBucket' => $input_bucket,// InputBucket is required
			'ContentConfig' => array(
				'Bucket' => $output_bucket,
				'StorageClass' => 'Standard',
				'Permissions' => array(
					array(
						'GranteeType' => 'Email',
						'Grantee' => 'o.dania@efluxz.com',
						'Access' => array('FullControl')
					),
					// ... repeated
				),
			),
			'ThumbnailConfig' => array(
				'Bucket' => $thumbs_bucket,
				'StorageClass' => 'Standard',
				'Permissions' => array(
					array(
						'GranteeType' => 'Email',
						'Grantee' => 'o.dania@efluxz.com',
						'Access' => array('FullControl')
					),
				),
			),
			'Role' => 'arn:aws:iam::991818255754:role/Elastic_Transcoder_Default_Role'// Role is required
		));
			
			#$plname=$result['Pipeline']['Name'];
			#$plID=$result['Pipeline']['Id'];
			
			#echo $plname.' => '.$plID;
			
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') Created A Pipeline Successfully. Pipeline Name Is '.$PipelineName;
			
			$ret='OK';
		}else
		{
			$ret='Could Not Create Pipeline. Pipeliine '.strtoupper($PipelineName).' Has Already Been Created!';
		}
		
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'CREATED AWS PIPELINE',$_SESSION['LogID']);
		
		echo $ret; 
	}
	
	public function GetPipeLineNames()
	{
		#Get AWS Keys
		$access=''; $secret='';
		
		$sql="SELECT aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
		}
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));

		$result = $client->listPipelines(['Ascending' => 'true']);
		
		$rows=array();
			
		foreach ($result['Pipelines'] as $row)
		{
			$Name=''; $Id=''; $Status='';
			
			if ($row['Name']) $Name=$row['Name'];
			if ($row['Id']) $Id=$row['Id'];
			if ($row['Status']) $Status=$row['Status'];

			if (strtoupper(trim($Status))=='ACTIVE') $tp=array('PipelineName'=>$Name,'PipelineID'=>$Id);

			$rows[]=$tp;
		}
		
		echo json_encode($rows);
	}
	
	public function GetJobInputFileNames()
	{
		$PipelineId=''; $Category='';
		
		if ($this->input->post('PipelineId')) $PipelineId = trim($this->input->post('PipelineId'));
		if ($this->input->post('Category')) $Category = trim($this->input->post('Category'));
		
		#Get AWS Keys
		$access=''; $secret='';
		
		$sql="SELECT aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
		}
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));
		
		$result = $client->readPipeline(['Id' => $PipelineId]);

		$InputBucket=$result['Pipeline']['InputBucket'];
				
		$size=''; $key=''; $rows=array();
		
		if ($InputBucket)
		{
			$s3 = new S3Client([
				'version' => '2006-03-01',
				'region'  => 'us-west-2',
				'scheme' => 'http',
				'credentials' => [
					'key'    => $access,
					'secret' => $secret
					]
			]);
			
			$res = $s3->listObjects([
				'Bucket' => $InputBucket,
				'Prefix' => $Category.'/'
			]);
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, 'Bucket => '.  $InputBucket."\n".'Prefix =>'. $Category.'/'); fclose($file);
			
			if (count($res['Contents'])>0)
			{
				foreach ($res['Contents'] as $file) 
				{
					if ($file['Size']) $size=$file['Size']; 
					if ($file['Key']) $key=$file['Key'];
		
					
					if (intval($size)>0)
					{
						$h=$this->CheckForVideoDetails(str_replace($Category.'/','',$key),$Category);
						
						$tp=array('Filename'=>$key,'HasDetails'=>$h);
						$rows[]=$tp;
					}
				}				
			}
		}
		
		echo json_encode($rows);
	}
	
	
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
	
	
	public function LoadJobs()
	{
		if ($this->input->post('pipelineid')) $pipelineid = trim($this->input->post('pipelineid'));
		
		#Get AWS Keys
		$access=''; $secret='';
		
		$sql="SELECT aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
		}
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));

		$result = $client->listJobsByPipeline(['Ascending' => 'true', 'PipelineId' => $pipelineid]);
		
		$data=array();
#$file = fopen('aaa.txt',"w"); fwrite($file,count($result['Pipelines'])."\n".getcwd()."\cacert.pem"); fclose($file);			
		
		if (count($result['Jobs'])>0)
		{
			foreach ($result['Jobs'] as $row)
			{#JobId,PipelineId,InputKey,OutputFiles,JabStatus
				$JobId=''; $PipelineId=''; $InputKey=''; $OutputFiles=''; $Status='';
				
				if ($row['Id']) $JobId=$row['Id'];
				if ($row['PipelineId']) $PipelineId=$row['PipelineId'];									
				if ($row['Input']['Key']) $InputKey=$row['Input']['Key'];
				if ($row['Status']) $Status=$row['Status'];
				
				if (trim(strtolower($Status))=='complete')
				{
					$Status="<font color='#249A47'>".$Status."</font>";
				}else
				{
					$Status="<font color='#BD1111'>".$Status."</font>";
				}
				
				if (count($row['Outputs']) > 0)
				{
					$fs=''; $cnt=0;
					
					foreach ($row['Outputs'] as $out)
					{
						$cnt++;
	
						if ($out['Key'])
						{								
							if (trim($fs)=='') $fs='('.$cnt.') '.$out['Key']; else $fs.= '<br>('.$cnt.') '.$out['Key'];
						}	
					}
					
					$OutputFiles=$fs;
				}
	
				$tp=array($JobId,$PipelineId,$InputKey,$OutputFiles,$Status);
	
				$data['data'][]=$tp;
			}	
		}else
		{
			#$tp=array(null,null,null,null,null);
	
			#$data['data'][]=$tp;
		}
		
		
		echo json_encode($data);
	}
	
	public function CreateJob()
	{#category,pipeline,inputkey,outputfile360,outputfile480,outputfile720
/*

1351620000001-000061 => System preset: Generic 320x240 => System preset generic 320x240 => mp4

1351620000001-000050 => System preset: Generic 360p 4:3 => System preset generic 360p 4:3 => mp4
1351620000001-000030 => System preset: Generic 480p 4:3 => System preset generic 480p 4:3 => mp4
 
1351620000001-000040 => System preset: Generic 360p 16:9 => System preset generic 360p 16:9 => mp4
1351620000001-000020 => System preset: Generic 480p 16:9 => System preset generic 480p 16:9 => mp4

1351620000001-000010 => System preset: Generic 720p => System preset generic 720p => mp4
1351620000001-000001 => System preset: Generic 1080p => System preset generic 1080p => mp4

 */
		$category=''; $pipelineid=''; $inputkey=''; $outputfile360=''; $outputfile480=''; $outputfile720='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('pipelineid')) $pipelineid = trim($this->input->post('pipelineid'));
		if ($this->input->post('inputkey')) $inputkey = trim($this->input->post('inputkey'));
		if ($this->input->post('outputfile360')) $outputfile360 = trim($this->input->post('outputfile360'));
		if ($this->input->post('outputfile480')) $outputfile480 = trim($this->input->post('outputfile480'));
		if ($this->input->post('outputfile720')) $outputfile720 = trim($this->input->post('outputfile720'));
		
		#Get AWS Keys
		$access=''; $secret=''; $input_bucket=''; $output_bucket=''; $thumbs_bucket='';
		
		$sql="SELECT input_bucket,output_bucket,thumbs_bucket,aws_key,aws_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->aws_key) $access = $row->aws_key;
			if ($row->aws_secret) $secret = $row->aws_secret;
			
			if ($row->input_bucket) $input_bucket = $row->input_bucket;
			if ($row->output_bucket) $output_bucket = $row->output_bucket;
			if ($row->thumbs_bucket) $thumbs_bucket = $row->thumbs_bucket;
		}
		
		$flag=TRUE;
		
		$client = ElasticTranscoderClient::factory(array(
			'version' => '2012-09-25',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));
		
		#Health/ = Ouput Key Prefix
		$result = $client->createJob(array(			
			'PipelineId' => $pipelineid,
			'Input' => array('Key' => $inputkey),
			#'OutputKeyPrefix' => $category.'/',
			'Outputs' => array(
				array(
					'Key' => $outputfile360,
					'PresetId' => '1351620000001-000050',#360p
				),
				array(
					'Key' => $outputfile480,
					'PresetId' => '1351620000001-000030',#480p
				),
				array(
					'Key' => $outputfile720,
					'PresetId' => '1351620000001-000010',#720p
				)
			)
		));
		
		#print_r($result['Job']);
		$Msg='';

		$jid=$result['Job']['Id'];
		$pid=$result['Job']['PipelineId'];
		$inkey=$result['Job']['Input']['Key'];
		
		if ((trim($pid)==trim($pipelineid)) && (trim($inkey)==trim($inputkey)))
		{
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') Created A Job Successfully. Job ID Is '.$JobID.' And Pipeline ID Is '.$pipelineid;
			
			$filename=trim(str_replace($category.'/','',$inputkey));
			
			#Get Schedule ID
			$schedule_id=intval($this->getdata_model->GetNextID('videos','schedule_id'));
			
			#Update video table - Encode=1
			$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(filename)='".$this->db->escape_str($filename)."')";
			$query = $this->db->query($sql);	
			
			$this->db->trans_start();
						
			if ($query->num_rows() > 0 )#Update
			{
				$dat=array(
					'encoded' => 1,
					'schedule_id' => $this->db->escape_str($schedule_id),
					'video_status' => 'Encoded'
					);
					
				$this->db->where(array('category'=>$category,'filename'=>$filename));
				$this->db->update('videos', $dat);
				
				$this->db->trans_complete();
			}
			
			$ret='OK';
		}else
		{
			$Msg='Creation Of Job By '.$_SESSION['UserFullName'].'('.$_SESSION['username'].') Was Not Successful. Input Key Is '.$inputkey.' And Pipeline ID Is '.$pipelineid;
			
			$ret='Creation Of Job Was Not Successful.';
		}
		
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'CREATED AWS JOB',$_SESSION['LogID']);
		
		echo $ret; 
	}
	
	public function index()
	{#$file = fopen('aaa.txt',"w"); fwrite($file,'Almost'); fclose($file);
		#$file = fopen('aaa.txt',"w"); fwrite($file, __DIR__); fclose($file);
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
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];

			$data['VideoCategories'] = $this->getdata_model->GetVideoCategories();
			$data['OldPassword']=$_SESSION['pwd'];

			$this->load->view('videos_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
