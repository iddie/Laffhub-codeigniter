<?php
	date_default_timezone_set('Africa/Lagos');
	
	#http://localhost/html-directory/receiver.php?msisdn=sender&message=sms&shortcode=sc 
	#http://54.175.220.214/incomingsms/receiver.php?msisdn=2348020566067&message=Laff&shortcode=2001
	
	#$file = fopen('aaa.txt',"a"); fwrite($file, "LANDED".PHP_EOL); fclose($file);
	
	$msisdn=''; $message=''; $shortcode='';
	
	#$db = new mysqli('localhost', 'root', '', 'laffhubdb');
	$db = new mysqli('localhost', 'laffhub_laffuser', 'vUzm6Nh^^y*v', 'laffhub_laffhubdb');
	
	try
	{
		if ((isset($_GET["msisdn"])) and (isset($_GET["message"])) and (isset($_GET["shortcode"]))) 
		{ 			
		  #Process
		  $msisdn=str_replace('+','',trim($_GET["msisdn"]));
		  $message=trim($_GET["message"]);
		  $shortcode=trim($_GET["shortcode"]);
		  $network='Airtel';
		  $reqdate=date('Y-m-d H:i:s');
		  
		  CheckSubscriptionDate($msisdn,$db);
		  
		  $subscriptionId=strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10));
		  
		 $file = fopen('messages.txt',"a"); fwrite($file, "MSISDN=".$msisdn." => SHORTCODE=".$shortcode." => Network=".$network." => Date=".$reqdate." => MESSAGE=".$message.PHP_EOL); fclose($file);
		 
		 SaveSmsRequest($network,$msisdn,$shortcode,$message,$reqdate,$db);
		 
		 $ret=CheckForBlackList($network,$msisdn,$db);
		 #$trial=IsNewSubscriber($db,$msisdn,$network);
		
		/*elseif (($trial==1) and ($shortcode=='2001'))#Trial Account
		{
			
		}*/
		
		if (($ret==true) and ($shortcode=='2001'))#Blaclisted Number
		{
			$msg='We are sorry, the phone number, '.$msisdn.', cannot subscribe to this service.';
			  
			$ret=SendAirtelSms($msisdn,$msg,$db);
		}elseif ((($shortcode=='2001') and (strtoupper($message)=='OUT')) or (($shortcode=='2001') and (strtoupper($message)=='STOP')))
		  {
			  	#Unsubscribe subscriber
			 	 #$file = fopen('aaa_OUT.txt',"a"); fwrite($file, "\nUnsubscribe ".$msisdn."  =>  ".date('d M Y H:i:s').PHP_EOL); fclose($file);
				 
			  	#Check if subscriber has active subscription
				$sql="SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$msisdn."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
					
					$subscriptionId=$row['subscriptionstatus'];
					$lastplan=$row['plan'];
							
					$dt=date('Y-m-d H:i:s');

					#Opt out
					$sql = "DELETE FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$msisdn."')";
					
					if ($db->query($sql) === TRUE)
					{
						#CANCELLED
						$cancelled='0';						
						
						$Msg='Subscriber with MSISDN, '.$msisdn.', has opted out of Laffhub service successfully.';
						$message = "Dear customer, you have unsubscribed from Laffhub service successfully. Text YES to 2001 to activate 7dys/15 videos. Service costs N100. NO DATA COST.";
						
						#Remove watchlist entry
						$sql = "DELETE FROM watchlists WHERE (TRIM(subscriptionId)='".$db->escape_string($subscriptionId)."')";
						$db->query($sql);
						
						#INSERT INTO optouts table
						$db->autocommit(FALSE);
								
						$sql='INSERT INTO optouts (network,msisdn,lastplan,optout_date) VALUES (?,?,?,?)';
						
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$pl=$db->escape_string($lastplan);
						
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('ssss',$nt,$ph,$pl,$dt);
		
						$stmt->execute();/* Execute statement */
							
						$db->commit();
					
						$ret=SendAirtelSms($msisdn,$message,$db);
					} else
					{
						$Msg='Unsubscription of '.$msisdn.' failed. '.$db->error; 
					}						
								
					
					$remote_ip=getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];
		
					#$host = $_SERVER['REMOTE_HOST'];
					$remote_host='';
					if ($remote_ip) $remote_host=gethostbyaddr($remote_ip);
						
