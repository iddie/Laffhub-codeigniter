<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub</title>
    
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">-->
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/homestyle.css">
   <link href="<?php echo base_url();?>css/form-elements.css" rel="stylesheet">
   
   
   	 <?php include('links.php'); ?>
    
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url();?>images/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url();?>images/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url();?>images/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>images/apple-touch-icon-57-precomposed.png">
    
  </head>
<!-- NAVBAR
================================================== -->
  <body>
    <?php include('header.php'); ?>
            <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <a href="<?php echo site_url('Home'); ?>"><img src="<?php echo base_url();?>images/logo.png" height="130px" style="margin-top:30px;"></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h2>Login to LaffHub</h2>
                            		<h4 class="redtext">Enter your username and password to sign in:</h4>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-key"></i>
                        		</div>
                            </div>
                            
                            <div class="form-bottom">
			                    <form role="form" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="txtLoginUsername">Username</label>
			                        	<input type="text" name="txtLoginUsername" placeholder="Username..." class="form-username form-control" id="txtLoginUsername">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="txtLoginPwd">Password</label>
			                        	<input type="password" name="txtLoginPwd" placeholder="Password..." class="form-password form-control" id="txtLoginPwd">
			                        </div>
			                        <button style="background-color:#DB5832" id="btnLogin" type="button" class="btn makebold">Sign In!</button>
			                    </form>
		                    </div>
                            
                            <div class="form-bottom">
                            	<!--<a href="<?php #echo base_url();?>doc/html/HealthyLiving.html" target="new">HTML Help</a>&nbsp;&nbsp;|&nbsp&nbsp;
                                <a href="<?php #echo base_url();?>doc/pdf/Healthy_Living_doc.pdf" target="new">PDF Help</a>-->
                            </div>
                        </div>
                    </div>
                   
                </div>
       
        <!-- Javascript -->
        <script src="<?php echo base_url();?>js/jquery.backstretch.js"></script>
        <script src="<?php echo base_url();?>js/scripts.js"></script>
         
        <!--[if lt IE 10]>
            <script src="<?php #echo base_url();?>js/placeholder.js"></script>
        <![endif]-->
  </body>
</html>
