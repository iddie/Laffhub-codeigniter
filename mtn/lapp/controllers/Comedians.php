<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Comedians extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function LoadComediansJson()
	{
				
		$sql = "SELECT '' AS SN,comedians.* FROM comedians,(SELECT @s:= 0) AS s ORDER BY comedian";
		
		$query = $this->db->query($sql);
		
		$ret=$query->result();
		
		
		$data=array();
		
		if (is_array($ret))
		{			
			foreach($ret as $row):
				$pix=''; $sta='';
				
				if ($row->pix) $pix='<img src="'.base_url().'comedian_pix/'.$row->pix.'" height="100px" title="'.strtoupper($row->comedian).'\'s Picture">';
				
				if ($row->comedian_status==1)
				{
					$sta='<span style="color:#256709;">Active</span>';
				}else
				{
					$sta='<span style="color:#B32D19;">Not Active</span>';
				}
								
				$tp=array('<img  style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="15" title="Select '.strtoupper($row->comedian).'\'s Record">',$row->comedian,$row->details,$pix,$sta,$row->id,$row->comedian_status,$row->pix);
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadComediansJson functions
	
	public function DeleteComedian()
	{
		$id=''; $ret='';
		
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM comedians WHERE id=".$id;
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();				
			if (isset($row)) $cm=$row->comedian;
			
			$this->db->trans_start();
			$this->db->delete('comedians', array('id' => $id)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted deleting comedian '".strtoupper($st)."' but failed.";
				$ret = "Comedian Record Could Not Be Deleted.";
			}else
			{
				$Msg="Comedian '".strtoupper($ct)."' has been deleted successfully.";				
				$ret = 'OK';
			}
#($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)				
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETE COMEDIAN',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete Comedian Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of DeleteComedian functions
	
	public function EditComedian()
	{
		$comedian=''; $details=''; $comedian_status='0'; $id='';
		
		if (isset($_FILES['pix'])) $Img = $_FILES['pix'];
		
		if ($this->input->post('comedian')) $comedian = trim($this->input->post('comedian'));
		if ($this->input->post('details')) $details = trim($this->input->post('details'));
		if ($this->input->post('comedian_status')) $comedian_status = $this->input->post('comedian_status');
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		$pix='';
		
		if ($Img)
		{
			$ext = explode('.', basename($Img['name']));
			
			$fn=str_replace(' ','_',$comedian).".".array_pop($ext);
			
			$target = "comedian_pix/".$fn;
			
			if(move_uploaded_file($Img['tmp_name'], $target))
			{
				$pix=$fn;					
				$this->getdata_model->ResizeImage($target,200);
			}
		}
		
		//Check if record exists
		$sql = "SELECT * FROM comedians WHERE id=".$id;
		$query = $this->db->query($sql);
		
		$ret='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();				
			if ($row->comedian) $cm=$row->comedian;
			if ($row->details) $dt=$row->details;
			if ($row->comedian_status) $sta=$row->comedian_status;
			
			$this->db->trans_start();

			$dat=array(
				'comedian' => $this->db->escape_str($comedian),
				'details' => $this->db->escape_str($details),
				'comedian_status' => $comedian_status
			);	
										
			$this->db->where('id', $id);
			$this->db->update('comedians', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted editing comedian ".strtoupper($comedian)." but failed.";
				$ret = "Comedian Record Could Not Be Edited.";
			}else
			{
				#Update Pix
				if ($pix)
				{
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($pix));	
					$this->db->where('id', $id);				
					$this->db->update('comedians', $dat);					
					$this->db->trans_complete();
				}
				
				$Msg="Comedian has been edited successfully. Old Values: Comedian => ".$cm."; Details => ".$dt."; Comedian Status => ".$sta.". Updated values: Comedian => ".$comedian."; Details => ".$details."; Comedian Status => ".$comedian_status;
				
				$ret = 'OK';
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDIT COMEDIAN',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Edit Comedian Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of EditComedian functions
	
	public function AddComedian()
	{
		$comedian=''; $details=''; $comedian_status='0'; $ret='';
		
		if (isset($_FILES['pix'])) $Img = $_FILES['pix'];
		
		if ($this->input->post('comedian')) $comedian = $this->input->post('comedian');
		if ($this->input->post('details')) $details = $this->input->post('details');
		if ($this->input->post('comedian_status')) $comedian_status = $this->input->post('comedian_status');
		
		$pix='';
		
		if ($Img)
		{
			$ext = explode('.', basename($Img['name']));
			
			$fn=str_replace(' ','_',$comedian).".".array_pop($ext);
			
			$target = "comedian_pix/".$fn;
			
			if(move_uploaded_file($Img['tmp_name'], $target))
			{
				$pix=$fn;					
				$this->getdata_model->ResizeImage($target,200);
			}
		}
		
		//Check if record exists
		$sql = "SELECT * FROM comedians WHERE (TRIM(comedian)='".$this->db->escape_str($comedian)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret='Comedian <b>'.strtoupper($comedian).'</b> exists in the database.';
		}else
		{
			$this->db->trans_start();
									
			$dat=array(
				'comedian' => $this->db->escape_str($comedian),
				'details' => $this->db->escape_str($details),
				'comedian_status' => $comedian_status
			);	
								
			$this->db->insert('comedians', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted adding comedian ".strtoupper($comedian)." but failed.";
				$ret = "Comedian Record Could Not Be Added.";
			}else
			{
				#Update Pix
				if ($pix)
				{
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($pix));	
					$this->db->where('comedian', $this->db->escape_str($comedian));				
					$this->db->update('comedians', $dat);					
					$this->db->trans_complete();
				}
				
				$Msg="Comedian ".strtoupper($comedian)." was added successfully.";
				$ret = "OK";
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADD COMEDIAN',$_SESSION['LogID']);
		}
		
		echo $ret;
	}#End Of AddComedian functions
	 	
	public function index()
	{
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
//$file = fopen('aaa.txt',"w"); fwrite($file, $_SESSION['password']); fclose($file);				
			$this->load->view('comedians_view',$data);
		}else
		{
			redirect('Userhome');
		}
	}
}
