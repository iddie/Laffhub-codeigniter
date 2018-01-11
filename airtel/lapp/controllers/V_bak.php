<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

class V extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
		
	 }

	public function AddComment()
	{		#category, filename, videocode, name, comment
		$email=''; $category=''; $filename=''; $videocode=''; $name=''; $comment=''; $videotitle='';
		$phone='';
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('category')) $category = trim($this->input->post('category'));
		if ($this->input->post('filename')) $filename = trim($this->input->post('filename'));
		if ($this->input->post('videotitle')) $videotitle = trim($this->input->post('videotitle'));
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('name')) $name = $this->input->post('name');
		if ($this->input->post('comment')) $comment = $this->input->post('comment');		
#$file = fopen('aaa.txt',"w"); fwrite($file,$action); fclose($file);
				
		//Check if record exists
		$sql = "SELECT * FROM comments WHERE ((TRIM(phone)='".$this->db->escape_str($phone)."') OR (TRIM(email)='".$this->db->escape_str($email)."')) AND (TRIM(videocode)='".$this->db->escape_str($videocode)."') AND (TRIM(comment)='".$this->db->escape_str($comment)."')";
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret = 'Comment <b>'.strtoupper($comment).'</b> for the video <b>'.strtoupper($videotitle).'</b> has already been added.';
		}else
		{
			$dt=date('Y-m-d H:i:s');
			
			$this->db->trans_start();
									
			$dat=array(
				'email' => $this->db->escape_str($email),
				'phone' => $this->db->escape_str($phone),
				'category' => $this->db->escape_str($category),
				'filename' => $this->db->escape_str($filename),
				'videotitle' => $this->db->escape_str($videotitle),
				'videocode' => $this->db->escape_str($videocode),
				'name' => $this->db->escape_str($name),
				'comment' => $this->db->escape_str($comment),				
				'commentstatus' => 1,
				'commentdate' => $dt
				);							
			
			$this->db->insert('comments', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				if ($phone)
				{
					$Msg="Subscriber ".strtoupper($name.'('.$phone.')')." attempted adding comment but failed.";
				}elseif ($email)
				{
					$Msg="Subscriber ".strtoupper($name.'('.$email.')')." attempted adding comment but failed.";
				}
								
				$ret = 'Comment Addition Was Not Successful.';
			}else
			{
				if ($phone)
				{
					$Msg="Comment By Subscriber ".strtoupper($name.'('.$phone.')')." Was Added Successfully.";
				}elseif ($email)
				{
					$Msg="Comment By Subscriber ".strtoupper($name.'('.$email.')')." Was Added Successfully.";
				}				
								
				$ret = 'OK'	;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($name,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ADDED COMMENT',$_SESSION['LogID']);
		}
		
		echo $ret;
	}
	
	public function PlayVideo()
	{
		$videocode=''; $subscriptionId=''; $videos_cnt_to_watch=''; $VideoTitle=''; $thumbnail='';
		$VideosWatched=0; $subscription_status='';
		
		if ($this->uri->segment(1)) $videocode=trim(str_replace('c-','',$this->uri->segment(1)));
				
		$tdt=date("Y-m-d H:i:s");
		
		$data['filename']=''; $data['title']=''; $data['jwplayer_key']='';
		$data['description']=''; $data['category']=''; $data['domain_name']='';
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		$lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$phone=$this->getdata_model->GetMSISDN();
		$network=$this->getdata_model->GetNetwork();
		
		$this->getdata_model->LoadSubscriberSession($data['Phone']);
		
		#Get Player Key
		$sql="SELECT * FROM settings";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();	

			if ($row->jwplayer_key) $data['jwplayer_key'] = $row->jwplayer_key;
			if ($row->thumbs_bucket) $ThumbBucket = $row->thumbs_bucket;
		}
		
		#Get domain_name
		$sql="SELECT domain_name FROM streaming_domain";
			
		$query = $this->db->query($sql);
				
		if ( $query->num_rows()> 0 )
		{
			$row = $query->row();						
			if ($row->domain_name) $data['domain_name'] = $row->domain_name;
		}
		
		#Get Video record
		$sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(video_code)='".$this->db->escape_str($videocode)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->filename) $data['filename']=$row->filename;
			if ($row->video_title) $data['title']=$row->video_title;
			if ($row->description) $data['description']=$row->description;
			if ($row->category) $data['category']=$row->category;
			if ($row->thumbnail) $data['thumbnail']=$row->thumbnail;
						
			if ($row->duration)
			{
				$data['duration']=$row->duration;				
				$d=explode(':',$row->duration);
				
				$sec=0;
				
				if (count($d)==3)
				{
					$sec=(intval($d[0],10)*120)+(intval($d[1],10)*60)+intval($d[2],10);
				}elseif (count($d)==2)
				{
					$sec=(intval($d[0],10)*60)+intval($d[1],10);
				}
				
				$data['duration_secs']=$sec;
			}
			
			#Save Transaction
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
			
			$data['Phone']=$phone;
			$data['Network']=$network;
			$data['subscriber_email']=$_SESSION['subscriber_email'];	
			$data['subscriber_name'] = $_SESSION['subscriber_name'];
			$data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
	
			$result=$this->getdata_model->GetSubscriptionDate($data['subscriber_email'],$data['Phone']);
								
			if (is_array($result))
			{
				$td=date('Y-m-d H:i:s');
				
				foreach($result as $row)
				{
					if ($row->subscribe_date) $dt = date('F d, Y',strtotime($row->subscribe_date));
					
					$data['subscribe_date'] = $dt;
					
					if ($row->exp_date) $edt = date('F d, Y',strtotime($row->exp_date));
					$data['exp_date'] = $edt;
					
					if ($td > date('Y-m-d H:i:s',strtotime($row->exp_date)))
					{
						if ($row->subscriptionstatus==1)
						{
							#Update Subscription Date
							$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'0');
						}
					}else
					{
						if (!$row->subscriptionstatus)
						{
							$this->getdata_model->UpdateSubscriptionStatus($data['subscriber_email'],$data['Phone'],'1');
							$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
						}else
						{
							$data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
						}
					}

					break;
				}
			}
			
			#Get active subscription ID
			$sql = "SELECT subscriptionId,videos_cnt_to_watch,subscriptionstatus FROM subscriptions WHERE (TRIM(msisdn)='".$this->db->escape_str($phone)."') ";
			
			$query = $this->db->query($sql);
					
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
				
				if ($row->subscriptionId) $subscriptionId=trim($row->subscriptionId);
				if ($row->videos_cnt_to_watch) $videos_cnt_to_watch=$row->videos_cnt_to_watch;
				if ($row->subscriptionstatus) $subscription_status=$row->subscriptionstatus;
			}
			
			if (!$subscription_status) $subscription_status='0';
			
			#$tddate=date('Y-m-d H:i',strtotime($tdt));
			$tddate=date('Y-m-d',strtotime($tdt));
			
			#Check if it is first time it registering
			$sql = "SELECT trans_date FROM transactions WHERE (DATE_FORMAT(trans_date,'%Y-%m-%d')='".$tddate."') AND (TRIM(filename)='".$row->filename."') AND ((TRIM(remote_address)='".$_SERVER['REMOTE_ADDR']."') OR (TRIM(phone)='".$phone."'))";
		
			$qry = $this->db->query($sql);
						
			$dat=array(
				'email' => $this->db->escape_str($data['subscriber_email']),
				'phone' => $this->db->escape_str($phone),
				'trans_date' => $tdt,
				'filename' => $this->db->escape_str($row->filename),
				'video_code' => $this->db->escape_str($videocode),
				'user_agent' => $this->db->escape_str($useragent),
				'video_category' => $this->db->escape_str($row->category),
				'remote_address' => $this->db->escape_str($remote_ip),	
				'remote_host' => $this->db->escape_str($remote_host),	
				'lang' => $this->db->escape_str($lang),
				'network' => $network
			);
			
			$this->db->insert('transactions', $dat);	
				
			$this->db->trans_complete();
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Transaction From User Agent '".strtoupper($useragent).", Remote Host '".strtoupper($remote_host)."' AND Remote IP'".strtoupper($remote_ip)."' Failed.";	
				
				$this->getdata_model->LogDetails('System',$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'NEW VIDEO REQUEST','System');
			}	
			
			$arr = explode('.', basename($row->filename));
			$ext=array_pop($arr);				
			$fn=str_replace('.'.$ext,'',basename($row->filename));
			
			$preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$data['category'].'/'.$data['thumbnail'];
			
			$data['videocode'] = $videocode;
			$data['VideosToWatch'] = $videos_cnt_to_watch;
			$data['subscriber_phone'] = $phone;
			$data['video_category'] = $row->category;
			$data['thumbs_bucket'] = $ThumbBucket;
			$data['subscriptionId'] = $subscriptionId;
			
			$data['subscriber_status'] = $this->getdata_model->GetSubscriptionStatus($data['subscriber_email'],$phone);
			
			$data['subscription_status']=$subscription_status;
			$data['preview_img']=$preview_img;
			$data['RelatedVideos']=$this->getdata_model->GetRelatedVideos($row->category,$videocode);
			$data['Comments']=$this->getdata_model->GetVideoComments($row->category,$videocode,$row->filename);
			$data['ViewPagePopularVideos']=$this->getdata_model->GetViewPagePopularVideos($videocode);
			
			if (count($data['Comments'])>1)
			{
				$data['CommentsCount']=count($data['Comments']).' Comments';
			}else
			{
				$data['CommentsCount']=count($data['Comments']).' Comment';
			}
			
			$data['Categories']=$this->getdata_model->GetCategories();
			
			#Check if subscriber has exceeded no of videos allowed or max watch count for a video (3)
			#1. Get video list from watchlists
			$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
		
			$query = $this->db->query($sql);
			
			$videolist='';
			
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->videolist) $videolist=trim($row->videolist);
			}
			
			#Check total number of videos watched - VideoCode|WatchCount^VideoCode|WatchCount
			if ($videolist <> '')
			{
				$arrWatched=array(); $tempTotal=0; $tempLimit=0; $codeexists=false;
				
				$arrTotalWatched=explode('^',$videolist);
				
				$VideosWatched=count($arrTotalWatched);
				
				$data['VideosWatched']=$VideosWatched;
				
				if (count($arrTotalWatched)>0)
				{
					$tempTotal=count($arrTotalWatched);
					
					foreach($arrTotalWatched as $itm):
						if ($itm)
						{
							$ex=explode('|',$itm);
							
							if (count($ex)>0)
							{
								$arrWatched[$ex[0]]=$ex[1]; #array[videocode]=watchcount
							}
						}
					endforeach;
				}
				
				if (count($arrWatched)>0)
				{					
					foreach($arrWatched as $code => $cnt): #foreach (array_expression as $key => $value)
						if ($code)
						{
							if (trim($code) == trim($videocode))
							{
								$codeexists=true;
								$tempLimit=intval($cnt,10);
								break;
							}
						}
					endforeach;
					
					#Code exists, check watch count
					if ($codeexists==true)
					{
						if ($tempLimit < 3)#OK
						{
							#Update WatchCount
							$data['ExceededTotal']='0';
							$data['ExceededVideoLimited']='0';
							$data['ExceededTotalVideos']='0';
							
							$this->load->view('v_view',$data);
						}else#Exceed video limit
						{
							$data['ExceededVideoLimited']='3';
							
							if (strtolower(trim($videos_cnt_to_watch)) <> 'unlimited')
							{
								if (intval($tempTotal,10) < intval($videos_cnt_to_watch,10))#OK
								{
									$data['ExceededTotal']='0';
									$data['ExceededTotalVideos']=intval($videos_cnt_to_watch,10)-intval($tempTotal,10);
								}else
								{
									$data['ExceededTotal']='1';
									$data['ExceededTotalVideos']=intval($videos_cnt_to_watch,10);
								}	
							}else
							{
								$data['ExceededTotal']='0';
								$data['ExceededTotalVideos']='Unlimited';
							}							
							
							$this->load->view('v_view',$data);
						}
					}else #Code not exist, check total videos
					{
						if (strtolower(trim($videos_cnt_to_watch)) <> 'unlimited')
						{
							if (intval($tempTotal,10) < intval($videos_cnt_to_watch,10))
							{
								$data['ExceededTotal']='0';
								$data['ExceededTotalVideos']=intval($videos_cnt_to_watch,10)-intval($tempTotal,10);
							}else
							{
								$data['ExceededTotal']='1';
								$data['ExceededTotalVideos']=intval($videos_cnt_to_watch,10);
							}
							
							$data['ExceededVideoLimited']='0';	
						}else
						{
							$data['ExceededTotal']='0';
							$data['ExceededTotalVideos']='Unlimited';
						}						
						
						$this->load->view('v_view',$data);
					}
				}else
				{
					#Update WatchCount
					$data['ExceededTotal']='0';
					$data['ExceededVideoLimited']='0';
					$data['ExceededTotalVideos']='0';
					
					$this->load->view('v_view',$data);	
				}
			}else
			{
				#Update WatchCount
				$data['ExceededTotal']='0';
				$data['ExceededVideoLimited']='0';
				$data['ExceededTotalVideos']='0';
				
				$this->load->view('v_view',$data);
			}
		}else
		{
			$this->load->view('notfound',$data);
		}
	}
	
	public function CheckForWatchCount()
	{
		$phone=''; $email=''; $videocode=''; $subscriptionId=''; $videos_cnt_to_watch='';
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = $this->input->post('email');
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));
		
		#Get videos_cnt_to_watch
		$sql = "SELECT videos_cnt_to_watch FROM subscriptions WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
			
		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
			
			if ($row->videos_cnt_to_watch) $videos_cnt_to_watch=$row->videos_cnt_to_watch;
		}
		
		$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
		
			$query = $this->db->query($sql);
			
			$videolist='';
			
			if ($query->num_rows() > 0 )
			{
				$row = $query->row();
			
				if ($row->videolist) $videolist=trim($row->videolist);
			}
			
		
			#Check total number of videos watched - VideoCode|WatchCount^VideoCode|WatchCount
			if ($videolist <> '')
			{
				$arrWatched=array(); $tempTotal=0; $tempLimit=0; $codeexists=false;
				
				$arrTotalWatched=explode('^',$videolist);
				
				$VideosWatched=count($arrTotalWatched);
					
				if (count($arrTotalWatched)>0)
				{
					$tempTotal=count($arrTotalWatched);
					
					foreach($arrTotalWatched as $itm):
						if ($itm)
						{
							$ex=explode('|',$itm);
							
							if (count($ex)>0)
							{
								$arrWatched[$ex[0]]=$ex[1]; #array[videocode]=watchcount
							}
						}
					endforeach;
				}
				
				if (count($arrWatched)>0)
				{					
					foreach($arrWatched as $code => $cnt):
						if ($code)
						{
							if (trim($code) == trim($videocode))
							{
								$codeexists=true;
								$tempLimit=intval($cnt,10);

								break;
							}
						}
					endforeach;
					
					#Code exists, check watch count
					if ($codeexists==true)
					{
						if ($tempLimit < 3)#OK
						{
							$data='OK';
						}else#Exceed video limit
						{
							if (strtolower(trim($videos_cnt_to_watch)) <> 'unlimited')
							{
								if (intval($tempTotal,10) < intval($videos_cnt_to_watch,10))#OK
								{
									$diff=intval($videos_cnt_to_watch,10) - intval($tempTotal,10);
									
									if ($diff<2)
									{
										$data='You have watched this particular video 3 times. However, you still have the privilege of selecting <b>' . $diff . ' new video</b> for your current subscription plan.';
									}else
									{
										$data='You have watched this particular video 3 times. However, you still have the privilege of selecting <b>' . $diff . ' new videos</b> for your current subscription plan.';
									}
								}else
								{
									$data='You have exceeded the total number of videos allowed for your current subscription plan. Please renew your subscription to watch videos.';
								}		
							}else
							{
								$data='You have watched this particular video 3 times. You still have the privilege of selecting <b>UNLIMITED new videos</b> for your current subscription plan.';
							}
						}
					}else #Code not exist, check total videos
					{
						if (strtolower(trim($videos_cnt_to_watch)) <> 'unlimited')
						{
							if (intval($tempTotal,10) < intval($videos_cnt_to_watch,10))
							{
								$data='OK';
							}else
							{
								$data='You have exceeded the total number of videos allowed for your current subscription plan. Please renew your subscription to watch videos.';
							}	
						}else
						{
							$data='You have watched this particular video 3 times. You still have the privilege of selecting <b>UNLIMITED new videos</b> for your current subscription plan.';
						}						
					}
				}else
				{
					$data='OK';	
				}
			}else
			{
				$data='OK';
			}
		
		echo $data;
	}
	
	public function UpdateWatchCount()
	{
		$phone=''; $email=''; $videocode=''; $subscriptionId='';
		
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = $this->input->post('email');
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));
		
		$this->getdata_model->SetWatchCount($videocode,$phone,$email,$subscriptionId);	
		
		echo 'OK';				
	}
	
#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
#if ($domainname && $filename) $preview_url='https://'.$domainname.'/'.$filename;	
	public function index()
	{
				
	}
}
