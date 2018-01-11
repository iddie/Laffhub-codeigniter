<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');


class Transactions extends CI_Controller {
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url');
										
		#Load the getdata model to make it available to *all* of the controller's actions 
		$this->load->model('getdata_model');
		
		#$this->PlayVideo();
	 }
	
	public function v($key)
	{
		$this->PlayVideo($key);
	}
	
	public function PlayVideo($VideoCode)
	{
#$file = fopen('aaa.txt',"a"); fwrite($file,"\n".date('Y-m-d H:i:s')." => ".GetMSISDN()." => ".$_SERVER['REQUEST_METHOD']." => ".http_response_code()); fclose($file);
		$tdt=date("Y-m-d H:i:s");
		
		$data['filename']=''; $data['title']=''; $data['jwplayer_key']='';
		$data['description']=''; $data['category']=''; $data['domain_name']='';
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		
		#Get Player Key
		$sql="SELECT jwplayer_key FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();	
					
			if ($row->jwplayer_key) $data['jwplayer_key'] = $row->jwplayer_key;
		}
		
		#Get domain_name
		$sql="SELECT domain_name FROM streaming_domain";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();						
			if ($row->domain_name) $data['domain_name'] = $row->domain_name;
		}
		
		#Get Active rss feed
		$sql = "SELECT filename,rss_feed.title,description,rss_feed.category FROM rss_feed,active_rss_feed WHERE (rss_feed.feed_id=active_rss_feed.feed_id) AND (TRIM(rss_feed.video_code)='".$this->db->escape_str($VideoCode)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->filename) $data['filename']=$row->filename;
			if ($row->title) $data['title']=$row->title;
			if ($row->description) $data['description']=$row->description;
			if ($row->category) $data['category']=$row->category;
			
			#Save Transaction
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
			$phone=$this->getdata_model->GetMSISDN();
			
			
			if ($phone)
			{
				$t=trim($phone[0]);
				
				if (trim($t) != '') $phone='+'.trim($phone);
			}
			
			#$tddate=date('Y-m-d H:i',strtotime($tdt));
			$tddate=date('Y-m-d',strtotime($tdt));
			
			#Check if it is first time it registering
			#$sql = "SELECT trans_date FROM transactions WHERE (DATE_FORMAT(trans_date,'%Y-%m-%d %H:%i')='".$tddate."') AND (TRIM(filename)='".$row->filename."') AND ((TRIM(remote_address)='".$_SERVER['REMOTE_ADDR']."') OR (TRIM(phone)='".$phone."'))";
			$sql = "SELECT trans_date FROM transactions WHERE (DATE_FORMAT(trans_date,'%Y-%m-%d')='".$tddate."') AND (TRIM(filename)='".$row->filename."') AND ((TRIM(remote_address)='".$_SERVER['REMOTE_ADDR']."') OR (TRIM(phone)='".$phone."'))";
		
			$qry = $this->db->query($sql);
