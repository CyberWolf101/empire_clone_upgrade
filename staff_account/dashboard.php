<?php include "header.php";

/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $insert = mysqli_query($con,"UPDATE appointments SET status='completed' where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
    echo "<script>alert('Service Completed successfully!'); window.location.href = 'dashboard.php';</script>";
    exit(); // Make sure to exit the script after the alert
}



?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Your Today's Appointment</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Appointments</li>
            </ol>
          </div>
          
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
                        <th>Customer</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
$sql = "SELECT * from appointments where staff='$id'  AND status!='processing' AND date='$date' ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {

$status=$row['status'];
$order=$row['id'];

$sqls = "SELECT * from saloon_orders where id='$order' ORDER BY s ASC";
$sql2s = mysqli_query($con,$sqls);
while ($rows = mysqli_fetch_array($sql2s)) {
$cname=$rows['name'];
}


$show="<span class='badge badge-success' style='text-transform:capitalize;'>$status</span>";
if($status=="processed"){
$show="
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Mark As Completed' class='btn btn-sm btn-primary' >";
                        
}
                        
                        
                        
                         echo "
                         <tr>
                         <td>".$row['servicename']."</td>
                         <td>".$row['start_time']." - ".$row['end_time']." </td>	
                        <td>&#8358;".$row['price']."</td>	
                        <td>".$cname."</td>	
                         <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to mark this service as completed (".$row['servicename'].")?\");'>$show</form></td>		
                        </tr>";

        
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
          
<?php include "footer.php"; ?>