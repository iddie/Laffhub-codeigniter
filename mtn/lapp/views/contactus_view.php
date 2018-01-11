<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>LaffHub::Contact Us</title>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--FAVICON-->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>acss/favicons/icon.png">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="32x32">
  <link rel="icon" type="image/png" href="<?php echo base_url(); ?>acss/favicons/icon.png" sizes="16x16">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  <link rel="manifest" href="<?php echo base_url(); ?>acss/favicons/manifest.json">
  
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>acss/contact-us_assets/css/contact-us.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css" type="text/css"/>
  <link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">
  
  
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/general.js"></script>
<script src="<?php echo base_url();?>js/modernAlert.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>

<?php include('googleanalytics.php'); ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->

<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<script>
(function($){
	
var Title='<font color="#AF4442">LaffHub Help</font>';
var m='';
var self;


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
			defaultButtonsText: {ok : 'OK', cancel : 'Cancel'},
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
			content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Contact Page. Please Wait...</b></p></center>'
		}
	);
	
	LoadCaptcha();
	
	$('#ancRefresh').click(function(e) {
		try
		{
			LoadCaptcha();
		}catch(e)
		{
			
		}
	});
	
	$('#btnSubmit').click(function(e) {
		try
		{
			CheckForm();
		}catch(e)
		{
			m='Submit Contact Button Click ERROR:\n'+e;
			
			HideShowMsg(m,'Show','alert');	
			alert(m,'LaffHub Message');	
			setTimeout(function() {
				$('#divAlert').fadeOut('slow');
			}, 10000);
		}
	});
	
	function CheckForm()
	{
		 try
		 {
			var nm=$.trim($('#txtName').val());
			var em=$.trim($('#txtEmail').val());
			var sb=$.trim($('#txtSubject').val());
			var msg=$.trim($('#txtMessage').val());
			var cap=$.trim($('#txtCaptcha').val()).replace(new RegExp(',', 'g'), '');
			var ans=$.trim($('#ans').val());
			
			//Name
			if (!nm)
			{
				m='Name field must not be blank.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus(); return false;
			}
			
			if ($.isNumeric(nm))
			{
				m='Name must NOT be a number.';
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus(); return false;
			}
			
			if (nm.length<3)
			{
				m='Name must be in full.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtName').focus(); return false;
			}
			
			//Email
			if (!em)
			{
				m='Email field must not be blank.';
				
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtEmail').focus(); return false;
			}
		
			//Valid Email?
			//  /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
			var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
			if(!rx.test(em))
			{
				m='Invalid email address.';   
				
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtEmail').focus(); return false;
			}
			
			//Subject
			if (!sb)
			{
				m='Subject field must not be blank.';
								
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtSubject').focus(); return false;
			}
			
			if ($.isNumeric(sb))
			{
				m='Subject field must NOT be a number.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtSubject').focus(); return false;
			}
			
			if (sb.length < 3)
			{
				m='Subject must be in full.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtSubject').focus(); return false;
			}
			
			//Message
			if (!msg)
			{
				m='Message field must not be blank.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtMessage').focus(); return false;
			}
			
			if ($.isNumeric(msg))
			{
				m='Message field must NOT be a number.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtMessage').focus(); return false;
			}
			
			if (msg.length < 3)
			{
				m='Message must be meaningful.';
				alert(m,'LaffHub Help');
				HideShowMsg(m,'Show','alert');	
				
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtMessage').focus(); return false;
			}
			
			//Captcha
			if (!cap)
			{
				m='Answer field must not be blank.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtCaptcha').focus(); return false;
			}
			
			if (!$.isNumeric(cap))
			{
				m='Answer field must be a number.';
				
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtCaptcha').focus(); return false;
			}
			
			if (parseInt(cap) != parseInt(ans))
			{
				m='Answer to mathematical question NOT corret.';
				HideShowMsg(m,'Show','alert');	
				alert(m,'LaffHub Help');
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
				
				$('#txtCaptcha').focus(); return false;
			}
			
			//Confirm
			
			m='Do you want to go ahead and submit the message? (Click <b>Yes</b> to proceed or <b>No</b> to abort)?';
				
			confirm(m, 'LaffHub Message', SendMessage,null,{ok : 'Yes', cancel : 'No'});		
		 }catch(e)
		 {
			$.msg('unblock');
			
			m='CHECK FORM ERROR:\n'+e; 
			
			HideShowMsg(m,'Show','alert');	
				
				setTimeout(function() {
					$('#divAlert').fadeOut('slow');
				}, 10000);
			
			return false;
		 }
	 }//End CheckForm
	 
	 
});

	function SendMessage(input)
	{
		var self;
		
		if (input === true)
		{
			//Subscribe
			$.msg(
				{
					autoUnblock : false ,
					clickUnblock : false,
					fadeIn : 500,
					fadeOut : 200,
					afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
					klass : 'mtn-custom-theme',
					bgPath : '<?php echo base_url();?>images/',
					content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Submitting Message. Please Wait...</b></p></center>'
				}
			);
						
			$('#divAlert').html('');
			
			//Make Ajax Request
			var nm=$.trim($('#txtName').val());
			var em=$.trim($('#txtEmail').val());
			var sb=$.trim($('#txtSubject').val());
			var msg=$.trim($('#txtMessage').val());
								
			var mydata={name:nm, email:em, message:msg, subject:sb};
																							
			$.ajax({
				url: "<?php echo site_url('Contactus/ProcessMessage');?>",
				data: mydata,
				type: 'POST',
				dataType: 'text',
				complete: function(xhr, textStatus) {
					
				},
				success: function(data,status,xhr) {	
					$.msg('unblock'); 
					
					var ret=$.trim(data);

					if (ret.toUpperCase() == 'OK')
					{
						m='Message Was Submitted successfully.';
						
						$('#txtName').val('');
						$('#txtEmail').val('');
						$('#txtSubject').val('');
						$('#txtMessage').val('');
						LoadCaptcha();
						
						HideShowMsg(m,'Show','success');
						alert(m,'LaffHub Help');
					
						setTimeout(function() {
							$('#divAlert').fadeOut('slow');							
						}, 10000);
					}else
					{
						m=data;
						
						HideShowMsg(m,'Show','alert');	
						alert(m,'LaffHub Help');
						
						setTimeout(function() {
							$('#divAlert').fadeOut('slow');
						}, 10000);
					}
		
				},
				error:  function(xhr,status,error) {
					$.msg('unblock');
					m='Error '+ xhr.status + ' Occurred: ' + error;
					
					HideShowMsg(m,'Show','alert');	
					alert(m,'LaffHub Help');
					setTimeout(function() {
						$('#divAlert').fadeOut('slow');
					}, 10000);
				}
			});
		} else
		{
			m='Message Submision Cancelled';
			
			HideShowMsg(m,'Show','alert');	
			alert(m,'LaffHub Help');
			setTimeout(function() {
				$('#divAlert').fadeOut('slow');
			}, 10000);
		}
	}
	
	function LoadCaptcha()
	{
		try
		{//"What is <?php# echo $x.' + '.$y; ?>? (Anti-spam)"
			$('#txtCaptcha').val('');
			
			$.ajax({
				url: "<?php echo site_url('Contactus/ComputeCaptcha');?>",
				type: 'POST',
				dataType: 'text',
				complete: function(xhr, textStatus) {
					
				},
				success: function(data,status,xhr) {												
					var v='';
					v=$.trim(data);
					var s=v.split('|');
					var x=s[0], y=s[1];
					var txt='What is ' + x + ' + ' + y + '? (Anti-spam)';
					
					$('#txtCaptcha').prop('placeholder',txt);
					$('#ans').val(parseInt(x)+parseInt(y));
					
				},
				error:  function(xhr,status,error) {						
					m='Error '+ xhr.status + ' Occurred: ' + error;
					HideShowMsg(m,'Show','alert');	
				
					setTimeout(function() {
						$('#divAlert').fadeOut('slow');
					}, 10000);
				}
			});
		}catch(e)
		{
			m='LoadCaptcha ERROR:\n'+e;
			
			HideShowMsg(m,'Show','alert');	
					
			setTimeout(function() {
				$('#divAlert').fadeOut('slow');
			}, 10000);
		}
	}
	
})(jQuery);
</script>

