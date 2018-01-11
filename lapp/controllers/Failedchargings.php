<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#include ('nairaandkobo.php');
#include("mpdf/mpdf.php");
include('classes/PHPExcel.php');

class Failedchargings extends CI_Controller {
	
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
				
		$sql="SELECT network,DATE_FORMAT(chargingdate,'%d %b %Y') AS sdate, plan,COUNT(msisdn) AS Total FROM cust_failed_charging WHERE (TRIM(network)='".$network."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(chargingdate,'%Y-%m-%d'),plan ORDER BY chargingdate DESC;";

#$file = fopen('aaa.txt',"w"); fwrite($file, $sql."\n"); fclose($file);	
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$sn=0;

			while ($row = $query->unbuffered_row('array')):
				$sn++;
					
				$tp=array($sn,$row['network'],$row['sdate'],$row['plan'],number_format($row['Total'],0));					
				
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
		$startdate=''; $enddate='';	$network='';
		
		if ($this->input->post('network')) $network = trim($this->input->post('network'));
		if ($this->input->post('startdate')) $startdate = $this->input->post('startdate');
		if ($this->input->post('enddate')) $enddate = $this->input->post('enddate');
		if ($this->input->post('title')) $title = $this->input->post('title');
		
		$sql="SELECT network,DATE_FORMAT(chargingdate,'%d %b %Y') AS sdate, plan,COUNT(msisdn) AS Total FROM cust_failed_charging WHERE (TRIM(network)='".$network."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(chargingdate,'%Y-%m-%d'),plan ORDER BY chargingdate DESC;";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			$logo=dirname(__FILE__)."/header_logo.png";
								
			$xls_file='failedchargingsreport.xls';
							
			#Create new PHPExcel object
			$objPHPExcel = new PHPExcel();
			
			$objPHPExcel->getProperties()->setCreator("Eastwinds ICT")
								 ->setLastModifiedBy("Eastwinds ICT")
								 ->setTitle($title)
								 ->setSubject($title)
								 ->setDescription($title)
								 ->setKeywords("Failed Chargings")
								 ->setCategory("Failed Chargings");
			

								 
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
			#A to E
			$styleArray = array('font' => array('bold' => true));
			
			$objPHPExcel->getActiveSheet()->setCellValue('A4','');
			$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')
										  ->getAlignment()
										  ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setSize(14);
			
			
			$objPHPExcel->getActiveSheet()->setCellValue('A2','')
										  ->mergeCells('A2:E2');
							
			$objPHPExcel->getActiveSheet()->setCellValue('A3','')
										  ->mergeCells('A3:E3');												  
			$styleArray = array(
				'font'  => array(
					'bold'  => true,
					'color' => array('rgb' => '000000'),
					'size'  => 14,
					'name'  => 'Calibri'
			));
				
			$objPHPExcel->getActiveSheet()->getStyle('A4')->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A4',strtoupper($title))
										  ->mergeCells('A4:E4');
			$objPHPExcel->getActiveSheet()->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			
			#HEADING
			$objPHPExcel->getActiveSheet()->setCellValue('A5', "S/N")
								  ->setCellValue('B5', "Network")
								  ->setCellValue('C5', "Charging Date")
								  ->setCellValue('D5', "Plan")
								  ->setCellValue('E5', "No. Of Subscribers");
			
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
				
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($styleArray);
			
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFont()->setSize(10);
												  
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFill()->getStartColor()->setRGB('FF0000');

			$styleArray = array(
				'font'  => array(
				'bold'  => false,
				'color' => array('rgb' => 'FFFFFF'),
				'size'  => 10,
				'name'  => 'Calibri'
			));
				
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($styleArray);				
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			
			$i=5; $sn=0;

			while ($row = $query->unbuffered_row('array')):
				$i++; $sn++; $dt=''; $nt=''; $tot=''; $pl='';				
				
				if ($row['sdate']) $dt=$row['sdate'];
				if ($row['network']) $nt=$row['network'];
				if ($row['Total']) $tot=$row['Total'];
				if ($row['plan']) $pl=$row['plan'];				
								
				$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, $sn);
				$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, $nt);
				$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, $dt);
				$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, $pl);
				$objPHPExcel->getActiveSheet()->setCellValue('E' . $i, $tot);					
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$styleArray = array(
					'font'  => array(
					'bold'  => false,
					'color' => array('rgb' => '000000'),
					'size'  => 10,
					'name'  => 'Calibri'
				));
					
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':E'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode('#,###,###,###');
			endwhile;
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);#Auto Column
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);#Auto Column
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(17);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(19);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(27);	
								
			#Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setTitle('Failed Chargings Records');
			
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
			$ret="There is no failed charging record for the selected date.";
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
			
			

			$this->load->view('failedchargings_view',$data);
		}else
		{
			redirect("Home");
		}
	}
}
