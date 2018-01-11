<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Service Subscription</title>
<!--FAVICON-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
<link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
<link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg" color="#ff0000">
<meta name="theme-color" content="#ffffff">
<!--/FAVICON-->



<link rel="stylesheet" href="<?php echo base_url(); ?>acss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet">

<!--Datatable-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.jqueryui.min.css">
<!--End Datatable-->

<link rel="stylesheet" href="<?php echo base_url();?>css/pikaday.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/date-theme.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/date-triangle.css">
<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url();?>js/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/modernAlert.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>




<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>




<script>
(function($){

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
	var table;
	var self;
	
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

	$(document).ready(function(e) 
	{
        modernAlert({
                backgroundColor: '#fff',
                color: '#555',
                borderColor: '#ccc',
                titleBackgroundColor: '#C8552E',//#e8a033
                titleColor: '#fff',
                defaultButtonsText: {ok : 'Ok', cancel : 'Cancel'},
                overlayColor: 'rgba(0, 0, 0, 0.5)',
                overlayBlur: 2 //Set false to disable it or interger for pixle
            });
				
		$('#lblSubscriptionId').html(SubscriptionId);
		
		var pickerstart = new Pikaday(
		{
			field: document.getElementById('txtStartDate'),
			firstDay: 0,//first day of the week (0: Sunday, 1: Monday, etc)
			showDaysInNextAndPreviousMonths: true,
			enableSelectionDaysInNextAndPreviousMonths: true,
			//minDate: new Date(),
			//maxDate: new Date(2020, 12, 31),
			format: 'DD MMM YYYY',
				onSelect: function() {
				//alert(this.getMoment().format('Do MMMM YYYY'));//6th September 2017
			}
			//yearRange: [2000,2020]
		});
		
		var pickerend = new Pikaday(
		{
			field: document.getElementById('txtEndDate'),
			firstDay: 0,//first day of the week (0: Sunday, 1: Monday, etc)
			showDaysInNextAndPreviousMonths: true,
			enableSelectionDaysInNextAndPreviousMonths: true,
			//minDate: new Date(),
			//maxDate: new Date(2020, 12, 31),
			format: 'DD MMM YYYY',
				onSelect: function() {
				//alert(this.getMoment().format('Do MMMM YYYY'));//6th September 2017
			}
			//yearRange: [2000,2020]
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
				m="Start Date Changed ERROR:\n"+e;
				alert(m, 'LaffHub Message');					
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
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
				m="End Date Changed ERROR:\n"+e;
				alert(m, 'LaffHub Message');					
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
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
					alert(m, 'LaffHub Message');					
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
				}
				
				if (!ddt.isValid())
				{
					m="Subscription End Date Is Not Valid. Please Select A Valid Subscription End Date";
										
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
				}
									
				//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
				
				var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
				//var diff = moment.duration(ddt.diff(pdt));
							
				if (dys<0)
				{	
					$('#txtEndDate').val('');
					
					m="Subscription End Date Is Before Subscription Start Date. Please Correct Your Entries!";
					
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
				}
			}catch(e)
			{
				m="VerifyStartAndEndDates ERROR:\n"+e;
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		}
		
		LoadPlans(Network);
			
		function LoadPlans(network)
		{
			var self;
			
			try
			{				
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'mtn-custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Plans. Please Wait...</b></p></center>'
					}
				);
				
				//$.blockUI({message: '<img src="<?php #echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plans. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				$('#cboPlan').empty();
				
				$.ajax({
					url: "<?php echo site_url('Prices/LoadPlans');?>",
					type: 'POST',
					data:{network:network},
					dataType: 'json',
					complete: function(xhr, textStatus) {
						//$.msg('unblock');
					},
					success: function(data,status,xhr) {
                		$.msg('unblock');
													
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
							$.msg('unblock');
							
							m='Error '+ xhr.status + ' Occurred: ' + error;
							alert(m, 'LaffHub Message');
							bootstrap_alert.warning(m);
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
				});				
				
			}catch(e)
			{
				$.msg('unblock');
				m='LoadPlans Module ERROR:\n'+e;
				
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
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
				m="Service Plan Changed Changed ERROR:\n"+e;
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});
		
		function LoadPlanDetails(network,plan)
		{
			var self;
			
			try
			{
				$('#lblDuration').html('');
				$('#lblVideoCount').html('');					
				$('#lblAmount').html('');
				$('#lblSubscriptionDate').html('');					
				$('#lblExpiryDate').html('');
				
				//$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plan Details. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'mtn-custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Plan Details. Please Wait...</b></p></center>'
					}
				);
				
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
						$.msg('unblock');
						
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
							$.msg('unblock');
							
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
			
				//$.msg('unblock');
			}catch(e)
			{
				$.msg('unblock');
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
				$('#divAlert').html('');
					
				if (!Validate()) return false;
				
				var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
				var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
				var ph=$('#lblPhone').html();
				var nt=$('#lblNetwork').html();
											
				DisplayHistory(ph,sdt,edt,nt);
			}catch(e)
			{
				var m='Display Subscription History Button Click ERROR:\n'+e;
			   
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});//btnDisplay.click
		
		function Validate()
		{
			try
			{
				var nt=$('#lblNetwork').html();
				var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
				var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
				var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
				var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
				var p=$.trim($('#txtStartDate').val());
				var d=$.trim($('#txtEndDate').val());
				
				//Network
				if (!nt)
				{
					m='Network has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}
				
				//Start date Not Select. End Date Selected
				if (!p)
				{
					m='You have not selected the report start date.';
										
					alert(m, 'LaffHub Message');					
					bootstrap_alert.warning(m);
					
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false; 
				}
				
				if (!d)
				{
					m='You have not selected the report end date.';
					
					alert(m, 'LaffHub Message');					
					bootstrap_alert.warning(m);
					
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false; 
				}
				
				if (!p && d)
				{
					m='You have selected the report end date. Report start date field must also be selected.';
					
					alert(m, 'LaffHub Message');
					
					bootstrap_alert.warning(m);
					
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false; 
				}
				
				//End date Not Select. Start Date Selected
				if (p && !d)
				{
					m='You have selected the report start date. Report end date field must also be selected.';
					
					alert(m, 'LaffHub Message');
					
					bootstrap_alert.warning(m);
					
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false; 
				}
				
				if (p)
				{
					if (!pdt.isValid())
					{
						m="Report Start Date Is Not Valid. Please Select A Valid Report Start Date";
						alert(m, 'LaffHub Message');					
						bootstrap_alert.warning(m);
					
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);						
						
						return false;
					}	
				}
				
				if (d)
				{
					if (!ddt.isValid())
					{
						m="Report End Date Is Not Valid. Please Select A Valid Report End Date";
						alert(m, 'LaffHub Message');					
						bootstrap_alert.warning(m);
					
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
													
						return false;
					}	
				}
				
				
				if (p && d)
				{
					var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
					var diff = moment.duration(ddt.diff(pdt));
										
					if (dys<0)
					{
						m="Report End Date Is Before The Start Date. Please Correct Your Entries!";
						alert(m, 'LaffHub Message');					
						bootstrap_alert.warning(m);
					
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
						
						return false;
					}	
				}
											
				return true;
			}catch(e)
			{
				m='VALIDATE ERROR:\n'+e;
						
				alert(m, 'LaffHub Message');					
				bootstrap_alert.warning(m);
					
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
				
				return false;
			}
		}
			
		function DisplayHistory(msisdn,sdt,edt,network)
		{
			var self;
			
			try
			{
				//$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Retrieving Subscription History. Please Wait...</b></p>',theme: true,baseZ: 2000});
				
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'mtn-custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Retrieving Subscription History. Please Wait...</b></p></center>'
					}
				);
		
				var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
				var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
				//var pdt = moment(startdt), ddt = moment(enddt);
				var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
				
				if (!pdt.isValid()) sdt='';
				if (!ddt.isValid()) edt='';
				
				//Make Ajax Request
				var msg;
									
				msg='Subscription Report';					
				
				if (sdt && edt)
				{
					if (sdt == edt)
					{
						msg = msg + ' For '+ $('#txtStartDate').val();
					}else
					{
						msg = msg + ' Between '+ $('#txtStartDate').val() + ' And ' + $('#txtEndDate').val();
					}
				}
				
				var mydata={msisdn:msisdn,startdate:sdt,enddate:edt,network:network};	
															
				$.ajax({
					url: "<?php echo site_url('Subscribe/LoadSubscriptionHistory'); ?>",
					data: mydata,
					type: 'POST',
					dataType: 'json',
					beforeSend: function(){
						//if (table) table.destroy();
					},
					complete: function(xhr, textStatus) {					
						$.msg('unblock');
						
						activateTab('tabReport');
					},
					success: function(dataSet,status,xhr) {
						$.msg('unblock');
						
						if (table) table.destroy();
							
							table = $('#recorddisplay').DataTable( {
								dom: 'B<"top"if>rt<"bottom"lp><"clear">',
								autoWidth:false,
								destroy:true,
								lengthMenu: [ [ 10, 25, 50, 100,-1 ],[ '10', '25', '50', '100', 'All' ] ],
								language: {zeroRecords: "No Subscription History Record Found"},
								columnDefs: [
									{
										"targets": [ 0,1,2,3,4,5,6 ],
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
								order: [[ 1, 'asc' ]],
								data: dataSet,
			//SN,Network,Plan,Amount,SubscriptionDate,ExpiryDate,SubscriptionStatus
								columns: [
									{ width: "7%" },//SN
									{ width: "20%" },//Network
									{ width: "14%" },//Plan
									{ width: "14%" },//Amount
									{ width: "15%" },//Subscription Date
									{ width: "14%" },//Expiry Date
									{ width: "16%" }//Subscription Status									
								],
							} );
							
							table.on( 'order.dt search.dt', function () {
								table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
									cell.innerHTML = i+1;
								} );
							} ).draw();
						
					},
					error:  function(xhr,status,error) {
						$.msg('unblock');
						m='Error '+ xhr.status + ' Occurred: ' + error;
						alert(m, 'LaffHub Message');
						bootstrap_alert.warning(m);
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});	
				
				
			}catch(e)
			{
				$.msg('unblock');
				m='DisplayHistory Module Button Click ERROR:\n'+e;
				
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
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
				checkForm();				
			}catch(e)
			{
				var m='Subscribe Button Click ERROR:\n'+e;
				
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);				
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
							
				return false;
			}
		});//btnSubscribe.click
		
		function Subscribe(input)
		{
			var self;
			
			if (input === true)
			{
				//Subscribe
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'mtn-custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Subscribing User. Please Wait...</b></p></center>'
					}
				);
				
				//Make Ajax Request
				var nt=$('#lblNetwork').html();
				var ph=$('#lblPhone').html();
				var em=$('#lblEmail').html();
				var du=$('#lblDuration').html();
				var sid=$('#lblSubscriptionId').html();
				var pl=$('#cboPlan').val();
				var au=$('#cboAutoBilling').val();
				var vid=$('#lblVideoCount').html();			
				var amt=$('#lblAmount').html().replace(new RegExp(',', 'g'), '');				
				var sdt=ChangeDateFrom_dMY_To_Ymd($('#lblSubscriptionDate').html(),'-',' ');
				var edt=ChangeDateFrom_dMY_To_Ymd($('#lblExpiryDate').html(),'-',' ');				
				
				//Initiate POST
				var uri = "<?php echo site_url('Subscribe/SubscribeUser');?>";
				var xhr = new XMLHttpRequest();
				var fd = new FormData();
				
				xhr.open("POST", uri, true);
				
				xhr.onreadystatechange = function() {
					//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
					if (xhr.readyState === 4 && xhr.status === 200)
					{
						// Handle response.
						$.msg('unblock');
						
						var res=$.trim(xhr.responseText);
						
						if (res.toUpperCase()=='PROCESSING')
						{
                            m = "Please check the Pop-up on your phone to confirm your subscription request or <b>'Dial *560*1#'</b>";
							bootstrap_Success_alert.warning(m);			
							alert(m, 'LaffHub Message');
							setTimeout(function() {
								var url="<?php echo site_url('Subscribe/confirm'); ?>";
								window.location.replace(url);	
							}, 2000);

						}else
						{
							m=res;
							bootstrap_alert.warning(m);					
							alert(m, 'LaffHub Message');
							setTimeout(function() {
                                var url="<?php echo site_url('Subscribe/confirm'); ?>";
                                window.location.replace(url);	;
							}, 2000);
						}
					}else
					{
						$.msg('unblock');
					}
				};

				fd.append('network',nt);					
				fd.append('msisdn', ph);
				fd.append('email', em);
				fd.append('plan',pl);
				fd.append('duration',du);				
				fd.append('amount', amt);
				fd.append('subscribe_date',sdt);					
				fd.append('exp_date', edt);
				fd.append('autobilling', au);
				fd.append('videos_cnt_to_watch', vid);
				fd.append('subscriptionId', sid);
	
				xhr.send(fd);// Initiate a multipart/form-data upload
			} else
			{
				$.msg('unblock');
				m='Subscription Cancelled';
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		}
	
		function checkForm()
		{
			try
			 {
				var nt=$('#lblNetwork').html();
				var ph=$('#lblPhone').html();
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
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					activateTab('tabData'); return false;
				}
				
				//Phone
				if (!ph)
				{
					m='Subscriber phone and email have not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					activateTab('tabData'); return false;
				}
				
				//Subscription ID
				if (!sid)
				{
					m='Subscriber ID has not been displayed. Please click on <b>Refresh</b> button to reload the subscription page. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					activateTab('tabData'); return false;
				}
				
				//Plan
				if ($('#cboPlan > option').length < 2)
				{
					m='No service plan record was captured. Please contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					 activateTab('tabData'); return false;
				}
				
				if (!pl)
				{
					m="Please select a service plan.";
					
					
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#cboPlan').focus();  activateTab('tabData'); return false;
				}				
				
				//Duration
				if (!du)
				{
					m='Service plan duration has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#cboPlan').focus(); activateTab('tabData'); return false;
				}
				
				//No Of Videos
				if (!vid)
				{
					m='Number of videos allowed for the service plan has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
							
					$('#cboPlan').focus(); activateTab('tabData'); return false;
				}
				
				//Amount
				if (!amt)
				{
					m='Service plan amount has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#cboPlan').focus(); activateTab('tabData'); return false;
				}
				
				//Subscription Date
				if (!s)
				{
					m='Subscription date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#cboPlan').focus(); activateTab('tabData'); return false;
				}
				
				//Expiry Date
				if (!e)
				{
					m='Subscription expiry date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					$('#cboPlan').focus(); activateTab('tabData'); return false;
				}
			//confirm('Confirm Message', 'Confirm title', callback_function, null, {ok : 'textForOkButton', cancel : 'textForCancelButton'});
			//confirm('Confirm Message', 'Confirm title', function(input){var str = input === true ? 'Ok' : 'Cancel'; alert('You clicked ' + str, 'Simple Alert');});"
				m='Are you sure you want to subscribe to '+nt.toUpperCase()+' '+pl.toUpperCase()+' plan? (Click "Yes" to proceed or "No" to abort)?';
				
				confirm(m, 'LaffHub Message', Subscribe,null,{ok : 'Yes', cancel : 'No'});		
			 }catch(e)
			 {
				m='CHECK FORM ERROR:\n'+e; 
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			
				return false;
			 }
		 }//End CheckForm
    });
	
	
		 
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
			m="ResetControls ERROR:\n"+e;
			alert(m, 'LaffHub Message');
			bootstrap_alert.warning(m);
			setTimeout(function() {
				$('#divAlert').fadeOut('fast');
			}, 10000);
		}
	}//End ResetControls
	
})(jQuery);
</script>
</head>
<body class="page">
    <div class="page__layout">
      <div class="overlay"></div>
      	<?php include('newusernav.php'); ?>
        
        <div id="content-ajax">
        <!--MAIN-->
        <main class="page__main main">
          	<div class="col-md-12">
            <br>
                 <div class="panel panel-info">
                  <!-- Default panel contents -->
                  <div class="panel-heading size-20">
                    <span class="size-18 makebold"><i class="fa fa-volume-control-phone"></i> Service Subscription </span>
                  </div>
          
                  <div class="panel-body">    
                <!--Tab-->
                    <ul class="nav nav-tabs " style="font-weight:bold;">
                      <li  role="presentation" class="active"><a data-toggle="tab" href="#tabData"><i class="glyphicon glyphicon-list-alt"></i> Subscription Details</a></li>
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
                                     <label style="text-transform:none; color:#E5B400;" class="form-control" id="lblNetwork"><?php echo $Network; ?></label>
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

                            <!--Enable Auto-Billing/Email-->
                            <div class="form-group">
                                <!--Enable Auto-Billing-->
                                <div class="col-sm-3" title="Enable Auto-Billing">
                                    <div class="col-sm-3">
                                        <input type="hidden" class="form-control nobold" id="cboAutoBilling" title="Subscription Expiry Date" value="1">
                                    </div>
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
                            	<input class="form-control" style="background-color:#ffffff; color:#000000;" readonly type="text" id="txtStartDate" placeholder="Start Date">
                                
                              <i class="fa fa-calendar form-control-feedback" style="margin-top:8px; margin-right:10px;"></i>
                            </div>
          
                            
                            <!--End Date-->
                            <label class="col-sm-2 control-label" for="txtEndDate" title="End Date">End&nbsp;Date</label>
                            
                            <div class="col-sm-2" title="End Date">
                              <input style="background-color:#ffffff; color:#000000;" readonly id="txtEndDate" type="text" class="form-control" placeholder="End Date">
                              <i class="fa fa-calendar form-control-feedback" style="margin-top:8px; margin-right:10px;"></i>
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
                                    <th>S/N</th>
                                    <th>NETWORK</th>
                                    <th>SERVICE&nbsp;PLAN</th>
                                    <th>AMOUNT</th>
                                    <th>SUBSCRIPTION&nbsp;DATE</th>
                                    <th>EXPIRY&nbsp;DATE</th>
                                    <th>SUBSCRIPTION&nbsp;STATUS</th>
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
        </main>
        <!--/MAIN-->
        
        <!--FOOTER-->
       <?php include('newuserfooter.php'); ?>
        <!--/FOOTER-->
      </div>
	</div>
    

<script src="<?php echo base_url();?>js/moment.min.js"></script>
<script src="<?php echo base_url();?>js/pikaday.js"></script>
 
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!--Datatable-->
<script type='text/javascript' src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.select.min.js"></script> 
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.fixedColumns.min.js"></script>
<!--End Datatable-->

<!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>acss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->
 
</body>
</html>