<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Videos extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
		$this->load->model('videos_model');
	 }
	
	public function LoadVideosJson()
	{
		$ret=$this->videos_model->GetVideos();
		
		$data=array();
		
		if (is_array($ret))
		{#'SELECT','DELETE',video_title,category,'VIDEO',video_status,streaming_link,video_id,filename,video_status
		
#<a href="'.base_url().'videos/'.$row->trailer_filename.'" class="btn btn-lg btn-primary mylightbox" role="button" title="'.$row->title.'" data-group="carousalgroup" data-width="480" data-height="320">Preview</a>
			foreach($ret as $row):
				$status=''; $video='&nbsp;';
								
				if ($row->video_status==1)
				{
					$status='<font color="#249A47">Active</font>';
				}else
				{
					$status='<font color="#BD1111">Non-Active</font>';
				}
				
				if ($row->filename)
				{
					$video='<img style="cursor:pointer;" src="'.base_url().'images/video_icon.png" height="50px" title="Click To View '.strtoupper($row->video_title).'" onclick="ShowVideo(\''.$row->video_title.'\',\''.$row->filename.'\')">';
				}
							
				$tp=array('<img  style="cursor:pointer" src="'.base_url().'images/pencil_icon.png" height="15" title="Edit '.strtoupper($row->video_title).'\'s Record">','<img style="cursor:pointer" src="'.base_url().'images/delete_icon.png" height="15" onclick="DeleteRow(\''.$row->video_id.'\',\''.$row->video_title.'\')" title="Delete '.strtoupper($row->video_title).'">',$row->video_title,$row->category,$video,$status,$row->streaming_link,$row->video_id,$row->filename,$row->video_status);
				
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadVideosJson functions
	
	public function AddVideos()
	{
		$username=''; $firstname=''; $lastname=''; $email=''; $phone='';  $accountstatus='0'; $role='';
		$pwd=''; $Upload_Video='0'; $CreateUser='0'; $SetParameters='0'; $ViewLogReport='0';
		$User=''; $UserFullname='';
		
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('firstname')) $firstname = trim($this->input->post('firstname'));
		if ($this->input->post('lastname')) $lastname = trim($this->input->post('lastname'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('pwd')) $pwd = $this->input->post('pwd');
		if ($this->input->post('accountstatus')) $accountstatus = trim($this->input->post('accountstatus'));		
		if ($this->input->post('role')) $role = trim($this->input->post('role'));
		if ($this->input->post('Upload_Video')) $Upload_Video = trim($this->input->post('Upload_Video'));		
		if ($this->input->post('CreateUser')) $CreateUser = trim($this->input->post('CreateUser'));		
		if ($this->input->post('SetParameters')) $SetParameters = trim($this->input->post('SetParameters'));
		if ($this->input->post('ViewLogReport')) $ViewLogReport = trim($this->input->post('ViewLogReport'));
		
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullname')) $UserFullname = trim($this->input->post('UserFullname'));

		$datecreated=date('Y-m-d H:i:s');
		
		if (!$accountstatus) $accountstatus='0';
		
#username,firstname,lastname,pwd,email,phone,accountstatus,datecreated,role,
#Upload_Video,CreateUser,SetParameters,ViewLogReport
	
		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret = 'User Account Registration Was Not Successful. Username "'.$username.'" Exists In The Database.';
		}else
		{			
			$this->db->trans_start();
									
			$dat=array(
				'username' => $this->db->escape_str($username),
				'firstname' => $this->db->escape_str($firstname),
				'lastname' => $this->db->escape_str($lastname),
				'pwd' => $this->db->escape_str($pwd),
				'email' => $this->db->escape_str($email),
				'phone' => $this->db->escape_str($phone),
				'accountstatus' => $this->db->escape_str($accountstatus),
				'datecreated' => $this->db->escape_str($datecreated),
				'role' => $this->db->escape_str($role),
				'Upload_Video' => $this->db->escape_str($Upload_Video),
				'CreateUser' => $this->db->escape_str($CreateUser),
				'SetParameters' => $this->db->escape_str($SetParameters),
				'ViewLogReport' => $this->db->escape_str($ViewLogReport)
				);							
			
			$this->db->insert('userinfo', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';		
			
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg=$User.'('.$UserFullname.") attempted creating a user account with username '".$username."' but failed.";
				$ret = 'User Account Creation Was Not Successful.';
			}else
			{
				$Msg="User Account Creation Was successful.";				
				
				$ret ='OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullname,$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$remote_ip,$remote_host,'CREATE USER ACCOUNT',$_SESSION['LogIn']);
		}
				
		echo $ret;
	}
	
	public function EditVideos()
	{
		$username=''; $firstname=''; $lastname=''; $email=''; $phone='';  $accountstatus='0'; $role='';
		$pwd=''; $Upload_Video='0'; $CreateUser='0'; $SetParameters='0'; $ViewLogReport='0';
		$User=''; $UserFullname='';
		
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('firstname')) $firstname = trim($this->input->post('firstname'));
		if ($this->input->post('lastname')) $lastname = trim($this->input->post('lastname'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('accountstatus')) $accountstatus = trim($this->input->post('accountstatus'));		
		if ($this->input->post('role')) $role = trim($this->input->post('role'));
		if ($this->input->post('Upload_Video')) $Upload_Video = trim($this->input->post('Upload_Video'));		
		if ($this->input->post('CreateUser')) $CreateUser = trim($this->input->post('CreateUser'));		
		if ($this->input->post('SetParameters')) $SetParameters = trim($this->input->post('SetParameters'));
		if ($this->input->post('ViewLogReport')) $ViewLogReport = trim($this->input->post('ViewLogReport'));
		
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullname')) $UserFullname = trim($this->input->post('UserFullname'));
		
		if (!$accountstatus) $accountstatus='0';
		
#username,firstname,lastname,pwd,email,phone,accountstatus,datecreated,role,
#Upload_Video,CreateUser,SetParameters,ViewLogReport

		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";

//$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);			

		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			$ret = 'Could Not Edit User Account Record. Record Does Not Exist.';
		}else
		{
			$row = $query->row();	
			
			$OldFname=''; $OldLname=''; $OldEmail=''; $OldPhone=''; $OldStatus='0';
			$OldRole=''; $OldUploadVideo='0'; $OldCreateUser='0'; $OldSetParameters='0'; $OldViewLogReport='0';
			
			if (isset($row))
			{ 	
				if ($row->firstname) $OldFname=$row->firstname;
				if ($row->lastname) $OldLname=$row->lastname;
				if ($row->email) $OldEmail=$row->email;
				if ($row->phone) $OldPhone=$row->phone;
				if ($row->accountstatus==1) $OldStatus=$row->accountstatus;
				if ($row->role) $OldRole=$row->role;				
				if ($row->Upload_Video==1) $OldUploadVideo=$row->Upload_Video;				
				if ($row->CreateUser==1) $OldCreateUser=$row->CreateUser;				
				if ($row->SetParameters==1) $OldSetParameters=$row->SetParameters;				
				if ($row->ViewLogReport==1) $OldViewLogReport=$row->ViewLogReport;
			}
			
			$OldValues='First Name='.$OldFname.'; Last Name='.$OldLname.'; Email='.$OldEmail.'; Phone='.$OldPhone.'; Account Status='.$OldStatus.'; Role='.$OldRole.'; Upload Video='.$OldUploadVideo.'; Create User='.$OldCreateUser.'; Set Parameters='.$OldSetParameters.'; View Log Report='.$OldViewLogReport;
			
			$NewValues='First Name='.$firstname.'; Last Name='.$lastname.'; Email='.$email.'; Phone='.$phone.'; Account Status='.$accountstatus.'; Role='.$role.'; Upload Video='.$Upload_Video.'; Create User='.$CreateUser.'; Set Parameters='.$SetParameters.'; View Log Report='.$ViewLogReport;
			
			$this->db->trans_start();
									
			$dat=array(
				'firstname' => $this->db->escape_str($firstname),
				'lastname' => $this->db->escape_str($lastname),
				'email' => $this->db->escape_str($email),
				'phone' => $this->db->escape_str($phone),
				'accountstatus' => $this->db->escape_str($accountstatus),
				'role' => $this->db->escape_str($role),
				'Upload_Video' => $this->db->escape_str($Upload_Video),
				'CreateUser' => $this->db->escape_str($CreateUser),
				'SetParameters' => $this->db->escape_str($SetParameters),
				'ViewLogReport' => $this->db->escape_str($ViewLogReport)
				);							
			
			$this->db->where('username', $username);
			$this->db->update('userinfo', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
		
			if ($this->db->trans_status() === FALSE)
			{					
				$Msg=$User.'('.$UserFullname.") attempted editing user account record with username '".$username."' but failed.";
				
				$ret = 'User Account Record Could Not Be Edited.';
			}else
			{
				$Msg="User account record has been edited successfully by ".strtoupper($User.'('.$UserFullname)."). Old Values => ".$OldValues.". Updated values => ".$NewValues;				
				
				$ret ='OK';	
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($UserFullname,$Msg,$_SESSION['username'],date('Y-m-d H:i:s'),$remote_ip,$remote_host,'EDIT USER ACCOUNT',$_SESSION['LogIn']);
		}
				
		echo $ret;
	}
	
	public function DeleteVideos()
	{
		$UserFullName=''; $fullname=''; $User=''; $username='';
		
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('fullname')) $fullname = trim($this->input->post('fullname'));
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullName')) $UserFullName = trim($this->input->post('UserFullName'));
		
		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			
			$this->db->trans_start();
			$this->db->delete('userinfo', array('username' => $username)); 				
			$this->db->trans_complete();
						
			$Msg='';
		
			if ($this->db->trans_status() === FALSE)
			{
				$Msg=$User.'('.$UserFullName.") attempted deleting user account record with username '".$username."' but failed.";
				
				$ret = 'User Account Record Could Not Be Deleted.';
			}else
			{
				$Msg="User account record ".strtoupper($username).'('.strtoupper($fullname).") has been edited successfully by ".strtoupper($User.'('.$UserFullName).").";
				
				$ret="OK";
			}
			
			$this->getdata_model->LogDetails($UserFullName,$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETE USER ACCOUNT',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete User Account Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}
	
	public function index()
	{
		//$file = fopen('aaa.txt',"w"); fwrite($file, $_SESSION['email']); fclose($file);
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
			
			$data['VideoCategories'] = $this->getdata_model->GetVideoCategories();
			$data['OldPassword']=$_SESSION['pwd'];
			
			$this->load->view('videos_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
