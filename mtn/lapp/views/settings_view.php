<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Portal Settings</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="stylesheet" href="<?php echo base_url();?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
    
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">

 	<link rel="stylesheet" href="<?php echo base_url();?>css/AdminLTE.min.css">
    
    <?php include('homelink.php'); ?>
    
    <script>
		var Title='<font color="#AF4442">Portal Settings Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var no_of_videos_per_day='<?php echo $no_of_videos_per_day; ?>';
		var companylogo='<?php echo $companylogo; ?>';
		var default_network='<?php echo $default_network; ?>';
		var RefreshDuration='<?php echo $RefreshDuration; ?>';
		var InputBucket='<?php echo $input_bucket; ?>';
		var OutputBucket='<?php echo $output_bucket; ?>';
		var ThumbBucket='<?php echo $thumbs_bucket; ?>';
		
		var CompanyName,CompanyEmail,CompanyPhone,Website,DefaultNetwork,VideoCount,RefreshSeconds,GoogleKey;
		var JWKey, JWSecret, JWPlayerID,emGSM,emEmails,SmsURL,SmsUsername,SmsPWD,AwsKey,AwsSecret;
		var JWPlayerKey;
	
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
				$.blockUI.defaults.css = {};// clear out plugin default styling
			});
		
			$(document).ajaxStop($.unblockUI);
			
			LoadNetwork();
			LoadBuckets();
						
			function LoadBuckets()
			{
				try
				{
					$('#cboInputBucket').empty();
					$('#cboOutputBucket').empty();
					$('#cboThumbBucket').empty();
					
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Buckets. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$.ajax({
						type: "POST",
						dataType: 'text',
						url: '<?php echo site_url('Settings/GetAmazonBuckets'); ?>',
						complete: function(xhr, textStatus) {
							$.unblockUI;
						},
						success: function(data,status,xhr) //we're calling the response json array 'cntry'
						{
							$.unblockUI();
							
							var ret=$.trim(data);
							
							if (ret.length > 0)
							{		
								var b=ret.split('^');
								
								if (b.length>0)
								{
									$('#cboInputBucket').append( new Option('[SELECT INPUT BUCKET]','') );
									$('#cboOutputBucket').append( new Option('[SELECT OUTPUT BUCKET]','') );
									$('#cboThumbBucket').append( new Option('[SELECT THUMBNAIL BUCKET]','') );
									
									for (var i=0; i<b.length; i++)
									{
										$('#cboInputBucket').append( new Option(b[i],b[i]) );
										$('#cboOutputBucket').append( new Option(b[i],b[i]) );
										$('#cboThumbBucket').append( new Option(b[i],b[i]) );
									}
								}
								
								if ($('#cboInputBucket > option').length > 2) $('#cboInputBucket').val(InputBucket);
								if ($('#cboOutputBucket > option').length > 2) $('#cboOutputBucket').val(OutputBucket);
								if ($('#cboThumbBucket > option').length > 2) $('#cboThumbBucket').val(ThumbBucket);																						
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
					 }); //end AJAX
				}catch(e)
				{
					$.unblockUI();
					m='LoadBuckets Module ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			function LoadNetwork()
			{
				try
				{
					$('#cboNetwork').empty();
					$('#cboNetwork').append( new Option('[SELECT]','') );
					$('#cboNetwork').append( new Option('Airtel','Airtel') );
					$('#cboNetwork').append( new Option('Etisalat','Etisalat') );
					$('#cboNetwork').append( new Option('GLO','GLO') );					
					$('#cboNetwork').append( new Option('MTN','MTN') );
					
					$('#cboNetwork').val(default_network);
				}catch(e)
				{
					$.unblockUI();
					m='LoadNetwork Module ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			}
			
			if (companylogo)
			{
				$('#imgLogo').prop('src','<?php echo base_url();?>images/'+companylogo);
			}else
			{
				$('#imgLogo').prop('src',emptypix);
			}
			
			$('#btnUpdate').click(function(e) {
				try
				{
					if (!CheckForm()) return false;
			
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Update Portal Settings. Please Wait...</p>',theme: true,baseZ: 2000});
										
					LoadValues();
								
					//Initiate POST
					var uri = "<?php echo site_url('Settings/Update');?>";
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
								m='Portal Setting Has Been Updated Successfully.';
																
								ResetControls();
										
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

					if (logo_pix != null) fd.append('logo_pix', logo_pix);			

					fd.append('no_of_videos_per_day', VideoCount);			
					fd.append('companyname',CompanyName);
					fd.append('companyemail', CompanyEmail);
					fd.append('companyphone', CompanyPhone);
					fd.append('website', Website);
					fd.append('RefreshDuration', RefreshSeconds);
					fd.append('default_network', DefaultNetwork);
					fd.append('google_shortener_api', GoogleKey);
					fd.append('jw_api_key', JWKey);
					fd.append('jw_api_secret', JWSecret);
					fd.append('jw_player_id', JWPlayerID);
					fd.append('username', Username);
					fd.append('UserFullName', UserFullName);
					fd.append('emergency_no', emGSM);
					fd.append('emergency_emails', emEmails);					
					fd.append('sms_url', SmsURL);
					fd.append('sms_username', SmsUsername);
					fd.append('sms_password', SmsPWD);
					fd.append('input_bucket', InputBucket);
					fd.append('output_bucket', OutputBucket);
					fd.append('thumbs_bucket', ThumbBucket);
					fd.append('aws_key', AwsKey);
					fd.append('aws_secret', AwsSecret);
					fd.append('jwplayer_key', JWPlayerKey);

					xhr.send(fd);// Initiate a multipart/form-data upload

					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Update Button Click ERROR:\n'+e;
					
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
            });//btnUpdate Click Ends
			
			function LoadValues()
			{
				try
				{
					CompanyName=$.trim($('#txtCompanyname').val());
					CompanyEmail=$.trim($('#txtEmail').val());
					CompanyPhone=$.trim($('#txtPhone').val());
					Website=$.trim($('#txtWebsite').val());
					DefaultNetwork=$.trim($('#cboNetwork').val());
					VideoCount=$.trim($('#txtVideos').val()).replace(new RegExp(',', 'g'), '');
					RefreshSeconds=$.trim($('#txtRefreshDuration').val()).replace(new RegExp(',', 'g'), '');
					GoogleKey=$.trim($('#txtGoogle').val());
					JWKey=$.trim($('#txtJWKey').val());
					JWSecret=$.trim($('#txtJWSecret').val());
					JWPlayerID=$.trim($('#txtJWPlayerID').val());					
					emGSM=$.trim($('#txtemGSM').val());
					emEmails=$.trim($('#txtemEmail').val());					
					SmsURL=$.trim($('#txtSmsUrl').val());
					SmsUsername=$.trim($('#txtSmsUsername').val());
					SmsPWD=$.trim($('#txtSmsPwd').val());
					InputBucket=$.trim($('#cboInputBucket').val());
					OutputBucket=$.trim($('#cboOutputBucket').val());
					ThumbBucket=$.trim($('#cboThumbBucket').val());
					AwsKey=$.trim($('#txtAwsKey').val());
					AwsSecret=$.trim($('#txtAwsSecret').val());
					JWPlayerKey=$.trim($('#txtJWPlayerKey').val());

					if (logo_pix==null) logo_pix='';
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
			
			function ResetControls()
			{
				try
				{
					Username='<?php echo $_SESSION['username']; ?>';
					UserFullName='<?php echo $_SESSION['UserFullName']; ?>';
					no_of_videos_per_day='<?php echo $_SESSION['no_of_videos_per_day']; ?>';
					companylogo='<?php echo $_SESSION['companylogo']; ?>';
					default_network='<?php echo $_SESSION['default_network']; ?>';
					RefreshDuration='<?php echo $_SESSION['RefreshDuration']; ?>';
					GoogleKey='<?php echo $_SESSION['google_shortener_api']; ?>';
					JWKey='<?php echo $_SESSION['jw_api_key']; ?>';
					JWSecret='<?php echo $_SESSION['jw_api_secret']; ?>';
					JWPlayerID='<?php echo $_SESSION['jw_player_id']; ?>';					
					emGSM='<?php echo $_SESSION['emergency_no']; ?>';	
					emEmails='<?php echo $_SESSION['emergency_emails']; ?>';					
					SmsURL='<?php echo $_SESSION['sms_url']; ?>';
					SmsUsername='<?php echo $_SESSION['sms_username']; ?>';
					SmsPWD='<?php echo $_SESSION['sms_password']; ?>';
					InputBucket='<?php echo $_SESSION['input_bucket']; ?>';
					OutputBucket='<?php echo $_SESSION['output_bucket']; ?>';
					ThumbBucket='<?php echo $_SESSION['thumbs_bucket']; ?>';
					AwsKey='<?php echo $_SESSION['aws_key']; ?>';
					AwsSecret='<?php echo $_SESSION['aws_secret']; ?>';
					JWPlayerKey='<?php echo $_SESSION['jwplayer_key']; ?>';
										
					$('#txtCompanyname').val('<?php echo $_SESSION['companyname']; ?>');
					$('#txtEmail').val('<?php echo $_SESSION['companyemail']; ?>');
					$('#txtPhone').val('<?php echo $_SESSION['companyphone']; ?>');
					$('#txtWebsite').val('<?php echo $_SESSION['website']; ?>');
					$('#cboNetwork').val(default_network);
					$('#txtVideos').val(no_of_videos_per_day);
					$('#txtRefreshDuration').val(RefreshDuration);
					$('#txtGoogle').val(GoogleKey);					
					$('#txtJWKey').val(JWKey);
					$('#txtJWSecret').val(JWSecret);
					$('#txtJWPlayerID').val(JWPlayerID);
					$('#txtemGSM').val(emGSM);
					$('#txtemEmail').val(emEmails);					
					$('#txtSmsUrl').val(SmsURL);
					$('#txtSmsUsername').val(SmsUsername);
					$('#txtSmsPwd').val(SmsPWD);
					$('#cboInputBucket').val(InputBucket);
					$('#cboOutputBucket').val(OutputBucket);
					$('#cboThumbBucket').val(ThumbBucket);					
					$('#txtAwsKey').val(AwsKey);
					$('#txtAwsSecret').val(AwsSecret);
					$('#txtJWPlayerKey').val(JWPlayerKey);
					
					$('#imgLogo').prop('src',emptypix);								
					$('#txtLogo').val('');
								
					if (companylogo)
					{
						$('#imgLogo').prop('src','<?php echo base_url(); ?>images/'+companylogo);								
						$('#txtLogo').val('');
					}
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
		
		
		function CheckForm()
		{
			try
			{
				var cmp=$.trim($('#txtCompanyname').val());
				var em=$.trim($('#txtEmail').val());
				var ph=$.trim($('#txtPhone').val());
				var web=$.trim($('#txtWebsite').val());
				var net=$.trim($('#cboNetwork').val());
				var no=$.trim($('#txtVideos').val());
				var ref=$.trim($('#txtRefreshDuration').val());		
				
				var gog=$.trim($('#txtGoogle').val());		
				var jwkey=$.trim($('#txtJWKey').val());		
				var jwsecret=$.trim($('#txtJWSecret').val());
				var jwplayid=$.trim($('#txtJWPlayerID').val());				
				var emph=$.trim($('#txtemGSM').val());
				var emem=$.trim($('#txtemEmail').val());				
				var url=$.trim($('#txtSmsUrl').val());
				var un=$.trim($('#txtSmsUsername').val());
				var pwd=$.trim($('#txtSmsPwd').val());
				var ibk=$('#cboInputBucket').val();
				var	obk=$('#cboOutputBucket').val();
				var	tbk=$('#cboThumbBucket').val();				
				var	awskey=$('#txtAwsKey').val();
				var	awssecret=$('#txtAwsSecret').val();
				var	jwpkey=$('#txtJWPlayerKey').val();
														
				//Username
				if (!Username)
				{
					m='Your current session seems to have timed out. Refresh the window. If it is still blank, sign out and sign in again before continuing with the settings update.';
					
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
				
				
				//Company Name
				if (!cmp)
				{
					m='Company name field must not be blank.';
					
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
					
					$('#txtCompanyname').focus(); return false;
				}
			
				if ($.isNumeric(cmp))
				{
					m='Company name field must not be a number.';
					
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
					
					$('#txtCompanyname').focus(); return false;
				}
				
				//Company Email
				if (!em)
				{
					m='Company email field must not be blank.';
					
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
					
					$('#txtEmail').focus(); return false;
				}
				
				//Valid Email
				if(!isEmail(em))
				{
					m='Invalid company email address.';   
					
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
					
					$('#txtEmail').focus(); return false;
				}
				
				//Company Phone
				if (!ph)
				{
					m='Company phone field must not be blank.';
					
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
					
					$('#txtPhone').focus(); return false;
				}
				
				//Website
				if (!web)
				{
					m='Company website field must not be blank.';
					
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
					
					$('#txtWebsite').focus(); return false;
				}
				
				//Valid Url
				var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
				
				if(!isUrl(web))
				{
					m='Invalid company website url. Please add http:// or https:// to your url if it is not added.';   
					
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
					
					$('#txtWebsite').focus(); return false;
				}
				
				//Default Network
				if (!net)
				{
					m='Please select a default network.';
					
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
					
					$('#cboNetwork').focus(); return false;
				}
				
				//No of videos per day
				if (!no)
				{
					m='No of videos per day field must not be blank.';
					
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
					
					$('#txtVideos').focus(); return false;
				}
				
				if (!$.isNumeric(no))
				{
					m='No of videos per day field must be a number.';
					
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
					
					$('#txtVideos').focus(); return false;
				}
				
				if (parseInt(no,10)==0)
				{
					m='No of videos per day field must not be zero.';
					
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
					
					$('#txtVideos').focus(); return false;
				}
				
				if (parseInt(no,10)<0)
				{
					m='No of videos per day field must not be a negative number.';
					
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
					
					$('#txtVideos').focus(); return false;
				}	
				
				//Google Key
				if (!gog)
				{
					m='Google URL Shortening Key field must not be blank.';
					
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
					
					$('#txtGoogle').focus(); return false;
				}
				
				//JW API Key
				if (!jwkey)
				{
					m='JW Player API key field must not be blank.';
					
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
					
					$('#txtJWKey').focus(); return false;
				}
				
				//JW API  Secret
				if (!jwsecret)
				{
					m='JW Player secret key field must not be blank.';
					
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
					
					$('#txtJWSecret').focus(); return false;
				}
				
				//JW Player ID
				if (!jwplayid)
				{
					m='JW Player ID field must not be blank.';
					
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
					
					$('#txtJWPlayerID').focus(); return false;
				}
				
				//Emergency Mobile Nos
				if (!emph)
				{
					m='Emergency mobile number(s) field must not be blank. Separate multiple phone numbers by commas.';
					
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
					
					$('#txtemGSM').focus(); return false;
				}
				
				var s=emem.split(',');
				
				if (s.length>1)
				{
					for(var i=0; i<s.length; i++)
					{
						//Emergency Email
						if (!s[i])
						{
							m='Emergency email field must not be blank. Separate multiple emails by commas.';
							
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
							
							$('#txtemEmail').focus(); return false;
						}
						
						//Valid Emergency Email
						if(!isEmail(s[i]))
						{
							m='Invalid emergency email address ('+s[i]+'). Separate multiple emails by commas.';   
							
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
							
							$('#txtemEmail').focus(); return false;
						}	
					}
					
				}else
				{
					//Emergency Email
					if (!emem)
					{
						m='Emergency email(s) field must not be blank. Separate multiple emails by commas.';
						
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
						
						$('#txtemEmail').focus(); return false;
					}
					
					//Valid Emergency Email
					if(!isEmail(emem))
					{
						m='Invalid emergency email(s) address. Separate multiple emails by commas.';   
						
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
						
						$('#txtemEmail').focus(); return false;
					}	
				}
				
				
				
				//Bulk SMS Provider URL
				if (!url)
				{
					m='Bulk sms provider url field must not be blank.';
					
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
					
					$('#txtSmsUrl').focus(); return false;
				}
				
				//Valid Url
				var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
				
				if(!isUrl(url))
				{
					m='Bulk sms provider url. Please add http:// or https:// to your url if it is not added.';   
					
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
					
					$('#txtSmsUrl').focus(); return false;
				}
				
				//Bulk SMS Account Username
				if (!un)
				{
					m='Bulk sms account username field must not be blank.';
					
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
					
					$('#txtSmsUsername').focus(); return false;
				}
				
				//Bulk SMS Account Password
				if (!$.trim(pwd))
				{
					m='Bulk sms account password field must not be blank.';
					
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
					
					$('#txtSmsPwd').focus(); return false;
				}
				
				//Input Bucket
				if ($('#cboInputBucket > option').length < 2)
				{
					m='Amazon buckets(folders) have not been loaded. Please refresh the page. If the issue persist contact the system admin at support@laffhub.com.';
					
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
				
				if (!ibk)
				{
					m='Please select the amazon input bucket(folder).';
					
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
					
					$('#cboInputBucket').focus(); return false;
				}
				
				//Output Bucket
				if ($('#cboOutputBucket > option').length < 2)
				{
					m='Amazon buckets(folders) have not been loaded. Please refresh the page. If the issue persist contact the system admin at support@laffhub.com.';
					
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
				
				if (!obk)
				{
					m='Please select the amazon output bucket(folder).';
					
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
					
					$('#cboOutputBucket').focus(); return false;
				}
				
				//Thumb Bucket
				if ($('#cboThumbBucket > option').length < 2)
				{
					m='Amazon buckets(folders) have not been loaded. Please refresh the page. If the issue persist contact the system admin at support@laffhub.com.';
					
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
				
				if (!tbk)
				{
					m='Please select the amazon thumbnail bucket(folder).';
					
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
					
					$('#cboThumbBucket').focus(); return false;
				}
				
				//Different input and output buckets
				if ($.trim(ibk).toLowerCase()==$.trim(obk).toLowerCase())
				{
					m='Amazon input and output buckets(folders) must be different.';
					
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
					
					$('#cboInputBucket').focus(); return false;
				}
				
				if ($.trim(ibk).toLowerCase()==$.trim(tbk).toLowerCase())
				{
					m='Amazon input and thumbnail buckets(folders) must be different.';
					
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
					
					$('#cboThumbBucket').focus(); return false;
				}
				
				if ($.trim(obk).toLowerCase()==$.trim(tbk).toLowerCase())
				{
					m='Amazon output and thumbnail buckets(folders) must be different.';
					
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
					
					$('#cboThumbBucket').focus(); return false;
				}
				
				//AWS Key
				if (!awskey)
				{
					m='Amazon API key field must not be blank.';
					
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
					
					$('#txtAwsKey').focus(); return false;
				}
				
				//AWS  Secret
				if (!awssecret)
				{
					m='Amazon secret code field must not be blank.';
					
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
					
					$('#txtAwsSecret').focus(); return false;
				}
				
				//JWPlayer Key Secret
				if (!jwpkey)
				{
					m='JWPlayer key field must not be blank.';
					
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
					
					$('#txtJWPlayerKey').focus(); return false;
				}
				
				//Confirm Registration
				if (!confirm('This action will permanently set or modify the portal settings record. Do you want to proceed with the update?  Click "OK" to proceed or "CANCEL" to abort!'))
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
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				
				return false;
			}
		}
		
		function ShowPicture(title,imgname)
		{
			$('#idModalTitle').html(title);
			$('#idModalBody').html('<img src="<?php echo base_url(); ?>' + imgname + '">');
			$('#divPictureModal').modal('show');
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
              <div class="panel-heading"><i class="fa fa-gear"></i>&nbsp;Portal&nbsp;Settings</div>
              <div class="panel-body">
                           <p>
                           		<div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                           </p>
                            
              	<form class="form-horizontal"> 
               	    	<!--Company Name/Company Email-->                                    
                        <div class="form-group" title="Company Name">
                      <label for="txtCompanyname" class="col-sm-2 control-label ">Company Name<span class="redtext">*</span></label>
    
                      <div class="col-sm-3">
                         <input value="<?php echo $companyname; ?>" type="text" class="form-control" id="txtCompanyname" placeholder="Company Name">
                        <i class="material-icons form-control-feedback size-18" style="margin-right:12px; margin-top:7px;">contacts</i>
                      </div>
                      
                      <!-- Company Email-->
                      <label for="txtEmail" class="col-sm-3 control-label ">Company Email<span class="redtext">*</span></label>
        
                      <div class="col-sm-3">
                         <input value="<?php echo $companyemail; ?>" type="text" class="form-control" id="txtEmail" placeholder="Company Email">
                         <span class="glyphicon glyphicon-envelope form-control-feedback" style="margin-right:12px;"></span>
                      </div>
                    </div> 
                    
                    <!--Company Phone/Website-->                                     
                      <div class="form-group" title="Company Phone">
                      <label for="txtPhone" class="col-sm-2 control-label ">Company Phone<span class="redtext">*</span></label>
    
                      <div class="col-sm-3">
                         <input value="<?php echo $companyphone; ?>" type="tel" class="form-control" id="txtPhone" placeholder="Company Phone">
                         <span class="glyphicon glyphicon-phone form-control-feedback" style="margin-right:12px;"></span>
                      </div>
                      
                      <!--Website-->
                      <label for="txtWebsite" class="col-sm-3 control-label " title="Company Website">Company Website<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Company Website">
                         <input value="<?php echo $website; ?>" type="text" class="form-control" id="txtWebsite" placeholder="Company Website">
                         <i class="glyphicon glyphicon-globe form-control-feedback size-18" style="margin-right:12px; margin-top:0px;"></i>
                      </div>
                    </div>  
                      
                     <!--Defaulty Network/No Of Videos Per Day-->
                     <div class="form-group">
                     	<!--Defaulty Network-->
                      <label for="cboNetwork" class="col-sm-2 control-label " title="Default Network">Default Network<span class="redtext">*</span></label>
    
                      <div class="col-sm-3">
                         <select id="cboNetwork" class="form-control" title="Default Network"></select>
                         <i class="material-icons form-control-feedback" style="margin-right:12px; margin-top:7px;">network_wifi</i>
                      </div>
                      
                      <!--No Of Videos Per Day-->
                      <label for="txtVideos" class="col-sm-3 control-label " title="Number Of Videos Per Day">No Of Videos/Day<span class="redtext">*</span></label>
    
                      <div class="col-sm-3" title="Number Of Videos Per Day">
                         <input value="<?php echo $no_of_videos_per_day; ?>" type="number" class="form-control" id="txtVideos" placeholder="Videos Per Day">
                         <i class="glyphicon glyphicon-globe form-control-feedback size-18" style="margin-right:12px; margin-top:0px;"></i>
                      </div>
                    </div> 
                    
                                     
                    <!--Page AutoRefresh Duration In Seconds/Google URL Shortening Key-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--Page AutoRefresh Duration In Seconds-->
                      <label for="txtRefreshDuration" class="col-sm-2 control-label " title="Page AutoRefresh Duration In Seconds">AutoRefresh Duration<span class="redtext">*</span></label>
    
                      <div class="col-sm-2" title="Page AutoRefresh Duration In Seconds">
                         <input value="<?php echo $RefreshDuration; ?>" type="number" class="form-control" id="txtRefreshDuration" placeholder="Page AutoRefresh Duration (Seconds)">
                         <i class="material-icons form-control-feedback size-18" style="margin-right:12px; margin-top:7px;"">restore_page</i>
                      </div>
                      
                      <label class="col-sm-1 control-label left redtext" style="width:auto;">(In Secs)</label>
                      
                      	<!--Google URL Shortening Key-->
                      <label for="txtGoogle" class="col-sm-2 control-label ">Google URL Key<span class="redtext">*</span></label>
        
                      <div class="col-sm-4" title="Google URL Shortening Key">
                         <input value="<?php echo $google_shortener_api; ?>" type="text" class="form-control" id="txtGoogle" placeholder="Google URL Shortening Key API">
                         <i class="fa fa-key form-control-feedback size-18" style="margin-right:12px; margin-top:0px;"></i>
                      </div>
                    </div>
                    
                    <!--JW Player API Key/JW Player Secret Key-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--JW Player API Key-->
                      <label for="txtJWKey" class="col-sm-2 control-label " title="JW Player API Key">JW Player API Key<span class="redtext">*</span></label>
    
                      <div class="col-sm-2" title="JW Player API Key">
                         <input value="<?php echo $jw_api_key; ?>" type="text" class="form-control" id="txtJWKey" placeholder="JW Player API Key">
                         <i class="fa fa-key form-control-feedback size-18" style="margin-right:12px; margin-top:0px;""></i>
                      </div>
                                            
                      	<!--JW Player Secret Key-->
                        <label class="col-sm-3 control-label left" for="txtJWSecret" title="JW Player API Secret Key.">JW Player Secret Key<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-4">
                           <input value="<?php echo $jw_api_secret; ?>" type="text" class="form-control" id="txtJWSecret" placeholder="JW Player Secret Key">
                           <i class="fa fa-lock form-control-feedback size-18" style="margin-right:12px; margin-top:0px;""></i>
                          </div>
                    </div>
                    
                    
                    <!--JW Player ID/Emergency Number-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--JW Player ID-->
                        <label class="col-sm-2 control-label left" for="txtJWPlayerID" title="JW Player ID">JW Player ID<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-3" title="JW Player ID">
                       <input value="<?php echo $jw_player_id; ?>" type="text" class="form-control" id="txtJWPlayerID" placeholder="JW Player ID">
                      </div>
                    
                    	<!--Emergency Number-->
                        <label title="Emergency Mobile Numbers For Critical Messages. Separate multiple phone numbers by commas. Maximum is 15 Numbers" class="col-sm-2 control-label left" for="txtemGSM">Emergency GSM No<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-4" title="Emergency Mobile Numbers For Critical Messages. Separate multiple phone numbers by commas. Maximum is 15 Numbers">
                            <input value="<?php echo $emergency_no; ?>" type="text" class="form-control" id="txtemGSM" placeholder="Emergency Mobile Numbers[10 Maxumum]">
                          </div>
                    </div>
                    
                    
                    <!--Bulk SMS URL/Bulk SMS Username-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--Bulk SMS URL-->
                        <label class="col-sm-2 control-label left" for="txtSmsUrl" title="Bulk SMS Provider URL">Bulk SMS URL<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-3" title="Bulk SMS Provider URL">
                       <input value="<?php echo $sms_url; ?>" type="text" class="form-control" id="txtSmsUrl" placeholder="Bulk SMS Provider URL">
                      </div>
                    
                    	<!--Bulk SMS Username-->
                        <label title="Bulk SMS Account Username" class="col-sm-2 control-label left" for="txtSmsUsername">Bulk SMS Username<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-4" title="Bulk SMS Account Username">
                            <input value="<?php echo $sms_username; ?>" type="text" class="form-control" id="txtSmsUsername" placeholder="Bulk SMS Account Username">
                          </div>
                    </div>
                    
                    <!--Bulk SMS Password/Amazon Input Bucket-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--Bulk SMS Password-->
                        <label class="col-sm-2 control-label left" for="txtSmsPwd" title="Bulk SMS Account Password">Bulk SMS Password<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-3" title="Bulk SMS Account Password">
                       <input value="<?php echo $sms_password; ?>" type="text" class="form-control" id="txtSmsPwd" placeholder="Bulk SMS Account Password">
                      </div>
                      
                      <!--Amazon Input Bucket-->
                      <label class="col-sm-2 control-label left" for="cboInputBucket" title="Amazon Input Bucket">Amazon Input Bucket<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-4" title="Amazon Input Bucket">
                       <select class="form-control" id="cboInputBucket"></select>
                      </div>
                    </div>
                    
                    <!--Amazon Output Bucket/Amazon Thumbnail Bucket-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--Amazon Output Bucket-->
                        <label class="col-sm-2 control-label left" for="cboOutputBucket" title="Amazon Output Bucket">Amazon Output Bucket<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-3" title="Amazon Output Bucket">
                       <select class="form-control" id="cboOutputBucket"></select>
                      </div>
                      
                      <!--Amazon Thumbnail Bucket-->
                      <label class="col-sm-2 control-label left" for="cboThumbBucket" title="Amazon Thumbnail Bucket">Amazon Thumb Bucket<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-4" title="Amazon Thumbnail Bucket">
                       <select class="form-control" id="cboThumbBucket"></select>
                      </div>
                    </div>
                    
                    <!--Amazon Key/Amazon Secret-->
                    <div class="form-group" style="margin-top:20px;">
                    	<!--Amazon Key-->
                        <label class="col-sm-2 control-label left" for="txtAwsKey" title="Amazon Key">Amazon Key<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-3" title="Amazon Key">
                       <input value="<?php echo $aws_key; ?>" type="text" class="form-control" id="txtAwsKey" placeholder="Amazon Key">
                      </div>
                      
                      <!--Amazon Thumbnail Bucket-->
                      <label class="col-sm-2 control-label left" for="txtAwsSecret" title="Amazon Secret Code">Amazon Secret Code<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-4" title="Amazon Secret Code">
                       <input value="<?php echo $aws_secret; ?>" type="text" class="form-control" id="txtAwsSecret" placeholder="Amazon Secret Code">
                      </div>
                    </div>
                    
                    <!--JWPlayer Key-->
                    <div class="form-group">
                    	<!--Amazon Key-->
                        <label class="col-sm-2 control-label left"></label>
                          
                      <div align="center" class="col-sm-3">
                       
                      </div>
                        
                        <!--JWPlayer Key-->
                        <label class="col-sm-2 control-label left" for="txtJWPlayerKey" title="JWPlayer Key">JWPlayer Key<span class="redtext">*</span></label>
                          
                      <div align="center" class="col-sm-4" title="JWPlayer Key">
                       <input value="<?php echo $jwplayer_key; ?>" type="text" class="form-control" id="txtJWPlayerKey" placeholder="JWPlayer Key">
                      </div>
                    </div>
                    
                     <!--Emergency Emails/Company Logo-->
                    <div class="form-group" style="margin-top:20px;">
                    <!--Emergency Emails-->
                        <label title="Emergency Emails For Critical Messages. Separate multiple emails by commas" class="col-sm-2 control-label left" for="txtemEmail">Emergency Emails<span class="redtext">*</span></label>
                          
                          <div align="center" class="col-sm-3" title="Emergency Emails For Critical Messages. Separate multiple emails by commas">
                            <textarea rows="4" class="form-control" id="txtemEmail" placeholder="Emergency Emails"><?php echo $emergency_emails; ?></textarea>
                          </div>
                                           
                    <!--Company Logo-->
                        <label class="col-sm-2 control-label left" for="txtLogo">Company Logo</label>
                          <div align="center" class="col-sm-4" title="Company Logo." style="border:dashed thin;">
                            <img src="" id="imgLogo" style="border:1; border-style:solid; background-color:#FFF;" width="100px" /><p></p>
                            <input id="txtLogo" name="txtLogo" type="file" accept="image/jpeg,image/png" onchange="GetFile(this,'Logo');" style="max-height:100px;">
                          </div>
                    </div>
                       
                                        
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                   
                    <div align="center" class="form-group">
                      <div class="col-sm-offset-1 col-sm-8">
                        <div class="box-footer">
                        	<button style="width:150px;" id="btnUpdate" type="button" class="btn btn-primary"><span class="glyphicon glyphicon-edit" ></span> Update Settings</button>
                                                        
                            <button style="margin-left:30px; width:150px;" id="btnRefreshProfile" type="button" class="btn btn-danger right" onClick="window.location.reload(true);"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                          </div>
                      </div>
                    </div>
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
    
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
    <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
    
    <script type='text/javascript' src="<?php echo base_url();?>js/highcharts/highcharts.js"></script>
	<script type='text/javascript' src="<?php echo base_url();?>js/highcharts/exporting.js"></script>
       
     
       
  </body>
</html>
