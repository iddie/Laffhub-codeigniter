<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require 'aws/aws-autoloader.php';

use Aws\CloudFront\CloudFrontClient;

class Distribution extends CI_Controller {
		
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	 
	public function LoadDistributions()
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
		
		$client = CloudFrontClient::factory(array(
			'version' => '2016-08-01',
			'region'  => 'us-west-2',
			'http'    => ['verify' => __DIR__.'/cacert.pem'],
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		));

		$result = $client->listDistributions();
		
		$data=array();
#$file = fopen('aaa.txt',"w"); fwrite($file,count($result['Pipelines'])."\n".getcwd()."\cacert.pem"); fclose($file);			
		
		$sn=-1;
		
		foreach ($result['DistributionList']['Items'] as $row)
		{#DistributionID,DomainName,Origin,State,Status
			#print_r($row); echo '<br><br>';
			$DistributionID=''; $DomainName=''; $Origin=''; $State=''; $Status='';
			
			if ($row['Id']) $DistributionID=$row['Id'];
			if ($row['DomainName']) $DomainName=$row['DomainName'];									
			if ($row['Origins']['Items'][0]['DomainName']) $Origin=$row['Origins']['Items'][0]['DomainName'];
			if ($row['Status']) $Status=$row['Status'];
			if ($row['Enabled']) $State=$row['Enabled'];
			
			if (trim(strtolower($Status))=='deployed')
			{
				$Status="<font color='#249A47'>".$Status."</font>";
			}else
			{
				$Status="<font color='#BD1111'>".$Status."</font>";
			}
			
			if ($State==1)
			{
				$State="<font color='#249A47'>Enabled</font>";
			}else
			{
				$State="<font color='#BD1111'>Disabled</font>";
			}
			
			$sn++;
			
			$view='<img onClick="GetRow(\''.$sn.'\');" style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Select Distribution">';
			
			$tp=array($view,$DistributionID,$DomainName,$Origin,$State,$Status);
			#echo $DistributionID.'<br>'.$DomainName.'<br>'.$Origin.'<br>'.$State.'<br>'.$Status.'<br><br>';
			$data['data'][]=$tp;
		}		
		
		echo json_encode($data);
	}
	
	public function Update()
	{
		$distribution_Id=''; $domain_name=''; $origin=''; $ret='';
		
		
		
		if ($this->input->post('distribution_Id')) $distribution_Id = $this->input->post('distribution_Id');
		if ($this->input->post('domain_name')) $domain_name = $this->input->post('domain_name');
		if ($this->input->post('origin')) $origin = $this->input->post('origin');
		
		$Msg='';
						
		//Check if record exists
		$sql = "SELECT * FROM streaming_domain";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
			
			$dat=array(
				'distribution_Id' => $this->db->escape_str($distribution_Id),
				'domain_name' => $this->db->escape_str($domain_name),
				'origin' => $this->db->escape_str($origin),
				'insert_date' => date('Y-m-d H:i:s')
			);
			
			$this->db->insert('streaming_domain', $dat);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' attempted inserting streaming distribution record but failed.";
				$ret = 'Setting Video Distribution Was Not Successful.';
			}else
			{
				$_SESSION['distribution_Id'] = $distribution_Id;
				$_SESSION['domain_name'] = $domain_name;
				$_SESSION['origin']  = $origin;				
							
				$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' inserted streaming distribution.";
		
				$ret = 'OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$remote_ip,$remote_host,'INSERTED STREAMING DISTRIBUTION',$_SESSION['LogID']);
		}else#Update
		{
			#Get Old Values
			$row = $query->row();
			
			$OldDId=''; $OldDnm=''; $OldOri='';
		
			if (isset($row))
			{	
				if ($row->distribution_Id) $OldDId = $row->distribution_Id;
				if ($row->domain_name) $OldDnm = $row->domain_name;
				if ($row->origin) $OldOri = $row->origin;
			}
						
			$BeforeValues="Distribution ID = ".$OldDId."; Domain Name = ".$OldDnm."; Origin = ".$OldOri;				
			$AfterValues="Distribution ID = ".$distribution_Id."; Domain Name = ".$domain_name."; Origin = ".$origin;
						
			//Update
			$this->db->trans_start();
			
			$dat=array(
				'distribution_Id' => $this->db->escape_str($distribution_Id),
				'domain_name' => $this->db->escape_str($domain_name),
				'origin' => $this->db->escape_str($origin),
				'updated_date' => date('Y-m-d H:i:s')
			);
			
			$this->db->update('streaming_domain', $dat);
			
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' attempted updating streaming distribution record but failed.";
				$ret = 'Updating Streaming Distribution Was Not Successful.';
			}else
			{
				$_SESSION['distribution_Id'] = $distribution_Id;
				$_SESSION['domain_name'] = $domain_name;
				$_SESSION['origin']  = $origin;			
							
				$Msg="User '".$_SESSION['UserFullName']."(".$_SESSION['username'].")' updated streaming distribution record. Old Values => ".$BeforeValues.". Updated values => ".$AfterValues;
		
				$ret = 'OK';	
			}
						
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED STREAMING DISTRIBUTION',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
			
	public function index()
	{		
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
			if ($_SESSION['google_shortener_api']) $data['google_shortener_api'] = $_SESSION['google_shortener_api'];
			if ($_SESSION['jw_api_key']) $data['jw_api_key'] = $_SESSION['jw_api_key'];
			if ($_SESSION['jw_api_secret']) $data['jw_api_secret'] = $_SESSION['jw_api_secret'];
			if ($_SESSION['jw_player_id']) $data['jw_player_id'] = $_SESSION['jw_player_id'];			
			if ($_SESSION['emergency_emails']) $data['emergency_emails'] = $_SESSION['emergency_emails'];
			if ($_SESSION['emergency_no']) $data['emergency_no'] = $_SESSION['emergency_no'];			
			if ($_SESSION['sms_url']) $data['sms_url'] = $_SESSION['sms_url'];
			if ($_SESSION['sms_username']) $data['sms_username'] = $_SESSION['sms_username'];
			if ($_SESSION['sms_password']) $data['sms_password'] = $_SESSION['sms_password'];			
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];			
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			
			if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
			if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
			if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
								
			$data['OldPassword']=$_SESSION['pwd'];
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
			
			$this->load->view('distribution_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
