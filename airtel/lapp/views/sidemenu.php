<?php $CurrentPage=uri_string(); ?>
 
 <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
              <div class="pull-left label" style="font-size:14px;">	  	
                <span class="label label-danger left size-14"><a id="ancMenuSignOut" href="#" style="color:#F2DEDE;">&nbsp;&nbsp;&nbsp&nbsp;Sign Out&nbsp;&nbsp;&nbsp&nbsp;</a></span>
              </div>
           
           <div class="pull-left label yellowtext" style="font-size:14px; margin-top:10px;" title="Username">	  	
            <span class="pull-left">
                Username:&nbsp;
            </span>&nbsp;&nbsp;
            <span class="label " style="color:#F2DEDE;"><?php echo $username; ?></span>
          </div>
           	             
             <div class="pull-left label" style="font-size:14px; margin-top:5px;">	
              	<a href="<?php echo site_url("Userhome"); ?>">
                   <?php
                   		if ($CurrentPage=='Userhome') 
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
					if (($CurrentPage=='Editprofile') || ($CurrentPage=='Editpassword'))
					{
						echo '<li class="treeview active">';
					}else
					{
						echo '<li class="treeview">';
					}
				?>
                
            	<a href="#" title="Click to expand activities">
                    <i class="fa fa-edit"></i> <span> Edit User Information</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                
                 <ul class="treeview-menu">
                	 <?php
					 	if ($CurrentPage=='Editprofile') 
						{
							echo '<li class="active"><a href="'.site_url("Editprofile").'"><i class="fa fa-dot-circle-o"></i> <span>Edit Profile</span></a></li>';
						}else
						{
							echo '<li><a href="'.site_url("Editprofile").'"><i class="fa fa-dot-circle-o"></i> <span>Edit Profile</span></a></li>';
						}	
						
						if ($CurrentPage=='Editpassword') 
						{
							echo ' <li id="liPassword" class="active"><a id="ancPassword" href="'.site_url("Editpassword").'"><i class="fa fa-dot-circle-o"></i> <span>Change Password</span></a></li>';
						}else
						{
							echo ' <li><a href="'.site_url("Editpassword").'"><i class="fa fa-dot-circle-o"></i> <span>Change Password</span></a></li>';
						}			   
				   ?>     
                  </ul>
            </li>
                
            	
            <li class="header">PORTAL ADMIN</li>
            
             <?php
				if (($CurrentPage=='Activatevideo') or ($CurrentPage=='Publishers') or ($CurrentPage=='Previewvideo')  or ($CurrentPage=='Createthumbnails'))
				{
					echo '<li class="treeview active">';
				}else
				{
					echo '<li class="treeview">';
				}
				
				echo '<a href="#" title="Click to expand activities">
                    <i class="fa fa-shopping-bag"></i> <span> Contents</span> <i class="fa fa-angle-left pull-right"></i>
                </a>';
				
				echo '<ul class="treeview-menu">';
				
				#Preview Videos
				if ($CurrentPage=='Previewvideo') 
				{
					echo ' <li title="Preview Videos" class="active"><a href="'.site_url("Previewvideo").'"><i class="fa fa-dot-circle-o"></i> <span>Preview Videos</span></a></li>';
				}else
				{
					echo ' <li title="Preview Videos"><a href="'.site_url("Previewvideo").'"><i class="fa fa-dot-circle-o"></i> <span>Preview Videos</span></a></li>';
				}
												
				if ($ApproveVideo==1)
				{
					if ($CurrentPage=='Activatevideo') 
					{
						echo ' <li class="active"><a href="'.site_url("Activatevideo").'"><i class="fa fa-dot-circle-o"></i> <span>Activate Video</span></a></li>';
					}else
					{
						echo ' <li><a href="'.site_url("Activatevideo").'"><i class="fa fa-dot-circle-o"></i> <span>Activate Video</span></a></li>';
					}	
				}
				
				#Create Thumbnails
				if ($CurrentPage=='Createthumbnails') 
				{
					echo ' <li class="active"><a href="'.site_url("Createthumbnails").'"><i class="fa fa-dot-circle-o"></i> <span>Create Thumbnails</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Createthumbnails").'"><i class="fa fa-dot-circle-o"></i> <span>Create Thumbnails</span></a></li>';
				}	
				
									
				#Publishers
				if ($CreatePublisher==1)
				{
					if ($CurrentPage=='Publishers') 
					{
						echo ' <li class="active" title="Modify Publisher Account"><a href="'.site_url("Publishers").'"><i class="fa fa-dot-circle-o"></i> <span>Modify Publisher Account</span></a></li>';
					}else
					{
						echo ' <li title="Modify Publisher Account"><a href="'.site_url("Publishers").'"><i class="fa fa-dot-circle-o"></i> <span>Modify Publisher Account</span></a></li>';
					}	
				}					  	
              echo '    
			  			</ul>
                     </li>';
				
		   ?>
          
          <?php 
           #Capture Events
            if ($CreateEvents==1)
            {
                if ($CurrentPage=='Captureevents') 
                {
                    echo ' <li title="Capture Events" class="active"><a href="'.site_url("Captureevents").'"><i class="fa fa-birthday-cake"></i> <span>Events</span></a></li>';
                }else
                {
                    echo ' <li title="Capture Events"><a href="'.site_url("Captureevents").'"><i class="fa fa-birthday-cake"></i> <span>Events</span></a></li>';
                }	
            }
           ?>
           
           <?php
           ####################### VIDEO SETTINGS     ################################		
				#Video Setting
				if (($CurrentPage=='Videocat') or ($CurrentPage=='Distribution') or ($CurrentPage=='Comedians'))
				{
					echo '<li class="treeview active">';
				}else
				{
					echo '<li class="treeview">';
				}
				
				echo '<a href="#" title="Click to expand activities">
                    <i class="fa fa-video-camera"></i> <span> Video Settings</span> <i class="fa fa-angle-left pull-right"></i>
                </a>';
				
				echo '<ul class="treeview-menu">';
				
				#Video Categoried
				if ($CurrentPage=='Videocat') 
				{
					echo ' <li class="active"><a href="'.site_url("Videocat").'"><i class="fa fa-dot-circle-o"></i> <span>Video Categories</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Videocat").'"><i class="fa fa-dot-circle-o"></i> <span>Video Categories</span></a></li>';
				}
				
				#Video Distribution
				if ($Upload_Video==1)	   
				{			
					if ($CurrentPage=='Distribution') 
					{
						echo ' <li class="active"><a href="'.site_url("Distribution").'"><i class="fa fa-dot-circle-o"></i> <span>Set Video Distribution</span></a></li>';
					}else
					{
						echo ' <li><a href="'.site_url("Distribution").'"><i class="fa fa-dot-circle-o"></i> <span>Set Video Distribution</span></a></li>';
					}
				}
				
				#Comedians
				if ($CurrentPage=='Comedians') 
				{
					echo ' <li class="active"><a href="'.site_url("Comedians").'"><i class="fa fa-dot-circle-o"></i> <span>Comedians</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Comedians").'"><i class="fa fa-dot-circle-o"></i> <span>Comedians</span></a></li>';
				}	   
				 
              echo '    </ul>
                     </li>';
           ####################### END VIDEO SETTINGS ################################
			?>
            
            
            
            <?php
			#prices,messages, airtel settings
           ####################### NETWORK SETTINGS     ################################		
				#Video Setting
				if (($CurrentPage=='Prices') or ($CurrentPage=='Messages') or ($CurrentPage=='Airtelsettings') or ($CurrentPage=='Serviceplans'))
				{
					echo '<li class="treeview active">';
				}else
				{
					echo '<li class="treeview">';
				}
				
				echo '<a href="#" title="Click to expand activities">
                    <i class="glyphicon glyphicon-signal"></i> <span> Network Settings</span> <i class="fa fa-angle-left pull-right"></i>
                </a>';
				
				echo '<ul class="treeview-menu">';
				
				#Serviceplans
				if ($CurrentPage=='Serviceplans') 
				{
					echo ' <li class="active"><a href="'.site_url("Serviceplans").'"><i class="fa fa-dot-circle-o"></i> <span>Service Plans</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Serviceplans").'"><i class="fa fa-dot-circle-o"></i> <span>Service Plans</span></a></li>';
				}
				
				#Prices
				if ($CurrentPage=='Prices') 
				{
					echo ' <li class="active"><a href="'.site_url("Prices").'"><i class="fa fa-dot-circle-o"></i> <span>Prices</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Prices").'"><i class="fa fa-dot-circle-o"></i> <span>Prices</span></a></li>';
				}
				
				#Messages
				if ($CurrentPage=='Messages') 
				{
					echo ' <li class="active"><a href="'.site_url("Messages").'"><i class="fa fa-dot-circle-o"></i> <span>Subscriber Messages</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Messages").'"><i class="fa fa-dot-circle-o"></i> <span>Subscriber Messages</span></a></li>';
				}
				
				#Airtel Settings
				if ($CurrentPage=='Airtelsettings') 
				{
					echo ' <li class="active"><a href="'.site_url("Airtelsettings").'"><i class="fa fa-dot-circle-o"></i> <span>Airtel Settings</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Airtelsettings").'"><i class="fa fa-dot-circle-o"></i> <span>Airtel Settings</span></a></li>';
				}	   
				 
              echo '    </ul>
                     </li>';
           ####################### END NETWORK SETTINGS ################################
			?>
            
            <!--Payment Settings-->
             <?php
			#Paystack
           ####################### PAYMENT SETTINGS     ################################		
				#Video Setting
				if (($CurrentPage=='Paystack'))
				{
					echo '<li class="treeview active">';
				}else
				{
					echo '<li class="treeview">';
				}
				
				echo '<a href="#" title="Click to expand activities">
                    <i class="glyphicon glyphicon-ruble"></i> <span> Payment Settings</span> <i class="fa fa-angle-left pull-right"></i>
                </a>';
				
				echo '<ul class="treeview-menu">';
				
				#Paystack
				if ($CurrentPage=='Paystack') 
				{
					echo ' <li class="active"><a href="'.site_url("Paystack").'"><i class="fa fa-dot-circle-o"></i> <span>Paystack Settings</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Paystack").'"><i class="fa fa-dot-circle-o"></i> <span>Paystack Settings</span></a></li>';
				}   
				 
              echo '    </ul>
                     </li>';
           ####################### END NETWORK SETTINGS ################################
			?>
            
            <!--System Settings-->
            <?php				
				#System Setting
				if (($CurrentPage=='Settings') or ($CurrentPage=='Users'))
				{
					echo '<li class="treeview active">';
				}else
				{
					echo '<li class="treeview">';
				}
				
				echo '<a href="#" title="Click to expand activities">
                    <i class="fa fa-gear"></i> <span> System Settings</span> <i class="fa fa-angle-left pull-right"></i>
                </a>';
				
				echo '<ul class="treeview-menu">';
				
				#Settings
				if ($SetParameters==1)	   
				{			
					if ($CurrentPage=='Settings') 
					{
						echo ' <li class="active"><a href="'.site_url("Settings").'"><i class="fa fa-dot-circle-o"></i> <span>Portal Settings</span></a></li>';
					}else
					{
						echo ' <li><a href="'.site_url("Settings").'"><i class="fa fa-dot-circle-o"></i> <span>Portal Settings</span></a></li>';
					}
				}
						
				#Users
				if ($CreateUser==1)	   
				{			
					if ($CurrentPage=='Users') 
					{
						echo ' <li class="active"><a href="'.site_url("Users").'"><i class="fa fa-dot-circle-o"></i> <span>User Account</span></a></li>';
					}else
					{
						echo ' <li><a href="'.site_url("Users").'"><i class="fa fa-dot-circle-o"></i> <span>User Account</span></a></li>';
					}
				}			   
				 
              echo '    </ul>
                     </li>';
					 
					 
					 
			############## REPORTS        #######################################
			if (($CurrentPage=='Logreport'))
			{
				echo '<li class="treeview active">';
			}else
			{
				echo '<li class="treeview">';
			}
			
			echo '<a href="#" title="Click to expand activities">
				<i class="fa fa-pie-chart"></i> <span> Reports</span> <i class="fa fa-angle-left pull-right"></i>
			</a>';
			
			echo '<ul class="treeview-menu">';
										
			#Audit Trail
			if ($ViewLogReport==1)	   
			{			
				if ($CurrentPage=='Logreport') 
				{
					echo ' <li class="active"><a href="'.site_url("Logreport").'"><i class="fa fa-dot-circle-o"></i> <span>Audit Trail</span></a></li>';
				}else
				{
					echo ' <li><a href="'.site_url("Logreport").'"><i class="fa fa-dot-circle-o"></i> <span>Audit Trail</span></a></li>';
				}
			}   
			 
		  echo '    
					</ul>
				 </li>';
			############## END OF REPORTS #######################################
					 
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
				echo ' <li class="active"><a target="new" href="'.base_url().'doc/html/HealthyLiving.html"><i class="fa fa-dot-circle-o"></i> <span>HTML Help</span></a></li>';
			}else
			{
				echo ' <li><a target="new" href="'.base_url().'doc/html/HealthyLiving.html"><i class="fa fa-dot-circle-o"></i> <span>HTML Help</span></a></li>';
			}
			
			/*
			#Pdf Help
			if ($CurrentPage=='Pdfhelp') 
			{
				echo ' <li class="active"><a target="new" href="'.base_url().'doc/pdf/Healthy_Living_doc.pdf"><i class="fa fa-dot-circle-o"></i> <span>PDF Help</span></a></li>';
			}else
			{
				echo ' <li><a target="new" href="'.base_url().'doc/pdf/Healthy_Living_doc.pdf"><i class="fa fa-dot-circle-o"></i> <span>PDF Help</span></a></li>';
			}		*/	   
			 
		  echo '    </ul>
				 </li>';
		   ?>
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>