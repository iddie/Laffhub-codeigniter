<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>LaffHub::Confirmation Page</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
     <?php include('frontlink.php'); ?>
     
     <link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">

<script>
$(document).ready(function(e) {
    $('#btnSignIn').mouseover(function(e) {
        $(this).css('background-color','#B98619');
    });
	
	$('#btnSignIn').mouseout(function(e) {
        $(this).css('background-color','#265A88');
    });
});
</script>
  </head>
  <body>
  	<header>
<nav class="navbar navbar-default">
    <div class="container"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="<?php echo site_url('Home'); ?>"><img src="<?php echo base_url();?>images/logo.png" class="img-responsive"></a> </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li class="search-box">
            <form action="" autocomplete="on">
              <input id="search" name="search" type="text" placeholder="What're we looking for ?">
              <input id="search_submit" value="Rechercher" type="submit">
            </form>
          </li>
          <li><a href="<?php echo site_url('Home');?>"><i class="fa fa-sign-in"></i> Login</a></li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
    <!-- /.container-fluid --> 
  </nav>
    
    
    </header>
    <section class="home-slider" >
    
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" >
 
  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="<?php echo base_url();?>images/banner2.jpg" alt="...">
    </div>
       
    <div class="carousel-caption"  style="height:90%; width:60%; left:20%;">
      <div class="login-form">
     	<div class="<?php echo $PanelTheme; ?>">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><?php echo $ConfirmTitle; ?></div>
                <div class="panel-body">
                    <div class="box-body">
                        <form class="form-horizontal">
                        <div class="form-group">
                           <div style="text-shadow:none; color:#727272; margin:20px;"><?php echo $ConfirmInfo; ?></div> 
                       </div>
                     
                     </div>
                   </form>     
                        
                       
             
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
               </div>
                                   
                 
                <div align="center" style="margin-top:50px; ">                
               <a id="btnSignIn" title="Back To Home Page" style="width:auto; background-color:#265A88; color:#ffffff;" href="<?php echo site_url('Home'); ?>" class="btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="glyphicon glyphicon-home"></i> Home&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </a>
                
                
                </div>
              
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
    
     <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
  </body>
</html>