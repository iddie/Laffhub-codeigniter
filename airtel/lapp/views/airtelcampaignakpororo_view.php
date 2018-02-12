<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaffHub::Service Subscription</title>
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
</head>
<body class="page">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WNPD6HC"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
                        <span class="size-18 makebold"><i class="fa fa-volume-control-phone"></i> Service Subscription </span>
                    </div>

                    <div class="panel-body">

                        <div class="tab-content">
                            <div id="tabData" class="row tab-pane fade in active ">

                                <form class="form-horizontal">

                                    <br>
                                    <div class="img-responsive">
                                        <img src="<?php echo base_url(); ?>acss/images/landingbanner.png" class="img-responsive" id="imgSubscribe"/>
                                    </div>
                                    <br>

                                    <!--<div class="form-group">-->
                                    <!--    <label for="lblSubscriptionId" class="col-sm-2 control-label" title="Subscription ID">Subscription ID</label>-->

                                    <div class="col-sm-3" title="Subscription ID" >
                                        <input type="hidden" style="background-color:#C5522D; color:#ffffff;" id="lblSubscriptionId" class="form-control" value="<?php echo $subscriptionId; ?>">
                                    </div>
                            </div>

                            <div align="center">
                                <div id = "divAlert"></div>
                            </div>

                            <div>
                                <div class="form-group" style="margin-top:30px;">
                                    <div class="col-sm-offset-2 col-sm-7">
                                        <button title="Add Subscription" id="buttonSubscribe" type="button" class="btn btn-success" role="button" style="text-align:center;"><i class="fa fa-credit-card-alt"></i> Continue</button>


                                        <!--<button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="btn btn-info" role="button" style="width:120px;  margin-left:10px;" ><i class="fa fa-refresh"></i> Refresh</button>-->
                                    </div>
                                </div>
                            </div>

                            <!--Network/Phone Number-->
                            <div class="form-group">
                                <div class="col-sm-3" title="<?php echo $Network; ?>">
                                    <input type="hidden" style="text-transform:none; color:#EC1D22;" class="form-control" id="lblNetwork" value="<?php echo $Network; ?>">
                                </div>

                                <!--Phone Number-->
                                <div class="col-sm-3" title="Subscriber Phone Number" >
                                    <input type="hidden" id="lblPhone" class="form-control nobold" title="Phone Number" value="<?php echo $Phone; ?>">
                                </div>
                            </div>


                            <!--Service Plan Duration/Service Plan-->
                            <div class="form-group">
                                <!--Service Plan-->
                                <div class="col-sm-3" title="Service Plan" >
                                    <input type="hidden" id="cboPlan" class="form-control">
                                </div>

                                <!--Service Plan Duration-->
                                <div class="col-sm-3" title="Service Plan Duration">
                                    <input type="hidden" class="form-control nobold" id="lblDuration">
                                </div>
                            </div>


                            <!--No Of Videos/Amount-->
                            <div class="form-group">
                                <!--No Of Videos-->

                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control nobold" id="lblVideoCount" title="No Of Videos To Watch">
                                </div>

                                <!--Amount-->
                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control nobold" id="lblAmount" title="Subscription Amount">
                                </div>
                            </div>

                            <!--Subscription Date/Expiry Date-->
                            <div class="form-group">
                                <!--Subscription Date-->
                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control nobold" id="lblSubscriptionDate" title="Subscription Date">
                                </div>

                                <!--Expiry Date-->
                                <div class="col-sm-3">
                                    <input type="hidden" class="form-control nobold" id="lblExpiryDate" title="Subscription Expiry Date">
                                </div>
                            </div>

                            <!--Enable Auto-Billing/Email-->
                            <div class="form-group">

                                <!--Enable Auto-Billing-->
                                <div class="col-sm-3" title="Enable Auto-Billing">
                                    <div class="col-sm-3">
                                        <input type="hidden" class="form-control nobold" id="cboAutoBilling" title="Subscription Expiry Date" value="1">
                                    </div>

                                </div>
                            </div>

                            <!--Subscription ID-->
                            </form>
                            <br>
                        </div><!--End Of Tab Content 1-->
                    </div>

                </div>
            </div>
    </div>
    </main>
    <!--/MAIN-->

    <!--FOOTER-->
    <?php include('newuserfooter.php'); ?>
    <!--/FOOTER-->
