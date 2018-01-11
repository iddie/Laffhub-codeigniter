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
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109268177-1');
</script>

<script>
(function($){
	
	var self;

})(jQuery);	
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
          <!--INDEX-->
          <div class="inner inner--withslider">
            <!--Slider Starts-->
            <div class="hero-slider-wrap scrollreveal scrollAnimateHeroSlider">
              <div class="hero-slider">
                <div class="sld">
                  <div class="hero-slider__slide">
                    <div class="hero-slider__slide-image">
                      <span class="hero-slider__lazy-load-img" data-original="<?php echo base_url(); ?>lcss/images/ad15.jpg"></span>
                    </div>
                    <div class="container">
                      <div class="row row--flex">
                        <div class="col-xxl-6 col-xl-7">
                          <div class="hero-slider__slide-left">
                            <div class="hero-slider__slide-text">
                              <div class="hero-slider__button">
                                <a class="">  </a><!--Watch now-->
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="sld">
                  <div class="hero-slider__slide">
                    <div class="hero-slider__slide-image">
                      <span class="hero-slider__lazy-load-img" data-original="<?php echo base_url(); ?>lcss/images/ad12.jpg"></span>
                    </div>
                    <div class="container">
                      <div class="row row--flex">
                        <div class="col-xxl-6 col-xl-7">
                          <div class="hero-slider__slide-left">
                            <div class="hero-slider__slide-text">
                              <div class="hero-slider__button">
                                <a class=" ">  </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="sld">
                  <div class="hero-slider__slide">
                    <div class="hero-slider__slide-image">
                      <span class="hero-slider__lazy-load-img" data-original="<?php echo base_url(); ?>lcss/images/ad13.jpg"></span>
                    </div>
                    <div class="container">
                      <div class="row row--flex">
                        <div class="col-xxl-6 col-xl-7">
                          <div class="hero-slider__slide-left">
                            <div class="hero-slider__slide-text">
                              <div class="hero-slider__button">
                                <a class="">  </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="sld">
                  <div class="hero-slider__slide">
                    <div class="hero-slider__slide-image">
                      <span class="hero-slider__lazy-load-img" data-original="<?php echo base_url(); ?>lcss/images/ad14.jpg"></span>
                    </div>
                    <div class="container">
                      <div class="row row--flex">
                        <div class="col-xxl-6 col-xl-7">
                          <div class="hero-slider__slide-left">
                            <div class="hero-slider__slide-text">
                              <div class="hero-slider__button">
                                <a class="">  </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="container">
                <div class="hero-slider-dots">
                  <div class="hero-slider-nums"></div>
                </div>
              </div>
            </div><!--Slider Ends-->
           
          <!--Featured Starts-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>Featured</h4><!--Featured-->
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($FeaturedVideos) > 0)
                          {
                              foreach($FeaturedVideos as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
                                      $views='0 View'; $comedian=''; $likes='0'; $dislikescnt=0;
                                      $likescnt=0; $likestotal=0;
                                      $videourl='c-'.$row->video_code;

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
				<div class="col-xs-6 col-sm-3 col-lg-2">
                  <a href="'.site_url($videourl).'" class="video-preview">
                    <div class="video-preview__image">
                      <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                      <div class="video-preview__info">
                        <div class="video-preview__duration">'.$row->duration.'</div>
                        <div class="video-preview__likes">'.$likes.'%</div>
                        <div class="video-preview__quality">HD</div>
                      </div>
                    </div>
                    <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                    <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                    <h5 class="video-preview__views">'.$views.'</h5>
                    <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
          <!--Featured Ends-->  
       
           
           <!--Latest Videos Starts-->
            <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section__inner">
                  <div class="section-heading">
                    <h4>Latest Videos</h4>
                  </div>
                  <div class="row row--flex">                  	
                    <?php
						if (count($LatestVideos) > 0)
						{
							foreach($LatestVideos as $row):
								if ($row->thumbnail and $row->video_code)
								{
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
					 <div class="col-lg-2 col-sm-3">
                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                        <div class="video-preview__image">
                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                          <div class="video-preview__info">
                            <div class="video-preview__duration">'.$row->duration.'</div>
                            <div class="video-preview__likes">'.$likes.'%</div>
                            <div class="video-preview__quality">HD</div>
                          </div>
                        </div>
                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                        <h5 class="video-preview__views">'.$views.'</h5>
                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
            <!--Latest Videos Ends-->
           <!--Most viewed Starts-->
            <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section__inner">
                  <div class="section-heading">
                    <h4>Most Viewed</h4><!--Most Popular-->
                  </div>
                  <div class="row row--flex">
                  	<?php
						if (count($PopularMovies) > 0)
						{
							foreach($PopularMovies as $row):
								if ($row->thumbnail and $row->video_code)
								{
									 $views='0 View'; $comedian=''; $likes='0'; $dislikescnt=0;
									 $likescnt=0; $likestotal=0;
									 $videourl='c-'.$row->video_code;
									 
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
					<div class="col-xs-6 col-sm-3 col-lg-2">
                      <a href="'.site_url($videourl).'" class="video-preview">
                        <div class="video-preview__image">
                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                          <div class="video-preview__info">
                            <div class="video-preview__duration">'.$row->duration.'</div>
                            <div class="video-preview__likes">'.$likes.'%</div>
                            <div class="video-preview__quality">HD</div>
                          </div>
                        </div>
                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                        <h5 class="video-preview__views">'.$views.'</h5>
                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
            <!--Most viewed Ends-->
            
          <!--StandUp Comedy Listing-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>StandUp Comedy</h4>
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($StandUpComedy) > 0)
                          {
                              foreach($StandUpComedy as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
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
                                     <div class="col-lg-2 col-sm-3">
                                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                                        <div class="video-preview__image">
                                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                                          <div class="video-preview__info">
                                            <div class="video-preview__duration">'.$row->duration.'</div>
                                            <div class="video-preview__likes">'.$likes.'%</div>
                                            <div class="video-preview__quality">HD</div>
                                          </div>
                                        </div>
                                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                                        <h5 class="video-preview__views">'.$views.'</h5>
                                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
    
          <!--Comedy Skits Listing-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>Comedy Skits</h4>
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($ComedySkits) > 0)
                          {
                              foreach($ComedySkits as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
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
                                     <div class="col-lg-2 col-sm-3">
                                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                                        <div class="video-preview__image">
                                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                                          <div class="video-preview__info">
                                            <div class="video-preview__duration">'.$row->duration.'</div>
                                            <div class="video-preview__likes">'.$likes.'%</div>
                                            <div class="video-preview__quality">HD</div>
                                          </div>
                                        </div>
                                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                                        <h5 class="video-preview__views">'.$views.'</h5>
                                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
    
    
          <!--Comedy News Listing-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>Comedy News</h4>
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($ComedyNews) > 0)
                          {
                              foreach($ComedyNews as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
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
                                     <div class="col-lg-2 col-sm-3">
                                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                                        <div class="video-preview__image">
                                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                                          <div class="video-preview__info">
                                            <div class="video-preview__duration">'.$row->duration.'</div>
                                            <div class="video-preview__likes">'.$likes.'%</div>
                                            <div class="video-preview__quality">HD</div>
                                          </div>
                                        </div>
                                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                                        <h5 class="video-preview__views">'.$views.'</h5>
                                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
    
          <!--Just For Laugh Listing-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>Just For Laugh Gags</h4>
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($JustForLaughs) > 0)
                          {
                              foreach($JustForLaughs as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
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
                                     <div class="col-lg-2 col-sm-3">
                                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                                        <div class="video-preview__image">
                                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                                          <div class="video-preview__info">
                                            <div class="video-preview__duration">'.$row->duration.'</div>
                                            <div class="video-preview__likes">'.$likes.'%</div>
                                            <div class="video-preview__quality">HD</div>
                                          </div>
                                        </div>
                                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                                        <h5 class="video-preview__views">'.$views.'</h5>
                                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
    
          <!--Arewa Listing-->
          <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                  <div class="section__inner">
                      <div class="section-heading">
                          <h4>Arewa</h4>
                      </div>
                      <div class="row row--flex">
                          <?php
                          if (count($Arewa) > 0)
                          {
                              foreach($Arewa as $row):
                                  if ($row->thumbnail and $row->video_code)
                                  {
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
                                     <div class="col-lg-2 col-sm-3">
                                      <a href="c-'.$row->video_code.'" class="video-preview video-preview--md">
                                        <div class="video-preview__image">
                                          <span class="lazy-bg-img" data-original="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'"></span>
                                          <div class="video-preview__info">
                                            <div class="video-preview__duration">'.$row->duration.'</div>
                                            <div class="video-preview__likes">'.$likes.'%</div>
                                            <div class="video-preview__quality">HD</div>
                                          </div>
                                        </div>
                                        <h4 class="video-preview__comedianName">'.$comedian.'</h4>
                                        <h4 class="video-preview__comedianName">'.$row->video_title.'</h4>
                                        <h5 class="video-preview__views">'.$views.'</h5>
                                        <h5 class="video-preview__descr">'.trim($row->description).'</h5>
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
          <!--/INDEX-->
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