<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>LaffHub</title>
<link rel="icon" href="images/logoshort.png" type="image/x-icon" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">
</head>
<body>
<style>
.img-desc {
    background: #000;
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
@media only screen and (max-width: 768px) and (min-width: 561px) {
.row {
    margin-right: -15px;
}
}
@media only screen and (min-width:300px) and (max-width:560px) {
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    padding-right: 0px !important;
}
}
</style>
<header>
   <?php include('usernav.php'); ?>
</header>
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
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10 base base1">
        <div class="event">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="images/event.jpg" class="img-responsive"> </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="event-desc">
                <div class="row">
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12"> 
                  	 <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <div class="cale"> 
                  	<h5> 1  </h5>
                    <h5> May </h5>
                    
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                  <div class="cale"> 
                  	<h5><i class="fa fa-clock-o" aria-hidden="true"></i>  </h5>
                    <h5> 3:00 pm </h5>
                    
                    </div>
                  </div>
                  </div>
                   </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="price-btn">
                          <button class="btn btn-info form-control"> Price starts from $15</button>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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

<div class="col-lg-4 col-md-4 col-sm-4 col-xs-10 base base1">
        <div class="event">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="images/event.jpg" class="img-responsive"> </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="event-desc">
                <div class="row">
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12"> 
                     <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <div class="cale"> 
                    <h5> 1  </h5>
                    <h5> May </h5>
                    
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                  <div class="cale"> 
                    <h5><i class="fa fa-clock-o" aria-hidden="true"></i>  </h5>
                    <h5> 3:00 pm </h5>
                    
                    </div>
                  </div>
                  </div>
                   </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="price-btn">
                          <button class="btn btn-info form-control"> Price starts from $15</button>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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

     <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10 base base1">
        <div class="event">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"> <img src="images/event.jpg" class="img-responsive"> </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="event-desc">
                <div class="row">
                  <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12"> 
                     <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                  <div class="cale"> 
                    <h5> 1  </h5>
                    <h5> May </h5>
                    
                    </div>
                  </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> 
                  <div class="cale"> 
                    <h5><i class="fa fa-clock-o" aria-hidden="true"></i>  </h5>
                    <h5> 3:00 pm </h5>
                    
                    </div>
                  </div>
                  </div>
                   </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
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
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="price-btn">
                          <button class="btn btn-info form-control"> Price starts from $15</button>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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

    </div>
  </div>
</div>
 </div>
 
 <?php include('footer.php'); ?>


<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>