<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Editpassword extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function EditPwd()
	{
		$UserFullName=''; $username=''; $Pwd=''; $email='';
	
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('username')) $username = $this->input->post('username');	
		if ($this->input->post('Pwd')) $Pwd = $this->input->post('Pwd');
		if ($this->input->post('email')) $email = $this->input->post('email');
					
		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			$ret='User record with username "'.$username.'" does not exist in the database.';
		}else
		{	
			$dat=array('pwd' => $this->db->escape_str($Pwd));	
			
			//Update transactions
			$this->db->where(array('username'=>$this->db->escape_str($username)));
			$this->db->update('userinfo', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="The user '".$UserFullName.'('.$username.")' attempted changing password but failed.";
				$ret = 'Change Of Password Was Not Successful.';
			}else
			{
				$Msg="User '".$UserFullName.'('.$username.")' edited password successfully.";	
				
				$_SESSION['pwd']=$Pwd;
				
				#Send Email
				if (trim($email) != '')
				{
					$from='support@laffhub.com';
					$to=$email;
					$subject='Changed Password';
					$Cc='idongesit_a@yahoo.com';
					
					$img=base_url()."images/emaillogo.png";
					
					$message = '
						<html>
						<head>
						<meta charset="utf-8">
						<title>LaffHub Password Update</title>
						</head>
						<body>
								<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
								
								Hello '.$UserFullName.',<br><br></p>
								
								<p>You have successfully updated your access password. Please ensure that you have noted down your new password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.</p>
																																										
								<p>Best Regards</p>
								<p>
									LaffHub Team<br>
									<a href="mailto:support@laffhub.com">support@laffhub.com</a>
								</p>
						</body>
						</html>';
						
					$altmessage = '
						Hello '.$UserFullName.',
								
						You have successfully updated your access password. Please ensure that you have noted down your new password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.
																																					
						Best Regards
						
						LaffHub Team
						
						support@laffhub.com';
						
						$v=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$UserFullName);
				}
				
				$ret ="Ok";	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'CHANGE PASSWORD',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
			
	public function index()
	{
		//$file = fopen('aaa.txt',"w"); fwrite($file,"Email = ".$_SESSION['email']); fclose($file);
		
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
			
			$data['OldPassword']=$_SESSION['pwd'];
			
			$this->load->view('editpassword_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
