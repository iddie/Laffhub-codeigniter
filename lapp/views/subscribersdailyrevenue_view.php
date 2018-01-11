<?php #session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>LaffHub | Subscribers Daily Revenue Report</title>
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

    <!-- Tell the browser to be responsive to screen width -->   
    
<?php include('homelink.php'); ?>    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="<?php echo base_url();?>js/html5shiv.min.js"></script>
        <script src="<?php echo base_url();?>js/respond.min.js"></script>
    <![endif]-->
	
    <script type="text/javascript">	
		var seldata
		var table;
						
		var table;
		var Title='<font color="#AF4442">Daily Revenue Report Help</font>';
		var m='';
		
		bootstrap_alert = function() {}
		bootstrap_alert.warning = function(message) 
		{
		   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
		
		$(document).ready(function(e)  
		{
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
			
			$(document).ajaxStop($.unblockUI);
					
			LoadPlans();
			
			function LoadPlans()
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plans. Please Wait...</b></p>',theme: true,baseZ: 2000});
					
					$('#cboPlan').empty();
					
					$.ajax({
						url: "<?php echo site_url('Prices/LoadPlans');?>",
						type: 'POST',
						data:{network:'Airtel'},
						dataType: 'json',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($(data).length > 0)
							{
								$('#cboPlan').append( new Option('[ALL]','') );
								
								$.each($(data), function(i,e)
								{
									if ((e.plan) && ($.trim(e.plan).toLowerCase() != 'free trial')) $('#cboPlan').append( new Option($.trim(e.plan),$.trim(e.plan)) );
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
					m='LoadPlans Module ERROR:\n'+e;
					
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
						
			$('#txtDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 3,
				forceParse: 0,
				format: 'dd M yyyy'
			});
			
			$('#txtDate').change(function(e) {
				try
				{
					if ($('#txtDate').val())
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
			
			
			function VerifyStartAndEndDates()
			{
				try
				{
					$('#divAlert').html('');
					
					var rdt=$.trim($('#txtDate').val());
															
					if (!rdt)
					{
						m="Please Select A Report Date";
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
						
						$('#txtDate').focus();
					}
					
					var startdt=ChangeDateFrom_dMY_To_Ymd(rdt,'-',' ');
					var tdt='<?php echo date('Y-m-d'); ?>';
															
					//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
					
					var aft=moment(startdt).isSameOrAfter(tdt);
															
					if (aft==true)
					{
						$('#txtDate').val('');
						
						m="Report Date Cannot Exceed Yesterday's Date. Please Correct Your Entry!";
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
						
						$('#txtDate').focus();
					}
				}catch(e)
				{
					$.unblockUI();
					m="VerifyStartAndEndDates ERROR:\n"+e;
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
			}
			
			$('#btnDisplay').click(function(e) 
			{
				try
				{
					$('#divAlert').html('');
					
					if (!Validate()) return false;
					
					var nt=$('#txtNetwork').val();
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtDate').val(),'-',' ');
					var pl=$.trim($('#cboPlan').val());
												
					DisplayReport(sdt,pl,nt);
				}catch(e)
				{
					$.unblockUI();
					m='Display Report Button Click ERROR:\n'+e;
					
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
			
			$('#btnExcel').click(function(e) 
			{
                try
				{
					$('#divAlert').html('');
					
					if (!Validate()) return false;
					
					var nt=$('#txtNetwork').val();
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtDate').val(),'-',' ');
					var pl=$.trim($('#cboPlan').val());
										
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Generating Report. Please Wait...</p>',theme: true,baseZ: 2000});
												
					//Make Ajax Request	
					var msg = 'Airtel Subscribers Daily Revenue Report For '+ $('#txtDate').val();
										
					var mydata={reportdate:sdt,plan:pl,network:nt,title:msg};
											
					$.ajax({
						url: '<?php echo site_url('Subscribersdailyrevenue/PrintReport'); ?>',
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							//$.unblockUI();
						},
						success: function(data,status,xhr) {				
							//Clear boxes
							$.unblockUI();
							
							var d=$.trim(data);
							
							if (d.toLowerCase()==('airtelsubscribersdailyrevenuereport.xls'))
							{
								window.open('<?php echo base_url();?>reports/airtelsubscribersdailyrevenuereport.xls')
							}else
							{
								m=d;
								
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
						},
						error:  function(xhr,status,error) {
							$.unblockUI();
							m='Error '+ xhr.status + ' Occurred: ' + error;
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
						}
					});	
				}catch(e)
				{
					$.unblockUI();
					m='Print Button Click ERROR:\n'+e;
							
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
			
			$('#btnExportExcel').click(function(e) {
                try
				{
					$('#btnExcel').trigger('click');
				}catch(e)
				{
					$.unblockUI();
					m='Top Print Button Click ERROR:\n'+e;
					
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
			
			$('#btnPrintTop').click(function(e) {
                try
				{
					$('#btnExcel').trigger('click');
				}catch(e)
				{
					$.unblockUI();
					m='Top Print Button Click ERROR:\n'+e;
					
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
			
			function DisplayReport(sdt,plan,network)
			{
				try
				{
					$.blockUI({message:'<img src="<?php echo base_url();?>images/loader.gif" /><p>Retrieving Revenue Records. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var msg= 'Airtel Subscribers Daily Revenue Report For '+ $('#txtDate').val();

					var mydata={reportdate:sdt,plan:plan,network:network};										

					$.ajax({
						url: "<?php echo site_url('Subscribersdailyrevenue/GetReport'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {					
							$.unblockUI();
							
							activateTab('tabReport');
						},
						success: function(dataSet,status,xhr) {
							if (table) table.destroy();
								
								table = $('#recorddisplay').DataTable( {
									dom: '<"top"if>rt<"bottom"lp><"clear">',
									autoWidth:false,
									lengthMenu: [ [10,20,50,100,-1], [10,20,50,100,"All"] ],
									language: {zeroRecords: "No Airtel Subscribers Daily Revenue Record Found"},								
									columnDefs: [
										{
											"targets": [ 0,1,2,3 ],
											"visible": true
										},
										{
											"targets": [ 1,2,3 ],
											"searchable": true
										},
										{
											"searchable": false,
											"orderable": false,
											"targets": [ 0 ]
										},
										{
											"orderable": true,
											"targets": [ 1,2,3 ]
										},
										{ className: "dt-right", "targets": [ 2 ] },
										{ className: "dt-center", "targets": [ 0,1,3 ] },
										{ className: "td.class-name", "targets": [0,1,2,3]}
									],					
									//order: [[ 1, 'desc' ]],
									data: dataSet,
									columns: [
										{ width: "10%" },//SNO
										{ width: "30%" },//MSISDN
										{ width: "30%" },//PRICE
										{ width: "30%" }//TRANSACTION TIME
									],
								} );
								
								table.on( 'order.dt search.dt', function () {
									table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
										cell.innerHTML = i+1;
									} );
								} ).draw();	
								
								//Compute Total
								var Total=table.column(2).data().sum(); //Revenue
								$('#tdTotalPrice').html(number_format (Total, 2, '.', ','));
						},
						error:  function(xhr,status,error) 
						{
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
					
					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='DisplayReport Module Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}//End DisplayReport
						
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
			
			function Validate()
			{
				try
				{
					var rdt=$.trim($('#txtDate').val());
															
					if (!rdt)
					{
						m="Please Select A Report Date";
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
						
						$('#txtDate').focus();
					}
					
					var startdt=ChangeDateFrom_dMY_To_Ymd(rdt,'-',' ');
					var tdt='<?php echo date('Y-m-d'); ?>';
															
					//Start date Not Select. End Date Selected
					if (!rdt)
					{
						m='You have not selected the report date.';
						
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
						
						$('#txtDate').focus(); return false; 
					}
					
					
					var aft=moment(startdt).isSameOrAfter(tdt);
					
					if (aft==true)
					{
						m="Report Date Cannot Exceed Yesterday's Date. Please Correct Your Entry!";
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
						
						$('#txtDate').focus(); return false;
					}	
					
												
					return true;
				}catch(e)
				{
					$.unblockUI();
					m='VALIDATE ERROR:\n'+e;
							
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
		});//End Ready
		
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
         <h4>LaffHub</h4>
          
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
              <div class="panel-heading size-16">
              	<i class="fa fa-area-chart"></i> Subscribers Daily Revenue Report
                
                <span style="float:right;"><a href="<?php echo site_url('Home');?>"><i class="fa fa-home"></i> Home</a></span>
                </div>
            <div class="panel-body">
                <br>
                <form class="form-horizontal">
              	<!--Tab-->
                <ul class="nav nav-tabs " style="font-weight:bold;">
                	<li class="active"><a data-toggle="tab" href="#tabData"><i class="material-icons">select_all</i> Display Parameters</a></li>
                    
                	<li><a data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Revenue</a></li>
                </ul>
    			<!--Tab Ends-->
                
                <!--Tab Details-->
				<div class="tab-content">
                	<div id="tabData" class="row tab-pane fade in active ">	
                        <br>
                        <div class="col-sm-offset-3" style="padding-left:10px; margin-bottom:10px;"><i><u>Fields With <span class="redtext">*</span> Are Required!</u></i></div>
                        <br>
                         <form class="form-horizontal"> 
                  			   <div class="form-group">
                                    <!--Network-->
                                    <label class="col-sm-3 control-label left" for="txtNetwork" title="Network">Network</label>
                                    
                                    <div class="col-sm-3" title="Network">
                                      <input style="background-color:#ffffff; color:#EE151F;" readonly id="txtNetwork" type="text" class="form-control" placeholder="Report Date" value="Airtel">
                                    </div>                      
                     			</div>
                                
                                <div class="form-group">
                                    <!--Plan-->
                                    <label class="col-sm-3 control-label left" for="cboPlan" title="Plan">Plan<span class="redtext">*</span></label>
                                    
                                    <div class="col-sm-3" title="Plan">
                                      <select id="cboPlan" class="form-control"></select>
                                    </div>                      
                     			</div>
                                
                                <div class="form-group">
                                    <!--Report Start Date-->
                                    <label class="col-sm-3 control-label left" for="txtDate" title="Report Date">Report&nbsp;Date<span class="redtext">*</span></label>
                                    
                                    <div class="col-sm-3" title="Report Date">
                                      <input readonly id="txtDate" type="text" class="form-control" placeholder="Report Date" value="<?php echo date('d M Y',strtotime('-1 day')); ?>">
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>                      
                     			</div>
                      		
                           <div class="form-group" style="margin-top:30px;">
                            <div class="col-sm-8 col-sm-offset-3">
                             <button title="Display Revenue Report" style="width:140px;" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display Report</button>
                             
                             <button style="margin-left:15px; width:140px; padding:5px;" id="btnExportExcel" type="button" class="btn btn-success right"><i class="glyphicon glyphicon-export"></i> Export As Excel</button>
                             
                             <button style="width:140px; margin-left:15px;" id="btnRefreshSubscription" type="button" class="btn btn-danger" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Reset</button>
                            </div>                        
                        </div>
                   		</form>
                                           
                    </div><!--End Of tabData-->
                  
                    <!--Report Tab -->
                    <div id="tabReport" class="row tab-pane fade ">
                        <br> 
                         <div class="form-group" style="margin-bottom:15px;">
                            <div align="center">
                             <button style="width:140px; padding:5px;" id="btnPrintTop" type="button" class="btn btn-success right"><i class="glyphicon glyphicon-export"></i> Export As Excel</button>
                             
                             <button disabled style="margin-left:15px; width:140px; padding:5px;" id="btnPDFTop" type="button" class="btn btn-info right hide"><i class="glyphicon glyphicon-export"></i> Export As PDF</button>
                            </div>                        
                        </div>
                        
                         <center>
                            <div id="divReport" class="table-responsive" style="width:100%">
                            <table align="center" id="recorddisplay" cellspacing="0" title="Daily Revenue Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%">
                                <thead style="color:#ffffff; background-color:#FF0000; ">
                                    <tr>
                                        <td style="font-weight:bold;">S/N</td>
                                        <td style="font-weight:bold;">MSISDN</td>
                                        <td style="font-weight:bold;">Price&nbsp;(&#8358;)</td>
                                         <td style="font-weight:bold;">Transaction Time</td>
                                    </tr>
                                </thead>
                                
                                <tfoot style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>
                                    <td colspan="2" style="font-weight:bold; text-align:center; padding-right:8px;" valign="middle">TOTAL&nbsp;REVENUE&nbsp;(&#8358;)</td>
                                    <td id="tdTotalPrice" style="font-weight:bold; text-align:right; padding-right:8px;" valign="middle"></td>
                                    <td valign="middle"></td>
                                </tr>
                            </tfoot>
                              </table>
                            </div>
                       
                            <div class="form-group" style="margin-top:15px;">
                                <div align="center">
                                <button style="margin-left:15px; width:140px; padding:5px;" id="btnExcel" type="button" class="btn btn-success right"><i class="glyphicon glyphicon-export"></i> Export As Excel</button>
                                
                                <button disabled style="margin-left:15px; width:140px; padding:5px;" id="btnPDF" type="button" class="btn btn-info right hide"><i class="glyphicon glyphicon-export"></i> Export As PDF</button>
                                </div>
                            </div>
                           </center>
                    </div>
                  </div><!--End Of Tab Content 1-->
                    
                            
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
               </div>
                                   
                 
                </form>
            </div>
                           
              </div>
          </div>
              </div><!-- col-md-12 END -->
      		</div><!-- row END -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          
        </div>
        <strong>Copyright &copy; <?php echo date('Y');?> <a style="color:#DA7659;" href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
      </footer>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->
       
       
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