#LogDetails($Name,$Activity,$Username,$logdate,$ip,$host,$Operation,$LogID,$db)					
					LogDetails($network.' LaffHub',$Msg,$msisdn,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'OPTED OUT OF '.strtoupper($network).' LAFFHUB','System',$db);					
				}else
				{
					$message = "Your attempt to opt out from Laffhub failed. You have no subscription on Laffhub service. Text YES to 2001 to activate 7days/15 videos. Service costs N100.";
						
					$ret=SendAirtelSms($msisdn,$message,$db);
				}
		  }elseif ((($shortcode=='2001') and (strtoupper($message)=='COMEDY')) or (($shortcode=='2001') and (strtoupper($message)=='YES')) or (($shortcode=='2001') and (strtoupper($message)=='OKAY')) or (($shortcode=='2001') and (strtoupper($message)=='OK')))#Activation Of N100/Weekly
		  {#WEEKLY
			$subscription_msg=''; $amount=''; $insufficent_balance_msg=''; $wrong_keyword_msg='';
			$subscriptiondays=''; $amount=''; $autobilling='1'; $videos_cnt_to_watch=''; $email='';
			$subscriptionstatus='1'; $watched='0';
			
			$plan='Weekly';
			
			//Check if record exists
			$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (subscriptionstatus=1) AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			$ret=''; $watched=0; $maxwatch=0; $expdt=''; $activeplan=''; $flag=false; $ex='';
			
			#There is active subscription
			if ($query->num_rows > 0 )
			{
				$row = $query->fetch_assoc();
				
				if ($row['exp_date'])
				{
					$expdt=date('d M Y @ H:i',strtotime($row['exp_date']));
					$ex=date('Y-m-d H:i',strtotime($row['exp_date']));
				}
				
				if ($row['plan']) $activeplan=trim($row['plan']);			
				if ($row['videos_cnt_watched']) $watched=intval($row['videos_cnt_watched']);
				if ($row['videos_cnt_to_watch']) $maxwatch=$row['videos_cnt_to_watch'];
							
				$ret="You currently have an active subscription to this service which will expire on ".$expdt.". Visit www.laffhub.com to enjoy your videos.";
		
				$Msg="Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$activeplan."; Expiry Date => ".$expdt;
				
				#There is active subscription
				$ret=SendAirtelSms($msisdn,$ret,$db);
			}else
			{
				#Subscribe
				#Get amount and duration
				$sql="SELECT price,duration FROM prices WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['price']) $amount = $row['price'];
					if ($row['duration']) $subscriptiondays = $row['duration'];
				}
				
				#Get Messages
				$sql="SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['subscription']) $subscription_msg = $row['subscription'];
					if ($row['insufficent_balance']) $insufficent_balance_msg = $row['insufficent_balance'];
					if ($row['wrong_keyword']) $wrong_keyword_msg = $row['wrong_keyword'];
				}
				
				$subscribe_date = date('Y-m-d H:i:s');
				$exp_date=date('Y-m-d H:i:s',strtotime("+".$subscriptiondays." days",strtotime($subscribe_date)));
				
				$eventType='Subscription Purchase'; #ReSubscription
				
				##################### GET TOTAL VIDEOS TO WATCH
				$sql="SELECT no_of_videos FROM plans WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['no_of_videos']) $videos_cnt_to_watch = $row['no_of_videos'];
				}
				
				$transid=''; $cptransid=''; $subscription_message=''; $errorCode=''; 
				$errorMessage=''; $subscription_status=''; $billing_status='';
	
				$ret=BillAirtelSubscriber($msisdn,$amount,$subscriptiondays,$eventType,$db);
				
				if ($ret['TransId']) $transid=$ret['TransId'];
				if ($ret['cpTransId']) $cptransid=$ret['cpTransId'];
				
				if ($ret['errorMessage'])
				{
					$subscription_message=$ret['errorMessage'];
				}else
				{
					if (trim(strtoupper($ret['Status']))=='OK') $subscription_message='Successful';
				}
				
				if ($ret['errorCode']) $errorCode=$ret['errorCode'];
				if ($ret['Status']) $subscription_status=$ret['Status'];
				
				if (trim(strtoupper($subscription_status))=='OK')
				{
					#Add entry to accounts table
					$db->autocommit(FALSE);
							
					$sql='INSERT INTO accounts (email,network,msisdn,plan,duration,amount,paymentdate,subscriptionId) VALUES (?,?,?,?,?,?,?,?)';
					
					$em=$db->escape_string($email);
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$du=$db->escape_string($subscriptiondays);#duration
					$amt=$db->escape_string($amount);
					$pdt=$db->escape_string($subscribe_date);
					$sid=$db->escape_string($subscriptionId);
										 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('ssssidss',$em,$nt,$ph,$pl,$du,$amt,$pdt,$sid);
	
					$stmt->execute();/* Execute statement */
						
					$db->commit();
					
					
					#Save to subscriptions table
					$result=SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db);

#$file = fopen('aaa_SUB.txt',"a"); fwrite($file, "\n\nSUCCESS\nStatus=".$ret['Status']."\nResult=".$result."MSISDN=".$msisdn."\nPlan=".$plan."\nAmount=".$amount.PHP_EOL); fclose($file);

					 #Send Message - Success					 
					 if (trim(strtoupper($result))=='OK')
					 {
						 #$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
					 }else
					 {
						 #$ret=SendAirtelSms($msisdn,$ret,$db);
					 }
				}elseif (trim(strtoupper($ret['Status']))=='FAILED')
				{#Send Message
					if (trim(strtoupper($ret['errorCode']))=='OL404')
					{
						$bal=floatval(str_replace('Insufficient Balance.#~#','',$ret['errorMessage']));
						
						$ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);
					}else
					{
						$ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
					}
				}	
			}
		  }elseif (($shortcode=='2001') and (strtoupper($message)=='DAY'))#Activation Of N20/Daily
		  {#DAY		  
			  	$subscription_msg=''; $amount=''; $insufficent_balance_msg=''; $wrong_keyword_msg='';
				$subscriptiondays=''; $amount=''; $autobilling='1'; $videos_cnt_to_watch=''; $email='';
				$subscriptionstatus='1'; $watched='0';
				
				$plan='Daily';
				
				//Check if record exists
				$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (subscriptionstatus=1) AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
				
				if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
				
				$ret=''; $watched=0; $maxwatch=0; $expdt=''; $activeplan=''; $flag=false; $ex='';
				
				#There is active subscription
				if ($query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
					
					if ($row['exp_date'])
					{
						$expdt=date('d M Y @ H:i',strtotime($row['exp_date']));
						$ex=date('Y-m-d H:i',strtotime($row['exp_date']));
					}
					
					if ($row['plan']) $activeplan=trim($row['plan']);			
					if ($row['videos_cnt_watched']) $watched=intval($row['videos_cnt_watched']);
					if ($row['videos_cnt_to_watch']) $maxwatch=$row['videos_cnt_to_watch'];
								
					$ret="You currently have an active subscription to this service which will expire on ".$expdt.". Visit www.laffhub.com to enjoy your videos.";
			
					$Msg="Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$activeplan."; Expiry Date => ".$expdt;
					
					#There is active subscription
					$ret=SendAirtelSms($msisdn,$ret,$db);
				}else
				{
					#Subscribe
					#Get amount and duration
					$sql="SELECT price,duration FROM prices WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
			
					if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
					
					if ( $query->num_rows > 0 )
					{
						$row = $query->fetch_assoc();
								
						if ($row['price']) $amount = $row['price'];
						if ($row['duration']) $subscriptiondays = $row['duration'];
					}
					
					#Get Messages
					$sql="SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
			
					if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
					
					if ( $query->num_rows > 0 )
					{
						$row = $query->fetch_assoc();
								
						if ($row['subscription']) $subscription_msg = $row['subscription'];
						if ($row['insufficent_balance']) $insufficent_balance_msg = $row['insufficent_balance'];
						if ($row['wrong_keyword']) $wrong_keyword_msg = $row['wrong_keyword'];
					}
					
					$subscribe_date = date('Y-m-d H:i:s');
					$exp_date=date('Y-m-d H:i:s',strtotime("+".$subscriptiondays." days",strtotime($subscribe_date)));
					
					$eventType='Subscription Purchase'; #ReSubscription
					
					##################### GET TOTAL VIDEOS TO WATCH
					$sql="SELECT no_of_videos FROM plans WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
			
					if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
					
					if ( $query->num_rows > 0 )
					{
						$row = $query->fetch_assoc();
								
						if ($row['no_of_videos']) $videos_cnt_to_watch = $row['no_of_videos'];
					}
					
					$transid=''; $cptransid=''; $subscription_message=''; $errorCode=''; 
					$errorMessage=''; $subscription_status=''; $billing_status='';
	
					$ret=BillAirtelSubscriber($msisdn,$amount,$subscriptiondays,$eventType,$db);
					
					if ($ret['TransId']) $transid=$ret['TransId'];
					if ($ret['cpTransId']) $cptransid=$ret['cpTransId'];
					
					if ($ret['errorMessage'])
					{
						$subscription_message=$ret['errorMessage'];
					}else
					{
						if (trim(strtoupper($ret['Status']))=='OK') $subscription_message='Successful';
					}
					
					if ($ret['errorCode']) $errorCode=$ret['errorCode'];
					if ($ret['Status']) $subscription_status=$ret['Status'];
					
					if (trim(strtoupper($subscription_status))=='OK')
					{
						#Add entry to accounts table
						$db->autocommit(FALSE);
								
						$sql='INSERT INTO accounts (email,network,msisdn,plan,duration,amount,paymentdate,subscriptionId) VALUES (?,?,?,?,?,?,?,?)';
						
						$em=$db->escape_string($email);
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$pl=$db->escape_string($plan);
						$du=$db->escape_string($subscriptiondays);#duration
						$amt=$db->escape_string($amount);
						$pdt=$db->escape_string($subscribe_date);
						$sid=$db->escape_string($subscriptionId);
											 
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('ssssidss',$em,$nt,$ph,$pl,$du,$amt,$pdt,$sid);
		
						$stmt->execute();/* Execute statement */
							
						$db->commit();
						
						
						#Save to subscriptions table
						$result=SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db);
		
	#$file = fopen('aaa_SUB.txt',"a"); fwrite($file, "\n\nSUCCESS\nStatus=".$ret['Status']."\nResult=".$result."MSISDN=".$msisdn."\nPlan=".$plan."\nAmount=".$amount.PHP_EOL); fclose($file);
	
						 #Send Message - Success					 
						 if (trim(strtoupper($result))=='OK')
						 {
							 #$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
						 }else
						 {
							 #$ret=SendAirtelSms($msisdn,$ret,$db);
						 }
					}elseif (trim(strtoupper($ret['Status']))=='FAILED')
					{#Send Message
						if (trim(strtoupper($ret['errorCode']))=='OL404')
						{
							$bal=floatval(str_replace('Insufficient Balance.#~#','',$ret['errorMessage']));
							
							$ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);
						}else
						{
							$ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
						}
					}	
				}
				
