<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Pubeditpassword extends CI_Controller {
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
						
						$v=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altMessage,$UserFullName);
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
			
			$data['OldPassword']=$_SESSION['publisher_pwd'];
			
			$this->load->view('pubeditpassword_view',$data);
		}else
		{
			redirect("Dashboard");
		}
	}
}
