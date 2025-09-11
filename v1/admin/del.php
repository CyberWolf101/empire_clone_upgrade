 <?php include"header.php"; ?>
 <?php

    $sql = "SELECT * from repair_center where status != '' && repairhistory ='no' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
  
  ?>
   
          
           
    
           <br /><br />  
           <div class="container">  
                <h3 align="center">Datatables Jquery Plugin with Php MySql and Bootstrap</h3>  
                <br />  
                <div class="table-responsive">  
                     <table id="employee_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                                  <td>No</td>
					<td>Item</td>
					<td>Client</td>
					<td>Date</td>
					<td>Status</td>
					<td>View Details</td>
					<td>Repair History</td>
                               </tr>  
                          </thead>  
                          <?php  
                       
while ($row = mysqli_fetch_array($sql2)) {
                               echo "
                              	<tr  bgcolor='#fff'>
<td>" . $row['repair_id'] . "</td>
<td>" . $row['item'] . "</td>
<td>" . $row['name'] . "</td>
<td>" . $row['date'] . "</td>
<td>" . $row['status'] . "</td>
<td><form action='repair_details.php' method='post'>
<input type='text' name='repair_id' value='" . $row['s'] . "' required hidden>  
<input type='submit' name='repair' value='View Details' class='submitn' ></form></td>	
<td><a href='#close" . $row['s'] . "' data-toggle='modal'  ><button class='submitn'>Close Request</button></a>
			
		
	<div class='modal fade' id='close" . $row['s'] . "' role='dialog'>
    <div class='vertical-alignment-helper'>
    <div class='modal-dialog vertical-align-center'>
    
        <div class='modal-content'>
        <div class='modal-header'>
		  <h4 class='modal-title w-100 text-center' style='color:black;'>Close Repair Request?</h4>
        </div>
        
        <div class='modal-body w-100 text-center' style='color:#FFFFFF;'>
        <p style='color:black; font-weight:600;'>Are you sure you want to close this request</p>
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
</tr>

                               ";  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
 
 <script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  
 </script>  
 
 
  <?php include"footer.php" ?>