</div>
</div>

 <script src="<?php echo base_url();?>js/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>


    <script src="<?php echo base_url();?>js/bootbox.min.js"></script>
    <script src="<?php echo base_url();?>js/general.js"></script>
    <script src="<?php echo base_url();?>js/modernAlert.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
    <script src="<?php echo base_url();?>js/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109268177-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-109268177-2');
    </script>

    <!-- Hotjar Tracking Code for www.laffhub.com -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:694882,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WNPD6HC');</script>
    <!-- End Google Tag Manager -->

    <script>
        (function($){

            var SubscriberEmail="<?php echo $subscriber_email; ?>";
            var SubscriptionDate="<?php echo $subscribe_date; ?>";
            var ExpiryDate="<?php echo $exp_date; ?>";
            var SubscriptionStatus='<?php echo $subscriptionstatus; ?>';
            var Network='<?php echo $Network; ?>';
            var Phone='<?php echo $Phone; ?>';
            var Email='<?php echo $subscriber_email; ?>';
            var SubscriptionId='<?php echo strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10)); ?>';

            var Title='<font color="#AF4442">Service Subscription Help</font>';
            var m='';
            var table;
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

            $(document).ready(function(e)
            {
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

                $('#lblSubscriptionId').val(SubscriptionId);

                var pickerstart = new Pikaday(
                    {
                        field: document.getElementById('txtStartDate'),
                        firstDay: 0,//first day of the week (0: Sunday, 1: Monday, etc)
                        showDaysInNextAndPreviousMonths: true,
                        enableSelectionDaysInNextAndPreviousMonths: true,
                        //minDate: new Date(),
                        //maxDate: new Date(2020, 12, 31),
                        format: 'DD MMM YYYY',
                        onSelect: function() {
                            //alert(this.getMoment().format('Do MMMM YYYY'));//6th September 2017
                        }
                        //yearRange: [2000,2020]
                    });

                var pickerend = new Pikaday(
                    {
                        field: document.getElementById('txtEndDate'),
                        firstDay: 0,//first day of the week (0: Sunday, 1: Monday, etc)
                        showDaysInNextAndPreviousMonths: true,
                        enableSelectionDaysInNextAndPreviousMonths: true,
                        //minDate: new Date(),
                        //maxDate: new Date(2020, 12, 31),
                        format: 'DD MMM YYYY',
                        onSelect: function() {
                            //alert(this.getMoment().format('Do MMMM YYYY'));//6th September 2017
                        }
                        //yearRange: [2000,2020]
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
                        m="Start Date Changed ERROR:\n"+e;
                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
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
                        m="End Date Changed ERROR:\n"+e;
                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
                });

                function VerifyStartAndEndDates()
                {
                    try
                    {
                        $('#divAlert').html('');

                        var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
                        var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
                        //var pdt = moment(startdt), ddt = moment(enddt);
                        var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));


                        if (!pdt.isValid())
                        {
                            m="Subscription Start Date Is Not Valid. Please Select A Valid Subscription Start Date";
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);
                        }

                        if (!ddt.isValid())
                        {
                            m="Subscription End Date Is Not Valid. Please Select A Valid Subscription End Date";

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);
                        }

                        //moment('2010-10-20').isSameOrBefore('2010-10-21');  // true

                        var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
                        //var diff = moment.duration(ddt.diff(pdt));

                        if (dys<0)
                        {
                            $('#txtEndDate').val('');

                            m="Subscription End Date Is Before Subscription Start Date. Please Correct Your Entries!";

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);
                        }
                    }catch(e)
                    {
                        m="VerifyStartAndEndDates ERROR:\n"+e;
                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
                }

                LoadPlans(Network);

                function LoadPlans(network)
                {
                    var plan;

                    if(Network == 'Airtel') {
                        plan = $('#cboPlan').val("Daily");
                    }

                    if( plan.val() === 'Daily') {
                        $('#lblDuration').html('');
                        $('#lblVideoCount').html('');
                        $('#lblAmount').html('');
                        $('#lblSubscriptionDate').html('');
                        $('#lblExpiryDate').html('');

                        document.getElementById('buttonSubscribe').disabled=true;

                        var nt= $('#lblNetwork').val();

                        var pl= $('#cboPlan').val();

                        if (nt && pl) LoadPlanDetails(nt,pl);
                    }
                }

                function LoadPlanDetails(network,plan)
                {
                    try
                    {
                        $('#lblDuration').val('');
                        $('#lblVideoCount').html('');
                        $('#lblAmount').html('');
                        $('#lblSubscriptionDate').html('');
                        $('#lblExpiryDate').html('');

                        //$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Loading Plan Details. Please Wait...</b></p>',theme: true,baseZ: 2000});

                        $.msg(
                            {
                                autoUnblock : false ,
                                clickUnblock : false,
                                klass : 'airel-custom-theme',
                                bgPath : '<?php echo base_url();?>images/',
                                content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Loading Plan Details. Please Wait...</b></p></center>'
                            }
                        );

                        var mydata={network:network,plan:plan};

                        $.ajax({
                            url: '<?php echo site_url('AirtelCampaignFlogMe/LoadPlanDetails'); ?>',
                            type: 'POST',
                            data:{network:network,plan:plan},
                            dataType: 'json',
                            success: function(data,status,xhr) {
                                $.msg('unblock');

                                if ($(data).length > 0)
                                {

                                    $.each($(data), function(i,e)
                                    {
                                        if (e.amount) $('#lblAmount').val($.trim(e.amount));
                                        if (e.duration)
                                        {
                                            $('#lblDuration').val($.trim(e.duration));

                                            var subdate='<?php echo date('d M Y'); ?>';
                                            var sdt=ChangeDateFrom_dMY_To_Ymd(subdate,'-',' ');
                                            var expdate=moment(sdt.replace(new RegExp('-', 'g'), '/')).add(parseInt(e.duration,10), 'days').format("DD MMM YYYY");

                                            $('#lblSubscriptionDate').val(subdate);
                                            $('#lblExpiryDate').val(expdate);
                                        }

                                        if (e.no_of_videos) $('#lblVideoCount').val($.trim(e.no_of_videos));



                                        //var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');

                                        return false;
                                    });

                                    document.getElementById('buttonSubscribe').disabled=false;
                                }
                            },
                            error:  function(xhr,status,error) {
                                $.msg('unblock');

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

                        //$.msg('unblock');
                    }catch(e)
                    {
                        $.msg('unblock');
                        m="LoadPlanDetails Module ERROR:\n"+e;
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

                $('#btnDisplay').click(function(e) {
                    try
                    {
                        $('#divAlert').html('');

                        if (!Validate()) return false;

                        var sdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
                        var edt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
                        var ph=$('#lblPhone').html();
                        var nt=$('#lblNetwork').html();

                        DisplayHistory(ph,sdt,edt,nt);
                    }catch(e)
                    {
                        var m='Display Subscription History Button Click ERROR:\n'+e;

                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
                });//btnDisplay.click

                function Validate()
                {
                    try
                    {
                        var nt=$('#lblNetwork').val();
                        var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
                        var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
                        var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
                        var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));
                        var p=$.trim($('#txtStartDate').val());
                        var d=$.trim($('#txtEndDate').val());

                        //Network
                        if (!nt)
                        {
                            m='Network has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            return false;
                        }

                        //Start date Not Select. End Date Selected
                        if (!p)
                        {
                            m='You have not selected the report start date.';

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);

                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            return false;
                        }

                        if (!d)
                        {
                            m='You have not selected the report end date.';

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);

                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            return false;
                        }

                        if (!p && d)
                        {
                            m='You have selected the report end date. Report start date field must also be selected.';

                            alert(m, 'LaffHub Message');

                            bootstrap_alert.warning(m);

                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            return false;
                        }

                        //End date Not Select. Start Date Selected
                        if (p && !d)
                        {
                            m='You have selected the report start date. Report end date field must also be selected.';

                            alert(m, 'LaffHub Message');

                            bootstrap_alert.warning(m);

                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            return false;
                        }

                        if (p)
                        {
                            if (!pdt.isValid())
                            {
                                m="Report Start Date Is Not Valid. Please Select A Valid Report Start Date";
                                alert(m, 'LaffHub Message');
                                bootstrap_alert.warning(m);

                                setTimeout(function() {
                                    $('#divAlert').fadeOut('fast');
                                }, 10000);

                                return false;
                            }
                        }

                        if (d)
                        {
                            if (!ddt.isValid())
                            {
                                m="Report End Date Is Not Valid. Please Select A Valid Report End Date";
                                alert(m, 'LaffHub Message');
                                bootstrap_alert.warning(m);

                                setTimeout(function() {
                                    $('#divAlert').fadeOut('fast');
                                }, 10000);

                                return false;
                            }
                        }


                        if (p && d)
                        {
                            var dys=ddt.diff(pdt, 'days') //If this -ve invalid date entries. Delivery earlier than pickup
                            var diff = moment.duration(ddt.diff(pdt));

                            if (dys<0)
                            {
                                m="Report End Date Is Before The Start Date. Please Correct Your Entries!";
                                alert(m, 'LaffHub Message');
                                bootstrap_alert.warning(m);

                                setTimeout(function() {
                                    $('#divAlert').fadeOut('fast');
                                }, 10000);

                                return false;
                            }
                        }

                        return true;
                    }catch(e)
                    {
                        m='VALIDATE ERROR:\n'+e;

                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);

                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);

                        return false;
                    }
                }

                function DisplayHistory(msisdn,sdt,edt,network)
                {
                    var self;

                    try
                    {
                        //$.blockUI({message: '<img src="<?php# echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px;"><b>Retrieving Subscription History. Please Wait...</b></p>',theme: true,baseZ: 2000});

                        $.msg(
                            {
                                autoUnblock : false ,
                                clickUnblock : false,
                                afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
                                klass : 'airel-custom-theme',
                                bgPath : '<?php echo base_url();?>images/',
                                content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Retrieving Subscription History. Please Wait...</b></p></center>'
                            }
                        );

                        var startdt=ChangeDateFrom_dMY_To_Ymd($('#txtStartDate').val(),'-',' ');
                        var enddt=ChangeDateFrom_dMY_To_Ymd($('#txtEndDate').val(),'-',' ');
                        //var pdt = moment(startdt), ddt = moment(enddt);
                        var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/')), ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));

                        if (!pdt.isValid()) sdt='';
                        if (!ddt.isValid()) edt='';

                        //Make Ajax Request
                        var msg;

                        msg='Subscription Report';

                        if (sdt && edt)
                        {
                            if (sdt == edt)
                            {
                                msg = msg + ' For '+ $('#txtStartDate').val();
                            }else
                            {
                                msg = msg + ' Between '+ $('#txtStartDate').val() + ' And ' + $('#txtEndDate').val();
                            }
                        }

                        var mydata={msisdn:msisdn,startdate:sdt,enddate:edt,network:network};

                        $.ajax({
                            url: "<?php echo site_url('AirtelCampaignFlogMe/LoadSubscriptionHistory'); ?>",
                            data: mydata,
                            type: 'POST',
                            dataType: 'json',
                            beforeSend: function(){
                                //if (table) table.destroy();
                            },
                            complete: function(xhr, textStatus) {
                                $.msg('unblock');

                                activateTab('tabReport');
                            },
                            success: function(dataSet,status,xhr) {
                                $.msg('unblock');

                                if (table) table.destroy();

                                table = $('#recorddisplay').DataTable( {
                                    dom: 'B<"top"if>rt<"bottom"lp><"clear">',
                                    autoWidth:false,
                                    destroy:true,
                                    lengthMenu: [ [ 10, 25, 50, 100,-1 ],[ '10', '25', '50', '100', 'All' ] ],
                                    language: {zeroRecords: "No Subscription History Record Found"},
                                    columnDefs: [
                                        {
                                            "targets": [ 0,1,2,3,4,5,6 ],
                                            "visible": true,
                                            "searchable": true
                                        },
                                        {
                                            "searchable": false,
                                            "orderable": false,
                                            "targets": 0
                                        },
                                        {
                                            "orderable": true,
                                            "targets": [ 1,2,3,4,5,6 ]
                                        },
                                        { className: "dt-center", "targets": [ 0,1,2,3,4,5,6 ] }
                                    ],
                                    order: [[ 1, 'asc' ]],
                                    data: dataSet,
                                    //SN,Network,Plan,Amount,SubscriptionDate,ExpiryDate,SubscriptionStatus
                                    columns: [
                                        { width: "7%" },//SN
                                        { width: "20%" },//Network
                                        { width: "14%" },//Plan
                                        { width: "14%" },//Amount
                                        { width: "15%" },//Subscription Date
                                        { width: "14%" },//Expiry Date
                                        { width: "16%" }//Subscription Status
                                    ],
                                } );

                                table.on( 'order.dt search.dt', function () {
                                    table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                                        cell.innerHTML = i+1;
                                    } );
                                } ).draw();

                            },
                            error:  function(xhr,status,error) {
                                $.msg('unblock');
                                m='Error '+ xhr.status + ' Occurred: ' + error;
                                alert(m, 'LaffHub Message');
                                bootstrap_alert.warning(m);
                                setTimeout(function() {
                                    $('#divAlert').fadeOut('fast');
                                }, 10000);
                            }
                        });


                    }catch(e)
                    {
                        $.msg('unblock');
                        m='DisplayHistory Module Button Click ERROR:\n'+e;

                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
                }//End DisplayHistory


                // Add event listener for opening and closing details
                $('#recorddisplay tbody').on('click', 'td', function () {
                    var tr = $(this).closest('tr');
                    var row = table.row( tr );
                    editdata = row.data();

                    var colIndex = $(this).index();

                    if (colIndex==0) SelectRow(editdata);
                } );

                $('#buttonSubscribe').click(function(e) {
                    try
                    {
                        checkForm();
                        fireEskimi();
                        
                    }catch(e)
                    {
                        var m='Subscribe Button Click ERROR:\n'+e;

                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);

                        return false;
                    }
                });//buttonSubscribe.click
                
             $('#imgSubscribe').click(function(e) {
                try
                {
                    checkForm();
                    fireEskimi();
                    
                }catch(e)
                {
                    var m='Subscribe Button Click ERROR:\n'+e;
    
                    alert(m, 'LaffHub Message');
                    bootstrap_alert.warning(m);
                    setTimeout(function() {
                        $('#divAlert').fadeOut('fast');
                    }, 10000);
    
                    return false;
                }
            });//imgSubscribe.click

                function Subscribe(input)
                {
                    var self;

                    if (input === true)
                    {
                        //Subscribe
                        $.msg(
                            {
                                autoUnblock : false ,
                                clickUnblock : false,
                                afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
                                klass : 'airel-custom-theme',
                                bgPath : '<?php echo base_url();?>images/',
                                content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Subscribing User. Please Wait...</b></p></center>'
                            }
                        );

                        //Make Ajax Request
                        var nt=$('#lblNetwork').val();
                        var ph=$('#lblPhone').val();
                        var em=$('#lblEmail').html();
                        var du=$('#lblDuration').val();
                        var sid=$('#lblSubscriptionId').val();
                        var pl=$('#cboPlan').val();
                        var au=$('#cboAutoBilling').val();
                        var vid=$('#lblVideoCount').val();
                        var amt=$('#lblAmount').val().replace(new RegExp(',', 'g'), '');
                        var sdt=ChangeDateFrom_dMY_To_Ymd($('#lblSubscriptionDate').val(),'-',' ');
                        var edt=ChangeDateFrom_dMY_To_Ymd($('#lblExpiryDate').val(),'-',' ');

                        //Initiate POST
                        var uri = "<?php echo site_url('AirtelCampaignAkpororo/SubscribeUser');?>";
                        var xhr = new XMLHttpRequest();
                        var fd = new FormData();

                        xhr.open("POST", uri, true);

                        xhr.onreadystatechange = function() {
                            //0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
                            if (xhr.readyState == 4 && xhr.status == 200)
                            {
                                // Handle response.
                                $.msg('unblock');

                                var res=$.trim(xhr.responseText);

                                if (res.toUpperCase()=='OK')
                                {
                                    m='You Have Successfully Subscribed To <b>'+nt.toUpperCase()+' '+pl.toUpperCase()+' Plan</b>.';

                                    bootstrap_Success_alert.warning(m);
                                    alert(m, 'LaffHub Message');
                                    setTimeout(function() {
                                        var url="<?php echo site_url('c-59f7061885a20442591229'); ?>";
                                        window.location.replace(url);
                                    }, 500);

                                }else
                                {
                                    m=res;
                                    bootstrap_alert.warning(m);
                                    alert(m, 'LaffHub Message');
                                    setTimeout(function() {
                                        $('#divAlert').fadeOut('fast');
                                    }, 10000);
                                }
                            }else
                            {
                                $.msg('unblock');
                            }
                        };


                        fd.append('network',nt);
                        fd.append('msisdn', ph);
                        fd.append('email', em);
                        fd.append('plan',pl);
                        fd.append('duration',du);
                        fd.append('amount', amt);
                        fd.append('subscribe_date',sdt);
                        fd.append('exp_date', edt);
                        fd.append('autobilling', au);
                        fd.append('videos_cnt_to_watch', vid);
                        fd.append('subscriptionId', sid);

                        xhr.send(fd);// Initiate a multipart/form-data upload
                    } else
                    {
                        $.msg('unblock');
                        m='Subscription Cancelled';
                        alert(m, 'LaffHub Message');
                        bootstrap_alert.warning(m);
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);
                    }
                }

                function checkForm()
                {
                    try
                    {
                        var nt=$('#lblNetwork').val();
                        var ph=$('#lblPhone').val();
                        var du=$('#lblDuration').val();
                        var sid=$('#lblSubscriptionId').val();
                        var pl=$('#cboPlan').val();
                        var vid=$('#lblVideoCount').val();
                        var amt=$('#lblAmount').val().replace(new RegExp(',', 'g'), '');

                        var startdt=ChangeDateFrom_dMY_To_Ymd($('#lblSubscriptionDate').val(),'-',' ');
                        var enddt=ChangeDateFrom_dMY_To_Ymd($('#lblExpiryDate').html(),'-',' ');
                        var pdt = moment(startdt.replace(new RegExp('-', 'g'), '/'));
                        var ddt = moment(enddt.replace(new RegExp('-', 'g'), '/'));

                        var s=$.trim($('#lblSubscriptionDate').val());
                        var e=$.trim($('#lblExpiryDate').val());

                        //Network
                        if (!nt)
                        {
                            m='Network has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            activateTab('tabData'); return false;
                        }

                        //Phone
                        if (!ph)
                        {
                            m='Subscriber phone and email have not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            activateTab('tabData'); return false;
                        }

                        //Subscription ID
                        if (!sid)
                        {
                            m='Subscriber ID has not been displayed. Please click on <b>Refresh</b> button to reload the subscription page. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            activateTab('tabData'); return false;
                        }

                        //Plan
                        if (pl !== 'Daily')
                        {
                            m='No service plan record was captured. Please contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            activateTab('tabData'); return false;
                        }

                        if (!du)
                        {
                            m='Service plan duration has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            $('#cboPlan').focus(); activateTab('tabData'); return false;
                        }

                        //No Of Videos
                        if (!vid)
                        {
                            m='Number of videos allowed for the service plan has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            $('#cboPlan').focus(); activateTab('tabData'); return false;
                        }

                        //Amount
                        if (!amt)
                        {
                            m='Service plan amount has not been displayed. Please make sure you have active internet connection. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';

                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            $('#cboPlan').focus(); activateTab('tabData'); return false;
                        }

                        //Subscription Date
                        if (!s)
                        {
                            m='Subscription date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            $('#cboPlan').focus(); activateTab('tabData'); return false;
                        }

                        //Expiry Date
                        if (!e)
                        {
                            m='Subscription expiry date has not been displayed. Please refresh your browser. You may also sign out and sign in again. If this persists, contact our support at <a href="mailto:support@laffhub.com">support@laffhub.com</a>.';
                            alert(m, 'LaffHub Message');
                            bootstrap_alert.warning(m);
                            setTimeout(function() {
                                $('#divAlert').fadeOut('fast');
                            }, 10000);

                            $('#cboPlan').focus(); activateTab('tabData'); return false;
                        }
                        //confirm('Confirm Message', 'Confirm title', callback_function, null, {ok : 'textForOkButton', cancel : 'textForCancelButton'});
                        //confirm('Confirm Message', 'Confirm title', function(input){var str = input === true ? 'Ok' : 'Cancel'; alert('You clicked ' + str, 'Simple Alert');});"

                        m ='Are you sure you want to subscribe to '+nt.toUpperCase()+' '+pl.toUpperCase()+' plan for N20? (Click "Yes" to proceed or "No" to abort)?';
                        Subscribe(true);

                        // confirm(m, 'LaffHub Message', Subscribe,null,{ok : 'Yes', cancel : 'No'});
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



            function SelectRow(dat)
            {
                if (dat)
                {
                    var cm=dat[1],dt=dat[2],pix=dat[7],sta=dat[4],id=dat[5],status=dat[6];

                    $('#lblNetwork').val(cm);

                    $('#lblDuration').html('');
                    $('#lblVideoCount').html('');
                    $('#lblAmount').html('');
                    $('#lblSubscriptionDate').html('');
                    $('#lblExpiryDate').html('');

                    document.getElementById('buttonSubscribe').disabled=false;

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
                    $('#cboPlan').val('');
                    $('#lblDuration').html('');
                    $('#lblVideoCount').html('');
                    $('#lblAmount').html('');
                    $('#cboAutoBilling').val('1');
                    $('#lblSubscriptionDate').html('');
                    $('#lblExpiryDate').html('');

                    SubscriptionId='<?php echo strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10)); ?>';
                    $('#lblSubscriptionId').val(SubscriptionId);

                    if (document.getElementById('buttonSubscribe')) document.getElementById('buttonSubscribe').disabled=true;
                }catch(e)
                {
                    m="ResetControls ERROR:\n"+e;
                    alert(m, 'LaffHub Message');
                    bootstrap_alert.warning(m);
                    setTimeout(function() {
                        $('#divAlert').fadeOut('fast');
                    }, 10000);
                }
            }//End ResetControls
            
                    
        function fireEskimi() {
            var img=document.createElement("img");
            img.setAttribute("src", "//dsp.eskimi.com/pixel/cookie");
            img.style.display = "none";
            document.body.appendChild(img);
        }// Eskimi pixel tracking

        })(jQuery);
        
    </script>

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