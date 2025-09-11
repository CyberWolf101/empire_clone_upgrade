<?php session_start();
$orid=$_SESSION['ider'];
include"head.php";
 ?>
<main id="main">
<!---...This is for Microlashing section - single.. --->
<style>


.ter{
background-color:#fff;
margin-bottom:10px;
outline:none;
border:none;
padding:10px;

}



.span{

font-size:13px;
font-weight:600;
color:black;

}
.img{
max-width:40%;
max-height:40%;
border-radius:50%;
background-color:#000000;
}


</style>

    <!-- ======= Pricing Section ======= -->
    <section id="pricing" class="pricing section-bg" style="background-color:none;  border:none;">
      <div class="container" style="width:100%; margin:auto;">
        <div class="section-title">
          <h2 >ADD EXTRA SERVICE</h2>
          <p>Click to choose your desired services and get it done faster</p>
        </div>

        <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="box" data-aos="zoom-in" data-aos-delay="100">
			
    <?php
 include "connect_to_mysqli.php";
  
$sql = "SELECT all* from cater ORDER BY name ASC";
		$sql2 = mysqli_query($con,$sql);
			 while($row = mysqli_fetch_array($sql2))
				  {
				  $imageURL='../categ/'.$row["file_name"];	
				  
				  echo'<form method="post" action="mainsubs.php">
      <input type="text" value="'.$row["id"].'" class="form-control" name="cate" hidden />
      <input type="text" value="'.$row["id"].'" class="form-control" name="cate" hidden />
	<div class="row"  style="width:100%; margin:auto; padding:10px;">
    <button type="submit" value="Pedicure" name="submit" class="ter">
	<div class="row"  style="width:100%; margin:auto;">
	  <div class="col"><img src="'.$imageURL.'" class="img"/></div>
	   <div class="col"><span style="color:black;">'.$row["name"].'</span></div>
		   </div></button></div>
			
			
          
	
			</form>	';
				  }
			?>
     </div></div>
			

  
	


	

       
      </div>
    </section><!-- End Pricing Section -->

   
  </main><!-- End #main -->
<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/typed.js/typed.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>
   
</body>
</html>