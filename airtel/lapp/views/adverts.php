 <div class="section section_banners scrollreveal scrollAnimateFade">
      <div class="container">
        <div class="row">
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
                            if (file_exists('http://laffhub.com/ads_pix/'.trim($row->pix)))
                            {
                                $pix='http://laffhub.com/ads_pix/'.trim($row->pix);
                            }else
                            {
                                $pix='http://laffhub.com/images/nophoto.jpg';
                            }					
                        }else
                        {					
                            $pix='http://laffhub.com/images/nophoto.jpg';
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