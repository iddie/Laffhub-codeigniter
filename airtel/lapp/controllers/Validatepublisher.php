<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Validatepublisher extends CI_Controller {	
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
		$activationCode = $parameters['cd'];
				
		#Check for the code in the db
		$sql = "SELECT * FROM publishers WHERE (sha1(publisher_email)='".$this->db->escape_str($activationCode)."')";
		
		$query = $this->db->query($sql);
			
		$nm='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
					
			if ($row->publisher_name)
			{
				$nm=$row->publisher_name;
				$this->user=$nm;
			}
			
			if ($row->publisher_email) $em=$row->publisher_email;
			if ($row->publisher_phone) $ph=$row->publisher_phone;
			if ($row->publisher_status) $sta=$row->publisher_status;
							
			if ($sta != 1)
			{
				$this->db->trans_start();
	
				$dat=array('publisher_status' => 1);	
													
				$this->db->where('sha1(publisher_email)', $activationCode);				
				$this->db->update('publishers', $dat);								
				$this->db->trans_complete();
							
				$Msg='';
	
				if ($this->db->trans_status() === FALSE)
				{					
					$Msg=$nm." attempted activating the publisher's account with email <b>".$em."</b> but failed.";
					
					$rows[] = "Publisher account activation was not successful.";
				}else
				{
					$Msg="Publisher with email <b>".$em."</b> successfully activated his/her account.";
										
					$rows[] = "<p>Your account has been successfully activated. You can log in to your account dashboard to modify any data and to carry out transactions.</p>";
					
					$this->reg_success=true;
				}	
			}else
			{
				$Msg=$nm." attempted activating an already activated account with email <b>".$em."</b> but failed.";
				$rows[] = "Account has already been activated.";
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$this->getdata_model->PublisherLogDetails($nm,$Msg,$em,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ACTIVATED PUBLISHER',$activationCode);
			
			$this->index();
		}
		
	}#End Of Register functions
			
	public function index()
	{		
		if ($this->reg_success==true)
		{
			$data['RegisterInfo']='<strong>Congratulations '.$this->user.'!</strong> You have successfully activated your account. Click on the link below to go to the publisher login page.<p>&nbsp;</p><p><a href="'.site_url("Pubhome").'" class="btn btn-flat btn-primary">HOME</a></p>';
			
			$this->load->view('registersuccess_view',$data);#Success Page
		}else
		{
			$data['RegisterInfo']='<strong>Sorry!</strong> Your account activation was not successful. If you have previously activated the account, clicking on the link will now be of no effect.';
			
			$this->load->view('registerfail_view',$data);#Fail Page
		}	
	}
}
