 <footer id="footer">
    
      <div class="container">
       
	<?php
		include "every.php";
	
						$sql = "SELECT count(*) As 'total'
						FROM cart where staff='$name' && status='Paid'
						 ";
		 $sql2 = mysqli_query($con,$sql);
		 $dad = mysqli_fetch_assoc($sql2);
		 
 
           $kany=$dad['total'];
		   $date = date('Y-m-d');
		
		$sqd = "SELECT count(*) As 'total'
						FROM cart where staff='$name' && date='$date' && app='Confirmed'
						 ";
		 $sqd2 = mysqli_query($con,$sqd);
		 $dadd = mysqli_fetch_assoc($sqd2);
		 
 
 $kan=$dadd['total'];    
				  	
						?>
          <div class="row adjust" style="margin:auto; max-width:100%;" align="center">
		  
         <div class="col">
		   <a href="dash.php#today" style="color:#fff;"><i class="bx bx-package slap"></i>
		   <br /><span >For Today(<?php echo $kan; ?>)</span></a>
		   </div> 
		   
		
		     <div class="col">
		   <a href="rent.php" style="color:#fff;"><i class="bx bx-mail-send slap" ></i>
		   <br /><span >All Appointments(<?php echo $kany; ?>)</span></a>
		   </div>
		   
		<div class="col">
		   <a href="dash.php" style="color:#fff;"><i class="bx bx-user slap" style="border:3px solid #FFC700;"></i>
		   <br /><span >Profile</span></a>
		   </div>

         
          

        
      </div>
    </div>
</footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="ri-arrow-up-line"></i></a>
  <div id="preloader"></div>


  <!-- Vendor JS Files -->
  <script src="assets/vendor/jquery/jquery.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/jquery.easing/jquery.easing.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/waypoints/jquery.waypoints.min.js"></script>
  <script src="assets/vendor/counterup/counterup.min.js"></script>
  <script src="assets/vendor/venobox/venobox.min.js"></script>
  <script src="assets/vendor/owl.carousel/owl.carousel.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>






