<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#error_reporting(E_ALL); ini_set('display_errors', 1); 

require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';
include('common.php');

$network='Airtel';

AutoRenewSubscriptions($network,$db);

function AutoRenewSubscriptions($network,$db)
{
    $dt = date('Y-m-d H:i:s');

    $sql = "SELECT DISTINCT(plan) AS plan FROM subscriptions WHERE (TRIM(network)='" . $network . "') AND ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '" . $dt . "') OR (subscriptionstatus=0))";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    $arr = array();

    if ($query->num_rows > 0) {
        while ($rw = $query->fetch_assoc()) {
            if ($rw['plan']) $arr[$rw['plan']] = 0;
        }
    }

    $totalRecordsToRenew = '';

    $portionSize = 100;

    $sql = "SELECT COUNT(msisdn) AS TotalRecords FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '" . $dt . "') OR (TRIM(network)='" . $db->escape_string($network) . "') AND (subscriptionstatus=0))";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    if ($query->num_rows > 0) {
        while ($rw = $query->fetch_assoc()) {
            if ($rw['TotalRecords']) $totalRecordsToRenew = $rw['TotalRecords'];
        }
    }

    for ($i = 0; $i <= ceil($totalRecordsToRenew / $portionSize); $i++) {

        $limitFrom = $portionSize * $i;

        $dt = date('Y-m-d H:i:s');

        $sql = "SELECT msisdn,plan,duration,amount,email,subscribe_date,exp_date,videos_cnt_to_watch,autobilling FROM subscriptions WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND ((DATE_FORMAT(`exp_date`,'%Y-%m-%d %H:%i:%s') <= '" . $dt . "') OR (subscriptionstatus=0)) ORDER BY exp_date ASC LIMIT $limitFrom, $portionSize";

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

            if ($srow['msisdn']) $msisdn = trim($srow['msisdn']);
            if ($srow['plan']) $plan = trim($srow['plan']);
            if ($srow['duration'] == 3) {
                $subscriptiondays = 1;
            } else {
                $subscriptiondays = trim($srow['duration']);
            }
            if ($srow['amount']) $amount = $srow['amount'];
            if ($srow['email']) $email = trim($srow['email']);
            if ($srow['exp_date']) $exp_date = $srow['exp_date'];

            if ($srow['videos_cnt_to_watch'] == 8) {
                $videos_cnt_to_watch = 5;
            } else {
                $videos_cnt_to_watch = $srow['videos_cnt_to_watch'];
            }
            if ($srow['autobilling']) $autobilling = $srow['autobilling'];
            if ($srow['subscriptionstatus'] == 1) {
                $subscriptionstatus = $srow['subscriptionstatus'];
            } else {
                $subscriptionstatus = '0';
            }

            if (trim($msisdn) <> '') {
                $black = CheckForBlackList($network, $msisdn, $db);

                if ($black == false) {

                    $rt = CheckSubscriptionDate($network, $msisdn, $db);

                    if ($rt == false) {

                        $subscriptionId = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

                        if ($autobilling == 1) {#echo '<br>'.$plan.' => '.$msisdn.' => '.$arr[$plan].'<br>';
                            try {
                                $ret = '';
                                $watched = 0;
                                $maxwatch = 0;
                                $expdt = '';
                                $activeplan = '';
                                $flag = false;
                                $ex = '';
                                $subscriptionstatus = 1;

                                $subscribe_date = date('Y-m-d H:i:s');
                                $exp_date = date('Y-m-d H:i:s', strtotime("+" . $subscriptiondays . " days", strtotime($subscribe_date)));

                                $eventType = 'Subscription Purchase'; #ReSubscription

                                $transid = '';
                                $cptransid = '';
                                $subscription_message = '';
                                $errorCode = '';
                                $errorMessage = '';
                                $subscription_status = '';
                                $billing_status = '';

                                $ret = BillAirtelSubscriber($msisdn, $amount, $subscriptiondays, $eventType, $db);
                                $isnew = IsNewSubscriber($db, $msisdn, $network);

                                #Array ( [status] => Failure [transactionId] => twss_54e21d846749779 [error] => Array ( [errorCode] => OL404 [errorMessage] => Insufficient Balance.#~#95.45 ) [cpTransactionId] => 20170617155509_2348083964929 )

                                if (isset($ret)) {
                                    if (strtoupper($ret['Status']) <> 'FAILED')#Success
                                    {
                                        $arr[$plan] = $arr[$plan] + 1;

                                        if ($ret['TransId']) $transid = $ret['TransId'];
                                        if ($ret['cpTransId']) $cptransid = $ret['cpTransId'];

                                        if ($ret['errorMessage']) {
                                            $subscription_message = $ret['errorMessage'];
                                        } else {

                                            if (trim(strtoupper($ret['Status'])) == 'OK') $subscription_message = 'Successful';
                                        }

                                        if ($ret['errorCode']) $errorCode = $ret['errorCode'];
                                        if ($ret['Status']) $subscription_status = $ret['Status'];

                                        if (trim(strtoupper($subscription_status)) == 'OK') {
                                            #Save to subscriptions table
                                            $result = SubscribeAirtelUser($email, $network, $msisdn, $plan, $subscriptiondays, $amount, $autobilling, $subscribe_date, $exp_date, $watched, $videos_cnt_to_watch, $subscriptionstatus, $transid, $cptransid, $subscription_message, $errorCode, $errorMessage, $subscription_status, $subscriptionId, $db);

                                            #Send Message - Success
                                            if (trim(strtoupper($result)) == 'OK') {
                                                #$ret=SendAirtelSms($msisdn,$subscription_msg,$db);
                                            } else {
                                                #$ret=SendAirtelSms($msisdn,$ret,$db);
                                            }


                                            $passed++;

                                            #Log
                                            $file = fopen('airtel_renewal_success.txt', "a");
                                            fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $ret["Status"] . ",Trans. Id: " . $ret["TransId"] . ",CP Trans. Id: " . $ret["cpTransId"] . "," . $passed . PHP_EOL);
                                            fclose($file);


                                            $Msg = "Renewal was successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Duration => " . $subscriptiondays . "; Amount => " . $amount . "; Subscription Date => " . $subscribe_date . "; Expiry Date => " . $exp_date;

                                            $remote_ip = getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];

                                            #$host = $_SERVER['REMOTE_HOST'];
                                            $remote_host = '';
                                            if ($remote_ip) $remote_host = gethostbyaddr($remote_ip);

                                            LogDetails($network . '(' . $msisdn . ')', $Msg, $msisdn, date('Y-m-d H:i:s'), $remote_ip, $remote_host, 'SUBSCRIPTION RENEWAL', 'System', $db);

                                        } elseif (trim(strtoupper($ret['Status'])) == 'FAILED') {#Send Message
                                            if (trim(strtoupper($ret['errorCode'])) == 'OL404') {
                                                $bal = floatval(str_replace('Insufficient Balance.#~#', '', $ret['errorMessage']));

                                                #$ret=SendAirtelSms($msisdn,$insufficent_balance_msg,$db);
                                            } else {
                                                #$ret=SendAirtelSms($msisdn,$ret['errorMessage'],$db);
                                            }
                                        }
                                    } else {
                                        #echo 'MSISDN: '.$msisdn.' <=> Status: '.$ret['Status'].' <=> Error Code: '.$ret['errorCode'].' <=> Error Message: '.$ret['errorMessage'].'<br>';
                                        $failed++;

                                        $file = fopen('airtel_renewal_error.txt', "a");
                                        fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $ret["Status"] . ",Error Code: " . $ret["errorCode"] . ",Error Message: " . $ret["errorMessage"] . "," . $failed . PHP_EOL);
                                        fclose($file);

                                        if (trim(strtoupper($ret["Status"])) == 'FAILED') {
                                            if ($isnew == 1)#Insert Into Failed Activations
                                            {
                                                $sql = "SELECT msisdn FROM failed_activations WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                                if ($query->num_rows > 0) {
                                                    $db->autocommit(FALSE);

                                                    $sql = "UPDATE failed_activations SET plan='" . $plan . "',activationdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                } else {
                                                    $em = '';

                                                    $db->autocommit(FALSE);

                                                    $sql = "INSERT INTO failed_activations (network,msisdn,email,plan,activationdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                }
                                            } else#cust_failed_charging
                                            {
                                                if (trim(strtoupper($ret['errorCode'])) <> 'OL404')#cust_failed_charging
                                                {
                                                    $sql = "SELECT msisdn FROM cust_failed_charging WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                                    if ($query->num_rows > 0) {
                                                        $db->autocommit(FALSE);
                                                        $sql = "UPDATE cust_failed_charging SET plan='" . $plan . "',chargingdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                        if (!$query = $db->query($sql)) {
                                                            $file = fopen('mysqli_error_log.txt', "a");
                                                            fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                            fclose($file);
                                                        }

                                                        $db->commit();
                                                    } else {
                                                        $em = '';

                                                        $db->autocommit(FALSE);

                                                        $sql = "INSERT INTO cust_failed_charging (network,msisdn,email,plan,chargingdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                        if (!$query = $db->query($sql)) {
                                                            $file = fopen('mysqli_error_log.txt', "a");
                                                            fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                            fclose($file);
                                                        }

                                                        $db->commit();
                                                    }
                                                } else#greyarea
                                                {
                                                    $sql = "SELECT msisdn FROM greyareas WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                                    if ($query->num_rows > 0)#There is active subscription
                                                    {
                                                        $db->autocommit(FALSE);

                                                        $sql = "UPDATE greyareas SET plan='" . $plan . "',chargingdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                        if (!$query = $db->query($sql)) {
                                                            $file = fopen('mysqli_error_log.txt', "a");
                                                            fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                            fclose($file);
                                                        }

                                                        $db->commit();

                                                    } else {
                                                        $db->autocommit(FALSE);

                                                        $em = '';

                                                        $db->autocommit(FALSE);

                                                        $sql = "INSERT INTO greyareas (network,msisdn,email,plan,chargingdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                        if (!$query = $db->query($sql)) {
                                                            $file = fopen('mysqli_error_log.txt', "a");
                                                            fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                            fclose($file);
                                                        }

                                                        $db->commit();
                                                    }
                                                }
                                            }
                                        }

                                        $Msg = "Renewal was NOT  successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Error Code => " . $ret["errorCode"] . "; Error Message => " . $ret["errorMessage"];
                                        $remote_ip = getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];

                                        #$host = $_SERVER['REMOTE_HOST'];
                                        $remote_host = '';
                                        if ($remote_ip) $remote_host = gethostbyaddr($remote_ip);

                                        LogDetails($network . '(' . $msisdn . ')', $Msg, $msisdn, date('Y-m-d H:i:s'), $remote_ip, $remote_host, 'SUBSCRIPTION RENEWAL FAILED', 'System', $db);
                                    }
                                } else {
                                    $failed++;

                                    $file = fopen('airtel_renewal_error.txt', "a");
                                    fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Status: " . $ret["Status"] . ",Error Code: " . $ret["errorCode"] . ",Error Message: " . $ret["errorMessage"] . "," . $failed . PHP_EOL);
                                    fclose($file);

                                    if (trim(strtoupper($ret["Status"])) == 'FAILED') {
                                        if ($isnew == 1)#Insert Into Failed Activations
                                        {
                                            $sql = "SELECT msisdn FROM failed_activations WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                            if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                            if ($query->num_rows > 0) {
                                                $db->autocommit(FALSE);

                                                $sql = "UPDATE failed_activations SET plan='" . $plan . "',activationdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(activationdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                if (!$query = $db->query($sql)) {
                                                    $file = fopen('mysqli_error_log.txt', "a");
                                                    fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                    fclose($file);
                                                }

                                                $db->commit();
                                            } else {
                                                $em = '';

                                                $db->autocommit(FALSE);

                                                $sql = "INSERT INTO failed_activations (network,msisdn,email,plan,activationdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                if (!$query = $db->query($sql)) {
                                                    $file = fopen('mysqli_error_log.txt', "a");
                                                    fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                    fclose($file);
                                                }

                                                $db->commit();
                                            }
                                        } else#cust_failed_charging
                                        {
                                            if (trim(strtoupper($ret['errorCode'])) <> 'OL404')#cust_failed_charging
                                            {
                                                $sql = "SELECT msisdn FROM cust_failed_charging WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                                if ($query->num_rows > 0) {
                                                    $db->autocommit(FALSE);
                                                    $sql = "UPDATE cust_failed_charging SET plan='" . $plan . "',chargingdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                } else {
                                                    $em = '';

                                                    $db->autocommit(FALSE);

                                                    $sql = "INSERT INTO cust_failed_charging (network,msisdn,email,plan,chargingdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                }
                                            } else#greyarea
                                            {
                                                $sql = "SELECT msisdn FROM greyareas WHERE (TRIM(network)='" . $db->escape_string($network) . "') AND (TRIM(msisdn)='" . $db->escape_string($msisdn) . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

                                                if ($query->num_rows > 0)#There is active subscription
                                                {
                                                    $db->autocommit(FALSE);

                                                    $sql = "UPDATE greyareas SET plan='" . $plan . "',chargingdate='" . $subscribe_date . "' WHERE (TRIM(network)='" . $network . "') AND (TRIM(msisdn)='" . $msisdn . "') AND (DATE_FORMAT(chargingdate,'%Y-%m-%d')='" . date('Y-m-d') . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                } else {
                                                    $db->autocommit(FALSE);

                                                    $em = '';

                                                    $db->autocommit(FALSE);

                                                    $sql = "INSERT INTO greyareas (network,msisdn,email,plan,chargingdate) VALUES ('" . $network . "','" . $msisdn . "','" . $em . "','" . $plan . "','" . $subscribe_date . "')";

                                                    if (!$query = $db->query($sql)) {
                                                        $file = fopen('mysqli_error_log.txt', "a");
                                                        fwrite($file, date('Y-m-d H:i:s') . ",AirtelAutoRenewSchedule.php,AutoRenewSubscriptions,ERROR=>" . $db->error . PHP_EOL);
                                                        fclose($file);
                                                    }

                                                    $db->commit();
                                                }
                                            }
                                        }
                                    }

                                    $Msg = "Renewal was NOT  successful. Details: Network => " . $network . "; MSISDN => " . $msisdn . "; Service Plan => " . $plan . "; Error Code => " . $ret["errorCode"] . "; Error Message => " . $ret["errorMessage"];
                                    $remote_ip = getRealIpAddr(); #$_SERVER['REMOTE_ADDR'];

                                    #$host = $_SERVER['REMOTE_HOST'];
                                    $remote_host = '';
                                    if ($remote_ip) $remote_host = gethostbyaddr($remote_ip);

                                    LogDetails($network . '(' . $msisdn . ')', $Msg, $msisdn, date('Y-m-d H:i:s'), $remote_ip, $remote_host, 'SUBSCRIPTION RENEWAL FAILED', 'System', $db);
                                }

                            } catch (Exception $e) {#Array ( [Status] => FAILED [errorCode] => FFF [errorMessage] => Could not connect to host )

                            }
                        }
                    } else {

                        $file = fopen('already_active.txt', "a");
                        fwrite($file, date('Y-m-d H:i:s') . ",MSISDN: " . $msisdn . ",Plan: " . $plan . ",Status: " . $subscriptionstatus . ",Subscribe Date: " . $subscribe_date . ",Expiry Date: " . $exp_date . "," . PHP_EOL);
                        fclose($file);
                    }
                }
            }
        endwhile;
    }

    if (count($arr) > 0) {
        foreach ($arr as $pl => $cnt):
            if (($pl) and intval($cnt) > 0) {
                $db->autocommit(FALSE);

                $sql = 'INSERT INTO autorenewals (network,plan,totalrenewal,renewaldate) VALUES (?,?,?,?)';

                $nt = $db->escape_string($network);
                $pdt = $db->escape_string($subscribe_date);

                $stmt = $db->prepare($sql);/* Prepare statement */

                if ($stmt === false) trigger_error('Wrong SQL: ' . $sql . ' Error: ' . $db->error, E_USER_ERROR);

                /* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
                $stmt->bind_param('ssis', $nt, $pl, $cnt, $pdt);

                $stmt->execute();/* Execute statement */

                $db->commit();
            }
        endforeach;
    }

    return true;
}

?>