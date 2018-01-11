<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
 
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

<title>LaffHub | Playing - <?php echo $title; ?></title>

<meta property="og:url" content="https://content.jwplatform.com/previews/<?php echo $filename; ?>-<?php echo $player_id; ?>">
<meta property="og:title" content="<?php echo $title; ?>">
<meta property="og:image" content="https://assets-jpcust.jwpsrv.com/thumbs/<?php echo $filename; ?>-720.jpg">
<meta property="og:description" content="<?php echo $title; ?>">
<meta property="og:type" content="video">
<meta property="og:video" content="https://content.jwplatform.com/players/<?php echo $filename; ?>.swf">
<meta property="og:video:secure_url" content="https://content.jwplatform.com/players/<?php echo $filename; ?>.swf">
<meta property="og:video:type" content="application/x-shockwave-flash">
<meta property="og:video:width" content="320">
<meta property="og:video:height" content="260">
<meta name="twitter:card" content="player">
<meta name="twitter:player" content="https://content.jwplatform.com/players/<?php echo $filename; ?>-<?php echo $player_id; ?>.html">
<meta name="twitter:player:width" content="320">
<meta name="twitter:player:height" content="260">
<meta name="twitter:player:stream" content="https://content.jwplatform.com/videos/<?php echo $filename; ?>-480.mp4">
<meta name="twitter:player:stream:content_type" content="video/mp4; codecs=&quot;avc1.42E01E, mp4a.40.2&quot;">

<style type="text/css">
	body { margin:0 auto; padding:0; background:#EEE; overflow: hidden; }
	#title { font:bold 24px/36px Arial, sans-serif; color:#000; margin:40px auto 10px auto; display:none; text-shadow:#FFF 2px 2px 0; }
	#description { font:13px/20px Arial, sans-serif; margin:15px auto; display:none; text-shadow:#FFF 1px 1px 0; }
</style>

<script></script>
</head>

<body>
	<h1 id="title"><?php echo $title; ?></h1>
    <div id="botr_<?php echo $filename; ?>_<?php echo $player_id; ?>_div"></div>
    <script type="text/javascript" src="//content.jwplatform.com/players/<?php echo $filename; ?>-<?php echo $player_id; ?>.js"></script>
    <p id="description"><?php echo $title; ?></p>
    
	<script>
        if (window == window.top) {
            document.getElementById("title").style.display = "block";
            document.getElementById("description").style.display = "block";
            document.body.style.width = "90%";
        } else {
          /** Used to pass play/pause messages parent iframe via postMessage **/
          window.addEventListener("message", function(evt) {
            switch (evt.data) {
              case "play":
                jwplayer().play();
                break;
              case "pause":
                jwplayer().pause();
                break;
            }
          });
        }
    </script>
</body>
</html>



