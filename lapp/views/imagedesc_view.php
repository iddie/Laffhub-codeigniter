<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Iamge Description</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
</head>
<body>
<header> <?php include('usernav.php'); ?> </header>

<section class="channel-wrapper">
<div class="container">
<div  class="img-desc">
<div class="channels">
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="channel-in">
        <div class="list-div-text channel-in ">
          <h4> Whats makes u belive that you are firtile ? </h4>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <p> Artist : </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <p> Drama , Hindi , 2016 </p>
            </div>
          </div>
        </div>
        <img src="images/l1.jpg" class="img-responsive">
        <div class="list-div-text channel-in ">
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <a href="#" style="color:#db5832;"> Read More </a></p>
        </div>
      </div>
      
      
    </div>
    
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
      <div class="channel-in">
        <div class="list-div-text channel-in ">
          <h4> Whats makes u belive that you are firtile ? </h4>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <p> Artist : </p>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <p> Drama , Hindi , 2016 </p>
            </div>
          </div>
        </div>
        <img src="images/l1.jpg" class="img-responsive">
        <div class="list-div-text channel-in ">
          <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. <a href="#" style="color:#db5832;"> Read More </a></p>
        </div>
      </div>
      
      
    </div>
  </div>
</div>
</div>
</section>

<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
</body>
</html>