<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
   <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Adverts</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <?php include('homelink.php'); ?>
    
    <script>
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
		
		var Title='<font color="#AF4442">Adverts Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,seldata;
		
		var logo_pix=null;
		var emptypix='<?php echo base_url();?>images/nophoto.jpg';
		
		function GetFile(input,SelectedFile)
		{
			try
			{
				var img;
				
				if ($.trim(SelectedFile)=='Picture')
				{
					logo_pix=null;
					
					if (input.files && input.files[0]) logo_pix=input.files[0];
					
					if (logo_pix != null)
					{
						//check whether browser fully supports all File API
						if (window.File && window.FileReader && window.FileList && window.Blob) 
						{
							var fr = new FileReader;
							fr.onload = function() { // file is loaded
								img = new Image;
								img.onload = function() 
								{ // image is loaded; sizes are available
									if (this.width < 370)
									{
										m="The advert picture width is 370 pixels. The height is 340 pixels.";
										bootstrap_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
										
										$('#txtPix').val(''); 
										$('#imgPix').prop('src',emptypix);
										
										return false;
									}
									
									if (this.height < 340)
									{
										m="The advert picture height is 340 pixels. The width is 370 pixels.";
										bootstrap_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
										
										$('#txtPix').val(''); 
										$('#imgPix').prop('src',emptypix);
										
										return false;
									}
								};
								
								img.src = fr.result; // is the data URL because called with readAsDataURL
							};
							
							fr.readAsDataURL(input.files[0]);
						}else
						{
							m="Please upgrade your browser, because your current browser lacks some new features we need!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							
							return false;
						}
						
						var s=logo_pix.name.split('.'); var ext=$.trim(s[s.length-1]);
						
						if (((ext.toLowerCase()!='jpg') && (ext.toLowerCase()!='jpeg')) )
						{
							m="Invalid Advert Picture File Format. Only JPEG Files Are Allowed.";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
							
							$('#txtPix').val(''); 
							$('#imgPix').prop('src',emptypix);
							return false;
						}
							
						var reader = new FileReader();
						 reader.onload = function(e){
						   $('#imgPix').attr('src', e.target.result);
						 }
						 
						 reader.readAsDataURL(input.files[0]);
					}
				}
			}catch(e)
			{
				m='GETFILE ERROR:\n'+e;
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
			}
		} //End GetFile
						
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
		
        	$(document).ajaxStop($.unblockUI);
			
			$('#txtStartDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 0,
				forceParse: 0,
				format: 'dd M yyyy'
			});
			
			$('#txtStartDate').change(function(e) {
				try
				{
					if ($('#txtStartDate').val() && $('#txtEndDate').val())
					{
						VerifyStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="Start Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } }
					});
				}
            });
			
			$('#txtEndDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 0,
				forceParse: 0,
				format: 'dd M yyyy'
			});
						
			$('#txtEndDate').change(function(e) 
			{
				try
				{
					if ($('#txtStartDate').val() && $('#txtEndDate').val())
					{
						VerifyStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="End Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } }
					});
				}
            });
			
			
			$('#txtFilterStartDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 0,
				forceParse: 0,
				format: 'dd M yyyy'
			});
			
			$('#txtFilterStartDate').change(function(e) {
				try
				{
					if ($('#txtFilterStartDate').val() && $('#txtFilterEndDate').val())
					{
						VerifyFilterStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="Start Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			$('#txtFilterEndDate').datepicker({
				weekStart: 1,
				todayBtn:  1,
				autoclose: 1,
				todayHighlight: 1,
				startView: 0,
				forceParse: 0,
				format: 'dd M yyyy'
			});
						
			$('#txtFilterEndDate').change(function(e) 
			{
				try
				{
					if ($('#txtFilterStartDate').val() && $('#txtFilterEndDate').val())
					{
						VerifyFilterStartAndEndDates();
					}	
				}catch(e)
				{
					$.unblockUI();
					m="End Date Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			function VerifyFilterStartAndEndDates()
			{
				try
				{
					$('#divAlert').html('');
					
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
					var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid())
					{
						m="Advert Start Date Is Not Valid. Please Select A Valid Advert Start Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
					
					if (!ddt.isValid())
					{
						m="Advert End Date Is Not Valid. Please Select A Valid Advert End Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
										
					//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
					
					var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
					var diff = moment.duration(ddt.diff(pdt));
										
					if (dys<0)
					{
						$('#txtFilterEndDate').val('');
												
						m="Advert End Date Is Before Advert Start Date. Please Correct Your Entries!";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
				}catch(e)
				{
					$.unblockUI();
					m="VerifyStartAndEndDates ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
				}
			}
			
			function VerifyStartAndEndDates()
			{
				try
				{
					$('#divAlert').html('');
					
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
					var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					
					if (!pdt.isValid())
					{
						m="Advert Start Date Is Not Valid. Please Select A Valid Advert Start Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
					
					if (!ddt.isValid())
					{
						m="Advert End Date Is Not Valid. Please Select A Valid Advert End Date";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
										
					//moment('2010-10-20').isSameOrBefore('2010-10-21');  // true
					
					var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
					var diff = moment.duration(ddt.diff(pdt));
										
					if (dys<0)
					{
						$('#txtEndDate').val('');
												
						m="Advert End Date Is Before Advert Start Date. Please Correct Your Entries!";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
					}
				}catch(e)
				{
					$.unblockUI();
					m="VerifyStartAndEndDates ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
				}
			}
			
			$('#btnReset').click(function(e) {
                try
				{
					$('#divAlert').html('');
					$('#txtFilterStartDate').val('');
					$('#txtFilterEndDate').val('');
					$('#cboFilterStatus').val('All');
					
					$('#recorddisplay > tbody').html('');
					
					$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Reset Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			$('#btnDisplay').click(function(e) {
                try
				{
					$('#divAlert').html('');
					
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterStartDate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterEndDate').val(),'-',' ');
					var sta=$('#cboFilterStatus').val();
					
					if (!ValidateFilter()) return false;
					
					DisplayAdverts(sdt,edt,sta);
					
					$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Adverts Display Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
			function DisplayAdverts(sdt,edt,sta)
			{
				try
				{					
					table = $('#recorddisplay').DataTable( {
					select: true,
					destroy:true,
					dom: '<"top"if>rt<"bottom"lp><"clear">',
					language: {zeroRecords: "No Advert Record Found"},
					columnDefs: [ 
						{
							"targets": [ 0,1,2,3,4,5 ],
							"visible": true,
						},
						{
							"targets": [ 6,7,8,9 ],
							"visible": false,
						},
						{
							"targets": [ 1,2,3,4 ],
							"orderable": true
						},
						{
							"targets": [ 1,2,3,4 ],
							"searchable": true
						},
						{
							"targets": [ 0,5,6,7,8,9 ],
							"orderable": false,
							"searchable": false
						},
						{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8,9 ] }
					],  //[SELECT],Title,StartDate,EndDate,Status,Pix,Id,Description,status,pix
					columns: [
						{ width: "5%" },//Select
						{ width: "40%" },//Title
						{ width: "13%" },//StartDate
						{ width: "13%" },//EndDate
						{ width: "14%" },//Status
						{ width: "15%" },//Pix
						{ width: "0%" },//Record ID
						{ width: "0%" },//Description
						{ width: "0%" },//status
						{ width: "0%" }//pix
					],
					order: [[ 2, 'desc' ],[ 2, 'asc']],
					ajax: {
					  	url: '<?php echo site_url('Ads/LoadAds'); ?>',
						data: {startdate:sdt, enddate:edt, status:sta},
						type: 'POST',
						dataType: 'json'
				   }
				} );
				}catch(e)
				{
					$.unblockUI();
					m='DisplayAdverts ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			}
			
			function ValidateFilter()
			{
				try
				{
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtFilterEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
					var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					var p=$.trim($('#txtFilterStartDate').val());
					var d=$.trim($('#txtFilterEndDate').val());
										
					//Start date Not Select. End Date Selected
					if (!p)
					{
						m='You have not selected the display start date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtFilterStartDate').focus(); return false; 
					}
					
					if (!d)
					{
						m='You have not selected the display end date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtFilterEndDate').focus(); return false; 
					}
					
					if (!p && d)
					{
						m='You have selected the display end date. Display start date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtFilterStartDate').focus(); return false; 
					}
					
					//End date Not Select. Start Date Selected
					if (p && !d)
					{
						m='You have selected the display start date. Display end date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtFilterEndDate').focus(); return false; 
					}
					
					if (p)
					{
						if (!pdt.isValid())
						{
							m="Display Start Date Is Not Valid. Please Select A Valid Display Start Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
							
							
							$('#txtFilterStartDate').focus(); return false;
						}	
					}
					
					if (d)
					{
						if (!ddt.isValid())
						{
							m="Display End Date Is Not Valid. Please Select A Valid Display End Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
														
							$('#txtFilterEndDate').focus(); return false;
						}	
					}
					
					
					if (p && d)
					{
						var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
						if (dys<0)
						{
							m="Display End Date Is Before The Start Date. Please Correct Your Entries!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
							
							$('#txtFilterEndDate').focus(); return false;
						}	
					}
												
					return true;
				}catch(e)
				{
					$.unblockUI();
					m='ValidateFilter ERROR:\n'+e;
							
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close!", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
					
					return false;
				}
			}
						
			$('#btnDelete').click( function () 
			{
				try
				{
					var ct='',id='';
					ct=seldata[1]; id=seldata[6];
					
					//Validate 
					if ($.trim(id)=='')
					{
						m='Please select an advert from the table before clicking on "DELETE" button.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
								
						$('#txtTitle').focus(); return false;
					}else
					{
						if (!confirm('Are you sure you want to delete the advert "'+ct.toUpperCase()+'" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
						{
							return false;
						}else//Delete
						{
							//Send values here
							$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting Advert. Please Wait...</p>',theme: true,baseZ: 2000});
						
							//Make Ajax Request			
							var mydata={id: id};
							
							$.ajax({
								url: '<?php echo site_url('Ads/DeleteAdvert'); ?>',
								data: mydata,
								type: 'POST',
								dataType: 'text',
								success: function(data,status,xhr) {
									$.unblockUI();
									
									var ret=$.trim(data);
									
									if (ret.toUpperCase()=='OK')
									{
										m='Advert With Title <b>'+ct.toUpperCase()+'</b> Was Successfully Deleted!';
									
										bootstrap_Success_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
										
										ResetControls();
									}else
									{
										ResetControls();
								
										m=ret;
										bootstrap_alert.warning(m);
										bootbox.alert({ 
											size: 'small', message: m, title:Title,
											buttons: { ok: { label: "Close", className: "btn-danger" } },
											callback:function(){
												setTimeout(function() {
													$('#divAlert').fadeOut('fast');
												}, 10000);
											}
										});
									}
								},
								error:  function(xhr,status,error) 
								{
									m='Error '+ xhr.status + ' Occurred: ' + error;
									bootstrap_alert.warning(m);
									
									bootbox.alert({ 
										size: 'small', message: m, title:Title,
										buttons: { ok: { label: "Close", className: "btn-danger" } },
										callback:function(){
											setTimeout(function() {
												$('#divAlert').fadeOut('fast');
											}, 10000);
										}
									});
								}
							});	
						}
					}
				}catch(e)
				{
					$.unblockUI();
					m='Delete Advert Button Click ERROR:\n'+e;
					
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			} );	
								
			//Edit record
			$('#btnEdit').click(function(e){//EDIT
				try
				{
					if (logo_pix==null) logo_pix='';
					
					if (!checkForm('EDIT')) return false;
					
					var t=seldata[1],id=seldata[6];
												
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing Advert. Please Wait...</p>',theme: true,baseZ: 2000});
									
					//Make Ajax Request	
					var tit=$.trim($('#txtTitle').val());
					var desc=$.trim($('#txtDescription').val());
					var url = $.trim($('#txtURL').val());
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');					
					var sta=$.trim($('#cboStatus').val());
						
					
					
					//Initiate POST
					var uri = "<?php echo site_url('Ads/EditAdvert');?>";
					var xhr = new XMLHttpRequest();
					var fd = new FormData();
					
					xhr.open("POST", uri, true);
					
					xhr.onreadystatechange = function() {
						//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
						if (xhr.readyState == 4 && xhr.status == 200)
						{
							// Handle response.
							$.unblockUI();
							
							var res=$.trim(xhr.responseText);
														
							if (res.toUpperCase()=='OK')
							{
								m='Advert with title <b>'+t.toUpperCase()+'</b> Was Edited Successfully!';
								
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
								
								ResetControls();
							}else
							{
								ResetControls();
								
								m=res;
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}
						}
					};

					if (logo_pix != null) fd.append('logo_pix', logo_pix);
					
					fd.append('title', tit);
					fd.append('description', desc);
					fd.append('startdate', sdt);
					fd.append('enddate', edt);
					fd.append('ads_status', sta);
					fd.append('id', id);
					fd.append('url', url);

					xhr.send(fd);
				}catch(e)
				{
					$.unblockUI();
					var m='Edit Advert Click ERROR:\n'+e;
				   
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			});//End click-btnEdit
			
			$('#btnAdd').click(function(e) {
				try
				{
					if (logo_pix==null) logo_pix='';
					
					if (!checkForm('ADD')) return false;
				
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Adding Advert. Please Wait...</p>',theme: true,baseZ: 2000});
					
					//Make Ajax Request
					var tit=$.trim($('#txtTitle').val());
					var desc=$.trim($('#txtDescription').val());
					var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');					
					var sta=$.trim($('#cboStatus').val());
                    var url=$.trim($('#txtURL').val());

                    //Initiate POST
					var uri = "<?php echo site_url('Ads/AddAdvert');?>";
					var xhr = new XMLHttpRequest();
					var fd = new FormData();

					xhr.open("POST", uri, true);
					
					xhr.onreadystatechange = function() {
						//0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
						if (xhr.readyState == 4 && xhr.status == 200)
						{
							// Handle response.
							$.unblockUI();
							
							var res=$.trim(xhr.responseText);
														
							if (res.toUpperCase()=='OK')
							{
								m='Advert with title <b>'+tit.toUpperCase()+'</b> Was Added Successfully!';
								bootstrap_Success_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							
								//Clear boxes
								ResetControls();
							}else
							{
								ResetControls();
				
								m=res;
								bootstrap_alert.warning(m);
								bootbox.alert({ 
									size: 'small', message: m, title:Title,
									buttons: { ok: { label: "Close", className: "btn-danger" } },
									callback:function(){
										setTimeout(function() {
											$('#divAlert').fadeOut('fast');
										}, 10000);
									}
								});
							}
						}
					};

					if (logo_pix != null) fd.append('logo_pix', logo_pix);
					
					fd.append('title', tit);
					fd.append('description', desc);
					fd.append('startdate', sdt);
					fd.append('enddate', edt);
					fd.append('ads_status', sta);
					fd.append('url', url);
					xhr.send(fd);
					
					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					var m='Add Advert Button Click ERROR:\n'+e;
				   
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
			});//btnAdd.click
						
			function checkForm(fn)
			{
				try
				 {
					var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
					var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
					var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
					var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
					var tdt='<?php echo date('Y-m-d'); ?>';
					var p=$.trim($('#txtStartDate').val());
					var d=$.trim($('#txtEndDate').val());
					var url=$.trim($('#txtURL').val());
					var tit=$.trim($('#txtTitle').val());
					var sta=$.trim($('#cboStatus').val());
					
					 var ct='',id='';
					 
					 if (seldata)
					 {
						ct=seldata[1];
						id=seldata[6];
						
						if ($.trim(ct)=='')
						{
							if ($.trim(fn).toUpperCase()=='EDIT')
							{
								m='Please select an advert record before clicking on "EDIT" button.';
							}else
							{
								m='Please select an advert record before clicking on "DELETE" button.';
							}
							
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
							
							activateTab('tabReport'); return false;
						}
					 }
					
					//Title 
					if (tit=='')
					{
						m="Please enter the advert title.";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtTitle').focus(); activateTab('tabData'); return false;
					}

                     if (!url)
                     {
                         m='You have not entered the link for the advert.';

                         bootstrap_alert.warning(m);
                         bootbox.alert({
                             size: 'small', message: m, title:Title,
                             buttons: { ok: { label: "Close!", className: "btn-danger" } },
                             callback:function(){
                                 setTimeout(function() {
                                     $('#divAlert').fadeOut('fast');
                                 }, 10000);
                             }
                         });

                         $('#txtURL').focus(); activateTab('tabData'); return false;
                     }

					if ($.isNumeric(tit))
					{
						m="Advert title field must not be a number.";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtTitle').focus(); activateTab('tabData'); return false;
					}
					
					//Start date Not Select. End Date Selected
					if (!p)
					{
						m='You have not selected the advert start date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtStartDate').focus(); activateTab('tabData'); return false; 
					}
					
					if (!d)
					{
						m='You have not selected the advert end date.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtEndDate').focus(); activateTab('tabData'); return false; 
					}
					
					if (!p && d)
					{
						m='You have selected the advert end date. Advert start date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtStartDate').focus(); activateTab('tabData'); return false; 
					}
					
					//End date Not Select. Start Date Selected
					if (p && !d)
					{
						m='You have selected the advert start date. Advert end date field must also be selected.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtEndDate').focus(); activateTab('tabData'); return false; 
					}
					
					if (p)
					{
						if (!pdt.isValid())
						{
							m="Advert Start Date Is Not Valid. Please Select A Valid Advert Start Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});							
							
							$('#txtStartDate').focus(); activateTab('tabData'); return false;
						}	
					}
					
					if (d)
					{
						if (!ddt.isValid())
						{
							m="Advert End Date Is Not Valid. Please Select A Valid Advert End Date";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
														
							$('#txtEndDate').focus(); activateTab('tabData'); return false;
						}	
					}
					
					
					if (p && d)
					{
						var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
						if (dys<0)
						{
							m="Advert End Date Is Before The Start Date. Please Correct Your Entries!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
							
							$('#txtEndDate').focus(); activateTab('tabData'); return false;
						}	
					}
					
					var valid=moment(startdt).isBefore(tdt);
					
					if (valid==true)
					{
						m="Advert start cannot before today. Please correct your entry!";
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#txtStartDate').focus(); activateTab('tabData'); return false;
					}
					
					if (!sta)
					{
						m='Please select the advert status.';
						
						bootstrap_alert.warning(m);
						bootbox.alert({ 
							size: 'small', message: m, title:Title,
							buttons: { ok: { label: "Close!", className: "btn-danger" } },
							callback:function(){
								setTimeout(function() {
									$('#divAlert').fadeOut('fast');
								}, 10000);
							}
						});
						
						$('#cboStatus').focus(); activateTab('tabData'); return false; 
					}
					
					if ($.trim(fn).toUpperCase()=='ADD')
					{
						if ((logo_pix == null) || ($.trim(logo_pix) == ''))
						{
							m="No advert picture has been selected!";
							bootstrap_alert.warning(m);
							bootbox.alert({ 
								size: 'small', message: m, title:Title,
								buttons: { ok: { label: "Close!", className: "btn-danger" } },
								callback:function(){
									setTimeout(function() {
										$('#divAlert').fadeOut('fast');
									}, 10000);
								}
							});
							
							activateTab('tabData'); return false;
						}
					}
					
					if (!confirm('Are you sure you want to '+fn+' this advert record (Click "OK" to proceed or "CANCEL") to abort)?'))
					{
						activateTab('tabData'); return false;
					}
										
					return true;			
				 }catch(e)
				 {
					m='CHECK FORM ERROR:\n'+e; 
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },
						callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
					
					return false;
				 }
			 }//End CheckForm
        });//End document ready
		
		function GetRow(sn)
		{
			$('#txtTitle').val('');
			$('#txtDescription').val('');
			$('#txtStartDate').val('');
			$('#txtEndDate').val('');
			$('#cboStatus').val('');
			
			$('#imgPix').prop('src','');								
			$('#txtPix').val('');
			$('#hidID').val('');

			if (sn>-1)
			{
				var dat = table.row( sn ).data();
				
				seldata=dat;
		
				if (dat)
				{
					var tit=dat[1],sdt=dat[2],edt=dat[3],id=dat[6],desc=dat[7],sta=dat[8],px=dat[9];
					
					$('#txtTitle').val(tit);
					$('#txtDescription').val(desc);
					$('#txtStartDate').val(sdt);
					$('#txtEndDate').val(edt);
					$('#cboStatus').val(sta);
					$('#hidID').val(id);
					
					if (px)
					{
						$('#imgPix').prop('src','<?php echo base_url();?>ads_pix/'+px);
					}else
					{
						$('#imgPix').prop('src',emptypix);
					}
					
					activateTab('tabData');
														
					document.getElementById('btnEdit').disabled=false;
					document.getElementById('btnDelete').disabled=false;
					document.getElementById('btnAdd').disabled=true;
				}
			}
		}
		
		function SelectRow(dat)
		{
			if (dat)
			{//[SELECT],Title,StartDate,EndDate,Status,Pix,Id,Description,status,pix
				var tit=dat[1],sdt=dat[2],edt=dat[3],id=dat[6],desc=dat[7],sta=dat[8],px=dat[9];
				
				$('#txtTitle').val(tit);
				$('#txtDescription').val(desc);
				$('#txtStartDate').val(sdt);
				$('#txtEndDate').val(edt);
				$('#cboStatus').val(sta);
				
				if (px)
				{
					$('#imgPix').prop('src','<?php echo base_url();?>ads_pix/'+px);
				}else
				{
					$('#imgPix').prop('src',emptypix);
				}
				
				activateTab('tabData');
			}else
			{
				ResetControls();
			}
		}
		
		function ResetControls()
		{
			try
			{
				$('#txtTitle').val('');
				$('#txtDescription').val('');
                $('#txtURL').val('');
				$('#txtStartDate').val('');
				$('#txtEndDate').val('');
				$('#cboStatus').val('');
				$('#hidID').val('');
				
				$('#imgPix').prop('src','');								
				$('#txtPix').val('');
				
				$('#btnReset').trigger('click');
											
				if (document.getElementById('btnDelete')) document.getElementById('btnDelete').disabled=true;
				if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
				if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
			}catch(e)
			{
				$.unblockUI();
				m="ResetControls ERROR:\n"+e;
				bootstrap_alert.warning(m);
				bootbox.alert({ 
					size: 'small', message: m, title:Title,
					buttons: { ok: { label: "Close", className: "btn-danger" } },
					callback:function(){
						setTimeout(function() {
							$('#divAlert').fadeOut('fast');
						}, 10000);
					}
				});
			}
		}//End ResetControls
				
		
		
    </script>
  </head>
  <body class="hold-transition skin-yellow sidebar-mini">
  	   
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini" style="font-size:15px;"><b>LaffHub</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img style="margin-top:-10px; margin-left:-10px;" src="<?php echo base_url();?>images/header_logo.png" /></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
             	  

			   <li class="dropdown user user-menu" title="User Role">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  Role:&nbsp;&nbsp;<span class="hidden-xs"><?php echo $role; ?></span>
                </a>
              </li>
               
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span> <span id="spnUserFullname" class="hidden-xs"><?php echo $UserFullName.' ('.$username.')';?></span>
                </a>
                <ul class="dropdown-menu btn-primary">
                  <!-- User name -->
                  <li class="user-body" title="Username">
                    <p><b>Username:</b> <?php echo '<span class="yellowtext">'.$username.'</span>'; ?></p>
                  </li>
                  
                   <!-- Fullname -->
                  <li class="user-body" title="User FullName">
                    <p><b>Full Name:</b> <span id="spnUserFullname1"><?php echo '<span class="yellowtext">'.$UserFullName.'</span>'; ?></span></p>
                  </li>
                  
                 <!--Role-->
				 <li class="user-body"  title="User Role">  	
                    <p><b>Role:</b> <?php echo '<span class="yellowtext">'.$role.'</span>'; ?></p>
                </li>
                     <!--Category End-->          
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-right">
                      <a href="<?php echo site_url("Logout"); ?>" class="btn btn-danger btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
     <?php include('sidemenu.php'); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
         <h4>
            
          </h4>
          
          <ol class="breadcrumb size-16">
            <li><a href="<?php echo site_url("Logout"); ?>"><i class="fa fa-home"></i> Home</a></li>
          </ol>
        </section>
        
       

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          		<div class="col-md-12">
          			<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-television"></i> Adverts</div>
                <div class="panel-body">                
              		 
                     <!--Tab-->
                    <ul class="nav nav-tabs " style="font-weight:bold;">
                      <li  role="presentation" class="active"><a data-toggle="tab" href="#tabData"><i class="glyphicon glyphicon-plus"></i> Capture Adverts</a></li>
                      <li role="presentation"><a data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Adverts</a></li>
                    </ul>
                    <!--Tab Ends-->
                    
                    <div class="tab-content"> 
                    		<div id="tabData" class="row tab-pane fade in active " style="margin:5px 5px;">
                        	 <p>
                            <div align="center" id="txtInfo" style="text-align:center; font-weight:bold; font-style:italic; color: #BBBBBB; " class=" size-14">Fields With <span class="redtext">*</span> Are Required!</div>
                       </p>
                     
                   		<form class="form-horizontal">	
                		<div class="form-group" title="Advert Title">
                      		<label for="txtTitle" class="col-sm-2 control-label ">Advert Title<span class="redtext">*</span></label>
    
                          <div class="col-sm-10">
                             <input style="text-transform:none;" type="text" class="form-control" id="txtTitle" placeholder="Enter Advert Title" required>
                             
                             <input type="hidden" id="hidID">
                          </div>
                    	</div>
                        
                        <div class="form-group" title="Advert Description">
                      		<label for="txtDescription" class="col-sm-2 control-label ">Advert Description<span class="redtext">*</span></label>
    
                          <div class="col-sm-10">
                             <input style="text-transform:none;" type="text" class="form-control" id="txtDescription" placeholder="Enter Advert Description" required>
                          </div>
                    	</div>

                        <div class="form-group" title="Advert Description">
                            <label for="txtURL" class="col-sm-2 control-label ">Advert URL<span class="redtext">*</span></label>

                            <div class="col-sm-10">
                                <input style="text-transform:none;" type="text" class="form-control" id="txtURL" placeholder="Enter comedian's page. e.g. Comedian/ShowComedian/3" required>
                            </div>
                        </div>
                        
                        <!--Advert Start Date/End Date-->
                        <div class="form-group" title="Advert Duration">
                           <!--Advert Start Date-->
                            <label class="col-sm-2 control-label left" for="txtStartDate" title="Advert Start Date">Advert Start Date</label>
                                    
                            <div class="col-sm-2" title="Advert Start Date">
                              <input readonly id="txtStartDate" type="text" class="form-control" placeholder="Advert Start Date">
                              <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                            </div> 
                            
                            <!--Advert End Date-->
                            <label class="col-sm-4 control-label left" for="txtEndDate" title="Advert End Date">Advert End Date</label>
                                    
                            <div class="col-sm-2" title="Advert End Date">
                              <input readonly id="txtEndDate" type="text" class="form-control" placeholder="Advert End Date">
                              <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                            </div> 
                        </div>
                                               
                        <!--Status/Advert Picture -->
                        <div class="form-group">    
                        	<!--Advert Status-->
                            <label class="col-sm-2 control-label left" for="cboStatus">Advert Status</label>
                                    
                            <div class="col-sm-2">
                              <select id="cboStatus" class="form-control">
                              	<option value="">[SELECT]</option>
                                <option value="1">Active</option>
                                <option value="0">Disabled</option>
                              </select>
                            </div>
                            
                            <!--Advert Picture- 370 x 340-->                    	
                            <label  title="Picture Width=370px, Picture Height=340px" class="col-sm-4 control-label left" for="txtPix">Advert Picture(370px x 340px)</label>
                              <div class="col-sm-4" title="Advert Picture. Picture Width=370px, Picture Height=340px" style="border:dashed thin;">
                                <img src="" id="imgPix" style="border:1; border-style:solid; background-color:#FFF;" width="200px" /><p></p>
                                <input id="txtPix" name="txtPix" type="file" accept="image/jpeg,image/png" onchange="GetFile(this,'Picture');">
                              </div>
                        </div>
                    
                   
    				
                    <div class="form-group" style="margin-bottom:30px;">
                        <div class="col-sm-offset-2 col-sm-7">
                         	<button title="Add Record" id="btnAdd" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Add</span>
                            </button>
                            
                            <button disabled title="Edit Record" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Edit</span>
                            </button>
                            
                            <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-primary" role="button" style="width:140px; " >
                                <span class="ui-button-text">Refresh</span>
                            </button>
                            
                            <button disabled title="Delete Selected Record" id="btnDelete" type="button" class="btn btn-danger" role="button" style="text-align:center; width:140px; padding-left:20px; padding-right:20px;">
                                <span class="ui-button-text">Delete Record</span>
                            </button>
                        </div>
                        
                      
                        
                    </div>
                    </form>                    
                    	
                        </div>
                    
                    	<div id="tabReport" class="tab-pane fade">
                        	 <center>                           
                        
                        	<div class="table-responsive" style="margin-top:20px; ">
                                <!--Display Start Date/End Date-->
                                <form class="form-horizontal">
                                <div class="form-group">
                                   <!--Display Start Date-->
                                    <label class="col-sm-2 control-label left" for="txtFilterStartDate" title="Advert Start Date">Display Start Date</label>
                                            
                                    <div class="col-sm-2" title="Display Start Date">
                                      <input readonly id="txtFilterStartDate" type="text" class="form-control" placeholder="Display Start Date">
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div> 
                                    
                                    <!--Display End Date-->
                                    <label class="col-sm-2 control-label left" for="txtFilterEndDate" title="Display End Date">Display End Date</label>
                                            
                                    <div class="col-sm-2" title="Display End Date">
                                      <input readonly id="txtFilterEndDate" type="text" class="form-control" placeholder="Display End Date">
                                      <i class="fa fa-calendar form-control-feedback" style="margin-right:12px;"></i>
                                    </div>
                                    
                                    <!--Advert Status-->
                                    <label class="col-sm-2 control-label left" for="cboFilterStatus" title="Advert Status">Advert Status</label>
                                            
                                    <div class="col-sm-2" title="Advert Status">
                                      <select id="cboFilterStatus" class="form-control">
                                        <option value="All">[ALL]</option>
                                        <option value="1">Active</option>
                                        <option value="0">Disabled</option>
                                      </select>
                                    </div> 
                                </div>
                            
                                <div class="form-group">                                    
                                    <!--Display Button/Reset Button-->         
                                    <div class="col-sm-6 col-sm-offset-1">
                                         <button title="Display Adverts" id="btnDisplay" type="button" class="btn btn-info" style="width:140px; " >
                                            <span class="ui-button-text">Display</span>
                                        </button>
                                        
                                        <button title="Reset Display" id="btnReset" type="button" class="btn btn-warning" style="width:140px; margin-left:20px;" >
                                            <span class="ui-button-text">Reset</span>
                                        </button>
                                    </div>
                                </div>
                                </form>
                            
                            <table align="center" id="recorddisplay" cellspacing="0" title="Adverts" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                                  <thead style="color:#ffffff; background-color:#7E7B7B;">
                                    <tr>
                                        <th></th>
                                        <th>ADVERT TITLE</th>
                                        <th>START DATE</th>
                                        <th>END DATE</th>
                                        <th>STATUS</th>
                                        <th>ADVERT PIX</th>
                                        <th class="hide">RECORD ID</th>
                                        <th class="hide">Description</th>
                                        <th class="hide">status</th>
                                        <th class="hide">pix</th>
                                    </tr>
                                  </thead>

                              </table>
                            </div>
                       </center>
                        </div>
                    
                    </div>
                    
                    <div align="center">
                   	 	<div id = "divAlert"></div>
                   </div>
                       
              </div>
          </div>
        		</div>        
      		</div><!-- /.row -->
        </section><!-- /.row (main row) -->
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
         
        </div>
        <strong>Copyright &copy; <?php echo date('Y');?> <a href="">LaffHub</a>.</strong> All rights reserved.
      </footer>

      
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
    </div><!-- ./wrapper -->

    <!-- jQuery 2.1.4 -->
   
    <script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
     <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    
    <script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
    <!--<script src="<?php echo base_url();?>js/raphael-min.js"></script>-->
  	 <!--<script src="<?php #echo base_url();?>js/morris.min.js"></script>-->
     <script src="<?php echo base_url();?>js/jquery.sparkline.min.js"></script>
     <script src="<?php echo base_url();?>js/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="<?php echo base_url();?>js/jquery-jvectormap-world-mill-en.js"></script>
     <!--<script src="<?php #echo base_url();?>js/jquery.knob.js"></script>-->
     <!--<script src="<?php #echo base_url();?>js/Chart.min.js"></script><!-- AdminLTE App -->
     <script src="<?php echo base_url();?>js/moment.min.js"></script>
     <script src="<?php echo base_url();?>js/daterangepicker.js"></script>
     <script src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
     <script src="<?php echo base_url();?>js/bootstrap3-wysihtml5.all.min.js"></script>
     <script src="<?php echo base_url();?>js/jquery.slimscroll.min.js"></script>  
    <script src="<?php echo base_url();?>js/fastclick.min.js"></script>
    <script src="<?php echo base_url();?>js/app.min.js"></script>
    <!--<script src="<?php #echo base_url();?>js/dashboard.js"></script>-->

        
  </body>
</html>
