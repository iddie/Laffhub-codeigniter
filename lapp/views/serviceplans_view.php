<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Service Plans</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
        
    <script>
		var Title='<font color="#AF4442">Service Plans Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';		
		var table,editdata,seldata;
			
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
			
			LoadNetwork();
			
			function LoadNetwork()
			{
				try
				{
					$('#cboNetwork').empty();
					$('#cboNetwork').append( new Option('[SELECT]','') );
					$('#cboNetwork').append( new Option('Airtel','Airtel') );				
					$('#cboNetwork').append( new Option('MTN','MTN') );
					$('#cboNetwork').append( new Option('WIFI','WIFI') );
					$('#cboNetwork').append( new Option('Etisalat','Etisalat') );
					$('#cboNetwork').append( new Option('GLO','GLO') );	
				}catch(e)
				{
					$.unblockUI();
					m='LoadNetwork Module ERROR:\n'+e;
					
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
			
			if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
			if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
			if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
			
			$('#recorddisplay tbody').on('click', 'td', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
				editdata = row.data();
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{					
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=false;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=true;
								
					//Get Selected Value
					var val=table.row( this ).data();
					seldata=val;
					var nt=val[1],pl=val[2],du=val[3],vid=val[4],st=val[5],sta=val[6],id=val[7];
					
					$('#cboNetwork').val(nt);
					$('#cboStatus').val(sta);
					$('#txtDuration').val(du);
					$('#txtVideos').val(vid);
					$('#txtPlan').val(pl);
					$('#hidID').val(id);
				}
				else {
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					ResetControls();
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
			} );
			
			$('#cboNetwork').change(function(e) {
                try
				{
					$('#cboStatus').val('');
					$('#txtDuration').val('');
					$('#txtVideos').val('');
					$('#txtPlan').val('');
					$('#hidID').val('');
					
					$('#recorddisplay > tbody').html('');
										
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
					
					var nt=$(this).val();
					
					if (nt) LoadPlans(nt);
				}catch(e)
				{
					$.unblockUI();
					m="Network Changed Changed ERROR:\n"+e;
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
					   
			function LoadPlans(network)
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Plans. Please Wait...</p>',theme: true,baseZ: 2000});
					
					var mydata={network:network};
					
					table = $('#recorddisplay').DataTable( {
						lengthMenu: [ [5, 10, 25, 50,100, -1], [5, 10, 25, 50,100, "All"] ],
						select:true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						//processing: "Loading Distribution...",
						language: {
							zeroRecords: "No Service Plan Record",
							//loadingRecords: "Loading Distribution. Please Wait...",
							emptyTable: "No Service Plan Data Available"
							},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4,5 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{
								"targets": [ 6,7 ],
								"visible": false
							},
							{
								"targets": [ 0,6,7 ],
								"orderable": false,
								"searchable": false
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7 ] }
						],//[SELECT],Network,ServicePlan,PlanDuration,Videos,StatusText,Status
						columns: [
							{ width: "5%" },//SELECT
							{ width: "15%" },//Network
							{ width: "30%" },//Service Plan
							{ width: "20%" },//Plan Duration
							{ width: "15%" },//No Of Videos
							{ width: "15%" },//StatusText
							{ width: "0%" },//Status
							{ width: "0%" }//Id
						],
						order: [[ 1, 'asc' ],[ 3, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Serviceplans/LoadPlans'); ?>',
							type: 'POST',
							data: mydata,
							complete: function(xhr, textStatus) {
								$.unblockUI();
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
							},
							dataType: 'json'
					   }
					} );
					
					$.unblockUI();	
					
					
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
			
			$('#btnDelete').click( function () 
			{
				try
				{
					var nt=editdata[1],pl=editdata[2],du=editdata[3],vid=editdata[4],st=editdata[5],sta=editdata[6],id=editdata[7];
					
					//Validate 
					if ($.trim(id)=='')
					{
						m='Please select a service plan from the table before clicking on "DELETE" button.';
						
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
								
						$('#cboNetwork').focus(); return false;
					}else
					{
						if (!confirm('Are you sure you want to delete the service plan "'+pl.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Service Plan. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id:id,Username:Username,UserFullName:UserFullName};
							
							$.ajax({
								url: '<?php echo site_url('Serviceplans/DeletePlan'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {
									$.unblockUI();
									
									var ret='';
									ret=$.trim(data);
									
									if (ret.toUpperCase()=='OK')
									{
										m='Service Plan <b>'+pl.toUpperCase()+'</b> For <b>'+nt.toUpperCase()+'</b> Was Deleted Successfully!';
										
										$('#cboStatus').val('');
										$('#txtDuration').val('');
										$('#txtVideos').val('');
										$('#txtPlan').val('');
										$('#hidID').val('');
										
										document.getElementById('btnDelete').disabled=true;
										document.getElementById('btnEdit').disabled=true;
										document.getElementById('btnAdd').disabled=false;
										
										LoadPlans(nt);
										
										bootstrap_Success_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
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
						}
					}
				}catch(e)
				{
					$.unblockUI();
					m='Delete Service Plan Button Click ERROR:\n'+e;
					
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
			} );	
								
			//Edit record
			$('#btnEdit').click(function(e){//EDIT
				try
				{
					if (!checkForm('EDIT')) return false;
					
					var nt=editdata[1],pl=editdata[2],du=editdata[3],vid=editdata[4],st=editdata[5],sta=editdata[6],id=editdata[7];
										
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Service Plan. Please Wait...</p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request			
					var nt=$.trim($('#cboNetwork').val());
				 	var st=$.trim($('#cboStatus').val());
				 	var du=$.trim($('#txtDuration').val()).replace(new RegExp(',', 'g'), '');
					var vid=$.trim($('#txtVideos').val()).replace(new RegExp(',', 'g'), '');
				 	var pl=$.trim($('#txtPlan').val());
											
					var mydata={network:nt,plan:pl,duration:du,no_of_videos:vid,plan_status:st,id:id,Username:Username,UserFullName:UserFullName};
					
					$.ajax({
						url: '<?php echo site_url('Serviceplans/EditPlan'); ?>',
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret='';
							ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								m='Service Plan <b>'+pl.toUpperCase()+'</b> For <b>'+nt.toUpperCase()+'</b> Was Edited Successfully!';
								
								$('#cboStatus').val('');
								$('#txtDuration').val('');
								$('#txtVideos').val('');
								$('#txtPlan').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								LoadPlans(nt);
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
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
					var m='Edit Service Plan Button Click ERROR:\n'+e;
				   
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
			});//End click-btnEdit
			
			$('#btnAdd').click(function(e) {
				try
				{
					if (!checkForm('ADD')) return false;
				
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Service Plan. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var nt=$.trim($('#cboNetwork').val());
				 	var st=$.trim($('#cboStatus').val());
				 	var du=$.trim($('#txtDuration').val()).replace(new RegExp(',', 'g'), '');
					var vid=$.trim($('#txtVideos').val()).replace(new RegExp(',', 'g'), '');
				 	var pl=$.trim($('#txtPlan').val());
						
					var mydata={network:nt,plan:pl,duration:du,no_of_videos:vid,plan_status:st,Username:Username,UserFullName:UserFullName};
					
					$.ajax({
						url: "<?php echo site_url('Serviceplans/AddPlan'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret='';
							ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								m='Service Plan <b>'+pl.toUpperCase()+'</b> For <b>'+nt.toUpperCase()+'</b> Was Added Successfully!';
								
								$('#cboStatus').val('');
								$('#txtDuration').val('');
								$('#txtVideos').val('');
								$('#txtPlan').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								LoadPlans(nt);
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
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
					var m='Add Service Plan Button Click ERROR:\n'+e;
				   
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
			});//btnAdd.click
			
			
			function checkForm(fn)
			{
				try
				 {
					 var nt=$.trim($('#cboNetwork').val());
					 var st=$.trim($('#cboStatus').val());
					 var du=$.trim($('#txtDuration').val()).replace(new RegExp(',', 'g'), '');
					 var vid=$.trim($('#txtVideos').val()).replace(new RegExp(',', 'g'), '');
					 var pl=$.trim($('#txtPlan').val());
					 
					 var oldnt='',id='';
					 
					 if (seldata)
					 {
						oldnt=seldata[1];
						id=seldata[6];
						
						if ($.trim(oldnt)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a service plan before clicking on "EDIT" button.';
							}else
							{
								m='Please select a service plan before clicking on "DELETE" button.';
							}
							
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
							
							$('#cboNetwork').focus(); return false;
						}
					 }
					
					//Network 
					if (!nt)
					{
						m="Please select a network.";
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
						
						$('#cboNetwork').focus(); return false;
					}
					
					//Plan
					if (!pl)
					{
						m="Service plan field must not be blank.";
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
						
						$('#txtPlan').focus(); return false;
					}
							
					if ($.isNumeric(pl))
					{
						m="Service plan field must NOT be a number.";
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
						
						$('#txtPlan').focus(); return false;
					}
					
					if (pl.length < 2)
					{
						m="Please enter a meaningful service plan.";
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
						
						$('#txtPlan').focus(); return false;
					}
					
					//Duration
					if (!du)
					{
						m="Service plan duration field must not be blank.";
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
						
						$('#txtDuration').focus(); return false;
					}
							
					if (!$.isNumeric(du))
					{
						m="Service plan duration field MUST be a number.";
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
						
						$('#txtDuration').focus(); return false;
					}
					
					if (isInteger(du)==false)
					{
						m="Service plan duration must be a whole number.";
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
						
						$('#txtDuration').focus(); return false;
					}
					
					if (parseInt(du,10)==0)
					{
						m="Service plan duration must not be zero.";
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
						
						$('#txtDuration').focus(); return false;
					}
					
					if (parseInt(du,10)<0)
					{
						m="Service plan duration must not be a negative number.";
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
						
						$('#txtDuration').focus(); return false;
					}
					
					//No of Videos
					if (!vid)
					{
						m="No of videos to watch field must not be blank.";
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
						
						$('#txtVideos').focus(); return false;
					}
							
					if ($.isNumeric(vid))
					{
						if (isInteger(vid)==false)
						{
							m="No of videos to watch must be a whole number.";
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
							
							$('#txtVideos').focus(); return false;
						}
						
						if (parseInt(vid,10)==0)
						{
							m="No of videos to watch must not be zero.";
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
							
							$('#txtVideos').focus(); return false;
						}
						
						if (parseInt(vid,10)<0)
						{
							m="No of videos to watch must not be a negative number.";
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
							
							$('#txtVideos').focus(); return false;
						}	
					}
					
					
					
					//Status
					if (!st)
					{
						m="Please indicate if the service plan should be activated or not by selecting the status.";
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
							
					if (!confirm('Are you sure you want to '+fn+' this service plan record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
		
		function isInteger(value) 
		{
			if (isNaN(value)) return false;
		  	
			var x = parseFloat(value);
		  	
			 return (x | 0) === x;
		}
		
		function GetRow(sn)
		{
			$('#cboNetwork').val('');
			$('#cboStatus').val('');
			$('#txtDuration').val('');
			$('#txtVideos').val('');
			$('#txtPlan').val('');
			$('#hidID').val('');

			if (sn>-1)
			{
				var dat = table.row( sn ).data();
				
				editdata=dat;
		
				if (dat)
				{
					var nt=dat[1],pl=dat[2],du=dat[3],vid=dat[4],st=dat[5],sta=dat[6],id=dat[7];
					
					$('#cboNetwork').val(nt);
					$('#cboStatus').val(sta);
					$('#txtDuration').val(du);
					$('#txtVideos').val(vid);
					$('#txtPlan').val(pl);
					$('#hidID').val(id);
														
					document.getElementById('btnEdit').disabled=false;
					document.getElementById('btnDelete').disabled=false;
					document.getElementById('btnAdd').disabled=true;
				}
			}
		}
		
		function ResetControls()
		{
			try
			{
				$('#cboNetwork').val('');
				$('#cboStatus').val('');
				$('#txtDuration').val('');
				$('#txtVideos').val('');
				$('#txtPlan').val('');
				$('#hidID').val('');
				
				$('#recorddisplay > tbody').html('');
				
				if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
				if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
				if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
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
          <span style="float:left; font-size:22px; color:#AC5288;">LaffHub</span>
          
          <span style="float:right;"><a id="ancLogout" href="#"><i class="fa fa-home"></i> Home</a></span>
        </section>

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          <div class="col-md-12">
         		<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading"><i class="fa fa-language"></i> Service Plans</div>
              <div class="panel-body">
                          
                <div align="center" class="size-14 " style="font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; margin-bottom:10px;">Fields With <span class="redtext">*</span> Are Required!</div>
                
              	<form class="form-horizontal"> 
               	    	<!--Network-->                                    
                     <div class="form-group">
                      <label for="cboNetwork" class="col-sm-2 control-label" title="Network">Network<span class="redtext">*</span></label>
                      
                      <input type="hidden" id="hidID">
    
                      <div class="col-sm-2" title="Network">
                         <select id="cboNetwork" class="form-control"></select>
                      </div>
                      
                      <!-- Plan-->
                      <label for="txtPlan" class="col-sm-2 control-label" title="Service Plan">Service&nbsp;Plan<span class="redtext">*</span></label>
        
                      <div class="col-sm-3" title="Service Plan">
                         <input type="text" id="txtPlan" class="form-control">
                      </div>
                    </div> 
                                          
                    
                     <div class="form-group"> 
                     <!-- Plan Duration-->
                       <label for="txtDuration" class="col-sm-2 control-label" title="Plan Duration">Plan&nbsp;Duration(Days)<span class="redtext">*</span></label>
        
                      <div class="col-sm-2" title="Plan Duration">
                         <input type="text" id="txtDuration" class="form-control">
                      </div>
                      
                     <!--No Of Videos-->
                     <label for="txtVideos" class="col-sm-2 control-label" title="Number Of Videos To Watch">No&nbsp;Of&nbsp;Videos<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Number Of Videos To Watch">
                         <input type="text" id="txtVideos" class="form-control">
                      </div>
                    </div> 
                    
                     <div class="form-group"> 
                     <!--Status-->
                     <label for="cboStatus" class="col-sm-2 control-label" title="Service Plan Status">Plan&nbsp;Status<span class="redtext">*</span></label>
    
                      <div class="col-sm-2" title="Service Plan Status">
                         <select id="cboStatus" class="form-control">
                         	<option value="">[SELECT]</option>
                            <option value="0">Not Active</option>
                            <option value="1">Active</option>
                         </select>
                      </div>
                                     
                       <div class="col-sm-offset-6">                                        
						<button title="Add Service Plan" id="btnAdd" type="button" class="btn btn-primary " role="button" style="text-align:center; width:110px;"><i class="glyphicon glyphicon-plus-sign"></i> Add Plan</button>
							
                         <button disabled title="Edit Service Plan" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:110px; margin-left:10px;"><i class="glyphicon glyphicon-edit"></i> Edit Plan</button> 
                         
                        <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:110px;  margin-left:10px;"><i class="fa fa-times-circle"></i> Delete Plan</button>
                        
               			<button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="text-align:center; width:110px; margin-left:10px;"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                        
                        
                		</div>
                    </div> 
                                                            
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                   <br>
                    <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="Network Plans" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>SELECT</th>
                                <th>NETWORK</th>
                                <th>SERVICE&nbsp;PLAN</th>
                                <th>PLAN&nbsp;DURATION</th>
                                <th>NO&nbsp;OF&nbsp;VIDEOS</th>
                                <th>STATUS</th> 
                                <th class="hide">Status</th> 
                                <th class="hide">id</th> 
                            </tr>
                          </thead>
                      </table>
                    </div>
                </center>
                   
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
     
    
       
     
       
  </body>
</html>
