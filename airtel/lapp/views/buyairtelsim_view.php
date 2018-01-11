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
                                        <img src="<?php echo base_url(); ?>acss/images/buysim.png" class="img-responsive"/>
                                    </div>

                                    <br>
                                    <div align="center">
                                        <div id = "divAlert"></div>
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

        });

    })(jQuery);
</script>


<script src="<?php echo base_url();?>js/moment.min.js"></script>
<script src="<?php echo base_url();?>js/pikaday.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<!--SCRIPTS MAIN-->
<script src="<?php echo base_url(); ?>acss/js/main.js" async></script>
<!--/SCRIPTS MAIN-->



</body>
</html>