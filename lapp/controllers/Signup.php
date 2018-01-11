<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
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
		$name=''; $facebook_id=''; $gender=''; $email='';
		
		if ($this->input->post('name')) $name = $this->CleanData(trim($this->input->post('name')));
		if ($this->input->post('gender')) $gender = $this->CleanData(trim($this->input->post('gender')));
		if ($this->input->post('email')) $email = $this->CleanData(trim($this->input->post('email')));
		if ($this->input->post('id')) $facebook_id = $this->CleanData(trim($this->input->post('id')));
		
		
		$gender=ucwords(strtolower($gender));
#$file = fopen('aaa.txt',"a"); fwrite($file,"facebook_id=".$facebook_id."\nEmail=".$email."\nName=".$name."\nGender=".$gender); fclose($file);	
		
		#Check for the code in the db
		$sql = "SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (facebook_id='".$this->db->escape_str($facebook_id)."')";
		$query = $this->db->query($sql);
				
		if ($query->num_rows() == 0 )#Insert
		{
			$this->db->trans_start();
										
			$dat=array(
				'email' => $this->db->escape_str($email),
				'pwd' => $this->db->escape_str($password),
				'name' => $this->db->escape_str($name),
				'facebook_id' => $this->db->escape_str($facebook_id),
				'accountstatus' => 1,
				'reg_date' => date('Y-m-d H:i:s')
				);							
			
			$this->db->insert('subscribers', $dat);
			
			$this->db->trans_complete();
						
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="Attempted signing up subscriber with email ".$email." but failed.";
				$rows = array(
					'status' => 'Signup Was Not Successful.',
					'name' => $name,
					'Flag'=>'FAIL',
					'email' =>  $email
					);
			}else
			{
				$Msg="Subscriber facebook registration was successful.";		
					
				#Send Email - EmailSender($from,$to,$Cc,$message,$name)
				$from='support@laffhub.com';#'register@laffhub.com';
				$to=$email;
				$subject='LaffHub Subscriber Signup';
				$Cc='idongesit_a@yahoo.com';
				
				$img=base_url()."images/emaillogo.png";
				
				$nm='';
				
				if ($name) $nm=$name; else $nm='Subscriber';
	
				$message = '
					<html>
					<head>
					<meta charset="utf-8">
					<title>LaffHub | Subscriber Registration</title>
					</head>
					<body>
							<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
							
							Hello '.$nm.',<br><br>
							
							<p>Your have successfully signed up on LaffHub portal as a subscriber.</p>
							
							<p>Your account access username is your email address: '.$to.'</a></p>
							
							<p>For further enquiries, please contact us at <a href="mailto:support@efluxz.com">support@efluxz.com</a>.</p>
												
							<p>Best Regards</p>
							<p>
								LaffHub Team<br>
								<a href="mailto:support@efluxz.com">support@efluxz.com</a>
							</p>
					</body>
					</html>';
					
				$altmessage = '
					Hello '.$nm.',
							
					Your have successfully signed up on LaffHub portal as a subscriber.
							
					Your account access username is your email address: '.$to.'
							
					For further enquiries, please contact us at support@efluxz.com.
																												
					Best Regards
					
					LaffHub Team
					
					support@laffhub.com';
					
				$ret=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$message,$altmessage,$nm);
				
				if ($ret != 'OK')
				{
					$rows =array(
						'status' => "Signup was not successful.",
						'Flag'=>'FAIL',
						'name' => $name,
						'email' => $email
						);
					
					#Delete Entry From subscribers Table
					$this->db->trans_start();
					$this->db->delete('subscribers', array('email' => $this->db->escape_str($email))); 				
					$this->db->trans_complete();
				}else
				{
					
				
					$rows =array(
						'status' => "Registration Was Successful.",
						'Flag'=>'OK',
						'name' => $name,
						'email' => $email
					);
				}
			}
			
			#Log
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
			$this->getdata_model->LogDetails($name,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'REGISTERED SUBSCRIBER USING FACEBOOK',$activationCode);
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
		
	public function RegisterUser()
	{		
		$email=''; $password=''; $name='';
				
		if ($this->input->post('name')) $name = trim($this->input->post('name'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('password')) $password = $this->input->post('password');		
#$file = fopen('aaa.txt',"w"); fwrite($file,$action); fclose($file);				
		
		//Check if record exists
		$sql = "SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$rows = array(
				'status' => 'Signup Was Not Successful. Email <b>'.$email.'</b> exists in the database.',
				'Flag'=>'FAIL'
				);
		}else
		{
			#$activationCode=sha1($email);			
			#$activationurl=base_url()."Signup/ActivateUser/cd/".$activationCode;
						
			$this->db->trans_start();
										
			$dat=array(
				'email' => $this->db->escape_str($email),
				'pwd' => $this->db->escape_str($password),
				'name' => $this->db->escape_str($name),
				'facebook_id' => '',
				'accountstatus' => 1,
				'reg_date' => date('Y-m-d H:i:s')
				);							
			
			$this->db->insert('subscribers', $dat);
			
			$this->db->trans_complete();
			
			$Msg=''; $name='';
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg="Attempted signing up subscriber with email ".$email." but failed.";
				$rows = array(
					'status' => 'Signup Was Not Successful.',
					'Flag'=>'FAIL',
					'email' =>  $email
					);
			}else
			{
				$network='WIFI';
				$tdt=date("Y-m-d H:i:s");
				$msisdn='';
				$phone='';
				
				//Create trial account
				$isnew=$this->getdata_model->IsNewSubscriberEmail($email,$network);
				
				if (intval($isnew,10)==1)
				{				
					#### Update Into new_subscriptions						
					$sql="SELECT email FROM new_subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";
						
					$query = $this->db->query($sql);
				
					if ($query->num_rows() > 0 )
					{
						$this->db->trans_start();
	
						$dat=array('plan' => 'Trial', 'subscriptiondate' => $tdt);	
						
						$where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";
						
						$this->db->where($where);				
						$this->db->update('new_subscriptions', $dat); 	
						
						$this->db->trans_complete();
					}else
					{
						$this->db->trans_start();
		
						$dat=array(
							'network' => $this->db->escape_str($network),
							'msisdn' => $this->db->escape_str($phone),
							'plan' => 'Trial',
							'email' => $this->db->escape_str($email),
							'subscriptiondate' => $tdt,
						);	
													
						$this->db->insert('new_subscriptions', $dat); 	
						
						$this->db->trans_complete();
					}
					########################### DAILY REPORT FUNCTIONS #######################
					
					$subscriptionId=strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10));
					$msisdn=$phone;				$plan='Trial';		$duration=2;
					$videos_cnt_to_watch = 3;	$amount=0;			$autobilling=1;
					$subscribe_date = date('Y-m-d H:i:s');
					$exp_date=date('Y-m-d H:i:s',strtotime("+".$duration." days",strtotime($subscribe_date)));
					$watched=0;
					
					#Update freetrials table
					$this->db->trans_start();
		
					$dat=array(
						'network' => $this->db->escape_str($network),
						'msisdn' => $msisdn,
						'email' => $this->db->escape_str($email),							
						'triedfree' => 1,
						'trialdate' => $subscribe_date,
						'trialexpire' => $this->db->escape_str($exp_date),
						'trialdays' => $duration
					);
					
					$this->db->insert('freetrials', $dat);
					$this->db->trans_complete();					
					############# freetrials ##############
					
					
					
					#Save Subscription Record
					$this->db->trans_start();
		
					$dat=array(
						'subscriptionId' => $subscriptionId,
						'email' => $this->db->escape_str($email),
						'network' => $this->db->escape_str($network),
						'msisdn' => $msisdn,
						'plan' => $this->db->escape_str($plan),
						'duration' => $this->db->escape_str($duration),
						'amount' => $this->db->escape_str($amount),
						'autobilling' => $this->db->escape_str($autobilling),
						'subscribe_date' => $subscribe_date,
						'exp_date' => $this->db->escape_str($exp_date),
						'videos_cnt_watched' => $watched,
						'videos_cnt_to_watch' => $this->db->escape_str($videos_cnt_to_watch),
						'subscriptionstatus' => 1
					);	
					
					
					$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0 )#There is active subscription
					{
						$row = $query->row();
						
						if ($row->subscriptionstatus==0)
						{
							
							$where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
							$this->db->where($where);
							$this->db->update('subscriptions', $dat); 
						}
					}else
					{
						$this->db->insert('subscriptions', $dat); 	
					}						
					
					
					$this->db->trans_complete();					
					
					#Create record in watchlists table
					$this->db->trans_start();
					$dat=array('subscriptionId' => $subscriptionId, 'videolist' => '');			
					$this->db->insert('watchlists', $dat);
					$this->db->trans_complete();
					
					$Msg="Signup was successful. Trial account was also created. Details: Network => ".$network."; MSISDN => ".$msisdn."; Email => ".$email."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
				}
				
				
				$rows = array(
					'status' => 'You have registered successfully.<br>A trial account has been created for you.<br>This trial account allows you to watch 3 free videos and it is valid for 2 days.',
					'Flag'=>'OK',
					'email' =>  $email
					);
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($name,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'SIGNUP SUBSCRIBER',$activationCode);
		}
				
		echo json_encode($rows);
	}
	
	
	public function Confirmsignup()#Registration Complete
	{
		$name=''; $email=''; $flag='';
		
		$parameters = $this->uri->uri_to_assoc();
		if ($parameters['name']) $name = urldecode($parameters['name']);
		if ($parameters['email']) $email = urldecode($parameters['email']);
		if ($parameters['flag']) $flag = $parameters['flag'];
		if ($parameters['f']) $fb = $parameters['f'];
		
		#$file = fopen('aaa.txt',"w"); fwrite($file,"Name=".$name."\nEmail=".$email."\nFlag=".$flag); fclose($file);	
		
		if ($flag=='OK')
		{
			$data['PanelTheme']="panel panel-success";		
			$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Signup Confirmation';
			
			if (trim($name) != '')
			{
				if (trim(strtolower($fb))=='y')
				{
					$data['ConfirmInfo']='<span><strong>Congratulations '.$name.'!</strong> You have successfully signed up with LaffHub.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub home page.<br><br>Your login mail is <b>'.$email.'</b>!</span>';
				}else
				{
					$data['ConfirmInfo']='<span><strong>Hello '.$name.'!</strong> You have successfully signed up with LaffHub.<br><br>However, you will not be able to login until you activate your account. Please confirm the mail sent to <b>'.$email.'</b> to activate your account!</span>';	
				}
				
			}else
			{
				if (trim(strtolower($fb))=='y')
				{
					'<span><strong>Congratulations!</strong> You have successfully signed up with LaffHub.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub home page.<br><br>Your login mail is <b>'.$email.'</b>!</span>';
				}else
				{
					$data['ConfirmInfo']='<span>You have successfully signed up with LaffHub.<br><br>However, you will not be able to login until you activate your account. Please confirm the mail sent to <b>'.$email.'</b> to activate your account!</span>';
				}				
			}	
		}else
		{
			$data['PanelTheme']="panel panel-danger";
			$data['ConfirmTitle']='<i class="fa fa-times-circle"></i> Signup Confirmation';
			
			if (trim($name) != '')
			{
				$data['ConfirmInfo']="<strong>Sorry ".$name."!</strong> You account signup was not successful. Please start the signup process again.";	
			}else
			{
				$data['ConfirmInfo']="<strong>Sorry!</strong> You account signup was not successful. Please start the signup process again.";	
			}
		}
		
		$this->load->view('confirmview_view',$data);
	}#End Of Confirmsignup functions
	
	public function ActivateUser()
	{
		$parameters = $this->uri->uri_to_assoc();
		$activationCode = $parameters['cd'];
		
		$name=''; $email=''; $sta='0'; $reg_success=false;
				
		#Check for the code in the db
		$sql = "SELECT * FROM subscribers WHERE (sha1(email)='".$this->db->escape_str($activationCode)."')";
		
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
					
			if ($row->name) $name=$row->name;
			if ($row->email) $email=$row->email;
			if ($row->accountstatus) $sta=$row->accountstatus;
							
			if ($sta != 1)
			{
				$this->db->trans_start();
	
				$dat=array('accountstatus' => 1);	
													
				$this->db->where('sha1(email)', $activationCode);				
				$this->db->update('subscribers', $dat);								
				$this->db->trans_complete();
							
				$Msg='';
	
				if ($this->db->trans_status() === FALSE)
				{		
					if ($name)
					{
						$Msg="Subscriber, ".$name.", attempted activating the account with email <b>".$email."</b> but failed.";	
					}else
					{
						$Msg="Subscriber attempted activating the account with email <b>".$email."</b> but failed.";	
					}
					
					
					$rows[] = "Account activation was not successful.";
				}else
				{
					$Msg="Subscriber with email <b>".$email."</b> successfully activated his/her account.";
										
					$rows[] = "<p>Your account has been successfully activated. You can log in to your account dashboard to modify any data and to carry out transactions.</p>";
					
					$reg_success=true;
				}	
			}else
			{
				if ($name)
				{
					$Msg="Subscriber, ".$name.", attempted activating an already activated account with email <b>".$email."</b> but failed.";
				}else
				{
					$Msg="Subscriber attempted activating an already activated account with email <b>".$email."</b> but failed.";
				}
				
				$rows[] = "Account has already been activated.";
			}
			
			if ($name) $nm=$_SESSION['subscriber_name']; else $nm=$email;
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$this->getdata_model->LogDetails($nm,$Msg,$em,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ACTIVATED SUBCRIBER',$activationCode);
			
					
			if ($reg_success==true)
			{
				$data['PanelTheme']="panel panel-success";		
				$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Account Activation';
				
				if (trim($name) != '')
				{
					$data['ConfirmInfo']='<span><strong>Congratulations '.$name.'!</strong> You have successfully activated  your account with LaffHub.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub home page.<br><br>Your login mail is <b>'.$email.'</b>!</span>';
				}else
				{
					$data['ConfirmInfo']='<span><strong>Congratulations!</strong> You have successfully activated  your account with LaffHub.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub home page.<br><br>Your login mail is <b>'.$email.'</b>!</span>';
				}	
			}else
			{
				$data['PanelTheme']="panel panel-danger";
				$data['ConfirmTitle']='<i class="fa fa-times-circle"></i> Account Activation';
				
				if (trim($name) != '')
				{
					$data['ConfirmInfo']="<strong>Sorry ".$name."!</strong> You account activation was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";
				}else
				{
					$data['ConfirmInfo']="<strong>Sorry!</strong> You account activation was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";
				}
			}			
			
			$this->load->view('confirmview_view',$data);
		}
		
	}#End Of ActivateUser functions
	
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
		
		$this->load->view('signup_view',$data);#Fail Page	
	}
}
