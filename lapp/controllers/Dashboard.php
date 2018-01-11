<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
	
	
	public function GetTransactions()
	{
		$year=''; $month='';$network=''; $category=''; $startdate=''; $enddate=''; $data=array();
		
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
		
		if ($query->num_rows() > 0)
		{
			$sn=0;
			
			while ($row = $query->unbuffered_row('array')):
				$sn++;
					
				$tp=array($row['SN'],$row['trans_date'],$row['phone'],$row['category'],$row['video_title'],$row['user_agent'],$row['network'],$row['video_id'],$row['filename']);
				$data[]=$tp;
			endwhile;
			
			print_r(json_encode($data));
		}else
		{
			print_r(json_encode($data));
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
		if ($_SESSION['publisher_email'])
		{
			$data['publisher_email']=$_SESSION['publisher_email'];
			
			$data['publisher_status'] = '0';
					
			if ($_SESSION['publisher_name']) $data['publisher_name'] = $_SESSION['publisher_name'];
			if ($_SESSION['publisher_phone']) $data['publisher_phone'] = $_SESSION['publisher_phone'];
			if ($_SESSION['publisher_pwd']) $data['publisher_pwd'] = $_SESSION['publisher_pwd'];			
			if ($_SESSION['datecreated']) $data['datecreated'] = $_SESSION['datecreated'];
			if ($_SESSION['publisher_status']) $data['publisher_status'] = $_SESSION['publisher_status'];
						
			if ($_SESSION['companyname']) $data['companyname'] = $_SESSION['companyname'];
			if ($_SESSION['companyemail']) $data['companyemail'] = $_SESSION['companyemail'];
			if ($_SESSION['companyphone']) $data['companyphone'] = $_SESSION['companyphone'];
			if ($_SESSION['website']) $data['website'] = $_SESSION['website'];
			if ($_SESSION['companylogo']) $data['companylogo'] = $_SESSION['companylogo'];
			if ($_SESSION['RefreshDuration']) $data['RefreshDuration'] = $_SESSION['RefreshDuration'];
			if ($_SESSION['default_network']) $data['default_network'] = $_SESSION['default_network'];
			if ($_SESSION['no_of_videos_per_day']) $data['no_of_videos_per_day'] = $_SESSION['no_of_videos_per_day'];
			
			if ($_SESSION['input_bucket']) $data['input_bucket'] = $_SESSION['input_bucket'];
			if ($_SESSION['output_bucket']) $data['output_bucket'] = $_SESSION['output_bucket'];
			if ($_SESSION['thumbs_bucket']) $data['thumbs_bucket'] = $_SESSION['thumbs_bucket'];
			if ($_SESSION['aws_key']) $data['aws_key'] = $_SESSION['aws_key'];
			if ($_SESSION['aws_secret']) $data['aws_secret'] = $_SESSION['aws_secret'];
			if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];

			$data['VideoCategories'] = $this->getdata_model->GetVideoCategories();
			
			#$file = fopen('aaa.txt',"w"); fwrite($file, $data['OldPassword']); fclose($file);	
			
			$this->load->view('dashboard_view',$data);
		}else
		{
			redirect("Pubhome");
		}
	}
}
