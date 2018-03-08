<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
session_start();
set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

include('common.php');

$network='Airtel';

$msisdn='';


if ((isset($_REQUEST["msisdn"])) && (isset($_REQUEST["productID"])) && (isset($_REQUEST["statusCode"])))
{
    $msisdn = trim($_REQUEST["msisdn"]);
    $productID = trim($_REQUEST["productID"]);
    $status = trim($_REQUEST["status"]);
    $transid =trim($_REQUEST["transid"]);
    $errorCode = trim($_REQUEST["statusCode"]);
    $cpId = trim($_REQUEST["cpId"]);
    $subscribe_date = urldecode(($_REQUEST["subscribe_date"]));
    $lowBalance = trim($_REQUEST["low_balance"]);
    $amountCharged = trim($_REQUEST["amountCharged"]);


    $cptransid=date('YmdHis').'_'.$msisdn;

    $subscriptionstatus=''; $subscription_status=''; $errorMessage = $status; $subscription_message = '';


    $subscriptionId = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

    $amount=''; $plan=''; $email=''; $watched=0; $videos_cnt_to_watch=''; $exp_date=''; $duration=''; $subscriptiondays=''; $autobilling=1;

    $passed=''; $failed =''; $response='';


    if(($productID == '6300') || ($productID == '6302') || ($productID == '6305') || ($productID == '6308')){

        $plan = 'Daily';

    }elseif (($productID == '6301') || ($productID == '6304') || ($productID == '6307')) {

        $plan = 'Weekly';

    }
    elseif (($productID == '6303') || ($productID == '6309')) {

        $plan = 'Monthly';

    }elseif (($productID == '6306')) {

        $plan = 'Unlimited';

    }

    if( $errorCode == '1000'){

        $subscription_status='OK'; $subscription_message = $status; $subscriptionstatus='1';

        $sql = "SELECT plan,duration,no_of_videos FROM plans WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(plan)='" . $db->escape_string($plan) . "')";

        if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

        if ($query->num_rows > 0) {

            while ($rw = $query->fetch_assoc()) {

                if ($rw['plan'] == 'Daily') {

                    $subscriptiondays = $rw['duration'];
                    $exp_date = date('Y-m-d H:i:s', strtotime("+" . $subscriptiondays . " days", strtotime($subscribe_date)));
                    $videos_cnt_to_watch = $rw['no_of_videos'];
                }
            }
        }

        $sql = "SELECT plan,price FROM prices WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(plan)='" . $db->escape_string($plan) . "')";

        if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

        if ($query->num_rows > 0) {

            while ($rw = $query->fetch_assoc()) {

                if ($rw['plan'] == 'Daily') {

                    $amount = $rw['price'];
                }
            }
        }

        $passed++;

        $Msg = "Renewal was successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Duration => " . $subscriptiondays . "; Amount => " . $amount . "; Subscription Date => " . $subscribe_date . "; Expiry Date => " . $exp_date;

        #Log
        $file = fopen('SE_renewal_success.txt', "a");
        fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $status . ",Trans. Id: " . $transid . ",CP Trans. Id: " . $cptransid . "," . $passed . PHP_EOL);
        fclose($file);

    }elseif($errorCode == '1013') {

        $subscription_status='FAILED RENEWAL'; $subscription_message = 'Failed Renewal due to low balance'; $subscriptionstatus='0';

        $Msg = "Renewal was not successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Duration => " . $subscriptiondays . "; Amount => " . $amount . "; Subscription Date => " . $subscribe_date . "; Expiry Date => " . $exp_date;

        $failed++;

        #Log
        $file = fopen('SE_lowbalance_notification.txt', "a");
        fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $status . ",Trans. Id: " . $transid . ",CP Trans. Id: " . $cptransid . "," . $failed . PHP_EOL);
        fclose($file);
    }

    $result = SubscribeAirtelUser($email, $network, $msisdn, $plan, $subscriptiondays, $amount, $autobilling, $subscribe_date, $exp_date, $watched, $videos_cnt_to_watch, $subscriptionstatus, $transid, $cptransid, $subscription_message, $errorCode, $errorMessage, $subscription_status, $subscriptionId, $db);

    if($result == 'OK') {

        $response = 'Successful';

    } elseif($result == 'FAILED') {

        $response = 'Failed';
    }

    $remote_ip = getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];

    #$host = $_SERVER['REMOTE_HOST'];
    $remote_host = '';
    if ($remote_ip) $remote_host = gethostbyaddr($remote_ip);

    LogDetails($network . '(' . $msisdn . ')', $Msg, $msisdn, date('Y-m-d H:i:s'), $remote_ip, $remote_host, 'SUBSCRIPTION RENEWAL', 'System', $db);

    echo $response;

} else
{
    $message='Invalid Request From '.getRealIpAddr();

    $ussd_response=$message;

    $file = fopen('ussd_messages_bad.txt',"a"); fwrite($file, $message.PHP_EOL); fclose($file);
}


?>
