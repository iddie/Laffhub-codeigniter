<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

    <title>LaffHub | Update Video Details</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <?php include('homelink.php'); ?>

    <script src="<?php echo base_url();?>js/jwplayer.js"></script>


    <style>

        .big-box
        {
            position: absolute;
            width: 50%;
            height: 50%;
            color:white;
        }
        .big-box h2
        {
            text-align: center;
            margin-top: 20%;
            padding: 20px;
            width: 100%;
            font-size: 1.8em;
            letter-spacing: 2px;
            font-weight: 700;
            text-transform: uppercase;
            cursor:pointer;
        }
        @media screen and (max-width: 46.5em)
        {
            .big-box h2
            {
                font-size:16px;
                padding-left:0px;
            }
        }
        @media screen and (max-width: 20.5em)
        {
            .big-box h2
            {
                font-size:12px;
                padding-left:0px;
                margin-top:30%;
            }
        }


        .modal-content-one
        {
            background-color:yellowgreen;
        }
        .modal-content-two
        {
            background-color:#E6537D;
        }
        .modal-content-three
        {
            background-color:crimson;
        }
        .modal-content-four
        {
            background-color:lightseagreen;
        }
        .close
        {
            color:white ! important;
            opacity:1.0;
        }
    </style>

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

        bootstrap_Assignalert = function() {}
        bootstrap_Assignalert.warning = function(message)
        {
            $('#divAssignAlert').html('<div class="alert alert-danger alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#AF4442">'+message+'</font></span></div>')
        }

        bootstrap_Success_Assignalert = function() {}
        bootstrap_Success_Assignalert.warning = function(message)
        {
            $('#divAssignAlert').html('<div class="alert alert-success alert-dismissable fade in show"><button type="button" class="close" data-dismiss="alert" aria-label="close" aria-hidden = "true">&times;</button><span><font color="#1B691A">'+message+'</font></span></div>')
        }

        var Title='<font color="#AF4442">Update Video Details Help</font>';
        var m='';

        var RefreshDuration='<?php echo $RefreshDuration; ?>';
        var Username='<?php echo $username; ?>';
        var UserFullName='<?php echo $UserFullName; ?>';
        var table,assigntable;
        var InputBucket='<?php echo $input_bucket; ?>';
        var OutputBucket='<?php echo $output_bucket; ?>';
        var ThumbBucket='<?php echo $thumbs_bucket; ?>';

        $(document).ready(function(e) {
            $(function() {
                // clear out plugin default styling
                $.blockUI.defaults.css = {};
            });

            $(document).ajaxStop($.unblockUI);

            if (RefreshDuration) RefreshDuration=RefreshDuration*1000;

            $('#btnUpdate').prop('disabled',true);
            $('#btnAssign').prop('disabled',true);

            LoadComedians();

            function LoadComedians()
            {
                try
                {
                    $('#cboComedians').empty();
                    $('#cboAssignComedians').empty();

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Comedians. Please Wait...</p>',theme: true,baseZ: 2000});

                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: '<?php echo site_url('Editvideodetails/GetComedians'); ?>',
                        complete: function(xhr, textStatus) {
                            $.unblockUI;
                        },
                        success: function(data,status,xhr) //we're calling the response json array 'cntry'
                        {
                            if ($(data).length > 0)
                            {
                                $('#cboComedians').append( new Option('[SELECT]','') );
                                $('#cboAssignComedians').append( new Option('[SELECT]','') );

                                $.each($(data), function(i,e)
                                {
                                    if (e.comedian)
                                    {
                                        $('#cboComedians').append( new Option(e.comedian,e.comedian) );
                                        $('#cboAssignComedians').append( new Option(e.comedian,e.comedian) );
                                    }
                                });
                            }

                            $.unblockUI();
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
                    }); //end AJAX
                }catch(e)
                {
                    $.unblockUI();
                    m='LoadComedians Module ERROR:\n'+e;

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

            CheckForAWSValues();

            function CheckForAWSValues()
            {
                $.unblockUI();

                if (!InputBucket || !OutputBucket || !ThumbBucket)
                {
                    m='One or more of the Amazon buckets(folders) values is not set. These values can be set in the PORTAL SETTINGS menu item of the SETTINGS/USERS menu. You need the necessary permission to be be able to set the value. You may contact the system administrator to do this. You will now be redirected back to the dashboard';

                    bootstrap_alert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback: function(){
                            window.location.href='<?php echo site_url("Userhome"); ?>';
                        }
                    });
                }

                if (!('<?php echo $aws_key; ?>') || !('<?php echo $aws_secret; ?>'))
                {
                    m='The Amazon key and/or the Amazon secret code is not set. These values can be set in the PORTAL SETTINGS menu item of the SETTINGS/USERS menu. You need the necessary permission to be be able to set the value. You may contact the system administrator to do this. You will now be redirected back to the dashboard';

                    bootstrap_alert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback: function(){
                            window.location.href='<?php echo site_url("Userhome"); ?>';
                        }
                    });
                }
            }

            function LoadVideos(category,status)
            {
                try
                {
                    table = $('#recorddisplay').DataTable( {
                        //select: true,

                        dom: '<"top"if>rt<"bottom"lp><"clear">',
                        destroy:true,
                        autoWidth:false,
                        language: {zeroRecords: "No Video Record Found"},
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        columnDefs: [
                            {
                                "targets": [ 7,8 ],
                                "visible": false
                            },
                            {
                                "targets": [ 0,1,2,3,4,5,6 ],
                                "visible": true,
                            },
                            {
                                "targets": [ 2,4,5 ],
                                "searchable": true,
                                "orderable": true
                            },
                            {
                                "targets": [ 0,1,3,6 ],
                                "searchable": false,
                                "orderable": false
                            },
                            { className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7,8] }
                        ],//[SELECT],Category,Title,Description,Comedian,VideoStatus,[Preview],Filename,VideoCode
                        columns: [
                            { width: "5%", },//SELECT
                            { width: "15%" },//CATEGORY
                            { width: "22%", },//VIDEO TITLE
                            { width: "32%" },//DESCRIPTION
                            { width: "15%" },//COMEDIAN
                            { width: "5%" },//VIDEO STATUS
                            { width: "6%" },//PREVIEW VIDEO
                            { width: "0%" },//FILENAME
                            { width: "0%" }//VIDEO CODE
                        ],
                        order: [[ 1, 'asc' ],[ 2, 'asc' ]],
                        ajax: {
                            url: '<?php echo site_url('Editvideodetails/LoadVideosJson'); ?>',
                            type: 'POST',
                            data: {category:category, status:status, InputBucket:InputBucket, ThumbBucket:ThumbBucket},
                            complete: function(xhr, textStatus) {
                                $.unblockUI();
                            },
                            error:  function(xhr,status,error) {
                                $.unblockUI();
                                m='Error '+ xhr.status + ' Occurred: ' + error;
                                bootstrap_alert.warning(m);
                                bootbox.alert({
                                    size: 'small', message: m, title:Title,
                                    buttons: { ok: { label: "Close", className: "btn-danger" } }
                                });
                            },
                            dataType: 'json'
                        }
                    } );

                    $.unblockUI();
                }catch(e)
                {
                    $.unblockUI();
                    m='LoadVideos ERROR:\n'+e;

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

                $('#cboFeature').empty();
                $('#cboFeature').append( new Option('SELECT', '') );
                $('#cboFeature').append( new Option('YES', 'YES') );
                $('#cboFeature').append( new Option('NO', 'NO') );

            }//LoadVideos

            $('#btnCloseVideoModal').click(function(e) {
                try
                {
                    $('#idModalBody').html('');
                }catch(e)
                {
                    $.unblockUI();
                    m="Modal Close Button Click ERROR:\n"+e;
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

            if (InputBucket && OutputBucket && ThumbBucket && ('<?php echo $aws_key; ?>') && ('<?php echo $aws_secret; ?>'))
            {
                LoadCategories();
            }

            function LoadCategories()
            {
                try
                {
                    $('#cboCategory').empty();
                    $('#cboAssignCategory').empty();

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Data. Please Wait...</p>',theme: true,baseZ: 2000});

                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        url: '<?php echo site_url('Editvideodetails/GetCategories'); ?>',
                        complete: function(xhr, textStatus) {
                            $.unblockUI;
                        },
                        success: function(data,status,xhr) //we're calling the response json array 'cntry'
                        {
                            if ($(data).length > 0)
                            {
                                $('#cboCategory').append( new Option('[SELECT]','') );
                                $('#cboAssignCategory').append( new Option('[SELECT]','') );

                                $.each($(data), function(i,e)
                                {
                                    if (e.category)
                                    {
                                        $('#cboCategory').append( new Option(e.category,e.category) );
                                        $('#cboAssignCategory').append( new Option(e.category,e.category) );
                                    }
                                });
                            }

                            $.unblockUI();
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
                    }); //end AJAX
                }catch(e)
                {
                    $.unblockUI();
                    m='LoadCategories Module ERROR:\n'+e;

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

            $('#btnAssignDisplay').click(function(e) {
                try
                {
                    $('#divAssignAlert').html('');

                    var sta=$('#cboAssignStatus').val();
                    var cat=$('#cboAssignCategory').val();

                    if ($('#cboAssignCategory > option').length < 2)
                    {
                        m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignCategory').focus(); return false;
                    }

                    if (!cat)
                    {
                        m='Please select a category.';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignCategory').focus(); return false;
                    }

                    if (!sta)
                    {
                        m='Please select the status of the videos you want to display.';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignStatus').focus(); return false;
                    }

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});

                    //Display videos
                    LoadAssignVideos(cat,sta);
                }catch(e)
                {
                    $.unblockUI();
                    m='Display Video Button Click ERROR:\n'+e;

                    bootstrap_Assignalert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback:function(){
                            setTimeout(function() {
                                $('#divAssignAlert').fadeOut('fast');
                            }, 10000);
                        }
                    });
                }
            });

            function LoadAssignVideos(category,status)
            {
                try
                {
                    assigntable = $('#assignrecorddisplay').DataTable( {
                        //select: true,

                        dom: '<"top"if>rt<"bottom"lp><"clear">',
                        destroy:true,
                        autoWidth:false,
                        language: {zeroRecords: "No Video Record Found"},
                        lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
                        columnDefs: [
                            {
                                "targets": [ 7 ],
                                "visible": false
                            },
                            {
                                "targets": [ 0,1,2,3,4,5,6 ],
                                "visible": true,
                            },
                            {
                                "targets": [ 2,3,4 ],
                                "searchable": true,
                                "orderable": true
                            },
                            {
                                "targets": [ 0,1,5,6,7 ],
                                "searchable": false,
                                "orderable": false
                            },
                            { className: "dt-center", "targets": [ 0,1,2,3,4,5,6,7] }
                        ],//[SELECT],Category,Title,Description,Comedian,VideoStatus,[Preview],VideoCode
                        columns: [
                            { width: "5%", },//SELECT
                            { width: "17%" },//CATEGORY
                            { width: "23%", },//VIDEO TITLE
                            { width: "26%", },//DESCRIPTION
                            { width: "15%" },//COMEDIAN
                            { width: "7%" },//VIDEO STATUS
                            { width: "5%" },//PREVIEW VIDEO
                            { width: "0%" }//VIDEO CODE
                        ],
                        order: [[ 1, 'asc' ],[ 2, 'asc' ]],
                        ajax: {
                            url: '<?php echo site_url('Editvideodetails/LoadAssignVideosJson'); ?>',
                            type: 'POST',
                            data: {category:category, status:status, InputBucket:InputBucket, ThumbBucket:ThumbBucket},
                            complete: function(xhr, textStatus) {
                                $.unblockUI();
                            },
                            error:  function(xhr,status,error) {
                                $.unblockUI();
                                m='Error '+ xhr.status + ' Occurred: ' + error;
                                bootstrap_Assignalert.warning(m);
                                bootbox.alert({
                                    size: 'small', message: m, title:Title,
                                    buttons: { ok: { label: "Close", className: "btn-danger" } },
                                    callback:function(){
                                        setTimeout(function() {
                                            $('#divAssignAlert').fadeOut('fast');
                                        }, 10000);
                                    }
                                });
                            },
                            dataType: 'json'
                        }
                    } );

                    $.unblockUI();
                }catch(e)
                {
                    $.unblockUI();
                    m='LoadAssignVideos ERROR:\n'+e;

                    bootstrap_Assignalert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback:function(){
                            setTimeout(function() {
                                $('#divAssignAlert').fadeOut('fast');
                            }, 10000);
                        }
                    });
                }

            }//LoadAssignVideos

            $('#btnDisplay').click(function(e) {
                try
                {
                    $('#divAlert').html('');

                    var sta=$('#cboStatus').val();
                    var cat=$('#cboCategory').val();

                    if ($('#cboCategory > option').length < 2)
                    {
                        m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';

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

                        $('#cboCategory').focus(); return false;
                    }

                    if (!cat)
                    {
                        m='Please select a category.';

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

                        $('#cboCategory').focus(); return false;
                    }

                    if (!sta)
                    {
                        m='Please select the status of the videos you want to display.';

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

                        $('#cboStatus').focus(); return false;
                    }

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Loading Videos. Please Wait...</p>',theme: true,baseZ: 2000});

                    //Display videos
                    LoadVideos(cat,sta);
                }catch(e)
                {
                    $.unblockUI();
                    m='Display Video Button Click ERROR:\n'+e;

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

            $('#btnUpdate').click(function(e) {
                try
                {
                    var tit=$.trim($('#txtTitle').val());
                    var desc=$.trim($('#txtDescription').val());
                    var com=$.trim($('#cboComedians').val());
                    var cat=$('#cboCategory').val();
                    var cd=$('#hidVideoCode').val();
                    var sta=$('#cboStatus').val();
                    var feature=$('#cboFeature').val();

                    if ($('#cboCategory > option').length < 2)
                    {
                        m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';

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

                        $('#cboCategory').focus(); return false;
                    }

                    if (!cat)
                    {
                        m='No category is selected. Update cannot continue.';

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

                        $('#cboCategory').focus(); return false;
                    }

                    if (!cd)
                    {
                        m='Error uploading the selected video. Cannot detect video code. Please refresh the page and start the update process afresh.';

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

                    //Video Title
                    if (!tit)
                    {
                        m='Please enter a valid video title.';

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
                    }

                    if ($.isNumeric(tit))
                    {
                        m='Video title field must not be a number.';

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
                    }

                    //Description
                    if (desc)
                    {
                        if ($.isNumeric(desc))
                        {
                            m='Video description field must not be a number.';

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

                            $('#txtDescription').focus(); return false;
                        }
                    }

                    //Comedians
                    if ($('#cboComedians > option').length < 2)
                    {
                        m='No comedian record has been captured into the database. Please contact the system administrator at support@laffhub.com';

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

                        $('#cboComedians').focus(); return false;
                    }

                    if (!com)
                    {
                        m='No comedian is selected. Update cannot continue.';

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

                        $('#cboComedians').focus(); return false;
                    }

                    //Confirm Upload
                    if (!confirm('Updating the selected video record will permanently modify the record and it is irreversible. Do you want to proceed with the updating of the video?  Click "OK" to proceed or "CANCEL" to abort!'))
                    {
                        return false;
                    }

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Updating Video Details. Please Wait...</p>',theme: true,baseZ: 2000});

                    var mydata={category:cat, video_title:tit,description:desc,comedian:com,video_code:cd, featured:feature};

                    $.ajax({
                        url: "<?php echo site_url('Editvideodetails/UpdateDetails');?>",
                        data: mydata,
                        type: 'POST',
                        dataType: 'text',
                        complete: function(xhr, textStatus) {
                            $.unblockUI;
                        },
                        success: function(data,status,xhr) {
                            $.unblockUI();

                            if ($.trim(data)=='OK')
                            {
                                $.unblockUI();

                                $('#txtTitle').val('');
                                $('#txtDescription').val('');
                                $('#cboComedians').val('');

                                if (document.getElementById('btnUpdateVideo')) document.getElementById('btnUpdateVideo').disabled=true;

                                LoadVideos(cat,sta);

                                m='Video "'+tit.toUpperCase()+'" Was Updated Successfully.';

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
                }catch(e)
                {
                    $.unblockUI();
                    m='Update Video Button Click ERROR:\n'+e;

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

            function GetSelectedVideos()
            {
                try
                {
                    var data = assigntable.rows().nodes();
                    var cd='';

                    $.each(data, function (index, value)
                    {
                        if ($(this).find('input').prop('checked'))
                        {
                            if (cd=='')
                            {
                                cd=$(this).find('input').attr('data-videocode');
                            }else
                            {
                                cd += ','+$(this).find('input').attr('data-videocode');
                            }
                        }
                    });

                    return cd;
                }catch(e)
                {
                    $.unblockUI();
                    m='GetSelectedVideos ERROR:\n'+e;

                    bootstrap_Assignalert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback:function(){
                            setTimeout(function() {
                                $('#divAssignAlert').fadeOut('fast');
                            }, 10000);
                        }
                    });

                    return '';
                }
            }

            $('#btnAssign').click(function(e) {
                try
                {
                    var com=$.trim($('#cboAssignComedians').val());
                    var cat=$('#cboAssignCategory').val();
                    var cd=GetSelectedVideos();
                    var sta=$('#cboAssignStatus').val();

                    if ($('#cboAssignCategory > option').length < 2)
                    {
                        m='No video category record has been captured into the database. Please contact the system administrator at support@laffhub.com';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignCategory').focus(); return false;
                    }

                    if (!cat)
                    {
                        m='No category is selected. Update cannot continue.';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignCategory').focus(); return false;
                    }

                    if (!cd)
                    {
                        m='No video has been selected. You must select at least a video.';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        return false;
                    }

                    //Comedians
                    if ($('#cboAssignComedians > option').length < 2)
                    {
                        m='No comedian record has been captured into the database. Please contact the system administrator at support@laffhub.com';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignComedians').focus(); return false;
                    }

                    if (!com)
                    {
                        m='No comedian is selected. Update cannot continue.';

                        bootstrap_Assignalert.warning(m);
                        bootbox.alert({
                            size: 'small', message: m, title:Title,
                            buttons: { ok: { label: "Close", className: "btn-danger" } },
                            callback:function(){
                                setTimeout(function() {
                                    $('#divAssignAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });

                        $('#cboAssignComedians').focus(); return false;
                    }

                    //Confirm
                    if (!confirm('Assigning comedians to the selected videos will permanently modify the record and it is irreversible. Do you want to proceed with the assigning of the comedians?  Click "OK" to proceed or "CANCEL" to abort!'))
                    {
                        return false;
                    }

                    $.blockUI({message: '<img src="<?php echo base_url();?>images/loader.gif" /><p>Assigning Comedian To Videos. Please Wait...</p>',theme: true,baseZ: 2000});

                    var mydata={category:cat, comedian:com,video_code:cd};

                    $.ajax({
                        url: "<?php echo site_url('Editvideodetails/AssignComedian');?>",
                        data: mydata,
                        type: 'POST',
                        dataType: 'text',
                        complete: function(xhr, textStatus) {
                            $.unblockUI;
                        },
                        success: function(data,status,xhr) {
                            $.unblockUI();

                            if ($.trim(data)=='OK')
                            {
                                $.unblockUI();

                                $('#cboAssignComedians').val('');

                                if (document.getElementById('btnAssign')) document.getElementById('btnAssign').disabled=true;

                                LoadAssignVideos(cat,sta);

                                m='Comedian Assignment Was Done Successfully.';

                                bootstrap_Success_Assignalert.warning(m);
                                bootbox.alert({
                                    size: 'small', message: m, title:Title,
                                    buttons: { ok: { label: "Close", className: "btn-danger" } },
                                    callback:function(){
                                        setTimeout(function() {
                                            $('#divAssignAlert').fadeOut('fast');
                                        }, 10000);
                                    }
                                });
                            }else
                            {
                                $.unblockUI();

                                m=data;

                                bootstrap_Assignalert.warning(m);
                                bootbox.alert({
                                    size: 'small', message: m, title:Title,
                                    buttons: { ok: { label: "Close", className: "btn-danger" } },
                                    callback:function(){
                                        setTimeout(function() {
                                            $('#divAssignAlert').fadeOut('fast');
                                        }, 10000);
                                    }
                                });
                            }
                        },
                        error:  function(xhr,status,error) {
                            $.unblockUI();

                            m='Error '+ xhr.status + ' Occurred: ' + error;
                            bootstrap_Assignalert.warning(m);
                            bootbox.alert({
                                size: 'small', message: m, title:Title,
                                buttons: { ok: { label: "Close", className: "btn-danger" } },
                                callback:function(){
                                    setTimeout(function() {
                                        $('#divAssignAlert').fadeOut('fast');
                                    }, 10000);
                                }
                            });
                        }
                    });
                }catch(e)
                {
                    $.unblockUI();
                    m='Assign Comedian Button Click ERROR:\n'+e;

                    bootstrap_Assignalert.warning(m);
                    bootbox.alert({
                        size: 'small', message: m, title:Title,
                        buttons: { ok: { label: "Close", className: "btn-danger" } },
                        callback:function(){
                            setTimeout(function() {
                                $('#divAssignAlert').fadeOut('fast');
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
                $('#hidVideoCode').val('');
                $('#hidFilename').val('');

                $('#cboComedians').val('');
                $('#cboCategory').val('');
                $('#cboStatus').val('');
                $('#txtTitle').val('');
                $('#txtDescription').val('');
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

        function GetRow(sn)
        {
            $('#hidVideoCode').val('');
            $('#hidFilename').val('');

            $('#cboComedians').val('');
            $('#txtTitle').val('');
            $('#txtDescription').val('');

            if (sn>-1)
            {
                var row = table.row( sn ).data();

                if (row)
                {
                    var cat=row[1],tit=row[2],des=row[3],com=row[4],sta=row[5],fn=row[7],cd=row[8], ft=row[9] ;

                    $('#hidFilename').val(fn);
                    $('#hidVideoCode').val(cd);

                    $('#txtTitle').val(tit);
                    $('#txtDescription').val(des);
                    $('#cboComedians').val(com);
                    $('#cboFeature').val(ft);

                    $('#btnUpdate').prop('disabled',false);
                }
            }
        }

        function GetAssignRow(sn)
        {
            $('#hidAssignVideoCode').val('');
            $('#cboAssignComedians').val('');

            if (sn>-1)
            {
                var row = assigntable.row(sn).data();

                if (row)
                {
                    var cat=row[1],tit=row[2],des=row[3],com=row[4],sta=row[5],cd=row[7];

                    $('#hidAssignVideoCode').val(cd);
                    $('#cboAssignComedians').val(com);

                    $('#btnAssign').prop('disabled',false);
                }
            }
        }

        function ShowVideo(preview_url,title,ext,preview_img)
        {
            //alert(preview_url+"."+ext);

            jwplayer.key='<?php echo $jwplayer_key; ?>';
            //jwplayer.key='';

            jwplayer("idModalBody").setup({
                image: preview_img,
                file: preview_url+"."+ext,
                width: "100%",
                stretching: "uniform",
                aspectratio: "16:9"
            });

            //width: "640",
            //height: "360",

            $('#idModalTitle').html(title);
            //$('#idModalBody').html(src);
            $('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
        }

        function ShowVideo_Encoded(preview_url,title,ext,preview_img)
        {
            //alert(preview_url+"_360p."+ext+'\n\n'+ext);
            //fn.'_360p.'.$ext
            jwplayer.key='<?php echo $jwplayer_key; ?>';

            jwplayer("idModalBody").setup({
                image: preview_img,
                sources: [
                    { file: preview_url+"_360p."+ext, label: "360p SD" },
                    { file: preview_url+"_720p."+ext, label: "720p SD" },
                    { file: preview_url+"_1080p."+ext, label: "1080p HD" }
                ],
                width: "100%",
                stretching: "uniform",
                aspectratio: "16:9"//4:3
            });



            //alert(src);
            $('#idModalTitle').html(title);
            //$('#idModalBody').html(src);
            $('#divVideoModal').modal({backdrop:'static',keyboard:false, show:true});
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
                        <div class="panel-heading size-20"><i class="fa fa-pencil-square"></i> Edit Video Details</div>
                        <div class="panel-body">
                            <div class="box-body">
                                <!--Tab-->
                                <ul class="nav nav-tabs " style="font-weight:bold;">
                                    <li class="active"><a data-toggle="tab" href="#tabDetails"><i class="glyphicon glyphicon-pencil"></i> Edit Video Details</a></li>
                                    <li><a data-toggle="tab" href="#tabAssign"><i class="fa fa-adn"></i> Assign Comedian To Videos</a></li>
                                </ul>
                                <!--Tab Ends-->

                                <div class="tab-content">
                                    <div id="tabDetails" class="row tab-pane fade in active ">
                                        <p align="center" class="size-12 nobold" style="font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; margin-top:5px; ">Fields With <span class="redtext">*</span> Are Required!</p>
                                        <form class="form-horizontal">
                                            <!--Video category/Video Status-->
                                            <div class="form-group">
                                                <!--Video category-->
                                                <label class="col-sm-2 control-label" for="cboCategory" title="Select Video category">Video category<span class="redtext">*</span></label>

                                                <div class="col-sm-4" title="Select Video category">
                                                    <select id="cboCategory" class="form-control" style="padding:3px;"></select>
                                                </div>

                                                <!--Video Status-->
                                                <label class="col-sm-2 control-label" for="cboStatus" title="Select Video Status">Video Status<span class="redtext">*</span></label>

                                                <div class="col-sm-2" title="Select Video Play Status">
                                                    <select id="cboStatus" class="form-control" style="padding:3px;">
                                                        <option value="">[SELECT]</option>
                                                        <option value="1">Activated</option>
                                                        <option value="0">Not Activated</option>
                                                    </select>
                                                </div>

                                                <span><!--Refresh Button-->
                                                    <button style="width:140px; float:right; margin-right:20px;" id="btnDisplay" type="button" class="btn btn-info"><span class="glyphicon glyphicon-play-circle" ></span> Load Videos</button>
                                                 </span>

                                                <input type="hidden" id="hidFilename">
                                                <input type="hidden" id="hidVideoCode">
                                            </div>

                                            <div class="form-group">
                                                <!--Video Title-->
                                                <label class="col-sm-2 control-label" for="txtTitle" title="Enter Video Title">Video Title<span class="redtext">*</span></label>

                                                <div class="col-sm-8" title="Enter Video Title">
                                                    <input type="text" placeholder="Video Title" id="txtTitle" class="form-control">
                                                </div>

                                                <span><!--Update Button-->
                                                    <button title="Click This Button To Update Video Details" style="width:140px; float:right; margin-right:20px;" id="btnUpdate" type="button" class="btn btn-primary"><i class="glyphicon glyphicon-check"></i> Update Details
                                </button>
                                                 </span>
                                            </div>

                                            <div class="form-group">
                                                <!--Video Description-->
                                                <label class="col-sm-2 control-label" for="txtDescription" title="Enter Video Description">Video Description</label>

                                                <div class="col-sm-8" title="Enter Video Description">
                                                    <textarea rows="2" id="txtDescription" class="form-control" placeholder="Video Description"></textarea>
                                                </div>

                                                <span><!--Refresh Button-->
                                                    <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-danger" role="button" style="width:140px; float:right; margin-right:20px;"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
                                                 </span>
                                            </div>

                                            <div class="form-group">
                                                <!--Comedian-->
                                                <label class="col-sm-2 control-label" for="cboComedians" title="Select Comedian">Comedian<span class="redtext">*</span></label>

                                                <div class="col-sm-4" title="Select Comedian">
                                                    <select id="cboComedians" class="form-control" style="padding:3px;"></select>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <!--Feature-->
                                                <label class="col-sm-2 control-label" for="cboFeature" title="Feature Video">Feature<span class="redtext">*</span></label>

                                                <div class="col-sm-2" title="Feature Video">
                                                    <select id="cboFeature" class="form-control" style="padding:3px;"></select>
                                                </div>
                                            </div>

                                        </form>

                                        <div align="center" style="margin-top:10px;" id = "divAlert"></div>
                                        <center>
                                            <div class="table-responsive">
                                                <table align="center" id="recorddisplay" cellspacing="0" title="Video Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                                                    <thead style="color:#ffffff; background-color:#7E7B7B;">
                                                    <tr>
                                                        <th>SELECT</th>
                                                        <th>CATEGORY</th>
                                                        <th>TITLE</th>
                                                        <th>DESCRIPTION</th>
                                                        <th>COMEDIAN</th>
                                                        <th>STATUS</th>
                                                        <th>PREVIEW</th><!--Preview-->
                                                        <th class="hide">FILENAME</th>
                                                        <th class="hide">VIDEOCODE</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </center>
                                    </div><!--End tabDetails-->

                                    <div id="tabAssign" class="tab-pane fade">
                                        <p align="center" class="size-12 nobold" style="font-style:italic; font-family:Segoe, 'Segoe UI', 'DejaVu Sans', 'Trebuchet MS', Verdana, sans-serif; margin-top:5px; ">Fields With <span class="redtext">*</span> Are Required!</p>
                                        <form class="form-horizontal">
                                            <!--Video category/Video Status-->
                                            <div class="form-group">
                                                <!--Video category-->
                                                <label class="col-sm-2 control-label" for="cboAssignCategory" title="Select Video category">Video category<span class="redtext">*</span></label>

                                                <div class="col-sm-4" title="Select Video category">
                                                    <select id="cboAssignCategory" class="form-control" style="padding:3px;"></select>
                                                </div>

                                                <!--Video Status-->
                                                <label class="col-sm-2 control-label" for="cboAssignStatus" title="Select Video Status">Video Status<span class="redtext">*</span></label>

                                                <div class="col-sm-2" title="Select Video Play Status">
                                                    <select id="cboAssignStatus" class="form-control" style="padding:3px;">
                                                        <option value="">[SELECT]</option>
                                                        <option value="1">Activated</option>
                                                        <option value="0">Not Activated</option>
                                                    </select>
                                                </div>

                                                <input type="hidden" id="hidAssignVideoCode">
                                            </div>

                                            <div class="form-group">
                                                <!--Comedian-->
                                                <label class="col-sm-2 control-label" for="cboAssignComedians" title="Select Comedian">Comedian<span class="redtext">*</span></label>

                                                <div class="col-sm-4" title="Select Comedian">
                                                    <select id="cboAssignComedians" class="form-control" style="padding:3px;"></select>
                                                </div>

                                                <div class="col-sm-6">
                                                    <button style="width:130px; " id="btnAssignDisplay" type="button" class="btn btn-info"><span class="glyphicon glyphicon-play-circle" ></span> Load Videos</button>

                                                    <button title="Click This Button To Assign Comedian To Selected Video(s)" style="width:150px; margin-left:10px;" id="btnAssign" type="button" class="btn btn-primary"><i class="fa fa-adn"></i> Assign Comedian</button>

                                                    <button  onClick="window.location.reload(true);" title="Refresh Form" id="btnAssignRefresh" type="button" class="btn btn-danger" role="button" style="width:130px; margin-left:10px;"><i class="glyphicon glyphicon-refresh"></i> Refresh</button>
                                                </div>
                                            </div>

                                            <!--Buttons-->
                                            <div class="form-group">

                                                <div class="col-sm-offset-2">

                                                </div>
                                            </div>
                                        </form>

                                        <div align="center" style="margin-top:10px;" id = "divAssignAlert"></div>
                                        <center>
                                            <div class="table-responsive">
                                                <table align="center" id="assignrecorddisplay" cellspacing="0" title="Video Records" class="hover display table table-bordered stripe table-condensed"  style="border:solid; width:99.5%;">
                                                    <thead style="color:#ffffff; background-color:#7E7B7B;">
                                                    <tr>
                                                        <th>SELECT</th>
                                                        <th>CATEGORY</th>
                                                        <th>TITLE</th>
                                                        <th>DESCRIPTION</th>
                                                        <th>COMEDIAN</th>
                                                        <th>STATUS</th>
                                                        <th>PREVIEW</th><!--Preview-->
                                                        <th class="hide">VIDEOCODE</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </center>
                                    </div><!--End tabAssign-->
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
<!--<script src="<?php# echo base_url();?>js/raphael-min.js"></script>-->
<!--<script src="<?php# #echo base_url();?>js/morris.min.js"></script>-->
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

<!--******************************* Video Preview modal **************************************************-->
<div class="modal fade" id="divVideoModal" tabindex="-1" role="dialog" aria-labelledby="modal-register-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button id="btnCloseVideoModal" type="button" class="close" data-dismiss="modal" title="Click To Close The Popup Screen">
                    <span aria-hidden="true" style="font-size:xx-large;">&times;</span><span class="sr-only">Close</span>
                </button>

                <h3  id="idModalTitle"align="center" class=" makebold label-primary pad-3 modal-title" style="width:100%;"></h3>
            </div>

            <div class="register-box form-bottom modal-body" style="width:100%; margin-top:-30px;">
                <div id="idModalBody" class="register-box-body"></div></div>

        </div><!--End modal-content-->
    </div><!--End modal-dialogue-->
</div>
<!--******************************* End Preview Video modal **********************************************-->
</body>
</html>

