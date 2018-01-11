<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Rc extends CI_Controller {	
	private $reg_success=false;
	private $name='';
	private $email='';
	private $facebook='';
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	
	public function RegCom()#Registration Complete
	{#http://laffhub.com/Rc/RegCom/name/Idongesit%20Akpan/email/idongesit_a@yahoo.com/flag/OK/f/n
		$parameters = $this->uri->uri_to_assoc();
		$this->name = urldecode($parameters['name']);
		$this->email = urldecode($parameters['email']);
		$this->facebook = urldecode($parameters['f']);
		$flag = $parameters['flag'];
		
		if (trim(strtoupper($flag))=='OK') $this->reg_success=true;
		
		$this->index();
	}#End Of RegisterComplete functions
	
	
	public function index()
	{
		if ($this->reg_success==true)
		{
			if (trim(strtolower($this->facebook))=='y')
			{
				$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Signup Confirmation';
			}else
			{
				$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Account Activation';
			}
			
			$data['PanelTheme']="panel panel-success";		
			
			
			if (trim($this->name) != '')
			{
				if (trim(strtolower($this->facebook))=='y')
				{
					$data['ConfirmInfo']='<span><strong>Congratulations '.$this->name.'!</strong> You have successfully signed up successfully on LaffHub as a publisher.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub Publisher home page.</span>';	
				}else
				{
					$data['ConfirmInfo']='<span><strong>Congratulations '.$this->name.'!</strong> You have successfully registered your account. However, you will not be able to login until you activate your account. Please confirm the mail sent to <b>'.$this->email.'</b> to activate your account!</span>';	
				}				
			}else
			{
				if (trim(strtolower($this->facebook))=='y')
				{
					$data['ConfirmInfo']='<span><strong>Congratulations!</strong> You have successfully signed up successfully on LaffHub as a publisher.<br><br>Click on the <b>HOME</b> button to proceed to the LaffHub Publisher home page.</span>';
				}else
				{
					$data['ConfirmInfo']='<span><strong>Congratulations!</strong> You have successfully registered your account. However, you will not be able to login until you activate your account. Please confirm the mail sent to <b>'.$this->email.'</b> to activate your account!</span>';	
				}				
			}	
		}else
		{
			$data['PanelTheme']="panel panel-danger";
			
			if (trim(strtolower($this->facebook))=='y')
			{
				$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Signup Confirmation';
			}else
			{
				$data['ConfirmTitle']='<i class="glyphicon glyphicon-ok-sign"></i> Account Activation';
			}
			
			if (trim($this->name) != '')
			{
				if (trim(strtolower($this->facebook))=='y')
				{
					$data['ConfirmInfo']="<strong>Sorry ".$this->name."!</strong> Signup was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";	
				}else
				{
					$data['ConfirmInfo']="<strong>Sorry ".$this->name."!</strong> You account activation was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";
				}				
			}else
			{
				if (trim(strtolower($this->facebook))=='y')
				{
					$data['ConfirmInfo']="<strong>Sorry!</strong> Signup was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";
				}else
				{
					$data['ConfirmInfo']="<strong>Sorry!</strong> You account activation was not successful. If you have previously activated the account, clicking on the link in your email will now be of no effect.";	
				}
			}
		}
		
		if (trim(strtolower($this->facebook))=='y')
		{	
			$this->load->view('pubconfirmview_view',$data);
		}else
		{
			$this->load->view('confirmview_view',$data);
		}
	}
}
