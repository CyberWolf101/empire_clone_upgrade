<?php  ob_start(); session_start();  include "submit.php";  ?>            
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Chbluxuryempire - Booking</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="main/assets/img/favicon.png" rel="icon">
  <link href="main/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="main/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="main/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="main/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="main/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="main/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="main/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="main/assets/css/style.css" rel="stylesheet">



<script src="main/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="main/assets/vendor/jquery/jquery.min.js"></script>


<script>
$(document).ready(function(){
        $("#myModal").modal('show');
    });
</script>


  <!-- =======================================================
  * Template Name: Knight - v4.3.0
  * Template URL: https://bootstrapmade.com/knight-free-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
<style>

.ter{
background-color:#fff;
padding:0 10px;
}
.check{
padding:2%;
font-size:12px;
width:25%;
}
.check span{

font-size:13px;
font-weight:500;

}
.img{
max-width:30%;
max-height:30%;
border-radius:50%;
}
.submitn{
  
  background: #FFC700;
  color: #fff;
  border-radius: 5px;
  padding: 10px;
  font-size: 10px;
  font-weight: 600;
  outline:none;
  border:none;
 margin-top:20px;
}

.submitn:hover{
  background: #000000;
  color: #fff;
  outline:none;
  border:none;
}
input{
    margin-top:20px;
}
.can{
    
    border:none;
    border-bottom: 1px solid #333;
    padding-top:20px;
}
.hd h4{
    padding-top:20px;
   color:#F5F5F5;
   font-size:18px;
}

</style> 
<?php

$ran=$_SESSION['hord'];
$tot=$_SESSION['tot'];
$email=$_SESSION['emails'];
$first=$_SESSION['first'];
?>
    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto; ">
       
         <div class="row">
             <div class="justify-content-center" align="center">
           <div class="col-lg-6 col-md-12">
               	<div class="row">
               	 <div class="col-lg-6 col-md-6 col-sm-6">
               	     <div style="color:white">
               	  <img src="./assets/img/luxury/logo_luxury.png" style="max-height: 60px;" alt="" class="img">CHB Luxury Ltd</div>
               	 </div>  
               	  <div class="col-lg-4 col-md-6 col-sm-6">
              <div style="color:white;" >	NGN  <?php echo $tot; ?> <br><?php echo $email; ?> </div>
               	 </div>  
               	 <div class="col-lg-12 col-md-12 col-sm-12">
               	<div class="can"></div>
               	
               	
               	
             </div>.
 
             
             <div class="col-lg-12 col-md-12 col-sm-12"> <div class="hd"><h4>Enter your giftcard details to pay</h4></div></div>
             <div class="col-lg-12 col-md-12 col-sm-12"><form method="post"><div class="form-group">
             
   <span>  <?php echo $err; ?> </span>   <span>  <?php echo $err1; ?> </span>  <span>  <?php echo $alert; ?> </span>  
    <input type="text" class="form-control" id="inputAddress" name="gift" placeholder="Enter Card Number" required>
    <input type="hidden" value=" <?php echo $tot; ?>" class="form-control"  name="total">
    <input type="hidden" value=" <?php echo $email; ?>" class="form-control"  name="email">
     <input type="hidden" value=" <?php echo $first; ?>" class="form-control"  name="first">
  </div><button type="submit" name="submit" class="submitn" style="font-weight: 600; font-size: 0.8rem; padding:6px; background:#FEBF01; color: #F5F5F5;">
                               Pay NGN <?php echo " ".$tot; ?></button></form>  </div>
               	  
               	    
               	</div>
               	    </div>
               	    </div>
              
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
  
 
  <!-- Vendor JS Files -->
  <script src="main/main/assets/vendor/aos/aos.js"></script>
  <script src="main/main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="main/main/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="main/main/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="main/main/assets/vendor/php-email-form/validate.js"></script>
  <script src="main/main/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="main/main/assets/vendor/bootstrap/js/bootstrap.js"></script>

  <!-- Template Main JS File -->
  <script src="main/main/assets/js/main.js"></script>

</body>

</html>