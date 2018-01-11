<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LaffHub</title>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--FAVICON-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  <link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg" color="#ff0000">
  <meta name="theme-color" content="#ffffff">

</head>


  <body>

  <?php

  foreach ($header as $name => $value) {
      echo "$name: $value </br>";
  }
  
  print"<table border=0>";
    foreach ($_SERVER as $key=>$val )
       {
         echo "<tr><td>".$key."</td><td>" .$val."</tr>";
        }
    print"</table>";

  ?>

  </body>
</html>