<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function UpdateProfile()
	{
		$subscribername=''; $Pwd=''; $email=''; $pwdflag='';
	
		if ($this->input->post('subscribername')) $subscribername = trim($this->input->post('subscribername'));
		if ($this->input->post('pwdflag')) $pwdflag = trim($this->input->post('pwdflag'));	
		if ($this->input->post('Pwd')) $Pwd = $this->input->post('Pwd');
		if ($this->input->post('email')) $email = $this->input->post('email');
					
		//Check if record exists
		$sql = "SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			$ret='Subscriber record with email <b>'.$email.'</b> not found.';
		}else
		{	
			if (strtolower($pwdflag)=='yes')
			{
				$dat=array('pwd' => $this->db->escape_str($Pwd),'name' => $this->db->escape_str($subscribername));	
			}else
			{
				$dat=array('name' => $this->db->escape_str($subscribername));	
			}
			
			//Update subscribers
			$this->db->where(array('email'=>$this->db->escape_str($email)));
			$this->db->update('subscribers', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="The subscriber with email '".$email."' attempted updating profile but failed.";
				$ret = 'Profile Update Was Not Successful.';
			}else
			{
				$_SESSION['subscriber_name']=$subscribername;
				$data['subscriber_name']=$subscribername;
				
				if (strtolower($pwdflag)=='yes') $data['subscriber_pwd'] = $Pwd;
				 
				$Msg="Subscriber '".$subscribername.'('.$email.")' updated profile successfully.";	
				
				#Send Email
				if (trim($email) != '')
				{
					$from='support@laffhub.com';
					$to=$email;
					$subject='Profile Update';
					$Cc='idongesit_a@yahoo.com';
					
					$img=base_url()."images/emaillogo.png";
					
					$message = '
						<html>
						<head>
						<meta charset="utf-8">
						<title>LaffHub Subscriber Profile Update</title>
						</head>
						<body>
								<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
								
								Hello '.$subscribername.',<br><br></p>
								
								<p>You have successfully updated your LaffHub profile. Please ensure that you have noted down your password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.</p>
																																										
								<p>Best Regards</p>
								<p>
									LaffHub Team<br>
									<a href="mailto:support@laffhub.com">support@laffhub.com</a>
								</p>
						</body>
						</html>';
						
					$altmessage = '
						Hello '.$subscribername.',
								
						You have successfully updated your LaffHub profile. Please ensure that you have noted down your new password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.
																																					
						Best Regards
						
						LaffHub Team
						
						support@laffhub.com';
						
						$v=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$subscribername);
				}
				
				$ret ="OK";	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullName,$Msg,$username,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'UPDATED PROFILE',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
	
	public function index()
	{
		if ($_SESSION['subscriber_email'])
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			if ($_SESSION['subscriber_name']) $data['subscriber_name'] = $_SESSION['subscriber_name'];
			if ($_SESSION['subscriber_pwd']) $data['subscriber_pwd'] = $_SESSION['subscriber_pwd'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['subscriber_status']) $data['subscriber_status'] = $_SESSION['subscriber_status'];
			if ($_SESSION['facebook_id']) $data['facebook_id'] = $_SESSION['facebook_id'];
			if ($_SESSION['subscriber_plan']) $data['subscriber_plan'] = $_SESSION['subscriber_plan'];

			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];
			if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
			if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
			if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
			
			$data['subscribe_date'] = ''; $data['exp_date'] = ''; $data['subscriptionstatus'] = '';
			$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
			
			$data['Network']=$this->getdata_model->GetNetwork();
			$data['Phone']=$this->getdata_model->GetMSISDN();
			
			$result=$this->getdata_model->GetSubscriptionDate($data['subscriber_email'],$data['Phone']);
								
			if (is_array($result))
			{
				$td=date('Y-m-d H:i:s');
				
				foreach($result as $row)
				{
					if ($row->subscribe_date) $dt = date('F d, Y',strtotime($row->subscribe_date));
					
					$data['subscribe_date'] = $dt;
					
					if ($row->exp_date) $edt = date('F d, Y',strtotime($row->exp_date));
					$data['exp_date'] = $edt;
					
					if ($td > date('Y-m-d H:i:s',strtotime($row->exp_date)))
					{
						if ($row->subscriptionstatus==1)
						{
							#Update Subscription Date
							$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'0');
						}
					}else
					{
						$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
					}

					break;
				}
			}			
									
			$data['OldPassword']=$_SESSION['subscriber_pwd'];
			$data['Categories']=$this->getdata_model->GetCategories();
			$this->load->view('profile_view',$data);#Fail Page
		}else
		{
			redirect("Home");
		}	
	}
}
