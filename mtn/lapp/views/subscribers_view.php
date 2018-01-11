<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Africa/Lagos');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Subscribers</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?php echo base_url();?>js/html5shiv.min.js"></script>
        <script src="<?php echo base_url();?>js/respond.min.js"></script>
    <![endif]-->
    
    <script>
		var default_network='<?php echo $default_network; ?>';
		
		var Username='<?php echo $username; ?>';
		var table;
		var Title='<font color="#AF4442">Subscribers Help</font>';
		var m='';
				
		bootstrap_alert = function() {}
		bootstrap_alert.warning = function(message) 
		{
		   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
		
    	$(document).ready(function(e) 
		{
			var buttonCommon = {
				exportOptions: {
					/*format: {
						body: function ( data, column, row, node ) {
							// Strip $ from salary column to make it numeric
							return column === 5 ? data.replace( /[$,]/g, '' ) : data;
						}
					}*/
				}
			};
			
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			LoadNetwork();
			GetSubscribers();
			
			function LoadNetwork()
			{
				try
				{
					$('#cboNetwork').empty();
					$('#cboNetwork').append( new Option('[SELECT]','') );
					$('#cboNetwork').append( new Option('Airtel','Airtel') );
					$('#cboNetwork').append( new Option('Etisalat','Etisalat') );
					$('#cboNetwork').append( new Option('GLO','GLO') );					
					$('#cboNetwork').append( new Option('MTN','MTN') );
					
					$('#cboNetwork').val(default_network);
				}catch(e)
				{
					$.unblockUI();
					m='LoadNetwork Module ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
						
			$('#btnDisplay').click(function(e) 
			{
				try
				{
					var nt=$('#cboNetwork').val();
					
					DisplaySubscribers(nt);
				}catch(e)
				{
					$.unblockUI();
					m='Display Report Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			});//btnDisplay.click
			
			function DisplaySubscribers(nt)
			{
				try
				{
					$.blockUI({message:'<img src="<?php echo base_url();?>images/loader.gif" /><p>Retrieving Subscribers. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var msg;
										
					if (nt) msg = nt + ' Subscribers Report'; else msg = 'Subscribers Report';
					
					
					var mydata={network:nt};	
																
					$.ajax({
						url: "<?php echo site_url('Subscribers/GetSubscribers'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'json',
						beforeSend: function(){
							//if (table) table.destroy();
						},
						complete: function(xhr, textStatus) {					
							$.unblockUI();
						},
						success: function(dataSet,status,xhr) {
							if (table) table.destroy();
								
								table = $('#recorddisplay').DataTable( {
									dom: 'B<"top"if>rt<"bottom"lp><"clear">',
									//dom: 'Bfrtip',
									language: {zeroRecords: "No Subscriber Record Found"},
									buttons: [						
										$.extend( true, {}, buttonCommon, {
											extend: 'pdf',
											pageSize: 'A4',//LEGAL
											title: '',
											message: msg, 
											download: 'open',
											exportOptions: {
												//columns: [ 0, 1, 2, 3 ]
											},
											//message: msg,
											customize: function ( doc ) {
												doc.content[1].table.widths = [ '10%', '30%', '30%', '30%' ],
												doc.content.splice( 0, 0, {
													margin: [ 0, 0, 0, 12 ],
													alignment: 'left',
													image: 'data:image/png;base64,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'
												} );
											}
										} ),
										
										$.extend( true, {}, buttonCommon, {
											extend: 'copyHtml5'
										} ),
										$.extend( true, {}, buttonCommon, {
											extend: 'excelHtml5'
										} ),
										$.extend( true, {}, buttonCommon, {
											extend: 'csvHtml5'
										} ),
										$.extend( true, {}, buttonCommon, {
											extend: 'print',
											customize: function ( win ) {
												$(win.document.body)
													.css( 'font-size', '12pt' )
													.prepend(
														'<img src="<?php echo base_url(); ?>images/emaillogo.png" style="top:20px; left:0; padding-bottom:15px;" />'
													);
							 
												$(win.document.body).find( 'table' )
													.addClass( 'compact' )
													.css( 'font-size', 'inherit' ).css( 'margin-top', '50px' );
											},
											pageSize: 'A4',//LEGAL
											title: '',
											message: '<b>'+msg+'</b>',
											exportOptions: {
												columns: [ 0, 1, 2, 3 ]
											}
										} ),
									],
									
									columnDefs: [
										{
											"targets": [ 0,1,2,3 ],
											"visible": true,
											"orderable": true,
											"searchable": true
										},
										{
											"searchable": false,
											"orderable": false,
											"targets": 0
										},
										{ 
											className: "dt-center", "targets": [ 0,1,2,3 ] 
										},
										{ 
											className: "dt-center","targets": [ 0,1,2,3 ],"appliesTo": ['pdf','print'] 
										}
									],					
									order: [[ 2, 'asc' ]],
									data: dataSet,//SN/Phone/Network/Status
									columns: [
										{ width: "10%" },//SN
										{ width: "30%" },//Phone
										{ width: "30%" },//Network
										{ width: "10%" }//Status
									]
								});
								
								table.on( 'order.dt search.dt', function () {
									table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
										cell.innerHTML = i+1;
									} );
								} ).draw();
							
						},
						error:  function(xhr,status,error) {
							m='Error '+ xhr.status + ' Occurred: ' + error;
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
							}
					});	
					
					$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='DisplaySubscribers Module Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}//End DisplaySubscribers
			
			function GetSubscribers()
			{
				try
				{
					var nt=$('#cboNetwork').val();
					
					DisplaySubscribers(nt)
				}catch(e)
				{

				}
			}
        });//End document ready
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
              <div class="box box-success expanded-box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-users"></i> Subscribers</h3>
    
                  <div class="box-tools pull-right" title="Click to collapse or expand">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                
                
                 <div class="box-body">
                 	<!--Network-->
                 	<div class="form-group">
                        <label class="col-sm-1 control-label" for="cboNetwork" title="Select Network">Network</label>
                        
                        <div class="col-sm-2" title="Select Network">
                          <select id="cboNetwork" class="form-control"></select>
                        </div>
                        
                        <span>
                         <!--Buttons-->
                            <button style="width:150px;" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display</button>
                            
                         	<button style="width:150px; margin-left:15px;" id="btnRefreshSubscrbers" type="button" class="btn btn-danger" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Reset</button>
                         </span>
                   </div>
                 </div>
                 	
                    
                    <div align="center">
                        <div id = "divAlert"></div>
                    </div>
                    
                    <table id="recorddisplay" cellspacing="0" class="display table table-bordered table-hover table-striped" width="99.5%" title="Subscribers Records">
                  
                        <thead align="left" style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                              <td><b>SN</b></td>
                              <td><b>Phone.&nbsp;No</b></td>
                              <td><b>Network</b></td>
                              <td><b>Status</b></td>
                            </tr>
                        </thead>
      
                        </table>
                </div>
              </div>
              <!-- /.box -->
            </div>        
            <!-- right col -->
          </div>     
          
         <div class="row">     <!-- BAR CHART -->
          <div class="col-md-12">
          <div class="box box-success collapsed-box box-solid"><!--collapsed-box-->
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-bar-chart"></i>&nbsp;Transaction&nbsp;Summary</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"  title="Click to collapse or expand"><i class="fa fa-plus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              	<div style="width:78%; margin-top:30px;" id="ChartContainer"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>        
        <!-- right col -->
      </div>
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          
        </div>
        <strong>Copyright &copy; <?php echo date('Y');?> <a href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
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
    <!--<script src="<?php #echo base_url();?>js/dashboard.js"></script>-->
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
    
    <script type='text/javascript' src="<?php echo base_url();?>js/highcharts/highcharts.js"></script>
	<script type='text/javascript' src="<?php echo base_url();?>js/highcharts/exporting.js"></script>
        
  </body>
</html>
