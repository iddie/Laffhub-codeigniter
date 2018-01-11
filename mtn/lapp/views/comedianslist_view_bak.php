<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Comedians</title>
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style2.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<!--<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">-->


<link rel="stylesheet" href="<?php echo base_url();?>iconfont/material-icons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/raterater.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>

<script src="<?php echo base_url();?>js/holder.min.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script src="<?php echo base_url();?>js/bootbox.min.js"></script>

<style>
	.more-less {
        float: right;
        color: #ffffff;
    }
	
	.panel-default > .panel-heading + .panel-collapse > .panel-body {
        border-top-color: #EEEEEE;
    }
</style>

<script>
	function toggleIcon(e) 
	{
		$(e.target)
			.prev('.panel-heading')
			.find(".more-less")
			.toggleClass('glyphicon-plus glyphicon-minus');
	}
	
$(document).ready(function(e) {
	$('.panel-group').on('hidden.bs.collapse', toggleIcon);
	$('.panel-group').on('shown.bs.collapse', toggleIcon);    
});

</script>

</head>
<body>

<header> <?php include('usernav.php'); ?> </header>

<section class="channel-wrapper">
<div class="container">
	<div class="row" style="margin-top:-30px;">
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="title">
          <h3 style="color:#2C86B9;"> Comedians </h3>
        </div>
      </div>
    </div>

	<?php
		if (count($Comedians) > 0)
		{
			echo '
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			';
			
			$i=0;
			foreach($Comedians as $row):
				$row->comedian=trim($row->comedian);
				$row->pix=trim($row->pix);
				
				if ($row->comedian)
				{
					$i++;
					$pix=base_url().'images/nophoto.jpg';
					
					if ($row->pix) $pix=base_url().'comedian_pix/'.$row->pix;
					
					$cm=$row->comedian;
					
					$id='collapse'.$i;
					
					if ($i==1)
					{
						$plus='<i class="more-less glyphicon glyphicon-minus"></i>';
						$expand='aria-expanded="true"';
						$in='in';
					}else
					{
						$plus='<i class="more-less glyphicon glyphicon-plus"></i>';
						$expand='aria-expanded="false"';
						$in='';
					}
					
					echo '
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h4 class="panel-title" title="To view '.$cm.'\'s details and link to videos click here." >
									<a data-toggle="collapse" data-parent="#accordion" '.$expand.' href="#'.$id.'">
										'.$plus.'
										'.$cm.'
									</a>
								</h4>
							</div>
							<div id="'.$id.'" class="panel-collapse collapse '.$in.'">
								<div class="panel-body">
									<div class="user col-lg-1  col-md-1 col-sm-1 col-xs-12">
										<a title="Click to view '.$cm.'\'s videos" href="'.site_url('Comedian/ShowComedian/'.$row->id).'"><img  width="100px" style="border:thin solid #555555; padding:5px; margin-top:0px;" src="'.$pix.'" class="img-responsive" ></a>
									</div>
									
									<div class="col-lg-11 col-md-11 col-sm-11 col-xs-12">	
										'.$row->details.' <a title="Click to view '.$cm.'\'s videos" style="color:#B51B2E;" target="_self" href="'.site_url('Comedian/ShowComedian/'.$row->id).'" >View '.$cm.'\'s videos</a>
									 </div>
								</div>
							</div>
						</div>
					';
				}
			endforeach;
			
			echo '
					</div>
				</div>
			</div>
			';
		}
	?>
       
</section>


<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
</body>
</html>