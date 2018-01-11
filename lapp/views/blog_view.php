<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Blog</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<!--<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">-->


<link rel="stylesheet" href="<?php echo base_url();?>iconfont/material-icons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>

<script src="<?php echo base_url();?>js/holder.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script src="<?php echo base_url();?>js/bootbox.min.js"></script>

<script>
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	
	bootstrap_alert = function() {}
	bootstrap_alert.warning = function(message) 
	{
	   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
	}
	
	bootstrap_Success_alert = function() {}
	bootstrap_Success_alert.warning = function(message) 
	{
	   $('#divAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
	}
	
	$(document).ready(function(e) {
        $(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);
    });
</script>
</head>
<body>
<header> <?php include('usernav.php'); ?> </header>

<section class="channel-wrapper">
<div class="container">
<div  class="img-desc">
<div class="channels">
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      <div class="channel-in">
        <div class="list-div-text channel-in ">
          <h4> Whats makes u belive that you are firtile ? </h4>
          <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <p> Artist : </p>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
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
    
       <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      <div class="channel-in">
        <div class="list-div-text channel-in ">
          <h4> Whats makes u belive that you are firtile ? </h4>
          <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <p> Artist : </p>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
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

    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
      <div class="channel-in">
        <div class="list-div-text channel-in ">
          <h4> Whats makes u belive that you are firtile ? </h4>
          <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
              <p> Artist : </p>
            </div>
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
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
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
</body>
</html>