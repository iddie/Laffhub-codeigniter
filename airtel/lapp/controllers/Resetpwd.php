<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Resetpwd extends CI_Controller {	
	private $ptype='',$pemail='',$pflag=false, $palert='';
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function EditPwd()
	{
		$email=''; $usertype=''; $Pwd='';
	
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('pwd')) $Pwd = $this->input->post('pwd');
		if ($this->input->post('usertype')) $usertype = trim($this->input->post('usertype'));
						
		//Check if record exists
		if (trim(strtolower($usertype))=='subscriber')
		{
			$sql = "SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		}elseif (trim(strtolower($usertype))=='publisher')
		{
			$sql = "SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."')";
		}
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			if (trim(strtolower($usertype))=='subscriber')
			{
				$ret='Subscriber record with email <b>'.$email.'</b> does not exist in the database.';
			}elseif (trim(strtolower($usertype))=='publisher')
			{
				$ret='Publisher record with email <b>'.$email.'</b> does not exist in the database.';
			}			
		}else
		{
			$this->db->trans_start();	//Update password		
						
			if (trim(strtolower($usertype))=='subscriber')
			{
				$this->db->where('email', $this->db->escape_str($email));
				$dat=array('pwd' => $this->db->escape_str($Pwd));
				$this->db->update('subscribers', $dat);
			}elseif (trim(strtolower($usertype))=='publisher')
			{
				$this->db->where('publisher_email', $this->db->escape_str($email));
				$dat=array('publisher_pwd' => $this->db->escape_str($Pwd));
				$this->db->update('publishers', $dat);
			}
			
			$this->db->trans_complete();

			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				if (trim(strtolower($usertype))=='subscriber')
				{
					$Msg="Attempted editing subscriber password with email '".$email."' but failed.";
				}elseif (trim(strtolower($usertype))=='publisher')
				{
					$Msg="Attempted editing publisher password with email '".$email."' but failed.";
				}

				$ret = 'Password Reset Was Not Successful.';
			}else
			{
				#Update reset_pwd table
				$sql="SELECT * FROM reset_pwd WHERE (email='".$this->db->escape_str($email)."') AND (usertype='".$this->db->escape_str($usertype)."')";

				$query = $this->db->query($sql);
				#$file = fopen('aaa.txt',"w"); fwrite($file, $query->num_rows()); fclose($file);
				if ( $query->num_rows() > 0 )
				{
					$this->db->trans_start();	
								
					$this->db->where(array('email' => $this->db->escape_str($email), 'usertype' => $this->db->escape_str($usertype)));
					$this->db->delete('reset_pwd');
					$this->db->trans_complete();
				}
				
				if (trim(strtolower($usertype))=='subscriber')
				{
					$Msg="Subscriber with email ".$email." carried out a successful password reset.";
				}elseif (trim(strtolower($usertype))=='publisher')
				{
					$Msg="Publisher with email ".$email." carried out a successful password reset.";
				}
								
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				$from='support@laffhub.com';
				$to=$email;
				$subject='Password Reset';
				$Cc='idongesit_a@yahoo.com';
				
				$img=base_url()."images/emaillogo.png";
							
				if (trim(strtolower($usertype))=='subscriber')
				{
					$nm=$this->getdata_model->GetSubscriberName($email);
					
					if (trim($nm)=='') $nm='Subscriber';
				}elseif (trim(strtolower($usertype))=='publisher')
				{
					$nm=$this->getdata_model->GetPublisherName($email);
					
					if (trim($nm)=='') $nm='Publisher';
				}
				
	#$file = fopen('aaa.txt',"w"); fwrite($file, $nm); fclose($file);			
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				$from='support@laffhub.com';
				$to=$email;
				$subject='Updated Password';
				$Cc='idongesit_a@yahoo.com';
				
				$img=base_url()."images/logo.png";
				
				$message = '
					<html>
					<head>
					<meta charset="utf-8">
					<title>LaffHub Password Reset</title>
					</head>
					<body>
							<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
							
							Hello '.$nm.',<br><br></p>
							
							<p>You have successfully reset your access password. Please ensure that you have noted down your new password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.</p>
																																									
							<p>Best Regards</p>
							<p>
								LaffHub Team<br>
								<a href="mailto:support@laffhub.com">support@laffhub.com</a>
							</p>
					</body>
					</html>';
					
					$altmessage = '
						Hello '.$nm.',
								
						You have successfully reset your access password. Please ensure that you have noted down your new password. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS.
																																					
						Best Regards
						
						LaffHub Team
						
						support@laffhub.com';
				
				#SendEmail($from,$to,$subject,$Cc,$message,$altMessage,$name)
				$v=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$nm);
		
				$ret ="OK";	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($nm,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'PASSWORD RESET',$_SESSION['LogID']);
		}
	
		echo $ret;	
	}
	
	public function Reset()
	{
		$parameters = $this->uri->uri_to_assoc();
		$ResetCode = $parameters['rc'];
		$usertype = $parameters['ut'];#Subscriber or Publisher
		
		$sql="SELECT * FROM reset_pwd WHERE (SHA1(email)='".$this->db->escape_str($ResetCode)."') AND (usertype='".$this->db->escape_str($usertype)."')";
			
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
			$em='';
			
			if ($row->email) $em=$row->email;
			
			$this->pemail=$em;
			$this->ptype=ucwords(strtolower($usertype));
			$this->pflag=true;
		}else
		{
			$this->palert='Sorry. Your Password Request Was Not Found In Our Database. You Cannot Proceed With The Reset.';
		}
		
		$this->index();
	}
	
	public function index()
	{
		$data['UserType']=$this->ptype;
		$data['Email']=$this->pemail;
		$data['Alert']=$this->palert;
		
		if ($this->pflag==true) $data['Success']='Yes'; else $data['Success']='No';
		
		$this->load->view('resetpwd_view',$data);
	}
}
