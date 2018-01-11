<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

require 'aws/aws-autoloader.php';
use Aws\S3\S3Client;

class Settings extends CI_Controller {
		
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	 
	public function GetAmazonBuckets()
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
		
		$s3Client = new S3Client([
			'version' => 'latest',
			'region'  => 'us-west-2',
			'scheme' => 'http',
			'credentials' => [
				'key'    => $access,
				'secret' => $secret
				]
		]);
		
		$result = $s3Client->listBuckets();
		
		#$bks[]=array();		
		$b='';

		foreach ($result['Buckets'] as $bucket) 
		{
			#$bks[]=$bucket['Name'];
			if ($b=='') $b=$bucket['Name']; else $b .= '^'.$bucket['Name'];
		}
		
		#$bks=array_filter($bks);		
		echo $b; 
	}
	
	public function Update()
	{
		$no_of_videos_per_day=''; $companyname=''; $companyemail=''; $ret=''; $companyphone='';
		$username=''; $UserFullName=''; $website=''; $RefreshDuration=''; $default_network='';
		$LogoImg=''; $google_shortener_api=''; $jw_api_key=''; $jw_api_secret=''; $jw_player_id='';
		$emergency_no=''; $emergency_emails=''; $sms_url=''; $sms_username=''; $sms_password='';
		$input_bucket=''; $output_bucket=''; $thumbs_bucket=''; $aws_key=''; $aws_secret='';
		$jwplayer_key='';
		
		if (isset($_FILES['logo_pix'])) $LogoImg = $_FILES['logo_pix'];
		
		if ($this->input->post('username')) $username = $this->input->post('username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('companyname')) $companyname = $this->input->post('companyname');
		if ($this->input->post('no_of_videos_per_day')) $no_of_videos_per_day = $this->input->post('no_of_videos_per_day');
		if ($this->input->post('companyemail')) $companyemail = $this->input->post('companyemail');
		if ($this->input->post('companyphone')) $companyphone = $this->input->post('companyphone');
		if ($this->input->post('website')) $website = $this->input->post('website');
		if ($this->input->post('RefreshDuration')) $RefreshDuration = $this->input->post('RefreshDuration');
		if ($this->input->post('default_network')) $default_network = $this->input->post('default_network');	
		if ($this->input->post('google_shortener_api')) $google_shortener_api = $this->input->post('google_shortener_api');		
		if ($this->input->post('jw_api_key')) $jw_api_key = $this->input->post('jw_api_key');
		if ($this->input->post('jw_api_secret')) $jw_api_secret = $this->input->post('jw_api_secret');
		if ($this->input->post('jw_player_id')) $jw_player_id = $this->input->post('jw_player_id');		
		if ($this->input->post('emergency_no')) $emergency_no = $this->input->post('emergency_no');
		if ($this->input->post('emergency_emails')) $emergency_emails = $this->input->post('emergency_emails');
		if ($this->input->post('sms_url')) $sms_url = $this->input->post('sms_url');
		if ($this->input->post('sms_username')) $sms_username = $this->input->post('sms_username');
		if ($this->input->post('sms_password')) $sms_password = $this->input->post('sms_password');		
		if ($this->input->post('input_bucket')) $input_bucket = $this->input->post('input_bucket');
		if ($this->input->post('output_bucket')) $output_bucket = $this->input->post('output_bucket');
		if ($this->input->post('thumbs_bucket')) $thumbs_bucket = $this->input->post('thumbs_bucket');
		if ($this->input->post('aws_key')) $aws_key = $this->input->post('aws_key');
		if ($this->input->post('aws_secret')) $aws_secret = $this->input->post('aws_secret');
		if ($this->input->post('jwplayer_key')) $jwplayer_key = $this->input->post('jwplayer_key');
	
		$Lpix=''; $Msg='';
		
		if ($LogoImg)
		{
			$logo_filename = $LogoImg['name'];
			
			$ext = explode('.', basename($logo_filename));
			
			$fn="companylogo.".array_pop($ext);
			
			$target ="images/".$fn;
			
			if(move_uploaded_file($LogoImg['tmp_name'], $target))
			{
				$Lpix=$fn;					
				$this->getdata_model->ResizeImage($target,200);
			}
		}else
		{
			$Lpix='';
		}
		
		//Check if record exists
		$sql = "SELECT * FROM settings";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
			
			$dat=array(
				'no_of_videos_per_day' => $this->db->escape_str($no_of_videos_per_day),
				'companyname' => $this->db->escape_str($companyname),
				'companyemail' => $this->db->escape_str($companyemail),
				'companyphone' => $this->db->escape_str($companyphone),
				'website' => $this->db->escape_str($website),					
				'RefreshDuration' => $this->db->escape_str($RefreshDuration),
				'default_network' => $this->db->escape_str($default_network),
				'google_shortener_api' => $this->db->escape_str($google_shortener_api),
				'jw_api_key' => $this->db->escape_str($jw_api_key),
				'jw_api_secret' => $this->db->escape_str($jw_api_secret),
				'jw_player_id' => $this->db->escape_str($jw_player_id),
				'emergency_emails' => $this->db->escape_str($emergency_emails),				
				'sms_url' => $this->db->escape_str($sms_url),
				'sms_username' => $this->db->escape_str($sms_username),
				'sms_password' => $this->db->escape_str($sms_password),				
				'emergency_no' => $this->db->escape_str($emergency_no),
				'input_bucket' => $this->db->escape_str($input_bucket),
				'output_bucket' => $this->db->escape_str($output_bucket),
				'thumbs_bucket' => $this->db->escape_str($thumbs_bucket),
				'aws_key' => $this->db->escape_str($aws_key),
				'aws_secret' => $this->db->escape_str($aws_secret),
				'jwplayer_key' => $this->db->escape_str($jwplayer_key)
			);
			
			$this->db->insert('settings', $dat);
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted inserting record into the 'settings' table but failed.";
				$ret = 'Updating Portal Settings Was Not Successful.';
			}else
			{
				#Update Logo
				if ($Lpix)
				{
					$this->db->trans_start();			
					$dat=array('companylogo' => $this->db->escape_str($Lpix));					
					$this->db->update('settings', $dat);					
					$this->db->trans_complete();
				}
				
				$_SESSION['companyname'] = $companyname;
				$_SESSION['companyemail'] = $companyemail;
				$_SESSION['companyphone']  = $companyphone;
				$_SESSION['website'] = $website;				
				if ($Lpix) $_SESSION['companylogo'] = $Lpix;				
				$_SESSION['RefreshDuration'] = $RefreshDuration;
				$_SESSION['default_network'] = $default_network;
				$_SESSION['no_of_videos_per_day'] = $no_of_videos_per_day;
				$_SESSION['google_shortener_api'] = $google_shortener_api;				
				$_SESSION['jw_api_key'] = $jw_api_key;
				$_SESSION['jw_api_secret'] = $jw_api_secret;
				$_SESSION['jw_player_id'] = $jw_player_id;
				$_SESSION['emergency_emails']=$emergency_emails;
				$_SESSION['emergency_no']=$emergency_no;				
				$_SESSION['sms_url']=$sms_url;
				$_SESSION['sms_username']=$sms_username;
				$_SESSION['sms_password']=$sms_password;				
				$_SESSION['input_bucket']=$input_bucket;
				$_SESSION['output_bucket']=$output_bucket;
				$_SESSION['thumbs_bucket']=$thumbs_bucket;				
				$_SESSION['aws_key']=$aws_key;
				$_SESSION['aws_secret']=$aws_secret;
				$_SESSION['jwplayer_key']=$jwplayer_key;
				
							
				$Msg="User '".$UserFullName."(".$username.")' inserted record into the 'settings' table.";
		
				$ret = 'OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'INSERTED PORTAL SETTINGS',$_SESSION['LogID']);
		}else#Update
		{
			#Get Old Values
			$row = $query->row();
			
			$OldName=''; $OldPhone=''; $OldWeb=''; $OldEmail=''; $OldLogo=''; $OldRefresh=''; $OldNet=''; $OldNo='';
			$OldJWKey=''; $OldJWSecret=''; $OldPlayerId=''; $OldemGsm=''; $OldemEmail=''; $OldSmsUrl='';
			$OldSmsUname=''; $OldSmsPwd=''; $OldInBucket=''; $OldOutBucket=''; $OldThumbBucket='';
			$OldAwsKey=''; $OldAwsSecret=''; $Oldjwplayer_key='';
		
			if (isset($row))
			{	
				if ($row->companyname) $OldName = $row->companyname;
				if ($row->companyemail) $OldEmail = $row->companyemail;
				if ($row->companyphone) $OldPhone = $row->companyphone;
				if ($row->website) $OldWeb = $row->website;
				if ($row->companylogo) $OldLogo = $row->companylogo;
				if ($row->RefreshDuration) $OldRefresh = $row->RefreshDuration;
				if ($row->default_network) $OldNet = $row->default_network;
				if ($row->no_of_videos_per_day) $OldNo = $row->no_of_videos_per_day;
				if ($row->google_shortener_api) $OldGoogle = $row->google_shortener_api;				
				if ($row->jw_api_key) $OldJWKey = $row->jw_api_key;
				if ($row->jw_api_secret) $OldJWSecret = $row->jw_api_secret;
				if ($row->jw_player_id) $OldPlayerId = $row->jw_player_id;				
				if ($row->emergency_no) $OldemGsm = $row->emergency_no;
				if ($row->emergency_emails) $OldemEmail = $row->emergency_emails;				
				if ($row->sms_url) $OldSmsUrl = $row->sms_url;
				if ($row->sms_username) $OldSmsUname = $row->sms_username;
				if ($row->sms_password) $OldSmsPwd = $row->sms_password;				
				if ($row->input_bucket) $OldInBucket = $row->input_bucket;
				if ($row->output_bucket) $OldOutBucket = $row->output_bucket;
				if ($row->thumbs_bucket) $OldThumbBucket = $row->thumbs_bucket;				
				if ($row->aws_key) $OldAwsKey = $row->aws_key;
				if ($row->aws_secret) $OldAwsSecret = $row->aws_secret;
				if ($row->jwplayer_key) $Oldjwplayer_key = $row->jwplayer_key;
			}
						
			$BeforeValues="Company Name = ".$OldName."; Company Phone = ".$OldPhone."; Company Email = ".$OldEmail."; Company Website = ".$OldWeb."; Company Logo Name = ".$OldLogo."; Page Refresh Duration = ".$OldRefresh."; Default Network = ".$OldNet."; No. Of Videos/Day = ".$OldNo."; Google Url Shortener API = ".$OldGoogle."; JW Player API Key = ".$OldJWKey."; JW Player API Secret = ".$OldJWSecret."; JW Player ID = ".$OldPlayerId."; Emergency Mobile Number(s) = ".$OldemGsm."; Emergency Email(s) = ".$OldemEmail."; Bulk SMS Provider URL = ".$OldSmsUrl."; Bulk SMS Account Username = ".$OldSmsUname."; Bulk SMS Account Password = ".$OldSmsPwd."; Amazon Input Bucket = ".$OldInBucket."; Amazon Output Bucket = ".$OldOutBucket."; Amazon Thumbnail Bucket = ".$OldThumbBucket."; Amazon Key = ".$OldAwsKey."; Amazon Secret Code = ".$OldAwsSecret."; JWPlayer Key = ".$Oldjwplayer_key;				
				
			$AfterValues="Company Name = ".$companyname."; Company Phone = ".$companyphone."; Company Email = ".$companyemail."; Company Website = ".$website."; Company Logo Name = ".$Lpix."; Page Refresh Duration = ".$RefreshDuration."; Default Network = ".$default_network."; No. Of Videos/Day = ".$no_of_videos_per_day."; Google Url Shortener API = ".$google_shortener_api."; JW Player API Key = ".$jw_api_key."; JW Player API Secret = ".$jw_api_secret."; JW Player ID = ".$jw_player_id."; Emergency Mobile Number(s) = ".$emergency_no."; Emergency Email(s) = ".$emergency_emails."; Bulk SMS Provider URL = ".$sms_url."; Bulk SMS Account Username = ".$sms_username."; Bulk SMS Account Password = ".$sms_password."; Amazon Input Bucket = ".$input_bucket."; Amazon Output Bucket = ".$output_bucket."; Amazon Thumbnail Bucket = ".$thumbs_bucket."; Amazon Key = ".$aws_key."; Amazon Secret Code = ".$aws_secret."; JWPlayer Key = ".$jwplayer_key;
						
			//Update transactions
			$this->db->trans_start();			
			#$where = "username='".$this->db->escape_str($username)."'";
			
			$dat=array(
				'no_of_videos_per_day' => $this->db->escape_str($no_of_videos_per_day),
				'companyname' => $this->db->escape_str($companyname),
				'companyemail' => $this->db->escape_str($companyemail),
				'companyphone' => $this->db->escape_str($companyphone),
				'website' => $this->db->escape_str($website),					
				'RefreshDuration' => $this->db->escape_str($RefreshDuration),
				'default_network' => $this->db->escape_str($default_network),
				'google_shortener_api' => $this->db->escape_str($google_shortener_api),
				'jw_api_key' => $this->db->escape_str($jw_api_key),
				'jw_api_secret' => $this->db->escape_str($jw_api_secret),
				'jw_player_id' => $this->db->escape_str($jw_player_id),
				'emergency_emails' => $this->db->escape_str($emergency_emails),
				'emergency_no' => $this->db->escape_str($emergency_no),
				'sms_url' => $this->db->escape_str($sms_url),
				'sms_username' => $this->db->escape_str($sms_username),
				'sms_password' => $this->db->escape_str($sms_password),
				'input_bucket' => $this->db->escape_str($input_bucket),
				'output_bucket' => $this->db->escape_str($output_bucket),
				'thumbs_bucket' => $this->db->escape_str($thumbs_bucket),
				'aws_key' => $this->db->escape_str($aws_key),
				'aws_secret' => $this->db->escape_str($aws_secret),
				'jwplayer_key' => $this->db->escape_str($jwplayer_key)
			);
			
			$this->db->update('settings', $dat);
			
			$this->db->trans_complete();
				
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="User '".$UserFullName."(".$username.")' attempted editing portal settings record but failed.";
				$ret = 'Updating Portal Settings Was Not Successful.';
			}else
			{
				#Update Logo
				if ($Lpix)
				{
					$this->db->trans_start();			
					$dat=array('companylogo' => $this->db->escape_str($Lpix));					
					$this->db->update('settings', $dat);					
					$this->db->trans_complete();
				}
				
				$_SESSION['companyname'] = $companyname;
				$_SESSION['companyemail'] = $companyemail;
				$_SESSION['companyphone']  = $companyphone;
				$_SESSION['website'] = $website;				
				if ($Lpix) $_SESSION['companylogo'] = $Lpix;				
				$_SESSION['RefreshDuration'] = $RefreshDuration;
				$_SESSION['default_network'] = $default_network;
				$_SESSION['no_of_videos_per_day'] = $no_of_videos_per_day;
				$_SESSION['google_shortener_api'] = $google_shortener_api;				
				$_SESSION['jw_api_key'] = $jw_api_key;
				$_SESSION['jw_api_secret'] = $jw_api_secret;
				$_SESSION['jw_player_id'] = $jw_player_id;
				$_SESSION['emergency_emails']=$emergency_emails;
				$_SESSION['emergency_no']=$emergency_no;				
				$_SESSION['sms_url']=$sms_url;
				$_SESSION['sms_username']=$sms_username;
				$_SESSION['sms_password']=$sms_password;
				$_SESSION['input_bucket']=$input_bucket;
				$_SESSION['output_bucket']=$output_bucket;
				$_SESSION['thumbs_bucket']=$thumbs_bucket;
				$_SESSION['aws_key']=$aws_key;
				$_SESSION['aws_secret']=$aws_secret;
				$_SESSION['jwplayer_key']=$jwplayer_key;
							
				$Msg="User '".$UserFullName."(".$username.")' updated portal settings record. Old Values => ".$BeforeValues.". Updated values => ".$AfterValues;
		
				$ret = 'OK';	
			}
						
						
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED PORTAL SETTINGS',$_SESSION['LogID']);
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
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
					
			$data['OldPassword']=$_SESSION['pwd'];
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $this->getdata_model->BulkSMSBalance()); fclose($file);
			
			$this->load->view('settings_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
