<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>LaffHub::Reset Password</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
   <?php include('frontlink.php'); ?>
   
   <link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">


<script>
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

var Title='<font color="#FFFFFF">Password Reset Help</font>';
var process_alert='<?php echo $Alert; ?>';
var Success='<?php echo trim($Success); ?>';
var m='';

$(document).ready(function(e) {
	$(function() {
					// clear out plugin default styling
					$.blockUI.defaults.css = {};
				});
		
	$(document).ajaxStop($.unblockUI);
	
	if (Success.toLowerCase()=='no')
	{
		$('#btnResetPwd').prop('disabled',true);
		
		var m=process_alert;
		
		bootstrap_alert.warning(m);
		bootbox.alert({ 
			size: 'small', message: m, title:Title,
			buttons: { ok: { label: "Close", className: "btn-danger" } },
			callback:function(){
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});
	}
	
    $('#btnResetPwd').mouseover(function(e) {
        $(this).css('background-color','#B98619');
    });
	
	$('#btnResetPwd').mouseout(function(e) {
        $(this).css('background-color','#265A88');
    });
	
	$('#btnResetPwd').click(function(e) 
	{						
		try
		{
			if (!CheckForm()) return false;
					
			//Send values here
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Resetting Password. Please Wait...</b></p>',theme: true,baseZ: 2000});
							
			//Make Ajax Request
			var ut=$.trim($('#lblUserType').html());
			var em=$.trim($('#lblEmail').html());
								
			var mydata={usertype:ut, email:em,pwd:sha512($('#txtPwd').val())};
					
			$.ajax({
				url: "<?php echo site_url('Resetpwd/EditPwd');?>",
				data: mydata,
				type: 'POST',
				dataType: 'text',
				complete: function(xhr, textStatus) {
					//$.unblockUI;
				},
				success: function(data,status,xhr) {	
					$.unblockUI;
						
					if ($.trim(data.toUpperCase())=='OK')
					{
						m='Password Reset Was Successful.';
						bootstrap_Success_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								window.location.href='<?php echo site_url("Home");?>';
							}
						});
					}else
					{
						$.unblockUI;
						
						m=data;
											
						bootstrap_login_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}					
				},
				error:  function(xhr,status,error) {
						$.unblockUI;
						m='Error '+ xhr.status + ' Occurred: ' + error;
				
						bootstrap_login_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
			});	
			
			//$.unblockUI();
		}catch(e)
		{
			m='Reset Password Button Click ERROR:\n'+e;
				
			bootstrap_login_alert.warning(m);
			bootbox.alert({ 
			size: 'small', message: m, title:Title,
			buttons: { ok: { label: "Close", className: "btn-danger" } },
			callback:function(){
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});
		}
	});
				
	function CheckForm()
	{
			
		try
		{
			var ut=$.trim($('#lblUserType').html());
			var em=$.trim($('#lblEmail').html());
			var pwd=$('#txtPwd').val();
			var cpwd=$('#txtConfirmPwd').val();
			
			//User Type
			if (!ut)
			{
				m='User type field is not displaying. Refresh the screen. If it still does not show, click on the link in your email again.';
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				return false;
			}
			
			//Email
			if (!em)
			{
				m='Your email is not displaying. Refresh the screen. If it still does not show, click on the link in your email again.';
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				return false;
			}
			
			//Password
			if (!$.trim(pwd))
			{
				m='Password field must not be blank.';
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtPwd').focus(); return false;
			}
			
			if (pwd.length<6)
			{
				m='Minimum password size is six (6) characters.';
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtPwd').focus(); return false;
			}
			
			//Confirm Password
			if (!$.trim(cpwd))
			{
				m='Confirm password field must not be blank.';
				//$('#status').html(m); alert(m); 
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtConfirmPwd').focus();   return false;
			}
			
			if (pwd != cpwd)
			{
				m='New password and confirming password fields do not match.';
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtConfirmPwd').focus();   return false;
			}
			
			//Confirm Registration
		if (!confirm('Do you want to proceed with the password reset? (Click OK to proceed or CANCEL to abort)'))
		{
			return false;
		}
				
			return true;
		}catch(e)
		{
			$.unblockUI();
			m='CheckForm ERROR:\n'+e;
			
			bootstrap_alert.warning(m);
			bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
			
			return false;
		}
	}
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
                                <h3 align="center" class=" makebold label-info " style="width:100%; margin-top:-30px; margin-bottom:30px;">Password Reset</h3>
                                
                                <p align="center" class="size-12 nobold" style="color:#4C4949; text-shadow:none; font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; ">Fields With <span class="redtext">*</span> Are Required!</p>                              
                               
                                <div class="form-group" title="Type Of User">
                                    <label class="col-sm-4 control-label" for="lblUserType" style="color:#4C4949;text-shadow:none;">Type Of User</label>
                                    <div class="col-sm-8">
                                       <label style="color:#990000; text-align:left;text-shadow:none;"  class="form-control" id="lblUserType"><?php echo $UserType; ?></label>
                                    </div>
                                  </div>
                                   
                                  <div class="form-group" title="Your Email Address">
                                    <label style="margin-top:5px;color:#4C4949;text-shadow:none;" class="col-sm-4 control-label" for="lblEmail" >Email</label>
                                    <div class="col-sm-8">
                                      <label style="color:#990000; text-align:left;text-shadow:none;" class="form-control" id="lblEmail"><?php echo $Email; ?></label>
                                    </div>
                                  </div>
                                  
                                   <div class="form-group" title="Enter Your New Password">
                                    <label style="margin-top:5px;color:#4C4949;text-shadow:none;" class="col-sm-4 control-label" for="txtPwd">New&nbsp;Password<span class="redtext">*</span></label>
                                    <div class="col-sm-8">
                                      <input style="text-shadow:none;" id="txtPwd" type="password" class="form-control padright" placeholder="New Password">
                                        <span class="glyphicon glyphicon-lock form-control-feedback" style="margin-right:12px;"></span>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group" title="Confirm Your New Password">
                                    <label style="margin-top:5px;color:#4C4949;text-shadow:none;" class="col-sm-4 control-label" for="txtConfirmPwd">Confirm&nbsp;New&nbsp;Password<span class="redtext">*</span></label>
                                    <div class="col-sm-8">
                                      <input style="text-shadow:none;" id="txtConfirmPwd" type="password" class="form-control padright" placeholder="Confirm New Password">
                                        <span class="glyphicon glyphicon-lock form-control-feedback" style="margin-right:12px;"></span>
                                    </div>
                                  </div>
                                <br>
            
                                         
                                  <div align="center">
                                       <div id = "divAlert"></div>
                                  </div>
           
                                  <div class="row">  
                                     <div class="col-xs-3">&nbsp;</div>
                                           
                                    <!-- /.col -->
                                    <div class="col-xs-5" title="Click To Submit">
                                      <button id="btnResetPwd" type="button" class="btn btn-primary btn-block btn-flat"><i class="glyphicon glyphicon-send"></i>&nbsp;Reset Password Request</button>
                                    </div>
                                    <!-- /.col -->
                                             
                                   
                                    <div class="col-xs-4">
                                      <div title="Click To Refresh The Entries">
                                          <button onClick="window.location.reload(true);" type="button" class="btn btn-warning btn-block btn-flat"> <i class="glyphicon glyphicon-refresh"></i>&nbsp;&nbsp;Refresh&nbsp;&nbsp;</button>            
                                      </div>
                                    </div>
                                  </div>
                                  
                                  <div align="center" class="row">
                                     
                                     
                                     <span style="float:right; color:#4C4949; text-shadow:none;" class="text-center space-top size-12">I Have An Account. <a href="<?php echo site_url("Login"); ?>"><b class="redtext">Login</b></a>.</span>
                                   </div>
    						</form>
              
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
    <script src="<?php echo base_url();?>js/jquery.blockUI.js"></script> 
    
  </body>
</html>