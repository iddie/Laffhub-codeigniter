<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Activate Video</title>
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
		
		
		var Title='<font color="#AF4442">Activate Video Help</font>';
		var m='';
		
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,uploadcategory,bucket;
		var editdata,seldata,table_pipelines,table_jobs;
		var InputBucket='<?php echo $input_bucket; ?>';
		var OutputBucket='<?php echo $output_bucket; ?>';
		var ThumbBucket='<?php echo $thumbs_bucket; ?>';
				
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			if (RefreshDuration) RefreshDuration=RefreshDuration*1000;
			
			$('#btnEncode').prop('disabled',true);
			
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
					$('#hidPublisher').val(pb);
					
					$('#lblPublisher').html(pb);
					$('#cboPublisher').val(pb);
					$('#cboCategory').val(cat);
					
					$('#btnEncode').prop('disabled',false);
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
			
			LoadPublishers();
						
			function LoadPublishers()
			{
				try
				{
					$('#cboPublisher').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Publishers. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Previewvideo/GetPublishers'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboPublisher').append( new Option('[ALL PUBLISHERS]','ALL') );
		
								$.each($(data), function(i,e)
								{
									if (e.publisher_name && e.publisher_email)
									{
										$('#cboPublisher').append( new Option(e.publisher_name+' ('+e.publisher_email+')',e.publisher_email) );
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
					m='LoadPublishers Module ERROR:\n'+e;
					
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
						
			$('#btnEncodeClose').click(function(e) {
                try
				{
					$('#btnPipelineRefresh').trigger('click');
					LoadPipelineNames();
					activateTab('tabJob');
				}catch(e)
				{
					$.unblockUI();
					m="Encode Modal Close Button Click ERROR:\n"+e;
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
			
			$('#btnJobClose').click(function(e) {
                try
				{
					$('#btnJobRefresh').trigger('click');
					LoadPipelineNames();
					activateTab('tabJob');
				}catch(e)
				{
					$.unblockUI();
					m="Job Close Button Click ERROR:\n"+e;
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
            });
						
			LoadPipelineNames();
			
			function LoadPipelineNames()
			{
				try
				{
					$('#cboPipelineName').empty();
					$('#cboJobInputFileName').empty();
					
					$('#lbl360p').html('');
					$('#lbl720p').html('');
					$('#lbl1080p').html('');
					
					$('#recorddisplay_pipelines > tbody').html('');
					$('#recorddisplay_jobs > tbody').html('');
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Pipeline Names. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Activatevideo/GetPipeLineNames'); ?>',
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
				}
			}		

			
			function LoadVideos(publisher,category,status)
			{
				try
				{					
					table = $('#recorddisplay').DataTable( {
						 select: true,
						 
						dom: '<"top"if>rt<"bottom"lp><"clear">',
						destroy:true,
						autoWidth:false,
						language: {zeroRecords: "No Video Record Found"},
						lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
						columnDefs: [ 
							{
								"targets": [ 10,11 ],
								"visible": false
							},
							{
								"targets": [ 0,1,2,3,4,5,6,7,8,9 ],
								"visible": true,
								"searchable": true,
								"orderable": true
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8,9,10,11 ] }
						],//[SELECT],PublisherEmail,Category,Title,Size,Duration,Date_Created,Encoded,VideoStatus,[Preview],Filename,VideoCode
						columns: [
							{ width: "5%", },//SELECT
							{ width: "18%", },//PUBLISHER EMAIL
							{ width: "12%" },//CATEGORY
							{ width: "22%", },//VIDEO TITLE							
							{ width: "9%" },//VIDEO SIZE
							{ width: "9%" },//DURATION
							{ width: "10%" },//DATE CREATED
							{ width: "4%" },//ENCODED
							{ width: "5%" },//VIDEO STATUS
							{ width: "6%" },//PREVIEW VIDEO
							{ width: "0%" },//FILENAME
							{ width: "0%" }//VIDEO CODE
						],
						order: [[ 1, 'asc' ],[ 2, 'asc' ],[ 3, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Activatevideo/LoadVideosJson'); ?>',
							type: 'POST',
							data: {category:category, publisher:publisher, status:status, InputBucket:InputBucket, ThumbBucket:ThumbBucket},
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
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Data. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Activatevideo/GetCategories'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							if ($(data).length > 0)
							{																									
								$('#cboCategory').append( new Option('[ALL CATEGORIES]','ALL') );
		
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
					$('#cboPublisher').val('ALL');
					$('#cboCategory').val('ALL');
					$('#cboStatus').val('');
					$('#hidFilename').val('');
					$('#hidVideoCode').val('');
					$('#hidPublisher').val('');
					
					$('#cboPublisher').val('ALL');
					
					$('#lblPublisher').html('');
					$('#cboCategory').val('ALL');
					
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
					
					var pub=$('#cboPublisher').val();
					var cat=$('#cboCategory').val();
					var sta=$('#cboStatus').val();
										
					if (!sta)
					{
						m='Please select the status of the videos you want to display.';
						
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
						
						$('#cboStatus').focus(); return false;
					}
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Display videos
					LoadVideos(pub,cat,sta);
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#cboCategory').focus(); return false;
					}
					
					/*
					if (!cat)
					{
						m='Please select a category.';
						
						bootstrap_Pipeline_alert.warning(m);
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
					*/
					
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
							url: '<?php echo site_url('Activatevideo/LoadPipeLines'); ?>',
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
					m='LoadPipeLines ERROR:\n'+e;
					
					bootstrap_Pipeline_alert.warning(m);
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
					
					/*
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
					*/
					
					$('#lblEncodeCategory').html(cat);
//alert('Filename => '+fn+'\nVideo Code => '+cd);					
					LoadPipeLines();
					
					$('#divEncodeVideoModal').modal({backdrop:'static',keyboard:false, show:true});
				}catch(e)
				{
					$.unblockUI();
					m='Encode Video Button Click ERROR:\n'+e;
					
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
					});
						
						return false;
					}
					
					if (!pn)
					{
						m='Pipeline name field must not be blank.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
					});
						
						$('#txtPipelineName').focus(); return false;
					}
					
					if ($.isNumeric(pn))
					{
						m='Pipeline name field must not be a number.';
						
						bootstrap_Pipeline_alert.warning(m);
						bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
					});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
					});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Pipeline_alert.warning(m);
								bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divPipelineAlert').fadeOut('fast');
							}, 10000);
						}
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
					$('#lbl720p').html('');
					$('#lbl1080p').html('');
					
					var pid=$(this).val();
					var cat=$('#lblEncodeCategory').html();
					var pub=$('#lblPublisher').html();
					
					if (pid)
					{
						LoadInputFilenames(pid,cat,pub);//Dropdown
						LoadJobs(pid);//Table
					}
					
				}catch(e)
				{
					$.unblockUI();
					m='Pipeline Name Change ERROR:\n'+e;
					
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
            });
			
			$('#cboJobInputFileName').change(function(e) {
                try
				{
					$('#lbl360p').html(''); $('#lbl720p').html(''); $('#lbl1080p').html('');
					
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
							var t=fn.split('.');
							
							$('#lbl360p').html(cat+'/'+$.trim(t[0])+'_360p.'+t[1]);
							$('#lbl720p').html(cat+'/'+$.trim(t[0])+'_720p.'+t[1]);
							$('#lbl1080p').html(cat+'/'+$.trim(t[0])+'_1080p.'+t[1]);
						}
					}					
				}catch(e)
				{
					$.unblockUI();
					m='Pipeline Name Change ERROR:\n'+e;
					
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
						url: '<?php echo site_url('Activatevideo/GetJobInputFileNames'); ?>',
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
							url: '<?php echo site_url('Activatevideo/LoadJobs'); ?>',
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
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
					$('#lbl720p').html('');
					$('#lbl1080p').html('');
					$('#recorddisplay_jobs > tbody').html('');
				}catch(e)
				{
					$.unblockUI();
					m='Create Job Refresh Button Click ERROR:\n'+e;
					
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
            });
            
			$('#btnCreateJob').click(function(e) {
				try
				{
					$('#divAlert').html('');
					$('#divJobAlert').html('');
					
					var cat=$('#lblEncodeCategory').html();
					var pub=$.trim($('#lblPublisher').html());
					var pn=$.trim($('#cboPipelineName').val());
					//var jb=$('#cboJobInputFileName').val();
					var jb=$('#cboJobInputFileName :selected').text();
					
					var p360=$('#lbl360p').html();
					var p720=$('#lbl720p').html();
					var p1080=$('#lbl1080p').html();
										
					if (!cat)
					{
						m='No Video category Was Selected. Creating Of Job Cannot Continue.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						return false;
					}
					
					if ($('#cboPipelineName > option').length < 2) 
					{
						m='No pipeline record was pulled from the Amazon storage. Please check your internet connection.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						return false;
					}
					
					if (!pn)
					{
						m='Pipeline name must be selected.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						return false;
					}
					
					if (!jb)
					{
						m='Job filename must be selected.';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#cboJobInputFileName').focus(); return false;
					}
					
					var t=jb.split('/');
					var f=t[t.length-1];
					var flag=$.trim($('#cboJobInputFileName').val()).toUpperCase();
					
					if (flag=='NO')
					{
						m='Title and description of the video "'+f.toUpperCase()+'" have not been captured by the publisher. Publisher must capture the video title and description before you can encode the file. Please contact the publisher to update the video title and description';
						
						bootstrap_Job_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function()
							{								
								$('#cboPipelineName').val('');
								$('#cboJobInputFileName').empty();
								
								$('#lbl360p').html('');
								$('#lbl720p').html('');
								$('#lbl1080p').html('');
								$('#recorddisplay_jobs > tbody').html('');
								activateTab('tabJob');
					
								$('#divEncodeVideoModal').modal('hide');																	
							}
						});						
						
						return false;
					}
					
					//Confirm pipeline creation
					if (!confirm('This action will create a new job record on the Amazon Web Services and it is irreversible. Do you want to proceed with the creation of the job?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={category:cat,pipelineid:pn,inputkey:jb,outputfile360:p360,outputfile720:p720,outputfile1080:p1080,publisher:pub};
										
					$.ajax({
						url: "<?php echo site_url('Activatevideo/CreateJob');?>",
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
								$('#lbl360p').html(''); $('#lbl720p').html(''); $('#lbl1080p').html('');
								
								LoadJobs(pn);
								
								bootstrap_SuccessJob_alert.warning(m);
								bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_Job_alert.warning(m);
								bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
						});							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_Job_alert.warning(m);
								bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
								}, 10000);
							}
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
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divJobAlert').fadeOut('fast');
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
					$('#btnEncode').prop('disabled',true);
					
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
					
					$('#btnEncode').prop('disabled',false);
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
              <div class="panel-heading size-20"><i class="glyphicon glyphicon-ok-sign"></i> Activate Video</div>
                <div class="panel-body">
                    <div class="box-body">
                        <form class="form-horizontal">
                        <!--Publisher-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="cboPublisher" title="Select Publisher">Publisher</label>
                            
                            <div class="col-sm-7" title="Select Publisher">
                              <select id="cboPublisher" class="form-control" style="padding:3px;"></select>
                            </div>
                            
                            <span><!--Buttons-->                             
                                <button style="width:150px; float:right; margin-right:20px;" id="btnDisplay" type="button" class="btn btn-info"><span class="glyphicon glyphicon-play-circle" ></span> Load Videos</button>
                             </span>
                             
                             <input type="hidden" id="hidFilename">
                             <input type="hidden" id="hidVideoCode">
                             <input type="hidden" id="hidPublisher">
                       </div>
                       
                       <!--Video category/Video Status-->
                        <div class="form-group">
                            <!--Video category-->
                            <label class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category</label>
                            
                            <div class="col-sm-3" title="Select Video category">
                              <select id="cboCategory" class="form-control" style="padding:3px;"></select>
                            </div>
                            
                            <!--Video Status-->
                            <label class="col-sm-2 control-label" for="cboStatus" title="Select Video Status">Video Play Status</label>
                            
                            <div class="col-sm-2" title="Select Video Play Status">
                              <select id="cboStatus" class="form-control" style="padding:3px;">
                              	<option value="">[SELECT]</option>
                                <option value="1">Activated</option>
                                <option value="0">Not Activated</option>
                              </select>
                            </div>
                            
                            <span><!--Refresh Button-->                             
                                <button style="width:150px; float:right; margin-right:20px;" id="btnRefreshDisplay" type="button" class="btn btn-warning"><i class="material-icons">refresh</i> Reset Display</button>
                             </span>
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
                                <th>SELECT</th>
                                <th>PUBLISHER&nbsp;EMAIL</th>
                                <th>CATEGORY</th>
                                <th>TITLE</th>
                                <th>SIZE</th>
                                <th>DURATION</th>
                                <th>DATE&nbsp;CREATED</th>                             
                                <th>ENCODED</th>
                                <th>STATUS</th>
                                <th>PREVIEW</th><!--Preview-->
                                <th class="hide">FILENAME</th>
                                <th class="hide">VIDEOCODE</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                   </center>
                
             
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
               </div>
                                   
                 
                <div align="center" style="margin-top:30px; ">                
               <button title="Click This Button To Encode And Activate A Video" style="width:auto; margin-left:10px;" id="btnEncode" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-check"></i> Encode/Activate Video
                </button>
                
                <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-danger" role="button" style="text-align:center; width:140px; margin-left:10px;"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
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
        <label class="col-sm-2 control-label size-12" for="lblEncodeCategory">Video Category</label>
        <div class="col-sm-4">
           <label style="background-color:#FBEEEE; color:#D9383B;" id="lblEncodeCategory" class="form-control"></label>
        </div>
        
        <!--Selected Publisher-->
        <label class="col-sm-2 control-label size-12" for="lblPublisher">Publisher</label>
        <div class="col-sm-4">
           <label style="background-color:#FBEEEE; color:#D9383B;" id="lblPublisher" class="form-control"></label>
        </div>
      </div>
      
      <!--############################################ Tab #################################################-->
        <ul class="nav nav-tabs " style="font-weight:bold;">
          <li class="active"><a data-toggle="tab" href="#tabJob"><i class="fa fa-tasks"></i> Create Job</a></li>
          
          <li><a data-toggle="tab" href="#tabPipeline"><i class="fa fa-cogs"></i> Create Pipeline</a></li>
        </ul>
      <!--########################################## Tab Ends ###########################################-->
  
  <!--########################################## Tab Contents ###########################################-->   
  <div class="tab-content" id="tabs">
        <!--#################### Create Job ################--> 
        <div class="tab-pane fade in active" id="tabJob">
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
                   <label style="font-weight:normal;" class="form-control" id="lbl360p" title="Generic Preset 360p (16:9)"></label>
                </div>
                
                <div class="col-sm-3">
                   <label style="font-weight:normal;" class="form-control" id="lbl720p" title="Generic Preset 720p (4:3)"></label>
                </div>
                
                <div class="col-sm-3">
                   <label style="font-weight:normal;" class="form-control" id="lbl1080p" title="Generic Preset 1080p"></label>
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
        
        
        <!--#################### Create Pipeline Pane ################-->
        <div class="tab-pane fade" id="tabPipeline">
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

