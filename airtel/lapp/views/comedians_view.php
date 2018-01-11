<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
   <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Comedians</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'UA-109268177-2');
    </script>

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
		
		var Title='<font color="#AF4442">Comedians Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,editdata,seldata;
		
		function GetFile(input,SelectedFile)
		{
			try
			{
				var img;
				
				logo_pix=null;
				
				if ($.trim(SelectedFile)=='Logo')
				{					
					if (input.files && input.files[0]) logo_pix=input.files[0];
					
					if (logo_pix != null)
					{
						//check whether browser fully supports all File API
						if (window.File && window.FileReader && window.FileList && window.Blob) 
						{
							var fr = new FileReader;
							fr.onload = function() { // file is loaded
								img = new Image;
								img.onload = function() 
								{ // image is loaded; sizes are available
									if (this.width < 200)
									{
										m="The comedian picture width must be at least 200 pixels.";
										bootstrap_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } }
										});
										
										$('#txtLogo').val(''); 
										$('#imgLogo').prop('src',emptypix);
										
										return false;
									}
								};
								
								img.src = fr.result; // is the data URL because called with readAsDataURL
							};
							
							fr.readAsDataURL(input.files[0]);
						}else
						{
							m="Please upgrade your browser, because your current browser lacks some new features we need!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
							
							return false;
						}
						
						var s=logo_pix.name.split('.'); var ext=$.trim(s[s.length-1]);
						
						if (((ext.toLowerCase()!='jpg') && (ext.toLowerCase()!='jpeg')) )
						{
							if (ext.toLowerCase()!='png') 
							{
								m="Invalid comedian picture file format. JPEG or PNG files are allowed.";
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
								
								$('#txtLogo').val(''); 
								$('#imgLogo').prop('src',emptypix);
								return false;	
							}
						}
							
						var reader = new FileReader();
						 reader.onload = function(e){
						   $('#imgLogo').attr('src', e.target.result);
						 }
						 
						 reader.readAsDataURL(input.files[0]);
					}
				}
			}catch(e)
			{
				m='GETFILE ERROR:\n'+e;
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});
			}
		} //End GetFile
		
		var logo_pix=null;
		var emptypix='data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==';
		var nophoto='<?php echo base_url();?>images/nophoto.jpg';
					
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			$('#imgLogo').prop('src',emptypix);
			
			table = $('#recorddisplay').DataTable( {
					select: true,
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					autoWidth:false,
					destroy:true,
					lengthMenu: [ [ 10, 25, 50, 100,-1 ],[ '10', '25', '50', '100', 'All' ] ],
					language: {zeroRecords: "No Comedian Record Found"},
					columnDefs: [ 
						{
							"targets": [ 0,1,2,3,4 ],
							"visible": true,
						},
						{
							"targets": [ 5,6,7 ],
							"visible": false,
						},
						{
							"targets": [ 1 ],
							"orderable": true
						},
						{
							"targets": [ 1 ],
							"searchable": true
						},
						{
							"targets": [ 0,2,3,4,5,6,7 ],
							"orderable": false,
							"searchable": false
						},
						{ className: "dt-center", "targets": [ 0,1,3,4,5,6,7 ] },
						{ className: "dt-left", "targets": [ 2 ] }
					],
					columns: [
						{ width: "5%" },//Select
						{ width: "15%" },//Comedian
						{ width: "60%" },//Details
						{ width: "10%" },//Pictures
						{ width: "10%" },//Status
						{ width: "0%" },//Record ID
						{ width: "0%" },//Status
						{ width: "0%" }//pix
					],
					order: [[ 0, 'asc' ]],
					ajax: {
					  	url: '<?php echo site_url('Comedians/LoadComediansJson'); ?>',
						type: 'POST',
						dataType: 'json'
				   }
				} );
			
			// Add event listener for opening and closing details
			$('#recorddisplay tbody').on('click', 'td', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
				editdata = row.data();
				
				var colIndex = $(this).index();
				
				if (colIndex==0) SelectRow(editdata);
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{							
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					document.getElementById('btnDelete').disabled=false;
					document.getElementById('btnEdit').disabled=false;
					document.getElementById('btnAdd').disabled=true;
								
					//alert('Select');
					//Get Selected Value
					var val=table.row( this ).data();
					seldata=val;
					var cm=val[1],dt=val[2],pix=val[7],sta=val[4],id=val[5],status=val[6];
					
					$('#txtComedian').val(cm);
					$('#txtDetails').val(dt);
					$('#cboStatus').val(status);
					
					if (pix)
					{
						$('#imgLogo').prop('src','<?php echo base_url();?>comedian_pix/'+pix);
					}else
					{
						$('#imgLogo').prop('src',emptypix);
						$('#txtLogo').val('');
					}
					
					activateTab('tabData');
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
			
			$('#btnDelete').click( function () 
			{
				try
				{
					var cm='',id='';
					cm=seldata[1]; id=seldata[3];
					
					//Validate 
					if ($.trim(id)=='')
					{
						m='Please select a comedian from the table before clicking on "DELETE" button.';
						
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
								
						$('#txtComedian').focus(); return false;
					}else
					{
						if (!confirm('Are you sure you want to delete the comedian "'+cm.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Comedian. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id: id};
							
							$.ajax({
								url: '<?php echo site_url('Comedians/DeleteComedian'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {
									$.unblockUI();
									
									var ret=$.trim(data);
									
									if (ret.toUpperCase()=='OK')
									{
										table.ajax.reload( function ( json ) {
											m='Comedian "'+ct.toUpperCase()+'" Was Successfully Deleted!';
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
										} );
									
										//Clear boxes
										ResetControls();
									}else
									{
										
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
					m='Delete Comedian Button Click ERROR:\n'+e;
					
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Comedian. Please Wait...</p>',theme: true,baseZ: 2000});
					
					var ocm=seldata[1],id=seldata[5];
					var cm=$.trim($('#txtComedian').val());
					var dt=$.trim($('#txtDetails').val()).replace(new RegExp("'", "g"), "`").replace(new RegExp('"', "g"), "`");
					var sta=$('#cboStatus').val();
					
					if (logo_pix==null) logo_pix='';
									
					//Initiate POST
					var uri = "<?php echo site_url('Comedians/EditComedian');?>";
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
								table.ajax.reload( function ( json ) {
									m='Comedian <b>'+ocm.toUpperCase()+'</b> Was Successfully Edited!';
									
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
									
									//Clear boxes
									ResetControls();
								} );
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
				
					if (logo_pix != null) fd.append('pix', logo_pix);			

					fd.append('comedian', cm);			
					fd.append('details',dt);
					fd.append('id',id);					
					fd.append('comedian_status', sta);

					xhr.send(fd);// Initiate a multipart/form-data upload		

				}catch(e)
				{
					$.unblockUI();
					var m='Edit Comedian Button Click ERROR:\n'+e;
				   
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Comedian. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var cm=$.trim($('#txtComedian').val());
					var dt=$.trim($('#txtDetails').val());
					var sta=$('#cboStatus').val();
					
					if (logo_pix==null) logo_pix='';
					
					//Initiate POST
					var uri = "<?php echo site_url('Comedians/AddComedian');?>";
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
								table.ajax.reload( function ( json ) {
									m='Comedian "'+cm.toUpperCase()+'" Was Added Successfully!';
									
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
									
									//Clear boxes
									ResetControls();
								} );
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
				
					if (logo_pix != null) fd.append('pix', logo_pix);			

					fd.append('comedian', cm);			
					fd.append('details',dt);					
					fd.append('comedian_status', sta);

					xhr.send(fd);// Initiate a multipart/form-data upload
									
				}catch(e)
				{
					$.unblockUI();
					var m='Add Comedian Button Click ERROR:\n'+e;
				   
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
					 var c=$.trim($('#txtComedian').val());
					 var d=$.trim($('#txtDetails').val());
					 var sta=$('#cboStatus').val();
					 var ct='',id='';
					 
					 if (seldata)
					 {
						ct=seldata[1];
						id=seldata[3];
						
						if ($.trim(ct)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a comedian before clicking on "EDIT" button.';
							}else
							{
								m='Please select a comedian before clicking on "DELETE" button.';
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
							
							$('#txtComedian').focus(); return false;
						}
					 }
					
					//Validate 
					if (c=='')
					{
						m="Please enter a comedian name.";
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
						
						activateTab('tabData');
						
						$('#txtComedian').focus(); return false;
					}
					
					if ($.isNumeric(c))
					{
						m="Comedian name field must not be a number.";
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
						
						activateTab('tabData');
						
						$('#txtComedian').focus(); return false;
					}
					
					if (c.length < 2)
					{
						m="Please enter a meaningful comedian name.";
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
						
						activateTab('tabData');
						
						$('#txtComedian').focus(); return false;
					}		
					
					
					if (d)
					{
						if ($.isNumeric(d))
						{
							m="Comedian details field must not be a number.";
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
							
							activateTab('tabData');
							
							$('#txtDetails').focus(); return false;
						}
						
						if (d.length < 3)
						{
							m="Please enter a meaningful comedian details.";
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
							
							activateTab('tabData');
							
							$('#txtDetails').focus(); return false;
						}	
					}
					
					//Status
					if (!sta)
					{
						m="Please select comedian status.";
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
						
						activateTab('tabData');
						
						$('#cboStatus').focus(); return false;
					}
						
					if (!confirm('Are you sure you want to '+fn+' this comedian record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var cm=dat[1],dt=dat[2],pix=dat[7],sta=dat[4],id=dat[5],status=dat[6];
					
				$('#txtComedian').val(cm);
				$('#txtDetails').val(dt);
				$('#cboStatus').val(status);
				
				if (pix)
				{
					$('#imgLogo').prop('src','<?php echo base_url();?>comedian_pix/'+pix);
				}else
				{
					$('#imgLogo').prop('src',emptypix);
					$('#txtLogo').val('');
				}
				
				document.getElementById('btnDelete').disabled=false;
				document.getElementById('btnEdit').disabled=false;
				document.getElementById('btnAdd').disabled=true;
				
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
				$('#txtComedian').val('');
				$('#txtDetails').val('');
				$('#cboStatus').val('');
				
				$('#imgLogo').prop('src',emptypix);								
				$('#txtLogo').val('');
				
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
              <div class="panel-heading size-20"><i class="fa fa-street-view"></i> Comedians</div>
                <div class="panel-body">    
                	<!--Tab-->
                        <ul class="nav nav-tabs " style="font-weight:bold;">
                          <li  role="presentation" class="active"><a data-toggle="tab" href="#tabData"><i class="glyphicon glyphicon-list-alt"></i> Comedian Details</a></li>
                          <li role="presentation"><a data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Comedians</a></li>
                        </ul>
                        <!--Tab Ends-->
                        
                         <div class="tab-content">
                         	<div id="tabData" class="row tab-pane fade in active ">
                    			 
                                 
                                 <div align="center" id="txtInfo" style="font-weight:bold; font-style:italic; color: #BBBBBB; margin-top:10px; margin-bottom:10px; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                                 
                                 <form class="form-horizontal"> 
                                    <div class="form-group">
                                      <label for="txtComedian" class="col-sm-2 control-label " title="Comedian">Comedian<span class="redtext">*</span></label>
                    
                                      <div class="col-sm-6" title="Comedian">
                                         <input style="text-transform:none;" type="text" class="form-control" id="txtComedian" placeholder="Enter Comedian">
                                      </div>
                                    </div>
                                    
                                    <div class="form-group">
                                      <!--Details-->
                                      <label for="txtDetails" class="col-sm-2 control-label" title="Comedian Details">Comedian Details</label>
                    
                                      <div class="col-sm-9" title="Comedian Details" > 
                                         <textarea rows="10" style="text-transform:none; " type="text" class="form-control" id="txtDetails" placeholder="Enter Comedian Details"></textarea>
                                      </div>
                                    </div>
                                    
                                    <div class="form-group" title="Comedian Picture">
                                      <!--Picture-->
                                      <label for="txtPix" class="col-sm-2 control-label">Comedian Picture</label>
                    
                                      	<div class="col-sm-5" title="Company Logo." style="border:dashed thin;">
                                        <img src="" id="imgLogo" style="border:1; border-style:solid; background-color:#FFF;" width="100px" /><p></p>
                                        <input id="txtLogo" name="txtLogo" type="file" accept="image/jpeg,image/png" onchange="GetFile(this,'Logo');" style="max-height:100px;">
                                      </div>
                                      
                                      <!--Status-->
                                      <label for="cboStatus" class="col-sm-2 control-label" title="Comedian Status">Comedian Status</label>
                    
                                      <div class="col-sm-2" title="Comedian Details"> 
                                         <select class="form-control" id="cboStatus">
                                         	<option value="">[SELECT]</option>
                                            <option value="0">Not Active</option>
                                            <option value="1">Active</option>
                                         </select>
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
                            
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="width:120px;  margin-left:10px;" >
                                <span class="ui-button-text">Refresh</span>
                            </button>
                            
                            <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:120px; margin-left:10px; ">
                                <span class="ui-button-text">Delete Record</span>
                            </button>
                        </div>
                        
                      
                        
                    </div>
                    </center>
                    
                    </form>   
                     		<br>
                           
                        </div><!--End Of Tab Content 1-->
                        
                        <div id="tabReport" class="tab-pane fade">
                 		   <center>
                            <div class="table-responsive" style="margin-top:20px; ">
                            <table align="center" id="recorddisplay" cellspacing="0" title="Comedians Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                                  <thead style="color:#ffffff; background-color:#7E7B7B;">
                                    <tr>
                                        <th>SELECT</th>
                                        <th>COMEDIAN</th>
                                        <th>COMEDIAN DETAIL</th>
                                        <th>PICTURE</th>
                                        <th>STATUS</th>
                                        <th class="hide">RECORD ID</th>
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
