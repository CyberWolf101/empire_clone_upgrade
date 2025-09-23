<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from staff where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Staff Deleted successfully!'); window.location.href = 'staff.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

if(isset($_GET['walletid'])){
    $staffwallet = $_GET['walletid'];
    $insert = mysqli_query($con,"UPDATE staff SET wallet='0' where s='$staffwallet'") or die ('Could not connect: ' .mysqli_error($con));
    echo "<script>alert('Amount in wallet paid out successfully!'); window.location.href = 'staff.php';</script>";
    exit(); // Make sure to exit the script after the alert
}

?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Saloon Staff</h1> 
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Staff</li>
            </ol>
          </div>
          
           <!-- Row -->
          <div class="row">
          
          <!-- Datatables -->
            <div class="col-lg-12" style="margin-top:2%;">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary"></h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush text-primary" id="dataTable">
                    <thead class="thead-light">
                      <tr>
                        <th>Name</th>
                        <th>Wallet</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Service</th>
                        <th>Last Login</th>
                        <th></th>
                         <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                         <th>Name</th>
                         <th>Wallet</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Password</th>
                        <th>Service</th>
                        <th>Last Login</th>
                        <th></th>
                         <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$sql = "SELECT * from staff ORDER BY name ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
 $category=$row['section'];


            $sqls = "SELECT * from category where id = '$category'  ";
			$sql2s = mysqli_query($con,$sqls);
		    while($rows = mysqli_fetch_array($sql2s))
			{$categoryname = $rows["name"];}
					  
					  
echo "
                         <tr>
                         <td>".$row['name']."</td>
                         <td><b>&#8358;".$row['wallet']."</b>";
                         
                         if($row['wallet'] > 0 ){echo "
                        <br><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to pay out the amount of (&#8358;".$row['wallet'].")  to staff (".$row['name'].") now ?\");'>
		                <input type='text' name='walletid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Mark As Paid' class='btn btn-sm btn-danger' ></form>"; }
                        
                        
                        echo "</td>
                         <td>".$row['email']."</td>
                         <td>".$row['phone']."</td>
                         <td>".$row['password']."</td>
                         <td>".$categoryname."</td>
                         <td>".$row['logdate']."</td>
                        <td><form action='editsaloonstaff.php' method='get'>
		                <input type='text' name='category' value='" . $row['id'] . "' required hidden>  
                        <input type='submit' name='edit' value='Edit Details' class='btn btn-sm btn-primary' ></form></td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this staff (".$row['name'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Staff' class='btn btn-sm btn-danger' ></form></td>	
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
          
<?php include "footer.php"; ?>