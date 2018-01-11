<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport">
<title>LaffHub</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">


<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/holder.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>

<script src="<?php echo base_url();?>js/jwplayer.js"></script>

<style type="text/css">
	/*img.img-responsive
	{
		width: 67%;
		float: left;
	}
*/
</style>

<script>
var Network='<?php echo $Network;?>';
var Phone='<?php echo $Phone; ?>';
var Email='<?php echo $subscriber_email; ?>';
var VideoCode='<?php echo $videocode; ?>';
var Finished=false;

var VideosToWatch='<?php echo $VideosToWatch; ?>';
var VideosWatched='<?php echo $VideosWatched; ?>';
var ExceededVideoLimited='<?php echo $ExceededVideoLimited; ?>';
var ExceededTotalVideos='<?php echo $ExceededTotalVideos; ?>';
var ExceededTotal='<?php echo $ExceededTotal; ?>';//Used if ExceededVideoLimited=3


bootstrap_alert = function() {}
bootstrap_alert.warning = function(message) 
{
   $('#divAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
}

bootstrap_comment_alert = function() {}
bootstrap_comment_alert.warning = function(message) 
{
   $('#divComment').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
}

bootstrap_success_comment_alert = function() {}
bootstrap_success_comment_alert.warning = function(message) 
{
   $('#divComment').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
}

var SubscriptionStatus='<?php echo $subscription_status; ?>';
var SubscriptionID='<?php echo $subscriptionId; ?>';
var SubscriberStatus='<?php echo $subscriber_status; ?>';
var SubscriberEmail='<?php echo $subscriber_email; ?>';
var SubscriberName='<?php echo $subscriber_name; ?>';
var SubscriberPhone='<?php echo $subscriber_phone; ?>';
var CommentsCount='<?php echo $CommentsCount; ?>';
var DurationInSeconds='<?php echo $duration_secs; ?>';
var Duration='<?php echo $duration; ?>';

var Title='<font color="#AF4442">LaffHub Help</font>';
var m='';
//alert(Phone+'\n'+SubscriptionStatus);
$(document).ready(function(e) {
    if (SubscriptionStatus==0)
	{
		m='Dear subscriber, your subscription is currently not active. To upgrade your subscription to watch full LaffHub videos click on <b>Subscribe</b> menu item or <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a>';
		
		bootstrap_alert.warning(m);
	}
	
	$('#spnCommentCount').html(CommentsCount);
	
	$(function() {
		// clear out plugin default styling
		$.blockUI.defaults.css = {};
	});

    $(document).ajaxStop($.unblockUI);
			
	$('#btnComment').click(function(e) {
        try
		{
			var cm=$.trim($('#txtComment').val());
			var fn='<?php echo $filename; ?>';
			var cat='<?php echo $video_category; ?>';
			var cd='<?php echo $videocode; ?>';
			var vt='<?php echo $title; ?>';
			
			if ((!SubscriberEmail) && (!Phone))
			{
				m='Your current session has timed out. Please signout and signin again to be able to add comments to the video.';
					
				bootstrap_comment_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divComment').fadeOut('fast');
						}, 10000);
					}
				});
				
				return false;
			}
			
			if (!cm)
			{
				m='Please enter a comment before clicking on the <b>Submit</b> button.';
					
				bootstrap_comment_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divComment').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtComment').focus();
				return false;
			}
			
			if ($.isNumeric(cm))
			{
				m='Comment must not be a number but a meaningful statement.';
					
				bootstrap_comment_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divComment').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtComment').focus();
				return false;
			}
			
			if (cm.length<2)
			{
				m='Please enter a meaningful statement.';
					
				bootstrap_comment_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divComment').fadeOut('fast');
						}, 10000);
					}
				});
				
				$('#txtComment').focus();
				return false;
			}
			
			//Confirm Addition
			/*if (!confirm('Do you want to proceed with the comment submission? (Click OK to proceed or CANCEL to abort)'))
			{
				return false;
			}*/
			
			var nm='';
			
			if (!SubscriberName)
			{
				if (Phone) nm=Phone; else nm=Email;
			}else
			{
				nm=SubscriberName;
			}
			
			//Add Comment
			var mydata={email:SubscriberEmail, phone:Phone, category:cat, filename:fn, videocode:cd, name:nm, comment:cm, videotitle:vt};
				 				  
			$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Adding Comment. Please Wait...</b></p>',theme: true,baseZ: 2000});
			
			$.ajax({
				type: "POST",
				dataType: 'text',
				data: mydata,
				url: '<?php echo site_url('V/AddComment');?>',
				success: function(data,status,xhr) {
					$.unblockUI();
							
					var ret=$.trim(data);
					
					if (ret.toUpperCase()=='OK')
					{
						m='Comment Was Added successful';
						
						bootstrap_success_comment_alert.warning(m);
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
						
						bootstrap_comment_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divComment').fadeOut('fast');
								}, 10000);
							}
						});
					}
				},
				error:  function(xhr,status,error) {
						$.unblockUI();
						
						m='Error '+ xhr.status + ' Occurred: ' + error;
						bootstrap_comment_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divComment').fadeOut('fast');
								}, 10000);
							}
						});
					}		
			});
			
			$.unblockUI();
		}catch(e)
		{
			$.unblockUI();
			m='Submit Comment Click ERROR:\n'+e;
			
			bootstrap_comment_alert.warning(m);
			bootbox.alert({ 
				size: 'small', message: m, title:Title,
				buttons: { ok: { label: "Close", className: "btn-danger" } },
				callback:function(){
					setTimeout(function() {
						$('#divComment').fadeOut('fast');
					}, 10000);
				}
			});
		}
    });
});
</script>
</head>
<body style="background-color:#f1f1f1">