#$file = fopen('aaa.txt',"w"); fwrite($file,"\n".$sql); fclose($file);						
			if ($qry->num_rows() == 0 )
			{
				$dat=array(
					'phone' => $this->db->escape_str($phone),
					'trans_date' => $tdt,
					'filename' => $this->db->escape_str($row->filename),
					'user_agent' => $this->db->escape_str($useragent),
					'video_category' => $this->db->escape_str($row->category),
					'remote_address' => $this->db->escape_str($remote_ip),	
					'remote_host' => $this->db->escape_str($remote_host),	
					'lang' => $this->db->escape_str($lang),
					'network' => 'MTN'
				);
				
				$this->db->insert('transactions', $dat);	
					
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === FALSE)
				{
					$Msg="Transaction From User Agent '".strtoupper($useragent).", Remote Host '".strtoupper($remote_host)."' AND Remote IP'".strtoupper($remote_ip)."' Failed.";	
					
					$this->getdata_model->LogDetails('System',$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'GENERATE NEW VIDEO FEED','System');
				}
				
				if (trim($phone) != '')
				{
					#Add record to subscribers table
					$sql = "SELECT phone FROM subscribers WHERE (TRIM(phone)='".$this->db->escape_str($phone)."')";		
					$query = $this->db->query($sql);
								
					if ($query->num_rows() == 0 )#Insert
					{
						$dat=array(
						'phone' => $this->db->escape_str($phone),
							'subscriber_status' => 1,
							'network' => 'MTN'
						);
					
						$this->db->insert('subscribers', $dat);	
						
						$this->db->trans_complete();
					}	
				}					
			}
			
			$this->load->view('play',$data);
		}else
		{
			$this->load->view('notfound',$data);
		}
	}
	
	public function GetVideo()
	{		
		if ($this->input->post('username')) $username = $this->input->post('username');
		if ($this->input->post('UserFullName')) $UserFullName = $this->input->post('UserFullName');
		if ($this->input->post('companyname')) $companyname = $this->input->post('companyname');
		
		#echo 'Thanks For Sending.<br>';
		#$headers=apache_request_headers();
		
		$s='';
		
		$s='SERVER_NAME: '.$_SERVER['SERVER_NAME'].'<br>';
		$s.='QUERY_STRING: '.$_SERVER['QUERY_STRING'].'<br>';
		$s.='HTTP_ACCEPT: '.$_SERVER['HTTP_ACCEPT'].'<br>';
		$s.='HTTP_ACCEPT_CHARSET: '.$_SERVER['HTTP_ACCEPT_CHARSET'].'<br>';
		$s.='HTTP_ACCEPT_ENCODING: '.$_SERVER['HTTP_ACCEPT_ENCODING'].'<br>';
		$s.='HTTP_ACCEPT_LANGUAGE: '.$_SERVER['HTTP_ACCEPT_LANGUAGE'].'<br>';
		$s.='HTTP_CONNECTION: '.$_SERVER['HTTP_CONNECTION'].'<br>';
		$s.='HTTP_HOST: '.$_SERVER['HTTP_HOST'].'<br>';
		$s.='HTTP_REFERER: '.$_SERVER['HTTP_REFERER'].'<br>';
		$s.='HTTP_USER_AGENT: '.$_SERVER['HTTP_USER_AGENT'].'<br>';
		$s.='HTTPS: '.$_SERVER['HTTPS'].'<br>';
		$s.='REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'].'<br>';
		$s.='REMOTE_HOST: '.$_SERVER['REMOTE_HOST'].'<br>';
		$s.='REMOTE_PORT: '.$_SERVER['REMOTE_PORT'].'<br>';
		$s.='SCRIPT_FILENAME: '.$_SERVER['SCRIPT_FILENAME'].'<br>';
		$s.='SERVER_ADMIN: '.$_SERVER['SERVER_ADMIN'].'<br>';
		$s.='SERVER_PORT: '.$_SERVER['SERVER_PORT'].'<br>';
		$s.='SERVER_SIGNATURE: '.$_SERVER['SERVER_SIGNATURE'].'<br>';
		$s.='PATH_TRANSLATED: '.$_SERVER['PATH_TRANSLATED'].'<br>';
		$s.='REQUEST_URI: '.$_SERVER['REQUEST_URI'].'<br>';
		$s.='AUTH_TYPE: '.$_SERVER['AUTH_TYPE'];
		
		#$bla = $_SERVER['REMOTE_ADDR'];
   #echo "<li>REMOTE_ADDR = $bla</li>";
   #foreach($_SERVER as $h=>$v)
        #if(ereg('HTTP_(.+)',$h,$hp))
               #echo "<li>$h = $v</li>\n";
		   
		#$headers = getallheaders (); print_r($headers);
		
		$headers = $_SERVER;

foreach ($headers as $header => $value){

echo "$header: $value <br />\n";
}
		
		#echo $_SERVER['HTTP_USER_AGENT'];
		exit();
		
		
		$indicesServer = array('PHP_SELF',
		'GATEWAY_INTERFACE','SERVER_ADDR',
		'SERVER_NAME','SERVER_SOFTWARE',
		'SERVER_PROTOCOL','REQUEST_METHOD',
		'REQUEST_TIME','REQUEST_TIME_FLOAT',
		'HTTP_ACCEPT','HTTP_ACCEPT_CHARSET',
		'HTTP_ACCEPT_ENCODING','HTTP_ACCEPT_LANGUAGE',
		'HTTP_CONNECTION','REMOTE_ADDR',
		'HTTP_REFERER','HTTP_USER_AGENT',
		'X_H3G_MSISDN','X_NOKIA_MSISDN','X-UP-CALLING-LINE-I',
		'X_MSISDN','MSISDN',
		'X_NETWORK_INFO','X-WAP-MSISDN',
		'X-UP-SUBNO','PHP_AUTH_PW',
		'AUTH_TYPE','PATH_INFO','ORIG_PATH_INFO'	
			) ; 
	
	
	#header("Content-Type: text/vnd.wap.wml");
	
		$t= '<table cellpadding="10">' ;
		foreach ($indicesServer as $arg) {
			if (isset($_SERVER[$arg])) {
				$t.= '<tr><td>'.$arg.'</td><td>' . $_SERVER[$arg] . '</td></tr>' ;
			}
			else {
				$t.= '<tr><td>'.$arg.'</td><td>-</td></tr>' ;
			}
		}
		$t .= '</table>' ;
		
		foreach ($headers as $header => $value) {
			#$s.="$header: $value <br />";
		}

		print_r($t);
	}

	
	public function index()
	{		
		
	}
}
?>

