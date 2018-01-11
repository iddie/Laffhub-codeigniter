<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
	 }
		
	public function index()
	{
		$data['Network']=$this->getdata_model->GetNetwork();
		$data['Phone']=$this->getdata_model->GetMSISDN();
			
		if ((!$data['Network']) or (!$data['Phone']))
		{
			redirect('Subscriberhome');
		}else
		{
			$data['Categories']=$this->getdata_model->GetCategories();
			$data['CategoryVideos']=$this->getdata_model->GetTotalCategoryVideos();
			$data['MostPopulatCategories']=$this->getdata_model->GetMostPopularCategories();
			$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
			$data['Comedians']=$this->getdata_model->GetComedians();
			$this->load->view('categories_view',$data);	
		}	
	}
}
