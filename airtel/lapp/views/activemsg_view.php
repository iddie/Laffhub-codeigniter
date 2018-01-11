<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Set Active Health Message</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var seldata,OldId='';
		var Title='<font color="#AF4442">Set Active Tip Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
	
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
		
		var table,editdata,seldata;
					
    	$(document).ready(function(e) {
			$(function() {			
				$.blockUI.defaults.css = {};// clear out plugin default styling
			});
		
			$(document).ajaxStop($.unblockUI);
					
			table = $('#recorddisplay').DataTable( {
					 select: true,
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					autoWidth:false,
					language: {zeroRecords: "No Health Message Record Found"},
					lengthMenu: [
										[ 10, 25, 50, 100,-1 ],
										[ '10', '25', '50', '100', 'All' ]
									],
					columnDefs: [ 
						{
							"targets": [ 0,1,2 ],//Record ID
							"visible": true,
							"searchable": true,
							"orderable": true
						},
						{ className: "dt-center", "targets": [ 0,1,2 ] }
					],//Msg,MsgID,Status
					columns: [
						{ width: "80%" },//Msg
						{ width: "10%" },//Msg ID
						{ width: "10%" }//Status
					],
					order: [[ 2, 'desc' ]],
					ajax: '<?php echo site_url('Activemsg/LoadMsgJson'); ?>'
				} );
				
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
								
					//alert('Select');
					//Get Selected Value
					var val=table.row( this ).data();
					seldata=val;
					var g=val[0],id=val[1],sta=val[2];
					
					if ($.trim($('#txtID').val())) OldId=$.trim($('#txtID').val());
					
					$('#txtMsg').val(g); $('#txtID').val(id); $('#txtStatus').val(sta);
					
				}
				else {
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					//alert('UnSelect');
					
					$('#txtMsg').val(''); $('#txtID').val(''); $('#txtStatus').val('');
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
			} );
								
			$('#btnSetMsg').click(function(e) {
				try
				{
					if (!checkForm()) return false;
					
					$('#divAlert').html('');
					
					//Send values here
						$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Setting Active Health Message. Please Wait...</p>',theme: true,baseZ: 2000});
																					
					$.ajax({
						url: "<?php echo site_url('Activemsg/SetActiveMsg'); ?>",
						//data: mydata,
						type: 'POST',
						dataType: 'json',
						beforeSend: function(){
							//if (table) table.destroy();
						},
						complete: function(xhr, textStatus) {
							$.unblockUI();
						},
						success: function(dataSet,status,xhr) {
							if ($(dataSet).length > 0)
							{
								$.each($(dataSet), function(i,e)
								{
									if (e.Status=='OK')
									{
										//Clear boxes
										OldId='';
										
										//table.ajax.reload();
										
										bootstrap_Success_alert.warning(e.Msg);
										bootbox.alert({ 
											size: 'small', message: e.Msg, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback: function (){
												window.location.reload(true);
											}
										});
									}else
									{
										bootstrap_alert.warning(e.Msg);
										bootbox.alert({ 
											size: 'small', message: e.Msg, title:Title,
											buttons: { ok: { label: "Close!", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									}
								});
							}else
							{
								m='No Record Was Returned.';
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close!", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
								
								table=null;//if (table) table.destroy();
							}
							
							//LoadPostings();
							//alert(data);
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								m='Error '+ xhr.status + ' Occurred: ' + error;
								
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close!", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}
					});		
				}catch(e)
				{
					$.unblockUI();
					m='Set Active Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			});//btnDisplay.click
        });//End document ready
		
		function checkForm()
		 {
			  try
			 {
				var g='',id='';
				 
				 if (seldata)
				 {
					g=seldata[1]; id=seldata[2];
					
					if ($.trim(g)=='')
					{
						m='Please select the message to set as the current natural health tip from the table before clicking on "SET AS CURRENT TIP" button.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						return false;
					}
				 }
				 
				 if (!confirm('Are you sure you want to set this message as the current message (Click "OK" to proceed or "CANCEL") to abort)?'))
				{
					return false;
				}
						
				return true;			
			 }catch(e)
			 {
				m='CHECK FORM ERROR:\n'+e; 
				
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
		 }//End CheckForm
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
                  <span class="glyphicon glyphicon-user"></span> <span id="spnUserFullname" class="hidden-xs"><?php echo $UserFullName.' ('.$username.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Username">
                    <p><b>Username:</b> <?php echo '<span class="yellowtext">'.$username.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="User FullName">
                    <p><b>Full Name:</b> <span id="spnUserFullname1"><?php echo '<span class="yellowtext">'.$UserFullName.'</span>'; ?></span></p>
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
              <div class="panel-heading"><i class="fa fa-weixin"></i> Set Active Natural Health Tip</div>
              <div class="panel-body">
              		<form class="form-horizontal"> 
                	<div class="form-group" title="Natural Health Tip">
                      <label for="txtMsg" class="col-sm-2 control-label ">Active Natural Health Tip<span class="redtext">*</span></label>
    
                      <div class="col-sm-10">
                         <textarea readonly style="text-transform:none;" class="form-control" id="txtMsg" placeholder="Natural Health Tip" required rows="3"><?php echo $ActiveMsg; ?></textarea>
                         <i class="fa fa-tag form-control-feedback size-20"  style="margin-right:12px;"></i>
                      </div>
                      
                    </div>
                    
                    <!--Health Messages ID/Status-->
                    <div class="form-group" title="Health Tip ID">
                      <!--Health Messages ID-->
                     <label for="txtID" class="col-sm-2 control-label ">Health Tip ID<span class="redtext">*</span></label>
    
                      <div class="col-sm-2">
                         <input readonly style="text-transform:none;" type="text" class="form-control" id="txtID" placeholder="Natural Health Tip ID" required value="<?php echo $ActiveMsgID; ?>">
                        </div>
                      
                       <!--Status-->
                      <label for="txtStatus" class="col-sm-2 control-label ">Status<span class="redtext">*</span></label>
    
                      <div align="center" class="col-sm-2" title="Natural Health Tip Status">
                       <input readonly value="<?php echo $ActiveStatus; ?>" type="text" class="form-control redtext" id="txtStatus" placeholder="Natural Health Tip Status">
                      </div>
                      
                      <div class="col-sm-4">
                       	<button title="Set Current Natural Health Tip" id="btnSetMsg" type="button" class="btn btn-warning makebold" role="button" style="width:200px;" >
                            <span class="ui-button-text"><i class="fa fa-gear"></i> Set As Current Tip</span>
                        </button>
                    	</div>
                    </div>
                    
                   
                    <!--Publish Date/Expiry Date-->
                    <div class="form-group">
                    	<!--Publish Date-->
                        <label class="col-sm-2 control-label left" title="Natural Health Tip Publish Date" for="txtPublishDate">Publish Date<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-2" title="Natural Health Tip Publish Date">
                            <input  readonly value="<?php echo $ActivePublishDate; ?>" type="text" class="form-control" id="txtPublishDate" placeholder="Message Publish Date">
                          </div>
                         
                         
                         <!--Expiry Date--> 
                        <label class="col-sm-2 control-label left" for="txtExpiryDate" title="Natural Health Tip Expiry Date">Expiry Date<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-2" title="Natural Health Tip Expiry Date">
                       <input readonly value="<?php echo $ActiveExpireDate; ?>" type="text" class="form-control" id="txtExpiryDate" placeholder="Messages Expiry Date">
                      </div>
                      
                      <div class="col-sm-4">
                       	<button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-primary makebold" role="button" style="width:200px; " >
                            <span class="ui-button-text">Refresh</span>
                        </button>
                          
                    	</div>
                    </div>
                    
                        
    				<div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                  
                     <!--Display Table:  table table-hover table-bordered table-condensed stripe -->
                     <div class="table-responsive" >
                        <table id="recorddisplay" border="1" cellspacing="0" class="display table table-bordered table-hover table-striped" width="100%">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>                                  
                                    <th>NATURAL HEALTH TIP</th>
                                    <th>TIP&nbsp;ID</th>
                                    <th>STATUS</th>
                                </tr>
                              </thead>     
                          </table>
                    	</div>
                        <input type="hidden" name="hidID" id="hidID" value="" />
                    </form>
              </div><!--End Panel Body-->
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
