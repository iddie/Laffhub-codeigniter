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
<header>
   <?php include('usernav.php'); ?>
</header>
 <section class="home-slider" >
  <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
    <!-- Indicators -->
    
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active"> <img src="images/Beowulf2.jpg" alt="..."> </div>
     </div>

     </div>
     </section>
     <div class="clear"> </div>
      <div class="carousel-caption">
     

        <div class="login-form">
          <form >
            <div class="sign-in-head">
              <h3> Sign In </h3>
            </div>
            
           
  <div class="form-group">
    <input type="email" class="form-control"  placeholder="Your Email">
  </div>
  <div class="form-group">
    <input type="password" class="form-control"  placeholder="Your Password">
  </div>
  <div class="form-group">
  <h5> Forget  Password <span> <a href=""> Click here </a></span></h5>
  </div>
  
  <button onClick="window.location.href='profile.php'" type="button" class="btn btn-default signup">Login</button>
  <div class="form-group">
  <h5> <label><input type="checkbox" name="checkbox" value="value">Remember me</label> </h5>
  </div>
  <div class="sign-up"> <img class="lines" src="images/or.png" class="img-responsive" width="100%"><div class="hr-label"><span class="hr-label__text">OR</span></div> </div>
</form><div class="login-social">
            <div class="login_fb">
            <a href="#">
<h5> Login with Facebook</h5> </a>


</div>
</div>
<div class="login-social">
            <div class="login_twitter">
            <a href="#">
<h5> Login with Twitter</h5> </a>


</div>
</div>
<div class="form-groups">
  <h5> Don't have an account? <span> <a href="signup.html"> Sign Up </a></span></h5>
  </div>
          </form>
          <div class="login-social"> </div>
         <p style="font-weight:bold; padding-right:5px; padding-top:10px;">   <a style="color: #db5832;" href="#"><span style=" text-shadow: 0 0px 0px rgba(0,0,0,.6);">Publisher Login </span> </a></p>
        </div>
  
      </div>
  
 <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 social">
        <img src="images/fb.png">
        <img src="images/twitter.png">
        <img src="images/inst.png">
        </div>
    </div>
  

    <!-- Controls --> 
</section>
</div>
<!--
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 socials">
        <img src="images/fb.png">
        <img src="images/twitter.png">
        <img src="images/inst.png">
        </div>
        -->
        
  <?php include('footer.php'); ?>

<script src="js/jquery.min.js"></script> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>