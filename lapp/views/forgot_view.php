<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>:: LaffHub :: Reset Password Request</title>
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
		
		var Title='<font color="#FFFFFF">Reset Password Request Help</font>';
		var m='';
				
		$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
	
			$(document).ajaxStop($.unblockUI);				
					
			$('#btnSubmit').click(function(e) 
			{
				try
				{
					if (!CheckForm()) return false;
							
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Sending Password Link. Please Wait...</b></p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request
					var em=$('#txtEmail').val();
												
					var mydata={usertype:'Subscriber', email:em};
							
					$.ajax({
						url: "<?php echo site_url('Forgot/ForgotPwd');?>",
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
								m='Password Reset Link Has Been Sent To <b>'+em+'</b>.';				
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}else
							{
								m=data;
													
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
						},
						error:  function(xhr,status,error) {
								$.unblockUI;
								m='Error '+ xhr.status + ' Occurred: ' + error;
						
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
					});	
					
					//$.unblockUI();
				}catch(e)
				{
					m='Submit Button Click ERROR:\n'+e;
						
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
			});
		});
		
		function CheckForm()
		{
				var Title='<font color="#FFFFFF">Reset Password Request Help</font>';
				var m='';
				
				try
				{
					var em=$.trim($('#txtEmail').val());			
					
					//Email
					if (!em)
					{
						m='Email field must not be blank.';
						
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
						
						$('#txtEmail').focus(); return false;
					}
					
					//Valid Email?
					//  /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
					var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
					if(!rx.test(em))
					{
						m='Invalid email address.';   
						
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
						
						$('#txtEmail').focus(); return false;
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
        <!--<ul class="nav navbar-nav">
          <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Categories1</a></li>
              <li><a href="#">Categories2</a></li>
              <li><a href="#">Categories3</a></li>
              <li><a href="#">Categories4</a></li>
            </ul>
          </li>
          <li><a href="comedian.html">Comedian <span class="sr-only">(current)</span></a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Events</a></li>
        </ul>-->
        <ul class="nav navbar-nav navbar-right">
          <li class="search-box">
            <form action="" autocomplete="on">
              <input id="search" name="search" type="text" placeholder="What're we looking for ?">
              <input id="search_submit" value="Rechercher" type="submit">
            </form>
          </li>
          <li><a href="<?php echo site_url('Home');?>">Login</a></li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
    <!-- /.container-fluid --> 
  </nav>
    
    
    </header>
    <section class="home-slider" >
    
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" >
  <!-- Indicators -->
  <ol class="carousel-indicators">
    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
    <div class="item active">
      <img src="<?php echo base_url();?>images/banner2.jpg" alt="...">
    </div>
    <div class="item">
     <img src="<?php echo base_url();?>images/banner2.jpg" alt="...">
    </div>
    
    <div class="carousel-caption"  style="height:80%;">
      <div class="login-form">
      <form >
     <div class="sign-up-head"> <h3> Reset Password Request </h3></div>
   <div class="form-group">
    <label style="text-shadow:none; color:#777777; text-align:left; float:left;">Email where reset link will be sent:</label>
  	</div>
  <div class="form-group">
    <input id="txtEmail" type="email" class="form-control"  placeholder="Email">
  </div>
  
 <div align="center">
    <div id = "divAlert"></div>
</div>

  <button id="btnSubmit" type="button" class="btn btn-primary">Submit Request</button>
</form>

<p style="color:#740507; text-shadow: 0 0px 0px rgba(0,0,0,.6); margin-top:10px;" class="text-left"> already a user? <a class="redtext" href="<?php echo site_url('Home'); ?>">sign in</a> </p>

<br>
<div>
	<a href="<?php echo site_url("Pubhome");?>" class="redtext" title="Go to publisher page if you are a publisher or want to be a publisher.">Publisher Login</a>
</div>

      </div>
  </div>
</div>
  <!-- Controls -->
  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
    
    </section>
    
   <?php include('userfooter.php'); ?>

    <script src="<?php echo base_url();?>js/jquery.min.js"></script>
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery.blockUI.js"></script> 
  </body>
</html>