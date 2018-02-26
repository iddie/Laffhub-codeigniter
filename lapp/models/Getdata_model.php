<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_STRICT);

#error_reporting(E_ALL); ini_set('display_errors', 1); 

date_default_timezone_set('Africa/Lagos');

require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';
require_once('SimpleImage.php');
require_once('botr/api.php');
require_once('nairaandkobo.php');
require 'recommend.php';
	
class Getdata_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function GetAdminRoot()
	{
		$host=strtolower(trim($_SERVER['HTTP_HOST']));
		
		if ($host=='localhost')
		{
			return 'http://localhost/laffhub/';
		}else
		{
			return 'https://laffhub.com/';
		}
	}
	
	public function GetMTNSettings()
	{
		$sql = "SELECT * FROM mtn_settings";
		$query = $this->db->query($sql);
					
		return $query->result();
	}
	
	public function GetTotalCategoryVideos()
	{
		$sql="SELECT category,COUNT(video_code) AS TotalVideos,(SELECT pix FROM video_categories WHERE (TRIM(video_categories.category)=videos.category) LIMIT 0,1) AS pix FROM videos GROUP BY category ORDER BY category";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetMostPopularCategories()
	{
		$sql="SELECT `category`,count(watchcount) AS TotalCount,(SELECT pix FROM video_categories WHERE (TRIM(video_categories.category)=videos.category) LIMIT 0,1) AS pix FROM `videos` GROUP BY category ORDER BY category LIMIT 0,4";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetPaginationUrl($page,$rowcount,$qry,$order)
	{
		$start = (int)(($page - 1 ) * $rowcount);
		$qry .= $order." LIMIT ".$start.", ".$rowcount;
		
		return $qry;
	 }
	
	public function GetFeaturedVideos()
    {
        #Get Featured Videos
        $sql = "SELECT * FROM videos WHERE (TRIM(featured)='YES') AND (play_status=1) AND (encoded=1)";

        $query = $this->db->query($sql);

        return $query->result();
    }
	

	public function GetRandomlyRelatedVideos($category,$videocode,$comedian)
	{
		$sql = "SELECT * FROM videos WHERE (TRIM(comedian)='".$this->db->escape_str($comedian)."') AND (play_status=1) AND (encoded=1) AND (TRIM(video_code) <> '".$this->db->escape_str($videocode)."') ORDER BY RAND() LIMIT 12";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetRandomCategoryVideos()
    {
        $category_array = array();  $category_videos=array();  $category_display='';

        $sql = "SELECT category FROM video_categories";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                array_push($category_array, $row->category);
            }

            for ($i = 0; $i < count($category_array); $i++) {

                $videos_captured=array();

                $sql = "SELECT * FROM videos WHERE (TRIM(category)='" . $category_array[$i] . "') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

                $query = $this->db->query($sql);

                if($query->num_rows() > 0){

                    foreach ($query->result() as $row) {

                        $video_record = array(
                            'video_title' => $row->video_title,
                            'category' => $row->category,
                            'duration' => $row->duration,
                            'date_created' => $row->date_created,
                            'encoded' => $row->encoded,
                            'description' => $row->description,
                            'video_code' => $row->video_code,
                            'thumbnail' => $row->thumbnail,
                            'play_status' => $row->play_status,
                            'watchcount' => $row->watchcount,
                            'comedian' => $row->comedian,
                            'likes' => $row->likes,
                            'dislikes' => $row->dislikes,
                            'filename' => $row->filename
                        );

                        array_push($videos_captured, $video_record);
                    }
                    array_push($category_videos, $videos_captured);
                }else
                {
                   unset($category_array[$i]);
                   $category_array[$i] = array_values( $category_array[$i]);
                }
            }
            $category_array = array_filter($category_array);
            $category_array = array_values( $category_array);
            $category_display = array_combine($category_array, $category_videos);

        }
        return $category_display;
    }

    public function GetComedySkits()
    {
        #Get Comedy skit videos
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='Comedy Skits') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

        $query = $this->db->query($sql);

        return $query->result();
    }


    public function GetJustForLaughs()
    {
        #Get Just for Laughs
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='Just For Laughs Gag') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function GetStandUpComedy()
    {
        #Get Standup Comedy clips
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='Stand Up Comedy') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

        $query = $this->db->query($sql);

        return $query->result();
    }

    public function GetArewa()
    {
        #Get Arewa clips
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='Arewa') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

        $query = $this->db->query($sql);

        return $query->result();
    }


    public function GetComedyNews()
    {
        #Get Comedy news
        $sql = "SELECT * FROM videos WHERE (TRIM(category)='Comedy News') AND (play_status=1) AND (encoded=1) ORDER BY RAND() LIMIT 12";

        $query = $this->db->query($sql);

        return $query->result();
    }

	public function GetActiveAdverts()
	{
		$sql="SELECT * FROM ads WHERE ads_status=1";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetTrialAccounts($startdate,$enddate,$network)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM trials WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(subscribe_date,'%Y-%m-%d') ORDER BY DATE_FORMAT(subscribe_date,'%Y-%m-%d') DESC";
		
		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetFailedChargings($startdate,$enddate,$network)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM cust_failed_charging WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(chargingdate,'%Y-%m-%d') ORDER BY DATE_FORMAT(chargingdate,'%Y-%m-%d') DESC";
		
		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetFailedActivations($startdate,$enddate,$network)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM failed_activations WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(activationdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(activationdate,'%Y-%m-%d') ORDER BY DATE_FORMAT(activationdate,'%Y-%m-%d') DESC";
		
		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetGreyAreas($startdate,$enddate,$network)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM greyareas WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(chargingdate,'%Y-%m-%d') ORDER BY DATE_FORMAT(chargingdate,'%Y-%m-%d') DESC";
		
		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetCancelledSubscribers($startdate,$enddate,$network)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM optouts WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(optout_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(optout_date,'%Y-%m-%d') ORDER BY DATE_FORMAT(optout_date,'%Y-%m-%d') DESC";

		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetNewSubscribers($startdate,$enddate,$network)
	{
		if (trim(strtolower($network))=='airtel')
		{
			$sql="SELECT DATE_FORMAT(subscriptiondate,'%Y-%m-%d') AS 'dt', COUNT(msisdn) AS Total FROM new_subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(subscriptiondate,'%Y-%m-%d') ORDER BY DATE_FORMAT(subscriptiondate,'%Y-%m-%d') DESC";	
		}elseif (trim(strtolower($network))=='wifi')
		{
			$sql="SELECT DATE_FORMAT(subscriptiondate,'%Y-%m-%d') AS 'dt', COUNT(email) AS Total FROM new_subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(subscriptiondate,'%Y-%m-%d') ORDER BY DATE_FORMAT(subscriptiondate,'%Y-%m-%d') DESC";
		}
		

		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	public function GetTotalDaySubscription($network,$todaydate)
	{
		$sql="SELECT COUNT(msisdn) AS Total FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".$todaydate."')";

		$query = $this->db->query($sql);
		
		$cnt=0;
		
		if ($query->num_rows() > 0 )#Insert
		{
			$row = $query->row();
			
			if ($row->Total) $cnt=$row->Total;
		}
		
		return $cnt;		
	}
	
	public function GetActiveSubscribers($startdate,$enddate,$network)
	{		
		$sql="SELECT COUNT(msisdn) AS Total FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') BETWEEN '".$startdate."' AND '".$enddate."') GROUP BY DATE_FORMAT(subscribe_date,'%Y-%m-%d') ORDER BY DATE_FORMAT(subscribe_date,'%Y-%m-%d') DESC";		

$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);

		$query = $this->db->query($sql);
		
		$arr=array();
		
		if ($query->num_rows() > 0 )#Insert
		{
			foreach ($query->result() as $row)
			{
					if ($row->dt and $row->Total) $arr[$row->dt]=$row->Total;
			}
			
			return $arr;
		}
		
		return $arr;		
	}
	
	#NEW
	public function IsNewSubscriber($msisdn,$network)
	{
		$sql="SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";

		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0)
		{
			return 0;
		}else
		{
			#Check blacklist
			$sql="SELECT * FROM blacklist WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				return 0; 
			}else
			{
				#Check optouts
				$sql="SELECT * FROM optouts WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";
				
				$query = $this->db->query($sql);
				
				if ($query->num_rows() > 0)
				{
					return 0;
				}else
				{
					#Check freetrials
					$sql="SELECT * FROM freetrials WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(msisdn)='".$this->db->escape_str($msisdn)."')";
					
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0) return 0; else return 1;
				}
			}
		}
	}
	
	public function IsNewSubscriberEmail($email,$network)
	{
		$sql="SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";

		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0)
		{
			return 0;
		}else
		{
			#Check blacklist
			$sql="SELECT * FROM blacklist WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				return 0; 
			}else
			{
				#Check optouts
				$sql="SELECT * FROM optouts WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
				
				$query = $this->db->query($sql);
				
				if ($query->num_rows() > 0)
				{
					return 0;
				}else
				{
					#Check freetrials
					$sql="SELECT * FROM freetrials WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
					
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0) return 0; else return 1;
				}
			}
		}
	}
	
	public function LoadSubscriberSession($email)
	{
		$LogDate=date('Y-m-d H:i:s');
		$remote_ip=$_SERVER['REMOTE_ADDR'];
		$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
	
		$_SESSION['LogID']='';
		$_SESSION['subscriber_email']=$email;
		$_SESSION['subscriber_name']='';
		$_SESSION['subscriber_plan'] = '';
		$_SESSION['subscriber_status'] = '0';
		$_SESSION['subscription_status'] = '0';
		$_SESSION['SubscriberPhone']='';
		$_SESSION['facebook_id']='';
		#$_SESSION['SubscriberPhone']=$this->GSMPhoneNo(getenv('HTTP_MSISDN'));	
				
		$_SESSION['network'] = '';
		$_SESSION['jwplayer_key']='';		
		$_SESSION['distribution_Id']='';
		$_SESSION['domain_name']='';
		$_SESSION['origin']='';
		$_SESSION['thumbs_bucket'] = '';
		
		$dt=date('Y-m-d H:i:s');
		$edt = date('Y-m-d H:i:s',strtotime($dt.'+1 day'));
	
		$_SESSION['subscribe_date']=$dt;
		$_SESSION['exp_date'] = $edt;
		
		$_SESSION['RemoteIP']=$remote_ip;
		$_SESSION['RemoteHost']=$remote_host;
		$_SESSION['LogIn']=date('Y-m-d H:i:s');
		
		$_SESSION['subscriber_name'] = 'LaffHub Subscriber';
		$_SESSION['subscriber_pwd'] = '000000';	
		$_SESSION['subscriber_email'] = $email;					
		$_SESSION['datecreated'] = $dt;
		$_SESSION['subscriber_status'] = 1;		
		$_SESSION['subscription_status']='1';		
		$_SESSION['LogID']=uniqid();		
	
		$this->LogDetails('Promo Login','Subscriber Login',$_SESSION['subscriber_email'],$LogDate,$remote_ip,$remote_host,'SUBSCRIBER LOGIN',$_SESSION['LogID']);																			
							
		$ret='OK';

		
		#################################
		
		$sql="SELECT * FROM settings";

		$query = $this->db->query($sql);
	
		$row = $query->row();
		
		if (isset($row))
		{
			if ($row->no_of_videos_per_day) $_SESSION['no_of_videos_per_day']=$row->no_of_videos_per_day;
			if ($row->companyname) $_SESSION['companyname'] = $row->companyname;
			if ($row->companyemail) $_SESSION['companyemail'] = $row->companyemail;
			if ($row->companyphone) $_SESSION['companyphone'] = $row->companyphone;
			if ($row->website) $_SESSION['website'] = $row->website;
			if ($row->companylogo) $_SESSION['companylogo'] = $row->companylogo;
			if ($row->RefreshDuration) $_SESSION['RefreshDuration'] = $row->RefreshDuration;
			if ($row->default_network) $_SESSION['default_network'] = $row->default_network;
			if ($row->google_shortener_api) $_SESSION['google_shortener_api'] = $row->google_shortener_api;
			if ($row->jw_api_key) $_SESSION['jw_api_key'] = $row->jw_api_key;
			if ($row->jw_api_secret) $_SESSION['jw_api_secret'] = $row->jw_api_secret;
			if ($row->jw_player_id) $_SESSION['jw_player_id'] = $row->jw_player_id;		
			if ($row->emergency_emails) $_SESSION['emergency_emails'] = $row->emergency_emails;
			if ($row->emergency_no) $_SESSION['emergency_no'] = $row->emergency_no;						
			if ($row->sms_url) $_SESSION['sms_url'] = $row->sms_url;
			if ($row->sms_username) $_SESSION['sms_username'] = $row->sms_username;
			if ($row->sms_password) $_SESSION['sms_password'] = $row->sms_password;						
			if ($row->input_bucket) $_SESSION['input_bucket'] = $row->input_bucket;
			if ($row->output_bucket) $_SESSION['output_bucket'] = $row->output_bucket;
			if ($row->thumbs_bucket) $_SESSION['thumbs_bucket'] = $row->thumbs_bucket;
			if ($row->aws_key) $_SESSION['aws_key'] = $row->aws_key;
			if ($row->aws_secret) $_SESSION['aws_secret'] = $row->aws_secret;
			if ($row->jwplayer_key) $_SESSION['jwplayer_key'] = $row->jwplayer_key;
		}
	
		#Get Distribution Details
		$sql="SELECT * FROM streaming_domain";

		$query = $this->db->query($sql);
	
		$row = $query->row();
		
		if (isset($row))
		{
			if ($row->distribution_Id) $_SESSION['distribution_Id']=$row->distribution_Id;
			if ($row->domain_name) $_SESSION['domain_name'] = $row->domain_name;
			if ($row->origin) $_SESSION['origin'] = $row->origin;
		}
		
#echo session_id();	
		return true;
	}
	
	public function GetNetwork_bak()
	{
		$ip=$_SERVER['REMOTE_ADDR'];
		
		$nt='';
		
		if (trim($ip)=='::1')  $nt= 'WIFI';
		
		$sql = "SELECT network FROM isp_info WHERE TRIM(ip)='".$this->db->escape_str($ip)."'";
		$query = $this->db->query($sql);

		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
			
			if ($row->network) $nt=trim($row->network);
		}
				
		$_SESSION['InternetSource']=$nt;
		
		return $nt;
	}
	
	public function InternetSource($org,$countryCode,$isp)
	{
		if (!$countryCode and !$isp) return 'WIFI';
		 
		 #GLO      =>  Country Code=NG; $org=globacom; ISP=Globacom Limited; IP=197.211.56.44
		 #ETISALAT =>  Country Code=NG; $org=Emts-nigeria; ISP=EMTS Limited / Etisalat Nigeria; IP=41.190.2.246
		 #AIRTEL   =>  Country Code=NG; $org=Airtel Networks Limited; ISP=Airtel Networks Limited; IP=105.112.41.142
		 #MTN      =>  Country Code=NG; $org=MTN Nigeria; ISP=MTN Nigeria;  IP=197.210.46.18
		 
		 $countryCode=trim(strtolower($countryCode));
		 $isp=trim(strtolower($isp));
		 
		 if (($countryCode=='ng') and ($isp=='airtel networks limited'))#Airtel
		 {
			 $sub=stristr($isp,'airtel');
			 
			 if ($sub !== FALSE) return 'Airtel'; else return 'WIFI';
		 }elseif (($countryCode=='ng') and ($isp=='mtn nigeria'))#MTN
		 {
			 $sub=stristr($isp,'mtn');
			 
			 if ($sub !== FALSE) return 'MTN'; else return 'WIFI';
		 }elseif (($countryCode=='ng') and ($isp=='etisalat nigeria'))#Etisalat
		 {
			 $sub=stristr($isp,'etisalat');

			 if ($sub !== FALSE) return 'Etisalat'; else return 'WIFI';

		 }elseif (($countryCode=='ng') and ($isp=='globacom limited'))#GLO
		 {
			 return 'WIFI';
		 }else
		 {
			 return 'WIFI';
		 }
	 }
	 
	public function GetNetwork()
	{

        $ip='';

        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        }else{

            $ip = $_SERVER['REMOTE_ADDR'];
        }

		$ret='WIFI';
		
		if (trim($ip)<>'::1')
		{

		    $curl = curl_init();
			
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://pro.ip-api.com/json/'.$ip.'?key=5ulj4xXAgcXFzcV'
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			curl_close($curl);
			
			
			$result=json_decode($resp);
			
			$ret='WIFI';	
			
			#echo 'Msg: '.$result->status;
			if (trim(strtolower($result->status)) <> 'fail')
			{
				$ret=$this->InternetSource($result->org,$result->countryCode,$result->isp);
				
				if (trim(strtolower($ret)) <> 'wifi')
				{
					$sql = "SELECT * FROM isp_info WHERE (TRIM(network)='".trim($ret)."') AND (TRIM(ip)='".$this->db->escape_str($ip)."')";
								
					$query = $this->db->query($sql);
					
					if ($query->num_rows() == 0 )#Insert
					{
						$this->db->trans_start();
						
						$dat=array('network' => $ret,'ip' => $this->db->escape_str($ip));							
						
						$this->db->insert('isp_info', $dat);
						
						$this->db->trans_complete();	
					}	
				}
				
			}else
			{
				#Try db
				$sql = "SELECT network FROM isp_info WHERE TRIM(ip)='".$this->db->escape_str($ip)."'";
				$query = $this->db->query($sql);
		
				if ($query->num_rows() > 0)
				{
					$row = $query->row();
					
					if ($row->network) $ret=trim($row->network);
				}
			}
		}else
		{
			$curl = curl_init();
			
			curl_setopt_array($curl, array(
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => 'http://pro.ip-api.com/json/?key=5ulj4xXAgcXFzcV'
			));
			// Send the request & save response to $resp
			$resp = curl_exec($curl);
			curl_close($curl);
			
			
			$result=json_decode($resp);
			
			$ret='WIFI';	
			
			#echo 'Msg: '.$result->status;
			if (trim(strtolower($result->status)) <> 'fail')
			{
				$ret=$this->InternetSource($result->org,$result->countryCode,$result->isp);
				
				if (trim(strtolower($ret)) <> 'wifi')
				{
					$sql = "SELECT * FROM isp_info WHERE (TRIM(network)='".trim($ret)."') AND (TRIM(ip)='".$this->db->escape_str($ip)."')";
								
					$query = $this->db->query($sql);
					
					if ($query->num_rows() == 0 )#Insert
					{
						$this->db->trans_start();
						
						$dat=array('network' => $ret,'ip' => $this->db->escape_str($ip));							
						
						$this->db->insert('isp_info', $dat);
						
						$this->db->trans_complete();	
					}	
				}
				
			}else
			{
				#Try db
				$sql = "SELECT network FROM isp_info WHERE TRIM(ip)='".$this->db->escape_str($ip)."'";
				$query = $this->db->query($sql);
		
				if ($query->num_rows() > 0)
				{
					$row = $query->row();
					
					if ($row->network) $ret=trim($row->network);
				}
			}
		}
		
		$_SESSION['InternetSource']=$ret;
		
		return $ret;
	}
	
	public function GetMSISDN()
	{		
		$ph='';
				
		if (getenv('HTTP_X_UP_CALLING_LINE_ID'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('HTTP_X_UP_CALLING_LINE_ID')));
		}elseif (getenv('HTTP_MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('HTTP_MSISDN')));
		}elseif (getenv('X_UP_CALLING_LINE_ID'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('X_UP_CALLING_LINE_ID')));
		}elseif (getenv('HTTP_X_MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('HTTP_X_MSISDN')));
		}elseif (getenv('X-MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('X-MSISDN')));
		}elseif (getenv('X_MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('X_MSISDN')));
		}elseif (getenv('MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('MSISDN')));
		}elseif (getenv('X-UP-CALLING-LINE-ID'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('X-UP-CALLING-LINE-ID')));
		}elseif (getenv('X_WAP_NETWORK_CLIENT_MSISDN'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('X_WAP_NETWORK_CLIENT_MSISDN')));
		}elseif (getenv('HTTP_X_HTS_CLID'))
		{
			$ph=strip_tags($this->CleanPhoneNo(getenv('HTTP_X_HTS_CLID')));
		}
		
		return $ph;
	}
	
	public function CheckForBlackList($network,$email)
	{
		$sql = "SELECT * FROM blacklist WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0) return true; else return false;
		
	}
	
	public function GetWatchList($subscriptionId)
	{
		$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
		
		$query = $this->db->query($sql);
		
		$videolist='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->videolist) $videolist=trim($row->videolist);
		}
		
		return $videolist;
	}
	
	public function GetPaystackSettings()
	{
		$sql="SELECT * FROM paystack_settings";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetSubscriptionStatus($email,$phone)
	{
		if (!$email) return NULL;
		
		$sql="SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);
		
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
			
			if ($row->exp_date) $edt = $row->exp_date;
			
			$now=date('Y-m-d H:i:s');
			
			if ($now > $edt) return true; else return false;
		}else
		{
			return false;
		}
	}
	
	public function GetSubscriptionDate($email,$phone)
	{
		if (!$email) return NULL;
		
		$sql="SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
				
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetSubscriptionDetails($network,$msisdn,$email)
	{
		$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);
					
		return $query->result_array();
	}
	
	public function GetLatestMovies()
	{
		#Get Popular Movies ID
		$sql="SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) ORDER BY date_created DESC LIMIT 0,8";
		
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetViewPagePopularVideos($videocode)
	{
		#Get Popular Movies ID
		$sql="SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(video_code)<>'".trim($videocode)."') ORDER BY watchcount DESC LIMIT 0,14";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetPopularMovies()
	{		
			
		$sql="SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (watchcount>0) ORDER BY watchcount DESC LIMIT 0,18";
		
#$file = fopen('db_error.txt',"w"); fwrite($file,"Email=".$email."\nPhone=".$phone."\n".$sql); fclose($file);		

		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetLatestVideos()
	{
		$sql="SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) ORDER BY date_created DESC LIMIT 0,12";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function RecommendedMovies($email,$phone)
	{
		if ($email or $phone)
		{			
			#Get Build Movies Arrays
			$subscriber_id=''; $arrSubscriber=array(); $arrSubscriberVideos=array();
				
			if ($phone) $subscriber_id=$phone; elseif ($email) $subscriber_id=$email;
			
			#Check if subscriber has rating record
			$sql="SELECT * FROM user_ratings WHERE (TRIM(msisdn)='".$this->db->escape_str($subscriber_id)."') OR (TRIM(email)='".$this->db->escape_str($subscriber_id)."')";
			
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0)
			{
				#Get unique msisdn or email
				$sql="SELECT msisdn,email FROM user_ratings ORDER BY msisdn,email";
				
				$query = $this->db->query($sql);
				
				foreach ($query->result() as $row):
					if ($row->msisdn)
					{
						if(in_array($row->msisdn, $arrSubscriber)===FALSE) $arrSubscriber[]=$row->msisdn;
					}elseif ($row->email)
					{
						if(in_array($row->email, $arrSubscriber)===FALSE) $arrSubscriber[]=$row->email;
					}
				endforeach;
				
				#Get each subscriber's videos in user_rating table
				if (count($arrSubscriber) > 0)
				{
					foreach ($arrSubscriber as $sub):
						if ($sub)
						{
							$sql="SELECT video_code,rating FROM user_ratings WHERE ((TRIM(msisdn)='".$this->db->escape_str($sub)."') OR (TRIM(email)='".$this->db->escape_str($sub)."')) AND (rating>0)";
	
	
	
							$query = $this->db->query($sql);
							
							foreach ($query->result() as $row):
								if ($row->video_code and $row->rating)
								{
									$arrSubscriberVideos[$sub][$row->video_code]=$row->rating;
								}
							endforeach;
						}
					endforeach;
				}
				
				$DisplayCount=12; $output=array();
									
				if (count($arrSubscriberVideos) > 0)
				{				
					if ($phone) $Subscriber_Id=$phone; elseif ($email) $Subscriber_Id=$email;
					
					$re = new Recommend();
					$ret= $re->getRecommendations($arrSubscriberVideos, $Subscriber_Id);
				
				#$file = fopen('aaa.txt',"w"); fwrite($file,"Ret = ".count($ret)."\nDisplayCount=".$DisplayCount); fclose($file);
					if (count($ret) > $DisplayCount)
					{
						$i=0;
						foreach($ret as $key => $val):
							$i++;
							if ($i <= $DisplayCount) $output[$key]=$val; else break;
						endforeach;
					}else
					{
						$output=$ret;
					}
							
					return $output;
				}else
				{
					return NULL;
				}	
			}else
			{
				return NULL;
			}
		}else
		{
			return NULL;
		}
	}
	
	public function GetVideoTitle($video_code)
	{
		if ($video_code)
		{
			#Get Ratings
			$sql="SELECT video_title FROM videos WHERE (TRIM(video_code)='".$this->db->escape_str($video_code)."')";
			$query = $this->db->query($sql);
			
			$row = $query->row();
			
			if ($row->video_title) return $row->video_title; else return '';	
		}else
		{
			return '';
		}
	}
	
	public function AirtelSubscription($msisdn,$amount,$subscriptiondays,$eventType)
	{
		$Username_Charge = ''; $Password_Charge = ''; $cpId=''; $location=''; $wsdl='';
		$messaging_url='';
		
		$sql="SELECT * FROM airtel_settings";		
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row_array();
							
			if ($row['billing_password']) $Password_Charge=trim($row['billing_password']);
			if ($row['billing_username']) $Username_Charge=trim($row['billing_username']);
			if ($row['cpId']) $cpId=trim($row['cpId']);
			if ($row['billing_location']) $location=trim($row['billing_location']);
			if ($row['wsdl_path']) $wsdl=trim($row['wsdl_path']);
			if ($row['messaging_url']) $messaging_url=$row['messaging_url'];
		}
		
		$cpTid=date('YmdHis').'_'.$msisdn;					
		#$wsdl='http://www.laffhub.com/airtel/ChargingHttpService_ChargingHttp_Service.wsdl';
		
		try
		{
			#$location='https://196.46.244.21:8443/ChargingServiceFlowWeb/sca/ChargingExport1';
			#$location='https://172.24.15.10:8443/ChargingServiceFlowWeb/sca/ChargingExport1';
					
			$options=array(
				'uri'=>'http://efluxz.com/billingservice',
				'location' => $messaging_url
			);
				
			$client=new SoapClient(NULL,$options);
			
			$param=array(
				'username'			=> $Username_Charge,
				'password'			=> $Password_Charge,
				'location'			=> $location,
				'wsdl'				=> $wsdl,
				'userId' 			=> $msisdn, 
				'amount' 			=> $amount,
				'cpId'  			=> $cpId,
				'eventType' 		=> $eventType,
				'subscriptiondays'	=> $subscriptiondays
			);
			
			$result=$client->BillAirtelUser($param);
			
			return $result['description'];			
		} catch(Exception $e)
		{
			return array('Status' => 'FAILED','errorCode' => 'FFF','errorMessage' => $e->getMessage());
		}
	}
	
	public function SendAirtelSubScriptionMessage($msisdn,$message)
	{
		$Username = ''; $Password = ''; $messaging_url='';
		
		$sql="SELECT * FROM airtel_settings";		
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row_array();
							
			if ($row['messaging_password']) $Password=trim($row['messaging_password']);
			if ($row['messaging_username']) $Username=trim($row['messaging_username']);
			if ($row['messaging_url']) $messaging_url=trim($row['messaging_url']);
		}	
		
		$options=array(
			'uri'=>'http://efluxz.com/billingservice',
			'location' => $messaging_url
		);
		
		$client=new SoapClient(NULL,$options);
		
		$param=array(
			'msisdn' 		=> $msisdn, 
			'message' 		=> $message,
			'Username'  	=> $Username,
			'Password' 		=> $Password
			);
			
		$result=$client->SendMsgToAirtelUser($param);
		
		return $result;
	}
	
	public function GetAirtelSettings()
	{
		$sql = "SELECT * FROM airtel_settings";
		$query = $this->db->query($sql);
					
		return $query->result();
	}
		
	
	public function GetSubscriberPlan($email,$phone)
	{
		if (!$email) return NULL;
		
		$sql="SELECT plan FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";		
		
		$pl='';
		
		$query = $this->db->query($sql);			
	
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
							
			if ($row->plan) $pl=trim($row->plan);
		}

		return $pl;
	}
	 
	public function GetComedianVideos($comedian)
	{
		$sql = "SELECT * FROM videos WHERE (TRIM(comedian)='".$this->db->escape_str($comedian)."') AND (play_status=1)";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function UpdateUserWatchCount($videocode,$phone,$email,$subscriptionId)
	{
		$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
		
		$query = $this->db->query($sql);
			
		$videolist='';
		
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->videolist) $videolist=trim($row->videolist);
		}
		
		if ($videolist <> '')
		{
			$arrWatched=array(); $list=''; $found=false;
				
			$arrTotalWatched=explode('^',$videolist);
			
			foreach($arrTotalWatched as $itm):
				if ($itm)
				{#$file = fopen('aaa.txt',"a"); fwrite($file,"\nItem=".$itm); fclose($file);
					$ex=explode('|',$itm);
					
					$cd=''; $wc=0;
					
					if (count($ex)>0)
					{
						$cd=$ex[0]; $wc=$ex[1];
						#$arrWatched[$ex[0]]=$ex[1]; #array[videocode]=watchcount
						if (trim($cd) == trim($videocode))
						{
							$wc++;
							$found=true;
							
							if ($list=='') $list=$cd.'|'.$wc; else $list .= '^'.$cd.'|'.$wc;					
						}else
						{
							if ($list=='') $list=$cd.'|'.$wc; else $list .= '^'.$cd.'|'.$wc;
						}						
					}
				}
			endforeach;
			
			if ($found==false)
			{
				if ($list=='') $list=$videocode.'|1'; else $list .= '^'.$videocode.'|1';
			}
			
			if ($list <> '')
			{
				$this->db->trans_start();
	
				$dat=array('videolist' => $list);
						
				$this->db->where('subscriptionId',$subscriptionId);
				$this->db->update('watchlists', $dat);
				
				$this->db->trans_complete();
			}
		}else
		{#Create new list
			$this->db->trans_start();
		
			$dat=array('videolist' => trim($videocode).'|1');
					
			$this->db->where('subscriptionId',$subscriptionId);
			$this->db->update('watchlists', $dat);
			
			$this->db->trans_complete();
		}
						
		return true;
	}
	
	public function SetWatchCount($videocode,$phone,$email,$subscriptionId)
	{
		$sql="SELECT watchcount FROM videos WHERE (TRIM(video_code)='".$this->db->escape_str($videocode)."')";	
		$query = $this->db->query($sql);	

		$wc=0;
				
		if ($query->num_rows() > 0 )#Update
		{
			$row=$query->row();
			
			if ($row->watchcount) $wc=$row->watchcount;
		}
		
		$wc++;
		
		$this->db->trans_start();
		
		$dat=array('watchcount' => $wc);
				
		$this->db->where('video_code',$videocode);
		$this->db->update('videos', $dat);
		
		$this->db->trans_complete();
		
		$ret=$this->UpdateUserWatchCount($videocode,$phone,$email,$subscriptionId);
		
		
		#Update Subscription table
		$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$videolist='';
			
			$row = $query->row();
		
			if ($row->videolist) $videolist=trim($row->videolist);
			
			if ($videolist <> '')
			{
				$TotalWatched=count(explode('^',$videolist));
				
				$sql="SELECT videos_cnt_watched FROM subscriptions WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
		
				$query = $this->db->query($sql);			
			
				if ( $query->num_rows()> 0 )
				{
					$this->db->trans_start();
		
					$dat=array('videos_cnt_watched' => $TotalWatched);
							
					$this->db->where('subscriptionId',$subscriptionId);
					$this->db->update('subscriptions', $dat);
					
					$this->db->trans_complete();
				}
			}
		}
		
		return $wc;
	}
	
	public function CheckSubscriptionDate($email,$phone)
	{
		if (!$email) return NULL;
		
		$dt=date('Y-m-d H:i:s');
		
		$ret='0';
		
		$sql="SELECT exp_date FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
				
		$query = $this->db->query($sql);			
	
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
							
			if ($row->exp_date) $expdt=$row->exp_date;
		
			if ($dt < $expdt)
			{
				$ret='1';
				
			}else
			{
				$this->UpdateSubscriptionStatus($email,$phone,'0');
			}
		}
