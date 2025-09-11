<?php include"header.php" ?>
 

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
  <?php include"footer.php" ?>