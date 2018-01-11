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
    
    <title>LaffHub | Publisher Registration</title>
    
    <?php include('links.php'); ?>

    <script>
		var Title='<font color="#AF4442">Publisher Signup Help</font>';
		var m='';
					  
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
		
    	$(document).ready(function(e) {
			$(function() {			
				$.blockUI.defaults.css = {};// clear out plugin default styling
			});
		
			$(document).ajaxStop($.unblockUI);
			
			$('#chkContract').click(function(e) {
                try
				{
					var chk=$(this).prop('checked');
					
				   	if (chk==true)
				   	{
					  	document.getElementById('btnFacebook').disabled=false;
					  	document.getElementById('btnRegister').disabled=false;
				   	}else
				   	{
					 	document.getElementById('btnFacebook').disabled=true;
						document.getElementById('btnRegister').disabled=true;
				   	}	
				}catch(e)
				{
					m='Contract Agreement CheckBox Click ERROR:\n'+e;
				
					$.unblockUI();					
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
				
			$('#btnRegister').click(function(e) {
				try
				{
					if (!CheckRegister()) return false;
			
					var nm=$.trim($('#txtName').val());
					var ph=$.trim($('#txtPhone').val());
					var em=$.trim($('#txtEmail').val());
					var agree=$('#chkContract').prop('checked');
					var con;
					
					if (agree==true) con='1'; else con='0';
					
					var mydata={email:em, phone:ph, name:nm, contract:con, password:sha512($('#txtPwd').val())};
				
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Registering Publisher. Please Wait...</p>',theme: true,baseZ: 2000});
																									
					$.ajax({
						url: "<?php echo site_url('Registerpub/RegisterPublisher');?>",
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							//$.unblockUI;
							
							//data = JSON.parse(data);
		
							if ($(data).length > 0)
							{																								
								$.each($(data), function(i,e)
								{
									m=e.status;
									
									if (e.Flag == 'OK')
									{
										var url='<?php echo base_url(); ?>Rc/RegCom/name/'+e.name+'/email/'+e.email+'/flag/'+e.Flag+'/f/n';
										//bootstrap_alert.warning(m);
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
					m='Register Button Click ERROR:\n'+e;
					
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
            });//btnRegister Click Ends
			
			$('#btnFacebook').click(function(e) 
			{
				try
				{
					//validate contact
					var agree=$('#chkContract').prop('checked');
					
					if (agree==false)
					{
						m='You MUST agree to the contract agreement before you can register as a plublisher on LaffHub portal.';
						
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
									
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Registering Publisher. Please Wait...</p>',theme: true,baseZ: 2000});
					
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
					$('#btnFacebook').show();
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
			  var Title='<font color="#AF4442">Publisher Facebook Registration</font>';
				
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
				FB.api('/me',{fields: 'email,gender,name'}, function(response) {
				 
				var ph=$.trim($('#txtPhone').val());
				var agree=$('#chkContract').prop('checked');
				var con;
				
				if (agree==true) con='1'; else con='0';
				
				  var mydata={phone:ph, contact:con, name:response.name, id:response.id, email:response.email,gender:response.gender};
				 				  
				  $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Facebook Registration In Progress. Please Wait...</p>',theme: true,baseZ: 2000});
				  
				  $.ajax({
						type: "POST",
						dataType: 'json',
						data: mydata,
						url: '<?php echo site_url('Registerpub/FacebookRegister');?>',
						success: function(data,status,xhr) {
							$.unblockUI;
						
							if ($(data).length > 0)
							{																								
								$.each($(data), function(i,e)
								{									
									m=e.status;
									
									if (e.Flag == 'OK')
									{
										var url='<?php echo base_url(); ?>Rc/RegCom/name/'+e.name+'/email/'+e.email+'/flag/'+e.Flag+'/f/y';
										//bootstrap_alert.warning(m);
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
			}
			
        });//End Of Document Ready
		
		function CheckRegister()
		{
			try
			{
				var nm=$.trim($('#txtName').val());
				var ph=$.trim($('#txtPhone').val());
				var em=$.trim($('#txtEmail').val());
				var pwd=$('#txtPwd').val();
				var cpwd=$('#txtConfirmPwd').val();
				var agree=$('#chkContract').prop('checked');
					
				//Name	
				if (!nm)
				{
					m='Publisher name field must not be blank.';
					
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
					m='Publisher name field must not be a number.';
					
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
					
					
				//Phone
				if (!ph)
				{
					m='Publisher phone number must not be blank.';
					
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
					
					$('#txtPhone').focus(); return false;
				}
				
				//Email
				if (!em)
				{
					m='Publisher email field must not be blank.';
					
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
					m='Invalid publisher email address.';   
					
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
					m='Publisher password field must not be blank.';
					
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
					m='Minimum publisher password size is six characters.';
					
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
					m='Confirm publisher password field must not be blank.';
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
					m='Publisher password and confirming password fields do not match.';
					
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
				
				
				//Contract
				if (agree==false)
				{
					m='You MUST agree to the contract agreement before you can register as a plublisher on LaffHub portal.';
					
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
				
				//Confirm Publisher Registration
				if (!confirm('Do you want to proceed with the publisher registration? (Click OK to proceed or CANCEL to abort)'))
				{
					return false;
				}
				
				return true;
			}catch(e)
			{
				$.unblockUI();
				m='Register Button Click ERROR:\n'+e;
				
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
  	<?php include('pubnav.php'); ?>
    
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
        <br><br><br><br>
        <p align="center" class="size-12 nobold" style="font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; ">Fields With <span class="redtext">*</span> Are Required!</p>
        
         <div class="panel panel-warning">
              <div align="center" class="panel-heading" style="font-size:18px; text-transform:uppercase;"><b>Publisher Registration</b></div>
              
              <div class="panel-body">
              	<form id="frmRegister" method="post" class="form-horizontal" role="form" data-inset="true">
                         
              	<!--Name-->
                <div class="form-group" title="Publisher Name">
                    <label class="col-sm-2 control-label  left" for="txtName">Name<span class="redtext">*</span></label>
                    <div class="col-sm-4">
                      <input id="txtName" type="text" class="form-control" placeholder="Your Name">
                        <i class="material-icons form-control-feedback size-18" style="margin-right:12px; margin-top:7px;">contacts</i>
                    </div>
                  </div>
                  
                  
                  <!--Email/Phone-->
                    <div class="form-group">
                    <!--Email-->                    
                    <label class="col-sm-2 control-label" for="txtEmail" title="Publisher Email">Email<span class="redtext">*</span></label>
                    <div class="col-sm-4" title="Publisher Email">
                      <input id="txtEmail" type="email" class="form-control" placeholder="Your Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback size-18" style="margin-right:12px;"></span>
                        
                        <i id="faEmail" class="fa fa-spinner fa-spin form-control-feedback size-18 hidden" style="margin-right:-15px;"></i>
                    </div>
                    
                    <!--Phone-->
                    <label class="col-sm-2 control-label " for="txtPhone" title="Publisher Phone Number">Phone<span class="redtext">*</span></label>
                    <div class="col-sm-3" title="Publisher Phone Number">
                      <input id="txtPhone" type="text" class="form-control" placeholder="Your Phone Number">
                        <i class="fa fa-phone-square form-control-feedback size-18" style="margin-right:12px;"></i></span>
                    </div>
                  </div>
                  
                 
                  <!--Pwd/Confirm Pwd-->                  
	              <div class="form-group">
                       <!--Pwd-->
                       <label class="col-sm-2 control-label" for="txtPwd" title="Enter Your Password">Password<span class="redtext">*</span></label>
                        <div class="col-sm-4" title="Enter Your Password">
                            <input id="txtPwd" type="password" class="form-control" placeholder="Password">
                            <span class="glyphicon glyphicon-lock form-control-feedback" style="margin-right:12px;"></span>
                        </div>
                        
                        <!--Confirm Pwd-->
                        <label class="col-sm-2 control-label" for="txtConfirmPwd" title="Confirm Your Password">Confirm&nbsp;Password<span class="redtext">*</span></label>
                
                	<div class="col-sm-3" title="Confirm Your Password">
                        <input id="txtConfirmPwd" type="password" class="form-control" placeholder="Confirm Password">
                        <span class="glyphicon glyphicon-log-in form-control-feedback" style="margin-right:12px;"></span>
                    </div> 
                </div>
                
                <!--Contract Agreement-->
                <div class="form-group" title="Contract Agreement">
                    <label class="col-sm-2 control-label  left" for="divContract">Contract Agreement<span class="redtext">*</span></label>
                    <div class="col-sm-9">
                      <div id="divContract" type="text" class="form-control" style="height:150px;"></div>
                        <i class="fa fa-file-text form-control-feedback" style="margin-right:12px;"></i>
                        &nbsp;&nbsp;<span class="redtext">*</span><label for="chkContract">I Accept The Contract Agreement</label>
                        <input type="checkbox" id="chkContract" style="float:left;">
                    </div>
                  </div>
                 
                                
                <fieldset class="col-sm-12 size-14">
                  <div align="center">
                        <div id = "divAlert"></div>
                   </div>
                              
                   <div class="row">  
                        <div class="col-xs-2">&nbsp;</div>
                           
                    <!-- /.col -->
                        <div class="col-xs-2" title="Click To Register Register Publisher">
                          <button disabled id="btnRegister" type="button" class="btn btn-primary btn-block btn-flat"><i class="fa fa-plus-square size-18"></i> Register</button>
                        </div>
                    <!-- /.col -->
                             
                   
                        <div class="col-xs-2">
                          <div title="Click To Refresh The Entries">
                              <button onClick="window.location.reload(true);" id="btnRefresh" type="button" class="btn btn-warning btn-block btn-flat"><i class="fa fa-refresh size-18"></i> Refresh</button>            
                          </div>
                        </div>
                        
                        <div class="col-xs-1" style="margin-top:10px; margin-left:30px; font-weight:bold;"> OR </div> 
                        
                        <div class="col-xs-3" style="margin-top:-10px;">
                          <div class="social-auth-links" title="Register Using Facebook">                                               
                              <div class="" title="Register Using Facebook">
                                <button type="button" disabled id="btnFacebook" class="btn btn-block btn-social btn-facebook btn-flat" style="text-align:center;"><i class="fa fa-facebook"></i> Register Using Facebook</button>
                              </div>
                        
                         </div>
                        </div>       
                  </div>
                  
                  <br>
                </fieldset>             
                
                
    </form>
              </div>
              
              <div class="panel-footer" align="center">
              	<strong>Copyright &copy; <?php echo date('Y');?> <a href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
              </div><!--FOOTER-->
            </div>
            
  	</div><!-- /.container -->
   
   <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
  
  </body>
</html>
