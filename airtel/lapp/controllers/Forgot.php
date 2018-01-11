<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function ForgotPwd()
	{
		$email=''; $usertype=''; $nm='';;
		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('usertype')) $usertype = trim($this->input->post('usertype'));
		
		$dt=date('Y-m-d H:i:s');
				
		#Check for the email
		if (strtolower($usertype)=='subscriber')
		{
			$sql="SELECT * FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
			
			$nm=$this->getdata_model->GetSubscriberName($email);
		}elseif (strtolower($usertype)=='publisher')
		{
			$sql="SELECT * FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."')";	
			
			$nm=$this->getdata_model->GetPublisherName($email);
		}
		
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()== 0 )
		{
			$ret="The email you entered (<b>".$email."</b>) does not exist in our database. Please check your email entry and the user type selected.";
		}else
		{
			$expdt=date('Y-m-d H:i:s',strtotime($dt.' 1day'));
			
			#Check if there is an existing request
			$sql="SELECT * FROM reset_pwd WHERE (email='".$this->db->escape_str($email)."') AND (usertype='".$this->db->escape_str($usertype)."')";
			
			$query = $this->db->query($sql);
			
			$Flag=false;
			
			if ( $query->num_rows()> 0 )
			{
				$row = $query->row();
			
				if ($row->status==1)
				{
					$ret="You have already requested for password reset and the link is still active. Please click on the <b>RESET YOUR PASSWORD</b> link in the email sent to you.";
				}else
				{
					$Flag=true;
				}
			}else
			{
				$Flag=true;
			}
			
			if ($Flag==true)
			{
				$this->db->trans_start();
				
				$dat=array(
					'usertype' => $this->db->escape_str($usertype),
					'email' => $this->db->escape_str($email),
					'request_date' => $this->db->escape_str($dt),
					'expire_date' => $this->db->escape_str($expdt),
					'status' => 1
					);
				
				$this->db->insert('reset_pwd', $dat);
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{					
					$ret = 'Password Reset Request Was Not Successful. Please Try Again.';
				}else
				{
					if (strtolower($usertype)=='subscriber')
					{
						$Msg='Subscriber requested for a password reset successfully!';
					}elseif (strtolower($usertype)=='publisher')
					{
						$Msg='Publisher requested for a password reset successfully!';
					}
					
					$ResetCode=sha1($email);
					$link=base_url()."Resetpwd/Reset/ut/".$usertype."/rc/".$ResetCode;
					
					if (trim($nm)=='') $nm='Subscriber';
				
					#Send Email Notification
					$emailmsg='
						<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub Password Reset" />
						<br><br>
						Dear '.$nm.',<br><br>
						
						We received a request to reset your LaffHub password. Click the link below to choose a new one:<br><br><a href="'.$link.'"><b style="color:#660000;">Reset Your Password</b></a><br><br>
																					
						Please note that this link will expire after 24 hours.<br><br>
						
						Contact  us at <a href="mailto:support@laffhub.com">support@laffhub.com</a> if you need assistance.<br><br>
						
						Yours Faithfully,<br><br>
						
						LaffHub
						';
						
						$altemailmsg='
						Dear '.$nm.',
						
						We received a request to reset your LaffHub password. To reset your password, copy this link and paste in your browser: '.$link.'. 
																					
						Please note that this link will expire after 24 hours. 
						
						Contact  us at support@laffhub.com if you need assistance. 
						
						Yours Faithfully, 
						
						LaffHub
						';
						
						$RT=$this->getdata_model->SendEmail('support@laffhub.com',$email,'LaffHub Password Reset Link','',$emailmsg,$altemailmsg,$nm);
					
					$ret='OK';
					
					$remote_ip=$_SERVER['REMOTE_ADDR'];
					$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
				
					$this->getdata_model->LogDetails($email,$Msg, $email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'PASSWORD RESET LINK','');
				}	
			}
		}
		
		echo $ret;
	}
	
	public function index()
	{			
		#Update password reset table
		$sql="SELECT * FROM reset_pwd";

		$query = $this->db->query($sql);
		#$file = fopen('aaa.txt',"w"); fwrite($file, $query->num_rows()); fclose($file);
		if ( $query->num_rows() > 0 )
		{
			foreach ($query->result() as $rec)
			{
				$id=$rec->id;
				$sta=$rec->status;
				
				if (!$sta) $sta='0';
				
				$expdt=$rec->expire_date;

#$file = fopen('aaa.txt',"w"); fwrite($file, "id=".$id."\nSta=".$sta."\ndt=".$dt."\nexpdt=".$expdt); fclose($file);
								
				#Compare 
				if (($dt >= $expdt) or ($sta=='0'))
				{
					$this->db->trans_start();				
					$this->db->where('id', $id);
					$this->db->delete('reset_pwd');
					$this->db->trans_complete();
				}
			}
		}
		
		$this->load->view('forgot_view',$data);#Fail Page	
	}
}
