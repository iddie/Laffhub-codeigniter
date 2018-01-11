<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LaffHub</title>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--FAVICON-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  <link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg" color="#ff0000">
  <meta name="theme-color" content="#ffffff">
  <!--/FAVICON-->

  <!--CSS CRITICAL-->
  <style>
    html {
      background: #000;
    }

    body {
      background: #000;
      opacity: 0;
    }
  </style>
  <!--/CSS CRITICAL-->
  
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>acss/css/main.css"><!--CSS MAIN-->
<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>
  
 <script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<!--<script src="--><?php //echo base_url();?><!--js/modernAlert.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>

<!-- Hotjar Tracking Code for www.laffhub.com -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:694882,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script src="<?php echo base_url();?>js/jwplayer.js"></script>
<script src="<?php echo base_url();?>js/jquery.timeago.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>js/livestamp.js" type="text/javascript"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-2');
</script>

<script>
(function($){
	var SubscriptionStatus='<?php echo $SubscribeStatus; ?>';
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	var VideoCode='<?php echo $videocode; ?>';
	var Finished=false;
	
	var CanPlayVideo='<?php echo $CanPlayVideo; ?>';
	var CanPlayNew='<?php echo $CanPlayNew; ?>';
	var NoPlayReason='<?php echo $NoPlayReason; ?>';
	var NewVideoPlay='<?php echo $NewVideoPlay; ?>';
	var SpareView='<?php echo $SpareView; ?>';
	var RePlayOld='<?php echo $RePlayOld; ?>';
	var newvideo='<?php echo $newvideo; ?>';
	
	var CurrentVideoCount='<?php echo $CurrentVideoCount; ?>';
	var CommentsCount='<?php echo $CommentsCount; ?>';
	var DurationInSeconds='<?php echo $duration_secs; ?>';
	var Duration='<?php echo $duration; ?>'
	var MaxVideo='<?php echo $MaxVideo; ?>'
	var VideosWatched='<?php echo $VideosWatched; ?>';
	var domainname='<?php echo $domain_name; ?>';
	var VideoTitle='<?php echo $title; ?>';
	var category='<?php echo $category; ?>';
	var thumbnail='<?php echo $thumbnail; ?>';
	var CanPlay=false;
	var filename='<?php echo $filename; ?>';     
	var arr = filename.split('.');
	var ext=$.trim(arr[arr.length-1]);				
	var fn=filename.replace('.'+ext,'');
	var preview_url='https://'+domainname+'/'+category+'/'+fn;
	var PlayVideo=false;
	var videotime=30;
	var self;
	var Stopped=false;
	
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	var self;
	
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
		
	
	var SubscriptionID='<?php echo $subscriptionId; ?>';
	var SubscriberName='<?php echo $subscriber_name; ?>';
	

$(document).ready(function(e) {
	$('#spnCommentCount').html(CommentsCount);
	
	$('#player').bind('contextmenu',function() { return false; });

	modernAlert({
                backgroundColor: '#fff',
                color: '#555',
                borderColor: '#ccc',
                titleBackgroundColor: '#C8552E',//#e8a033
                titleColor: '#fff',
                defaultButtonsText: {ok : 'Ok', cancel : 'Cancel'},
                overlayColor: 'rgba(0, 0, 0, 0.5)',
                overlayBlur: 2 //Set false to disable it or interger for pixle
            });

	$.msg(
		{
			autoUnblock : true ,
			clickUnblock : true,
			fadeIn : 500,
			fadeOut : 200,
			timeOut : 1000,
			afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
			klass : 'airel-custom-theme',
			bgPath : '<?php echo base_url();?>images/',
			content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Video. Please Wait...</b></p></center>'
		}
	);

	$.timeago.settings.allowFuture = true;
	$("time.timeago").timeago();
	
	jwplayer.key='<?php echo $jwplayer_key; ?>';
        
	jwplayer("player").setup({
	 image: '<?php echo $preview_img; ?>',
	  sources: [
		{ file: preview_url+"_360p."+ext, label: "360p SD" },
		{ file: preview_url+"_720p."+ext, label: "720p SD" },
		{ file: preview_url+"_1080p."+ext, label: "1080p HD" }
	  ],
	 width: "100%",
	 aspectratio: "16:9"
	});
	
	$('#btnComment').prop('disabled',true);
	$('#btnReplyComment').prop('disabled',true);
	
	$('#divError').fadeOut('fast');
	$('#divReplyError').fadeOut('fast');
	
	$('#spinner').fadeOut('fast');
	$('#replyspinner').fadeOut('fast');
	
	$('#txtComment').keyup(function(e) {
        $('#btnComment').prop('disabled',true);
		
		try
		{
			EnableSubmitButton();
		}catch(e)
		{
			
		}
    });
	
	$('#txtName').keyup(function(e) {
        $('#btnComment').prop('disabled',true);
		
		try
		{
			EnableSubmitButton();
		}catch(e)
		{
			
		}
    });
	
	$('#txtReplyComment').keyup(function(e) {
        $('#btnReplyComment').prop('disabled',true);
		
		try
		{
			EnableReplySubmitButton();
		}catch(e)
		{
			
		}
    });
	
	$('#txtReplyName').keyup(function(e) {
        $('#btnReplyComment').prop('disabled',true);
		
		try
		{
			EnableReplySubmitButton();
		}catch(e)
		{
			
		}
    });
	
	function EnableReplySubmitButton()
	{
		try
		{			
			var cm=$.trim($('#txtReplyComment').val());
			var nm=$.trim($('#txtReplyName').val());
			
			if (cm && nm) $('#btnReplyComment').prop('disabled',false);
		}catch(e)
		{
			
		}
	}
	
	function EnableSubmitButton()
	{
		try
		{			
			var cm=$.trim($('#txtComment').val());
			var nm=$.trim($('#txtName').val());
			
			if (cm && nm) $('#btnComment').prop('disabled',false);
		}catch(e)
		{
			
		}
	}
	
	if (parseInt(SubscriptionStatus,10) != 1)
	{		
		m='Your LaffHub subscription is currently not active.You can only view 30 seconds of the video. Subscribe or renew your subscription to view the full video. Click <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a> to subscribe.';
		
		var m1='Your LaffHub subscription is currently not active.You can only view 30 seconds of the video.<br>Subscribe or renew your subscription to view the full video. Click <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a> to subscribe.';
		
		
		jwplayer("player").onTime(function() {
			if (jwplayer("player").getPosition() >= 30) //30
			{
				jwplayer("player").seek(30);
				jwplayer("player").play(false);
				
				bootstrap_alert.warning(m);					
				alert(m1, 'LaffHub Message');
				setTimeout(function() {
					//$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});		
	}else//Active
	{
		//var videotime=parseInt(DurationInSeconds,10) - 20;		
		
		if (CurrentVideoCount >= 5)
		{
			if (parseInt(VideosWatched,10) >= parseInt(MaxVideo,10))
			{
				if (SpareView==true)//Can watch other videos
				{
					PlayVideo=false;
					
					//Stop but can choose another video
					m='You have reached the maximum number of times you can watch <b>' + $.trim(VideoTitle).toUpperCase() +'</b>. You can, however, watch any other video that you have watched less than 3 times.';					
					
					var m1='You have reached the maximum number of times you can watch this video. You can only view 30 seconds of this video.<br>You can, however, watch any other video that you have watched less than 3 times.';
		
					jwplayer("player").onTime(function() {
						if (jwplayer("player").getPosition() >= 30) //30
						{
							jwplayer("player").seek(30);
							jwplayer("player").play(false);
							
							bootstrap_alert.warning(m);					
							alert(m1, 'LaffHub Message');
							setTimeout(function() {
								//$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}else//Exhausted ALL
				{
					PlayVideo=false;
					
					//Stop. Exhausted all videos
					m='You have reached the maximum number of videos and the maximum number of times you can watch each video for your current subscription plan. Please renew your subscription to enjoy the hilarious videos. You can only watch 30 seonds of any video now. Click <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a> to subscribe';
			
					var m1='You have reached the maximum number of times you can watch this video.<br>You can only view 30 seconds of videos now.<br>Click on SUBSCRIBE link to subscribe.';
					
					jwplayer("player").onTime(function() {
						if (jwplayer("player").getPosition() >= 30) //30
						{
							jwplayer("player").seek(30);
							jwplayer("player").play(false);
							
							bootstrap_alert.warning(m);					
							alert(m1, 'LaffHub Message');
							setTimeout(function() {
								//$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});					
				}							
			}else//VideosWatched < MaxVideo
			{
				//Stop but can choose another video
				if (SpareView==true)
				{
					PlayVideo=false;
					
					m='You have reached the maximum number of times you can watch <b>' + $.trim(VideoTitle).toUpperCase() +'</b>. You can, however, watch any other video that you have watched less than 3 times.';					
					
					var m1='You have reached the maximum number of times you can watch this video. You can only view 30 seconds of this video.<br>You can, however, watch any other video that you have watched less than 3 times.';
		
					jwplayer("player").onTime(function() {
						if (jwplayer("player").getPosition() >= 30) //30
						{
							jwplayer("player").seek(30);
							jwplayer("player").play(false);
							
							bootstrap_alert.warning(m);					
							alert(m1, 'LaffHub Message');
							setTimeout(function() {
								//$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}else//Watch new video
				{
					PlayVideo=false;
					
					m='You have reached the maximum number of times you can watch this video. You can only view 30 seconds of this video. You can, however, watch a new video.';					
					
					var m1='You have reached the maximum number of times you can watch this video. You can only view 30 seconds of this video.<br>You can, however, watch a new video.';
		
					jwplayer("player").onTime(function() {
						if (jwplayer("player").getPosition() >= 30) //30
						{
							jwplayer("player").seek(30);
							jwplayer("player").play(false);
							
							bootstrap_alert.warning(m);					
							alert(m1, 'LaffHub Message');
							setTimeout(function() {
								//$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}							
			}
		}else//CurrentVideoCount < 5
		{
			if (newvideo==true)
			{
				if (parseInt(VideosWatched,10) < parseInt(MaxVideo,10))//Play
				{
					PlayVideo=true;//Play
				}else//Maxvideo reached - Stop
				{
					if (SpareView==true)//Can only watched old not up to 3
					{
						//Stop but can replay old movie
						PlayVideo=false;
						
						m='You have reached the maximum number of times you can watch this video. You can only view 30 seconds of this video. You can, however, watch any other video that you have watched less than 3 times.';					
					
						var m1='You have reached the maximum number of different videos allowed to watch but you can still watch the previous played videos. To watch new videos again, please renew your subscription to enjoy the hilarious videos. Click <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a> to subscribe.';
		
						jwplayer("player").onTime(function() {
							if (jwplayer("player").getPosition() >= 30) //30
							{
								jwplayer("player").seek(30);
								jwplayer("player").play(false);
								
								bootstrap_alert.warning(m1);					
								alert(m1, 'LaffHub Message');
								setTimeout(function() {
									//$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}else
					{
						//Stop. Exhausted all videos
						PlayVideo=false;
						
						var m='You have reached the maximum number of videos and the maximum number of times you can watch each video for your current subscription plan. Please renew your subscription to enjoy the hilarious videos. Click <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a> to subscribe.';			
					
						var m1='You have reached the maximum number of times you can watch all your videos. You can only view 30 seconds of this video.<br>Click on SUBCRIBE below to subscribe or renew your subscription.';
		
						jwplayer("player").onTime(function() {
							if (jwplayer("player").getPosition() >= 30) //30
							{
								jwplayer("player").seek(30);
								jwplayer("player").play(false);
								
								bootstrap_alert.warning(m);					
								alert(m, 'LaffHub Message');
								setTimeout(function() {
									//$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
				}	
			}else
			{
				//Play
				PlayVideo=true;
			}						
		}
		
		if (PlayVideo==true)
		{
			jwplayer('player').onComplete( function(event) {
				   
				   CanPlay=false;
				});
				
			jwplayer('player').onReady ( function(event) {
				
			});
			
			jwplayer('player').on('firstFrame', function() 
			{
				if (CanPlay==false)
				{
					jwplayer("player").pause(true);
										
					var mydata={phone:Phone,email:Email,videocode:VideoCode,subscriptionId:SubscriptionID,title:VideoTitle};	
					
					$.ajax({
						url: "<?php echo site_url('V/CheckForWatchCount'); ?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						beforeSend: function(data,status,xhr) {

						},
						success: function(data,status,xhr) {
							var ret='';
							ret=$.trim(data);
	
							if (ret.toUpperCase()=='OK')
							{
								Stopped=false;
								CanPlay=true;
								jwplayer("player").pause(false);								
								UpdateWatchCount();
							}else
							{
								m=ret;
								CanPlay=false;
								Stopped=true;
																
								jwplayer("player").pause(true);
								jwplayer("player").stop();
								jwplayer("player").setControls(false);			
					
								bootstrap_alert.warning(m);					
								alert(m, 'LaffHub Message');
							}
						},
						error:  function(xhr,status,error) {
							m='Error '+ xhr.status + ' Occurred: ' + error;
							bootstrap_alert.warning(m);					
							alert(m, 'LaffHub Message');
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});	
				}else
				{
					if (Stopped==true)
					{
						jwplayer("player").pause(true);
						jwplayer("player").stop();
						jwplayer("player").setControls(false);

						bootstrap_alert.warning(m);					
						alert(m, 'LaffHub Message');
					}
				}
			});
			
			jwplayer("player").on('buffer', function()
			{
				
			});
			

			jwplayer("player").onTime(function() 
			{
				if (jwplayer("player").getPosition() >= 25)
				{
											
				}
			});
		}				
	}
	
	function UpdateWatchCount()
	{
		//Update Watch Count 
		var mydata={phone:Phone,email:Email,videocode:VideoCode,subscriptionId:SubscriptionID};	

		$.ajax({
			url: "<?php echo site_url('V/UpdateWatchCount'); ?>",
			data: mydata,
			type: 'POST',
			dataType: 'text',
			success: function(dataSet,status,xhr) {
				$('#spnWatchCount').html(number_format (dataSet, 0, '', ','));
			},
			error:  function(xhr,status,error) {
				m='Error '+ xhr.status + ' Occurred: ' + error;
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
		});	
	}
		
	$('#btnComment').click(function(e) {
        try
		{			
			var cd='<?php echo $videocode; ?>';
			var vt='<?php echo $title; ?>';
			var au=$.trim($('#txtName').val());
			var em='';			
			var cm=$.trim($('#txtComment').val());
			var pid='0'; //Parent Id
			
			if (!Phone)
			{
				m='Session has timed out. Please signout and signin again to be able to sbmit comments.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 15000);
							
				return false;
			}
			
			if (!cm)
			{
				m='Please type your comment.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtComment').focus();
				return false;
			}
			
			if ($.isNumeric(cm))
			{
				m='Please type a meaningful comment.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtComment').focus();
				
				return false;
			}
			
			if (cm.length<2)
			{
				m='Please enter a meaningful comment.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtComment').focus(); return false;
			}
			
			//Author		
			if (!au)
			{
				m='Please type your name.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus();	return false;
			}
			
			if ($.isNumeric(au))
			{
				m='Please type a meaningful name.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus();	return false;
			}
			
			if (au.length<2)
			{
				m='Please enter a meaningful name.';
					
				HideShowError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divError').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus(); return false;
			}
			
			$('#spinner').fadeIn('fast');
					
			//Add Comment
			var mydata={videocode:cd,videotitle:vt,author:au,msisdn:Phone,email:em,comment_text:cm, parent_id:pid};
				 				  
			
			$.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: '<?php echo site_url('V/AddComment');?>',
				success: function(data,status,xhr) {
					$('#spinner').fadeOut('slow');
							
					var ret=$.trim(data);
					
					if (ret.toUpperCase()=='OK')
					{
						LoadComments();
						
						$('#txtName').val('');
						$('#txtComment').val('');
						
						m='Comment Was Added successful';
						
						$('#divError').css({'background':'#D1F1D1','color':'#005109'});				
						
						HideShowError(m,'Show');
						
						setTimeout(function() {
							$('#divError').fadeOut('slow');
							$('#divError').css({'background':'#FFFFAA','color':'#990000'});	
						}, 2000);
					}else
					{
						m=data;
						
						HideShowError(m,'Show');
						PlaySound();
						
						setTimeout(function() {
							$('#divError').fadeOut('slow');
						}, 10000);
					}
				},
				error:  function(xhr,status,error) 
				{
					$('#spinner').fadeOut('slow');
					
					m='Error '+ xhr.status + ' Occurred: ' + error;
					HideShowError(m,'Show');
					PlaySound();
					setTimeout(function() {
						$('#divError').fadeOut('slow');
					}, 10000);
				}		
			});
		}catch(e)
		{
			$('#spinner').fadeOut('slow');
			
			m='Submit Comment Click ERROR:\n'+e;
			
			HideShowError(m,'Show');	
			PlaySound();
				
			setTimeout(function() {
				$('#divError').fadeOut('slow');
			}, 10000);
		}
    });
	
	$('#btnReplyComment').click(function(e) {
        try
		{			
			var cd='<?php echo $videocode; ?>';
			var vt='<?php echo $title; ?>';
			var au=$.trim($('#txtReplyName').val());
			var em='';			
			var cm=$.trim($('#txtReplyComment').val());
						
			var rid=$("#hidReplyId").val();
			var pid=$("#hidParentId").val();
			
			if (!Phone)
			{
				m='Session has timed out. Please signout and signin again to be able to submit reply.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 15000);
							
				return false;
			}
			
			if (!cm)
			{
				m='Please type your reply.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyComment').focus();
				return false;
			}
			
			if ($.isNumeric(cm))
			{
				m='Please type a meaningful reply.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyComment').focus();
				
				return false;
			}
			
			if (cm.length<2)
			{
				m='Please enter a meaningful reply.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyComment').focus(); return false;
			}
			
			//Author		
			if (!au)
			{
				m='Please type your name.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyName').focus();	return false;
			}
			
			if ($.isNumeric(au))
			{
				m='Please type a meaningful name.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyName').focus();	return false;
			}
			
			if (au.length<2)
			{
				m='Please enter a meaningful name.';
					
				HideShowReplyError(m,'Show');	
				PlaySound();
				setTimeout(function() {
					$('#divReplyError').fadeOut('slow');
				}, 10000);
				
				$('#txtReplyName').focus(); return false;
			}
			
			$('#replyspinner').fadeIn('fast');
					
			//Add Comment
			var mydata={videocode:cd,videotitle:vt,author:au,msisdn:Phone,email:em,comment_text:cm, parent_id:pid};				 				  
			
			$.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: '<?php echo site_url('V/AddComment');?>',
				success: function(data,status,xhr) {
					$('#replyspinner').fadeOut('slow');
							
					var ret=$.trim(data);
					
					if (ret.toUpperCase()=='OK')
					{
						LoadComments();
						
						$('#txtReplyName').val('');
						$('#txtReplyComment').val('');
						
						var rid=$("#hidReplyId").val('');
						var pid=$("#hidParentId").val('');
						
						m='Reply Was Added successful';
						
						$('#divReplyError').css({'background':'#D1F1D1','color':'#005109'});				
						
						HideShowReplyError(m,'Show');
						
						$('#btnClose').trigger('click');
						
						setTimeout(function() {
							$('#divReplyError').fadeOut('slow');
							$('#divReplyError').css({'background':'#FFFFAA','color':'#990000'});	
						}, 2000);
					}else
					{
						m=data;
						
						HideShowReplyError(m,'Show');
						PlaySound();
						
						setTimeout(function() {
							$('#divReplyError').fadeOut('slow');
						}, 10000);
					}
				},
				error:  function(xhr,status,error) 
				{
					$('#replyspinner').fadeOut('slow');
					
					m='Error '+ xhr.status + ' Occurred: ' + error;
					HideShowReplyError(m,'Show');
					PlaySound();
					setTimeout(function() {
						$('#divReplyError').fadeOut('slow');
					}, 10000);
				}		
			});
		}catch(e)
		{
			$('#replyspinner').fadeOut('slow');
			
			m='Submit Reply Comment Click ERROR:\n'+e;
			
			HideShowReplyError(m,'Show');	
			PlaySound();
				
			setTimeout(function() {
				$('#divReplyError').fadeOut('slow');
			}, 10000);
		}
    });
	
	$('#btnLike').click(function(e) {
        try
		{
			var fn='<?php echo $filename; ?>';
			var cat='<?php echo $category; ?>';
			var cd='<?php echo $videocode; ?>';
			var vt='<?php echo $title; ?>';
			
			var mydata={email:'', phone:Phone, videocode:cd, videotitle:vt};
				 				  
						
			$.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: '<?php echo site_url('V/LikeVideo');?>',
				success: function(data,status,xhr) {
					var ret=$.trim(data);
					
					if (ret != '') $('#spnLike').html(number_format(ret, '0', '', ','));
				},
				error:  function(xhr,status,error){}		
			});
		}catch(e){}
    });
	
	$('#btnDislike').click(function(e) {
        try
		{
			var fn='<?php echo $filename; ?>';
			var cat='<?php echo $category; ?>';
			var cd='<?php echo $videocode; ?>';
			var vt='<?php echo $title; ?>';
			
			var mydata={email:'', phone:Phone, videocode:cd, videotitle:vt};
				 				  
						
			$.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: '<?php echo site_url('V/DislikeVideo');?>',
				success: function(data,status,xhr) {
					var ret=$.trim(data);
					
					if (ret != '') $('#spnDislike').html(number_format(ret, '0', '', ','));
				},
				error:  function(xhr,status,error){}		
			});
		}catch(e){}
    });
	
	$('#btnClose').click(function(e) 
	{
        try
		{
			var rid=$("#hidReplyId").val();
			var pid=$("#hidParentId").val();
						
			$("#hidParentId").val('');
			$("#hidReplyId").val('');
		
			$('#txtReplyComment').val('');
			$('#txtReplyName').val('');
			
			$('#divReply').fadeOut('slow');
			$('#divReply').removeClass('show');
		}catch(e)
		{
			
		}
    });
	
	LoadComments();
	
	function LoadComments()
	{
		try
		{
			$('.comments__list').html('');
			$('.comments__counter').html('');
						
			var mydata={videocode:VideoCode};				  
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: '<?php echo site_url('V/GetComments');?>',
				success: function(data,status,xhr) {
					var count='0 Comment';
					var html='';
					
					if ($(data).length > 0)
					{
						if ($(data).length == 1) count='1 Comment'; else count=number_format($(data).length,0,'', ',') + ' Comments';
					}
					
					$('.comments__counter').html(count);
					
					if ($(data).length > 0)
					{
						var cnt=0;
//pos,parentid,commentid,author,comment,datecreated,likes												
						$.each($(data), function(i,e)
						{							
							if (e.commentid && e.author)
							{
								cnt++;
								
								if ($.trim(e.pos).toLowerCase()=='m')//Main Comment
								{
									//Main Comment
									if (cnt>1) html += '</div>';
									
									html += '<div class="comment__wrap">'+
									'<div class="comment">'+
									'<div class="comment__header"> <font color="#ffff00">' + e.author +'</font>, <span title="'+e.date+'" data-livestamp="' + e.date_seconds + '"></span>'+
									'<div class="comment__rating">'+
									'<button data-commentid="'+e.commentid+'" onClick="DislikeComment(this);" id="btnDislike' + e.commentid + '" class="comment__rating-down btn"></button>'+
										  '<div  title="Dislikes" id="divDislikeCount'+e.commentid+'" class="comment__rating-counter btn">'+number_format(e.dislikes,0,'', ',')+'</div>'+
										  '<div  title="Likes" id="divLikeCount'+e.commentid+'" class="comment__rating-counter btn">'+number_format(e.likes,0,'', ',')+'</div>'+
									'<button data-commentid="'+e.commentid+'" onClick="LikeComment(this);" id="btnLike'+e.commentid+'" class="comment__rating-up btn"></button></div></div>'+
									 '<div class="comment__body">'+
									'<p>'+e.comment+'</p>'+
									'</div>'+
									'<a id="btn'+e.commentid+'" data-commentid="'+e.commentid+'" data-parentid="'+e.parentid+'" style="cursor:pointer;" onClick="ShowForm(this);" class=" comment__reply">Reply</a>'+
								   '</div>';
								   //Main Comment End									
								}else if ($.trim(e.pos).toLowerCase()=='r')//Reply
								{//Reply Start
									html +='<div class="comment comment--reply">'+
                              '<div class="comment__header"> <font color="#F7C1F4">'+e.author+'</font>, <span title="'+e.date+'" data-livestamp="' + e.date_seconds + '"></span>'+
                                '<div class="comment__rating">'+
                                '  <button data-commentid="'+e.commentid+'" onClick="DislikeComment(this);" id="btnDislike'+e.commentid+'" class="comment__rating-down btn"></button>'+
								'<div title="Dislikes" id="divDislikeCount'+e.commentid+'" class="comment__rating-counter btn">'+number_format(e.dislikes,0,'', ',')+'</div>'+
                                 ' <div  title="Likes" id="divLikeCount'+e.commentid+'" class="comment__rating-counter btn">'+number_format(e.likes,0,'', ',')+'</div>'+
                                  '<button data-commentid="'+e.commentid+'" onClick="LikeComment(this);" id="btnLike'+e.commentid+'" class="comment__rating-up btn"></button>'+
                                '</div>'+
                              '</div>'+
                              '<div class="comment__body">'+
                             '   <p>'+e.comment+'</p>'+
                             ' </div>'+
                            '</div>';
							//Reply End
								}
							}
							
							html += '</div>';
							
							$('.comments__list').html(html);
						});
					}
				},
				error:  function(xhr,status,error) 
				{
					m='Error '+ xhr.status + ' Occurred: ' + error;
					HideShowError(m,'Show');
					PlaySound();
					setTimeout(function() {
						$('#divError').fadeOut('slow');
					}, 10000);
				}		
			});
		}catch(e)
		{
			
		}
	}
	
});//End document ready

})(jQuery);

function HideShowReplyError(msg,action)
{
	//Clear
	$('#divReplyError').html(''); 
	$('#divReplyError').fadeOut('fast');
	
	//

	var ac=$.trim(action).toLowerCase(); //Show/Hide
	
	$('#divReplyError').html(msg);
	
	if (ac=='show')
	{
		$('#divReplyError').fadeIn('slow');
	}else
	{
		$('#divReplyError').fadeOut('slow');
	}
}

function HideShowError(msg,action)
{
	//Clear
	$('#divError').html(''); 
	$('#divError').fadeOut('fast');
	
	//

	var ac=$.trim(action).toLowerCase(); //Show/Hide
	
	$('#divError').html(msg);
	
	if (ac=='show')
	{
		$('#divError').fadeIn('slow');
	}else
	{
		$('#divError').fadeOut('slow');
	}
}

function ShowForm(btn)
{
	try
	{
		var pid=$('#'+btn.id).attr('data-commentid'); //Parent Id
		
		$("#hidParentId").val(pid);
		$("#hidReplyId").val(btn.id);
						
		$('#txtReplyComment').val('');
		$('#txtReplyName').val('');
		
		$('#divReply').fadeIn('slow');
		$('#divReply').removeClass('hide');	
				
		var pos = $('#'+btn.id).position();
		
		$('#divReply').insertAfter('#'+btn.id);
		$('#divReply').css( {'position': 'relative','left': pos.left});
		
		//$('#divReply').appendTo('#'+btn.id).html().css( {'position': 'relative','left': pos.left});
	}catch(e)
	{
		
	}
}

function DislikeComment(btn)
{
	try
	{
		//alert('Dislike Button Id='+btn.id);
		var Phone='<?php echo $Phone; ?>';
		var cid=$('#'+btn.id).attr('data-commentid'); //Comment Id		
		var mydata={email:'', phone:'<?php echo $Phone; ?>', comment_id:cid}; 							  
					
		$.ajax({
			type: "POST",
			dataType: 'text',
			data: mydata,
			url: '<?php echo site_url('V/DislikeComment');?>',
			success: function(data,status,xhr) {
				var ret=$.trim(data);
				
				if (ret != '') $('#divDislikeCount'+cid).html(number_format(ret, '0', '', ','));
			},
			error:  function(xhr,status,error){}		
		});
	}catch(e)
	{
		
	}
}

function LikeComment(btn)
{
	try
	{
		var cid=$('#'+btn.id).attr('data-commentid'); //Comment Id		
		var mydata={email:'', phone:'<?php echo $Phone; ?>', comment_id:cid}; 
				
		$.ajax({
			type: "POST",
			dataType: 'text',
			data: mydata,
			url: '<?php echo site_url('V/LikeComment');?>',
			success: function(data,status,xhr) {
				var ret=$.trim(data);
				
				if (ret != '') $('#divLikeCount'+cid).html(number_format(ret, '0', '', ','));
			},
			error:  function(xhr,status,error){}		
		});
	}catch(e)
	{
		
	}
}

function PlaySound()
{
	  var sound = document.getElementById("audio");
	  sound.play();
}

function ShowVideo(code)
{
	window.location.href='<?php echo base_url(); ?>' + code;
}

</script>

</head>
<body class="page" oncontextmenu="return false;">
<div class="page__layout">
  <div class="overlay"></div>
  <!--HEADER--><?php include('newusernav.php'); ?><!--/HEADER-->

  <!---Main--->
<img src="//dsp.eskimi.com/pixel/cookie" style="display:none" />
<div id="movie_page">

  <div class="container">

    <div class="row">

      <div class="movie-box col-lg-12 col-md-12 col-sm-12 col-xs-12" >

        <div class="movie-space responsive">

         <?php
				if ($filename && $category && $domain_name)
				{
					echo '
						<div id="player" title="'.$title.'" style="background:#000000;>Video is loading. Please wait...</div>
						
						';
						
						
				}else
				{
					$this->load->view('notfound',$data);
				}
			?>

        </div>

        <!--<a href="#" class="video-player__play"></a>-->

      </div>

    </div>

    <div class="row">

      <div class="video-info">
        <div class="video-info__left">
          <div class="video-rating">
          </div>
          <div class="rate-video">
            <a style="cursor:pointer;" title="Like <?php echo strtoupper($title); ?>" id="btnLike" class="rate-video__button rate-video__button--like">
              <span id="spnLike"><?php echo number_format($likes,0); ?></span>
            </a>
            <a style="cursor:pointer;" title="Dislike <?php echo strtoupper($title); ?>" id="btnDislike" class="rate-video__button rate-video__button--dislike">
              <span id="spnDislike"><?php echo number_format($dislikes,0); ?></span>
            </a>
          </div>
        </div>
        
        <div class="video-info__right">
          <div class="video-specs">
            <div class="video-specs__left">
              <p class="video-spec">
                <span>Views:</span> <span style="color:#EDEDED;" id="spnWatchCount"><?php echo number_format($watchcount,0); ?></span> </p>
              <p class="video-spec">
                <span>Duration:</span> <span id="spnDuration" style="color:#EDEDED;"><?php echo $duration_in_min.' min '.$duration_in_sec.' sec'; ?></span> </p>
              <p class="video-spec">
                <span>Added:</span> <time style="color:#EDEDED;" class="timeago" datetime="<?php  echo $date_created; ?>" title="<?php echo date('F d, Y',strtotime($date_created))?>"></time> </p>
            </div>
            <div class="video-specs__right">
              <p class="video-spec">
                <span>Comedian:</span>
                <a style="cursor:pointer;" href="<?php echo site_url('Comedian/ShowComedian/'.$comedian); ?>" class=""><?php echo $comedian; ?></a>
              </p>
              <p class="video-spec video-spec--category">
                <span>Category:</span>
                    <a href="<?php echo site_url('Category/ShowCategories/'.$category); ?>" class="video-specs__category" style="cursor:pointer;"><?php echo $category; ?></a>
              </p>
            </div>
          </div>
        </div>
        <div class="video-info__descr">
          <h3><?php echo $title; ?></h3>
          <p> <?php echo $description; ?> </p>
         <!-- <div class="seo-spoiler__link-center">
            <a href="#" class="seo-spoiler__link">Show more</a>
          </div>-->
        </div>
      </div>

    </div>
  </div>

  <div class="container">
    <div class="row">
      <section class="comments">
        <div class="comments__inner">
          <h3 class="comments__counter"></h3>
          <div class="comments__list"></div>
          
          <form class="comments-form form">                          
              <div class="row">
                <div class="col-lg-6">
                  <label class="form__label">
                    <span class="form__label-text"> Name
                      <span class="form__label-required">Required</span>
                    </span>
                    <span class="form__group">
                      <input id="txtName" type="text" class="form__field" placeholder="Your Name"> 
                    </span>
                      
                  </label>
                </div>
              </div>
              
               <label class="form__label">
                <span class="form__label-text">Comment
                <span class="form__label-required">Required</span>
                </span>
                <span class="form__group">
                  <textarea id="txtComment" class="form__field" placeholder="Type your comment here…"></textarea>
                </span>
              </label>
             
              <div class="comment__submit">
                <button id="btnComment" type="button" class="btn btn--red">Submit comment&nbsp;&nbsp;<i id="spinner" style="font-size:2em;" class="fa fa-spinner fa-spin"></i></button>
              </div>
              
              <div style="margin:0; background:#FFFFAA; color:#990000; font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size:14px; margin-top:3px;" id="divError"></div>
            </form>
        </div>
      </section>

    </div>

  </div>
</div>

  <div class="container">
    <div class="row">
      <div class="section section--last videos-section scrollreveal scrollAnimateFade">
        <div class="container">
          <div class="section__inner">
            <div class="section-heading">
              <h4>Related videos</h4>
              <!--<a href="#" class="section-heading__control section-heading__see-all"> See all </a>-->
            </div>
            <div class="row row--flex">
             	<?php
							if (count($RandomlyRelatedVideos)>0)
							{
							
							foreach($RandomlyRelatedVideos as $row):
								if (trim($row->video_code) and trim($row->thumbnail))
								{
									$row->video_title=trim(ucwords(strtolower($row->video_title)));
									$row->category=trim(ucwords(strtolower($row->category)));
									$videourl='c-'.$row->video_code;
									
									$views='0 View'; $comedian=''; $likes='0'; $dislikescnt=0;
									$likescnt=0; $likestotal=0;
									 
								 	if ($row->watchcount > 1) $views=$row->watchcount.' Views'; else $views=$row->watchcount.' View';
								 	if ($row->comedian) $comedian=ucwords(strtolower($row->comedian));
								 	if ($row->likes) $likescnt=ucwords(strtolower($row->likes));
								 	if ($row->dislikes) $dislikescnt=ucwords(strtolower($row->dislikes));
								 
								 	$likestotal=$likescnt+$dislikescnt;
								 
									if ($likestotal>0)
									{
									 $lk=(floatval($likescnt)/floatval($likestotal))*100;
									 
									 if (floatval($lk) > 0) $likes=round($lk,0);
									}
									
									echo '
							<div class="col-xxl-2 col-xl-3 col-lg-3 col-sm-6">
							  <a onClick="ShowVideo(\''.$videourl.'\')" style="cursor:pointer;" class="video-preview video-preview--sm">
								<div class="video-preview__image" title="'.$row->video_title.'">
								  <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
								  <div class="video-preview__info">
									<div class="video-preview__duration">'.$row->duration.'</div>
									<div class="video-preview__likes">'.$likes.'%</div>
									<div class="video-preview__quality">HD</div>
								  </div>
								</div>
								<h4 class="related_video_comedian">'.$comedian.'</h4>
                                <h4 class="related_video_comedian">'.$row->video_title.'</h4>
                                <h5 class="related_video_description">'.trim($row->description).'</h5>
							  </a>
							</div>		
									';		
								}
							endforeach;
							
						}
						?>
            </div>
            
            <!--<div class="page-controls">
              <a href="#" class="show-more">Load more related videos</a>
            </div>-->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--FOOTER--><?php include('newuserfooter.php'); ?>
 
  <!--/FOOTER-->
      </div>
    </div>
    <!--SCRIPTS MAIN--><script src="<?php echo base_url(); ?>acss/js/main.js" async></script><!--/SCRIPTS MAIN-->
    
    <audio id="audio" src="<?php echo base_url(); ?>beep.mp3" autostart="false" ></audio>
  </body>
</html>