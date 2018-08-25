<?php

date_default_timezone_set('Africa/Lagos');
require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';

#$db = new mysqli('localhost', 'root', '', 'htipsdb');
$db = new mysqli('localhost', 'healt521_htpuser', 'UhX)DFTNzpZ2', 'healt521_htipsdb');

if($db->connect_errno > 0) die('Unable to connect to database [' . $db->connect_error . ']');

$ret=CheckForActiveFeed($db);

if ($ret)
{
	#echo 'Changed';
	
	#Get Current File Name And Send To Numbers
	$sql="SELECT * FROM active_rss_feed";#feed_id
	
	if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
			
	if ( $query->num_rows > 0 )
	{
		$pubdate=''; $expdate='';
		
		#Get feed_id from active_rss_feed
		$row = $query->fetch_assoc();
					
		if ($row['feed_id']) $feed_id = $row['feed_id'];
		if ($row['pubdate']) $pubdate = date('d M Y @ H:i:s',strtotime($row['pubdate']));
		if ($row['expiredate']) $expdate = date('d M Y @ H:i:s',strtotime($row['expiredate']));
		
		#Get Feed Record
		$sql="SELECT * FROM rss_feed WHERE feed_id=".$feed_id;
		
		if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
		
		if ( $query->num_rows > 0 )
		{
			$title=''; $shortlink=''; $description=''; $category='';
			
			$row = $query->fetch_assoc();
					
			if ($row['title']) $title = utf8_encode($row['title']);
			if ($row['shortlink']) $url = $row['shortlink'];
			if ($row['description']) $description = utf8_encode($row['description']);
			if ($row['category']) $category = $row['category'];
			
			$from='support@healthyliving.ng';
			$to='o.dania@efluxz.com,davidumoh@icloud.com,ade@efluxz.com,david@laffhub.com,adetutu.adigwe@efluxz.com,adetola@efluxz.com';			
			$subject='Healthy Living Video Schedule';
			$Cc='cronjobs@healthyliving.ng,idongesit_a@yahoo.com,nsikakj@gmail.com';
			
			#$to='idongesit_a@yahoo.com';
			#$Cc='idongesit.akpan@eastwindtechnologies.com,nsikakj@gmail.com';
						
			$message='
				<img src="emaillogo.png" width="100" alt="Healthy Living" title="Healthy Living" />
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
				
			$v=SendEmail($from,$to,$subject,$Cc,$message,$altMessage,'');
		}
	}
}else
{
	#echo 'Not Changed';
}
	
function SendEmail($from,$to,$subject,$Cc,$message,$altMessage,$name)
{	
	$img="emaillogo.png";

	$mail = new PHPMailer();#Create a new PHPMailer instance
	$mail->CharSet = "UTF-8";
	$mail->isSMTP();#/Tell PHPMailer to use SMTP
	$mail->SMTPDebug = 0;		
	$mail->Debugoutput = 'html';//Ask for HTML-friendly debug output		
	$mail->Host = 'smtp.postmarkapp.com';//Set the hostname of the mail server	- smtp.postmarkapp.com
	$mail->Port = 587;//Set the SMTP port number - likely to be 25, 465 or 587	- 25, 2525, or 587
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;//Whether to use SMTP authentication		
	$mail->Username = "2711b43c-2beb-4cec-bbe4-9d262338bf9d";//Username to use for SMTP authentication		
	$mail->Password = "2711b43c-2beb-4cec-bbe4-9d262338bf9d";	#- efec7a8f-a894-4c2a-982f-b3e6deab999c
	$mail->setFrom($from, 'Healthy Living');//Set who the message is to be sent from		
	$mail->addReplyTo($from, 'Healthy Living');//Set an alternative reply-to address		
	
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
	#$mail->AddEmbeddedImage($img, "ms-attach", $img);
	$mail->Body  = $message;
	$mail->AltBody = $altMessage;
	$mail->msgHTML($message);
	
	
	//send the message, check for errors
	if (!$mail->send()) {
		$file = fopen('emailerror.txt',"a"); fwrite($file,"Mailer Error: " . $mail->ErrorInfo); fclose($file);
		
		return "MAILER ERROR: ". $mail->ErrorInfo;
		
	} else {
		return 'OK';
	}
}
	
