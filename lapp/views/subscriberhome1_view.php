<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<!--<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">-->


<link rel="stylesheet" href="<?php echo base_url();?>iconfont/material-icons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>

<script src="<?php echo base_url();?>js/holder.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>


<script>
	var Network='<?php echo $Network;?>';
	var Phone='<?php echo $Phone; ?>';
	
	var Title='<font color="#AF4442">LaffHub Help</font>';
	var m='';
	
		
	$(document).ready(function(e) {
        $(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);
    });
	
	function SelectCategory(sn,category)
	{
		try
		{
			/*if (!category) return false;
			
			$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:14px;"><b>Loading '+category.toUpperCase()+' Videos. Please Wait...</b></p>',theme: true,baseZ: 2000});
			
			var url='<?php# echo site_url('Subscriberhome');?>/index/'+category;
			
			window.location.href=url;*/
			
		}catch(e)
		{
			$.unblockUI();
			m='SelectCategory Click ERROR:\n'+e;
			
			alert(m);
		}
	}
</script>
</head>
<body>

<header>
	<?php include('usernav.php'); ?>
  
  <section class="slider-div" style="margin-top:60px;">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      </ol>
      
      <!-- Wrapper for slides -->
      <div class="carousel-inner" role="listbox">
        <div class="item active"> <img src="<?php echo base_url(); ?>images/banner22.jpg" alt="..."> </div>
        <div class="item"> <img src="<?php echo base_url(); ?>images/banner222.jpg" alt="..."> </div>
        <div class="item"> <img src="<?php echo base_url(); ?>images/banner2222.jpg" alt="..."> </div>
        <div class="carousel-caption"> </div>
      </div>
      <!-- Controls --> 
      <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next"> <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
  </section>
</header>
<div class="container">
<div class="wrapper2">
<div class="row">
<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">

  <?php
  	if (count($PopularMovies) > 0)
	{
		echo '
		<section class="content">
				<div class="row">
				  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="title">
					  <h2> Popular Videos </h2>
					</div>
				  </div>
				</div>
		';
		
				
		$i=0;

		echo '<div class="row">';
		
		foreach($PopularMovies as $row):
			if (trim($row->thumbnail))
			{
				$row->video_title=trim(ucwords(strtolower($row->video_title)));
				$row->category=trim(ucwords(strtolower($row->category)));
				$videourl='c-'.$row->video_code;
				
				$i++;
				
				if ($i<5)
				{
					
					
					echo '
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<div class="list-div">
								<a href="'.site_url($videourl).'"><img title="'.$row->video_title.'" style="height:150px;" src="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'" class="img-responsive"></a>
						  		<div class="list-div-text">
									<a href=""><h4 title="'.$row->video_title.'" style="height:40px; text-transform:capitalize; margin-top:-30px;"> '.$row->video_title.' </h4></a>
									<h5 title="'.$row->category.'" style="text-transform:capitalize;margin-top:-40px;"> <span class="redtext">Category:</span> '.$row->category.' </h5>
						  		</div>
							</div>
					  	</div>
					';	
				}else
				{
					if ($i==5)
					{
						echo '
							</div">
				</section>
				
				<section class="content">
						<div class="row">';
					}
					
					echo '
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<div class="list-div">
								<a href="'.site_url($videourl).'"><img title="'.$row->video_title.'" style="min-height:150px;" src="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'" class="img-responsive"></a>
						  		<div class="list-div-text">
									<a href=""><h4 title="'.$row->video_title.'" style="height:40px; text-transform:capitalize;margin-top:-30px;"> '.$row->video_title.' </h4></a>
									<h5 title="'.$row->category.'" style="text-transform:capitalize;margin-top:-40px;"> <span class="redtext">Category:</span> '.$row->category.' </h5>
						  		</div>
							</div>
					  	</div>
					';	
				}
			}
		endforeach;
		
		echo '</div">
			</section>
		</div>';
	}
  ?>
  
 <!-- <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
    <div class="list-div"> <img src="images/l2.jpg" class="img-responsive">
      <div class="list-div-text">
        <h4> MS Dhoni : The Untold Story </h4>
        <h5> Drama , Hindi , 2016 </h5>
      </div>
    </div>
  </div>-->


<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<?php
	if (count($PopularMovies) > 0)
	{
		echo '<section class="content content2">
				<div class="row">
				  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="title">
					  <h2> Popular Videos</h2>
					</div>
				  </div>
				</div>
			';
		
		$i=0;
		
		foreach($PopularMovies as $row):
			if (trim($row->thumbnail))
			{
				$row->video_title=trim(ucwords(strtolower($row->video_title)));
				$row->category=trim(ucwords(strtolower($row->category)));
				$videourl='c-'.$row->video_code;
				
				echo '
				<a href="'.site_url($videourl).'"><div class="row">
				  
					  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="list-div2">
							<img title="'.$row->video_title.'" style="min-height:60px; margin-bottom:20px;margin-top:-30px;"  src="https://s3-us-west-2.amazonaws.com/'.$thumbs_bucket.'/'.$row->category.'/'.trim($row->thumbnail).'" class="img-responsive">
						</div>
					  </div> 
					  
					  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="list-div-text2">
						  <h4 title="'.$row->video_title.'" style="height:50px; text-transform:capitalize;margin-top:-20px;"> '.$row->video_title.' </h4>
						 
						</div>
					  </div>
				 
				</div>	</a>	
				';# <h5> Category: '.$row->category.' </h5>
			}
		endforeach;
		
		echo '</section>';
	}
?>
    
</div>

</div>
</div>
</div>

<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.redirect.js"></script>

</body>
</html>