<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::Term Of Use</title>
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
  
.collapsibleList li{
  list-style-image : url('<?php echo base_url();?>images/arrowhead.png');
  cursor           : auto;
}

.collapsibleList li ol li{
  list-style-image:none;
  cursor           : auto;
}

li.collapsibleListOpen{
  list-style-image : url('<?php echo base_url();?>images/button-open.png');
  cursor           : pointer;
}

li.collapsibleListClosed{
  list-style-image : url('<?php echo base_url();?>images/button-closed.png');
  cursor           : pointer;
}

</style>
<!--/CSS CRITICAL-->


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>lcss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/CollapsibleLists.js"></script>


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>


<script>

(function($){
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	
	
	
	$(document).ready(function()
	{
		CollapsibleLists.applyTo(document.getElementById('divFAQ'));		 
	});
})(jQuery);

</script>
</head>
<body class="page">

<div class="page__layout">
      <div class="overlay"></div>
      
      <!--HEADER--><?php include('newusernav.php'); ?><!--/HEADER-->
      
      <div id="content-ajax">
      	<!--MAIN-->
        <main class="page__main main">
          
        </main>
        <!--/MAIN-->
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
                              <li>
                                <a href="<?php echo site_url('Aboutus'); ?>" class="">About Us</a>
                              </li>
                              <li class="is-active">
                                <a href="<?php echo site_url('Faq'); ?>" class="">FAQ</a>
                              </li>
                          </div>
                        </div>
                      </div>
                    </aside>
                    
                    <div class="info__article">
                      <article class="article">
                        <div class="article__inner">
                        
                          <h3>Frequently asked questions</h3>
                           
                           <div id="divFAQ">
                                <ul class="collapsibleList">
                                  <li>
                                    What is LaffHub service?
                                    <ul>
                                      <li style="text-align:justify;">LaffHub is a Video on Demand (VoD) service providing rich comedy content to the African community. It's a VoD service that can be accessed using a mobile phone, desktop computer and tablet. Customer can enjoy the service just by having an active subscription.</li>
                                    </ul>
                                  </li>
                                 
                                  <li>
                                    Who is LaffHub for?
                                    <ul>
                                      <li style="text-align:justify;">LaffHub is for everyone. Smartphone users, desktop users who wishes to stream comedy clips.</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    How do I access LaffHub?
                                    <ul>
                                      <li style="text-align:justify;">To access LaffHub, visit www.laffhub.com, click on Member Login and select your operator network.</li>
                                    </ul>
                                  </li>
                                                                    
                                  <li>
                                    Do I require a subscription package to access LaffHub?
                                    <ul>
                                      <li style="text-align:justify;">Yes you do</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    How many subscription packages are available?
                                    <ul>
                                      <li style="text-align:justify;">The only available package is <b>Monthly Plan</b> which cost &#8358;500 per month.</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    Do I require data plan to access LaffHub?
                                    <ul>
                                      <li style="text-align:justify;">Yes you need internet/data to access LaffHub.</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    Can I watch LaffHub on my mobile device, Tablet and PC?
                                    <ul>
                                      <li style="text-align:justify;">Yes, you can watch LaffHub across any device. The website is very responsive and adapts easily to any device.</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    I see a different version of LaffHub when I use another network. What does this mean?
                                    <ul>
                                      <li style="text-align:justify;">LaffHub is launched across various mobile network providers. Users on the other networks will see the web page aligned with the mobile operator’s brand</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    How is my subscription different when I am on other networks?
                                    <ul>
                                      <li style="text-align:justify;">Subscription is same across all mobile operator's platform.</li>
                                    </ul>
                                  </li>
                                  
                                  <li>
                                    Can I access LaffHub when I am out of Nigeria?
                                    <ul>
                                      <li style="text-align:justify;">Yes, you can access LaffHub outside Nigeria. To subscribe, use your debit/credit card to subscribe to LaffHub.</li>
                                    </ul>
                                  </li>
                                                                    
                                   <li>
                                    How do I logout of LaffHub page?
                                    <ul>
                                      <li style="text-align:justify;">Click on "My Account" Menu at the top right side of the page, and click on "Sign Out"</li>
                                    </ul>
                                  </li>
                                </ul>
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