<?php include"header.php" ?>
 

  <main id="main">
  <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

          <div class="section-title">
          <h2>ORISHIRISHI</h2>
          <p>Details and Receipts</p>
          </div>
  
<p><center><form action="" method="post"><input type="date" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				<tr  bgcolor="#CCCCCC">
					
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Customer</th>
					<th data-column-id='employee_salary'  width='200px'>Date</th>
					<th data-column-id='employee_salary'  width='200px'>Cost</th>
					<th data-column-id='employee_salary'  width='200px'>Status</th>
					<th data-column-id='employee_salary'  width='200px'>Method</th>
					
				</tr>
			</thead>
		  <?php
		  
	  include "connect_to_mysqli.php";
	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT * from foods where status ='Paid' && meth='Card' ORDER BY date DESC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$iwe=$row['id'];
$sqa = "SELECT sum(priced) from enter where orderid='$iwe' ";
$sqla2 = mysqli_query($con,$sqa);
			 while($ow = mysqli_fetch_array($sqla2))
{
$k=$ow[0];}

echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['name'] . "</td>
<td width='100px' >" . $row['date'] . "</td><td width='100px'>" . $k . "</td>
<td width='100px'>" . $row['app'] . "</td><td width='100px'>" . $row['meth'] . "</td>
</tr>
               ";  
				
}}

else
{
	 $sql = "SELECT * from foods where status ='Paid' && date='$sa' && meth='Card' ORDER BY s ASC";
		  $sql2 = mysqli_query($con,$sql);
		$i=1;
while ($row = mysqli_fetch_array($sql2)) {
$iwe=$row['id'];
$sqa = "SELECT sum(priced) from enter where orderid='$iwe' ";
$sqla2 = mysqli_query($con,$sqa);
			 while($ow = mysqli_fetch_array($sqla2))
{
$k=$ow[0];}

echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['name'] . "</td>
<td width='100px' >" . $row['date'] . "</td><td width='100px'>" . $k . "</td>
<td width='100px'>" . $row['app'] . "</td><td width='100px'>" . $row['meth'] . "</td>
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

  <?php include"footer.php" ?>