function CheckForActiveFeed($db)#2 - Returns TRUE/FALSE.    If it is available
{
	$ret=false;
	
	#Get current active feed
	$sql="SELECT * FROM active_rss_feed";
	$query = $db->query($sql);
	
	if ( $query->num_rows > 0 )#Exists - Check for Expiry Date
	{
		$rt=CheckIfActiveFeedExpired($db);#True - Expired, False - Not Expired
		
		if ($rt===true)
		{
			$ret=CreateFeedSaveFeedSaveXML($db);
		}
	}else#Create
	{
		$ret=CreateFeedSaveFeedSaveXML($db);
	}
	
	$query->free();
	
	return $ret;
}


function GetNextSchedule($CurrentID,$MaxID,$MinID,$db)
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
			$query = $db->query($sql);
			
			if ( $query->num_rows > 0 ) $found=true; else $new_id=intval($new_id)+1;
		}			
	}
	
	return $new_id;
}

function CheckIfActiveFeedExpired($db)#1 - Returns True/False
{
	$dt=date('Y-m-d H:i:s'); $ret=false;
	
	$sql="SELECT expiredate FROM active_rss_feed";		
		
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			

	if ( $query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
						
		if ($row['expiredate']) $expdt=$row['expiredate'];
	
		if ($dt > $expdt) $ret=true; else $ret=false;
	}else
	{
		$r=CreateFeedSaveFeedSaveXML($db);
		
		if ($r==true) $ret=false; else $ret=true;
	}
	
	$query->free();
	
	return $ret;
}

