<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | MTN Settings</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">MTN Settings Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var url='<?php echo $url; ?>';
		var svcid='<?php echo $svcid; ?>';
		var channel='<?php echo $channel; ?>';
			
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Update MTN Settings. Please Wait...</p>',theme: true,baseZ: 2000});
										
					LoadValues();
								
					//Initiate POST
					var uri = "<?php echo site_url('Mtnsettings/Update');?>";
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
								m='MTN Settings Have Been Updated Successfully.';
																
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

					fd.append('url', url);
					fd.append('svcid', svcid);
					fd.append('channel', channel);
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
					url=$.trim($('#txtURL').val());
					svcid=$.trim($('#txtServiceId').val());					
					channel=$.trim($('#txtChannel').val());
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
					
					url='<?php echo $_SESSION['url']; ?>';
					svcid='<?php echo $_SESSION['svcid']; ?>';
					channel='<?php echo $_SESSION['channel']; ?>';					

					$('#txtURL').val(url);	
					$('#txtServiceId').val(svcid);	
					$('#txtChannel').val(channel);
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
				var url=$.trim($('#txtURL').val());
				var sid=$.trim($('#txtServiceId').val());
				var channel=$.trim($('#txtChannel').val());
																		
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
												
				//Billing URL
				if (!url)
				{
					m='Billing URL field must not be blank.';
					
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
					
					$('#txtURL').focus(); return false;
				}
				
				//Service Id
				if (!sid)
				{
					m='Service Id field must not be blank.';
					
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
					
					$('#txtServiceId').focus(); return false;
				}
								
				//Delivery channel
				if (!channel)
				{
					m='Delivery channel field must not be blank.';
					
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
					
					$('#txtChannel').focus(); return false;
				}
				
				//Confirm Registration
				if (!confirm('This action will permanently set or modify the MTN settings record. Do you want to proceed with the update?  Click "OK" to proceed or "CANCEL" to abort!'))
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
              <div class="panel-heading"><i class="fa fa-gear"></i>&nbsp;MTN&nbsp;Settings</div>
              <div class="panel-body">
                           <p>
                           		<div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                           </p>
                            
              	<form class="form-horizontal">
                    <!--Billing URL-->
                    <div class="form-group">
                    	 <label for="txtURL" class="col-sm-2 control-label " title="Billing URL">Billing URL<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Billing URL">
                         <input value="<?php echo $url; ?>" type="text" class="form-control" id="txtURL" placeholder="Enter Billing URL">
                      </div>
                    </div>
                    
                     <!--Service ID-->
                    <div class="form-group">
                    	 <label for="txtServiceId" class="col-sm-2 control-label " title="Service ID">Service ID<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Service ID">
                         <input value="<?php echo $svcid; ?>" type="text" class="form-control" id="txtServiceId" placeholder="Enter Service ID">
                      </div>
                    </div>
                    
                    <!--Delivery Channel-->
                    <div class="form-group">
                    	 <label for="txtChannel" class="col-sm-2 control-label " title="Delivery Channel">Delivery Channel<span class="redtext">*</span></label>
    
                      <div class="col-sm-9" title="Delivery Channel">
                         <input value="<?php echo $channel; ?>" type="text" class="form-control" id="txtChannel" placeholder="Enter Delivery Channel">
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
