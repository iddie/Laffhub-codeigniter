<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LaffHub::Login</title>
 		<!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>lcss/assets/ico/icon.png">
       
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic">        
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/css/login-forms.css">
        
        <link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">
        
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
	<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
            <script src="<?php echo base_url();?>js/respond.min.js"></script>
        <![endif]-->
        
      <script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
        
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/s8.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>


<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-1');
</script>


<script>
(function($){
	
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone;?>';
	var Title='<font color="#AF4442">Subscriber Login Help</font>';
	var m='';

	bootstrap_alert = function() {}
	bootstrap_alert.warning = function(message) 
	{
	   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
	}
	
	$(document).ready(function(e) {
		$(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);	
					
		$('#btnLogin').click(function(e) 
		{				
			try
			{
				if (!CheckLogin()) return false;
						
				//Send values here
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signing In. Please Wait...</b></p>',theme: true,baseZ: 2000});
								
				//Make Ajax Request
				var em=$('#txtEmail').val();
										
				var mydata={email:em, pwd:sha512($('#txtLoginPwd').val())};
						
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
				m='Login Button Click ERROR:\n'+e;
					
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
		
		$('#btnFacebookLogin').click(function(e) {
			try
			{
				//Send values here
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signing In. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				$.ajaxSetup({ cache: true });
				
				 $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
					FB.init({
						appId: '<?php echo $appId; ?>',
						version: 'v2.8', // or v2.0, v2.1, v2.2, v2.3
						cookie : true,
						xfbml  : true
					});
									
					FB.getLoginStatus(function(response) {
					  LoginStatusCallback(response);
					});
			  	});
			}catch(e)
			{
				$('#btnFacebookLogin').show();
				m='Facebook Login Button Click ERROR:\n'+e;
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
	
		function getUserLoginInfo(uid,accessToken) {
			FB.api('/me',{fields: 'email,gender,name'}, function(response) {
			  
			  
			  var mydata={name:response.name, id:response.id, email:response.email,gender:response.gender};
			  //alert(JSON.stringify(mydata));
			  
			  $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Connecting To Facebook. Please Wait...</b></p>',theme: true,baseZ: 2000});
			  
			  $.ajax({
					type: "POST",
					dataType: 'text',
					data: mydata,
					url: "<?php echo site_url('Home/SubscriberFaceBookLogin'); ?>",
					success: function(data,status,xhr) {
						$.unblockUI;
					
						if ($.trim(data.toUpperCase())=='OK')
						{
							window.location.href='<?php echo site_url("Subscriberhome");?>';
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
						$('#btnFacebookLogin').show();
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
		
			});
		}
	
				
		function FBLogin()
		{
			var Title='<font color="#AF4442">Subscriber Facebook Login</font>';
			
		  FB.login(function(response) {
			 if (response.authResponse) 
			 {
				 //getUserInfo(); //Get User Information.
			  } else
			  {
				
				$('#btnFacebookLogin').show();
				
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
		}
		
		function LoginStatusCallback(response)
		{
			  var Title='<font color="#AF4442">Subscriber Facebook Login</font>';
				
			  if (response.status === 'connected') {
				  // Logged into your app and Facebook. We need to hide FB login button
				  
				  $('#btnFacebookLogin').hide();
				  
				  var uid = response.authResponse.userID;
				  var accessToken = response.authResponse.accessToken;
					
				  getUserLoginInfo(uid,accessToken);
			  } else if (response.status === 'not_authorized') {
				  // The person is logged into Facebook, but not your app.
				  //$('#status').html('Please log into LaffHub.');
				  m='Please log into LaffHub.';
				  
				  /*bootstrap_login_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});*/
					
					$('#btnFacebookLogin').show();
					
					FBLogin();
			  } else {
				  // The person is not logged into Facebook, so we're not sure if
				  // they are logged into this app or not.
				  //$('#status').html('Please log into facebook');
				  m='Please log into facebook';
				  Er
				//bootstrap_login_alert.warning(m);
				/*bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});*/
				
				$('#btnFacebookLogin').show();
				
				FBLogin();
			  }
			}
    }); //Document Ready End
	
	function CheckLogin()
	{
		try
		{
			var em=$.trim($('#txtEmail').val());
			var pwd=$('#txtLoginPwd').val();				
							
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
				
				$('#txtLoginPwd').focus(); return false;
			}
			
			return true;
		}catch(e)
		{
			$.unblockUI();
			m='CheckLogin ERROR:\n'+e;
			
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

})(jQuery);
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
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
		<!-- Top menu -->
		<nav class="navbar navbar-inverse navbar-fixed-top navbar-no-bg" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="">LaffHub Login</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">
						<!--<li><a class="scroll-link" href="#">Home</a></li>
						<li><a class="scroll-link" href="#">Blog</a></li>-->
					</ul>
				</div>
			</div>
		</nav>

		<!-- Login Form 1 -->
		<div class="l-form-1-container section-container section-container-image-bg">
	        <div class="container">
	            <div class="row">
	                <div class="col-sm-8 col-sm-offset-2 l-form-1 section-description wow fadeIn">
	                    <h2><strong>Login</strong></h2>
	                    <div class="divider-1 wow fadeInUp"><span></span></div>
	                </div>
	            </div>
	            <div class="row">
	            	<div class="col-sm-6 col-sm-offset-3 l-form-1-box wow fadeInUp">
	            		
	                    <div class="l-form-1-top">
                    		<div class="l-form-1-top-left">
                    			<h3>Login to LaffHub</h3>
								<p>Enter your email and password to log on:</p>
                    		</div>
                    		<div class="l-form-1-top-right">
                    			<i class="fa fa-lock"></i>
                    		</div>
                        </div>
                        <div class="l-form-1-bottom">
		                    <form role="form" action="" method="post">		                        
		                        <div class="form-group">
		                    		<label class="sr-only" for="txtEmail">E-mail</label>
		                        	<input type="text" placeholder="Email..." class="txtEmail form-control" id="txtEmail">
		                        </div>
		                        <div class="form-group">
		                        	<label class="sr-only" for="txtLoginPwd">Password</label>
		                        	<input type="password" placeholder="Password..." class="l-form-1-password form-control" id="txtLoginPwd">
		                        </div>
                                
                                <div align="center">
                                    <div id = "divAlert"></div>
                                </div>
		                        <button id="btnLogin" type="button" class="btn">Sign in!</button>
		                    </form>
                            
                            <br>
                            <span style="color:#ff0; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px;  margin-top:10px;" > Don't have an account ? <a href="<?php echo site_url('Signup');?>"><span style="color:#ccc; text-shadow: 0 0px 0px rgba(0,0,0,.6);">Sign Up </span> </a>
                            </span>
                            
                            <span style="color:#fff; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px;  float:right;" > <a href="<?php echo site_url('Pubhome');?>"><span style="color:#fff; text-shadow: 0 0px 0px rgba(0,0,0,.6);">Publisher Login </span> </a>
                            </span>
	                    </div>
	                    
	                </div>
	            </div>
	            <div class="row">
                    <div class="col-sm-6 col-sm-offset-3 l-form-1-social-login">
                    	<h3>...or login with:</h3>
                    	<div class="l-form-1-social-login-buttons">
                        	<a id="btnFacebookLogin" class="btn btn-link-2">
                        		<i class="fa fa-facebook"></i> Facebook
                        	</a>
                        	<!--<a class="btn btn-link-2" href="#">
                        		<i class="fa fa-twitter"></i> Twitter
                        	</a>-->
                    	</div>
                    </div>
                </div>
	        </div>
        </div>

        <!-- Footer -->
        <footer>
	        <div class="container">
	        	<div class="row">
                    <div class="col-sm-12 footer-social">
                    	<a href="https://www.facebook.com/thelaffhub" target="_blank"><i class="fa fa-facebook"></i></a>                        
                    	<a href="https://twitter.com/laffhub" target="_blank"><i class="fa fa-twitter"></i></a>
                    	<a href="https://www.instagram.com/laffhub" target="_blank"><i class="fa fa-instagram"></i></a>
                    </div>
	            </div>
	            <div class="row">
                    <div class="col-sm-12 footer-copyright">
                    	&copy;LaffHub by <a href="https://laffhub.com">EFLUXZ</a>.
                    </div>
                </div>
	        </div>
        </footer>
        

        <!-- Javascript -->
        <script src="<?php echo base_url(); ?>lcss/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/js/wow.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/js/retina-1.1.0.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/js/waypoints.min.js"></script>
        <script src="<?php echo base_url(); ?>lcss/assets/js/login-forms.js"></script>
        
        <!--[if lt IE 10]>
		assets src="assets/js/placeholder.js"></script>
		<![endif]-->

    </body>

</html>