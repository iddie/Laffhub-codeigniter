<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Africa/Lagos');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Audit Trail</title>
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
		var Title='<font color="#AF4442">Audit Trail Help</font>';
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
					format: {
						body: function ( data, column, row, node ) {
							return column === 0 ?
								data.replace(new RegExp('&nbsp;', 'g'), ' ') :
								data;
						}
					}
				}
			};
			
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
			
        	$(document).ajaxStop($.unblockUI);
			
			$('#txtStartate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 3,
				forceParse: 0,
				format: 'dd M yyyy'
			});
			
			$('#txtStartate').change(function(e) {
				try
				{
					if ($('#txtStartate').val() && $('#txtEndDate').val())
					{
						VerifyStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="Start Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } }
					});
				}
            });
			
			$('#txtEndDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 3,
				forceParse: 0,
				format: 'dd M yyyy'
			});
						
			$('#txtEndDate').change(function(e) 
			{
				try
				{
					if ($('#txtStartate').val() && $('#txtEndDate').val())
					{
						VerifyStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="End Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } }
					});
				}
            });
			
			function VerifyStartAndEndDates()
			{
				try
				{
					$('#divAlert').html('');
					
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					//var pdt = moment(startdt), ddt = moment(enddt);
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid())
					{
						m="Report Start Date Is Not Valid. Please Select A Valid Report Start Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } }
						});
					}
					
					if (!ddt.isValid())
					{
						m="Report End Date Is Not Valid. Please Select A Valid Report End Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } }
						});
					}
										
					//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
					
					var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
					var diff = moment.duration(ddt.diff(pdt));
					var mins = parseInt(diff.asMinutes());
					
					
					if (dys<0)
					{
						$('#txtEndDate').val('');
						$('#txtDays').val('');
						
						m="Report End Date Is Before Report Start Date. Please Correct Your Entries!";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } }
						});
					}else
					{
						if (dys==0)
						{
							if (mins<0)
							{
								$('#txtEndDate').val('');
								$('#txtDays').val('');
						
								m="Report End Date Is Before Report Start Date. Please Correct Your Entries!";
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close!", className: "btn-danger" } }
								});
							}
						}
					}
				}catch(e)
				{
					$.unblockUI();
					m="VerifyStartAndEndDates ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } }
					});
				}
			}
				
			$('#btnDisplay').click(function(e) 
			{
				try
				{
					if (!Validate()) return false;
					
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
		
					DisplayTransaction(sdt,edt);
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
			
			function DisplayTransaction(sdt,edt)
			{
				try
				{
					$.blockUI({message:'<img src="<?php echo base_url();?>images/loader.gif" /><p>Retrieving Audit Trail. Please Wait...</p>',theme: true,baseZ: 2000});
			
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					//var pdt = moment(startdt), ddt = moment(enddt);
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid()) sdt='';
					if (!ddt.isValid()) edt='';
					
					//Make Ajax Request
					var msg;
										
					msg='Audit Trail Report';					
					
					if (sdt && edt)
					{
						if (sdt == edt)
						{
							msg = msg + ' For '+ $('#txtStartate').val();
						}else
						{
							msg = msg + ' Between '+ $('#txtStartate').val() + ' And ' + $('#txtEndDate').val();
						}
					}
					
					var mydata={startdate:sdt,enddate:edt};	
																
					$.ajax({
						url: "<?php echo site_url('Logreport/GetAuditTrail'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'json',
						beforeSend: function(){
							//if (table) table.destroy();
						},
						complete: function(xhr, textStatus) {					
							$.unblockUI();
							
							$('#idData').removeClass('active');
							$('#idReport').addClass('active');					
							$('#idReport').trigger('click');
						},
						success: function(dataSet,status,xhr) {
							if (table) table.destroy();
								
								table = $('#recorddisplay').DataTable( {
									dom: 'B<"top"if>rt<"bottom"lp><"clear">',
									//dom: 'Bfrtip',
									language: {zeroRecords: "No Record Found"},
									buttons: [									
										$.extend( true, {}, buttonCommon, {
											extend: 'pdf',
											pageSize: 'A4',//LEGAL
											orientation: 'landscape',
											title: '',
											download: 'open',
											exportOptions: {
												columns: [ 0, 1, 2, 3, 4, 5, 6 ]
											},
											message: msg,
											customize: function ( doc ) {
												doc.content[1].table.widths = [ '15%', '10%', '10%', '10%', '35%', '10%', '10%' ],
												
												
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
											orientation: 'landscape',
											exportOptions: {
												columns: [ 0, 1, 2, 3, 4, 5, 6 ]
											}
										} ),
									],
									
									columnDefs: [
										{
											"targets": [ 0,1,2,3,4,5, 6 ],
											"visible": true,
											"searchable": true
										},
										{
											"searchable": false,
											"orderable": false,
											"targets": 0
										},
										{
											"orderable": true,
											"targets": [ 1,2,3,4,5,6 ]
										},
										{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6 ] }
									],					
									order: [[ 0, 'asc' ]],
									data: dataSet, //ActionDate,Username,Fullname,Operation,Activity,remote_ip,remote_host
									columns: [
										{ width: "5%" },//Action Date
										{ width: "5%" },//Username
										{ width: "10%" },//Fullname
										{ width: "10%" },//Operation
										{ width: "55%" },//Activity
										{ width: "5%" },//Remote IP
										{ width: "10%" }//Remote Host
									],
								} );
								

							
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
					m='DisplayTransaction Module Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}//End DisplayTransaction
			
			function GetAuditTrail()
			{
				try
				{
					//var randomnumber = Math.floor(Math.random() * 100); 
					
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					
					DisplayTransaction(sdt,edt)
				}catch(e)
				{

				}
			}
			
			
			function Validate()
			{
				try
				{
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					var p=$.trim($('#txtStartate').val());
					var d=$.trim($('#txtEndDate').val());
										
					//Start date Not Select. End Date Selected
					if (!p)
					{
						m='You have not selected the report start date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback: function() {
								$('#txtStartate').focus();
							  }
						});
						
						$('#txtStartate').focus(); return false; 
					}
					
					if (!d)
					{
						m='You have not selected the report end date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback: function() {
								$('#txtStartate').focus();
							  }
						});
						
						$('#txtEndDate').focus(); return false; 
					}
					
					if (!p && d)
					{
						m='You have selected the report end date. Report start date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback: function() {
								$('#txtStartate').focus();
							  }
						});
						
						$('#txtStartate').focus(); return false; 
					}
					
					//End date Not Select. Start Date Selected
					if (p && !d)
					{
						m='You have selected the report start date. Report end date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback: function() {
								$('#txtStartate').focus();
							  }
						});
						
						$('#txtEndDate').focus(); return false; 
					}
					
					if (p)
					{
						if (!pdt.isValid())
						{
							m="Report Start Date Is Not Valid. Please Select A Valid Report Start Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } }
							});
							
							
							$('#txtStartate').focus(); return false;
						}	
					}
					
					if (d)
					{
						if (!ddt.isValid())
						{
							m="Report End Date Is Not Valid. Please Select A Valid Report End Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } }
							});
														
							$('#txtEndDate').focus(); return false;
						}	
					}
					
					
					if (p && d)
					{
						var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
						var diff = moment.duration(ddt.diff(pdt));
						var mins = parseInt(diff.asMinutes());
						
						if (dys<0)
						{
							m="Report End Date Is Before The Start Date. Please Correct Your Entries!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } }
							});
							
							$('#txtEndDate').focus(); return false;
						}else
						{
							if (dys==0)
							{
								if (mins<0)
								{
									m="Report End Date Is Before Report Start Date. Please Correct Your Entries!";
									bootstrap_alert.warning(m);
									bootbox.alert({ 
										size: 'small', message: m, title:Title,
										buttons: { ok: { label: "Close!", className: "btn-danger" } }
									});
									
									$('#txtEndDate').focus(); return false;
								}
							}
						}	
					}
												
					return true;
				}catch(e)
				{
					$.unblockUI();
					m='VALIDATE ERROR:\n'+e;
							
					bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
					
					return false;
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
              	<div class="panel panel-info">
                	<div class="panel-heading size-20"><i class="fa fa-table"></i> Audit Trail Report</div>
                	
                    <div class="panel-body"> 
                    	<!--Tab-->
                        <ul class="nav nav-tabs " style="font-weight:bold;">
                          <li  role="presentation" class="active"><a id="idData" data-toggle="tab" href="#tabData"><i class="glyphicon glyphicon-list-alt"></i> Report Parameters</a></li>
                          <li role="presentation"><a id="idReport" data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Report</a></li>
                        </ul>
                        <!--Tab Ends-->
                        
                        <div class="tab-content">
                        	<div id="tabData" class="row tab-pane fade in active ">
                    			
                     		<br>
                            <form class="form-horizontal"> 
                  			   <div class="form-group">
                                    <!--Report Start Date-->
                                    <label class="col-sm-4 control-label left" for="txtStartate" title="Report Start Date">Report&nbsp;Start&nbsp;Date</label>
                                    
                                    <div class="col-sm-3" title="Report Start Date">
                                      <input readonly id="txtStartate" name="txtStartate" type="text" class="form-control" placeholder="Report Start Date">
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>                      
                     			</div>
                                
                                <div class="form-group">
                                   <!--Report End Date-->
                                    <label title="Report End Date" class="col-sm-4 control-label left" for="txtEndDate">Report&nbsp;End&nbsp;Date</label>
                                    
                                    <div class="col-sm-3" title="Report End Date">
                                      <input readonly id="txtEndDate" name="txtEndDate" type="text" class="form-control padright" placeholder="Report End Date">
                                      
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>                        
                     			</div>
                      		
                           
                   </form>
                        </div><!--End Of Tab Content 1-->
                
                		<div id="tabReport" class="tab-pane fade">
                 		   <center>
                        	<div class="table-responsive" style="margin-top:20px; ">
                      		  <table align="center" id="recorddisplay" cellspacing="0" title="Audit Trail Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>
                                    <th>Date</th><!--Actiondate-->
                                    <th>Username</th>
                                    <th>Fullname</th>                                   
                                    <th>Operation</th> 
                                    <th>Activity</th>                               
                                    <th>Remote&nbsp;IP</th>  
                                    <th>Computer</th>
                                </tr>
                              </thead>

                          </table>
                    		</div>
                       		</center>
                		</div><!--Table Tab Content-->
                     </div><!--"tab-content"-->
                        
                    <div align="center">
                        <div id = "divAlert"></div>
                    </div> 
                    
                    
                     <div align="center" style="margin-left:-90px;">
                     <!--Buttons-->
                     <br>
                        <button style="width:120px;" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display Report</button>
                        
                        <button style="width:120px; margin-left:15px;" id="btnRefreshSubscription" type="button" class="btn btn-danger" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Reset</button>
                     </div>
                    </div><!--"panel-body"-->
                
                </div><!--"panel panel-info"-->
                
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
