<script>

</script>

<style>

#icons{float:left;
    margin:5px;
   }

</style>
<nav class="navbar navbar-default" role="navigation" style="background:#ffffff;">
     <div class="container">
    	<div class="row">
        	<div class="col-md-4" class="makebold">
            	<span style="color:#EB2429; font-weight:bold;">Phone:</span>&nbsp;<b><?php echo $Phone; ?></b>
            </div>
            
            <!--
            <div class="col-md-4" class="makebold">
            	<span style="float:right;"><span style="color:#D88F77;">Subscription Status:&nbsp;</span><?php# echo $subscriptionstatus; ?></span>
            </div>
            
            <div class="col-md-4" class="makebold">
            	<span style="float:right;"><span style="color:#D88F77;">Expiry Date:&nbsp;</span><?php# echo $exp_date; ?></span>
            </div>
            -->
        </div>
    
    </div> 
    
    <div class="container"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-brand-centered">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
         
       </div>
       
       <div class="collapse navbar-collapse" id="navbar-brand-centered">
          <ul class="nav navbar-nav right">
          <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Categories <span class="caret"></span></a>
            <ul class="dropdown-menu" id="cboCategory">
              <?php
			  
					if (count($Categories)>0)
					{
						$cnt=0;
						
						foreach($Categories as $row)
						{
							if ($row->category)
							{
								$cnt++;
									#onClick="SelectCategory('.$cnt.',\''.trim($row->category).'\')"							
								echo  '<li><a style="margin-bottom:0;" href="'.site_url('Categories/ShowCategories/'.$row->category).'">'.trim($row->category).'</a></li>';
							}
						}
					}
				?>
            </ul>
          </li>
          
          <li><a href="<?php echo site_url('Comedianslist'); ?>">Comedian <span class="sr-only">(current)</span></a></li>
          <!--<li><a href="<?php# echo site_url('Blog'); ?>">Blog</a></li>-->
          <!--<li><a href="<?php# echo site_url('Events'); ?>">Events</a></li>-->
          <li><a href="<?php echo site_url('Subscribe'); ?>">Subscribe</a></li>
          
        </ul>
        
        <div align="center">
          	<a class="navbar-brand" href="">
            	
            </a>
            
            <!--Telco Logo-->
          
          
            <div class="navbar-brand navbar-brand-censtered img2">
                <a href="">
	                <img id="icons" src="<?php echo base_url();?>images/airtellogo.png" width="150px" class="img-responsive">
                    <img id="icons" src="<?php echo base_url();?>images/logo.png" width="150px"  class="img-responsive">
                </a>
            </div>
            
            
            

           
        </div>
               
         
        <ul class="nav navbar-nav navbar-right">
          <li class="search-box">
             <!--<form action="" autocomplete="on">
              <input id="search" name="search" type="text" placeholder="What're we looking for ?">
              <input id="search_submit" value="Rechercher" type="submit">
            </form>-->
          </li>
          
           <li class="dropdown dropdown2"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a style="margin-bottom:0;" href="<?php echo site_url('Subscriberhome'); ?>">Dashboard</a></li>
              <li title="Modify Profile"><a style="margin-bottom:0;" href="<?php echo site_url('Profile'); ?>">Profile</a></li>
              	<li title="Unsubscribe From LaffHub Service"><a style="margin-bottom:0;" href="<?php echo site_url('Unsubscribe'); ?>">Unsubscribe</a></li>
              	<li title="Sign Out From LaffHub"><a style="margin-bottom:0; cursor:pointer;" onClick="if (confirm('Do you want to log out? (Click OK to proceed or CANCEL to abort)')) window.location.href='<?php echo site_url('Subscriberlogout'); ?>';">Logout</a></li>
              	<li><a class="header__nav-link"><span style="color:#c2220c; font-weight:bold;">Phone: <?php echo $Phone; ?> </span></a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
    

   
   
  </nav>
  
  
  
  
  
 