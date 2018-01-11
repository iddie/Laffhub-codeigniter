<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require_once('getID3/getid3/getid3.php');
require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class Activatevideo extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	   
	public function LoadVideosJson()
	{
		$category=''; $publisher=''; $status=''; $InputBucket=''; $ThumbBucket='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('publisher')) $publisher = trim($this->input->post('publisher'));
		if ($this->input->post('status')) $status = trim($this->input->post('status'));
		if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
		if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));
		
		if (!$status) $status = '0';
		
		$sql = "SELECT DATE_FORMAT(date_created,'%d %b %Y') AS DateCreated,videos.* FROM videos WHERE (play_status=".$status.") ";
		
		$crit='';
		
		if (strtolower($publisher) <> 'all') $crit=" (TRIM(publisher_email)='".$publisher."')";
		
		if (strtolower($category) <> 'all')
		{
			if (trim($crit)=='')
			{
				$crit=" (TRIM(category)='".$category."')";
			}else
			{
				$crit .= " AND (TRIM(category)='".$category."')";
			}
		}
		
		if (trim($crit)!='') $sql .= " AND ".$crit;
		
		$sql .= " ORDER BY video_title";
		
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
				$title='&nbsp;'; $category=''; $size=''; $duration=''; $datecreated='';  $publisher='';
				$encoded=''; $filename=''; $status=''; $description=''; $preview_url=''; $vcd='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['publisher_email']) $publisher=$row['publisher_email'];
				if ($row['category']) $category=$row['category'];
				if ($row['video_code']) $vcd=$row['video_code'];
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
				
