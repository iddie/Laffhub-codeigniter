<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Preview Uploaded Videos</title>
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
		
		bootstrap_edit_alert = function() {}
		bootstrap_edit_alert.warning = function(message) 
		{
		   $('#divEditAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
				
		bootstrap_Success_edit_alert = function() {}
		bootstrap_Success_edit_alert.warning = function(message) 
		{
		   $('#divEditAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
		}
		
		var Title='<font color="#AF4442">Preview Videos Help</font>';
		var m='';
		
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,bucket;
		var editdata,seldata;
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
			
			if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;

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
			
			
			$('#btnEditRefresh').click(function(e) {
                try
				{
					$('#txtTitle').val('');
					$('#txtDescription').val('');
				}catch(e)
				{
					$.unblockUI();
					m="Edit Modal Refresh Button Click ERROR:\n"+e;
					bootstrap_edit_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divEditAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			$('#btnEdit').click(function(e) {
                try
				{
					var cat=$('#lblCategory').html();
					var fn=$('#lblFilename').html();
					var tit=$('#txtTitle').val();
					var desc=$('#txtDescription').val();
					var pub=$('#cboPublisher').val();
					
					if (!pub)
					{
						m='No publisher has been selected. Update cannot continue.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						 return false;
					}
					
					if (!cat)
					{
						m='No category is selected. Update cannot continue.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						 return false;
					}
					
					if (!fn)
					{
						m='No filename is selected. Update cannot continue.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						return false;
					}
					
					//Video Title
					if (!tit)
					{
						m='Video title field must not be blank.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						$('#txtTitle').focus(); return false;
					}
					
					if ($.isNumeric(tit))
					{
						m='Video title field must not be a number.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						$('#txtTitle').focus(); return false;
					}		
					
					//Description
					if (!desc)
					{
						m='Video description field must not be blank.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						$('#txtDescription').focus(); return false;
					}
					
					if ($.isNumeric(desc))
					{
						m='Video description field must not be a number.';
						
						bootstrap_edit_alert.warning(m);
						bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divEditAlert').fadeOut('fast');
									}, 10000);
								}
							});
						
						$('#txtDescription').focus(); return false;
					}
					
					//Confirm Upload
					if (!confirm('Are you sure you want to edit the selected video details?  Click "OK" to proceed or "CANCEL" to abort!'))
					{
						return false;
					}
										
					var mydata={category:cat, filename:fn,video_title:tit,description:desc,publisher_email:pub,Username:Username,UserFullName:UserFullName};
										
					$.ajax({
						url: "<?php echo site_url('Previewvideo/EditVideo');?>",
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
								$('#txtTitle').val('');
								$('#txtDescription').val('');	
								$('#lblFilename').html('');
																						
								m='Video "'+tit.toUpperCase()+'" With Filename "'+fn.toUpperCase()+'" Was Updated Successfully.';
								
								//reload ajax table
								table.ajax.reload( function ( json ) 
								{
									$('#btnEditClose').trigger('click');
								} );
									
								bootstrap_Success_edit_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divEditAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}else
							{
								$.unblockUI();
								
								m=data;
								
								bootstrap_edit_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divEditAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}		
						},
						error:  function(xhr,status,error) {
								$.unblockUI();
								
								m='Error '+ xhr.status + ' Occurred: ' + error;
								bootstrap_edit_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divEditAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}
					});
				}catch(e)
				{
					$.unblockUI();
					m='Edit Video Details Button Click ERROR:\n'+e;
					
					bootstrap_edit_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divEditAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			function LoadVideos(category,status,publisher)
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
							{
								"targets": [ 9 ],
								"visible": false,
								"searchable": false,
								"orderable": false
							},
							{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8,9 ] }
						],//[EDIT],Title,Category,Size,Duration,Date_Created,Filename,Status,[Preview],Description
						columns: [
							{ width: "2%", },//edit
							{ width: "25%", },//VIDEO TITLE
							{ width: "13%" },//CATEGORY
							{ width: "10%" },//VIDEO SIZE
							{ width: "10%" },//DURATION
							{ width: "10%" },//DATE CREATED
							{ width: "18%" },//FILENAME
							{ width: "5%" },//VIDEO STATUS
							{ width: "5%" },//PREVIEW VIDEO
							{ width: "0%" }//DESCRIPTION
						],
						order: [[ 1, 'asc' ]],
						ajax: {
							url: '<?php echo site_url('Previewvideo/LoadVideosJson'); ?>',
							type: 'POST',
							data: {category:category,InputBucket:InputBucket,ThumbBucket:ThumbBucket,status:status,publisher:publisher},
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
			
			LoadCategories();
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
								$('#cboPublisher').append( new Option('[SELECT]','') );
		
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
			
			function LoadCategories()
			{
				try
				{
					$('#cboCategory').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Categories. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'json',
						url: '<?php echo site_url('Previewvideo/GetCategories'); ?>',
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
					$('#cboPublisher').val('');
					$('#cboStatus').val('All');
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
					$('#recorddisplay > tbody').html('');
					
					var cat=$('#cboCategory').val();
					var pub=$('#cboPublisher').val();
					var sta=$('#cboStatus').val();
					
					//Publisher
					if ($('#cboPublisher > option').length < 2)
					{
						m='There is no publisher record in the database.';
						
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
						
						$('#cboPublisher').focus(); return false;
					}
					
					if (!pub)
					{
						m='Please select the publisher whose videos you want to preview.';
						
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
						
						$('#cboPublisher').focus(); return false;
					}
					
					//Category
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
					
					/*
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
					*/
					
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Display videos
					LoadVideos(cat,sta,pub);
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
        });//End document ready
		
		function GetRow(sn,img)
		{
			$('#lblCategory').val('');
			$('#lblFilename').html('');
			$('#txtTitle').val('');
			$('#txtDescription').val('');
			$('#imgVideo').prop('src','');
			
			if (sn>-1)
			{
				var row = table.row( sn ).data();
				
				if (row)
				{
					var cat=row[2],tit=row[1],des=row[9],fn=row[6];
										
	//[EDIT],Title,Category,Size,Duration,Date_Created,Filename,Status,[Preview],Description
					$('#lblCategory').html(cat);
					$('#lblFilename').html(fn);
					$('#txtTitle').val(tit);
					$('#txtDescription').val(des);
					$('#imgVideo').prop('src', img);
					
					$('#divEditModal').modal({backdrop:'static',keyboard:false, show:true});
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
            <li><a href="<?php echo site_url("logout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
       

        <!-- Main content -->
        <section class="content">
          	<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-file-video-o"></i> Preview Videos</div>
                <div class="panel-body">
                	<form class="form-horizontal">
                         <!--Publishers/Video category-->
                        <div class="form-group">
                            <!--Publishers-->
                            <label style="margin-top:5px;" class="col-sm-2 control-label" for="cboPublisher" title="Select Publisher">Publisher<span class="redtext">*</span></label>
                            
                            <div class="col-sm-5" title="Select Publisher">
                              <select id="cboPublisher" class="form-control"></select>
                            </div>
                            
                            <!--Video Categories-->
                             <label style="margin-top:5px;" class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category<span class="redtext">*</span></label>
                            
                            <div class="col-sm-3" title="Select Video category">
                              <select id="cboCategory" class="form-control"></select>
                            </div>
                       </div>
                       
                        <!--Status-->
                       	<div class="form-group ">                            
                            <!--Status-->
                            <label style="margin-top:5px;" class="col-sm-2 control-label" for="cboStatus" title="Select Video Status">Video Status</label>
                            
                            <div class="col-sm-2" title="Select Video Status">
                              <select id="cboStatus" class="form-control">
                                <option value="All">[ALL STATUS]</option>
                                <option value="1">Active</option>
                                <option value="0">Not Active</option>
                              </select>
                            </div>
                            
                            <span>
                             <!--Buttons-->
                                <button style="width:140px; " id="btnDisplay" type="button" class="btn btn-info"><span class="glyphicon glyphicon-play-circle" ></span> Display Videos</button>
                                
                                <button onClick="window.location.reload(true);" style="width:140px; margin-left:15px;" id="btnRefreshDisplay" type="button" class="btn btn-warning"><i class="material-icons">refresh</i> Refresh</button>
                             </span>
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
                                <th>EDIT</th>
                                <th>TITLE</th>
                                <th>CATEGORY</th>
                                <th>SIZE</th>
                                <th>DURATION</th>
                                <th>DATE&nbsp;CREATED</th>
                                <th>FILENAME</th>
                                <th>STATUS</th>
                                <th>PREVIEW</th><!--Preview-->
                                <th>DESCRIPTION</th>
                            </tr>
                          </thead>
                      </table>
                    </div>
                   </center>
                           
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





<!--******************************* EDIT modal **************************************************-->
<div class="modal fade" id="divEditModal" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true" style="margin-top:-15px;">
   <div class="modal-dialog" style="width: 75%; height: 95%; ">
       <div class="modal-content" style="height: auto; border-radius: 0; color:white; overflow:hidden;">
            <!--*****************UPLOAD******************-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" title="Click To Close The Edit Form">
                    <span aria-hidden="true" class=" size-55">&times;</span><span class="sr-only">Close</span>
                </button>
                
                <h3  id="modal-register-label"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%; font-size: 3em; font-weight: 500; margin: 0 0 5px 0;">Edit Video Details</h3>
            </div>

			<div class="register-box form-bottom modal-body" style="width:100%;">
              <div class="register-box-body">
                <form class="form-horizontal" role="form" data-inset="true" style="margin-top:-100px;">
                  
                  <div class="form-group" title="Video">
                    <label class="col-sm-2 control-label size-12" for="txtVideos">Video</label>
                    
                    <div class="col-sm-10" title="Videos">
                          <img id="imgVideo" height="150px;"/>
                    </div>
                  </div>
                  
                  <div class="form-group" title="Video Category">
                    <label class="col-sm-2 control-label size-12" for="lblCategory">Video Category</label>
                    <div class="col-sm-10">
                       <label id="lblCategory" class="form-control"></label>
                    </div>
                  </div>
                  
                  <div class="form-group" title="Video Filename">
                    <label class="col-sm-2 control-label size-12" for="lblFilename">Filename</label>
                    <div class="col-sm-10">
                       <label id="lblFilename" class="form-control"></label>
                    </div>
                  </div>
                  
                  <div class="form-group" title="Video Title">
                    <label class="col-sm-2 control-label size-12" for="txtTitle">Title<span class="redtext">*</span></label>
                    
                    <div class="col-sm-10">
                       <input id="txtTitle" type="text" class="form-control" placeholder="Video Title">
                    </div>
                  </div>
                  
                  <div class="form-group" title="Video Description">
                    <label class="col-sm-2 control-label size-12" for="txtDescription">Description<span class="redtext">*</span></label>
                    <div class="col-sm-10">
                       <textarea rows="4" id="txtDescription" class="form-control" placeholder="Video Description"></textarea>
                    </div>
                  </div>
                              
                        
                        
                   <div align="center">
                        <div id = "divEditAlert"></div>
                   </div>
                  
                  <!--Buttons Row-->
                  <div class="row">  
                     <div class="col-xs-3">&nbsp;</div>
                           
                    <!-- /.col -->
                    <div class="col-xs-2" title="Click To Upload">
                      <button id="btnEdit" type="button" class="btn btn-primary btn-block btn-flat">Edit Details</button>
                    </div>
                   
                   <div class="col-xs-2" title="Click To Refresh">
                      <button id="btnEditRefresh" type="button" class="btn btn-warning btn-block btn-flat">Refresh</button>
                    </div>
                    
                     <div class="col-xs-2">
                      <div title="Click To Close The Upload Form">
                          <button id="btnEditClose" type="button" class="btn btn-info btn-block btn-flat" data-dismiss="modal">Close</button>            
                      </div>
                    </div> 
                    
                  </div>
                </form>
              </div>
              <!-- /.form-box -->
            </div>
         </div>
       </div>
   </div>
<!--*****************EDIT END******************-->
 </body>
</html>
