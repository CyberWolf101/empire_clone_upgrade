<?php include "header.php"; ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Giftcard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Giftcard</li>
            </ol>
          </div>
          
         

 <!-- Invoice Example -->
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">GiftCards</h6>
                </div>
                
                <div class="table-responsive">
                  <table class="table align-items-center table-flush" id="dataTable" style="font-size:14px; color:black;">
                    <thead class="thead-light">
                      <tr>
                        <th>SN</th>
                        <th>CARD ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Amount Bought</th>
                        <th>Amount Left</th>
                        <th>Date Bought</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                        
<?php
	   
$sql = "SELECT  * FROM giftcard where status='paid' ORDER BY s DESC";
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
                        <td>".$row['giftcardno']."</td>
                        <td>".$row['ownername'] ." </td>
                        <td>".$row['owneremail'] ." </td>
                        <td>&#8358;".$row['amount'] ." </td>
                        <td>&#8358;".$row['amount_left'] ." </td>
                        <td>".$row['date'] ." </td>
                        <td><a href='view-giftcard-history.php?giftcard=".$row['giftcardno']."' class='btn btn-sm btn-primary'>History</td>
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