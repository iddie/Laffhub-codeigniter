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
		if (!$_SESSION['subscriber_email'])
		{
			redirect('Home');
		}else
		{
			$data['subscriber_email']=$_SESSION['subscriber_email'];
			
			$data['Categories']=$this->getdata_model->GetCategories();
			$data['CategoryVideos']=$this->getdata_model->GetTotalCategoryVideos();
			$data['MostPopulatCategories']=$this->getdata_model->GetMostPopularCategories();
			$data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
			$data['Comedians']=$this->getdata_model->GetComedians();
			$this->load->view('categories_view',$data);#Fail Page
		}
	}
}
