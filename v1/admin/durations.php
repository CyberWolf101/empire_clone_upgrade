<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
          <section id="about" class="about">
          <div class="container">
 
          <div class="section-title">
          <h2>Training Durations</h2>
          <p>Delete and Edit details</p>
          </div>
            	<?php 
		 
			if (isset($_GET['submin'])){
		    $use =  $_GET['ordid'];
			$del = mysqli_query($con,"DELETE from baby where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
		    echo"<p style='color:blue;'>Duration Successfully Deleted from Database!</staff>";	 
				}
		
					
			 if (isset($_POST['submin'])){
		    $use =  $_POST['ordid'];
			$per =  $_POST['price'];
			$ter =  $_POST['name'];
	  	 
		  			  $insert = mysqli_query($con,"UPDATE baby SET price= '$per' where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					  $insert = mysqli_query($con,"UPDATE baby SET name= '$ter' where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					  echo"<p style='color:blue;'>Updated Successfully!</staff>";	 
					  
			        	}
					 ?>
<p style="text-align:center;"><a href="services.php?academy=1" class="submitn">Add Training Duration</a></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'><thead>
			    	<tr  bgcolor="#CCCCCC">
					 <th data-column-id='employee_name'  width='200px'>Training</th>
					<th data-column-id='employee_salary'  width='200px'>Duration</th>
					<th data-column-id='employee_salary'  width='200px'>Price</th>
					<th data-column-id='employee_salary'  width='200px'></th>
					<th data-column-id='employee_salary'  width='200px'></th>
					
				</tr>
			</thead>
		  <?php
		  
    
		   
$sql = "SELECT sub.name,baby.id,baby.price,baby.name as duration from baby JOIN sub ON baby.gen = sub.id
JOIN cater ON sub.gen = cater.id where cater.id='0012' ";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

	echo "
             <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			 <input type='text' name='ordid' value='" . $row['id'] . "' required hidden>
			 <input type='text' name='name' value='".$row['duration'] . "' required>
			 </td>
				
			<td><input type='text' name='price' value='" . $row['price'] . "' required></td>	
			<td><input type='submit' name='submin' value='Update' class='submitn' ></form></td>	
			<td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
        <div class='modal-content'>
         <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete ?</h4>
        </div>
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete the " . $row['name'] . " (" . $row['duration'] . ") duration</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form></p>
        <p><button class='submitn' data-dismiss='modal' >No</button></p>
	   </div>
      </div>
    </div>
  </div>
 </div> 
</div>

             
            </div>
</td>
</tr>";
				
}
					
?> 
</table></div>
</p>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>