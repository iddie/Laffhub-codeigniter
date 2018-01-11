<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Unsubscribe User</title>
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
		
		var Title='<font color="#AF4442">Unsubscribe User Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		
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
						
			$('#btnVerify').click(function(e) {
                try
				{
					$('#divVerify').html('');
					$('#divAlert').html('');
					
					var nt=$('#cboNetwork').val();
					var nm=$.trim($('#txtVerify').val());
					
					if (!nt)
					{
						m='Please select a network.';
				
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
					
					//Phone
					if (!nm)
					{
						m='Please enter the phone number to be verified.';
				
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
						
						$('#txtVerify').focus(); return false;
					}
					
					var s=nm.split(',');
					
					if (s.length>1)
					{
						for(var i=0; i<s.length; i++)
						{
							if (!s[i])
							{
								m='Phone number field must not be blank. Separate multiple emails by commas.';
								
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
								
								$('#txtVerify').focus(); return false;
							}
							
							if (s[i].length < 11)
							{
								m='Please enter valid and correct phone number. Phone number '+s[i]+' is not valid.';
						
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
								
								$('#txtVerify').focus(); return false;
							}
							
							if (!$.isNumeric(s[i].replace('+','')))
							{
								m='Phone number field must be numeric. Phone number '+s[i]+' is not numeric.';
								
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
								
								$('#txtVerify').focus(); return false;
							}
						}
						
					}else
					{
						if (nm.length < 11)
						{
							m='Please enter valid and correct phone numbers.';
					
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
							
							$('#txtVerify').focus(); return false;
						}
					
						if (!$.isNumeric(nm.replace('+','')))
						{
							m='Phone number field must be numeric.';
							
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
							
							$('#txtVerify').focus(); return false;
						}
					}
										
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Verifying Numbers. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						data: {network:nt,numbers:nm},
						dataType: 'json',
						url: '<?php echo site_url('Blacklist/VerifyNumbers'); ?>',
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							$.unblockUI();
							
							if ($(data).length > 0)
							{
								$.each($(data),function(i,e)
								{
									var sta=$.trim(e.status);
									var msg=$.trim(e.msg);
									
									m=msg;
									
									if (sta.toUpperCase()=='OK')
									{
										if ($.trim(e.Nos)=='')
										{
											$('#txtVerify').val('');								
										}else
										{
											$('#divVerify').html(msg);
										}
										
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
									
									 return false;
								});
							}else
							{
								m='Verification was not successful.';
								
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
					 }); //end AJAX
					 
					// $.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Verify Numbers Click ERROR:\n'+e;
					
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
			
			$('#btnRemove').click(function(e) {
                try
				{
					var nt=$('#cboNetwork').val();
					var nm=$.trim($('#txtNumbers').val());
					
					if (!nt)
					{
						m='Please select a network.';
				
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
					
					//Phone
					if (!nm)
					{
						m='Please enter the phone number to be unsubscribed.';
				
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
						
						$('#txtNumbers').focus(); return false;
					}
					
					var s=nm.split(',');
					
					if (s.length>1)
					{
						for(var i=0; i<s.length; i++)
						{
							if (!s[i])
							{
								m='Phone number field must not be blank. Separate multiple emails by commas.';
								
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
								
								$('#txtNumbers').focus(); return false;
							}
							
							if (s[i].length < 11)
							{
								m='Please enter valid and correct phone number. Phone number '+s[i]+' is not valid.';
						
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
								
								$('#txtNumbers').focus(); return false;
							}
							
							if (!$.isNumeric(s[i].replace('+','')))
							{
								m='Phone number field must be numeric. Phone number '+s[i]+' is not numeric.';
								
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
								
								$('#txtNumbers').focus(); return false;
							}
						}
						
					}else
					{
						if (nm.length < 11)
						{
							m='Please enter valid and correct phone numbers.';
					
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
							
							$('#txtNumbers').focus(); return false;
						}
					
						if (!$.isNumeric(nm.replace('+','')))
						{
							m='Phone number field must be numeric.';
							
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
							
							$('#txtNumbers').focus(); return false;
						}
					}
					
					if (!confirm('Are you you want to unsubscribe the number(s)? (Click "OK" to proceed or "CANCEL") to abort)'))
					{
						return false;
					}
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Unsubscribing Numbers. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						data: {network:nt,numbers:nm},
						dataType: 'json',
						url: '<?php echo site_url('Removeuser/RemoveNumbers'); ?>',
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							$.unblockUI();
							
							if ($(data).length > 0)
							{
								$.each($(data),function(i,e)
								{
									var sta=$.trim(e.status);
									var msg=$.trim(e.msg);
									
									m=msg;
									
									if (sta.toUpperCase()=='OK')
									{
										$('#txtNumbers').val('');
										//$('#cboNetwork').val('');								
										
										bootstrap_Success_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-success" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									}else
									{
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
									
									 return false;
								});
							}else
							{
								m='Unsubscription was not successful.';
								
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
					 }); //end AJAX
					 
					// $.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Unsubscribe Numbers Click ERROR:\n'+e;
					
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
         <h4>
            LaffHub
          </h4>
          
          <ol class="breadcrumb size-16">
            <li><a href="<?php echo site_url("logout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
       

        <!-- Main content -->
        <section class="content">
          	<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-times"></i> Unsubscribe User</div>
                <div class="panel-body">
                	 <!--Network-->
                     <div class="row" title="Select Network">
                        <label class="col-sm-1 control-label" for="cboNetwork">Network</label>
                    
                        <div class="col-sm-2">
                          <select id="cboNetwork" class="form-control"></select>
                        </div>
                   </div>
                       
                   <div class="row">
                        <div title="Enter numbers to unsubscribe. Separate Multiple Numbers By Commas." class="col-sm-6 col-sm-offset-1">
                        	<textarea placeholder="Enter Numbers To Unsubscribe (Separate By Commas)" rows="10" id="txtNumbers" class="form-control"></textarea>
                        </div>
                          
                          
                       <!--buttons-->                      
                        <div class="col-md-2">
                            <button id="btnRemove" style="width:160px; font-weight:bold;" type="button" title="Unsubscribe User(s)" class="btn btn-danger center-block">
                                <i class="fa fa-times-circle"></i>&nbsp;Unsubscribe User
                       		</button>&nbsp;
                              
                      	<button style="width:160px; font-weight:bold;" id="btnRefresh" type="button" class="btn btn-warning center-block" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Refresh
                      	</button>
                        </div>  
                </div>
                
                   <br>             
                <!--Verify-->
                 <div class="row" title="Select Network">
                    <label class="col-sm-1 control-label" for="txtVerify">Verify Numbers:</label>
                
                    <div class="col-sm-6">
                      <textarea style="height:100px;" placeholder="Enter Numbers To Verify (Separate By Commas)" id="txtVerify" class="form-control"></textarea>
                    </div>
                    
                    <!--Verify Result-->   
                   <div class="col-md-5">                          
                      <div style="background:#F8F8D0; color:#aa0000; height:100px;" id="divVerify" align="left"></div>
                   </div>
               </div> 
               
               <!--Verify Button-->
                 <div class="row" title="Verify If Number(s) Exist In Subscription Table Or Not">
                    <div class="col-sm-2 col-sm-offset-1">
                      <button id="btnVerify" style="width:140px; font-weight:bold;" type="button" class="btn btn-info center-block">
                        <i class="glyphicon glyphicon-ok-sign"></i>&nbsp;Verify Numbers
                      </button>
                    </div>
               </div>
                    
                               
              <div align="center">
                 <div id = "divAlert"></div>
              </div>                           
              </div>
          </div>
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
    <!--<script src="<?php# echo base_url();?>js/raphael-min.js"></script>-->
  	 <!--<script src="<?php# #echo base_url();?>js/morris.min.js"></script>-->
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
