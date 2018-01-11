<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registerpub extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		
		$this->load->model('getdata_model');
		
	}
	
	function CleanData($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	
	public function FacebookRegister()
	{
		$name=''; $facebook_id=''; $gender=''; $email=''; $contact =''; $phone='';
		
		if ($this->input->post('contact')) $contact = trim($this->input->post('contact'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('name')) $name = $this->CleanData(trim($this->input->post('name')));
		if ($this->input->post('gender')) $gender = $this->CleanData(trim($this->input->post('gender')));
		if ($this->input->post('email')) $email = $this->CleanData(trim($this->input->post('email')));
		if ($this->input->post('id')) $facebook_id = $this->CleanData(trim($this->input->post('id')));
		
		
		$gender=ucwords(strtolower($gender));
#$file = fopen('aaa.txt',"a"); fwrite($file,"facebook_id=".$facebook_id."\nEmail=".$email."\nName=".$name); fclose($file);	
		
		#Check for the code in the db
		$sql = "SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."') AND (facebook_id='".$this->db->escape_str($facebook_id)."')";
		$query = $this->db->query($sql);
				
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
													
			$dat=array(
				'publisher_name' => $this->db->escape_str($name),
				'publisher_phone' => $this->db->escape_str($phone),
				'publisher_email' => $this->db->escape_str($email),
				'accept_contract' => $contact,
				'facebook_id' => $facebook_id,
				'publisher_status' => 1,
				'publisher_regdate' => date('Y-m-d H:i:s')
				);	
				
			$this->db->insert('publishers', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="Attempted registering publisher with email ".$email." but failed.";
				$rows = array(
					'status' => 'Publisher Registration Was Not Successful.',
					'Flag'=>'FAIL',
					'name' => $name,
					'phone' => $phone,
					'email' =>  $email
					);
			}else
			{
				$Msg="Publisher facebook registration was successful.";				
				
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				$from='support@laffhub.com';#'register@laffhub.com';
				$to=$email;
				$subject='LaffHub Publisher Signup';
				$Cc='idongesit_a@yahoo.com';
				
				$img=base_url()."images/emaillogo.png";
				
				$nm='';
				
				if ($name) $nm=$name; else $nm='Publisher';
	
				$message = '
					<html>
					<head>
					<meta charset="utf-8">
					<title>LaffHub | Publisher Registration</title>
					</head>
					<body>
							<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
							
							Hello '.$nm.',<br><br>
							
							<p>Your have successfully signed up on LaffHub portal as a publisher.</p>
							
							<p>Your account access username is your email address: '.$to.'</a></p>
							
							<p>For further enquiries, please contact us at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.</p>
												
							<p>Best Regards</p>
							<p>
								LaffHub Team<br>
								<a href="mailto:support@laffhub.com">support@laffhub.com</a>
							</p>
					</body>
					</html>';
					
				$altmessage = '
					Hello '.$nm.',
							
					Your have successfully signed up on LaffHub portal as a publisher.
							
					Your account access username is your email address: '.$to.'
							
					For further enquiries, please contact us at support@laffhub.com.
																												
					Best Regards
					
					LaffHub Team
					
					support@laffhub.com';
					
				$ret=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$nm);
				
				if ($ret != 'OK')
				{
					$rows =array(
						'status' => "Registration was not successful.",
						'Flag'=>'FAIL',
						'name' => $name,
						'phone' => '',
						'email' => $email
						);
					
					#Delete Entry From consumers Table
					$this->db->trans_start();
					$this->db->delete('publishers', array('publisher_email' => $this->db->escape_str($email))); 				
					$this->db->trans_complete();
				}else
				{										
					$rows =array(
						'status' => "Registration Was Successful.",
						'Flag'=>'OK',
						'name' => $name,
						'phone' => $phone,
						'email' => $email
						);
				}	
			}
			
			#Log
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
			$this->getdata_model->LogDetails($name,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'REGISTERED PUBLISHER USING FACEBOOK',$activationCode);
		}else
		{
			$rows =array(
				'status' => "User with email ".$email." has already been registered.",
				'Flag'=>'FAIL',
				'name' => $name,
				'company' => '',
				'phone' => '',
				'email' => $email
				);
		}
		
		echo json_encode($rows);
	}
	
	public function RegisterPublisher()
	{		
		$email=''; $phone=''; $name=''; $password=''; $contract='';
		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('name')) $name = trim($this->input->post('name'));
		if ($this->input->post('contract')) $contract = trim($this->input->post('contract'));
		if ($this->input->post('password')) $password = $this->input->post('password');		
