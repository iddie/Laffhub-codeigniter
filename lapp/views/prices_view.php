<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Prices</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Prices Help</font>';
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
					var nt=val[1],pl=val[2],du=val[3],pr=val[4],id=val[5];
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
					$('#lblDuration').html('');
					$('#cboPlan').empty();
					$('#txtPrice').val('');
					$('#hidID').val('');
					
					$('#recorddisplay > tbody').html('');
										
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
					
					var nt=$(this).val();
					
					if (nt)
					{
						LoadPlans(nt);
						LoadPrices(nt);
					}
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
			
			$('#cboPlan').change(function(e) {
                try
				{
					$('#lblDuration').html('');
					$('#txtPrice').val('');
					$('#hidID').val('');
					
					var nt=$('#cboNetwork').val();					
					var pl=$(this).val();
					
					if (nt && pl) LoadDuration(nt,pl);
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
					   
			function LoadPlans(network)
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Plans. Please Wait...</p>',theme: true,baseZ: 2000});
					
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
			
			function LoadPrices(network)
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Prices. Please Wait...</p>',theme: true,baseZ: 2000});
					
					var mydata={network:network};
					
					table = $('#recorddisplay').DataTable( {
						lengthMenu: [ [5, 10, 25, 50,100, -1], [5, 10, 25, 50,100, "All"] ],
						select:true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						//processing: "Loading Distribution...",
						language: {
							zeroRecords: "No Price Record",
							//loadingRecords: "Loading Distribution. Please Wait...",
							emptyTable: "No Price Data Available"
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
								"targets": [ 0,5 ],
								"orderable": false,
								"searchable": false
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
						],//[SELECT],Network,ServicePlan,Duration,Price,Id
						columns: [
							{ width: "5%" },//SELECT
							{ width: "20%" },//Network
							{ width: "35%" },//Service Plan
							{ width: "20%" },//Plan Duration
							{ width: "20%" },//Price
							{ width: "0%" }//Id
						],
						order: [[ 1, 'asc' ],[ 3, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Prices/LoadPrices'); ?>',
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
					m='LoadPrices Module ERROR:\n'+e;
					
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
					var nt=editdata[1],pl=editdata[2],du=editdata[3],pr=editdata[4],id=editdata[5];
					
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
						if (!confirm('Are you sure you want to delete the price "'+pr.toUpperCase()+' for the service plan '+pl.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Price. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id:id,Username:Username,UserFullName:UserFullName};
							
							$.ajax({
								url: '<?php echo site_url('Prices/DeletePrice'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {
									$.unblockUI();
									
									var ret='';
									ret=$.trim(data);
									
									if (ret.toUpperCase()=='OK')
									{
										m='Service Plan Price <b>&#8358;'+pr.toUpperCase()+'</b> For The Service Plan <b>'+pl.toUpperCase()+'</b> Was Deleted Successfully!';
										
										$('#cboPlan').val('');
										$('#lblDuration').html('');
										$('#txtPrice').val('');
										$('#hidID').val('');
										
										document.getElementById('btnDelete').disabled=true;
										document.getElementById('btnEdit').disabled=true;
										document.getElementById('btnAdd').disabled=false;
										
										LoadPrices(nt);
										
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
					m='Delete Price Button Click ERROR:\n'+e;
					
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
					
					var nt=editdata[1],pl=editdata[2],du=editdata[3],pr=editdata[4],id=editdata[5];
															
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Service Plan Price. Please Wait...</p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request			
					var nt=$.trim($('#cboNetwork').val());
				 	var pl=$.trim($('#cboPlan').val());
				 	var du=$.trim($('#lblDuration').html());
				 	var pr=$.trim($('#txtPrice').val()).replace(new RegExp(',', 'g'), '');
						
					var mydata={network:nt,plan:pl,duration:du,price:pr,id:id,Username:Username,UserFullName:UserFullName};				
										
					$.ajax({
						url: '<?php echo site_url('Prices/EditPrice'); ?>',
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret='';
							ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								m='Service Plan Price <b>&#8358;'+pr.toUpperCase()+'</b> For The Service Plan <b>'+pl.toUpperCase()+'</b> Was Edited Successfully!';
								
								$('#cboPlan').val('');
								$('#lblDuration').html('');
								$('#txtPrice').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								LoadPrices(nt);
								
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
					var m='Edit Price Button Click ERROR:\n'+e;
				   
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Price. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var nt=$.trim($('#cboNetwork').val());
				 	var pl=$.trim($('#cboPlan').val());
				 	var du=$.trim($('#lblDuration').html());
				 	var pr=$.trim($('#txtPrice').val()).replace(new RegExp(',', 'g'), '');
						
					var mydata={network:nt,plan:pl,duration:du,price:pr,Username:Username,UserFullName:UserFullName};
					
					$.ajax({
						url: "<?php echo site_url('Prices/AddPrice'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret='';
							ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								m='Service Plan Price <b>&#8358;'+pr.toUpperCase()+'</b> For The Plan <b>'+pl.toUpperCase()+'</b> Was Added Successfully!';
								
								$('#cboPlan').val('');
								$('#lblDuration').html('');
								$('#txtPrice').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								LoadPrices(nt);
								
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
					var m='Add Price Button Click ERROR:\n'+e;
				   
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
					 var pl=$.trim($('#cboPlan').val());
					 var du=$.trim($('#lblDuration').html());
					 var pr=$.trim($('#txtPrice').val()).replace(new RegExp(',', 'g'), '');
					 
					 var oldnt='',id='';
					 
					 if (seldata)
					 {
						oldnt=seldata[1];
						id=seldata[5];
						
						if ($.trim(oldnt)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a price record before clicking on "EDIT" button.';
							}else
							{
								m='Please select a price record before clicking on "DELETE" button.';
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
					if ($('#cboPlan > option').length < 2)
					{
						m="No price record was captured. Please contact the system administrator.";
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
						
						$('#cboPlan').focus(); return false;
					}
					
					//Duration
					if (!du)
					{
						m="Service plan duration has not been displayed. Please contact the system administrator to determine if the plan duration has been captured with the service plan.";
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
						
						$('#cboPlan').focus(); return false;
					}
					
					//Service Plan Price
					if (!pr)
					{
						m="Service plan price field must not be blank.";
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
						
						$('#txtPrice').focus(); return false;
					}
							
					if (!$.isNumeric(pr))
					{
						m="Service plan price field MUST be a number.";
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
						
						$('#txtPrice').focus(); return false;
					}
					
					if (parseFloat(pr)==0.00)
					{
						m="Service plan price must NOT be zero.";
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
						
						$('#txtPrice').focus(); return false;
					}
					
					if (parseFloat(pr)<0)
					{
						m="Service plan price must NOT be a negative number.";
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
						
						$('#txtPrice').focus(); return false;
					}
							
					if (!confirm('Are you sure you want to '+fn+' this price record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
				
		function GetRow(sn)
		{
			$('#cboNetwork').val('');
			$('#cboPlan').empty();
			$('#lblDuration').html('');
			$('#txtPrice').val('');
			$('#hidID').val('');

			if (sn>-1)
			{
				var dat = table.row( sn ).data();
				
				editdata=dat;
		
				if (dat)
				{
					var nt=dat[1],pl=dat[2],du=dat[3],pr=dat[4],id=dat[5];
					
					$('#cboNetwork').val(nt);
					$('#txtDuration').val(du);
					$('#txtPrice').val(pr);
					$('#hidID').val(id);
					
					LoadDisplayPlans(nt,pl);
														
					document.getElementById('btnEdit').disabled=false;
					document.getElementById('btnDelete').disabled=false;
					document.getElementById('btnAdd').disabled=true;
				}
			}
		}
		
		function LoadDisplayPlans(network,plan)
		{
			try
			{
				$('#cboPlan').empty();
					
				$.ajax({
					url: "<?php echo site_url('Prices/LoadPlans');?>",
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
							$('#cboPlan').append( new Option('[SELECT]','') );
							
							$.each($(data), function(i,e)
							{
								if (e.plan) $('#cboPlan').append( new Option($.trim(e.plan),$.trim(e.plan)) );
							});
							
							if ($('#cboPlan > option').length > 1)
							{
								$('#cboPlan').val(plan);
								LoadDuration(network,plan);
							}
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
				m="LoadDisplayPlans ERROR:\n"+e;
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
		
		function LoadDuration(network,plan)
		{
			try
			{
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Plan Duration. Please Wait...</p>',theme: true,baseZ: 2000});
				
				$('#lblDuration').html('');
				
				$.ajax({
					url: "<?php echo site_url('Prices/LoadDuration');?>",
					type: 'POST',
					data:{network:network,plan:plan},
					dataType: 'text',
					complete: function(xhr, textStatus) {
						//$.unblockUI;
					},
					success: function(data,status,xhr) {	
						$.unblockUI();
						
						var ret='';
						ret=$.trim(data);
						
						if ($.isNumeric(ret))
						{
							$('#lblDuration').html(ret);
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
				m='LoadDuration Module ERROR:\n'+e;
				
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
			
		function ResetControls()
		{
			try
			{
				$('#cboNetwork').val('');
				$('#lblDuration').html('');
				$('#cboPlan').empty();
				$('#txtPrice').val('');
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
              <div class="panel-heading"><i class="glyphicon glyphicon-tag"></i> Prices</div>
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
                      <label for="cboPlan" class="col-sm-2 control-label" title="Service Plan">Service&nbsp;Plan<span class="redtext">*</span></label>
        
                      <div class="col-sm-3" title="Service Plan">
                         <select id="cboPlan" class="form-control"></select>
                      </div>
                      
                       <div style="float:right; margin-right:20px;"> 
                       	<button title="Add Price" id="btnAdd" type="button" class="btn btn-primary " role="button" style="text-align:center; width:110px;"><i class="glyphicon glyphicon-plus-sign"></i> Add Price</button>
							
                         <button disabled title="Edit Price" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:110px; margin-left:10px;"><i class="glyphicon glyphicon-edit"></i> Edit Price</button> 
                       </div>
                    </div> 
                                          
                    
                     <div class="form-group"> 
                     <!-- Plan Duration-->
                       <label for="lblDuration" class="col-sm-2 control-label" title="Service Plan Duration">Plan&nbsp;Duration(Days)</label>
        
                      <div class="col-sm-2" title="Service Plan Duration">
                         <label id="lblDuration" class="form-control"></label>
                      </div>
                      
                     <!--Price-->
                     <label for="txtPrice" class="col-sm-2 control-label" title="Service Plan Price">Service&nbsp;Plan&nbsp;Price(&#8358;)<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Service Plan Price">
                         <input type="text" id="txtPrice" class="form-control">
                      </div>
                                           
                       <div style="float:right; margin-right:20px;">                                        
						
                        <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:110px; "><i class="fa fa-times-circle"></i> Delete Price</button>
                        
               			<button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="text-align:center; width:110px; margin-left:10px;"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                        
                        
                		</div>
                    </div> 
                                                            
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                   <br>
                    <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="Service Plans Prices" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>SELECT</th>
                                <th>NETWORK</th>
                                <th>SERVICE&nbsp;PLAN</th>
                                <th>PLAN&nbsp;DURATION</th>
                                <th>PRICE</th> 
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
