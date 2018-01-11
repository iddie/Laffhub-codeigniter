<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
  
<title>LaffHub::Comedians</title>
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
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-1');
</script>


<script>
function ShowVideo(code)
{	
	window.location.href='<?php echo base_url(); ?>' + code;
}
</script>

</head>
<body class="page">
<div class="page__layout">
  <div class="overlay"></div>
  <!--HEADER-->
 	 <?php include('newusernav.php'); ?>
      <!--/HEADER-->
      
      <div id="content-ajax">
        <!--MAIN-->
        <main class="page__main main">
          <!--ACTORS-->
          <div class="inner">

            <div class="section section--last categories scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section-heading">
                  <h4>Comedians</h4>
                </div>
                
                <div class="row row--flex">
                <?php
					if (count($Comedians) > 0)
					{										
						foreach($Comedians as $row):
							$row->comedian=trim($row->comedian);
							$row->pix=trim($row->pix);
							$pix=$AdminRoot.'images/nophoto.jpg';
							
							if ($row->comedian)
							{
								$cm=$row->comedian;
								
								if ($row->pix)
								{
									$pix=$AdminRoot.'comedian_pix/'.$row->pix;//getcwd()
								}
		
								echo '
					<div class="col-xxl-2 col-lg-3 col-md-4 col-sm-6">
                    <a title="Click to view '.$cm.'\'s videos" href="'.site_url('Comedian/ShowComedian/'.$row->id).'" class="category-preview ">
                      <div class="category-preview__image">

                        <span class="lazy-bg-img" data-original="'.$pix.'"></span>
                      </div>
                      <div class="category-preview__name">'.$cm.'</div>
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
            
           <!--BANNERS--><?php include('adverts.php'); ?><!--/BANNERS-->
          </div>
          <!--/ACTORS-->
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