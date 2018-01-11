<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Subscriber Profile</title>
<!--FAVICON-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>lcss/favicons/icon.png">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>lcss/favicons/icon.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>lcss/favicons/icon.png" sizes="16x16">
<link rel="manifest" href="<?php echo base_url(); ?>lcss/favicons/manifest.json">
<link rel="mask-icon" href="<?php echo base_url(); ?>lcss/favicons/safari-pinned-tab.svg" color="#ff0000">
<meta name="theme-color" content="#ffffff">
<!--/FAVICON-->


<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>lcss/css/main1.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<script src="<?php echo base_url();?>js/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/modernAlert.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/s8.min.js"></script>


<script>
(function($){
	
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	var SubscriberEmail="<?php echo $subscriber_email; ?>";
	var SubscriptionDate="<?php echo $subscribe_date; ?>";
	var ExpiryDate="<?php echo $exp_date; ?>";
	var SubscriptionStatus='<?php echo $subscriptionstatus; ?>';
	var OldPassword='<?php echo $OldPassword; ?>';
	
	var Title='<font color="#AF4442">Update Profile Help</font>';
	var m='';
	var self;
	
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
       modernAlert({
                backgroundColor: '#fff',
                color: '#555',
                borderColor: '#ccc',
                titleBackgroundColor: '#C8552E',//#e8a033
                titleColor: '#fff',
                defaultButtonsText: {ok : 'Ok', cancel : 'Cancel'},
                overlayColor: 'rgba(0, 0, 0, 0.5)',
                overlayBlur: 2 //Set false to disable it or interger for pixle
            });
			
		$.msg(
		{
			autoUnblock : true ,
			clickUnblock : true,
			fadeIn : 500,
			fadeOut : 200,
			timeOut : 1000,
			afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
			klass : 'custom-theme',
			bgPath : '<?php echo base_url();?>images/',
			content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Page. Please Wait...</b></p></center>'
			}
		);	
		
		$('#chkChangePwd').click(function(e) {
            try
			{
				var chk=$(this).prop('checked');
				
				if (chk==true)
				{
					$('#divOldPwd').removeClass('hide').addClass('show')
					$('#divNewPwd').removeClass('hide').addClass('show')
					$('#divConfirmPwd').removeClass('hide').addClass('show')
				}else
				{
					$('#divOldPwd').removeClass('show').addClass('hide')
					$('#divNewPwd').removeClass('show').addClass('hide')
					$('#divConfirmPwd').removeClass('show').addClass('hide')
				}
			}catch(e)
			{
				$.msg('unblock');
				m='Change Password Click ERROR:\n'+e;
				
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
				
				return false;
			}
        });
		
		$('#btnUpdate').click(function(e) {
			try
			{
				CheckForm();
			}catch(e)
			{
				$.msg('unblock');
				m='Update Button Click ERROR:\n'+e;
				
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});//btnUpdate Click Ends
			
		function CheckForm()
		{
			try
			{
				var em=$.trim($('#lblEmail').html());//Email
				var nm=$.trim($('#txtName').val());
				var oldpwd=$('#txtOldPwd').val();
				var newpwd=$('#txtNewPwd').val();
				var cpwd=$('#txtConfirmPwd').val();
				var chk=$('#chkChangePwd').prop('checked');
				
				//Email
				if (!em)
				{
					m='Subscriber email field is blank. Your session may have expired. Logout and login again';
					
					bootstrap_alert.warning(m);					
					alert(m, 'LaffHub Message');
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}
				
				//Name				
				if (!nm)
				{
					m='Name field must not be blank.';
					
					bootstrap_alert.warning(m);					
					alert(m, 'LaffHub Message');
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#txtName').focus(); return false;
				}
				
				if ($.isNumeric(nm))
				{
					m='Name field must not be a number.';
					
					bootstrap_alert.warning(m);					
					alert(m, 'LaffHub Message');
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#txtName').focus(); return false;	
				}
				
				if (nm.length < 3)
				{
					m='Please enter your full name.';
					
					bootstrap_alert.warning(m);					
					alert(m, 'LaffHub Message');
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#txtName').focus(); return false;	
				}					
				
				
				if (chk==true)
				{
					//Old Password
					if (!$.trim(oldpwd))
					{
						m='Old password field must not be blank';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtOldPwd').focus(); return false;
					}
					
					if (sha512(oldpwd) != OldPassword)
					{
						m='Old password entered is not correct!';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtOldPwd').focus();  return false;
					}
					
					//New Password
					if (!$.trim(newpwd))
					{
						m='New password field must not be blank.';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtNewPwd').focus(); return false;
					}
					
					if (newpwd.length<6)
					{
						m='Minimum new password size is six (6) characters.';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtNewPwd').focus(); return false;
					}
					
					//Confirm Password
					if (!$.trim(cpwd))
					{
						m='Confirm password field must not be blank.';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtConfirmPwd').focus();   return false;
					}
					
					if (newpwd != cpwd)
					{
						m='Password and confirming password fields do not match.';
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						$('#txtConfirmPwd').focus();   return false;
					}	
				}
								
				//Confirm Registration
				m='Do you want to proceed with the profile update? (Click <b>Yes</b> to proceed or <b>No</b> to abort)?';
				
				confirm(m, 'LaffHub Message', Updateprofile,null,{ok : 'Yes', cancel : 'No'});
			}catch(e)
			{
				$.msg('unblock');
				m='CheckForm ERROR:\n'+e;
				
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
				
				return false;
			}
		}
		
		function Updateprofile(input)
		{
			if (input === true)
			{
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Updating Profile. Please Wait...</b></p></center>'
					}
				);
				
				var nm=$.trim($('#txtName').val());
				var chk=$('#chkChangePwd').prop('checked');
				var pwdflag='No';
				var mydata;
				
				if (chk==true)
				{
					pwdflag='Yes';
					mydata={email:SubscriberEmail, subscribername:nm, pwdflag:pwdflag, Pwd:sha512($('#txtNewPwd').val())};
				}else
				{
					mydata={email:SubscriberEmail, subscribername:nm, pwdflag:pwdflag};
				}
												
				$.ajax({
					url: "<?php echo site_url('Profile/UpdateProfile');?>",
					data: mydata,
					type: 'POST',
					dataType: 'text',
					success: function(data,status,xhr) {	
						$.msg('unblock');
						
						if ($.trim(data).toUpperCase()=='OK')
						{
							m='Profile Update Was successful';
															
							bootstrap_Success_alert.warning(m);			
							alert(m, 'LaffHub Message');
							setTimeout(function() {
								window.location.reload(true);	
							}, 5000);
						}else
						{
							m=data;
							
							bootstrap_alert.warning(m);					
							alert(m, 'LaffHub Message');
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}		
					},
					error:  function(xhr,status,error) {
						$.msg('unblock');
						
						m='Error '+ xhr.status + ' Occurred: ' + error;
						
						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
			} else
			{
				$.msg('unblock');
				m='Profile Update Cancelled';
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		}
		
		$('#btnLogOut').click(function(e) {
            try
			{
				if (!confirm('Do you want to log out? (Click OK to proceed or CANCEL to abort)'))
				{
					return false;
				}
				
				window.location.href='<?php echo site_url('Subscriberlogout'); ?>';
			}catch(e)
			{
				$.msg('unblock');
				m='Log Out Button Click ERROR:\n'+e;
				
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
        });
    });
	
})(jQuery);	
</script>
</head>


<body class="page">
    <div class="page__layout">
      <div class="overlay"></div>
      	<?php include('newusernav.php'); ?>
        
        <div id="content-ajax">
        <!--MAIN-->
        <main class="page__main main">
          	<div class="col-md-12">
            <br>
                 <div class="panel panel-info">
                      <!-- Default panel contents -->
                      <div class="panel-heading size-20">
                        <span class="size-22 makebold"><i class="fa fa-picture-o"></i> PROFILE </span>
                      </div>
                      
                       <div class="panel-body">
                       		<div class="row">
                            <form class="form-horizontal">
                                 <!--Left Column-->
                                <div class="col-md-6">
                                   <center><span class="size-18 makebold" style="color:#2C86B9;">Profile Details </span></center><br>
                                    
                                    <div class="form-group" title="Your fullname">
                                        <label for="lblEmail" class="col-sm-3 control-label ">Email</label>
                                        
                                        <div class="col-sm-9">
                                        <label id="lblEmail" class="form-control nobold" ><?php echo $subscriber_email; ?></label>
                                        </div>
                                    </div>
                                
                                    <div class="form-group"  title="Your fullname">
                                        <label for="txtName" class="col-sm-3 control-label">Fullname</label>
                                        
                                        <div class="name-type col-sm-9">
                                        	<input style="color:#686868; font-weight:normal;" id="txtName" type="text" class="form-control" placeholder="Your fullname"  value="<?php echo $subscriber_name; ?>">
                                        </div>
                                    </div>
                                
                                    <div class="form-group">			     
                                        <label class="col-sm-12 col-sm-offset-3">
                                            <input id="chkChangePwd" type="checkbox"><span class="redtext size-16">&nbsp;&nbsp;<b>Change Password</b></span>
                                        </label>
                                    </div>	
                                
                                    <div id="divOldPwd" title="Your Current Password" class="form-group hide">
	                                    <label for="txtOldPwd" class="col-sm-3 control-label">Old Password</label>
                                        
                                        <div class="name-type col-sm-9">
                                         	<input id="txtOldPwd" type="password" class="form-control nobold" placeholder="Your Current Password">
                                        </div>                                        
                                    </div>	
                                
                                    <div id="divNewPwd" title="Your New Password" class="form-group hide">
                                    	<label for="txtNewPwd" class="col-sm-3 control-label">New Password</label>
                                        
                                        <div class="name-type col-sm-9">
                                        	<input id="txtNewPwd" type="password" class="form-control" placeholder="Your New Password">
                                        </div>                                        
                                    </div>	
                                
                                    <div id="divConfirmPwd" title="Confirm Your New Password" class="form-group hide">
                                    	<label for="txtConfirmPwd" class="col-sm-3 control-label">Retype Password</label>
                                        <div class="name-type col-sm-9">
                                        	<input id="txtConfirmPwd" type="password" class="form-control" placeholder="Confirm Your New Password">
                                        </div>
                                        
                                    </div>
                                
                                    <div align="center" class="form-group">
                                        <div id = "divAlert"></div>
                                    </div>
                    
                               		<div class="form-group">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 col-sm-offset-3">
                                            <button type="button" id="btnUpdate" class="btn btn-info form-control"><i class="glyphicon glyphicon-edit"></i> UPDATE PROFILE</button>
                                         </div>
                                         
                                         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <button onClick="window.location.reload(true);" class="btn btn-warning form-control"><i class="glyphicon glyphicon-refresh"></i> REFRESH</button>
                                         </div>
                                    </div>
                                </div>
                                
                                <!--Right Column-->
                                <div class="col-md-6">
                                    <center><span class="size-18 makebold" style="color:#2C86B9;"> Subscription Information </span></center><br>
                                    
                                   	<div title="Last Subscription Date" class="form-group">
                                        <label class="place col-sm-5 control-label" for="lblLastSubscriptionDate">Last Subscription Date:</label>                                           
                                         <div class="col-sm-6">
                                            <label id="lblLastSubscriptionDate" class="form-control nobold"><?php echo $subscribe_date; ?></label> 
                                        </div>                                       
                                    </div>
                                    
                                    <div title="Subscription Expiry Date" class="form-group">
                                        <label class="col-sm-5 control-label" for="lblExpiryDate">Subscription Expiry Date:</label>  
                                            
                                         <div class="col-sm-6">
                                            <label id="lblExpiryDate" class="form-control nobold"><?php echo $exp_date; ?></label> 
                                        </div>
                                    </div>
                                    
                                    <div title="Subscription Status" class="form-group">
                                        <label class="control-label col-sm-5" for="lblStatus">Subscription Status:</label>  
                                            
                                         <div class="col-sm-6">
                                            <label id="lblStatus" class="form-control nobold"><?php echo $subscriptionstatus; ?></label> 
                                        </div> 
                                    </div>
                                    
                                     <div title="Subscription Plan" class="form-group">
                                        <label class="place col-sm-5 control-label" for="lblPlan">Subscription Plan:</label>
                                         <div class="col-sm-6">
                                            <label id="lblPlan" class="form-control nobold"><?php echo $subscriber_plan; ?></label>
                                        </div>                                       
                                    </div>
                                    
                                    <div style="margin-top:30px;" class="col-sm-6 col-sm-offset-5 former">
                                    <button id="btnLogOut" class="btn btn-danger form-control"><i class="fa fa-sign-out"></i> LOGOUT</button>
                                	</div>
                                 </div>
                            </form>
                            </div>
                       </div>
                    </div> 
            </div>
        </main>
        <!--/MAIN-->
        
        <!--FOOTER-->
       <?php include('newuserfooter.php'); ?>
        <!--/FOOTER-->
      </div>
	</div>
    

<script src="<?php echo base_url();?>js/moment.min.js"></script>
<script src="<?php echo base_url();?>js/pikaday.js"></script>
 
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!--Datatable-->
<script type='text/javascript' src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.select.min.js"></script> 
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.fixedColumns.min.js"></script>
<!--End Datatable-->

<!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>lcss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->

 
</body>

</html>