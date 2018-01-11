<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Edit Profile</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Edit Profile Help</font>';
		var m='';
		
		var PublisherEmail='<?php echo $publisher_email; ?>';
		var PublisherName='<?php echo $publisher_name; ?>';
	
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Publisher Profile. Please Wait...</p>',theme: true,baseZ: 2000});
										
					//Make Ajax Request
					var nm=$.trim($('#txtName').val());
					var ph=$.trim($('#txtPhone').val());
												
					var mydata={publisher_name:nm, publisher_phone:ph, PublisherEmail:PublisherEmail, PublisherName:PublisherName};
																									
					$.ajax({
						url: "<?php echo site_url('Pubeditprofile/EditProfile');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							var ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								$.unblockUI();
								
								m='Profile Editing Was successful';
								
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
					m='Edit Button Click ERROR:\n'+e;
					
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
            });//btnEdit Click Ends
        });//End document ready
		
		
		function CheckForm()
		{
			try
			{
				var nm=$.trim($('#txtName').val());
				var ph=$.trim($('#txtPhone').val());				
								
				//PublisherEmail
				if (!PublisherEmail)
				{
					m='Your current session seems to have timed out. Refresh the window. If it is still blank, sign out and sign in again before continuing with the profile editing.';
					
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
				
				//Publisher Name
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
				
								
				//Confirm Update
				if (!confirm('This action will permanently modify your profile record. Do you want to proceed with the updating of the profile data?  Click "OK" to proceed or "CANCEL" to abort!'))
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
                  Role:&nbsp;&nbsp;<span class="hidden-xs yellowtext">Publisher</span>
                </a>
              </li>
               
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span> <span class="hidden-xs yellowtext"><?php echo $publisher_name.' ('.$publisher_email.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Email">
                    <p><b>Email:</b> <?php echo '<span class="yellowtext">'.$publisher_email.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="Publisher Name">
                    <p><b>Name:</b> <?php echo '<span class="yellowtext">'.$publisher_name.'</span>'; ?></p>
                  </li>
                  
                 <!--Role-->
				 <li class="user-body"  title="User Role">  	
                    <p><b>Role:</b> <span class="yellowtext">Publisher</span></p>
                </li>
                     <!--Category End-->          
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?php echo site_url("Publogout"); ?>" class="btn btn-danger btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
     	<?php include('pubsidemenu.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
         <h4>
             
          </h4>
          
          <ol class="breadcrumb size-16">
            <li><a href="<?php echo site_url("Publogout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          <div class="col-md-12">
         		<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading"><i class="fa fa-edit"></i>&nbsp;Edit&nbsp;User&nbsp;Profile</div>
              <div class="panel-body">
                           <p>
                           		<div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                           </p>
                            
              	<form class="form-horizontal"> 
               	    	<!--Publisher Name-->                                    
                        <div class="form-group" title="Publisher Name">
                      <label for="txtName" class="col-sm-3 control-label ">Publisher Name<span class="redtext">*</span></label>
    
                      <div class="col-sm-6">
                         <input value="<?php echo $publisher_name; ?>" type="tel" class="form-control" id="txtName" placeholder="Publisher Name">
                        <i class="material-icons form-control-feedback size-18" style="margin-right:12px; margin-top:7px;">contacts</i>
                      </div>
                    </div> 
                                                              
                       <!--Phone-->                                     
                      <div class="form-group" title="Publisher Phone Number">
                      <label for="txtPhone" class="col-sm-3 control-label ">Phone No</label>
    
                      <div class="col-sm-6">
                         <input value="<?php echo $publisher_phone; ?>" type="tel" class="form-control" id="txtPhone" placeholder="Publisher Phone No">
                         <span class="glyphicon glyphicon-phone form-control-feedback" style="margin-right:12px;"></span>
                      </div>
                    </div>  
                                        
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <div align="center" class="form-group">
                      <div class="col-sm-offset-1 col-sm-8">
                        <div class="box-footer">
                        	<button style="width:150px;" id="btnEdit" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit" ></span> Edit Profile</button>
                                                        
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
