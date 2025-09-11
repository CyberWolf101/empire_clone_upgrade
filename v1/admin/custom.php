<?php include"header.php" ; ?>

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
<?php include "footer.php" ?>