<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LaffHub SignUp</title>
        
        <!-- Favicon and touch icons -->
		<link rel="shortcut icon" href="<?php echo base_url(); ?>lcss/assets/ico/icon.png">

        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100italic,300,300italic,400,400italic,500,500italic">        
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/css/animate.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>lcss/assets/css/registration-forms.css">
        
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
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signing Up. Please Wait...</b></p>',theme: true,baseZ: 2000});
			
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
			m='Facebook Signup Button Click ERROR:\n'+e;
			
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
							  
			  $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Facebook Signup. Please Wait...</b></p>',theme: true,baseZ: 2000});
			  
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
									
									//var url='<?php# echo base_url(); ?>Rc/RegCom/name/'+e.name+'/email/'+e.email+'/flag/'+e.Flag+'/f/y';
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
			var nm=$.trim($('#txtName').val());
			var mydata={name:nm,email:em, password:sha512($('#txtPwd').val())};
			
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signup In Progress. Please Wait...</b></p>',theme: true,baseZ: 2000});
																							
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
			var nm=$.trim($('#txtName').val());
			var pwd=$('#txtPwd').val();
			var cpwd=$('#txtConfirmPwd').val();
			
			//Name
			if (!nm)
			{
				m='Name field must not be blank.';
				
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
				
				$('#txtName').focus(); return false;
			}
			
			if ($.isNumeric(nm))
			{
				m='Name field must not be a number.';
				
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
				
				$('#txtName').focus(); return false;
			}
			
			if (nm.length < 3)
			{
				m='Please enter a meaningful name.';
				
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
				
				$('#txtName').focus(); return false;
			}
			
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

})(jQuery);
</script>

       
    </head>

    <body>

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
					<a class="navbar-brand" href="">LaffHub Sign Up</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a class="" href="<?php echo site_url('Home');?>">Login</a></li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Registration Form 1 -->
		<div class="r-form-1-container section-container section-container-image-bg">
	        <div class="container">
	            <div class="row">
	                <div class="col-sm-8 col-sm-offset-2 r-form-1 section-description wow fadeIn">
	                    <h2><strong>Sign Up</strong></h2>
	                    <div class="divider-1 wow fadeInUp"><span></span></div>
	                </div>
	            </div>
	            <div class="row">
	            	<div class="col-sm-6 col-sm-offset-3 r-form-1-box wow fadeInUp">
	            		
	                    <div class="r-form-1-top">
                    		<div class="r-form-1-top-left">
                    			<h3>Sign Up Now</h3>
                        		<p>Please fill in the form below:</p>
                    		</div>
                    		<div class="r-form-1-top-right">
                    			<i class="fa fa-pencil"></i>
                    		</div>
                        </div>
                        <div class="r-form-1-bottom">
		                    <form role="form" action="" method="post">
		                    	<div class="form-group">
		                    		<label class="sr-only" for="txtName">Name</label>
		                        	<input type="text" placeholder="Name [Required]..." class="r-form-1-first-name form-control" id="txtName">
		                        </div>

		                        <div class="form-group">
		                        	<label class="sr-only" for="txtEmail">Email</label>
		                        	<input type="text" placeholder="Email [Required]..." class="txtEmail form-control" id="txtEmail">
		                        </div>
								<div class="form-group">
									<label class="sr-only" for="txtPwd">Password</label>
									<input type="password" placeholder="Password [Required]..." class="l-form-1-password form-control" id="txtPwd">
								</div>
								<div class="form-group">
									<label class="sr-only" for="txtConfirmPwd">Password Confirmation</label>
									<input type="password" placeholder="Password Confirmation [Required]..." class="l-form-1-password form-control" id="txtConfirmPwd">
								</div>
                                
                                <div align="center">
                                    <div id = "divAlert"></div>
                                </div>

		                        <button id="btnCreate" type="button" class="btn"><i class="fa fa-plus-circle"></i> Sign me up!</button>
		                    </form>
	                    </div>

						<div class="row">
							<div class="col-sm-6 col-sm-offset-3 l-form-1-social-login">
								<h3>...or sign up with:</h3>
								<div class="l-form-1-social-login-buttons">
									<a id="btnFacebook" class="btn btn-link-2 btn-primary">
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
						&copy;LaffHub by <a href="http://laffhub.com">EFLUXZ</a>.
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
        <script src="<?php echo base_url(); ?>lcss/assets/js/registration-forms.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>