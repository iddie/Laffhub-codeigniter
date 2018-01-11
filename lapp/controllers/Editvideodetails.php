<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require_once('getID3/getid3/getid3.php');
require 'aws/aws-autoloader.php';

use Aws\S3\S3Client;
use Aws\ElasticTranscoder\ElasticTranscoderClient;

class Editvideodetails extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	   
	public function LoadAssignVideosJson()
	{
		$category=''; $status=''; $InputBucket=''; $ThumbBucket='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('status')) $status = trim($this->input->post('status'));
		if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
		if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));
		
		if (!$status) $status = '0';
		
		$sql = "SELECT * FROM videos WHERE (play_status=".$status.") AND (TRIM(category)='".$category."')";
		
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
				$title=''; $category=''; $filename=''; $status=''; $description=''; $preview_url=''; $vcd=''; $com='';
				
				if ($row['video_title']) $title=$row['video_title'];
				if ($row['category']) $category=$row['category'];
				if ($row['video_code']) $vcd=$row['video_code'];				
				if ($row['encoded']) $encoded=$row['encoded'];
				if ($row['filename']) $filename=$row['filename'];
				if ($row['video_status']) $status=$row['video_status'];
				if ($row['description']) $description=$row['description'];
				if ($row['comedian']) $com=$row['comedian'];
								
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
						
				$view='<input data-videocode="'.$vcd.'" type="checkbox" id="chk'.$sn.'" onClick="GetAssignRow(\''.$sn.'\');" title="Click to select '.strtoupper($title).'" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;">';

	#[SELECT],Category,Title,Comedian,VideoStatus,[Preview],VideoCode			

				$tp=array($view,$category,$title,$description,$com,$status,$video,$vcd);

				$data['data'][]=$tp;
			}
		}
		
		echo json_encode($data);
	}#End Of LoadAssignVideosJson functions
	
	 public function LoadVideosJson()
    {
        $category=''; $status=''; $InputBucket=''; $ThumbBucket='';

        if ($this->input->post('category')) $category = trim($this->input->post('category'));
        if ($this->input->post('status')) $status = trim($this->input->post('status'));
        if ($this->input->post('InputBucket')) $InputBucket = trim($this->input->post('InputBucket'));
        if ($this->input->post('ThumbBucket')) $ThumbBucket = trim($this->input->post('ThumbBucket'));

        if (!$status) $status = '0';

        $sql = "SELECT * FROM videos WHERE (play_status=".$status.") AND (TRIM(category)='".$category."')";

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
                $title=''; $category=''; $filename=''; $status=''; $description=''; $preview_url=''; $vcd=''; $com=''; $feature='';

                if ($row['video_title']) $title=$row['video_title'];
                if ($row['category']) $category=$row['category'];
                if ($row['video_code']) $vcd=$row['video_code'];
                if ($row['encoded']) $encoded=$row['encoded'];
                if ($row['filename']) $filename=$row['filename'];
                if ($row['video_status']) $status=$row['video_status'];
                if ($row['description']) $description=$row['description'];
                if ($row['comedian']) $com=$row['comedian'];
                if ($row['featured']) $feature=$row['featured'];

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

                $view='<i title="Click to select '.strtoupper($title).'" onClick="GetRow(\''.$sn.'\');" style="color:#1671DF; font-size:22px; cursor:pointer; margin-top:5px;" class="fa fa-check-square"></i>';

                #[SELECT],Category,Title,Description,Comedian,VideoStatus,[Preview],Filename,VideoCode

                $tp=array($view,$category,$title,$description,$com,$status,$video,$filename,$vcd, $feature);

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
		$sql = "SELECT comedian FROM comedians ORDER BY comedian";
		
		$query = $this->db->query($sql);
		
		echo json_encode($query->result());
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
	
	    public function UpdateDetails()
    {
        $category=''; $comedian=''; $video_title=''; $description=''; $video_code=''; $featured='';

        if ($this->input->post('category')) $category = trim($this->input->post('category'));
        if ($this->input->post('comedian')) $comedian = trim($this->input->post('comedian'));
        if ($this->input->post('video_title')) $video_title = trim($this->input->post('video_title'));
        if ($this->input->post('description')) $description = trim($this->input->post('description'));
        if ($this->input->post('video_code')) $video_code = trim($this->input->post('video_code'));
        if ($this->input->post('featured')) $featured = trim($this->input->post('featured'));


        #Update Video
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(video_code)='".$this->db->escape_str($video_code)."')";
        $query = $this->db->query($sql);

        $Msg='';

        $this->db->trans_start();

        if ($query->num_rows() > 0 )#Update
        {
            $dat=array(
                'comedian' => $this->db->escape_str($comedian),
                'video_title' => $this->db->escape_str($video_title),
                'description' => $this->db->escape_str($description),
                'featured' => $this->db->escape_str($featured)
            );

            $this->db->where(array('category'=>$category,'video_code'=>$video_code));
            $this->db->update('videos', $dat);

            $this->db->trans_complete();

            $Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') updated details of the video "'.strtoupper($title).' successfully.';

            $ret='OK';
        }else
        {
            $Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') could not update details of the video "'.strtoupper($title).'. Video record was not found in the database.';

            $ret='Video Update Was Not Successful. Video Record Was Not Found In The Database.';
        }

        $this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'UPDATED VIDEO DETAILS',$_SESSION['LogID']);

        echo $ret;
    }#End UpdateDetails
	
	public function AssignComedian()
	{
		$category=''; $comedian=''; $video_code='';
		
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('comedian')) $comedian = trim($this->input->post('comedian'));
		if ($this->input->post('video_code')) $video_code = trim($this->input->post('video_code'));
		
		$vcds=explode(',',$video_code);
		
		$Msg='';
		
		if (count($vcds)>0)
		{
			$cnt=0;
			
			foreach($vcds as $cd):
				if (trim($cd))
				{
					#Update Video
					$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(video_code)='".trim($this->db->escape_str($cd))."')";
					$query = $this->db->query($sql);	
										
					$this->db->trans_start();
								
					if ($query->num_rows() > 0 )#Update
					{			
						$dat=array('comedian' => $this->db->escape_str($comedian));
							
						$this->db->where(array('category'=>$category,'video_code'=>$cd));
						$this->db->update('videos', $dat);
						
						$this->db->trans_complete();							
						
						$cnt++;
					}		
				}
			endforeach;
			
			if ($cnt > 0)
			{
				if (($cnt)==count($vcds))
				{
					$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') assigned the comedian "'.strtoupper($comedian).' to videos with codes '.$video_code.' successfully.';
					
					$ret='OK';
				}else
				{
					$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') assigned the comedian "'.strtoupper($comedian).' to videos with codes '.$video_code.' successfully.';
					
					$ret='Comedian Was Assigned Successfully To <b>Some</b> Of The Selected Videos.';
				}
			}else
			{
				$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') could not assign comedian "'.strtoupper($comedian).' to videos with code(s) '.$video_code.'.';	
				
				$ret='Comedian Assignment Was Not Successful.';
			}
		}else
		{
			$Msg=$_SESSION['UserFullName'].'('.$_SESSION['username'].') could not assign comedian to videos.';
			
			$ret='Comedian Assignment Was Not Successful.';
		}		
		
		$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$_SERVER['REMOTE_ADDR'],gethostbyaddr($_SERVER['REMOTE_ADDR']),'ASSIGN COMEDIAN TO VIDEOS',$_SESSION['LogID']);		
	
		echo $ret;
	}#End AssignComedian
	
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

			$this->load->view('editvideodetails_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
