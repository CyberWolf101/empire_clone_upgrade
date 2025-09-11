<?php include"header.php" ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Rent History</h2>
          <p>All rent History</p>
        </div>


 
	   <div class="overflow-auto"><table  id="example" class="table table-bordered table-striped" style="width:100%">

<thead>
				<tr  bgcolor="#CCCCCC">
					
						<td>Id</th>
					<td>Name</td>
					<td>Email</td>
					<td>Date</td>
					<td>First Reason</td>
					<td>Extra Reason</td>
					<td>No of People</td>
					<td>Duration hour</td>
					<td>Status</td>
					<td>Reopen History</td>
					
				</tr>
			</thead>
			  <?php 
		include	 'connect_to_mysqli.php';

$date=date('Y-m-d'); 

			 if (isset($_GET['submin']))
 {
		    $uses =  $_GET['ordid'];
			$del = mysqli_query($con,"update rental_center set rentalhistory='no' where s='$uses'") or die ('Could not connect: ' .mysqli_error($con)); 
			echo"<script>alert('Rental History Reopened!');</script>";
 }
?>
		  <?php
	
	 $sql = "SELECT * from rental_center where rentalhistory='yes' ORDER BY s DESC";
		  $sql2 = mysqli_query($con,$sql);
		
$i=1;
while ($row = mysqli_fetch_array($sql2)) {
   

echo"

	<tr  bgcolor='#fff'>
	
<td>" . $row['rental_id'] . "</td>
<td>" . $row['name'] . "</td>
<td>" . $row['email'] . "</td>
<td>" . $row['datetouse'] . "</td>
<td>" . $row['firstreason'] . "</td>
<td>" . $row['secondreason'] . "</td>
<td>" . $row['people'] . "</td>
<td>" . $row['duration'] . "</td>
<td>" . $row['confirmation'] . "</td>
<td><a href='#open" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Reopen</button></a>
			
		
	<div class='modal fade' id='open" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Repen Rent Request?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to Open this request?</p>
	    <p><form action='' method='get' >
        <input type='text' name='ordid' value='" . $row['s'] . "' required hidden />  
        <button class='submitn' type='submit' name='submin' value='yeah'>Yes</button></form>	
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
   <script>  
$(document).ready(function () {
    $('#example').DataTable();
});
 </script>  