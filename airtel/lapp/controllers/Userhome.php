<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Userhome extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	
	public function GetTransactions()
	{
		$year=''; $month='';$network=''; $category=''; $startdate=''; $enddate='';
		
		if ($this->input->post('year')) $year = $this->input->post('year');
		if ($this->input->post('month')) $month = $this->input->post('month');
		if ($this->input->post('network')) $network = $this->input->post('network');
		if ($this->input->post('category')) $category = $this->input->post('category');		
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		
		#if ($phone) $phone=$this->getdata_model->GSMPhoneNo($phone);
				
		$sql="SELECT @s:=@s+1 AS SN,DATE_FORMAT(trans_date,'%d&nbsp;%b&nbsp;%Y&nbsp;%H:%i') AS trans_date,video_category,(SELECT video_title FROM videos WHERE TRIM(videos.filename)=TRIM(transactions.filename) LIMIT 0,1) AS video_title,filename AS filename,user_agent,network,phone,(SELECT category FROM videos WHERE TRIM(videos.filename)=TRIM(transactions.filename) LIMIT 0,1) AS category FROM transactions,(SELECT @s:= 0) AS s";
		
		$crit='';
		
		if (trim($year) != '') $crit=" (YEAR(trans_date)='".$year."')";	
		
		if (trim($month) != '')
		{
			if (trim($crit)=='')
			{
				$crit=" (DATE_FORMAT(trans_date,'%M')='".$month."')";
			}else
			{
				$crit .= " AND (DATE_FORMAT(trans_date,'%M')='".$month."')";
			}
		}
		
		if (trim($network) != '')
		{
			if (trim($crit)=='')
			{
				$crit=" (TRIM(network)='".trim($network)."')";
			}else
			{
				$crit .= " AND (TRIM(network)='".trim($network)."')";
			}
		}
		
		if (trim($category) != '')
		{
			if (trim($crit)=='')
			{
				$crit=" (TRIM(video_category)='".trim($category)."')";
			}else
			{
				$crit .= " AND (TRIM(video_category)='".trim($category)."')";
			}
		}
		
		if ((trim($startdate) != '') && (trim($enddate) != ''))
		{
			if (trim($crit)=='')
			{
				$crit=" (DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."')";
			}else
			{
				$crit .= " AND (DATE_FORMAT(trans_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."')";
			}
		}
		
		if (trim($crit) != '') $sql .= ' WHERE'.$crit;
		
		$sql .= " ORDER BY trans_date DESC,network,phone";

#$file = fopen('aaa.txt',"w"); fwrite($file, $sql."\n"); fclose($file);	
		$query = $this->db->query($sql);
		
		$results = $query->result_array();
		
		if ($results)
		{
			$data=array();
			
			$sn=0;

			if (is_array($results))
			{
				foreach($results as $row):
					$sn++;
					
					$tp=array($row['SN'],$row['trans_date'],$row['phone'],$row['category'],$row['video_title'],$row['user_agent'],$row['network'],$row['video_id'],$row['filename']);
					$data[]=$tp;
				endforeach;
			}
			
			print_r(json_encode($data));
		}else
		{
			print_r(json_encode($results));
		}
	}
	
	public function data()
	{
		
		$data = $this->getdata_model->get_data();
		
		#Get Available Months
		$arrMonths=array();
		
		foreach ($data as $value)#TransMonth,TransYear,network,Cnt => January	| 2016 | Etisalat | 7 
		{
			if ($value)
			{
				#for($i=0; $i < count($value); $i++)
				foreach ($value as $k=>$v)
				{#echo($k.' => '.$v.'<br>');
					if (trim(strtolower($k))=='transmonth')
					{
						if (in_array($v,$arrMonths)===FALSE) $arrMonths[]=$v;
					}
				}
			}
		}
		
		
		
		
		#$arrMonths=array('January','February','March','April','May','June','July','August','September','October','November','December');
		
		$glo=array();$mtn=array(); $etisalat=array(); $airtel=array();
		
		#Arrange Data In Network
		foreach ($data as $row)#TransMonth,TransYear,network,Cnt => January	| 2016 | Etisalat | 7 
		{
			if (trim(strtolower($row->network))=='glo') $glo[$row->TransMonth] += intval($row->Cnt);
			if (trim(strtolower($row->network))=='airtel') $airtel[$row->TransMonth] += intval($row->Cnt);
			if (trim(strtolower($row->network))=='etisalat') $etisalat[$row->TransMonth] += intval($row->Cnt);
			if (trim(strtolower($row->network))=='mtn') $mtn[$row->TransMonth] += intval($row->Cnt);
		}
	
	
	
		
		$mydata=array();
		
		#Arrange Networks together to form Array(month,Airtel,Etisalat,GLO,MTN)
		foreach ($arrMonths as $mn):
			$mydata[$mn]=array($airtel[$mn],$etisalat[$mn],$glo[$mn],$mtn[$mn]);
		endforeach;
			
			
		$category = array();
		$category['name'] = 'Category';
		
		$series1 = array();
		$series1['name'] = 'AIRTEL';
		
		$series2 = array();
		$series2['name'] = 'ETISALAT';
		
		$series3 = array();
		$series3['name'] = 'GLO';
		
		$series4 = array();
		$series4['name'] = 'MTN';
		
#[Jan] => Array([0] => [1] => 7 [2] => 12 [3] => 8) [Feb] => Array([0] => 7 [1] => 9 [2] => 5 [3] => 9)
		
		foreach ($mydata as $month => $value)#TransMonth,TransYear,network,Cnt => January	| 2016 | Etisalat | 7 
		{
			if (count($value)>0)
			{
				$category['data'][] = $month;
				
				$series1['data'][] = $value[0];
				$series2['data'][] = $value[1];
				$series3['data'][] = $value[2];
				$series4['data'][] = $value[3];
			}
			
		}
		
		#print json_encode($mydata, JSON_NUMERIC_CHECK);
		#print_r($mydata);
		#exit();	
		#[{"name":"Category","data":["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]},{"name":"AIRTEL","data":[4,5,6,2,5,7,2,1,6,7,3,4]},{"name":"ETISALAT","data":[5,2,3,6,7,1,2,6,6,4,6,3]},{"name":"GLO","data":[7,8,9,6,7,10,9,7,6,9,8,4]},{"name":"MTN","data":["","","","","","","","","","","",""]}]
		

		/*foreach ($data as $row)#TransMonth,TransYear,network,Cnt => January	| 2016 | Etisalat | 7 
		{
			$category['data'][] = $row->month;
			$series1['data'][] = $row->wordpress;
			$series2['data'][] = $row->codeigniter;
			$series3['data'][] = $row->highcharts;
			$series4['data'][] = '';
		}
		*/
		
		$result = array();
		array_push($result,$category);
		array_push($result,$series1);
		array_push($result,$series2);
		array_push($result,$series3);
		array_push($result,$series4);
		
		print json_encode($result, JSON_NUMERIC_CHECK);
	}
	
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
			if ($_SESSION['google_shortener_api']) $data['google_shortener_api'] = $_SESSION['google_shortener_api'];
			if ($_SESSION['jw_api_key']) $data['jw_api_key'] = $_SESSION['jw_api_key'];
			if ($_SESSION['jw_api_secret']) $data['jw_api_secret'] = $_SESSION['jw_api_secret'];
			if ($_SESSION['jw_player_id']) $data['jw_player_id'] = $_SESSION['jw_player_id'];			
			if ($_SESSION['emergency_emails']) $data['emergency_emails'] = $_SESSION['emergency_emails'];
			if ($_SESSION['emergency_no']) $data['emergency_no'] = $_SESSION['emergency_no'];			
			if ($_SESSION['sms_url']) $data['sms_url'] = $_SESSION['sms_url'];
			if ($_SESSION['sms_username']) $data['sms_username'] = $_SESSION['sms_username'];
			if ($_SESSION['sms_password']) $data['sms_password'] = $_SESSION['sms_password'];			
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];			
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			
			if ($_SESSION['distribution_Id']) $data['distribution_Id'] = $_SESSION['distribution_Id'];
			if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
			if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];
						
			$data['OldPassword']=$_SESSION['pwd'];
			$data['CategoryData'] = $this->getdata_model->GetVideoCategories();	
			
			$this->load->view('userhome_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
