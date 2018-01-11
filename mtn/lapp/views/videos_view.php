<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Videos</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    <script src="<?php echo base_url();?>js/jwplayer.js"></script>
    
    <?php include('googleanalytics.php'); ?>
    
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
		
		bootstrap_Upload_alert = function() {}
		bootstrap_Upload_alert.warning = function(message) 
		{
		   $('#divUploadAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
		
		bootstrap_UpdateSuccess_alert = function() {}
		bootstrap_UpdateSuccess_alert.warning = function(message) 
		{
		   $('#divAddTitleAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
		}
		
		bootstrap_AddTitle_alert = function() {}
		bootstrap_AddTitle_alert.warning = function(message) 
		{
		   $('#divAddTitleAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}		
		
		bootstrap_Pipeline_alert = function() {}
		bootstrap_Pipeline_alert.warning = function(message) 
		{
		   $('#divPipelineAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
		
		bootstrap_SuccessPipeline_alert = function() {}
		bootstrap_SuccessPipeline_alert.warning = function(message) 
		{
		   $('#divPipelineAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
		}
		
		
		bootstrap_Job_alert = function() {}
		bootstrap_Job_alert.warning = function(message) 
		{
		   $('#divJobAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
		
		bootstrap_SuccessJob_alert = function() {}
		bootstrap_SuccessJob_alert.warning = function(message) 
		{
		   $('#divJobAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
		}
		
		
		var Title='<font color="#AF4442">Video Module Help</font>';
		var m='';
		
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,uploadcategory,bucket;
		var table_details, editdata,seldata,table_pipelines,table_jobs;
		var InputBucket='<?php echo $input_bucket; ?>';
		var OutputBucket='<?php echo $output_bucket; ?>';
		var ThumbBucket='<?php echo $thumbs_bucket; ?>';
		
		var video_file=null;
		
		function GetFile(input,SelectedFile)
		{
			try
			{				
				if ($.trim(SelectedFile)=='Video')
				{
					video_file=null;
					
					if (input.files && input.files[0]) video_file=input.files[0];
					
					if (video_file != null)
					{
						//THE METHOD THAT SHOULD SET THE VIDEO SOURCE
						if (input.files && input.files[0]) {
							var file = input.files[0];
							
							var fsize = file.size;
							
							var s=file.name.split('.');
							var ext=$.trim(s[s.length-1]);
	
							if (ext.toLowerCase()!='mp4')
							{
								m='Invalid Video Format For <b>'+file.name.toUpperCase()+'</b>. Only <b>MP4</b> Files Are Allowed. Please note that the affected file will NOT be uploaded. Other files in the batch that have no issue with their formats will, however, be uploaded. Click on the DELETE (REMOVE FILE) icon on the affected file preview to remove the file from the batch.';
								bootstrap_Upload_alert.warning(m);
								bootbox.alert({ 
									size: 'auto', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
									
								return false;
							}
																
							if(fsize>62914560) //do something if file size more than 60 mb (1048576-1MB)
							{
								var sz=fsize/1048576;
								
								m='The size of <b>'+file.name.toUpperCase()+'</b> is <b>' + number_format(sz,0,'.',',') + 'MB</b> which is too large for upload. Maximum size allowed for each file is <b>60MB</b>. Please note that the affected file will NOT be uploaded. Other files in the batch that have no issue with their sizes will, however, be uploaded. Click on the DELETE (REMOVE FILE) icon on the affected file preview to remove the file from the batch.';
								bootstrap_Upload_alert.warning(m);
								bootbox.alert({ 
									size: 'auto', message: m, title:Title,
									buttons: { ok: { label: "Close!", className: "btn-danger" } }
								});
																
								return false;
							}
						}	
					}					
				}
			}catch(e)
			{
				m='GETFILE ERROR:\n'+e;
				bootstrap_Upload_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close!", className: "btn-danger" } }
				});
			}
		} //End GetFile
		
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			if (RefreshDuration) RefreshDuration=RefreshDuration*1000;
			
			if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;
			
			//ShowLoadVideos();
			//ShowLoadJobs();
			//ShowLoadPipeLines();
			
			function ShowLoadPipeLines() {
                setInterval(function() {
					var cat=$('#cboCategory').val();
					
					if (cat) LoadPipeLines();
                }, RefreshDuration);
            }
			
			function ShowLoadJobs() {
                setInterval(function() {
					var pn=$.trim($('#cboPipelineName').val());
					
					if (pn) LoadJobs(pn);
                }, RefreshDuration);
            }
										
			function ShowLoadVideos() {
                setInterval(function() {
					var cat=$('#cboCategory').val();
					
					if (cat) LoadVideos(cat);
                }, RefreshDuration);
            }
			
			// Add event listener for opening and closing details
			$('#recorddisplay_details tbody').on('click', 'td', function () 
			{
				var tr = $(this).closest('tr');
				var row = table_details.row( tr );
				editdata = row.data();
				
				var colIndex = $(this).index();
				
				if (colIndex==0) SelectRow(editdata);
			} );	
			
			$('#recorddisplay_details tbody').on( 'click', 'tr', function () 
			{
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=false;
					//Get Selected Value					
					var val=table_details.row( this ).data();
					seldata=val;
					var cat=val[1],tit=val[2],des=val[3],fn=val[4];
					
//[View],Category,Title,Description,Filename,Status,
					
					//$('#lblAddCategory').html(cat);
					$('#txtTitle').val(tit);
					$('#txtDescription').val(des);	
					$('#lblFilename').html(fn);
				}
				else 
				{					
					table_details.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					
					ResetControls();
				}
			} );
			
			$('#recorddisplay_details tbody').on( 'click', 'tr', function () 
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
		
			$('#btnCloseModal').click(function(e) {
                try
				{
					$('#txtVideos').fileinput('clear');
					$('#idModalBody').html('');
				}catch(e)
				{
					$.unblockUI();
					m="Modal Close Button Click ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
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
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnUploadClose').click(function(e) {
                try
				{
					$('#txtVideos').fileinput('clear');
				}catch(e)
				{
					$.unblockUI();
					m="Upload Modal Close Button Click ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnEncodeClose').click(function(e) {
                try
				{
					//Reset Controls
				}catch(e)
				{
					$.unblockUI();
					m="Encode Modal Close Button Click ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnJobClose').click(function(e) {
                try
				{
					$('#btnJobRefresh').trigger('click');
					activateTab('tabPipeline');
				}catch(e)
				{
					$.unblockUI();
					m="Job Close Button Click ERROR:\n"+e;
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
						
			LoadPipelineNames();
			
			function LoadPipelineNames()
			{
				try
				{
					$('#cboPipelineName').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Pipeline Names. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Videos/GetPipeLineNames'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboPipelineName').append( new Option('[SELECT]','') );
		
								$.each($(data), function(i,e)
								{
									if (e.PipelineName && e.PipelineID)
									{
										$('#cboPipelineName').append( new Option(e.PipelineName,e.PipelineID) );
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
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
						}
					 }); //end AJAX
				}catch(e)
				{
					$.unblockUI();
					m='LoadPipelineNames Module ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			function LoadValues()
			{
				try
				{
					uploadcategory=$.trim($('#lblCategory').val());
					bucket=$.trim($('#lblInputFolder').val());
					
					if (video_file==null) video_file='';
				}catch(e)
				{
					m='LoadValues ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			

			function UploadVideo()//For Testing
			{
				try
				{
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Uploading Video. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$('#divAlert').html('');
										
					LoadValues();
								
					//Initiate POST
					var uri = "<?php echo site_url('Videos/AddVideos');?>";
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
								m='Video Uploaded Successfully.';
																
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
								
								$('#recorddisplay > tbody').html('');								
								
								$('#btnUploadClose').trigger('click');
								
								//LoadVideos(uploadcategory);
								
								$('#txtVideos').fileinput('clear');
								uploadcategory='';
								bucket='';
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

					if (video_file != null) fd.append('video_file', video_file);			

					fd.append('category', uploadcategory);			
					fd.append('bucket',bucket);
					
					xhr.send(fd);// Initiate a multipart/form-data upload

					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            }//UploadVideo Ends
			
			
			function LoadVideos(category)
			{
				try
				{					
					table = $('#recorddisplay').DataTable( {
						 select: true,
						 
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						language: {zeroRecords: "No Video Record Found"},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4,5,6,7,8 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8 ] }
						],//Title,Category,Size,Duration,Date_Created,Encoded,Distributed,Filename,Status,[Preview]
						columns: [
							{ width: "25%", },//VIDEO TITLE
							{ width: "12%" },//CATEGORY
							{ width: "10%" },//VIDEO SIZE
							{ width: "10%" },//DURATION
							{ width: "10%" },//DATE CREATED
							{ width: "5%" },//ENCODED
							{ width: "18%" },//FILENAME
							{ width: "5%" },//VIDEO STATUS
							{ width: "5%" }//PREVIEW VIDEO
						],
						order: [[ 0, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Videos/LoadVideosJson'); ?>',
							type: 'POST',
							data: {category:category},
							complete: function(xhr, textStatus) {
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
							},
							dataType: 'json'
					   }
					} );
					
					$.unblockUI();	
				}catch(e)
				{
					$.unblockUI();
					m='LoadVideos ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
				
			}//LoadVideos
			
			if (InputBucket && OutputBucket && ThumbBucket && ('<?php echo $aws_key; ?>') && ('<?php echo $aws_secret; ?>'))
			{
				LoadCategories();
			}
						
			function LoadCategories()
			{
				try
				{
					$('#cboCategory').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Data. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Videos/GetCategories'); ?>',
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
								buttons: { ok: { label: "Close", className: "btn-danger" } }
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
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			var $input = $("#txtVideos");
			
			// the file input
			$input.fileinput({
					uploadUrl:"<?php echo site_url('Videos/UploadVideo');?>",
					showUpload:true,
					allowedFileExtensions:['mp4'],
					showCaption: true,
					showUpload: false,
					allowedFileTypes: ["video"],
					allowedPreviewTypes:['video'],//['image', 'html', 'text', 'video', 'audio', 'flash', 'object']
					maxFileCount:10,
					minFileCount: 1,
					//showPreview:false,
					uploadAsync: false,
					uploadExtraData:function() { 
						return {
							category:uploadcategory,
							bucket:bucket,
							thumbucket:ThumbBucket
						};
					}
				});
			
			$('#btnRefreshDisplay').click(function(e) {
                try
				{
					$.unblockUI();
					$('#cboCategory').val('');
					$('#recorddisplay > tbody').html('');
				}catch(e)
				{
					$.unblockUI();
					m='Display Video Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
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
						m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Display videos
					LoadVideos(cat);
				}catch(e)
				{
					$.unblockUI();
					m='Display Video Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnUpload').click(function(e) {
                try
				{
					$('#divAlert').html('');
					
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					$('#lblCategory').html(cat);					
					$('#lblInputFolder').html(InputBucket);
					$('#lblStoragePath').html(InputBucket+'/'+cat);
										
					$('#divUploadVideo').modal({backdrop:'static',keyboard:false, show:true});
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnUploadRefresh').click(function(e) {
                try
				{
					$.unblockUI();
					$('#divUploadAlert').html('');
					$('#txtVideos').fileinput('clear');
					$('#txtVideos').fileinput('refresh');
				}catch(e)
				{
					$.unblockUI();
					m='Upload Refresh Button Click ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnUpdateRefresh').click(function(e) {
                try
				{
					$.unblockUI();
					$('#txtTitle').val('');
					$('#txtDescription').val('');	
					$('#lblFilename').html('');
					
					if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;
					
					AddMovieDetails('');
				}catch(e)
				{
					$.unblockUI();
					m='Update Refresh Button Click ERROR:\n'+e;
					
					bootstrap_AddTitle_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
				
			$('#btnUploadVideo').click(function(e) {
                try
				{					
					uploadcategory=$('#lblCategory').html();
					bucket=$('#lblInputFolder').html();
					
					//Confirm Upload
					if (!confirm('Uploading this file will add this video to the portal video store and it is irreversible. This process may take some time depending on your internet bandwidth and/or the total size of videos(s) you are uploading. Do you want to proceed with the uploading of the video?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Uploading Video. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$input.fileinput("upload");
					//UploadVideo();
					
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video Button Click ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
						
			$('#txtVideos').on('fileerror', function(event, data, msg) {
			   try
				{
					$.unblockUI();
					
					m=msg;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}catch(e)
				{
					$.unblockUI();
					m='File ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			});

			$('#txtVideos').on('filebatchuploaderror', function(event, data, previewId, index) 
			{
				try
				{
					$.unblockUI();
					
					var form = data.form, files = data.files, extra = data.extra;
					var response = data.response, reader = data.reader;	
					
					m=response.error;
					
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}catch(e)
				{
					$.unblockUI();
					m='Trigger File Batch Upload ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			});
			
			

			$('#txtVideos').on('filebatchuploadsuccess', function(event, data, previewId, index) 
			{
				try
				{
					$.unblockUI();
					
					 var form = data.form, files = data.files, extra = data.extra;
					 var response = data.response, reader = data.reader;
					
					if ($.trim(response.uploaded).toUpperCase()=='OK')
					{
						m='Video Upload Was Successful!';
						
						bootstrap_Success_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#recorddisplay > tbody').html('');
						
						
						$('#btnUploadClose').trigger('click');
						
						if (response.FileCount>0)
						{
							//alert(response.FileCount+'\n'+response.UploadFiles);
							
							$('#txtVideos').fileinput('clear');
							uploadcategory='';
							bucket='';
							
							AddMovieDetails(response.UploadFiles);
						}
					}
				}catch(e)
				{
					$.unblockUI();
					m='Trigger Upload Data ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
				
			});//End filebatchuploadcomplete
			
			$('#btnUpdateVideo').click(function(e) {
                try
				{
					var cat=$('#lblAddCategory').html();
					var fn=$('#lblFilename').html();
					var tit=$('#txtTitle').val();
					var desc=$('#txtDescription').val();
					
					if (!cat)
					{
						m='No category is selected. Update cannot continue.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						 return false;
					}
					
					if (!fn)
					{
						m='No filename is selected. Update cannot continue.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					//Video Title
					if (!tit)
					{
						m='Video title field must not be blank.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtTitle').focus(); return false;
					}
					
					if ($.isNumeric(tit))
					{
						m='Video title field must not be a number.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtTitle').focus(); return false;
					}		
					
					//Description
					if (!desc)
					{
						m='Video description field must not be blank.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtDescription').focus(); return false;
					}
					
					if ($.isNumeric(desc))
					{
						m='Video description field must not be a number.';
						
						bootstrap_AddTitle_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtDescription').focus(); return false;
					}
					
					//Confirm Upload
					if (!confirm('Updating the selected video record will permanently modify the record and it is irreversible. Do you want to proceed with the updating of the video?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={category:cat, filename:fn,video_title:tit,description:desc};
										
					$.ajax({
						url: "<?php echo site_url('Videos/UpdateVideo');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($.trim(data)=='OK')
							{
								$.unblockUI();
								
								$('#txtTitle').val('');
								$('#txtDescription').val('');	
								$('#lblFilename').html('');
								
								if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;
								
								AddMovieDetails('');
														
								m='Video "'+tit.toUpperCase()+'" With Filename "'+fn.toUpperCase()+'" Was Updated Successfully.';
										
								bootstrap_UpdateSuccess_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_AddTitle_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_AddTitle_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
					});
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video Button Click ERROR:\n'+e;
					
					bootstrap_AddTitle_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			function AddMovieDetails(files)
			{
				try
				{
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					$('#lblAddCategory').html(cat);
					
					//Load Video Table
					table_details = $('#recorddisplay_details').DataTable( {
						select: true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						language: {zeroRecords: "No Video Record Found"},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4,5 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
						],//Category,Title,Description,Filename,Status
						columns: [
							{ width: "5%" },//[VIEW]
							{ width: "10%" },//CATEGORY
							{ width: "25%" },//VIDEO TITLE
							{ width: "30%" },//DESCRIPTION
							{ width: "20%" },//FILENAME
							{ width: "10%" }//VIDEO STATUS
						],
						order: [[ 1, 'asc' ],[ 2, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Videos/LoadVideoDetailsJson'); ?>',
							type: 'POST',
							data: {category:cat,files:files},
							beforeSend: function() {					
								$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
							},
							complete: function(xhr, textStatus) {
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
							},
							dataType: 'json'
					   }
					} );	
															
					$('#divAddVideoTitle').modal({backdrop:'static',keyboard:false, show:true});
					
					//return true;
					
					//LoadVideos(uploadcategory);	
				}catch(e)
				{
					$.unblockUI();
					m='AddMovieDetails ERROR:\n'+e;
					
					bootstrap_AddTitle_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			$('#btnAddVideoDetails').click(function(e) {
                try
				{
					$('#divAlert').html('');
					
					AddMovieDetails('');
				}catch(e)
				{
					$.unblockUI();
					m='Add Video Details Button Click ERROR:\n'+e;
					
					bootstrap_AddTitle_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			
			function LoadPipeLines()
			{
				try
				{
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					$('#lblEncodeCategory').html(cat);
					
					//$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Pipelines. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Load Video Table
					table_pipelines = $('#recorddisplay_pipelines').DataTable( {
						//select: true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						language: {
							zeroRecords: "No Pipeline Record Or Table Still Be Loading",
							//loadingRecords: "Loading Jobs. Please Wait...",
							emptyTable: "No Pipeline Data Available"
							},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4 ] }
						],//[Name],[Id],[InputBucket],[OutputBucket],[Status]
						columns: [
							{ width: "30%" },//PIPELINE NAME
							{ width: "20%" },//PIPELINE ID
							{ width: "20%" },//INPUT BUCKET
							{ width: "20%" },//OUTPUT BUCKET
							{ width: "10%" },//STATUS
						],
						order: [[ 0, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Videos/LoadPipeLines'); ?>',
							type: 'POST',
							complete: function(xhr, textStatus) {
								$.unblockUI();
							},
							error:  function(xhr,status,error) {
								$.unblockUI();
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							},
							dataType: 'json'
					   }
					} );	
					
					//$.unblockUI();	
				}catch(e)
				{
					$.unblockUI();
					m='AddMovieDetails ERROR:\n'+e;
					
					bootstrap_AddTitle_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			$('#btnEncode').click(function(e) {
                try
				{
					$('#divAlert').html('');
					$('#divPipelineAlert').html('');
					
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					$('#lblEncodeCategory').html(cat);
					
					LoadPipeLines();
					
					$('#divEncodeVideoModal').modal({backdrop:'static',keyboard:false, show:true});
				}catch(e)
				{
					$.unblockUI();
					m='Encode Video Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#btnPipelineRefresh').click(function(e) {
                try
				{
					$.unblockUI();
					$('#txtPipelineName').val('');
										
					LoadPipeLines();
				}catch(e)
				{
					$.unblockUI();
					m='Create Pipeliine Refresh Button Click ERROR:\n'+e;
					
					bootstrap_Pipeline_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
					
			$('#btnCreatePipeline').click(function(e) {
                try
				{
					$('#divAlert').html('');
					$('#divPipelineAlert').html('');
					
					var cat=$('#lblEncodeCategory').html();
					var pn=$.trim($('#txtPipelineName').val());
										
					if (!cat)
					{
						m='No Video category Was Selected. Creating Of Pipeline Cannot Continue.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					if (!pn)
					{
						m='Pipeline name field must not be blank.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtPipelineName').focus(); return false;
					}
					
					if ($.isNumeric(pn))
					{
						m='Pipeline name field must not be a number.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#txtPipelineName').focus(); return false;
					}
					
					//Confirm pipeline creation
					if (!confirm('This action will create a new pipeline record on the Amazon Web Services and it is irreversible. Do you want to proceed with the creation of the pipeline?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={PipelineName:pn};
										
					$.ajax({
						url: "<?php echo site_url('Videos/CreatePipeLine');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						beforeSend: function() {					
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Creating Pipeline. Please Wait...</p>',theme: true,baseZ: 2000});
							},
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($.trim(data)=='OK')
							{
								$.unblockUI();
																								
								m='Pipeline With Name '+pn+' Was Successfully Created!';
								
								$('#txtPipelineName').val('');
								
								LoadPipeLines();
								LoadPipelineNames();
								
								bootstrap_SuccessPipeline_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
					});
					
					
//bootstrap_Pipeline_alert		
//bootstrap_SuccessPipeline_alert
				}catch(e)
				{
					$.unblockUI();
					m='Create Pipeline Button Click ERROR:\n'+e;
					
					bootstrap_Pipeline_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#cboPipelineName').change(function(e) {
                try
				{
					$('#cboJobInputFileName').empty();					
					$('#recorddisplay_jobs > tbody').html('');
					$('#cboJobInputFileName').text('');
					
					$('#lbl360p').html('');
					$('#lbl480p').html('');
					$('#lbl720p').html('');
					
					var pid=$(this).val();
					var cat=$('#lblEncodeCategory').html();
					
					if (pid)
					{
						LoadInputFilenames(pid,cat);//Dropdown
						LoadJobs(pid);//Table
					}
					
				}catch(e)
				{
					$.unblockUI();
					m='Pipeline Name Change ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			$('#cboJobInputFileName').change(function(e) {
                try
				{
					$('#lbl360p').html(''); $('#lbl480p').html(''); $('#lbl720p').html('');
					
					var tmp=$(this).val();
					var cat=$('#lblEncodeCategory').html();
					var fn='';
					
					if (tmp)
					{
						var jfn=$('#cboJobInputFileName :selected').text();
						var a=jfn.split('/');
						
						if (a.length>0)
						{
							if (a.length==2) fn=a[1]; else fn=a[0];
						}
						
						if (fn != '')
						{
						//Alzheimers_Risk_360p.mp4, Alzheimers_Risk_480p.mp4, Alzheimers_Risk_720p.mp4
						// "Category/movie.mp4"
							var t=fn.split('.');
							
							$('#lbl360p').html(cat+'/'+$.trim(t[0])+'_360p.'+t[1]);
							$('#lbl480p').html(cat+'/'+$.trim(t[0])+'_480p.'+t[1]);
							$('#lbl720p').html(cat+'/'+$.trim(t[0])+'_720p.'+t[1]);
						}
					}					
				}catch(e)
				{
					$.unblockUI();
					m='Pipeline Name Change ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
            });
			
			function LoadInputFilenames(PipelineID,Category)
			{
				try
				{
					$('#cboJobInputFileName').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Jobs/Input Files. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						data: {PipelineId:PipelineID,Category:Category},
						dataType: 'json',
						url: '<?php echo site_url('Videos/GetJobInputFileNames'); ?>',
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
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			function LoadJobs(PipelineID)
			{
				try
				{
					//$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p>Loading Jobs. Please Wait...</p>',theme: true,baseZ: 2000});
					
					table_jobs = $('#recorddisplay_jobs').DataTable( {
						//select: true,
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						//processing: "Loading Jobs...",
						language: {
							zeroRecords: "No Job Record Or Table Still Be Loading",
							//loadingRecords: "Loading Jobs. Please Wait...",
							emptyTable: "No Job Data Available"
							},
						columnDefs: [ 
							{
								"targets": [ 0,1,2,3,4 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4 ] }
						],//JobId,PipelineId,InputKey,OutputFiles,JabStatus
						columns: [
							{ width: "20%" },//JOB ID
							{ width: "20%" },//PIPELINE ID
							{ width: "20%" },//INPUT KEY
							{ width: "30%" },//OUTPUT FILES
							{ width: "10%" }//JOB STATUS
						],
						order: [[ 0, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Videos/LoadJobs'); ?>',
							data:{pipelineid:PipelineID},
							type: 'POST',
							complete: function(xhr, textStatus) {
								$.unblockUI();
							},
							
							error:  function(xhr,status,error) {
								$.unblockUI();
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							},
							dataType: 'json'
					   }
					} );
					
					//$.unblockUI();	
					
					
				}catch(e)
				{
					$.unblockUI();
					m='LoadJobs Module ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			
			$('#btnJobRefresh').click(function(e) {
                try
				{
					$.unblockUI();
					$('#cboPipelineName').val('');
					$('#cboJobInputFileName').empty();
					
					$('#lbl360p').html('');
					$('#lbl480p').html('');
					$('#lbl720p').html('');
					$('#recorddisplay_jobs > tbody').html('');
				}catch(e)
				{
					$.unblockUI();
					m='Create Job Refresh Button Click ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}	
            });
            
			$('#btnCreateJob').click(function(e) {
				try
				{
					$('#divAlert').html('');
					$('#divJobAlert').html('');
					
					var cat=$('#lblEncodeCategory').html();
					var pn=$.trim($('#cboPipelineName').val());
					//var jb=$('#cboJobInputFileName').val();
					var jb=$('#cboJobInputFileName :selected').text();
					
					var p360=$('#lbl360p').html();
					var p480=$('#lbl480p').html();
					var p720=$('#lbl720p').html();
										
					if (!cat)
					{
						m='No Video category Was Selected. Creating Of Job Cannot Continue.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					if ($('#cboPipelineName > option').length < 2) 
					{
						m='No pipeline record was pulled from the Amazon storage. Please check your internet connection.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					if (!pn)
					{
						m='Pipeline name must be selected.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboPipelineName').focus(); return false;
					}
					
					//Job file name
					if ($('#cboJobInputFileName > option').length < 2) 
					{
						m='No job filename record was pulled from the Amazon storage. Please check your internet connection.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						return false;
					}
					
					if (!jb)
					{
						m='Job filename must be selected.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
						
						$('#cboJobInputFileName').focus(); return false;
					}
					
					var t=jb.split('/');
					var f=t[t.length-1];
					var flag=$.trim($('#cboJobInputFileName').val()).toUpperCase();
					
					if (flag=='NO')
					{
						m='Title and description of the file "'+f.toUpperCase()+'" have not been captured. You must capture the title and description of the video before you can encode the file. You will now be directed to where you can carry out this update';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function()
							{
								$('#lblFilename').html(f);
								$('#lblAddCategory').html(cat);
								
								table_details = $('#recorddisplay_details').DataTable( {
								select: true,
								dom: '<"top"if>rt<"bottom"lp><"clear">',
								destroy:true,
								autoWidth:false,
								language: {zeroRecords: "No Video Record Found"},
								columnDefs: [ 
									{
										"targets": [ 0,1,2,3,4,5 ],
										"visible": true,
										"searchable": true,
										"orderable": true
									},
									{ className: "dt-center", "targets": [ 0,1,2,3,4,5 ] }
								],//Category,Title,Description,Filename,Status
								columns: [
									{ width: "5%" },//[VIEW]
									{ width: "10%" },//CATEGORY
									{ width: "25%" },//VIDEO TITLE
									{ width: "30%" },//DESCRIPTION
									{ width: "20%" },//FILENAME
									{ width: "10%" }//VIDEO STATUS
								],
								order: [[ 1, 'asc' ],[ 2, 'asc' ]],
								ajax: {
									url: '<?php echo site_url('Videos/LoadVideoDetailsJson'); ?>',
									type: 'POST',
									data: {category:cat,files:f},
									beforeSend: function() {					
										$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
									},
									complete: function(xhr, textStatus) {
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
									},
									dataType: 'json'
							   }
							} );	
								
								if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=false;
								
								$('#cboPipelineName').val('');
								$('#cboJobInputFileName').empty();
								
								$('#lbl360p').html('');
								$('#lbl480p').html('');
								$('#lbl720p').html('');
								$('#recorddisplay_jobs > tbody').html('');
								activateTab('tabPipeline');
					
								$('#divEncodeVideoModal').modal('hide');															
								$('#divAddVideoTitle').modal({backdrop:'static',keyboard:false, show:true});		
							}
						});						
						
						return false;
					}
					
					//Confirm pipeline creation
					if (!confirm('This action will create a new job record on the Amazon Web Services and it is irreversible. Do you want to proceed with the creation of the job?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={category:cat,pipelineid:pn,inputkey:jb,outputfile360:p360,outputfile480:p480,outputfile720:p720};
										
					$.ajax({
						url: "<?php echo site_url('Videos/CreateJob');?>",
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
																								
								m='Job Was Successfully Created!';
								
								//LoadPipelineNames();
								$('#cboJobInputFileName').val('');								
								$('#lbl360p').html(''); $('#lbl480p').html(''); $('#lbl720p').html('');
								
								LoadJobs(pn);
								
								bootstrap_SuccessJob_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_Job_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Job_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } }
								});
							}
					});
					
				}catch(e)
				{
					$.unblockUI();
					m='Create Job Button Click ERROR:\n'+e;
					
					bootstrap_Job_alert.warning(m);
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
				if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;
				//$('#lblAddCategory').html('');
				$('#txtTitle').val('');
				$('#txtDescription').val('');	
				$('#lblFilename').html('');
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
				var cat=dat[1],tit=dat[2],des=dat[3],fn=dat[4];
					
//[View],Category,Title,Description,Filename,Status,
					
					$('#lblAddCategory').html(cat);
					$('#txtTitle').val(tit);
					$('#txtDescription').val(des);	
					$('#lblFilename').html(fn);
			}else
			{
				ResetControls();
			}
		}
		
		function GetRow(sn)
		{
			ResetControls();
			
			if (sn>-1)
			{
				var row = table_details.row( sn ).data();
				
				if (row)
				{
					var cat=row[1],tit=row[2],des=row[3],fn=row[4];
						
	//[View],Category,Title,Description,Filename,Status,
						
						$('#lblAddCategory').html(cat);
						$('#txtTitle').val(tit);
						$('#txtDescription').val(des);	
						$('#lblFilename').html(fn);
						
						if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=false;
				}
			}			
		}
		
		function ShowVideo(preview_url,title,ext)
		{
			//fn.'_360p.'.$ext
			jwplayer.key='<?php echo $jwplayer_key; ?>';
			jwplayer("idModalBody").setup({
			 image: "",
			  sources: [
				{ file: preview_url+"_360p."+ext, label: "360p SD" },
				 { file: preview_url+"_480p."+ext, label: "480p SD" },
				{ file: preview_url+"_720p."+ext, label: "720p HD" }
			  ],
			 width: "100%",
			 aspectratio: "4:3"
			});
			
			
					
	//alert(src);							
			$('#idModalTitle').html(title);
			//$('#idModalBody').html(src);
			$('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
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
              <div class="panel-heading size-20"><i class="fa fa-file-video-o"></i> Videos</div>
                <div class="panel-body">                
             
                <!--Tab Details-->
				<div class="tab-content">
                    <div class="box-body">
                        <!--Video category-->
                        <div class="form-group">
                            <label style="width:auto;" class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category</label>
                            
                            <div class="col-sm-3" title="Select Video category">
                              <select id="cboCategory" class="form-control"></select>
                            </div>
                            
                            <span>
                             <!--Buttons-->
                                <button style="width:150px;" id="btnDisplay" type="button" class="btn btn-info"><span class="glyphicon glyphicon-play-circle" ></span> Load Videos</button>
                                
                                <button style="width:150px; margin-left:15px;" id="btnRefreshDisplay" type="button" class="btn btn-warning"><i class="material-icons">refresh</i> Reset Display</button>
                             </span>
                       </div>
                     </div>
                        
                        
                        <div align="center">
                            <div id = "divAlert"></div>
                        </div>
                    
                <center>
                    <div class="table-responsive">
                    <table align="center" id="recorddisplay" cellspacing="0" title="Video Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>TITLE</th>
                                <th>CATEGORY</th>
                                <th>SIZE</th>
                                <th>DURATION</th>
                                <th>DATE&nbsp;CREATED</th>                             
                                <th>ENCODED</th>
                                <th>FILENAME</th>
                                <th>STATUS</th>
                                <th>PREVIEW</th><!--Preview-->
                            </tr>
                          </thead>
                      </table>
                    </div>
                   </center>
                
             
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
               </div>
                                   
                 
                <div align="center" style="margin-top:30px; ">
				<button style="width:140px;" id="btnUpload" type="button" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Upload Video
                </button>
                
                <button style="width:140px; margin-left:10px;" id="btnAddVideoDetails" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Add Video Details
                </button>
                
                <button style="width:140px; margin-left:10px;" id="btnEncode" type="button" class="btn btn-primary"><i class="fa fa-lock"></i> Encode Video
                </button>
                
                <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-danger" role="button" style="text-align:center; width:120px; margin-left:10px;"><i class="material-icons">restore_page</i> Refresh</button>
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

<!--******************************* Upload Movie modal **************************************************-->
<div class="modal fade" id="divUploadVideo" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
   <div class="modal-dialog" style="width: 100%; height: 100%; padding: 0; margin:0;">
       <div class="modal-content" style="height: 100%; border-radius: 0; color:white; overflow:auto;">
            <!--*****************UPLOAD******************-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Click To Close The Upload Form">
                    <span aria-hidden="true" class=" size-55">&times;</span><span class="sr-only">Close</span>
                </button>
                
                <h3  id="modal-register-label"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%; font-size: 3em; font-weight: 500; margin: 0 0 5px 0;">Upload Video</h3>
               <p style="width:95%;" align="center"><span id="status" class="label label-danger"></span></p>
            </div>

			<div class="register-box form-bottom modal-body" style="width:100%;">
  <div class="register-box-body">
    <form class="form-horizontal" role="form" data-inset="true" style="margin-top:-120px;">
      <div class="form-group" title="Video Category">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblCategory">Video Category</label>
        <div class="col-sm-10">
           <label id="lblCategory" class="form-control"></label>
        </div>
      </div>
      
      <div class="form-group" title="Amazon Input Folder">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblInputFolder">AWS Input Folder</label>
        <div class="col-sm-10">
           <label id="lblInputFolder" class="form-control"></label>
        </div>
      </div>
      
      <div class="form-group" title="Amazon Storage Path">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblStoragePath">AWS Storage Path</label>
        <div class="col-sm-10">
           <label id="lblStoragePath" class="form-control"></label>
        </div>
      </div>
      
      
     <!--
     <div class="form-group">
        <label class="col-sm-2 control-label left" for="txtVideo">Video<span class="redtext">*</span></label>
          <div align="center" class="col-sm-9">
            <input style="margin-top:5px;" id="txtVideo" name="txtVideo" type="file" accept="video/mp4" onchange="GetFile(this,'Video');"><br>
            
            <video class="hidden" id="vidTrailer" width="420" controls > 
               <source id="srcMP4" src="" type="video/mp4" media="all and (max-width: 480px)">
              Your browser does not support the video tag.
            </video>
          </div>
      </div> -->
      
      
      <div class="form-group" title="Select Videos To Upload">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="txtVideos">Videos<span class="redtext">*</span></label>
        <div class="col-sm-10">
          <!--Videos-->
              <div class="form-group" title="Videos" style="margin-top:20px;">
                  <div class="col-sm-12">
                    <input onChange="GetFile(this,'Video');" id="txtVideos" name="txtVideos[]" type="file" class="file-loading" multiple>
                  </div>
            </div>
        </div>
      </div>
            
            
            
       <div align="center">
            <div id = "divUploadAlert"></div>
       </div>
      
      <!--Buttons Row-->     
      <div class="row">  
      	 <div class="col-xs-3">&nbsp;</div>
               
        <!-- /.col -->
        <div class="col-xs-2" title="Click To Upload">
          <button id="btnUploadVideo" type="button" class="btn btn-primary btn-block btn-flat">Upload</button>
        </div>
       
       <div class="col-xs-2" title="Click To Refresh">
          <button id="btnUploadRefresh" type="button" class="btn btn-warning btn-block btn-flat">Refresh</button>
        </div>
        
         <div class="col-xs-2">
          <div title="Click To Close The Upload Form">
              <button id="btnUploadClose" type="button" class="btn btn-default btn-block btn-flat" data-dismiss="modal">Close</button>            
          </div>
        </div> 
        
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!--*****************UPLOAD END******************-->
		</div><!--End modal-content-->
	</div><!--End modal-dialogue-->
</div>
<!--******************************* End Upload Movie modal **********************************************--> 

<!--******************************* Add Movie Title modal **************************************************-->
<div class="modal fade" id="divAddVideoTitle" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
   <div class="modal-dialog" style="width: 100%; height: 100%; padding: 0; margin:0;">
       <div class="modal-content" style="height: 100%; border-radius: 0; color:white; overflow:auto;">
            <!--*****************ADD TITLE******************-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Click To Close The Add Title Form">
                    <span aria-hidden="true" class=" size-55">&times;</span><span class="sr-only">Close</span>
                </button>
                
                <h3  id="modal-register-label"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%; font-size: 3em; font-weight: 500; margin: 0 0 5px 0;">Add Video Details</h3>
               <p style="width:95%;" align="center"><span id="status" class="label label-danger"></span></p>
            </div>

			<div class="register-box form-bottom modal-body" style="width:100%;">
  <div class="register-box-body">
    <form class="form-horizontal" role="form" data-inset="true" style="margin-top:-120px;">
      <!--Category/Filename-->
      <div class="form-group">
      	<!--Category-->
        <label style="margin-left:50px;" class="col-sm-1 control-label size-12" for="lblAddCategory" title="Video Category">Video&nbsp;Category</label>
        <div class="col-sm-3" title="Video Category">
           <label id="lblAddCategory" class="form-control"></label>
        </div>
        
        <!--Filename-->
        <label class="col-sm-1 control-label size-12" for="lblFilename" title="Video File Name">File Name</label>
        <div class="col-sm-6" title="Video File Name">
           <label id="lblFilename" class="form-control"></label>
        </div>
      </div>
      
      
      <div class="form-group" title="Video Title">
        <label style="margin-left:50px;" class="col-sm-1 control-label size-12" for="txtTitle">Video Title</label>
        <div class="col-sm-10">
           <input type="text" placeholder="Video Title" id="txtTitle" class="form-control" required>
        </div>
      </div>
      
      <div class="form-group" title="Videos Description">
        <label style="margin-left:50px;" class="col-sm-1 control-label size-12" for="txtDescription">Description</label>
        
        <div class="col-sm-6">
          <!--Videos-->
              <div class="form-group">
                  <div class="col-sm-12">
                    <textarea rows="3" id="txtDescription" class="form-control" placeholder="Video Description"></textarea>
                  </div>
            </div>
        </div>
        
        <button style="width:150px; height:75px;" id="btnUpdateVideo" type="button" class="btn btn-info btn-flat">Update Video Details</button>
        
         <button style="width:100px; margin-left:10px; height:75px;" id="btnUpdateRefresh" type="button" class="btn btn-warning btn-flat">Refresh</button>
         
          <button style="width:100px; margin-left:10px; height:75px;" id="btnUpdateClose" type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button> 
        <div class="col-xs-2" title="Click To Update Video">
          
        </div>
       
       <div class="col-xs-2" title="Click To Refresh">
         
        </div>
        
         <div class="col-xs-2">
          <div title="Click To Close The Update Form">
                        
          </div>
        </div> 
      </div>
      
       <center>
        <div class="table-responsive">
        <table align="center" id="recorddisplay_details" cellspacing="0" title="Video Details" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
              <thead style="color:#ffffff; background-color:#7E7B7B;">
                <tr>
                	<th></th>
                    <th>VIDEO&nbsp;CATEGORY</th>
                    <th>VIDEO&nbsp;TITLE</th>
                    <th>VIDEO&nbsp;DESCRIPTION</th>
                    <th>VIDEO&nbsp;FILENAME</th>
                    <th>VIDEO&nbsp;STATUS</th> 
                </tr>
              </thead>
          </table>
        </div>
       </center>
            
            
            
       <div align="center">
            <div id = "divAddTitleAlert"></div>
       </div>
      
      <!--Buttons Row-->     
      <div class="row">  
      	 <div class="col-xs-3">&nbsp;</div>
               
        <!-- /.col -->
        
        
      </div>
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!--*****************UPLOAD END******************-->
		</div><!--End modal-content-->
	</div><!--End modal-dialogue-->
</div>
<!--******************************* End Movie Title modal **********************************************--> 


<!--******************************* ENCODE MOVIE MODAL ************************************************-->
<div class="modal fade" id="divEncodeVideoModal" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
   <div class="modal-dialog" style="width: 100%; height: 100%; padding: 0; margin:0;">
       <div class="modal-content" style="height: 100%; border-radius: 0; color:white; overflow:auto;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Click To Close The Econde Form">
                    <span aria-hidden="true" class=" size-55">&times;</span><span class="sr-only">Close</span>
                </button>
                
                <h3  id="modal-register-label"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%; font-size: 3em; font-weight: 500; margin: 0 0 5px 0;"><i class="material-icons" style="font-size:0.8em; margin-top:25px;">enhanced_encryption</i> Encode Video</h3>
               <p style="width:95%;" align="center"><span id="status" class="label label-danger"></span></p>
            </div>

			<div class="register-box form-bottom modal-body" style="width:100%;">
  <div class="register-box-body">
    <form class="form-horizontal" role="form" data-inset="true" style="margin-top:-120px;">
    <div align="center" style="font-size:14px; font-style:italic; font-weight:bold; margin-bottom:10px; color:#056E7B;">Fields With <span class="redtext">*</span> Are Mandatory!</div>
    
      <div class="form-group" title="Video Category">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblEncodeCategory">Video Category</label>
        <div class="col-sm-5">
           <label style="background-color:#FBEEEE; color:#D9383B;" id="lblEncodeCategory" class="form-control"></label>
        </div>
      </div>
      
      <!--############################################ Tab #################################################-->
        <ul class="nav nav-tabs " style="font-weight:bold;">
          <li class="active"><a data-toggle="tab" href="#tabPipeline"><i class="fa fa-tasks"></i> Create Pipeline</a></li>
          
          <li><a data-toggle="tab" href="#tabJob"><i class="fa fa-cogs"></i> Create Job</a></li>
        </ul>
      <!--########################################## Tab Ends ###########################################-->
  
  <!--########################################## Tab Contents ###########################################-->   
  <div class="tab-content" id="tabs">
        <!--#################### Create Pipeline Pane ################--> 
        <div class="tab-pane fade in active" id="tabPipeline">
        <br>
        	<!--Pipeline Input Bucket/Output Bucket-->
           <!--<div class="form-group">
            	<!--Pipeline Input Bucket
                <label class="col-sm-2 control-label size-12" for="lblPipelineInputBucket" title="Input Bucket(Folder)">AWS Input Bucket</label>
                <div class="col-sm-4" title="Input Bucket(Folder)">
                   <label class="form-control" id="lblPipelineInputBucket" style="font-weight:normal;"><?php# echo $input_bucket; ?></label>
                </div>-->
                
                 <!--Pipeline Output Bucket
                <label class="col-sm-2 control-label size-12" for="lblPipelineOutputBucket" title="Output Bucket(Folder)">AWS Output Bucket</label>
                <div class="col-sm-3" title="Output Bucket(Folder)">
                   <label class="form-control" id="lblPipelineOutputBucket" style="font-weight:normal;"><?php# echo $output_bucket; ?></label>
                </div>
              </div>  -->
              
        	<!--Pipeline Name-->
             <div class="form-group">
                <label class="col-sm-2 control-label size-12" for="txtPipelineName" title="Enter Pipeline Name (Max Length: 40)">Pipeline Name<span class="redtext">*</span></label>
                
                <div class="col-sm-4" title="Enter Pipeline Name (Max Length: 40)">
                   <input id="txtPipelineName" type="text" class="form-control" placeholder="Pipeline Name (Max Length is 40 Characters)" maxlength="40">
                </div>
                
                <span class="right">
                <button style="width:170px;" id="btnCreatePipeline" type="button" class="btn btn-primary btn-flat size-18"><i class="fa fa-tasks"></i> Create Pipeline</button>
               
                  <button style="width:170px; margin-left:20px;" id="btnPipelineRefresh" type="button" class="btn btn-warning btn-flat size-18"><i class="fa fa-refresh"></i> Refresh</button>
                  
                  <button title="Click To Close The Encode Screen" id="btnEncodeClose" type="button" class="btn btn-danger btn-flat size-18" data-dismiss="modal" style="width:170px; margin-left:20px;"><i class="fa fa-sign-out"></i> Close</button>
                 </span>
              </div>
             
                              
                                        
               <div align="center">
                    <div id = "divPipelineAlert"></div>
               </div>
				
                <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay_pipelines" cellspacing="0" title="AWS Pipelines" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>PIPELINE&nbsp;NAME</th>
                                <th>PIPELINE&nbsp;ID</th>
                                <th>INPUT&nbsp;BUCKET</th>
                                <th>OUTPUT&nbsp;BUCKET</th>
                                <th>STATUS</th> 
                            </tr>
                          </thead>
                      </table>
                    </div>
                </center>
        </div>
        <!--#################### End Create Pipeline Pane ############--> 
        
        
        <!--#################### Create Job ################-->
        <div class="tab-pane fade" id="tabJob">
        	<br>
        	<!--Pipeline Name/File Name-->
           <div class="form-group">
            	<!--Pipeline Name-->
                <label class="col-sm-2 control-label size-12" for="cboPipelineName" title="Pipeline Name">Pipeline Name</label>
                <div class="col-sm-4" title="Pipeline Name">
                   <select class="form-control" id="cboPipelineName"></select>
                </div>
                
                 <!--Filename-->
                <label class="col-sm-2 control-label size-12" for="cboJobInputFileName" title="Input Filename">Input Filename</label>
                <div class="col-sm-3" title="Input Filename">
                   <select class="form-control" id="cboJobInputFileName"></select>
                </div>
              </div>  
              
        	<!--Output Filenames-->
             <div class="form-group">
                <label class="col-sm-2 control-label size-12" for="lbl360p" title="Output Filenames">Output Filenames</label>
                
                <div class="col-sm-3">
                   <label style="font-weight:normal;" class="form-control" id="lbl360p" title="Generic Preset 360p (4:3)"></label>
                </div>
                
                <div class="col-sm-3">
                   <label style="font-weight:normal;" class="form-control" id="lbl480p" title="Generic Preset 480p (4:3)"></label>
                </div>
                
                <div class="col-sm-3">
                   <label style="font-weight:normal;" class="form-control" id="lbl720p" title="Generic Preset 720p"></label>
                </div>
              </div>
              
              <div class="form-group">
              	<div align="center">
                <button style="width:170px;" id="btnCreateJob" type="button" class="btn btn-primary btn-flat size-18"><i class="fa fa-cogs"></i> Create Job</button>
               
                  <button style="width:170px; margin-left:20px;" id="btnJobRefresh" type="button" class="btn btn-warning btn-flat size-18"><i class="fa fa-refresh"></i> Refresh</button>
                  
                  <button title="Click To Close The Encode Screen" id="btnJobClose" type="button" class="btn btn-danger btn-flat size-18" data-dismiss="modal" style="width:170px; margin-left:20px;"><i class="fa fa-sign-out"></i> Close</button>
                 </div>
              </div>
             
                              
                                        
               <div align="center">
                    <div id = "divJobAlert"></div>
               </div>
				
                <center>
                	 <div class="table-responsive">
                    <table align="center" id="recorddisplay_jobs" cellspacing="0" title="AWS Jobs" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                          <thead style="color:#ffffff; background-color:#7E7B7B;">
                            <tr>
                                <th>JOB&nbsp;ID</th>
                                <th>PIPELINE&nbsp;ID</th>
                                <th>INPUT&nbsp;FILENAME</th>
                                <th>OUTPUT&nbsp;FILENAMES</th>
                                <th>STATUS</th> 
                            </tr>
                          </thead>
                      </table>
                    </div>
                </center>
        </div>
        <!--#################### End Create Job Pane ############--> 
        
  </div>
  <!--########################################## Tab Contents Ends ##########################################-->
      
    </form>
  </div>
  <!-- /.form-box -->
</div>
<!--*****************UPLOAD END******************-->
		</div><!--End modal-content-->
	</div><!--End modal-dialogue-->
</div>
<!--******************************* ENCODE MOVIE END **************************************************-->
 </body>
</html>
