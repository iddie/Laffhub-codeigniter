
<script>
	$(document).ready(function(e) {
		$(function() {
			// clear out plugin default styling
			$.blockUI.defaults.css = {};
		});
		
		$(document).ajaxStop($.unblockUI);	
		
		bootstrap_login_alert = function() {}
		bootstrap_login_alert.warning = function(message) 
		{
		   $('#divLoginAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
		}
				
		$('#btnLogin').click(function(e) 
		{
			var Title='<font color="#AF4442">User Login Help</font>';
			var m='';
				
			try
			{
				if (!CheckLogin()) return false;
						
				//Send values here
				$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Signing In User. Please Wait...</p>',theme: true,baseZ: 2000});
								
				//Make Ajax Request
				var un=$('#txtLoginUsername').val();
										
				var mydata={username:un, pwd:sha512($('#txtLoginPwd').val())};
						
				$.ajax({
					url: "<?php echo site_url('Adminlogin/myLogin');?>",
					data: mydata,
					type: 'POST',
					dataType: 'text',
					complete: function(xhr, textStatus) {
						//$.unblockUI;
					},
					success: function(data,status,xhr) {	
						//$.unblockUI;
							
						if ($.trim(data.toUpperCase())=='OK')
						{							
							window.location.href='<?php echo site_url("Userhome");?>';
						}else
						{
							$.unblockUI;
							
							m=data;
												
							bootstrap_login_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } }
							});
						}					
					},
					error:  function(xhr,status,error) {
						$.unblockUI;
						m='Error '+ xhr.status + ' Occurred: ' + error;
				
						bootstrap_login_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } }
						});
					}
				});	
				
				$.unblockUI();
			}catch(e)
			{
				m='Login Button Click ERROR:\n'+e;
					
				bootstrap_login_alert.warning(m);
				bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
			}
		});
		
		
    }); //Document Ready End
	
	function CheckLogin()
	{
			var Title='<font color="#AF4442">User Login Help</font>';
			var m='';
			
			try
			{
				var un=$.trim($('#txtLoginUsername').val());
				var pwd=$('#txtLoginPwd').val();				
								
				//Email
				if (!un)
				{
					m='Username field must not be blank.';
					
					bootstrap_login_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtLoginUsername').focus(); return false;
				}
								
				//Password
				if (!$.trim(pwd))
				{
					m='Password field must not be blank.';
					
					bootstrap_login_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } }
					});
					
					$('#txtLoginPwd').focus(); return false;
				}
				
				return true;
			}catch(e)
			{
				$.unblockUI();
				m='Login Button Click ERROR:\n'+e;
				
				bootstrap_login_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } }
				});
				
				return false;
			}
		}
</script>