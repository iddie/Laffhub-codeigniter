<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#error_reporting(E_ALL); ini_set('display_errors', 1); 

include('common.php');

$network='Airtel';

ProcessSubscriptions($network,$notificationdb,$db);

function ProcessSubscriptions($network,$notificationdb,$db)
{

    $totalRecordsToRenew = '';

    $portionSize = 100;

    $sql = "SELECT COUNT(msisdn) AS TotalRecords FROM notifications WHERE (updated=0))";

    if (!$query = $notificationdb->query($sql)) die('There was an error running the query [' . $notificationdb->error . ']');

    if ($query->num_rows > 0) {
        while ($rw = $query->fetch_assoc()) {

            if ($rw['TotalRecords']) $totalRecordsToRenew = $rw['TotalRecords'];
        }
    }

    for ($i = 0; $i <= ceil($totalRecordsToRenew / $portionSize); $i++) {

       $limitFrom = $portionSize * $i;

       $dt = date('Y-m-d H:i:s');

       $sql = "SELECT msisdn,errorcode,productid,amount, transid,errormsg,requesttype,chargingtime,updated FROM notifications WHERE updated=0 LIMIT $limitFrom, $portionSize";

       if (!$qry = $notificationdb->query($sql)) die('There was an error running the query [' . $notificationdb->error . ']');

        while ($srow = $qry->fetch_assoc()):

            $amount=''; $msisdn = ''; $errorCode = ''; $productid = ''; $transid = ''; $errorMsg = ''; $plan='';
            $requesttype=''; $processed=''; $productid=''; $watched=0; $videos_cnt_to_watch=''; 
            $subscriptionstatus=''; $transid=''; $cptransid=''; $subscription_message=''; 
            $subscription_status=''; $subscriptionId=''; $email=''; $autobilling=1;

            $cptransid=date('YmdHis').'_'.$msisdn; 

            $subscriptionId = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

            if ($srow['msisdn']) $msisdn = trim($srow['msisdn']);
            if ($srow['productid']) $productid = trim($srow['productid']);
            if ($srow['transid']) $transid = trim($srow['transid']);
            if ($srow['errormsg']) $errorMsg = trim($srow['errormsg']);
            if ($srow['requesttype']) $requesttype = trim($srow['requesttype']);
            if ($srow['chargingtime']) $subscribe_date = trim($srow['chargingtime']);
            if ($srow['updated']) $processed = trim($srow['updated']);
            if ($srow['errorcode']) $erroCode = trim($srow['errorcode']);

            if(($productid == '6300') || ($productid == '6302') || ($productid == '6305') || ($productid == '6308')){

                $plan = 'Daily';

            }elseif (($productid == '6301') || ($productid == '6304') || ($productid == '6307')) {
        
                $plan = 'Weekly';

            }
            elseif (($productid == '6303') || ($productid == '6309')) {
        
                $plan = 'Monthly';

            }elseif (($productid == '6306')) {
        
                $plan = 'Unlimited';

            }

            if(( $errorCode == '1000') && ($requesttype =='Renewal') && $processed == 0){

                $subscription_status='OK'; $subscription_message=$errorMsg; $subscriptionstatus='1';
        
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
        
                SubscribeAirtelUser($email, $network, $msisdn, $plan, $subscriptiondays, $amount, $autobilling, $subscribe_date, $exp_date, $watched, $videos_cnt_to_watch, $subscriptionstatus, $transid, $cptransid, $subscription_message, $errorCode, $errorMessage, $subscription_status, $subscriptionId, $db);
        
                $Msg = "Renewal was successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Duration => " . $subscriptiondays . "; Amount => " . $amount . "; Subscription Date => " . $subscribe_date . "; Expiry Date => " . $exp_date;

                UpdateNotificationDB($msisdn,$notificationdb);
            
                #Log
                $file = fopen('SE_renewal_success.txt', "a");
                fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $status . ",Trans. Id: " . $transid . ",CP Trans. Id: " . $cptransid . "," . $passed . PHP_EOL);
                fclose($file);
        
            }elseif($errorCode == '1013') {
                ## successful deprovisioning after grace period

                UnSubscriberUser($msisdn, $network, $db);
    
                UpdateNotificationDB($msisdn,$notificationdb);

                #Log
                $file = fopen('SE_lowbalance_notification.txt', "a");
                fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $status . ",Trans. Id: " . $transid . ",CP Trans. Id: " . $cptransid . "," . $failed . PHP_EOL);
                fclose($file);
        
            }elseif($errorCode == '1001')  //Stop notification from Airtel Start/Stop
            {
                UnSubscriberUser($msisdn, $network, $db);
                
                UpdateNotificationDB($msisdn,$notificationdb);
            }

        endwhile;
    }
}

?>