function CreateFeedSaveFeedSaveXML($db)#3 - Returns TRUE/FALSE
{
	$displaydays=0; $googlekey=''; $website=''; $companyname=''; #$jw_player_id='';
	$MaxId=0; $MinId=0; $new_id=0; $ret=false; $OldFeed=0;
	
	#Get Settings
	$sql="SELECT * FROM settings";

	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
	if ( $query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
				
		if ($row['no_of_videos_per_day']) $displaydays = $row['no_of_videos_per_day'];
		if ($row['google_shortener_api']) $googlekey = $row['google_shortener_api'];
		if ($row['website']) $website = $row['website'];
		if ($row['companyname']) $companyname = $row['companyname'];
	}
	
	#Get Maximum Schedule ID
	$sql="SELECT MAX(schedule_id) AS MaxID FROM videos WHERE TRIM(video_status)='encoded'";

	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
	if ( $query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
					
		if ($row['MaxID']) $MaxId = $row['MaxID'];
	}
	
	#Get Minimum Schedule ID
	$sql="SELECT MIN(schedule_id) AS MinID FROM videos WHERE TRIM(video_status)='encoded'";
		
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');	
			
	if ( $query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
					
		if ($row['MinID']) $MinId = $row['MinID'];
	}else
	{
		$MinId=1;
	}
	
	$OldScheduleID='';
	
	#Get current active feed
	$sql="SELECT schedule_id,feed_id FROM active_rss_feed";
	
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
	if ($query->num_rows > 0)#Get new_id
	{
		$row = $query->fetch_assoc();
		
		if ($row['feed_id']) $OldFeed=$row['feed_id'];
		if ($row['schedule_id']) $OldScheduleID=$row['schedule_id'];
		
		if ($row['schedule_id'])
		{
			$new_id=GetNextSchedule($OldScheduleID,$MaxId,$MinId,$db);
			
			
			
			/*if (intval($OldScheduleID)==intval($MaxId))
			{
				$new_id=intval($MinId); #Go Minimum Id
			}else
			{
				$new_id=intval($OldScheduleID)+1;
			} */
		}else
		{
			$new_id=1;
		}
	}else#New 
	{
		$new_id=$MinId;
	}
	
	$feed_id=0; $longlink=''; $shortlink=''; $video_description=''; $video_title=''; $video_key='';
	$dt=date('Y-m-d H:i:s'); $category=''; $schedule_id=''; $video_code=''; $thumbnail='';
	
	#Get Video details
	$sql = "SELECT * FROM videos WHERE (schedule_id=".$new_id.")";
	
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
				
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
	
		if ($row['filename']) $video_key=$row['filename'];
		if ($row['video_title']) $video_title=$row['video_title'];
		if ($row['description']) $video_description=$row['description'];
		if ($row['video_status']) $video_status=$row['video_status'];
		if ($row['category']) $category=$row['category'];
		if ($row['schedule_id']) $schedule_id=$row['schedule_id'];
		if ($row['video_code']) $video_code=$row['video_code'];
		if ($row['thumbnail']) $thumbnail=$row['thumbnail'];
		
		#Create New Feed and Store active_rss_feed and rss_feed
		if (trim(strtolower($video_status)) == 'encoded')
		{
			$feed_id=intval(GetNextID('rss_feed','feed_id',$db));
		
			$longlink='http://healthyliving.ng/admin/index.php/Transactions/v/'.$video_code; #Generate Link
			$shortlink=GetShortenUrl($longlink,$db);
			
			try
			{
				$db->autocommit(FALSE);
								
				$sql='INSERT INTO rss_feed (title,longlink,shortlink,description,status,category,feed_id,video_key,video_code,thumbnail,insert_date) VALUES (?,?,?,?,?,?,?,?,?,?,?)';
				
				$tit=$db->escape_string($video_title);
				$long=$db->escape_string($longlink);
				$sht=$db->escape_string($shortlink);
				$des=$db->escape_string($video_description);
				$stat='Not Running';
				$categ=$db->escape_string($category);
				$fid=$db->escape_string($feed_id);
				$vk=$db->escape_string($video_key);
				$vc=$db->escape_string($video_code);
				$th=$db->escape_string($thumbnail);
				$indt=$dt;
				 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('ssssssissss',$tit,$long,$sht,$des,$stat,$categ,$fid,$vk,$vc,$th,$indt);

				$stmt->execute();/* Execute statement */
					
				$db->commit();	
			}catch (Exception $e)
			{
				$ret=false;
				$db->rollback();
			}
			
			#Replace Old Active Feed
			$qry = "SELECT * FROM active_rss_feed";
			
			if(!$myquery = $db->query($qry)) die('There was an error running the query ['.$db->error.']');
			
			$pdt=date('Y-m-d H:i:s', strtotime('today midnight'));
			$expdt= date('Y-m-d H:i:s', strtotime('+'.$displaydays.'days',strtotime($pdt)));
						
			try
			{
				if ($myquery->num_rows > 0 )#Update
				{
					$db->autocommit(FALSE);
								
					$sql='UPDATE active_rss_feed SET feed_id=?,pubdate=?,expiredate=?,schedule_id=?,insert_date=?';
					
					$fid=$db->escape_string($feed_id);
					$pubdt=$db->escape_string($pdt);
					$exdt=$db->escape_string($expdt);
					$sid=$db->escape_string($new_id);
					$indt=$dt;
					 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('issis',$fid,$pubdt,$exdt,$sid,$indt);

					$stmt->execute();/* Execute statement */
						
					$db->commit();
				}else#Insert
				{
					$db->autocommit(FALSE);
								
					$sql='INSERT INTO active_rss_feed (feed_id,pubdate,expiredate,schedule_id,insert_date) VALUES (?,?,?,?,?)';
					$fid=$db->escape_string($feed_id);
					$pubdt=$db->escape_string($pdt);
					$exdt=$db->escape_string($expdt);
					$sid=$db->escape_string($new_id);
					$indt=$dt;
					 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('issis',$fid,$pubdt,$exdt,$sid,$indt);

					$stmt->execute();/* Execute statement */
						
					$db->commit();
				}
			
				#Update rss_feed status
				try
				{
					$st='Running';
					
					$db->autocommit(FALSE);
								
					$sql='UPDATE rss_feed SET status=? WHERE feed_id=?';
					
					$sta=$st;
					$fid=$feed_id;
										 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('si',$sta,$fid);

					$stmt->execute();/* Execute statement */
						
					$db->commit();
				}catch (Exception $e)
				{
					$db->rollback();
				}
				
				####END Of rss_feed UPDATE
				
				#Update Old Video Status
				if ($OldFeed>0)
				{
					try
					{
						$st='Not Running';					
						$db->autocommit(FALSE);
									
						$sql='UPDATE rss_feed SET status=? WHERE feed_id=?';
						
						$sta=$st;
						$fid=$OldFeed;
											 
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('si',$sta,$fid);
	
						$stmt->execute();/* Execute statement */
							
						$db->commit();
					}catch (Exception $e)
					{
						$db->rollback();
					}					
				}
				
				#Generate current rss						
				$ret=GenerateRSS($new_id,$db);
				
				if ($ret==true)
				{
					$Msg="System Generated A New Video Feed With ID '".$new_id."' And Also Created Active Feed For Export Successfully.";
				}else
				{
					$Msg="Feed Generation Was Not Successfully.";
				}
			}catch (Exception $e)
			{
				$db->rollback();
				
				$ret=false;
				$Msg="Feed Generation Was Not Successfully.";
			}	
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
	
	$remote_ip=getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];
			
	#$host = $_SERVER['REMOTE_HOST'];
	$remote_host='';
	if ($remote_ip) $remote_host=gethostbyaddr($remote_ip); 			
	
	LogDetails('System',$Msg,'System',date('Y-m-d H:i:s'),$remote_ip,$remote_host,'GENERATED NEW VIDEO FEED','System',$db);	
	
	$myquery->free();
	$query->free();
	
	return $ret;
}

