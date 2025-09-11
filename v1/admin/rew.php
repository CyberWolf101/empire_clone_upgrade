<?php include "header.php" ?>


  <main id="main">

   
  <div class="section-title">
          <h2>All Review</h2>
          <p>Delete Option</p>
        </div>
		<?php 
		     include "connect_to_mysqli.php";
			 if (isset($_GET['submin']))
 {
		    $use =  $_GET['ordid'];
			
	  	  
		  			 $del = mysqli_query($con,"DELETE from rew where s='$use'") or die ('Could not connect: ' .mysqli_error($con)); 
					 
				echo"<p style='color:blue;'>Review Successfully Deleted from Database!</staff>";	 
				}
					 ?>

<p><center><form action="" method="post"><input type="text" name="kayd" /><input type="submit" value="Search" /></form></center></p>
<p>
	   <div class="overflow-auto"><table class='table table-condensed table-hover table-striped' width='90%' border="0" cellspacing='10' data-toggle='bootgrid'>

<thead>
				<tr  bgcolor="#CCCCCC">
					
					
					<th data-column-id='employee_name'  width='200px'>Review</th>
					<th data-column-id='employee_salary'  width='200px'></th>
				
				</tr>
			</thead>
		  <?php
		  
	 	include "connect_to_mysqli.php";
	  $sa =  $_POST['kayd'];
	 
	 
	    if((!$sa))
	  {
		
	
		   
			 $sql = "SELECT * from rew ORDER BY vew ASC";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {

echo "










<tr bgcolor='#fff'><td width='200px' >" . $row['vew'] . "</td>
			<td><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Review?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this review(" . $row['vew'] . ")</p>
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
		   
					$sql = "SELECT all* from rew WHERE vew LIKE '%".$sa."%' ORDER By name DESC LIMIT 1000";
		  $sql2 = mysqli_query($con,$sql);
		
while ($row = mysqli_fetch_array($sql2)) {
echo "









<tr bgcolor='#fff'><td width='200px' >" . $row['vew'] . "</td>
			<td><a href='#delete" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Delete</button></a>
			
		
	<div class='modal fade' id='delete" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Delete Review?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to delete this review(" . $row['vew'] . ")</p>
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