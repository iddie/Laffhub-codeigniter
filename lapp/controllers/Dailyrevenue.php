<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#include ('nairaandkobo.php');
#include("mpdf/mpdf.php");
include('classes/PHPExcel.php');

class Dailyrevenue extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
		
	function Capitalize($wd)
	{
		if (!$wd) return '';
		
		if (stripos($wd,'/')===false)
		{
			if (strlen($wd)==2) $v=strtoupper($wd);
			
			return ucwords(strtolower($wd));
		}else
		{
			$v='';
			
			$t=explode('/',$wd);
			
			if (count($t)>0)
			{			
				for($i=0; $i<count($t); $i++)	:
					if ($v=='') $v=ucwords(strtolower($t[$i])); else $v .= '/'.ucwords(strtolower($t[$i]));
				endfor;
			}
			
			if (strlen($v)==2) $v=strtoupper($v);
			
			return $v;
		}
	}

	public function GetReport()
	{
		$startdate=''; $enddate='';	$network='';
				
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		
		if (strtolower($network)=='airtel')
		{
			$sql="SELECT DATE_FORMAT(subscribe_date,'%d&nbsp;%b&nbsp;%Y') AS sdate,airtel_daily_revenue.* FROM airtel_daily_revenue WHERE (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') ORDER BY subscribe_date DESC;";	
		}		

		$query = $this->db->query($sql);
	
		if ($query->num_rows() > 0)
		{
			$data=array();
			
			$sn=0;
			
			while ($row = $query->unbuffered_row('array')):
				$sn++; $n20=0; $n100=0; $n200=0; $n500=0;
					
				if ($row['N20total']) $n20=$row['N20total'];
				if ($row['N100total']) $n100=$row['N100total'];
				if ($row['N200total']) $n200=$row['N200total'];					
				if ($row['N500total']) $n500=$row['N500total'];					
									
				$revenue = floatval($n20) + floatval($n100) + floatval($n200) + floatval($n500);
#SN,Date,Revenue,N20Revenue,N100Revenue,N200Revenue,N500Revenue,Active,New,Trial,FailedActivations,CustomersWithFailedCharging,GrayArea,Cancelled,ChargingSuccessRate
				
				$tp=array($sn,$row['sdate'],number_format($revenue,2),number_format($n20,2),number_format($n100,2),number_format($n200,2),number_format($n500,2),number_format($row['active'],0),number_format($row['newsub'],0),number_format($row['trial'],0),number_format($row['failed_activation'],0),number_format($row['custfailedcharging'],0),number_format($row['greyarea'],0),number_format($row['cancelled'],0),number_format($row['charging_success'],2));					
				
				$data[]=$tp;
			endwhile;
			
			print_r(json_encode($data));
		}else
		{
			print_r(json_encode($data));
		}		
	}
	
	public function PrintReport()
	{
		$startdate=''; $enddate='';	$network=''; $arrRevenue=array();
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('title')) $title = $this->input->post('title');
		
		#Get Date array
		if (strtolower($network)=='airtel')
		{
			$sql="SELECT DATE_FORMAT(subscribe_date,'%d %b %Y') AS sdate,airtel_daily_revenue.* FROM airtel_daily_revenue WHERE (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') ORDER BY subscribe_date DESC;";
		}
	
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$logo=dirname(__FILE__)."/header_logo.png";				
			$xls_file='dailyrevenuereport.xls';
							
			#Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Eastwinds ICT")
								 ->setLastModifiedBy("Eastwinds ICT")
								 ->setTitle($title)
								 ->setSubject($title)
								 ->setDescription($title)
								 ->setKeywords("Daily Revenue,Daily")
								 ->setCategory("Daily Revenue");
			

								 
			$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
			
			#Margin is set in inches (0.5cm)
			$margin = 0.5;
			
			$objPHPExcel->getActiveSheet()->getPageMargins()->setTop($margin);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom($margin);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft($margin);
			$objPHPExcel->getActiveSheet()->getPageMargins()->setRight($margin);
			
			$objPHPExcel->setActiveSheetIndex(0);
			
			#Add Logo
			$objDrawing = new PHPExcel_Worksheet_Drawing();
			$objDrawing->setName('LaffHub Logo');
			$objDrawing->setDescription('LaffHub Logo');
			$objDrawing->setPath($logo);
			$objDrawing->setCoordinates('A1');
			$objDrawing->setResizeProportional(false);
			$objDrawing->setHeight(50);
			$objDrawing->setWidth(120);
			$objDrawing->setOffsetX(1);
			$objDrawing->setOffsetY(1);
			$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
			#A to O
			$styleArray = array('font' => array('bold' => true));
			
			$objPHPExcel->getActiveSheet()->setCellValue('A4','');
			$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')
										  ->getAlignment()
										  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFont()->setSize(14);
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2','')
										  ->mergeCells('A2:O2');
							
			$objPHPExcel->getActiveSheet()->setCellValue('A3','')
										  ->mergeCells('A3:O3');												  
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => '000000'),
					'size'  => 14,
					'name'  => 'Calibri'
			));
				
			$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A4',strtoupper($title))
										  ->mergeCells('A4:O4');
			$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			
			#HEADING
			$objPHPExcel->getActiveSheet()->setCellValue('A5', "S/N")
								  ->setCellValue('B5', "Date")
								  ->setCellValue('C5', "Revenue")
								  ->setCellValue('D5', "N20 Revenue")
								  ->setCellValue('E5', "N100 Revenue")
								  ->setCellValue('F5', "N200 Revenue")
								  ->setCellValue('G5', "N500 Revenue")
								  ->setCellValue('H5', "Active")
								  ->setCellValue('I5', "New")
								  ->setCellValue('J5', "Trial")
								  ->setCellValue('K5', "Failed Activations")
								  ->setCellValue('L5', "Customers with Failed Chargings")
								  ->setCellValue('M5', "Grey Area")
								  ->setCellValue('N5', "Cancelled")
								  ->setCellValue('O5', "Charging Success Rate, %");
			
			$objPHPExcel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C5:G5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$objPHPExcel->getActiveSheet()->getStyle('H5:N5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('O5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			
				
			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFont()->setSize(10);
												  
			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getFill()->getStartColor()->setRGB('FF0000');
			
			#$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
			
			$styleArray = array(
				'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => 'FFFFFF'),
				'size'  => 10,
				'name'  => 'Calibri'
			));
				
			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->getStyle('D5:G5')->getAlignment()->setWrapText(true);

			#$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
			
			$objPHPExcel->getActiveSheet()->getStyle('L5')->getAlignment()->setWrapText(true);

			
			$objPHPExcel->getActiveSheet()->getStyle('O5')->getAlignment()->setWrapText(true); 

			$objPHPExcel->getActiveSheet()->getStyle('A5:O5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$i=5; $sn=0; $totalrevenue=0;
			
			while ($row = $query->unbuffered_row('array')):
				$i++; $sn++; $n20=0; $n100=0; $n200=0; $n500=0;
				
				$dt=''; $rev='0'; $n20='0'; $n100='0'; $n200='0'; $n500='0'; $active='0'; $newsub='0';
				$trial='0'; $failed_activation='0'; $custfailedcharging='0'; $greyarea='0'; $cancelled='0';
				$charging_success='0';
					
				if ($row['sdate']) $dt=$row['sdate'];
				if ($row['N20total']) $n20=$row['N20total'];
				if ($row['N100total']) $n100=$row['N100total'];
				if ($row['N200total']) $n200=$row['N200total'];					
				if ($row['N500total']) $n500=$row['N500total'];					
				if ($row['active']) $active= $row['active']; #number_format($row['active'],0);
				if ($row['newsub']) $newsub=$row['newsub'];
				if ($row['trial']) $trial=$row['trial'];
				if ($row['failed_activation']) $failed_activation=$row['failed_activation'];
				if ($row['custfailedcharging']) $custfailedcharging=$row['custfailedcharging'];
				if ($row['greyarea']) $greyarea=$row['greyarea'];
				if ($row['cancelled']) $cancelled=$row['cancelled'];
				if ($row['charging_success']) $charging_success=number_format($row['charging_success'],2);
				
				$revenue = floatval($n20) + floatval($n100) + floatval($n200) + floatval($n500);
				
				$totalrevenue += floatval($revenue);
				$total20 += floatval($n20);
				$total100 += floatval($n100);
				$total200 += floatval($n200);
				$total500 += floatval($n500);
		
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sn);
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $dt);
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $revenue);
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $n20);
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $n100);
				$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $n200);
				$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $n500);
				$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $active);
				$objPHPExcel->getActiveSheet()->setCellValue('I' . $i, $newsub);
				$objPHPExcel->getActiveSheet()->setCellValue('J' . $i, $trial);
				$objPHPExcel->getActiveSheet()->setCellValue('K' . $i, $failed_activation);
				$objPHPExcel->getActiveSheet()->setCellValue('L' . $i, $custfailedcharging);
				$objPHPExcel->getActiveSheet()->setCellValue('M' . $i, $greyarea);
				$objPHPExcel->getActiveSheet()->setCellValue('N' . $i, $cancelled);
				$objPHPExcel->getActiveSheet()->setCellValue('O' . $i, $charging_success);
				
				#Value Format
				$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
				$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
				
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':N'.$i)->getNumberFormat()->setFormatCode('#,###,###,###');
				
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':B'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i.':N'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				
				$styleArray = array(
					'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => '000000'),
					'size'  => 9,
					'name'  => 'Calibri'
				));
					
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->applyFromArray($styleArray);
			endwhile;
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);#Auto Column
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);#Auto Column
			
			$i++;

			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10.86);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8.57);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14.57);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
			$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
			$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(14);
			$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(9);
			$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(8.5);
			$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(13.14);
			
			
			#FOOTER
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, "")
								  ->setCellValue('B'.$i, "")
								  ->setCellValue('C'.$i, $totalrevenue)
								  ->setCellValue('D'.$i, $total20)
								  ->setCellValue('E'.$i, $total100)
								  ->setCellValue('F'.$i, $total200)
								  ->setCellValue('G'.$i, $total500)
								  ->setCellValue('H'.$i, "")
								  ->setCellValue('I'.$i, "")
								  ->setCellValue('J'.$i, "")
								  ->setCellValue('K'.$i, "")
								  ->setCellValue('L'.$i, "")
								  ->setCellValue('M'.$i, "")
								  ->setCellValue('N'.$i, "")
								  ->setCellValue('O'.$i, "");
			
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								
												  
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getFill()->getStartColor()->setRGB('92D050');
			
			$styleArray = array(
				'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '000000'),
				'size'  => 10,
				'name'  => 'Calibri'
			));
				
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':O'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			#FORMAT CELLS
			$objPHPExcel->getActiveSheet()->getStyle('C'.$i.':G'.$i)->getNumberFormat()
				->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
			
			
								
			#Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Daily Revenue Records');
			
			#Set print footers
			$objPHPExcel->getActiveSheet()
				->getHeaderFooter()->setOddFooter('&R&D &T&C&LPage &P Of &N');
			$objPHPExcel->getActiveSheet()
				->getHeaderFooter()->setEvenFooter('&L&D &T&C&RPage &P Of &N');
				
			#Save Excel 2007 file
			#$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			#$objWriter->save($xlsx_file);
			
			if (file_exists(base_url().'reports/'.$xls_file)) unlink(base_url().'reports/'.$xls_file);
			
			#Save Excel 95 file
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');#Excel5
			$objWriter->save('reports/'.$xls_file);
			
			#$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			#$objWriter->save(base_url().'reports/'.$xls_file, __FILE__);
			
			$ret=$xls_file; #Or $xlsx_file
		}else
		{
			$ret="There is no daily revenue record for the selected date.";
		}
				
		echo $ret;
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
			
			

			$this->load->view('dailyrevenue_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
