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
    
    <title>LaffHub | Publisher</title>
    
    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">-->
   <link rel="stylesheet" href="<?php echo base_url(); ?>css/homestyle.css">
   <link href="<?php echo base_url();?>css/form-elements.css" rel="stylesheet">
   
   
   	 <?php include('links.php'); ?>
    
   
    
  

<script>
	var Title='<font color="#AF4442">Publisher Login Help</font>';
	var m='';
	
	$(document).ready(function(e) {
		$(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);	
		
		bootstrap_alert = function() {}
		bootstrap_alert.warning = function(message) 
		{
		   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
				
		$('#btnLogin').click(function(e) 
		{				
			try
			{
				if (!CheckLogin()) return false;
						
				//Send values here
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Signing In Publisher. Please Wait...</p>',theme: true,baseZ: 2000});
								
				//Make Ajax Request
				var em=$('#txtEmail').val();
										
				var mydata={email:em, pwd:sha512($('#txtLoginPwd').val())};
						
				$.ajax({
					url: "<?php echo site_url('Pubhome/PublisherLogin');?>",
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
							window.location.href='<?php echo site_url("Dashboard");?>';
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
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Signing In Publisher. Please Wait...</p>',theme: true,baseZ: 2000});
			
			$.ajaxSetup({ cache: true });
			  $.getScript('//connect.facebook.net/en_US/sdk.js', function(){
				FB.init({
						appId: '<?php echo $appId; ?>',
						version: 'v2.8', // or v2.0, v2.1, v2.2, v2.3
						cookie     : true,
						xfbml  : true
					});
									
					//FB.getLoginStatus(updateStatusCallback);
					
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
		  
		  $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Connecting To Facebook. Please Wait...</p>',theme: true,baseZ: 2000});
		  
		  $.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: "<?php echo site_url('Pubhome/PublisherFaceBookLogin'); ?>",
				success: function(data,status,xhr) {
					$.unblockUI;
				
					if ($.trim(data.toUpperCase())=='OK')
					{
						window.location.href='<?php echo site_url("Dashboard");?>';
					}else
					{
						$.unblockUI;
						
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
			var Title='<font color="#AF4442">Publisher Facebook Login</font>';
			
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
			  var Title='<font color="#AF4442">Publisher Facebook Login</font>';
				
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
			m='Sign In Button Click ERROR:\n'+e;
			
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
<!-- NAVBAR
================================================== -->
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
            <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <a href="<?php echo site_url("Home");?>"><img src="<?php echo base_url();?>images/logo.png" height="130px" style="margin-top:30px;"></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h2>LaffHub Publisher Login</h2>
                            		<h4 class="redtext">Enter your email and password to sign in:</h4>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-key"></i>
                        		</div>
                            </div>
                            
                            <div class="form-bottom">
			                    <form role="form" method="post" class="login-form">
			                    	<div class="form-group">
			                    		<label class="sr-only" for="txtEmail">Email</label>
			                        	<input type="text" name="txtEmail" placeholder="Email..." class="form-username form-control" id="txtEmail">
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="txtLoginPwd">Password</label>
			                        	<input type="password" name="txtLoginPwd" placeholder="Password..." class="form-password form-control" id="txtLoginPwd">
			                        </div>
			                        
                                    <div align="center" style="margin-top:10px;">
                                        <div id = "divAlert"></div>
                                   </div>
                                   
                                    <button style="background-color:#DB5832" id="btnLogin" type="button" class="btn makebold">Sign In With Email!</button>
                                    <div align="center" style="margin-top:15px;">OR</div>
                                    <div class="social-auth-links" title="Login Using Facebook">                                               
                                          <div class="" title="Signin Using Facebook">
                                            <button type="button" style="background-color:#3b5998; text-align:center;" id="btnFacebookLogin" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook-official size-22" style="margin-top:10px;"></i> Signin Using Facebook</button>
                                          </div>
                                    
                                     </div>
                                     
                                     <div align="center" class="size-20" style="margin-top:20px;">
                                        Do Not Have Account? Click <a target="_self" href="<?php echo site_url("Registerpub");?>" target="new">Here</a> To Register.
                                    </div>
			                    </form>
                                
                                
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
