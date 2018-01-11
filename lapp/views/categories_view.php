<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::Video Categories</title>
<!--FAVICON-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>lcss/favicons/icon.png">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>lcss/favicons/icon.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>lcss/favicons/icon.png" sizes="16x16">
<link rel="manifest" href="<?php echo base_url(); ?>lcss/favicons/manifest.json">
<link rel="mask-icon" href="<?php echo base_url(); ?>lcss/favicons/safari-pinned-tab.svg" color="#ff0000">
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

<link rel="stylesheet" href="<?php echo base_url(); ?>lcss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109230973-1"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-1');
</script>


<script>
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	
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
        $(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);
    });
	
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
          <!--CATEGORIES-->
          <div class="inner">
            <div class="section popular scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section__inner">
                  <div class="section-heading">
                    <h4>Popular categories</h4>
                  </div>
                  <div class="row row--flex row--portrait">
                  <?php
				  	if (count($MostPopulatCategories) > 0)
					{
						foreach($MostPopulatCategories as $row):
							if ($row->category)
							{
								$pix='';
				
								if ($row->pix)
								{
									if (file_exists('category_pix/'.trim($row->pix)))
									{
										$pix=base_url().'category_pix/'.trim($row->pix);
									}else
									{
										$pix=base_url().'images/nophoto.jpg';
									}					
								}else
								{					
									$pix=base_url().'images/nophoto.jpg';
								}
								
								echo '
					<div class="col-sm-3">
					  <a style="cursor:pointer;" onClick="ShowVideo(\'Category/ShowCategories/'.$row->category.'\');" class="">
                        <div class="category-preview__image">
                          <span class="lazy-bg-img" data-original="'.$pix.'"></span>
                        </div>
                        <div class="category-preview__name">'.trim($row->category).'</div>
                      </a>
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
            
            <div class="section categories scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section-heading">
                  <h4>Categories</h4>
                </div>
                <div class="row">
                
                  <div class="col-xxl-12 col-md-12 col-sm-12"><!--col-md-9 col-sm-8-->
                    <div class="row five-columns five-columns--portrait row--flex">
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
						<div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
						<a style="cursor:pointer;" onClick="ShowVideo(\'Category/ShowCategories/'.$cat.'\');" class="category-preview">
                          <div class="category-preview__image">
                            <span class="lazy-bg-img" data-original="'.$pix.'"></span>
                            <div class="category-preview__info">
                              <div class="category-preview__count"></div>
                            </div>
                          </div>
                          <div class="category-preview__name">'.$cat.'</div>
                        </a>
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
            </div>
            
            <!--BANNERS--><?php include('adverts.php'); ?><!--/BANNERS-->

          </div>
          <!--/CATEGORIES-->
        </main>
        <!--/MAIN-->
        
        <!--FOOTER-->
       <?php include('newuserfooter.php'); ?>
        <!--/FOOTER-->
     </div>
</div>

 <!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>lcss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->

</body>
</html>