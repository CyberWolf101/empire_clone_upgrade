<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Delta Kitchen</h2>
          <p>Orders and Receipts</p>
        </div>
  
<p><center><form action="" method="post"><input type="date" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p> <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				    <tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='200px'>No</th>
					<th data-column-id='employee_name'  width='200px'>Customer</th>
					<th data-column-id='employee_name'  width='200px'>Contact No</th>
					<th data-column-id='employee_salary'  width='200px'>Date</th>
					<th data-column-id='employee_salary'  width='200px'>Cost</th>
					<th data-column-id='employee_salary'  width='200px'>Status</th>
					<th data-column-id='employee_salary'  width='200px'>Reciept</th>
				</tr>
			</thead>
		  <?php
		  
	  include "connect_to_mysqli.php";
	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		   
			 $sql = "SELECT sum(price*quantity) as total,name,date,status,phone,order_id from delta_cart where app ='Confirmed' GROUP BY order_id ORDER BY date DESC";
		     $sql2 = mysqli_query($con,$sql);
	    	 $i=1;
while ($row = mysqli_fetch_array($sql2)) {
    
$iwe=$row['order_id'];


echo"

<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['name'] . "</td>
<td width='100px'>" . $row['phone'] . "</td>
<td width='100px' >" . $row['date'] . "</td><td width='100px'>" . $row['total']  . "</td>
<td width='100px'>" . $row['status'] . "</td>
<td><form action='deltareciept.php' method='post'>
<input type='text' name='ordid' value='" . $row['order_id'] . "' required hidden>  
<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>
</tr> ";  
				
}}

else
{
 $sql = "SELECT sum(price*quantity) as total,name,date,status,phone,order_id from delta_cart where app ='Confirmed' AND date='$sa' GROUP BY order_id ORDER BY s ASC";
  $sql2 = mysqli_query($con,$sql);
	    	 $i=1;
while ($row = mysqli_fetch_array($sql2)) {
    
$iwe=$row['order_id'];


echo"
<tr bgcolor='#fff'><td width='100px' >" . $i++ . "</td><td width='100px'>" . $row['name'] . "</td>
<td width='100px'>" . $row['phone'] . "</td>
<td width='100px' >" . $row['date'] . "</td><td width='100px'>" . $row['total']  . "</td>
<td width='100px'>" . $row['status'] . "</td>
<td><form action='deltareciept.php' method='post'>
<input type='text' name='ordid' value='" . $row['order_id'] . "' required hidden>  
<input type='submit' name='submin' value='Print Receipt' class='submitn' ></form></td>
</tr> ";  
				
}}
				   
?> 
	  </table></div>
	  </p>
      

      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->
  <?php include"footer.php" ?>