#$file = fopen('aaa.txt',"w"); fwrite($file,$action); fclose($file);	
		
		if (!$contract) $contract='0';
		
		//Check if record exists
		$sql = "SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$rows = array(
				'status' => 'Publiser Registration Was Not Successful. Email <b>'.$email.'</b> Has Already Been Used.',
				'Flag'=>'FAIL'
				);
		}else
		{
			$hcode=uniqid();
			$activationCode=sha1($email);
			
			$activationurl=base_url()."Validatepublisher/Register/cd/".$activationCode;
			
			$this->db->trans_start();
									
			$dat=array(
				'publisher_name' => $this->db->escape_str($name),
				'publisher_email' => $this->db->escape_str($email),
				'publisher_phone' => $this->db->escape_str($phone),
				'accept_contract' => $this->db->escape_str($contract),
				'publisher_pwd' => $this->db->escape_str($password),
				'publisher_status' => 0,
				'publisher_regdate' => date('Y-m-d H:i:s')
				);							
			
			$this->db->insert('publishers', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="Attempted registering publisher with email ".$email." but failed.";
				$rows = array(
					'status' => 'Publisher Registration Was Not Successful.',
					'Flag'=>'FAIL',
					'name' => $name,
					'phone' => $phone,
					'email' =>  $email
					);
			}else
			{
				$Msg="Publisher Registration Was Successful.";				
				
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				$from='support@laffhub.com';#'register@laffhub.com';
				$to=$email;
				$subject='Verify Your Email Address';
				$Cc='idongesit_a@yahoo.com';
				
				$ret=$this->getdata_model->SendRegistrationEmail($from,$to,$subject,$Cc,$name,$activationurl);
				
				if ($ret != 'OK')
				{
					$rows =array(
						'status' => "Publisher registration was not successful. Could not send notification email.",
						'Flag'=>'FAIL',
						'name' => $name,
						'phone' => $phone,
						'email' => $email
						);
					
					#Delete Entry From publishers Table
					$this->db->trans_start();
					$this->db->delete('publishers', array('publisher_email' => $this->db->escape_str($email))); 				
					$this->db->trans_complete();
				}else
				{
					$rows =array(
						'status' => "Publisher registration was successful but the account has not been activated. An email has been sent to <b>$email</b>. Click on the link in the email to activate the account.",
						'Flag'=>'OK',
						'name' => $name,
						'phone' => $phone,
						'email' => $email
						);
				}	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->PublisherLogDetails($name,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'REGISTER PUBLISHER',$activationCode);
		}
		
		echo json_encode($rows);
	}
	
	
	public function index()
	{
		#Get facebook App ID and App Secret
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()> 0 )######### Facebook API Configuration ##########
		{
			$row = $query->row();
			
			if (isset($row))
			{
				$appId=$row->fb_appid;//Facebook App ID
				$data['appSecret']=$row->fb_appsecret;//Facebook App Secret
			}
		}
		
		if (strtolower(trim($_SERVER['HTTP_HOST']))=='localhost')
		{
			$data['appId']='250754245335621';//Facebook App ID
		}elseif (strtolower(trim($_SERVER['HTTP_HOST']))=='healthyliving.ng')
		{
			$data['appId']='210076916146163';//Facebook App ID
		}else
		{
			$data['appId']=$appId;//Facebook App ID
		}
#1657250141235264
#$file = fopen('aaa.txt',"w"); fwrite($file,$data['appId']); fclose($file);
				
		$this->load->view('registerpub_view',$data);
	}
}
