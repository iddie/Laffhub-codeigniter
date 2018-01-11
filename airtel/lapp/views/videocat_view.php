<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
   <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Video Categries</title>
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
		
		var Title='<font color="#AF4442">Video Category Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,editdata,seldata;
		
		var logo_pix=null;
		var emptypix='data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==';
		
		function GetFile(input,SelectedFile)
		{
			try
			{
				var img;
				
				if ($.trim(SelectedFile)=='Logo')
				{
					logo_pix=null;
					
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
										m="The company logo width must be at least 200 pixels.";
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
								m="Invalid Company Logo File Format. JPEG or PNG Files Are Allowed.";
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
						
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			table = $('#recorddisplay').DataTable( {
					select: true,
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					language: {zeroRecords: "No Record Found"},
					columnDefs: [ 
						{
							"targets": [ 0,1,2,3 ],
							"visible": true,
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
							"targets": [ 0,2,3 ],
							"orderable": false,
							"searchable": false
						},
						{ className: "dt-center", "targets": [ 0,1,2,3 ] }
					],
					columns: [
						{ width: "10%" },//Select
						{ width: "60%" },//Category
						{ width: "30%" },//Pix
						{ width: "0%" }//Record ID
					],
					order: [[ 1, 'asc' ]],
					ajax: {
					  	url: '<?php echo site_url('Videocat/LoadVideoCategoriesJson'); ?>',
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
					
					if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=false;
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
					if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=true;
								
					//alert('Select');
					//Get Selected Value
					var val=table.row( this ).data();
					seldata=val;
					var cn=val[1],pix=val[2],id=val[3];;
					
					$('#txtCategory').val(cn);
					
					//if (logo_pix != null) fd.append('logo_pix', logo_pix);	
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
					var ct='',id='';
					ct=seldata[1]; id=seldata[2];
					
					//Validate 
					if ($.trim(id)=='')
					{
						m='Please select a video category from the table before clicking on "DELETE" button.';
						
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
								
						$('#txtCategory').focus(); return false;
					}else
					{
						if (!confirm('Are you sure you want to delete the video category "'+ct.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Video Category. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id: id};
							
							$.ajax({
								url: '<?php echo site_url('Videocat/DeleteCategory'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {
									$.unblockUI();
									
									var ret=$.trim(data);
									
									if (ret.toUpperCase()=='OK')
									{
										table.ajax.reload( function ( json ) {
											m='Video Category "'+ct.toUpperCase()+'" Was Successfully Deleted!';
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
					m='Delete Video Category Button Click ERROR:\n'+e;
					
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
					
					var cn=seldata[1],px=seldata[2],id=seldata[3];
										
					var ds=$.trim($('#txtCategory').val());
					
					if (logo_pix==null) logo_pix='';
												
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Video Category. Please Wait...</p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request			
					var mydata={category:ds, id:id};
					
					$.ajax({
						url: '<?php echo site_url('Videocat/EditCategory'); ?>',
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								table.ajax.reload( function ( json ) {
									m='Video Category "'+cn.toUpperCase()+'" Was Successfully Edited!';
									
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
					var m='Edit Video Category Button Click ERROR:\n'+e;
				   
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
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Video Category. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var g=$.trim($('#txtCategory').val());
						
					var mydata={category:g};
					
					$.ajax({
						url: "<?php echo site_url('Videocat/AddCategory'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						success: function(data,status,xhr) {
							$.unblockUI();
									
							var ret=$.trim(data);
							
							if (ret.toUpperCase()=='OK')
							{
								m='Video Category "'+g.toUpperCase()+'" Was Inserted Successfully!';
								
								$("#txtCategory").val('');
								
								table.ajax.reload();
								
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
					var m='Add Video Category Button Click ERROR:\n'+e;
				   
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
					 var d=$.trim($('#txtCategory').val());
					 var ct='',id='';
					 
					 if (seldata)
					 {
						ct=seldata[1];
						id=seldata[2];
						
						if ($.trim(ct)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select a video category before clicking on "EDIT" button.';
							}else
							{
								m='Please select a video category before clicking on "DELETE" button.';
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
							
							$('#txtCategory').focus(); return false;
						}
					 }
					
					//Validate 
					if (d=='')
					{
						m="Please enter a video category.";
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
						
						$('#txtCategory').focus(); return false;
					}
							
					if ($.isNumeric(d))
					{
						m="Video category field must not be a number.";
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
						
						$('#txtCategory').focus(); return false;
					}
							
					if (!confirm('Are you sure you want to '+fn+' this video category record (Click "OK" to proceed or "CANCEL") to abort)?'))
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
        });//End document ready
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var cn=dat[1],id=dat[2];
				
				$('#txtCategory').val(cn);
			}else
			{
				ResetControls();
			}
		}
		
		function ResetControls()
		{
			try
			{
				$('#txtCategory').val('');
				
				$('#imgLogo').prop('src',emptypix);								
				$('#txtLogo').val('');
							
				if (companylogo)
				{
					$('#imgLogo').prop('src','<?php echo base_url(); ?>images/'+companylogo);								
					$('#txtLogo').val('');
				}
				
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
              <div class="panel-heading size-20"><i class="glyphicon glyphicon-subtitles"></i> Video Categories</div>
                <div class="panel-body">                
              		 <p>
                            <div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                       </p>
                     
                    <form class="form-horizontal"> 
                		<div class="form-group" title="Video Category">
                      		<label for="txtCategory" class="col-sm-2 control-label ">Video Category<span class="redtext">*</span></label>
    
                          <div class="col-sm-7">
                             <input style="text-transform:none;" type="text" class="form-control" id="txtCategory" placeholder="Enter Video Category" required>
                             <i class="fa fa-flag form-control-feedback size-20"  style="margin-right:12px;"></i>
                          </div>
                    	</div>
                       
                        <!--Pix-->
                        <div class="form-group" title="Video Category Picture">                        	
                            <label class="col-sm-2 control-label left" for="txtLogo">Category Picture</label>
                              <div align="center" class="col-sm-7" title="Category Picture." style="border:dashed thin;">
                                <img src="" id="imgLogo" style="border:1; border-style:solid; background-color:#FFF;" width="100px" /><p></p>
                                <input id="txtLogo" name="txtLogo" type="file" accept="image/jpeg,image/png" onchange="GetFile(this,'Logo');" style="max-height:100px;">
                              </div>
                        </div>
                    
                        
    				<div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
    				<center>
                    <div class="form-group" style="margin-bottom:30px;">
                        <div class="col-sm-offset-2 col-sm-7">
                         	<button title="Add Record" id="btnAdd" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Add</span>
                            </button>
                            
                            <button disabled title="Edit Record" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Edit</span>
                            </button>
                            
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-primary" role="button" style="width:140px; " >
                                <span class="ui-button-text">Refresh</span>
                            </button>
                            
                            <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Delete Record</span>
                            </button>
                        </div>
                        
                      
                        
                    </div>
                    </center>
                    
                    	 <center>
                        <div class="table-responsive" style="margin-top:20px; ">
                        <table align="center" id="recorddisplay" cellspacing="0" title="Video Categories" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>
                                 	<th></th>
                                    <th>VIDEO CATEGORY</th>
                                    <th>CATEGORY PIX</th>
                                    <th class="hide">RECORD ID</th>
                                </tr>
                              </thead>
                                  
                          </table>
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
