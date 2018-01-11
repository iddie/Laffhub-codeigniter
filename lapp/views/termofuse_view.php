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
</style>
<!--/CSS CRITICAL-->

<link rel="stylesheet" href="<?php echo base_url(); ?>lcss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109230973-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-109230973-1');
</script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

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
                              <li class="is-active">
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
                          <h3>TERMS AND CONDITIONS FOR USAGE</h3>
                          
                          <h4>1. INTRODUCTION</h4>
                          
                          <p align="justify">Welcome to LaffHub.com (the "Site"), owned and operated by EFLUXZ Mobile Solutions ("EFLUXZ MOBILE SOLUTION" "we" "our"). We are a company registered in Nigeria. We are a subscription service that provides our members ("you" "yourself" "Users") with access to content including but not limited to short comedy clips. Comic movies, live comedy performances, access to motion pictures, television and other audio-visual entertainment ("content") delivered over the Internet, to Internet enabled devices.
                          
                          <ul>
                          	<li>The Terms and Conditions set out below including our privacy policy, constitute the terms and conditions for the use and enjoyment of the laff Hub ("our" "we") website ("site"). Please take a few minutes to review these Terms and Conditions. Your Use of This site is governed by These Terms and Conditions as well as our Privacy Policy. </li>
                            <li>Your use of this site constitutes your agreement to follow these rules and to be bound by them. If you do not agree with any of these Terms and Conditions, you are strongly advised not to use our site. Further, you shall be subject to any additional terms of use that apply when you use certain products (for example, a voucher) or posted guidelines or rules applicable to our service, which may be posted and modified from time to time. All such guidelines are hereby incorporated by reference into the Terms.</li>
                          </ul>
                          </p>
                          
                          
                         
                          <h4>2. MODIFICATION OF THIS AGREEMENT</h4>
                          
                          <p align="justify"><ul>
                          <li>We reserve the right to revise the terms of this Agreement, at any time and from time to time, for any reason in our sole discretion by posting an updated Terms of Use Agreement without advance notice to you. Your use of this website following any such change constitutes your agreement to follow and be bound by the Terms and Conditions as changed. For this reason, we encourage you to review these Terms and Conditions whenever you use this website.</li>

                          <li>By using LaffHub service, you consent to receiving electronic communication from LaffHub and EFLUXZ Mobile Solutions relating to your account. We will communicate with you by e-mail, or by posting notices on the Site or through other methods. For contractual purposes, you consent to receive communications from us electronically and you agree that all agreements, notices, disclosures and other communications that we provide you electronically satisfy any legal requirement that such communication be in writing. You also consent to receiving certain other communication from us, such as newsletters, special offers, questionnaires, customer surveys or other announcements via email or other methods. You may opt out of receiving non-transactional communications, including marketing communications from us by following the directions in our e-mail to “unsubscribe” from our mailing list, or by sending an e-mail request to <a href="mailto:support@laffhub.com">support@laffhub.com</a>. Please be aware that if you chose not to receive such communication, certain offers attached to services you have chosen may be affected. We will still communicate with you in connection with transactional communications, including but not limited to servicing your account, invoices and customer services. Please review our privacy policy here <a href="<?php echo site_url('Privacypolicy'); ?>">http://www.laffhub.com/privacy</a>, for other choices with regards to opting out of cookies and interest based advertising from third party advertising agencies.</li>
                          
                          <li>We continually update the LaffHub service, including the content library, delivery methods and pricing. And we may add or remove content from our Service, or change the basis on which the content is available on the service. We reserve the right in our sole and absolute discretion to make changes from time to time and without notice in how we offer and operate our service.</li>
                          </ul></p>

						<h4>3. ELIGIBILITY</h4>
                        
                                                
                        <p align="justify"><br>
						<ul>
                        	<li><p align="justify">You must be <b>18 years</b> or have attained the <b>age of majority in your territory or country</b> to register as a member of the site. You must provide us with true, accurate, current and complete information about yourself (as prompted by the registration form).</p>
                        	<p align="justify">In furtherance of this, we reserve the right to ask for written proof of age or eligibility from you. If we discover or suspect that you do not comply with the age requirements, we reserve the right to suspend or terminate your membership of the Website or use of the Website immediately and without notice and/or disqualify you from any application process.</p>
                        </li>
                        </ul>
                        </p>
                        
                        
                        <h4>4. Subscription, Free Trials, Billing, Renewal and Cancellation</h4>
                        
                        <h5><i>Subscription</i></h5>
                        <p align="justify">
                            <ul>
                                <li><b>Reoccurring Subscription:</b> Your LaffHub subscription, which may start with a free trial, will continue, daily, weekly,  month-to-month, or according to your chosen subscription plan “Subscription Period” as currently available on the Site (subject to a few exceptions, such as Payments by direct bank and cash payments), unless and until you cancel your subscription or we terminate or discontinue it.</li>
                                
                                <li><b>Differing Subscription Plans:</b> We may offer a number of subscription plans, including special promotional plans with differing conditions and limitations. You can find specific details regarding your subscription plan with LaffHub by visiting the Site. We reserve the right to modify, terminate or otherwise amend our offered subscription plans.</li>
                            </ul>
                        </p>
                                                
                        <h5><i>Free Trials</i></h5>
                        <p align="justify">Your LaffHub subscription may start with a free trial. You can cancel your LaffHub free trial at any time. Unless otherwise noted, free trials may not be combined with any other offers and are limited to one per member. Free trials are for new and certain former members only. EFLUXZ reserves the right, in its absolute discretion to determine your free trial eligibility.</p>
                        
                        <p align="justify">Subscription plans detailed during the free trial sign up may differ based on your geographic location. Efluxz reserves the right, in its absolute discretion to determine the default Subscription plan post free trial.</p>
                       
                        <h5><i>Billing</i></h5>
                        <p align="justify">By signing up for your LaffHub subscription, you are expressly agreeing that we are authorized to charge you a subscription fee at the then current rate, daily, weekly  every one (1) month, three (3) months, six (6) months, or twelve (12) months ("Subscription Period") depending on your subscription plan, in addition to any other fees or charges you may incur in connection with your use of the LaffHub service, including any applicable taxes to the Payment Method accepted by Efluxz Mobile Solutions. You acknowledge that the amount billed during each Subscription Period may vary for reasons that include promotional offers, changes in your Subscription plan, or changes in any applicable taxes and you authorize Efluxz to charge your Payment Method for the corresponding amounts.</p>
                        
                        <h5><i>Reoccurring billing</i></h5>
                        <p align="justify">We automatically bill your Payment Method on the calendar day corresponding to the commencement of your Subscription Period and each Subscription Period thereafter (subject to a few exceptions, such as Payments by direct bank and cash payments) unless and until you cancel your subscription. By way of example, if you became a paying subscriber on July 1, for a one-month subscription plan, your Payment Method would next be billed on August 1. Also, if you became a paying subscriber on June 1, for a 6-month subscription plan, your Payment Method would next be billed on January 1. Subscription fees are fully earned upon payment. We reserve the right to change the timing of our billing. In the event your Subscription Period begins on a day not contained in a given month, we may bill your Payment Method on a day in the applicable month or Subscription Period or such other day, as we deem appropriate.</p>
                        
                        <p align="justify">As used in these Terms, "Billing" shall indicate either a charge or debit, as applicable, against your Payment Method.</p>
                        
                        <h5><i>Billing Cycle</i></h5>
                        
                        <p align="justify">The subscription fee will be billed at the beginning of your Subscription Period, and on each Subscription Period renewal thereafter unless and until you cancel your subscription or the account or service is otherwise suspended, terminated or discontinued pursuant to these Terms. To see the commencement date for your next billing cycle or renewal period, go to the Status section on "Your Profile" page on the Site. Alternatively, you may refer to your email invoice confirming your subscription from <a href="mailto:support@laffhub.com">support@laffhub.com</a>. However, if you change your Payment Method, this could result in changing the calendar day upon which you are billed.</p>
                        
                        <h5><i>Automatic Renewal</i></h5>
                        <p align="justify">If your Payment Method expires and you do not edit your Payment Method information or cancel your account, your service will automatically renew without prior notice to you, using the details of your previous payment. You must cancel your service before it renews in order to avoid us billing your account. However, where there are insufficient funds in your account, your account access would be terminated following several attempts to renew your subscription and communicate the issue to you.</p>
                        
                        <h5><i>No Refunds</i></h5>
                        <p align="justify">All fees and charges are non-refundable including refunds for partial months and content not watched. Very rarely, if there are special circumstances where LaffHub determines it is appropriate (e.g., the LaffHub service is unavailable for days due to technical difficulties), we may provide credits to affected subscribers. The amount and form of such credits, and the decision to provide them, are at LaffHub's sole and absolute discretion, and the provision of credits in one instance does not entitle anyone to credits in the future under similar or different circumstances.</p>
                        
                        <h5><i>Cancelling your subscription</i></h5>
                        <p align="justify">You can view details on how to cancel your account via your LaffHub Profile page. Alternatively, you can send an email to <a href="mailto:support@laffhub.com">support@laffhub.com</a> requesting that we cancel your account. You must cancel your subscription 72 hours before it renews each Subscription Period in order to avoid the next Subscription Period’s billing. You can cancel your LaffHub subscription at anytime. However note that <b>WE DO NOT OFFER REFUNDS FOR PARTIAL MONTHS</b>. If you cancel your subscription prior to the allotted expiration, you will continue to have access to LaffHub for the remainder of your Subscription Period. To cancel, please click on "Your Profile" at the top right of your Profile window on the Site, and select Unsubscribe option. Alternatively, if that option is not available on your account, you can send an email to <a href="mailto:support@laffhub.com">support@laffhub.com</a>  with "cancellation" written in the subject line and your username (at the top right of the Profile window) and your email address in the body of the email. </p>
                        
                        <h5><i>Price changes</i></h5>
                        <p align="justify">We reserve the right to adjust pricing for our service or any components thereof, including pricing for subscription plans, in any manner and at any time as we may determine in our sole and absolute discretion.</p>
                        
                        <h4>5. COPYRIGHT</h4>
                        
                        <p align="justify"></p>
                        <ul>
                        	<li>All copyrights and other rights in the materials included in these web pages, including, but not limited to audio clips, video clips, page headers, text, images, illustrations and all graphics, are owned and controlled by <b>laffHub</b> under intellectual property rights and/or licenses.</li>
                            <li>You may only access, use or download these materials for your own personal, non-commercial use.</li>
                            <li>You are not permitted to download, copy, broadcast, store, transmit, add to, create a derivative work, adapt or alter in any way the content of the web pages that are part of the Website, or any part of them, for any other purpose without our prior written permission.</li>
                        </ul>
                        
                        
                        <h4>6. SECURITY OF ACCOUNT</h4>
                        
                        <p align="justify">
                        <ul>
                        	<li>You accept that it is your sole responsibility to maintain the confidentiality of your password and you are entirely responsible for any and all activities that occur under your account.</li>
                            <li>You agree to:
                            	<ul type="square">
                                	<li>notify us immediately of any unauthorized use of your account or any other breach of security; and</li>
                                    <li>exit from your account at the end of each session.</li>
                                    <li>do all things necessary to keep your account secured and comply with the terms and conditions set out above.</li>
                                </ul>
                            </li>
                        </ul>
                        </p>
                        
                        
                        <h4>7. WARRANTY</h4>
                        
                        <p align="justify">
                        <ul>
                        	<li>The information contained on this site is provided in good faith, and every reasonable effort is made to ensure that it is accurate and up to date. Accordingly, this information is provided 'as is' without warranty of any kind.</li>
                            <li>Neither we nor any third party provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this site for any particular purpose.</li>
                            <li>You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
                        </ul>
                        </p>
                        
                                                
                        <h4>8. LIABILITY DISCLAIMER</h4>
                        
                        <p align="justify">
                        <ul>
                        	<li>Your use of any information or materials on this site is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
                            <li>In no event shall <b>laffHub</b> be liable for any damage arising, directly or indirectly, from the use of the information contained on this site including damages arising from inaccuracies, omissions or errors.</li>
                            <li>Any person relying on any of the information contained on this site or making any use of the information contained herein, shall do so at their own risk. We hereby disclaim any liability and shall not be held liable for any damages including, without limitation, direct, indirect or consequential damages including loss of revenue, loss of profit, loss of opportunity or other losses howsoever.</li>
                        </ul>
                        </p>
                        
                        <h4>9. LINKS TO OTHER WEBSITES</h4>
                        
                        <p align="justify">
                        <ul>
                        	<li>Our online services may contain links to other websites which are not controlled by us. Any such links are provided solely for your convenience and this does not amount to an endorsement by us of that website or its content.</li>
                            <li>By using those links, you will leave our online services. Please bear in mind that you do so at your own risk. These Terms and Conditions apply to your use of our online services and we cannot be responsible for any content, or products and services available on any other website as we do not control them.</li>
                        </ul>
                        </p>

						                        
                       <h4>10. APPLICABLE LAW</h4> 
                       
                       <p align="justify">
                       <ul>
                       	<li>The laws applicable to this terms and conditions shall at all times be the Laws of the Federal Republic Of Nigeria and all international conventions, treaties and all statutes to which the Federal Republic of Nigeria is a party.</li>
                        <li>If for any reason, the applicable laws do not allow the limitation of liability as set forth above, so this limitation of liability may not apply to you; or if any part of this limitation on liability is found to be invalid or unenforceable for any reason, then the aggregate liability of <b>laffHub</b> under such circumstances for liabilities that otherwise would have been limited shall not exceed Five Thousand Naira (&#8358;5,000).</li>
                       </ul>
                       </p>
                        
                                                
                        
                        </div>
                      </article>
                    </div>
                  </div>
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