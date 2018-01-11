<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	public function LoadUsersJson()
	{
		$sql = "SELECT '' AS SN,DATE_FORMAT(datecreated,'%d %b %Y') AS date_created,CONCAT(firstname,' ',lastname) AS fullname ,userinfo.* FROM userinfo,(SELECT @s:= 0) AS s ORDER BY firstname,lastname";
		
		$query = $this->db->query($sql);
		
		$ret=$query->result();
		
		$data=array();
		
		if (is_array($ret))
		{
			foreach($ret as $row):
				$perm=''; $accountstatus='';
				
				if ($row->AddItem==1) $perm='Add Item';
				
				if ($row->EditItem==1)
				{
					if (trim($perm=='')) $perm='Edit Item'; else $perm .= ', Edit Item';
				}
				
				if ($row->DeleteItem==1)
				{
					if (trim($perm=='')) $perm='Delete Item'; else $perm .= ', Delete Item';
				}
				
				if ($row->CreateUser==1)
				{
					if (trim($perm=='')) $perm='Create Users'; else $perm .= ', Create Users';
				}
				
				if ($row->ClearLogFiles==1)
				{
					if (trim($perm=='')) $perm='Clear Log Files'; else $perm .= ', Clear Log Files';
				}
				
				if ($row->CreatePublisher==1)
				{
					if (trim($perm=='')) $perm='Create Publisher'; else $perm .= ', Create Publisher';
				}
			
				if ($row->CreateComedian==1)
				{
					if (trim($perm=='')) $perm='Create Comedian'; else $perm .= ', Create Comedian';
				}
				
				if ($row->SetParameters==1)
				{
					if (trim($perm=='')) $perm='Set Parameters'; else $perm .= ', Set Parameters';
				}
				
				if ($row->ViewLogReport==1)
				{
					if (trim($perm=='')) $perm='View Log Reports'; else $perm .= ', View Log Reports';
				}
				
				if ($row->ViewReports==1)
				{
					if (trim($perm=='')) $perm='View Reports'; else $perm .= ', View Reports';
				}
				
				if ($row->CreateCategory==1)
				{
					if (trim($perm=='')) $perm='Create Category'; else $perm .= ', Create Category';
				}
				
				if ($row->CreateEvents==1)
				{
					if (trim($perm=='')) $perm='Create Events'; else $perm .= ', Create Events';
				}
				
				if ($row->ApproveVideo==1)
				{
					if (trim($perm=='')) $perm='Approve Videos'; else $perm .= ', Approve Videos';
				}
				
				if ($row->ApproveComment==1)
				{
					if (trim($perm=='')) $perm='Approve Comments'; else $perm .= ', Approve Comments';
				}
				
				if ($row->AddBanners==1)
				{
					if (trim($perm=='')) $perm='Add Banners'; else $perm .= ', Add Banners';
				}
				
				if ($row->AddMobileOperator==1)
				{
					if (trim($perm=='')) $perm='Add Mobile Operator'; else $perm .= ', Add Mobile Operator';
				}
				
				if ($row->Upload_Video==1)
				{
					if (trim($perm=='')) $perm='Upload Videos'; else $perm .= ', Upload Videos';
				}
				
				if ($row->AddArticlesToBlog==1)
				{
					if (trim($perm=='')) $perm='Add Articles To Blog'; else $perm .= ', Add Articles To Blog';
				}
				
				if ($row->CheckDailyReports==1)
				{
					if (trim($perm=='')) $perm='Check Daily Reports'; else $perm .= ', Check Daily Reports';
				}
				
				if ($row->ModifyStaticPage==1)
				{
					if (trim($perm=='')) $perm='Modify Static Page'; else $perm .= ', Modify Static Page';
				}

#[EDIT],[DELETE],Username,Name,Email,Phone,Status,Role,[Permissions],Datecreated,Pwd,
//AddItem,EditItem,DeleteItem,CreateUser,ClearLogFiles,CreatePublisher,CreateComedian,SetParameters,ViewLogReport,ViewReports,ACCOUNSTATUS,CreateCategory,CreateEvents,ApproveVideo,ApproveComment,AddBanners,AddMobileOperator,Upload_Video,AddArticlesToBlog,CheckDailyReports, ModifyStaticPage,firstname,lastname
												
				if ($row->accountstatus==1)
				{
					$accountstatus='<font color="#249A47">Active</font>';
				}else
				{
					$accountstatus='<font color="#BD1111">Non-Active</font>';
				}
				
				$tp=array('<img  style="cursor:pointer" src="'.base_url().'images/pencil_icon.png" height="15" title="Edit '.strtoupper($row->fullname.'('.$row->username).')\'s Record">','<img  style="cursor:pointer" src="'.base_url().'images/delete_icon.png" height="15" onclick="DeleteRow(\''.$row->username.'\',\''.$row->fullname.'\')" title="Delete '.strtoupper($row->fullname.'('.$row->username).')">',$row->username,$row->fullname,$row->email,$row->phone,$accountstatus,$row->role,$perm,$row->date_created,$row->pwd,$row->AddItem,$row->EditItem,$row->DeleteItem,$row->CreateUser,$row->ClearLogFiles,$row->CreatePublisher,$row->CreateComedian,$row->SetParameters,$row->ViewLogReport,$row->ViewReports,$row->accountstatus,$row->CreateCategory,$row->CreateEvents,$row->ApproveVideo,$row->ApproveComment,$row->AddBanners,$row->AddMobileOperator,$row->Upload_Video,$row->AddArticlesToBlog,$row->CheckDailyReports,$row->ModifyStaticPage,$row->firstname,$row->lastname);
 
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadUsersJson functions
	
	public function AddUsers()
	{
		$username=''; $email=''; $phone='';  $accountstatus='0'; $role='';
		$pwd=''; $User=''; $UserFullname=''; $firstname=''; $lastname='';
		
		$AddItem='0'; $EditItem='0'; $DeleteItem='0'; $CreateUser='0'; $CreatePublisher='0'; $CreateComedian='0';
		$CreateCategory='0'; $CreateEvents='0'; $ApproveVideo='0'; $ApproveComment='0'; $AddBanners='0'; 
		$AddMobileOperator='0'; $Upload_Video='0'; $AddArticlesToBlog='0'; $CheckDailyReports='0'; 
		$ModifyStaticPage='0'; $ClearLogFiles='0'; $SetParameters='0'; $ViewLogReport='0'; $ViewReports='0';
		
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('firstname')) $firstname = trim($this->input->post('firstname'));
		if ($this->input->post('lastname')) $lastname = trim($this->input->post('lastname'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('pwd')) $pwd = $this->input->post('pwd');
		if ($this->input->post('accountstatus')) $accountstatus = trim($this->input->post('accountstatus'));		
		if ($this->input->post('role')) $role = trim($this->input->post('role'));
		
		if ($this->input->post('AddItem')) $AddItem = trim($this->input->post('AddItem'));		
		if ($this->input->post('EditItem')) $EditItem = trim($this->input->post('EditItem'));
		if ($this->input->post('DeleteItem')) $DeleteItem = trim($this->input->post('DeleteItem'));				
		if ($this->input->post('CreateUser')) $CreateUser = trim($this->input->post('CreateUser'));
		if ($this->input->post('CreatePublisher')) $CreatePublisher = trim($this->input->post('CreatePublisher'));
		if ($this->input->post('CreateComedian')) $CreateComedian = trim($this->input->post('CreateComedian'));
		if ($this->input->post('CreateCategory')) $CreateCategory = trim($this->input->post('CreateCategory'));		
		if ($this->input->post('CreateEvents')) $CreateEvents = trim($this->input->post('CreateEvents'));
		if ($this->input->post('ApproveVideo')) $ApproveVideo = trim($this->input->post('ApproveVideo'));		
		if ($this->input->post('ApproveComment')) $ApproveComment = trim($this->input->post('ApproveComment'));
		if ($this->input->post('AddBanners')) $AddBanners = trim($this->input->post('AddBanners'));
		if ($this->input->post('AddMobileOperator')) $AddMobileOperator = trim($this->input->post('AddMobileOperator'));
		if ($this->input->post('Upload_Video')) $Upload_Video = trim($this->input->post('Upload_Video'));
		if ($this->input->post('AddArticlesToBlog')) $AddArticlesToBlog = trim($this->input->post('AddArticlesToBlog'));
		if ($this->input->post('CheckDailyReports')) $CheckDailyReports = trim($this->input->post('CheckDailyReports'));
		if ($this->input->post('ModifyStaticPage')) $ModifyStaticPage = trim($this->input->post('ModifyStaticPage'));	
		if ($this->input->post('ClearLogFiles')) $ClearLogFiles = trim($this->input->post('ClearLogFiles'));
		if ($this->input->post('SetParameters')) $SetParameters = trim($this->input->post('SetParameters'));
		if ($this->input->post('ViewLogReport')) $ViewLogReport = trim($this->input->post('ViewLogReport'));		
		if ($this->input->post('ViewReports')) $ViewReports = trim($this->input->post('ViewReports'));
						
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullname')) $UserFullname = trim($this->input->post('UserFullname'));

		$datecreated=date('Y-m-d H:i:s');
		
		if (!$accountstatus) $accountstatus='0';
			
		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret = 'User Account Registration Was Not Successful. Username "'.$username.'" Exists In The Database.';
		}else
		{
			#Check If Email Exists
			$sql = "SELECT * FROM userinfo WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$ret = 'Email Address <b>'.$email.'</b> Has Already Been Used. Please Use A Different Email.';
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
					'AddItem' => $this->db->escape_str($AddItem),
					'EditItem' => $this->db->escape_str($EditItem),
					'DeleteItem' => $this->db->escape_str($DeleteItem),
					'CreateUser' => $this->db->escape_str($CreateUser),
					'CreatePublisher' => $this->db->escape_str($CreatePublisher),
					'CreateComedian' => $this->db->escape_str($CreateComedian),
					'CreateCategory' => $this->db->escape_str($CreateCategory),
					'CreateEvents' => $this->db->escape_str($CreateEvents),
					'ApproveVideo' => $this->db->escape_str($ApproveVideo),
					'ApproveComment' => $this->db->escape_str($ApproveComment),
					'AddBanners' => $this->db->escape_str($AddBanners),
					'AddMobileOperator' => $this->db->escape_str($AddMobileOperator),
					'Upload_Video' => $this->db->escape_str($Upload_Video),
					'AddArticlesToBlog' => $this->db->escape_str($AddArticlesToBlog),
					'CheckDailyReports' => $this->db->escape_str($CheckDailyReports),
					'ModifyStaticPage' => $this->db->escape_str($ModifyStaticPage),					
					'ClearLogFiles' => $this->db->escape_str($ClearLogFiles),					
					'SetParameters' => $this->db->escape_str($SetParameters),
					'ViewLogReport' => $this->db->escape_str($ViewLogReport),
					'ViewReports' => $this->db->escape_str($ViewReports)					
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
		}
				
		echo $ret;
	}
	
	public function EditUsers()
	{
		$username=''; $email=''; $phone='';  $accountstatus='0'; $role=''; $pwd=''; $User=''; 
		$UserFullname=''; $firstname=''; $lastname='';
		
		$AddItem='0'; $EditItem='0'; $DeleteItem='0'; $CreateUser='0'; $CreatePublisher='0'; $CreateComedian='0';
		$CreateCategory='0'; $CreateEvents='0'; $ApproveVideo='0'; $ApproveComment='0'; $AddBanners='0'; 
		$AddMobileOperator='0'; $Upload_Video='0'; $AddArticlesToBlog='0'; $CheckDailyReports='0'; 
		$ModifyStaticPage='0'; $ClearLogFiles='0'; $SetParameters='0'; $ViewLogReport='0'; $ViewReports='0';
		
		if ($this->input->post('username')) $username = trim($this->input->post('username'));
		if ($this->input->post('firstname')) $firstname = trim($this->input->post('firstname'));
		if ($this->input->post('lastname')) $lastname = trim($this->input->post('lastname'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('accountstatus')) $accountstatus = trim($this->input->post('accountstatus'));		
		if ($this->input->post('role')) $role = trim($this->input->post('role'));
		
		if ($this->input->post('AddItem')) $AddItem = trim($this->input->post('AddItem'));		
		if ($this->input->post('EditItem')) $EditItem = trim($this->input->post('EditItem'));
		if ($this->input->post('DeleteItem')) $DeleteItem = trim($this->input->post('DeleteItem'));				
		if ($this->input->post('CreateUser')) $CreateUser = trim($this->input->post('CreateUser'));
		if ($this->input->post('CreatePublisher')) $CreatePublisher = trim($this->input->post('CreatePublisher'));
		if ($this->input->post('CreateComedian')) $CreateComedian = trim($this->input->post('CreateComedian'));
		if ($this->input->post('CreateCategory')) $CreateCategory = trim($this->input->post('CreateCategory'));		
		if ($this->input->post('CreateEvents')) $CreateEvents = trim($this->input->post('CreateEvents'));
		if ($this->input->post('ApproveVideo')) $ApproveVideo = trim($this->input->post('ApproveVideo'));		
		if ($this->input->post('ApproveComment')) $ApproveComment = trim($this->input->post('ApproveComment'));
		if ($this->input->post('AddBanners')) $AddBanners = trim($this->input->post('AddBanners'));
		if ($this->input->post('AddMobileOperator')) $AddMobileOperator = trim($this->input->post('AddMobileOperator'));
		if ($this->input->post('Upload_Video')) $Upload_Video = trim($this->input->post('Upload_Video'));
		if ($this->input->post('AddArticlesToBlog')) $AddArticlesToBlog = trim($this->input->post('AddArticlesToBlog'));
		if ($this->input->post('CheckDailyReports')) $CheckDailyReports = trim($this->input->post('CheckDailyReports'));
		if ($this->input->post('ModifyStaticPage')) $ModifyStaticPage = trim($this->input->post('ModifyStaticPage'));	
		if ($this->input->post('ClearLogFiles')) $ClearLogFiles = trim($this->input->post('ClearLogFiles'));
		if ($this->input->post('SetParameters')) $SetParameters = trim($this->input->post('SetParameters'));
		if ($this->input->post('ViewLogReport')) $ViewLogReport = trim($this->input->post('ViewLogReport'));		
		if ($this->input->post('ViewReports')) $ViewReports = trim($this->input->post('ViewReports'));
				
		if ($this->input->post('User')) $User = trim($this->input->post('User'));
		if ($this->input->post('UserFullname')) $UserFullname = trim($this->input->post('UserFullname'));
		
		if (!$accountstatus) $accountstatus='0';
		
		//Check if record exists
		$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."')";

#$file = fopen('aaa.txt',"w"); fwrite($file, $EditItem); fclose($file);			

		$query = $this->db->query($sql);
					
		if ($query->num_rows() == 0 )
		{
			$ret = 'Could Not Edit User Account Record. Record Does Not Exist.';
		}else
		{
			$row = $query->row();	
			
			$OldFname=''; $OldLname=''; $OldEmail=''; $OldPhone=''; $OldStatus='0';
			$OldRole=''; $OldCreateUser='0'; $OldSetParameters='0'; $OldViewLogReport='0';			
			$OldEditItem='0'; $OldAddItem='0'; $OldDeleteItem='0';$OldCreateUsers='0';$OldClearLogFiles='0';
			$OldCreatePublisher='0'; $OldCreateComedian='0'; $OldSetParameters='0';$OldViewLogReports='0';
			$OldViewReports='0'; $OldCreateCategory='0'; $OldCreateEvents='0'; $OldApproveVideo='0';
			$OldApproveComment='0'; $OldAddBanners='0'; $OldAddMobileOperator='0'; $OldUpload_Video='0'; 
			$OldAddArticlesToBlog='0'; $OldCheckDailyReports='0'; $OldModifyStaticPage='0';
			
			if (isset($row))
			{ 	
				if ($row->firstname) $OldFname=$row->firstname;
				if ($row->lastname) $OldLname=$row->lastname;
				if ($row->email) $OldEmail=$row->email;
				if ($row->phone) $OldPhone=$row->phone;
				if ($row->accountstatus==1) $OldStatus=$row->accountstatus;
				if ($row->role) $OldRole=$row->role;				
				
				if ($row->AddItem==1) $OldAddItem=$row->AddItem;				
				if ($row->EditItem==1) $OldEditItem=$row->EditItem;								
				if ($row->DeleteItem==1) $OldDeleteItem=$row->DeleteItem;
				if ($row->CreateUser==1) $OldCreateUser=$row->CreateUser;
				if ($row->CreatePublisher==1) $OldCreatePublisher=$row->CreatePublisher;
				if ($row->CreateComedian==1) $OldCreateComedian=$row->CreateComedian;
				if ($row->CreateCategory==1) $OldCreateCategory=$row->CreateCategory;
				if ($row->CreateEvents==1) $OldCreateEvents=$row->CreateEvents;
				if ($row->ApproveVideo==1) $OldApproveVideo=$row->ApproveVideo;				
				if ($row->ApproveComment==1) $OldApproveComment=$row->ApproveComment;
				if ($row->AddBanners==1) $OldAddBanners=$row->AddBanners;
				if ($row->AddMobileOperator==1) $OldAddMobileOperator=$row->AddMobileOperator;
				if ($row->Upload_Video==1) $OldUpload_Video=$row->Upload_Video;
				if ($row->AddArticlesToBlog==1) $OldAddArticlesToBlog=$row->AddArticlesToBlog;
				if ($row->CheckDailyReports==1) $OldCheckDailyReports=$row->CheckDailyReports;
				if ($row->ModifyStaticPage==1) $OldModifyStaticPage=$row->ModifyStaticPage;
				if ($row->ClearLogFiles==1) $OldClearLogFiles=$row->ClearLogFiles;							
				if ($row->SetParameters==1) $OldSetParameters=$row->SetParameters;
				if ($row->ViewLogReport==1) $OldViewLogReport=$row->ViewLogReport;				
				if ($row->ViewReports==1) $OldViewReports=$row->ViewReports;								
			}

			$OldValues='First Name='.$OldFname.'; Last Name='.$OldLname.'; Email='.$OldEmail.'; Phone='.$OldPhone.'; Account Status='.$OldStatus.'; Role='.$OldRole.'; Add Item='.$OldAddItem.'; Edit Item='.$OldEditItem.'; Delete Item='.$OldDeleteItem.'; Create Users='.$OldCreateUser.'; Clear Log Files='.$OldClearLogFiles.'; Create Publishers='.$OldCreatePublisher.'; Create Comedians='.$OldCreateComedian.'; Set Parameters='.$OldSetParameters.'; View Log Reports='.$OldViewLogReport.'; View Reports='.$OldViewReports.'; Create Categories='.$OldCreateCategory.'; Create Events='.$OldCreateEvents.'; Approve Videos='.$OldApproveVideo.'; Approve Comments='.$OldApproveComment.'; Add Banners='.$OldAddBanners.'; Add Mobile Operators='.$OldAddMobileOperator.'; Upload Videos='.$OldUpload_Video.'; Add Articles To Blog='.$OldAddArticlesToBlog.'; Check Daily Reports='.$OldCheckDailyReports.'; Modify Static Pages='.$OldModifyStaticPage;
			
			$NewValues='First Name='.$firstname.'; Last Name='.$lastname.'; Email='.$email.'; Phone='.$phone.'; Account Status='.$accountstatus.'; Role='.$role.'; Add Item='.$AddItem.'; Edit Item='.$EditItem.'; Delete Item='.$DeleteItem.'; Create Users='.$CreateUser.'; Clear Log Files='.$ClearLogFiles.'; Create Publishers='.$CreatePublisher.'; Create Comedians='.$CreateComedian.'; Set Parameters='.$SetParameters.'; View Log Reports='.$ViewLogReport.'; View Reports='.$ViewReports.'; Create Categories='.$CreateCategory.'; Create Events='.$CreateEvents.'; Approve Videos='.$ApproveVideo.'; Approve Comments='.$ApproveComment.'; Add Banners='.$AddBanners.'; Add Mobile Operators='.$AddMobileOperator.'; Upload Videos='.$Upload_Video.'; Add Articles To Blog='.$AddArticlesToBlog.'; Check Daily Reports='.$CheckDailyReports.'; Modify Static Pages='.$ModifyStaticPage;
			
			$this->db->trans_start();
									
			$dat=array(
				'firstname' => $this->db->escape_str($firstname),
				'lastname' => $this->db->escape_str($lastname),
				'email' => $this->db->escape_str($email),
				'phone' => $this->db->escape_str($phone),
				'accountstatus' => $this->db->escape_str($accountstatus),
				'role' => $this->db->escape_str($role),
				'AddItem' => $this->db->escape_str($AddItem),
				'EditItem' => $this->db->escape_str($EditItem),
				'DeleteItem' => $this->db->escape_str($DeleteItem),
				'CreateUser' => $this->db->escape_str($CreateUser),
				'CreatePublisher' => $this->db->escape_str($CreatePublisher),
				'CreateComedian' => $this->db->escape_str($CreateComedian),
				'CreateCategory' => $this->db->escape_str($CreateCategory),
				'CreateEvents' => $this->db->escape_str($CreateEvents),
				'ApproveVideo' => $this->db->escape_str($ApproveVideo),
				'ApproveComment' => $this->db->escape_str($ApproveComment),
				'AddBanners' => $this->db->escape_str($AddBanners),
				'AddMobileOperator' => $this->db->escape_str($AddMobileOperator),
				'Upload_Video' => $this->db->escape_str($Upload_Video),
				'AddArticlesToBlog' => $this->db->escape_str($AddArticlesToBlog),
				'CheckDailyReports' => $this->db->escape_str($CheckDailyReports),
				'ModifyStaticPage' => $this->db->escape_str($ModifyStaticPage),					
				'ClearLogFiles' => $this->db->escape_str($ClearLogFiles),					
				'SetParameters' => $this->db->escape_str($SetParameters),
				'ViewLogReport' => $this->db->escape_str($ViewLogReport),
				'ViewReports' => $this->db->escape_str($ViewReports)
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
	
	public function DeleteUser()
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
									
			$data['AddItem'] = '0'; $data['EditItem'] = '0'; $data['DeleteItem'] = '0';
			$data['Upload_Video'] = '0'; $data['CreateUser'] = '0'; $data['SetParameters'] = '0';
			$data['ViewLogReport'] = '0'; $data['ClearLogFiles'] = '0'; $data['ViewReports'] = '0';
			$data['CreatePublisher'] = '0'; $data['CreateComedian'] = '0'; $data['CreateCategory'] = '0';
			$data['ApproveVideo'] = '0'; $data['ApproveComment'] = '0'; $data['AddBanners'] = '0';
			$data['ModifyStaticPage'] = '0'; $data['AddArticlesToBlog'] = '0';
			$data['CheckDailyReports'] = '0'; $data['AddMobileOperator'] = '0'; $data['CreateEvents'] = '0';
						
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
			
			
			#################################
			#Permissions
			if ($_SESSION['AddItem']==1) $data['AddItem'] = $_SESSION['AddItem'];
			if ($_SESSION['EditItem']==1) $data['EditItem'] = $_SESSION['EditItem'];
			if ($_SESSION['DeleteItem']== 1) $data['DeleteItem'] = $_SESSION['DeleteItem'];
			if ($_SESSION['Upload_Video']== 1) $data['Upload_Video'] = $_SESSION['Upload_Video'];
			if ($_SESSION['CreateUser']==1) $data['CreateUser'] = $_SESSION['CreateUser'];
			if ($_SESSION['SetParameters']== 1) $data['SetParameters'] = $_SESSION['SetParameters'];
			if ($_SESSION['ViewLogReport']== 1) $data['ViewLogReport'] = $_SESSION['ViewLogReport'];
			if ($_SESSION['ClearLogFiles']==1) $data['ClearLogFiles'] = $_SESSION['ClearLogFiles'];
			if ($_SESSION['ViewReports']==1) $data['ViewReports'] = $_SESSION['ViewReports'];
			if ($_SESSION['CreatePublisher']==1) $data['CreatePublisher'] = $_SESSION['CreatePublisher'];
			if ($_SESSION['CreateComedian']== 1) $data['CreateComedian'] = $_SESSION['CreateComedian'];
			if ($_SESSION['CreateCategory']== 1) $data['CreateCategory'] = $_SESSION['CreateCategory'];
			if ($_SESSION['ApproveVideo']==1) $data['ApproveVideo'] = $_SESSION['ApproveVideo'];
			if ($_SESSION['ApproveComment']==1) $data['ApproveComment'] = $_SESSION['ApproveComment'];
			if ($_SESSION['AddBanners']== 1) $data['AddBanners'] = $_SESSION['AddBanners'];
			if ($_SESSION['ModifyStaticPage']== 1) $data['ModifyStaticPage'] = $_SESSION['ModifyStaticPage'];
			if ($_SESSION['AddArticlesToBlog']== 1) $data['AddArticlesToBlog'] = $_SESSION['AddArticlesToBlog'];
			if ($_SESSION['CheckDailyReports']== 1) $data['CheckDailyReports'] = $_SESSION['CheckDailyReports'];
			if ($_SESSION['AddMobileOperator']== 1) $data['AddMobileOperator'] = $_SESSION['AddMobileOperator'];
			if ($_SESSION['CreateEvents']== 1) $data['CreateEvents'] = $_SESSION['CreateEvents'];
			###############################
			
			if ($_SESSION['companyname']) $data['companyname'] = $_SESSION['companyname'];
			if ($_SESSION['companyemail']) $data['companyemail'] = $_SESSION['companyemail'];
			if ($_SESSION['companyphone']) $data['companyphone'] = $_SESSION['companyphone'];
			if ($_SESSION['website']) $data['website'] = $_SESSION['website'];
			if ($_SESSION['companylogo']) $data['companylogo'] = $_SESSION['companylogo'];
			if ($_SESSION['RefreshDuration']) $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
			if ($_SESSION['default_network']) $data['default_network'] = $_SESSION['default_network'];
			if ($_SESSION['no_of_videos_per_day']) $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
			
			$data['OldPassword']=$_SESSION['pwd'];
			
			$this->load->view('users_view',$data);
		}else
		{
			redirect("Userhome");
		}
	}
}
