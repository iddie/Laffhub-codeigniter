<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?=$title?>- LaffHub</title>
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="<?=$description?>">
  <!--FAVICON-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png"
    sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png"
    sizes="16x16">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  <link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg"
    color="#ff0000">
  <meta property="og:site_name" content="LaffHub">
  <meta property="og:title" content="<?=$title?>">
  <meta property="og:image" content="<?=$thumbnail?>">
  <meta property="og:description" content="<?=$description?>">
  <meta property="og:url" content="<?=$url?>">
  <meta property="og:type" content="video.other">
  <!--Twitter -->
  <meta property="fb:app_id" content="2113994645510768">
  <meta name="twitter:card" content="player">
  <meta name="twitter:site" content="@laffhub">
  <meta name="twitter:url" content="<?=$url?>">
  <meta name="twitter:title" content="<?=$title?>">
  <meta name="twitter:description" content="<?=$description?>">
  <meta name="twitter:image" content="<?=$thumbnail?>">
  <meta name="twitter:app:url:googleplay" content="<?=$url?>">
  <meta name="twitter:player" content="<?=$url?>">
  <meta name="twitter:player:width" content="1280">
  <meta name="twitter:player:height" content="720">
  <meta name="theme-color" content="#ffffff">
  <!--/FAVICON-->

  <!--CSS CRITICAL-->
  <style>
    html {
      background: #000;
    }

    body {
      background: #000;
      opacity: 0;
    }
  </style>
  <!--/CSS CRITICAL-->
</head>

<body>
  Redirecting..Please wait
  <script>
    window.location.href = '<?=$url?>'
  </script>
</body>

</html>