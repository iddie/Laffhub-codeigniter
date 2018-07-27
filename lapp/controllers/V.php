<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Africa/Lagos');


class V extends CI_Controller {	
	
	function __construct() 
	{
		parent::__construct();
		$this->load->helper('url'); 
		$this->load->model('getdata_model');
		
	 }

	public function LikeComment()
	{
		$email=''; $phone=''; $comment_id=''; $comment=''; $author=''; $likes=0; $ret='';
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('comment_id')) $comment_id = trim($this->input->post('comment_id'));
		
		#Get comment details
		$sql = "SELECT likes,comment_text,author FROM comments WHERE (comment_id=".$this->db->escape_str($comment_id).")";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() > 0 )
		{
			$row=$query->row();
				
			if ($row->likes) $likes = $row->likes;
			if ($row->comment_text) $comment = $row->comment_text;
			if ($row->author) $author = $row->author;
		}
			
		//Check if record exists
		$sql = "SELECT * FROM comments_likes WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(comment_id)='".$this->db->escape_str($comment_id)."')";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() == 0 )
		{
			$this->db->trans_start();
									
			$dat=array(
				'email' => $this->db->escape_str($email),
				'msisdn' => $this->db->escape_str($phone),
				'comment_id' => $this->db->escape_str($comment_id),
				'likecomment' => 'Y',
				'likedate' => $dt
				);							
			
			$this->db->insert('comments_likes', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Subscriber with email ".$email.' ('.strtoupper($author).") attempted liking comment ".strtoupper($comment)." but failed.";
			}else
			{
				$likes++;
				
				#Update like count in comments table
				$sql = "SELECT likes FROM comments WHERE (TRIM(comment_id)='".$this->db->escape_str($comment_id)."')";
		
				$query = $this->db->query($sql);
				
				if ($query->num_rows() > 0 )
				{
					$this->db->trans_start();
										
					$dat=array('likes' => $likes);							
					
					$this->db->where('comment_id', $comment_id);
					$this->db->update('comments', $dat);
					
					$this->db->trans_complete();
				}
								
				$Msg="Subscriber with email ".$email.' ('.strtoupper($author).") liked the comment ".strtoupper($comment)." successfully.";			
								
				$ret = $likes;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($email,$Msg,$auhor,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'LIKED COMMENT',$_SESSION['LogID']);
		}
		
		echo $ret;
	}
	
	public function DislikeComment()
	{
		$email=''; $phone=''; $comment_id=''; $comment=''; $author=''; $dislikes=0; $ret='';
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('comment_id')) $comment_id = trim($this->input->post('comment_id'));
		
		#Get comment details
		$sql = "SELECT dislikes,comment_text,author FROM comments WHERE (comment_id=".$this->db->escape_str($comment_id).")";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() > 0 )
		{
			$row=$query->row();
				
			if ($row->dislikes) $dislikes = $row->dislikes;
			if ($row->comment_text) $comment = $row->comment_text;
			if ($row->author) $author = $row->author;
		}
			
		//Check if record exists
		$sql = "SELECT * FROM comments_likes WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(comment_id)='".$this->db->escape_str($comment_id)."')";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() == 0 )
		{
			$this->db->trans_start();
									
			$dat=array(
				'email' => $this->db->escape_str($email),
				'msisdn' => $this->db->escape_str($phone),
				'comment_id' => $this->db->escape_str($comment_id),
				'likecomment' => 'N',
				'likedate' => $dt
			);							
			
			$this->db->insert('comments_likes', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Subscriber with email ".$email.' ('.strtoupper($author).") attempted disliking comment ".strtoupper($comment)." but failed.";
			}else
			{
				$dislikes++;
				
				#Update like count in comments table
				$sql = "SELECT dislikes FROM comments WHERE (TRIM(comment_id)='".$this->db->escape_str($comment_id)."')";
		
				$query = $this->db->query($sql);
				
				if ($query->num_rows() > 0 )
				{
					$this->db->trans_start();
										
					$dat=array('dislikes' => $dislikes);							
					
					$this->db->where('comment_id', $comment_id);
					$this->db->update('comments', $dat);
					
					$this->db->trans_complete();
				}
								
				$Msg="Subscriber with email ".$email.' ('.strtoupper($author).") disliked the comment ".strtoupper($comment)." successfully.";			
								
				$ret = $dislikes;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($email,$Msg,$auhor,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'DISLIKED COMMENT',$_SESSION['LogID']);
		}
		
		echo $ret;
	}
	
	public function DislikeVideo()
	{
		$email=''; $videocode=''; $videotitle=''; $phone=''; $dislikes=0;
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('videotitle')) $videotitle = trim($this->input->post('videotitle'));
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
						
		//Check if record exists
		$sql = "SELECT * FROM likes WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(video_code)='".$this->db->escape_str($videocode)."')";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() == 0 )
		{
			$this->db->trans_start();
									
			$dat=array(
				'email' => $this->db->escape_str($email),
				'msisdn' => $this->db->escape_str($phone),
				'video_code' => $this->db->escape_str($videocode),
				'likevideo' => 'N',
				'actiondate' => $dt
				);							
			
			$this->db->insert('likes', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Subscriber with email ".$email." attempted disliking video ".$videotitle.'('.$videocode.") but failed.";
								
				$ret = '';
			}else
			{
				$dislikes++;
				
				#Update like count in videos table
				$sql = "SELECT dislikes FROM videos WHERE (TRIM(video_code)='".$this->db->escape_str($videocode)."')";
		
				$query = $this->db->query($sql);
				
				$dt=date('Y-m-d H:i:s');
							
				if ($query->num_rows() > 0 )
				{
					$row=$query->row();
					
					if ($row->dislikes) $dislikes += intval($row->dislikes);					
					
					$this->db->trans_start();
										
					$dat=array('dislikes' => $dislikes);							
					
					$this->db->where('video_code', $videocode);
					$this->db->update('videos', $dat);
					
					$this->db->trans_complete();
				}
								
				$Msg="Subscriber with email ".$email." disliked the video ".$videotitle.'('.$videocode.") successfully.";				
								
				$ret = $dislikes;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($email,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'DISLIKED VIDEO',$_SESSION['LogID']);
		}else
		{
			$ret='';
		}
		
		echo $ret;
	}
	
	public function LikeVideo()
	{
		$email=''; $videocode=''; $videotitle=''; $phone=''; $likes=0;
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('videotitle')) $videotitle = trim($this->input->post('videotitle'));
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
						
		//Check if record exists
		$sql = "SELECT * FROM likes WHERE (TRIM(email)='".$this->db->escape_str($email)."') AND (TRIM(video_code)='".$this->db->escape_str($videocode)."')";
		
		$query = $this->db->query($sql);
		
		$dt=date('Y-m-d H:i:s');
					
		if ($query->num_rows() == 0 )
		{
			$this->db->trans_start();
									
			$dat=array(
				'email' => $this->db->escape_str($email),
				'msisdn' => $this->db->escape_str($phone),
				'video_code' => $this->db->escape_str($videocode),
				'likevideo' => 'Y',
				'actiondate' => $dt
				);							
			
			$this->db->insert('likes', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Subscriber with email ".$email." attempted liking video ".$videotitle.'('.$videocode.") but failed.";
								
				$ret = '';
			}else
			{
				$likes++;
				
				#Update like count in videos table
				$sql = "SELECT likes FROM videos WHERE (TRIM(video_code)='".$this->db->escape_str($videocode)."')";
		
				$query = $this->db->query($sql);
				
				$dt=date('Y-m-d H:i:s');
							
				if ($query->num_rows() > 0 )
				{
					$row=$query->row();
					
					if ($row->likes) $likes += intval($row->likes);					
					
					$this->db->trans_start();
										
					$dat=array('likes' => $likes);							
					
					$this->db->where('video_code', $videocode);
					$this->db->update('videos', $dat);
					
					$this->db->trans_complete();
				}
								
				$Msg="Subscriber with email ".$email." liked the video ".$videotitle.'('.$videocode.") successfully.";				
								
				$ret = $likes;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($email,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'LIKED VIDEO',$_SESSION['LogID']);
		}else
		{
			$ret='';
		}
		
		echo $ret;
	}
	
	public function AddComment()
	{
		$videocode=''; $videotitle=''; $author=''; $msisdn=''; $email='';  $comment_text=''; $parent_id='';
		
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('videotitle')) $videotitle = trim($this->input->post('videotitle'));
		if ($this->input->post('author')) $author = $this->input->post('author');
		if ($this->input->post('msisdn')) $msisdn = trim($this->input->post('msisdn'));		
		if ($this->input->post('email')) $email = trim($this->input->post('email'));
		if ($this->input->post('comment_text')) $comment_text = $this->input->post('comment_text');			
		if ($this->input->post('parent_id')) $parent_id = trim($this->input->post('parent_id'));
		
		if (!$parent_id) $parent_id='0';
		
		if ($this->input->post('filename')) $filename = trim($this->input->post('filename'));
		
			