<header>
<?php include('usernav.php'); ?>
</header>

   
    
    <div class="container paddingcont pp">
        <div class="wrapper2">
        	<div align="center" id = "divAlert"></div>
            
            <div class="row no_margin">
                <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12 no_padding">
                    <!--Video Display Section-->
                    <section class="content">
                        <div class="row margin_right">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php
									if ($filename && $category && $domain_name)
									{
										echo '
											<div id="player" title="'.$title.'">Video is loading. Please wait...</div>
											
											';
											
											
									}else
									{
										$this->load->view('notfound',$data);
									}
								?>
                                
                                <div class="row no_margin">
                                    <div class="col-lg-8 col-md-9 col-sm-8 col-xs-12 descript">
                                        <div class="vedio-title">
                                            <p>
                                            	<?php echo '<b>'.strtoupper($title).'</b><br>'; ?>
                                                <?php echo $description; ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!--Social Media Links-->
                                    <div class="col-lg-4 col-md-3 col-sm-4 col-xs-12 soc">
                                        <ul class="social socials">
                                          <li><a href="#" class="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                          <li><a href="#" class="instagram"><i class="fa fa-instagram"></i></a></li>
                                          <li><a href="#" class="youtube"><i class="fa fa-youtube-square"></i></a></li>
                                          <li><a href="#" class="twitter"><i class="fa fa-twitter-square"></i></a></li>
                                          <li><a href="#" class="whatsapp"><i class="fa fa-whatsapp"></i></a></li>
                                        </ul>                                
                                   </div>                                
                                </div>
                            </div>
                        </div>
                    </section>
    
                    <div class="row no_margin">
                       
                        <div class="row no_margin">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <form>
                                	 <br><h5> <b><span id="spnCommentCount"></span></b> </h5>
                                	<div class="form-group"><textarea style="height:50px; width:100%;" id="txtComment" placeholder="Add Comment" class="form-control"></textarea>
                                        <br>
                                        <div align="center">
                                            <div id = "divComment"></div>
                                        </div>
                                    
                                        <div class="comment" style="margin-top:10px;">
                                        	<input id="btnComment" class="btn btn-default" type="Comment" value="Submit" style="margin-top:-50px;">
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="comment-socail">
                                    <h6> Share with  </h6>
                                
                                     <div class="row">
                                      <div class="ccol-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <ul class="social sc">
                                          <li><a href="#" class="facebook"><i class="fa fa-facebook-official"></i></a></li>
                                          <li><a href="#" class="instagram"><i class="fa fa-instagram"></i></a></li>
                                          <li><a href="#" class="youtube"><i class="fa fa-youtube-square"></i></a></li>
                                          <li><a href="#" class="twitter"><i class="fa fa-twitter-square"></i></a></li>
                                          <li><a href="#" class="whatsapp"><i class="fa fa-whatsapp"></i></a></li>
                                        </ul>
                                      </div>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                        
                        <!--Comment Section-->
                         <div class="row no_margin">
                            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">
                            
                                <?php
                                    if (count($Comments) > 0)
                                    {
										$i=0;
                                        foreach($Comments as $row):
                                            if (trim($row->comment))
                                            {
												$nm='';
												
												if ($row->name)
												{
													$nm=trim($row->name);
												}elseif ($row->phone)
												{
													$nm=trim($row->phone);
												}elseif ($row->email)
												{
													$nm=trim($row->email);
												}
												
												$i++;
												
												if ($i > 1) echo '<br>';
                                                echo '
                                                    <div class="row">
														<div class="user col-lg-2  col-md-2 col-sm-2 col-xs-12">
															<img height="80" src="'.base_url().'images/user.png" class="img-responsive" >
														</div>	
														
														<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12" style="margin-left:-30px;">
															<h5 style="text-transform:capitalize; font-size:13px;"><b>'.$nm.'</b><span style="float:right">'.date('D, d M Y H:i',strtotime($row->commentdate)).'</span></h5>
															<hr color="#DB5832" style="margin-top:0; height:1px;">
															
															<h5 align="justify" style="text-transform:none; margin-top:-10px; font-size:12px;">'.$row->comment.'</h5>
														</div>		
                                                    </div>				
                                                ';
                                            }
                                        endforeach;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
    			
                <!--Most Popular Section-->
                <div class="col-lg-4 col-md-3 col-sm-3 col-xs-12 no_padding wd">
                    <section class="content content2">
                        <div class="row nomar">
                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 title tt">
                        
                            <div class="title titleupnext">
                              <h2> Most Popular </h2>
                            </div>
                          </div>
                        </div>
    
    					<!--Popular Videos 1-->
                         <?php
							if (count($ViewPagePopularVideos) > 0)
							{
								foreach($ViewPagePopularVideos as $row):
									if ($row->thumbnail and $row->video_code)
									{
										$comedian=''; $views='';
										
										if ($row->watchcount > 1) $views=$row->watchcount.' Views'; else $views=$row->watchcount.' View';
										if ($row->comedian)
										{
											if (strtolower(trim($row->comedian))<> 'undefined')
											{
												$comedian='<h6 style="text-transform:capitalize; font-size:12px;">'.ucwords(strtolower($row->comedian)).'</h6>';
											}
										}
										
										
										echo '
											<div class="row">
											  <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 mobilev" style="">
												<div class="list-div2">
													<img onClick="ShowVideo(\'c-'.$row->video_code.'\');" title="Click to watch '.strtoupper($row->video_title).'" style="cursor:pointer; height:90px;" src="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'" class="">
												</div>
											  </div>
											  
											  <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 no_padding nop">
												<div class="list-div-text2 abc" style="padding-left:0;">
													<h4><b>'.trim($row->video_title).'</b></h4>
													<span>'.$comedian.'</span>
													<span>'.$views.'</span>
												 </div>
											  </div>
											</div>
										';
									}
								endforeach;
							}
						?> 
                    </section>
                </div>
                
                
            </div>
        </div>
    </div> 
    
     <script>
        var domainname='<?php echo $domain_name; ?>';
        var filename='<?php echo $filename; ?>';
		var VideoTitle='<?php echo $title; ?>';
        var category='<?php echo $category; ?>';
		var thumbnail='<?php echo $thumbnail; ?>';
		var CanPlay=false;
        
        var arr = filename.split('.');
        var ext=$.trim(arr[arr.length-1]);				
        var fn=filename.replace('.'+ext,'');
        var preview_url='https://'+domainname+'/'+category+'/'+fn;
		
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
		
		if (SubscriptionStatus != 1)
		{
			m='You can only view 30 seconds of the video. Subscribe or renew your subscription to view the full video. To subscribe click on <b>Subscribe</b> menu item or <a style="position:inherit;" href="<?php echo site_url('Subscribe'); ?>">Here</a>';
			
			jwplayer("player").onTime(function() {
				if (jwplayer("player").getPosition() >= 30) //30
				{
					jwplayer("player").seek(30);
					jwplayer("player").play(false);
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
				}
			});	
			
		}else//Active
		{
			//var videotime=parseInt(DurationInSeconds,10) - 20;
			var videotime=63;
			
			//ExceededVideoLimited
			//ExceededTotalVideos
			//ExceededTotal - Used if ExceededVideoLimited=3
			
			if (ExceededVideoLimited=='3')
			{
				if (ExceededTotal=='1')
				{//Cannot run
					m='You have exceeded the total number of videos allowed for your current subscription plan. Please renew your subscription to watch videos.';
					
					$("#player").html(m).css('color','#660000');
					jwplayer("player").remove();
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:'Subscription Notice',
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}else if (parseInt(ExceededTotalVideos) < parseInt(VideosToWatch))
				{
					if ($.trim(VideosToWatch).toLowerCase() != 'unlimited')
					{
						var diff=parseInt(VideosToWatch) - parseInt(VideosWatched);
						
						if (diff<2)
						{
							m='You have watched this particular video 3 times. However, you still have the privilege of selecting <b>' + diff + ' new video</b> for your current subscription plan.';
						}else
						{
							m='You have watched this particular video 3 times. However, you still have the privilege of selecting <b>' + diff + ' new videos</b> for your current subscription plan.';
						}	
					}else
					{
						m='You have watched this particular video 3 times. You still have the privilege of selecting <b>UNLIMITED new videos</b> for your current subscription plan.';
					}					
					
					$("#player").html(m).css('color','#660000');
					jwplayer("player").remove();
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:'Subscription Notice',
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			}else
			{
				jwplayer('player').onComplete( function(event) {
				   //alert('Finished');
				   CanPlay=false;
				});
				
				jwplayer('player').onReady ( function(event) {
					//alert('Ready');
				});
				
				jwplayer("player").on('buffer', function()
				{
					if (CanPlay==false)
					{
						jwplayer("player").pause(true);
											
						var mydata={phone:Phone,email:Email,videocode:VideoCode,subscriptionId:SubscriptionID};	
						
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
									CanPlay=true;
									
									jwplayer("player").pause(false);
								}else
								{
									m=ret;
									CanPlay=false;
									
									jwplayer("player").remove();
									$("#player").html(m).css('color','#660000');
						
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
					}
				});
				

				jwplayer("player").onTime(function() 
				{
					if (Finished==false)
					{
						if (jwplayer("player").getPosition() >= videotime)
						{
							Finished=true;
							
							//Update Watch Count 
							var mydata={phone:Phone,email:Email,videocode:VideoCode,subscriptionId:SubscriptionID};	
					//alert(Finished);
																		
							$.ajax({
								url: "<?php echo site_url('V/UpdateWatchCount'); ?>",
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(dataSet,status,xhr) {
									
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
				});			
			}	
		}
		
		function ShowVideo(code)
		{
			var url='<?php echo base_url(); ?>' + code;
			
			window.location.href=code;
		}
    </script>

<?php include('userfooter.php'); ?>
     
<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>


<script>
	var wd=$('#player').width();
	var tp=$('#player').css('top');
	
	$('#tit').width(wd);
	$('#title').css('',tp);
</script>
</body>
</html>