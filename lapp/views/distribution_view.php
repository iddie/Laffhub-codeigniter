<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Set Video Distribution</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Video Distribution Help</font>';
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
			
			if (document.getElementById('btnSetDistribution')) document.getElementById('btnSetDistribution').disabled=true;
			
			// Add event listener for opening and closing details
			$('#recorddisplay tbody').on('click', 'td', function () 
			{
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
					
					if (document.getElementById('btnSetDistribution')) document.getElementById('btnSetDistribution').disabled=false;
					//Get Selected Value					
					var val=table.row( this ).data();
					seldata=val;
					var id=val[1],nm=val[2],ori=val[3],st=val[4],status=val[5];
					
//[VIEW],DistributionID,DomainName,Origin,State,Status
					
					$('#lblDistributionID').html(id);
					$('#lblDomainName').html(nm);	
					$('#hidStatus').val(status);
					$('#hidState').val(st);	
					$('#lblOrigin').html(ori);
				}
				else 
				{					
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					ResetControls();
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				$(this).toggleClass('selected');
			} );
			
						   
			LoadDistribution();
			
			function LoadDistribution()
			{
				try
				{
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Distributions. Please Wait...</p>',theme: true,baseZ: 2000});
					
					table = $('#recorddisplay').DataTable( {
						select: true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						//processing: "Loading Distribution...",
						language: {
							zeroRecords: "No Distribution Record Or Table Still Be Loading",
							//loadingRecords: "Loading Distribution. Please Wait...",
							emptyTable: "No Distribution Data Available"
							},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4,5 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{
								"targets": [ 0 ],
								"orderable": false,
								"searchable": false
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
						],//[SELECT],DistributionID,DomainName,Origin,State,Status
						columns: [
							{ width: "5%" },//SELECT
							{ width: "20%" },//DISTRIBUTION ID
							{ width: "25%" },//DOMAIN NAME
							{ width: "30%" },//ORIGIN
							{ width: "10%" },//STATE
							{ width: "10%" }//DISTRIBUTION STATUS
						],
						order: [[ 3, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Distribution/LoadDistributions'); ?>',
							type: 'POST',
							complete: function(xhr, textStatus) {
								$.unblockUI();
							},
							dataType: 'json'
					   }
					} );
					
					//$.unblockUI();	
					
					
				}catch(e)
				{
					$.unblockUI();
					m='LoadDistribution Module ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			$('#btnSetDistribution').click(function(e) {
				try
				{
					$('#divAlert').html('');
					
					var did=$('#lblDistributionID').html();
					var dnm=$('#lblDomainName').html();	
					var status=$('#hidStatus').val();
					var st=$('#hidState').val();	
					var ori=$('#lblOrigin').html();
					
					st=st.replace("<font color='#249A47'>",'').replace('</font>','').replace("<font color='#BD1111'>",'');
					status=status.replace("<font color='#249A47'>",'').replace('</font>','').replace("<font color='#BD1111'>",'');					
					//alert(status+'\n'+st); return false;
					
					if (!st || !status)
					{
						m='No distribution record has been selected. Click on the image (magnifying glass) in the first column of the row containting the distribution record to set.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
								
					if (!did || !dnm || !ori)
					{
						m='No distribution record has been selected. Click on the image (magnifying glass) in the first column of the row containting the distribution record to set.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					if ($.trim(status).toLowerCase() != 'deployed')
					{
						m='The status of the selected distribution is "' + status.toUpperCase() + '". Status must be "DEPLOYED" before you can use the distribution. Please contact the system administrator.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					} 
					
					if ($.trim(st).toLowerCase() != 'enabled')
					{
						m='The state of the selected distribution is "'+st.toUpperCase()+'". State must be "ENABLED" before you can use the distribution. Please contact the system administrator.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
										
					//Confirm
					if (!confirm('This action will set selected distribution as the active distribution to use in streaming videos. Do you want to proceed with the action?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={distribution_Id:did,domain_name:dnm,origin:ori};
										
					$.ajax({
						url: "<?php echo site_url('Distribution/Update');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						beforeSend: function() {					
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Creating Job. Please Wait...</p>',theme: true,baseZ: 2000});
							},
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($.trim(data)=='OK')
							{
								$.unblockUI();
																								
								m='Streaming Distribution Was Successfully Set!';
																
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback: function() {
  										window.location.reload(true);
									}
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
					});
					
				}catch(e)
				{
					$.unblockUI();
					m='Create Distribution Button Click ERROR:\n'+e;
					
					bootstrap_Distribution_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			});
			
        });//End document ready
		
		function ResetControls()
		{
			try
			{					
				if (document.getElementById('btnSetDistribution')) document.getElementById('btnSetDistribution').disabled=true;
				
				$('#lblDistributionID').html('');
				$('#lblDomainName').html('');	
				$('#hidStatus').val('');
				$('#hidState').val('');	
				$('#lblOrigin').html('');
			}catch(e)
			{
				$.unblockUI();
				m="ResetControls ERROR:\n"+e;
				bootstrap_AddTitle_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});
			}
		}//End ResetControls
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var id=dat[1],nm=dat[2],ori=dat[3],st=dat[4],status=dat[5];
					
//[View],Category,Title,Description,Filename,Status,
				$('#lblDistributionID').html(id);
				$('#lblDomainName').html(nm);	
				$('#hidStatus').val(status);
				$('#hidState').val(st);	
				$('#lblOrigin').html(ori);
			}else
			{
				ResetControls();
			}
		}
		
		function GetRow(sn)
		{
			//alert(table.rows( '.selected' ).count());
			ResetControls();
			
			if (sn>-1)
			{
				var row = table.row( sn ).data();
				
				if (row)
				{
					var id=row[1],nm=row[2],ori=row[3],st=row[4],status=row[5];
						
	//[VIEW],DistributionID,DomainName,Origin,State,Status
					
					if (table.rows( '.selected' ).count() == 0)
					{
						$('#lblDistributionID').html(id);
						$('#lblDomainName').html(nm);	
						$('#hidStatus').val(status);
						$('#hidState').val(st);	
						$('#lblOrigin').html(ori);
							
						if (document.getElementById('btnSetDistribution')) document.getElementById('btnSetDistribution').disabled=false;
					}
				}
			}			
		}
		
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
              <div class="panel-heading"><i class="fa fa-random"></i>&nbsp;Set&nbsp;Video&nbsp;Distribution</div>
              <div class="panel-body">
                          
                            
              	<form class="form-horizontal"> 
               	    	<!--Distribution ID/Domain Name-->                                    
                        <div class="form-group" title="Distribution ID">
                      <label for="lblDistributionID" class="col-sm-2 control-label ">Distribution ID</label>
    
                      <div class="col-sm-3">
                         <label style="font-weight:normal;" class="form-control" id="lblDistributionID"><?php echo $distribution_Id; ?></label>
                      </div>
                      
                      <!-- Domain Name-->
                      <label for="lblDomainName" class="col-sm-3 control-label" title="Domain Name">Domain Name</label>
        
                      <div class="col-sm-4" title="Domain Name">
                         <label class="form-control" id="lblDomainName" style="font-weight:normal;"><?php echo $domain_name; ?></label>
                         
                         <input id="hidState" type="hidden">
                         <input id="hidStatus" type="hidden">
                      </div>
                    </div> 
                                          
                     <!--Distribution Origin-->
                     <div class="form-group">
                     	<!--Distribution Origin-->
                      <label for="lblOrigin" class="col-sm-2 control-label " title="Default Network">Distribution Origin</label>
    
                      <div class="col-sm-5">
                         <label class="form-control" id="lblOrigin" style="font-weight:normal;"><?php echo $origin; ?></label>
                      </div>
                      
                       <!--Buttons-->
                         <span style="float:right; margin-right:20px;">
                        <button style="width:180px" id="btnSetDistribution" type="button" class="btn btn-primary btn-flat size-18"><i class="fa fa-wrench"></i> Set Distribution</button>
                       
                          <button onClick="window.location.reload(true);" style="width:180px; margin-left:20px;" id="btnRefresh" type="button" class="btn btn-warning btn-flat size-18"><i class="fa fa-refresh"></i> Refresh</button>
                          
                         </span>
                    </div> 
                                                            
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="AWS Distributions" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th></th>
                                <th>DISTRIBUTION&nbsp;ID</th>
                                <th>DOMAIN&nbsp;NAME</th>
                                <th>ORIGIN</th>
                                <th>STATE</th>
                                <th>STATUS</th> 
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
