<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribers extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	
	public function GetSubscribers()
	{
		$network='';
		
		if ($this->input->post('network')) $network = $this->input->post('network');
						
		$sql="SELECT @s:=@s+1 AS SN,phone,network,subscriber_status FROM subscribers,(SELECT @s:= 0) AS s";
		
		if (trim($network) != '') $sql .= " WHERE (TRIM(network)='".trim($network)."')";
		
		$sql .= " ORDER BY network,phone";

#$file = fopen('aaa.txt',"w"); fwrite($file, $sql."\n"); fclose($file);	
		$query = $this->db->query($sql);
		
		$results = $query->result_array();
		
		if ($results)
		{
			$data=array();

			if (is_array($results))
			{
				foreach($results as $row):
					$status='';
					 
					 if ($row['subscriber_status']==1) $status='Active'; else $status='Not Active';
				
					$tp=array($row['SN'],$row['phone'],$row['network'],$status);
					$data[]=$tp;
				endforeach;
			}
			
			print_r(json_encode($data));
			//echo json_encode($data);
		}else
		{
			print_r(json_encode($results));
		}
	}
		
	public function index()
	{		
		if ($_SESSION['username'])
		{
			$data['username']=$_SESSION['username'];
			
			$data['Upload_Video']='0'; $data['CreateUser']='0'; $data['SetParameters']='0';
			$data['accountstatus'] = '0'; $data['ViewLogReport']='0';
						
			if ($_SESSION['username']) $data['username'] = $_SESSION['username'];
			if ($_SESSION['firstname']) $data['firstname'] = $_SESSION['firstname'];
			if ($_SESSION['lastname']) $data['lastname'] = $_SESSION['lastname'];
			if ($_SESSION['UserFullName']) $data['UserFullName'] = $_SESSION['UserFullName'];
			if ($_SESSION['pwd']) $data['pwd'] = $_SESSION['pwd'];
			if ($_SESSION['phone']) $data['phone'] = $_SESSION['phone'];
			if ($_SESSION['email']) $data['email'] = $_SESSION['email'];
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['accountstatus']) $data['accountstatus'] = $_SESSION['accountstatus'];
			if ($_SESSION['role']) $data['role'] = $_SESSION['role'];
			if ($_SESSION['Upload_Video']==1) $data['Upload_Video'] = $_SESSION['Upload_Video'];
			if ($_SESSION['CreateUser']==1) $data['CreateUser'] = $_SESSION['CreateUser'];
			if ($_SESSION['SetParameters']== 1) $data['SetParameters'] = $_SESSION['SetParameters'];
			if ($_SESSION['ViewLogReport']== 1) $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
			
			if ($_SESSION['companyname']) $data['companyname'] = $_SESSION['companyname'];
			if ($_SESSION['companyemail']) $data['companyemail'] = $_SESSION['companyemail'];
			if ($_SESSION['companyphone']) $data['companyphone'] = $_SESSION['companyphone'];
			if ($_SESSION['website']) $data['website'] = $_SESSION['website'];
			if ($_SESSION['companylogo']) $data['companylogo'] = $_SESSION['companylogo'];
			if ($_SESSION['RefreshDuration']) $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
			if ($_SESSION['default_network']) $data['default_network'] = $_SESSION['default_network'];
			if ($_SESSION['no_of_videos_per_day']) $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
						
			$data['OldPassword']=$_SESSION['pwd'];
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $data['OldPassword']); fclose($file);	
			
			$this->load->view('subscribers_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
