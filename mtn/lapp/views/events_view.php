<?php session_start();
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>LaffHub::Events</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="icon" type="image/png" href="<?php echo base_url();?>images/icon.png">

<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-theme.min.css">
<link rel="stylesheet" href="<?php echo base_url();?>iconfont/material-icons.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/general.css">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="<?php echo base_url();?>css/ie10-viewport-bug-workaround.css" rel="stylesheet">



<link href="<?php echo base_url();?>hcss/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/style.css" rel="stylesheet">
<link href="<?php echo base_url();?>hcss/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

<style>
.img-desc {
    background: #c5c3c4;
    padding: 10px 20px;
}
h4 {
    float: left;
}
.profile-img{
    float: left;
    width: 25%; 
}
.channel-in{
   min-height:600px;
   background:#ffffff;
}
input.form-control {
    border: 1px solid #c5c3c4;
    border-radius: 0px;
}
.img-portion img{
    width:100%;
    float:left;	
}
.former {
    margin-top: 15px;
}
.channel-wrapper input.form-control {
    width: 100%;
    height: 35px;
}
.name-type {
    position: relative;
    top: -30px;
    margin-top: 30px;
}
.channel-in2 {
    background: #fff;
    min-height: 558px;
    padding: 20px;
}
@media only screen and (min-width:768px)
{
    .base1{
	    padding-right:15px !important; 
	}
	.base2{
	    padding-left:15px !important; 
	}
}
@media only screen and (max-width:767px){
    .base1 {
        margin-bottom: 20px !important;
    }
}
i.fa.fa-check-circle {
    font-size: 35px;
    color: green;
}
p.sub {
    font-size: 19px;
    font-weight: 600;
}
p.place {
    font-size: 16px;
    font-weight: 600;
}
</style>

<!--Javascripts-->
<script src="<?php echo base_url();?>js/jquery-1.12.4_min.js"></script>
<script src="<?php echo base_url();?>js/ie10-viewport-bug-workaround.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src=<?php echo base_url();?>js/html5shiv.min.js"></script>
  <script src="<?php echo base_url();?>js/respond.min.js"></script>
<![endif]-->



</head>
<body>

<header> <?php include('usernav.php'); ?> </header>

<section class="channel-wrapper">
<div class="container">
<div  class="img-desc">
  <div class="channels">
    <div class="row">
      <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 col-md-offset-2 col-lg-offset-2 col-sm-8 offset-2">
        <div class="go-div">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search for Events...">
            <span class="input-group-btn">
            <button class="btn btn-default" type="button">Go!</button>
            </span> </div>
          <!-- /input-group --> 
        </div>
        <!-- /.col-lg-6 --> 
      </div>
      <!-- /.row --> 
    </div>
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 base base1">
        <div class="event">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="<?php echo base_url();?>images/event.jpg" class="img-responsive"> </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="event-desc">
                <div class="row">
                  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
                  	 <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="cale"> 
                  	<h5> 1  </h5>
                    <h5> May </h5>
                    
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                  <div class="cale"> 
                  	<h5><i class="fa fa-clock-o" aria-hidden="true"></i>  </h5>
                    <h5> 3:00 pm </h5>
                    
                    </div>
                  </div>
                  </div>
                   </div>
                  <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <table class="table">
                      <tr>
                        <th >Days</th>
                        <th >Hrs</th>
                        <th >Min</th>
                        <th >Sec</th>
                      </tr>
                      <tr>
                        <td >5</td>
                        <td >120</td>
                        <td >1200</td>
                        <td >2000</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p> The Price starts from the reason..</p>
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="price-btn">
                          <button class="btn btn-info form-control"> Price starts from $15</button>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="ticket-btn">
                          <button class="btn btn-info form-control">Get Tickets</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 base base1">
        <div class="event">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="<?php echo base_url();?>images/event.jpg" class="img-responsive"> </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="event-desc">
                <div class="row">
                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"> 
                  	 <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <div class="cale"> 
                  	<h5> 1  </h5>
                    <h5> May </h5>
                    
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                  <div class="cale"> 
                  	<h5><i class="fa fa-clock-o" aria-hidden="true"></i>  </h5>
                    <h5> 3:00 pm </h5>
                    
                    </div>
                  </div>
                  </div>
                   </div>
                  <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                    <table class="table">
                      <tr>
                        <th >Days</th>
                        <th >Hrs</th>
                        <th >Min</th>
                        <th >Sec</th>
                      </tr>
                      <tr>
                        <td >5</td>
                        <td >120</td>
                        <td >1200</td>
                        <td >2000</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                      <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="price-btn">
                          <label style="width:60px;" class=" btn-info form-control"> Price</label>
                        </div>
                      </div>
                      <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="ticket-btn">
                          <label style="width:auto; overflow:auto; height:80px; margin-left:-60px;" class="btn-info form-control"> TicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTicketsTickets</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>


<?php include('userfooter.php'); ?>

<script src="<?php echo base_url();?>js/jquery.min.js"></script> 
<script src="<?php echo base_url();?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/bootbox.min.js"></script>
</body>
</html>