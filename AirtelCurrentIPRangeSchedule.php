<?php

date_default_timezone_set('Africa/Lagos');
require 'phpmailer/class.phpmailer.php';
require 'phpmailer/class.smtp.php';

#$db = new mysqli('localhost', 'root', '', 'laffhubdb');
$db = new mysqli('localhost', 'laffhub_laffuser', 'vUzm6Nh^^y*v', 'laffhub_laffhubdb');

if($db->connect_errno > 0) die('Unable to connect to database [' . $db->connect_error . ']');

$ret=GetDailySubscriptions($db);

function GetDailySubscriptions($db)
{
	$sql="SELECT `network`,`ip` FROM isp_info ORDER BY network,ip";
	
	if (!$query = $db->query($sql)) die('There was an error running the query [' . $db->error . ']');			
	
	
	
	if ($query->num_rows > 0 )
	{
		$table='<table style="border:solid thin;" cellpadding="5" cellspacing="0">
			<tr bgcolor="#eeeeee">
				<th align="center" style="border:solid thin;">NETWORK</th>
				<th align="center" style="border:solid thin;">IP</th>
			</tr>
		';
		
		$i=0;
		
		while($row = $query->fetch_assoc())
		{
			$td='';
			
			$nt=$row['network']; $ip=$row['ip'];
			
			if ($nt and $cnt)
			{
				$td='<tr>
						<td align="center" style="border-style:solid; border-width:thin;">'.$nt.'</td>
						<td align="center" style="border-style:solid; border-width:thin;">'.$ip.'</td>
					</tr>';
				
				$table .= $td; 
				
				$i++;
			}
		}
		
		$table .="</table>";
		
		if ($i>0) #Send email
		{
			#echo $table;
			
			$from='admin@laffhub.com';
			$to='idongesit_a@yahoo.com,nsikakj@gmail.com,o.dania@efluxz.com';			
			$subject='Telco DNS IP';
			$Cc='';
			
			$message='
				<img src="emaillogo.png" width="100" alt="LaffHub" title="LaffHub" />
				<br><br>
				Hello,<br><br>
				
				Below is the current Telco DNS IPs report:<br><br>'.
				$table.'<br>
								
				<b><font color="#DB5832">laffhub.com</font></b>';
				
				$altMessage='';
				
			$v=SendEmail($from,$to,$subject,$Cc,$message,$altMessage,'');
		}	
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