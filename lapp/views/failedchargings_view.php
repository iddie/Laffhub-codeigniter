<?php #session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>LaffHub | Failed Chargings Report</title>
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
		var Title='<font color="#AF4442">Failed Chargings Report Help</font>';
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
			
			LoadNetwork();
			
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
					
				}catch(e)
				{
					$.unblockUI();
					m='LoadNetwork Module ERROR:\n'+e;
					
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
			
			$('#txtStartDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 3,
				forceParse: 0,
				format: 'dd M yyyy'
			});
			
			$('#txtStartDate').change(function(e) {
				try
				{
					if ($('#txtStartDate').val() && $('#txtEndDate').val())
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
					if ($('#txtStartDate').val() && $('#txtEndDate').val())
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
					
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					//var pdt = moment(startdt), ddt = moment(enddt);
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid())
					{
						m="Report Start Date Is Not Valid. Please Select A Valid Report Start Date";
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
					
					if (!ddt.isValid())
					{
						m="Report End Date Is Not Valid. Please Select A Valid Report End Date";
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
										
					//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
					
					var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
					var diff = moment.duration(ddt.diff(pdt));
										
					if (dys<0)
					{
						$('#txtEndDate').val('');
						$('#txtDays').val('');
						
						m="Report End Date Is Before Report Start Date. Please Correct Your Entries!";
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
					
					var nt=$('#cboNetwork').val();
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
												
					DisplayReport(sdt,edt,nt);
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
					
					var nt=$('#cboNetwork').val();
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
										
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Generating Report. Please Wait...</p>',theme: true,baseZ: 2000});
												
					//Make Ajax Request	
					var msg;
										
					msg='Failed Chargings Report';					
					
					if (sdt && edt)
					{
						if (sdt == edt)
						{
							msg = msg + ' For '+ $('#txtStartDate').val();
						}else
						{
							msg = msg + ' From '+ $('#txtStartDate').val() + ' To ' + $('#txtEndDate').val();
						}
					}
				
					var mydata={startdate:sdt,enddate:edt,network:nt,title:msg};
						
					$.ajax({
						url: '<?php echo site_url('Failedchargings/PrintReport'); ?>',
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
							
							if (d==('failedchargingsreport.xls'))
							{
								window.open('<?php echo base_url();?>reports/failedchargingsreport.xls')
							}else
							{
								m=d;
								
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
						error:  function(xhr,status,error) 
						{
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
			
			function DisplayReport(sdt,edt,network)
			{
				try
				{
					$.blockUI({message:'<img src="<?php echo base_url();?>images/loader.gif" /><p>Retrieving Failed Chargings Records. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var msg;
										
					msg='Failed Chargings Report';					
					
					if (sdt && edt)
					{
						if (sdt == edt)
						{
							msg = msg + ' For '+ $('#txtStartDate').val();
						}else
						{
							msg = msg + ' From '+ $('#txtStartDate').val() + ' To ' + $('#txtEndDate').val();
						}
					}
				
					var mydata={startdate:sdt,enddate:edt,network:network};										

					$.ajax({
						url: "<?php echo site_url('Failedchargings/GetReport'); ?>",
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
									language: {zeroRecords: "No Failed Charging Record Found"},								
									columnDefs: [
										{
											"targets": [ 0,1,2,3,4 ],
											"visible": true,
											"searchable": false
										},
										{
											"orderable": true,
											"targets": [ 1 ]
										},
										{
											"orderable": false,
											"targets": [ 0,2,3,4 ]
										},
										{ className: "dt-center", "targets": [ 0,1,2,3,4 ] },
										{ className: "td.class-name", "targets": [0,1,2,3,4]}
									],					
									//order: [[ 2, 'desc' ]],
									data: dataSet,
									columns: [
										{ width: "10%" },//SNO
										{ width: "20%" },//Network
										{ width: "20%" },//Charging Date	
										{ width: "20%" },//Plan									
										{ width: "30%" },//No. Of Subscribers
									], 
								} );
								
								table.on( 'order.dt search.dt', function () {
									table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
										cell.innerHTML = i+1;
									} );
								} ).draw();
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
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
					var nt=$('#cboNetwork').val();
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					var p=$.trim($('#txtStartDate').val());
					var d=$.trim($('#txtEndDate').val());
										
					//Network
					if (!nt)
					{
						m='Please select a network.';
						
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
						
						$('#cboNetwork').focus(); return false; 
					}
					
					//Start date Not Select. End Date Selected
					if (!p)
					{
						m='You have not selected the report start date.';
						
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
						
						$('#txtStartDate').focus(); return false; 
					}
					
					if (!d)
					{
						m='You have not selected the report end date.';
						
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
						
						$('#txtEndDate').focus(); return false; 
					}
					
					if (!p && d)
					{
						m='You have selected the report end date. Report start date field must also be selected.';
						
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
						
						$('#txtStartDate').focus(); return false; 
					}
					
					//End date Not Select. Start Date Selected
					if (p && !d)
					{
						m='You have selected the report start date. Report end date field must also be selected.';
						
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
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
							
							
							$('#txtStartDate').focus(); return false;
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
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
														
							$('#txtEndDate').focus(); return false;
						}	
					}
					
					
					if (p && d)
					{
						var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
						var diff = moment.duration(ddt.diff(pdt));
						
						if (dys<0)
						{
							m="Report End Date Is Before The Start Date. Please Correct Your Entries!";
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
							
							$('#txtEndDate').focus(); return false;
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
              	<i class="fa fa-area-chart"></i> Failed Chargings Report
                
                <span style="float:right;"><a href="<?php echo site_url('Home');?>"><i class="fa fa-home"></i> Home</a></span>
                </div>
            <div class="panel-body">
                <br>
                <form class="form-horizontal">
              	<!--Tab-->
                <ul class="nav nav-tabs " style="font-weight:bold;">
                	<li class="active"><a data-toggle="tab" href="#tabData"><i class="material-icons">select_all</i> Display Parameters</a></li>
                    
                	<li><a data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Failed Chargings</a></li>
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
                                    <label class="col-sm-3 control-label left" for="cboNetwork" title="Network">Network<span class="redtext">*</span></label>
                                    
                                    <div class="col-sm-3" title="Network">
                                      <select id="cboNetwork" class="form-control"></select>
                                    </div>                      
                     			</div>
                                
                                <div class="form-group">
                                    <!--Report Start Date-->
                                    <label class="col-sm-3 control-label left" for="txtStartDate" title="Report Start Date">Report&nbsp;Start&nbsp;Date<span class="redtext">*</span></label>
                                    
                                    <div class="col-sm-3" title="Report Start Date">
                                      <input readonly id="txtStartDate" name="txtStartDate" type="text" class="form-control" placeholder="Report Start Date">
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>                      
                     			</div>
                                
                                <div class="form-group">
                                   <!--Report End Date-->
                                    <label title="Report End Date" class="col-sm-3 control-label left" for="txtEndDate">Report&nbsp;End&nbsp;Date<span class="redtext">*</span></label>
                                    
                                    <div class="col-sm-3" title="Report End Date">
                                      <input readonly id="txtEndDate" name="txtEndDate" type="text" class="form-control padright" placeholder="Report End Date">
                                      
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>                        
                     			</div>
                      		
                           <div class="form-group" style="margin-top:30px;">
                            <div class="col-sm-8 col-sm-offset-3">
                             <button title="Display Failed Chargings Report" style="width:140px;" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display Report</button>
                             
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
                            <table align="center" id="recorddisplay" cellspacing="0" title="Failed Chargings Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%">
                                <thead style="color:#ffffff; background-color:#FF0000; ">
                                    <tr>
                                        <td style="font-weight:bold;">SN</td>
                                        <td style="font-weight:bold;">Network</td>                                        <td style="font-weight:bold;">Charging Date</td>
                                        <td style="font-weight:bold;">Plan</td>
                                        <td style="font-weight:bold;">No.&nbsp;Of&nbsp;Subscribers</td>
                                    </tr>
                                </thead>
                              </table>
                            </div>
                       
                            <div class="form-group" style="margin-top:10px;">
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