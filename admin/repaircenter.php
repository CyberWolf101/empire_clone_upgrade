<?php include "header.php";

/// Delete
// if(isset($_GET['categoryid'])){
//     $service_delete = $_GET['categoryid'];
//     $insert = mysqli_query($con,"UPDATE repair_center SET repairhistory='yes' where s='$id'") or die ('Could not connect: ' .mysqli_error($con)); 
//     echo "<script>alert('Repair Request Closed Successfully!'); window.location.href = 'repaircenter.php';</script>";
//     exit(); // Make sure to exit the script after the alert
// }
if (isset($_GET['categoryid'])) {
  $service_delete = $_GET['categoryid'];

  $update = mysqli_query($con, "UPDATE repair_center SET repairhistory='yes' WHERE s='$service_delete'")
    or die('Could not connect: ' . mysqli_error($con));


  // Redirect to reviews.php with a success message
  header("Location: repaircenter.php");
  exit();
}


?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Repair Center</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Repair Center</li>
  </ol>
</div>


<!-- Row -->
<div class="row">

  <!-- Datatables -->
  <div class="col-lg-12" style="margin-top:2%;">
    <div class="card mb-4">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Repairs</h6>
      </div>
      <div class="table-responsive p-3">
        <table class="table align-items-center table-flush text-primary" id="dataTable" style="font-size:15px;">
          <thead class="thead-light">
            <tr>
              <th>Repair ID</th>
              <th>Item</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Status</th>
              <th>View</th>
              <th>Close</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Repair ID</th>
              <th>Item</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Status</th>
              <th>View</th>
              <th>Close</th>
            </tr>
          </tfoot>
          <tbody>
            <?php
            $sql = "SELECT * from repair_center WHERE repairhistory ='no' ORDER BY s DESC";
            $sql2 = mysqli_query($con, $sql);
            $i = 1;
            while ($row = mysqli_fetch_array($sql2)) {
              $ids = $row['s'];

              $status = $row['status'];

              //color
              if ($status == "pending") {
                $bg = "badge-warning";
                $status = "booking";
              } else if ($status == "processing") {
                $bg = "badge-primary";
              } else if ($status == "cancelled") {
                $bg = "badge-danger";
              } else if ($status == "processed" || $status == "completed") {
                $bg = "badge-success";
              } else {
                $bg = "badge-info";
              }

              echo "<tr>
<td>" . $row['repair_id'] . "</td>
<td>" . $row['item'] . "</td>
<td>" . $row['name'] . "</td>
<td>" . $row['date'] . "</td>
<td><span class='badge $bg'> " . $row['status'] . "</span></td>
<td><a href='viewrepair.php?repairid=" . $row['repair_id'] . "'  class='btn btn-sm btn-primary'> View</a></td> 
<td><form action='' method='get' onsubmit='return confirm(\"Are you sure you want to delete this repair request (" . $row['item'] . ")?\");'>
<input type='text' name='categoryid' value='" . $row['s'] . "' required hidden>  
<input type='submit' name='delete' value='Delete Request' class='btn btn-sm btn-danger' ></form></td>	
</tr>";






            }
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>


  <?php include "footer.php"; ?>