<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>MOOV | Registration</title>
    
    <?php include('links.php'); ?>
  
</head>
<body class="body">
	<?php include('pubnav.php'); ?>
 
 <div align="center" class="container">      
 	<br><br><br><br>
    
    <div class="panel panel-info" style="">
  		<div align="center" class="panel-heading" style="font-size:18px; text-transform:uppercase;"><b>Registration Notification</b></div>
        
        <div class="panel-body">
                   
                      <div align="center" class="row">
                              
                            <div align="center" class="alert"  style="background:#F2DEDE; color:#A94481; width:50%; margin-top:100px; margin-bottom:100px;">
                                <?php echo $RegisterInfo; ?>
                              </div>    
                      </div>
        
                  
        </div>
  		
         <div class="panel-footer" align="center">
            <strong>Copyright &copy; <?php echo date('Y');?> <a href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
          </div><!--FOOTER-->
	</div>
 
   </div><!-- /.container --> 
    
    
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->    
    <script src="<?php echo base_url(); ?>js/ie10-viewport-bug-workaround.js"></script>
    
    <?php include("footer.php"); ?>
</body>
</html>