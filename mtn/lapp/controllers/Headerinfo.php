<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Headerinfo extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('getdata_model');
    }

    public function index()
    {
        $this->load->library('session');
        $header = getallheaders();
        $data['header']= $header;
        $this->load->view('headerinfo', $data);
    }
}