#$file = fopen('aaa.txt',"w"); fwrite($file,$action); fclose($file);
				
		//Check if record exists
		$sql = "SELECT * FROM comments WHERE (TRIM(author)='".$this->db->escape_str($author)."') AND (TRIM(videocode)='".$this->db->escape_str($videocode)."') AND (TRIM(comment_text)='".$this->db->escape_str($comment_text)."')";
		
		$query = $this->db->query($sql);
					
		if ($query->num_rows() > 0 )
		{
			$ret = 'Comment has already been added.';
		}else
		{
			$dt=date('Y-m-d H:i:s');
			
			$this->db->trans_start();
									
			$dat=array(
				'videocode' => $this->db->escape_str($videocode),
				'videotitle' => $this->db->escape_str($videotitle),
				'author' => $this->db->escape_str($author),
				'msisdn' => $this->db->escape_str($msisdn),
				'email' => $this->db->escape_str($email),
				'comment_text' => $this->db->escape_str($comment_text),				
				'parent_id' => $this->db->escape_str($parent_id),
				'likes' => 0,
				'dislikes' => 0,
				'commentstatus' => 1,
				'created_date' => $dt
				);							
			
			$this->db->insert('comments', $dat);
			
			$this->db->trans_complete();
			
			$Msg='';
			
			if ($this->db->trans_status() === FALSE)
			{
				$Msg="Author ".strtoupper($author.'('.$msisdn.')')." attempted adding comment but failed.";
								
				$ret = 'Comment Addition Was Not Successful.';
			}else
			{
				$Msg="Comment By ".strtoupper($author.'('.$msisdn.')')." Was Added Successfully.";			
								
				$ret = 'OK'	;
			}
			
			$remote_ip=$_SERVER['REMOTE_ADDR'];
			$remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);
		
			$this->getdata_model->LogDetails($author,$Msg,$email,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'ADDED COMMENT',$_SESSION['LogID']);
		}
		
		echo $ret;
	}
    public function DemoPlay()
    {
        if ($this->uri->segment(1)) {
            $videocode=trim(str_replace('x-', '', $this->uri->segment(1)));
            $data['videocode'] = $videocode;
            $data['url'] = 'https://laffhub.com/c-'.$videocode;
            //get video data
            $query = $this->db->get_where('videos', ['video_code'=>$videocode]);
            $video = $query->row();
            $data['title'] = $video->video_title;
            $data['description'] = $video->description;
            $data['thumbnail'] = sprintf("https://s3-us-west-2.amazonaws.com/laffhub-thumbs/%s/%s",
             $video->category, trim($video->thumbnail));

            $this->load->view('demo_view',$data);
        }
        else{
            redirect('https://laffhub.com');
        }
       
    }
    public function PlayVideo()
    {

        $videocode=''; $subscriptionId=''; $MaxVideo=''; $VideoTitle=''; $thumbnail='';
        $VideosWatched=0; $SubscriberEmail=''; $DurationInSeconds='0'; $Plan=''; $CurrentVideoCount=0;
        $SubscriberName=''; $phone=''; $network=''; $CommentsCount='0'; $videolist=''; $CanPlayVideo=false;
        $NoPlayReason=''; $CanPlayNew=false; $lang=''; $useragent='';

        $phone=$this->getdata_model->GetMSISDN();
        $network=$this->getdata_model->GetNetwork();

        $data['Phone']=$phone;
        $data['Network']=$network;
        $ret=$network;

        $host=strtolower(trim($_SERVER['HTTP_HOST']));
        $redirect_videocode = null;
        if ($this->uri->segment(1)) {
            $redirect_videocode=trim($this->uri->segment(1));
        }
        else{
            $redirect_videocode = 'Subscriberhome';
        }
        #Check network
        if (strtolower(trim($ret))=='airtel')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost/airtellaffhub/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://airtel.laffhub.com/'.$redirect_videocode);
            }
        }elseif (strtolower(trim($ret))=='mtn')
        {
            if ($host=='localhost')
            {
                redirect('http://localhost/mtnlaffhub/Subscriberhome', 'refresh');
            }else
            {
                redirect('http://mtn.laffhub.com/'.$redirect_videocode);
            }
        }elseif (strtolower(trim($ret))=='etisalat')
        {
            redirect('http://comedy.cloud9.com.ng', 'refresh');

        } elseif (strtolower(trim($ret))=='wifi')
        {
            if ($this->uri->segment(1)) $videocode=trim(str_replace('c-','',$this->uri->segment(1)));

            $tdt=date("Y-m-d H:i:s");

            $data['filename']=''; $data['title']=''; $data['jwplayer_key']='';
            $data['description']=''; $data['category']=''; $data['domain_name']='';
            $data['distribution_Id']=''; $data['origin'] = '';
            $data['subscriptionstatus'] = '<span style="color:#9E0911;">Not Active</span>';
            $data['SubscribeStatus'] = '0';

            $useragent=$_SERVER['HTTP_USER_AGENT'];
            $lang=$_SERVER['HTTP_ACCEPT_LANGUAGE'];

            #Get Player Key
            if ($_SESSION['jwplayer_key']) $data['jwplayer_key'] = $_SESSION['jwplayer_key'];

            if ($_SESSION['thumbs_bucket']) $ThumbBucket = $_SESSION['thumbs_bucket'];

            #Get domain_name
            if ($_SESSION['distribution_Id']) $data['distribution_Id']=$_SESSION['distribution_Id'];
            if ($_SESSION['domain_name']) $data['domain_name'] = $_SESSION['domain_name'];
            if ($_SESSION['origin']) $data['origin'] = $_SESSION['origin'];

            $data['subscriber_email']=$_SESSION['subscriber_email'];

            $email=$data['subscriber_email'];

            $this->getdata_model->CheckSubscriptionDate($email,'');

            if ($_SESSION['subscriber_email']) {

                #Check if subscriber has record in subscription table
                $sql = "SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."') ";

                $query = $this->db->query($sql);

                if ($query->num_rows() == 0 )#New User
                {
                    $isnew=$this->getdata_model->IsNewSubscriberEmail($email,$network);

                    if (intval($isnew,10)==1)
                    {
                        #Create new account
                        ########################### DAILY REPORT FUNCTIONS #######################
                        #NEW - Captured at subscription (Portal and SMS)
                        #### Update Into new_subscriptions
                        $sql="SELECT email FROM new_subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

                        $query = $this->db->query($sql);

                        if ($query->num_rows() > 0 )
                        {
                            $this->db->trans_start();

                            $dat=array('plan' => 'Trial', 'subscriptiondate' => $tdt);

                            $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";

                            $this->db->where($where);
                            $this->db->update('new_subscriptions', $dat);

                            $this->db->trans_complete();
                        }else
                        {
                            $this->db->trans_start();

                            $dat=array(
                                'network' => $this->db->escape_str($network),
                                'msisdn' => $this->db->escape_str($phone),
                                'plan' => 'Trial',
                                'email' => $this->db->escape_str($email),
                                'subscriptiondate' => $tdt,
                            );

                            $this->db->insert('new_subscriptions', $dat);

                            $this->db->trans_complete();
                        }
                        ########################### DAILY REPORT FUNCTIONS #######################

                        $subscriptionId=strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10));
                        $msisdn=$phone;				$plan='Trial';		$duration=2;
                        $videos_cnt_to_watch = 3;	$amount=0;			$autobilling=1;
                        $subscribe_date = date('Y-m-d H:i:s');
                        $exp_date=date('Y-m-d H:i:s',strtotime("+".$duration." days",strtotime($subscribe_date)));
                        $watched=0;

                        #Update freetrials table
                        $this->db->trans_start();

                        $dat=array(
                            'network' => $this->db->escape_str($network),
                            'msisdn' => $msisdn,
                            'email' => $this->db->escape_str($email),
                            'triedfree' => 1,
                            'trialdate' => $subscribe_date,
                            'trialexpire' => $this->db->escape_str($exp_date),
                            'trialdays' => $duration
                        );

                        $this->db->insert('freetrials', $dat);
                        $this->db->trans_complete();
                        ############# freetrials ##############



                        #Save Subscription Record
                        $this->db->trans_start();

                        $dat=array(
                            'subscriptionId' => $subscriptionId,
                            'email' => $this->db->escape_str($email),
                            'network' => $this->db->escape_str($network),
                            'msisdn' => $msisdn,
                            'plan' => $this->db->escape_str($plan),
                            'duration' => $this->db->escape_str($duration),
                            'amount' => $this->db->escape_str($amount),
                            'autobilling' => $this->db->escape_str($autobilling),
                            'subscribe_date' => $subscribe_date,
                            'exp_date' => $this->db->escape_str($exp_date),
                            'videos_cnt_watched' => $watched,
                            'videos_cnt_to_watch' => $this->db->escape_str($videos_cnt_to_watch),
                            'subscriptionstatus' => 1
                        );


                        $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
                        $query = $this->db->query($sql);

                        if ($query->num_rows() > 0 )#There is active subscription
                        {
                            $row = $query->row();

                            if ($row->subscriptionstatus==0)
                            {

                                $where = "(TRIM(network)='".$this->db->escape_str($network)."') AND (TRIM(email)='".$this->db->escape_str($email)."')";
                                $this->db->where($where);
                                $this->db->update('subscriptions', $dat);
                            }
                        }else
                        {
                            $this->db->insert('subscriptions', $dat);
                        }

                        $this->db->trans_complete();

                        $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Email => ".$email."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;

                        #Create record in watchlists table
                        $this->db->trans_start();
                        $dat=array('subscriptionId' => $subscriptionId, 'videolist' => '');
                        $this->db->insert('watchlists', $dat);
                        $this->db->trans_complete();

                        $ret='OK';

                        $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
                    }
                }

                if ($query->num_rows() > 0 )
                {
                    $sql = "SELECT * FROM subscriptions WHERE (TRIM(email)='".$this->db->escape_str($email)."') ";

                    $query = $this->db->query($sql);

                    $row = $query->row();

                    if ($row->subscriptionId) $subscriptionId=trim($row->subscriptionId);
                    if ($row->videos_cnt_to_watch) $MaxVideo=trim($row->videos_cnt_to_watch);
                    if ($row->subscriptionstatus) $subscription_status=$row->subscriptionstatus;
                    if ($row->plan) $Plan=trim($row->plan);
                    if ($row->email) $SubscriberEmail=trim($row->email);

                    if (strtolower($MaxVideo)=='unlimited') $MaxVideo='1000000000';

                    $data['subscriber_email']= $SubscriberEmail;
                    $data['MaxVideo']=$MaxVideo;
                    $data['subscriptionId'] = $subscriptionId;

                    if (!$subscription_status) $subscription_status='0';

                    #Check if Subscription is active
                    $result=$this->getdata_model->GetSubscriptionDate($email,'');

                    if (is_array($result))
                    {
                        foreach($result as $rw)
                        {
                            if ($rw->subscribe_date) $dt = date('F d, Y',strtotime($rw->subscribe_date));

                            $data['subscribe_date'] = $dt;

                            if ($rw->exp_date) $edt = date('F d, Y',strtotime($rw->exp_date));
                            $data['exp_date'] = $edt;

                            if ($tdt > date('Y-m-d H:i:s',strtotime($rw->exp_date)))
                            {
                                if ($rw->subscriptionstatus==1)
                                {
                                    #Update Subscription Date
                                    $this->getdata_model->UpdateSubscriptionStatus($email,$phone,'0');
                                    $data['SubscribeStatus'] = '0';
                                }
                            }else
                            {
                                if (!$rw->subscriptionstatus)
                                {
                                    $this->getdata_model->UpdateSubscriptionStatus($email,$phone,'1');
                                    $data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
                                    $data['SubscribeStatus'] = '1';
                                }else
                                {
                                    $data['subscriptionstatus'] = '<span style="color:#099E11;">Active</span>';
                                    $data['SubscribeStatus'] = '1';
                                }
                            }

                            break;
                        }
                    }

                    #Get Video Lists
                    $sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";

                    $query = $this->db->query($sql);

                    #Get VideosWatched
                    if ($query->num_rows() > 0 )
                    {
                        $row = $query->row();

                        if ($row->videolist) $videolist=trim($row->videolist);

                        if ($videolist <> '') $VideosWatched=count(explode('^',$videolist)); else $VideosWatched=0;
                    }else
                    {
                        $VideosWatched=0;
                    }

                    $data['VideosWatched']=$VideosWatched;
                    $SpareView=false;

                    #Get Current Video Count
                    if ($videolist <> '')
                    {
                        $arrWatched=array();

                        $arrTotalWatched=explode('^',$videolist);

                        if (count($arrTotalWatched)>0)
                        {
                            foreach($arrTotalWatched as $itm):
                                if ($itm)
                                {
                                    $ex=explode('|',$itm);

                                    if (count($ex)>0)
                                    {
                                        if (trim($ex[0])== trim($videocode)) $CurrentVideoCount=$ex[1];
                                        if (intval($ex[1],10) < 3) $SpareView=true;
                                    }
                                }
                            endforeach;
                        }
                    }

                    $data['CurrentVideoCount']=$CurrentVideoCount;

                    #Get Video Duration & Total Comments
                    $sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(video_code)='".$this->db->escape_str($videocode)."')";

                    $query = $this->db->query($sql);

                    if ($query->num_rows() > 0 )
                    {
                        $row = $query->row();

                        $data['watchcount']='0'; $data['comedian']=''; $data['likes']='0'; $data['dislikes']='0';

                        if ($row->filename) $data['filename']=$row->filename;
                        if ($row->video_title) $data['title']=$row->video_title;
                        if ($row->description) $data['description']=$row->description;
                        if ($row->category) $data['category']=$row->category;
                        if ($row->thumbnail) $data['thumbnail']=$row->thumbnail;
                        if ($row->watchcount) $data['watchcount']=$row->watchcount;

                        if ($row->comedian)
                        {
                            if (strtolower($row->comedian)<>'undefined') $data['comedian']=$row->comedian;
                        }

                        if ($row->date_created) $data['date_created']=$row->date_created;
                        if ($row->likes) $data['likes']=$row->likes;
                        if ($row->dislikes) $data['dislikes']=$row->dislikes;

                        if ($row->duration)
                        {
                            $data['duration']=$row->duration;
                            $d=explode(':',$row->duration);

                            $sec=0;

                            if (count($d)==3)
                            {
                                $sec=(intval($d[0],10)*120)+(intval($d[1],10)*60)+intval($d[2],10);

                                $data['duration_in_min']=(intval($d[0],10)*60);
                                $data['duration_in_sec']=(intval($d[1],10)*60)+intval($d[2],10);
                            }elseif (count($d)==2)
                            {
                                $sec=(intval($d[0],10)*60)+intval($d[1],10);

                                $data['duration_in_min']=$d[0];
                                $data['duration_in_sec']=$d[1];
                            }

                            $data['duration_secs']=$sec;
                        }
                    }

                    $data['NewVideoPlay']='-1';
                    $RePlayOld=false;

                    ###PUTTING ALL TOGETHER
                    #Check if current video maximum count of 3 has been reached
                    if ($CurrentVideoCount >= 3)
                    {
                        if (intval($VideosWatched,10) >= intval($MaxVideo,10))
                        {
                            if ($SpareView==true)#Can watch other videos
                            {
                                #Stop but can choose another video
                                $CanPlayVideo=false;
                                $NoPlayReason='You can only watch <b>'.$MaxVideo.'</b> videos for the current subscription plan. Please note that each selected video can be watched 3 times. Any video you have not watched up to 3 times can be rewatched.';
                                $CanPlayNew=false;
                                $RePlayOld=true;
                            }else#Exhausted
                            {
                                #Stop. Exhausted all videos
                                $CanPlayVideo=false;
                                $NoPlayReason='You have exhausted all the videos you are allowed to watch for the current subscription plan. Please renew your subscription.';
                                $CanPlayNew=false;
                                $RePlayOld=false;
                            }
                        }else#$VideosWatched < $MaxVideo
                        {
                            #Stop but can choose another video
                            if ($SpareView==true)
                            {
                                $CanPlayVideo=false;
                                $CanPlayNew=true;
                                $RePlayOld=true;
                                $NoPlayReason='You have reached The maximum number of times the selected video can be watched. You can, however, watch a new video or any other video that you have watched less than 3 times.';
                            }else
                            {
                                $CanPlayVideo=false;
                                $CanPlayNew=true;
                                $RePlayOld=false;
                                $NoPlayReason='You have reached The maximum number of times the selected video can be watched. You can, however, watch a new video.';
                            }
                        }
                    }else#$CurrentVideoCount < 3
                    {
                        #Check if it is a new video
                        if ($videolist <> '')
                        {
                            $arrWatched=array(); $newvideo=true;

                            $arrTotalWatched=explode('^',$videolist);

                            if (count($arrTotalWatched)>0)
                            {
                                foreach($arrTotalWatched as $itm):
                                    if ($itm)
                                    {
                                        $ex=explode('|',$itm);

                                        if (count($ex)>0)
                                        {
                                            if (trim($ex[0])== trim($videocode))
                                            {
                                                $newvideo=false;
                                                break;
                                            }
                                        }
                                    }
                                endforeach;
                            }
                        }

                        if ($newvideo==true)
                        {
                            if (intval($VideosWatched,10) < intval($MaxVideo,10))#Play
                            {
                                #Play
                                $CanPlayVideo=true;
                                $CanPlayNew=true;
                                $NoPlayReason='';
                                $RePlayOld=true;
                                $SpareView=true;
                                $data['NewVideoPlay']=true;
                            }else#Maxvideo reached - Stop
                            {
                                if ($SpareView==true)#Can only watched old not up to 3
                                {
                                    #Stop but can replay old movie
                                    $RePlayOld=true;
                                    $CanPlayVideo=false;
                                    $NoPlayReason='You can only watch <b>'.trim($MaxVideo).'</b> videos for the current subscription plan. Please note that each video can be watched 3 times. You can rewatch any video you have not watched up to 3 times.';
                                    $CanPlayNew=false;
                                    $data['NewVideoPlay']=false;
                                }else
                                {
                                    #Stop. Exhausted all videos
                                    $CanPlayVideo=false;
                                    $RePlayOld=false;
                                    $NoPlayReason='You have exhausted all the videos you are allowed to watch for the current subscription plan. Please renew your subscription.';
                                    $CanPlayNew=false;
                                }
                            }
                        }else#Old video
                        {
                            #Play
                            $CanPlayVideo=true;
                            $CanPlayNew=true;
                            $NoPlayReason='';
                            $RePlayOld=true;
                            $data['NewVideoPlay']=true;
                        }
                    }

                    $data['CanPlayVideo']=$CanPlayVideo;
                    $data['CanPlayNew']=$CanPlayNew;
                    $data['NoPlayReason']=$NoPlayReason;
                    $data['SpareView']=$SpareView;
                    $data['newvideo']=$newvideo;
                    $data['videolist']=$videolist;

                    #Save Transaction
                    $remote_ip=$_SERVER['REMOTE_ADDR'];
                    $remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);

                    #$tddate=date('Y-m-d H:i',strtotime($tdt));
                    $tddate=date('Y-m-d',strtotime($tdt));

                    #Check if it is first time it registering
                    #$sql = "SELECT trans_date FROM transactions WHERE (DATE_FORMAT(trans_date,'%Y-%m-%d')='".$tddate."') AND (TRIM(video_code)='".$videocode."') AND (TRIM(phone)='".$phone."')";

                    #$qry = $this->db->query($sql);

                    $this->db->trans_start();

                    $dat=array(
                        'email' => $this->db->escape_str($email),
                        'phone' => $this->db->escape_str($data['Phone']),
                        'trans_date' => $tdt,
                        'filename' => $this->db->escape_str($data['filename']),
                        'video_code' => $this->db->escape_str($videocode),
                        'user_agent' => $this->db->escape_str($useragent),
                        'video_category' => $this->db->escape_str($data['category']),
                        'remote_address' => $this->db->escape_str($remote_ip),
                        'remote_host' => $this->db->escape_str($remote_host),
                        'lang' => $this->db->escape_str($lang),
                        'network' => $network
                    );

                    $this->db->insert('transactions', $dat);

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE)
                    {
                        $Msg="Transaction from subscriber with email ".strtoupper($email)." failed.";

                        $this->getdata_model->LogDetails($email,$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'NEW WATCH VIDEO REQUEST','System');
                    }else
                    {
                        $Msg="Transaction from subscriber with email ".strtoupper($email)." was successful.";

                        $this->getdata_model->LogDetails($email,$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'NEW WATCH VIDEO REQUEST','System');
                    }

                    $arr = explode('.', basename($data['filename']));
                    $ext=array_pop($arr);
                    $fn=str_replace('.'.$ext,'',basename($data['filename']));

                    $preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$data['category'].'/'.$data['thumbnail'];
                    $data['videocode'] = $videocode;
                    $data['thumbs_bucket'] = $ThumbBucket;
                    $data['preview_img']=$preview_img;
                    $data['RelatedVideos']=$this->getdata_model->GetRelatedVideos($data['category'],$videocode);
                    $data['ViewPagePopularVideos']=$this->getdata_model->GetViewPagePopularVideos($videocode);
                    $data['Categories']=$this->getdata_model->GetCategories();
                    $data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
                    $data['RandomlyRelatedVideos']=$this->getdata_model->GetRandomlyRelatedVideos($data['category'],$videocode,$data['comedian']);

                    $this->load->view('v_view',$data);
            }

            } else
            {

                $subscriptionId=strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10));

                #Create record in watchlists table
                $this->db->trans_start();
                $dat=array('subscriptionId' => $subscriptionId, 'videolist' => '');
                $this->db->insert('watchlists', $dat);
                $this->db->trans_complete();

                #Get Video Lists
                $sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";

                $query = $this->db->query($sql);

                #Get VideosWatched
                if ($query->num_rows() > 0 )
                {
                    $row = $query->row();

                    if ($row->videolist) $videolist=trim($row->videolist);

                    if ($videolist <> '') $VideosWatched=count(explode('^',$videolist)); else $VideosWatched=0;
                }else
                {
                    $VideosWatched=0;
                }

                $data['VideosWatched']=$VideosWatched;
                $SpareView=false;

                #Get Current Video Count
                if ($videolist <> '')
                {
                    $arrWatched=array();

                    $arrTotalWatched=explode('^',$videolist);

                    if (count($arrTotalWatched)>0)
                    {
                        foreach($arrTotalWatched as $itm):
                            if ($itm)
                            {
                                $ex=explode('|',$itm);

                                if (count($ex)>0)
                                {
                                    if (trim($ex[0])== trim($videocode)) $CurrentVideoCount=$ex[1];
                                    if (intval($ex[1],10) < 3) $SpareView=true;
                                }
                            }
                        endforeach;
                    }
                }

                $data['CurrentVideoCount']=$CurrentVideoCount;

                #Get Video Duration & Total Comments
                $sql = "SELECT * FROM videos WHERE (play_status=1) AND (encoded=1) AND (TRIM(video_code)='".$this->db->escape_str($videocode)."')";

                $query = $this->db->query($sql);

                if ($query->num_rows() > 0 )
                {
                    $row = $query->row();

                    $data['watchcount']='0'; $data['comedian']=''; $data['likes']='0'; $data['dislikes']='0';

                    if ($row->filename) $data['filename']=$row->filename;
                    if ($row->video_title) $data['title']=$row->video_title;
                    if ($row->description) $data['description']=$row->description;
                    if ($row->category) $data['category']=$row->category;
                    if ($row->thumbnail) $data['thumbnail']=$row->thumbnail;
                    if ($row->watchcount) $data['watchcount']=$row->watchcount;

                    if ($row->comedian)
                    {
                        if (strtolower($row->comedian)<>'undefined') $data['comedian']=$row->comedian;
                    }

                    if ($row->date_created) $data['date_created']=$row->date_created;
                    if ($row->likes) $data['likes']=$row->likes;
                    if ($row->dislikes) $data['dislikes']=$row->dislikes;

                    if ($row->duration)
                    {
                        $data['duration']=$row->duration;
                        $d=explode(':',$row->duration);

                        $sec=0;

                        if (count($d)==3)
                        {
                            $sec=(intval($d[0],10)*120)+(intval($d[1],10)*60)+intval($d[2],10);

                            $data['duration_in_min']=(intval($d[0],10)*60);
                            $data['duration_in_sec']=(intval($d[1],10)*60)+intval($d[2],10);
                        }elseif (count($d)==2)
                        {
                            $sec=(intval($d[0],10)*60)+intval($d[1],10);

                            $data['duration_in_min']=$d[0];
                            $data['duration_in_sec']=$d[1];
                        }

                        $data['duration_secs']=$sec;
                    }
                }

                $data['NewVideoPlay']='-1';
                $RePlayOld=false;

                ###PUTTING ALL TOGETHER
                #Check if current video maximum count of 3 has been reached
                if ($CurrentVideoCount >= 3)
                {
                    if (intval($VideosWatched,10) >= intval($MaxVideo,10))
                    {
                        if ($SpareView==true)#Can watch other videos
                        {
                            #Stop but can choose another video
                            $CanPlayVideo=false;
                            $NoPlayReason='You can only watch <b>'.$MaxVideo.'</b> videos for the current subscription plan. Please note that each selected video can be watched 3 times. Any video you have not watched up to 3 times can be rewatched.';
                            $CanPlayNew=false;
                            $RePlayOld=true;
                        }else#Exhausted
                        {
                            #Stop. Exhausted all videos
                            $CanPlayVideo=false;
                            $NoPlayReason='You have exhausted all the videos you are allowed to watch for the current subscription plan. Please renew your subscription.';
                            $CanPlayNew=false;
                            $RePlayOld=false;
                        }
                    }else#$VideosWatched < $MaxVideo
                    {
                        #Stop but can choose another video
                        if ($SpareView==true)
                        {
                            $CanPlayVideo=false;
                            $CanPlayNew=true;
                            $RePlayOld=true;
                            $NoPlayReason='You have reached The maximum number of times the selected video can be watched. You can, however, watch a new video or any other video that you have watched less than 3 times.';
                        }else
                        {
                            $CanPlayVideo=false;
                            $CanPlayNew=true;
                            $RePlayOld=false;
                            $NoPlayReason='You have reached The maximum number of times the selected video can be watched. You can, however, watch a new video.';
                        }
                    }
                }else#$CurrentVideoCount < 3
                {
                    #Check if it is a new video
                    if ($videolist <> '')
                    {
                        $arrWatched=array(); $newvideo=true;

                        $arrTotalWatched=explode('^',$videolist);

                        if (count($arrTotalWatched)>0)
                        {
                            foreach($arrTotalWatched as $itm):
                                if ($itm)
                                {
                                    $ex=explode('|',$itm);

                                    if (count($ex)>0)
                                    {
                                        if (trim($ex[0])== trim($videocode))
                                        {
                                            $newvideo=false;
                                            break;
                                        }
                                    }
                                }
                            endforeach;
                        }
                    }

                    if ($newvideo==true)
                    {
                        if (intval($VideosWatched,10) < intval($MaxVideo,10))#Play
                        {
                            #Play
                            $CanPlayVideo=true;
                            $CanPlayNew=true;
                            $NoPlayReason='';
                            $RePlayOld=true;
                            $SpareView=true;
                            $data['NewVideoPlay']=true;
                        }else#Maxvideo reached - Stop
                        {
                            if ($SpareView==true)#Can only watched old not up to 3
                            {
                                #Stop but can replay old movie
                                $RePlayOld=true;
                                $CanPlayVideo=false;
                                $NoPlayReason='You can only watch <b>'.trim($MaxVideo).'</b> videos for the current subscription plan. Please note that each video can be watched 3 times. You can rewatch any video you have not watched up to 3 times.';
                                $CanPlayNew=false;
                                $data['NewVideoPlay']=false;
                            }else
                            {
                                #Stop. Exhausted all videos
                                $CanPlayVideo=false;
                                $RePlayOld=false;
                                $NoPlayReason='You have exhausted all the videos you are allowed to watch for the current subscription plan. Please renew your subscription.';
                                $CanPlayNew=false;
                            }
                        }
                    }else#Old video
                    {
                        #Play
                        $CanPlayVideo=true;
                        $CanPlayNew=true;
                        $NoPlayReason='';
                        $RePlayOld=true;
                        $data['NewVideoPlay']=true;
                    }
                }

                $data['CanPlayVideo']=$CanPlayVideo;
                $data['CanPlayNew']=$CanPlayNew;
                $data['NoPlayReason']=$NoPlayReason;
                $data['SpareView']=$SpareView;
                $data['newvideo']=$newvideo;
                $data['videolist']=$videolist;

                #Save Transaction
                $remote_ip=$_SERVER['REMOTE_ADDR'];
                $remote_host=gethostbyaddr($_SERVER['REMOTE_ADDR']);

                #$tddate=date('Y-m-d H:i',strtotime($tdt));
                $tddate=date('Y-m-d',strtotime($tdt));

                #Check if it is first time it registering
                #$sql = "SELECT trans_date FROM transactions WHERE (DATE_FORMAT(trans_date,'%Y-%m-%d')='".$tddate."') AND (TRIM(video_code)='".$videocode."') AND (TRIM(phone)='".$phone."')";

                #$qry = $this->db->query($sql);

                $this->db->trans_start();

                $dat=array(
                    'email' => $this->db->escape_str($email),
                    'phone' => $this->db->escape_str($data['Phone']),
                    'trans_date' => $tdt,
                    'filename' => $this->db->escape_str($data['filename']),
                    'video_code' => $this->db->escape_str($videocode),
                    'user_agent' => $this->db->escape_str($useragent),
                    'video_category' => $this->db->escape_str($data['category']),
                    'remote_address' => $this->db->escape_str($remote_ip),
                    'remote_host' => $this->db->escape_str($remote_host),
                    'lang' => $this->db->escape_str($lang),
                    'network' => $network
                );

                $this->db->insert('transactions', $dat);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE)
                {
                    $Msg="Transaction from subscriber with email ".strtoupper($email)." failed.";

                    $this->getdata_model->LogDetails($email,$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'NEW WATCH VIDEO REQUEST','System');
                }else
                {
                    $Msg="Transaction from subscriber with email ".strtoupper($email)." was successful.";

                    $this->getdata_model->LogDetails($email,$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'NEW WATCH VIDEO REQUEST','System');
                }

                $arr = explode('.', basename($data['filename']));
                $ext=array_pop($arr);
                $fn=str_replace('.'.$ext,'',basename($data['filename']));

                $preview_img='https://s3-us-west-2.amazonaws.com/'.$ThumbBucket.'/'.$data['category'].'/'.$data['thumbnail'];
                $data['videocode'] = $videocode;
                $data['thumbs_bucket'] = $ThumbBucket;
                $data['preview_img']=$preview_img;
                $data['RelatedVideos']=$this->getdata_model->GetRelatedVideos($data['category'],$videocode);
                $data['ViewPagePopularVideos']=$this->getdata_model->GetViewPagePopularVideos($videocode);
                $data['Categories']=$this->getdata_model->GetCategories();
                $data['ActiveAdverts']=$this->getdata_model->GetActiveAdverts();
                $data['RandomlyRelatedVideos']=$this->getdata_model->GetRandomlyRelatedVideos($data['category'],$videocode,$data['comedian']);

                $this->load->view('v_view',$data);
            }
        } else
        {
            redirect("Subscriberhome");
        }
    }

	public function GetComments()
	{
		$videocode='';
		
		if ($this->input->post('videocode')) $videocode = $this->input->post('videocode');
	
		$cm=$this->getdata_model->GetVideoComments($videocode);	
		
		echo json_encode($cm);
	 }
	 
	public function CheckForWatchCount()
	{
		$phone=''; $email=''; $videocode=''; $subscriptionId=''; $title='';
		$VideosWatched=0; $MaxVideo=0; $SpareView=false; $CurrentVideoCount=0;
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = $this->input->post('email');
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));
		if ($this->input->post('title')) $title = trim($this->input->post('title'));
		
		#Get videos_cnt_to_watch
		$sql = "SELECT videos_cnt_to_watch FROM subscriptions WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";
			
		$query = $this->db->query($sql);
				
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
			
			if ($row->videos_cnt_to_watch) $MaxVideo=trim($row->videos_cnt_to_watch);

			if (strtolower($MaxVideo)=='unlimited') $MaxVideo='1000000000';
		}
		
		#Get Video Lists
		$sql = "SELECT videolist FROM watchlists WHERE (TRIM(subscriptionId)='".$this->db->escape_str($subscriptionId)."')";				
				
		$query = $this->db->query($sql);
		
		#Get VideosWatched
		if ($query->num_rows() > 0 )
		{
			$row = $query->row();
		
			if ($row->videolist) $videolist=trim($row->videolist);
			
			if ($videolist <> '') $VideosWatched=count(explode('^',$videolist)); else $VideosWatched=0;
		}else
		{
			$VideosWatched=0;
		}
		
		#Get Current Video Count
		if ($videolist <> '')
		{
			$arrWatched=array(); 
	
			$arrTotalWatched=explode('^',$videolist);
			
			if (count($arrTotalWatched)>0)
			{
				foreach($arrTotalWatched as $itm):
					if ($itm)
					{
						$ex=explode('|',$itm);
						
						if (count($ex)>0)
						{
							if (trim($ex[0])== trim($videocode)) $CurrentVideoCount=$ex[1];
							if (intval($ex[1],10) < 3) $SpareView=true;
						}
					}
				endforeach;
			}
		}
				
		#Check if current video maximum count of 3 has been reached
		if ($CurrentVideoCount >= 3)
		{
			if (intval($VideosWatched,10) >= intval($MaxVideo,10))
			{
				if ($SpareView==true)#Can watch other videos
				{
					#Stop but can choose another video
					$CanPlayVideo=false;
					$CanPlayNew=false;
					$RePlayOld=true;
					
					$data='You have reached the maximum number of times you can watch <b>'.strtoupper($title).'</b>. You can, however, watch a new video or any other video that you have watched less than 3 times.';
				}else#Exhausted
				{
					#Stop. Exhausted all videos
					$CanPlayVideo=false;
					$CanPlayNew=false;
					$RePlayOld=false;
					
					$data='You have reached the maximum number of videos and the maximum number of times you can watch each video for the current subscription plan.';
				}							
			}else#$VideosWatched < $MaxVideo
			{
				#Stop but can choose another video
				if ($SpareView==true)
				{
					$CanPlayVideo=false;
					$CanPlayNew=true;
					$RePlayOld=true;
					
					$data='You have reached the maximum number of times you can watch <b>'.strtoupper($title).'</b>. You can, however, watch a new video or any other video that you have watched less than 3 times.';
				}else
				{
					$CanPlayVideo=false;
					$CanPlayNew=true;
					$RePlayOld=false;
					
					$data='You have reached the maximum number of times you can watch <b>'.strtoupper($title).'</b>. You can, however, watch a new video.';
				}							
			}
		}else#$CurrentVideoCount < 3
		{
			$newvideo=false;
			#Check if it is a new video
			if ($videolist <> '')
			{
				$arrWatched=array(); $newvideo=true;
		
				$arrTotalWatched=explode('^',$videolist);
				
				if (count($arrTotalWatched)>0)
				{
					foreach($arrTotalWatched as $itm):
						if ($itm)
						{
							$ex=explode('|',$itm);
							
							if (count($ex)>0)
							{
								if (trim($ex[0])== trim($videocode))
								{
									$newvideo=false;
									break;
								}
							}
						}
					endforeach;
				}
			}
			
			if ($newvideo==true)
			{
				if (intval($VideosWatched,10) < intval($MaxVideo,10))#Play
				{
					#Play
					$CanPlayVideo=true;
					$CanPlayNew=true;
					$RePlayOld=true;
					
					$data='OK';
				}else#Maxvideo reached - Stop
				{
					if ($SpareView==true)#Can only watched old not up to 3
					{
						#Stop but can replay old movie
						$RePlayOld=true;
						$CanPlayVideo=false;
						$CanPlayNew=false;
						
						$data='You have reached the maximum number of times you can watch <b>'.strtoupper($title).'</b>. You can, however, watch any other video that you have watched less than 3 times.';
					}else
					{
						#Stop. Exhausted all videos
						$CanPlayVideo=false;
						$RePlayOld=false;
						$CanPlayNew=false;
						
						$data='You have reached the maximum number of videos and the maximum number of times you can watch each video for the current subscription plan.';
					}
				}	
			}else#Old video
			{
				#Play
				$CanPlayVideo=true;
				$CanPlayNew=true;
				$RePlayOld=true;
				
				$data='OK';
			}						
		}
		
		#$file = fopen('aaa.txt',"a"); fwrite($file,"\nMaxVideo=".$MaxVideo."\nVideosWatched=".$VideosWatched."\nData=".$data); fclose($file);
				
		echo $data;
	}
	
	public function UpdateWatchCount()
	{
		$phone=''; $email=''; $videocode=''; $subscriptionId='';	
		
		if ($this->input->post('phone')) $phone = trim($this->input->post('phone'));
		if ($this->input->post('email')) $email = $this->input->post('email');
		if ($this->input->post('videocode')) $videocode = trim($this->input->post('videocode'));
		if ($this->input->post('subscriptionId')) $subscriptionId = trim($this->input->post('subscriptionId'));

#$file = fopen('aaa.txt',"w"); fwrite($file,"video code=".$videocode."\nsubscription Id=".$subscriptionId); fclose($file);				
		$ret=$this->getdata_model->SetWatchCount($videocode,$phone,$email,$subscriptionId);	
		
		echo $ret;				
	}
	
#https://d2dm1rzdyku85l.cloudfront.net/Alzheimers_Risk_360p.mp4
#if ($domainname && $filename) $preview_url='https://'.$domainname.'/'.$filename;	
	public function index()
	{
				
	}
}
