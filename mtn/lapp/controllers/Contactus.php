<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');

class Contactus extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
	 
	public function ProcessMessage()
	{
		$name=''; $email=''; $message=''; $subject='';

		if ($this->input->post('name')) $name = trim($this->input->post('name'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('message')) $message = trim($this->input->post('message'));
		if ($this->input->post('subject')) $subject = trim($this->input->post('subject'));
		
		$from='admin@laffhub.com';
		$to='support@laffhub.com';
		$Cc='';
		
		$img=base_url()."emaillogo.png";
		
		$emailmsg='
			<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" />
			<br><br>
			Dear Support,<br><br>
			
			A contact message has been sent. Below are details of the message:<br><br>
			
			<b>Sender Name:</b> '.$name.'<br>
			<b>Email:</b> '.$email.'<br>
			<b>Subject:</b> '.$subject.'<br>
			<b>Message:</b><br><font color="#FF0000">'.$message.'</font><br>';
			
		$altemailmsg='
			Dear Support, 
			
			A contact message has been sent. Below are details of the message:
			
			Sender Name: '.$name.'
			Email: '.$email.'
			Subject: '.$subject.'
			Message: '.$message;
		
		$ret=$this->getdata_model->SendEmail($from,$to,$subject,$Cc,$emailmsg,$altemailmsg,'LaffHub Admin');
		
		if (trim(strtoupper($ret))=='OK')
		{
			echo 'OK';
		}else
		{
			echo 'Could Not Send The Contact Message. Please Retry.';
		}		
	}
	
	public function ComputeCaptcha()
	{
		$x=rand(1,13);
		$y=rand(1,13);
		
		echo $x.'|'.$y;
	}
	
	public function index()
	{
		if (!$_SESSION['subscriber_email'])
		{
			redirect('Home');
		}else
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
			$data['Categories']=$this->getdata_model->GetCategories();
					
			$this->load->view('contactus_view',$data);	
		}
	}
}
