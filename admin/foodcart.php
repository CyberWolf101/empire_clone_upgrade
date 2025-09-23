<?php 
$bot = "SELECT all* from refreshments where orderid='$saloon' ";
$bot2 = mysqli_query($con,$bot);
if (mysqli_affected_rows($con) > 0){
?>
          
                  

          
            <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Refreshment Cart</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary">
                    <thead class="thead-light">
                      <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from refreshments where orderid='$saloon' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

echo "
                         <tr>
                         <td>".$row['item']."</td>
                        <td>&#8358;".$row['unitprice']."</td>	
                        <td>".$row['quantity']."</td>
                        <td>&#8358;".$row['totalprice']." </td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this item (".$row['item'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Item' class='btn btn-sm btn-danger' ></form></td>	
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
          
