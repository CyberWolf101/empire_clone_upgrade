<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>All Expense Log</h2>
        </div>
<p><center><form action="" method="post"><input type="date" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					
				<th data-column-id='employee_name'  width='200px'>Amount</th>
					<th data-column-id='employee_name'  width='200px'>Title</th>
					<th data-column-id='employee_name'  width='200px'>Description</th>
					<th data-column-id='employee_salary'  width='200px'>Date</th>
					<th data-column-id='employee_salary'  width='200px'>Time</th>
					
				</tr>
			</thead>
		  <?php
		  
	 	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT * from pense ORDER BY date DESC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {



echo "<tr bgcolor='#fff'><td width='200px' >" . $row['amount'] . "</td><td width='200px'>" . $row['title'] ."</td><td width='200px'>" . $row['des'] . "</td>
<td width='200px'>" . $row['date'] . "</td><td width='200px'>" . $row['tim'] . "</td></tr>";
				
}}
else
{
    
		$sql = "SELECT all* from pense WHERE date='$sa' ORDER By tim DESC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {




echo "<tr bgcolor='#fff'><td width='200px' >" . $row['amount'] . "</td><td width='200px'>" . $row['title'] ."</td><td width='200px'>" . $row['des'] . "</td>
<td width='200px'>" . $row['date'] . "</td><td width='200px'>" . $row['tim'] . "</td></tr>";
    
    
    
}   
}




				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div>
	  </p>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>