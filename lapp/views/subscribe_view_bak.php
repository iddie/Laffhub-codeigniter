<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Service Subscription</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style2.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css"> 

<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.bootstrap.min.css">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.jqueryui.min.css">


 <link rel="stylesheet" href="<?php echo base_url();?>css/datepicker3.css">

<link rel="stylesheet" href="<?php echo base_url();?>iconfont/material-icons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>

<script src="<?php echo base_url();?>js/holder.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/s8.min.js"></script>


<script>
	var SubscriberEmail="<?php echo $subscriber_email; ?>";
	var SubscriptionDate="<?php echo $subscribe_date; ?>";
	var ExpiryDate="<?php echo $exp_date; ?>";
	var SubscriptionStatus='<?php echo $subscriptionstatus; ?>';
	var Network='<?php echo $Network; ?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	var SubscriptionId='<?php echo strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10)); ?>';
	
	var Title='<font color="#AF4442">Service Subscription Help</font>';
	var m='';
	
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
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);
		
		$('#lblSubscriptionId').html(SubscriptionId);
		
		$('#txtStartDate').datepicker({
				weekStart: 1,
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
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
			}
		});
		
		$('#txtEndDate').datepicker({
			weekStart: 1,
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
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
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
					m="Subscription Start Date Is Not Valid. Please Select A Valid Subscription Start Date";
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
				
				if (!ddt.isValid())
				{
					m="Subscription End Date Is Not Valid. Please Select A Valid Subscription End Date";
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
									
				//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
				
				var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
				var diff = moment.duration(ddt.diff(pdt));
								
				if (dys<0)
				{
					$('#txtEndDate').val('');
					
					m="Subscription End Date Is Before Subscription Start Date. Please Correct Your Entries!";
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
			}catch(e)
			{
				$.unblockUI();
				m="VerifyStartAndEndDates ERROR:\n"+e;
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
		
		LoadPlans(Network);
			
		function LoadPlans(network)
		{
			try
			{
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plans. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				$('#cboPlan').empty();
				
				$.ajax({
					url: "<?php echo site_url('Prices/LoadPlans');?>",
					type: 'POST',
					data:{network:network},
					dataType: 'json',
					complete: function(xhr, textStatus) {
						//$.unblockUI;
					},
					success: function(data,status,xhr) {	
						$.unblockUI();
						
						if ($(data).length > 0)
						{
							$('#cboPlan').append( new Option('[SELECT]','') );
							
							$.each($(data), function(i,e)
							{
								if (e.plan) $('#cboPlan').append( new Option($.trim(e.plan),$.trim(e.plan)) );
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
		
		$('#cboPlan').change(function(e) {
			try
			{
				$('#lblDuration').html('');
				$('#lblVideoCount').html('');					
				$('#lblAmount').html('');
				$('#lblSubscriptionDate').html('');					
				$('#lblExpiryDate').html('');
								
				document.getElementById('btnSubscribe').disabled=true;
								
				var nt=$('#lblNetwork').html();					
				var pl=$(this).val();
				
				if (nt && pl) LoadPlanDetails(nt,pl);
			}catch(e)
			{
				$.unblockUI();
				m="Service Plan Changed Changed ERROR:\n"+e;
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
		
		function LoadPlanDetails(network,plan)
		{
			try
			{
				$('#lblDuration').html('');
				$('#lblVideoCount').html('');					
				$('#lblAmount').html('');
				$('#lblSubscriptionDate').html('');					
				$('#lblExpiryDate').html('');
				
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plan Details. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				var mydata={network:network,plan:plan};
				
				$.ajax({
					url: '<?php echo site_url('Subscribe/LoadPlanDetails'); ?>',
					type: 'POST',
					data:{network:network,plan:plan},
					dataType: 'json',
					complete: function(xhr, textStatus) {
						//$.unblockUI;
					},
					success: function(data,status,xhr) {	
						$.unblockUI();
						
						if ($(data).length > 0)
						{
							
							$.each($(data), function(i,e)
							{
								if (e.amount) $('#lblAmount').html($.trim(e.amount));
								if (e.duration)
								{
									$('#lblDuration').html($.trim(e.duration));
									
									var subdate='<?php echo date('d M Y'); ?>';
									var sdt=ChangeDateFrom_dMY_To_Ymd(subdate,'-',' ');
									var expdate=moment(sdt.replace(new RegExp('-', 'g'), '/')).add(parseInt(e.duration,10), 'days').format("DD MMM YYYY");
									
									$('#lblSubscriptionDate').html(subdate);
									$('#lblExpiryDate').html(expdate);
								}
								
								if (e.no_of_videos) $('#lblVideoCount').html($.trim(e.no_of_videos));
								
								
								
								//var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
								
								return false;
							});
							
							document.getElementById('btnSubscribe').disabled=false;
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
			
				$.unblockUI();
			}catch(e)
			{
				$.unblockUI();
				m="LoadMessages Module ERROR:\n"+e;
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
		
		$('#btnDisplay').click(function(e) {
			try
			{
				if (!checkForm()) return false;
				
				PayStackPayment();								
			}catch(e)
			{
				$.unblockUI();
				var m='Subscribe User Button Click ERROR:\n'+e;
			   
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
		});//btnDisplay.click
		
		function DisplayHistory(sdt,edt)
		{
			try
			{
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Retrieving Subscription History. Please Wait...</b></p>',theme: true,baseZ: 2000});
		
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
								autoWidth:false,
								destroy:true,
								lengthMenu: [ [ 10, 25, 50, 100,-1 ],[ '10', '25', '50', '100', 'All' ] ],
								language: {zeroRecords: "No Subscription History Record Found"},
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
				m='DisplayHistory Module Button Click ERROR:\n'+e;
				
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});
			}
		}//End DisplayHistory
					
			
		// Add event listener for opening and closing details
		$('#recorddisplay tbody').on('click', 'td', function () {
			var tr = $(this).closest('tr');
			var row = table.row( tr );
			editdata = row.data();
			
			var colIndex = $(this).index();
			
			if (colIndex==0) SelectRow(editdata);
		} );
				
		$('#btnSubscribe').click(function(e) {
			try
			{
				if (!checkForm()) return false;
				
				PayStackPayment();								
			}catch(e)
			{
				$.unblockUI();
				var m='Subscribe User Button Click ERROR:\n'+e;
			   
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
		});//btnSubscribe.click
    });
	
	function checkForm()
	{
		try
		 {
			var nt=$('#lblNetwork').html();
			var ph=$('#lblPhone').html();
			var em=$('#lblEmail').html();
			var du=$('#lblDuration').html();
			var sid=$('#lblSubscriptionId').html();
			var pl=$('#cboPlan').val();
			var vid=$('#lblVideoCount').html();			
			var amt=$('#lblAmount').html().replace(new RegExp(',', 'g'), '');
			
			var startdt=ChangeDateFrom_dMY_To_Ymd($('#lblSubscriptionDate').html(),'-',' ');
			var enddt=ChangeDateFrom_dMY_To_Ymd($('#lblExpiryDate').html(),'-',' ');
			var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
			var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
			
			var s=$.trim($('#lblSubscriptionDate').html());
			var e=$.trim($('#lblExpiryDate').html());
			 
			//Network
			if (!nt)
			{
				m='Network has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				activateTab('tabData'); return false;
			}
			
			//Phone And Email
			if (!ph && !em)
			{
				m='Subscriber phone and email have not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				activateTab('tabData'); return false;
			}
			
			//Subscription ID
			if (!sid)
			{
				m='Subscriber ID has not been displayed. Please click on <b>Refresh</b> button to reload the subscription page. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				activateTab('tabData'); return false;
			}
			
			//Plan
			if ($('#cboPlan > option').length < 2)
			{
				m='No service plan record was captured. Please contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				 activateTab('tabData'); return false;
			}
			
			if (!pl)
			{
				m="Please select a service plan.";
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
				
				$('#cboPlan').focus();  activateTab('tabData'); return false;
			}				
			
			//Duration
			if (!du)
			{
				m='Service plan duration has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				$('#cboPlan').focus(); activateTab('tabData'); return false;
			}
			
			//No Of Videos
			if (!vid)
			{
				m='Number of videos allowed for the service plan has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				$('#cboPlan').focus(); activateTab('tabData'); return false;
			}
			
			//Amount
			if (!amt)
			{
				m='Service plan amount has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				$('#cboPlan').focus(); activateTab('tabData'); return false;
			}
			
			//Subscription Date
			if (!s)
			{
				m='Subscription date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				$('#cboPlan').focus(); activateTab('tabData'); return false;
			}
			
			//Expiry Date
			if (!e)
			{
				m='Subscription expiry date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
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
				
				$('#cboPlan').focus(); activateTab('tabData'); return false;
			}
				
			if (!confirm('Are you sure you want to subscribe to '+nt.toUpperCase()+' '+pl.toUpperCase()+' plan? (Click "OK" to proceed or "CANCEL") to abort)?'))
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
		 
	function SelectRow(dat)
	{
			if (dat)
			{
				var cm=dat[1],dt=dat[2],pix=dat[7],sta=dat[4],id=dat[5],status=dat[6];
					
				$('#lblNetwork').val(cm);
				
				$('#lblDuration').html('');
				$('#lblVideoCount').html('');					
				$('#lblAmount').html('');
				$('#lblSubscriptionDate').html('');					
				$('#lblExpiryDate').html('');
							
				document.getElementById('btnSubscribe').disabled=false;
				
				activateTab('tabData');
			}else
			{
				ResetControls();
			}
}

	function ResetControls()
	{
		try
		{
			$('#cboPlan').val('');			
			$('#lblDuration').html('');
			$('#lblVideoCount').html('');					
			$('#lblAmount').html('');
			$('#cboAutoBilling').val('1');	
			$('#lblSubscriptionDate').html('');					
			$('#lblExpiryDate').html('');	
			
			SubscriptionId='<?php echo strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10)); ?>';
			$('#lblSubscriptionId').html(SubscriptionId);	
			
			if (document.getElementById('btnSubscribe')) document.getElementById('btnSubscribe').disabled=true;
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
	
	function PayStackPayment()
	{
		try
		{
			var ph=$('#lblPhone').html();
			var nt=$('#lblNetwork').html();
			var em=$('#lblEmail').html();
			var du=$('#lblDuration').html();
			var pl=$('#cboPlan').val();
			var au=$('#cboAutoBilling').val();
			var vid=$('#lblVideoCount').html()
			var amt=$('#lblAmount').html().replace(new RegExp(',', 'g'), '');				
			var sdt=ChangeDateFrom_dMY_To_Ymd($('#lblSubscriptionDate').html(),'-',' ');
			var edt=ChangeDateFrom_dMY_To_Ymd($('#lblExpiryDate').html(),'-',' ');
			var sid=$('#lblSubscriptionId').html();
				
			var desc=pl+' Plan Subscription.';			
									
			var	PayMethod='PayStack';
			var TransAmt=parseFloat(amt) * 100;
			var payment_currency='<?php echo trim($payment_currency); ?>';							
	
			var mydata;
									
			mydata={amount:TransAmt, currency:payment_currency, email:em, phone:ph, gateway:PayMethod,description:desc, subscribe_date:sdt, exp_date:edt, videos_cnt_to_watch:vid, subscriptionId:sid, network:nt, plan:pl, duration:du};
			
			//Log Transaction Here
			$.ajax({
				url: '<?php echo site_url('Subscribe/LogTrans'); ?>',
				data: mydata,
				type: 'POST',
				dataType: 'text',
				success: function(data,status,xhr) {				
					$.unblockUI();
					
					var ret='';
					ret=$.trim(data);
											
					if (ret.toUpperCase()=='OK')
					{
						var handler = PaystackPop.setup(
						{
							key: '<?php echo $PublicKey; ?>',
							email: em,//merchant email is em
							amount: TransAmt,
							ref: sid,
							metadata: {
								custom_fields: [
									{
										display_name: 'Subscriber Email',
										variable_name: 'subscriber_email',
										value: em  
									},
									{
										display_name: "Description",
										variable_name: "subscription_description",
										value: desc
									}
								]
							},
						  callback: function(response){
							  //Verify Transaction
							 VerifyPayment(sid,em,amt,ph,nt,pl,du,sdt,edt,vid);
							  
						  },
						  onClose: function(){
							  //Delete payment_log record
							 $.ajax({
								url: '<?php echo site_url('Subscribe/DeletePaymentLog'); ?>',
								data: {email:em, phone:ph, gateway:PayMethod,subscriptionId:sid},
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {				
									$.unblockUI();
								},
								error:  function(xhr,status,error) {
									bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
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
							  
							  m='Payment Was Terminated!';
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
					
						handler.openIframe();
					}else
					{
						m=ret;
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
					bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
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
				
			m="PayStackPayment ERROR:\n"+e;
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
	
	function VerifyPayment(sid,email,amount,phone,network,plan,duration,subscribe_date,exp_date,vid) 
	{
		var m;
		
		try
		{		
			var	PayMethod='PayStack';
			var TransAmt=parseFloat(amount) * 100;
				
			var mydata={email:email, phone:phone, amount:TransAmt, subscriptionId:sid, gateway:PayMethod, network:network, plan:plan,duration:duration,subscribe_date:subscribe_date,exp_date:exp_date,videos_cnt_to_watch:vid};
			
			//Log Transaction Here
			$.ajax({
				url: '<?php echo site_url('Subscribe/VerifyTransaction'); ?>',
				data: mydata,
				type: 'POST',
				dataType: 'text',
				success: function(data,status,xhr) {				
					$.unblockUI();
					
					var ret='';
					ret=$.trim(data).toUpperCase();
											
					if (ret=='OK')
					{
						//PayForTransaction(tranx_url, mydata);
						m='Subscription was successful. Subscription Id is <b><font color="#ff0000">' + sid +'</font></b>.';
					  	bootstrap_Success_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									window.location.reload(true);
								}
							});
					}else
					{
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
					bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
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
		   m='MakePayment Module ERROR:\n'+e;
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
</script>
</head>
<body>
<style>
.img-desc {
    background: #c5c3c4;
    padding: 10px 20px;
}
h4 {
    
}
.profile-img{
    float: left;
    width: 25%; 
}
.channel-in{
   min-height:600px;
   background:#ffffff;
}


.former {
    margin-top: 15px;
}
.channel-wrapper input.form-control {
    width: 100%;
    height: 35px;
}
.name-type {
    position: relative;
    top: -30px;
    margin-top: 30px;
}
.channel-in2 {
    background: #fff;
    min-height: 558px;
    padding: 20px;
}
@media only screen and (min-width:768px)
{
    .base1{
	    padding-right:15px !important; 
	}
	.base2{
	    padding-left:15px !important; 
	}
}
@media only screen and (max-width:767px){
    .base1 {
        margin-bottom: 20px !important;
    }
}
i.fa.fa-check-circle {
    font-size: 35px;
    color: green;
}
p.sub {
    font-size: 19px;
    font-weight: 600;
}
p.place {
    font-size: 16px;
    font-weight: 600;
}
</style>
<header> <?php include('usernav.php'); ?> </header>
 
 <div class="container">
 	<div class="content-wrapper">
 		<section class="content">
        	<div class="row">     
          		<div class="col-md-12">
          			<div class="panel panel-info">
                      <!-- Default panel contents -->
                      <div class="panel-heading size-20">
                        <span class="size-18 makebold"><i class="fa fa-volume-control-phone"></i> Service Subscription </span>
                      </div>
              
               		  <div class="panel-body">    
                	<!--Tab-->
                        <ul class="nav nav-tabs " style="font-weight:bold;">
                          <li  role="presentation" class="active"><a data-toggle="tab" href="#tabData"><i class="material-icons">select_all</i> Subscription Details</a></li>
                          <li role="presentation"><a data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Subscription History</a></li>
                        </ul>
                        <!--Tab Ends-->
                        
                         <div class="tab-content">
                         	<div id="tabData" class="row tab-pane fade in active ">
                    			 
                                 
                                 <div align="center" id="txtInfo" style="font-weight:bold; font-style:italic; color: #BBBBBB; margin-top:10px; margin-bottom:10px; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                                 
                                 <form class="form-horizontal"> 
                                    <!--Network/Phone Number-->
                                    <div class="form-group">
                                      <!--Network-->
                                      <label for="lblNetwork" class="col-sm-2 control-label " title="<?php echo $Network; ?>">Network<span class="redtext">*</span></label>
                    
                                      <div class="col-sm-3" title="<?php echo $Network; ?>">
                                         <label style="text-transform:none; color:#EC1D22;" class="form-control" id="lblNetwork"><?php echo $Network; ?></label>
                                      </div>
                                      
                                      <!--Phone Number-->
                                      <label for="lblPhone" class="col-sm-3 control-label" title="Subscriber Phone Number">Phone No</label>
                    
                                      <div class="col-sm-3" title="Subscriber Phone Number" > 
                                         <label id="lblPhone" class="form-control nobold" title="Phone Number"><?php echo $Phone; ?></label>
                                      </div>
                                    </div>
                                    
                                    
                                    <!--Service Plan Duration/Service Plan-->
                                    <div class="form-group">
                                    	<!--Service Plan-->
                                      <label for="cboPlan" class="col-sm-2 control-label" title="Service Plan">Service Plan<span class="redtext">*</span></label>
                    
                                      <div class="col-sm-3" title="Service Plan" > 
                                         <select id="cboPlan" class="form-control"></select>
                                      </div>
                                    
                                    	<!--Service Plan Duration-->
                                      <label for="lblDuration" class="col-sm-3 control-label" title="Service Plan Duration">Service Plan Duration</label>
                    
                                      <div class="col-sm-3" title="Service Plan Duration"> 
                                         <label class="form-control nobold" id="lblDuration"></label>
                                      </div>                                      
                                    </div>
                                    
                                   
                                    <!--No Of Videos/Amount-->
                                    <div class="form-group">
                                    <!--No Of Videos-->
                                  <label for="lblVideoCount" class="col-sm-2 control-label" title="No Of Videos To Watch">No Of Videos</label>
                
                                  <div class="col-sm-3"> 
                                     <label class="form-control nobold" id="lblVideoCount" title="No Of Videos To Watch"></label>
                                  </div>
                                
                                    <!--Amount-->
                                  <label for="lblAmount" class="col-sm-3 control-label" title="Subscription Amount">Subscription Amount (&#8358;)</label>
                
                                  <div class="col-sm-3"> 
                                     <label class="form-control nobold" id="lblAmount" title="Subscription Amount"></label>
                                  </div>
                                </div>
                                
                                <!--Subscription Date/Expiry Date-->
                                <div class="form-group">
                                    <!--Subscription Date-->
                                  <label for="lblSubscriptionDate" class="col-sm-2 control-label" title="Subscription Date">Subscription Date</label>
                
                                  <div class="col-sm-3"> 
                                     <label class="form-control nobold" id="lblSubscriptionDate" title="Subscription Date"></label>
                                  </div>
                                
                                    <!--Expiry Date-->
                                  <label for="lblExpiryDate" class="col-sm-3 control-label" title="Subscription Expiry Date">Subscription Expiry Date</label>
                
                                  <div class="col-sm-3"> 
                                     <label class="form-control nobold" id="lblExpiryDate" title="Subscription Expiry Date"></label>
                                  </div>
                                </div>
                                
                                <!--Enable Auto-Billing/Email-->
                                <div class="form-group">
                                   <!--Email-->
                                   <label for="lblEmail" class="col-sm-2 control-label" title="Subscriber Email">Email</label>
                    
                                      <div class="col-sm-3" title="Subscriber Email" > 
                                         <label id="lblEmail" class="form-control nobold"><?php echo $subscriber_email; ?></label>
                                      </div>
                                   
                                   <!--Enable Auto-Billing-->
                                   <label for="cboAutoBilling" class="col-sm-3 control-label" title="Enable Auto-Billing">Enable Auto-Billing<span class="redtext">*</span></label>
                
                                  <div class="col-sm-3" title="Enable Auto-Billing"> 
                                    <select id="cboAutoBilling" class="form-control">
                                    	<option value="1">Enable</option>
                                        <option value="0">Disable</option>
                                    </select>
                                  </div>                                  
                                </div>
                                
                                <!--Subscription ID-->
                                <div class="form-group">
                                   <label for="lblSubscriptionId" class="col-sm-2 control-label" title="Subscription ID">Subscription ID</label>
                    
                                      <div class="col-sm-3" title="Subscription ID" > 
                                         <label style="background-color:#C5522D; color:#ffffff;" id="lblSubscriptionId" class="form-control"><?php echo $subscriptionId; ?></label>
                                      </div>                                  
                                </div>
                                            
                            <div align="center">
                                <div id = "divAlert"></div>
                           </div>
                   
    				<center>
                    
                    <div class="redtext"><b>NOTE:</b> Please wait briefly after successful subscription for page refresh.</div>
                    
                    <div class="form-group" style="margin-top:30px;">
                        <div class="col-sm-offset-2 col-sm-7">
                         	<button title="Add Subscription" id="btnSubscribe" type="button" class="btn btn-primary" role="button" style="text-align:center; width:120px;"><i class="fa fa-credit-card-alt"></i> Subscribe</button>
                                                        
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="width:120px;  margin-left:10px;" ><i class="fa fa-refresh"></i> Refresh</button>
                        </div>
                        
                      
                        

                    </div>
                    </center>
                    
                    </form>   
                     		<br>
                           
                        </div><!--End Of Tab Content 1-->
                        
                        <div id="tabReport" class="tab-pane fade">
                        	<br>
                 		   <center>
                           <form class="form-horizontal">
                            <div class="form-group">
                            	<!--Start Date-->
                                <label class="col-sm-2 control-label" for="txtStartDate" title="Start Date">Start&nbsp;Date</label>
                                
                                <div class="col-sm-2" title="Start Date">
                                  <input style="background-color:#ffffff; color:#000000; z-index:0;" readonly id="txtStartDate" type="text" class="form-control" placeholder="Start Date">
                                  <i class="fa fa-calendar form-control-feedback" style="margin-top:12px; margin-right:10px;"></i>
                                </div>
                                
                                <!--End Date-->
                                <label class="col-sm-2 control-label" for="txtEndDate" title="End Date">End&nbsp;Date</label>
                                
                                <div class="col-sm-2" title="End Date">
                                  <input style="background-color:#ffffff; color:#000000; z-index:0;" readonly id="txtEndDate" type="text" class="form-control" placeholder="End Date">
                                  <i class="fa fa-calendar form-control-feedback" style="margin-top:12px; margin-right:10px;"></i>
                                </div>
                                
                                <div class="col-sm-4">
                                 
                                    <button style="" id="btnDisplay" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-play-circle" ></span> Display Subscriptions</button>
                                    
                                    <button style="width:120px; margin-left:15px;" id="btnRefreshSubscription" type="button" class="btn btn-danger" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Reset</button>
                                 </div>
                            </div>
                            </form> 
                            
                            <div class="table-responsive" style="margin-top:20px; ">
                            <table align="center" id="recorddisplay" cellspacing="0" title="Subscription Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                                  <thead style="color:#ffffff; background-color:#7E7B7B;">
                                    <tr>
                                        <th>SELECT</th>
                                        <th>NETWORK</th>
                                        <th>SERVICE&nbsp;PLAN</th>
                                        <th>DURATION</th>
                                        <th>NO&nbsp;OF&nbsp;VIDEOS</th>
                                        <th>AMOUNT(&#8358;)</th>
                                        <th>SUB.&nbsp;DATE</th>
                                        <th>EXP.&nbsp;DATE</th>
                                    </tr>
                                  </thead>
                                      
                              </table>
                            </div>
                           </center>
                       </div>
                    </div>
                
              </div>
          			</div>
        		</div>        
      		</div><!-- /.row -->
        </section><!-- /.row (main row) -->
    </div>
</div>

<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>

<script type='text/javascript' src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.select.min.js"></script> 
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.fixedColumns.min.js"></script>

 <script src="<?php echo base_url();?>js/jquery.sparkline.min.js"></script>
 <script src="<?php echo base_url();?>js/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-jvectormap-world-mill-en.js"></script>
 <script src="<?php echo base_url();?>js/moment.min.js"></script>
 <script src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
 <script src="<?php echo base_url();?>js/bootstrap-datetimepicker.min.js"></script>
 <script src="<?php echo base_url();?>js/bootstrap3-wysihtml5.all.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
</body>
</html>