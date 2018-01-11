<?php
	$headers = $_SERVER;
	
	foreach ($headers as $header => $value) 
	{
			echo $header.': '.$value.'<br />';
	}
?>