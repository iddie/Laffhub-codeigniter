<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url();?>js/modernAlert.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.redirect.js"></script>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-109230973-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-109230973-1', 'auto');
</script>

<script>
(function($){
	
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
         //$(document).ajaxStop($.msg('unblock'));
		
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
					
		$('#btnSearch').click(function(e) {
            try
			{
				
				var txt=$.trim($('#txtSearch').val());
				
				if (!txt)
				{
					m='Please enter the text to search for.';
					
					bootstrap_alert.warning(m);					
					alert(m, 'LaffHub Message');
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);	
					
					$('#txtSearch').focus();
					
					return false;
				}
				
				$.msg(
					{
						autoUnblock : true ,
						clickUnblock : true,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Searching For Video. Please Wait...</b></p></center>'
					}
				);
				
				$.redirect("<?php echo site_url('Searchresult/Search');?>",{searchstring: txt});				
			}catch(e)
			{
				$.msg('unblock');
				var m='Search Button Click ERROR:\n'+e;
			   
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
        });
		
		$('#txtSearch').keyup(function(e) {
            try
			{
				if (e.keyCode == 13)
				{
					$('#btnSearch').trigger('click');
				}
			}catch(e)
			{
				$.msg('unblock');
				var m='Keyup ERROR:\n'+e;
			   
				bootstrap_alert.warning(m);					
				alert(m, 'LaffHub Message');
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}
        });
    });//End document ready

})(jQuery);

function NewPage(url)
{
	window.location.href='<?php echo base_url(); ?>' + url;
}

function SubscriberSignOut(input)
{
	if (input === true)
	{
		window.location.href='<?php echo site_url('Subscriberlogout'); ?>';
	} else
	{
		m='Sign Out Cancelled';
		alert(m, 'LaffHub Message');
		bootstrap_alert.warning(m);
		setTimeout(function() {
			$('#divAlert').fadeOut('fast');
		}, 10000);
	}
}

function Ask()
{
	confirm('Do you want to sign out? (Click <b>Yes</b> to proceed or <b>No</b> to abort)', 'LaffHub Message', SubscriberSignOut,null,{ok : 'Yes', cancel : 'No'});
}

</script>


 
 <header class="page__header header ">
 	 	 <div class="container">
         	<div id="divAlert" align="center"></div>
         </div>
     
        <div class="container">
       
        
          <div class="header__left">
            <a href="<?php echo site_url('Subscriberhome'); ?>" class="logo">
              <img src="<?php echo base_url(); ?>lcss/images/logo.png" alt="">
            </a>
            
            <nav class="header__nav">
              <ul class="header__nav-list">
                <li class="header__nav-item">
                  <a style="cursor:pointer;" onClick="NewPage('Subscriberhome')" class="header__nav-link"> Home</a>
                </li>
                <li class="header__nav-item header__nav-item--dropdown">
                  <a title="Video Categories" style="cursor:pointer;" onClick="NewPage('Categories')" class="header__nav-link  mobile-ajax-off"> Categories </a>
                  <div class="header__nav-dropdown header__nav-dropdown--categories">
                   <?php
				   		if (count($Categories)>0)
						{
							$i=0;
							
							foreach($Categories as $row)
							{
								if ($row->category)
								{
									$i++;	
									
									if ($i==1) echo '<ul>';							
									
									echo '
										<li><a style="cursor:pointer;" onClick="NewPage(\'Category/ShowCategories/'.$row->category.'\')" class="">'.trim($row->category).'</a></li>
									';
									
									if ($i == 4)
									{
										 echo '</ul>';
										$i=0;
									}
								}
							}
						}
				   ?>
                   
                  </div>
                </li>
                <li class="header__nav-item">
                  <a style="cursor:pointer;" onClick="NewPage('Comedianslist')" class="header__nav-link "> Comedians </a>
                </li>
                
                <li class="header__nav-item">
                  <a style="cursor:pointer;" onClick="NewPage('Subscribe')" class="header__nav-link "> Subscribe </a>
                </li>
                
<!--                <li class="header__nav-item">-->
<!--                  <a class="header__nav-link "><span style="color:#5F5F5F; font-size:16px;"><span style="color:#000000;"><b>Email:</b></span> --><?php //echo $subscriber_email; ?><!-- </span></a>-->
<!--                </li>-->
              </ul>
            </nav>
          </div>
          
          
          <div class="header__right" style="margin-top:20px;">
            <div class="dropdown lng-dropdown">
              <a href="#" class="dropdown__toggle" data-toggle="dropdown">
                <span>My Account</span>
              </a>

              <ul class="dropdown-menu">
              	<li><a style="cursor:pointer;" onClick="NewPage('Subscriberhome')"><span>Dashboard</span></a></li>
                <li><a style="cursor:pointer;" onClick="NewPage('Profile')"><span>Profile</span></a></li>
                <li><a style="cursor:pointer;" onClick="NewPage('Unsubscribe')"><span>Unsubscribe</span></a></li>
               	<li><a style="cursor:pointer;" onClick="Ask();"><span>Sign Out</span></a></li>
              </ul>
	
            </div>

            <a title="Search For Video" href="#" class="search-open"></a>
            <a title="Close Search Box"  href="#" class="search-close"></a>
          </div>
                    
          <!-- search form -->
          <form action="#" method="post" class="search" style="margin-top:20px;">
            <input id="txtSearch" type="text" name="search" class="search__field" placeholder="Search Laffhub" autocomplete="off">
            <button id="btnSearch" class="search__button" type="button"></button>
            <div class="search__enter"> Enter </div>
          </form>
          
          
          <!-- /search form -->
          <a href="#" class="menu-open">
            <span></span>
            <span></span>
            <span></span>
          </a>
          <a href="#" class="menu-close">
            <span></span>
            <span></span>
            <span></span>
          </a>
        </div>
        <div class="mobile-menu">
          <div class="mobile-menu__scroll-content"> </div>
        </div>
      </header>
      
    