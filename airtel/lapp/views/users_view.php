<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">
    
    <title>LaffHub | Users Information</title>
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
		
		var Title='<font color="#AF4442">Create User Help</font>';
		var m='';
		
		var Username='<?php echo $username; ?>';
		var UserFullName='<?php echo $UserFullName; ?>';
		var table,editdata,seldata;
						
    	$(document).ready(function(e) {
			$(function() {
				// clear out plugin default styling
				$.blockUI.defaults.css = {};
			});
			
			$(document).ajaxStop($.unblockUI);
			
			table = $('#recorddisplay').DataTable( {
				dom: '<"top"if>rt<"bottom"lp><"clear">',
				autoWidth:false,
				language: {zeroRecords: "No User Record Found"},
				lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
				select:true,
				destroy:true,
				columnDefs: [ 
					{
						"targets": [ 0,1,2,3,4,5,6,7,8,9 ],
						"visible": true,
						"searchable": true
					},
					{
						"targets": [ 10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33 ],
						"visible": false,
						"searchable": false
					},
					{
						"targets": [ 2,3,6,7,9 ],
						"orderable": true
					},
					{
						"targets": [ 0,1,4,5,8 ],
						"orderable": false
					},
					{ className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8,9 ] }
				],
				columns: [
					{ width: "5%" },//Edit
					{ width: "5%" },//Delete
					{ width: "10%" },//Username
					{ width: "10%" },//Fullname
					{ width: "10%" },//Email
					{ width: "10%" },//Phone
					{ width: "5%" },//Status
					{ width: "5%" },//Role
					{ width: "35%" },//Permissions
					{ width: "5%" },//Date Created
					{ width: "0%" },//Password
					{ width: "0%" },//AddItem
					{ width: "0%" },//EditItem
					{ width: "0%" },//DeleteItem
					{ width: "0%" },//CreateUser
					{ width: "0%" },//ClearLogFiles
					{ width: "0%" },//CreatePublisher
					{ width: "0%" },//CreateComedian
					{ width: "0%" },//SetParameters
					{ width: "0%" },//ViewLogReport
					{ width: "0%" },//ViewReports
					{ width: "0%" },//Account Status					
					{ width: "0%" },//CreateCategory
					{ width: "0%" },//CreateEvents
					{ width: "0%" },//ApproveVideo					
					{ width: "0%" },//ApproveComment
					{ width: "0%" },//AddBanners
					{ width: "0%" },//AddMobileOperator
					{ width: "0%" },//Upload_Video
					{ width: "0%" },//AddArticlesToBlog
					{ width: "0%" },//CheckDailyReports
					{ width: "0%" },//ModifyStaticPage
					{ width: "0%" },//firstname
					{ width: "0%" }//lastname
				],
				order: [[ 2, 'asc' ]],
				ajax: {
					url: '<?php echo site_url('Users/LoadUsersJson'); ?>',
					type: 'POST',
					dataType: 'json'
			   }
			} );
				
			// Add event listener for opening and closing details
			$('#recorddisplay tbody').on('click', 'td', function () {
				var tr = $(this).closest('tr');
				var row = table.row( tr );
				editdata = row.data();
				
				var colIndex = $(this).index();
				
				if (colIndex==0) SelectRow(editdata);
			} );	
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () 
			{							
				if ( $(this).hasClass('selected') ) {
					$(this).removeClass('selected');
					
					if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
					if (document.getElementById('btnCreate')) document.getElementById('btnCreate').disabled=true;

					//alert('Select');
					//Get Selected Value					
					var val=table.row( this ).data();
					seldata=val;
					var un=val[2],fn=val[3],em=val[4],ph=val[5],sta=val[6],rl=val[7],pem=val[8],dt=val[9],pwd=val[10],ait=val[11],eit=val[12],dit=val[13],cusr=val[14],clog=val[15],cp=val[16],ccm=val[17],spar=val[18],vlog=val[19],vrep=val[20],st=val[21],ccat=val[22],cev=val[23],avid=val[24],acm=val[25],abn=val[26],aop=val[27],uvid=val[28],ablog=val[29],crep=val[30], mpg=val[31],fname=val[32],lname=val[33];
					
//[EDIT],[DELETE],Username,Name,Email,Phone,Status,Role,[Permissions],Datecreated,Pwd,
//AddItem,EditItem,DeleteItem,CreateUser(14),ClearLogFiles,CreatePublisher,CreateComedian,SetParameters,ViewLogReport,ViewReports,ACCOUNSTATUS,CreateCategory,CreateEvents,ApproveVideo,ApproveComment,AddBanners,AddMobileOperator,Upload_Video,AddArticlesToBlog,CheckDailyReports, ModifyStaticPage,firstname,lastname
		
					$('#txtUsername').val(un);
					$('#txtFirstname').val(fname);
					$('#txtLastname').val(lname);
					$('#txtPhone').val(ph);
					$('#txtEmail').val(em);
					$('#cboRole').val(rl);
					$('#cboStatus').val(st);
					$('#txtPwd').val(pwd);
					$('#txtConfirmPwd').val('');
					
					$('#chkAddItem').prop('checked',false);
					$('#chkEditItem').prop('checked',false);
					$('#chkDeleteItem').prop('checked',false);
					$('#chkCreateUser').prop('checked',false);
					$('#chkCreatePublisher').prop('checked',false);
					$('#chkCreateComedian').prop('checked',false);
					$('#chkCreateCategory').prop('checked',false);
					$('#chkCreateEvents').prop('checked',false);
					$('#chkApproveVideo').prop('checked',false);
					$('#chkApproveComment').prop('checked',false);					
					$('#chkAddBanners').prop('checked',false);
					$('#chkAddMobileOperator').prop('checked',false);
					$('#chkUpload_Video').prop('checked',false);					
					$('#chkAddArticlesToBlog').prop('checked',false);
					$('#chkCheckDailyReports').prop('checked',false);
					$('#chkModifyStaticPage').prop('checked',false);					
					$('#chkClearLogFiles').prop('checked',false);					
					$('#chkSetParameters').prop('checked',false);
					$('#chkViewLogReports').prop('checked',false);
					$('#chkViewReports').prop('checked',false);					
					
					
					if (parseInt(ait,10)==1) $('#chkAddItem').prop('checked',true);
					if (parseInt(eit,10)==1) $('#chkEditItem').prop('checked',true);
					if (parseInt(dit,10)==1) $('#chkDeleteItem').prop('checked',true);
					if (parseInt(cusr,10)==1) $('#chkCreateUser').prop('checked',true);
					if (parseInt(cp,10)==1) $('#chkCreatePublisher').prop('checked',true);
					if (parseInt(ccm,10)==1) $('#chkCreateComedian').prop('checked',true);
					if (parseInt(ccat,10)==1) $('#chkCreateCategory').prop('checked',true);
					if (parseInt(cev,10)==1) $('#chkCreateEvents').prop('checked',true);
					if (parseInt(avid,10)==1) $('#chkApproveVideo').prop('checked',true);					
					if (parseInt(acm,10)==1) $('#chkApproveComment').prop('checked',true);
					if (parseInt(abn,10)==1) $('#chkAddBanners').prop('checked',true);
					if (parseInt(aop,10)==1) $('#chkAddMobileOperator').prop('checked',true);
					if (parseInt(uvid,10)==1) $('#chkUpload_Video').prop('checked',true);
					if (parseInt(ablog,10)==1) $('#chkAddArticlesToBlog').prop('checked',true);
					if (parseInt(crep,10)==1) $('#chkCheckDailyReports').prop('checked',true);
					if (parseInt(mpg,10)==1) $('#chkModifyStaticPage').prop('checked',true);					
					if (parseInt(clog,10)==1) $('#chkClearLogFiles').prop('checked',true);					
					if (parseInt(spar,10)==1) $('#chkSetParameters').prop('checked',true);
					if (parseInt(vlog,10)==1) $('#chkViewLogReports').prop('checked',true);
					if (parseInt(vrep,10)==1) $('#chkViewReports').prop('checked',true);					
								
					
					$('#txtUsername').prop('disabled',true); 
					$('#txtPwd').prop('disabled',true); 
					$('#txtConfirmPwd').prop('disabled',true); 
				}
				else 
				{					
					table.$('tr.selected').removeClass('selected');
					$(this).addClass('selected');
					//alert('UnSelect');
					
					ResetControls();
				}
			} );
			
			$('#recorddisplay tbody').on( 'click', 'tr', function () {
				$(this).toggleClass('selected');
			} );
			
