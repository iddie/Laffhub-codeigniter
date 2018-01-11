<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::About Us</title>
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
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-71459616-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-71459616-1');
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
    });
</script>
</head>
<body class="page">

<div class="page__layout">
      <div class="overlay"></div>
      
      <!--HEADER--><?php include('newusernav.php'); ?><!--/HEADER-->
      
      <div id="content-ajax">
      	<!--MAIN-->
        <main class="page__main main">
           <!--INFO-->
          <div class="inner">
            <div class="info">
              <div class="container scrollreveal scrollAnimateFade">
                <div class="info__inner">
                  <div class="info__content">
                    
                   	<aside class="aside-left">
                      <div class="aside-left__inner">
                        <a href="#" class="btn aside-left__toggle"> Show menu </a>
                        <div class="aside-left__collapse">
                          <div class="aside__group">
                            <h5 class="aside__heading">Information</h5>
                            <ul class="aside__menu">
                              <li>
                                <a href="<?php echo site_url('Termofuse'); ?>" class="">Terms of Use</a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('Privacypolicy'); ?>" class="">Privacy Policy</a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('Cookiepolicy'); ?>" class="">Cookie Policy</a>
                              </li>
                              <li class="is-active">
                                <a href="<?php echo site_url('Aboutus'); ?>" class="">About Us</a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('Faq'); ?>" class="">FAQ</a>
                              </li>
                          </div>
                        </div>
                      </div>
                    </aside>
                    
                    <div class="info__article">
                      <article class="article">
                        <div class="article__inner">
                          <h3>About Us</h3>
                          <h4> </h4>
                          <p></p>
                          <br>
                        </div>
                      </article>
                    </div>
                  </div>
                  <aside class="aside visible-xl visible-xxl">
                    <div class="bnr-container">
                      <a href="#">
                        <div class="lazy-img banner-image">
                          <img data-original="<?php echo base_url(); ?>lcss/images/banner_01.jpg" src="#" alt="banner image"> </div>
                      </a>
                    </div>
                  </aside>
                </div>
              </div>
            </div>
          </div>
          <!--/INFO-->
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