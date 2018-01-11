<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
		$css=$this->getdata_model->GetCSS();
			
		if ($css) $data['CSS']=$css; else $data['CSS']='style.css';
			
		$data['Categories']=$this->getdata_model->GetCategories();
		$this->load->view('events_view',$data);#Fail Page	
	}
}
