<?php include "header.php"; ?>

             <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800">Members</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Membership</li>
            </ol>
          </div>
          
         

 <!-- Invoice Example -->
            <div class="col-xl-12 col-lg-12 mb-4">
              <div class="card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All valid and inavlid memberships</h6>
                </div>
                
                <div class="table-responsive">
                  <table class="table align-items-center table-flush" style="font-size:13px; color:black;">
                    <thead class="thead-light">
                      <tr>
                        <th>SN</th>
                        <th>Member ID</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                        
<?php
	   
$sql = "SELECT  * FROM members where paystatus='paid' ORDER BY s DESC";
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
                        <td>".$row['cardno']." </td>
                        <td>".$row['type'] ." </td>
                        <td>".$row['name'] ." </td>
                        <td>".$row['email'] ." </td>
                        <td>".$row['phone'] ." </td>
                        <td>".$row['end_date'] ." </td>
                        <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
                       <td><a href='viewmember.php?order=".$row['s']."' class='btn btn-sm btn-primary'> Details</a></td>	
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