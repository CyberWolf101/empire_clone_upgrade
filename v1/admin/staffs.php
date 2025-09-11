<?php include"header.php" ?>
 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Staff</h2>
          <p>Delete and Edit staff details</p>
        </div>
<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin']))
 {
		    $use =  $_GET['ordid'];
			
	  	  
		  			 $del = mysqli_query($con,"DELETE from staff where email='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
				echo"<p style='color:blue;'>Staff Successfully Deleted from Database!</staff>";	 
				}
					 ?>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					
					<th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_salary'  width='200px'>Email</th>
					<th data-column-id='employee_salary'  width='200px'>Phone</th>
					<th data-column-id='employee_salary'  width='200px'>Password</th>
					<th data-column-id='employee_salary'  width='200px'>Section</th>
					<th data-column-id='employee_salary'  width='200px'>Role</th>
						<th data-column-id='employee_salary'  width='200px'>Services</th>
					<th data-column-id='employee_salary'  width='200px'></th>
					<th data-column-id='employee_salary'  width='200px'></th>
					
				</tr>
			</thead>
		  <?php
		  
	  include "connect_to_mysqli.php";
	
		   
			 $sql = "SELECT * from staff ";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
$key=$row['id']; 
 $sqs = "SELECT * from cater where id='$key' ";
		  $sqls = mysqli_query($con,$sqs);
while ($rod = mysqli_fetch_array($sqls)) {
$keen=$rod['name']; }
echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td><td width='200px'>" . $row['email'] ."</td><td width='200px'>" . $row['phone'] . "</td><td width='200px'>" . $row['pass'] . "</td><td width='200px'>" . $row['section'] . "</td><td width='200px'>" . $row['status'] . "</td><td width='200px'>" . $keen. "</td>
             <td><form action='straf.php' method='post'>
			<input type='text' name='ordid' value='" . $row['email'] . "' required hidden>  
                <input type='submit' name='submit' value='Edit' class='submitn'  ></form></td>
				
				
				
				
				<td><a href='#delete" . $row['id'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['id'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Staff?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this staff(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['email'] . "' required hidden />  
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
	  </table></div>
	  </p>
      

      </div>
    </section><!-- End About Section -->

   
  </main><!-- End #main -->

  <?php include"footer.php" ?>