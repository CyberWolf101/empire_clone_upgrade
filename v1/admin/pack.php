<?php include "header.php" ?>
<?php include "member.php"; ?> 

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Add Membership Package</h2>
        </div>
 <div style="color:red;" >
		<?php global $statusMsg; echo "$statuMsg";?><br><?php global $suc; echo "$suc"; ?><?php global $chk; echo "$chk"; ?><?php global $nom; echo "$nom"; ?></div>
         <form  method="post" enctype="multipart/form-data" >
      <p>  <input type="text" class="form-control" placeholder="*Name" name="far" required></p>
	   <p><input type="number" class="form-control"   placeholder="*Price" name="ph" required></p>
      <p><input type="submit" value="Register" name="submit" class="submitn" style="text-align:center;"/></p></form>
      </div>
    </section><!-- End About Section -->

  
  
  
  
  
  
  
  
  
  
  
  <div class="section-title">
          <h2>All Packages</h2>
          <p>View and Edit prices</p>
        </div>
		<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin']))
 {
		    $use =  $_GET['ordid'];
			
	  	  
		  			 $del = mysqli_query($con,"DELETE from see where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
				echo"<p style='color:blue;'>Package Successfully Deleted from Database!</staff>";	 
				}
					 ?>
<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_POST['submin']))
 {
		    $use =  $_POST['ordid'];
			$per =  $_POST['ph'];
			
	  	 
		  			  $insert = mysqli_query($con,"UPDATE see SET price= '$per' where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					  
					
					 
					
				echo"<p style='color:blue;'>Package Successfully Updated!</staff>";	 
				}
					 ?>
<p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					
					<th data-column-id='employee_name'  width='200px'>Name</th>
					<th data-column-id='employee_salary'  width='200px'>Price</th>
					<th data-column-id='employee_salary'  width='200px'></th>
					<th data-column-id='employee_salary'  width='200px'></th>
				</tr>
			</thead>
		  <?php
		  
	 	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		
	
		   
			 $sql = "SELECT * from see ORDER BY name ASC";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['s'] . "' required hidden>  
			<input type='text' name='ph' value='" . $row['price'] . "' required> 
                </td>

				
				
			<td> 
                <input type='submit' name='submin' value='Update' class='submitn' ></form></td>	
			<td><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Service?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this service(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
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
		   
					$sql = "SELECT all* from see WHERE name LIKE '%".$sa."%' ORDER By name DESC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['name'] . "</td>
             <td><form action='' method='post'>
			<input type='text' name='ordid' value='" . $row['s'] . "' required hidden>  
			<input type='text' name='ph' value='" . $row['price'] . "' required> 
                </td>

				
				
			<td> 
                <input type='submit' name='submin' value='Update' class='submitn' ></form></td>	
			<td><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Service?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this service(" . $row['name'] . ")</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
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
	  </table></div>
	  </p>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
   
  </main><!-- End #main -->

  <?php include"footer.php" ?>