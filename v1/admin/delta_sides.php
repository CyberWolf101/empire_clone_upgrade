<?php include"header.php" ?>
 

   <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

          <div class="section-title">
          <h2>Additional Meals</h2>
          <p>View and Delete sections</p>
          </div>
<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin']))
               {
		    $use =  $_GET['ordid'];
			$del = mysqli_query($con,"DELETE from sub where id='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
	   	    echo"<p style='color:blue;'>Sub Category Successfully Deleted!</p>";	 
				} ?>
				
<p style="text-align:center;">
<a href="subs.php?add=1" class="submitn" >Add New Sub Category</a>
<a href="delta_addmeal.php?kayd=1" class="submitn" >Add New Meal</a></p>

<p><div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>
<thead><tr  bgcolor="#CCCCCC">
					<th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_name'  width='200px'>Edit</th>
					<th data-column-id='employee_name'  width='200px'>View Meals</th>
					<th data-column-id='employee_name'  width='200px'></th>
				</tr>
			</thead>
<?php
$sql = "SELECT * from sub where gen='0017' ORDER BY name ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "


        <tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
        <td><form action='suber.php' method='post'>
        <input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
        <input type='submit' name='submit' value='Edit' class='submitn'  ></form></td>
                
                
                <td><form action='additional.php' method='post'>
			    <input type='text' name='ordid' value='" . $row['id'] . "' required hidden>  
                <input type='submit' name='submin' value='View Meals' class='submitn' ></form></td>	
                <td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Sub Category?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this  subcategory(" . $row['name'] . ")</p>
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




	 
					
	
	  
	 
		 
	
	  ?> 
	  </table></div> </p>
      </div>
     </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>