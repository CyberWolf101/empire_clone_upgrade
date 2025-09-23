<?php 
$bot = "SELECT * from appointments where id='$saloon' ";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) > 0){
?>
          
                  

          
            <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Services Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Service</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Staff</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from appointments where id='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['servicename']."</td>
                         <td>".$row['start_time']." - ".$row['end_time']." </td>	
                        <td>&#8358;".$row['price']."</td>	
                        <td>".$row['staffname']."</td>	
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this service (".$row['servicename'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Service' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
                  <center><p><a href="salooncheckout.php"  class='btn btn-sm btn-warning'>Proceed to checkout</a></p></center>
                </div>
              </div>
            </div>
          
          
   <?php } ?>       
          
