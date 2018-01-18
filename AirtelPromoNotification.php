<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#error_reporting(E_ALL); ini_set('display_errors', 1);

require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';
include('common.php');

$network='Airtel';

AirtelPromoNotification($network,$db);

function AirtelPromoNotification($network,$db)
{
    $plan = 'Airtel_Promo';

    $totalRecordsToTrack = '';

    $portionSize = 100;

    $sql = "SELECT COUNT(msisdn) AS TotalRecords FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(plan)='" . $plan . "')";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    if ($query->num_rows > 0) {
        while ($rw = $query->fetch_assoc()) {
            if ($rw['TotalRecords']) $totalRecordsToTrack = $rw['TotalRecords'];
        }
    }

    for ($i = 0; $i <= ceil($totalRecordsToTrack / $portionSize); $i++) {

        $limitFrom = $portionSize * $i;

        $dt = date('Y-m-d H:i');

        $sql = "SELECT msisdn,plan,duration,amount,email,subscribe_date,exp_date,videos_cnt_to_watch,autobilling FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(plan)='" . $db->escape_string($plan) . "') LIMIT $limitFrom, $portionSize";

        if (!$qry = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

        while ($srow = $qry->fetch_assoc()):

            $plan = '';
            $msisdn = '';
            $exp_date='';

            if ($srow['msisdn']) $msisdn = trim($srow['msisdn']);
            if ($srow['plan']) $plan = trim($srow['plan']);
            if ($srow['exp_date']) $exp_date = $srow['exp_date'];;

            $expiry_date = date('Y-m-d H:i', strtotime($exp_date));
            $today = new DateTime($dt);
            $ex_date = new DateTime($expiry_date);
            $interval = $ex_date->diff($today);
            $duration = $interval->format('%R%a days');
            $days_left = str_replace('-','',$duration);

            $message = "Dear customer, Your Laffhub Free Trial expires in " . $days_left . ".To keep enjoying comedy clips, text 'DAY' to 2001 at 20/day. To opt-out, text OUT to 2001";

            if ($days_left == '4 days') {

                SendAirtelSms($msisdn,$message,$db);

            }elseif($days_left == '3 days') {

                SendAirtelSms($msisdn,$message,$db);

            }elseif($days_left == '0 days') {

                $message = "Dear customer, Your Laffhub Free Trial expires today.To keep enjoying comedy clips, text 'DAY' to 2001 at 20/day. To opt-out, text OUT to 2001";
                SendAirtelSms($msisdn,$message,$db);

            }elseif($duration == '+0 days') {

                $message = "Dear customer, Your Laffhub Free Trial has expired.To keep enjoying comedy clips, text 'DAY' to 2001 at 20/day. To opt-out, text OUT to 2001";
                SendAirtelSms($msisdn,$message,$db);
            }

        endwhile;
    }
}

?>