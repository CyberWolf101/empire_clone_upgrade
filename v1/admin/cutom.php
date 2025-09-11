<?php include"head.php" ; ?>

  <main id="main">

  <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Customers List</h2>
          <p>Customers Details</p>
        </div>
  	
		 

<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
				
					<th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_name'  width='200px'>Email</th>
					<th data-column-id='employee_name'  width='200px'>Phone Number</th>
					
					
				
					
				</tr>
			</thead>
		  <?php
include "connect_to_mysqli.php";

		   
			 $sql = "SELECT DISTINCT name,email,phone from foods where status='Paid' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px'>" . $row['name'] . "</td>
<td width='200px' >" . $row['email'] . "</td>
<td width='200px' >" . $row['phone'] . "</td>	
</tr>
               ";  
				
}
				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      

      

      </div>
    </section><!-- End About Section -->

   
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