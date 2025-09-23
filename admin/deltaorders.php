<?php include "header.php"; ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h6 mb-0 text-gray-800">Delta Kitchen Orders</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Orishirishi</li>
            </ol>
          </div>
          
         

 <!-- Invoice Example -->
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold h5 text-primary">Delta Kitchen Orders</h6>
                </div>
                
                <div class="table-responsive">
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                      <tr>
                        <th>SN</th>
                        <th>Booking ID</th>
                        <th>Preorder</th>
                        <th>Customer</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>View</th>
                        <th>Reciept</th>
                      </tr>
                    </thead>
                    <tbody  style='font-size:16px !important; color:black;'>
                        
<?php
$pr="";	   
$sql = "SELECT  * FROM saloon_orders where section='kitchen' AND pay_status='paid' AND type='online' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

$status=$row['status'];
$pr=$row['preorder'];
$prdate=$row['preorder_date'];

//color
if ($status=="no"){
  $bg="badge-warning";
  $status="booking";
}
else if ($status=="processing"){
  $bg="badge-primary";
}
else if ($status=="cancelled"){
  $bg="badge-danger";
}
else if ($status=="processed" || $status=="completed"){
  $bg="badge-success";
}


if($pr=="1"){
$prtext="<span class='badge badge-primary' style='text-transform:capitalize;'>Preorder</span><br>$prdate";
}else{
$prtext="<span class='badge badge-warning' style='text-transform:capitalize;'>Instant</span>";
}
echo" 
               <tr>
                        <td> ".$i++." </td>
                        <td>".$row['id']." </td>
                         <td>$prtext</td>
                        <td>".$row['name'] ." </td>
                        <td>&#8358; ".$row['total_amount']." </td>
                        <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
                        <td><a href='viewkitchen.php?order=".$row['id']."' class='btn btn-sm btn-primary'> View Order</a></td>
                        <td><a href='kitchenreciept.php?order=".$row['id']."' style='font-size:13px !important;' class='btn btn-sm btn-primary'> Print Receipt </a></td>	
                      </tr>";
				
}

?>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                
                    </tbody>
                  </table>
                </div>
                <div class="card-footer"></div>
              </div>
              </div>
















<?php include "footer.php"; ?>