#$file = fopen('aaa.txt',"w"); fwrite($file,$dt."\nExp. Date=".$expdt."\nRet=".$ret); fclose($file);		
		return $ret;
	}
	
	public function UpdateSubscriptionStatus($email,$phone,$status)
	{
		if (!$email) return NULL;
		
		$sql="SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		
		$query = $this->db->query($sql);	

		$this->db->trans_start();
					
		if ($query->num_rows() > 0 )#Update
		{
			$dat=array('subscriptionstatus' => $status);				
			$this->db->where(array('email'=>$email));
			$this->db->update('subscriptions', $dat);
			
			$this->db->trans_complete();
		}
		
		return true;
	}
	
	public function GetRelatedVideos($category,$videocode)
	{
		$sql = "SELECT * FROM videos WHERE (TRIM(category)='".$this->db->escape_str($category)."') AND (TRIM(video_code)<>'".$this->db->escape_str($videocode)."')";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetComedians()
	{
		$sql = "SELECT * FROM comedians WHERE (comedian_status=1) ORDER BY comedian";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetVideoComments($videocode)
	{
		$arrParents=array(); $results=array();
		
		$sql="SELECT DISTINCT comment_id FROM comments WHERE (TRIM(videocode)='".$this->db->escape_str($videocode)."') AND (commentstatus=1) AND (parent_id=0)";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0)
		{
			while ($row = $query->unbuffered_row('array')):					
				$arrParents[]=$row['comment_id'];
			endwhile;
			
		
			if (count($arrParents) > 0)
			{
				foreach($arrParents as $pid):
					#Get top level record
					$sql = "SELECT * FROM comments WHERE (TRIM(videocode)='".$this->db->escape_str($videocode)."') AND (commentstatus=1) AND (TRIM(comment_id)=".$this->db->escape_str($pid).")";
				
					$query = $this->db->query($sql);
					
					if ($query->num_rows() > 0)
					{
						$rw = $query->row();
						
						#pos = parent (m) or child (r)
						$date_seconds=strtotime($rw->created_date);
						$date=date('d M Y @ H:i',strtotime($rw->created_date));
						
						$results[]=array('pos'=>'m','parentid'=>$rw->parent_id,'commentid'=>$rw->comment_id,'author'=>$rw->author,'comment'=>$rw->comment_text,'datecreated'=>$rw->created_date,'likes'=>$rw->likes,'date_seconds'=>$date_seconds,'date'=>$date,'dislikes'=>$rw->dislikes);
					}
					
					#Get children
					$sql = "SELECT * FROM comments WHERE (TRIM(videocode)='".$this->db->escape_str($videocode)."') AND (commentstatus=1) AND (TRIM(parent_id)=".$this->db->escape_str($pid).") ORDER BY created_date DESC";
				
					$query = $this->db->query($sql);	
					
					if ($query->num_rows() > 0)
					{
						while ($rw = $query->unbuffered_row('array')):					
							#pos = parent (m) or child (r)
							$date_seconds=strtotime($rw['created_date']);
							$date=date('d M Y @ H:i',strtotime($rw['created_date']));
							
							$results[]=array('pos'=>'r','parentid'=>$rw['parent_id'],'commentid'=>$rw['comment_id'],'author'=>$rw['author'],'comment'=>$rw['comment_text'],'datecreated'=>$rw['created_date'],'likes'=>$rw['likes'],'date_seconds'=>$date_seconds,'date'=>$date,'dislikes'=>$rw['dislikes']);	
						endwhile;
						
						
					}
				endforeach;
			}
		}		
		
		return $results;
	}
	
	public function GetCategoryMovies($sql)
	{
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	
	public function GetPublisherName($email)
	{
		if (!$email) return '';
		
		$sql = "SELECT publisher_name FROM publishers WHERE (TRIM(publisher_email)='".$this->db->escape_str($email)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row=$query->row();
			
			if ($row->publisher_name) return trim($row->publisher_name); else return '';
		}else
		{
			return '';
		}
	}
	
	public function GetSubscriberName($email)
	{
		if (!$email) return '';
		
		$sql = "SELECT name FROM subscribers WHERE (TRIM(email)='".$this->db->escape_str($email)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row=$query->row();
			
			if ($row->name) return trim($row->name); else return '';
		}else
		{
			return '';
		}
	}
	
	public function GetVideoCategories()
	{
		$sql="SELECT category FROM video_categories ORDER BY category";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function GetCategories()
	{
		$sql="SELECT category,pix FROM video_categories ORDER BY category";
		
		$query = $this->db->query($sql);
		
		return $query->result();
	}
	
	public function CheckForActiveMsg()#2 - Returns TRUE/FALSE.    If it is available
	{
		$ret=false;
		
		#Get current active message
		$sql="SELECT * FROM current_msg";
		$query = $this->db->query($sql);
	
		if ( $query->num_rows() > 0 )#Exists - Check for Expiry Date
		{
			$rt=$this->CheckIfActiveMsgExpired();#True - Expired, False - Not Expired
			
			if ($rt===true)
			{
				$ret=$this->CreateMsgSaveMsg();
			}
		}else#Create
		{
			$ret=$this->CreateMsgSaveMsg();
		}
		
		return $ret;
	}
	
	public function CheckIfActiveMsgExpired()#1 - Returns True/False
	{
		$dt=date('Y-m-d H:i:s');
		
		$ret=false;
		
		$sql="SELECT expiredate FROM current_msg";		
			
		$query = $this->db->query($sql);			
	
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
							
			if ($row->expiredate) $expdt=$row->expiredate;
		
			if ($dt > $expdt) $ret=true; else $ret=false;
		}else
		{
			$ret=$this->CreateMsgSaveMsg();
		}
		
		return $ret;
	}
	
	public function CreateMsgSaveMsg()#3 - Returns TRUE/FALSE
	{
		$displaydays=1; $MaxId=0; $MinId=0; $new_id=0; $ret=false; $OldMsg='';
			
		#Get Maximum msg_id ID
		$sql="SELECT MAX(msg_id) AS MaxID FROM health_msgs WHERE msg_status=1";
	
		$query = $this->db->query($sql);
				
		if ( $query->num_rows() > 0 )
		{
			$row = $query->row();
						
			if ($row->MaxID) $MaxId = $row->MaxID;
		}
		
		#Get Minimum Msg ID
		$sql="SELECT MIN(msg_id) AS MinID FROM health_msgs WHERE msg_status=1";
			
		$query = $this->db->query($sql);	
				
		if ( $query->num_rows() > 0 )
		{
			$row = $query->row();
						
			if ($row->MinID) $MinId = $row->MinID;
		}else
		{
			$MinId=1;
		}
		
		$OldScheduleID='';
		
		#Get current msg
		$sql="SELECT msg_id FROM current_msg";
		
		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0)#Get new_id
		{
			$row = $query->row();
			
			if ($row->msg_id)
			{
				$OldScheduleID=$row->msg_id;
				
				$new_id=$this->GetNextMsgSchedule($OldScheduleID,$MaxId,$MinId);					
			}else
			{
				$new_id=1;
			}
		}else#New 
		{
			$new_id=$MinId;
		}
		
		
		$msg='';
		$dt=date('Y-m-d H:i:s'); $schedule_id=''; $msg_status='';
		
		#Get Tag details
		$sql = "SELECT * FROM health_msgs WHERE (msg_id=".$new_id.") AND (msg_status=1)";
	
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->msg) $msg=$row->msg;
			if ($row->msg_status) $msg_status=$row->msg_status;
				
			#Replace Old Active Msg
			$qry = "SELECT * FROM current_msg";
			
			$myquery = $this->db->query($qry);
			
			$pdt=date('Y-m-d H:i:s', strtotime('today midnight'));
			$expdt= date('Y-m-d H:i:s', strtotime('+'.$displaydays.'days',strtotime($pdt)));
			
			$ret=false;
			
			$dat=array(
				'msg_id' => $this->db->escape_str($new_id),
				'pubdate' => $this->db->escape_str($pdt),
				'expiredate' => $this->db->escape_str($expdt),					
				'insert_date' => $dt
			);	
			
			$this->db->trans_start();
			
			if ($myquery->num_rows() > 0 )#Update
			{
				$this->db->update('current_msg', $dat);	
			}else#Insert
			{
				$this->db->insert('current_msg', $dat);	
			}
			
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === TRUE)
			{
				#Update health_msgs status
				$dat=array('status' => 'Running');
				
				$this->db->trans_start();
				$this->db->where('msg_id', $new_id);
				$this->db->update('health_msgs', $dat);						
				
				$this->db->trans_complete();
				####END Of health_msgs UPDATE
				
				#Update Old health_msgs Status
				if ($OldScheduleID > 0)
				{
					$dat=array('status' => 'Not Running');
					$this->db->trans_start();
					$this->db->where('msg_id', $OldScheduleID);
					$this->db->update('health_msgs', $dat);						
					$this->db->trans_complete();
				}
										
				$ret=$this->GenerateMsgRSS($new_id);
				
				if ($ret==true)
				{
					#Send Message
					$this->NotifyMsgScheduleByEmail();
				
					$Mg="System Generated A New Natural Health Tip Feed With ID '".$new_id."' And Also Created Active Natural Health Tip Feed For Export Successfully.";
				}else
				{
					$Mg="Natural Health Tip Feed Generation Was Not Successfully.";
				}				
			}else
			{
				$ret=false;
				$Mg="Natural Health Tip Feed Generation Was Not Successfully.";
			}	
		}else
		{
			$ret=false;
			$Msg="Health Message Generation Was Not Successful. No Health Message With The ID ".$new_id;
		}

		$remote_host='';			
		$remote_ip=$this->getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];
		
		#$host = $_SERVER['REMOTE_HOST'];
		
		if ($remote_ip) $remote_host=gethostbyaddr($remote_ip); 		
		
		$this->LogDetails('System',$Mg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'GENERATED NEW NATURAL HEALTH TIP','System');
				
		return $ret;
	}
	
	public function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) //to check ip is pass from proxy
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		
		return $ip;
	}
	
	public function GenerateMsgRSS($schedule_id)
	{
		$ret=false;
		
		if ($schedule_id)
		{
			$msg='';
						
			#Get current message.xml
			$sql = "SELECT * FROM health_msgs WHERE (msg_id=".$this->db->escape_str($schedule_id).")";
			$query = $this->db->query($sql);
					
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->msg) $msg=$this->encode_utf8($row->msg);
			}
						
			if ($msg)
			{			
				// The XML structure				
				$data = '<?xml version="1.0" encoding="UTF-8" ?>';
				$data .= '<rss version="2.0">';
				$data .= '<channel>';
								
				#rss items
				$data .= '<item>';
				$data .= '<title>LaffHub</title>';
				$data .= '<description>'.stripslashes($msg).'</description>';
				$data .='<pubDate>'.date('D d M Y H:i:s O').'</pubDate>';
				$data .= '</item>';
				
				$data .='<image>';
				$data .='<url>https://laffhub.com/img/logo.jpg</url>';
				$data .='</image>';	
				$data .= '</channel>';
				$data .= '</rss> ';
				 
				$file = fopen(str_replace('admin','',getcwd()).'message.xml',"w"); fwrite($file, $data);  fclose($file);
				#$file = fopen('message.xml',"w"); fwrite($file, $data);  fclose($file);
				
				$ret=true;
			}else
			{
				$ret=$this->CreateMsgSaveMsg();
			}
		}else
		{
			$ret=$this->CheckForActiveMsg();
		}
		
		return $ret;
	}
	
	function encode_utf8($s)
	{
	$cp1252_map = array(
	"\xc2\x80" => "\xe2\x82\xac",
	"\xc2\x82" => "\xe2\x80\x9a",
	"\xc2\x83" => "\xc6\x92",
	"\xc2\x84" => "\xe2\x80\x9e",
	"\xc2\x85" => "\xe2\x80\xa6",
	"\xc2\x86" => "\xe2\x80\xa0",
	"\xc2\x87" => "\xe2\x80\xa1",
	"\xc2\x88" => "\xcb\x86",
	"\xc2\x89" => "\xe2\x80\xb0",
	"\xc2\x8a" => "\xc5\xa0",
	"\xc2\x8b" => "\xe2\x80\xb9",
	"\xc2\x8c" => "\xc5\x92",
	"\xc2\x8e" => "\xc5\xbd",
	"\xc2\x91" => "\xe2\x80\x98",
	"\xc2\x92" => "\xe2\x80\x99",
	"\xc2\x93" => "\xe2\x80\x9c",
	"\xc2\x94" => "\xe2\x80\x9d",
	"\xc2\x95" => "\xe2\x80\xa2",
	"\xc2\x96" => "\xe2\x80\x93",
	"\xc2\x97" => "\xe2\x80\x94",
	"\xc2\x98" => "\xcb\x9c",
	"\xc2\x99" => "\xe2\x84\xa2",
	"\xc2\x9a" => "\xc5\xa1",
	"\xc2\x9b" => "\xe2\x80\xba",
	"\xc2\x9c" => "\xc5\x93",
	"\xc2\x9e" => "\xc5\xbe",
	"\xc2\x9f" => "\xc5\xb8"
	);
	
	$s=strtr(utf8_encode($s), $cp1252_map);
	
	$s=str_replace('&',"&#38;",$s);
	$s=str_replace('<',"&#60;",$s);
	$s=str_replace('>',"&#62;",$s);
	$s=str_replace('"',"&#34;",$s);
	$s=str_replace("'","&#39;",$s);
	
	return $s;
}

	function NotifyMsgScheduleByEmail()
	{
		#Get Current File Name And Send To Numbers
		$sql="SELECT * FROM current_msg";
		
		$query = $this->db->query($sql);
				
		if ( $query->num_rows() > 0 )
		{
			$pubdate=''; $expdate='';
			
			#Get msg_id from current_msg
			$row = $query->row();
						
			if ($row->msg_id) $msg_id = $row->msg_id;
			if ($row->pubdate) $pubdate = date('d M Y @ H:i:s',strtotime($row->pubdate));
			if ($row->expiredate) $expdate = date('d M Y @ H:i:s',strtotime($row->expiredate));		

			#Get Msg Record
			$sql="SELECT * FROM health_msgs WHERE msg_id=".$msg_id;
			
			$query = $this->db->query($sql);
			
			if ( $query->num_rows() > 0 )
			{
				$msg='';
				
				$row = $query->row();
						
				if ($row->msg) $msg = utf8_encode($row->msg);
				
				$from='support@laffhub.com';
				$to='o.dania@efluxz.com,davidumoh@icloud.com,ade@efluxz.com,david@laffhub.com';
				$subject='LaffHub Schedule';
				$Cc='cronjobs@laffhub.com,idongesit_a@yahoo.com,nsikakj@gmail.com';
				
				#$to='idongesit_a@yahoo.com';
							
				$message='
					<img src="images/emaillogo.png" width="100" alt="LaffHub" title="LaffHub" />
					<br><br>
					Hello,<br><br>
					
					A new <b><i>LaffHub</i></b> has been scheduled. Details of the message are below:<br><br>
					<p style="text-indent:50px;"><b>Health Tip:</b> '.stripcslashes($msg).'</p>
					<p style="text-indent:50px;"><b>Schedule Date:</b> '.$pubdate.'</p>
					<p style="text-indent:50px;"><b>Schedule Expiry Date:</b> '.$expdate.'</p>
					<br>
									
					<b><font color="#6DC742">laffhub.com</font></b>';
					
					$altMessage='
					Hello, 
					
					A new LaffHub has been scheduled. Details of the message are below: 
					
					Health Tip: '.stripcslashes($msg).'; 
					Schedule Date: '.$pubdate.';			 
					Schedule Expiry Date: '.$expdate.'.
					
					laffhub.com';
					
					
				$v=$this->SendEmail($from,$to,$subject,$Cc,$message,$altMessage,'');
				
				#$file = fopen('aaa.txt',"w"); fwrite($file,$v."\n".$message); fclose($file);
			}
		}
	}
		
	public function GetNextMsgSchedule($CurrentID,$MaxID,$MinID)
	{
		if (!$CurrentID) return 1;
		if (!$MaxID || !$MinID) return null;
		
		$new_id=0;
		
		if (intval($CurrentID)==intval($MaxID))
		{
			$new_id=intval($MinID);
		}else
		{
			$new_id=intval($CurrentID)+1;
			
			$found=false;
			
			while ($found==false)
			{
				$sql="SELECT msg FROM health_msgs WHERE (msg_id=".$new_id.") AND (msg_status=1)";		
				$query = $this->db->query($sql);
				
				if ( $query->num_rows() > 0 ) $found=true; else $new_id=intval($new_id)+1;
			}			
		}
		
		return $new_id;
	}
	
	
	
	####################################################################
	public function GetMsgStatus($msg_Id)
	{
		if ($msg_Id)
		{
			$sql = "SELECT status FROM health_msgs WHERE (TRIM(msg_id)=".$this->db->escape_str($msg_Id).")";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row=$query->row();
				
				if ($row->status) return $row->status; else return '';
			}else
			{
				return '';
			}	
		}else
		{
			return '';
		}
	}
	
	public function GetMsgID($msg)
	{
		if ($msg)
		{
			$sql = "SELECT msg_id FROM health_msgs WHERE (TRIM(msg)='".$this->db->escape_str($msg)."')";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row=$query->row();
				
				if ($row->msg_id) return $row->msg_id; else return '';
			}else
			{
				return '';
			}	
		}else
		{
			return '';
		}
	}
	
	public function GetCurrentMsg()
	{
		$sql = "SELECT msg FROM health_msgs,current_msg WHERE (health_msgs.msg_id=current_msg.msg_id)";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row=$query->row();
			
			if ($row->msg) return $row->msg; else return '';
		}else
		{
			return '';
		}
	}
	
	public function GetActiveMsgExpireDate($msg_Id)
	{
		if ($msg_Id)
		{
			$sql = "SELECT expiredate FROM current_msg WHERE (msg_id=".$this->db->escape_str($msg_Id).")";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row=$query->row();
				
				if ($row->expiredate) return $row->expiredate; else return NULL;
			}else
			{
				return NULL;
			}	
		}else
		{
			return NULL;
		}
	}
	
	public function GetActiveMsgPublishDate($msg_Id)
	{
		if ($msg_Id)
		{
			$sql = "SELECT pubdate FROM current_msg WHERE (msg_id=".$this->db->escape_str($msg_Id).")";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row=$query->row();
				
				if ($row->pubdate) return $row->pubdate; else return NULL;
			}else
			{
				return NULL;
			}	
		}else
		{
			return NULL;
		}
	}
	
	public function GetVideosUploadedByPublisher($email)
	{
		if ($email)
		{
			$sql = "SELECT count(video_title) AS cnt FROM videos WHERE (publisher_email='".$this->db->escape_str($email)."') AND (NOT video_title IS NULL)";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row=$query->row();
				
				if ($row->cnt) return $row->cnt; else return '0';
			}else
			{
				return '0';
			}	
		}else
		{
			return '0';
		}
	}
	
		
	public function CheckFolderIsEmptyOrNot( $folderName )
	{
		if (is_dir($folderName))
		{
			$files = array ();
			if ( $handle = opendir ( $folderName ) ) 
			{
				while ( false !== ( $file = readdir ( $handle ) ) ) 
				{
					if ( $file != "." && $file != ".." ) 
					{
						$files [] = $file;
					}
				}
				
				closedir ( $handle );
			}
			
			return ( count ( $files ) > 0 ) ? TRUE: FALSE;
		}else
		{
			return FALSE;
		}
		
		
	}
	
	public function GetActiveFeedRecord()
	{		
		#Get Active Feed
		$sql="SELECT feed_id FROM active_rss_feed";
		
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->feed_id) $feed_id=$row->feed_id;
					
			if ($feed_id)
			{
				$sql = "SELECT (SELECT schedule_id FROM active_rss_feed WHERE active_rss_feed.feed_id=".$feed_id." LIMIT 0,1) AS schedule_id,(SELECT pubdate FROM active_rss_feed WHERE active_rss_feed.feed_id=".$feed_id." LIMIT 0,1) AS pubdate,(SELECT expiredate FROM active_rss_feed WHERE active_rss_feed.feed_id=".$feed_id." LIMIT 0,1) AS expiredate, rss_feed.* FROM rss_feed WHERE rss_feed.feed_id=".$feed_id." ORDER BY feed_id";

#$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);				
				
				$query = $this->db->query($sql);
				
				if ($query->num_rows() > 0 )
				{
					#print_r($query->result()); exit();
					return $query->result();
				}else
				{
					return null;
				}
			}else
			{
				return null;
			}
