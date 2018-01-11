<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Natural Health Tip</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Natural Health Tip Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
	
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
		
		var table,editdata,seldata;
					
    	$(document).ready(function(e) {
			$(function() {			
				$.blockUI.defaults.css = {};// clear out plugin default styling
			});
		
			$(document).ajaxStop($.unblockUI);
					
			table = $('#recorddisplay').DataTable( {
					 select: true,
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					autoWidth:false,
					language: {zeroRecords: "No Health Message Record Found"},
					lengthMenu: [
										[ 10, 25, 50, 100,-1 ],
										[ '10', '25', '50', '100', 'All' ]
									],
					columns: [
						{ width: "8%" },//SN
						{ width: "65%" },//Message
						{ width: "15%" },//Msg ID
						{ width: "12%" }//Msg Status
					],
					columnDefs: [ 
						{
							"targets": [ 0,1,2,3 ],//Record ID
							"visible": true
						},
						{
							"targets": [ 1,2,3 ],
							"searchable": true
						},
						{
							"targets": [ 0 ],//SN
							"searchable": false
						},
						{
							orderable: true,
							className: 'select-checkbox',
							targets:   0
						},
						{ className: "dt-center", "targets": [ 0,1,2,3 ] }
					],
								
					order: [[ 0, 'asc' ]],
					ajax: '<?php echo site_url('Healthmsg/LoadMsgJson'); ?>'
				} );
				
			table.on( 'order.dt search.dt', function () {
					table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
						cell.innerHTML = i+1;
					} );
				} ).draw();
				
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=false;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=true;
								
					//alert('Select');
					//Get Selected Value
					var val=table.row( this ).data();
					seldata=val;
					var g=val[1],tid=val[2], sta=val[3];
					
					if (sta=='Active') sta='1'; else sta='0';
					
					$('#txtMsg').val(g); $('#txtID').val(tid); $('#cboStatus').val(sta);
					
					activateTab('tabActive');
				}
				else {
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					$('#txtMsg').val(''); $('#txtID').val(''); $('#cboStatus').val('');
					
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
			} );
					
			
			$('#btnDelete').click( function () 
			{
				try
				{
					var g='',id='';
					g=seldata[1]; id=seldata[2], sta=seldata[3];
					
					//Validate 
					if ($.trim(id)=='')
					{
						m='Please select a message from the table before clicking on "DELETE" button.';
						
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
								
						$('#txtMsg').focus(); return false;
					}else
					{
						if (!confirm('Are you sure you want to delete the health message "'+g.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Health Message. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id: id};
							
							$.ajax({
								url: '<?php echo site_url('Healthmsg/DeleteMsg'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'json',
								complete: function(xhr, textStatus) {
									table.ajax.reload( function ( json ) {
										m='The Health Message "'+g.toUpperCase()+'" Was Successfully Deleted!';
										bootstrap_Success_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close!", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									} );
		
									$.unblockUI();
								},
								success: function(data,status,xhr) {				
									//Clear boxes
									$("#txtMsg").val(''); $("#txtID").val(''); $("#cboStatus").val('');
									if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
									if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
									if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
								},
								error:  function(xhr,status,error) {
									bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
									bootbox.alert({ 
										size: 'small', message: 'Error '+ xhr.status + ' Occurred: ' + error, title:Title,
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
					m='Delete Health Message Button Click ERROR:\n'+e;
					
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
			} );	
			
			//Edit record
			$('#btnEdit').click(function(e){//EDIT
				try
				{
					if (!checkForm('EDIT')) return false;
					
					$('#divAlert').html('');
					
					var g='',id='';
					g=seldata[1]; id=seldata[2], tsta=seldata[3];
										
					var tg=$.trim($('#txtMsg').val());
					var tid=$.trim($('#txtID').val());
					var tsta=$.trim($('#cboStatus').val());
					
					/*tg=tg.replace(new RegExp('&', 'g'), '&amp;');
					tg=tg.replace(new RegExp('"', 'g'), '&quot;');
					tg=tg.replace(new RegExp("'", 'g'), '&apos;');
					tg=tg.replace(new RegExp('<', 'g'), '&lt;');
					tg=tg.replace(new RegExp('>', 'g'), '&gt;');*/
					
																
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Health Message. Please Wait...</p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request			
					var mydata={msg:tg, msg_id:tid,msg_status:tsta};
					
					$.ajax({
						url: '<?php echo site_url('Healthmsg/EditMsg'); ?>',
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {
							table.ajax.reload( function ( json ) {
								m='"'+g.toUpperCase()+'" Was Successfully Edited!';
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close!", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							} );

							$.unblockUI();
						},
						success: function(data,status,xhr) {				
							//Clear boxes
							$("#txtMsg").val(''); $("#txtID").val(''); $("#cboStatus").val('');
							if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
							if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
							if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
						},
						error:  function(xhr,status,error) {
							bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
							bootbox.alert({ 
								size: 'small', message: 'Error '+ xhr.status + ' Occurred: ' + error, title:Title,
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
					var m='Edit Health Message Button Click ERROR:\n'+e;
				   
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
			});//End click-btnEdit
				
			$('#btnAdd').click(function(e) {
				try
				{
					if (!checkForm('ADD')) return false;
				
					$('#divAlert').html('');
					
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Health Message. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var tg=$.trim($('#txtMsg').val());
					var tid=$.trim($('#txtID').val());
					var sta=$.trim($('#cboStatus').val());
					
					/*tg=tg.replace(new RegExp('&', 'g'), '&amp;');
					tg=tg.replace(new RegExp('"', 'g'), '&quot;');
					tg=tg.replace(new RegExp("'", 'g'), '&apos;');
					tg=tg.replace(new RegExp('<', 'g'), '&lt;');
					tg=tg.replace(new RegExp('>', 'g'), '&gt;');*/
						
					var mydata={msg:tg, msg_id:tid,msg_status:sta};
					
					$.ajax({
						url: "<?php echo site_url('Healthmsg/AddMsg'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {
							$.unblockUI();
						},
						success: function(data,status,xhr) {
							$.unblockUI();
							
							if ($(data).length > 0)
							{
								$.each($(data), function(i,e)
								{
									if (e.Status=='OK')
									{
										//Clear boxes
										$("#txtMsg").val(''); $("#txtID").val('');  $("#cboStatus").val('');
										
										table.ajax.reload();
										
										bootstrap_Success_alert.warning(e.Msg);
										bootbox.alert({ 
											size: 'small', message: e.Msg, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									}else
									{
										bootstrap_alert.warning(e.Msg);
										bootbox.alert({ 
											size: 'small', message: e.Msg, title:Title,
											buttons: { ok: { label: "Close!", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									}
								});
							}			
							
						},
						error:  function(xhr,status,error) {
							$.unblockUI();
							bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
							bootbox.alert({ 
								size: 'small', message: 'Error '+ xhr.status + ' Occurred: ' + error, title:Title,
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
					var m='Add Health Message Button Click ERROR:\n'+e;
				   
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
			});//btnAdd.click
			
			$('#btnMsgID').click(function(e) 
			 {
				 try
				{
					$('#txtID').val('');
				
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Generating Message ID. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						url: "<?php echo site_url('Healthmsg/GetMsgID');?>",
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							$.unblockUI();
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							$('#txtID').val(data);		
						},
						error:  function(xhr,status,error) {
								$.unblockUI;
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
					
					$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					
					m='Generate Tag ID Button Click ERROR:\n'+e;
					
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
            });//btnMsgID Ends
			
			function checkForm(fn)
			{
				 try
				 {
					 var d=$.trim($('#txtMsg').val());
					 var tid=$.trim($('#txtID').val());
					 var sta=$.trim($('#cboStatus').val());
					 
					 var g='',id='';
					 
					 if (seldata)
					 {
						g=seldata[1];
						id=seldata[2];
						
						if ($.trim(g)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a message before clicking on "EDIT" button.';
							}else
							{
								m='Please select a message before clicking on "DELETE" button.';
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
							
							$('#txtMsg').focus(); return false;
						}
					 }
					
					
					//Validate 
					
					//Validate 
					if (d=='')
					{
						m="Please enter message.";
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
						
						$('#txtMsg').focus(); return false;
					}
							
					if ($.isNumeric(d))
					{
						m="Message must not be a number.";
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
						
						$('#txtMsg').focus(); return false;
					}
					
					if (tid=='')
					{
						m="Message id field must not be blank. Click on 'GENERATE MESSAGE ID' button to generate an id for the message.";
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
						
						$('#btnMsgID').focus(); return false;
					}
					
					//Status
					if (!sta)
					{
						m="Please select message status.";
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
						
						$('#cboStatus').focus(); return false;
					}
							
					if (!confirm('Are you sure you want to '+fn+' this health message record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
						buttons: { ok: { label: "Close!", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
					
					return false;
				 }
			 }//End CheckForm
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
                  <span class="glyphicon glyphicon-user"></span> <span id="spnUserFullname" class="hidden-xs"><?php echo $UserFullName.' ('.$username.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Username">
                    <p><b>Username:</b> <?php echo '<span class="yellowtext">'.$username.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="User FullName">
                    <p><b>Full Name:</b> <span id="spnUserFullname1"><?php echo '<span class="yellowtext">'.$UserFullName.'</span>'; ?></span></p>
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
          	  <!-- Default panel contents -->
              <div class="panel-heading"><i class="material-icons">message</i> Natural Health Tip</div>
              <div class="panel-body">
              	<!--Tab-->
                <ul class="nav nav-tabs " style="font-weight:bold;">
                  <li class="active"><a id="idActive" data-toggle="tab" href="#tabActive"><i class="glyphicon glyphicon-list-alt"></i> Natural Health Tip Details</a></li>
                  <li><a id="idRSS" data-toggle="tab" href="#tabRSS"><i class="fa fa-eye"></i> View Created LaffHub</a></li>
                </ul>
    			<!--Tab Ends-->
                
                <!--Tab Details-->
				<div class="tab-content">
                	<div id="tabActive" class="row tab-pane fade in active "><!--Active Feed Tab-->
                    	<br>
                        <form class="form-horizontal"> 
                	<div class="form-group" title="Natural Health Tip">
                      <label for="txtMsg" class="col-sm-2 control-label ">Health Tip<span class="redtext">*</span></label>
    
                      <div class="col-sm-7">
                         <textarea style="text-transform:none;" class="form-control" id="txtMsg" placeholder="Enter Natural Health Tip" required rows="5"></textarea>
                      </div>
                    </div>
                    
                    <div class="form-group" title="Natural Health Tip ID">
                      <label for="txtID" class="col-sm-2 control-label ">Health Tip ID<span class="redtext">*</span></label>
    
                      <div class="col-sm-3">
                         <input readonly style="text-transform:none;" type="text" class="form-control" id="txtID" placeholder="Generate Natural Health Tip ID" required>
                          </div>
                      
                       <div class="col-sm-3">
                          <button title="Generate Health Tip ID" id="btnMsgID" type="button" class="btn btn-warning makebold" role="button" style="width:170px; " >
                            <span class="ui-button-text">Generate Health Tip ID</span>
                        </button>
                    	</div>
                    </div>
                    
                    <!--Status-->
                     <div class="form-group" title="Natural Health Tip Status">
                      <label for="cboStatus" class="col-sm-2 control-label ">Health Tip Status<span class="redtext">*</span></label>
    
                      <div class="col-sm-3">
                         <select style="text-transform:none;" type="text" class="form-control" id="cboStatus">
                         	<option value="">[SELECT]</option>
                            <option value="0">Disabled</option>
                            <option value="1">Active</option>
                         </select>
                     </div>
                    </div>
                    
                        
    				<div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
    				
                    </form>
                    </div><!--End Active Feed Tab-->
                    
                    <div id="tabRSS" class="row tab-pane fade"><!--RSS Feed Tab-->
                    	 <center>
                        <div class="table-responsive" style="margin-top:5px;">
                        <table id="recorddisplay" border="1" cellspacing="0" class="display table table-bordered table-hover table-striped" width="100%">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>
                                    <th>SN</th>                                    
                                    <th>Natural&nbsp;Health&nbsp;Tip</th>
                                    <th>Health&nbsp;Tip&nbsp;ID</th>
                                    <th>Health&nbsp;Tip&nbsp;Status</th>
                                </tr>
                              </thead>     
                          </table>
                    	</div>                            
                       </center>
                    </div><!--End Health Message Tab-->
                  </div><!--End Contents-->
                  
                    <div class="form-group" style="margin-bottom:50px;">
                        <div class="col-sm-offset-2 col-sm-7">
                          <?php
                            if ($_SESSION['SetParameters']==1)
                            {
                                echo '
                             <button title="Create Health Message" id="btnAdd" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text"><i class="fa fa-plus-square"></i> Create Message</span>
                            </button>
							
							<button disabled title="Edit Record" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                    <span class="ui-button-text"><i class="fa fa-edit"></i> Edit</span>
                                </button>
								
							<button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                    <span class="ui-button-text"><i class="fa fa-trash"></i> Delete Message</span>
                                </button>
                                ';
                            }
                             ?>
                            
                            
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-primary" role="button" style="width:140px; " >
                                <span class="ui-button-text"><i class="fa fa-refresh"></i> Refresh</span>
                            </button>
                        </div>
                    </div>
              </div><!--End Panel Body-->
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
    
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
    
    <script type='text/javascript' src="<?php echo base_url();?>js/highcharts/highcharts.js"></script>
	<script type='text/javascript' src="<?php echo base_url();?>js/highcharts/exporting.js"></script>
       
     
       
  </body>
</html>
