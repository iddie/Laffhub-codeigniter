<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::Home</title>

<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

<?php include('frontlink.php'); ?>

<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>  

<script>
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
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signing In Publisher. Please Wait...</b></p>',theme: true,baseZ: 2000});
								
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
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Signing In Subscriber. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
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

<header>
  <nav class="navbar navbar-default">
    <div class="container"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        <a class="navbar-brand" href="<?php echo site_url('Home') ?>"><img src="<?php echo base_url();?>images/logo.png" class="img-responsive"></a> </div>
        
         <div class="navbar-header">
        <a class="navbar-brand" href="index.html"><img src="<?php echo base_url();?>images/rs_airtel.png" class="img-responsive"></a>
      </div>
      
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        
        <ul class="nav navbar-nav navbar-right">
          <!--<li class="search-box">
            <form action="" autocomplete="on">
              <input id="search" name="search" type="text" placeholder="What're we looking for ?">
              
            </form>
          </li>-->
          <li><a href="<?php echo site_url('Signup');?>">Sign up</a></li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
    <!-- /.container-fluid --> 
  </nav>
</header>

<section class="home-slider">
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
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
     
      <br><div class="carousel-caption" >
        <div class="login-form">
          <form>
            <div class="sign-up-head">
              <h3> Sign in Page</h3>
            </div>
                        
            <div class="form-group">
              <input id="txtEmail" type="email" class="form-control"  placeholder="Your Email">
            </div>
            <div class="form-group">
              <input id="txtLoginPwd" type="password" class="form-control"  placeholder="Your Password">
            </div>
            
            <div align="center">
                <div id = "divAlert"></div>
            </div>

            <p style="color:#85b140; text-shadow: 0 0px 0px rgba(0,0,0,.6);" class="text-left"> Forgot password? <a class="redtext" href="<?php echo site_url('Forgot'); ?>">Click Here</a> </p>
            <button id="btnLogin" type="button" class="btn btn-default" style="font-size:20px;"><i class="fa fa-sign-in"></i> Sign in</button>
            
            <div class="sign-up"> <img src="<?php echo base_url();?>images/or.png" class="img-responsive" width="100%"> </div>
            
            <div class="sign-up">
            	<button type="button" style="background-color:#3b5998; text-align:center;" id="btnFacebookLogin" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook-official size-22" style="margin-top:10px;"></i> Sign in Using Facebook</button>
             </div>
             
          </form>
          <div class="login-social"> </div>
          <p style="color:#333; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px;" > Don't have an account ? <a href="<?php echo site_url('Signup');?>" style="top:0px;"><span style="color:#85b140; text-shadow: 0 0px 0px rgba(0,0,0,.6);">Sign Up </span> </a></p>
          
     
			<div class="form-group">
				<!--<a style="background-color:#EF9D04;" class="btn btn-info" href="<?php# echo site_url("Pubhome");?>" title="Go to publisher page if you are a publisher or want to be a publisher.">Publisher Login</a>-->
                
                <p title="Go to publisher page if you are a publisher or want to be a publisher." style="color:#333; text-shadow: 0 0px 0px rgba(0,0,0,.6); padding-right:5px;" ><a href="<?php echo site_url('Pubhome');?>" style="top:0px;"><span style="text-shadow: 0 0px 0px rgba(0,0,0,.6); color:#EF9D04" >Publisher Login </span> </a></p>
			</div>
        </div>
      </div>
    </div>
    <!-- Controls --> 
</section>

<br>
<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>

</body>
</html>