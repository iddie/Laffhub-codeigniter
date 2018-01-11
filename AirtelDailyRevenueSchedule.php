<?php

date_default_timezone_set('Africa/Lagos');
require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';

include('common.php');

$ret=GetDailySubscriptions($db);

function GetDailySubscriptions($db)
{
    $dt=date('Y-m-d',strtotime('-1 day'));
    $rdt=date('jS M Y',strtotime('-1 day'));
    $network='Airtel';

    #$dt='2017-08-24';
    #$rdt='24th Aug 2017';

    #Get Plans
    $plans=array();

    $sql="SELECT DISTINCT plan FROM `subscription_history` WHERE (TRIM(network)='".$network."') AND (DATE_FORMAT(`subscribe_date`,'%Y-%m-%d')='".$dt."') AND (TRIM(subscription_status)='OK')  ORDER BY plan";

    if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

    while($row = $query->fetch_assoc()) if (isset($row['plan'])) $plans[]=$row['plan'];

    if (count($plans)>0)
    {
        $table='<table style="border:solid thin;" cellpadding="5" cellspacing="0">
			<tr bgcolor="#eeeeee">
				<th align="center" style="border:solid thin;">NETWORK</th>
				<th align="center" style="border:solid thin;">PLAN</th>
				<th align="center" style="border:solid thin;">REVENUE</th>
			</tr>
		';

        $total=0; $i=0;

        foreach($plans as $plan):
            #echo $plan.'<br>';

            $sql="SELECT COUNT(msisdn) AS SubscriberCount, amount FROM `subscription_history` WHERE (DATE_FORMAT(`subscribe_date`,'%Y-%m-%d')='".$dt."') AND (TRIM(plan)='".$plan."') AND (TRIM(subscription_status)='OK') ";

            if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');

            $cnt=0;

            $row = $query->fetch_assoc();

            if ($row['SubscriberCount']) $cnt=$row['SubscriberCount'];
            if ($row['amount']) $amount=$row['amount'];
            $revenue = $cnt * $amount;

            $table .=
                '<tr>
					<td align="center" style="border-style:solid; border-width:thin;">'.$network.'</td>
					<td align="center" style="border-style:solid; border-width:thin;">'.$plan.'</td>
					<td align="center" style="border-style:solid; border-width:thin;">'.number_format($revenue,0).'</td>
				</tr>';

            $i++;

            $total += $revenue;
        endforeach;

        $table .='
			<tr bgcolor="#eeeeee">
				<th colspan="2" align="center" style="border:solid thin;">TOTAL REVENUE</th>
				<th align="center" style="border:solid thin;">'.number_format($total,0).'</th>
			</tr>
		';

        $table .="</table>";

        #echo $table;

        $from='admin@laffhub.com';
        $to='o.dania@efluxz.com,davidumoh@icloud.com,ade@efluxz.com,david@laffhub.com,adetutu.adigwe@efluxz.com,adetola@efluxz.com';
        $subject='Airtel LaffHub Daily Revenue Report For '.$rdt;

        $message='
			<img src="emaillogo.png" width="100" alt="LaffHub" title="LaffHub" />
			<br><br>
			Hello,<br><br>
			
			Below is Airtel Laffhub Revenue report for '.$rdt.':<br><br>'.
            $table.'<br>
							
			<b><font color="#DB5832">laffhub.com</font></b>';

        $altMessage='';

        $v=SendEmail($from,$to,$subject,$Cc,$message,$altMessage,'');
    }

    $query->free();

    return true;
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
    $mail->Port = 2525;//Set the SMTP port number - likely to be 25, 465 or 587	- 25, 2525, or 587
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;//Whether to use SMTP authentication
    $mail->Username = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";//Username to use for SMTP authentication
    $mail->Password = "b0d395e5-1f76-4124-b6dc-cc2b1f9d6516";	#- efec7a8f-a894-4c2a-982f-b3e6deab999c
    $mail->setFrom($from, 'LaffHub');//Set who the message is to be sent from
    $mail->addReplyTo($from, 'LaffHub');//Set an alternative reply-to address

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
        $file = fopen('emailerror.txt',"a"); fwrite($file,date('d M Y H:i')." => Mailer Error: " . $mail->ErrorInfo.PHP_EOL); fclose($file);

        return "MAILER ERROR: ". $mail->ErrorInfo;

    } else {
        return 'OK';
    }
}


$db->close();

?>