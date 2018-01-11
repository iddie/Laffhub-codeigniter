<link media="screen" href="<?php echo base_url();?>css/jquery.msg.css" rel="stylesheet" type="text/css">

<script src="<?php echo base_url();?>js/modernAlert.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.center.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.msg.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.redirect.js"></script>

<script>
    (function($){

        var Title='<font color="#AF4442">LaffHub Help</font>';
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
            //$(document).ajaxStop($.msg('unblock'));

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

            $('#btnSearch').click(function(e) {
                try
                {

                    var txt=$.trim($('#txtSearch').val());

                    if (!txt)
                    {
                        m='Please enter the text to search for.';

                        bootstrap_alert.warning(m);
                        alert(m, 'LaffHub Message');
                        setTimeout(function() {
                            $('#divAlert').fadeOut('fast');
                        }, 10000);

                        $('#txtSearch').focus();

                        return false;
                    }

                    $.msg(
                        {
                            autoUnblock : true ,
                            clickUnblock : true,
                            afterBlock : function() {self = this;/* store 'this' for other scope to use*/},
                            klass : 'airel-custom-theme',
                            bgPath : '<?php echo base_url();?>images/',
                            content: '<center><img src="<?php echo base_url();?>images/loader.gif" /><p style="color:#fff; font-size:20px; margin-top:10px;"><b>Searching For Video. Please Wait...</b></p></center>'
                        }
                    );

                    $.redirect("<?php echo site_url('Searchresult/Search');?>",{searchstring: txt});
                }catch(e)
                {
                    $.msg('unblock');
                    var m='Search Button Click ERROR:\n'+e;

                    bootstrap_alert.warning(m);
                    alert(m, 'LaffHub Message');
                    setTimeout(function() {
                        $('#divAlert').fadeOut('fast');
                    }, 10000);
                }
            });

            $('#txtSearch').keyup(function(e) {
                try
                {
                    if (e.keyCode == 13)
                    {
                        $('#btnSearch').trigger('click');
                    }
                }catch(e)
                {
                    $.msg('unblock');
                    var m='Keyup ERROR:\n'+e;

                    bootstrap_alert.warning(m);
                    alert(m, 'LaffHub Message');
                    setTimeout(function() {
                        $('#divAlert').fadeOut('fast');
                    }, 10000);
                }
            });
        });//End document ready

    })(jQuery);

    function NewPage(url)
    {
        window.location.href='<?php echo base_url(); ?>' + url;
    }

    function SubscriberSignOut(input)
    {
        if (input === true)
        {
            window.location.href='<?php echo site_url('Subscriberlogout'); ?>';
        } else
        {
            m='Sign Out Cancelled';
            alert(m, 'LaffHub Message');
            bootstrap_alert.warning(m);
            setTimeout(function() {
                $('#divAlert').fadeOut('fast');
            }, 10000);
        }
    }

    function Ask()
    {
        confirm('Do you want to sign out? (Click <b>Yes</b> to proceed or <b>No</b> to abort)', 'LaffHub Message', SubscriberSignOut,null,{ok : 'Yes', cancel : 'No'});
    }
</script>


<header class="page__header header ">
    <div class="container">
        <div id="divAlert" align="center"></div>
    </div>

    <div class="container">

        <div class="header__left">
            <a href="<?php echo site_url('Subscriberhome'); ?>" class="logo">
                <img src="<?php echo base_url(); ?>acss/images/mtn_logo.png" alt="">
            </a>
            <a href="<?php echo site_url('Subscriberhome'); ?>" class="logo laffhub-logo">
                <img src="<?php echo base_url(); ?>acss/images/logo.png" alt="">
            </a>
        </div>

</header>

