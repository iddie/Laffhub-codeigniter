<?php $CurrentPage=uri_string(); ?>
 
 <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
              <div class="pull-left label" style="font-size:14px;">	  	
                <span class="label label-danger left size-14"><a href="<?php echo site_url("Publogout"); ?>" style="color:#F2DEDE;">&nbsp;&nbsp;&nbsp&nbsp;Sign Out&nbsp;&nbsp;&nbsp&nbsp;</a></span>
              </div>
           
           <div class="pull-left label yellowtext" style="font-size:14px; margin-top:10px;" title="Username">	  	
            <span class="pull-left">
                Email:&nbsp;
            </span>&nbsp;&nbsp;
            <span class="label " style="color:#F2DEDE;"><?php echo $publisher_email; ?></span>
          </div>
           	             
             <div class="pull-left label" style="font-size:14px; margin-top:5px;">	
              	<a href="<?php echo site_url("Dashboard"); ?>">
                   <?php
                   		if ($CurrentPage=='Dashboard') 
						{
							echo '<i class="fa fa-dashboard active" style="color:#fff; margin-top:10px;"></i> <span style="color:#fff;">Dashboard</span>';
						}else
						{
							echo '<i class="fa fa-dashboard"></i> <span>Dashboard</span>';
						}				   
				   ?> 
                </a>
              </div>
          </div>
          
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">PROFILE UPDATE</li>
            <?php
				if ($CurrentPage=='Pubeditprofile') 
				{
					echo '<li class="active"><a href="'.site_url("Pubeditprofile").'"><i class="fa fa-edit"></i> <span>Edit Profile</span></a></li>';
				}else
				{
					echo '<li><a href="'.site_url("Pubeditprofile").'"><i class="fa fa-edit"></i> <span>Edit Profile</span></a></li>';
				}	
				
				/*
				if ($CurrentPage=='Pubeditpassword') 
				{
					echo ' <li id="liPassword" class="active"><a id="ancPassword" href="'.site_url("Pubeditpassword").'"><i class="fa fa-key"></i> <span>Modify Password</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Pubeditpassword").'"><i class="fa fa-key"></i> <span>Modify Password</span></a></li>';
				}
				*/			   
		   ?> 
                
            	
            <li class="header">CONTENTS</li>
            
             <?php
				#Upload Videos
				if ($CurrentPage=='Uploadvideos') 
				{
					echo ' <li title="Upload Videos" class="active"><a href="'.site_url("Uploadvideos").'"><i class="fa fa-cloud-upload"></i> <span>Upload Videos</span></a></li>';
				}else
				{
					echo ' <li title="Upload Videos"><a href="'.site_url("Uploadvideos").'"><i class="fa fa-cloud-upload"></i> <span>Upload Videos</span></a></li>';
				}
				
										
				#View Videos
				if ($CurrentPage=='Viewvideos') 
				{
					echo ' <li class="active" title="View Videos"><a href="'.site_url("Viewvideos").'"><i class="fa fa-video-camera"></i> <span>View Videos</span></a></li>';
				}else
				{
					echo ' <li title="View Videos"><a href="'.site_url("Viewvideos").'"><i class="fa fa-video-camera"></i> <span>View Videos</span></a></li>';
				}				
		   ?>
           
          <li class="header">DOCUMENTATION</li> 
                       
            <?php									 
			######################################################################
			if (($CurrentPage=='Htmlhelp') || ($CurrentPage=='Pdfhelp'))
			{
				echo '<li class="treeview active">';
			}else
			{
				echo '<li class="treeview">';
			}
				
			echo '<a href="#" title="Click to expand activities">
				<i class="fa fa-question-circle"></i> <span> Help</span> <i class="fa fa-angle-left pull-right"></i>
			</a>';
			
			echo '<ul class="treeview-menu">';
			
			#Html Help
			if ($CurrentPage=='Htmlhelp') 
			{
				echo ' <li class="active"><a target="new" href="#"><i class="fa fa-dot-circle-o"></i> <span>HTML Help</span></a></li>';
			}else
			{
				echo ' <li><a target="new" href="#"><i class="fa fa-dot-circle-o"></i> <span>HTML Help</span></a></li>';
			}
			
			#Pdf Help
			if ($CurrentPage=='Pdfhelp') 
			{
				echo ' <li class="active"><a target="new" href=""><i class="fa fa-dot-circle-o"></i> <span>PDF Help</span></a></li>';
			}else
			{
				echo ' <li><a target="new" href=""><i class="fa fa-dot-circle-o"></i> <span>PDF Help</span></a></li>';
			}			   
			 
		  echo '    </ul>
				 </li>';
		   ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>