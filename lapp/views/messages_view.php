<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
   <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Subscriber Messages</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    
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
		
		var Title='<font color="#AF4442">Subscriber Messages Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,editdata,seldata;
					
    	$(document).ready(function(e) {
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
					});
				}
			}
			
			$('#cboNetwork').change(function(e) {
                try
				{
					$('#cboPlan').empty();
					$('#txtSubscription').val('');
					$('#txtRenew').val('');					
					$('#txtInsufficientBal').val('');
					$('#txtExpiryNotice').val('');					
					$('#txt24ExpiryNotice').val('');
					$('#txtFallBack').val('');					
					$('#txtUpsellNotice').val('');
					$('#txtWrongKeyword').val('');
					$('#hidID').val('');
					
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
			
			$('#cboPlan').change(function(e) {
                try
				{
					$('#txtSubscription').val('');
					$('#txtRenew').val('');					
					$('#txtInsufficientBal').val('');
					$('#txtExpiryNotice').val('');					
					$('#txt24ExpiryNotice').val('');
					$('#txtFallBack').val('');					
					$('#txtUpsellNotice').val('');
					$('#txtWrongKeyword').val('');
					
					$('#hidID').val('');
					
					var nt=$('#cboNetwork').val();					
					var pl=$(this).val();
					
					if (nt && pl)
					{
						LoadMessages(nt,pl)
					}else
					{
						document.getElementById('btnDelete').disabled=true;
						document.getElementById('btnEdit').disabled=true;
						document.getElementById('btnAdd').disabled=false;
					}
					
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
									
			$('#btnDelete').click( function () 
			{
				try
				{
					var nt=$.trim($('#cboNetwork').val());
					var pl=$.trim($('#cboPlan').val());
					var id=$.trim($('#hidID').val());
					
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
						
						$('#cboNetwork').focus();  return false;
					}
					 
					//Plan
					if ($('#cboPlan > option').length < 2)
					{
						m="No service plan record was captured. Please contact the system administrator.";
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
						
						$('#cboPlan').focus();  return false;
					}
					
					
					if (!confirm('Are you sure you want to delete the service plan messages from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
					{
						return false;
					}else//Delete
					{
						//Send values here
						$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Messages. Please Wait...</p>',theme: true,baseZ: 2000});
					
						//Make Ajax Request			
						var mydata={network:nt, plan:pl};
						
						$.ajax({
							url: '<?php echo site_url('Messages/DeleteMessage'); ?>',
							data: mydata,
							type: 'POST',
							dataType: 'text',
							success: function(data,status,xhr) {
								$.unblockUI();
								
								var ret='';
								ret=$.trim(data);
								
								if (ret.toUpperCase()=='OK')
								{
									m='Service Plan Messages Were Deleted Successfully!';
										
									$('#cboPlan').val('');								
									$('#txtSubscription').val('');
									$('#txtRenew').val('');					
									$('#txtInsufficientBal').val('');
									$('#txtExpiryNotice').val('');					
									$('#txt24ExpiryNotice').val('');
									$('#txtFallBack').val('');					
									$('#txtUpsellNotice').val('');
									$('#txtWrongKeyword').val('');
									$('#hidID').val('');
									
									document.getElementById('btnDelete').disabled=true;
									document.getElementById('btnEdit').disabled=true;
									document.getElementById('btnAdd').disabled=false;
									
									//LoadMessages(nt,pl);
									
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
				}catch(e)
				{
					$.unblockUI();
					m='Delete Messages Button Click ERROR:\n'+e;
					
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
																						
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Messages. Please Wait...</p>',theme: true,baseZ: 2000});
										
					var nt=$.trim($('#cboNetwork').val());
					var pl=$.trim($('#cboPlan').val());
					var sb=$.trim($('#txtSubscription').val());
					var rn=$.trim($('#txtRenew').val());					
					var bal=$.trim($('#txtInsufficientBal').val());
					var ex=$.trim($('#txtExpiryNotice').val());					
					var ex24=$.trim($('#txt24ExpiryNotice').val());
					var fall=$.trim($('#txtFallBack').val());					
					var up=$.trim($('#txtUpsellNotice').val());
					var wr=$.trim($('#txtWrongKeyword').val());
					var id=$.trim($('#hidID').val());
					
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
						
						$('#cboNetwork').focus();  return false;
					}
					 
					//Plan
					if ($('#cboPlan > option').length < 2)
					{
						m="No service plan record was captured. Please contact the system administrator.";
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
						
						$('#cboPlan').focus();  return false;
					}
					
					//Initiate POST
					var uri = "<?php echo site_url('Messages/EditMessage');?>";
					var xhr = new XMLHttpRequest();
					var fd = new FormData();
					
					xhr.open("POST", uri, true);
					
					xhr.onreadystatechange = function() {
						//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
						if (xhr.readyState == 4 && xhr.status == 200)
						{
							// Handle response.
							$.unblockUI();
							
							var res=$.trim(xhr.responseText).toUpperCase();
							
							if (res.toUpperCase()=='OK')
							{
								m='Service Plan Messages Were Edited Successfully!';
									
								$('#cboPlan').val('');								
								$('#txtSubscription').val('');
								$('#txtRenew').val('');					
								$('#txtInsufficientBal').val('');
								$('#txtExpiryNotice').val('');					
								$('#txt24ExpiryNotice').val('');
								$('#txtFallBack').val('');					
								$('#txtUpsellNotice').val('');
								$('#txtWrongKeyword').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								//LoadMessages(nt,pl);
								
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
						}
					};	

					fd.append('network', nt);			
					fd.append('plan',pl);
					fd.append('subscription', sb);			
					fd.append('renewal',rn);
					fd.append('insufficent_balance', bal);			
					fd.append('expiry_notice',ex);
					fd.append('expiry_notice_24hrs', ex24);			
					fd.append('fallback_notice',fall);
					fd.append('upsell_notice', up);			
					fd.append('wrong_keyword',wr);					
					fd.append('Username', Username);
					fd.append('UserFullName', UserFullName);
					fd.append('id',id);					
					
					xhr.send(fd);// Initiate a multipart/form-data upload		

				}catch(e)
				{
					$.unblockUI();
					var m='Edit Message Button Click ERROR:\n'+e;
				   
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Message. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var nt=$.trim($('#cboNetwork').val());
					var pl=$.trim($('#cboPlan').val());
					
					var sb=$.trim($('#txtSubscription').val());
					var rn=$.trim($('#txtRenew').val());					
					var bal=$.trim($('#txtInsufficientBal').val());
					var ex=$.trim($('#txtExpiryNotice').val());					
					var ex24=$.trim($('#txt24ExpiryNotice').val());
					var fall=$.trim($('#txtFallBack').val());					
					var up=$.trim($('#txtUpsellNotice').val());
					var wr=$.trim($('#txtWrongKeyword').val());
					
					//Initiate POST
					var uri = "<?php echo site_url('Messages/AddMessage');?>";
					var xhr = new XMLHttpRequest();
					var fd = new FormData();
					
					xhr.open("POST", uri, true);
					
					xhr.onreadystatechange = function() {
						//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
						if (xhr.readyState == 4 && xhr.status == 200)
						{
							// Handle response.
							$.unblockUI();
							
							var res=$.trim(xhr.responseText);
														
							if (res.toUpperCase()=='OK')
							{
								m='Service Plan Messages Were Added Successfully!';
									
								$('#cboPlan').val('');								
								$('#txtSubscription').val('');
								$('#txtRenew').val('');					
								$('#txtInsufficientBal').val('');
								$('#txtExpiryNotice').val('');					
								$('#txt24ExpiryNotice').val('');
								$('#txtFallBack').val('');					
								$('#txtUpsellNotice').val('');
								$('#txtWrongKeyword').val('');
								$('#hidID').val('');
								
								document.getElementById('btnDelete').disabled=true;
								document.getElementById('btnEdit').disabled=true;
								document.getElementById('btnAdd').disabled=false;
								
								//LoadMessages(nt,pl);
								
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
								m=res;
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
						}
					};
				
					fd.append('network', nt);			
					fd.append('plan',pl);
					fd.append('subscription', sb);			
					fd.append('renewal',rn);
					fd.append('insufficent_balance', bal);			
					fd.append('expiry_notice',ex);
					fd.append('expiry_notice_24hrs', ex24);			
					fd.append('fallback_notice',fall);
					fd.append('upsell_notice', up);			
					fd.append('wrong_keyword',wr);					
					fd.append('Username', Username);
					fd.append('UserFullName', UserFullName);

					xhr.send(fd);// Initiate a multipart/form-data upload
									
				}catch(e)
				{
					$.unblockUI();
					var m='Add Message Button Click ERROR:\n'+e;
				   
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
					
					var sb=$.trim($('#txtSubscription').val());
					var rn=$.trim($('#txtRenew').val());					
					var bal=$.trim($('#txtInsufficientBal').val());
					var ex=$.trim($('#txtExpiryNotice').val());					
					var ex24=$.trim($('#txt24ExpiryNotice').val());
					var fall=$.trim($('#txtFallBack').val());					
					var up=$.trim($('#txtUpsellNotice').val());
					var wr=$.trim($('#txtWrongKeyword').val());
					
					 var ont='',id='';
					 
					 if (seldata)
					 {
						ont=seldata[1];
						id=seldata[4];
	//var nt=val[1],pl=val[2],submsg=val[3],id=val[4],renew=val[5],bal=val[6],ex=val[7],ex24=val[8],fall=val[9],upsell=val[10],wrong=val[11];					
						if ($.trim(ont)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a message record before clicking on "EDIT" button.';
							}else
							{
								m='Please select a message record before clicking on "DELETE" button.';
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
							
							return false;
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
						
						$('#cboNetwork').focus();  return false;
					}
					 
					//Plan
					if ($('#cboPlan > option').length < 2)
					{
						m="No service plan record was captured. Please contact the system administrator.";
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
						
						$('#cboPlan').focus();  return false;
					}
					
					//Subscription Plan
					if (!sb)
					{
						m="Subscription message field MUST NOT be blank.";
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
						
						$('#txtSubscription').focus();  return false;
					}
					
					if ($.isNumeric(sb))
					{
						m="Subscription message field must not be a number.";
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
						
						$('#txtSubscription').focus(); return false;
					}
					
					if (sb.length < 2)
					{
						m="Please enter a meaningful Subscription message.";
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
						
						$('#txtSubscription').focus(); return false;
					}		
					
					//Renewal
					if (rn)
					{
						if ($.isNumeric(rn))
						{
							m="Subscription renewal message field must not be a number.";
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
														
							$('#txtRenew').focus(); return false;
						}
						
						if (rn.length < 2)
						{
							m="Please enter a meaningful subscription renewal message.";
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
						
							$('#txtRenew').focus(); return false;
						}	
					}
					
					//Insufficient Balance
					if (bal)
					{
						if ($.isNumeric(bal))
						{
							m="Insufficent balance message field must not be a number.";
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
														
							$('#txtInsufficientBal').focus(); return false;
						}
						
						if (bal.length < 2)
						{
							m="Please enter a meaningful insufficent balance message.";
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
														
							$('#txtInsufficientBal').focus(); return false;
						}	
					}
					
					//Expiry Notice
					if (ex)
					{
						if ($.isNumeric(ex))
						{
							m="Subscription plan expiry notice message field must not be a number.";
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
														
							$('#txtExpiryNotice').focus(); return false;
						}
						
						if (ex.length < 2)
						{
							m="Please enter a meaningful subscription plan expiry notice message.";
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
														
							$('#txtExpiryNotice').focus(); return false;
						}	
					}
					
					//24 Hours Expiry Notice
					if (ex24)
					{
						if ($.isNumeric(ex24))
						{
							m="24 hours before plan expiration message field must not be a number.";
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
														
							$('#txt24ExpiryNotice').focus(); return false;
						}
						
						if (ex24.length < 2)
						{
							m="Please enter a meaningful 24 hours before plan expiration message.";
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
														
							$('#txt24ExpiryNotice').focus(); return false;
						}	
					}
					
					//Fall Back
					if (fall)
					{
						if ($.isNumeric(fall))
						{
							m="Fall back notification message field must not be a number.";
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
														
							$('#txtFallBack').focus(); return false;
						}
						
						if (fall.length < 2)
						{
							m="Please enter a meaningful fall back notification message.";
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
														
							$('#txtFallBack').focus(); return false;
						}	
					}
					
					//Upsell Notice
					if (up)
					{
						if ($.isNumeric(up))
						{
							m="Upsell notification message field must not be a number.";
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
														
							$('#txtUpsellNotice').focus(); return false;
						}
						
						if (up.length < 2)
						{
							m="Please enter a meaningful upsell notification message.";
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
							
							$('#txtUpsellNotice').focus(); return false;
						}	
					}
					
					//Wrong Keyword
					if (wr)
					{
						if ($.isNumeric(wr))
						{
							m="Wrong keyword message field must not be a number.";
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
							
							$('#txtWrongKeyword').focus(); return false;
						}
						
						if (wr.length < 2)
						{
							m="Please enter a meaningful wrong keyword message.";
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
														
							$('#txtWrongKeyword').focus(); return false;
						}	
					}
						
					if (!confirm('Are you sure you want to '+fn+' this service plan subscriber messages record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
		
		function LoadMessages(network,plan)
		{
			try
			{
				$('#txtSubscription').val('');
				$('#txtRenew').val('');					
				$('#txtInsufficientBal').val('');
				$('#txtExpiryNotice').val('');					
				$('#txt24ExpiryNotice').val('');
				$('#txtFallBack').val('');					
				$('#txtUpsellNotice').val('');
				$('#txtWrongKeyword').val('');
				$('#hidID').val('');
				
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Messages. Please Wait...</p>',theme: true,baseZ: 2000});
				
				var mydata={network:network,plan:plan};
				
				$.ajax({
					url: '<?php echo site_url('Messages/LoadMessages'); ?>',
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
								if (e.subscription) $('#txtSubscription').val($.trim(e.subscription));
								if (e.renewal) $('#txtRenew').val($.trim(e.renewal));
								if (e.insufficent_balance) $('#txtInsufficientBal').val($.trim(e.insufficent_balance));
								if (e.expiry_notice) $('#txtExpiryNotice').val($.trim(e.expiry_notice));
								if (e.expiry_notice_24hrs) $('#txt24ExpiryNotice').val($.trim(e.expiry_notice_24hrs));
								if (e.fallback_notice) $('#txtFallBack').val($.trim(e.fallback_notice));
								if (e.upsell_notice) $('#txtUpsellNotice').val($.trim(e.upsell_notice));
								if (e.wrong_keyword) $('#txtWrongKeyword').val($.trim(e.wrong_keyword));
								if (e.id) $('#hidID').val($.trim(e.id));
								
								return false;
							});
							
							if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=false;
							if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
							if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
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
								LoadMessages(network,plan);
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
		
		function ResetControls()
		{
			try
			{
				$('#cboNetwork').val('');
				$('#cboPlan').empty();
				
				$('#txtSubscription').val('');
				$('#txtRenew').val('');					
				$('#txtInsufficientBal').val('');
				$('#txtExpiryNotice').val('');					
				$('#txt24ExpiryNotice').val('');
				$('#txtFallBack').val('');					
				$('#txtUpsellNotice').val('');
				$('#txtWrongKeyword').val('');
				
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
              <div class="panel-heading size-20"><i class="glyphicon glyphicon-text-background"></i> Subscriber Messages</div>
                <div class="panel-body">    
                	<div align="center" id="txtInfo" style="font-weight:bold; font-style:italic; color: #BBBBBB; margin-top:10px; margin-bottom:10px; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                                 
                  <form class="form-horizontal"> 
                                    <!--Network/Plan-->                                    
                                     <div class="form-group">
                                      <label for="cboNetwork" class="col-sm-2 control-label" title="Network">Network<span class="redtext">*</span></label>
                                      
                                      <input type="hidden" id="hidID">
                    
                                      <div class="col-sm-4" title="Network">
                                         <select id="cboNetwork" class="form-control"></select>
                                      </div>
                                      
                                      <!-- Plan-->
                                      <label for="cboPlan" class="col-sm-2 control-label" title="Service Plan">Service&nbsp;Plan<span class="redtext">*</span></label>
                        
                                      <div class="col-sm-4" title="Service Plan" style="margin-left:-10px;">
                                         <select style="padding-bottom:3px; padding-top:3px;" id="cboPlan" class="form-control"></select>
                                      </div>
                                    </div>
                                    
                                     <!--Subscription Message/Renewal Message-->
                                    <div class="form-group">
                                      <!--Subscription Message-->
                                      <label for="txtSubscription" class="col-sm-2 control-label" title="Subscription Message">Subscription Message<span class="redtext">*</span></label>
                    
                                      <div class="col-sm-4" title="Subscription Message" > 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtSubscription" placeholder="Enter Subscription Message"></textarea>
                                      </div>
                                      
                                      <!--Renewal Message-->
                                      <label for="txtRenew" class="col-sm-2 control-label" title="Renewal Message">Renewal Message</label>
                    
                                      <div class="col-sm-4" title="Renewal Message" style="margin-left:-10px;"> 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtRenew" placeholder="Enter Renewal Message"></textarea>
                                      </div>
                                    </div>
                                    
                                    <!--Insufficient Balance/Expiry Notice-->
                                    <div class="form-group">
                                      <!--Subscription Message-->
                                      <label for="txtInsufficientBal" class="col-sm-2 control-label" title="Insufficient Balance Message">Insufficient Balance</label>
                    
                                      <div class="col-sm-4" title="Insufficient Balance Message" > 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtInsufficientBal" placeholder="Enter Insufficient Balance Message"></textarea>
                                      </div>
                                      
                                      <!--Expiry Notice-->
                                      <label for="txtExpiryNotice" class="col-sm-2 control-label" title="Expiry Notice Message">Expiry Notice</label>
                    
                                      <div class="col-sm-4" title="Expiry Notice Message" style="margin-left:-10px;"> 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtExpiryNotice" placeholder="Enter Expiry Notice Message"></textarea>
                                      </div>
                                    </div>
                                    
                                    
                                    <!--24 Hours To Expiry/Fall Back Message-->
                                    <div class="form-group">
                                      <!--24 Hours To Expiry-->
                                      <label for="txt24ExpiryNotice" class="col-sm-2 control-label" title="24 Hours To Expiry Message">24 Hours To Expiry</label>
                    
                                      <div class="col-sm-4" title="24 Hours To Expiry Message" > 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txt24ExpiryNotice" placeholder="Enter 24 Hours To Expiry Message"></textarea>
                                      </div>
                                      
                                      <!--Fall Back-->
                                      <label for="txtFallBack" class="col-sm-2 control-label" title="Fall Back Message">Fall Back</label>
                    
                                      <div class="col-sm-4" title="Fall Back Message" style="margin-left:-10px;"> 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtFallBack" placeholder="Enter Fall Back Message"></textarea>
                                      </div>
                                    </div>
                                    
                                    <!--Upsell Notice/Wrong Keyword Message-->
                                    <div class="form-group">
                                      <!--Upsell Notice-->
                                      <label for="txtUpsellNotice" class="col-sm-2 control-label" title="Upsell Notice">Upsell Notice</label>
                    
                                      <div class="col-sm-4" title="Upsell Notice" > 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtUpsellNotice" placeholder="Enter Upsell Notice"></textarea>
                                      </div>
                                      
                                      <!--Wrong Keyword-->
                                      <label for="txtWrongKeyword" class="col-sm-2 control-label" title="Wrong Keyword Message">Wrong Keyword</label>
                    
                                      <div class="col-sm-4" title="Wrong Keyword Message" style="margin-left:-10px;"> 
                                         <textarea rows="3" style="text-transform:none; " type="text" class="form-control" id="txtWrongKeyword" placeholder="Enter Wrong Keyword Message"></textarea>
                                      </div>
                                    </div>
                            <div align="center">
                                <div id = "divAlert"></div>
                           </div>
                   
    				<center>
                    <div class="form-group" style="margin-top:30px;">
                        <div class="col-sm-offset-2 col-sm-7">
                         	<button title="Add Record" id="btnAdd" type="button" class="btn btn-primary" role="button" style="text-align:center; width:120px;">
                                <span class="ui-button-text">Add</span>
                            </button>
                            
                            <button disabled title="Edit Record" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:120px; margin-left:10px;">
                                <span class="ui-button-text">Edit</span>
                            </button>
                            
                            <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:120px; margin-left:10px; ">
                                <span class="ui-button-text">Delete Record</span>
                            </button>
                            
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="width:120px;  margin-left:10px;" >
                                <span class="ui-button-text">Refresh</span>
                            </button>
                        </div>
                        
                      
                        
                    </div>
                    </center>
                    
                    </form>  
              </div>
          </div>
        		</div>        
      		</div><!-- /.row -->
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

        
  </body>
</html>
