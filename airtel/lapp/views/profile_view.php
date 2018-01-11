<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Subscriber Profile</title>
<!--FAVICON-->
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
<link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
<link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
<link rel="mask-icon" href="<?php echo base_url(); ?>acss/favicons/safari-pinned-tab.svg" color="#ff0000">
<meta name="theme-color" content="#ffffff">
<!--/FAVICON-->

<link rel="stylesheet" href="<?php echo base_url(); ?>acss/css/main.css"><!--CSS MAIN-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>
<link href="<?php echo base_url();?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>css/font-awesome.min.css" rel="stylesheet">

<!--Datatable-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.dataTables.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/dataTables.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.dataTables.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fixedHeader.jqueryui.min.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/select.jqueryui.min.css">
<!--End Datatable-->

<link rel="stylesheet" href="<?php echo base_url();?>css/pikaday.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/date-theme.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/date-triangle.css">
<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url();?>js/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/modernAlert.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>




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
	var SubscriberEmail="<?php echo $subscriber_email; ?>";
	var SubscriptionDate="<?php echo $subscribe_date; ?>";
	var ExpiryDate="<?php echo $exp_date; ?>";
	var SubscriptionStatus='<?php echo $subscriptionstatus; ?>';
		
	var Title='<font color="#AF4442">Update Profile Help</font>';
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
					content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Page. Please Wait...</b></p></center>'
				}
			);
		
    });
	
})(jQuery);
</script>
</head>
<body class="page">

<div class="page__layout">
	<div class="overlay"></div>
    
    <?php include('newusernav.php'); ?>
    
    <div id="content-ajax">
    	<!--MAIN-->
        	<main class="page__main main">
            	<div class="col-md-12">
                    <br>
                        
                 	<div class="panel panel-info">
                      <!-- Default panel contents -->
                      <div class="panel-heading size-20">
                        <span class="size-22 makebold"><i class="fa fa-picture-o"></i> PROFILE </span>
                      </div>
                      
                       <div class="panel-body">
                       		<div class="row">
                            <form class="form-horizontal">
                                 <!--Left Column-->
                                <div class="col-md-6">
                                   <center><span class="size-18 makebold" style="color:#2C86B9;">Profile Details </span></center><br>
                                    
                                    <!--Subscription Date-->
                                    <div class="form-group"  title="Subscription Date">
                                        <label for="lblLastSubscriptionDate" class="col-sm-4 control-label">Subscription Date:</label>
                                        <div class="name-type col-sm-8">
                                        	 <label id="lblLastSubscriptionDate" class="form-control nobold"><?php echo $subscribe_date; ?></label> 
                                        </div>
                                    </div>
                                    
                                    
                                     <div class="form-group"  title="Subscription Expiry Date">
                                        <label for="lblExpiryDate" class="col-sm-4 control-label">Expiry Date:</label>                                        
                                        <div class="name-type col-sm-8">
                                        	<label id="lblExpiryDate" class="form-control nobold"><?php echo $exp_date; ?></label>
                                        </div>
                                    </div>
                                    
                                
                                   
                                
                                                                 
                    
                               		<div class="form-group">
                                        <!--<input type="submit" class="btn btn-info form-control" value="UPDATE PROFILE">-->
                                         <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                           
                                         </div>
                                    </div>
                                </div>
                                
                                <!--Right Column-->
                                <div class="col-md-6">
                                    <center><span class="size-18 makebold" style="color:#2C86B9;"> Subscription Information </span></center><br>
                                    
                                   	<div title="Subscription Status" class="form-group">
                                        <label class="place col-sm-6 control-label" for="lblStatus">Subscription Status:</label>
                                         <div class="col-sm-5">
                                            <label id="lblStatus" class="form-control nobold"><?php echo $subscriptionstatus; ?></label>
                                        </div>                                       
                                    </div>
                                    
                                     <div title="Subscription Plan" class="form-group">
                                        <label class="place col-sm-6 control-label" for="lblPlan">Subscription Plan:</label>
                                         <div class="col-sm-5">
                                            <label id="lblPlan" class="form-control nobold"><?php echo $subscriber_plan; ?></label>
                                        </div>                                       
                                    </div>
                                    
                                    <div title="" class="form-group">
                                       <label class="place col-sm-6 control-label">&nbsp;</label>
                                            
                                         <div class="col-sm-5">
                                              <button type="button" onClick="window.location.reload(true);" class="btn btn-warning form-control"><i class="glyphicon glyphicon-refresh"></i> REFRESH</button>
                                        </div>
                                    </div>
                                    
                                 </div>
                            </form>
                            </div>
                       </div>
                    </div>
                </div> 
            </main>
        <!--END MAIN-->
        
        <!--FOOTER-->
		<?php include('newuserfooter.php'); ?>
        <!--/FOOTER-->
    </div>
</div>


<script src="<?php echo base_url();?>js/moment.min.js"></script>
<script src="<?php echo base_url();?>js/pikaday.js"></script>
 
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
 <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!--Datatable-->
<script type='text/javascript' src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.select.min.js"></script> 
<script type='text/javascript' src="<?php echo base_url();?>js/dataTables.fixedColumns.min.js"></script>
<!--End Datatable-->

<!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>acss/js/main.js" async></script>    
<!--/SCRIPTS MAIN-->

</body>
</html>