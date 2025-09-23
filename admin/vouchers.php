<?php include "header.php"; ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">E-GIFTCARD SPA PACKAGE VOUCHERS</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Vouchers</li>
            </ol>
          </div>
          
         

 <!-- Invoice Example -->
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All valid and inavlid gift spa package vouchers</h6>
                </div>
                
                <div class="table-responsive">
                  <table class="table align-items-center table-flush" style="font-size:13px; color:black;" id="dataTable">
                    <thead class="thead-light">
                      <tr>
                        <th>SN</th>
                        <th>Voucher ID</th>
                        <th>Owner</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                        
<?php
	   
$sql = "SELECT  * FROM voucher_orders where pay_status='paid' ORDER BY s DESC";
$sql2 = mysqli_query($con,$sql);
$i=1;
while ($row = mysqli_fetch_array($sql2)) {

$status=$row['status'];

//color
if ($status=="invalid"){
  $bg="badge-warning";
  $status="booking";}
else if ($status=="valid" || $status=="completed"){
  $bg="badge-success";
}


echo" 
                     <tr>
                        <td> ".$i++." </td>
                        <td>".$row['orderid']." </td>
                        <td>".$row['ownername'] ." </td>
                        <td>".$row['owneremail'] ." </td>
                        <td>".$row['ownerphone'] ." </td>
                        <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
                       <td><a href='viewvoucher.php?order=".$row['s']."' class='btn btn-sm btn-primary'> Details</a></td>	
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