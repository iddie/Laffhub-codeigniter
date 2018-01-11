<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>LaffHub::Search Results</title>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    
	<script>
(function($){
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	var searchstring='<?php echo $searchstring; ?>';
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	var self;
	
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
				klass : 'airel-custom-theme',
				bgPath : '<?php echo base_url();?>images/',
				content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Search Result. Please Wait...</b></p></center>'
			}
		);
		
		$('#btnNext').click(function(e) {
            try
			{
				var page='<?php echo $page; ?>';
			
				if (!page) page=1; else page=parseInt(page,10)+1;
				
				$.redirect("<?php echo site_url('Searchresult/Search');?>",{searchstring: searchstring, page:page});				
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
				
				$.redirect("<?php echo site_url('Searchresult/Search');?>",{searchstring:searchstring, page:page}); 
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
                       
           <!--Search result Starts-->
            <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section__inner">
                  <div class="section-heading">
                    <h4>Search result</h4>
                  </div>
                  <div class="row row--flex">
                  	<?php
						if (count($SearchResult) > 0)
						{
							foreach($SearchResult as $row):
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
                  
                  <!--pagination-->
                  <div align="center" class="page-controls ">
                    <a id="btnPrevious" class="btn previous round pagination">&laquo; Prev</a>
					<a id="btnNext" class="btn next round pagination">Next &raquo;</a>
                  </div> 
                </div>
              </div>
            </div>
            <!--Search result Ends-->
            
            
            <!--Adverts Starts-->
            <div class="section videos-section scrollreveal scrollAnimateFade">
              <div class="container">
                <div class="section__inner">
                  <div class="row row--flex">                  	
                   	<?php                
					if (count($ActiveAdverts) > 0)
					{
						foreach($ActiveAdverts as $row):
							if ($row->pix)
							{
								$tit=trim($row->title);
								$pix='';
								
								if ($row->pix)
								{
									if (file_exists('ads_pix/'.trim($row->pix)))
									{
										$pix=base_url().'ads_pix/'.trim($row->pix);
									}else
									{
										$pix=base_url().'images/nophoto.jpg';
									}					
								}else
								{					
									$pix=base_url().'images/nophoto.jpg';
								}
								
								echo '
				 <div class="col-lg-3 col-sm-6">
                    <div class="bnr-container">
                      <a href="#" title="'.$tit.'">
                        <div class="lazy-img banner-image">
                          <img data-original="'.$pix.'" src="#" alt="banner image"> </div>
                      </a>
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
            <!--Adverts Ends-->
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
    <script src="<?php echo base_url(); ?>acss/js/main.js" async></script>    
    <!--/SCRIPTS MAIN-->
  </body>
</html>