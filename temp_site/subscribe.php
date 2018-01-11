<?php
$toEmail = "info@creativecrunk.com"; /*Replace it recipient email address*/
if(isset($_POST['postEmail'])){
$subject 		= 'Email For Subcribe...'; //Subject line for emails

//Let's clean harmful characters from raw POST data using PHP Sanitize filters. 
$postEmail 		= filter_var($_POST["postEmail"], FILTER_SANITIZE_EMAIL);
//$postEmail 		= $_POST['postEmail'];

//similar validation applies to all data, unless you want to replace with some other strong validation.
if(strlen($postEmail)<1)
{
	header('HTTP/1.1 500 Email Field Empty'); 
	exit();
}
//Finally we can proceed with PHP email.
$headers = 'From: '.$postEmail.'' . "\r\n" .
    'Reply-To: '.$postEmail.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	
//Email Body
$Body = "";
$Body .= "Email Address: ";
$Body .= $postEmail;
$Body .= "\n";
$Body .= "\n";


@$sentMail = mail($toEmail, $subject, $Body .' Your Subscriber', $headers);

if(!$sentMail)
	{
		header('HTTP/1.1 500 Couldnot send mail! Sorry..'); 
		exit();
	}else{
		echo 'Thank you for your Subscribe';
	}
} // isset sub-form
if(isset($_POST['cont-name']) && isset($_POST['cont-email'])){
	$subject 		= 'Email For inquiries...'; //Subject line for emails

	//Let's clean harmful characters from raw POST data using PHP Sanitize filters. 
	$cont_name= filter_var($_POST["cont-name"], FILTER_SANITIZE_EMAIL);
	$cont_email = filter_var($_POST["cont-email"], FILTER_SANITIZE_EMAIL);
	$cont_message = filter_var($_POST["cont-message"], FILTER_SANITIZE_EMAIL);
	//similar validation applies to all data, unless you want to replace with some other strong validation.
	if(strlen($cont_message)<1 )
	{
		header('HTTP/1.1 500 Message Field Empty'); 
		exit();
	}
	//Finally we can proceed with PHP email.
	$headers = 'From: '.$cont_email.'' . "\r\n" .
		'Reply-To: '.$cont_email.'' . "\r\n" .
		'X-Mailer: PHP/' . phpversion();
		
	//Email Body
	$Body = "";
	$Body .= "Email Address: ";
	$Body .= $cont_email;
	$Body .= "\n";
	$Body .= "\n";
	$Body .= "Name : ";
	$Body .= $cont_name;
	$Body .= "\n";
	$Body .= "\n";
	$Body .= "Message";
	$Body .= $cont_message;
	$Body .= "\n";
	$Body .= "\n";


	@$sentMail = mail($toEmail, $subject, $Body .' Your Subscriber', $headers);

	if(!$sentMail)
		{
			header('HTTP/1.1 500 Couldnot send mail! Sorry..'); 
			exit();
		}else{
			echo 'Thank you! We will contact you soon';
		}
}// end isset cont-form
?>