#return array('Status' => 'OK','errorCode' => '','errorMessage' =>'', 'TransId' => '','cpTransId' => '');

#return array('Status' => 'FAILED','errorCode' => '','errorMessage' =>$errormsg, 'TransId' => $transid,'cpTransId' => $cptransid);

#$file = fopen('aaa_Bill.txt',"a"); fwrite($file, "Status=".$ret['Status']."\nTrans Id=".$ret['TransId']."\nCp Trans ID=".$ret['cpTransId']."\nError Code=".$ret['errorCode']."\nError Message=".$ret['errorMessage'].PHP_EOL); fclose($file);
								
				
		  }elseif (($shortcode=='2001') and (strtoupper($message)=='MONTH'))#Activation Of N200/Daily
		  {#MONTH		  
			$subscription_msg=''; $amount=''; $insufficent_balance_msg=''; $wrong_keyword_msg='';
			$subscriptiondays=''; $amount=''; $autobilling='1'; $videos_cnt_to_watch=''; $email='';
			$subscriptionstatus='1'; $watched='0';
			
			$plan='Monthly';
			
			//Check if record exists
			$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (subscriptionstatus=1) AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			$ret=''; $watched=0; $maxwatch=0; $expdt=''; $activeplan=''; $flag=false; $ex='';
			
			#There is active subscription
			if ($query->num_rows > 0 )
			{
				$row = $query->fetch_assoc();
				
				if ($row['exp_date'])
				{
					$expdt=date('d M Y @ H:i',strtotime($row['exp_date']));
					$ex=date('Y-m-d H:i',strtotime($row['exp_date']));
				}
				
				if ($row['plan']) $activeplan=trim($row['plan']);			
				if ($row['videos_cnt_watched']) $watched=intval($row['videos_cnt_watched']);
				if ($row['videos_cnt_to_watch']) $maxwatch=$row['videos_cnt_to_watch'];
							
				$ret="You currently have an active subscription to this service which will expire on ".$expdt.". Visit www.laffhub.com to enjoy your videos.";
		
				$Msg="Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$activeplan."; Expiry Date => ".$expdt;
				
				#There is active subscription
				$ret=SendAirtelSms($msisdn,$ret,$db);
			}else
			{
				#Subscribe
				#Get amount and duration
				$sql="SELECT price,duration FROM prices WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['price']) $amount = $row['price'];
					if ($row['duration']) $subscriptiondays = $row['duration'];
				}
				
				#Get Messages
				$sql="SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['subscription']) $subscription_msg = $row['subscription'];
					if ($row['insufficent_balance']) $insufficent_balance_msg = $row['insufficent_balance'];
					if ($row['wrong_keyword']) $wrong_keyword_msg = $row['wrong_keyword'];
				}
				
				$subscribe_date = date('Y-m-d H:i:s');
				$exp_date=date('Y-m-d H:i:s',strtotime("+".$subscriptiondays." days",strtotime($subscribe_date)));
				
				$eventType='Subscription Purchase'; #ReSubscription
				
				##################### GET TOTAL VIDEOS TO WATCH
				$sql="SELECT no_of_videos FROM plans WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['no_of_videos']) $videos_cnt_to_watch = $row['no_of_videos'];
				}
				
				$transid=''; $cptransid=''; $subscription_message=''; $errorCode=''; 
				$errorMessage=''; $subscription_status=''; $billing_status='';
	
				$ret=BillAirtelSubscriber($msisdn,$amount,$subscriptiondays,$eventType,$db);
				
				if ($ret['TransId']) $transid=$ret['TransId'];
				if ($ret['cpTransId']) $cptransid=$ret['cpTransId'];
				
				if ($ret['errorMessage'])
				{
					$subscription_message=$ret['errorMessage'];
				}else
				{
					if (trim(strtoupper($ret['Status']))=='OK') $subscription_message='Successful';
				}
				
				if ($ret['errorCode']) $errorCode=$ret['errorCode'];
				if ($ret['Status']) $subscription_status=$ret['Status'];
				
				if (trim(strtoupper($subscription_status))=='OK')
				{
					#Add entry to accounts table
					$db->autocommit(FALSE);
							
					$sql='INSERT INTO accounts (email,network,msisdn,plan,duration,amount,paymentdate,subscriptionId) VALUES (?,?,?,?,?,?,?,?)';
					
					$em=$db->escape_string($email);
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$du=$db->escape_string($subscriptiondays);#duration
					$amt=$db->escape_string($amount);
					$pdt=$db->escape_string($subscribe_date);
					$sid=$db->escape_string($subscriptionId);
															 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('ssssidss',$em,$nt,$ph,$pl,$du,$amt,$pdt,$sid);
	
					$stmt->execute();/* Execute statement */
						
					$db->commit();
					
					
					#Save to subscriptions table
					$result=SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db);

					 #Send Message - Success					 
					 if (trim(strtoupper($result))=='OK')
					 {
						 #$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
					 }else
					 {
						 #$ret=SendAirtelSms($msisdn,$ret,$db);
					 }
				}elseif (trim(strtoupper($ret['Status']))=='FAILED')
				{#Send Message
					if (trim(strtoupper($ret['errorCode']))=='OL404')
					{
						$bal=floatval(str_replace('Insufficient Balance.#~#','',$ret['errorMessage']));
						
						$ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);
					}else
					{
						$ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
					}
				}	
			}
		  }elseif (($shortcode=='2001') and (strtoupper($message)=='UNLIMITED'))#Activation Of N500/UNLIMITED
		  {#UNLIMITED		  
			$subscription_msg=''; $amount=''; $insufficent_balance_msg=''; $wrong_keyword_msg='';
			$subscriptiondays=''; $amount=''; $autobilling='1'; $videos_cnt_to_watch=''; $email='';
			$subscriptionstatus='1'; $watched='0';
			
			$plan='Unlimited';
			
			//Check if record exists
			$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (subscriptionstatus=1) AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			$ret=''; $watched=0; $maxwatch=0; $expdt=''; $activeplan=''; $flag=false; $ex='';
			
			#There is active subscription
			if ($query->num_rows > 0 )
			{
				$row = $query->fetch_assoc();
				
				if ($row['exp_date'])
				{
					$expdt=date('d M Y @ H:i',strtotime($row['exp_date']));
					$ex=date('Y-m-d H:i',strtotime($row['exp_date']));
				}
				
				if ($row['plan']) $activeplan=trim($row['plan']);			
				if ($row['videos_cnt_watched']) $watched=intval($row['videos_cnt_watched']);
				if ($row['videos_cnt_to_watch']) $maxwatch=$row['videos_cnt_to_watch'];
							
				$ret="You currently have an active subscription to this service which will expire on ".$expdt.". Visit www.laffhub.com to enjoy your videos.";
		
				$Msg="Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$activeplan."; Expiry Date => ".$expdt;
				
				#There is active subscription
				$ret=SendAirtelSms($msisdn,$ret,$db);
			}else
			{
				#Subscribe
				#Get amount and duration
				$sql="SELECT price,duration FROM prices WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['price']) $amount = $row['price'];
					if ($row['duration']) $subscriptiondays = $row['duration'];
				}
				
				#Get Messages
				$sql="SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['subscription']) $subscription_msg = $row['subscription'];
					if ($row['insufficent_balance']) $insufficent_balance_msg = $row['insufficent_balance'];
					if ($row['wrong_keyword']) $wrong_keyword_msg = $row['wrong_keyword'];
				}
				
				$subscribe_date = date('Y-m-d H:i:s');
				$exp_date=date('Y-m-d H:i:s',strtotime("+".$subscriptiondays." days",strtotime($subscribe_date)));
				
				$eventType='Subscription Purchase'; #ReSubscription
				
				##################### GET TOTAL VIDEOS TO WATCH
				$sql="SELECT no_of_videos FROM plans WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$plan."')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['no_of_videos']) $videos_cnt_to_watch = $row['no_of_videos'];
				}
				
				$transid=''; $cptransid=''; $subscription_message=''; $errorCode=''; 
				$errorMessage=''; $subscription_status=''; $billing_status='';
	
				$ret=BillAirtelSubscriber($msisdn,$amount,$subscriptiondays,$eventType,$db);
				
				if ($ret['TransId']) $transid=$ret['TransId'];
				if ($ret['cpTransId']) $cptransid=$ret['cpTransId'];
				
				if ($ret['errorMessage'])
				{
					$subscription_message=$ret['errorMessage'];
				}else
				{
					if (trim(strtoupper($ret['Status']))=='OK') $subscription_message='Successful';
				}
				
				if ($ret['errorCode']) $errorCode=$ret['errorCode'];
				if ($ret['Status']) $subscription_status=$ret['Status'];
				
				if (trim(strtoupper($subscription_status))=='OK')
				{
					#Add entry to accounts table
					$db->autocommit(FALSE);
							
					$sql='INSERT INTO accounts (email,network,msisdn,plan,duration,amount,paymentdate,subscriptionId) VALUES (?,?,?,?,?,?,?,?)';
					
					$em=$db->escape_string($email);
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$du=$db->escape_string($subscriptiondays);#duration
					$amt=$db->escape_string($amount);
					$pdt=$db->escape_string($subscribe_date);
					$sid=$db->escape_string($subscriptionId);
										 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('ssssidss',$em,$nt,$ph,$pl,$du,$amt,$pdt,$sid);
	
					$stmt->execute();/* Execute statement */
						
					$db->commit();
					
					
					#Save to subscriptions table
					$result=SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db);


