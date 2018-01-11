<?php
	date_default_timezone_set('Africa/Lagos');
	error_reporting(E_ALL); ini_set('display_errors', 1);
	
	include('class.smpp.php');

	$src  = "Laffhub"; // or text 
	$dst  = "2348023351689";
	$message = "You have successfully activated your Daily Laffhub subscription. Watch 3 videos at www.laffhub.com valid for 1day. NO DATA COST. To opt out, text OUT to 2001";

	$s = new smpp();
	$s->debug=1;
	
	$Username='LAFFHUB';
	$Password='S@Wc#5v';

	// $host,$port,$system_id,$password
	$s->open("172.24.4.12", 31110, $Username, $Password);

	// $source_addr,$destintation_addr,$short_message,$utf=0,$flash=0
	$s->send_long($src, $dst, $message);

	/* To send unicode 
	$utf = true;
	$message = iconv('Windows-1256','UTF-16BE',$message);
	$s->send_long($src, $dst, $message, $utf);
	*/

	$s->close();
	
	echo 'Message Sent';
	?>