#title,longlink,shortlink,description,STATUS,feed_id,filename,schedule_id,pubdate,expiredate
		}else
		{
			return null;		
		}
	}
	
	public function GenerateRSS($schedule_id)
	{
		$ret=false;
		
		if ($schedule_id)
		{
			$filename=''; $video_title=''; $video_description=''; $companyname=''; $website='';
			$pubdate=''; $expiredate=''; $feed_id=0; $thumb_bucket=''; $thumbnail=''; $category='';
			
			#Get Settings
			$sql="SELECT * FROM settings";
				
			$query = $this->db->query($sql);
					
			if ( $query->num_rows()> 0 )
			{
				$row = $query->row();	
						
				if ($row->no_of_videos_per_day) $no_of_videos_per_day = $row->no_of_videos_per_day;
				if ($row->website) $website = $row->website;
				if ($row->companyname) $companyname = $row->companyname;
				if ($row->thumbs_bucket) $thumb_bucket = $row->thumbs_bucket;
			}
			
			#Get current rss.xml
			$sql = "SELECT * FROM videos WHERE (schedule_id=".$this->db->escape_str($schedule_id).")";
			$query = $this->db->query($sql);
					
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->filename) $filename=$row->filename;
				if ($row->video_title) $video_title=$row->video_title;
				if ($row->description) $video_description=$row->description;
				if ($row->thumbnail) $thumbnail=$row->thumbnail;
				if ($row->category) $category=$row->category;
			}
						
			$sql = 'SELECT * FROM active_rss_feed';
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
				
				if ($row->pubdate) $pubdate=$row->pubdate;
				if ($row->expiredate) $expiredate=$row->expiredate;
				if ($row->feed_id) $feed_id=$row->feed_id;
			}		
			
			#Call rss_feed
			$sql = 'SELECT * FROM rss_feed WHERE feed_id='.$feed_id;
			$query = $this->db->query($sql);
			
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				// The XML structure
				/*
				$data = '<?xml version="1.0" encoding="UTF-8" ?>';
				$data .= '<rss version="2.0">';
				$data .= '<channel>';
				$data .= '<title>'.$companyname.'</title>';
				$data .= '<link>'.$website.'</link>';
				$data .= '<description>Healthy Living Portal</description>';
				
				#rss items
				$data .= '<item>';
				$data .= '<title>'.$row->title.'</title>';
				$data .= '<link>'.$row->shortlink.'</link>';
				$data .= '<description>'.$row->description.'</description>';
				$data .= '</item>';
		
				$data .= '</channel>';
				$data .= '</rss> ';
				*/
				
				$data = '<?xml version="1.0" encoding="UTF-8" ?>';
				$data .= '<rss version="2.0">';
				$data .= '<channel>';
				#$data .= '<title>'.$companyname.'</title>';
				#$data .= '<link>'.$website.'</link>';
				#$data .= '<description>Healthy Living Portal</description>';
				
				#rss items
				$data .= '<item>';
				$data .= '<title>'.$row->title.'</title>';
				#$data .= '<link>'.$row->shortlink.'</link>';
				$data .= '<description>HealthPlus presents Secret of Long Life and Healthy Living. Watch '.strtoupper($row->title).' at '.$row->shortlink.'</description>';
				$data .='<pubDate>'.date('D d M Y H:i:s O').'</pubDate>';
				$data .= '</item>';
				
				$data .='<image>';
				$data .='<url>https://s3-us-west-2.amazonaws.com/'.$thumb_bucket.'/'.$category.'/'.$row->thumbnail.'</url>';
				$data .='</image>';
