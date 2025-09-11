<?php include"header.php" ?>
<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>CHB LUXURY ACADEMY</h2>
          <p>Here are all the logs of every paid training</p>
        </div>
  
<p><center><form action="" method="post"><input type="text" Placeholder="Booking ID" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Booking ID</th>
					<th data-column-id='employee_name'  width='200px'>Training</th>
					<th data-column-id='employee_name'  width='200px'>Duration</th>
					<th data-column-id='employee_name'  width='200px'>Amount</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_name'  width='200px'>Client Email</th>
					<th data-column-id='employee_name'  width='200px'>Client Number</th>
					<th data-column-id='employee_name'  width='200px'>Date</th>
				</tr>
			    </thead>
		  <?php
$sa =$_POST['kayd'];
if((!$sa))
  {
		   
$sql = "SELECT * from academy_cart where status ='Paid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td>
<td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['training'] . "</td>
<td width='200px' >" . $row['duration'] . "</td>
<td width='200px' >" . $row['price'] . "</td>
<td width='200px' >" . $row['name'] . "</td>
<td width='200px' >" . $row['email'] . "</td>
<td width='200px' >" . $row['phone'] . "</td>
<td width='200px' >" . $row['date'] . "</td>
</tr>
               ";  
				
}}

else
{
$sql = "SELECT * from academy_cart where id='$sa' && status ='Paid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"
<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td>
<td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['training'] . "</td>
<td width='200px' >" . $row['duration'] . "</td>
<td width='200px' >" . $row['price'] . "</td>
<td width='200px' >" . $row['name'] . "</td>
<td width='200px' >" . $row['email'] . "</td>
<td width='200px' >" . $row['phone'] . "</td>
<td width='200px' >" . $row['date'] . "</td>	
</tr>
";  
				
}
}
?> 
	  </table></div> </p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>