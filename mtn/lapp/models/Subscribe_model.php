<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_STRICT);

date_default_timezone_set('Africa/Lagos');

class Subscribe_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		
		$this->load->model('getdata_model');
	}	
		
	
}
?>
