<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<head>

<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta http-equiv="refresh" />
 
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
<title>LaffHub | Service Information </title>

<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-theme.min.css"> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"><\/script>')</script> 

 <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>

<style>
  .center {text-align:justify; margin-left: 25px; margin-right: 25px; margin-bottom: auto; margin-top: auto;}
</style>

</head>

<body class="bg-warning" style="color:#dd0000;">
<div class="container">
  <div class="row">
    <div>
    	                
      <div class="hero-unit center">
          <h1 align="center">Service Information</h1>
          <br />
          <h3 align="center"><b>We are sorry, the phone number <span style="color:#000000;"><?php echo $Phone; ?></span> cannot subscribe to this service</b>.</h3>
          
        </div>
        <br />
        
    </div>
  </div>
</div>
   

</body>
</html>