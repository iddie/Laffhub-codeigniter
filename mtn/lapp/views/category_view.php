<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::Video Category</title>
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

<link rel="stylesheet" href="<?php echo base_url(); ?>acss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>
<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/modernAlert.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-3');
</script>


<script>
(function($){
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	var Category='<?php echo $Category; ?>';
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
	
	$(document).ready(function(e) {
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
				afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
				klass : 'mtn-custom-theme',
				bgPath : '<?php echo base_url();?>images/',
				content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading '+Category+'\'s Videos. Please Wait...</b></p></center>'
			}
		);
		
		$('#btnNext').click(function(e) {
            try
			{
				var page='<?php echo $page; ?>';
							
				if (!page) page=1; else page=parseInt(page,10)+1;
				
				$.redirect("<?php echo site_url('Category/GetCategory');?>",{category: Category, page:page}); 
				
				//<?php# echo site_url('Comedian/ShowComedian/'.$ComedianId); ?>
				
			}catch(e)
			{
				$.msg('unblock');
				var m='Next Button Click ERROR:\n'+e;
			   
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
        });
		
		
		
		$('#btnPrevious').click(function(e) {
            try
			{
				var page='<?php echo $page; ?>';
				
				if (!page) page=1; else page=parseInt(page,10)-1;
				
				if (parseInt(page,10) < 1) page=1;
				
				$.redirect("<?php echo site_url('Category/GetCategory');?>",{category: Category, page:page}); 
			}catch(e)
			{
				$.msg('unblock');
				m='Previous Button Click ERROR:\n'+e;
			   bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
        });
    });
	
})(jQuery);	

function ShowVideo(code)
{	
	window.location.href='<?php echo base_url(); ?>' + code;
}
</script>
</head>
<body class="page">

<div class="page__layout">
      <div class="overlay"></div>
      
      <!--HEADER--><?php include('newusernav.php'); ?><!--/HEADER-->
      
      <div id="content-ajax">
      	<!--MAIN-->
        <main class="page__main main">
          <!--CATEGORY-->
          <div class="inner">
            <div class="section section--last category scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="row">
                  <div class="col-xxl-2 col-md-3 col-sm-4">
                    <div class="aside_categories-list">
                      <a href="#" class="aside_categories-list__toggle btn"> Show categories </a>
                      <div class="aside_categories-list__collapse">
                        <div class="aside_categories-list__heading"> Categories </div>
                        <ul class="categories-menu">
                          <?php
						  	if (count($Categories) > 0)
							{
								foreach($Categories as $row):
									if ($row->category)
									{
										$cat=trim($row->category);
										$pix='';
						
										if ($row->pix)
										{
											if (file_exists('http://laffhub.com/category_pix/'.trim($row->pix)))
											{
												$pix='http://laffhub.com/category_pix/'.trim($row->pix);
											}else
											{
												$pix='http://laffhub.com/images/nophoto.jpg';
											}					
										}else
										{					
											$pix='http://laffhub.com/images/nophoto.jpg';
										}
										
										echo '
							 <li>
                            <a style="cursor:pointer;" onClick="ShowVideo(\'Category/ShowCategories/'.$cat.'\');" class="">'.$cat.'
                              <span class="categories-menu__badge"></span><!--326-->
                            </a>
                          </li>			
										';
									}
								endforeach;
							}
						  ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-xxl-10 col-md-9 col-sm-8">
                    <div class="section-heading">
                      <h4><?php echo $Category; ?></h4>
                    </div>
                    <div class="row five-columns row--flex">
                    	<?php
                    	if (count($CategoryVideos)>0)
						{
							
							foreach($CategoryVideos as $row):
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
							 <div class="col-xxl-2 col-lg-3 col-md-3 col-sm-6">
								<a title="Click to watch '.strtoupper($row->video_title).'" style="cursor:pointer;" onClick="ShowVideo(\''.$videourl.'\');" class="video-preview video-preview--sm">
								  <div class="video-preview__image" title="'.$row->video_title.'">
									<span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
									<div class="video-preview__info">
									  <div class="video-preview__duration">'.$row->duration.'</div>
									  <div class="video-preview__likes">'.$likes.'%</div>
									  <div class="video-preview__quality">HD</div>
									</div>
								  </div>
								  <h4 class="video-preview__comedianName">'.$comedian.'</h4>
								  <h5 class="video-preview__descr">'.trim($row->description).'</h5>
								</a>
							  </div>	
									';		
								}
							endforeach;
							
						}
					?>
                    </div>
                    
                    <!--pagination-->
                  <div align="center" class="page-controls ">
                    <a id="btnPrevious" class="btn previous round pagination">&laquo; Prev</a>
					<a id="btnNext" class="btn next round pagination">Next &raquo;</a>
                  </div> 
                  </div>
                </div>
              </div>
            </div>
            <!--BANNERS--><?php include('adverts.php'); ?><!--/BANNERS-->
          </div>
          <!--/CATEGORY-->
        </main>
        <!--/MAIN-->
        
        <!--FOOTER-->
       <?php include('newuserfooter.php'); ?>
        <!--/FOOTER-->
     </div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.redirect.js"></script>
    
 <!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>acss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->

</body>
</html>