//AddItem,EditItem,DeleteItem,CreateUser,CreatePublisher, CreateComedian, CreateCategory, CreateEvents,ApproveVideo, ApproveComment, AddBanners, AddMobileOperator,Upload_Video, AddArticlesToBlog, CheckDailyReports, ModifyStaticPage,SetParameters, ViewLogReport,ClearLogFiles,ViewReports

//ApproveComment,AddBanners,AddMobileOperator,Upload_Video, AddArticlesToBlog, CheckDailyReports, ModifyStaticPage
			
			$('#cboRole').change(function(e) 
			{
                try
				{
					var rl=$(this).val();	
					
					if ($.trim(rl.toLowerCase())=='admin')
					{
						$('#chkAddItem').prop('checked',true);
						$('#chkEditItem').prop('checked',true);
						$('#chkDeleteItem').prop('checked',true);
						$('#chkCreateUser').prop('checked',true);
						$('#chkCreatePublisher').prop('checked',true);
						$('#chkCreateComedian').prop('checked',true);
						$('#chkCreateCategory').prop('checked',true);
						$('#chkCreateEvents').prop('checked',true);
						$('#chkApproveVideo').prop('checked',true);
						$('#chkApproveComment').prop('checked',true);
						$('#chkAddBanners').prop('checked',true);
						$('#chkAddMobileOperator').prop('checked',true);
						$('#chkUpload_Video').prop('checked',true);
						$('#chkAddArticlesToBlog').prop('checked',true);
						$('#chkCheckDailyReports').prop('checked',true);
						$('#chkModifyStaticPage').prop('checked',true);
						$('#chkSetParameters').prop('checked',true);
						$('#chkViewLogReports').prop('checked',true);
						$('#chkClearLogFiles').prop('checked',true);
						$('#chkViewReports').prop('checked',true);						
						
						//disabled attributes
						$('#chkAddItem').prop('disabled',true);
						$('#chkEditItem').prop('disabled',true);
						$('#chkDeleteItem').prop('disabled',true);
						$('#chkCreateUser').prop('disabled',true);
						$('#chkCreatePublisher').prop('disabled',true);
						$('#chkCreateComedian').prop('disabled',true);
						$('#chkCreateCategory').prop('disabled',true);
						$('#chkCreateEvents').prop('disabled',true);
						$('#chkApproveVideo').prop('disabled',true);
						$('#chkApproveComment').prop('disabled',true);
						$('#chkAddBanners').prop('disabled',true);
						$('#chkAddMobileOperator').prop('disabled',true);
						$('#chkUpload_Video').prop('disabled',true);
						$('#chkAddArticlesToBlog').prop('disabled',true);
						$('#chkCheckDailyReports').prop('disabled',true);
						$('#chkModifyStaticPage').prop('disabled',true);
						$('#chkSetParameters').prop('disabled',true);
						$('#chkViewLogReports').prop('disabled',true);
						$('#chkClearLogFiles').prop('disabled',true);
						$('#chkViewReports').prop('disabled',true);			
					}else
					{
						$('#chkAddItem').prop('checked',false);
						$('#chkEditItem').prop('checked',false);
						$('#chkDeleteItem').prop('checked',false);
						$('#chkCreateUser').prop('checked',false);
						$('#chkCreatePublisher').prop('checked',false);
						$('#chkCreateComedian').prop('checked',false);
						$('#chkCreateCategory').prop('checked',false);
						$('#chkCreateEvents').prop('checked',false);
						$('#chkApproveVideo').prop('checked',false);
						$('#chkApproveComment').prop('checked',false);
						$('#chkAddBanners').prop('checked',false);
						$('#chkAddMobileOperator').prop('checked',false);
						$('#chkUpload_Video').prop('checked',false);
						$('#chkAddArticlesToBlog').prop('checked',false);
						$('#chkCheckDailyReports').prop('checked',false);
						$('#chkModifyStaticPage').prop('checked',false);
						$('#chkSetParameters').prop('checked',false);
						$('#chkViewLogReports').prop('checked',false);
						$('#chkClearLogFiles').prop('checked',false);
						$('#chkViewReports').prop('checked',false);
												
						//disabled attributes						
						$('#chkAddItem').prop('disabled',false);
						$('#chkEditItem').prop('disabled',false);
						$('#chkDeleteItem').prop('disabled',false);
						$('#chkCreateUser').prop('disabled',false);
						$('#chkCreatePublisher').prop('disabled',false);
						$('#chkCreateComedian').prop('disabled',false);
						$('#chkCreateCategory').prop('disabled',false);
						$('#chkCreateEvents').prop('disabled',false);
						$('#chkApproveVideo').prop('disabled',false);
						$('#chkApproveComment').prop('disabled',false);
						$('#chkAddBanners').prop('disabled',false);
						$('#chkAddMobileOperator').prop('disabled',false);
						$('#chkUpload_Video').prop('disabled',false);
						$('#chkAddArticlesToBlog').prop('disabled',false);
						$('#chkCheckDailyReports').prop('disabled',false);
						$('#chkModifyStaticPage').prop('disabled',false);
						$('#chkSetParameters').prop('disabled',false);
						$('#chkViewLogReports').prop('disabled',false);
						$('#chkClearLogFiles').prop('disabled',false);
						$('#chkViewReports').prop('disabled',false);						
					}
				}catch(e)
				{
					$.unblockUI();
					m="Role Changed ERROR:\n"+e;
					bootstrap_alert.warning(m);
					bootbox.alert({ 
						size: 'small', message: m, title:Title,
						buttons: { ok: { label: "Close", className: "btn-danger" } },callback:function(){
							setTimeout(function() {
								$('#divAlert').fadeOut('fast');
							}, 10000);
						}
					});
				}
            });
			
						
			$('#btnEdit').click(function(e) {
				try
				{
					if (editdata)
					{						
						var un=editdata[2],fn=editdata[3],em=editdata[4],ph=editdata[5],sta=editdata[6],rl=editdata[7],pem=editdata[8],dt=editdata[9],pwd=editdata[10],ait=editdata[11],eit=editdata[12],dit=editdata[13],cusr=editdata[14],clog=editdata[15],vgu=editdata[16],ctag=editdata[17],spar=editdata[18],vlog=editdata[19],vrep=editdata[20],st=editdata[21],actmem=editdata[22],withreq=editdata[23],conreq=editdata[24];
						
						if (!un)
						{
							m='Please select the record you want to edit by clicking on the row containing the user account record in the table in VIEW USERS tab and modify the required data before continuing with the editing.';
						
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
					}else
					{
						m='Please select the record you want to edit by clicking on the row containing the user account record in the table in VIEW USERS tab and modify the required data before continuing with the editing.';
						
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
					
					if (!CheckForm('edit')) return false;
					
					m=''
					
					$('#divAlert').html('');
					
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Editing User Account. Please Wait...</p>',theme: true,baseZ: 2000});
										
					//Make Ajax Request					
					var un=$.trim($('#txtUsername').val());
					var fn=$.trim($('#txtFirstname').val());
					var ln=$.trim($('#txtLastname').val());
					var ph=$.trim($('#txtPhone').val());
					var em=$.trim($('#txtEmail').val());
					var rl=$('#cboRole').val();
					var sta=$('#cboStatus').val();
					var ait='0',eit='0',dit='0',cusr='0',clog='0',cp='0',ccm='0',spar='0',vlog='0',vrep='0';
					var ccat='0',cev='0',avid='0',acm='0',abn='0',aop='0',uvid='0',ablog='0',crep='0',mpg='0';
					
					if ($('#chkAddItem').prop('checked')) ait='1';
					if ($('#chkEditItem').prop('checked')) eit='1';
					if ($('#chkDeleteItem').prop('checked')) dit='1';
					if ($('#chkCreateUser').prop('checked')) cusr='1';
					if ($('#chkCreatePublisher').prop('checked')) cp='1';
					if ($('#chkCreateComedian').prop('checked')) ccm='1';
					if ($('#chkCreateCategory').prop('checked')) ccat='1';
					if ($('#chkCreateEvents').prop('checked')) cev='1';
					if ($('#chkApproveVideo').prop('checked')) avid='1';					
					if ($('#chkApproveComment').prop('checked')) acm='1';
					if ($('#chkAddBanners').prop('checked')) abn='1';					
					if ($('#chkAddMobileOperator').prop('checked')) aop='1';
					if ($('#chkUpload_Video').prop('checked')) uvid='1';
					if ($('#chkAddArticlesToBlog').prop('checked')) ablog='1';
					if ($('#chkCheckDailyReports').prop('checked')) crep='1';
					if ($('#chkModifyStaticPage').prop('checked')) mpg='1';					
					if ($('#chkClearLogFiles').prop('checked')) clog='1';					
					if ($('#chkSetParameters').prop('checked')) spar='1';
					if ($('#chkViewLogReports').prop('checked')) vlog='1';
					if ($('#chkViewReports').prop('checked')) vrep='1';	
					
					var mydata={username:un, firstname:fn, lastname:ln, email:em, phone:ph, accountstatus:sta, role:rl, User:Username,UserFullname:UserFullName,AddItem:ait,EditItem:eit,DeleteItem:dit,CreateUser:cusr,CreatePublisher:cp,CreateComedian:ccm,CreateCategory:ccat,CreateEvents:cev,ApproveVideo:avid,ApproveComment:acm,AddBanners:abn,AddMobileOperator:aop,Upload_Video:uvid,AddArticlesToBlog:ablog,CheckDailyReports:crep, ModifyStaticPage:mpg,ClearLogFiles:clog,SetParameters:spar,ViewLogReport:vlog,ViewReports:vrep};
										
					$.ajax({
						url: "<?php echo site_url('Users/EditUsers');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							if ($.trim(data)=='OK')
							{
								$.unblockUI();
																
								m='User Account "'+un.toUpperCase()+'('+fn.toUpperCase()+')" Was Edited successfully.';
								
								table.ajax.reload();
								
								ResetControls();
										
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
							}else
							{
								$.unblockUI();
								
								m=data;
								
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
						error:  function(xhr,status,error) {
								$.unblockUI();
								
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
					
					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Edit Button Click ERROR:\n'+e;
					
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
            });//btnEdit Click Ends
			
			$('#btnCreate').click(function(e) {
				try
				{
					if (!CheckForm('create')) return false;
					
					m='';
					
					//Send values here
					$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Creating User Account. Please Wait...</p>',theme: true,baseZ: 2000});
					
					$('#divAlert').html('');
										
					//Make Ajax Request
					var un=$.trim($('#txtUsername').val());
					var fn=$.trim($('#txtFirstname').val());
					var ln=$.trim($('#txtLastname').val());
					
					var ph=$.trim($('#txtPhone').val());
					var em=$.trim($('#txtEmail').val());
					var rl=$('#cboRole').val();
					var sta=$('#cboStatus').val();
					var pwd=$('#txtPwd').val();
					var cpwd=$('#txtConfirmPwd').val();
					var ait='0',eit='0',dit='0',cusr='0',clog='0',cp='0',ccm='0',spar='0',vlog='0',vrep='0';
					var ccat='0',cev='0',avid='0',acm='0',abn='0',aop='0',uvid='0',ablog='0',crep='0',mpg='0';
					
					if ($('#chkAddItem').prop('checked')) ait='1';
					if ($('#chkEditItem').prop('checked')) eit='1';
					if ($('#chkDeleteItem').prop('checked')) dit='1';
					if ($('#chkCreateUser').prop('checked')) cusr='1';
					if ($('#chkCreatePublisher').prop('checked')) cp='1';
					if ($('#chkCreateComedian').prop('checked')) ccm='1';
					if ($('#chkCreateCategory').prop('checked')) ccat='1';
					if ($('#chkCreateEvents').prop('checked')) cev='1';
					if ($('#chkApproveVideo').prop('checked')) avid='1';					
					if ($('#chkApproveComment').prop('checked')) acm='1';
					if ($('#chkAddBanners').prop('checked')) abn='1';					
					if ($('#chkAddMobileOperator').prop('checked')) aop='1';
					if ($('#chkUpload_Video').prop('checked')) uvid='1';
					if ($('#chkAddArticlesToBlog').prop('checked')) ablog='1';
					if ($('#chkCheckDailyReports').prop('checked')) crep='1';
					if ($('#chkModifyStaticPage').prop('checked')) mpg='1';					
					if ($('#chkClearLogFiles').prop('checked')) clog='1';					
					if ($('#chkSetParameters').prop('checked')) spar='1';
					if ($('#chkViewLogReports').prop('checked')) vlog='1';
					if ($('#chkViewReports').prop('checked')) vrep='1';					
																				
					var mydata={username:un, firstname:fn, lastname:ln, email:em, phone:ph, accountstatus:sta, role:rl, pwd:sha512($('#txtPwd').val()),User:Username,UserFullname:UserFullName,AddItem:ait,EditItem:eit,DeleteItem:dit,CreateUser:cusr,CreatePublisher:cp,CreateComedian:ccm,CreateCategory:ccat,CreateEvents:cev,ApproveVideo:avid,ApproveComment:acm,AddBanners:abn,AddMobileOperator:aop,Upload_Video:uvid,AddArticlesToBlog:ablog,CheckDailyReports:crep, ModifyStaticPage:mpg,ClearLogFiles:clog,SetParameters:spar,ViewLogReport:vlog,ViewReports:vrep};
																								
					$.ajax({
						url: "<?php echo site_url('Users/AddUsers');?>",
						data: mydata,
						type: 'POST',
						dataType: 'text',
						complete: function(xhr, textStatus) {
							//$.unblockUI;
						},
						success: function(data,status,xhr) {	
							$.unblockUI();
							
							var ret=$.trim(data);
		
							if (ret.toUpperCase() == 'OK')
							{
								m='User Account "'+un.toUpperCase()+'('+fn.toUpperCase()+')" Was Created successfully.';
								
								ResetControls();
								
								table.ajax.reload();
								
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
							}else
							{
								m=data;
								
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
						error:  function(xhr,status,error) {
								$.unblockUI;
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
					
					//$.unblockUI();
				}catch(e)
				{
					$.unblockUI();
					m='Create User Button Click ERROR:\n'+e;
					
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
            });//btnCreate Click Ends
			
			function CheckForm(par)
			{
				try
				{
					var un=$.trim($('#txtUsername').val());
					var fn=$.trim($('#txtFirstname').val());
					var ln=$.trim($('#txtLastname').val());
					var ph=$.trim($('#txtPhone').val());
					var em=$.trim($('#txtEmail').val());
					var rl=$('#cboRole').val();
					var sta=$('#cboStatus').val();
					var pwd=$('#txtPwd').val();
					var cpwd=$('#txtConfirmPwd').val();
					
					if (!Username)
					{
						m='Your username is not set. Your current session may have timed out. Please refresh the page. If this error persists, sign out and sign in again.';
						
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
					
					if (par.toLowerCase()=='create')
					{
						//Username			
						if (!un)
						{
							m='Username field must not be blank.';
							
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
							
							activateTab('tabData');
							
							$('#txtUsername').focus(); return false;
						}
					}
					
					//First Name
					if (!fn)
					{
						m='First name field must not be blank.';
						
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
						
						activateTab('tabData');
						
						$('#txtFirstname').focus(); return false;
					}
					
					if ($.isNumeric(fn))
					{
						m='First name field must not be a number.';
						
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
						
						activateTab('tabData');
						
						$('#txtFirstname').focus(); return false;
					}
					
					//Last Name
					if (!ln)
					{
						m='Last name field must not be blank.';
						
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
						
						activateTab('tabData');
						
						$('#txtLastname').focus(); return false;
					}
					
					if ($.isNumeric(ln))
					{
						m='Last name field must not be a number.';
						
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
						
						activateTab('tabData');
						
						$('#txtLastname').focus(); return false;
					}
										
					//Email
					if (!em)
					{
						m='Email field must not be blank.';
						
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
						
						activateTab('tabData');
						
						$('#txtEmail').focus(); return false;
					}
					
					//Valid Email?
					//  /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
					var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
					if(!rx.test(em))
					{
						m='Invalid email address.';   
						
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
						
						activateTab('tabData');
						
						$('#txtEmail').focus(); return false;
					}
					
					//Role
					if (!rl)
					{
						m='Please select user role.';
						
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
						
						activateTab('tabData');
						
						$('#cboRole').focus(); return false;
					}
					
					//Status
					if (!sta)
					{
						m='Please select user account status.';
						
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
						
						activateTab('tabData');
						
						$('#cboStatus').focus(); return false;
					}
					
					if (par.toLowerCase()=='create')
					{
						//Pwd
						if (!$.trim(pwd))
						{
							m='Password field must not be blank.';
							
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
							
							activateTab('tabData');
							
							$('#txtPwd').focus(); return false;
						}
						
						if (pwd.length<6)
						{
							m='Minimum password size is six(6) characters.';
							
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
							
							activateTab('tabData');
							
							$('#txtPwd').focus(); return false;
						}
						
						//Confirm Password
						if (!$.trim(cpwd))
						{
							m='Confirm password field must not be blank.';
							//$('#status').html(m); alert(m); 
							
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
							
							activateTab('tabData');
							
							$('#txtConfirmPwd').focus();   return false;
						}
						
						if (pwd != cpwd)
						{
							m='New password and confirming password fields do not match.';
							
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
							
							activateTab('tabData');
							
							$('#txtConfirmPwd').focus();   return false;
						}	
					}
					
														
					//Confirm
					if (par.toLowerCase()=='create')
					{
						if (!confirm('Do you want to proceed with the user account creation? (Click "OK" to proceed or "CANCEL" to abort)'))
						{
							return false;

						}	
					}
					
					if (par.toLowerCase()=='edit')
					{
						if (!confirm('Do you want to proceed with the editing of the user account? (Click "OK" to proceed or "CANCEL" to abort)'))
						{
							return false;
						}	
					}
					
					return true;
				}catch(e)
				{
					$.unblockUI();
					m='CheckForm ERROR:\n'+e;
					
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
			}
			
			$('#ancLogout').click(function(e) {
			try
			{
				LogOut();
			}catch(e)
			{
				$.unblockUI();
				m="Sign Out Button Click ERROR:\n"+e;
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
			
			$('#ancMenuSignOut').click(function(e) {
				try
				{
					LogOut();
				}catch(e)
				{
					$.unblockUI();
					m="Sign Out Button Click ERROR:\n"+e;
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
        });//End document ready
		
		function ResetControls()
		{
			try
			{					
				if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
				if (document.getElementById('btnCreate')) document.getElementById('btnCreate').disabled=false;
				
				$('#txtUsername').val('');
				$('#txtFirstname').val('');
				$('#txtLastname').val('');
				$('#txtPhone').val('');
				$('#txtEmail').val('');
				$('#cboRole').val('');
				$('#cboStatus').val('');
				$('#txtPwd').val('');
				$('#txtConfirmPwd').val('');
				
				$('#chkAddItem').prop('checked',false);
				$('#chkEditItem').prop('checked',false);
				$('#chkDeleteItem').prop('checked',false);
				$('#chkCreateUser').prop('checked',false);
				$('#chkCreatePublisher').prop('checked',false);
				$('#chkCreateComedian').prop('checked',false);
				$('#chkCreateCategory').prop('checked',false);
				$('#chkCreateEvents').prop('checked',false);
				$('#chkApproveVideo').prop('checked',false);
				$('#chkApproveComment').prop('checked',false);
				$('#chkAddBanners').prop('checked',false);
				$('#chkAddMobileOperator').prop('checked',false);
				$('#chkUpload_Video').prop('checked',false);
				$('#chkAddArticlesToBlog').prop('checked',false);
				$('#chkCheckDailyReports').prop('checked',false);
				$('#chkModifyStaticPage').prop('checked',false);
				$('#chkSetParameters').prop('checked',false);
				$('#chkViewLogReports').prop('checked',false);
				$('#chkClearLogFiles').prop('checked',false);
				$('#chkViewReports').prop('checked',false);
				
				
				$('#txtUsername').prop('disabled',false); 
				$('#txtPwd').prop('disabled',false); 
				$('#txtConfirmPwd').prop('disabled',false);
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
				
		function DeleteRow(uname,fullname)
		{			
			try
			{
				if (!uname)
				{
					m='There is a problem with the selected row. Click on REFRESH button to refresh the page. If this message keeps coming up, please contact us at <a href="support@speedmotioninternational.com">support@speedmotioninternational.com</a>.';
					
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
				}else
				{
					if (!confirm('Are you sure you want to delete the user account "'+uname.toUpperCase()+'('+fullname.toUpperCase()+')" from the database?. Please note that this action is irreversible. To continue, click "OK" otherwise, click "CANCEL".'))
					{
						return false;
					}else//Delete
					{
						//Send values here
						$.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Deleting User Account. Please Wait...</p>',theme: true,baseZ: 2000});
					
						$('#divAlert').html('');
						
						m=''
						
						//Make Ajax Request			
						var mydata={username:uname,fullname:fullname,UserFullName:UserFullName,User:Username};
						
						$.ajax({
							url: '<?php echo site_url('admin/Users/DeleteUser'); ?>',
							data: mydata,
							type: 'POST',
							dataType: 'text',
							complete: function(xhr, textStatus) {
								//$.unblockUI();
							},
							success: function(data,status,xhr) {				
								//Clear boxes							
								if ($.trim(data)=='OK')
								{
									ResetControls();
									
									$.unblockUI();
									
									//activateTab('tabData');
																	
									table.ajax.reload( function ( json ) {
										m='User Account "'+uname.toUpperCase()+'('+fullname.toUpperCase()+')" Was Deleted successfully.';
										
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
									} );
								}else
								{
									$.unblockUI();
									
									m=data;
									
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
							error:  function(xhr,status,error) {
								
								bootstrap_alert.warning('Error '+ xhr.status + ' Occurred: ' + error);
								bootbox.alert({ 
									size: 'small', message: 'Error '+ xhr.status + ' Occurred: ' + error, title:Title,
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
				m='Delete User Account Button Click ERROR:\n'+e;
				
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
		
		function SelectRow(dat)
		{
			if (dat)
			{
				var un=dat[2],fn=dat[3],em=dat[4],ph=dat[5],sta=dat[6],rl=dat[7],pem=dat[8],dt=dat[9],pwd=dat[10],ait=dat[11],eit=dat[12],dit=dat[13],cusr=dat[14],clog=dat[15],vgu=dat[16],ctag=dat[17],spar=dat[18],vlog=dat[19],vrep=dat[20],st=dat[21],actmem=dat[22],withreq=dat[23],conreq=dat[24];
								
				$('#txtUsername').val(un);
				$('#txtFullname').val(fn);
				$('#txtPhone').val(ph);
				$('#txtEmail').val(em);
				$('#cboRole').val(rl);
				$('#cboStatus').val(st);
				$('#txtPwd').val(pwd);
				$('#txtConfirmPwd').val('');
				
				$('#chkAddItem').prop('checked',false);
				$('#chkEditItem').prop('checked',false);
				$('#chkDeleteItem').prop('checked',false);
				$('#chkCreateUser').prop('checked',false);
				$('#chkClearLogFiles').prop('checked',false);
				$('#chkCreatePublisher').prop('checked',false);
				$('#chkCreateComedian').prop('checked',false);
				$('#chkSetParameters').prop('checked',false);
				$('#chkViewLogReports').prop('checked',false);
				$('#chkViewReports').prop('checked',false);				
				$('#chkCreateCategory').prop('checked',false);
				$('#chkCreateEvents').prop('checked',false);
				$('#chkApproveVideo').prop('checked',false);
				
				if (parseInt(ait,10)==1) $('#chkAddItem').prop('checked',true);
				if (parseInt(eit,10)==1) $('#chkEditItem').prop('checked',true);
				if (parseInt(dit,10)==1) $('#chkDeleteItem').prop('checked',true);
				if (parseInt(cusr,10)==1) $('#chkCreateUser').prop('checked',true);
				if (parseInt(clog,10)==1) $('#chkClearLogFiles').prop('checked',true);
				if (parseInt(vgu,10)==1) $('#chkCreatePublisher').prop('checked',true);
				if (parseInt(ctag,10)==1) $('#chkCreateComedian').prop('checked',true);
				if (parseInt(spar,10)==1) $('#chkSetParameters').prop('checked',true);
				if (parseInt(vlog,10)==1) $('#chkViewLogReports').prop('checked',true);
				if (parseInt(vrep,10)==1) $('#chkViewReports').prop('checked',true);				
				if (parseInt(actmem,10)==1) $('#chkCreateCategory').prop('checked',true);
				if (parseInt(withreq,10)==1) $('#chkCreateEvents').prop('checked',true);
				if (parseInt(conreq,10)==1) $('#chkApproveVideo').prop('checked',true);
				
				$('#txtUsername').prop('disabled',true); 
				$('#txtPwd').prop('disabled',true); 
				$('#txtConfirmPwd').prop('disabled',true);
				
				activateTab('tabData');
			}else
			{
				ResetControls();
			}
		}
		
		function LogOut()
		{
			var m="Signing out will abort every active process and unsaved data will be lost. Do you still want to sign out? (Click <b>YES</b> to proceed or <b>NO</b> to abort)";
										
			bootbox.confirm({
				title: "<font color='#ff0000'>SpeedMotion | Sign Out</font>",
				message: m,
				buttons: {
					confirm: {
						label: 'Yes',
						className: 'btn-success'
					},
					cancel: {
						label: 'No',
						className: 'btn-danger'
					}
				},
				callback: function (result) {
					if (result) window.location.href='<?php echo site_url("admin/Logout"); ?>';
				}
			});	
		}
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
                      <a href="#" onClick="LogOut();" class="btn btn-danger btn-flat">Sign Out</a>
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
        <section class="content-header" style="text-align:center;">
          <span style="float:left; font-size:22px; color:#DB5832;">LaffHub</span>
          
          <span style="float:right;"><a id="ancLogout" href="#"><i class="fa fa-home"></i> Home</a></span>
        </section> 
        
       

        <!-- Main content -->
        <section class="content">
        	<div class="row">     
          		<div class="col-md-12">
          			<div class="panel panel-info">
          	  <!-- Default panel contents -->
              <div class="panel-heading size-20"><i class="fa fa-users"></i> Users Information</div>
                <div class="panel-body">                
              	<!--Tab-->
                <ul class="nav nav-tabs " style="font-weight:bold;">
                  <li  role="presentation" class="active"><a id="idData" data-toggle="tab" href="#tabData"><i class="glyphicon glyphicon-list-alt"></i> User Data</a></li>
                  <li role="presentation"><a id="idReport" data-toggle="tab" href="#tabReport"><i class="fa fa-eye"></i> View Users</a></li>
                </ul>
    			<!--Tab Ends-->
                
                <!--Tab Details-->
				<div class="tab-content">
                	<div id="tabData" class="row tab-pane fade in active ">
                    	<div class="table-responsive">
                        	
                               <div align="center" class="size-14 " style="font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; margin-top:10px;">Fields With <span class="redtext">*</span> Are Required!</div>
                           
                            
                      		<br><form class="form-horizontal">                                                  
                              <!--Username-->
                              <div class="form-group" title="Enter Username">
                                <label class="col-sm-2 control-label  left" for="txtUsername">Username<span class="redtext">*</span></label>
                                <div class="col-sm-3">
                                  <input id="txtUsername" type="text" class="form-control" required="required" placeholder="Username">
                                </div>
                              </div>
                              
                              <!--First Name-->
                            <div class="form-group">
                                <label class="col-sm-2 control-label  left" for="txtFirstname" title="Enter First Name">First Name<span class="redtext">*</span></label>
                                <div class="col-sm-3" title="Enter First Name">
                                  <input id="txtFirstname" type="text" class="form-control" required="required" placeholder="First Name">
                                </div>
                                
                                <!--Last Name-->
                                 <label class="col-sm-3 control-label  left" for="txtLastname" title="Enter Last Name">Last Name<span class="redtext">*</span></label>
                                <div class="col-sm-3" title="Enter Last Name">
                                  <input id="txtLastname" type="text" class="form-control" required="required" placeholder="Last Name">
                                </div>
                              </div>
                              
                              

                              <!--Phone/Email-->
                            <div class="form-group" title="Enter Phone Number">
                            	 <!--Phone-->
                                <label class="col-sm-2 control-label " for="txtPhone">Phone</label>
                                <div class="col-sm-3">
                                  <input id="txtPhone" type="text" class="form-control" placeholder="Phone Number">
                                </div>
                                
                                <!--Email-->
                                <label class="col-sm-3 control-label" for="txtEmail" title="Enter Email">Email<span class="redtext">*</span></label>
                                    <div class="col-sm-3" title="Enter Email">
                                      <input id="txtEmail" type="email" class="form-control padright" required="required" placeholder="Email">
                                    </div>
                              </div>
                                    
                                
                                
                              <!--User Role/Status-->
                              <div class="form-group" title="Select User Role">
                              	<!--User Role-->
                                <label class="col-sm-2 control-label" for="cboRole">User Role<span class="redtext">*</span></label>
                                
                                <div class="col-sm-3">
                                <select id="cboRole" class="form-control">
                                	<option value="">[SELECT]</option>
                                    <option value="Others">Others</option>
                                    <option value="Admin">Admin</option>
                                </select>
                                </div>
                                
                                <!--User Status-->
                                <label class="col-sm-3 control-label" for="cboStatus" title="Select Account Status">Account Status<span class="redtext">*</span></label>
                                
                               <div class="col-sm-3" title="Select Account Status">
                                <select id="cboStatus" class="form-control">
                                	<option value="">[SELECT]</option>
                                    <option value="1">Active</option>
                                    <option value="0">Disabled</option>
                                </select>
                                </div>
                              </div>
                              
                               
                               <!--Password/Confirm Password-->
                               <div class="form-group" title="Enter Your Password">
                               <!--Password-->
                                <label class="col-sm-2 control-label" for="txtPwd">Password<span class="redtext">*</span></label>
                                <div class="col-sm-3">
                                    <input id="txtPwd" type="password" class="form-control" placeholder="Password" required="required">
                                </div>
                                
                                <!--Confirm Password-->
                                <label class="col-sm-3 control-label" for="txtConfirmPwd">Confirm&nbsp;Password<span class="redtext">*</span></label>
                        
                                <div class="col-sm-3">
                                    <input id="txtConfirmPwd" type="password" class="form-control" placeholder="Confirm Password" required="required">
                                </div> 
                            </div>
                  		
                        <!--Permissions--> 
                  		 <div class="form-group">
                         	<fieldset class="col-sm-10 col-sm-offset-1" style="border:thin solid; background-color:#F1EAF0;">
                          <legend style="width:auto; color:#7B090B;" class="size-14 makebold">Permissions</legend>
                             
                        <!--Permissions 1 => AddItem,EditItem,DeleteItem,CreateUser--> 
                  		 <div class="form-group">
                         	
                                <!--AddItem-->
                                <div class="col-sm-3" title="Add Item">
                                    <label><input type="checkbox" id="chkAddItem" value="AddItem" />&nbsp;Add&nbsp;Item</label>
                                </div>
                                
                                <!--EditItem-->
                                <div class="col-sm-3" title="Edit Item">
                                    <label><input type="checkbox" id="chkEditItem" value="EditItem" />&nbsp;Edit&nbsp;Item</label>
                                </div>
                                
                                <!--DeleteItem-->
                                <div class="col-sm-3" title="Delete Item">
                                    <label><input type="checkbox" id="chkDeleteItem" value="DeleteItem" />&nbsp;Delete&nbsp;Item</label>
                                </div>                
                              
                              	 <!--Create Users-->
                                <div class="col-sm-3" title="Create Users">
                                    <label><input type="checkbox" id="chkCreateUser" value="CreateUser" />&nbsp;Create&nbsp;Users</label>
                                </div>
                          </div>
                                
						<!--Permissions 2 => CreatePublisher, CreateComedian, CreateCategory, CreateEvents-->
                        <div class="form-group">
                                <!--Create Publishers -->
                                <div class="col-sm-3" title="Create Publishers">
                                    <label><input type="checkbox" id="chkCreatePublisher" value="CreatePublisher" />&nbsp;Create&nbsp;Publisher</label>
                                </div>
                                
                                <!--Create Comedian-->
                                <div class="col-sm-3" title="Create Comedian">
                                    <label><input type="checkbox" id="chkCreateComedian" value="CreateComedian" />&nbsp;Create&nbsp;Comedian</label>
                                </div>           
                              
                              	 <!--Create Category-->
                              	<div class="col-sm-3" title="Creat Category">
                                    <label><input type="checkbox" id="chkCreateCategory" value="CreateCategory" />&nbsp;Create&nbsp;Category</label>
                                </div>   
                                
                                <!--Create Events-->
                                <div class="col-sm-3" title="Create Events">
                                    <label><input type="checkbox" id="chkCreateEvents" value="CreateEvents" />&nbsp;Create&nbsp;Event</label>
                                </div>                        
                          </div>
                          
                       <!--Permissions 3 => ApproveVideo, ApproveComment, AddBanners, AddMobileOperator--> 
                         <div class="form-group">
                            <!--ApproveVideo-->
                            <div class="col-sm-3" title="Approve Video">
                                <label><input type="checkbox" id="chkApproveVideo" value="ApproveVideo" />&nbsp;Approve&nbsp;Video</label>
                            </div>
                                
                              <!--ApproveComment-->
                              <div class="col-sm-3" title="Approve Comments">
                                <label><input type="checkbox" id="chkApproveComment" value="ApproveComment" />&nbsp;Approve&nbsp;Comments</label>
                            </div>
                            
                              <!--AddBanners-->
                              <div class="col-sm-3" title="Add Banners">
                                <label><input type="checkbox" id="chkAddBanners" value="AddBanners" />&nbsp;Add&nbsp;Banners</label>
                            </div>
                              
                              <!--AddMobileOperator-->
                              <div class="col-sm-3" title="Add Mobile Operators">
                                <label><input type="checkbox" id="chkAddMobileOperator" value="AddMobileOperator" />&nbsp;Add&nbsp;Mobile&nbsp;Operator</label>
                            </div>
                          </div>                       
                          
                          <!--Permissions 4 => Upload_Video, AddArticlesToBlog, CheckDailyReports, ModifyStaticPage-->
                          <div class="form-group">
                            <!--Upload Videos-->
                            <div class="col-sm-3" title="Upload Videos">
                                <label><input type="checkbox" id="chkUpload_Video" value="Upload_Video" />&nbsp;Upload&nbsp;Videos</label>
                            </div>
                            
                            <!--AddArticlesToBlog-->
                            <div class="col-sm-3" title="Add Articles To Blog">
                                <label><input type="checkbox" id="chkAddArticlesToBlog" value="AddArticlesToBlog" />&nbsp;Add&nbsp;Articles&nbsp;To&nbsp;Blog</label>
                            </div>
                            
                            <!--Check Daily Reports-->
                            <div class="col-sm-3" title="Check Daily Reports">
                                <label><input type="checkbox" id="chkCheckDailyReports" value="CheckDailyReports" />&nbsp;Check&nbsp;Daily&nbsp;Reports</label>
                            </div> 
                            
                            <!--Modify Static Pages-->
                            <div class="col-sm-3" title="Modify Static Pages">
                                <label><input type="checkbox" id="chkModifyStaticPage" value="ModifyStaticPage" />&nbsp;Modify&nbsp;Static&nbsp;Pages</label>
                            </div>
                      	</div>
                          
                          
                          <!--Permissions 5 => SetParameters, ViewLogReport,ClearLogFiles,ViewReports--> 
                         <div class="form-group">
                            <!--SetParameters-->
                            <div class="col-sm-3" title="Set Parameters">
                                <label><input type="checkbox" id="chkSetParameters" value="SetParameters" />&nbsp;Set&nbsp;Parameters</label>
                            </div>
                            
                            <!--View Log Reports-->
                            <div class="col-sm-3" title="View Log Reports">
                                <label><input type="checkbox" id="chkViewLogReports" value="ViewLogReport" />&nbsp;View&nbsp;Log&nbsp;Reports</label>
                            </div>
                            
                            <!--ClearLogFiles-->
                            <div class="col-sm-3" title="Clear Log Data">
                                <label><input type="checkbox" id="chkClearLogFiles" value="ClearLogFiles" />&nbsp;Clear&nbsp;Log&nbsp;Data</label>
                            </div>
                            
                            <!--View Reports-->
                            <div class="col-sm-3" title="View Reports">
                                <label><input type="checkbox" id="chkViewReports" value="ViewReports" />&nbsp;View&nbsp;Reports</label>
                            </div> 
                      	</div>
                       </fieldset>
                          </div>                               
    </form>
                    </div>
                </div><!--End Of Tab Content 1-->
              
              <!--Table Tab Content-->
                   <div id="tabReport" class="tab-pane fade">
                    <center>
                        <div class="table-responsive" style="margin-top:20px; ">
                        <table align="center" id="recorddisplay" cellspacing="0" title="Users Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid;">
                              <thead style="color:#ffffff; background-color:#7E7B7B;">
                               <tr>
                                 	<th></th>
                                    <th></th>
                                    <th>USERNAME</th>
                                    <th>NAME</th>                               
                                    <th>EMAIL</th> 
                                    <th>PHONE</th>                               
                                    <th>STATUS</th>  
                                    <th>ROLE</th>
                                    <th>PERMISSIONS</th>                               
                                    <th>DATE&nbsp;CREATED</th>
                                    <th>PWD</th>                                     
                                    <th>AddItem</th>
                                    <th>EditItem</th>
                                    <th>DeleteItem</th>
                                    <th>CreateUser</th>
                                    <th>ClearLogFiles</th>
                                    <th>CreatePublisher</th>
                                    <th>CreateComedian</th>
                                    <th>SetParameters</th>
                                    <th>ViewLogReport</th>
                                    <th>ViewReports</th>
                                    <th>ACCOUNTSTATUS</th>                                    
                                    <th>CreateCategory</th>
                                    <th>CreateEvents</th>
                                    <th>ApproveVideo</th>                                    
                                    <th>ApproveComment</th>
                                    <th>AddBanners</th>
                                    <th>AddMobileOperator</th>                                    
                                    <th>Upload_Video</th>
                                    <th>AddArticlesToBlog</th>
                                    <th>CheckDailyReports</th>
                                    <th>ModifyStaticPage</th>                                    
                                    <th>firstname</th>
                                    <th>lastname</th>
                                </tr>
                              </thead>
                                 
                          </table>
                    	</div>
                       </center>
                </div><!--Table Tab Content-->
              
                <div align="center" style="margin-top:10px;">
                    <div id = "divAlert"></div>
               </div>
                                   
                 
                <div align="center" style="margin-top:30px;">
                <button title="Create User" id="btnCreate" type="button" class="btn btn-primary " role="button" style="text-align:center; width:150px;"><i class="glyphicon glyphicon-send"></i> Create User</button>
                
                <button disabled title="Edit User" id="btnEdit" type="button" class="btn btn-primary" role="button" style="text-align:center; width:150px; padding-left:20px; padding-right:20px;">
                    <span class="ui-button-text">Edit User</span>
                </button>
                                                                            
                <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-danger" role="button" style="text-align:center; width:150px;"><span class="glyphicon glyphicon-refresh" ></span> Refresh</button>
                </div>
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

        
  </body>
</html>