#$file = fopen('aaa_SUB.txt',"a"); fwrite($file, "SUCCESS\nStatus=".$ret['Status']."\nResult=".$result."\nMSISDN=".$msisdn."\nPlan=".$plan."\nAmount=".$amount.PHP_EOL); fclose($file);

					 #Send Message - Success					 
					 if (trim(strtoupper($result))=='OK')
					 {
						 #$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
					 }else
					 {
						 #$ret=SendAirtelSms($msisdn,$ret,$db);
					 }
				}elseif (trim(strtoupper($ret['Status']))=='FAILED')
				{#Send Message
					if (trim(strtoupper($ret['errorCode']))=='OL404')
					{
						$bal=floatval(str_replace('Insufficient Balance.#~#','',$ret['errorMessage']));
						
						$ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);
					}else
					{
						$ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
					}
				}	
			}
		  }elseif (($shortcode=='2001') and (strtoupper($message)=='HELP'))#Help
		  {		  
			  	$subscription_msg='Text DAY to 2001 for Daily Plan, COMEDY to 2001 for Weekly plan, MONTH to 2001 for Monthly Plan, UNLIMITED to 2001 for unlimited Plan,OUT to 2001 to unsubscribe';
							  
			  	$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
		  }else#Unknown Keyword
		  {
			  	$wrong_keyword_msg='';
					  	
				$sql="SELECT * FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='Daily')";
		
				if(!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
				if ( $query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
							
					if ($row['wrong_keyword']) $wrong_keyword_msg = $row['wrong_keyword'];
				}
				
				$ret=SendAirtelSms($msisdn,$wrong_keyword_msg,$db);
		  }
	   }else
	   {
		   echo 'No Values Sent.';
	   }
	}catch(Exception $e) {
	  echo 'ERROR MESSAGE: ' .$e->getMessage();
	}
	
	
	#********************************** FUNCTIONS ***********************************************************
	#********************************************************************************************************
	#********************************************************************************************************
	function UpdateActiveSubscriptions($db)
	{	
		$sql="UPDATE subscriptions SET subscriptionstatus=1 WHERE (DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') >  NOW())";
		 
		if (!$query = $db->query($sql))
		{
			$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,UpdateActiveSubscriptions,ERROR=>".$db->error.PHP_EOL); fclose($file);
		}
		
		return true;
	}

	function UpdateExpiredSubscriptions($db)
	{	
		$sql="UPDATE subscriptions SET subscriptionstatus=0 WHERE (DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= NOW())";
		 
		if (!$query = $db->query($sql))
		{
			$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,UpdateExpiredSubscriptions,ERROR=>".$db->error.PHP_EOL); fclose($file);
		}
		
		return true;
	}

	#NEW - Captured at subscription (Portal and SMS)
	function IsNewSubscriber($db,$msisdn,$network)
	{
		$sql="SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
	
		if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
				
		if ($query->num_rows > 0 )
		{
			return 0; 
		}else
		{
			#Check blacklist
			$sql="SELECT * FROM blacklist WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
	
			if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			if ($query->num_rows > 0)
			{
				return 0; 
			}else
			{
				#Check optouts
				$sql="SELECT * FROM optouts WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
				
				if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
				if ($query->num_rows > 0)
				{
					return 0;
				}else
				{
					#Check freetrials
					$sql="SELECT * FROM freetrials WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
					
					if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
					
					if ($query->num_rows > 0) return 0; else return 1;
				}
			}
		}
	}
	
	#MAIN AIRTEL SUBSCRIPTION MODULE
	function SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db)
	{
		$duration=$subscriptiondays; $Msg=''; $dt=date('Y-m-d H:i'); $new=0;
		
		$sentmessage='';
		$eventType='Subscription Purchase'; #ReSubscription
		
		$new=IsNewSubscriber($db,$msisdn,$network);
		
		if ($new==1)
		{
			$subscriptiondays += 2;
			$duration=$subscriptiondays;
			$videos_cnt_to_watch += 3;
		}
		
		if (trim(strtoupper($subscription_status))=='OK')
		{#- array('Status' => 'OK','errorCode' => '','errorMessage' =>'', 'TransId' => $transid,'cpTransId' => $cptransid);
					
			########################### DAILY REPORT FUNCTIONS #######################
			#NEW - Captured at subscription (Portal and SMS)			
			if ($new==1)
			{				
				#Update new_subscriptions
				$sql="SELECT msisdn FROM new_subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";
							
				if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

				if ($query->num_rows > 0 )#There is active subscription
				{
					$db->autocommit(FALSE);
					$sql="UPDATE new_subscriptions SET plan=?,subscriptiondate=? WHERE (TRIM(network)=?) AND (TRIM(msisdn)=?) AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')=?)";
			
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$sdt=$db->escape_string($subscribe_date);
					$sd=date('Y-m-d');
					 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('sssss',$pl,$sdt,$nt,$ph,$sd);
					$stmt->execute();/* Execute statement */				
					$db->commit();
				}else
				{
					$db->autocommit(FALSE);
					
					$sql='INSERT INTO new_subscriptions (network,msisdn,plan,email,subscriptiondate) VALUES (?,?,?,?,?)';
					
					$em=$db->escape_string($email);
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$sdt=$db->escape_string($subscribe_date);
									 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('sssss',$nt,$ph,$pl,$em,$sdt); 
					$stmt->execute();/* Execute statement */				
					$db->commit();	
				}				
			}
			
			
			#Update successful_charging
			$sql="SELECT msisdn FROM successful_charging WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')='".date('Y-m-d')."')";
							
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

			if ($query->num_rows > 0 )#There is active subscription
			{
				$db->autocommit(FALSE);
				$sql="UPDATE successful_charging SET plan=?,subscriptiondate=? WHERE (TRIM(network)=?) AND (TRIM(msisdn)=?) AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d')=?)";
		
				$nt=$db->escape_string($network);
				$ph=$db->escape_string($msisdn);
				$pl=$db->escape_string($plan);
				$sdt=$db->escape_string($subscribe_date);
				$sd=date('Y-m-d');
				 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('sssss',$pl,$sdt,$nt,$ph,$sd);
				$stmt->execute();/* Execute statement */				
				$db->commit();
			}else
			{
				$db->autocommit(FALSE);
				
				$sql='INSERT INTO successful_charging (network,msisdn,plan,email,subscriptiondate) VALUES (?,?,?,?,?)';
				
				$em=$db->escape_string($email);
				$nt=$db->escape_string($network);
				$ph=$db->escape_string($msisdn);
				$pl=$db->escape_string($plan);
				$sdt=$db->escape_string($subscribe_date);
								 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('sssss',$nt,$ph,$pl,$em,$sdt); 
				$stmt->execute();/* Execute statement */				
				$db->commit();	
			}
					
			########################### DAILY REPORT FUNCTIONS #######################
			
			
		
			#Create Subscription Record
			$db->autocommit(FALSE);
			
			$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			if ($query->num_rows > 0 )#There is active subscription
			{
				$row = $query->fetch_assoc();
				
				if ($row['subscriptionstatus']==0)
				{
					$sql='UPDATE subscriptions SET email=?,plan=?,duration=?,amount=?,autobilling=?,subscribe_date=?,exp_date=?,videos_cnt_watched=?,videos_cnt_to_watch=?,subscriptionstatus=?,subscriptionId=? WHERE TRIM(network)=? AND TRIM(msisdn)=?';
			
					$em=$db->escape_string($email);
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$du=$db->escape_string($subscriptiondays);#duration
					$amt=$db->escape_string($amount);
					$au=$db->escape_string($autobilling);
					$sdt=$db->escape_string($subscribe_date);
					$edt=$db->escape_string($exp_date);
					$wa=$db->escape_string($watched);
					$mxv=$db->escape_string($videos_cnt_to_watch);
					$sta=$db->escape_string($subscriptionstatus);
					$sid=$db->escape_string($subscriptionId);
					 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('ssidissiiisss',$em,$pl,$du,$amt,$au,$sdt,$edt,$wa,$mxv,$sta,$sid,$nt,$ph);
				}
			}else
			{
				$sql='INSERT INTO subscriptions (email,network,msisdn,plan,duration,amount,autobilling,subscribe_date,exp_date,videos_cnt_watched,videos_cnt_to_watch,subscriptionstatus,subscriptionId) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
			
				$em=$db->escape_string($email);
				$nt=$db->escape_string($network);
				$ph=$db->escape_string($msisdn);
				$pl=$db->escape_string($plan);
				$du=$db->escape_string($subscriptiondays);#duration
				$amt=$db->escape_string($amount);
				$au=$db->escape_string($autobilling);
				$sdt=$db->escape_string($subscribe_date);
				$edt=$db->escape_string($exp_date);
				$wa=$db->escape_string($watched);
				$mxv=$db->escape_string($videos_cnt_to_watch);
				$sta=$db->escape_string($subscriptionstatus);
				$sid=$db->escape_string($subscriptionId);
				 
				$stmt = $db->prepare($sql);/* Prepare statement */
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
				 
				/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
				$stmt->bind_param('ssssidissisis',$em,$nt,$ph,$pl,$du,$amt,$au,$sdt,$edt,$wa,$mxv,$sta,$sid); 	
			}

			$stmt->execute();/* Execute statement */				
			$db->commit();
			
			#########################
			#Create record in watchlists table			
			$db->autocommit(FALSE);
			
			$sql='INSERT INTO watchlists (subscriptionId,videolist) VALUES (?,?)';
			
			$sid=$db->escape_string($subscriptionId);
			$vid='';
				 
			$stmt = $db->prepare($sql);/* Prepare statement */
				
			if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
				 
			/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
			$stmt->bind_param('ss',$sid,$vid); 
				
			$stmt->execute();/* Execute statement */
				
			$db->commit();
			
			
			#Remove from optouts
			$sql = "SELECT * FROM optouts WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			if ($query->num_rows > 0 )#There is active subscription
			{
				$sql = "DELETE FROM optouts WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
				$db->query($sql);
			}
			################## END REMOVE		
			
			$Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$subscriptiondays."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
			
			$ret='OK';
						
			#GET SUBSCRIPTION MESSAGE
			$sql = "SELECT subscription,fallback_notice FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$db->escape_string($plan)."') ";
			
			if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
			
			if ($query->num_rows > 0 )
			{
				$row = $query->fetch_assoc();
				
				if ($row['subscription']) $sentmessage=$row['subscription'];
				
				if (!$sentmessage)
				{
					if ($row['fallback_notice']) $sentmessage=$row['fallback_notice'];
				}
			}
			
			if (!$sentmessage) $sentmessage='You have been charged N'.$amount.' for '.$plan.' Laffhub service. Visit www.laffhub.com. NO DATA COST. To opt out, text OUT to 2001.';
			
			#Send Message - Success
			 $result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
			 
#$file = fopen('aaa_SUB.txt',"a"); fwrite($file, "SUCCESS\nSent Message=".$sentmessage."\nMSISDN=".$msisdn."\nMsg Status=".$result_msg['Status'].PHP_EOL); fclose($file);				 
			 
			 if (strtoupper(trim($result_msg['Status']))<>'OK')
			 {
				 $ret='Subscription was successful but sms could not be delivered to your phone immediately. Below is your sms delivery response message: '.$result_msg['Msg'];
				 
				 $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
			 }				 
		}elseif (trim(strtoupper($billing_status))=='FAILED')#Subscription Failed
		{#- array('Status' => 'FAILED','errorCode' => $errorcode,'errorMessage' =>$errormsg, 'TransId' => $transid,'cpTransId' => $cptransid);
			
			########################### DAILY REPORT FUNCTIONS #######################
			#FAILED ACTIVATIONS - If new subscriber
			#$failedactivations=IsFailedActivation($db,$msisdn,$network,$substatus);
			if ($new==1) 
			{				
				#####################################
				#Insert into failed activation table
				#####################################
				$sql="SELECT msisdn FROM failed_activations WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='".date('Y-m-d')."')";
							
				if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

				if ($query->num_rows > 0 )#There is active subscription
				{
					$db->autocommit(FALSE);
					$sql="UPDATE failed_activations SET plan=?,activationdate=? WHERE (TRIM(network)=?) AND (TRIM(msisdn)=?) AND (DATE_FORMAT(activationdate,'%Y-%m-%d')=?)";
			
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$pl=$db->escape_string($plan);
					$adt==$db->escape_string($subscribe_date);
					$ad=date('Y-m-d');
					 
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('sssss',$pl,$adt,$nt,$ph,$ad);
					$stmt->execute();/* Execute statement */				
					$db->commit();
				}else
				{
					$db->autocommit(FALSE);
					
					$sql='INSERT INTO failed_activations (network,msisdn,email,plan,activationdate) VALUES (?,?,?,?,?)';
					$nt=$db->escape_string($network);
					$ph=$db->escape_string($msisdn);
					$em='';
					$pl=$db->escape_string($plan);
					$adt=$db->escape_string($subscribe_date);
										
					$stmt = $db->prepare($sql);/* Prepare statement */
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
					/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
					$stmt->bind_param('sssss',$nt,$ph,$em,$pl,$adt);	
					$stmt->execute();/* Execute statement */				
					$db->commit();	
				}	
			}else
			{
				$new='0';
				
				if (trim($errorCode)<>'OL404')
				{
					#####################################
					#Insert into cust_failed_charging table
					#####################################
					$sql="SELECT msisdn FROM cust_failed_charging WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='".date('Y-m-d')."')";
							
					if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
	
					if ($query->num_rows > 0 )#There is active subscription
					{
						$db->autocommit(FALSE);
						$sql="UPDATE cust_failed_charging SET plan=?,chargingdate=? WHERE (TRIM(network)=?) AND (TRIM(msisdn)=?) AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')=?)";
				
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$pl=$db->escape_string($plan);
						$adt=date('Y-m-d H:i:s');
						$ad=date('Y-m-d');
						 
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('sssss',$pl,$adt,$nt,$ph,$ad);
						$stmt->execute();/* Execute statement */				
						$db->commit();
					}else
					{
						$db->autocommit(FALSE);
						
						$sql='INSERT INTO cust_failed_charging (network,msisdn,email,plan,chargingdate) VALUES (?,?,?,?,?)';
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$em='';
						$pl=$db->escape_string($plan);
						$adt=date('Y-m-d H:i:s');
											
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('sssss',$nt,$ph,$em,$pl,$adt);								
						$stmt->execute();/* Execute statement */				
						$db->commit();	
					}
				}else
				{
					#GREY AREA - Captured at subscription (Portal and SMS) -> Failed charging bcos of no credit
					#if ((trim($errorCode)=='OL404') and ($new==0)) $greyarea=1;
					$greyarea='0';
					
					#####################################
					#Update greyareas table
					#####################################
					$sql="SELECT msisdn FROM greyareas WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='".date('Y-m-d')."')";
							
					if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
	
					if ($query->num_rows > 0 )#There is active subscription
					{
						$db->autocommit(FALSE);
						$sql="UPDATE greyareas SET plan=?,chargingdate=? WHERE (TRIM(network)=?) AND (TRIM(msisdn)=?) AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')=?)";
				
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$pl=$db->escape_string($plan);
						$adt=date('Y-m-d H:i:s');
						$ad=date('Y-m-d');
						 
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('sssss',$pl,$adt,$nt,$ph,$ad);
						$stmt->execute();/* Execute statement */				
						$db->commit();
					}else
					{
						$db->autocommit(FALSE);
					
						$sql='INSERT INTO greyareas (network,msisdn,email,plan,chargingdate) VALUES (?,?,?,?,?)';
						$nt=$db->escape_string($network);
						$ph=$db->escape_string($msisdn);
						$em='';
						$pl=$db->escape_string($plan);
						$adt=date('Y-m-d H:i:s');
											
						$stmt = $db->prepare($sql);/* Prepare statement */
						
						if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
						 
						/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
						$stmt->bind_param('sssss',$nt,$ph,$em,$pl,$adt); 
						$stmt->execute();/* Execute statement */				
						$db->commit();	
					}
				}
				
			}
			
			
			########################### DAILY REPORT FUNCTIONS #######################
									
			#Send Message
			if (trim($errorCode)=='OL404')
			{		
				$bal=floatval(str_replace('Insufficient Balance.#~#','',$errorMessage));
				
				#GET INSUFFICIENT BALANCE MESSAGE
				$sql = "SELECT insufficent_balance FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$db->escape_string($plan)."') ";
				
				if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
				
				if ($query->num_rows > 0 )
				{
					$row = $query->fetch_assoc();
					if ($row['insufficent_balance']) $sentmessage=$row['insufficent_balance'];
				}
				
				#Get Keyword
				$key='';
				
				if (strtolower(trim($plan))=='monthly') $key='MONTH';
				if (strtolower(trim($plan))=='weekly') $key='YES';
				if (strtolower(trim($plan))=='daily') $key='DAY';
				if (strtolower(trim($plan))=='unlimited') $key='UNLIMITED';
				
				if (!$sentmessage) $sentmessage='Laffhub '.trim($plan).' subscription service could not be activated due to insufficient airtime.Recharge & SMS '.$key.' to 2001.Service cost N'.trim($amount).'/'.$subscriptiondays.'days.NO DATA COST';
				
				#Send Message - Success
				 $result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
				 
				 if (strtoupper(trim($result_msg['Status']))=='OK')
				 {
					 $sentmessage=trim($sentmessage);
					 
					 if ($sentmessage[strlen($sentmessage)-1] <> '.') $sentmessage .='.';
					 
					$ret=$sentmessage.' Current balance is &#8358;'.$bal.'.';
					
					$Msg="Subscription was not successful due to insufficient balance. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date."; Current Balance => ".$bal;
				 }else
				 {
					 $sentmessage=trim($sentmessage);
					 
					if ($sentmessage[strlen($sentmessage)-1] <> '.') $sentmessage .='.';
					
					$ret=$sentmessage.' Current balance is &#8358;'.$bal.'.';
					
					 $ret='Subscription was not successful due to insufficient balance and sms could not be delivered to your phone immediately. Below is the sms delivery response message:<br><br><b>'.$ret.'</b>';
					 
					 $Msg="Subscription was not successful due to insufficient balance. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date."; Current Balance => ".$bal;
				 }
			}else
			{
				if (!$sentmessage) $sentmessage='Subscription was not successful. ERROR CODE: '.$result['errorCode'].'. Transaction ID: '.$cptransid.'.';
			
				#Send Message - Success
				 $result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
				 
				 if (strtoupper(trim($result_msg['Status']))=='OK')
				 {						
					$ret=$sentmessage.' ERROR MESSAGE: '.$result['errorMessage'];
					
					$Msg="Subscription was not successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date."; Error Code => ".$result['errorCode']."; Error Message => ".$result['errorMessage'];
				 }else
				 {
					 $ret='Subscription was not successful and but sms could not be delivered to your phone immediately. ERROR CODE: '.$result['errorCode'].'. ERROR MESSAGE: '.$result['errorMessage'].'. Transaction ID: '.$cptransid.'. Below is the sms delivery response message:<br><b>'.$result_msg['Msg'].'</b>';
					 
					 $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date."; Error Code => ".$result['errorCode']."; Error Message => ".$result['errorMessage'];
				 }
			}
		}
		
		#Save to subscription_history table			
		$db->autocommit(FALSE);
							
		$sql='INSERT INTO subscription_history (email,network,msisdn,plan,amount,subscribe_date,subscription_expiredate,transid,cptransid,sentmessage,subscription_message,errorcode,subscription_status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)';
		
		$em=$db->escape_string($email);
		$nt=$db->escape_string($network);
		$ph=$db->escape_string($msisdn);
		$pl=$db->escape_string($plan);
		$amt=$db->escape_string($amount);
		$sdt=$db->escape_string($subscribe_date);
		$edt=$db->escape_string($exp_date);
		$tid=$db->escape_string($transid);
		$cptid=$db->escape_string($cptransid);
		$smsg=$db->escape_string($sentmessage);			
		$submsg=$db->escape_string($subscription_message);
		$ercd=$db->escape_string($errorCode);						
		$sta=$db->escape_string($subscription_status);			
		 
		$stmt = $db->prepare($sql);/* Prepare statement */
		
		if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
		 
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param('ssssdssssssss',$em,$nt,$ph,$pl,$amt,$sdt,$edt,$tid,$cptid,$smsg,$submsg,$ercd,$sta);

		$stmt->execute();/* Execute statement */
			
		$db->commit();
					
		#Reset SESSION variables
		$sdt = date('F d, Y',strtotime($subscribe_date));				
		$edt = date('F d, Y',strtotime($exp_date));
						
		$_SESSION['subscribe_date']=$sdt;
		$_SESSION['exp_date']=$edt;
		$_SESSION['subscriptionstatus']='<span style="color:#099E11;">Active</span>';
		
		LogDetails($network.'('.$msisdn.')',$Msg,$msisdn,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'SUBSCRIBED USER','System',$db);
		
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

	function BillAirtelSubscriber($msisdn,$amount,$subscriptiondays,$eventType,$db)
	{
		$Username_Charge = ''; $Password_Charge = ''; $cpId=''; $location=''; $wsdl=''; $messaging_url='';
		
		$sql="SELECT * FROM airtel_settings";		
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			
	
		if ( $query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
							
			if ($row['billing_password']) $Password_Charge=trim($row['billing_password']);
			if ($row['billing_username']) $Username_Charge=trim($row['billing_username']);
			if ($row['cpId']) $cpId=trim($row['cpId']);
			if ($row['billing_location']) $location=trim($row['billing_location']);
			if ($row['wsdl_path']) $wsdl=trim($row['wsdl_path']);
			if ($row['messaging_url']) $messaging_url=$row['messaging_url'];
		}
		
		$cpTid=date('YmdHis').'_'.$msisdn;					
		#$wsdl='http://www.laffhub.com/airtel/ChargingHttpService_ChargingHttp_Service.wsdl';

#$file = fopen('aaa.txt',"w"); fwrite($file, "MSISDN=".$msisdn."\nMESSAGE=".$message."\nSHORTCODE=".$shortcode."\nNetwork=".$network."\n\nAmount=".$amount."\nSubscription Days=".$subscriptiondays."\nEvent Type=".$eventType."\nMessaging URL=".$messaging_url."\nUsername=".$Username_Charge."\nPassword=".$Password_Charge."\nBilling Location=".$location."\nWSDL=".$wsdl.PHP_EOL); fclose($file);
		
		try
		{
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
	
	function SendAirtelSms($msisdn,$message,$db)
	{
		$Username = ''; $Password = ''; $messaging_url='';
		
		$sql="SELECT * FROM airtel_settings";		
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			
	
		if ( $query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
							
			if ($row['messaging_password']) $Password=$row['messaging_password'];
			if ($row['messaging_username']) $Username=$row['messaging_username'];
			if ($row['messaging_url']) $messaging_url=$row['messaging_url'];
		}
		#$messaging_url=http://144.217.72.146/server.php
		
		$options=array(
			'uri'=>'http://efluxz.com/billingservice',
			'location' => $messaging_url
		);
		
		$client=new SoapClient(NULL,$options);
		
		#$msisdn='2348023351689'; #2348022227157
		#$message = "Your attempt to opt out from Laffhub failed. You have no subscription on Laffhub service. Just joking";
		
		$param=array(
			'msisdn' 		=> $msisdn, 
			'message' 		=> $message,
			'Username'  	=> $Username,
			'Password' 		=> $Password
			);
			
		$result=$client->SendMsgToAirtelUser($param);
		
		return $result;
	}
	
	function CheckSubscriptionDate($phone,$db)
	{
		$dt=date('Y-m-d H:i:s');
		
		$ret='0';
		
		$sql="SELECT exp_date FROM subscriptions WHERE (TRIM(msisdn)='".$db->escape_string($phone)."')";
		
		if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			
	
		if ( $query->num_rows> 0 )
		{
			$row = $query->fetch_assoc();
							
			if ($row['exp_date']) $expdt=$row['exp_date'];
		
			if ($dt < $expdt)
			{
				UpdateSubscriptionStatus($phone,'1',$db);
			}else
			{
				UpdateSubscriptionStatus($phone,'0',$db);
			}
		}

		return true;
	}
	
	function UpdateSubscriptionStatus($phone,$status,$db)
	{
				
		$sql="SELECT * FROM subscriptions WHERE (TRIM(msisdn)='".$db->escape_string($phone)."')";
			
		if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

		if ($query->num_rows > 0 )#Update
		{
			$db->autocommit(FALSE);
									
			$sql='UPDATE subscriptions SET subscriptionstatus=? WHERE msisdn=?';
						 
			$stmt = $db->prepare($sql);/* Prepare statement */
			
			if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
			 
			/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
			$stmt->bind_param('is',$status,$phone);

			$stmt->execute();/* Execute statement */
				
			$db->commit();
		}
		
		return true;
	}
	
	function CheckForBlackList($network,$phone,$db)
	{
		$sql = "SELECT * FROM blacklist WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($phone)."')";	
		
		if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			
	
		if ($query->num_rows > 0) return true; else return false;
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
				
				if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
				 
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
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
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
					
					if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
					 
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
	
	function SaveSmsRequest($network,$msisdn,$shortcode,$message,$requestdate,$db)
	{
		#Save to DB				
		$sql = "SELECT * FROM sms_requests WHERE DATE_FORMAT(requestdate,'%Y-%m-%d %H:%i:%s')='".$requestdate."'";
		
		if(!$result = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
		
		if ($result->num_rows == 0 )
		{
			$db->autocommit(FALSE);
				
			$sql='INSERT INTO sms_requests (network,msisdn,shortcode,message,requestdate) VALUES (?,?,?,?,?)';										
			$stmt = $db->prepare($sql);#
			
			if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);
			
			$message=$db->escape_string($message);
			$msisdn=$db->escape_string($msisdn);
			$shortcode=$db->escape_string($shortcode);
			
			#Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob#
			$stmt->bind_param('sssss',$network,$msisdn,$shortcode,$message,$requestdate);				
			$stmt->execute();			
			$db->commit();
		}
	}
?>