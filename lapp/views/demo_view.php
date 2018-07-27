<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
 <title><?=$title?> - LaffHub</title>  
 <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="<?=$description?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--FAVICON-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  <link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg" color="#ff0000">
  <meta property="og:site_name" content="LaffHub">
  <meta property="og:title" content="<?=$title?>">
<meta property="og:image" content="<?=$thumbnail?>">
<meta property="og:description" content="<?=$description?>">
<meta property="og:url" content="<?=$url?>">
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