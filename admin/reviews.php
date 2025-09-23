<?php include "header.php"; 


/// Delete
if(isset($_GET['categoryid'])){
    $service_delete = $_GET['categoryid'];
    $del = mysqli_query($con,"DELETE from reviews where s='$service_delete'") or die ('Could not connect: ' .mysqli_error($con)); 
   if ($del) {
        // Redirect to reviews.php with a success message
        header("Location: reviews.php?status=success");
        exit();
    } else {
        // Redirect with an error message
        header("Location: reviews.php?status=error");
        exit();
    }
}



					 ?>


             <div class="d-sm-flex align-items-center justify-content-between mb-4">
             <h1 class="h3 mb-0 text-gray-800">All Reviews</h1>
             <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Reviews</li>
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
                  <table class="table align-items-center table-flush text-primary" id="dataTable" style="font-size:15px;">
                    <thead class="thead-light">
                      <tr>
                        <th>Reviews</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                         <th>Reviews</th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                        <?php
$disabled ='disabled';
 if ($status=="superadmin"){ $disabled =''; }
$sql = "SELECT * from reviews ORDER BY s ASC";
$sql2 = mysqli_query($con,$sql);
while ($row = mysqli_fetch_array($sql2)) {
// $sub= $row["sub_category"]; 

echo "
                         <tr>
                         <td>" . $row['view'] . "</td>
                        <td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this review (".$row['view'].")?\");'>
		                <input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
                        <input type='submit' name='delete' value='Delete Review' class='btn btn-sm btn-danger' $disabled ></form></td>	
                        </tr>";

      
				
				
				
		
}
?> 
                      
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          
          
          
<?php include "footer.php"; ?>