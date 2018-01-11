<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#error_reporting(E_ALL); ini_set('display_errors', 1);

require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';
include('common.php');

$network='Airtel';

#CheckForExpiredSubscriptions($network,$db);

CheckSubscriptions($network,$db);

function CheckSubscriptions($network,$db)
{
    $dt = date('Y-m-d H:i:s');

    #$sql="SELECT DISTINCT(plan) AS plan FROM subscriptions WHERE ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '".$dt."') OR (subscriptionstatus=0)) AND (msisdn IN ('2348023351689','2348022227157'))";

    $sql = "SELECT DISTINCT(plan) AS plan FROM subscriptions WHERE (TRIM(network)='" . $network . "') AND ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '" . $dt . "') OR (subscriptionstatus=0))";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    $arr = array();

    if ($query->num_rows > 0) {
        while ($rw = $query->fetch_assoc()) {
            if ($rw['plan']) $arr[$rw['plan']] = 0;
        }
    }

    #$sql="SELECT * FROM subscriptions WHERE ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '".$dt."') OR (subscriptionstatus=0)) AND (msisdn IN ('2348023351689','2348022227157'))";

    $sql = "SELECT msisdn,plan,duration,amount,email,subscribe_date,exp_date,videos_cnt_to_watch,autobilling FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '" . $dt . "') OR (subscriptionstatus=0))";

    if (!$qry = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    $failed = 0;
    $passed = 0;

    while ($srow = $qry->fetch_assoc()):

        $amount = '';
        $subscriptiondays = '';
        $videos_cnt_to_watch = '';
        $email = '';
        $subscriptionstatus = '';
        $watched = '0';
        $autobilling = 1;
        $msisdn = '';
        $subscribe_date='';
        $exp_date='';

        if ($srow['msisdn']) $msisdn = trim($srow['msisdn']);
        if ($srow['plan']) $plan = trim($srow['plan']);
        if ($srow['amount']) $amount = $srow['amount'];
        if ($srow['email']) $email = trim($srow['email']);
        if ($srow['subscribe_date']) $subscribe_date = $srow['subscribe_date'];
        if ($srow['exp_date']) $exp_date = $srow['exp_date'];

        if ($srow['subscriptionstatus'] == 1) {
            $subscriptionstatus = $srow['subscriptionstatus'];
        } else {
            $subscriptionstatus = '0';
        }

        $file = fopen('db_result.txt', "a");
        fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $subscriptionstatus . ",Subscribe Date: " . $subscribe_date . ",Expiry Date: " . $exp_date . "," . PHP_EOL);
        fclose($file);

    endwhile;

}

?>