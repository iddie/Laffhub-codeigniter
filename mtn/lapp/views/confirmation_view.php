<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaffHub::Service Confirmation</title>
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


    <link rel="stylesheet" href="<?php echo base_url();?>css/pikaday.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/date-theme.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/date-triangle.css">
    <link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

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

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-WNPD6HC');</script>
    <!-- End Google Tag Manager -->

    <script>
        (function($){

            var Network='<?php echo $Network; ?>';
            var Phone='<?php echo $Phone; ?>';
            var SubscriptionId='<?php echo strtoupper(substr(md5(uniqid(mt_rand(), true)) , 0, 10)); ?>';
            var Title='<font color="#AF4442">Service Subscription Help</font>';
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

            $(document).ready(function(e)
            {
                var pageLoadTime = new Date().getTime();

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


                $('#buttonSubscribe').click(function(e) {

                    var timeSpent =  new Date().getTime();
                    var timeElapsed = timeSpent - pageLoadTime;

                    if (timeElapsed <= 300000){

                        try
                        {
                            Subscribe();

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

                    }else {

                        m='Your session has expired, you have a 5mins timeframe to confirm your request. Pls re-subscribe again';
                        bootstrap_Success_alert.warning(m);
                        alert(m, 'LaffHub Message');
                        setTimeout(function() {
                            var url="<?php echo site_url('Subscribe'); ?>";
                            window.location.replace(url);
                        }, 5000);

                    }
                });//buttonSubscribe.click

                function Subscribe()
                {
                    var self;

                    if (Phone !== null)
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
                        var ph= Phone;
                        var nt= Network;

                        //Initiate POST
                        var uri = "<?php echo site_url('Subscribe/confirmation');?>";
                        var xhr = new XMLHttpRequest();
                        var fd = new FormData();

                        xhr.open("POST", uri, true);
                        fd.append('msisdn', ph);
                        fd.append('network', nt);
                        xhr.send(fd);// Initiate a multipart/form-data upload

                        xhr.onreadystatechange = function() {
                            //0-request not initialized , 1-server connection established, 2-request received, 3-processing request, 4-request finished and response is ready
                            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200)
                            {
                                // Handle response.
                                $.msg('unblock');
                                var res=$.trim(xhr.responseText);
                                if (res.toUpperCase()==='OK')
                                {
                                    m='You Have Successfully Subscribed To MTN Laffhub Weekly Plan. Enjoy hilarious comedy clips';
                                    bootstrap_Success_alert.warning(m);
                                    alert(m, 'LaffHub Message');
                                    setTimeout(function() {
                                        var url="<?php echo site_url('Subscriberhome'); ?>";
                                        window.location.replace(url);
                                    }, 5000);

                                }else
                                {
                                    m=res;
                                    bootstrap_alert.warning(m);
                                    alert(m, 'LaffHub Message');
                                    setTimeout(function() {
                                        $('#divAlert').fadeOut('fast');
                                    }, 10000);
                                }
                            } else
                            {
                                $.msg('unblock');
                            }
                        };

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
            });

        })(jQuery);
    </script>

</head>
<body class="page">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WNPD6HC"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page__layout">
    <div class="overlay"></div>
    <?php include('campaignusernav_view.php'); ?>

    <div id="content-ajax">
        <!--MAIN-->
        <main class="page__main main">
            <div class="col-md-12">
                <br>
                <div class="panel panel-info">
                    <!-- Default panel contents -->
                    <div class="panel-heading size-20">
                        <span class="size-18 makebold"><i class="fa fa-volume-control-phone"></i> Subscription Confirmation </span>
                    </div>

                    <div class="panel-body">

                        <div class="tab-content">
                            <div id="tabData" class="row tab-pane fade in active ">

                                <form class="form-horizontal">

                                    <br>
                                    <div class="img-responsive">
                                        <img src="<?php echo base_url(); ?>acss/images/mtnconfirm.png" class="img-responsive"/>
                                    </div>

                                    <br>

                                    <!--<div class="form-group">-->
                                    <!--    <label for="lblSubscriptionId" class="col-sm-2 control-label" title="Subscription ID">Subscription ID</label>-->

                                    <div class="col-sm-3" title="Subscription ID" >
                                        <input type="hidden" style="background-color:#C5522D; color:#ffffff;" id="lblSubscriptionId" class="form-control" value="<?php echo $subscriptionId; ?>">
                                    </div>
                                </form>
                            </div>

                            <div align="center">
                                <div id = "divAlert"></div>
                            </div>

                            <div>
                                <div class="form-group" style="margin-top:10px;">
                                    <div class="col-sm-offset-2 col-sm-7">
                                        <p id="instruction">Confirm your subscription via the Pop-up window on your mobile phone or dial *560*1# before clicking on "Continue"</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group" style="margin-top:30px;">
                                    <div class="col-sm-offset-2 col-sm-7">
                                        <button title="Add Subscription" id="buttonSubscribe" type="button" class="btn btn-success" role="button" style="text-align:center;"><i class="fa fa-credit-card-alt"></i> Continue</button>
                                    </div>
                                </div>
                            </div>
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