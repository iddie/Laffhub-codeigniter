<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
session_start();
set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

include('common.php');

$network='Airtel';

$msisdn='';

if ((isset($_REQUEST["msisdn"])) && (isset($_REQUEST["reqType"])) && (isset($_REQUEST["param"])))
{
    $reqType=trim($_REQUEST["reqType"]);
    $msisdn=str_replace('+','',trim($_REQUEST["msisdn"]));
    $productID=trim($_REQUEST["param"]);
    $channel=trim($_REQUEST["channel"]);
    $reqdate=date('Y-m-d H:i:s');
    $message='Subscription Request Through Start Stop';
    $shortCode=trim($_REQUEST["shortCode"]);
    $svcid= $productID;

    $plan=''; $svcdesc='';

    $file = fopen('incoming_start.txt',"a"); fwrite($file, "MSISDN=".$msisdn." => reqType=".$reqType." => channel=".$channel." => Date=".$reqdate.PHP_EOL); fclose($file);

    SaveSmsRequest($network,$msisdn,$productID,$message,$reqdate,$db);

    if (isset($productID) && isset($msisdn))
    {

        $ret=CheckForBlackList($network,$msisdn,$db);

        if($ret==true)#Blacklisted Number
        {
            ## Send SMS to Susbcriber
            $msg='We are sorry, the phone number, '.$msisdn.', has been blacklisted and cannot subscribe to this service.';
            $ret=SendAirtelSms($msisdn,$msg,$db);
            $svcdesc = 'LAFFHUB COMEDY';

            # Send response to VPN Server
            $status="ERROR";

            $response = $status;

        }
        else {

            if (($productID == '6300') || ($productID == '6302') || ($productID == '6305') || ($productID == '6308') && ($reqType == "SUB")) {

                $plan = 'Daily';

            } elseif (($productID == '6301') || ($productID == '6304') || ($productID == '6307') && ($reqType == "SUB")) #Process Weekly Plan
            {
                $plan = 'Weekly';

            } elseif (($productID == '6303') || ($productID == '6309') && ($reqType == "SUB")) #Process Monthly Limited Plan
            {
                $plan = 'Monthly';

            } elseif (($productID == '6306') && ($reqType == "SUB")) #Process Monthly Unlimited Plan
            {
                $plan = 'Unlimited';

            }

            $subscription_msg = '';
            $amount = '';
            $insufficent_balance_msg = '';
            $wrong_keyword_msg = '';
            $subscriptiondays = '';
            $amount = '';
            $autobilling = '1';
            $videos_cnt_to_watch = '';
            $email = '';
            $subscriptionstatus = '1';
            $watched = '0';
            $subscriptionId=strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10));
            $response='';

            //Check if record exists
            $sql = "SELECT * FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (subscriptionstatus=1) AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "')";
            if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

            $ret = '';
            $watched = 0;
            $maxwatch = 0;
            $expdt = '';
            $activeplan = '';
            $flag = false;
            $ex = '';

            #There is active subscription
            if ($query->num_rows > 0) {
                $row = $query->fetch_assoc();

                if ($row['exp_date']) {
                    $expdt = date('d M Y @ H:i', strtotime($row['exp_date']));
                    $ex = date('Y-m-d H:i', strtotime($row['exp_date']));
                }

                if ($row['plan']) $activeplan = trim($row['plan']);
                if ($row['videos_cnt_watched']) $watched = intval($row['videos_cnt_watched']);
                if ($row['videos_cnt_to_watch']) $maxwatch = $row['videos_cnt_to_watch'];

                $ret = "You currently have an active subscription to this service which will expire on " . $expdt . ". Visit www.laffhub.com to enjoy videos.";

                $Msg = "Subscription was not successful. Subscriber has an active subscription running. Current Subscription Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $activeplan . "; Expiry Date => " . $expdt;

                $status = 'ERROR';

                SendAirtelSms($msisdn, $ret, $db);

                $response =  $status;

            } else {

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

                $eventType='Subscription Purchase'; #Subscription

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

                $ret= SubscriptionEngineSubscribe($msisdn,$productID);

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

                $status = '';

                if((trim(strtoupper($subscription_status))=='OK') && ($errorCode == '1000'))
                {
                    $status = 'SUB';
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

                    $file = fopen('startstop_billing_success.txt',"a"); fwrite($file, $subscribe_date.", MSISDN: ".$msisdn.", Status: ".$result.", Plan: ".$plan.", Amount: ".$amount.", Subscription ID: ".$subscriptionId.", Transid Id: ".$transid.", CP Trans ID: ".$cptransid.PHP_EOL); fclose($file);

                    #Send Message - Success
                    SendAirtelSms($msisdn,$subscription_msg,$db);

                    $response = $status;


                }elseif (trim(strtoupper($ret['Status']))=='FAILED')
                {#Send Message

                    $file = fopen('startstop_billing_failed.txt',"a"); fwrite($file, $subscribe_date.", MSISDN: ".$msisdn.", Status: ".$ret['Status'].", Plan: ".$plan.", Amount: ".$amount.", Error Code: ".$ret['errorCode'].", Error Message: ".$ret['errorMessage'].PHP_EOL); fclose($file);

                    $status="ERROR";

                    if (($ret['errorCode']) =='3404')
                    {
                        $response = $status;
                        $bal=floatval(str_replace('Insufficient Balance.#~#','',$ret['errorMessage']));
                        $ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);

                    }elseif (($ret['errorCode']) =='3003')
                    {
                        $response = $status;
                        $ussd_response='Laffhub '.trim($plan).' subscription was not successful. ERROR: '.$ret['errorMessage'];
                        $ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
                    }
                }
            }

        }
    }

    echo $response;

} else
{
    $message='Invalid Request From '.getRealIpAddr();

    $ussd_response=$message;

    $file = fopen('ussd_messages_bad.txt',"a"); fwrite($file, $message.PHP_EOL); fclose($file);
}

?>
