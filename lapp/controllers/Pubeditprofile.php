<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Pubeditprofile extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function EditProfile()
	{ 
		$publisher_name=''; $publisher_phone=''; $ret=''; $PublisherEmail=''; $PublisherName='';
		
		if ($this->input->post('PublisherEmail')) $PublisherEmail = $this->input->post('PublisherEmail');
		if ($this->input->post('PublisherName')) $PublisherName = $this->input->post('PublisherName');
		
		if ($this->input->post('publisher_name')) $publisher_name = $this->input->post('publisher_name');
		if ($this->input->post('publisher_phone')) $publisher_phone = $this->input->post('publisher_phone');
					
		//Check if record exists
		$sql = "SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($PublisherEmail)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			$ret='Record with email <b>'.$PublisherEmail.'</b> does not exist in the database.';
		}else
		{
			#Get Old Values
			$row = $query->row();
			
			$OldName=''; $OldPhone='';
		
			if (isset($row))
			{
				if ($row->publisher_name) $OldName = $row->publisher_name;
				if ($row->publisher_phone) $OldPhone = $row->publisher_phone;
			}
			
			$BeforeValues="Publisher Name = ".$OldName."; Publisher Phone = ".$OldPhone;				
				
			$AfterValues="Publisher Name = ".$publisher_name."; Publisher Phone = ".$publisher_phone;
						
			//Update transactions
			$this->db->trans_start();			
			$where = "publisher_email='".$this->db->escape_str($PublisherEmail)."'";
			
			#username,firstname,lastname,email,phone	
			$dat=array(
				'publisher_name' => $this->db->escape_str($publisher_name),
				'publisher_phone' => $this->db->escape_str($publisher_phone)
			);
			
			$this->db->update('publishers', $dat, $where);
			
			$this->db->trans_complete();
			
			$nm=''; $Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="The publisher '".$PublisherName.'('.$PublisherEmail.")' attempted editing publiser profile but failed.";
				$ret = 'Profile Editing Was Not Successful.';
			}else
			{				
				$_SESSION['publisher_name']=$publisher_name;
				$_SESSION['publisher_phone']=$publisher_phone;
								
				$Msg="Publisher '".$PublisherName.'('.$PublisherEmail.")' edited profile successfully. Profile data before editing => ".$BeforeValues.". Profile data after editing => ".$AfterValues;			
				
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				if (trim($email) != '')
				{
					$from='support@laffhub.com';
					$to=$email;
					$subject='Updated Profile';
					$Cc='idongesit_a@yahoo.com';
					
					$img=base_url()."images/emaillogo.png";
					
					$message = '
						<html>
						<head>
						<meta charset="utf-8">
						<title>LaffHub | Publisher Profile Update</title>
						</head>
						<body>
								<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
								
								Hello '.$PublisherName.',<br><br></p>
								
								<p>You have successfully updated your profile.</p>
																																										
								<p>Best Regards</p>
								<p>
									LaffHub Team<br>
									<a href="mailto:support@laffhub.com">support@laffhub.com</a>
								</p>
						</body>
						</html>';
						
					$altmessage = '
						Hello '.$PublisherName.',
								
						You have successfully updated your profile.
																																					
						Best Regards
						
						LaffHub Team
						
						laffhub.com';	
						
					$v=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$PublisherName);
				}
				
				$ret ="OK";	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->PublisherLogDetails($PublisherName,$Msg,$PublisherEmail,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'PUBLISHER PROFILE EDIT',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
			
	public function index()
	{		
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
					
			$this->load->view('Pubeditprofile_view',$data);
		}else
		{
			redirect("Dashboard");
		}
	}
}
