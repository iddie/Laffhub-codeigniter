<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>LaffHub::Sign Up</title>
	<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
       
<?php include('frontlink.php'); ?>

<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">
  
<!--
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
        async defer>
    </script>
-->

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

/*
var verifyCallback = function(response)
{
	try
	{
	   if (response)
	   {
		   $('#btnCreate').prop('disabled',false);
		   $('#btnFacebook').prop('disabled',false);
	   }else
	   {
		   $('#btnCreate').prop('disabled',true);
		   $('#btnFacebook').prop('disabled',true);
	   }	
	}catch(e)
	{
		m='Verify Callback Function ERROR:\n'+e;
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
   
};


var onloadCallback = function() {
	grecaptcha.render('captcha', {
	  //'sitekey' : '6Lc2gCATAAAAAEeUuIwDG-Tos1J6_Tayceodqhyn',
	  'sitekey' : '6LcOJRwUAAAAABORgXvJXp1kvTCfuj3Kyhkgs0U5',
	  'theme' : 'light',//dark or light
	  'callback' : verifyCallback,
	  'size' : 'normal'//normal,compact
	});
  };
*/

var Title='<font color="#AF4442">Subscriber SignUp Help</font>';
var m='';

$(document).ready(function(e) {
    $(function() {			
		$.blockUI.defaults.css = {};// clear out plugin default styling
	});
		
	$(document).ajaxStop($.unblockUI);
	
	$('#btnFacebook').click(function(e) 
	{
		try
		{							
			//Send values here
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Registering Subscriber. Please Wait...</b></p>',theme: true,baseZ: 2000});
			
			$.ajaxSetup({ cache: true });
			 
			  $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
				FB.init({
						appId: '<?php echo $appId; ?>',
						version: 'v2.8', // or v2.0, v2.1, v2.2, v2.3
						cookie : true,
						xfbml  : true
					});
					FB.getLoginStatus(function(response) {
					  statusChangeCallback(response);
					});
			  });
			  
			  //$.unblockUI();
		}catch(e)
		{
			$.unblockUI();
			m='Facebook Register Button Click ERROR:\n'+e;
			
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
		
	function statusChangeCallback(response)
	{
	  if (response.status === 'connected') {
		  // Logged into your app and Facebook.
		  // we need to hide FB login button
		  
		  $('#btnFacebook').hide();
		  
		  //fetch data from facebook
		  
		  var uid = response.authResponse.userID;
		  var accessToken = response.authResponse.accessToken;
			
		  getRegisterUserInfo(uid,accessToken);
	  } else if (response.status === 'not_authorized') {					
			FBLogin();
	  } else 
	  {
			FBLogin();
	  }
	  
	  $('#btnFacebook').show();
	}
	
	function FBLogin()
	{
		var Title='<font color="#AF4442">Facebook Login</font>';
		
		FB.login(function(response) {
		 if (response.authResponse) 
		 {
			 getRegisterUserInfo(); //Get User Information.
		  } else
		  {
		   m='Authorization failed.';
		   
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
		},{scope: 'public_profile,email'});
		
		
		//$.unblockUI();
		
		$('#btnFacebook').show();
	}

	function getRegisterUserInfo(uid,accessToken) 
	{
		try
		{
			FB.api('/me',{fields: 'email,gender,name'}, function(response) {
		 		
			  var mydata={name:response.name, id:response.id, email:response.email,gender:response.gender};
							  
			  $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Facebook Registration. Please Wait...</b></p>',theme: true,baseZ: 2000});
			  
			  $.ajax({
					type: "POST",
					dataType: 'json',
					data: mydata,
					url: '<?php echo site_url('Signup/FacebookRegister');?>',
					success: function(data,status,xhr) {
						$.unblockUI;
					
						if ($(data).length > 0)
						{																								
							$.each($(data), function(i,e)
							{									
								m=e.status;
								
								if (e.Flag == 'OK')
								{
									var url='<?php echo base_url(); ?>Signup/Confirmsignup/name/'+e.name+'/email/'+e.email+'/flag/'+e.Flag+'/f/y';
									
									//var url='<?php echo base_url(); ?>Rc/RegCom/name/'+e.name+'/email/'+e.email+'/flag/'+e.Flag+'/f/y';
									bootbox.alert({ 
										size: 'small', message: m, title:Title,
										buttons: { ok: { label: "Close", className: "btn-danger" } },
										callback:function(){
											window.location.href = url;
										}
										
									});
								}else
								{
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
								
								return;
							});
						}
					},
					error:  function(xhr,status,error) {
							$.unblockUI;
							$('#btnFacebook').show();
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
			});//FB.api End
			
			//$.unblockUI();
		}catch(e)
		{
			$.unblockUI();
			
			m='getRegisterUserInfo Function ERROR: '+e;
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
	}
			
	$('#btnCreate').click(function(e) {
		try
		{
			if (!CheckForm()) return false;
	
			//Make Ajax Request
			var em=$.trim($('#txtEmail').val());
			var mydata={email:em, password:sha512($('#txtPwd').val())};
			
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>User Signup In Progress. Please Wait...</b></p>',theme: true,baseZ: 2000});
																							
			$.ajax({
				url: "<?php echo site_url('Signup/RegisterUser');?>",
				data: mydata,
				type: 'POST',
				dataType: 'json',
				complete: function(xhr, textStatus) {
					//$.unblockUI;
				},
				success: function(data,status,xhr) {	
					$.unblockUI;
					
					if ($(data).length > 0)
					{																								
						$.each($(data), function(i,e)
						{
							m=e.status;
							
							if (e.Flag == 'OK')
							{
								//var url='<?php# echo base_url(); ?>Signup/Confirmsignup/email/'+e.email+'/flag/'+e.Flag;
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										//window.location.href = url;
										Login(em,sha512($('#txtPwd').val()));
										//$.redirect("<?php# echo site_url('');?>",{email: em, pwd:}); 
									}
									
								});
							}else
							{
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
							
							return;
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
			$.unblockUI();
			m='Signup Button Click ERROR:\n'+e;
			
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
	});//btnCreate Click Ends
	
	function Login(em,pwd)
	{
		try
		{
			var mydata={email:em, pwd:pwd};
						
			$.ajax({
				url: "<?php echo site_url('Home/UserLogin');?>",
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
						window.location.href='<?php echo site_url("Subscriberhome"); ?>';
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
			
			$.unblockUI();
		}catch(e)
		{
			$.unblockUI();
			m='Login Function ERROR:\n'+e;
			
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
	}
			
	function CheckForm()
	{
		try
		{
			var em=$.trim($('#txtEmail').val());//Email
			var pwd=$('#txtPwd').val();
			var cpwd=$('#txtConfirmPwd').val();
			
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
				m='Password and confirming password fields do not match.';
				
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
			//if (!confirm('Do you want to proceed with the signup? (Click OK to proceed or CANCEL to abort)'))
			//{
			//	return false;
			//}
			
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
  	<script>
  window.fbAsyncInit = function() {
    FB.init({
		appId      : '<?php echo $appId; ?>',
		version    : 'v2.8',
		cookie     : true,
		xfbml  : true
    });
	
	FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

  	<header>
<nav class="navbar navbar-default" style="background:#ffffff;">
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
     <img src="<?php echo base_url();?>images/banner2a.jpg" alt="...">
    </div>
    
    <div class="item">
     <img src="<?php echo base_url();?>images/banner2b.jpg" alt="...">
    </div>
    
    <div class="carousel-caption"  style="height:99%;">
      <div class="login-form">
      <form >
     <div style="height:28px; margin-top:-12px; font-weight:bold; font-size:20px; color:#534E4E; background-color:#EEEEEE; margin-bottom:5px;"> Sign Up </div>
  <div class="form-group">
    <input id="txtEmail" type="email" class="form-control"  placeholder="Your Email">
  </div>
  <div class="form-group">
    <input id="txtPwd" type="password" class="form-control"  placeholder="Your Password">
  </div>
  
  <div class="form-group">
    <input id="txtConfirmPwd" type="password" class="form-control"  placeholder="Confirm Your Password">
  </div>
 
 <!--<div class="form-group">
    <div>
        <div align="center" id="captcha"></div>
    </div>
</div>-->

<div align="center">
    <div id = "divAlert"></div>
</div>

<div class="form-group"> 
  <button id="btnCreate" type="button" class="btn btn-info"><i class="fa fa-plus-circle"></i> Create Account</button>
</div>  
  

<div class="sign-up"> <img src="<?php echo base_url();?>images/or.png" class="img-responsive"  height="5px"> </div>

<div align="center" class="sign-up">
	<button type="button" id="btnFacebook" class="btn btn-block btn-social btn-facebook btn-flat" style="background-color:#3b5998; text-align:center; color:#ffffff; padding-left:5px; padding-right:5px; width:auto;"><i class="fa fa-facebook"></i>&nbsp;&nbsp;Register Using Facebook&nbsp;</button>
</div>


<div class="form-group">
<p style="color:#740507; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px; margin-top:10px;"> already a user? <a href="<?php echo site_url('Home'); ?>" style="top:0px;"><span style="color:#85b140; text-shadow: 0 0px 0px rgba(0,0,0,.6);">Sign In</span></a> </p>
</div>




<div class="form-group">
	<p title="Go to publisher page if you are a publisher or want to be a publisher." style="color:#333; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px;" ><a href="<?php echo site_url('Pubhome');?>" style="top:0px;"><span style="text-shadow: 0 0px 0px rgba(0,0,0,.6); color:#EF9D04">Publisher Login </span> </a></p>
</div>
</form>

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
    <script src="<?php echo base_url();?>js/jquery.redirect.js"></script>
     
  </body>
</html>