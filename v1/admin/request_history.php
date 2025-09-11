<?php include"header.php"; ?>

   
 <?php

    $sql = "SELECT * from repair_center where status != '' && repairhistory ='yes' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
  
  ?>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="section-title">
          <h2>Repair History</h2>
          <p>All repair History</p>
        </div>

<p><div class="overflow-auto"><table id="example" class="table table-bordered table-striped" style="width:100%">

<thead>
				   <tr bgcolor="#CCCCCC">
				     <td>No</td>
					<td>Item</td>
					<td>Client</td>
					<td>Date</td>
					<td>Status</td>
						<td>Close History</td>
					
				</tr>
			</thead>
			 <?php 
		include	 'connect_to_mysqli.php';

$date=date('Y-m-d'); 

			 if (isset($_GET['submin']))
 {
		    $uses =  $_GET['ordid'];
			$del = mysqli_query($con,"update repair_center set repairhistory='no' where s='$uses'") or die ('Could not connect: ' .mysqli_error($con)); 
			echo"<script>alert('Repair History Reopened!');</script>";
 }
?>
		  <?php

	      
while ($row = mysqli_fetch_array($sql2)) {
   

echo"

	<tr bgcolor='#fff'>
	
<td>" . $row['repair_id'] . "</td>
<td>" . $row['item'] . "</td>
<td>" . $row['name'] . "</td>
<td>" . $row['date'] . "</td>
<td>" . $row['status'] . "</td>
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