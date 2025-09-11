<?php include"head.php" ; ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

          <div class="section-title">
          <h2>All Bookings</h2>
          <p>Here are all the logs of every paid appointment. Print receipts here</p>
          </div>
  
<p><center><form action="" method="post"><input type="text" Placeholder="Booking ID" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Booking ID</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_name'  width='200px'></th>
					<th data-column-id='employee_name'  width='200px'></th>
					
				
					
				</tr>
			</thead>
		  <?php
		  
	  
	include "connect_to_mysqli.php";
	  $sa =$_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
		$sql = "SELECT DISTINCT id,name,meth from cart where status ='Paid' ORDER BY s DESC";
		$sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td>
<td><form action='view.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='View Booking' class='submitn' ></form></td>
<td><form action='rece.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>
			
			
</tr>
               ";  
				
}}

else
{
	$sql = "SELECT DISTINCT id,name from cart where id='$sa' && status ='Paid'  ORDER BY s DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td> 
<td>         <form action='view.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='View Booking' class='submitn' ></form></td>
<td>        <form action='rece.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
			
			
</tr>
               ";  
				
}
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