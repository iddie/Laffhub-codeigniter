<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Modify Publisher Account</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    <script src="<?php echo base_url();?>js/jwplayer.js"></script>
    
      
	<script>
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
		
		var Title='<font color="#AF4442">Modify Publisher Account Help</font>';
		var m='';
		
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';			
		
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			document.getElementById('btnModify').disabled=true;
			
			LoadPublishers();
			
			function LoadPublishers()
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Publishers. Please Wait...</p>',theme: true,baseZ: 2000});
					
					table = $('#recorddisplay').DataTable( {
						select: true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						//processing: "Loading Distribution...",
						language: {
							zeroRecords: "No Publisher Record",
							//loadingRecords: "Loading Distribution. Please Wait...",
							emptyTable: "No Publisher Record Available"
							},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{
								"targets": [ 5 ],
								"visible": false
							},
							{
								"targets": [ 0 ],
								"orderable": false,
								"searchable": false
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
						],//[SELECT],Name,Email,NoVideo,Status,Sta
						columns: [
							{ width: "5%" },//SELECT
							{ width: "25%" },//PUBLISHER NAME
							{ width: "25%" },//PUBLISHER EMAIL
							{ width: "30%" },//NO OF VIDEO
							{ width: "15%" },//STATUS
							{ width: "0%" }//STATUS
						],
						order: [[ 1, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Publishers/LoadPublisherJson'); ?>',
							type: 'POST',
							complete: function(xhr, textStatus) {
								$.unblockUI();
							},
							dataType: 'json'
					   }
					} );
					
					//$.unblockUI();	
					
					
				}catch(e)
				{
					$.unblockUI();
					m='LoadPublishers Module ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
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
			
			// Add event listener for opening and closing details
			$('#recorddisplay tbody').on('click', 'td', function () 
			{
				var tr = $(this).closest('tr');
				var row = table.row( tr );
				editdata = row.data();
				
				var colIndex = $(this).index();
				
				if (colIndex==0) SelectRow(editdata);
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					document.getElementById('btnModify').disabled=false;
					//Get Selected Value					
					var val=table.row( this ).data();
					seldata=val;
					var nm=val[1],em=val[2],no=val[3],sta=val[4],status=val[5];
					
//[VIEW],PubName,PubEmail,NoVideo,Status,St
					
					$('#lblPublisher').html(nm);
					$('#lblEmail').html(em);	
					$('#cboStatus').val(status);
				}
				else 
				{					
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					ResetControls();
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				$(this).toggleClass('selected');
			} );
			
			$('#btnModify').click(function(e) {
                try
				{
					$('#divAlert').html('');
					
					var em=$('#lblEmail').html();
					var pb=$('#lblPublisher').html();
					var sta=$('#cboStatus').val();
					
					//Publisher
					if (!em)
					{
						m='No publisher record has been selected. Click on the image (magnifying glass) in the first column of the row containting the publisher record whose status you want to modify.';
						
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
					
					//Status					
					if (!sta)
					{
						m='Please select publisher status.';
						
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
						
						$('#cboStatus').focus(); return false;
					}
					
					//Confirm
					if (!confirm("This action will modify the selected publisher's record. Please note that publisher will not be able to access his account again if his status is set to NOT ACTIVE. Do you want to proceed with the action?  Click 'OK' to proceed or 'CANCEL' to abort!"))
					{
						return false;
					}
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Modifying Publisher Account. Please Wait...</p>',theme: true,baseZ: 2000});				
					
					var mydata={publisher_email:em,publisher_name:pb,publisher_status:sta,User:Username,UserFullName:UserFullName};
										
					$.ajax({
						url: "<?php echo site_url('Publishers/UpdatePublisherStatus');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($.trim(data)=='OK')
							{
								$.unblockUI();
																								
								m='Publisher Status Was Successfully Modifies.';
																
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
								$.unblockUI();
								
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
								$.unblockUI();
								
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
				}catch(e)
				{
					$.unblockUI();
					m='Modify Button Click ERROR:\n'+e;
					
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
		
		function ResetControls()
		{
			try
			{					
				document.getElementById('btnModify').disabled=true;
				
				$('#lblPublisher').html('');
				$('#lblEmail').html('');	
				$('#cboStatus').val('');
			}catch(e)
			{
				$.unblockUI();
				m="ResetControls ERROR:\n"+e;
				bootstrap_AddTitle_alert.warning(m);
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
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var nm=dat[1],em=dat[2],no=dat[3],sta=dat[4],status=dat[5];

				$('#lblPublisher').html(nm);
				$('#lblEmail').html(em);	
				$('#cboStatus').val(status);
			}else
			{
				ResetControls();
			}
		}
		
		function GetRow(sn)
		{
			//alert(table.rows( '.selected' ).count());
			ResetControls();
			
			if (sn>-1)
			{
				var dat = table.row( sn ).data();
				
				if (dat)
				{
					var nm=dat[1],em=dat[2],no=dat[3],sta=dat[4],status=dat[5];
						
					if (table.rows( '.selected' ).count() == 0)
					{
						$('#lblPublisher').html(nm);
						$('#lblEmail').html(em);	
						$('#cboStatus').val(status);
							
						document.getElementById('btnModify').disabled=false;
					}
				}
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
            <li><a href="<?php echo site_url("logout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
       

        <!-- Main content -->
        <section class="content">
          	<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-edit"></i> Modify Publisher Account</div>
                <div class="panel-body">
                	<form class="form-horizontal"> 
               	    	<!--Publisher/Email-->                                    
                        <div class="form-group" title="Publisher">
                      		<label for="lblPublisher" class="col-sm-2 control-label ">Publisher Name</label>
    
                             <div class="col-sm-4">
                                <label style="font-weight:normal;" class="form-control" id="lblPublisher"></label>
                              </div>
                      
                      		<!-- Publisher Email-->
                      	<label for="lblEmail" class="col-sm-2 control-label" title="Publisher Email">Publisher Email</label>
        
                      	<div class="col-sm-4" title="Publisher Email">
                         	<label class="form-control nobold" id="lblEmail"></label>
                      </div>
                    </div> 
                                          
                     <!--Status-->
                     <div class="form-group">
                      <label for="cboStatus" class="col-sm-2 control-label " title="Publisher Status">Publisher Status</label>
    
                      <div class="col-sm-4">
                         <select class="form-control" id="cboStatus" >
                         	<option value="">[SELECT]</option>
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
						 </select>
                      </div>
                      
                       <!--Buttons-->
                         <span style="float:right; margin-right:20px;">
                        <button style="width:180px" id="btnModify" type="button" class="btn btn-primary btn-flat"><i class="fa fa-pencil-square"></i> Modify Account</button>
                       
                          <button onClick="window.location.reload(true);" style="width:180px; margin-left:20px;" id="btnRefresh" type="button" class="btn btn-warning btn-flat"><i class="fa fa-refresh"></i> Refresh</button>
                          
                         </span>
                    </div> 
                                                            
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="Laffhub Publishers" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th></th>
                                <th>PUBLISHER&nbsp;NAME</th>
                                <th>PUBLISHER&nbsp;EMAIL</th>
                                <th>NO&nbsp;OF&nbsp;VIDEOS&nbsp;UPLOADED</th>
                                <th>ACCOUNT&nbsp;STATUS</th> 
                                <th class="hide">STATUS</th> 
                            </tr>
                          </thead>
                      </table>
                    </div>
                </center>
                   
              <!-- /.box-body -->
              		
                </form>
                           
              </div>
          </div>
        </section><!-- /.row (main row) -->
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
    <!--<script src="<?php# echo base_url();?>js/raphael-min.js"></script>-->
  	 <!--<script src="<?php# #echo base_url();?>js/morris.min.js"></script>-->
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
    <!--<script src="<?php #echo base_url();?>js/dashboard.js"></script>-->

 </body>
</html>
