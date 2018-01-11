<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Cookiepolicy extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
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
					
			$this->load->view('cookiepolicy_view',$data);	
		}
	}
}
