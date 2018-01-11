<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Ads extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	}
	
	public function LoadAds()
	{
		$startdate=''; $enddate=''; $status=''; $data=array();
		
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('status')) $status = $this->input->post('status');
		
		if (!$status) $status='0';
		
		$sql="SELECT DATE_FORMAT(startdate,'%d %b %Y') AS AdsStartDate,DATE_FORMAT(enddate,'%d %b %Y') AS AdsEndDate, ads.* FROM ads WHERE (DATE_FORMAT(startdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."')";
		
		if (trim(strtolower($status)) <> 'all') $sql .= " AND (ads_status=".$status.")";
		
		$sql .= " ORDER BY startdate,`title`";		
				
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$sn=-1;
			
			while ($row = $query->unbuffered_row('array')):
				$sn++; $pix=''; $sta='Disabled';
				
				if ($row['ads_status']==1) $sta='Active';
				
				if ($row['pix'])
				{
					if (file_exists('ads_pix/'.trim($row['pix'])))
					{
						$pix='<img src="'.base_url().'ads_pix/'.trim($row['pix']).'" height="60px" title="'.strtoupper($row['title']).'\'s Picture">';
					}else
					{
						$pix='<img src="'.base_url().'images/nophoto.jpg" height="60px" title="'.strtoupper($row['title']).'\'s Picture">';
					}
				}else
				{					
					$pix='<img src="'.base_url().'images/nophoto.jpg" height="60px" title="'.strtoupper($row['title']).'\'s Picture">';
				}
				
