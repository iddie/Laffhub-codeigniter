<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
   <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Change Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Change Password Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var email='<?php echo $email; ?>';
		
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
						
			$('#btnEdit').click(function(e) {
				try
				{
					if (!CheckForm()) return false;
			
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Password. Please Wait...</p>',theme: true,baseZ: 2000});
					
					var nm=$.trim($('#txtFullName').val());
					var un=$.trim($('#txtUsername').val());

					var mydata={email:email, UserFullName:nm, username:un,Pwd:sha512($('#txtNewPwd').val())};
								
					$.ajax({
						url: "<?php echo site_url('Editpassword/EditPwd');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {							
							$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI;
							
							$.unblockUI();
							
							if ($.trim(data)=='Ok')
							{
								$.unblockUI();
								var p='<?php $data['OldPassword']=$_SESSION['pwd']; echo $data['OldPassword'];  ?>';
								m='Password Editing Was successful';
								
								$('#txtConfirmPwd').val(''); $('#txtNewPwd').val(''); $('#txtOldPwd').val('');
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}else
							{
								$.unblockUI;
								
								m=data;
								
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI;
							
							bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
							bootbox.alert({ 
								size: 'small', message: 'Error '+ xhr.status + ' Occurred: ' + error, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
							}
					});
					
					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Edit Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });//btnEdit Click Ends
        });//End document ready
				
		function CheckForm()
		{
			try
			{
				var un=$.trim($('#txtUsername').val());
				var nm=$.trim($('#txtFullName').val());				
				var oldpwd=$('#txtOldPwd').val();
				var pwd=$('#txtNewPwd').val();
				var cpwd=$('#txtConfirmPwd').val();
				var UserOldPassword='<?php echo $_SESSION['pwd']; ?>';
								
				if (!un)
				{
					m='Username is not displaying. Please refresh the page. If this does not work, then log out and log in again. If the username is still not displaying then contact our support person.';
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					return false;
				}
								
				if (!nm) 
				{
					m='Full name is not displaying. Please refresh the page. If this does not work, then log out and log in again. If the user full name is still not displaying then contact our support person.';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					return false;
				}
				
				if (!oldpwd)
				{
					m='Old password field must not be blank';
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtOldPwd').focus();  return false;
				}
				
				if (sha512(oldpwd) != UserOldPassword)
				{
					m='Incorrect Old Password!';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtOldPwd').focus();  return false;
				}
						
				//Password
				if (!$.trim(pwd))
				{
					m='New password field must not be blank.';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtNewPwd').focus();  return false;
				}
				
				if (pwd.length<6)
				{
					m='Minimum password size is six characters.';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtNewPwd').focus(); return false;
				}
				
				//Confirm Password
				if (!$.trim(cpwd))
				{
					m='Confirm password field must not be blank.';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtConfirmPwd').focus();   return false;
				}
				
				if (pwd != cpwd)
				{
					m='New password and confirming password do not match.';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtConfirmPwd').focus();   return false;
				}
				
				//Confirm Update
				if (!confirm("This process will permanently modify your password. Please ensure that you have noted the new password before proceeding with the update as this operation is irreversible. DO NOT EXPOSE YOUR PASSWORD TO UNAUTHORIZED PERSONS. Do you want to proceed with the update? (Click OK to proceed or CANCEL to abort)"))
				{
					return false;	
				}
				
				return true;
			}catch(e)
			{
				$.unblockUI();
				m='VALIDATE PASSWORD EDITING ERROR:\n'+e;
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});
				
				return false;
			}
		}
    </script>
  </head>
  <body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">

     	 <header class="main-header">
        <!-- Logo -->
        <a href="" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini" style="font-size:15px;"><b>LaffHub</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img style="margin-top:-10px; margin-left:-10px;" src="<?php echo base_url();?>images/header_logo.png" /></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
             	  

			   <li class="dropdown user user-menu" title="User Role">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Role:&nbsp;&nbsp;<span class="hidden-xs"><?php echo $role; ?></span>
                </a>
              </li>
               
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span> <span class="hidden-xs"><?php echo $UserFullName.' ('.$username.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Username">
                    <p><b>Username:</b> <?php echo '<span class="yellowtext">'.$username.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="User FullName">
                    <p><b>Full Name:</b> <?php echo '<span class="yellowtext">'.$UserFullName.'</span>'; ?></p>
                  </li>
                  
                 <!--Role-->
				 <li class="user-body"  title="User Role">  	
                    <p><b>Role:</b> <?php echo '<span class="yellowtext">'.$role.'</span>'; ?></p>
                </li>
                     <!--Category End-->          
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?php echo site_url("Logout"); ?>" class="btn btn-danger btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
     	<?php include('sidemenu.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
         <h4>
             
          </h4>
          
          <ol class="breadcrumb size-16">
            <li><a href="<?php echo site_url("Logout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          <div class="col-md-12">
         		<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading"><i class="fa fa-key"></i>&nbsp;Change&nbsp;User&nbsp;Password</div>
              <div class="panel-body">
                           <p>
                           		<div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                           </p>
           		
                	<form class="form-horizontal">
                	<div class="form-group" title="Username">
                      <label for="txtUsername" class="col-sm-3 control-label">Username<span class="redtext">*</span></label>
    
                      <div class="col-sm-6">
                      	<input value="<?php echo $username; ?>" type="text" class="form-control label-default" id="txtUsername" readonly placeholder="Username">
                        <i class="fa fa-user form-control-feedback size-20"  style="margin-right:12px;"></i>
                      </div>
                    </div>
                    
                    <div class="form-group" title="Full Name">
                      <label for="txtFullName" class="col-sm-3 control-label">Full Name<span class="redtext">*</span></label>
    
                      <div class="col-sm-6">
                      	<input value="<?php echo $UserFullName; ?>" type="text" class="form-control label-default" id="txtFullName" readonly>
                        <span class="glyphicon glyphicon-user form-control-feedback" style="margin-right:12px;"></span>
                      </div>
                    </div>
                                                            
                    <div class="form-group" title="User Old Password">
                      <label for="txtOldPwd" class="col-sm-3 control-label">Old Password<span class="redtext">*</span></label>
    
                      <div class="col-sm-6">
                      		<input type="password" class="form-control padright" id="txtOldPwd" placeholder="Old Password" required="required">
            				<span class="glyphicon glyphicon-lock form-control-feedback" style="margin-right:12px;"></span>
                      </div>
                    </div>
                    
                    <div class="form-group" title="User New Password">
                      <label for="txtNewPwd" class="col-sm-3 control-label">New Password<span class="redtext">*</span></label>
    					
                        <div class="col-sm-6">
                      		<input type="password" class="form-control padright" id="txtNewPwd" placeholder="New Password" required="required">
            				<span class="glyphicon glyphicon-lock form-control-feedback" style="margin-right:12px;"></span>
                      </div>                                                
                    </div>
                    
                                        
                    <div class="form-group" title="Confirm Password">
                      <label for="txtConfirmPwd" class="col-sm-3 control-label">Confirm&nbsp;Password<span class="redtext">*</span></label>                    
                       <div class="col-sm-6">
                      <input type="password" class="form-control padright" id="txtConfirmPwd" placeholder="Confirm Password" required="required">
            			<span class="glyphicon glyphicon-log-in form-control-feedback" style="margin-right:12px;"></span>
                        </div>
                    </div>
                                        
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <div align="center" class="form-group">
                      <div class="col-sm-offset-1 col-sm-8">
                        <div class="box-footer">
                        	<button style="width:150px;" id="btnEdit" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit" ></span> Change Password</button>
                            
                            
                            <button style="margin-left:30px; width:150px;" id="btnRefreshProfile" type="button" class="btn btn-danger right" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                          </div>
                      </div>
                    </div>
              <!-- /.box-body -->
              		
                </form>
              </div>
          </div>
        </div>        
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
         
        </div>
        <strong>Copyright &copy; <?php echo date('Y');?> <a href="">LaffHub</a>.</strong> All rights reserved.
      </footer>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
   
    <script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
     <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
	  
	  //Load State
		var s='<?php echo trim($state); ?>';
				
		$("#cboState > option").each(function() 
		{
			//this.text + ' ' + this.value
			if ($.trim(this.value.toLowerCase())==s.toLowerCase())
			{
				$("#cboState").val(s);
				return;
			}
		});
		
		var pctype='<?php if (trim(strtolower($usertype))=='provider') echo trim($provider_type); elseif (trim(strtolower($usertype))=='customer') echo trim($customer_category); ?>';
		
		$("#cboType > option").each(function() 
		{
			if ($.trim(this.value.toLowerCase())==pctype.toLowerCase())
			{
				$("#cboType").val(pctype);
				return;
			}
		});
		
		
    </script>
    
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <!--<script src="<?php echo base_url();?>js/raphael-min.js"></script>-->
  	 <!--<script src="<?php #echo base_url();?>js/morris.min.js"></script>-->
     <script src="<?php echo base_url();?>js/jquery.sparkline.min.js"></script>
     <script src="<?php echo base_url();?>js/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery-jvectormap-world-mill-en.js"></script>
     <!--<script src="<?php #echo base_url();?>js/jquery.knob.js"></script>-->
     <!--<script src="<?php #echo base_url();?>js/Chart.min.js"></script><!-- AdminLTE App -->
     <script src="<?php echo base_url();?>js/moment.min.js"></script>
     <script src="<?php echo base_url();?>js/daterangepicker.js"></script>
     <script src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
     <script src="<?php echo base_url();?>js/bootstrap3-wysihtml5.all.min.js"></script>
     <script src="<?php echo base_url();?>js/jquery.slimscroll.min.js"></script>  
    <script src="<?php echo base_url();?>js/fastclick.min.js"></script>
    <script src="<?php echo base_url();?>js/app.min.js"></script>
    
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
    
    <script type='text/javascript' src="<?php echo base_url();?>js/highcharts/highcharts.js"></script>
	<script type='text/javascript' src="<?php echo base_url();?>js/highcharts/exporting.js"></script>
       
     
       
  </body>
</html>
