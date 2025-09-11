<?php  ob_start(); session_start(); include "connect_to_mysqli.php";  $id=$_SESSION['order'];?>
<!DOCTYPE html>
<html lang="en">

  <head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Delta Kitchen</title>
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


<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="main/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="main/assets/vendor/jquery/jquery.min.js"></script>
<script type="text/javascript" src="deltashow.js"></script>
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
font-weight:700;

}
.img{
max-width:50%;
max-height:50%;
border-radius:50%;
}
.btn-buya {
  display: inline-block;
  padding:6px;
  border:none;
  color: #fff;
  font-size: 10px;
  text-transform:uppercase;
  font-family: "Montserrat", sans-serif;
  font-weight: 800;
  transition: 0.3s;
  background:#FEBF01;
  
}

#clocs
{
display:none;}

#cloch{
display:none;
}
</style>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="margin-top:50px; background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title" style="color:#FFFFFF;">
          <h2>REFRESHMENTS</h2>
          <p>Get refreshed with our food options</p>
          </div>

        <div class="row">
		<div class="col-lg-12">
        <div class="box" data-aos="zoom-in" data-aos-delay="20" style="min-height:200px;">
      
            
           <div style="margin-top:30px;">
           <button  type="button" value="" class="btn-buya">ALL</button>  
            <?php
   $sql = "SELECT * from delta_category where status='active'";
   $sql2 = mysqli_query($con,$sql);
   while ($row = mysqli_fetch_array($sql2)) {
   echo'
  <button  type="button" value="'.$row['s'].'" class="btn-buya">'.$row['name'].'</button>';

} ?></div>	
            
<table border="0" cellspacing='0' style="border-collapse:separate; border:none; outline:none; margin:auto; border-spacing:0px 10px;">
<thead><th></th><th></th><th></th></thead>
<tbody id="showdelta">
		  
</tbody>
</table
		  
		


	

       
      </div></div></div></div>
    </section><!-- End Pricing Section -->
  </main><!-- End #main -->
  <!-- Vendor JS Files -->
  <script src="main/assets/vendor/aos/aos.js"></script>
  <script src="main/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="main/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="main/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="main/assets/vendor/php-email-form/validate.js"></script>
  <script src="main/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="main/assets/vendor/bootstrap/js/bootstrap.js"></script>

  <!-- Template Main JS File -->
  <script src="main/assets/js/main.js"></script>

</body>

</html>