//[SELECT],Title,StartDate,EndDate,Status,Pix,Id,Description,status,pix							
				$tp=array('<img onClick="GetRow(\''.$sn.'\');"  style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Select '.strtoupper($row['title']).'\'s Record">',$row['title'],$row['AdsStartDate'],$row['AdsEndDate'],$sta,$pix,$row['id'],$row['description'],$row['ads_status'],$row['pix']);
				
				$data['data'][]=$tp;				
			endwhile;
			
			echo json_encode($data);
		}else
		{
			echo json_encode($data);
		}		
	}#End LoadAds functions
	
	public function DeleteAdvert()
	{
		$id=''; $ret='';
		
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM ads WHERE id=".$id;
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$oldpix=''; $tit=''; $sdt='';
			
			$row = $query->row();				
			
			if ($row->title) $tit=$row->title;
			if ($row->startdate) $sdt=$row->startdate;
						
			if ($row->pix) $oldpix=$row->pix;
						
			$this->db->trans_start();
			$this->db->delete('ads', array('id' => $id)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg='Advert with title <b>'.strtoupper($tit).' for '.strtoupper(date('d M Y',strtotime($sdt))).'</b> could not be deleted.';
				
				$ret = "Advert Record Could Not Be Deleted.";
			}else
			{
				if (file_exists('ads_pix/'.$oldpix)) unlink('ads_pix/'.$oldpix);
				
				$Msg='Advert with title <b>'.strtoupper($tit).' for '.strtoupper(date('d M Y',strtotime($sdt))).'</b> has been deleted successfully.';			
				$ret = 'OK';
			}
#($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)				
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETED ADVERT',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete Advert Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of DeleteCategory functions
	
	public function EditAdvert()
	{
		$title=''; $ret=''; $AdvImg=''; $description=''; $startdate=''; $enddate=''; $ads_status=''; $id='';
		
		if (isset($_FILES['logo_pix'])) $AdvImg = $_FILES['logo_pix'];
		
		if ($this->input->post('title')) $title = $this->input->post('title');
		if ($this->input->post('description')) $description = $this->input->post('description');
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('ads_status')) $ads_status = $this->input->post('ads_status');
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM ads WHERE id=".$id;
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0 )
		{
			$oldpix=''; $tit=''; $de=''; $sdt=''; $edt=''; $sta='0';
			
			$row = $query->row();
							
			if ($row->title) $tit=$row->title;
			if ($row->description) $de=$row->description;
			if ($row->startdate) $sdt=$row->startdate;
			if ($row->enddate) $edt=$row->enddate;
			if ($row->pix) $oldpix=$row->pix;
			if ($row->ads_status==1) $sta='1';
			
			$OldValues='Title='.$tit.'; Description='.$de.'; Start Date='.$sdt.'; End Date='.$edt.'; Status='.$sta;
			$NewValues='Title='.$title.'; Description='.$description.'; Start Date='.$startdate.'; End Date='.$enddate.'; Status='.$ads_status;
			
			if ($AdvImg)
			{
				#Delete old picture
				if (file_exists('ads_pix/'.$oldpix)) unlink('ads_pix/'.$oldpix);
					
				$ads_pixname = $AdvImg['name'];
				
				$ext = explode('.', basename($ads_pixname));
				
				$fn=str_replace(' ','_',trim($title)).".".array_pop($ext);
				
				$target ="ads_pix/".$fn;
				
				if(move_uploaded_file($AdvImg['tmp_name'], $target))
				{
					$Lpix=$fn;			
					$image_info = getimagesize($img);//index 0 is width, index 1 is heigth					  
					$wd=$image_info[0]; #$ht=$image_info[1];					
					if ($wd > 370) $this->getdata_model->ResizeImage($target,370);
				}
			}else
			{
				$Lpix='';
			}
			
			
			
			$this->db->trans_start();
			
			$dat=array(
				'title' => $this->db->escape_str($title),
				'description' => $this->db->escape_str($description),
				'startdate' => $this->db->escape_str($startdate),
				'enddate' => $this->db->escape_str($enddate),
				'ads_status' => $this->db->escape_str($ads_status)
			);

			$this->db->where('id', $id);
			$this->db->update('ads', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg='Advert with title <b>'.strtoupper($title).' for '.strtoupper(date('d M Y',strtotime($startdate))).'</b> could not be edited.';
				
				$ret = "Advert Could Not Be Edited.";
			}else
			{
				#Update Logo
				if ($Lpix)
				{					
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($Lpix));
					$this->db->where('id', $id);				
					$this->db->update('ads', $dat);					
					$this->db->trans_complete();					
				}
				
				$Msg="Advert has been edited successfully by ".strtoupper($UserFullName."(".$Username.")").". Old Values: ".$OldValues.". Updated values: ".$NewValues;
				
				$ret = 'OK';
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDITED ADVERT',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Edit Advert Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of EditCategory functions
	
	public function AddAdvert()
	{
		$title=''; $ret=''; $AdvImg=''; $description=''; $startdate=''; $enddate=''; $ads_status='';
		
		if (isset($_FILES['logo_pix'])) $AdvImg = $_FILES['logo_pix'];
		
		if ($this->input->post('title')) $title = $this->input->post('title');
		if ($this->input->post('description')) $description = $this->input->post('description');
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('ads_status')) $ads_status = $this->input->post('ads_status');
		
		if (!$ads_status) $ads_status='0';
				
		$Lpix='';
		
		//Check if record exists
		$sql = "SELECT * FROM ads WHERE (TRIM(title)='".$this->db->escape_str($title)."') AND (DATE_FORMAT(startdate,'%Y-%m-%d')='".$startdate."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret='Advert with title <b>'.strtoupper($title).' for '.strtoupper(date('d M Y',strtotime($startdate))).'</b> exists in the database.';
		}else
		{
			if ($AdvImg)
			{#adv_x
				$ads_pixname = $AdvImg['name'];
				
				$ext = explode('.', basename($ads_pixname));
				
				$fn=str_replace("'",'',trim($title));
				
				$fn=str_replace(' ','_',trim($title)).".".array_pop($ext);
				
				$target ="ads_pix/".$fn;
				
				if(move_uploaded_file($AdvImg['tmp_name'], $target))
				{
					$Lpix=$fn;
					
					$image_info = getimagesize($img);//index 0 is width, index 1 is heigth					  
					$wd=$image_info[0]; #$ht=$image_info[1];					
					if ($wd > 370) $this->getdata_model->ResizeImage($target,370);					
				}
			}else
			{
				$Lpix='';
			}
			
			$this->db->trans_start();
									
			$dat=array(
				'title' => $this->db->escape_str($title),
				'description' => $this->db->escape_str($description),
				'startdate' => $this->db->escape_str($startdate),
				'enddate' => $this->db->escape_str($enddate),
				'ads_status' => $this->db->escape_str($ads_status)
				);		
								
			$this->db->insert('ads', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg='Advert with title <b>'.strtoupper($title).' for '.strtoupper(date('d M Y',strtotime($startdate))).'</b> could not be added.';
				
				$ret = "Advert Record Could Not Be Added.";
			}else
			{
				#Update Logo
				if ($Lpix)
				{
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($Lpix));
					
					$where = "(TRIM(title)='".$this->db->escape_str($title)."') AND (DATE_FORMAT(startdate,'%Y-%m-%d')='".$startdate."')";
					
					$this->db->where($where);									
					$this->db->update('ads', $dat);					
					$this->db->trans_complete();
				}
				
				$Msg='Advert with title <b>'.strtoupper($title).' for '.strtoupper(date('d M Y',strtotime($startdate))).'</b> was added successfully.';
				
				$ret = "OK";
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADDED ADVERT',$_SESSION['LogID']);
		}
		
		echo $ret;
	}#End Of AddCategory functions
	 	
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
			$this->load->view('ads_view',$data);
		}else
		{
			redirect('Userhome');
		}
	}
}
