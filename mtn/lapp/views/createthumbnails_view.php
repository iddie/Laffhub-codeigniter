<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Create Thumbnails</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    <script src="<?php echo base_url();?>js/jwplayer.js"></script>
 
    
    <style>
	
    .big-box
    {
      position: absolute;
      width: 50%;
      height: 50%;
      color:white;
    }
    .big-box h2
    {
      text-align: center;
      margin-top: 20%;
      padding: 20px;
      width: 100%;
      font-size: 1.8em;
      letter-spacing: 2px;
      font-weight: 700;
      text-transform: uppercase;
    cursor:pointer;
    }
    @media screen and (max-width: 46.5em) 
    {
      .big-box h2
      {
        font-size:16px;
        padding-left:0px;
      }
    }
    @media screen and (max-width: 20.5em) 
    {
      .big-box h2
      {
        font-size:12px;
        padding-left:0px;
        margin-top:30%;
        }
    }
    
    
    .modal-content-one
    {
      background-color:yellowgreen;
    }
    .modal-content-two
    {
      background-color:#E6537D;
    }
    .modal-content-three
    {
      background-color:crimson;
    }
    .modal-content-four
    {
      background-color:lightseagreen;
    }
    .close
    {
      color:white ! important;
      opacity:1.0;
    }
    </style>
    
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
		
		var Title='<font color="#AF4442">Create Thumbnails Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,bucket,editdata,seldata;
		var InputBucket='<?php echo $input_bucket; ?>';
		var OutputBucket='<?php echo $output_bucket; ?>';
		var ThumbBucket='<?php echo $thumbs_bucket; ?>';
				
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					//Get Selected Value					
					var val=table.row( this ).data();
					seldata=val;
					var pb=val[1],cat=val[2],fn=val[10],cd=val[11];
					
					$('#hidFilename').val(fn);
					$('#hidVideoCode').val(cd);
										
					$('#cboCategory').val(cat);
					
					$('#btnCreate').prop('disabled',false);
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
			
			
			CheckForAWSValues();
			
			function CheckForAWSValues()
			{
				$.unblockUI();
				
				if (!InputBucket || !OutputBucket || !ThumbBucket)
				{
					m='One or more of the Amazon buckets(folders) values is not set. These values can be set in the PORTAL SETTINGS menu item of the SETTINGS/USERS menu. You need the necessary permission to be be able to set the value. You may contact the system administrator to do this. You will now be redirected back to the dashboard';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback: function(){
							window.location.href='<?php echo site_url("Userhome"); ?>';
						}
					});
				}
				
				if (!('<?php echo $aws_key; ?>') || !('<?php echo $aws_secret; ?>'))
				{
					m='The Amazon key and/or the Amazon secret code is not set. These values can be set in the PORTAL SETTINGS menu item of the SETTINGS/USERS menu. You need the necessary permission to be be able to set the value. You may contact the system administrator to do this. You will now be redirected back to the dashboard';
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback: function(){
							window.location.href='<?php echo site_url("Userhome"); ?>';
						}
					});
				}
			}
					
			$('#btnCloseVideoModal').click(function(e) {
                try
				{
					$('#idModalBody').html('');
				}catch(e)
				{
					$.unblockUI();
					m="Modal Close Button Click ERROR:\n"+e;
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

			
			if (InputBucket && OutputBucket && ThumbBucket && ('<?php echo $aws_key; ?>') && ('<?php echo $aws_secret; ?>'))
			{
				LoadCategories();
			}
						
			function LoadCategories()
			{
				try
				{
					$('#cboCategory').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Categories. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Createthumbnails/GetCategories'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboCategory').append( new Option('[SELECT]','') );
		
								$.each($(data), function(i,e)
								{
									if (e.category)
									{
										$('#cboCategory').append( new Option(e.category,e.category) );
									}
								});
							}
							
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
						}
					 }); //end AJAX
				}catch(e)
				{
					$.unblockUI();
					m='LoadCategories Module ERROR:\n'+e;
					
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
			
			$('#btnRefreshDisplay').click(function(e) {
                try
				{
					$.unblockUI();
					$('#cboCategory').val('ALL');
					$('#hidFilename').val('');
					$('#hidVideoCode').val('');
										
					$('#recorddisplay > tbody').html('');
				}catch(e)
				{
					$.unblockUI();
					m='Display Video Button Click ERROR:\n'+e;
					
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
			
			$('#btnDisplay').click(function(e) {
                try
				{
					$('#divAlert').html('');
					
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No category record has been captured. Please contain the system administrator.';
						
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
					
					if (!cat)
					{
						m='Please select a video category.';
						
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
						
						$('#cboCategory').focus();
						
						return false;
					}
										
					//Display videos
					LoadVideos(cat);
				}catch(e)
				{
					$.unblockUI();
					m='Display Video Button Click ERROR:\n'+e;
					
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
			
			function LoadInputFilenames(PipelineID,Category,publisher)
			{
				try
				{
					$('#cboJobInputFileName').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Jobs/Input Files. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						data: {PipelineId:PipelineID,Category:Category, publisher:publisher},
						dataType: 'json',
						url: '<?php echo site_url('Createthumbnails/GetJobInputFileNames'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboJobInputFileName').append( new Option('[SELECT]','') );
		
								$.each($(data), function(i,e)
								{
									if (e.Filename && e.HasDetails)
									{
										$('#cboJobInputFileName').append( new Option(e.Filename,e.HasDetails) );
									}
								});
								
								var sel=$('#lblEncodeCategory').html() + '/' + $('#hidFilename').val();
																
								$("#cboJobInputFileName option:contains(" + sel + ")").attr('selected', 'selected');
								
								$("#cboJobInputFileName").trigger('change');
							}
							
							$.unblockUI();
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
					 }); //end AJAX
				}catch(e)
				{
					$.unblockUI();
					m='LoadInputFilenames Module ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
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

		function ResetControls()
		{
			try
			{					
				$('#btnCreate').prop('disabled',true);
				
				$('#hidFilename').val('');
				$('#hidVideoCode').val('');
				$('#hidPublisher').val('');
				$('#cboCategory').val('ALL');
				
				$('#cboPublisher').val('ALL');
				$('#lblPublisher').html('');
			}catch(e)
			{
				$.unblockUI();
				m="ResetControls ERROR:\n"+e;
				bootstrap_AddTitle_alert.warning(m);
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
					
		function CreateThumb(fileurl,videocode,id,imgpath,cat)
		{
			try
			{
				if (!confirm('Are you sure you want to create thumbnail for the selected video? (Click "OK" to proceed or "CANCEL") to abort)?'))
			{
				return false;
			}
				
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Creating Video Thumbnail. Please Wait...</p>',theme: true,baseZ: 2000});
				
				$.ajax({
					type: "POST",
					data: {imagepath:imgpath,category:cat,id:id,videocode:videocode,fileurl:fileurl,bucket:InputBucket,thumbucket:ThumbBucket},
					dataType: 'text',
					url: '<?php echo site_url('Createthumbnails/CreateThumbnail'); ?>',
					complete: function(xhr, textStatus) {
						$.unblockUI;
					},
					success: function(data,status,xhr) //we're calling the response json array 'cntry'
					{
						$.unblockUI();
						
						var ret='';
						ret=$.trim(data);
						
						if (ret.toUpperCase()=='OK')
						{
							m='Video Thumbnail Was Created Successfully!';
							
							LoadVideos(cat);
											
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
							m=ret;
							
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
			}catch(e)
			{
				$.unblockUI();
				m="CreateThumb Function ERROR:\n"+e;
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
		
		function LoadVideos(category)
		{
			try
			{
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
								
				table = $('#recorddisplay').DataTable( {
					 select: true,
					 
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					destroy:true,
					autoWidth:false,
					language: {zeroRecords: "No Video Record Found"},
					lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
					columnDefs: [ 
						{
							"targets": [ 5,6 ],
							"visible": false
						},
						{
							"targets": [ 0,1,2,3,4 ],
							"visible": true,
							"searchable": true,
							"orderable": true
						},
						{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6 ] }
					],//[CREATE THUMBNAIL],Preview,Category,Title,VideoStatus,thumbnailFilename,VideoCode
					columns: [
						{ width: "15%", },//CREATE THUMBNAIL
						{ width: "10%" },//PREVIEW VIDEO
						{ width: "30%", },//CATEGORY						
						{ width: "35%" },//VIDEO TITLE
						{ width: "10%" },//VIDEO STATUS
						{ width: "0%" },//FILENAME
						{ width: "0%" }//VIDEO CODE
					],
					order: [[ 2, 'asc' ],[ 3, 'asc' ]],
					ajax: {
						url: '<?php echo site_url('Createthumbnails/LoadVideosJson'); ?>',
						type: 'POST',
						data: {category:category, InputBucket:InputBucket, ThumbBucket:ThumbBucket},
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
				
				//$.unblockUI();	
			}catch(e)
			{
				$.unblockUI();
				m='LoadVideos ERROR:\n'+e;
				
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
			
		}//LoadVideos
			
		function GetRow(sn)
		{
			ResetControls();
			
			if (sn>-1)
			{
				var row = table.row( sn ).data();
				
				if (row)
				{
					var pb=row[1],cat=row[2],fn=row[10],cd=row[11];
						
					$('#hidFilename').val(fn);
					$('#hidVideoCode').val(cd);
					$('#hidPublisher').val(pb);
					
					$('#lblPublisher').html(pb);
					$('#cboPublisher').val(pb);
					$('#cboCategory').val(cat);
					
					$('#btnCreate').prop('disabled',false);
				}
			}			
		}
		
		function ShowVideo(preview_url,title,ext,preview_img)
		{
			//alert(preview_url+"."+ext);
			
			jwplayer.key='<?php echo $jwplayer_key; ?>';
			//jwplayer.key='';

			jwplayer("idModalBody").setup({
			 image: preview_img,
			 file: preview_url+"."+ext,
			 width: "100%",
			 stretching: "uniform",
			 aspectratio: "16:9"
			});
			
			//width: "640",
			//height: "360",
							
			$('#idModalTitle').html(title);
			//$('#idModalBody').html(src);
			$('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
		}
		
		function ShowVideo_Encoded(preview_url,title,ext,preview_img)
		{
			//alert(preview_url+"_360p."+ext+'\n\n'+ext);
			//fn.'_360p.'.$ext
			jwplayer.key='<?php echo $jwplayer_key; ?>';

			jwplayer("idModalBody").setup({
			 image: preview_img,
			  sources: [
				{ file: preview_url+"_360p."+ext, label: "360p SD" },
				{ file: preview_url+"_720p."+ext, label: "720p SD" },
				{ file: preview_url+"_1080p."+ext, label: "1080p HD" }
			  ],
			 width: "100%",
			 stretching: "uniform",
			 aspectratio: "16:9"//4:3
			});
			
			
					
	//alert(src);							
			$('#idModalTitle').html(title);
			//$('#idModalBody').html(src);
			$('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
		}
		
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
              <div class="panel-heading size-20"><i class="glyphicon glyphicon-picture"></i> Create Video Thumbnail</div>
                <div class="panel-body">
                    <div class="box-body">
                        <form class="form-horizontal">                       
                       <!--Video category/Video Status-->
                        <div class="form-group">
                            <!--Video category-->
                            <label class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category</label>
                            
                            <div class="col-sm-4" title="Select Video category">
                              <select id="cboCategory" class="form-control" style="padding:3px;"></select>
                            </div>
                            
                            <div class="col-sm-5" title="Select Video Play Status">
                              <button style="width:140px;" id="btnDisplay" type="button" class="btn btn-info"><i class="fa fa-file-image-o"></i> Load Images</button>
                                
                                <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-danger" role="button" style="text-align:center; width:140px; margin-left:10px;"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
                            </div>
                                                         
                             <input type="hidden" id="hidFilename">
                             <input type="hidden" id="hidVideoCode">
                       </div>
                     </div>
                   </form>     
                        
                        <div align="center">
                            <div id = "divAlert"></div>
                        </div>
                    
                <center>
                    <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="Video Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>CREATE&nbsp;THUMBNAIL</th>
                                <th>VIDEO&nbsp;PREVIEW</th><!--Preview-->
                                <th>CATEGORY</th>
                                <th>TITLE</th>                            
                                <th>STATUS</th>                                
                                <th class="hide">THUMBNAILFILENAME</th>
                                <th class="hide">VIDEOCODE</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                   </center>
                
             
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
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

     
      <!--******************************* Video Preview modal **************************************************-->
<div class="modal fade" id="divVideoModal" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button id="btnCloseVideoModal" type="button" class="close" data-dismiss="modal" title="Click To Close The Popup Screen">
                    <span aria-hidden="true" style="font-size:xx-large;">&times;</span><span class="sr-only">Close</span>
                </button>
                
                <h3  id="idModalTitle"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%;"></h3>
            </div>
        
            <div class="register-box form-bottom modal-body" style="width:100%; margin-top:-30px;">
              <div id="idModalBody" class="register-box-body"></div></div>
        
        </div><!--End modal-content-->
    </div><!--End modal-dialogue-->
</div>
<!--******************************* End Preview Video modal **********************************************-->


 </body>
</html>

