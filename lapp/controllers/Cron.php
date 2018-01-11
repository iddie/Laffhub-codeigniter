<?php

date_default_timezone_set('Africa/Lagos');

class Cron extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
		
		$ret=$this->getdata_model->CheckForActiveFeed();		
	 }
	
	public function index()
	{
		
	}
}
?>