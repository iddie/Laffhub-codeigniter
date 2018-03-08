<?php
set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

include('conn.php');

function AirtelUSSDDisplayMenu()
{  
	$ussd_text  = "CON Welcome To TheLAAFHUB Comedy Service. \n";
	$ussd_text .= "1. Daily Plan/N20/Daily \n";
	$ussd_text .= "2. Weekly Plan/N100/7dys \n";
	$ussd_text .= "3. Monthly Plan/N200/30dys \n";
	$ussd_text .= "4. Unlimited Plan/N500/30dys \n";
	$ussd_text .= "5. Stop Subscription \n";
	$ussd_text .= " \n";
	$ussd_text .= "[Select 1, 2, 3, 4 or 5]";
	 

	ussd_proceed($ussd_text);
}

function ussd_proceed($ussd_text)
{ 	
	// Print the response onto the page so that our gateway can read it
	header('Content-type: text/plain');
	
	echo $ussd_text;
	exit(0);  
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

function UpdateActiveSubscriptions($db)
{	
	$sql="UPDATE subscriptions SET subscriptionstatus=1 WHERE (TRIM(network)='Airtel') AND (DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') >  NOW())";
	 
	if (!$query = $db->query($sql))
	{
		$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,UpdateActiveSubscriptions,ERROR=>".$db->error."\n"); fclose($file);
	}
	
	return true;
}

function UpdateExpiredSubscriptions($db)
{	
	$sql="UPDATE subscriptions SET subscriptionstatus=0 WHERE (TRIM(network)='Airtel') AND (DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= NOW())";
	 
	if (!$query = $db->query($sql))
	{
		$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,UpdateExpiredSubscriptions,ERROR=>".$db->error."\n"); fclose($file);
	}
	
	return true;
}

function GetSuccessfulSubscription($db,$dt,$network)
{
	$sql="SELECT COUNT(msisdn) AS Total FROM subscription_history WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$db->escape_string($dt)."') AND (TRIM(subscription_status)='OK')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetTrials($db,$dt,$network)
{
	$sql="SELECT COUNT(msisdn) AS Total FROM trials WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetFailedChargings($db,$dt,$network)
{#cust_failed_charging,chargingdate
	$sql="SELECT COUNT(msisdn) AS Total FROM cust_failed_charging WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetFailedActivations($db,$dt,$network)
{#failed_activations,activationdate
	$sql="SELECT COUNT(msisdn) AS Total FROM failed_activations WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(activationdate,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetGreyAreas($db,$dt,$network)
{
	$sql="SELECT COUNT(msisdn) AS Total FROM greyareas WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetCancelledSubscribers($db,$dt,$network)
{
	$sql="SELECT COUNT(msisdn) AS Total FROM optouts WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(optout_date,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
}

function GetNewSubscribers($db,$dt,$network)
{
	$sql="SELECT COUNT(msisdn) AS Total FROM new_subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (DATE_FORMAT(subscriptiondate,'%Y-%m-%d') = '".$db->escape_string($dt)."')";

	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');
	
	$tot=0;
	
	if ($query->num_rows > 0 )
	{
		$row = $query->fetch_assoc();
		
		if ($row['Total']) $tot=$row['Total'];
	}
	
	return $tot;
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
		
			if ($query->num_rows > 0) return 0; else return 1;
		}
	}
}

function CheckSubscriptionDate($network,$phone,$db)
{
	$dt=date('Y-m-d H:i:s');
	
	$ret='0'; $state='';
	
	$sql="SELECT exp_date FROM subscriptions WHERE (TRIM(msisdn)='".$db->escape_string($phone)."')";
	
	if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			

	if ( $query->num_rows> 0 )
	{
		$row = $query->fetch_assoc();
						
		if ($row['exp_date']) $expdt=$row['exp_date'];
	
		if ($dt < $expdt)
		{
			UpdateSubscriptionStatus($network,$phone,'1',$db);
			$state = true;
		}else
		{
			UpdateSubscriptionStatus($network,$phone,'0',$db);
			$state = false;
		}
	}
	return $state;
}
	
function CheckForExpiredSubscriptions($network,$db)
{
	$dt=date('Y-m-d H:i:s');
	
	$sql="SELECT msisdn,exp_date FROM subscriptions WHERE (subscriptionstatus=1)";
	
	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');			
	
	while($row = $query->fetch_assoc())
	{
		$expdt=$row['exp_date'];
		$msisdn=$row['msisdn'];
		
		if ($dt < $expdt)
		{
			UpdateSubscriptionStatus($network,$msisdn,'1',$db);
		}else
		{
			UpdateSubscriptionStatus($network,$msisdn,'0',$db);
		}
	}
	
#$file = fopen('aaa.txt',"w"); fwrite($file,$dt."\nExp. Date=".$expdt."\nRet=".$ret); fclose($file);		
	$query->free();
	
	return true;
}

function UpdateSubscriptionStatus($network,$msisdn,$status,$db)
{
			
	$sql="SELECT * FROM subscriptions WHERE (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
	
	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');	
				
	if ($query->num_rows > 0 )#Update
	{
		$db->autocommit(FALSE);
		
		$sql='UPDATE subscriptions SET subscriptionstatus=? WHERE TRIM(network)=? AND TRIM(msisdn)=?';
		
		#$nt='Airtel';
		$ph=$db->escape_string($msisdn);
		 
		$stmt = $db->prepare($sql);/* Prepare statement */
		
		if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$conn->error, E_USER_ERROR);
		 
		/* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
		$stmt->bind_param('iss',$status,$network,$ph);
		
		$stmt->execute();/* Execute statement */
				
		$db->commit();
	}
	
	return true;
}

function CheckForBlackList($network,$phone,$db)
{
	$sql = "SELECT * FROM blacklist WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($phone)."')";	
	
	if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');			

	if ($query->num_rows > 0)
	{
		return true;
	}
    return true;
}


#MAIN AIRTEL SUBSCRIPTION MODULE
function SubscribeAirtelUser($email,$network,$msisdn,$plan,$subscriptiondays,$amount,$autobilling,$subscribe_date,$exp_date,$watched,$videos_cnt_to_watch,$subscriptionstatus,$transid,$cptransid,$subscription_message,$errorCode,$errorMessage,$subscription_status,$subscriptionId,$db)
{
	$duration=$subscriptiondays; $Msg=''; $dt=date('Y-m-d H:i'); $ret='';
	
	$sentmessage='';

	$eventType='Subscription Purchase'; #ReSubscription
				
	if (trim(strtoupper($subscription_status))=='OK')
	{

		$sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(msisdn)='".$db->escape_string($msisdn)."')";
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
		
		if ($query->num_rows > 0 )#There is active subscription
		{
			$row = $query->fetch_assoc();
			
			if (($row['subscriptionstatus']==0) || ($row['subscriptionstatus']==1))
			{
				$db->autocommit(FALSE);
				
				$sql="UPDATE subscriptions SET email='".$email."',plan='".$plan."',duration='".$subscriptiondays."',amount='".$amount."',autobilling='".$autobilling."',subscribe_date='".$subscribe_date."',exp_date='".$exp_date."',videos_cnt_watched='".$watched."',videos_cnt_to_watch='".$videos_cnt_to_watch."',subscriptionstatus='".$subscriptionstatus."',subscriptionId='".$subscriptionId."' WHERE (TRIM(network)='".$network."') AND (TRIM(msisdn)='".$msisdn."')";
		
				if (!$query = $db->query($sql))
				{
					$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,SubscribeAirtelUser,ERROR=>".$db->error."\n"); fclose($file);
				}
						
				$db->commit();
			}
		}else
		{
			$db->autocommit(FALSE);
			
			$sql="INSERT INTO subscriptions (email,network,msisdn,plan,duration,amount,autobilling,subscribe_date,exp_date,videos_cnt_watched,videos_cnt_to_watch,subscriptionstatus,subscriptionId) VALUES ('".$email."','".$network."','".$msisdn."','".$plan."','".$subscriptiondays."','".$amount."','".$autobilling."','".$subscribe_date."','".$exp_date."','".$watched."','".$videos_cnt_to_watch."','".$subscriptionstatus."','".$subscriptionId."')";	
	
			if (!$query = $db->query($sql))
			{
				$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,SubscribeAirtelUser,ERROR=>".$db->error."\n"); fclose($file);
			}
					
			$db->commit();
		}


		#Create record in watchlists table			
		$db->autocommit(FALSE);
		
		$vid='';
		
		$sql="INSERT INTO watchlists (subscriptionId,videolist) VALUES ('".$subscriptionId."','".$vid."')";
											
		if (!$query = $db->query($sql))
		{
			$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,SubscribeAirteluser,ERROR=>".$db->error."\n"); fclose($file);
		}
			
		$db->commit();

		$Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$subscriptiondays."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
		
		$ret='OK';
		
		#GET SUBSCRIPTION MESSAGE
		$sql = "SELECT renewal,fallback_notice FROM subscriber_messages WHERE (TRIM(network)='".$db->escape_string($network)."') AND (TRIM(plan)='".$db->escape_string($plan)."') ";
		
		if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');
		
		if ($query->num_rows > 0 )
		{
			$row = $query->fetch_assoc();
			
			if ($row['renewal']) $sentmessage=$row['renewal'];
			
			if (!$sentmessage)
			{
				if ($row['fallback_notice']) $sentmessage=$row['fallback_notice'];
			}
		}
		
		if (!$sentmessage) $sentmessage='You have been charged N'.$amount.' for '.$plan.' Laffhub service. Visit www.laffhub.com. NO DATA COST. To opt out, text OUT to 2001.';

		#Send Message - Success
		 $result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
		 
#$file = fopen('aaa_SUB.txt',"a"); fwrite($file, "\n\nSUCCESS\nSent Message=".$sentmessage."\nMSISDN=".$msisdn."\nMsg Status=".$result_msg['Status']); fclose($file);
		 
		 if (strtoupper(trim($result_msg['Status']))<>'OK')
		 {
			 $ret='Subscription was successful but sms could not be delivered to your phone immediately. Below is your sms delivery response message: '.$result_msg['Msg'];
			 
			 $Msg="Subscription was successful. Details: Network => ".$network."; MSISDN => ".$msisdn."; Service Plan => ".$plan."; Duration => ".$duration."; Amount => ".$amount."; Subscription Date => ".$subscribe_date."; Expiry Date => ".$exp_date;
		 }				 
	}elseif (trim(strtoupper($subscription_status))=='FAILED')#Subscription Failed
	{#- array('Status' => 'FAILED','errorCode' => $errorcode,'errorMessage' =>$errormsg, 'TransId' => $transid,'cpTransId' => $cptransid);
											
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
			 #$result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
			 
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
			 #$result_msg=SendAirtelSms($msisdn,$sentmessage,$db);
			 
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
	}elseif(trim(strtoupper($subscription_status))=='FAILED RENEWAL')
    {
	    $phone = $msisdn;

        $sql="SELECT * FROM subscriptions WHERE (TRIM(msisdn)='".$db->escape_string($phone)."')";

        if (!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

        if ( $query->num_rows> 0 )
        {
            UpdateSubscriptionStatus($network,$phone,'0',$db);

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

            if (!$sentmessage) $sentmessage='Laffhub '.trim($plan).' subscription service could not be activated due to insufficient airtime.Recharge & SMS '.$key.' to 2001.Service cost N'.trim($amount).'/'.$subscriptiondays.'days.';

            SendAirtelSms($msisdn,$sentmessage,$db);

            $ret = 'FAILED';
        }
    }

	
	#Save to subscription_history table			
	$db->autocommit(FALSE);
						
	$sql="INSERT INTO subscription_history (email,network,msisdn,plan,amount,subscribe_date,subscription_expiredate,transid,cptransid,sentmessage,subscription_message,errorcode,subscription_status) VALUES ('".$email."','".$network."','".$msisdn."','".$plan."','".$amount."','".$subscribe_date."','".$exp_date."','".$transid."','".$cptransid."','".$sentmessage."','".$subscription_message."','".$errorCode."','".$subscription_status."')";
	
	
	if (!$query = $db->query($sql))
	{
		$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,SubscribeAirtelUser,ERROR=>".$db->error."\n"); fclose($file);
	}
	
	$db->commit();
				
	#Reset SESSION variables
	$sdt = date('F d, Y',strtotime($subscribe_date));				
	$edt = date('F d, Y',strtotime($exp_date));
					
	$_SESSION['subscribe_date']=$sdt;
	$_SESSION['exp_date']=$edt;
	$_SESSION['subscriptionstatus']='<span style="color:#099E11;">Active</span>';
	
	#LogDetails($network.'('.$msisdn.')',$Msg,$msisdn,date('Y-m-d H:i:s'),$remote_ip,$remote_host,'SUBSCRIBED USER','System',$db);
	
	return $ret;
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
								
			$sql="UPDATE loginfo SET Activity='".$Activity."',ActionDate='".$logdate."',LogOutDate='".$logdate."',Operation='".$Operation."',remote_ip='".$ip."',remote_host='".$host."' WHERE LogID='".$LogID."'";
											
			if (!$query = $db->query($sql))
			{
				$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,LoDetails,ERROR=>".$db->error."\n"); fclose($file);
			}
				
			$db->commit();
		}else
		{
			if (trim(strtoupper($Operation))=='LOGIN')
			{
				$db->autocommit(FALSE);
								
				$sql="INSERT INTO loginfo (LoginDate,Name,Activity,ActionDate,Username,Operation,LogID,remote_ip,remote_host) VALUES ('".$logdate."','".$Name."','".$Activity."','".$logdate."','".$Username."','".$Operation."','".$LogID."','".$ip."','".$host."')";
				
				if (!$query = $db->query($sql))
				{
					$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,LoDetails,ERROR=>".$db->error."\n"); fclose($file);
				}
				
				$db->commit();
			}else
			{
				$logdate=date('Y-m-d H:i:s');
				
				$db->autocommit(FALSE);
								
				$sql='INSERT INTO loginfo (LoginDate,Name,Activity,ActionDate,Username,Operation,LogID,remote_ip,remote_host) VALUES (?,?,?,?,?,?,?,?,?)';
				
				$sql="INSERT INTO loginfo (LoginDate,Name,Activity,ActionDate,Username,Operation,LogID,remote_ip,remote_host) VALUES ('".$logdate."','".$Name."','".$Activity."','".$logdate."','".$Username."','".$Operation."','".$LogID."','".$ip."','".$host."')";
				
				if (!$query = $db->query($sql))
				{
					$file = fopen('mysqli_error_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",common.php,LoDetails,ERROR=>".$db->error."\n"); fclose($file);
				}
					
				$db->commit();
			}		
		}
	}catch (Exception $e)
	{
		$db->rollback();
	}
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

#$file = fopen('aaa.txt',"w"); fwrite($file, "\n\nMSISDN=".$msisdn."\nMESSAGE=".$message."\nSHORTCODE=".$shortcode."\nNetwork=".$network."\n\nAmount=".$amount."\nSubscription Days=".$subscriptiondays."\nEvent Type=".$eventType."\nMessaging URL=".$messaging_url."\nUsername=".$Username_Charge."\nPassword=".$Password_Charge."\nBilling Location=".$location."\nWSDL=".$wsdl); fclose($file);
	
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
?>