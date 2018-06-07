<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Africa/Lagos');

$this->load->view('template/header');
$this->load->view($content);
$this->load->view('template/footer');
?>