<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Validateuser extends CI_Controller {	
	private $reg_success=false;
	private $user='';
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function Register()
	{
		$parameters = $this->uri->uri_to_assoc();
		$activationCode = $parameters['MV'];
		$usertype = $parameters['TP'];##cust or prv
		
		#Check for the code in the db
		if (trim(strtolower($usertype))=='cust')
		{
			$sql = "SELECT * FROM consumers WHERE (sha1(email)='".$this->db->escape_str($activationCode)."')";
		}elseif (trim(strtolower($usertype))=='prv')
		{
			$sql = "SELECT * FROM providers WHERE (sha1(email)='".$this->db->escape_str($activationCode)."')";
		}elseif ((trim(strtolower($usertype))=='indboth') || (trim(strtolower($usertype))=='corboth'))
		{
			$sql = "SELECT * FROM providers WHERE (sha1(email)='".$this->db->escape_str($activationCode)."')";
			$csql = "SELECT * FROM consumers WHERE (sha1(email)='".$this->db->escape_str($activationCode)."')";
		}
		
		if ((trim(strtolower($usertype))=='cust') || (trim(strtolower($usertype))=='prv'))
		{
			$query = $this->db->query($sql);
			
			$nm='';
			
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
						
				if (isset($row))
				{
					if (trim(strtolower($usertype))=='cust')
					{
						$nm=$row->name;
					}elseif (trim(strtolower($usertype))=='prv')
					{
						if ($row->companyname)
						{
							$nm=$row->companyname.'('.$row->name.')';
						}else
						{
							$nm=$row->name;
						}
					}
					
					
					$em=$row->email;
					$sta=$row->accountstatus;
					
					$this->user=$nm;
				}
				
				if ($sta != 1)
				{
					$this->db->trans_start();
		
					$dat=array('accountstatus' => 1);	
														
					$this->db->where('sha1(email)', $activationCode);
					
					if (trim(strtolower($usertype))=='cust')
					{
						$this->db->update('consumers', $dat);
					}elseif (trim(strtolower($usertype))=='prv')
					{
						$this->db->update('providers', $dat);
					}
					
									
					$this->db->trans_complete();
								
					$Msg='';
		
					if ($this->db->trans_status() === FALSE)
					{					
						if (trim(strtolower($usertype))=='cust')
						{
							$Msg=$nm." attempted activating the consumer account with email ".$em." but failed.";
						}elseif (trim(strtolower($usertype))=='prv')
						{
							$Msg=$nm." attempted activating the service provider account with email ".$em." but failed.";
						}
						
						$rows[] = "Account activation was not successful.";
					}else
					{
						if (trim(strtolower($usertype))=='cust')
						{
							$Msg="Consumer with email ".$em." successfully activated his/her account.";
						}elseif (trim(strtolower($usertype))=='prv')
						{
							$Msg="Service provider with email ".$em." successfully activated his/her account.";
						}
											
						$rows[] = "<p>Your account has been successfully activated. You can log in to your account dashboard to modify any data and to carry out transactions.</p>";
						
						$this->reg_success=true;
					}	
				}else
				{
					$Msg=$nm." attempted activating an already activated account with email ".$em." but failed.";
					$rows[] = "Account has already been activated.";
				}
				
				$remote_ip=$_SERVER['REMOTE_ADDR'];
				$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
				
				if (trim(strtolower($usertype))=='cust')
				{
					$this->getdata_model->LogDetails($nm,$Msg,$em,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ACTIVATED CONSUMER',$activationCode);
				}elseif (trim(strtolower($usertype))=='prv')
				{
					$this->getdata_model->LogDetails($nm,$Msg,$em,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ACTIVATED SERVICE PROVIDER',$activationCode);
				}
				
				$this->index();
			}	
		}elseif ((trim(strtolower($usertype))=='indboth') || (trim(strtolower($usertype))=='corboth'))
		{
			$query = $this->db->query($sql);#Provider
			$cquery = $this->db->query($csql);#Customer
			
			$nm=''; $em=''; $sta='';
			
			//Provider
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
						
				if (isset($row))
				{
					if (trim(strtolower($usertype))=='corboth')
					{
						if ($row->companyname)
						{
							$nm=$row->companyname.'('.$row->name.')';
						}else
						{
							$nm=$row->name;
						}
					}else
					{
						$nm=$row->name;
					}
					
					$em=$row->email;
					$sta=$row->accountstatus;
					
					$this->user=$nm;
				}
			}
			
			if ($nm=='')
			{
				if ($cquery->num_rows() > 0 )#Customer
				{
					$row = $cquery->row();
							
					if (isset($row))
					{
						if (trim(strtolower($usertype))=='corboth')
						{
							if ($row->companyname)
							{
								$nm=$row->companyname.'('.$row->name.')';
							}else
							{
								$nm=$row->name;
							}
						}else
						{
							$nm=$row->name;
						}
						
						$em=$row->email;
						$sta=$row->accountstatus;
						
						$this->user=$nm;
					}
				}		
			}
			
			if ($sta != 1)
			{
				$this->db->trans_start();
	
				$dat=array('accountstatus' => 1);	
													
				$this->db->where('sha1(email)', $activationCode);
				
				$this->db->update('consumers', $dat);
				$this->db->update('providers', $dat);
												
				$this->db->trans_complete();
				
						
				$Msg='';
	
				if ($this->db->trans_status() === FALSE)
				{					
					$Msg=$nm." attempted activating the provider/consumer account with email ".$em." but failed.";
					
					$rows[] = "Account activation was not successful.";
				}else
				{
					$Msg="Service provider/customer with email ".$em." successfully activated his/her account.";
										
					$rows[] = "<p>Your account has been successfully activated. You can log in to your account dashboard to modify any data and to carry out transactions.</p>";
					
					$this->reg_success=true;
				}	
			}else
			{
				$Msg=$nm." attempted activating an already activated account with email ".$em." but failed.";
				$rows[] = "Account has already been activated.";
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$this->getdata_model->LogDetails($nm,$Msg,$em,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ACTIVATED PROVIDER/CONSUMER',$activationCode);
			
			$this->index();
		}
		
	}#End Of Register functions
			
	public function index()
	{		
		$this->getdata_model->KillSleepingConnections();
				
		if ($this->reg_success==true)
		{
			$data['RegisterInfo']='<strong>Congratulations '.$this->user.'!</strong> You have successfully activated your account. You will be redirected to the home page to sign in to the MOOV portal in 30 seconds or you can click on the link below to go to the home page immediately.<p>&nbsp;</p><p><a href="'.site_url("Home").'" class="btn btn-flat btn-primary">HOME</a></p>';
			
			$this->load->view('registersuccess_view',$data);#Success Page
		}else
		{
			$data['RegisterInfo']='<strong>Sorry!</strong> Your account activation was not successful. If you have previously activated the account, clicking on the link will now be of no effect. You will be redirected to the home page in 30 seconds or you can click on the link below to go to the home page immediately.<p>&nbsp;</p><p><a href="'.site_url("Home").'" class="btn btn-flat btn-primary">HOME</a></p>';
			
			$this->load->view('registerfail_view',$data);#Fail Page
		}	
	}
}
