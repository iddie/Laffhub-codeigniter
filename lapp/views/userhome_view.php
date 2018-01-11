<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Africa/Lagos');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>    

    
    <script>
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var default_network='<?php echo $default_network; ?>';
		
		var Username='<?php echo $username; ?>';
		var table;
		var Title='<font color="#AF4442">Dashboard Help</font>';
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
							return column === 1 ?
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
			
			if (RefreshDuration) RefreshDuration=RefreshDuration*1000;
			
			LoadYears();
			LoadMonths();
			LoadNetwork();
			GetTransactions();
			//ShowTransactions();
			LoadCharts();
			//ShowChart();
			
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
						m="Start Date And Time Is Not Valid. Please Select A Valid Start Date And Time";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } }
						});
					}
					
					if (!ddt.isValid())
					{
						m="End Date And Time Is Not Valid. Please Select A Valid End Date And Time";
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
						
						m="Transaction End Date Is Before Transaction Start Date. Please Correct Your Entries!";
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
						
								m="Transaction End Date Is Before Transaction Start Date. Please Correct Your Entries!";
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
			
			function LoadMonths()
			{
				try
				{
					var cmn='<?php echo date('F');  ?>';
					
					$('#cboMonth').empty();
					$('#cboMonth').append( new Option('[SELECT]','') );
					$('#cboMonth').append( new Option('January','January') );
					$('#cboMonth').append( new Option('February','February') );
					$('#cboMonth').append( new Option('March','March') );
					$('#cboMonth').append( new Option('April','April') );
					$('#cboMonth').append( new Option('May','May') );
					$('#cboMonth').append( new Option('June','June') );
					$('#cboMonth').append( new Option('July','July') );
					$('#cboMonth').append( new Option('August','August') );
					$('#cboMonth').append( new Option('September','September') );
					$('#cboMonth').append( new Option('October','October') );
					$('#cboMonth').append( new Option('November','November') );
					$('#cboMonth').append( new Option('December','December') );
					
					$('#cboMonth').val(cmn);
				}catch(e)
				{
					$.unblockUI();
					m='LoadMonths Module ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			function LoadYears()
			{
				try
				{
					$('#cboYear').empty();
					
					var cyr='<?php echo date('Y'); ?>';
					
					$('#cboYear').append( new Option('[SELECT]','') );
					
					for(var i=cyr; i>=2016; i--)
					{
						$('#cboYear').append( new Option(i,i) );
					}
					
					$('#cboYear').val(cyr);
				}catch(e)
				{
					$.unblockUI();
					m='LoadYears Module ERROR:\n'+e;
					
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
					if (!Validate()) return false;
					
					var yr=$('#cboYear').val();
					var mn=$('#cboMonth').val();
					var nt=$('#cboNetwork').val();
					var cat=$.trim($('#cboCategory').val());
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
		
					DisplayTransaction(yr,mn,nt,cat,sdt,edt);
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
			
			function DisplayTransaction(yr,mn,nt,cat,sdt,edt)
			{
				try
				{
					$.blockUI({message:'<img src="<?php echo base_url();?>images/loader.gif" /><p>Retrieving Transactions. Please Wait...</p>',theme: true,baseZ: 2000});
			
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					//var pdt = moment(startdt), ddt = moment(enddt);
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid()) sdt='';
					if (!ddt.isValid()) edt='';
					
					//Make Ajax Request
					var msg;
										
					if (mn) msg=mn+' '+yr +' Transaction History'; else msg=yr +' Transaction History';
					
					if (cat)
					{
						msg = msg + ' For Video Category '+cat;
					}else
					{
						if (nt) msg = msg + ' For '+nt ;
					}
					
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
					
					var mydata={year:yr, month:mn, network:nt, category:cat,startdate:sdt,enddate:edt};	
																
					$.ajax({
						url: "<?php echo site_url('Userhome/GetTransactions'); ?>",
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
									lengthMenu: [ 5, 10, 20, 25, 50, 75, 100 ],
									language: {zeroRecords: "No Transaction Record Found"},
									autoWidth:false,
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
												doc.content[1].table.widths = [ '5%', '15%', '10%', '10%', '25%', '25%', '10%' ],
												
												
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
											"targets": [ 7,8 ],
											"visible": false,
											"searchable": false
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
									order: [[ 1, 'desc' ]],
									data: dataSet,
									columns: [
										{ width: "8%" },//SN
										{ width: "10%" },//Trans. Date
										{ width: "10%" },//Phone
										{ width: "10%" },//Category
										{ width: "25%" },//Video Title
										{ width: "29%" },//User Agent
										{ width: "8%" },//Network
										{ width: "0%" },//Video ID
										{ width: "0%" }//Category
									],
								} );
								
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
					m='DisplayTransaction Module Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}//End DisplayTransaction
			
			function GetTransactions()
			{
				try
				{
					//var randomnumber = Math.floor(Math.random() * 100); 
					var yr=$('#cboYear').val();
					var mn=$('#cboMonth').val();
					var nt=$('#cboNetwork').val();
					var cat=$.trim($('#cboCategory').val());
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					
					DisplayTransaction(yr,mn,nt,cat,sdt,edt)
				}catch(e)
				{

				}
			}
			
						
			function ShowTransactions() {
                setInterval(function() {
                    GetTransactions();
                }, RefreshDuration);
            }
			
			var options = {
	            chart: {
	                renderTo: 'ChartContainer',
	                type: 'column',
	                marginRight: 130,
	                marginBottom: 25
	            },
	            title: {
	                text: 'Transaction Summary For <?php echo date('Y'); ?>',
	                x: -20 //center
	            },
	            subtitle: {
	                text: '',
	                x: -20
	            },
	            xAxis: {
	                categories: []
	            },
	            yAxis: {
					allowDecimals: false,
	                title: {
	                    text: 'Number Of Transactions/Month'
	                },
	                plotLines: [{
	                    value: 0,
	                    width: 1,
	                    color: '#808080'
	                }]
	            },
	            tooltip: {
	                formatter: function() {
	                        return '<b>'+ this.series.name +'</b><br/>'+
	                        this.x +': '+ this.y+'</font>';
	                }
	            },
	            legend: {
	                layout: 'vertical',
	                align: 'right',
	                verticalAlign: 'top',
	                x: -10,
	                y: 100,
	                borderWidth: 0,
					labelFormatter: function() {
						var total = 0;
						for(var i=this.yData.length; i--;) { total += this.yData[i]; };
						return this.name + ' - ' + total;
					}
	            },
				credits: {
					enabled: false
				 },
	            
	            series: []
	        }
			
			function ShowChart() {
                setInterval(function() {
                    LoadCharts();
                }, RefreshDuration);
            }
	        
			function LoadCharts()
			{
				try
				{
					 $.getJSON("Userhome/data", function(json) 
					{
						options.xAxis.categories = json[0]['data'];
						options.series[0] = json[1];
						options.series[1] = json[2];
						options.series[2] = json[3];
						options.series[3] = json[4];
						
						chart = new Highcharts.Chart(options);
					});		
				}catch(e)
				{
					
				}
			}
	       
			
			function Validate()
			{
				try
				{
					var yr=$.trim($('#cboYear').val());
					
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					var p=$.trim($('#txtStartate').val());
					var d=$.trim($('#txtEndDate').val());
					
					if (!yr) 
					{
						m='Please select transaction year.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboYear').focus(); return false;
					}
					
					//Start date Not Select. End Date Selected
					if (!p && d)
					{
						m='You have selected the transaction end date. Transaction start date field must also be selected.';
						
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
						m='You have selected the transaction start date. Transaction end date field must also be selected.';
						
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
							m="Transaction Start Date Is Not Valid. Please Select A Valid Transaction Start Date";
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
							m="Transaction End Date Is Not Valid. Please Select A Valid Transaction End Date";
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
							m="Transaction End Date Is Before The Start Date. Please Correct Your Entries!";
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
									m="Transaction End Date Is Before Transaction Start Date. Please Correct Your Entries!";
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
					m='VALIDATE YEAR ERROR:\n'+e;
							
					bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
					
					return false;
				}
			}
			
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
              <div class="box box-success expanded-box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-dashboard"></i> Dashboard</h3>
    
                  <div class="box-tools pull-right" title="Click to collapse or expand">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                
                
                 <div class="box-body">
                 	<!--Year-->
                 	<div class="form-group">
                       
                         <label  class="col-sm-1 control-label" for="cboYear" title="Select Transaction Year">Year<span class="redtext">*</span></label>
                        
                        <div class="col-sm-2" title="Select Transaction Year">                           
                          <select id="cboYear" class="form-control"></select>
                        </div>
                        
                        <!--Month-->
                        <label  class="col-sm-1 control-label" for="cboMonth" title="Select Transaction Month">Month</label>
                        
                        <div class="col-sm-2" title="Select Transaction Month">
                          <select id="cboMonth" class="form-control"></select>
                        </div>
                        
                       <!--Network-->
                        <label class="col-sm-1 control-label" for="cboNetwork" title="Select Network">Network</label>
                        
                        <div class="col-sm-2" title="Select Network">
                          <select id="cboNetwork" class="form-control"></select>
                        </div>
                        
                         <!--Category-->
                        <label class="col-sm-1 control-label" for="cboCategory" title="Video Categories">Category</label>
                        
                        <div class="col-sm-2" title="Enter Subscriber Phone Number">
                          <select id="cboCategory" class="form-control">
                          	 <?php
								if (count($CategoryData)>0) echo '<option value="">[SELECT]</option>';
								
								foreach($CategoryData as $row):
									if ($row->category)
									{
										echo '<option value="'.$row->category.'">'.$row->category.'</option>';
									}
								endforeach;
							?> 
                          </select>
                        </div>
                        
                      
                   </div>
                     
                     <div class="form-group">
                        <!--Start Date-->
                        <label class="col-sm-1 control-label left" for="txtStartate" title="Transaction Start Date">Start&nbsp;Date</label>
                        
                        <div class="col-sm-2" title="Transaction Start Date">
                          <input readonly id="txtStartate" name="txtStartate" type="text" class="form-control" placeholder="Start Date">
                          <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                        </div>
                        
                        <!--Transaction End Date-->
                        <label title="Transaction End Date" class="col-sm-1 control-label left" for="txtEndDate">End&nbsp;Date</label>
                        
                        <div class="col-sm-2" title="Transaction End Date">
                          <input readonly id="txtEndDate" name="txtEndDate" type="text" class="form-control padright" placeholder="End Date">
                          
                          <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                        </div>
                        
                         <span style="float:right;">
                         <!--Buttons-->
                            <button style="width:120px;" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display</button>
                            
                         	<button style="width:120px; margin-left:15px;" id="btnRefreshSubscription" type="button" class="btn btn-danger" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Reset</button>
                         </span>
                        
                      </div>
                 </div>
                 	
                    
                    <div align="center">
                        <div id = "divAlert"></div>
                    </div>
                    
                    <table id="recorddisplay" border="1" cellspacing="0" class="display table table-bordered table-hover table-striped" width="99.5%">
                        
                        <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                              <td><b>SN</b></td>
                              <td><b>Trans.&nbsp;Date</b></td>
                              <td><b>Phone</b></td>
                              <td><b>Video&nbsp;Category</b></td>
                              <td><b>Video&nbsp;Title</b></td>
                              <td><b>User&nbsp;Agent</b></td>
                              <td><b>Network</b></td>
                              <td class="hide"><b>Video ID</b></td>
                              <td class="hide"><b>Category</b></td>
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
        <strong>Copyright &copy; <?php echo date('Y');?> <a style="color:#DA7659;" href="http://www.laffhub.com" target="_blank">LaffHub</a>.</strong> All rights reserved.
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
