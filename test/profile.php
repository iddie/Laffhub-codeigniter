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
<header>
  <?php include('usernav.php'); ?>
</header>
<section class="channel-wrapper">
<div class="container">
<div  class="img-desc">
<div class="channels">
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 base base1">
      <div class="channel-in2">
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h4> Your Details </h4>
		  </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			    <img src="images/profile-icon.png" class="profile-img">  
			</div>
           	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <label>Profile Image</label>  
				<input type="file" name="#" >
			</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <label>Name</label>  
                <div class="name-type">
				<input type="text" class="form-control">
                </div>
			</div>	 	
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <h4>Change Password</h4>  
			</div>	
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <label>Old Password</label>  
				<input type="password" name="ravi" class="form-control">
			</div>	
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <label>New Password</label>  
				<input type="password" name="ravi" class="form-control">
			</div>	
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <label>Confirm Password</label>  
				<input type="password" name="ravi" class="form-control">
			</div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
			    <!--<input type="submit" class="btn btn-info form-control" value="UPDATE PROFILE">-->
				<button class="btn btn-info form-control">UPDATE PROFILE</button>
			</div>			
	    </div>
      </div>
    </div>   
    </div>
    
       <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 base base2">
       
      <div class="channel-in2">
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <h4>YOUR SUBSCRIPTIONS</h4>  
		</div>
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-2 img-portion"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
		<div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 img-portion"><p class="sub">Voucher</p></div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 former">
		    <p class="place">Your plan will expire on DECEMBER 21, 2017</p>  
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		    <h4>YOUR PREFERENCE</h4>  
			<button class="btn btn-danger form-control">RESET MY FAVOURATES</button>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 former">
		    <h4>Logout</h4>  
			<button class="btn btn-danger form-control">LOGOUT</button>
		</div>
      </div>
      </div>
      
    </div>
	
  </div>
</div>
</div>
</section>

<?php include('footer.php'); ?>

<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>