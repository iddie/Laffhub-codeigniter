<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Upload Videos</title>
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
		
		var Title='<font color="#AF4442">Upload Video Help</font>';
		var m='';
		
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var PublisherEmail='<?php echo $publisher_email; ?>';
		var PublisherName='<?php echo $publisher_name; ?>';
		var table,uploadcategory,bucket,comedian;
		var table_details, editdata,seldata;
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
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
					});
									
								return false;
							}
																
							if(fsize>104857600) //do something if file size more than 100 mb (1048576-1MB)
							{
								var sz=fsize/1048576;
								
								m='The size of <b>'+file.name.toUpperCase()+'</b> is <b>' + number_format(sz,0,'.',',') + 'MB</b> which is too large for upload. Maximum size allowed for each file is <b>100MB</b>. Please note that the affected file will NOT be uploaded. Other files in the batch that have no issue with their sizes will, however, be uploaded. Click on the DELETE (REMOVE FILE) icon on the affected file preview to remove the file from the batch.';
								bootstrap_Upload_alert.warning(m);
								bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
				}
            });
			
			LoadComedians();
						
			function LoadComedians()
			{
				try
				{
					$('#cboComedian').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Comedians. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Uploadvideos/GetComedians'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboComedian').append( new Option('[SELECT]','') );
		
								$.each($(data), function(i,e)
								{
									if (e.comedian)
									{
										$('#cboComedian').append( new Option(e.comedian,e.comedian) );
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
					m='LoadComedians Module ERROR:\n'+e;
					
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
						
			function LoadValues()
			{
				try
				{
					uploadcategory=$.trim($('#lblCategory').val());
					comedian=$.trim($('#cboComedian').val());
					bucket=$.trim($('#lblInputFolder').val());
					
					if (video_file==null) video_file='';
					if (comedian)
					{
						if (comedian.toLowerCase() == 'undefined') comedian='';
					}					
				}catch(e)
				{
					m='LoadValues ERROR:\n'+e;
					
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
			

			function UploadVideo()//For Testing
			{
				try
				{
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Uploading Video. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$('#divAlert').html('');
										
					LoadValues();
								
					//Initiate POST
					var uri = "<?php echo site_url('Uploadvideos/AddVideos');?>";
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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

					if (video_file != null) fd.append('video_file', video_file);			

					fd.append('category', uploadcategory);			
					fd.append('bucket',bucket);
					fd.append('comedian',comedian);
					
					
					xhr.send(fd);// Initiate a multipart/form-data upload

					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video ERROR:\n'+e;
					
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
								"targets": [ 0,1,2,3,4,5,6,7 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7 ] }
						],//Title,Category,Size,Duration,Date_Created,Filename,Status,[Preview]
						columns: [
							{ width: "27%", },//VIDEO TITLE
							{ width: "13%" },//CATEGORY
							{ width: "10%" },//VIDEO SIZE
							{ width: "10%" },//DURATION
							{ width: "10%" },//DATE CREATED
							{ width: "20%" },//FILENAME
							{ width: "5%" },//VIDEO STATUS
							{ width: "5%" }//PREVIEW VIDEO
						],
						order: [[ 0, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Uploadvideos/LoadVideosJson'); ?>',
							type: 'POST',
							data: {category:category,publisher_email:PublisherEmail,InputBucket:InputBucket,ThumbBucket:ThumbBucket},
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
						url: '<?php echo site_url('Uploadvideos/GetCategories'); ?>',
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
			
			var $input = $("#txtVideos");
			
			// the file input
			$input.fileinput({
					uploadUrl:"<?php echo site_url('Uploadvideos/UploadVideo');?>",
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
							comedian:comedian,
							bucket:bucket,
							publisher_email:PublisherEmail,
							PublisherName:PublisherName,
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
						m='No video category record has been captured into the database. Please contact the system administrator at <a href="mailto:support@laffub.com">support@laffhub.com</a>';
						
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
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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
						m='No video category record has been captured into the database. Please contact the support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>';
						
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
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
				}
            });
				
			$('#btnUploadVideo').click(function(e) {
                try
				{
					var cm=$('#cboComedian').val();
					
					//Comedian
					
					if ($('#cboComedian > option').length < 2)
					{
						m='No comedian record has been captured. Please contact our support team at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
						
						bootstrap_Upload_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divUploadAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						return false;
					}
					
					if (!cm)
					{
						m='Please select the comedian.';
						
						bootstrap_Upload_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divUploadAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#cboComedian').focus(); return false;
					}
					
					
					//Confirm Upload
					if (!confirm('This process may take some time depending on your internet bandwidth and/or the total size of video(s) you are uploading. Are you sure you want to upload?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
					
					uploadcategory=$('#lblCategory').html();
					bucket=$('#lblInputFolder').html();
					
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}catch(e)
				{
					$.unblockUI();
					m='File ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}catch(e)
				{
					$.unblockUI();
					m='Trigger File Batch Upload ERROR:\n'+e;
					
					bootstrap_Upload_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divUploadAlert').fadeOut('fast');
							}, 10000);
						}
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
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						 return false;
					}
					
					if (!fn)
					{
						m='No filename is selected. Update cannot continue.';
						
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
						
						return false;
					}
					
					//Video Title
					if (!tit)
					{
						m='Video title field must not be blank.';
						
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
						
						$('#txtTitle').focus(); return false;
					}
					
					if ($.isNumeric(tit))
					{
						m='Video title field must not be a number.';
						
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
						
						$('#txtTitle').focus(); return false;
					}		
					
					//Description
					if (!desc)
					{
						m='Video description field must not be blank.';
						
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
						
						$('#txtDescription').focus(); return false;
					}
					
					if ($.isNumeric(desc))
					{
						m='Video description field must not be a number.';
						
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
						
						$('#txtDescription').focus(); return false;
					}
					
					//Confirm Upload
					if (!confirm('Updating the selected video record will permanently modify the record and it is irreversible. Do you want to proceed with the updating of the video?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={category:cat, filename:fn,video_title:tit,description:desc,publisher_email:PublisherEmail,publisher_name:PublisherName};
										
					$.ajax({
						url: "<?php echo site_url('Uploadvideos/UpdateVideo');?>",
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
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
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
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
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
					});
				}catch(e)
				{
					$.unblockUI();
					m='Upload Video Button Click ERROR:\n'+e;
					
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
            });
			
			function AddMovieDetails(files)
			{
				try
				{
					var cat=$('#cboCategory').val();
					
					if ($('#cboCategory > option').length < 2)
					{
						m='No video category record has been captured into the database. Please contact the system administrator at <a href="mailto:support@laffhub.com>support@laffhub.com</a>';
						
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
						
						$('#cboCategory').focus(); return false;
					}
					
					if (!cat)
					{
						m='Please select a category.';
						
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
							url: '<?php echo site_url('Uploadvideos/LoadVideoDetailsJson'); ?>',
							type: 'POST',
							data: {category:cat,files:files,publisher_email:PublisherEmail},
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
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
				if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;
				//$('#lblAddCategory').html('');
				$('#txtTitle').val('');
				$('#txtDescription').val('');	
				$('#lblFilename').html('');
				$('#cboComedian').val('');
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
		
		function ShowVideo(preview_url,title,ext,preview_img)
		{
			//alert(preview_url+"."+ext);
			
			jwplayer.key='<?php echo $jwplayer_key; ?>';
			jwplayer("idModalBody").setup({
			 image: preview_img,
			 file: preview_url+"."+ext,
			 width: "100%",
			 aspectratio: "16:9"
			});
			
			//width: "640",
			//height: "360",

			
					
	//alert(src);							
			$('#idModalTitle').html(title);
			//$('#idModalBody').html(src);
			$('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
		}
		
		function ShowVideo_Encoded(preview_url,title,ext,preview_img)
		{
			
			//fn.'_360p.'.$ext
			jwplayer.key='<?php echo $jwplayer_key; ?>';
			jwplayer("idModalBody").setup({
			 image: preview_img,
			  sources: [
				{ file: preview_url+"_360p."+ext, label: "360p SD" },
				 { file: preview_url+"_480p."+ext, label: "480p SD" },
				{ file: preview_url+"_720p."+ext, label: "720p HD" }
			  ],
			 width: "100%",
			 aspectratio: "16:9"//4:3
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
                  Role:&nbsp;&nbsp;<span class="hidden-xs yellowtext">Publisher</span>
                </a>
              </li>
               
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span> <span class="hidden-xs yellowtext"><?php echo $publisher_name.' ('.$publisher_email.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Email">
                    <p><b>Email:</b> <?php echo '<span class="yellowtext">'.$publisher_email.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="Publisher Name">
                    <p><b>Name:</b> <?php echo '<span class="yellowtext">'.$publisher_name.'</span>'; ?></p>
                  </li>
                  
                 <!--Role-->
				 <li class="user-body"  title="User Role">  	
                    <p><b>Role:</b> <span class="yellowtext">Publisher</span></p>
                </li>
                     <!--Category End-->          
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?php echo site_url("Publogout"); ?>" class="btn btn-danger btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
     	<?php include('pubsidemenu.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
         <h4>
            
          </h4>
          
          <ol class="breadcrumb size-16">
            <li><a href="<?php echo site_url("Publogout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
       

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          		<div class="col-md-12">
          			<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-file-video-o"></i> Upload Videos</div>
                <div class="panel-body">                
             
                <!--Tab Details-->
				<div class="tab-content">
                    <div class="box-body">
                        <!--Video category-->
                        <div class="form-group">
                            <label style="width:auto;" class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category</label>
                            
                            <div class="col-sm-3" title="Select Video category">
                              <select style="padding-bottom:2px; padding-top:2px;" id="cboCategory" class="form-control"></select>
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
                
                <button style="width:auto; margin-left:10px;" id="btnAddVideoDetails" type="button" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Update Video Details
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
      
      <div class="form-group hide" title="Amazon Input Folder">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblInputFolder">AWS Input Folder</label>
        <div class="col-sm-10">
           <label id="lblInputFolder" class="form-control"></label>
        </div>
      </div>
      
      <div class="form-group hide" title="Amazon Storage Path">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="lblStoragePath">AWS Storage Path</label>
        <div class="col-sm-10">
           <label id="lblStoragePath" class="form-control"></label>
        </div>
      </div>
      
      <div class="form-group" title="Comedian">
        <label style="width:140px" class="col-sm-2 control-label size-12" for="cboComedian">Comedian<span class="redtext">*</span></label>
        <div class="col-sm-10">
           <select style="padding-bottom:2px; padding-top:2px;" id="cboComedian" class="form-control"></select>
        </div>
      </div>
      
            
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
      
      <div align="center" class="redtext" style="margin-bottom:20px;">By uploading your videos to this platform, you acknowledge that you agree to Laffhub’s <a href="#">Terms of service</a>.</div>
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
              <button id="btnUploadClose" type="button" class="btn btn-info btn-block btn-flat" data-dismiss="modal">Close</button>            
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
      
      <div class="form-group" title="Videoss Description">
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
         
          <button style="width:100px; margin-left:10px; height:75px;" id="btnUpdateClose" type="button" class="btn btn-info btn-flat" data-dismiss="modal">Close</button> 
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

 </body>
</html>