#https://s3-us-west-2.amazonaws.com/healthytips-thumbs/Health/mov.jpg	
				$data .= '</channel>';
				$data .= '</rss> ';
				 
				#header('Content-Type: application/xml');
				
				#$b="Base Url=".base_url()."\nGet Env HTTP Host=".getenv('HTTP_HOST')."\nDIR=".__DIR__."\nGet CWP=".getcwd();
				#$file = fopen('aaa.txt',"w"); fwrite($file, $b);  fclose($file);
				#/home/healt521/public_html/admin
				
				$file = fopen(str_replace('admin','',getcwd()).'rss.xml',"w"); fwrite($file, $data);  fclose($file);
				
				$ret=true;
			}else
			{
				$ret=$this->CreateFeedSaveFeedSaveXML();
			}
		}else
		{
			$ret=$this->CheckForActiveFeed();
		}
		
		return $ret;
	}
	
	public function CheckIfActiveFeedExpired()#1 - Returns True/False
	{
		$dt=date('Y-m-d H:i:s'); $ret=false;
		
		$sql="SELECT expiredate FROM active_rss_feed";		
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->expiredate) $expdt=$row->expiredate;
			
			if ($dt > $expdt) $ret=true; else $ret=false;
		}else
		{
			$r=$this->CreateFeedSaveFeedSaveXML();
			
			if ($r==true) $ret=false; else $ret=true;
		}
		
		return $ret;
	}
	
	public function CheckForActiveFeed()#2 - Returns TRUE/FALSE.    If it is available
	{
		$ret=false;
		
		#Get current active feed
		$sql="SELECT * FROM active_rss_feed";
		
		$query = $this->db->query($sql);
					
		if ( $query->num_rows()> 0 )#Exists - Check for Expiry Date
		{
			$rt=$this->CheckIfActiveFeedExpired();#True - Expired, False - Not Expired
			
			if ($rt==true)
			{
				$ret=$this->CreateFeedSaveFeedSaveXML();
			}
		}else#Create
		{#Do - 3
			$ret=$this->CreateFeedSaveFeedSaveXML();
		}
		
		return $ret;
	}
	
	function GetNextSchedule($CurrentID,$MaxID,$MinID)
	{
		if (!$CurrentID) return 1;
		if (!$MaxID || !$MinID) return null;
		
		$new_id=0;
		
		if (intval($CurrentID)==intval($MaxID))
		{
			$new_id=intval($MinID);
		}else
		{
			$new_id=intval($CurrentID)+1;
			
			$found=false;
			
			while ($found==false)
			{
				$sql="SELECT schedule_id FROM videos WHERE (schedule_id=".$new_id.") AND (TRIM(video_status)='Encoded')";
				$query = $this->db->query($sql);
				
				if ( $query->num_rows() > 0 ) $found=true; else $new_id=intval($new_id)+1;
			}			
		}
		
		return $new_id;
	}
	
	public function CreateFeedSaveFeedSaveXML()#3 - Returns TRUE/FALSE
	{
		$displaydays=0; $googlekey=''; $website=''; $companyname=''; #$jw_player_id='';
		$MaxId=0; $MinId=0; $new_id=0; $ret=false; $OldFeed=0;
		
		#Get Settings
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();	
					
			if ($row->no_of_videos_per_day) $displaydays = $row->no_of_videos_per_day;
			if ($row->google_shortener_api) $googlekey = $row->google_shortener_api;
			if ($row->website) $website = $row->website;
			if ($row->companyname) $companyname = $row->companyname;
			#if ($row->jw_player_id) $jw_player_id = $row->jw_player_id;
		}
		
		#Get Maximum Schedule ID
		$sql="SELECT MAX(schedule_id) AS MaxID FROM videos WHERE TRIM(video_status)='encoded'";
			
		$query = $this->db->query($sql);
			
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->MaxID) $MaxId = $row->MaxID;
		}
		
		#Get Minimum Schedule ID
		$sql="SELECT MIN(schedule_id) AS MinID FROM videos WHERE TRIM(video_status)='encoded'";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->MinID) $MinId = $row->MinID;
		}else
		{
			$MinId=1;
		}
		
		$OldScheduleID='';
		
		#Get current active feed
		$sql="SELECT schedule_id,feed_id FROM active_rss_feed";
		$query = $this->db->query($sql);


		
		if ( $query->num_rows()> 0 )#Get new_id
		{
			$row = $query->row();
			
			if ($row->feed_id) $OldFeed=$row->feed_id;
			if ($row->schedule_id) $OldScheduleID=$row->schedule_id;
			
			if ($row->schedule_id)
			{
				$new_id=$this->GetNextSchedule($OldScheduleID,$MaxId,$MinId);
				
				/*if (intval($OldScheduleID)==intval($MaxId))
				{
					$new_id=intval($MinId); #Go Minimum Id
				}else
				{
					$new_id=intval($OldScheduleID)+1;
				}*/
			}else
			{
				$new_id=1;
			}
		}else#New
		{
			$new_id=$MinId;
		}
		
