<?php include"head.php" ; ?>

  <main id="main">

     <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">
<?php

 include "connect_to_mysqli.php";
	  $sa =  $_POST['ordid'];

			 $sql = "SELECT * from cart where id='$sa'  ORDER BY s DESC";
		  $sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
    
    $nam=$row['name'];
}
    
    ?>
        <div class="section-title">
          <h2>Bookings</h2>
          <p><b><?php echo $nam; ?></b> Appointments</p>
        </div>

<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Service</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_salary'  width='200px'>Start Time</th>
					<th data-column-id='employee_salary'  width='200px'>End Time</th>
					<th data-column-id='employee_salary'  width='200px'>Staff</th>
					<th data-column-id='employee_salary'  width='200px'>Date</th>
					<th data-column-id='employee_salary'  width='200px'>Status</th>
			        
				
					
				</tr>
			</thead>
		  <?php
		  
	  include "connect_to_mysqli.php";
	  $sa =  $_POST['ordid'];

			 $sql = "SELECT * from cart where status ='Paid' && id='$sa'  ORDER BY s DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['service'] . "</td>
<td width='100px' >" . $row['name'] . "</td><td width='100px'>" . $row['timef'] . "</td>
<td width='100px'>" . $row['timet'] . "</td><td width='100px'>" . $row['staff'] . "</td><td width='100px'>" . $row['date'] . "</td>
<td width='100px' ><span class='submitad'>" . $row['app'] . "</span></td>

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