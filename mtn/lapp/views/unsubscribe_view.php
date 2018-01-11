<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub::Unsubscribe From Service</title>
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
	var SubscriberEmail="<?php echo $subscriber_email; ?>";
	var SubscriptionDate="<?php echo $subscribe_date; ?>";
	var ExpiryDate="<?php echo $exp_date; ?>";
	var SubscriptionStatus='<?php echo $subscriptionstatus; ?>';
	var Network='<?php echo $Network; ?>';
	var Phone='<?php echo $Phone; ?>';
	var Email='<?php echo $subscriber_email; ?>';
	
	var Title='<font color="#AF4442">Unsubscription Help</font>';
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
				fadeIn : 500,
				fadeOut : 200,
				timeOut : 500,
				afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
				klass : 'mtn-custom-theme',
				bgPath : '<?php echo base_url();?>images/',
				content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Page. Please Wait...</b></p></center>'
			}
		);
		
		function Unsubscribe(input)
		{
			if (input === true)
			{
				$.msg(
					{
						autoUnblock : false ,
						clickUnblock : false,
						afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
						klass : 'mtn-custom-theme',
						bgPath : '<?php echo base_url();?>images/',
						content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Unsubscribing User. Please Wait...</b></p></center>'
					}
				);
				
				//Make Ajax Request
				var nt=$('#lblNetwork').html();
				var ph=$('#lblPhone').html();
				var pl=$('#lblPlan').html();
				var sid=$('#lblSubscriptionId').html();
				
				//Initiate POST
				var uri = "<?php echo site_url('Unsubscribe/UnsubscribeUser');?>";
				var xhr = new XMLHttpRequest();
				var fd = new FormData();
				
				xhr.open("POST", uri, true);
				
				xhr.onreadystatechange = function() {
					//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
					if (xhr.readyState == 4 && xhr.status == 200)
					{
						// Handle response.
						$.msg('unblock');
						
						var res=$.trim(xhr.responseText).toUpperCase();
													
						if (res=='OK')
						{
							m='You Have Successfully Unsubscribed From LaffHub <b>'+pl.toUpperCase()+' Plan</b>.';
																
							
							alert(m, 'LaffHub Message');
							bootstrap_Success_alert.warning(m);
							setTimeout(function() {
								window.location.href='<?php echo site_url("Subscriberhome"); ?>';
							}, 5000);
						}else
						{
							m=res;
							alert(m, 'LaffHub Message');
							bootstrap_alert.warning(m);
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					}
				};
			
				
				fd.append('network',nt);					
				fd.append('msisdn', ph);
				fd.append('email', SubscriberEmail);
				fd.append('subscriptionId', sid);
				fd.append('plan',pl);
				
				xhr.send(fd);// Initiate a multipart/form-data upload	
			}else
			{
				$.msg('unblock');
				m='Unsubscription Cancelled';
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			}			
		}
		
		$('#btnUnsubscribe').click(function(e) {
			try
			{
				checkForm();
			}catch(e)
			{
				$.msg('unblock');
				var m='Unsubscribe Button Click ERROR:\n'+e;
			   
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);				
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
							
				return false;
			}
		});//btnUnsubscribe.click
		
		function checkForm()
		{
			try
			 {
				var nt=$('#lblNetwork').html();
				var ph=$('#lblPhone').html();
				var sta=$('#hidStatus').val();
				var pl=$('#lblPlan').html();
				
				var edt=$('#lblExpiryDate').html();
				var exdt;
								
				if (edt)
				{
					var today=moment().format('DD MMM YYYY @ HH:mm:ss');
					exdt=moment(today).isSameOrAfter(edt);
				}				
				 
				//Network
				if (!nt)
				{
					m='Network has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@efluxz.com">support@efluxz.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}
				
				//Phone
				if ((!ph) && (!SubscriberEmail))
				{
					m='Subscriber phone or email not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@efluxz.com">support@efluxz.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}
				
				//Plan				
				if (!pl)
				{
					m='No service plane has been displayed. Please make sure you have active internet connection. If you are sure you had subscribed to LaffHub service successfully, you may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@efluxz.com">support@efluxz.com</a>.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}				
				
				//Status
				if (parseInt(sta,10) != 1)
				{
					m='Your attempt to unsubscribe from Laffhub failed. You have no active subscription on Laffhub service.<br>Text <b>YES to 2001</b> to activate 7days/15 videos. Service costs N100. NO DATA COST.';
					alert(m, 'LaffHub Message');
					bootstrap_alert.warning(m);
					setTimeout(function() {
						$('#divAlert').fadeOut('fast');
					}, 10000);
					
					return false;
				}
									
				m='Are you sure you want to unsubscribe from LaffHub '+pl.toUpperCase()+' plan? (Click "Yes" to proceed or "No" to abort)?';
				
				confirm(m, 'LaffHub Message', Unsubscribe,null,{ok : 'Yes', cancel : 'No'});			
			 }catch(e)
			 {
				m='CHECK FORM ERROR:\n'+e; 
				alert(m, 'LaffHub Message');
				bootstrap_alert.warning(m);
				setTimeout(function() {
					$('#divAlert').fadeOut('fast');
				}, 10000);
			
				return false;
			 }
		 }//End CheckForm		 
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
                        <span class="size-18 makebold"><i class="glyphicon glyphicon-remove-circle"></i> Unsubscribe From Service </span>
                      </div>
                      
                      <div class="panel-body">                                                             
                            <form class="form-horizontal"> 
                                <!--Network/Phone Number-->
                                <div class="form-group">
                                  <!--Network-->
                                  <label for="lblNetwork" class="col-sm-2 control-label " title="<?php echo $Network; ?>">Network</label>
                
                                  <div class="col-sm-3" title="<?php echo $Network; ?>">
                                     <label style="text-transform:none; color:#E5B400;" class="form-control" id="lblNetwork"><?php echo $Network; ?></label>
                                  </div>
                                  
                                  <!--Phone Number-->
                                  <label for="lblPhone" class="col-sm-3 control-label" title="Subscriber Phone Number">Phone No</label>
                
                                  <div class="col-sm-3" title="Subscriber Phone Number" > 
                                     <label id="lblPhone" class="form-control nobold" title="Phone Number"><?php echo $Phone; ?></label>
                                  </div>
                                </div>
                                                
                                                
                                <!--Service Plan Duration/Service Plan-->
                                <div class="form-group">
                                    <!--Service Plan-->
                                  <label for="lblPlan" class="col-sm-2 control-label" title="Service Plan">Service Plan</label>
                
                                  <div class="col-sm-3" title="Service Plan" > 
                                     <label class="form-control nobold" id="lblPlan"><?php echo $plan; ?></label>
                                  </div>
                                
                                    <!--Service Plan Duration-->
                                  <label for="lblDuration" class="col-sm-3 control-label" title="Service Plan Duration">Service Plan Duration(Days)</label>
                
                                  <div class="col-sm-3" title="Service Plan Duration"> 
                                     <label class="form-control nobold" id="lblDuration"><?php echo $duration; ?></label>
                                  </div>                                      
                                </div>
                                                
                                               
                                <!--No Of Videos To Watch/ No of Videos Watched-->
                                <div class="form-group">
                                <!--No Of Videos-->
                              <label for="lblVideoCount" class="col-sm-2 control-label" title="No Of Videos To Watch">Videos To Watch</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblVideoCount" title="No Of Videos To Watch"><?php echo $videos_cnt_to_watch; ?></label>
                              </div>
                            
                                <!--No Of Videos Watched-->
                              <label for="lblWatched" class="col-sm-3 control-label" title="No Of Videos Watched">No Of Videos Watched</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblWatched" title="No Of Videos Watched"><?php echo $Watched; ?></label>
                              </div>
                            </div>
                                            
                            <!--Subscription Date/Expiry Date-->
                            <div class="form-group">
                                <!--Subscription Date-->
                              <label for="lblSubscriptionDate" class="col-sm-2 control-label" title="Subscription Date">Subscription Date</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblSubscriptionDate" title="Subscription Date"><?php echo $SubscriptionDate; ?></label>
                              </div>
                            
                                <!--Expiry Date-->
                              <label for="lblExpiryDate" class="col-sm-3 control-label" title="Subscription Expiry Date">Subscription Expiry Date</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblExpiryDate" title="Subscription Expiry Date"><?php echo $ExpiryDate; ?></label>
                              </div>
                            </div>
                            
                            <!--Subcription Amount/Subscription Status-->
                            <div class="form-group">
                              <!--Amount-->
                              <label for="lblAmount" class="col-sm-2 control-label" title="Subscription Amount">Amount Charged (&#8358;)</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblAmount" title="Subscription Amount"><?php echo $amount; ?></label>
                              </div>
                              
                              <!--Subscription Status-->
                              <label for="lblStatus" class="col-sm-3 control-label" title="Subscription Status">Subscription Status</label>
            
                              <div class="col-sm-3"> 
                                 <label class="form-control nobold" id="lblStatus" title="Subscription Status"><?php echo $subscriptionstatus; ?></label>
                                 
                                 <input type="hidden" id="hidStatus" value="<?php echo $status; ?>">
                            </div>
                           </div>
                           
                           <!--Subscription ID-->
                            <div class="form-group">
                               <label for="lblSubscriptionId" class="col-sm-2 control-label" title="Subscription ID">Subscription ID</label>
                
                                  <div class="col-sm-3" title="Subscription ID" > 
                                     <label style="background-color:#C5522D; color:#ffffff;" id="lblSubscriptionId" class="form-control nobold"><?php echo $subscriptionId; ?></label>
                                  </div>                                  
                            </div>                             
                            
                            <center>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-7" style="margin-top:30px;">
                                    <button title="Unsubscription User" id="btnUnsubscribe" type="button" class="btn btn-primary" role="button" style="text-align:center; width:120px;"><i class="glyphicon glyphicon-remove-sign"></i> Unsubscribe</button>
                                                                
                                    <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="width:120px;  margin-left:10px;" ><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
                            </div>
                            </center>
                                
                        </form>                
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