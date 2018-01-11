<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<head>

<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta http-equiv="refresh" content="10; url=<?php echo site_url('Subscribe'); ?>" />
 
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
<title>LaffHub | No Subscription </title>

<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap-theme.min.css"> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"><\/script>')</script> 

 <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>

<style>
  .center {text-align:justify; margin-left: 25px; margin-right: 25px; margin-bottom: auto; margin-top: auto;}
</style>

</head>

<body class="bg-warning">
<div class="container">
  <div class="row">
    <div>
    	                
      <div class="hero-unit center">
          <h1 align="center">No Subscription</h1>
          <br />
          <p align="center"><b>The phone number <?php echo $Phone; ?> is not subscribed to airtel.laffhub.com</b>.</p>
          <p align="center">You will be automatically redirected to the subscription page. If you are not redirected automatically after 10 seconds, follow this <a href="<?php echo site_url('Subscribe'); ?>">link to subscribe</a>.</p>
          
        </div>
        <br />
        
    </div>
  </div>
</div>
   

</body>
</html>