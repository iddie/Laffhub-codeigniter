<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Videocat extends CI_Controller {	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
								
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	}
	
	public function LoadVideoCategoriesJson()
	{
				
		$sql = "SELECT category,pix,id FROM video_categories,(SELECT @s:= 0) AS s ORDER BY category";
		
		$query = $this->db->query($sql);
		
		$ret=$query->result();
		
		$data=array();
		
		if (is_array($ret))
		{			
			foreach($ret as $row):
				$pix='';
				
				if ($row->pix)
				{
					if (file_exists('category_pix/'.trim($row->pix)))
					{
						$pix='<img src="'.base_url().'category_pix/'.trim($row->pix).'" height="100px" title="'.strtoupper($row->category).' Banner">';
					}else
					{
						$pix='<img src="'.base_url().'images/nophoto.jpg" height="100px" title="'.strtoupper($row->category).' Banner">';
					}					
				}else
				{					
					$pix='<img src="'.base_url().'images/nophoto.jpg" height="100px" title="'.strtoupper($row->category).' Banner">';
				}
				

								
				$tp=array('<img  style="cursor:pointer" src="'.base_url().'images/view_icon.png" height="20" title="Select '.strtoupper($row->category).'\'s Record">',$row->category,$pix,$row->id,$row->pix);
				
				$data['data'][]=$tp;
			endforeach;
		}
		
		echo json_encode($data);
	}#End Of LoadVideoCategoriesJson functions
	
	public function DeleteCategory()
	{
		$id=''; $ret='';
		
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM video_categories WHERE id=".$id;
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$oldpix=''; $st='';
			
			$row = $query->row();				
			
			if ($row->category) $st=$row->category;
			if ($row->pix) $oldpix=$row->pix;
						
			$this->db->trans_start();
			$this->db->delete('video_categories', array('id' => $id)); 				
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted deleting video category '".strtoupper($st)."' but failed.";
				$ret = "Video Category Record Could Not Be Deleted.";
			}else
			{
				if (file_exists('category_pix/'.$oldpix)) unlink('category_pix/'.$oldpix);
				
				$Msg="Video Category '".strtoupper($st)."' has been deleted successfully.";				
				$ret = 'OK';
			}
#($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)				
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'DELETE VIDEO CATEGORY',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Delete Video Category Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of DeleteCategory functions
	
	public function EditCategory()
	{
		$category=''; $id=''; $CatImg='';
		
		if (isset($_FILES['logo_pix'])) $CatImg = $_FILES['logo_pix'];
		
		if ($this->input->post('category')) $category = $this->input->post('category');
		if ($this->input->post('id')) $id = $this->input->post('id');
		
		//Check if record exists
		$sql = "SELECT * FROM video_categories WHERE id=".$id;
		$query = $this->db->query($sql);
		
		$ret='';
		
		if ($query->num_rows() > 0 )
		{
			$oldpix='';
			
			$row = $query->row();
							
			if ($row->category) $st=$row->category;
			if ($row->pix) $oldpix=$row->pix;
			
			if ($CatImg)
			{
				#Delete old picture
				if (file_exists('category_pix/'.$oldpix)) unlink('category_pix/'.$oldpix);
					
				$category_pixname = $CatImg['name'];
				
				$ext = explode('.', basename($category_pixname));
				
				$fn=str_replace(' ','_',trim($category)).".".array_pop($ext);
				
				$target ="category_pix/".$fn;
				
				if(move_uploaded_file($CatImg['tmp_name'], $target))
				{
					$Lpix=$fn;	
					
					$image_info = getimagesize($img);//index 0 is width, index 1 is heigth
					  
					$wd=$image_info[0]; #$ht=$image_info[1];
					
					if ($wd > 500) $this->getdata_model->ResizeImage($target,500);
				}
			}else
			{
				$Lpix='';
			}
			
			
			
			$this->db->trans_start();

			$dat=array('category' => $this->db->escape_str($category));							
			$this->db->where('id', $id);
			$this->db->update('video_categories', $dat); 	
			
			$this->db->trans_complete();
						
			$Msg='';

			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted editing video category ".strtoupper($category)." but failed.";
				$ret = "Video Category Record Could Not Be Edited.";
			}else
			{
				#Update Logo
				if ($Lpix)
				{					
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($Lpix));
					$this->db->where('id', $id);				
					$this->db->update('video_categories', $dat);					
					$this->db->trans_complete();					
				}
				
				$Msg="Video Category has been edited successfully. Old Values: Video Category => ".$st.". Updated values: Video Category => ".$category;
				
				$ret = 'OK';
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'EDIT VIDEO CATEGORY',$_SESSION['LogID']);
		}else
		{
			$ret="Could Not Edit Video Category Record. Record Does Not Exist.";
		}
		
		echo $ret;
	}#End Of EditCategory functions
	
	public function AddCategory()
	{
		$category=''; $ret=''; $CatImg='';
		
		if (isset($_FILES['logo_pix'])) $CatImg = $_FILES['logo_pix'];
		
		if ($this->input->post('category')) $category = $this->input->post('category');
		
		$Lpix='';
		
		//Check if record exists
		$sql = "SELECT * FROM video_categories WHERE (TRIM(category)='".$this->db->escape_str($category)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret='Video Category "'.strtoupper($category).'" exists in the database.';
		}else
		{
			if ($CatImg)
			{
				$category_pixname = $CatImg['name'];
				
				$ext = explode('.', basename($category_pixname));
				
				$fn=str_replace(' ','_',trim($category)).".".array_pop($ext);
				
				$target ="category_pix/".$fn;
				
				if(move_uploaded_file($CatImg['tmp_name'], $target))
				{
					$Lpix=$fn;	
					$image_info = getimagesize($img);//index 0 is width, index 1 is heigth
					  
					$wd=$image_info[0]; #$ht=$image_info[1];
					
					if ($wd > 500) $this->getdata_model->ResizeImage($target,500);
				}
			}else
			{
				$Lpix='';
			}
			
			$this->db->trans_start();
									
			$dat=array(
				'category' => $this->db->escape_str($category),
				'insert_date' => date('Y-m-d H:i:s')
				);		
								
			$this->db->insert('video_categories', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Attempted adding video category ".strtoupper($category)." but failed.";
				$ret = "Video Category Record Could Not Be Added.";
			}else
			{
				#Update Logo
				if ($Lpix)
				{
					$this->db->trans_start();			
					$dat=array('pix' => $this->db->escape_str($Lpix));
					$this->db->where('category', $this->db->escape_str($category));					
					$this->db->update('video_categories', $dat);					
					$this->db->trans_complete();
				}
				
				$Msg="Video Category ".strtoupper($category)." was added successfully.";
				$ret = "OK";
			}
			
			$this->getdata_model->LogDetails($_SESSION['UserFullName'],$Msg,$_SESSION['username'],$_SESSION['LogIn'],$_SESSION['RemoteIP'],$_SESSION['RemoteHost'],'ADD VIDEO CATEGORY',$_SESSION['LogID']);
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
			$this->load->view('videocat_view',$data);
		}else
		{
			redirect('Userhome');
		}
	}
}
