<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
        <h2>All Prices</h2>
        <p>View and Edit prices</p>
        </div>
		<?php 
		     include "connect_to_mysqli.php";
		
				 $use =  $_POST['ordid'];	
			 if (isset($_POST['submin'])){
		    $used =  $_POST['name'];
			$per =  $_POST['price'];
			
	  	 
		  			 
					    $insert = mysqli_query($con,"UPDATE rentrate SET price= ' $per' where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					  echo"<p style='color:blue;'>Rent rate Successfully Updated!</staff>";	 
					  
			        	}
					 ?>
					 
					 
<!----<p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p> ----->
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				<tr  bgcolor="#CCCCCC">
			         <th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_salary'  width='200px'>Price</th>
					
					<th data-column-id='employee_salary'  width='200px'></th>
				
				</tr>
			</thead>
		  <?php
		  
	    $sad =  $_POST['kayd'];
	  if((!$sad))
	  {
$sql = "SELECT * from  rentrate ";  

	     $sql2 = mysqli_query($con,$sql);
		while ($row = mysqli_fetch_array($sql2)) {
		echo "
            <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['s'] . "' required hidden>  
			<input type='text' name='price' value='₦" . $row['price'] . "' required> 
                </td>
                <td><input type='submit' name='submin' value='Update' class='submitn' ></form></td>
				
			
</tr>";
				
}}
else
{
		   
$sql4 = "SELECT * from  rentrate ";  

	     $sql5 = mysqli_query($con,$sql4);
		while ($row = mysqli_fetch_array($sql5)) {
		echo "
            <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['s'] . "' required hidden>  
			<input type='text' name='price' value='₦" . $row['price'] . "' required> 
                </td>
                <td><input type='submit' name='submin' value='Update' class='submitn' ></form></td>
				
			
</tr>";

				
}
}



				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div></p>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>