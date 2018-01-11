<?php

set_time_limit(0);
date_default_timezone_set('Africa/Lagos');

#error_reporting(E_ALL); ini_set('display_errors', 1); 

require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';
include('common.php');

$network='Airtel';

UpdateDaysActiveRecords($network,$db);

function UpdateDaysActiveRecords($network,$db)
{
    $dt=date('2017-11-21');
    // $dt=date('Y-m-d');
    #$dt=date('Y-m-d',strtotime('-1 day'));

    $tot=0; $n20=0; $n100=0; $n200=0; $n500=0;

    $new=GetNewSubscribers($db,$dt,$network);#Get new subscribers
    $cancelled=GetCancelledSubscribers($db,$dt,$network);#Get cancelled subscribers
    $greyarea=GetGreyAreas($db,$dt,$network);#Get cancelled subscribers
    $failedactivations=GetFailedActivations($db,$dt,$network);#Get cancelled subscribers
    $failedchargings=GetFailedChargings($db,$dt,$network);#Get cancelled subscribers
    $trial=GetTrials($db,$dt,$network);
    $successfulsubscription=GetSuccessfulSubscription($db,$dt,$network);

    #Compute revenue
    $sql="SELECT DATE_FORMAT(subscribe_date,'%Y-%m-%d') AS tdt, plan,SUM(amount) AS PlanTotal FROM subscription_history WHERE (TRIM(network)='".$network."') AND (TRIM(subscription_status)='OK') AND (DATE_FORMAT(subscribe_date,'%Y-%m-%d') = '".$dt."') GROUP BY plan ORDER BY subscribe_date DESC;";

    if(!$query = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

    if ($query->num_rows > 0)
    {
        while ($row = $query->fetch_assoc()):
            $dt=$row['tdt']; $plan=$row['plan']; $amt=floatval($row['PlanTotal']);

            if (isset($plan))#0-Date,1-Plan,2-Amount
            {
                if (strtolower(trim($plan))=='daily')
                {
                    $n20 = $amt;
                    $tot += $n20;
                }

                if (strtolower(trim($plan))=='weekly')
                {
                    $n100 = $amt;
                    $tot += $n100;
                }

                if (strtolower(trim($plan))=='monthly')
                {
                    $n200 = $amt;
                    $tot += $n200;
                }

                if (strtolower(trim($plan))=='unlimited')
                {
                    $n500 = $amt;
                    $tot += $n500;
                }
            }

            #echo $key.' => '.$dt.' => '.$plan.' => '.$amt.'<br>';
        endwhile;
    }


    #Active Subscribers
    $sql="SELECT COUNT(msisdn) AS Total FROM subscriptions WHERE (TRIM(network)='".$network."') AND subscriptionstatus=1";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');


    $charging_success=0; $allchargings=0;

    if ($query->num_rows > 0 )
    {
        $rw = $query->fetch_assoc();

        #echo 'Active='.$rw['Total'].'<br>New='.$new.'<br>Cancelled='.$cancelled; exit();

        if ($rw['Total'])
        {
            #SUCCESS CHARGE=(SUCCESSFUL SUBSCRIPTION)/(SUCCESSFUL SUBSCRIPTION + FAILED ACTIVATIONS + CUSTOMERS WITH FAILED CHARGING + GREY AREA)
            $allchargings += floatval($successfulsubscription + $failedactivations + $failedchargings + $greyarea);

            if (floatval($allchargings) > 0) $charging_success=floatval($successfulsubscription)/floatval($successfulsubscription + $failedactivations + $failedchargings + $greyarea);

            $charging_success=$charging_success * 100;


            #Update
            $db->autocommit(FALSE);

            $sql = "SELECT * FROM airtel_daily_revenue WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')='".$dt."'";

            if(!$result = $db->query($sql)) die('There was an error running the query ['.$db->error.']');

            if ($result->num_rows > 0 )
            {
                $sql="UPDATE airtel_daily_revenue SET active=?,newsub=?,cancelled=?,failed_activation=?,custfailedcharging=?,greyarea=?,trial=?,charging_success=?,revenue=?,N20total=?,N100total=?,N200total=?,N500total=? WHERE DATE_FORMAT(subscribe_date,'%Y-%m-%d')=?";

                $stmt = $db->prepare($sql);/* Prepare statement */

                if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);

                /* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
                $stmt->bind_param('iiiiiiiiddddds',$rw['Total'],$new,$cancelled,$failedactivations,$failedchargings,$greyarea,$trial,$charging_success,$tot,$n20,$n100,$n200,$n500,$dt);
            }else
            {
                $sql='INSERT INTO airtel_daily_revenue SET active=?,newsub=?,cancelled=?,failed_activation=?,custfailedcharging=?,greyarea=?,trial=?,charging_success=?,revenue=?,N20total=?,N100total=?,N200total=?,N500total=?,subscribe_date=?';

                $stmt = $db->prepare($sql);/* Prepare statement */

                if ($stmt === false) trigger_error('Wrong SQL: '.$sql.' Error: '.$db->error, E_USER_ERROR);

                /* Bind parameters. TYpes: s = string, i = integer, d = double,  b = blob */
                $stmt->bind_param('iiiiiiiiddddds',$rw['Total'],$new,$cancelled,$failedactivations,$failedchargings,$greyarea,$trial,$charging_success,$tot,$n20,$n100,$n200,$n500,$dt);
            }

            $stmt->execute();/* Execute statement */
            $db->commit();

            // $file = fopen('general_log.txt',"a"); fwrite($file, date('Y-m-d H:i:s').",Updated airtel_daily_revenue active record,Record Count=".$rw['Total'].",Report Date=".$dt.PHP_EOL); fclose($file);
        }
    }

    $query->free();

    return true;
}
?>