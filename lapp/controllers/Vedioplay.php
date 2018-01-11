<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Vedioplay extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
		$data['Categories']=$this->getdata_model->GetCategories();
		$this->load->view('vedioplay_view',$data);#Fail Page	
	}
}