#$file = fopen('aaa.txt',"w"); fwrite($file, $sql."\nOld Fees ID=".$OldFeed."\nOld Schedule ID=".$OldScheduleID."\nNew ID=".$new_id); fclose($file);		
		
		$feed_id=0; $longlink=''; $shortlink=''; $video_description=''; $video_title=''; $filename='';
		$dt=date('Y-m-d H:i:s'); $category=''; $schedule_id=''; $video_code=''; $thumbnail='';
		
		#Get Video details
		$sql = "SELECT * FROM videos WHERE (schedule_id=".$this->db->escape_str($new_id).")";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->filename) $filename=$row->filename;
			if ($row->video_title) $video_title=$row->video_title;
			if ($row->description) $video_description=$row->description;
			if ($row->video_status) $video_status=$row->video_status;
			if ($row->category) $category=$row->category;
			if ($row->schedule_id) $schedule_id=$row->schedule_id;
			if ($row->video_code) $video_code=$row->video_code;
			if ($row->thumbnail) $thumbnail=$row->thumbnail;
			
			#Create New Feed and Store active_rss_feed and rss_feed
			if (trim(strtolower($video_status)) == 'encoded')
			{
				$feed_id=intval($this->GetNextID('rss_feed','feed_id'));
				
				$longlink=base_url().'index.php/Transactions/v/'.$video_code; #Generate Link
				$shortlink=$this->GetShortenUrl($longlink);
				
				$this->db->trans_start();
				
				$dat=array(
					'title' => $this->db->escape_str($video_title),
					'longlink' => $this->db->escape_str($longlink),
					'shortlink' => $this->db->escape_str($shortlink),
					'description' => $this->db->escape_str($video_description),
					'status' => 'Not Running',
					'category' => $this->db->escape_str($category),
					'feed_id' => $this->db->escape_str($feed_id),
					'filename' => $this->db->escape_str($filename),
					'video_code' => $this->db->escape_str($video_code),
					'thumbnail' => $this->db->escape_str($thumbnail),
					'insert_date' => $dt
				);
				
				$this->db->insert('rss_feed', $dat);					
				$this->db->trans_complete();
				
				#Replace Old Active Feed
				#$dd=strtotime("+".$displaydays." days", strtotime($dt));
				#$expdt= date("Y-m-d H:i:s", $dd);
				
				$qry = "SELECT * FROM active_rss_feed";
				$myquery = $this->db->query($qry);
				
				$pdt=date('Y-m-d H:i:s', strtotime('today midnight'));
				$expdt= date('Y-m-d H:i:s', strtotime('+'.$displaydays.'days',strtotime($pdt)));
				
				$dat=array(
					'feed_id' => $this->db->escape_str($feed_id),
					'pubdate' => $this->db->escape_str($pdt),
					'expiredate' => $this->db->escape_str($expdt),
					'schedule_id' => $this->db->escape_str($new_id),						
					'insert_date' => $dt
				);	
						
				$this->db->trans_start();
				
				if ($myquery->num_rows() > 0 )#Update
				{
					$row = $myquery->row();		
								
					$this->db->update('active_rss_feed', $dat);	
				}else#Insert
				{
					$this->db->insert('active_rss_feed', $dat);	
				}
				
				$this->db->trans_complete();
				
				if ($this->db->trans_status() === TRUE)
				{
					#Update rss_feed status
					$dat=array('status' => 'Running');
					$this->db->trans_start();
					$this->db->where('feed_id', $feed_id);
					$this->db->update('rss_feed', $dat);						
					$this->db->trans_complete();
					####END Of rss_feed UPDATE
					
					#Update Old Video Status
					if ($OldFeed>0)
					{
						$dat=array('status' => 'Not Running');
						$this->db->trans_start();
						$this->db->where('feed_id', $OldFeed);
						$this->db->update('rss_feed', $dat);						
						$this->db->trans_complete();
					}
					
					#Generate current rss						
					$ret=$this->GenerateRSS($new_id);
					
					if ($ret==true)
					{
						#Send Message
						$this->NotifyScheduleByEmail();
						
						$Msg="System Generated A New Video Feed With ID '".$new_id."' And Also Create Active Feed For Export Successfully.";
					}else
					{
						$Msg="Feed Generation Was Not Successfully.";
					}
				}else
				{
					$ret=false;
					$Msg="Feed Generation Was Not Successfully.";
				}
								
				$remote_ip=$_SERVER['REMOTE_ADDR'];
				$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
				
				$this->LogDetails('System',$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'GENERATE NEW VIDEO FEED','System');		
			}else
			{
				$ret=false;
				$Msg="Feed Generation Was Not Successfully. Video Is Not Ready For Streaming";
			}
		}else
		{
			$ret=false;
			$Msg="Feed Generation Was Not Successfully. No Video With The Schedule ID ".$new_id;
		}
		
		return $ret;
	}
	
	function NotifyScheduleByEmail()
	{
		#Get Current File Name And Send To Numbers
		$sql="SELECT * FROM active_rss_feed";#feed_id
		
		$query = $this->db->query($sql);
				
		if ( $query->num_rows() > 0 )
		{
			$pubdate=''; $expdate='';
			
			#Get feed_id from active_rss_feed
			$row = $query->row();
						
			if ($row->feed_id) $feed_id = $row->feed_id;
			if ($row->pubdate) $pubdate = date('d M Y @ H:i:s',strtotime($row->pubdate));
			if ($row->expiredate) $expdate = date('d M Y @ H:i:s',strtotime($row->expiredate));		

			#Get Feed Record
			$sql="SELECT * FROM rss_feed WHERE feed_id=".$feed_id;
			
			$query = $this->db->query($sql);
			
			if ( $query->num_rows() > 0 )
			{
				$title=''; $shortlink=''; $description=''; $category='';
				
				$row = $query->row();
						
				if ($row->title) $title = utf8_encode($row->title);
				if ($row->shortlink) $url = $row->shortlink;
				if ($row->description) $description = utf8_encode($row->description);
				if ($row->category) $category = $row->category;
				
				$from='support@laffhub.com';
				$to='o.dania@efluxz.com,davidumoh@icloud.com,ade@efluxz.com,david@laffhub.com';
				$subject='Laffhub Video Schedule';
				$Cc='cronjobs@laffhub.com,idongesit_a@yahoo.com,nsikakj@gmail.com';
				
				#$to='idongesit_a@yahoo.com';
							
				$message='
					<img src="images/emaillogo.png" width="100" alt="Healthy Living" title="Healthy Living" />
					<br><br>
					Hello,<br><br>
					
					A new <b><i>Healthy Living Video</i></b> has been scheduled. Details of the video are below:<br><br>
					<p style="text-indent:50px;"><b>Title:</b> '.stripslashes(stripcslashes($title)).'</p>
					<p style="text-indent:50px;"><b>Category:</b> '.stripslashes(stripcslashes($category)).'</p>
					<p style="text-indent:50px;"><b>Url:</b> '.$url.'</p>
					<p style="text-indent:50px;"><b>Schedule Date:</b> '.$pubdate.'</p>
					<p style="text-indent:50px;"><b>Schedule Expiry Date:</b> '.$expdate.'</p>
					<p style="text-indent:50px;"><b>Description:</b></p>
					<p style="margin-left:75px;" align="justify"><i>'.stripslashes(stripcslashes(str_replace('\\n','<br><br>',$description))).'</i></p>
					<br>
									
					<b><font color="#6DC742">HealthyLiving.ng</font></b>';
					
					$altMessage='
					Hello, 
					
					A new Healthy Living video has been scheduled. Details of the video are below: 
					
					Title: '.stripslashes(stripcslashes($title)).';  
					Category: '.stripslashes(stripcslashes($category)).'; 
					Url: '.$url.';
					Schedule Date: '.$pubdate.';			 
					Schedule Expiry Date: '.$expdate.'; 
					Description: '.stripslashes(stripcslashes(str_replace('\\n',' ',$description))).'.
					
					HealthyLiving.ng';
					
					
				$v=$this->SendEmail($from,$to,$subject,$Cc,$message,$altMessage,'');
				
				#$file = fopen('aaa.txt',"w"); fwrite($file,$v); fclose($file);
			}
		}
	}
	
	public function GetActivePlayVideo()
	{
		$new_id=0; $MaxId=0; $MinId=0; $cur_id=0; $cur_expdt=''; $new_expdt=''; $dt=date('Y-m-d H:i:s');
		$filename=''; $video_title=''; $video_description=''; $video_status=''; $publish_date='';
		$schedule_id=''; $displaydays=0; $googlekey=''; $website=''; $companyname=''; $category='';
		$VideoCode=''; $thumbnail='';
		
		#Get Settings
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();
					
			if ($row->no_of_videos_per_day) $displaydays = $row->no_of_videos_per_day;
			if ($row->google_shortener_api) $googlekey = $row->google_shortener_api;
			if ($row->website) $website = $row->website;
			if ($row->companyname) $companyname = $row->companyname;
			#if ($row->jw_player_id) $jw_player_id = $row->jw_player_id;
		}
		
		#Get Maximum Schedule ID
		$sql="SELECT MAX(schedule_id) AS MaxID FROM videos WHERE TRIM(video_status)='Encoded'";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->MaxID) $MaxId = $row->MaxID;
		}
		
		#Get Minimum Schedule ID
		$sql="SELECT MIN(schedule_id) AS MinID FROM videos WHERE TRIM(video_status)='Encoded'";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();			
			if ($row->MinID) $MinId = $row->MinID;
		}
		
		#Get current active feed
		$sql="SELECT schedule_id,expiredate FROM active_rss_feed";
		$cnt=0;
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$cnt=$query->num_rows();
			$row = $query->row();
			
			if ($row->expiredate) $cur_expdt=$row->expiredate;
					
			if ($row->schedule_id)
			{
				$cur_id=$row->schedule_id;
				
				if (intval($cur_id)==intval($MaxId))
				{
					$new_id=intval($MinId); #Go Minimum Id
				}else
				{
					$new_id=intval($MinId)+1; #Go Minimum Id
				}
			}else
			{
				$new_id=1;
			}
		}else#New
		{
			$new_id=1;
		}
		
		#Check for expiration		
		if (($dt > $cur_expdt) || ($cnt==0)) #New feed
		{
			#Get Video details
			$sql = "SELECT * FROM videos WHERE (TRIM(schedule_id)='".$this->db->escape_str($new_id)."')";
			$query = $this->db->query($sql);
						
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->filename) $filename=$row->filename;
				if ($row->video_title) $video_title=$row->video_title;
				if ($row->description) $video_description=$row->description;
				if ($row->video_status) $video_status=$row->video_status;
				if ($row->category) $category=$row->category;
				if ($row->schedule_id) $schedule_id=$row->schedule_id;
				if ($row->video_code) $VideoCode=$row->video_code;
				if ($row->thumbnail) $thumbnail=$row->thumbnail;
				
				#Create New Feed and Store active_rss_feed and rss_feed
				if (trim(strtolower($video_status)) == 'encoded')
				{
					$feed_id=intval($this->GetNextID('rss_feed','feed_id'));
					
					#$longlink=base_url().'Transactions'; #Generate Link
					$longlink=base_url().'index.php/Transactions/v/'.$VideoCode; #Generate Link
					$shortlink=$this->GetShortenUrl($longlink);
					
					$this->db->trans_start();
					
					$dat=array(
						'title' => $this->db->escape_str($video_title),
						'longlink' => $this->db->escape_str($longlink),
						'shortlink' => $this->db->escape_str($shortlink),
						'description' => $this->db->escape_str($video_description),
						'category' => $this->db->escape_str($category),
						'status' => 'Not Running',
						'feed_id' => $this->db->escape_str($feed_id),
						'filename' => $this->db->escape_str($filename),
						'video_code' => $this->db->escape_str($VideoCode),
						'thumbnail' => $this->db->escape_str($thumbnail),
						'insert_date' => $dt
					);
					
					$this->db->insert('rss_feed', $dat);					
					$this->db->trans_complete();
					
					#Replace Old Active Feed
					#$dd=strtotime("+".$displaydays." days", strtotime($dt));
					#$expdt= date("Y-m-d H:i:s", $dd);
					
					$qry = "SELECT * FROM active_rss_feed";
					$myquery = $this->db->query($qry);
					
					$pdt=date('Y-m-d H:i:s', strtotime('today midnight'));
					$expdt= date('Y-m-d H:i:s', strtotime('+'.$displaydays.'days',strtotime($pdt)));
				
					$dat=array(
						'feed_id' => $this->db->escape_str($feed_id),
						'pubdate' => $this->db->escape_str($pdt),
						'expiredate' => $this->db->escape_str($expdt),
						'schedule_id' => $this->db->escape_str($new_id),						
						'insert_date' => $dt
					);	
							
					$this->db->trans_start();
					
					if ($myquery->num_rows() > 0 )#Update
					{
						$this->db->update('active_rss_feed', $dat);	
					}else#Insert
					{
						$this->db->insert('active_rss_feed', $dat);	
					}
					
					$this->db->trans_complete();
					
					if ($this->db->trans_status() === TRUE)
					{
						#Update rss_feed status
						$dat=array('status' => 'Running');
						$this->db->trans_start();
						$this->db->where('feed_id', $feed_id);
						$this->db->update('rss_feed', $dat);						
						$this->db->trans_complete();
						####END Of rss_feed UPDATE
						
						#Generate current rss						
						$re=$this->GenerateRSS($new_id);
						
						if ($re) $this->NotifyScheduleByEmail();
					}
					
					$Msg="System Generated A New Video Feed With ID '".$new_id."' And Also Create Active Feed For Export Successfully.";
					
					$remote_ip=$_SERVER['REMOTE_ADDR'];
					$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
					
					$this->LogDetails('System',$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'GENERATE NEW VIDEO FEED','System');		
				}							
			}
		}		
	}
	
	public function GetVideosFromJWPlayer()
	{
		$jwSecret=''; $jwKey='';
		
		$sql="SELECT jw_api_key,jw_api_secret FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->jw_api_key) $jwKey = $row->jw_api_key;
			if ($row->jw_api_secret) $jwSecret = $row->jw_api_secret;
		}
		
		if ($jwKey && $jwSecret)
		{
			$botr_api = new BotrAPI($jwKey, $jwSecret);
			$response = $botr_api->call("/videos/list");
			
			return $response;	
		}else
		{
			return NULL;
		}		
	}
	
	public function GetShortenUrl($longUrl)
	{
		//Url to shorten		
		$apiKey='';
		
		$sql="SELECT google_shortener_api FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if ($row->google_shortener_api) $apiKey = $row->google_shortener_api;
		}
		
		if ($apiKey)
		{
			// *** No need to modify any of the code line below. *** 
			$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
			$jsonData = json_encode($postData);
			$curlObj = curl_init();
			curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
			curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curlObj, CURLOPT_HEADER, 0);
			curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
			curl_setopt($curlObj, CURLOPT_POST, 1);
			curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);
			$response = curl_exec($curlObj);
			$json = json_decode($response);
			 
			curl_close($curlObj);
			
			return $json->id;	
		}else
		{
			return $longUrl;
		}		
	}
	
	public function get_data()
    {
		$sql="SELECT DATE_FORMAT(trans_date,'%b') AS TransMonth,DATE_FORMAT(trans_date,'%Y') AS TransYear,network,COUNT(network) AS Cnt FROM transactions WHERE (YEAR(trans_date)='2016') GROUP BY TransMonth,network ORDER BY trans_date,network";#%M - January, %b - Jan
		#$sql="SELECT month,wordpress,codeigniter,highcharts FROM test_data";
		
		$query = $this->db->query($sql);
				
       	return $query->result();
    }
	
	function GSMPhoneNo($phone)
	{
		if ($phone)
		{
			$new='';
			
			$first=$phone[0];
			$code=trim(substr($phone,0,4));
			
			if (($first=='+') && ($code=='+234'))
			{
				$new=trim($phone);
			}elseif ($first=='0')
			{
				$new='+234'.substr($phone,1);
			}elseif (($first=='2') && (trim(substr($phone,0,3))=='234'))
			{
				$new='+'.$phone;
			}			
			return $new;
		}else
		{
			return '';
		}
	}
	
	public function CleanPhoneNo($phone)
	{
		if ($phone)
		{
			$new='';
			
			$first=$phone[0];
			$code=trim(substr($phone,0,4));
			
			if (($first=='+') && ($code=='+234'))
			{
				$new=str_replace('+','',$phone);
			}elseif ($first=='0')
			{
				$new='234'.substr($phone,1);
			}elseif (($first=='2') && (trim(substr($phone,0,3))=='234'))
			{
				$new=$phone;
			}
			
			//$ret=$first.' : '.$code.' => '.$new;
			
			return $new;
		}else
		{
			return '';
		}
	}
			
	public function BuildAddress($street,$city,$lga,$state,$country)
	{
		$ret='';
		
		if ($street) $ret=$street;
		
		if ($city)
		{
			if (trim($ret)=='') $ret=$city; else $ret .= ','.$city;
			
			if ($lga)
			{
				if (trim(strtolower($city)) != trim(strtolower($lga)))
				{
					if (trim($ret)=='') $ret=$lga; else $ret .= ','.$lga;
				}
			}
		}else
		{
			if ($lga)
			{
				if (trim($ret)=='') $ret=$lga; else $ret .= ','.$lga;
			}
		}
	
		if ($state)
		{
			if (trim($ret)=='') $ret=$state; else $ret .= ','.$state;
		}
		
		if ($country)
		{
			if (trim($ret)=='') $ret=$country; else $ret .= ','.$country;
		}
		
		return $ret;
	}
			
	public function GetTransactions($year,$month,$network,$phone)
	{
		$sql="SELECT @s:=@s+1 AS SN,DATE_FORMAT(trans_date,'%d %b %Y') AS 'Trans. Date',phone,(SELECT video_title FROM videos WHERE TRIM(videos.video_id)=TRIM(transactions.video_id) LIMIT 0,1) AS video_title,video_id,user_agent,network,(SELECT filename FROM videos WHERE TRIM(videos.video_id)=TRIM(transactions.video_id) LIMIT 0,1) AS filename FROM transactions,(SELECT @s:= 0) AS s";
		
		$crit='';
		
		if ($year) $crit=" (YEAR(trans_date)='".$year."')";	
		
		if ($month)
		{
			if (trim($crit)=='')
			{
				$crit=" (DATE_FORMAT(trans_date,'%M')='".$month."')";
			}else
			{
				$crit .= " AND (DATE_FORMAT(trans_date,'%M')='".$month."')";
			}
		}
		
		if ($network)
		{
			if (trim($crit)=='')
			{
				$crit=" (TRIM(network)='".trim($network)."')";
			}else
			{
				$crit .= " AND (TRIM(network)='".trim($network)."')";
			}
		}
		
		if ($phone)
		{
			if (trim($crit)=='')
			{
				$crit=" (TRIM(phone)='".trim($phone)."')";
			}else
			{
				$crit .= " AND (TRIM(phone)='".trim($phone)."')";
			}
		}
		
		if (trim($crit) != '') $sql .= ' WHERE'.$crit;
		
		$sql .= " ORDER BY trans_date,network,phone";
#$file = fopen('aaa.txt',"w"); fwrite($file, $sql); fclose($file);		
		$query = $this->db->query($sql);
		
		$results = $query->result_array();
		
		if ($results)
		{
			$data=array();

			if (is_array($results))
			{
				foreach($results as $row):
					$tp=array($row['SN'],$row['trans_date'],$row['phone'],$row['video_title'],$row['user_agent'],$row['network'],$row['video_id'],$row['filename']);
					$data[]=$tp;
				endforeach;
			}
			
			print_r(json_encode($data));
			//echo json_encode($data);
		}else
		{
			return NULL;
		}
	}
			
	public function GetGenericNumericNextID($table,$field,$startNumber)
	{
		if (!$table) return '';
		if (!$field) return '';
		
		$sql="SELECT MAX(`".$field."`) AS currentid FROM `".$table."`";
		
		$query = $this->db->query($sql);
					
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
		//$file = fopen('aaa.txt',"w"); fwrite($file, $row->currentid); fclose($file);	
			if (isset($row))
			{
				if ($row->currentid>0)
				{
					$i=intval($row->currentid) + 1;
					return $i;	
				}else
				{
					return $startNumber;
				}
			}else
			{
				return $startNumber;
			}
		}else
		{
			return $startNumber;
		}	
	}
		
	public function ResizeImage($img,$newWidth)#Width In Pixels
	{
		//Resize very large images to 400
		 $image_info = getimagesize($img);//index 0 is width, index 1 is heigth
						  
		 $width=$image_info[0];
		 $height=$image_info[1];
		#$file = fopen('a_idong.txt',"a"); fwrite($file,$matno." => ".$width."\n"); fclose($file);
		//Determine is image is Portrait or Landscape
		 if ($width > $newWidth)//Resize
		 { 
			$imgW = new SimpleImage();
			$imgW->load($img);	
					
			$imgW->resizeToWidth($newWidth);
			$imgW->save($img);
		}
	}
	
	public function GetNumericNextID($table,$field)
	{
		if (!$table) return '';
		if (!$field) return '';
		
		$sql="SELECT MAX(`".$field."`) AS currentid FROM `".$table."`";
		
		$query = $this->db->query($sql);
					
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
		//$file = fopen('aaa.txt',"w"); fwrite($file, $row->currentid); fclose($file);	
			if (isset($row))
			{
				if ($row->currentid>0)
				{
					$i=intval($row->currentid) + 1;
					
	#$file = fopen('aaa.txt',"w"); fwrite($file, "i=".$i."\ncurrentid="+$row->currentid."\nintval=".intval($row->currentid)."\nintval+1=".(intval($row->currentid) + 1)."\nUnique=".$this->GenerateCode(6)); fclose($file);	
	
					return $i;	
				}else
				{
					return 100001;
				}
			}else
			{//$file = fopen('aaa.txt',"w"); fwrite($file, 'B4=100001'); fclose($file);	
				return 100001;
			}
		}else
		{
			$ch=100001;
	//$file = fopen('aaa.txt',"w"); fwrite($file, 'ch='.$ch); fclose($file);		
			return $ch;
		}	
	}
	
	public function GenerateCode($length=6)
	{
		return strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, $length));
	}
	
	function crypto_rand_secure($min, $max,$Id) 
	{
		$range = $max - $min;
		if ($range < 0) return $min; // not so random...
		$log = log($range, 2);
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes))); #md5(uniqid($Id, true));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		
		return $min + $rnd;
	}
	
	public function GetTransactionId($length = 24,$Id)
	{
		$token = "";
		
		$codeAlphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghjkmnpqrstuvwxyz";
		$codeAlphabet.= "23456789";

		for($i=0;$i<$length;$i++){
			$token .= $codeAlphabet[$this->crypto_rand_secure(0,strlen($codeAlphabet),$Id)];
		}
		
		return $token;
	}
	
	public function GetParameters()
	{
		$sql = "SELECT * FROM settings";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			return $query->result();
		}else
		{
			return null;
		}
	}
	
	public function BulkSMSBalance()
	{
		#Get Settings
		$emails=''; $phones='';
		
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();	
					
			if ($row->emergency_no) $phones = $row->emergency_no;
			if ($row->emergency_emails) $emails = $row->emergency_emails;			
		}
		
		//Get Parameters
		$ret=$this->GetParameters();
		
		$url='http://cloud.nuobjects.com/api/credit'; $user=''; $pass='';
		
		if (count($ret)>0)
		{
			foreach($ret as $row):
				if($row->sms_username) $user=$row->sms_username;
				if($row->sms_password) $pass=$row->sms_password;
			endforeach;
				
			if ($user && $pass)
			{
				$curlPost = 'user='.$user.'&pass='.$pass;
		
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				// response of the POST request
				$response = curl_exec($ch);
				curl_close($ch);
				
				$ret=str_replace(',','',$response);
				
				if (floatval($ret)<100)#Send message
				{
					$r=intval($ret);
					$ret=number_format(floatval($ret),0);
					
					$t='<i><b>'.$ret.' ('.str_replace('naira','',strtolower(MoneyInWords($r))).')</b></i>';
					$t=str_replace('.','',$t);
					
					
					$ta=$ret.' ('.str_replace('naira','',strtolower(MoneyInWords($r))).')';
					$ta=str_replace('.','',$ta);
					
					$img=base_url()."images/emaillogo.png";
					
					$message = '
						<html>
						<head>
						<meta charset="utf-8">
						<title>LaffHub | Bulk SMS Warning</title>
						</head>
						<body>
								<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
								
								<p>Hello User,<br><br></p>
								
								<p>The portal\'s bulk sms total units is '.$t.'. You are advised to credit the account so that the portal\'s messaging module can function effectively.</p>
																																										
								<p>Best Regards</p>
								<p>
									LaffHub<br>
								</p>
						</body>
						</html>';
						
					$altmessage = '
						Hello User,
								
						The portal\'s bulk sms total units is '.$ta.'. You are advised to credit the account so that the portal\'s messaging module can function effectively.
																																					
						Best Regards
						
						LaffHub
						';	
						
					$this->SendEmail('support@laffhub.com',$emails,'Bulk SMS Units Warning','',$message,$altmessage,'Health Advisor');
					
					$m='Portal\'s bulk sms total units is '.$ret.'. You\'re advised to credit the account so that the portal\'s messaging module can function effectively. LaffHub.';
					
					#Send SMS
					$this->SendBulkSMS('LaffHub',$this->GSMPhoneNo($phones),$m);
				}
				
				return $ret;
			}else#No Username and Password
			{
				$img=base_url()."images/emaillogo.png";
					
				$message = '
					<html>
					<head>
					<meta charset="utf-8">
					<title>LaffHub | Bulk SMS Warning</title>
					</head>
					<body>
							<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
							
							<p>Dear User,<br><br></p>
							
							<p>The bulk sms account information (username and password) have not been set on the LaffHub portal. You are advised to ensure that the account information are set so that the portal\'s messaging module can function effectively.</p>
							
							<p>To set the account information, sign in to the LaffHub portal with a valid account details, Under <b><i>Settings/Users</i></b> menu (side menu), click on <b><i>Portal Settings</i></b> to open the settings screen, enter the correct buksms account information and click on <b><i>Update Settings</i></b> button. Please note that <i><b>YOU MUST HAVE THE REQUIRED PERMISSION TO BE ABLE TO DO THE ABOVE.</i></b></p>
																																									
							<p>Best Regards</p>
							<p>
								LaffHub<br>
							</p>
					</body>
					</html>';
					
				$altmessage = '
					Dear User,
							
					The bulk sms account information (username and password) have not been set on the LaffHub portal. You are advised to ensure that the account information are set so that the portal\'s messaging module can function effectively.
					
					To set the account information, sign in to the LaffHub portal with a valid account details, Under "Settings/Users" menu (side menu), click on "Portal Settings" to open the settings screen, enter the correct buksms account information and click on "Update Settings" button. Please note that "YOU MUST HAVE THE REQUIRED PERMISSION TO BE ABLE TO DO THE ABOVE".
																																				
					Best Regards
					
					LaffHub
					';	
					
				$this->SendEmail('support@laffhub.com',$emails,'Bulk SMS Account Warning','',$message,$altmessage,'Health Advisor');
				
				return '';
			}
		}else
		{
			$img=base_url()."images/emaillogo.png";
					
			$message = '
				<html>
				<head>
				<meta charset="utf-8">
				<title>LaffHub | Bulk SMS Warning</title>
				</head>
				<body>
						<p><img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
						
						<p>Dear User,<br><br></p>
						
						<p>The bulk sms account information (username and password) have not been set on the LaffHub portal. You are advised to ensure that the account information are set so that the portal\'s messaging module can function effectively.</p>
						
						<p>To set the account information, sign in to the LaffHub portal with a valid account details, Under <b><i>Settings/Users</i></b> menu (side menu), click on <b><i>Portal Settings</i></b> to open the settings screen, enter the correct buksms account information and click on <b><i>Update Settings</i></b> button. Please note that <i><b>YOU MUST HAVE THE REQUIRED PERMISSION TO BE ABLE TO DO THE ABOVE.</i></b></p>
																																								
						<p>Best Regards</p>
						<p>
							LaffHub<br>
						</p>
				</body>
				</html>';
				
			$altmessage = '
				Dear User,
						
				The bulk sms account information (username and password) have not been set on the LaffHub portal. You are advised to ensure that the account information are set so that the portal\'s messaging module can function effectively.
				
				To set the account information, sign in to the LaffHub portal with a valid account details, Under "Settings/Users" menu (side menu), click on "Portal Settings" to open the settings screen, enter the correct buksms account information and click on "Update Settings" button. Please note that "YOU MUST HAVE THE REQUIRED PERMISSION TO BE ABLE TO DO THE ABOVE".
																																			
				Best Regards
				
				LaffHub
				';	
				
			$this->SendEmail('support@laffhub.com',$emails,'Bulk SMS Account Warning','',$message,$altmessage,'Health Advisor');
			
			return '';
		}
	}
		
	public function SendBulkSMS($from,$to,$msg)
	{
		#$this->BulkSMSBalance();
		
		if ($from && $to && $msg)
		{
			//Get Parameters
			$ret=$this->GetParameters();
			
			$url=''; $user=''; $pass='';
						
			if (count($ret)>0)
			{
				foreach($ret as $row):
					if($row->sms_url) $url=$row->sms_url;
					if($row->sms_username) $user=$row->sms_username;
					if($row->sms_password) $pass=$row->sms_password;
				endforeach;
				
//$file = fopen('aaa.txt',"w"); fwrite($file, count($ret)."\n".$from."\n".$to."\n".$msg); fclose($file);				
				if ($url && $user && $pass)
				{
					#Process Recipient Phone Numbers - GSMPhoneNo($phone)
					$arrPh=explode(',',$to); $p='';
					
					if (count($arrPh)>1)
					{
						foreach($arrPh as $v)
						{
							if ($v)
							{
								$pp=$this->GSMPhoneNo($v);
								
								if ($pp)
								{
									if ($p=='') $p=$pp; else $p .= ','.$pp;
								}
							}
						}
						
						$to=$pp;
					}
					
					$curlPost = 'user='.$user.'&pass='.$pass.'&to='.$to.'&from='.$from.'&msg='.$msg;
			
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					
					// response of the POST request
					$response = curl_exec($ch);
					curl_close($ch);
					
					if (stristr($response,$to) !== FALSE) return 'OK'; else return 'FAIL';
#http://cloud.nuobjects.com/api/credit/?user=demo&pass=demopass 

#msg -> must <= 905chars;
#to-> must be in international format and no prefix e.g. 234819...) Separate with comma when sending to multiple recipients e.g. 234805... , 234803...;
#from -> must <= 11chars for Alphanumerical or <= 16chars for Numerical) 
#type Message Format) -> 0 = Normal SMS, 1 = Flash SMS, 2 = Unicode SMS (Arabic, Chinese etc) 
				}else
				{
					return 'FAIL';
				}
			}else
			{
				return 'FAIL';
			}
		}else
		{
			return 'FAIL';
		}
	}
	
	public function SendRegistrationEmail($from,$to,$subject,$Cc,$name,$activationurl)
	{
		#$file = fopen('aaa.txt',"a"); fwrite($file,"\n\nFROM: ".$from."\nTO: " . $to); fclose($file);
		
		$img=base_url()."images/emaillogo.png";
		
		$message = '
			<html>
			<head>
			<meta charset="utf-8">
			<title>LaffHub | Registration</title>
			</head>
			<body>
					<img src="'.$img.'" width="100" alt="LaffHub" title="LaffHub" /></p>
					
					<br><br>Hello,<br><br>
					
					<p>Thank you for signing up at LaffHub.</p>
					
					<p>Your account access username is your email address: <a href="mailto:'.$to.'">'.$to.'</a></p>
					
					<p>For full access to your account on LaffHub portal, you will need to activate your account. To do so, click on the link below or copy and paste it in your browser (for security purpose, this is a one time action):
</p>
										
					<br><p><a href="'.$activationurl.'">'.$activationurl.'<a/></p>
																										
					<br><p>Best Regards</p>
					<br><p>
						LaffHub Team<br>
						<a href="mailto:support@laffhub.com">support@laffhub.com</a>
					</p>
			</body>
			</html>';
			
			$altmessage = '
			Hello,
					
			Thank you for signing up at support@laffhub.com.
					
			Your account access username is your email address: '.$to.'
					
			For full access to your account on the support@laffhub.com portal, you will need to activate your account. To do so, click on the link below or copy and paste it in your browser (for security purpose, this is a one time action):

										
			'.$activationurl.'
																										
			Best Regards
			
			LaffHub Team
			
			support@laffhub.com';
			
		
#$file = fopen('aaa.txt',"a"); fwrite($file, $img); fclose($file);

		//Create a new PHPMailer instance
		$mail = new PHPMailer;		
		$mail->isSMTP();//Tell PHPMailer to use SMTP
		$mail->CharSet = "UTF-8";
		//Enable SMTP debugging
		// 0 = off (for production use),  1 = client messages, 2 = client and server messages
		$mail->SMTPDebug = 0;		
		$mail->Debugoutput = 'html';//Ask for HTML-friendly debug output		
		$mail->Host = 'smtp.postmarkapp.com';//Set the hostname of the mail server	- smtp.postmarkapp.com
		$mail->Port = 2525;//Set the SMTP port number - likely to be 25, 465 or 587	- 25, 2525, or 587
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;//Whether to use SMTP authentication		
		$mail->Username = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";//Username to use for SMTP authentication		
		$mail->Password = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";	#- b0d395e5-1f76-4124-b6dc-cc2b1f9d6516	
		$mail->setFrom($from, 'LaffHub');//Set who the message is to be sent from		
		$mail->addReplyTo($from, 'LaffHub');//Set an alternative reply-to address		
		#$mail->addAddress($to, $name);//Set who the message is to be sent to
		
		$em=explode(',',$to);
		$em1=explode(';',$to);
		
		$tem='';
		
		if (count($em)>1)
		{
			foreach($em as $v)
			{
				$mail->addAddress($v,'');//Set who the message is to be sent to		
			}
		}elseif (count($em1)>1)
		{
			foreach($em1 as $v)
			{
				$mail->addAddress($v,'');//Set who the message is to be sent to		
			}
		}else
		{
			$mail->addAddress($to,$name);//Set who the message is to be sent to	
		}
			
		$mail->Subject = $subject;//Set the subject line
		$mail->isHTML(true);/*Set email format to HTML (default = true)*/
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		#$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		//Replace the plain text body with one created manually
		#$mail->AltBody = 'This is a plain-text message body';
		//Attach an image file
		#$mail->addAttachment('images/phpmailer_mini.png');
		$mail->AddEmbeddedImage($img, "ms-attach", $img);
		
		$mail->AltBody = $altmessage;
		
		#if ($Cc) $mail->addBCC($Cc);
		
		if ($Cc)
		{
			#$mail->addBCC($Cc);
			
			$em=explode(',',$Cc);
			$em1=explode(';',$Cc);
			
			$tem='';
			
			if (count($em)>1)
			{
				foreach($em as $v)
				{
					$mail->addBCC($v);//Set who the message is to be sent to		
				}
			}elseif (count($em1)>1)
			{

				foreach($em1 as $v)
				{
					$mail->addBCC($v);//Set who the message is to be sent to		
				}
			}else
			{
				$mail->addBCC($Cc);//Set who the message is to be sent to	
			}
		}
		
		$mail->Body  = $message;
		$mail->msgHTML($message);
		
		//send the message, check for errors
		if (!$mail->send()) {
			$file = fopen('emailerror.txt',"a"); fwrite($file,date('d M Y H:i')."\tMailer Error: " . $mail->ErrorInfo); fclose($file);
			
			return "MAILER ERROR: ". $mail->ErrorInfo;
		} else {
			return 'OK';
		}
	}
	
	public function SendEmail($from,$to,$subject,$Cc,$message,$altMessage,$name)
	{	
		$img=base_url()."images/emaillogo.png";

		$mail = new PHPMailer();#Create a new PHPMailer instance
		$mail->CharSet = "UTF-8";
		$mail->isSMTP();#/Tell PHPMailer to use SMTP
		$mail->SMTPDebug = 0;		
		$mail->Debugoutput = 'html';//Ask for HTML-friendly debug output		
		$mail->Host = 'smtp.postmarkapp.com';//Set the hostname of the mail server	- smtp.postmarkapp.com
		$mail->Port = 2525;//Set the SMTP port number - likely to be 25, 465 or 587	- 25, 2525, or 587
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;//Whether to use SMTP authentication		
		$mail->Username = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";//Username to use for SMTP authentication		
		$mail->Password = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";	#- efec7a8f-a894-4c2a-982f-b3e6deab999c
		$mail->setFrom($from, 'LaffHub');//Set who the message is to be sent from		
		$mail->addReplyTo($from, 'LaffHub');//Set an alternative reply-to address		
		
		$em=explode(',',$to);
		$em1=explode(';',$to);
		
		$tem='';
		
		if (count($em)>1)
		{
			foreach($em as $v)
			{
				$mail->addAddress($v,'');//Set who the message is to be sent to		
			}
		}elseif (count($em1)>1)
		{
			foreach($em1 as $v)
			{
				$mail->addAddress($v,'');//Set who the message is to be sent to		
			}
		}else
		{
			$mail->addAddress($to,$name);//Set who the message is to be sent to	
		}
		
		$mail->Subject = $subject;//Set the subject line
		$mail->isHTML(true);/*Set email format to HTML (default = true)*/

		if ($Cc)
		{
			#$mail->addBCC($Cc);
			
			$em=explode(',',$Cc);
			$em1=explode(';',$Cc);
			
			$tem='';
			
			if (count($em)>1)
			{
				foreach($em as $v)
				{
					$mail->addBCC($v);
				}
			}elseif (count($em1)>1)
			{
				foreach($em1 as $v)
				{
					$mail->addBCC($v);
				}
			}else
			{
				$mail->addBCC($Cc);
			}
		}
					
		//Attach an image file
		#$mail->AddEmbeddedImage("emaillogo.png", "ms-attach", "emaillogo.png");
		$mail->AddEmbeddedImage($img, "ms-attach", $img);
		$mail->Body  = $message;
		$mail->AltBody = $altMessage;
		$mail->msgHTML($message);
		
		
		//send the message, check for errors
		if (!$mail->send()) {
			$file = fopen('emailerror.txt',"a"); fwrite($file,date('d M Y H:i')."\tMailer Error: " . $mail->ErrorInfo); fclose($file);
			
			return "MAILER ERROR: ". $mail->ErrorInfo;
			
		} else {
			return 'OK';
		}
	}
	
	public function GetNextID($table,$field)
	{
		if (!$table) return '';
		if (!$field) return '';
		
		$sql="SELECT MAX(`".$field."`) AS currentid FROM `".$table."`";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )  //Build Array of results
		{
			$row = $query->row();
			
			if (isset($row))
			{
				$i=$row->currentid + 1;
								
				return str_pad($i, 10, "0", STR_PAD_LEFT);
			}else
			{
				return str_pad(1, 10, "0", STR_PAD_LEFT);
			}
		}else
		{
			return str_pad(1, 10, "0", STR_PAD_LEFT);
		}	
	}
	
	public function GetCountries()
	{
		$query = $this->db->query("SELECT country FROM countries ORDER BY country");
		
		return $query->result();
	}	
				
	public function GetUserData($username)
	{
		if ($username)
		{	
			$sql="SELECT * FROM userinfo WHERE (TRIM(username)='".$this->db->escape_str($username)."');";
				
			#Get User Details
			$query = $this->db->query($sql);
	
			$results = $query->result();
		
			if ($results)
			{
				return $results;
			}else
			{
				return NULL;
			}			
		}else
		{
			return NULL;
		}
	}
	
	public function DataCleaner($data) 
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		
		return $data;
	}
	
	public function KillSleepingConnections()
	{		
		$iMinSecondsToExpire = 10;
		
        $strSQL= "SHOW PROCESSLIST;";
				
		$query = $this->db->query("SHOW PROCESSLIST;");
		
		$arr=array();
				
		foreach ($query->result() as $row)
		{
			$iTime=''; $strState=''; $iPID='';
			#echo $row->title;
			if ($row->Id) $iPID=$row->Id;
			if ($row->Command) $strState=$row->Command;
			if ($row->Time) $iTime=$row->Time;
			
			if ((strtolower(trim($strState)) == "sleep") && ($iTime >= $iMinSecondsToExpire) && ($iPID > 0))
			{
			   $arr[]=$iPID;#This connection is sitting around doing nothing. Kill it.
			}
		}
		
		if (count($arr)>0)
		{
			foreach($arr as $p):
				if ($p)
				{
			 		$strSQL = "KILL ".$p.";";
				
					$query = $this->db->query($strSQL);	
				}
			endforeach;
		}
	}
	
	public function LogDetails($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID)
	{
		$this->db->trans_start();
				
		#LoginDate,`Name`,Activity,ActionDate,Username,LogOutDate,Operation,Company,LogID
		
		if (trim(strtoupper($Operation))=='LOGOUT') $logoutdate=date('Y-m-d H:i:s'); else $logoutdate='';
		
		if (trim(strtoupper($Operation))=='LOGOUT')
		{
			$logdate=date('Y-m-d H:i:s');
			
			$data = array(
				'Activity'      => $this->db->escape_str($Activity),
				'ActionDate'	=> $logdate,
				'LogOutDate' 	=> $logdate,
				'Operation' 	=> $Operation,
				'remote_ip'		=> $ip,
				'remote_host'	=> $host
			);

			$this->db->where('LogID', $LogID);
			$this->db->update('loginfo', $data); 
		}else
		{
			if (trim(strtoupper($Operation))=='LOGIN')
			{
				$this->db->insert('loginfo', array(
					'LoginDate' 	=> $logdate,
					'Name' 			=> $this->db->escape_str($Name),
					'Activity'      => $this->db->escape_str($Activity),
					'ActionDate'	=> $logdate,
					'Username' 		=> $this->db->escape_str($Username),
					'Operation' 	=> $Operation,
					'LogID'			=> $LogID,
					'remote_ip'		=> $ip,
					'remote_host'	=> $host
				));
			}else
			{
				$logdate=date('Y-m-d H:i:s');
				
				$this->db->insert('loginfo', array(
					'LoginDate' 	=> $logdate,
					'Name' 			=> $this->db->escape_str($Name),
					'Activity'      => $this->db->escape_str($Activity),
					'ActionDate'	=> $logdate,
					'Username' 		=> $this->db->escape_str($Username),
					'Operation' 	=> $Operation,
					'LogID'			=> $LogID,
					'remote_ip'		=> $ip,
					'remote_host'	=> $host
				));	
			}
				
		}
		
		
		$this->db->trans_complete();
	}
	
	public function PublisherLogDetails($Name,$Activity,$Email,$logdate,$ip,$host,$Operation,$LogID)
	{
		$this->db->trans_start();
				
		#LoginDate,`Name`,Activity,ActionDate,Email,LogOutDate,Operation,Company,LogID
		
		if (trim(strtoupper($Operation))=='LOGOUT') $logoutdate=date('Y-m-d H:i:s'); else $logoutdate='';
		
		if (trim(strtoupper($Operation))=='LOGOUT')
		{
			$logdate=date('Y-m-d H:i:s');
			
			$data = array(
				'Activity'      => $this->db->escape_str($Activity),
				'ActionDate'	=> $logdate,
				'LogOutDate' 	=> $logdate,
				'Operation' 	=> $Operation,
				'remote_ip'		=> $ip,
				'remote_host'	=> $host
			);

			$this->db->where('LogID', $LogID);
			$this->db->update('publisher_loginfo', $data); 
		}else
		{
			if (trim(strtoupper($Operation))=='LOGIN')
			{
				$this->db->insert('publisher_loginfo', array(
					'LoginDate' 	=> $logdate,
					'Name' 			=> $this->db->escape_str($Name),
					'Activity'      => $this->db->escape_str($Activity),
					'ActionDate'	=> $logdate,
					'Email' 		=> $this->db->escape_str($Email),
					'Operation' 	=> $Operation,
					'LogID'			=> $LogID,
					'remote_ip'		=> $ip,
					'remote_host'	=> $host
				));
			}else
			{
				$logdate=date('Y-m-d H:i:s');
				
				$this->db->insert('publisher_loginfo', array(
					'LoginDate' 	=> $logdate,
					'Name' 			=> $this->db->escape_str($Name),
					'Activity'      => $this->db->escape_str($Activity),
					'ActionDate'	=> $logdate,
					'Email' 		=> $this->db->escape_str($Email),
					'Operation' 	=> $Operation,
					'LogID'			=> $LogID,
					'remote_ip'		=> $ip,
					'remote_host'	=> $host
				));	
			}
				
		}
		
		
		$this->db->trans_complete();
	}
}
?>