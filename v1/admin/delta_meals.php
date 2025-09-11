<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Delta Kitchen Menu</h2>
          <p>View and Edit prices</p>
        </div>
		<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin'])){
		    $use =  $_GET['ordid'];
			$del = mysqli_query($con,"DELETE from baby where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
			$del = mysqli_query($con,"DELETE from delta_images where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
				echo"<p style='color:blue;'>Meal Successfully Deleted from Menu!</staff>";
				header("refresh:2;url=delta_meals.php"); 
				}
		
					
			 if (isset($_POST['submin'])){
		    $use =  $_POST['ordid'];
			$per =  $_POST['ph'];
			$ter =  $_POST['th'];
	  	 
		  			     $insert = mysqli_query($con,"UPDATE baby SET price= '$per' where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					     $insert = mysqli_query($con,"UPDATE baby SET time= '$ter' where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 	echo"<p style='color:blue;'>Price Successfully Updated!</staff>";	 
			        	}
					 ?>
					 
					 
<p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead>
				     <tr bgcolor="#CCCCCC">
			         <th data-column-id='employee_name'  width='200px'>Protein</th>
					<th data-column-id='employee_salary'  width='200px'>Price</th>
					<th data-column-id='employee_salary'  width='200px'></th>
					<th data-column-id='employee_salary'  width='200px'></th>
				</tr>
			</thead>
		  <?php
		  
	    $sa =  $_POST['kayd'];
	   if((!$sa))
	  {
$sql = "SELECT baby.id, baby.gen, baby.name, baby.price, baby.time
FROM baby
JOIN sub ON baby.gen = sub.id
JOIN cater ON sub.gen = cater.id
WHERE cater.id = '0015' ";

	     $sql2 = mysqli_query($con,$sql);
		 while ($row = mysqli_fetch_array($sql2)) {
		echo "
            <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='text' name='ph' value='" . $row['price'] . "' required> 
                </td>
					
				
				
			<td><input type='submit' name='submin' value='Update' class='submitn' ></form></td>	
			<td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Meal?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this meal from the menu(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
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
				
}}
else
{
 $sql = "SELECT baby.id, baby.gen, baby.name, baby.price, baby.time
FROM baby
JOIN sub ON baby.gen = sub.id
JOIN cater ON sub.gen = cater.id
WHERE cater.id = '0015' && baby.name LIKE '%".$sa."%' ORDER by name ASC"; 
		   $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "
<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
			<input type='text' name='ph' value='" . $row['price'] . "' required></td>
		
				
				
			<td><input type='submit' name='submin' value='Update' class='submitn' ></form></td>	
			<td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Meal?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this meal from the menu(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['id'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin'>Yes</button></form>	
        </p>
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
}



				   
					
	
	  
	 
		 
	
	  ?> 
	  </table></div></p>
      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>