<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

class Publishers extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	public function LoadPublisherJson()
	{		
		$sql = "SELECT* FROM publishers ORDER BY publisher_name";
		
		$query = $this->db->query($sql);
		
		$response=$query->result_array();		
		
		$data=array();
#$file = fopen('aaa.txt',"w"); fwrite($file,count($result['Pipelines'])."\n".getcwd()."\cacert.pem"); fclose($file);			
		
		$sn=-1;
		
		foreach ($response as $row)
		{
			$Status=''; $vidcnt='';
			
			if ($row['publisher_status']) $Status=$row['publisher_status'];
			
			#Get Video Count
			$vidcnt=$this->getdata_model->GetVideosUploadedByPublisher($row['publisher_email']);
			
			if (trim(strtolower($Status))==1)
			{
				$Status="<font color='#249A47'>Active</font>";
			}else
			{
				$Status="<font color='#BD1111'>Not Active</font>";
			}
			
			$sn++;
			
			$view='<img onClick="GetRow(\''.$sn.'\');" style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Select Publisher">';
			
			$tp=array($view,$row['publisher_name'],$row['publisher_email'],number_format($vidcnt,0),$Status,$row['publisher_status']);
			
			$data['data'][]=$tp;
		}		
		
		echo json_encode($data);
	}#End Of LoadVideosJson functions
	
	public function UpdatePublisherStatus()
	{#publisher_email,publisher_name,publisher_status,User,UserFullName
		$publisher_email=''; $publisher_name=''; $publisher_status=''; $User=''; $UserFullName=''; $ret='';
		
		if ($this->input->post('publisher_email')) $publisher_email = trim($this->input->post('publisher_email'));
		if ($this->input->post('publisher_name')) $publisher_name = trim($this->input->post('publisher_name'));
		if ($this->input->post('publisher_status')) $publisher_status = trim($this->input->post('publisher_status'));
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullName')) $UserFullName = trim($this->input->post('UserFullName'));
		
		if (!$publisher_status) $publisher_status='0';
		
		$Msg='';
						
		//Check if record exists
		$sql = "SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($publisher_email)."')";

		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )#Not Found
		{
			$ret = 'Publisher Record Modification Was Not Successful. Record With Email <b>'.$publisher_email.'</b> Not Found.';
		}else#Update
		{
			$this->db->trans_start();
			
			$dat=array('publisher_status' => $this->db->escape_str($publisher_status));
			
			$this->db->where('publisher_email', $this->db->escape_str($publisher_email));
			$this->db->update('publishers', $dat);
			
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$User.")' attempted updating publisher status but failed.";
				$ret = 'Updating Publisher Status Was Not Successful.';
			}else
			{
				if (trim($publisher_status)==1) $Status="ACTIVE"; else $Status="NOT ACTIVE";
				
				$Msg="User '".$UserFullName."(".$User.")' updated publisher status to ".$Status.".";
		
				$ret = 'OK';	
			}
						
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$User,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED PUBLISHER STATUS',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
			
	public function index()
	{#$file = fopen('aaa.txt',"w"); fwrite($file,'Almost'); fclose($file);
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

			$this->load->view('publishers_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
