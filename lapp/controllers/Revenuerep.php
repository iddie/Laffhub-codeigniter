<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#include ('nairaandkobo.php');
#include("mpdf/mpdf.php");
include('classes/PHPExcel.php');

class Revenuerep extends CI_Controller {
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
		//$this->load->helper('form');
		
		$this->load->model('getdata_model');
	 }
		
	public function GetReport()
	{
		$startdate=''; $enddate='';	$network=''; $data=array();
				
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		
		if (strtolower($network)=='airtel')
		{
			$sql="SELECT DATE_FORMAT(subscribe_date,'%d&nbsp;%b&nbsp;%Y') AS sdate,N20total,N100total,N200total,N500total FROM airtel_daily_revenue WHERE (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') ORDER BY subscribe_date DESC";

			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{				
				$sn=0;
	
				while ($row = $query->unbuffered_row('array')):
					$sn++; $totalrevenue=0;
						
					if ($row['N20total']) $totalrevenue += $row['N20total'];
					if ($row['N100total']) $totalrevenue += $row['N100total'];
					if ($row['N200total']) $totalrevenue += $row['N200total'];
					if ($row['N500total']) $totalrevenue += $row['N500total'];
					
#SN,Date,Network,N20Revenue,N100Revenue,N200Revenue,N500Revenue,TotalRevenue						
					$tp=array($sn,$row['sdate'],$network,number_format($row['N20total'],2),number_format($row['N100total'],2),number_format($row['N200total'],2),number_format($row['N500total'],2),number_format($totalrevenue,2),);						
					
					$data[]=$tp;
				endwhile;
				
				print_r(json_encode($data));
			}else
			{
				print_r(json_encode($data));
			}
		}		
	}
	
	public function PrintReport()
	{
		$startdate=''; $enddate='';	$network='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('title')) $title = $this->input->post('title');
		
		if (strtolower($network)=='airtel')
		{
			$sql="SELECT DATE_FORMAT(subscribe_date,'%d %b %Y') AS sdate,N20total,N100total,N200total,N500total FROM airtel_daily_revenue WHERE (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') ORDER BY subscribe_date DESC";

			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				$logo=dirname(__FILE__)."/header_logo.png";
									
				$xls_file='revenuesreport.xls';
								
				#Create new PHPExcel object
				$objPHPExcel = new PHPExcel();
				
				$objPHPExcel->getProperties()->setCreator("Eastwinds ICT")
									 ->setLastModifiedBy("Eastwinds ICT")
									 ->setTitle($title)
									 ->setSubject($title)
									 ->setDescription($title)
									 ->setKeywords("Revenues")
									 ->setCategory("Revenues");
				

									 
				$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
				
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
				#A to H
				$styleArray = array('font' => array('bold' => true));
				
				$objPHPExcel->getActiveSheet()->setCellValue('A4','');
				$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')
											  ->getAlignment()
											  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setSize(14);
				
				
				$objPHPExcel->getActiveSheet()->setCellValue('A2','')
											  ->mergeCells('A2:H2');
								
				$objPHPExcel->getActiveSheet()->setCellValue('A3','')
											  ->mergeCells('A3:H3');												  
				$styleArray = array(
					'font'  => array(
						'bold'  => true,
						'color' => array('rgb' => '000000'),
						'size'  => 14,
						'name'  => 'Calibri'
				));
					
				$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->setCellValue('A4',strtoupper($title))
											  ->mergeCells('A4:H4');
				$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				
				#HEADING
				$objPHPExcel->getActiveSheet()->setCellValue('A5', "S/N")
									  ->setCellValue('B5', "Date")
									  ->setCellValue('C5', "Network")
									  ->setCellValue('D5', "N20 Revenue")
									  ->setCellValue('E5', "N100 Revenue")
									  ->setCellValue('F5', "N200 Revenue")
									  ->setCellValue('G5', "N500 Revenue")
									  ->setCellValue('H5', "Total Revenue");
				
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
					
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFont()->setSize(10);
													  
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->getStartColor()->setRGB('DA7659');

				$styleArray = array(
					'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => 'FFFFFF'),
					'size'  => 10,
					'name'  => 'Calibri'
				));
					
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($styleArray);				
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				
				$i=5; $sn=0;
				$totalrevenue=0; $total20=0; $total100=0; $total200=0; $total500=0;
				
				while ($row = $query->unbuffered_row('array')):
					$i++; $sn++; $dt=0; $n20=0; $n100=0; $n200=0; $n500=0; $total=0;			
						
					if ($row['sdate']) $dt=$row['sdate'];
					if ($row['N20total']) $n20=$row['N20total'];
					if ($row['N100total']) $n100=$row['N100total'];
					if ($row['N200total']) $n200=$row['N200total'];
					if ($row['N500total']) $n500=$row['N500total'];
					
					$total=$n20+$n100+$n200+$n500;	
					
					$totalrevenue += floatval($total);
					$total20 += floatval($n20);
					$total100 += floatval($n100);
					$total200 += floatval($n200);
					$total500 += floatval($n500); 
								
					$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sn);
					$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $dt);
					$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $network);
					$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $n20);
					$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $n100);
					$objPHPExcel->getActiveSheet()->setCellValue('F' . $i, $n200);	
					$objPHPExcel->getActiveSheet()->setCellValue('G' . $i, $n500);	
					$objPHPExcel->getActiveSheet()->setCellValue('H' . $i, $total);				
					
					#Value Format
					$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);						
					
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':C'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
											
					$styleArray = array(
						'font'  => array(
						'bold'  => false,
						'color' => array('rgb' => '000000'),
						'size'  => 10,
						'name'  => 'Calibri'
					));
						
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($styleArray);
				endwhile;
				
				$i++;
					
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);#Auto Column
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);#Auto Column
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
				
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
				$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14);
				
				#FOOTER
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, "")
									  ->setCellValue('B'.$i, "")
									  ->setCellValue('C'.$i, "")
									  ->setCellValue('D'.$i, $total20)
									  ->setCellValue('E'.$i, $total100)
									  ->setCellValue('F'.$i, $total200)
									  ->setCellValue('G'.$i, $total500)
									  ->setCellValue('H'.$i, $totalrevenue);
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									
													  
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getFill()->getStartColor()->setRGB('92D050');
				
				$styleArray = array(
					'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => '000000'),
					'size'  => 10,
					'name'  => 'Calibri'
				));
					
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($styleArray);
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				
				#FORMAT CELLS
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i.':H'.$i)->getNumberFormat()
					->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					
					#End Footer
				
									
				#Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->setTitle('Revenues Records');
				
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
				$ret="There is no revenue record for the selected date.";
			}
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
			
			

			$this->load->view('revenuerep_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