function getRealIpAddr()
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

function GenerateRSS($schedule_id,$db)
{
	$ret=false;
	
	if ($schedule_id)
	{
		$video_key=''; $video_title=''; $video_description=''; $companyname=''; $website='';
		$pubdate=''; $expiredate=''; $feed_id=0; $thumb_bucket=''; $thumbnail=''; $category='';
		
		#Get Settings
		$sql="SELECT * FROM settings";
			
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
				
		if ($query->num_rows > 0)
		{
			$row = $query->fetch_assoc();	
					
			if ($row['no_of_videos_per_day']) $no_of_videos_per_day = $row['no_of_videos_per_day'];
			if ($row['website']) $website = $row['website'];
			if ($row['companyname']) $companyname = $row['companyname'];
			if ($row['thumbs_bucket']) $thumb_bucket = $row['thumbs_bucket'];
		}
		
		#Get current rss.xml
		$sql = "SELECT * FROM videos WHERE (schedule_id=".$db->escape_string($schedule_id).")";
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
				
		if ($query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
		
			if ($row['filename']) $video_key=$row['filename'];
			if ($row['video_title']) $video_title=$row['video_title'];
			if ($row['description']) $video_description=$row['description'];
			if ($row['thumbnail']) $thumbnail=$row['thumbnail'];
			if ($row['category']) $category=$row['category'];
		}
					
		$sql = 'SELECT * FROM active_rss_feed';
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
		
		if ($query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
			
			if ($row['pubdate']) $pubdate=$row['pubdate'];
			if ($row['expiredate']) $expiredate=$row['expiredate'];
			if ($row['feed_id']) $feed_id=$row['feed_id'];
		}		
		
		#Call rss_feed
		$sql = 'SELECT * FROM rss_feed WHERE feed_id='.$feed_id;
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
		
		if ($query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
					
			$data = '<?xml version="1.0" encoding="UTF-8" ?>';
			$data .= '<rss version="2.0">';
			$data .= '<channel>';
			#$data .= '<title>'.$companyname.'</title>';
			#$data .= '<link>'.$website.'</link>';
			#$data .= '<description>Healthy Living Portal</description>';
			
			#rss items
			$data .= '<item>';
			$data .= '<title>'.encode_utf8(stripslashes($row['title'])).'</title>';
			#$data .= '<link>'.$row->shortlink.'</link>';
						
			$data .= '<description>HealthPlus presents Secret of Long Life and Healthy Living. Watch '.strtoupper(encode_utf8(stripslashes($row['title']))).' at '.$row['shortlink'].'</description>';
			
			$data .='<pubDate>'.date('D d M Y H:i:s O').'</pubDate>';
			$data .= '</item>';
			
			$data .='<image>';
			$data .='<url>https://s3-us-west-2.amazonaws.com/'.$thumb_bucket.'/'.$category.'/'.$row['thumbnail'].'</url>';
			$data .='</image>';
#https://s3-us-west-2.amazonaws.com/healthytips-thumbs/Health/mov.jpg	
			$data .= '</channel>';
			$data .= '</rss> ';
			
			#$file = fopen(str_replace('admin','',getcwd()).'rss.xml',"w"); fwrite($file, $data);  fclose($file);
			$file = fopen('rss.xml',"w"); fwrite($file, $data);  fclose($file);
			
			$ret=true;
		}else
		{
			$ret=CreateFeedSaveFeedSaveXML($db);
		}
	}else
	{
		$ret=CheckForActiveFeed($db);
	}
	
	$query->free();
	
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

function GetShortenUrl($longUrl,$db)
{	
	#$apiKey = 'AIzaSyDxzNLR5qXsGk03NLIFydtOpsit3GpE2EY'; 
	
	$apiKey='';
	
	$sql="SELECT google_shortener_api FROM settings";
		
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
	if ( $query->num_rows > 0 )  //Build Array of results
	{
		$row = $query->fetch_assoc();
		
		if ($row['google_shortener_api']) $apiKey = $row['google_shortener_api'];
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
		
		$query->free();
		
		return $json->id;	
	}else
	{
		$query->free();
		
		return $longUrl;
	}		
}

function GetNextID($table,$field,$db)
{
	if (!$table) return '';
	if (!$field) return '';
	
	$sql="SELECT MAX(`".$field."`) AS currentid FROM `".$table."`";
		
	if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
	if ( $query->num_rows > 0 )  //Build Array of results
	{
		$row = $query->fetch_assoc();
		
		if ($row)
		{
			$i=$row['currentid'] + 1;
			
			$query->free();
							
			return str_pad($i, 10, "0", STR_PAD_LEFT);
		}else
		{
			$query->free();
			
			return str_pad(1, 10, "0", STR_PAD_LEFT);
		}
	}else
	{
		$query->free();
		
		return str_pad(1, 10, "0", STR_PAD_LEFT);
	}	
}	

function LogDetails($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID,$db)
{
	try
	{
		if (trim(strtoupper($Operation))=='LOGOUT') $logoutdate=date('Y-m-d H:i:s'); else $logoutdate='';
		
		if (trim(strtoupper($Operation))=='LOGOUT')
		{
			$logdate=date('Y-m-d H:i:s');

			$db->autocommit(FALSE);
								
			$sql='UPDATE loginfo SET Activity=?,ActionDate=?,LogOutDate=?,Operation=?,remote_ip=?,remote_host=? WHERE LogID=?';
			
			$act=$db->escape_string($Activity);
			$actdt=$logdate;
			$lgout=$logdate;
			$op=$Operation;
			$ip=$ip;
			$rhs=$host;
			$lid=$LogID;
			 
			$stmt = $db->prepare($sql);/* Prepare statement */
			
			if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
			 
			/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
			$stmt->bind_param('sssssss',$act,$actdt,$lgout,$op,$ip,$rhs,$lid);

			$stmt->execute();/* Execute statement */
				
			$db->commit();
		}else
		{
			if (trim(strtoupper($Operation))=='LOGIN')
			{
				$db->autocommit(FALSE);
								
				$sql='INSERT INTO loginfo (LoginDate,Name,Activity,ActionDate,Username,Operation,LogID,remote_ip,remote_host) VALUES (?,?,?,?,?,?,?,?,?)';
				$ldt=$logdate;
				$nm=$db->escape_string($Name);
				$act=$db->escape_string($Activity);
				$actdt=$logdate;
				$unm=$db->escape_string($Username);
				$op=$Operation;
				$lid=$LogID;
				$rip=$ip;
				$rhs=$host;
				 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('sssssssss',$ldt,$nm,$act,$actdt,$unm,$op,$lid,$rip,$rhs);

				$stmt->execute();/* Execute statement */
					
				$db->commit();
			}else
			{
				$logdate=date('Y-m-d H:i:s');
				
				$db->autocommit(FALSE);
								
				$sql='INSERT INTO loginfo (LoginDate,Name,Activity,ActionDate,Username,Operation,LogID,remote_ip,remote_host) VALUES (?,?,?,?,?,?,?,?,?)';
				$ldt=$logdate;
				$nm=$db->escape_string($Name);
				$act=$db->escape_string($Activity);
				$actdt=$logdate;
				$unm=$db->escape_string($Username);
				$op=$Operation;
				$lid=$LogID;
				$rip=$ip;
				$rhs=$host;
				 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('sssssssss',$ldt,$nm,$act,$actdt,$unm,$op,$lid,$rip,$rhs);

				$stmt->execute();/* Execute statement */
					
				$db->commit();
			}		
		}
	}catch (Exception $e)
	{
		$db->rollback();
	}
}

$db->close();

?>