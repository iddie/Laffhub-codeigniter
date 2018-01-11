<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');


class Requests extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
	 }
	
	public function GetVideoRequest()
	{
		$phone=''; $ret=''; $shortlink='';
		
		if ($this->input->post('phone')) $phone = $this->input->post('phone');

		if (trim($phone) != '')
		{
			#$file = fopen('aaa.txt',"w"); fwrite($file, "My Phone: ".$phone); fclose($file);	
			
			$phone=$this->getdata_model->CleanPhoneNo($phone);
			
			#Get Active rss feed
			$sql = "SELECT shortlink FROM rss_feed,active_rss_feed WHERE (rss_feed.feed_id=active_rss_feed.feed_id)";
			
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->shortlink) $shortlink=$row->shortlink;
			}
			
			if (trim($shortlink) != '')
			{
				#Prepare Message
				$ret='Thank you for subscribing to the Healthy Living service. Please click on the link below to watch the video: '.$shortlink;
				
				#Send Message
				$this->getdata_model->SendBulkSMS('LaffHub',$phone,$ret);	
			}else
			{
				$r=$this->getdata_model->CheckForActiveFeed();
				
				if ($r==true)
				{
					$ret='Thank you for subscribing to the Healthy Living service. Please click on the link below to watch the video: '.$shortlink;
					$this->getdata_model->SendBulkSMS('LaffHub',$phone,$ret);
				}else
				{
					$ret='System not available. Please contact us at support@laffhub.com!';
					
					$this->getdata_model->SendBulkSMS('LaffHub',$phone,$ret);
				}
			}			
		}else
		{
			$ret='Invalid Sender Phone  Number!';
		}
		
		print $ret;
	}
	
	public function index()
	{		
		
	}
}
?>

