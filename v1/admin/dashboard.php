<?php include"header.php" ?>
<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>All Bookings</h2>
          <p>Here are all the logs of everypaid appointment. Print receipts here</p>
        </div>
  
<p><center><form action="" method="post"><input type="text" Placeholder="Booking ID" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Booking ID</th>
					<th data-column-id='employee_name'  width='200px'>Client</th>
					<th data-column-id='employee_name'  width='200px'>PM</th>
					<th data-column-id='employee_name'  width='200px'></th>
					<th data-column-id='employee_name'  width='200px'></th>
				</tr>
			    </thead>
		  <?php
$sa =$_POST['kayd'];
if((!$sa))
  {
		   
$sql = "SELECT DISTINCT id,name,meth from cart where status ='Paid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td><td width='200px' >" . $row['meth'] . "</td>
<td><form action='views.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='View Booking' class='submitn' ></form></td>
<td><form action='reces.php' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
</tr>
               ";  
				
}}

else
{
$sql = "SELECT DISTINCT id,name,meth from cart where id='$sa' && status ='Paid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

echo"

<tr bgcolor='#fff'><td width='200px' >" . $i++ . "</td><td width='200px'>" . $row['id'] . "</td>
<td width='200px' >" . $row['name'] . "</td><td width='200px' >" . $row['meth'] . "</td>
<td><form action='views.php' method='post'>
<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
<input type='submit' name='submin' value='View Booking' class='submitn' ></form></td>
<td><form action='reces.php' method='post'>
<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>	
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