<div id='browser'>
  <div id='browser-bar'>
    <div class='circles'></div>
    <div class='circles'></div>
    <div class='circles'></div>
    <p>
      <a href="<?php echo site_url('Subscriberhome'); ?>">Home</a>
    </p>
    <span class='arrow entypo-resize-full'></span>
  </div>
  <div id='content'>
    <div id='left'>
      <div id='map'>
        <div class='map-locator'>
          <div class='tooltip'>
            <ul>
              <li>
                <span class='entypo-location'></span>
                <span class='selectedLocation'>Lagos</span>
              </li>
              <li>
                <span class='entypo-mail'></span>
                <a href='mailto:support@laffhub.com'>support@laffhub.com</a>
              </li>
              <li>
                <span class='entypo-phone'></span>
                +234-1-2919644
              </li>
            </ul>
          </div>
        </div>
        <div class='zoom'></div>
      </div>
    </div>
    <div id='right'>
      <p>Connect</p>
      <div id='social'>
        <a class='social' href="https://www.facebook.com/thelaffhub" target="_blank">
          <span class='entypo-facebook'></span>
        </a>
        
        <a class='social' href="https://twitter.com/laffhub" target="_blank">
          <span class='entypo-twitter'></span>
        </a>
        <a class='social' href="https://www.instagram.com/laffhub/" target="_blank">
          <span class='entypo-instagrem'></span>
        </a>
      </div>
      <form>
        <p>Get in Contact</p>
        <input id="txtName" placeholder='Name [Required]' type='text'>
        <input id="txtEmail" placeholder='Email [Required]' type='email'>
        <input id="txtSubject" placeholder='Subject [Required]' type='text'>
        <textarea id="txtMessage" placeholder='Message [Required]' rows='4'></textarea>
        
        <input id="txtCaptcha" style="width:72%; margin-top:8px;" type="text" placeholder=""> &nbsp;<a id="ancRefresh" style="cursor:pointer;"><i style="size-20" class="fa fa-refresh"></i> <b>Refresh</b></a>
       
        <input id="btnSubmit" placeholder='Send' type='button' value="Submit">
       <div align="center" style="margin-top:10px; padding-top:3px; padding-bottom:3px;" id="divAlert" class="fade in">
        </div>
        
        <input type="hidden" id="ans">
      </form>

      <p class='other entypo-mail'>
        <a href='mailto:support@laffhub.com'>support@laffhub.com</a>
      </p>
      <p class='other entypo-phone'>+234-1-2919644</p>
    </div>
  </div>

  <!--SCRIPTS MAIN-->
  <script
          src="<?php echo base_url(); ?>js/jquery-2.2.4.min.js"
          integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
          crossorigin="anonymous"></script>
  <script src="<?php echo base_url(); ?>acss/contact-us_assets/js/contact-us.js"></script>

  <!--/SCRIPTS MAIN-->
</div>