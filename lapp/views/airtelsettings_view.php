<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Airtel Settings</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Airtel Settings Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var messaging_username='<?php echo $messaging_username; ?>';
		var messaging_password='<?php echo $messaging_password; ?>';
		var billing_username='<?php echo $billing_username; ?>';
		var billing_password='<?php echo $billing_password; ?>';
		var wsdl_path='<?php echo $wsdl_path; ?>';
		var billing_location='<?php echo $billing_location; ?>';
		var messaging_url='<?php echo $messaging_url; ?>';
		var cpId='<?php echo $cpId; ?>';
		var opt_out_msg='<?php echo $opt_out_msg; ?>';
			
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
						
			$('#btnUpdate').click(function(e) {
				try
				{
					if (!CheckForm()) return false;
			
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Update Airtel Settings. Please Wait...</p>',theme: true,baseZ: 2000});
										
					LoadValues();
								
					//Initiate POST
					var uri = "<?php echo site_url('Airtelsettings/Update');?>";
					var xhr = new XMLHttpRequest();
					var fd = new FormData();
					
					xhr.open("POST", uri, true);
					
					xhr.onreadystatechange = function() {
						//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
						if (xhr.readyState == 4 && xhr.status == 200)
						{
							// Handle response.
							$.unblockUI();
							
							var res=$.trim(xhr.responseText);
														
							if (res.toUpperCase()=='OK')
							{
								m='Airtel Settings Have Been Updated Successfully.';
																
								ResetControls();
										
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback: function() {
  										window.location.reload(true);
									}
								});
							}else
							{
								m=res;
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
					};

					fd.append('messaging_username', messaging_username);
					fd.append('messaging_password', messaging_password);
					fd.append('billing_username', billing_username);
					fd.append('billing_password', billing_password);
					fd.append('wsdl_path', wsdl_path);
					fd.append('billing_location', billing_location);
					fd.append('messaging_url', messaging_url);
					fd.append('cpId', cpId);
					fd.append('opt_out_msg', opt_out_msg);
					fd.append('username', Username);
					fd.append('UserFullName', UserFullName);
					
					xhr.send(fd);// Initiate a multipart/form-data upload

					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Update Button Click ERROR:\n'+e;
					
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
            });//btnUpdate Click Ends
			
			function LoadValues()
			{
				try
				{
					
					var opt=$.trim($('#txtOptOut').val());
					
					messaging_username=$.trim($('#txtMsgUsername').val());
					messaging_password=$.trim($('#txtMsgPwd').val());					
					billing_username=$.trim($('#txtBillingUsername').val());
					billing_password=$.trim($('#txtBillingPwd').val());
					wsdl_path=$.trim($('#txtWSDL').val());
					billing_location=$.trim($('#txtBillingLocation').val());
					messaging_url=$.trim($('#txtMsgUrl').val());					
					cpId=$.trim($('#txtCPId').val());
					opt_out_msg=$.trim($('#txtOptOut').val());
				}catch(e)
				{
					m='LoadValues ERROR:\n'+e;
					
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
			
			function ResetControls()
			{
				try
				{
					Username='<?php echo $_SESSION['username']; ?>';
					UserFullName='<?php echo $_SESSION['UserFullName']; ?>';
					
					messaging_username='<?php echo $_SESSION['messaging_username']; ?>';
					messaging_password='<?php echo $_SESSION['messaging_password']; ?>';
					billing_username='<?php echo $_SESSION['billing_username']; ?>';
					billing_password='<?php echo $_SESSION['billing_password']; ?>';
					wsdl_path='<?php echo $_SESSION['wsdl_path']; ?>';
					billing_location='<?php echo $_SESSION['billing_location']; ?>';
					messaging_url='<?php echo $_SESSION['messaging_url']; ?>';
					cpId='<?php echo $_SESSION['cpId']; ?>';					
					opt_out_msg='<?php echo $_SESSION['opt_out_msg']; ?>';	
					
										
					$('#txtMsgUsername').val(messaging_username);
					$('#txtMsgPwd').val(messaging_password);
					$('#txtBillingUsername').val(billing_username);
					$('#txtBillingPwd').val(billing_password);
					$('#txtWSDL').val(wsdl_path);
					$('#txtBillingLocation').val(billing_location);	
					$('#txtMsgUrl').val(messaging_url);	
					$('#txtCPId').val(cpId);
					$('#txtOptOut').val(opt_out_msg);
				}catch(e)
				{
					$.unblockUI();
					m="ResetControls ERROR:\n"+e;
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
			}//End ResetControls
			
			$('#ancLogout').click(function(e) {
                try
				{
					LogOut();
				}catch(e)
				{
					$.unblockUI();
					m="Sign Out Button Click ERROR:\n"+e;
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
			
			$('#ancMenuSignOut').click(function(e) {
                try
				{
					LogOut();
				}catch(e)
				{
					$.unblockUI();
					m="Sign Out Button Click ERROR:\n"+e;
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
        });//End document ready
		
		
		function CheckForm()
		{
			try
			{
				var msgun=$.trim($('#txtMsgUsername').val());
				var msgpwd=$.trim($('#txtMsgPwd').val());
				var billun=$.trim($('#txtBillingUsername').val());
				var billpwd=$.trim($('#txtBillingPwd').val());
				var wsdl=$.trim($('#txtWSDL').val());				
				var loc=$.trim($('#txtBillingLocation').val());
				var murl=$.trim($('#txtMsgUrl').val());
				var cpid=$.trim($('#txtCPId').val());		
				var opt=$.trim($('#txtOptOut').val());
																		
				//Username
				if (!Username)
				{
					m='Your current session seems to have timed out. Refresh the window. If it is still blank, sign out and sign in again before continuing with the settings update.';
					
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
				
				
				//Messaging Username
				if (!msgun)
				{
					m='Messaging username field must not be blank.';
					
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
					
					$('#txtMsgUsername').focus(); return false;
				}
				
				//Messaging Password
				if (!msgpwd)
				{
					m='Messaging password field must not be blank.';
					
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
					
					$('#txtMsgPwd').focus(); return false;
				}
												
				//Billing Username
				if (!billun)
				{
					m='Billing username field must not be blank.';
					
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
					
					$('#txtBillingUsername').focus(); return false;
				}
				
				//Billing Password
				if (!billpwd)
				{
					m='Billing password field must not be blank.';
					
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
					
					$('#txtBillingPwd').focus(); return false;
				}
				
				
				//WSDL path
				if (!wsdl)
				{
					m='WSDL path field must not be blank.';
					
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
					
					$('#txtWSDL').focus(); return false;
				}
								
				//Billing Location
				if (!loc)
				{
					m='Billing location field must not be blank.';
					
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
					
					$('#txtBillingLocation').focus(); return false;
				}
				
				//Messaging Url
				if (!murl)
				{
					m='Messaging Url field must not be blank.';
					
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
					
					$('#txtMsgUrl').focus(); return false;
				}
								
				//CPID
				if (!cpid)
				{
					m='Content partner Id field must not be blank.';
					
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
					
					$('#txtCPId').focus(); return false;
				}
				
				//Opt Out Message
				if (!opt)
				{
					m='Opt out message field must not be blank.';
					
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
					
					$('#txtOptOut').focus(); return false;
				}
				
				if ($.isNumeric(opt))
				{
					m="SuOpt out message field must not be a number.";
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
					
					$('#txtOptOut').focus(); return false;
				}
				
				if (opt.length < 2)
				{
					m="Please enter a meaningful Opt out message.";
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
					
					$('#txtOptOut').focus(); return false;
				}
				
				//Confirm Registration
				if (!confirm('This action will permanently set or modify the airtel settings record. Do you want to proceed with the update?  Click "OK" to proceed or "CANCEL" to abort!'))
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
		
		
		function LogOut()
		{
			var m="Signing out will abort every active process and unsaved data will be lost. Do you still want to sign out? (Click <b>YES</b> to proceed or <b>NO</b> to abort)";
										
			bootbox.confirm({
				title: "<font color='#ff0000'>LaffHub | Sign Out</font>",
				message: m,
				buttons: {
					confirm: {
						label: 'Yes',
						className: 'btn-success'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result) window.location.href='<?php echo site_url("Logout"); ?>';
				}
			});	
		}
    </script>
  </head>
  <body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">

      <?php include('adminheader.php'); ?>
      <!-- Left side column. contains the logo and sidebar -->
     	<?php include('sidemenu.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
        <span style="float:left; font-size:22px; color:#AC5288;">LaffHub</span>
          
          <span style="float:right;"><a id="ancLogout" href="#"><i class="fa fa-home"></i> Home</a></span>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          <div class="col-md-12">
         		<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading"><i class="fa fa-gear"></i>&nbsp;Airtel&nbsp;Settings</div>
              <div class="panel-body">
                           <p>
                           		<div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                           </p>
                            
              	<form class="form-horizontal"> 
               	    	<!--Messaging Username/Messaging Password-->                                    
                     <div class="form-group">
                      <label for="txtMsgUsername" class="col-sm-2 control-label ">Messaging Username<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Messaging Username">
                         <input value="<?php echo $messaging_username; ?>" type="text" class="form-control" id="txtMsgUsername" placeholder="Enter Messaging Username">
                      </div>
                      
                      <!-- Messaging Password-->
                      <label for="txtMsgPwd" class="col-sm-3 control-label " title="Messaging Password">Messaging Password<span class="redtext">*</span></label>
        
                      <div class="col-sm-3">
                         <input value="<?php echo $messaging_password; ?>" type="text" class="form-control" id="txtMsgPwd" placeholder="Enter Messaging Password">
                      </div>
                    </div> 
                    
                    <!--Billing Username/Billing Pasword-->                                     
                      <div class="form-group">
                      <label for="txtBillingUsername" class="col-sm-2 control-label " title="Billing Username">Billing Username<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Billing Username">
                         <input value="<?php echo $billing_username; ?>" type="text" class="form-control" id="txtBillingUsername" placeholder="Enter Billing Username">
                      </div>
                      
                      <!--Billing Password-->
                      <label for="txtBillingPwd" class="col-sm-3 control-label " title="Billing Password">Billing Password<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Billing Password">
                         <input value="<?php echo $billing_password; ?>" type="text" class="form-control" id="txtBillingPwd" placeholder="Enter Billing Password">
                      </div>
                    </div>  
                      
                     <!--WSDL Path-->
                     <div class="form-group">
                      <label for="txtWSDL" class="col-sm-2 control-label " title="WSDL Path">WSDL Path<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="WSDL Path">
                         <input value="<?php echo $wsdl_path; ?>" type="text" class="form-control" id="txtWSDL" placeholder="Enter WSDL Path">
                      </div>
                    </div> 
                                       
                    
                    <!--Billing Location-->
                    <div class="form-group">
                    	 <label for="txtBillingLocation" class="col-sm-2 control-label " title="Billing Location">Billing Location<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Billing Location">
                         <input value="<?php echo $billing_location; ?>" type="text" class="form-control" id="txtBillingLocation" placeholder="Enter Billing Location">
                      </div>
                    </div>
                    
                     <!--Messaging URL-->
                    <div class="form-group">
                    	 <label for="txtMsgUrl" class="col-sm-2 control-label " title="Messaging URL">Messaging URL<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Messaging URL">
                         <input value="<?php echo $messaging_url; ?>" type="text" class="form-control" id="txtMsgUrl" placeholder="Enter Messaging URL">
                      </div>
                    </div>
                    
                    <!--Content Partner ID-->
                    <div class="form-group">
                    	 <label for="txtCPId" class="col-sm-2 control-label " title="Content Partner ID">Content Partner ID<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Content Partner ID">
                         <input value="<?php echo $cpId; ?>" type="text" class="form-control" id="txtCPId" placeholder="Enter Content Partner ID">
                      </div>
                    </div>
                    
                    
                     <!--Opt Out Message-->
                    <div class="form-group">
                        <label title="Opt Out Message" class="col-sm-2 control-label left" for="txtOptOut">Opt Out Message<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-9" title="Opt Out Message">
                            <textarea rows="4" class="form-control" id="txtOptOut" placeholder="Enter Opt Out Message"><?php echo $opt_out_msg; ?></textarea>
                          </div>
                    </div>
                       
                                        
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <div align="center" class="form-group">
                      <div class="col-sm-offset-1 col-sm-8">
                        <div class="box-footer">
                        	<button style="width:150px;" id="btnUpdate" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit" ></span> Update Settings</button>
                                                        
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
