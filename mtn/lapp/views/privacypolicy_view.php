<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>LaffHub::Privacy Policy</title>
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

<?php include('googleanalytics.php'); ?>

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
                              <li class="is-active">
                                <a href="<?php echo site_url('Privacypolicy'); ?>" class="">Privacy Policy</a>
                              </li>
                              <li>
                                <a href="<?php echo site_url('Cookiepolicy'); ?>" class="">Cookie Policy</a>
                              </li>
                              <li>
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
                          <h3>PRIVATE POLICY FOR <b style="font-weight:bolder;">LAFFHUB</b></h3>
                          <h4>1. Introduction</h4>
                          <p align="justify">
                          <ul>
                          	<li><b style="font-weight:bolder;">LaffHub</b> ("we" "our" "us") respect the privacy of visitors to its website ("site"), and we recognize your need for appropriate protection and management of personally identifiable information you share with us. This policy describes how we collect and use information about visitors to this website.</li>
                            <li>This Privacy Policy for protection of personal information ("Privacy Policy" "policy") sets out our guiding principles for the collection, use or disclosure of personal information through the use of the online services available on its site <b>LAFFHUB.COM</b>. </li>
                            <li>By registering as a member of our site or otherwise submitting your personal information to us, you acknowledge and consent to our practices described below.</li>
                          </ul>
                          </p>
                          
                          <h4>2. What Information do we Collect?</h4>
                          <p align="justify">
                          	<ul>
                            	<li>There are two types of information we collect, personal information and non-personal information.</li>
                                <li>Personal information is information that identifies you; such as your name and email address.</li>
                                <li>Non-personal information does not identify you personally, but gives us information about your computer and about your activities on our site such as your IP address, browser type, the date and time you access the site, the website that referred you to our site, etc.</li>
                            </ul>
                          </p>
                          
                          <h5>2.1 Personal Information</h5>
                          <p align="justify">
                          	<ul>
                            	<li>You may use some features of our site without providing any personal information. However, in order to take advantage of all the services and products that our site provides, it may be necessary to furnish your personal information such as, but not limited to, your: name, postal address, telephone number, fax number, geographic location, email address, age, gender, birth date, credit or debit card number or other payment account number and the applicable expiration date(s), comedic preferences, purchasing history and other identification and contact information.</li>
                                <li>In addition, you may also be providing personal information when you submit comments on this Website. We collect personal information when you voluntarily provide it. If you are a registered user of our site, we may retain your information as long as you maintain your registered status.</li>
                            </ul>
                          </p>
                          
                          <h5>2.2 Non-Personal Information</h5>
                          <p align="justify">
                          	<ul>
                            	<li>In addition to the aforementioned non-personal information we collect, we and our service providers also collect and store information from your computer using "cookies". If you do not want information collected through the use of cookies, you should modify your browser preferences by deleting or declining cookies.</li>
                            </ul>
                          </p>
                          
                          
                          <h4>3 3.	Disclosure to Third Parties</h4>
                          <p align="justify">
                          	<ul>
                            	<li>It is our policy not to disclose any personal information about users of our site to third parties; the information transmitted being only available to us and/or our affiliates, as the case may be. Also we will not disclose, sell or rent to any third party or organization, for marketing purposes, any personal information you provide on this website without your prior permission.</li>
                                <li>However, we reserve the right to disclose personal information about you if required by law or if court orders are issued in that regard, including receipt of a subpoena or a search warrant.</li>
                                <li>We reserve the right to disclose any information we deem necessary in good faith to:
                                <ol type="i">
                                	<li>comply with any requirement of law or legal process;</li>
                                    <li>enforce our Terms and Conditions Agreement; or</li>
                                    <li>protect the rights of our other members.</li>
                                </ol>
                                </li>
                            </ul>
                          </p>
                          
                          <h4>4. What do we Use Your Information For?</h4>
                          <p align="justify">
                          	<ul>
                            	<li>The purpose of the personal information we collect is to identify you when you navigate on our site, to satisfy your request of services and to provide you with certain services or advertisement offers that meet your personal needs. We collect personal information about you so that we can serve you better, perform our business activities and functions and to provide best possible quality of customer service.</li>
                            </ul> 
                          </p>
                          
                          <h4>5. How do we Protect your information</h4>
                          <p align="justify">
                          	<ul>
                            	<li>We realize you value your information and so do we. Therefore, we have taken appropriate security measures to help safeguard this information from unauthorized access and disclosure. We use data security systems to encrypt your personal and financial information to reduce the risk that your information will be obtained by unauthorized persons.</li>
                                <li>Only our authorized employees are permitted to access your personal information, and they may do so only for limited reasons. Our employees are kept up-to-date on our security and privacy practices, and the servers that store personal information are in a secure environment.</li>
                                <li>We want you to feel confident using our site. However, no method of transmission over the Internet, or method of electronic storage, can be completely secure. Therefore, although we take steps to secure your information, we cannot guarantee its absolute security. If you feel your information has been compromised in any way from the use of this Website, please contact us immediately.</li>
                            </ul> 
                          </p>
                          
                          <h4>6. Do we use cookies?</h4>
                          <p align="justify">
                          	<ul>
                            	<li>We use "cookies" on this site. A cookie is a small file stored on your computer and is tied to information about you. For example, cookies enable us to save your name and email address so that the next time you access our site  from the same computer, we will recognize your computer's browser as a previous visitor and will remember your name and email address when you sign in to your account.</li>
                                <li>This makes your experience on <b>laffHub</b> easier, faster, and smoother. If you do not want information collected through the use of cookies, kindly modify your browser preferences by deleting or declining cookies.</li>
                            </ul> 
                          </p>
                          
                          <h4>7. Thirty party links</h4>
                          <p align="justify">
                          	<ul>
                            	<li>Navigation on the <b>laffHub</b> website may sometimes offer links to other websites. However, we are not responsible for the content or practices employed by the organizations sponsoring these other websites. We are not responsible for the practices, acts or policies of such third parties with respect to the protection of your personal information.</li>
                                <li>We recommend that you carefully read the statement on the protection of personal information found on these websites in order to determine in an informed manner to what extent you want or not to use these websites regarding their privacy practice.</li>
                            </ul> 
                          </p>
                          
                          <h4>8. Complaints or Queries</h4>
                          <p align="justify">
                          	<ul>
                            	<li>We try to meet the highest standards when collecting and using personal information.  For this reason, we take any complaints we receive about this very seriously.</li>
                                <li>We encourage people to bring it to our attention if they think that our collection or use of information is unfair, misleading or inappropriate.  We would also welcome any suggestions for improving our procedures</li>
                            </ul> 
                          </p>
                          
                          <h4>9. Modifications to This Policy</h4>
                          <p align="justify">
                          	<ul>
                            	<li>There may be need to make changes to this Privacy Policy in the future, which we shall do without any prior notice to you; for example to reflect changes in the law, changes in the type of services or information we provide to you or collect from you, or to correct any errors. We will try to keep all such changes to a minimum. </li>
                                <li>We recommend that you check this policy from time to time to see if it has been changed or updated.</li>
                            </ul> 
                          </p>
                          
                          <h4>10. Correct and update your information</h4>
                          
                          <p align="justify">Users can modify or change their password, and e-mail address through their “My Profile” settings. Also, Users can modify, change or delete their personal information anytime by emailing us at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.</p>
                          
                          <h4>11. Opt out</h4>
                          
                          <p align="justify">Users may opt out of receiving communications from us by following directions in our e-mail to “unsubscribe” from our mailing list, or by sending an e-mail request to <a href="mailto:support@laffhub.com">support@laffhub.com</a>.</p>
                          
                          <h4>12. Children</h4>
                          <p align="justify">Users must be at least <b>18 years old</b>, or the <b>age of majority in their province, territory or country</b>, to have our permission to use this Site. Individuals under the age of 18, or applicable age of majority, may utilize the service only with involvement of a parent or legal guardian, under such person’s account and otherwise subject to the Site’s Terms of use. Our policy is that we do not knowingly collect, use or disclose personally identifiable information about minor visitors.</p>
                          
                          
                          <h4>12. Contact Us</h4>
                          <p align="justify">
                          <ul>
                          	<li>For any questions, comments or complaints regarding this Privacy Policy, you may do so by contacting us by e-mail to: <a href="mailto:support@laffhub.com">support@laffhub.com</a></li>
                          </ul>
                          </p>
                          
                          
                          <br>
                        </div>
                      </article>
                    </div>
                  </div>
                  <aside class="aside visible-xl visible-xxl">
                    <div class="bnr-container">
                      <a href="#">
                        <div class="lazy-img banner-image">
                          <img data-original="images/banner_01.jpg" src="#" alt="banner image"> </div>
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
<script src="<?php echo base_url(); ?>acss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->

</body>
</html>