#$file = fopen('aaa.txt',"w"); fwrite($file, "Domain Name=".$domainname."\nFilename=".$filename."\nStatus=".$status."\nCategory=".$category."\nInput Bucket=".$InputBucket."\nPublisher=".$publisher);fclose($file);		
		
				if ($domainname && $filename && (trim(strtolower($status))=='encoded'))
				{
					#$filename='bigbuck.mp4';
					$arr = explode('.', basename($filename));
					$ext=array_pop($arr);				
					$fn=str_replace('.'.$ext,'',basename($filename));
										
					$preview_url='https://'.$domainname.'/'.$category.'/'.$fn;
										
					$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$category.'/'.$fn.'.jpg';
	
	#$file = fopen('aaa.txt',"a"); fwrite($file,$preview_url."\n\n".$preview_img); fclose($file);				
					
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
			
	
			
						$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="25px" title="Click To Preview '.strtoupper($title).'" onclick="ShowVideo(\''.$preview_url.'\',\''.$title.'\',\''.$ext.'\',\''.$preview_img.'\')">';
					}else
					{
						$video='';
					}
				}
				
				$sn++;
						
				$view='<i onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square"></i>';

	#[Select],Title,Category,Size,Duration,Date_Created,Domain_Name,Encoded,Distributed,Filename,Status,[Preview]				

				$tp=array($view,$publisher,$category,$title,$size,$duration,$datecreated,$encoded,$status,$video,$filename,$vcd);

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
		$PipelineId=''; $Category=''; $publisher='';
		
		if ($this->input->post('PipelineId')) $PipelineId = trim($this->input->post('PipelineId'));
		if ($this->input->post('Category')) $Category = trim($this->input->post('Category'));
		if ($this->input->post('publisher')) $publisher = trim($this->input->post('publisher'));
		
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
						$ar[]=$tp;
					}
				}
				
				if 	(count($ar)>0)
				{
					foreach ($ar as $rec) 
					{
						$fn=str_replace($Category.'/','',$rec['Filename']);
										
						$sql = "SELECT video_title FROM videos WHERE (TRIM(category)='".$this->db->escape_str($Category)."') AND (TRIM(filename)='".$this->db->escape_str($fn)."') AND (TRIM(publisher_email)='".$this->db->escape_str($publisher)."')";

#$file = fopen('aaa.txt',"a"); fwrite($file, "\n". $sql); fclose($file);	
						
						$query = $this->db->query($sql);	
							
						if ($query->num_rows() > 0 )#Update
						{
							$rows[]=$rec;
						}
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
	{#category,pipeline,inputkey,outputfile360,outputfile720,outputfile1080
/*

1351620000001-000061 => System preset: Generic 320x240 => System preset generic 320x240 => mp4

1351620000001-000050 => System preset: Generic 360p 4:3 => System preset generic 360p 4:3 => mp4
1351620000001-000030 => System preset: Generic 480p 4:3 => System preset generic 480p 4:3 => mp4
 
1351620000001-000040 => System preset: Generic 360p 16:9 => System preset generic 360p 16:9 => mp4
1351620000001-000020 => System preset: Generic 480p 16:9 => System preset generic 480p 16:9 => mp4

1351620000001-000010 => System preset: Generic 720p => System preset generic 720p => mp4
1351620000001-000001 => System preset: Generic 1080p => System preset generic 1080p => mp4

 */
		$category=''; $pipelineid=''; $inputkey=''; $outputfile360=''; $outputfile720='';
		$publisher=''; $outputfile1080='';
		
		if ($this->input->post('publisher')) $publisher = trim($this->input->post('publisher'));
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('pipelineid')) $pipelineid = trim($this->input->post('pipelineid'));
		if ($this->input->post('inputkey')) $inputkey = trim($this->input->post('inputkey'));
		if ($this->input->post('outputfile360')) $outputfile360 = trim($this->input->post('outputfile360'));
		if ($this->input->post('outputfile720')) $outputfile720 = trim($this->input->post('outputfile720'));
		if ($this->input->post('outputfile1080')) $outputfile1080 = trim($this->input->post('outputfile1080'));
		
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
		
		#Delete Existing Files First
		/*$s3Client = new S3Client([
			'version' => 'latest',
			'region'  => 'us-west-2',
			'scheme' => 'http',
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		]);
		*/
		
		#$result = $s3Client->deleteObject(array('Bucket' => $output_bucket, 'Key' => $outputfile360));#360p
		#$result = $s3Client->deleteObject(array('Bucket' => $output_bucket, 'Key' => $outputfile720));#720p
		#$result = $s3Client->deleteObject(array('Bucket' => $output_bucket, 'Key' => $outputfile1080));#1080p

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
					'Key' => $outputfile720,
					'PresetId' => '1351620000001-000010',#720p
				),
				array(
					'Key' => $outputfile1080,
					'PresetId' => '1351620000001-000001',#1080p
				)
			)
		));
		
		#print_r($result['Job']);
		$Msg='';

		$jid=$result['Job']['Id'];
		$pid=$result['Job']['PipelineId'];
		$inkey=$result['Job']['Input']['Key'];

#$file = fopen('aaa.txt',"a"); fwrite($file,"pid=".$pid."\nPipelineid=".$pipelineid."\ninkey".$inkey."\ninputkey=".$inputkey); fclose($file);
		
		if ((trim($pid)==trim($pipelineid)) && (trim($inkey)==trim($inputkey)))
		{
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') Created A Job Successfully. Job ID Is '.$JobID.' And Pipeline ID Is '.$pipelineid;
			
			$filename=trim(str_replace($category.'/','',$inputkey));
			
			#Get Schedule ID
			$schedule_id=intval($this->getdata_model->GetNextID('videos','schedule_id'));
			
			#Update video table - Encode=1
			$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(filename)='".$this->db->escape_str($filename)."') AND (TRIM(publisher_email)='".$this->db->escape_str($publisher)."')";
			$query = $this->db->query($sql);	
	
	#$file = fopen('aaa.txt',"a"); fwrite($file,"\n\n".$sql); fclose($file);
			
			$this->db->trans_start();
						
			if ($query->num_rows() > 0 )#Update
			{
				$dat=array(
					'encoded' => 1,
					'schedule_id' => $this->db->escape_str($schedule_id),
					'play_status' => 1,
					'video_status' => 'Encoded'
					);
					
				$this->db->where(array('category'=>$category,'filename'=>$filename, 'publisher_email'=>$publisher));
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

			$this->load->view('activatevideo_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
