<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notify extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
	 }
		
	public function aoc()
	{
			echo "AIRTEL AOC CallBack";
	}
}
