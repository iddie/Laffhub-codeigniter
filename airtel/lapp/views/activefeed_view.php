<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Set Active RSS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Set Active RSS Help</font>';
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
					language: {zeroRecords: "No Record Found"},
					columnDefs: [ 
						{
							"targets": [ 0,1,2,3,4,5,6 ],
							"visible": true
						},
						{
							"targets": [ 7,8,9,10 ],
							"visible": false,
							"searchable": false
						},
						{
							"targets": [ 2,3,4,5,6 ],
							"orderable": true,
							"searchable": true
						},
						{
							"targets": [ 0,1 ],
							"orderable": false,
							"searchable": false
						},
						{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6 ] }
					],//[VIEW],KEY,TITLE,DESCRIPTION,SHORT-LINK,LONG-LINK,STATUS,SCHEDULE-ID,FEED-ID,PUBDATE,EXPDATE
					columns: [
						{ width: "5%" },//View
						{ width: "5%" },//Key
						{ width: "30%" },//Title
						{ width: "35%" },//Description
						{ width: "10%" },//Short Link
						{ width: "10%" },//Long Link
						{ width: "5%" },//Status
						{ width: "0%" },//Schedule ID
						{ width: "0%" },//Feed ID
						{ width: "0%" },//Publish Date
						{ width: "0%" }//Expiry Date
					],
					order: [[ 2, 'asc' ]],
					ajax: {
					  	url: '<?php echo site_url('Activefeed/LoadRSSJson'); ?>',
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
					
					//Get Selected Value					
					var val=table.row( this ).data();
					seldata=val;
					var key=val[1],tit=val[2],desc=val[3],slnk=val[4],llnk=val[5],sta=val[6],sch=val[7],fid=val[8];
					var pdt=val[9],edt=val[10];
					
//[VIEW],KEY,TITLE,DESCRIPTION,SHORT-LINK,LONG-LINK,STATUS,SCHEDULE-ID,FEED-ID,pubdate,expiredate
					
					$('#txtVideoKey').val(key);
					$('#txtVideoTitle').val(tit);
					$('#txtDescription').val(desc);	
					$('#txtShortLink').val(slnk);
					$('#txtLongLink').val(llnk);
					$('#txtStatus').val(sta);
					$('#txtScheduleID').val(sch);
					$('#txtFeedID').val(fid);
					$('#txtPublishDate').val(pdt);
					$('#txtExpiryDate').val(edt);
				}
				else 
				{					
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
										
					ResetControls();
				}
			} );
			
			function ResetControls()
			{
				try
				{
					$('#txtVideoKey').val('<?php echo $filename; ?>');
					$('#txtVideoTitle').val('<?php echo $title; ?>');
					$('#txtDescription').val('<?php echo $description; ?>');	
					$('#txtShortLink').val('<?php echo $shortlink; ?>');
					$('#txtLongLink').val('<?php echo $longlink; ?>');
					$('#txtStatus').val('<?php echo $status; ?>');
					$('#txtScheduleID').val('<?php echo $schedule_id; ?>');
					$('#txtFeedID').val('<?php echo $feed_id; ?>');
					$('#txtPublishDate').val('<?php echo $pubdate; ?>');
					$('#txtExpiryDate').val('<?php echo $expiredate; ?>');
				}catch(e)
				{
					$.unblockUI();
					m='ResetControls ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }

					});
					
					return false;
				}
			}
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
			} );
			
			$('#btnSet').click(function(e) {
				try
				{
					if (!CheckForm()) return false;
			
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Setting Active Feed. Please Wait...</p>',theme: true,baseZ: 2000});
										
								
					//Initiate POST					
					$.ajax({
						url: "<?php echo site_url('Activefeed/SetFeed');?>",
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							var ret=$.trim(data);
		
							if (ret.toUpperCase() == 'OK')
							{
								m='Active Feed Was Set Successfully.';
								
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback: function (){
										window.location.reload(true);
									}
								});
							}else
							{
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

					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Set Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });//btnSet Click Ends
			
			function CheckForm()
			{
				try
				{														
					if (!confirm('Do you want to proceed with the setting of the active feed? (Click "OK" to proceed or "CANCEL" to abort)'))
					{
						return false;
					}
					
					return true;
				}catch(e)
				{
					$.unblockUI();
					m='CheckForm ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }

					});
					
					return false;
				}
			}
        });//End document ready
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var key=dat[1],tit=dat[2],desc=dat[3],slnk=dat[4],llnk=dat[5],sta=dat[6],sch=dat[7],fid=dat[8];
				var pdt=dat[9],edt=dat[10];
				
				$('#txtVideoKey').val(key);
				$('#txtVideoTitle').val(tit);
				$('#txtDescription').val(desc);	
				$('#txtShortLink').val(slnk);
				$('#txtLongLink').val(llnk);
				$('#txtStatus').val(sta);
				$('#txtScheduleID').val(sch);
				$('#txtFeedID').val(fid);
				$('#txtPublishDate').val(pdt);
				$('#txtExpiryDate').val(edt);
				
				$('#idRSS').removeClass('active');
				$('#idActive').addClass('active');					
				$('#idActive').trigger('click');
			}else
			{
				ResetControls();
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
              <div class="panel-heading"><i class="fa fa-rss"></i>&nbsp;Set&nbsp;Active&nbsp;RSS&nbsp;Feed</div>
              <div class="panel-body">
              	<!--Tab-->
                <ul class="nav nav-tabs " style="font-weight:bold;">
                  <li  role="presentation" class="active"><a id="idActive" data-toggle="tab" href="#tabActive"><i class="material-icons">select_all</i> Active RSS Feed</a></li>
                  <li role="presentation"><a id="idRSS" data-toggle="tab" href="#tabRSS"><i class="fa fa-eye"></i> View Created RSS Feed</a></li>
                </ul>
    			<!--Tab Ends-->
                
                <!--Tab Details-->
				<div class="tab-content">
                	<div id="tabActive" class="row tab-pane fade in active "><!--Active Feed Tab-->
                    	<br>
                        <form class="form-horizontal"> 
               	    <!--Schedule ID/Feed ID-->                                    
                     <div class="form-group" title="Schedule ID">
                      <label for="txtScheduleID" class="col-sm-2 control-label ">Schedule ID</label>
    
                      <div class="col-sm-2">
                         <input value="<?php echo $schedule_id; ?>" type="text" class="form-control" id="txtScheduleID" placeholder="Schedule ID">
                      </div>
                      
                      <!-- Feed ID-->
                      <label for="txtFeedID" class="col-sm-5 control-label ">Feed ID</label>
        
                      <div class="col-sm-2">
                         <input value="<?php echo $feed_id; ?>" type="text" class="form-control" id="txtFeedID" placeholder="Feed ID">
                      </div>
                      
                      
                    </div>  
                    
                     <!--Video Title/Video Key-->
                     <div class="form-group">
                      <label for="txtVideoTitle" class="col-sm-2 control-label " title="Video Title">Video Title</label>
    
                      <div class="col-sm-6" title="Video Title">
                         <input value="<?php echo $title; ?>" type="text" class="form-control" id="txtVideoTitle" placeholder="Video Title">
                      </div>
                      
                      <!--Video Key-->
                      <label for="txtVideoKey" class="col-sm-1 control-label" title="Video File Name">Video Name</label>
    
                      <div class="col-sm-2" title="Video Key">
                         <input value="<?php echo $filename; ?>" type="text" class="form-control" id="txtVideoKey" placeholder="Video Key">
                      </div>
                    </div> 
                    
                                     
                    <!--Description/Rss Status-->
                    <div class="form-group">
                      <label for="txtDescription" class="col-sm-2 control-label " title="Video Description">Video Description</label>
    
                      <div class="col-sm-6" title="Video Description">
                         <textarea rows="3" class="form-control" id="txtDescription" placeholder="Video Description"><?php echo $description; ?></textarea>
                      </div>
                      
                      <!--Rss Status-->
                        <label class="col-sm-1 control-label left" for="txtStatus" title="RSS Status">Status</label>
                          
                      <div align="center" class="col-sm-2" title="RSS Status">
                       <input value="<?php echo $status; ?>" type="text" class="form-control redtext" id="txtStatus" placeholder="RSS Status">
                      </div>
                    
                    </div>

                    <!--Long Link-->
                    <div class="form-group">
                      <label for="txtLongLink" class="col-sm-2 control-label " title="Long Link (url)">Long Link</label>
    
                      <div class="col-sm-6" title="Long Link">
                         <input value="<?php echo $longlink; ?>" type="text" class="form-control" id="txtLongLink" placeholder="Long Link">
                      </div>
                                            
                      	<!--Short Link-->
                        <label class="col-sm-1 control-label left" for="txtShortLink" title="Short Link">Short URL</label>
                          
                          <div align="center" class="col-sm-2">
                           <input style="padding:4px;" value="<?php echo $shortlink; ?>" type="text" class="form-control" id="txtShortLink" placeholder="Short Link">
                          </div>
                    </div>
                    
                    
                    <!--Rss Status/Publish Date-->
                    <div class="form-group">
                    	<!--Publish Date-->
                        <label class="col-sm-2 control-label left" title="RSS Publish Date" for="txtPublishDate">Publish Date</label>
                          
                          <div align="center" class="col-sm-2" title="RSS Publish Date">
                            <input value="<?php echo $pubdate; ?>" type="text" class="form-control" id="txtPublishDate" placeholder="RSS Publish Date">
                          </div>
                         
                         
                         <!--Expiry Date--> 
                        <label class="col-sm-5 control-label left" for="txtExpiryDate" title="RSS Expiry Date">Expiry Date</label>
                          
                      <div align="center" class="col-sm-2" title="RSS Expiry Date">
                       <input value="<?php echo $expiredate; ?>" type="text" class="form-control" id="txtExpiryDate" placeholder="RSS Expiry Date">
                      </div>
                    </div>
                                        
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
              <!-- /.box-body -->
              		
                </form>
                    </div><!--End Active Feed Tab-->
                    
                    <div id="tabRSS" class="row tab-pane fade"><!--RSS Feed Tab-->
                    	 <center>
                        <div class="table-responsive" style="margin-top:20px; ">
                        <table align="center" id="recorddisplay" cellspacing="0" title="RSS Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:100%;">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                                <tr>
                                 	<th></th><!--View-->  
                                    <th>FILENAME</th>
                                    <th>VIDEO&nbsp;TITLE</th>
                                    <th>DESCRIPTION</th>
                                    <th>SHORT&nbsp;LINK</th>
                                    <th>LONG&nbsp;LINK</th><!--Hidden-->  
                                    <th>STATUS</th>
                                    <th>SCHEDULE_ID</th>  <!--Hidden-->                             
                                    <th>FEED_ID</th><!--Hidden-->
                                    <th>PUBDATE</th><!--Hidden-->
                                    <th>EXPDATE</th><!--Hidden-->  
                                </tr>
                              </thead>
                          </table>
                    	</div>                            
                       </center>
                    </div><!--End RSS Feed Tab-->
                  </div><!--End Contents-->
                  
                   <div align="center" class="form-group">
                  <div class="col-sm-offset-1 col-sm-8">
                    <div class="box-footer">
                        <button style="width:150px;" id="btnSet" type="button" class="btn btn-primary"><i class="fa fa-wrench"></i> Set Active RSS</button>
                                                    
                        <button style="margin-left:30px; width:150px;" id="btnRefreshProfile" type="button" class="btn btn-danger right" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                      </div>
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
