<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

<title>LaffHub | Playing - <?php echo $title; ?></title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"><\/script>')</script>

<script src="<?php echo base_url();?>js/jwplayer.js"></script>

<style type="text/css">
	body { margin:0 auto; padding:0; background:#EEE; overflow: hidden; }
	#title { font:bold 24px/36px Arial, sans-serif; color:#000; margin:40px auto 10px auto; display:none; text-shadow:#FFF 2px 2px 0; }
	#description { font:13px/20px Arial, sans-serif; margin:15px auto; display:none; text-shadow:#FFF 1px 1px 0; }
</style>

<script></script>
</head>

<body>
<div align="center">    
    <?php
		if ($filename && $category && $domain_name)
		{
			
			echo '
				<h1 id="title" title="'.$description.'">'.$title.'</h1>
				<div id="player" title="'.$description.'">Video is loading. Please wait...</div>
				';
		}else
		{
			$this->load->view('notfound',$data);
		}
	?>
    
    
    <script>
    if (window == window.top) {
       document.getElementById("title").style.display = "block";
       //document.getElementById("description").style.display = "block";
       // document.body.style.width = "90%";
    }
	
	var domainname='<?php echo $domain_name; ?>';
	var filename='<?php echo $filename; ?>';
	var category='<?php echo $category; ?>';
	
	var arr = filename.split('.');
	var ext=$.trim(arr[arr.length-1]);				
	var fn=filename.replace('.'+ext,'');
	var preview_url='https://'+domainname+'/'+category+'/'+fn;
    
	//fn.'_360p.'.$ext
	jwplayer.key='<?php echo $jwplayer_key; ?>';
	jwplayer("player").setup({
	 image: "",
	  sources: [
		{ file: preview_url+"_360p."+ext, label: "360p SD" },
		 { file: preview_url+"_480p."+ext, label: "480p SD" },
		{ file: preview_url+"_720p."+ext, label: "720p HD" }
	  ],
	 width: "100%",
	 aspectratio: "4:3"
	});
	
    </script>
 </div>
 
</body>
</html>



