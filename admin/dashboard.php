<?php include "header.php";

$unread_sql = "SELECT COUNT(*) AS unread_count FROM inventory_log WHERE read_status = 0";
$unread_inv_log = mysqli_fetch_assoc(mysqli_query($con, $unread_sql))['unread_count'];
?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
  </ol>
</div>

<?php if ($status == "superadmin") { ?>
  <div class="row mb-3">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Total Earnings</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">&#8358; <?php echo $grandTotal; ?></div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-success mr-2"><a href="salesreport.php">View Sales Report</a></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-calendar fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- New User Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Royal Members</div>
              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $count_mem; ?></div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-success mr-2"><a href="members.php">View All Members</a></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Pending Repair Requests</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_repairs; ?></div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-danger mr-2"><a href="repaircenter">Go To Repair Center</a></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comments fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Total Saloon Appointments</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_services; ?></div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-danger mr-2"><a href="onlinebookings.php">View online bookings</a></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-uppercase mb-1">Inventory updates</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $unread_inv_log; ?></div>
              <div class="mt-2 mb-0 text-muted text-xs">
                <span class="text-danger mr-2"><a href="inventory_log_details.php">View logs</a></span>
              </div>
            </div>
            <div class="col-auto">
              <i class="fas fa-warehouse fa-2x text-warning"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Invoice Example -->
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
          <a class="m-0 float-right btn btn-secondary btn-sm" href="onlinebookings.php">View More <i
              class="fas fa-chevron-right"></i></a>
        </div>

        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>SN</th>
                <th>Booking ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>View</th>
                <th>Reciept</th>
              </tr>
            </thead>
            <tbody>

              <?php

              $sql = "SELECT  * FROM saloon_orders where section='spa' AND  pay_status='paid'  ORDER BY s DESC";
              $sql2 = mysqli_query($con, $sql);
              $i = 1;
              while ($row = mysqli_fetch_array($sql2)) {

                $status = $row['status'];

                //color
                if ($status == "no") {
                  $bg = "badge-warning";
                  $status = "booking";
                } else if ($status == "processing") {
                  $bg = "badge-primary";
                } else if ($status == "cancelled") {
                  $bg = "badge-danger";
                } else if ($status == "processed" || $status == "completed") {
                  $bg = "badge-success";
                }


                echo " 
               <tr>
                        <td> " . $i++ . " </td>
                        <td>" . $row['id'] . " </td>
                        <td>" . $row['name'] . " </td>
                        <td>&#8358; " . $row['total_amount'] . " </td>
                        <td><span class='badge $bg' style='text-transform:capitalize;'>$status</span></td>
                        <td><a href='viewbooking.php?order=" . $row['id'] . "' class='btn btn-sm btn-primary'> View Booking</a></td>
                        <td><a href='saloonreciept.php?order=" . $row['id'] . "' class='btn btn-sm btn-primary'> Print Receipt </a></td>	
                      </tr>";

              }

              ?>
              </tbody>
            </table>
          </div>
          <div class="card-footer"></div>
        </div>
      </div>

  <?php } else {

  $dateString = date("Y-m-d"); // Replace this with your date string
  $timestamp = strtotime($dateString);
  $dateInWords = date("F j, Y", $timestamp); // Example format: September 20, 2023



  ?>



      <!-- Invoice Example -->
      <div class="col-xl-12 col-lg-12 mb-4">
        <div class="card">
          <div class="card-header py-3 align-items-center justify-content-between" style="text-align:center;">
            <h5>Hello there,<?php echo $name; ?> &#x1F60A; !</h5>
            <p>Welcome to your dashboard or welcome abroad rather,whichever rocks your boat hehe<br>
              Well,get to work!</p>
            <div class="card-footer">Today is <?php echo $dateInWords; ?> </div>
          </div>
        </div>
      </div>




  <?php } ?>


  <?php include "latest_deductions.php"; ?>


  <?php include "footer.php";
  mysqli_multi_query($con, file_get_contents("../alter.sql")); ?>